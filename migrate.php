<?php
require_once __DIR__ . '/app/Core/Database.php';

use App\Core\Database;

$db = Database::getInstance();
$driver = $db->getAttribute(PDO::ATTR_DRIVER_NAME);
$isMysql = $driver === 'mysql';

$idType = $isMysql ? 'INT AUTO_INCREMENT PRIMARY KEY' : 'INTEGER PRIMARY KEY AUTOINCREMENT';
$realType = $isMysql ? 'DOUBLE' : 'REAL';

// Houses table
$db->exec("CREATE TABLE IF NOT EXISTS houses (
    id {$idType},
    name TEXT NOT NULL,
    address TEXT NOT NULL,
    description TEXT,
    rent_amount {$realType} NOT NULL,
    status TEXT DEFAULT 'available',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Tenants table
$db->exec("CREATE TABLE IF NOT EXISTS tenants (
    id {$idType},
    name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    phone TEXT,
    id_proof TEXT,
    profile_photo TEXT,
    status TEXT DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Leases table
$db->exec("CREATE TABLE IF NOT EXISTS leases (
    id {$idType},
    house_id INTEGER NOT NULL,
    tenant_id INTEGER NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    rent_amount {$realType} NOT NULL,
    electricity_rate {$realType} DEFAULT 0,
    status TEXT DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (house_id) REFERENCES houses(id),
    FOREIGN KEY (tenant_id) REFERENCES tenants(id)
)");

// Payments table (Expanded for manual charges)
$db->exec("CREATE TABLE IF NOT EXISTS payments (
    id {$idType},
    lease_id INTEGER NOT NULL,
    amount {$realType} NOT NULL,
    electricity_units {$realType} DEFAULT 0,
    electricity_rate {$realType} DEFAULT 0,
    electricity_amount {$realType} DEFAULT 0,
    cleaning_qty {$realType} DEFAULT 0,
    cleaning_rate {$realType} DEFAULT 0,
    cleaning_amount {$realType} DEFAULT 0,
    water_qty {$realType} DEFAULT 0,
    water_rate {$realType} DEFAULT 0,
    water_amount {$realType} DEFAULT 0,
    payment_date DATE NOT NULL,
    status TEXT DEFAULT 'paid',
    transaction_id TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lease_id) REFERENCES leases(id)
)");

// Alter existing tables for updates
$paymentColumns = [
    'electricity_units', 'electricity_rate', 'electricity_amount',
    'cleaning_qty', 'cleaning_rate', 'cleaning_amount',
    'water_qty', 'water_rate', 'water_amount'
];

foreach ($paymentColumns as $col) {
    try { $db->exec("ALTER TABLE payments ADD COLUMN $col {$realType} DEFAULT 0"); } catch (Exception $e) {}
}

// Users table
$roleDefinition = $isMysql
    ? "role TEXT DEFAULT 'normal'"
    : "role TEXT CHECK(role IN ('normal', 'admin', 'super_admin')) DEFAULT 'normal'";

$db->exec("CREATE TABLE IF NOT EXISTS users (
    id {$idType},
    name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    {$roleDefinition},
    mfa_secret TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Audit Logs table
$db->exec("CREATE TABLE IF NOT EXISTS audit_logs (
    id {$idType},
    user_id INTEGER,
    action TEXT NOT NULL,
    details TEXT,
    ip_address TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)");

// Update existing tables for data isolation (Add created_by)
$tablesToUpdate = ['houses', 'tenants', 'leases', 'payments'];
foreach ($tablesToUpdate as $table) {
    try { $db->exec("ALTER TABLE $table ADD COLUMN created_by INTEGER"); } catch (Exception $e) {}
}

// Create a default Super Admin if not exists
$stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE role = 'super_admin'");
$stmt->execute();
if ($stmt->fetchColumn() == 0) {
    $password = password_hash('admin123', PASSWORD_BCRYPT);
    $db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)")
       ->execute(['Super Admin', 'admin@houserent.com', $password, 'super_admin']);
}

echo "Auth and Audit tables updated successfully!\n";
