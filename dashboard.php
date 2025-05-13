<?php
require_once 'db/config.php';
require_once 'db/db.php';
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$totalOrders = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];
$totalRevenue = $conn->query("SELECT SUM(total_amount) as total FROM orders")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style/sidebar.css">
    <link rel="stylesheet" href="style/dashboard.css">
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


    </div>

</body>

</html>