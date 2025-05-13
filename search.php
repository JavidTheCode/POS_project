<?php
require_once 'db/config.php';
require_once 'db/db.php';

$term = $_GET['q'] ?? '';
$stmt = $conn->prepare("SELECT * FROM  products WHERE name LIKE ?");
$likeTerm = '%' . $term . '%';
$stmt->bind_param('s', $likeTerm );
$stmt->execute();
$result = $stmt->get_result();

$products = [];

while($row = $result->fetch_assoc()){
    $products[] = $row; 
}

header('Content-Type: application/json');
echo json_encode($products);
