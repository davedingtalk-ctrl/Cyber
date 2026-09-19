<?php
require_once __DIR__ . '/helpers.php';
$db = cl_db();
$method = $_SERVER['REQUEST_METHOD'];

function cl_device_from_ua($ua) {
    if (preg_match('/mobile/i', $ua)) return 'mobile';
    if (preg_match('/tablet|ipad/i', $ua)) return 'tablet';
    return 'desktop';
}
function cl_browser_from_ua($ua) {
    if (preg_match('/edg/i', $ua)) return 'Edge';
    if (preg_match('/chrome/i', $ua)) return 'Chrome';
    if (preg_match('/safari/i', $ua)) return 'Safari';
    if (preg_match('/firefox/i', $ua)) return 'Firefox';
    return 'Other';
}

if ($method === 'POST') {
    $in = cl_input();
    $type = in_array($in['type'] ?? '', ['visit', 'pageview', 'click']) ? $in['type'] : 'pageview';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $ref = cl_str($_SERVER['HTTP_REFERER'] ?? '', 255);
    $stmt = $db->prepare('INSERT INTO analytics_events (event_type, device, browser, referrer) VALUES (?,?,?,?)');
    $stmt->execute([$type, cl_device_from_ua($ua), cl_browser_from_ua($ua), $ref]);
    cl_ok();
}

if ($method === 'GET') {
    cl_require_admin();
    $totalVisits = $db->query("SELECT COUNT(*) c FROM analytics_events WHERE event_type='visit'")->fetch()['c'];
    $todayVisits = $db->query("SELECT COUNT(*) c FROM analytics_events WHERE event_type='visit' AND DATE(created_at)=CURDATE()")->fetch()['c'];
    $pageViews = $db->query("SELECT COUNT(*) c FROM analytics_events WHERE event_type='pageview'")->fetch()['c'];
    $clicks = $db->query("SELECT COUNT(*) c FROM analytics_events WHERE event_type='click'")->fetch()['c'];
    $devices = $db->query("SELECT device, COUNT(*) c FROM analytics_events GROUP BY device")->fetchAll();
    $recent = $db->query("SELECT event_type, device, browser, created_at FROM analytics_events ORDER BY id DESC LIMIT 20")->fetchAll();
    cl_ok(compact('totalVisits', 'todayVisits', 'pageViews', 'clicks', 'devices', 'recent'));
}

cl_fail('Unknown request.');
