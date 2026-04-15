<?php
require_once 'includes/session.php';

$_SESSION = [];
session_destroy();

echo 'Logout de teste realizado';