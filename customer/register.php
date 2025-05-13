<?php
require_once '../db/config.php';
require_once '../db/db.php';
include '../sidebar.php'; // Sidebar daxil edilir

$message = '';

// Müştəri əlavə et
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (empty($name)) {
        $message = 'Name is required';
    } else {
        $stmt = $conn->prepare('INSERT INTO customers (name, email, phone) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $name, $email, $phone);
        if ($stmt->execute()) {
            $message = "Customer added successfully";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

// Bütün müştəriləri al
$customers = $conn->query("SELECT * FROM customers ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Customer</title>
    <link rel="stylesheet" href="../style/sidebar.css"> <!-- Stil faylı -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .main-content {
            margin-left: 220px; /* sidebar genişliyi */
            padding: 20px;
            background-color: #f9f9f9;
            min-height: 100vh;
        }

        h2, h3 {
            color: #333;
        }

        form input[type="text"],
        form input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        .message {
            padding: 10px;
            margin-bottom: 15px;
            color: green;
        }
    </style>
</head>
<body>

<div class="main-content">
    <h2>Add Customer</h2>

    <?php if (!empty($message)): ?>
        <div class="message"><strong><?= $message ?></strong></div>
    <?php endif; ?>

    <form method="post">
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Email:</label>
        <input type="email" name="email">

        <label>Phone:</label>
        <input type="text" name="phone" required>

        <button type="submit">Add Customer</button>
    </form>

    <hr>

    <h3>Customer List</h3>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $customers->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <br>
    <a href="../dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>
