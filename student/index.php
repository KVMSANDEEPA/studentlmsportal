<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../config/auth.php';
require_login();
$user = get_current_user();
$pageTitle = 'Student Dashboard';
include __DIR__ . '/../includes/header.php';
$pdo = get_db();

// Count enrolled courses
$stmt = $pdo->prepare('SELECT COUNT(*) AS c FROM enrollments WHERE user_id = ?');
$stmt->execute([$user['id']]);
$count = (int)$stmt->fetch()['c'];
?>
<div class="grid md:grid-cols-3 gap-4">
  <div class="md:col-span-2 space-y-4">
    <div class="bg-white border rounded p-4">
      <h2 class="font-semibold text-lg mb-2">Welcome, <?= esc($user['name']) ?></h2>
      <p class="text-sm text-gray-600">Student ID: <span class="font-mono font-semibold"><?= esc($user['student_no'] ?? '-') ?></span></p>
    </div>
    <div class="bg-white border rounded p-4">
      <h3 class="font-semibold mb-3">Quick links</h3>
      <div class="flex gap-3 text-sm">
        <a class="px-3 py-1.5 border rounded hover:bg-gray-50" href="/student/my_courses.php"><i class="fa-solid fa-book mr-2"></i> My Courses</a>
        <a class="px-3 py-1.5 border rounded hover:bg-gray-50" href="/student/profile.php"><i class="fa-solid fa-id-card mr-2"></i> Profile</a>
        <a class="px-3 py-1.5 border rounded hover:bg-gray-50" href="/store.php"><i class="fa-solid fa-store mr-2"></i> Store</a>
      </div>
    </div>
  </div>
  <div class="space-y-4">
    <div class="bg-white border rounded p-4">
      <div class="text-sm text-gray-600">Enrolled courses</div>
      <div class="text-2xl font-bold"><?= $count ?></div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
