<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Auth;

class DashboardController extends Controller {
    public function index() {
        $this->requireLogin();
        $db = Database::getInstance();
        
        $housesQuery = "SELECT COUNT(*) FROM houses";
        $tenantsQuery = "SELECT COUNT(*) FROM tenants";
        $rentQuery = "SELECT SUM(amount) FROM payments WHERE status = 'paid'";
        
        $hParams = [];
        $tParams = [];
        $rParams = [];

        $this->applyDataIsolation($housesQuery, $hParams);
        $this->applyDataIsolation($tenantsQuery, $tParams);
        
        if (Auth::role() === 'normal') {
            $rentQuery .= " AND created_by = ?";
            $rParams[] = Auth::user()['id'];
        }

        $stmt = $db->prepare($housesQuery);
        $stmt->execute($hParams);
        $housesCount = $stmt->fetchColumn();

        $stmt = $db->prepare($tenantsQuery);
        $stmt->execute($tParams);
        $tenantsCount = $stmt->fetchColumn();

        $stmt = $db->prepare($rentQuery);
        $stmt->execute($rParams);
        $totalRent = $stmt->fetchColumn() ?: 0;
        
        $this->render('dashboard', [
            'housesCount' => $housesCount,
            'tenantsCount' => $tenantsCount,
            'totalRent' => $totalRent
        ]);
    }
}
