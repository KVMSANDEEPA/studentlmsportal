<?php
require_once __DIR__ . '/includes/init.php';
$courseId = (int)($_POST['course_id'] ?? 0);
if ($courseId > 0 && !empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array_values(array_filter($_SESSION['cart'], fn($id) => (int)$id !== $courseId));
}
set_flash('success', 'Course removed from cart');
redirect('/cart.php');
