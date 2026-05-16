<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Auth;
use App\Core\Audit;

class TenantController extends Controller {
    public function index() {
        $this->requireLogin();
        $db = Database::getInstance();
        $query = "SELECT * FROM tenants";
        $params = [];
        $this->applyDataIsolation($query, $params);
        $query .= " ORDER BY created_at DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $tenants = $stmt->fetchAll();
        $this->render('tenants/index', ['tenants' => $tenants]);
    }

    public function create() {
        $this->requireLogin();
        $this->render('tenants/create');
    }

    public function store() {
        $this->requireLogin();
        $db = Database::getInstance();
        
        $profile_photo = $this->uploadFile($_FILES['profile_photo'], 'profile_');
        $id_proof = $this->uploadFile($_FILES['id_proof'], 'id_');

        $stmt = $db->prepare("INSERT INTO tenants (name, email, phone, profile_photo, id_proof, created_by) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['name'],
            $_POST['email'],
            $_POST['phone'],
            $profile_photo,
            $id_proof,
            Auth::user()['id']
        ]);
        Audit::log('Create Tenant', "Name: " . $_POST['name']);
        $this->redirect('/tenants');
    }

    public function edit() {
        $this->requireLogin();
        $id = $_GET['id'];
        $db = Database::getInstance();
        
        $query = "SELECT * FROM tenants WHERE id = ?";
        $params = [$id];
        $this->applyDataIsolation($query, $params);
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $tenant = $stmt->fetch();
        
        if (!$tenant) die("Unauthorized or Not Found");
        
        $this->render('tenants/edit', ['tenant' => $tenant]);
    }

    public function update() {
        $this->requireLogin();
        $id = $_POST['id'];
        $db = Database::getInstance();
        
        if (Auth::role() === 'normal') {
            $stmt = $db->prepare("SELECT id FROM tenants WHERE id = ? AND created_by = ?");
            $stmt->execute([$id, Auth::user()['id']]);
            if (!$stmt->fetch()) die("Unauthorized");
        }

        $stmt = $db->prepare("SELECT profile_photo, id_proof FROM tenants WHERE id = ?");
        $stmt->execute([$id]);
        $current = $stmt->fetch();

        $profile_photo = $this->uploadFile($_FILES['profile_photo'], 'profile_') ?: $current['profile_photo'];
        $id_proof = $this->uploadFile($_FILES['id_proof'], 'id_') ?: $current['id_proof'];

        $stmt = $db->prepare("UPDATE tenants SET name = ?, email = ?, phone = ?, status = ?, profile_photo = ?, id_proof = ? WHERE id = ?");
        $stmt->execute([
            $_POST['name'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['status'],
            $profile_photo,
            $id_proof,
            $id
        ]);
        Audit::log('Update Tenant', "ID: $id");
        $this->redirect('/tenants');
    }

    public function show() {
        $this->requireLogin();
        $id = $_GET['id'];
        $db = Database::getInstance();
        
        $query = "SELECT * FROM tenants WHERE id = ?";
        $params = [$id];
        $this->applyDataIsolation($query, $params);
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $tenant = $stmt->fetch();
        
        if (!$tenant) die("Unauthorized or Not Found");

        // Active Lease
        $stmt = $db->prepare("SELECT l.*, h.name as house_name FROM leases l JOIN houses h ON l.house_id = h.id WHERE l.tenant_id = ? AND l.status = 'active'");
        $stmt->execute([$id]);
        $lease = $stmt->fetch();

        // Payments
        $stmt = $db->prepare("SELECT p.*, h.name as house_name FROM payments p JOIN leases l ON p.lease_id = l.id JOIN houses h ON l.house_id = h.id WHERE l.tenant_id = ? ORDER BY p.payment_date DESC");
        $stmt->execute([$id]);
        $payments = $stmt->fetchAll();

        $this->render('tenants/show', [
            'tenant' => $tenant,
            'lease' => $lease,
            'payments' => $payments
        ]);
    }

    private function uploadFile($file, $prefix) {
        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/tenants/';
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = $prefix . time() . '_' . uniqid() . '.' . $extension;
            $targetPath = $uploadDir . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                return $filename;
            }
        }
        return null;
    }
}
