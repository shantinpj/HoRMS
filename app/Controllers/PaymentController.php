<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Auth;
use App\Core\Audit;

class PaymentController extends Controller {
    public function index() {
        $this->requireLogin();
        $db = Database::getInstance();
        $query = "SELECT p.*, h.name as house_name, t.name as tenant_name 
                 FROM payments p 
                 JOIN leases l ON p.lease_id = l.id 
                 JOIN houses h ON l.house_id = h.id 
                 JOIN tenants t ON l.tenant_id = t.id";
        $params = [];
        if (Auth::role() === 'normal') {
            $query .= " WHERE p.created_by = ?";
            $params[] = Auth::user()['id'];
        }
        $query .= " ORDER BY p.payment_date DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $payments = $stmt->fetchAll();
        $this->render('payments/index', ['payments' => $payments]);
    }

    public function create() {
        $this->requireLogin();
        $db = Database::getInstance();
        $query = "SELECT l.id, l.electricity_rate, h.name as house_name, t.name as tenant_name 
                 FROM leases l 
                 JOIN houses h ON l.house_id = h.id 
                 JOIN tenants t ON l.tenant_id = t.id 
                 WHERE l.status = 'active'";
        $params = [];
        if (Auth::role() === 'normal') {
            $query .= " AND l.created_by = ?";
            $params[] = Auth::user()['id'];
        }
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $leases = $stmt->fetchAll();
        $this->render('payments/create', ['leases' => $leases]);
    }

    public function store() {
        $this->requireLogin();
        $db = Database::getInstance();
        
        $elec_units = floatval($_POST['electricity_units'] ?? 0);
        $elec_rate = floatval($_POST['electricity_rate'] ?? 0);
        $elec_amount = $elec_units * $elec_rate;
        
        $clean_qty = floatval($_POST['cleaning_qty'] ?? 0);
        $clean_rate = floatval($_POST['cleaning_rate'] ?? 0);
        $clean_amount = $clean_qty * $clean_rate;
        
        $water_qty = floatval($_POST['water_qty'] ?? 0);
        $water_rate = floatval($_POST['water_rate'] ?? 0);
        $water_amount = $water_qty * $water_rate;
        
        $base_rent = floatval($_POST['amount'] ?? 0);
        $total_amount = $base_rent + $elec_amount + $clean_amount + $water_amount;

        $stmt = $db->prepare("INSERT INTO payments (
            lease_id, amount, 
            electricity_units, electricity_rate, electricity_amount, 
            cleaning_qty, cleaning_rate, cleaning_amount, 
            water_qty, water_rate, water_amount, 
            payment_date, transaction_id, created_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $_POST['lease_id'],
            $total_amount,
            $elec_units,
            $elec_rate,
            $elec_amount,
            $clean_qty,
            $clean_rate,
            $clean_amount,
            $water_qty,
            $water_rate,
            $water_amount,
            $_POST['payment_date'],
            $_POST['transaction_id'],
            Auth::user()['id']
        ]);
        
        Audit::log('Record Payment', "Lease ID: " . $_POST['lease_id'] . " Total: " . $total_amount);
        $this->redirect('/payments');
    }

    public function edit() {
        $this->requireLogin();
        $id = $_GET['id'];
        $db = Database::getInstance();
        
        $query = "SELECT * FROM payments WHERE id = ?";
        $params = [$id];
        $this->applyDataIsolation($query, $params);
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $payment = $stmt->fetch();

        if (!$payment) die("Unauthorized or Not Found");

        $leasesQuery = "SELECT l.id, l.electricity_rate, h.name as house_name, t.name as tenant_name 
                        FROM leases l 
                        JOIN houses h ON l.house_id = h.id 
                        JOIN tenants t ON l.tenant_id = t.id 
                        WHERE l.status = 'active' OR l.id = ?";
        $lParams = [$payment['lease_id']];
        if (Auth::role() === 'normal') {
            $leasesQuery .= " AND l.created_by = ?";
            $lParams[] = Auth::user()['id'];
        }
        
        $stmt = $db->prepare($leasesQuery);
        $stmt->execute($lParams);
        $leases = $stmt->fetchAll();
        
        $payment['base_rent'] = $payment['amount'] - $payment['electricity_amount'] - $payment['cleaning_amount'] - $payment['water_amount'];
        
        $this->render('payments/edit', [
            'payment' => $payment,
            'leases' => $leases
        ]);
    }

    public function update() {
        $this->requireLogin();
        $id = $_POST['id'];
        $db = Database::getInstance();
        
        if (Auth::role() === 'normal') {
            $stmt = $db->prepare("SELECT id FROM payments WHERE id = ? AND created_by = ?");
            $stmt->execute([$id, Auth::user()['id']]);
            if (!$stmt->fetch()) die("Unauthorized");
        }

        $elec_units = floatval($_POST['electricity_units'] ?? 0);
        $elec_rate = floatval($_POST['electricity_rate'] ?? 0);
        $elec_amount = $elec_units * $elec_rate;
        
        $clean_qty = floatval($_POST['cleaning_qty'] ?? 0);
        $clean_rate = floatval($_POST['cleaning_rate'] ?? 0);
        $clean_amount = $clean_qty * $clean_rate;
        
        $water_qty = floatval($_POST['water_qty'] ?? 0);
        $water_rate = floatval($_POST['water_rate'] ?? 0);
        $water_amount = $water_qty * $water_rate;
        
        $base_rent = floatval($_POST['amount'] ?? 0);
        $total_amount = $base_rent + $elec_amount + $clean_amount + $water_amount;

        $stmt = $db->prepare("UPDATE payments SET 
            lease_id = ?, amount = ?, 
            electricity_units = ?, electricity_rate = ?, electricity_amount = ?, 
            cleaning_qty = ?, cleaning_rate = ?, cleaning_amount = ?, 
            water_qty = ?, water_rate = ?, water_amount = ?, 
            payment_date = ?, transaction_id = ? 
            WHERE id = ?");
        
        $stmt->execute([
            $_POST['lease_id'],
            $total_amount,
            $elec_units,
            $elec_rate,
            $elec_amount,
            $clean_qty,
            $clean_rate,
            $clean_amount,
            $water_qty,
            $water_rate,
            $water_amount,
            $_POST['payment_date'],
            $_POST['transaction_id'],
            $id
        ]);
        
        Audit::log('Update Payment', "ID: $id");
        $this->redirect('/payments');
    }

    public function delete() {
        $this->requireLogin();
        $id = $_GET['id'];
        $db = Database::getInstance();
        
        if (Auth::role() === 'normal') {
            $stmt = $db->prepare("SELECT id FROM payments WHERE id = ? AND created_by = ?");
            $stmt->execute([$id, Auth::user()['id']]);
            if (!$stmt->fetch()) die("Unauthorized");
        }

        $stmt = $db->prepare("DELETE FROM payments WHERE id = ?");
        $stmt->execute([$id]);
        Audit::log('Delete Payment', "ID: $id");
        $this->redirect('/payments');
    }
}
