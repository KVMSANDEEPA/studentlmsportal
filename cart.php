<?php
require_once __DIR__ . '/includes/init.php';
$pdo = get_db();
$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0;
if ($cart) {
    $in = implode(',', array_fill(0, count($cart), '?'));
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id IN ($in)");
    $stmt->execute($cart);
    $items = $stmt->fetchAll();
    foreach ($items as $it) { $total += (float)$it['price']; }
}
$pageTitle = 'Cart';
include __DIR__ . '/includes/header.php';
?>
<h1 class="text-2xl font-semibold mb-4">Your Cart</h1>
<?php if (!$items): ?>
  <p class="text-gray-600">Cart is empty. <a class="text-indigo-600" href="/store.php">Go to Store</a></p>
<?php else: ?>
  <div class="grid md:grid-cols-3 gap-4">
    <div class="md:col-span-2 space-y-3">
      <?php foreach ($items as $it): ?>
        <div class="bg-white border rounded p-3 flex items-center justify-between">
          <div>
            <div class="font-medium"><?= esc($it['title']) ?></div>
            <div class="text-sm text-gray-600"><?= esc(format_currency((float)$it['price'])) ?></div>
          </div>
          <form method="post" action="/cart_remove.php">
            <input type="hidden" name="course_id" value="<?= (int)$it['id'] ?>" />
            <button class="text-sm text-red-600 hover:underline"><i class="fa-solid fa-trash"></i> Remove</button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="bg-white border rounded p-4">
      <div class="flex items-center justify-between mb-3">
        <span class="text-gray-600">Total</span>
        <span class="font-semibold"><?= esc(format_currency($total)) ?></span>
      </div>
      <form method="post" action="/checkout.php">
        <button class="w-full bg-indigo-600 text-white rounded py-2 hover:bg-indigo-700"><i class="fa-solid fa-credit-card mr-2"></i> Checkout</button>
      </form>
    </div>
  </div>
<?php endif; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
