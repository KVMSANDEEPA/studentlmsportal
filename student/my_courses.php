<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../config/auth.php';
require_login();
$user = get_current_user();
$pageTitle = 'My Courses';
include __DIR__ . '/../includes/header.php';
$pdo = get_db();

$stmt = $pdo->prepare('SELECT c.* FROM enrollments e JOIN courses c ON c.id = e.course_id WHERE e.user_id = ? ORDER BY e.created_at DESC');
$stmt->execute([$user['id']]);
$courses = $stmt->fetchAll();
?>
<h1 class="text-2xl font-semibold mb-4">My Courses</h1>
<?php if (!$courses): ?>
  <p class="text-gray-600">No enrollments yet. Browse the <a class="text-indigo-600" href="/store.php">store</a>.</p>
<?php else: ?>
  <div class="grid md:grid-cols-3 gap-4">
    <?php foreach ($courses as $c): ?>
      <div class="border rounded bg-white p-4">
        <h3 class="font-semibold text-lg mb-1"><?= esc($c['title']) ?></h3>
        <p class="text-sm text-gray-600 mb-2"><?= esc(mb_strimwidth($c['description'] ?? '', 0, 120, '...')) ?></p>
        <a class="text-sm text-indigo-600 hover:underline" href="#"><i class="fa-solid fa-play mr-1"></i> Open</a>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>
