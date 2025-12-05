<?php
include '../config/database.php';
session_start();
$login_message = "";
$status_type = "";

// PANGGIL FUNGSI connect_db() UNTUK MENDAPATKAN KONEKSI
$conn = connect_db();

// LOGIKA LOGIN HARUS DI ATAS SEMUA OUTPUT
if (isset($_POST["login"])) {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Gunakan prepared statement untuk keamanan (mencegah SQL Injection)
    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");

    if (!$stmt) {
        $login_message = "❌ Error: Database error - " . $conn->error;
        $status_type = "error";
    } else {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $data = $result->fetch_assoc();

            // Verifikasi password dengan password_verify (BCRYPT)
            if (password_verify($password, $data["password"])) {
                // SET VARIABEL SESI
                $_SESSION["user_id"] = $data["id"];
                $_SESSION["user_name"] = $data["name"];
                $_SESSION["user_role"] = $data["role"];
                $_SESSION["is_login"] = true;

                $stmt->close();
                $conn->close();

                // Redirect ke dashboard admin
                header("Location: dashboard.php");
                exit();
            } else {
                $login_message = "❌ Login Gagal. Email atau Password salah.";
                $status_type = "error";
            }
        } else {
            $login_message = "❌ Login Gagal. Email atau Password salah.";
            $status_type = "error";
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Online Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <h1 class="text-2xl font-bold text-gray-900">ShopHub</h1>
                <a href="register.php" class="text-indigo-600 hover:text-indigo-800 font-semibold">Daftar</a>
            </div>
        </div>
    </nav>

    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold mb-2 text-gray-900">Login to Your Account</h1>
            <p class="text-gray-600 mb-6">Welcome back! Please enter your details</p>

            <!-- Error Message -->
            <?php if (!empty($login_message)): ?>
                <div class="<?php echo $status_type === 'error' ? 'bg-red-100 border-red-400 text-red-700' : 'bg-green-100 border-green-400 text-green-700'; ?> 
                            mb-4 p-4 rounded-lg border">
                    <p class="text-sm font-medium"><?php echo htmlspecialchars($login_message); ?></p>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        placeholder="you@example.com"
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        placeholder="••••••••">
                </div>
                <button type="submit" name="login"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    Sign In
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">Dont have an account?
                    <a href="register.php" class="font-semibold text-indigo-600 hover:text-indigo-800">Sign Up</a>
                </p>
            </div>
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