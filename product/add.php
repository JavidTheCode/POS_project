<?php
require_once '../config.php';
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = trim($_POST['name']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    if (empty($name) || empty($price)) {
        echo "Name and Price are reqiured.";
    } else {
        $stmt = $conn->prepare("INSERT INTO products (name,price,stock) VALUES (?,?,?)");
        $stmt->bind_param('sdi', $name, $price, $stock);
        if ($stmt->execute()) {
            echo "Product added successfully";
        } else {
            echo "Error" . $conn->error;
        }
    }
}
?>
<h2>Add Product</h2>

<form method='post'>
    <label>Product Name:</label><br>
    <input type="text" name="name" required><br>
    <label>Price:</label><br>
    <input type="number" step="0.01" name="price" required><br>
    <label>Stock:</label><br>
    <input type="number" name="stock" value="0"><br>
    <button type="submit">Add Product</button>
</form>

<a href="../dashboard.php">Back to Dashboard</a>