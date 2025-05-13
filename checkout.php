<?php
require_once 'db/config.php';
require_once 'db/db.php';

// Müştəriləri siyahıya al
$customers = $conn->query("SELECT id, name FROM customers")->fetch_all(MYSQLI_ASSOC);

// Sifariş POST edildikdə
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty($_SESSION['cart'])) {
        echo "<p style='color:red;'>Your cart is empty. Cannot place an order.</p>";
        exit();
    }

    $customer_id = $_POST['customer_id'];
    $total = $_POST['total'];

    // Sifarişi `orders` cədvəlinə yaz
    $stmt = $conn->prepare("INSERT INTO orders (customer_id, total_amount) VALUES (?, ?)");
    $stmt->bind_param('id', $customer_id, $total);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Sifariş məhsullarını yaz
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param('iii', $order_id, $product_id, $quantity);
        $stmt->execute();
    }

    // Səbəti təmizlə
    $_SESSION['cart'] = [];
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Order Completed</title>
        <link rel="stylesheet" href="style/sidebar.css">
        <style>
            body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; }
            .dashboard-container { margin-left: 220px; padding: 20px; }
            .success-message {
                background-color: #d4edda;
                padding: 20px;
                border: 1px solid #c3e6cb;
                border-radius: 5px;
                color: #155724;
            }
            .button {
                display: inline-block;
                margin-top: 15px;
                padding: 10px 20px;
                background-color: #28a745;
                color: white;
                text-decoration: none;
                border-radius: 4px;
            }
            .button:hover { background-color: #218838; }
        </style>
    </head>
    <body>
        <?php require_once 'sidebar.php'; ?>
        <div class="dashboard-container">
            <div class="success-message">
                <h2>Order Placed Successfully!</h2>
                <p>Your order ID is <strong>#<?= $order_id ?></strong>.</p>
                <a class="button" href="menu.php">Back to Shop</a>
            </div>
        </div>
    </body>
    </html>
<?php
    exit();
}

// Toplam qiyməti hesablamaq və səbətdəki məhsulları çəkmək
$total = 0;
$cart_items = [];

foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $item_total = $product['price'] * $quantity;
    $cart_items[] = [
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $quantity,
        'item_total' => $item_total
    ];
    $total += $item_total;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="style/sidebar.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #eef2f3; margin: 0; }
        .dashboard-container { margin-left: 220px; padding: 20px; }
        .checkout-box {
            background: #fff; padding: 30px; max-width: 600px;
            border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 { color: #333; }
        .total { font-size: 20px; margin-bottom: 20px; font-weight: bold; }
        .order-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .order-table th, .order-table td {
            padding: 10px; text-align: left; border-bottom: 1px solid #ddd;
        }
        .order-table th { background-color: #f2f2f2; }
        button {
            background-color: #007bff; color: #fff; padding: 10px 15px;
            border: none; border-radius: 5px; cursor: pointer;
        }
        button:hover { background-color: #0069d9; }
        .button-back {
            background-color: #dc3545; color: white; padding: 10px 15px;
            border: none; border-radius: 5px; cursor: pointer; margin-right: 10px;
        }
        .button-back:hover { background-color: #c82333; }
        select {
            padding: 8px; margin-bottom: 15px; width: 100%;
            border: 1px solid #ccc; border-radius: 4px;
        }
        label { font-weight: bold; display: block; margin-bottom: 5px; }
    </style>
</head>
<body>

<?php require_once 'sidebar.php'; ?>

<div class="dashboard-container">
    <div class="checkout-box">
        <h2>Checkout</h2>

        <!-- Customer seçimi -->
        <form method="post">
            <label for="customer_id">Select Customer:</label>
            <select name="customer_id" id="customer_id" required>
                <option value="">-- Select Customer --</option>
                <?php foreach ($customers as $cust): ?>
                    <option value="<?= $cust['id'] ?>">
                        <?= htmlspecialchars($cust['name']) ?> (ID: <?= $cust['id'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Sepetteki məhsullar -->
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>$<?= number_format($item['item_total'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p class="total">Total: $<?= number_format($total, 2) ?></p>
            <a href="cart.php" class="button-back">Back to Cart</a>

            <!-- Gizli input və sifarişi tamamlamaq düyməsi -->
            <input type="hidden" name="total" value="<?= $total ?>">
            <button type="submit">Place Order</button>
        </form>
    </div>
</div>
</body>
</html>
