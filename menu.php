<?php
require_once '../config.php';
require_once '../db.php';

header('Content-Type: application/json');

$sql = "SELECT id, name, price FROM products";
$result = $conn->query($sql);

$menu = [];

while ($row = $result->fetch_assoc()) {
    $menu[] = $row;
}

echo json_encode($menu);
