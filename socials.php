<?php
require_once __DIR__ . '/helpers.php';
cl_require_admin();
$db = cl_db();
$method = $_SERVER['REQUEST_METHOD'];
$in = cl_input();

function cl_valid_url($u) {
    return filter_var($u, FILTER_VALIDATE_URL) !== false;
}

if ($method === 'POST') {
    $action = $in['action'] ?? 'create';

    if ($action === 'create') {
        $type = cl_str($in['type'] ?? 'Custom', 40);
        $url = cl_str($in['url'] ?? '', 500);
        if (!cl_valid_url($url)) cl_fail('Please provide a valid URL.');
        $maxOrder = $db->query('SELECT COALESCE(MAX(sort_order),0) m FROM socials')->fetch()['m'];
        $stmt = $db->prepare('INSERT INTO socials (type, url, enabled, sort_order) VALUES (?, ?, 1, ?)');
        $stmt->execute([$type, $url, $maxOrder + 1]);
        cl_ok(['id' => $db->lastInsertId()]);
    }

    if ($action === 'update') {
        $id = (int)($in['id'] ?? 0);
        $type = cl_str($in['type'] ?? 'Custom', 40);
        $url = cl_str($in['url'] ?? '', 500);
        if (!cl_valid_url($url)) cl_fail('Please provide a valid URL.');
        $db->prepare('UPDATE socials SET type=?, url=? WHERE id=?')->execute([$type, $url, $id]);
        cl_ok();
    }

    if ($action === 'toggle') {
        $id = (int)($in['id'] ?? 0);
        $db->prepare('UPDATE socials SET enabled = 1-enabled WHERE id=?')->execute([$id]);
        cl_ok();
    }

    if ($action === 'reorder') {
        $idA = (int)($in['a'] ?? 0); $idB = (int)($in['b'] ?? 0);
        $a = $db->prepare('SELECT sort_order FROM socials WHERE id=?'); $a->execute([$idA]); $oa = $a->fetch()['sort_order'];
        $b = $db->prepare('SELECT sort_order FROM socials WHERE id=?'); $b->execute([$idB]); $ob = $b->fetch()['sort_order'];
        $db->prepare('UPDATE socials SET sort_order=? WHERE id=?')->execute([$ob, $idA]);
        $db->prepare('UPDATE socials SET sort_order=? WHERE id=?')->execute([$oa, $idB]);
        cl_ok();
    }

    if ($action === 'delete') {
        $id = (int)($in['id'] ?? 0);
        $db->prepare('DELETE FROM socials WHERE id=?')->execute([$id]);
        cl_ok();
    }
}

cl_fail('Unknown request.');
