<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../config/auth.php';
require_admin();
$pdo = get_db();

$orders = $pdo->query("SELECT o.*, u.name, u.email FROM orders o JOIN users u ON u.id = o.user_id ORDER BY o.created_at DESC")->fetchAll();
$pageTitle = 'Orders';
include __DIR__ . '/../includes/header.php';
?>
<h1 class="text-2xl font-semibold mb-4">Orders</h1>
<div class="bg-white border rounded p-4">
  <table class="w-full text-sm">
    <thead>
      <tr class="text-left text-gray-600">
        <th class="py-2">Order #</th>
        <th>Customer</th>
        <th>Total</th>
        <th>Status</th>
        <th>Created</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($orders as $o): ?>
        <tr class="border-t">
          <td class="py-2">#<?= (int)$o['id'] ?></td>
          <td><?= esc($o['name']) ?> <span class="text-gray-500">(<?= esc($o['email']) ?>)</span></td>
          <td><?= esc(format_currency((float)$o['total'])) ?></td>
          <td><?= esc($o['status']) ?></td>
          <td><?= esc($o['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
