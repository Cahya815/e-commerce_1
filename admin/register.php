<?php
// ...existing code...
include '../config/database.php';
session_start();

$regis_message = "";
$status_type = "";

// PANGGIL FUNGSI connect_db() UNTUK MENDAPATKAN KONEKSI
$conn = connect_db();

// Jika koneksi gagal, connect_db() sudah die() — tapi cek tambahan
if (!$conn) {
    die("Error: Tidak dapat terhubung ke database.");
}

// LOGIKA REGISTER (HARUS DI ATAS SEMUA OUTPUT)
if (isset($_POST['register'])) {
    // Ambil input dan trim
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password_plain = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $role_input = trim($_POST['role'] ?? '');

    // Normalisasi role: hanya terima 'admin' atau 'user'
    $allowed_roles = ['admin', 'user'];
    $role = in_array($role_input, $allowed_roles, true) ? $role_input : 'user';

    // Validasi
    $errors = [];
    if ($name === '') {
        $errors[] = "Nama lengkap wajib diisi.";
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email tidak valid.";
    }
    if ($password_plain === '' || strlen($password_plain) < 6) {
        $errors[] = "Password minimal 6 karakter.";
    }
    if ($password_plain !== $password_confirm) {
        $errors[] = "Password dan konfirmasi tidak cocok.";
    }

    // Jika validasi gagal, set pesan
    if (!empty($errors)) {
        $regis_message = implode("<br>", $errors);
        $status_type = "error";
    } else {
        // Cek duplikat email menggunakan prepared statement
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        if (!$stmt_check) {
            $regis_message = "Error database: " . htmlspecialchars($conn->error);
            $status_type = "error";
        } else {
            $stmt_check->bind_param("s", $email);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                $regis_message = "Email sudah terdaftar.";
                $status_type = "error";
                $stmt_check->close();
            } else {
                $stmt_check->close();

                // Hash password
                $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

                // Insert user — pastikan kolom role di DB menerima 'admin' atau 'user'
                $stmt_ins = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
                if (!$stmt_ins) {
                    $regis_message = "Error database: " . htmlspecialchars($conn->error);
                    $status_type = "error";
                } else {
                    $stmt_ins->bind_param("ssss", $name, $email, $password_hashed, $role);

                    try {
                        $exec = $stmt_ins->execute();
                    } catch (mysqli_sql_exception $e) {
                        $exec = false;
                        $regis_message = "Error saat menyimpan user: " . htmlspecialchars($e->getMessage());
                        $status_type = "error";
                    }

                    if ($exec) {
                        // Berhasil registrasi
                        $_SESSION['status_message'] = "Registrasi berhasil. Silakan login.";
                        $_SESSION['status_type'] = "success";
                        $stmt_ins->close();
                        $conn->close();
                        header("Location: login.php");
                        exit();
                    } else {
                        if ($regis_message === "") {
                            $regis_message = "Gagal menyimpan data. Silakan coba lagi.";
                            $status_type = "error";
                        }
                        $stmt_ins->close();
                    }
                }
            }
        }
    }
}

// Tutup koneksi di akhir jika belum ditutup
// ...existing code...
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Online Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <h1 class="text-2xl font-bold text-gray-900">ShopHub</h1>
                <a href="login.php" class="text-indigo-600 hover:text-indigo-800 font-semibold">Kembali ke Login</a>
            </div>
        </div>
    </nav>

    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold mb-2 text-gray-900">Buat Akun Baru</h1>
            <p class="text-gray-600 mb-6">Daftar untuk mengakses admin panel</p>

            <?php if (!empty($regis_message)): ?>
                <div class="<?php echo ($status_type === 'success') ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'; ?> mb-4 p-4 rounded-lg border">
                    <p class="text-sm font-medium"><?php echo $regis_message; ?></p>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" id="name" name="name" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                        value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Tipe Akun</label>
                    <select id="role" name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="user" <?php echo (isset($_POST['role']) && $_POST['role'] === 'user') ? 'selected' : ''; ?>>User</option>
                        <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" required minlength="6"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="password_confirm" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <input type="password" id="password_confirm" name="password_confirm" required minlength="6"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <button type="submit" name="register"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg">
                    Daftar Sekarang
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">Sudah punya akun?
                    <a href="login.php" class="font-semibold text-indigo-600 hover:text-indigo-800">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>

    <?php
    if (isset($conn) && $conn) {
        $conn->close();
    }
    ?>
</body>

</html>
// ...existing code...