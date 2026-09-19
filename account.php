<?php
require_once __DIR__ . '/helpers.php';
cl_require_admin();
$db = cl_db();
$in = cl_input();

$newUsername = isset($in['username']) ? cl_str($in['username'], 64) : null;
$newPassword = isset($in['password']) ? (string)$in['password'] : null;
$currentPassword = (string)($in['current_password'] ?? '');

$stmt = $db->prepare('SELECT * FROM admin_users WHERE id=?');
$stmt->execute([$_SESSION['admin_id']]);
$user = $stmt->fetch();

if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
    cl_fail('Current password is incorrect.', 401);
}

$sql = 'UPDATE admin_users SET';
$params = [];
$parts = [];
if ($newUsername) { $parts[] = 'username=?'; $params[] = $newUsername; }
if ($newPassword) {
    if (strlen($newPassword) < 8) cl_fail('New password must be at least 8 characters.');
    $parts[] = 'password_hash=?'; $params[] = password_hash($newPassword, PASSWORD_BCRYPT);
}
if (!$parts) cl_fail('Nothing to update.');
$sql .= ' ' . implode(', ', $parts) . ' WHERE id=?';
$params[] = $_SESSION['admin_id'];

$db->prepare($sql)->execute($params);
cl_ok();
