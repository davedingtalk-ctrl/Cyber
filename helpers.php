<?php
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');
session_name(CL_SESSION_NAME);
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

function cl_db() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER, DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
        } catch (PDOException $e) {
            cl_fail('Database connection failed. Check config.php credentials.', 500);
        }
    }
    return $pdo;
}

function cl_input() {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function cl_ok($data = []) {
    echo json_encode(array_merge(['ok' => true], $data));
    exit;
}

function cl_fail($message, $code = 400) {
    http_response_code($code);
    echo json_encode(['ok' => false, 'error' => $message]);
    exit;
}

function cl_require_admin() {
    if (empty($_SESSION['admin_id'])) {
        cl_fail('Not authenticated.', 401);
    }
}

function cl_str($v, $max = 500) {
    $v = trim((string)$v);
    return mb_substr($v, 0, $max);
}
