<?php
require_once "../config.php";
require_once "../db.php";

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, stock = ?, WHERE id = ?");
    $stmt->bind_param('sdii', $name, $price, $stock, $id);
    if($stmt->execute()){
        header("Location:list.php");
        exit();
    }else{
        echo "Error updating product";
    }
}
?>
<h2>Edit Product</h2>
<form method="post">
    <label>Name:</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($product['name'])?>" required><br>
    <label>Price:</label><br>
    <input type="number" step="0.01" name="price" value="<?=$product['price']?>" required><br>
    <label>Stock</label><br>
    <input type="number" name="stock" value="<?=$product['stock'] ?>"><br><br>
    <button type="submit">Update Product</button>
</form>

<a href="list.php">Back to Product List</a>
