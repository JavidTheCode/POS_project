<?php
require_once 'config.php';
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['product_id'])) {
    $id = $_POST['product_id'];

    if (!isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = 1;
    } else {
        $_SESSION['cart'][$id]++;
    }

    header("Location:cart.php");
    exit();
}

if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}
?>

<h2>Cart</h2>
<?php if (!empty($_SESSION['cart'])): ?>
    <table border="1">
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
                <td><?= $product['name']; ?></td>
                <td><?= $quantity ?></td>
                <td><?= $subtotal ?></td>
                <td><a href="cart.php?remove=productId">Remove</a></td>
            </tr>
        <?php endforeach ?>

        <tr>
            <td colspan="2" <strong>Total</strong>></td>
            <td colspan="2"><?= $total ?></td>
        </tr>
    </table>
    <a href="checkout.php">Proceed to Checkout</a>
<?php else:
    echo "Cart is empty <br>";
endif;
?>
<a href="index.php">Back to Product List</a>