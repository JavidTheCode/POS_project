<?php
require_once "../config.php";
require_once "../db.php";

$result = $conn->query("SELECT * FROM products");
?>

<h2>Product List</h2>
<a href="add.php">Add New Product</a>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Stock</th>
    </tr>

    <?php while($row = $result->fetch_assoc()):?>
        <tr>
            <td><?=$row['id']?></td>
            <td><?=htmlspecialchars($row['name'])?></td>
            <td><?=$row['price']?></td>
            <td><?=$row['stock']?></td>
            <td>
                <a href="edit.php?id=<?=$row['id']?>">Edit</a> |
                <a href="delete.php?id=<?=$row['id']?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile ?>
</table>

<a href="../dashboard.php">Back to Dashboard</a>
