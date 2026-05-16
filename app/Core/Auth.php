<?php
namespace App\Core;

class Auth {
    public static function login($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['last_activity'] = time();
    }

    public static function logout() {
        session_unset();
        session_destroy();
    }

    public static function user() {
        if (!isset($_SESSION['user_id'])) return null;
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'role' => $_SESSION['user_role']
        ];
    }

    public static function check() {
        if (!isset($_SESSION['user_id'])) return false;
        
        // Session timeout (e.g., 30 minutes)
        if (time() - $_SESSION['last_activity'] > 1800) {
            self::logout();
            return false;
        }
        $_SESSION['last_activity'] = time();
        return true;
    }

    public static function role() {
        return $_SESSION['user_role'] ?? null;
    }

    public static function isSuperAdmin() {
        return self::role() === 'super_admin';
    }

    public static function isAdmin() {
        return in_array(self::role(), ['admin', 'super_admin']);
    }
}
