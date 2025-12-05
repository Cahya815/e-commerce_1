<?php
session_start();
include '../../config/database.php';

// Pengecekan Otorisasi Admin
if (!isset($_SESSION["is_login"]) || $_SESSION["user_role"] !== 'admin') {
    header("Location: ../../public/login.php");
    exit();
}
if (!function_exists('connect_db')) {
    die("Error: Fungsi koneksi database (connect_db) tidak ditemukan.");
}

$conn = connect_db();
$errors = [];
$product = null;
$categories = [];
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// --- Ambil Kategori untuk Dropdown ---
$sql_categories = "SELECT id, name FROM categories ORDER BY name ASC";
$result_categories = mysqli_query($conn, $sql_categories);
if ($result_categories) {
    $categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
    mysqli_free_result($result_categories);
}

// --- FASE 1: Ambil Data Produk Lama (GET) ---
if ($product_id > 0 && $_SERVER["REQUEST_METHOD"] !== "POST") {
    $sql_get = "SELECT id, name, description, price, stock, category_id, image FROM products WHERE id = $product_id";
    $result_get = mysqli_query($conn, $sql_get);

    if ($result_get && mysqli_num_rows($result_get) === 1) {
        $product = mysqli_fetch_assoc($result_get);
        mysqli_free_result($result_get);
    } else {
        // Produk tidak ditemukan
        mysqli_close($conn);
        header("Location: index.php?status=not_found");
        exit();
    }
}
// --- FASE 2: Proses Update Data (POST) ---
elseif ($_SERVER["REQUEST_METHOD"] === "POST" && $product_id > 0) {
    // Ambil dan bersihkan data input (gunakan data dari POST)
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = (int)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $category_id = (int)$_POST['category_id'];
    $current_image = mysqli_real_escape_string($conn, $_POST['current_image']); // Ambil nama gambar lama
    $image_name = $current_image;

    // Re-assign data POST ke variabel $product untuk mengisi ulang form jika ada error
    $product = [
        'id' => $product_id,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'stock' => $stock,
        'category_id' => $category_id,
        'image' => $current_image // Sementara, sebelum upload
    ];

    // --- Validasi (Sama seperti create.php) ---
    if (empty($name) || $price <= 0 || $category_id === 0) {
        $errors[] = "Semua field wajib diisi dan harga harus valid.";
    }

    // TODO: Tambahkan logika upload gambar baru di sini (dan hapus gambar lama jika ada)

    // Jika tidak ada error validasi, update database
    if (empty($errors)) {
        // Query UPDATE
        $sql_update = "UPDATE products SET 
                       name = '$name', 
                       description = '$description', 
                       price = $price, 
                       stock = $stock, 
                       category_id = $category_id, 
                       image = '$image_name' 
                       WHERE id = $product_id";

        if (mysqli_query($conn, $sql_update)) {
            // Sukses: Redirect ke halaman index (PRG Pattern)
            mysqli_close($conn);
            header("Location: index.php?status=success_update");
            exit();
        } else {
            $errors[] = "Gagal memperbarui produk: " . mysqli_error($conn);
        }
    }
}
// Jika diakses tanpa ID
elseif ($product_id === 0) {
    header("Location: index.php");
    exit();
}

mysqli_close($conn);

include '../partials/admin_header.php';
?>

<div class="container">
    <h2>Edit Produk: <?php echo htmlspecialchars($product['name'] ?? 'ID ' . $product_id); ?></h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($product): ?>
        <form action="edit.php?id=<?php echo $product_id; ?>" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($product['image'] ?? ''); ?>">

            <div class="form-group">
                <label for="name">Nama Produk:</label>
                <input type="text" id="name" name="name" required
                    value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="category_id">Kategori:</label>
                <select id="category_id" name="category_id" required>
                    <option value="0">-- Pilih Kategori --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"
                            <?php echo ($product['category_id'] === $category['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi:</label>
                <textarea id="description" name="description"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="price">Harga (Rp):</label>
                <input type="number" id="price" name="price" required min="1"
                    value="<?php echo htmlspecialchars($product['price'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="stock">Stok:</label>
                <input type="number" id="stock" name="stock" required min="0"
                    value="<?php echo htmlspecialchars($product['stock'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label>Gambar Saat Ini:</label>
                <?php if (!empty($product['image'])): ?>
                    <img src="../../uploads/<?php echo htmlspecialchars($product['image']); ?>" width="100" class="d-block mb-2">
                <?php else: ?>
                    <p>[Gambar Tidak Ada]</p>
                <?php endif; ?>
                <label for="image">Ganti Gambar (opsional):</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    <?php endif; ?>
</div>

<?php
include '../partials/admin_footer.php';
?>