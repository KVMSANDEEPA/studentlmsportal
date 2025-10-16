<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/config/auth.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $nic = trim($_POST['nic'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '') $errors[] = 'Name is required';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters';

    if (!$errors) {
        $pdo = get_db();
        // Generate a unique student number
        $studentNo = generate_student_number();
        // Ensure uniqueness loop (rare collision)
        for ($i=0; $i<5; $i++) {
            $stmt = $pdo->prepare('SELECT 1 FROM users WHERE student_no = ?');
            $stmt->execute([$studentNo]);
            if (!$stmt->fetch()) break;
            $studentNo = generate_student_number();
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        try {
            $stmt = $pdo->prepare('INSERT INTO users (student_no, nic, name, email, password_hash, role) VALUES (?, ?, ?, ?, ?, "student")');
            $stmt->execute([$studentNo, $nic ?: null, $name, $email, $hash]);
            set_flash('success', 'Registration successful. Please login. Your Student ID: ' . $studentNo);
            redirect('/login.php');
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'users.nic')) {
                $errors[] = 'NIC already registered';
            } elseif (str_contains($e->getMessage(), 'users.email')) {
                $errors[] = 'Email already registered';
            } else {
                $errors[] = 'Registration failed';
            }
        }
    }
}

$pageTitle = 'Register';
include __DIR__ . '/includes/header.php';
?>
<div class="max-w-lg mx-auto">
  <h1 class="text-2xl font-semibold mb-4">Create your account</h1>
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
      <label class="block text-sm mb-1">Full name</label>
      <input name="name" class="w-full border rounded px-3 py-2" required />
    </div>
    <div>
      <label class="block text-sm mb-1">Email</label>
      <input type="email" name="email" class="w-full border rounded px-3 py-2" required />
    </div>
    <div>
      <label class="block text-sm mb-1">NIC number (optional)</label>
      <input name="nic" class="w-full border rounded px-3 py-2" />
    </div>
    <div>
      <label class="block text-sm mb-1">Password</label>
      <input type="password" name="password" class="w-full border rounded px-3 py-2" required />
    </div>
    <button class="w-full bg-indigo-600 text-white rounded py-2 hover:bg-indigo-700">Register</button>
  </form>
  <p class="text-sm text-gray-600 mt-3">Already have an account? <a class="text-indigo-600" href="/login.php">Login</a></p>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
