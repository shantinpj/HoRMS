<?php
namespace App\Core;

class Controller {
    protected function render($view, $data = []) {
        extract($data);
        $user = Auth::user();
        require_once __DIR__ . "/../Views/layout/header.php";
        require_once __DIR__ . "/../Views/$view.php";
        require_once __DIR__ . "/../Views/layout/footer.php";
    }

    protected function redirect($url) {
        header("Location: $url");
        exit;
    }

    protected function requireLogin() {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
    }

    protected function requireRole($roles) {
        $this->requireLogin();
        if (!is_array($roles)) $roles = [$roles];
        if (!in_array(Auth::role(), $roles)) {
            http_response_code(403);
            die("Unauthorized Access");
        }
    }

    protected function applyDataIsolation(&$query, &$params) {
        if (Auth::role() === 'normal') {
            $operator = strpos($query, 'WHERE') === false ? ' WHERE ' : ' AND ';
            $query .= $operator . "created_by = ?";
            $params[] = Auth::user()['id'];
        }
    }
}
