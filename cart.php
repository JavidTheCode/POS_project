<?php
require_once 'db/config.php';
require_once 'db/db.php';

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['product_id'])) {
    $id = $_POST['product_id'];

    if (!isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = 1;
    } else {
        $_SESSION['cart'][$id]++;
    }

    header("Location: cart.php");
    exit();
}

if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="style/sidebar.css">
    <style>
        .dashboard-container {
            margin-left: 220px;
            padding: 20px;
            background-color: #f9f9f9;
            min-height: 100vh;
            font-family: Arial, sans-serif;
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        a.button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #2ecc71;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        a.button:hover {
            background-color: #27ae60;
        }

        .remove-link {
            color: red;
            text-decoration: none;
        }

        .remove-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php require_once 'sidebar.php'; ?>

<div class="dashboard-container">
    <h2>Cart</h2>

    <?php if (!empty($_SESSION['cart'])): ?>
        <table>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Action</th>
            </tr>

            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $productId => $quantity):
                $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->bind_param("i", $productId);
                $stmt->execute();
                $product = $stmt->get_result()->fetch_assoc();

                $subtotal = $product['price'] * $quantity;
                $total += $subtotal;
            ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= $quantity ?></td>
                    <td>$<?= number_format($subtotal, 2) ?></td>
                    <td><a class="remove-link" href="cart.php?remove=<?= $productId ?>">Remove</a></td>
                </tr>
            <?php endforeach ?>

            <tr>
                <td colspan="2"><strong>Total</strong></td>
                <td colspan="2"><strong>$<?= number_format($total, 2) ?></strong></td>
            </tr>
        </table>
        <a class="button" href="checkout.php">Proceed to Checkout</a>
    <?php else: ?>
        <p>Cart is empty</p>
    <?php endif; ?>

    <a class="button" href="menu.php">Back to Product List</a>
</div>

</body>
</html>
