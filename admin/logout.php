<?php
require_once __DIR__ . '/../includes/auth.php';
adminLogout();
redirect(ADMIN_URL . '/login.php');
