<?php
require_once __DIR__ . '/includes/init.php';

$courseId = (int)($_POST['course_id'] ?? 0);
if ($courseId > 0) {
    $_SESSION['cart'] = $_SESSION['cart'] ?? [];
    if (!in_array($courseId, $_SESSION['cart'], true)) {
        $_SESSION['cart'][] = $courseId;
    }
}
set_flash('success', 'Course added to cart');
redirect('/store.php');
