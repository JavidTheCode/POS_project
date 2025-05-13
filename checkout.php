<?php
require_once 'db/config.php';
require_once 'db/db.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty($_SESSION['cart'])) {
        echo "Your cart is empty. Cannot place an order.";
        exit();
    }
    $stmt = $conn->prepare("INSERT INTO orders (total_amount) VALUES (?)");
    $stmt->bind_param('d', $_POST['total']);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?,?,?)");
        $stmt->bind_param('iii', $order_id, $product_id, $quantity);
        $stmt->execute();
    }

    $_SESSION['cart'] = [];
    echo "Order placed successfully";
    echo "<a href='index.php'>Back to Shop</a>";
    exit();
}

$total = 0;
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $stmt = $conn->prepare("SELECT price FROM products  WHERE id = ?");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $total += $product['price'] * $quantity;
}
?>

<h2>Checkout</h2>
<p>Total: $<?= $total ?></p>
<form method="post">
    <input type="hidden" name="total" value="<?= $total ?>">
    <button type="submit">Place Order</button>
</form>