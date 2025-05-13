<?php
require_once '../db/config.php';
require_once '../db/db.php';

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();

header("Location:list.php");
exit();