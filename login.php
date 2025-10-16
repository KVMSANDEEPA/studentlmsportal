<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/config/auth.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier'] ?? ''); // email or student_no or NIC
    $password = $_POST['password'] ?? '';

    if ($identifier === '' || $password === '') {
        $errors[] = 'Identifier and password are required';
    } else {
        $pdo = get_db();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? OR student_no = ? OR nic = ? LIMIT 1');
        $stmt->execute([$identifier, $identifier, $identifier]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            if ((int)$user['active'] !== 1) {
                $errors[] = 'Account is inactive. Contact admin.';
            } else {
                login_user($user);
                set_flash('success', 'Welcome back, ' . $user['name'] . '!');
                if ($user['role'] === 'admin') redirect('/admin/index.php');
                redirect('/student/index.php');
            }
        } else {
            $errors[] = 'Invalid credentials';
        }
    }
}

$pageTitle = 'Login';
include __DIR__ . '/includes/header.php';
?>
<div class="max-w-md mx-auto">
  <h1 class="text-2xl font-semibold mb-4">Login</h1>
  <?php if ($errors): ?>
    <div class="mb-4 p-3 rounded bg-red-50 text-red-700">
      <ul class="list-disc pl-5 text-sm">
        <?php foreach ($errors as $e): ?>
          <li><?= esc($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
  <form method="post" class="space-y-4 bg-white p-6 rounded border">
    <div>
      <label class="block text-sm mb-1">Email / Student ID / NIC</label>
      <input name="identifier" class="w-full border rounded px-3 py-2" required />
    </div>
    <div>
      <label class="block text-sm mb-1">Password</label>
      <input type="password" name="password" class="w-full border rounded px-3 py-2" required />
    </div>
    <button class="w-full bg-indigo-600 text-white rounded py-2 hover:bg-indigo-700">Login</button>
  </form>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
