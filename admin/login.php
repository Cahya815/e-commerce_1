<?php
include '../config/database.php'; // Koneksi ke $conn
session_start();
$login_message = "";

// ----------------------------------------------------
// LOGIKA LOGIN HARUS DI ATAS SEMUA OUTPUT
// ----------------------------------------------------
if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Ambil SEMUA data user, termasuk role, name, dan password
    $sql = "SELECT id, name, email, password, role FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) { // Hanya boleh ada 1 hasil
        $data = $result->fetch_assoc();

        // SET VARIABEL SESI
        $_SESSION["user_id"] = $data["id"];
        $_SESSION["user_name"] = $data["name"];
        $_SESSION["user_role"] = $data["role"]; // Simpan role untuk otorisasi
        $_SESSION["is_login"] = true;

        $conn->close();
        header("Location: dashboard.php"); // Ganti dashboard.php
        exit(); // Penting: Hentikan eksekusi
    } else {
        $login_message = "Login Gagal. Email atau Password salah.";
    }
}
$conn->close(); // Tutup koneksi jika login gagal/atau saat halaman dimuat biasa
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
</head>

<body>
    <?php include "header.php" ?>
    <h2>LOGIN AKUN</h2>
    <i><?= $login_message ?></i>
    <form action="login.php" method="POST">
        <input type="email" placeholder="Email (Username)" name="email"><br>
        <input type="password" placeholder="Password" name="password"><br>
        <button type="submit" name="login">Login</button>
    </form>
</body>

</html>