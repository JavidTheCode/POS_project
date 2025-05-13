<?php
require_once 'db/config.php';
require_once 'db/db.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (empty($name)) {
        echo 'Name is required';
    } else {
        $stmt = $conn->prepare('INSERT INTO customers (name, email, phone) VALUES (?,?,?)');
        $stmt->bind_param('sss', $name, $email, $phone);
        if ($stmt->execute()) {
            echo "Customer added successfully";
        } else {
            echo "Error:" . $conn->error;
        }
    }
}
?>
<h2>Add customer</h2>
<form method ='post'>
    <label>Name:</label><br>
    <input type = 'text' name = 'name' required><br>
    <label>Email:</label><br>
    <input type = 'email' name = 'email'><br>
    <label>Phone:</label><br>
    <input type = 'text' name = 'phone' required><br><br>
    <button type="submit">Add customer</button>
</form>

<a href="../dashboard.php">Back to Dashboard</a>