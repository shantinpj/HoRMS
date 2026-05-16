<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Auth;

class DashboardController extends Controller {
    public function index() {
        $this->requireLogin();
        $db = Database::getInstance();
        
        $params = [];
        $housesQuery = "SELECT COUNT(*) FROM houses";
        $tenantsQuery = "SELECT COUNT(*) FROM tenants";
        $rentQuery = "SELECT SUM(amount) FROM payments WHERE status = 'paid'";
        
        $this->applyDataIsolation($housesQuery, $params);
        $this->applyDataIsolation($tenantsQuery, $params);
        // For rent, we need a separate check since it already has WHERE
        if (Auth::role() === 'normal') {
            $rentQuery .= " AND created_by = " . Auth::user()['id'];
        }

        $housesCount = $db->query($housesQuery)->fetchColumn();
        $tenantsCount = $db->query($tenantsQuery)->fetchColumn();
        $totalRent = $db->query($rentQuery)->fetchColumn() ?: 0;
        
        $this->render('dashboard', [
            'housesCount' => $housesCount,
            'tenantsCount' => $tenantsCount,
            'totalRent' => $totalRent
        ]);
    }
}
