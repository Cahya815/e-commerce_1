<?php
include '../config/database.php';
$regis_message = "";
session_start();
try {
    if (isset($_POST["register"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $hash_password = hash("sha256", $password);

        $input = "INSERT INTO users (username, password) VALUES ('$username', '$hash_password')";
        if ($conn->query($input)) {
            $regis_message = "Registrasi Berhasil";
        } else {
            $regis_message = "Registrasi Gagal: ";
        }
    }
} catch (mysqli_sql_exception) {
    $regis_message = "username habis ";
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
    <h2>REGISTER AKUN</h2>
    <i><?= $regis_message ?></i>
    <form action="register.php" method="POST">
        <input type="text" placeholder="username" name="username"><br>
        <input type="password" placeholder="password" name="password"><br>
        <button type="submit" name="register">Register</button>
    </form>
</body>

</html>