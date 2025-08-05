
<?php
session_start();
require_once 'includes/helpers.php';

$role = $_GET['role'] ?? 'parent';
$error = '';
$success = '';

if ($_POST) {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = sanitizeInput($_POST['phone']);
    $location = sanitizeInput($_POST['location']);
    
    // Validation
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'All fields are required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        // Check if user exists
        $users = loadJsonData('data/users.json');
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                $error = 'Email already exists';
                break;
            }
        }
        
        if (!$error) {
            // Create new user
            $newUser = [
                'id' => uniqid(),
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => $role,
                'phone' => $phone,
                'location' => $location,
                'created_at' => date('Y-m-d H:i:s'),
                'profile_complete' => true
            ];
            
            $users[] = $newUser;
            saveJsonData('data/users.json', $users);
            
            $_SESSION['user_id'] = $newUser['id'];
            $_SESSION['user_role'] = $role;
            $_SESSION['user_name'] = $name;
            
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ClassDiaspora</title>
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
                <i class="fas fa-user-plus text-4xl text-blue-900 mb-4"></i>
                <h1 class="text-2xl font-bold text-gray-800">Join as <?= ucfirst($role) ?></h1>
                <p class="text-gray-600">Create your account to get started</p>
            </div>

            <?php if ($error): ?>
                <script>
                    Swal.fire('Error', '<?= $error ?>', 'error');
                </script>
            <?php endif; ?>

            <form method="POST" id="registerForm">
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Full Name</label>
                        <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your full name">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Email Address</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your email">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Phone Number</label>
                        <input type="tel" name="phone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your phone number">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Location</label>
                        <input type="text" name="location" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="City, Country">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Password</label>
                        <input type="password" name="password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Create a password">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Confirm Password</label>
                        <input type="password" name="confirm_password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Confirm your password">
                    </div>

                    <button type="submit" class="w-full bg-blue-900 text-white py-3 rounded-lg font-semibold hover:bg-blue-800 transition-colors">
                        <i class="fas fa-user-check mr-2"></i>Create Account
                    </button>
                </div>
            </form>

            <div class="text-center mt-6">
                <p class="text-gray-600">Already have an account? 
                    <a href="login.php" class="text-blue-900 hover:underline">Sign in here</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.querySelector('input[name="password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                Swal.fire('Error', 'Passwords do not match', 'error');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                Swal.fire('Error', 'Password must be at least 6 characters', 'error');
                return false;
            }
        });
    </script>
</body>
</html>
