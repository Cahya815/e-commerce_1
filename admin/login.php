<?php
include '../config/database.php';
session_start();
$login_message = "";

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $hash_password = hash("sha256", $password);
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$hash_password'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $_SESSION["username"] = $data["username"];
        $_SESSION["is_login"] = true;
        header("Location: dashboard.php");
    } else {
        $login_message = "Login Gagal: ";
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Belajar PHP</title>
</head>

<body>
    <?php include "header.php" ?>
    <h2 " >LOGIN AKUN</h2>
    <i><?= $login_message ?></i>
    <form action=" login.php" method="POST">
        <input type="text" placeholder="username" name="username"><br>
        <input type="password" placeholder="password" name="password"><br>
        <button type="submit" name="login">Login</button>
        </form>
</body>

</html>