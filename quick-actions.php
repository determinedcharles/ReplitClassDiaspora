
<?php
session_start();
require_once 'includes/auth.php';

requireAuth();

$role = $_SESSION['user_role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Actions - ClassDiaspora</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .max-container { max-width: 680px; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php include 'includes/header.php'; ?>

    <div class="max-container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Quick Actions</h1>
            <p class="text-gray-600">Frequently used features and shortcuts</p>
        </div>

        <?php if ($role === 'teacher'): ?>
            <!-- Teacher Quick Actions -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <button onclick="window.location.href='curriculum.php'" class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition-shadow">
                    <i class="fas fa-upload text-3xl text-blue-600 mb-3"></i>
                    <h3 class="font-semibold text-gray-800">Upload Content</h3>
                    <p class="text-sm text-gray-600 mt-1">Add learning materials</p>
                </button>

                <button onclick="window.location.href='students.php'" class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition-shadow">
                    <i class="fas fa-users text-3xl text-green-600 mb-3"></i>
                    <h3 class="font-semibold text-gray-800">View Students</h3>
                    <p class="text-sm text-gray-600 mt-1">Manage your classes</p>
                </button>

                <button class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition-shadow">
                    <i class="fas fa-calendar text-3xl text-purple-600 mb-3"></i>
                    <h3 class="font-semibold text-gray-800">Schedule</h3>
                    <p class="text-sm text-gray-600 mt-1">Manage your time</p>
                </button>

                <button class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition-shadow">
                    <i class="fas fa-chart-bar text-3xl text-orange-600 mb-3"></i>
                    <h3 class="font-semibold text-gray-800">Analytics</h3>
                    <p class="text-sm text-gray-600 mt-1">Track progress</p>
                </button>

                <button onclick="window.location.href='messages.php'" class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition-shadow">
                    <i class="fas fa-comments text-3xl text-red-600 mb-3"></i>
                    <h3 class="font-semibold text-gray-800">Messages</h3>
                    <p class="text-sm text-gray-600 mt-1">Parent communication</p>
                </button>

                <button class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition-shadow">
                    <i class="fas fa-certificate text-3xl text-yellow-600 mb-3"></i>
                    <h3 class="font-semibold text-gray-800">Certificates</h3>
                    <p class="text-sm text-gray-600 mt-1">Award achievements</p>
                </button>
            </div>

        <?php else: ?>
            <!-- Parent Quick Actions -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <button onclick="window.location.href='browse-teachers.php'" class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition-shadow">
                    <i class="fas fa-search text-3xl text-blue-600 mb-3"></i>
                    <h3 class="font-semibold text-gray-800">Find Teachers</h3>
                    <p class="text-sm text-gray-600 mt-1">Browse qualified educators</p>
                </button>

                <button onclick="window.location.href='messages.php'" class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition-shadow">
                    <i class="fas fa-envelope text-3xl text-green-600 mb-3"></i>
                    <h3 class="font-semibold text-gray-800">Contact Request</h3>
                    <p class="text-sm text-gray-600 mt-1">Reach out to teachers</p>
                </button>

                <button onclick="window.location.href='curriculum.php'" class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition-shadow">
                    <i class="fas fa-book text-3xl text-purple-600 mb-3"></i>
                    <h3 class="font-semibold text-gray-800">Curriculum</h3>
                    <p class="text-sm text-gray-600 mt-1">View learning materials</p>
                </button>

                <button class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition-shadow">
                    <i class="fas fa-child text-3xl text-orange-600 mb-3"></i>
                    <h3 class="font-semibold text-gray-800">My Children</h3>
                    <p class="text-sm text-gray-600 mt-1">Manage profiles</p>
                </button>

                <button class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition-shadow">
                    <i class="fas fa-star text-3xl text-yellow-600 mb-3"></i>
                    <h3 class="font-semibold text-gray-800">Reviews</h3>
                    <p class="text-sm text-gray-600 mt-1">Rate teachers</p>
                </button>

                <button class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition-shadow">
                    <i class="fas fa-credit-card text-3xl text-red-600 mb-3"></i>
                    <h3 class="font-semibold text-gray-800">Payments</h3>
                    <p class="text-sm text-gray-600 mt-1">Manage transactions</p>
                </button>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
