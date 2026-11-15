<?php
require_once __DIR__ . '/../include/auth.php';
$_SESSION = [];
session_destroy();
header('Location: login.php');
exit;
