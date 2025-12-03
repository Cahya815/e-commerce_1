<?php
session_start();

// ----------------------------------------------------
// LOGIKA OTORISASI DAN LOGOUT HARUS DI ATAS SEMUA OUTPUT
// ----------------------------------------------------

// 1. Pengecekan Otorisasi: Jika sesi tidak lengkap, redirect ke login
if (!isset($_SESSION["is_login"]) || $_SESSION["is_login"] !== true) {
    header("Location: login.php");
    exit();
}

// 2. Logika Logout
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
// ----------------------------------------------------
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>

<body>
    <?php include "header.php" ?>
    <h3>Selamat datang, <?= $_SESSION["user_name"] ?> (Role: <?= $_SESSION["user_role"] ?>)</h3>

    <p>Ini adalah halaman yang hanya bisa diakses oleh pengguna yang sudah login.</p>

    <form action="dashboard.php" method="POST">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>

</html>