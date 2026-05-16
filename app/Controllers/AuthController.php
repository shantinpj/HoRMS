<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Auth;
use App\Core\Audit;

class AuthController extends Controller {
    public function showLogin() {
        if (Auth::check()) $this->redirect('/');
        $this->render('auth/login');
    }

    public function login() {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            Auth::login($user);
            Audit::log('Login', 'User logged in successfully');
            $this->redirect('/');
        } else {
            Audit::log('Failed Login Attempt', "Email: $email");
            $this->render('auth/login', ['error' => 'Invalid email or password']);
        }
    }

    public function logout() {
        Audit::log('Logout');
        Auth::logout();
        $this->redirect('/login');
    }
}
