<?php
require_once "../db/config.php";
require_once "../db/db.php";

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ? WHERE id = ?");
    $stmt->bind_param('sdi', $name, $price, $id);
    if ($stmt->execute()) {
        header("Location: list.php");
        exit();
    } else {
        echo "Error updating product: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../style/sidebar.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
        }

        .content {
            flex-grow: 1;
            padding: 40px;
            background-color: #f9f9f9;
        }

        .edit-container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .edit-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .edit-container label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

        .edit-container input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .edit-container button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .edit-container button:hover {
            background-color: #0056b3;
        }

        .edit-container a {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #007BFF;
        }

        .edit-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <?php require_once '../sidebar.php'; ?>

    <div class="content">
        <div class="edit-container">
            <h2>Edit Product</h2>
            <form method="post">
                <label>Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

                <label>Price:</label>
                <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>

                <button type="submit">Update Product</button>
            </form>
            <a href="list.php">Back to Product List</a>
        </div>
    </div>

</body>
</html>
