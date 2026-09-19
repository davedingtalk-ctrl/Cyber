<?php
require_once __DIR__ . '/helpers.php';
cl_require_admin();
$db = cl_db();
$in = cl_input();

$name = cl_str($in['name'] ?? 'Cyber Legend', 120);
$tagline = cl_str($in['tagline'] ?? '', 255);
$about = cl_str($in['about'] ?? '', 4000);
$badge = !empty($in['badge']) ? 1 : 0;
$dp_path = isset($in['dp_path']) ? cl_str($in['dp_path'], 255) : null;
$stats = isset($in['stats']) && is_array($in['stats']) ? $in['stats'] : null;

$sql = 'UPDATE profile SET name=?, tagline=?, about=?, badge=?';
$params = [$name, $tagline, $about, $badge];
if ($dp_path !== null) { $sql .= ', dp_path=?'; $params[] = $dp_path; }
if ($stats !== null) { $sql .= ', stats_json=?'; $params[] = json_encode(array_slice($stats, 0, 12)); }
$sql .= ' WHERE id=1';

$db->prepare($sql)->execute($params);
cl_ok();
