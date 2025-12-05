<?php
include '../config/database.php';
session_start();

// Pengecekan Otorisasi
if (!isset($_SESSION["is_login"]) || $_SESSION["user_role"] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Panggil koneksi untuk statistik (opsional)
$conn = connect_db();

// Logika Logout
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    if (isset($conn) && $conn) {
        $conn->close();
    }
    header("Location: login.php");
    exit();
}

$nama_user = $_SESSION["user_name"] ?? 'Administrator';

// Ambil statistik (opsional)
$total_products = 0;
$total_orders = 0;

// Query produk
$result_products = mysqli_query($conn, "SELECT COUNT(*) as count FROM products");
if ($result_products) {
    $row = mysqli_fetch_assoc($result_products);
    $total_products = $row['count'];
    mysqli_free_result($result_products);
}

// Query pesanan
$result_orders = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders");
if ($result_orders) {
    $row = mysqli_fetch_assoc($result_orders);
    $total_orders = $row['count'];
    mysqli_free_result($result_orders);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <h1 class="text-2xl font-bold text-gray-900">ShopHub Admin</h1>
                <form method="POST" class="inline">
                    <button type="submit" name="logout" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto mt-8 px-4">
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-3xl font-bold mb-2">Selamat Datang, <?php echo htmlspecialchars($nama_user); ?>! 👋</h2>
            <p class="text-gray-600">Anda login sebagai: <span class="font-semibold text-indigo-600">ADMIN</span></p>
        </div>

        <!-- Statistik Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700">Total Produk</h3>
                <p class="text-4xl font-bold text-blue-600 mt-2"><?php echo $total_products; ?></p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700">Total Pesanan</h3>
                <p class="text-4xl font-bold text-green-600 mt-2"><?php echo $total_orders; ?></p>
            </div>
        </div>

        <!-- Menu Utama -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="products/index.php" class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg p-6 transition">
                <h3 class="text-2xl font-bold mb-2">📦 Kelola Produk</h3>
                <p>Tambah, edit, atau hapus produk</p>
            </a>
            <a href="orders/index.php" class="bg-green-500 hover:bg-green-600 text-white rounded-lg p-6 transition">
                <h3 class="text-2xl font-bold mb-2">🛒 Kelola Pesanan</h3>
                <p>Lihat dan kelola pesanan pelanggan</p>
            </a>
        </div>
    </div>

    <?php
    // Tutup koneksi di akhir
    if (isset($conn) && $conn) {
        $conn->close();
    }
    ?>
</body>

</html>