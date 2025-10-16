<?php
require_once __DIR__ . '/../includes/init.php';

function get_current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function require_login(): void {
    if (!get_current_user()) {
        set_flash('error', 'Please login first.');
        redirect('/login.php');
    }
}

function require_admin(): void {
    $user = get_current_user();
    if (!$user || $user['role'] !== 'admin') {
        set_flash('error', 'Admins only.');
        redirect('/login.php');
    }
}

function login_user(array $user): void {
    $_SESSION['user'] = $user;
}

function logout_user(): void {
    unset($_SESSION['user']);
}

function generate_student_number(): string {
    // Format: S{YYYY}{random 5}
    $year = date('Y');
    $rand = substr(str_shuffle('0123456789'), 0, 5);
    return 'S' . $year . $rand;
}
