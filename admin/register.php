<?php
include '../config/database.php'; // Koneksi ke $conn
session_start();
$regis_message = "";

// ----------------------------------------------------
// LOGIKA REGISTER HARUS DI ATAS SEMUA OUTPUT
// ----------------------------------------------------
if (isset($_POST["register"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];



    try {
        // Query disesuaikan dengan kolom baru (name, email, password)
        $input = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

        if ($conn->query($input)) {
            $regis_message = "Registrasi Berhasil! Silakan Login.";
        } else {
            // Ini akan menangkap jika ada error unik/primary key
            $regis_message = "Registrasi Gagal. Email mungkin sudah terdaftar.";
        }
    } catch (mysqli_sql_exception $e) {
        // Ini jarang dibutuhkan jika logika di atas sudah baik, tapi tetap aman
        $regis_message = "Terjadi kesalahan database: " . $e->getMessage();
    }
}
$conn->close(); // Tutup koneksi setelah selesai berinteraksi dengan DB
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTER</title>
</head>

<body>
    <?php include "header.php" ?>
    <h2>REGISTER AKUN</h2>
    <i><?= $regis_message ?></i>
    <form action="register.php" method="POST">
        <input type="text" placeholder="Nama Lengkap" name="name"><br>
        <input type="email" placeholder="Email (Username)" name="email"><br>
        <input type="password" placeholder="Password" name="password"><br>
        <button type="submit" name="register">Register</button>
    </form>
</body>

</html>