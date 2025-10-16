<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../config/auth.php';
require_login();
$user = get_current_user();
$pageTitle = 'Profile';
$pdo = get_db();

$errors = [];
$success = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $nic = trim($_POST['nic'] ?? '');
    if ($name === '') $errors[] = 'Name is required';
    if (!$errors) {
        try {
            $stmt = $pdo->prepare('UPDATE users SET name = ?, nic = ? WHERE id = ?');
            $stmt->execute([$name, $nic ?: null, $user['id']]);
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['nic'] = $nic ?: null;
            $success = 'Profile updated.';
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'users.nic')) {
                $errors[] = 'NIC already in use by another user';
            } else {
                $errors[] = 'Update failed';
            }
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>
<div class="max-w-lg">
  <h1 class="text-2xl font-semibold mb-4">Profile</h1>
  <div class="bg-white border rounded p-4 space-y-4">
    <div class="text-sm text-gray-600">Student ID: <span class="font-mono font-semibold"><?= esc($user['student_no'] ?? '-') ?></span></div>

    <?php if ($success): ?>
      <div class="p-3 rounded bg-green-50 text-green-700 text-sm"><?= esc($success) ?></div>
    <?php endif; ?>

    <?php if ($errors): ?>
      <div class="p-3 rounded bg-red-50 text-red-700 text-sm">
        <ul class="list-disc pl-5">
        <?php foreach ($errors as $e): ?>
          <li><?= esc($e) ?></li>
        <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" class="space-y-3">
      <div>
        <label class="block text-sm mb-1">Full name</label>
        <input name="name" value="<?= esc($user['name']) ?>" class="w-full border rounded px-3 py-2" />
      </div>
      <div>
        <label class="block text-sm mb-1">NIC (optional)</label>
        <input name="nic" value="<?= esc($user['nic'] ?? '') ?>" class="w-full border rounded px-3 py-2" />
      </div>
      <button class="bg-indigo-600 text-white rounded px-4 py-2">Save</button>
    </form>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
