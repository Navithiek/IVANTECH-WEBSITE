<?php
// Run this script from CLI to seed initial admin/customer with secure password hashes.
// php scripts/seed.php
require_once __DIR__ . '/../config/database.php';
$pdo = getPDO();

function insertUser($pdo, $email, $password, $name, $role = 'customer'){
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (email,password,name,role,phone,address,status) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$email, $hash, $name, $role, '', '', 'active']);
    return $pdo->lastInsertId();
}

echo "Seeding default users...\n";
$adminId = insertUser($pdo, 'admin@ivantech.ph', 'Admin@1234', 'Ivan Reyes', 'admin');
$custId  = insertUser($pdo, 'juan@example.com', 'Customer@1234', 'Juan dela Cruz', 'customer');

echo "Inserted admin id={$adminId} customer id={$custId}\n";
echo "Done. Import database.sql first, then run this script to create hashed accounts.\n";
