<?php
require_once __DIR__ . '/helpers.php';
cl_require_admin();
$db = cl_db();
$in = cl_input();
$action = $in['action'] ?? 'create';

if ($action === 'create') {
    $maxOrder = $db->query('SELECT COALESCE(MAX(sort_order),0) m FROM sections')->fetch()['m'];
    $stmt = $db->prepare('INSERT INTO sections (title, description, icon, btn_text, btn_url, visible, sort_order) VALUES (?,?,?,?,?,1,?)');
    $stmt->execute(['New section', 'Description here.', '⚡', '', '', $maxOrder + 1]);
    cl_ok(['id' => $db->lastInsertId()]);
}

if ($action === 'update') {
    $id = (int)($in['id'] ?? 0);
    $title = cl_str($in['title'] ?? '', 160);
    $desc = cl_str($in['description'] ?? '', 2000);
    $icon = cl_str($in['icon'] ?? '', 20);
    $btnText = cl_str($in['btn_text'] ?? '', 60);
    $btnUrl = cl_str($in['btn_url'] ?? '', 500);
    $imagePath = isset($in['image_path']) ? cl_str($in['image_path'], 255) : null;

    $sql = 'UPDATE sections SET title=?, description=?, icon=?, btn_text=?, btn_url=?';
    $params = [$title, $desc, $icon, $btnText, $btnUrl];
    if ($imagePath !== null) { $sql .= ', image_path=?'; $params[] = $imagePath; }
    $sql .= ' WHERE id=?'; $params[] = $id;
    $db->prepare($sql)->execute($params);
    cl_ok();
}

if ($action === 'toggle') {
    $id = (int)($in['id'] ?? 0);
    $db->prepare('UPDATE sections SET visible = 1-visible WHERE id=?')->execute([$id]);
    cl_ok();
}

if ($action === 'reorder') {
    $idA = (int)($in['a'] ?? 0); $idB = (int)($in['b'] ?? 0);
    $a = $db->prepare('SELECT sort_order FROM sections WHERE id=?'); $a->execute([$idA]); $oa = $a->fetch()['sort_order'];
    $b = $db->prepare('SELECT sort_order FROM sections WHERE id=?'); $b->execute([$idB]); $ob = $b->fetch()['sort_order'];
    $db->prepare('UPDATE sections SET sort_order=? WHERE id=?')->execute([$ob, $idA]);
    $db->prepare('UPDATE sections SET sort_order=? WHERE id=?')->execute([$oa, $idB]);
    cl_ok();
}

if ($action === 'delete') {
    $id = (int)($in['id'] ?? 0);
    $db->prepare('DELETE FROM sections WHERE id=?')->execute([$id]);
    cl_ok();
}

cl_fail('Unknown request.');
