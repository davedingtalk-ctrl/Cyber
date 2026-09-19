<?php
require_once __DIR__ . '/helpers.php';
cl_require_admin();
$db = cl_db();
$in = cl_input();

function cl_hex($v, $fallback) {
    $v = trim((string)$v);
    return preg_match('/^#[0-9a-fA-F]{6}$/', $v) ? $v : $fallback;
}

$primary = cl_hex($in['primary_color'] ?? '', '#3b82f6');
$secondary = cl_hex($in['secondary_color'] ?? '', '#22d3ee');
$bg = cl_hex($in['bg_color'] ?? '', '#050810');
$glow = max(0, min(1, (float)($in['glow'] ?? 0.55)));
$animations = !empty($in['animations']) ? 1 : 0;

$db->prepare('UPDATE theme_settings SET primary_color=?, secondary_color=?, bg_color=?, glow=?, animations=? WHERE id=1')
   ->execute([$primary, $secondary, $bg, $glow, $animations]);
cl_ok();
