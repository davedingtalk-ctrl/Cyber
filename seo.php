<?php
require_once __DIR__ . '/helpers.php';
cl_require_admin();
$db = cl_db();
$in = cl_input();

$title = cl_str($in['title'] ?? 'Cyber Legend', 160);
$desc = cl_str($in['description'] ?? '', 300);
$keywords = cl_str($in['keywords'] ?? '', 300);
$ogTitle = cl_str($in['og_title'] ?? '', 160);
$ogDesc = cl_str($in['og_description'] ?? '', 300);
$ogImage = cl_str($in['og_image'] ?? '', 255);

$db->prepare('UPDATE seo_settings SET title=?, description=?, keywords=?, og_title=?, og_description=?, og_image=? WHERE id=1')
   ->execute([$title, $desc, $keywords, $ogTitle, $ogDesc, $ogImage]);
cl_ok();
