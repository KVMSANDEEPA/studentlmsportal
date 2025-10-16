<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/config/auth.php';
logout_user();
set_flash('success', 'Logged out successfully.');
redirect('/index.php');
