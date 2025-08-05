
<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/helpers.php';

requireAuth();

$user = getCurrentUser();
$role = $_SESSION['user_role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ClassDiaspora</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .max-container { max-width: 680px; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php include 'includes/header.php'; ?>

    <div class="max-container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6" data-aos="fade-up">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Welcome, <?= htmlspecialchars($user['name']) ?></h1>
                    <p class="text-gray-600"><?= ucfirst($role) ?> Dashboard</p>
                </div>
                <div class="text-right">
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                        <i class="fas fa-<?= $role === 'teacher' ? 'chalkboard-teacher' : 'users' ?> mr-1"></i>
                        <?= ucfirst($role) ?>
                    </span>
                </div>
            </div>
        </div>

        <?php if ($role === 'teacher'): ?>
            <!-- Teacher Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-users text-3xl text-blue-600 mr-4"></i>
                        <div>
                            <h3 class="text-xl font-semibold">Students</h3>
                            <p class="text-gray-600">Manage your students</p>
                        </div>
                    </div>
                    <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        View Students
                    </button>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-book text-3xl text-green-600 mr-4"></i>
                        <div>
                            <h3 class="text-xl font-semibold">Curriculum</h3>
                            <p class="text-gray-600">Upload learning materials</p>
                        </div>
                    </div>
                    <button class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition-colors">
                        Manage Content
                    </button>
                </div>
            </div>

        <?php else: ?>
            <!-- Parent Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-chalkboard-teacher text-3xl text-blue-600 mr-4"></i>
                        <div>
                            <h3 class="text-xl font-semibold">Find Teachers</h3>
                            <p class="text-gray-600">Connect with qualified educators</p>
                        </div>
                    </div>
                    <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Browse Teachers
                    </button>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-envelope text-3xl text-green-600 mr-4"></i>
                        <div>
                            <h3 class="text-xl font-semibold">Messages</h3>
                            <p class="text-gray-600">Communication hub</p>
                        </div>
                    </div>
                    <button class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition-colors">
                        View Messages
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-lg p-6" data-aos="fade-up" data-aos-delay="300">
            <h3 class="text-xl font-semibold mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <button onclick="window.location.href='profile.php'" class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg text-center transition-colors">
                    <i class="fas fa-user text-2xl text-gray-600 mb-2"></i>
                    <p class="text-sm font-semibold">Profile</p>
                </button>
                
                <button onclick="window.location.href='quick-actions.php'" class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg text-center transition-colors">
                    <i class="fas fa-cog text-2xl text-gray-600 mb-2"></i>
                    <p class="text-sm font-semibold">Settings</p>
                </button>
                
                <button class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg text-center transition-colors">
                    <i class="fas fa-bell text-2xl text-gray-600 mb-2"></i>
                    <p class="text-sm font-semibold">Notifications</p>
                </button>
                
                <button class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg text-center transition-colors">
                    <i class="fas fa-question-circle text-2xl text-gray-600 mb-2"></i>
                    <p class="text-sm font-semibold">Help</p>
                </button>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>
