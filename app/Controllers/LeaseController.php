<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Auth;
use App\Core\Audit;
use PDO;

class LeaseController extends Controller {
    public function index() {
        $this->requireLogin();
        $db = Database::getInstance();
        $query = "SELECT l.*, h.name as house_name, t.name as tenant_name 
                 FROM leases l 
                 JOIN houses h ON l.house_id = h.id 
                 JOIN tenants t ON l.tenant_id = t.id";
        $params = [];
        if (Auth::role() === 'normal') {
            $query .= " WHERE l.created_by = ?";
            $params[] = Auth::user()['id'];
        }
        $query .= " ORDER BY l.created_at DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $leases = $stmt->fetchAll();
        $this->render('leases/index', ['leases' => $leases]);
    }

    public function create() {
        $this->requireLogin();
        $db = Database::getInstance();
        
        $housesQuery = "SELECT * FROM houses WHERE status = 'available'";
        $tenantsQuery = "SELECT * FROM tenants WHERE status = 'active'";
        $params = [];
        $this->applyDataIsolation($housesQuery, $params);
        $this->applyDataIsolation($tenantsQuery, $params);

        $houses = $db->prepare($housesQuery);
        $houses->execute($params);
        $tenants = $db->prepare($tenantsQuery);
        $tenants->execute($params);

        $this->render('leases/create', [
            'houses' => $houses->fetchAll(),
            'tenants' => $tenants->fetchAll()
        ]);
    }

    public function store() {
        $this->requireLogin();
        $db = Database::getInstance();
        $db->beginTransaction();
        try {
            $stmt = $db->prepare("INSERT INTO leases (house_id, tenant_id, start_date, end_date, rent_amount, electricity_rate, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['house_id'],
                $_POST['tenant_id'],
                $_POST['start_date'],
                $_POST['end_date'],
                $_POST['rent_amount'],
                $_POST['electricity_rate'],
                Auth::user()['id']
            ]);

            // Update house status to occupied
            $stmt = $db->prepare("UPDATE houses SET status = 'occupied' WHERE id = ?");
            $stmt->execute([$_POST['house_id']]);

            $db->commit();
            Audit::log('Create Lease', "House ID: " . $_POST['house_id']);
            $this->redirect('/leases');
        } catch (\Exception $e) {
            $db->rollBack();
            die("Error creating lease: " . $e->getMessage());
        }
    }

    public function edit() {
        $this->requireLogin();
        $id = $_GET['id'];
        $db = Database::getInstance();
        
        $query = "SELECT * FROM leases WHERE id = ?";
        $params = [$id];
        $this->applyDataIsolation($query, $params);
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $lease = $stmt->fetch();

        if (!$lease) die("Unauthorized or Not Found");

        $houses = $db->query("SELECT * FROM houses WHERE status = 'available' OR id = " . $lease['house_id'])->fetchAll();
        $tenants = $db->query("SELECT * FROM tenants WHERE status = 'active' OR id = " . $lease['tenant_id'])->fetchAll();
        
        $this->render('leases/edit', [
            'lease' => $lease,
            'houses' => $houses,
            'tenants' => $tenants
        ]);
    }

    public function update() {
        $this->requireLogin();
        $id = $_POST['id'];
        $db = Database::getInstance();
        
        if (Auth::role() === 'normal') {
            $stmt = $db->prepare("SELECT id FROM leases WHERE id = ? AND created_by = ?");
            $stmt->execute([$id, Auth::user()['id']]);
            if (!$stmt->fetch()) die("Unauthorized");
        }

        $db->beginTransaction();
        try {
            $stmt = $db->prepare("SELECT house_id FROM leases WHERE id = ?");
            $stmt->execute([$id]);
            $oldHouseId = $stmt->fetchColumn();

            $stmt = $db->prepare("UPDATE leases SET house_id = ?, tenant_id = ?, start_date = ?, end_date = ?, rent_amount = ?, electricity_rate = ?, status = ? WHERE id = ?");
            $stmt->execute([
                $_POST['house_id'],
                $_POST['tenant_id'],
                $_POST['start_date'],
                $_POST['end_date'],
                $_POST['rent_amount'],
                $_POST['electricity_rate'],
                $_POST['status'],
                $id
            ]);

            if ($oldHouseId != $_POST['house_id']) {
                $db->prepare("UPDATE houses SET status = 'available' WHERE id = ?")->execute([$oldHouseId]);
                $db->prepare("UPDATE houses SET status = 'occupied' WHERE id = ?")->execute([$_POST['house_id']]);
            }

            if ($_POST['status'] === 'inactive') {
                $db->prepare("UPDATE houses SET status = 'available' WHERE id = ?")->execute([$_POST['house_id']]);
            } else if ($_POST['status'] === 'active') {
                $db->prepare("UPDATE houses SET status = 'occupied' WHERE id = ?")->execute([$_POST['house_id']]);
            }

            $db->commit();
            Audit::log('Update Lease', "ID: $id");
            $this->redirect('/leases');
        } catch (\Exception $e) {
            $db->rollBack();
            die("Error updating lease: " . $e->getMessage());
        }
    }

    public function delete() {
        $this->requireLogin();
        $id = $_GET['id'];
        $db = Database::getInstance();
        
        if (Auth::role() === 'normal') {
            $stmt = $db->prepare("SELECT id FROM leases WHERE id = ? AND created_by = ?");
            $stmt->execute([$id, Auth::user()['id']]);
            if (!$stmt->fetch()) die("Unauthorized");
        }

        $db->beginTransaction();
        try {
            $stmt = $db->prepare("SELECT house_id FROM leases WHERE id = ?");
            $stmt->execute([$id]);
            $houseId = $stmt->fetchColumn();

            if ($houseId) {
                $db->prepare("UPDATE houses SET status = 'available' WHERE id = ?")->execute([$houseId]);
            }

            $stmt = $db->prepare("DELETE FROM leases WHERE id = ?");
            $stmt->execute([$id]);

            $db->commit();
            Audit::log('Delete Lease', "ID: $id");
            $this->redirect('/leases');
        } catch (\Exception $e) {
            $db->rollBack();
            die("Error deleting lease: " . $e->getMessage());
        }
    }
}
