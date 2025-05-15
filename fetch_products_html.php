<?php
include 'db.php';

$productStmt = $pdo->query("SELECT * FROM products");
$products = $productStmt->fetchAll();

foreach ($products as $product): ?>
<tr>
    <form action="" method="POST">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <td><?= htmlspecialchars($product['barcode']) ?></td>
        <td><?= htmlspecialchars($product['name']) ?></td>
        <td><input type="number" step="0.01" name="new_price" value="<?= $product['price'] ?>" style="width: 80px;"></td>
        <td><input type="number" name="new_stock" value="<?= $product['stock'] ?>" style="width: 60px;"></td>
        <td><?= htmlspecialchars($product['supplier']) ?></td>
        <td>
            <button type="submit" name="update_product">Update</button>
            <a href="?delete_product_id=<?= $product['id'] ?>"><button type="button">Delete</button></a>
        </td>
    </form>
</tr>
<?php endforeach; ?>
