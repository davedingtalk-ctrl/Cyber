<?php
require_once __DIR__ . '/helpers.php';
cl_require_admin();
$db = cl_db();

$profile = $db->query('SELECT * FROM profile WHERE id=1')->fetch();
if ($profile) $profile['stats'] = json_decode($profile['stats_json'] ?: '[]', true);

$socials = $db->query('SELECT * FROM socials ORDER BY sort_order ASC')->fetchAll();
$sections = $db->query('SELECT * FROM sections ORDER BY sort_order ASC')->fetchAll();
$theme = $db->query('SELECT * FROM theme_settings WHERE id=1')->fetch();
$loader = $db->query('SELECT * FROM loader_settings WHERE id=1')->fetch();
$seo = $db->query('SELECT * FROM seo_settings WHERE id=1')->fetch();
$admin = $db->query('SELECT username FROM admin_users WHERE id=' . (int)$_SESSION['admin_id'])->fetch();

cl_ok(compact('profile', 'socials', 'sections', 'theme', 'loader', 'seo', 'admin'));
