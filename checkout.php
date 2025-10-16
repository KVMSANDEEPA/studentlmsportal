<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/config/auth.php';
require_login();

$pdo = get_db();
$user = get_current_user();
$cart = $_SESSION['cart'] ?? [];

if (!$cart) {
    set_flash('error', 'Cart is empty');
    redirect('/cart.php');
}

$pdo->beginTransaction();
try {
    // Calculate total and fetch courses
    $in = implode(',', array_fill(0, count($cart), '?'));
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id IN ($in) FOR UPDATE");
    $stmt->execute($cart);
    $courses = $stmt->fetchAll();
    if (!$courses) {
        throw new Exception('Courses not found');
    }
    $total = 0;
    foreach ($courses as $c) { $total += (float)$c['price']; }

    // Create order
    $stmt = $pdo->prepare('INSERT INTO orders (user_id, total, status) VALUES (?, ?, "paid")');
    $stmt->execute([$user['id'], $total]);
    $orderId = (int)$pdo->lastInsertId();

    // Items and enrollments
    $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, course_id, price) VALUES (?, ?, ?)');
    $enrollStmt = $pdo->prepare('INSERT IGNORE INTO enrollments (user_id, course_id) VALUES (?, ?)');
    foreach ($courses as $c) {
        $itemStmt->execute([$orderId, (int)$c['id'], (float)$c['price']]);
        $enrollStmt->execute([(int)$user['id'], (int)$c['id']]);
    }

    $pdo->commit();
    unset($_SESSION['cart']);
    set_flash('success', 'Checkout successful! You are enrolled in purchased courses.');
    redirect('/student/my_courses.php');
} catch (Throwable $e) {
    $pdo->rollBack();
    set_flash('error', 'Checkout failed. Please try again.');
    redirect('/cart.php');
}
