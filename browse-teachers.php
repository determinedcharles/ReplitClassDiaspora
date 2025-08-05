
<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/helpers.php';

requireAuth();

$user = getCurrentUser();
$role = $_SESSION['user_role'];

// Load teachers from users.json
$users = loadJsonData('data/users.json');
$teachers = array_filter($users, function($user) {
    return $user['role'] === 'teacher';
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Teachers - ClassDiaspora</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <h1 class="text-2xl font-bold text-gray-800">Browse Teachers</h1>
                    <p class="text-gray-600">Find qualified educators for your children</p>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-search text-gray-400"></i>
                    <input type="text" id="searchTeachers" placeholder="Search teachers..." 
                           class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <!-- Filter Options -->
            <div class="flex flex-wrap gap-2 mb-6">
                <button class="filter-btn active bg-blue-600 text-white px-4 py-2 rounded-full text-sm" data-filter="all">
                    All Teachers
                </button>
                <button class="filter-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-sm hover:bg-gray-300" data-filter="mathematics">
                    Mathematics
                </button>
                <button class="filter-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-sm hover:bg-gray-300" data-filter="english">
                    English
                </button>
                <button class="filter-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-sm hover:bg-gray-300" data-filter="science">
                    Science
                </button>
            </div>
        </div>

        <!-- Teachers Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="teachersGrid">
            <?php foreach ($teachers as $teacher): ?>
                <div class="teacher-card bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow" data-aos="fade-up">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-lg">
                                <?= strtoupper(substr($teacher['name'], 0, 1)) ?>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($teacher['name']) ?></h3>
                                <p class="text-gray-600 text-sm"><?= htmlspecialchars($teacher['location']) ?></p>
                            </div>
                        </div>
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">
                            <i class="fas fa-circle text-green-500 mr-1"></i>Available
                        </span>
                    </div>

                    <div class="mb-4">
                        <p class="text-gray-700 text-sm mb-2">Specializes in Elementary Education</p>
                        <div class="flex flex-wrap gap-1">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Mathematics</span>
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">English</span>
                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Science</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <span class="font-semibold">4.8</span>
                            <span class="ml-1">(24 reviews)</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-users mr-1"></i>
                            <span>45 students</span>
                        </div>
                    </div>

                    <div class="flex space-x-2">
                        <button onclick="contactTeacher('<?= $teacher['id'] ?>', '<?= htmlspecialchars($teacher['name']) ?>')" 
                                class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-envelope mr-2"></i>Contact
                        </button>
                        <button onclick="viewProfile('<?= $teacher['id'] ?>')" 
                                class="bg-gray-200 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors">
                            <i class="fas fa-user mr-2"></i>Profile
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($teachers)): ?>
            <div class="text-center py-12">
                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No Teachers Found</h3>
                <p class="text-gray-500">No teachers are currently available. Please check back later.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();

        // Filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active button
                document.querySelectorAll('.filter-btn').forEach(b => {
                    b.classList.remove('active', 'bg-blue-600', 'text-white');
                    b.classList.add('bg-gray-200', 'text-gray-700');
                });
                this.classList.add('active', 'bg-blue-600', 'text-white');
                this.classList.remove('bg-gray-200', 'text-gray-700');
            });
        });

        // Search functionality
        document.getElementById('searchTeachers').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const teacherCards = document.querySelectorAll('.teacher-card');
            
            teacherCards.forEach(card => {
                const teacherName = card.querySelector('h3').textContent.toLowerCase();
                const teacherLocation = card.querySelector('.text-gray-600').textContent.toLowerCase();
                
                if (teacherName.includes(searchTerm) || teacherLocation.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        function contactTeacher(teacherId, teacherName) {
            Swal.fire({
                title: `Contact ${teacherName}`,
                html: `
                    <div class="text-left">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                        <input type="text" id="messageSubject" class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-4" placeholder="Enter subject">
                        
                        <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <textarea id="messageContent" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Write your message here..."></textarea>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Send Message',
                confirmButtonColor: '#2563eb',
                preConfirm: () => {
                    const subject = document.getElementById('messageSubject').value;
                    const content = document.getElementById('messageContent').value;
                    
                    if (!subject || !content) {
                        Swal.showValidationMessage('Please fill in all fields');
                        return false;
                    }
                    
                    return { subject, content };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Here you would typically send the message via AJAX
                    Swal.fire({
                        title: 'Message Sent!',
                        text: `Your message has been sent to ${teacherName}`,
                        icon: 'success',
                        confirmButtonColor: '#2563eb'
                    });
                }
            });
        }

        function viewProfile(teacherId) {
            Swal.fire({
                title: 'Teacher Profile',
                text: 'Profile viewing feature coming soon!',
                icon: 'info',
                confirmButtonColor: '#2563eb'
            });
        }
    </script>
</body>
</html>
