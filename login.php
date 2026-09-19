<?php
require_once __DIR__ . '/helpers.php';

$in = cl_input();
$username = cl_str($in['username'] ?? '', 64);
$password = (string)($in['password'] ?? '');

if ($username === '' || $password === '') {
    cl_fail('Username and password are required.');
}

$db = cl_db();
$stmt = $db->prepare('SELECT * FROM admin_users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    // Same error for both cases — don't reveal which part was wrong.
    cl_fail('Incorrect username or password.', 401);
}

session_regenerate_id(true);
$_SESSION['admin_id'] = $user['id'];
cl_ok(['username' => $user['username']]);
