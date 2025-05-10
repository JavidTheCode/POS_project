<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
require_once 'config.php';
require_once 'db.php';

$totalOrders = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];

$totalRevenue = $conn->query("SELECT SUM(total_amount) as total FROM orders")->fetch_assoc()['total'];

$topProductQuery = "
    SELECT p.name, SUM(oi.quantity) as total_sold
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    GROUP BY oi.product_id
    ORDER BY total_sold DESC
    LIMIT 1
";
$topProduct = $conn->query($topProductQuery)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>

<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
    <p>This is the admin dashboard</p>

    <ul>
        <li><strong>Total Orders:</strong> <?= $totalOrders ?></li>
        <li><strong>Total Revenue:</strong> $<?= number_format($totalRevenue, 2) ?></li>
        <li><strong>Top Product:</strong> <?= $topProduct['name'] ?> (<?= $topProduct['total_sold'] ?> sold)</li>
    </ul>

    <a href="index.php">Back to Products</a> |


    <a href=auth/logout.php>Log out</a>

</body>

</html>