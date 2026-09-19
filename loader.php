<?php
require_once __DIR__ . '/helpers.php';
cl_require_admin();
$db = cl_db();
$in = cl_input();

$enabled = !empty($in['enabled']) ? 1 : 0;
$duration = max(0, min(10000, (int)($in['duration_ms'] ?? 2000)));
$text = cl_str($in['loader_text'] ?? 'Initializing Cyber Legend…', 120);

$db->prepare('UPDATE loader_settings SET enabled=?, duration_ms=?, loader_text=? WHERE id=1')
   ->execute([$enabled, $duration, $text]);
cl_ok();
