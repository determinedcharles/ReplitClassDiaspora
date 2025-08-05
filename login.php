
<?php
session_start();
require_once 'includes/helpers.php';

$error = '';

if ($_POST) {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Email and password are required';
    } else {
        $users = loadJsonData('data/users.json');
        
        foreach ($users as $user) {
            if ($user['email'] === $email && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_name'] = $user['name'];
                
                header('Location: dashboard.php');
                exit;
            }
        }
        
        $error = 'Invalid email or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ClassDiaspora</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .max-container { max-width: 680px; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="text-center mb-6">
                <i class="fas fa-sign-in-alt text-4xl text-blue-900 mb-4"></i>
                <h1 class="text-2xl font-bold text-gray-800">Welcome Back</h1>
                <p class="text-gray-600">Sign in to your account</p>
            </div>

            <?php if ($error): ?>
                <script>
                    Swal.fire('Error', '<?= $error ?>', 'error');
                </script>
            <?php endif; ?>

            <form method="POST" id="loginForm">
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Email Address</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your email">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Password</label>
                        <input type="password" name="password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your password">
                    </div>

                    <button type="submit" class="w-full bg-blue-900 text-white py-3 rounded-lg font-semibold hover:bg-blue-800 transition-colors">
                        <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                    </button>
                </div>
            </form>

            <div class="text-center mt-6">
                <p class="text-gray-600">Don't have an account? 
                    <a href="welcome.php" class="text-blue-900 hover:underline">Join ClassDiaspora</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
