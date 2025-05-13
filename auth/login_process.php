<?php

require_once '../config.php';
require_once '../db.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if ($password == $user['password']) {
        $_SESSION['username'] = $user['username'];
        header("Location: ../dashboard.php");
        exit();
    } else {
        header("Location: ../login.php?error=Invalid Password");
        exit();
    }
} else {
    header("Location: ../login.php?error=User not found");
    exit();
}
