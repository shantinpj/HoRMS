<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Auth;
use App\Core\Audit;

class UserController extends Controller {
    public function index() {
        $this->requireRole(['admin', 'super_admin']);
        $db = Database::getInstance();
        
        $query = "SELECT * FROM users";
        if (Auth::role() === 'admin') {
            $query .= " WHERE role = 'normal'"; // Admins can only see normal users
        }
        $query .= " ORDER BY created_at DESC";
        
        $users = $db->query($query)->fetchAll();
        $this->render('users/index', ['users' => $users]);
    }

    public function create() {
        $this->requireRole(['admin', 'super_admin']);
        $this->render('users/create');
    }

    public function store() {
        $this->requireRole(['admin', 'super_admin']);
        $db = Database::getInstance();
        
        $role = $_POST['role'];
        if (Auth::role() === 'admin' && $role !== 'normal') {
            die("Unauthorized role assignment");
        }

        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt = $db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $_POST['name'],
            $_POST['email'],
            $password,
            $role
        ]);
        
        Audit::log('Create User', "Name: " . $_POST['name'] . " Role: " . $role);
        $this->redirect('/users');
    }

    public function edit() {
        $id = $_GET['id'];
        $this->requireRole(['admin', 'super_admin']);
        $db = Database::getInstance();
        
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        if (!$user) die("User not found");
        if (Auth::role() === 'admin' && $user['role'] !== 'normal') die("Unauthorized");

        $this->render('users/edit', ['targetUser' => $user]);
    }

    public function update() {
        $id = $_POST['id'];
        $this->requireRole(['admin', 'super_admin']);
        $db = Database::getInstance();
        
        $stmt = $db->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $targetRole = $stmt->fetchColumn();

        if (Auth::role() === 'admin' && $targetRole !== 'normal') die("Unauthorized");

        $role = $_POST['role'];
        if (Auth::role() === 'admin' && $role !== 'normal') die("Unauthorized");

        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $stmt = $db->prepare("UPDATE users SET name = ?, email = ?, password = ?, role = ? WHERE id = ?");
            $stmt->execute([$_POST['name'], $_POST['email'], $password, $role, $id]);
        } else {
            $stmt = $db->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
            $stmt->execute([$_POST['name'], $_POST['email'], $role, $id]);
        }

        Audit::log('Update User', "ID: $id");
        $this->redirect('/users');
    }
}
