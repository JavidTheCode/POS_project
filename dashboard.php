<?php
require_once 'db/config.php';
require_once 'db/db.php';
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$totalOrders = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];
$totalRevenue = $conn->query("SELECT SUM(total_amount) as total FROM orders")->fetch_assoc()['total'];

$recentOrders = $conn->query("
    SELECT o.id, o.total_amount, o.created_at, c.name AS customer_name
    FROM orders o
    LEFT JOIN customers c ON o.customer_id = c.id
    ORDER BY o.created_at DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style/sidebar.css">
    <style>
        .dashboard-container {
            margin-left: 220px;
            padding: 20px;
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
            min-height: 100vh;
        }

        .dashboard-container h2 {
            margin-bottom: 10px;
            color: #333;
        }

        .dashboard-container p {
            color: #666;
            margin-bottom: 20px;
        }

        .stats-container {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .stat-box {
            flex: 1;
            min-width: 200px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .stat-box:hover {
            transform: translateY(-5px);
        }

        .stat-box h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .stat-box p {
            font-size: 24px;
            font-weight: bold;
            color: #27ae60;
        }

        .recent-orders {
            margin-top: 40px;
        }

        .recent-orders h3 {
            color: #333;
            margin-bottom: 10px;
        }

        .recent-orders table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .recent-orders th,
        .recent-orders td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .recent-orders th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php require_once 'sidebar.php'; ?>

    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
        <p>This is the admin dashboard</p>

        <div class="stats-container">
            <div class="stat-box">
                <h3>Total Orders</h3>
                <p><?= $totalOrders ?></p>
            </div>
            <div class="stat-box">
                <h3>Total Revenue</h3>
                <p>$<?= number_format($totalRevenue, 2) ?></p>
            </div>
        </div>

        <div class="recent-orders">
            <h3>Last 5 Orders</h3>
            <table>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Date</th>
                </tr>
                <?php if ($recentOrders->num_rows > 0): ?>
                    <?php while($order = $recentOrders->fetch_assoc()): ?>
                        <tr>
                            <td><?= $order['id'] ?></td>
                            <td><?= $order['customer_name'] ?? 'N/A' ?></td>
                            <td>$<?= number_format($order['total_amount'], 2) ?></td>
                            <td><?= date("d M Y H:i", strtotime($order['created_at'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4">No recent orders.</td></tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</body>

</html>
