<?php
require_once __DIR__ . '/includes/init.php';
$pdo = get_db();
$courses = $pdo->query('SELECT * FROM courses ORDER BY created_at DESC')->fetchAll();
$pageTitle = 'Store';
include __DIR__ . '/includes/header.php';
?>
<div class="flex items-center justify-between mb-4">
  <h1 class="text-2xl font-semibold">Course Store</h1>
  <a href="/cart.php" class="text-sm inline-flex items-center gap-2 px-3 py-1.5 border rounded hover:bg-gray-50"><i class="fa-solid fa-cart-shopping"></i> View Cart</a>
</div>
<div class="grid md:grid-cols-3 gap-4">
  <?php foreach ($courses as $c): ?>
    <div class="border rounded bg-white p-4 flex flex-col">
      <h3 class="font-semibold text-lg mb-1"><?= esc($c['title']) ?></h3>
      <p class="text-sm text-gray-600 flex-1 mb-3"><?= esc(mb_strimwidth($c['description'] ?? '', 0, 120, '...')) ?></p>
      <div class="flex items-center justify-between">
        <span class="font-semibold"><?= esc(format_currency((float)$c['price'])) ?></span>
        <form method="post" action="/cart_add.php">
          <input type="hidden" name="course_id" value="<?= (int)$c['id'] ?>" />
          <button class="text-sm inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-600 text-white rounded hover:bg-indigo-700"><i class="fa-solid fa-plus"></i> Add</button>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
