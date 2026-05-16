<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Auth;

class AuditController extends Controller {
    public function index() {
        $this->requireRole('super_admin');
        $db = Database::getInstance();
        
        $logs = $db->query("SELECT a.*, u.name as user_name FROM audit_logs a LEFT JOIN users u ON a.user_id = u.id ORDER BY a.created_at DESC LIMIT 500")->fetchAll();
        $this->render('audit/index', ['logs' => $logs]);
    }
}
