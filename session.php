<?php
require_once __DIR__ . '/helpers.php';
cl_ok(['authenticated' => !empty($_SESSION['admin_id'])]);
