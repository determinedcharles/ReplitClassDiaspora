
<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/helpers.php';

requireAuth();

$user = getCurrentUser();
$success = '';
$error = '';

if ($_POST) {
    $name = sanitizeInput($_POST['name']);
    $phone = sanitizeInput($_POST['phone']);
    $location = sanitizeInput($_POST['location']);
    
    if (empty($name)) {
        $error = 'Name is required';
    } else {
        // Update user data
        $users = loadJsonData('data/users.json');
        
        for ($i = 0; $i < count($users); $i++) {
            if ($users[$i]['id'] === $_SESSION['user_id']) {
                $users[$i]['name'] = $name;
                $users[$i]['phone'] = $phone;
                $users[$i]['location'] = $location;
                break;
            }
        }
        
        saveJsonData('data/users.json', $users);
        $_SESSION['user_name'] = $name;
        $success = 'Profile updated successfully';
        $user = getCurrentUser(); // Refresh user data
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - ClassDiaspora</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .max-container { max-width: 680px; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php include 'includes/header.php'; ?>

    <div class="max-container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="text-center mb-6">
                <i class="fas fa-user-circle text-6xl text-blue-900 mb-4"></i>
                <h1 class="text-2xl font-bold text-gray-800">My Profile</h1>
                <p class="text-gray-600">Manage your account information</p>
            </div>

            <?php if ($success): ?>
                <script>
                    Swal.fire('Success', '<?= $success ?>', 'success');
                </script>
            <?php endif; ?>

            <?php if ($error): ?>
                <script>
                    Swal.fire('Error', '<?= $error ?>', 'error');
                </script>
            <?php endif; ?>

            <form method="POST">
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Full Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Email Address</label>
                        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-500">
                        <p class="text-sm text-gray-500 mt-1">Email cannot be changed</p>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Phone Number</label>
                        <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Location</label>
                        <input type="text" name="location" value="<?= htmlspecialchars($user['location'] ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Role</label>
                        <input type="text" value="<?= ucfirst($user['role']) ?>" disabled class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Member Since</label>
                        <input type="text" value="<?= date('F j, Y', strtotime($user['created_at'])) ?>" disabled class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-500">
                    </div>

                    <button type="submit" class="w-full bg-blue-900 text-white py-3 rounded-lg font-semibold hover:bg-blue-800 transition-colors">
                        <i class="fas fa-save mr-2"></i>Update Profile
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <button onclick="confirmPasswordChange()" class="w-full bg-gray-600 text-white py-3 rounded-lg font-semibold hover:bg-gray-700 transition-colors">
                    <i class="fas fa-key mr-2"></i>Change Password
                </button>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
        function confirmPasswordChange() {
            Swal.fire({
                title: 'Change Password',
                text: 'This feature will be available soon!',
                icon: 'info'
            });
        }
    </script>
</body>
</html>
