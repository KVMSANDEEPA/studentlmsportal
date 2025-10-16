<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../config/auth.php';
require_admin();
$pdo = get_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';
    if ($id > 0) {
        if ($action === 'toggle') {
            $pdo->prepare('UPDATE users SET active = 1 - active WHERE id = ? AND role = "student"')->execute([$id]);
        }
    }
}

$students = $pdo->query("SELECT u.*, (SELECT COUNT(*) FROM enrollments e WHERE e.user_id = u.id) AS courses_count FROM users u WHERE role='student' ORDER BY created_at DESC")->fetchAll();
$pageTitle = 'Manage Students';
include __DIR__ . '/../includes/header.php';
?>
<h1 class="text-2xl font-semibold mb-4">Manage Students</h1>
<div class="bg-white border rounded p-4">
  <table class="w-full text-sm">
    <thead>
      <tr class="text-left text-gray-600">
        <th class="py-2">Name</th>
        <th>Email</th>
        <th>Student ID</th>
        <th>NIC</th>
        <th>Courses</th>
        <th>Status</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($students as $s): ?>
        <tr class="border-t">
          <td class="py-2"><?= esc($s['name']) ?></td>
          <td><?= esc($s['email']) ?></td>
          <td><span class="font-mono"><?= esc($s['student_no']) ?></span></td>
          <td><?= esc($s['nic'] ?? '-') ?></td>
          <td><?= (int)$s['courses_count'] ?></td>
          <td>
            <?php if ((int)$s['active'] === 1): ?>
              <span class="text-green-700">Active</span>
            <?php else: ?>
              <span class="text-gray-500">Inactive</span>
            <?php endif; ?>
          </td>
          <td>
            <form method="post">
              <input type="hidden" name="id" value="<?= (int)$s['id'] ?>" />
              <button name="action" value="toggle" class="text-sm px-3 py-1.5 border rounded">Toggle</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
