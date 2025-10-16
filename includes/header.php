<?php
if (!isset($pageTitle)) { $pageTitle = APP_NAME; }
$currentUser = $_SESSION['user'] ?? null;
$cartCount = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= esc($pageTitle) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
  <header class="bg-white border-b">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
      <a href="/index.php" class="flex items-center gap-2 font-semibold text-lg">
        <i class="fa-solid fa-graduation-cap text-indigo-600"></i>
        <span><?= esc(APP_NAME) ?></span>
      </a>
      <nav class="flex items-center gap-4 text-sm">
        <a class="hover:text-indigo-600" href="/store.php"><i class="fa-solid fa-store mr-1"></i> Store</a>
        <a class="hover:text-indigo-600 relative" href="/cart.php">
          <i class="fa-solid fa-cart-shopping mr-1"></i> Cart
          <?php if ($cartCount > 0): ?>
            <span class="absolute -top-2 -right-3 bg-indigo-600 text-white text-[10px] rounded-full px-1.5 py-0.5"><?= (int)$cartCount ?></span>
          <?php endif; ?>
        </a>
        <?php if ($currentUser): ?>
          <?php if ($currentUser['role'] === 'admin'): ?>
            <a class="hover:text-indigo-600" href="/admin/index.php"><i class="fa-solid fa-user-tie mr-1"></i> Admin</a>
          <?php else: ?>
            <a class="hover:text-indigo-600" href="/student/index.php"><i class="fa-solid fa-user-graduate mr-1"></i> Student</a>
          <?php endif; ?>
          <span class="text-gray-600 hidden sm:inline">Hi, <?= esc($currentUser['name']) ?></span>
          <a class="hover:text-indigo-600" href="/logout.php"><i class="fa-solid fa-right-from-bracket mr-1"></i> Logout</a>
        <?php else: ?>
          <a class="hover:text-indigo-600" href="/login.php"><i class="fa-solid fa-right-to-bracket mr-1"></i> Login</a>
          <a class="hover:text-indigo-600" href="/register.php"><i class="fa-solid fa-user-plus mr-1"></i> Register</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>
  <main class="container mx-auto px-4 py-6">
    <?php if (!empty($_SESSION['flash'])): $flash = $_SESSION['flash']; unset($_SESSION['flash']); ?>
      <div class="mb-4 p-3 rounded text-sm <?= $flash['type'] === 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' ?>">
        <?= esc($flash['message']) ?>
      </div>
    <?php endif; ?>
