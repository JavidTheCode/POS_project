<?php
session_start();

if(isset($_SESSION['username'])){
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <?php if(isset($_GET['error'])) :?>
        <p style: = "color:red;"> ]<?php echo $_GET['error'] ?></p>
    <?php endif;?>

    <form action="auth/login_process.php" method="post">
        <label>Username:</label><br>
        <input type="text" name="username" required ><br>
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>

    </form>
    
</body>
</html>

