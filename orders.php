<?php
require_once 'db/config.php';
require_once 'db/db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Orders</title>
    <link rel="stylesheet" href="style/sidebar.css">
    <style>
        .container {
            display: flex;
            min-height: 100vh;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
            box-sizing: border-box;
            margin-left: 230px;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px 12px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .order-header {
            background-color: #fff;
            border-left: 4px solid #4CAF50;
            padding: 10px 15px;
            margin: 20px 0 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            font-weight: bold;
            color: #333;
        }
    </style>
</head>

<body>

    <div class="container">

        <?php require_once 'sidebar.php'; ?>

        <div class="content">
            <h2>Placed Orders</h2>

            <?php
            $sql = "SELECT o.id AS order_id, o.total_amount, o.created_at, 
                           p.name AS product_name, p.price, oi.quantity,
                           c.name AS customer_name
                    FROM orders o
                    JOIN order_items oi ON o.id = oi.order_id
                    JOIN products p ON oi.product_id = p.id
                    LEFT JOIN customers c ON o.customer_id = c.id
                    ORDER BY o.created_at DESC";

            $result = $conn->query($sql);

            if ($result->num_rows > 0):
                $current_order_id = null;
                while ($row = $result->fetch_assoc()):
                    if ($current_order_id != $row['order_id']):
                        if ($current_order_id !== null) echo "</table>";
                        $current_order_id = $row['order_id'];

                        $customer_info = !empty($row['customer_name']) 
                            ? "Customer: {$row['customer_name']} | " 
                            : "";

                        echo "<div class='order-header'>{$customer_info}Date: {$row['created_at']} | Total: \${$row['total_amount']}</div>";
                        echo "<table>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                </tr>";
                    endif;
                    echo "<tr>
                            <td>{$row['product_name']}</td>
                            <td>\${$row['price']}</td>
                            <td>{$row['quantity']}</td>
                          </tr>";
                endwhile;
                echo "</table>";
            else:
                echo "<p>No orders placed yet.</p>";
            endif;
            ?>
        </div>
    </div>

</body>

</html>
