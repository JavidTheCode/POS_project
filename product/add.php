<?php
require_once '../db/config.php';
require_once '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = trim($_POST['name']);
    $price = $_POST['price'];
    
    if (empty($name) || empty($price)) {
        echo "Name and Price are reqiured.";
    } else {
        $stmt = $conn->prepare("INSERT INTO products (name,price) VALUES (?,?)");
        $stmt->bind_param('sd', $name, $price);
        if ($stmt->execute()) {
            echo "Product added successfully";
        } else {
            echo "Error" . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/sidebar.css">
    <title>Document</title>
    <style>
        .add-container {
    max-width: 400px;
    margin: 50px auto; /* ortalayır */
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    font-family: Arial, sans-serif;
}

.add-container h2 {
    text-align: center;
    margin-bottom: 20px;
}

.add-container form {
    display: flex;
    flex-direction: column;
}

.add-container label {
    margin-top: 10px;
    margin-bottom: 5px;
    font-weight: bold;
}

.add-container input {
    padding: 8px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.add-container button {
    margin-top: 15px;
    padding: 10px;
    font-size: 16px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.add-container button:hover {
    background-color: #0056b3;
}

.add-container a {
    display: block;
    text-align: center;
    margin-top: 15px;
    color: #007BFF;
    text-decoration: none;
}

.add-container a:hover {
    text-decoration: underline;
}

    </style>

</head>

<body>
    <?php require_once '../sidebar.php' ?>
    <div class="add-container">
        <h2>Add Product</h2>

        <form method='post'>
            <label>Product Name:</label><br>
            <input type="text" name="name" required><br>
            <label>Price:</label><br>
            <input type="number" step="0.01" name="price" required><br>
            <button type="submit">Add Product</button>
        </form>

        <a href="../dashboard.php">Back to Dashboard</a>
    </div>
</body>

</html>