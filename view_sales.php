<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Get all sales from the database
$salesStmt = $pdo->query("SELECT * FROM sales ORDER BY date DESC");
$sales = $salesStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Sales - Furrfect Pawshop</title>
</head>
<body>
    <h1>Sales History</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Sale ID</th>
                <th>Date</th>
                <th>Total</th>
                <th>Cash Handed</th>
                <th>Change</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sales as $sale): ?>
                <tr>
                    <td><?= $sale['id'] ?></td>
                    <td><?= $sale['date'] ?></td>
                    <td>₱<?= number_format($sale['total'], 2) ?></td>
                    <td>₱<?= number_format($sale['cash_handled'], 2) ?></td>
                    <td>₱<?= number_format($sale['change'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <a href="pos.php">Back to POS</a>
</body>
</html>
