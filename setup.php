<?php
require_once __DIR__ . '/helpers.php';

// Refuses to run if an admin already exists — safe to leave this file in
// place, but for extra safety you can delete or rename it after first use.
$db = cl_db();
$count = $db->query('SELECT COUNT(*) c FROM admin_users')->fetch()['c'];
if ($count > 0) {
    cl_fail('Setup already completed. An admin account already exists.', 403);
}

$username = 'admin';
$password = 'admin123';

$hash = password_hash($password, PASSWORD_BCRYPT);
$stmt = $db->prepare('INSERT INTO admin_users (username, password_hash) VALUES (?, ?)');
$stmt->execute([$username, $hash]);

cl_ok(['message' => 'Admin account created. Username: admin / Password: admin123 — log in and change this immediately in Admin > Security.']);
