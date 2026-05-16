<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Auth;
use App\Core\Audit;
use PDO;

class HouseController extends Controller {
    public function index() {
        $this->requireLogin();
        $db = Database::getInstance();
        $query = "SELECT * FROM houses";
        $params = [];
        $this->applyDataIsolation($query, $params);
        $query .= " ORDER BY created_at DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $houses = $stmt->fetchAll();
        $this->render('houses/index', ['houses' => $houses]);
    }

    public function create() {
        $this->requireLogin();
        $this->render('houses/create');
    }

    public function store() {
        $this->requireLogin();
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO houses (name, address, description, rent_amount, created_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['name'],
            $_POST['address'],
            $_POST['description'],
            $_POST['rent_amount'],
            Auth::user()['id']
        ]);
        Audit::log('Create House', "Name: " . $_POST['name']);
        $this->redirect('/houses');
    }

    public function edit() {
        $this->requireLogin();
        $id = $_GET['id'];
        $db = Database::getInstance();
        
        $query = "SELECT * FROM houses WHERE id = ?";
        $params = [$id];
        $this->applyDataIsolation($query, $params);
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $house = $stmt->fetch();
        
        if (!$house) die("Unauthorized or Not Found");
        
        $this->render('houses/edit', ['house' => $house]);
    }

    public function update() {
        $this->requireLogin();
        $id = $_POST['id'];
        $db = Database::getInstance();
        
        // Verify ownership for normal users
        if (Auth::role() === 'normal') {
            $stmt = $db->prepare("SELECT id FROM houses WHERE id = ? AND created_by = ?");
            $stmt->execute([$id, Auth::user()['id']]);
            if (!$stmt->fetch()) die("Unauthorized");
        }

        $stmt = $db->prepare("UPDATE houses SET name = ?, address = ?, description = ?, rent_amount = ?, status = ? WHERE id = ?");
        $stmt->execute([
            $_POST['name'],
            $_POST['address'],
            $_POST['description'],
            $_POST['rent_amount'],
            $_POST['status'],
            $id
        ]);
        Audit::log('Update House', "ID: $id");
        $this->redirect('/houses');
    }
}
