<?php
require_once 'db/config.php';
require_once 'db/db.php';

// Veritabanından ürünleri alıyoruz
$sql = "SELECT id, name, price FROM products";
$result = $conn->query($sql);

$menu = [];

while ($row = $result->fetch_assoc()) {
    $menu[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Menu</title>
    <link rel="stylesheet" href="style/sidebar.css">
    <style>
        .dashboard-container {
            margin-left: 220px;
            padding: 20px;
            background-color: #f9f9f9;
            min-height: 100vh;
            font-family: Arial, sans-serif;
        }

        .dashboard-container .header {
            margin-bottom: 20px;
        }

        .dashboard-container h1 {
            color: #333;
            margin-bottom: 5px;
        }

        .dashboard-container p {
            color: #666;
            margin-bottom: 20px;
        }

        .product-card {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            width: 200px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .card h3 {
            font-size: 18px;
            color: #333;
            margin: 10px 0 5px 0;
        }

        .card .price {
            color: green;
            font-weight: bold;
            margin: 5px 0 15px 0;
        }

        .card button {
            padding: 8px 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .card button:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>
    <?php require_once 'sidebar.php'; ?>

    <div class="dashboard-container">
        <div class="header">
            <h1>Our Product Menu</h1>
            <p>Choose from our wide range of products</p>
        </div>

        <div class="product-card">
            <?php foreach ($menu as $product) : ?>
                <div class="card">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="price">$<?= number_format($product['price'], 2) ?></p>
                    <button>Add to Cart</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>

</html>
