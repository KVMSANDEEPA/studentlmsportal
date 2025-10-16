<?php
require_once __DIR__ . '/includes/init.php';
$pageTitle = 'Welcome';
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section class="grid gap-6 md:grid-cols-2 items-center">
  <div>
    <h1 class="text-3xl font-bold mb-3">Welcome to <?= esc(APP_NAME) ?></h1>
    <p class="text-gray-600 mb-6">Discover and enroll in courses. Students can buy classes from the store and access them in their portal. Admins manage courses, students, and orders.</p>
    <div class="flex gap-3">
      <a href="/store.php" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
        <i class="fa-solid fa-store"></i>
        Browse Store
      </a>
      <a href="/register.php" class="inline-flex items-center gap-2 px-4 py-2 bg-white border rounded hover:bg-gray-50">
        <i class="fa-solid fa-user-plus"></i>
        Register
      </a>
    </div>
  </div>
  <div class="hidden md:block">
    <div class="rounded-lg border bg-white p-6">
      <ul class="space-y-3 text-sm">
        <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-green-600"></i> Student unique ID automatically generated</li>
        <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-green-600"></i> Purchase courses securely</li>
        <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-green-600"></i> Admin manages students and courses</li>
      </ul>
    </div>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
