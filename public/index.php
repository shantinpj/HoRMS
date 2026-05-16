<?php
session_start();

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Router;
use App\Controllers\DashboardController;
use App\Controllers\HouseController;
use App\Controllers\TenantController;
use App\Controllers\PaymentController;
use App\Controllers\LeaseController;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\AuditController;

$router = new Router();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Auth Routes
$router->add('GET', '/login', [AuthController::class, 'showLogin']);
$router->add('POST', '/login', [AuthController::class, 'login']);
$router->add('GET', '/logout', [AuthController::class, 'logout']);

// Main Routes
$router->add('GET', '/', [DashboardController::class, 'index']);

$router->add('GET', '/houses', [HouseController::class, 'index']);
$router->add('GET', '/houses/create', [HouseController::class, 'create']);
$router->add('POST', '/houses/store', [HouseController::class, 'store']);
$router->add('GET', '/houses/edit', [HouseController::class, 'edit']);
$router->add('POST', '/houses/update', [HouseController::class, 'update']);

$router->add('GET', '/tenants', [TenantController::class, 'index']);
$router->add('GET', '/tenants/create', [TenantController::class, 'create']);
$router->add('POST', '/tenants/store', [TenantController::class, 'store']);
$router->add('GET', '/tenants/edit', [TenantController::class, 'edit']);
$router->add('POST', '/tenants/update', [TenantController::class, 'update']);
$router->add('GET', '/tenants/show', [TenantController::class, 'show']);

$router->add('GET', '/payments', [PaymentController::class, 'index']);
$router->add('GET', '/payments/create', [PaymentController::class, 'create']);
$router->add('POST', '/payments/store', [PaymentController::class, 'store']);
$router->add('GET', '/payments/edit', [PaymentController::class, 'edit']);
$router->add('POST', '/payments/update', [PaymentController::class, 'update']);
$router->add('GET', '/payments/delete', [PaymentController::class, 'delete']);

$router->add('GET', '/leases', [LeaseController::class, 'index']);
$router->add('GET', '/leases/create', [LeaseController::class, 'create']);
$router->add('POST', '/leases/store', [LeaseController::class, 'store']);
$router->add('GET', '/leases/edit', [LeaseController::class, 'edit']);
$router->add('POST', '/leases/update', [LeaseController::class, 'update']);
$router->add('GET', '/leases/delete', [LeaseController::class, 'delete']);

// Admin/Super Admin Routes
$router->add('GET', '/users', [UserController::class, 'index']);
$router->add('GET', '/users/create', [UserController::class, 'create']);
$router->add('POST', '/users/store', [UserController::class, 'store']);
$router->add('GET', '/users/edit', [UserController::class, 'edit']);
$router->add('POST', '/users/update', [UserController::class, 'update']);

$router->add('GET', '/audit', [AuditController::class, 'index']);

$router->dispatch($method, $uri);
