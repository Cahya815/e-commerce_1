<?php
// 1. Pemuatan Konfigurasi & Koneksi
// Gunakan include_once untuk menghindari error jika file tidak ada
include '../config/database.php'; 
session_start();

// // Cek apakah fungsi koneksi tersedia
// if (!function_exists('mysqli_connect')) {
//     die("Error: Fungsi koneksi database (connect_db) tidak ditemukan.");
// }

// // Lakukan koneksi
// $conn = mysqli_connect();

// --- Logika Pengambilan Data Produk ---
$featured_products = [];
$error_message = '';

// Query untuk mengambil 8 produk terbaru atau unggulan
$sql = "SELECT id, name, price, image FROM products ORDER BY id DESC LIMIT 8";

$result = mysqli_query($conn, $sql);

if ($result) {
    // Cek apakah ada data yang ditemukan
    if (mysqli_num_rows($result) > 0) {
        $featured_products = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
    }
} else {
    // Catat error jika query gagal
    error_log("MySQLi Error fetching products: " . mysqli_error($conn));
    $error_message = "Terjadi kesalahan saat memuat daftar produk.";
}

// Tutup koneksi database setelah selesai mengambil data
mysqli_close($conn); 
?>

<?php 
// Asumsi file header.php ada di public/partials/
// Pastikan file ini ada
// include 'partials/header.php'; 
?>

    <main class="container">
        <section class="hero-banner">
            <h1>Selamat Datang di Toko Online Anda</h1>
            <p>Kualitas terjamin, harga bersaing. Siap melayani Anda 24/7.</p>
            <a href="product.php" class="btn btn-primary">Jelajahi Sekarang</a>
        </section>

        <hr>

        <section class="product-grid">
            <h2>🔥 Produk Pilihan</h2>
            
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php elseif (empty($featured_products)): ?>
                <div class="alert alert-info">Maaf, belum ada produk yang tersedia saat ini.</div>
            <?php else: ?>
                <div class="products-list">
                    <?php 
                    // Looping untuk menampilkan setiap produk
                    foreach ($featured_products as $product): 
                    ?>
                        <div class="product-card">
                            ); ?>] 
                            <a href="product.php?id=<?php echo $product['id']; ?>">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            </a>
                            <p class="price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                            <form action="cart.php" method="POST">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="hidden" name="qty" value="1">
                                <button type="submit" class="btn btn-secondary">Tambah ke Keranjang</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
        
        <hr>

        <section class="quick-categories">
            <h2>Cari Berdasarkan Kategori</h2>
            <ul>
                <li><a href="product.php?category=1">Fashion</a></li>
                <li><a href="product.php?category=2">Elektronik</a></li>
                <li><a href="product.php?category=3">Makanan</a></li>
            </ul>
        </section>
        
    </main>

<?php 
// Asumsi file footer.php ada di public/partials/
// Pastikan file ini ada
// include 'partials/footer.php'; 
?>