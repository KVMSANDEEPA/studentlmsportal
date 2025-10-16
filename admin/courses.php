<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../config/auth.php';
require_admin();
$pdo = get_db();

// Handle create/update/delete
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        if ($title === '') $errors[] = 'Title is required';
        if (!$errors) {
            $stmt = $pdo->prepare('INSERT INTO courses (title, description, price) VALUES (?, ?, ?)');
            $stmt->execute([$title, $description, $price]);
        }
    } elseif ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        if ($id > 0 && $title !== '') {
            $stmt = $pdo->prepare('UPDATE courses SET title=?, description=?, price=? WHERE id=?');
            $stmt->execute([$title, $description, $price, $id]);
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare('DELETE FROM courses WHERE id=?');
            $stmt->execute([$id]);
        }
    }
}

$courses = $pdo->query('SELECT * FROM courses ORDER BY created_at DESC')->fetchAll();
$pageTitle = 'Manage Courses';
include __DIR__ . '/../includes/header.php';
?>
<h1 class="text-2xl font-semibold mb-4">Manage Courses</h1>
<div class="grid md:grid-cols-2 gap-6">
  <div class="bg-white border rounded p-4">
    <h2 class="font-semibold mb-3">Create Course</h2>
    <?php if ($errors): ?>
      <div class="mb-3 p-2 rounded bg-red-50 text-red-700 text-sm"><?php foreach ($errors as $e) echo esc($e).' '; ?></div>
    <?php endif; ?>
    <form method="post" class="space-y-3">
      <input type="hidden" name="action" value="create" />
      <div>
        <label class="block text-sm mb-1">Title</label>
        <input name="title" class="w-full border rounded px-3 py-2" required />
      </div>
      <div>
        <label class="block text-sm mb-1">Description</label>
        <textarea name="description" class="w-full border rounded px-3 py-2" rows="3"></textarea>
      </div>
      <div>
        <label class="block text-sm mb-1">Price</label>
        <input type="number" step="0.01" name="price" class="w-full border rounded px-3 py-2" value="0" />
      </div>
      <button class="bg-indigo-600 text-white rounded px-4 py-2">Create</button>
    </form>
  </div>
  <div class="bg-white border rounded p-4">
    <h2 class="font-semibold mb-3">All Courses</h2>
    <div class="space-y-3">
      <?php foreach ($courses as $c): ?>
        <form method="post" class="border rounded p-3">
          <input type="hidden" name="id" value="<?= (int)$c['id'] ?>" />
          <div class="grid grid-cols-2 gap-2 mb-2">
            <input name="title" class="border rounded px-2 py-1" value="<?= esc($c['title']) ?>" />
            <input name="price" type="number" step="0.01" class="border rounded px-2 py-1" value="<?= esc($c['price']) ?>" />
          </div>
          <textarea name="description" class="w-full border rounded px-2 py-1" rows="2"><?= esc($c['description']) ?></textarea>
          <div class="mt-2 flex gap-2">
            <button name="action" value="update" class="text-sm px-3 py-1.5 bg-indigo-600 text-white rounded">Update</button>
            <button name="action" value="delete" class="text-sm px-3 py-1.5 bg-red-600 text-white rounded" onclick="return confirm('Delete this course?')">Delete</button>
          </div>
        </form>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
