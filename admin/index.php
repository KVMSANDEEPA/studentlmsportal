<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../config/auth.php';
require_admin();
$user = get_current_user();
$pageTitle = 'Admin Dashboard';
$pdo = get_db();

$totalStudents = (int)$pdo->query("SELECT COUNT(*) AS c FROM users WHERE role='student'")->fetch()['c'];
$totalCourses = (int)$pdo->query("SELECT COUNT(*) AS c FROM courses")->fetch()['c'];
$totalOrders = (int)$pdo->query("SELECT COUNT(*) AS c FROM orders")->fetch()['c'];
$revenue = (float)$pdo->query("SELECT COALESCE(SUM(total),0) AS s FROM orders WHERE status='paid'")->fetch()['s'];

include __DIR__ . '/../includes/header.php';
?>
<h1 class="text-2xl font-semibold mb-4">Admin Dashboard</h1>
<div class="grid md:grid-cols-4 gap-4 mb-6">
  <div class="bg-white border rounded p-4">
    <div class="text-sm text-gray-600">Students</div>
    <div class="text-2xl font-bold"><?= $totalStudents ?></div>
  </div>
  <div class="bg-white border rounded p-4">
    <div class="text-sm text-gray-600">Courses</div>
    <div class="text-2xl font-bold"><?= $totalCourses ?></div>
  </div>
  <div class="bg-white border rounded p-4">
    <div class="text-sm text-gray-600">Orders</div>
    <div class="text-2xl font-bold"><?= $totalOrders ?></div>
  </div>
  <div class="bg-white border rounded p-4">
    <div class="text-sm text-gray-600">Revenue</div>
    <div class="text-2xl font-bold"><?= esc(format_currency($revenue)) ?></div>
  </div>
</div>
<div class="flex gap-3 text-sm">
  <a class="px-3 py-1.5 border rounded hover:bg-gray-50" href="/admin/courses.php"><i class="fa-solid fa-book mr-2"></i> Manage Courses</a>
  <a class="px-3 py-1.5 border rounded hover:bg-gray-50" href="/admin/students.php"><i class="fa-solid fa-user-graduate mr-2"></i> Manage Students</a>
  <a class="px-3 py-1.5 border rounded hover:bg-gray-50" href="/admin/orders.php"><i class="fa-solid fa-receipt mr-2"></i> Orders</a>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
