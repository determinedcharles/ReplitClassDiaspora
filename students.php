
<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/helpers.php';

requireAuth();

$user = getCurrentUser();
$role = $_SESSION['user_role'];

// Redirect if not a teacher
if ($role !== 'teacher') {
    header('Location: dashboard.php');
    exit;
}

// Mock student data (in a real app, this would come from a database)
$students = [
    [
        'id' => 'student_001',
        'name' => 'Alice Johnson',
        'age' => 8,
        'grade' => '3rd Grade',
        'parent_name' => 'Sarah Johnson',
        'parent_email' => 'sarah@example.com',
        'subjects' => ['Mathematics', 'English'],
        'progress' => 85,
        'last_activity' => '2024-01-24 10:30:00',
        'status' => 'active'
    ],
    [
        'id' => 'student_002',
        'name' => 'Michael Chen',
        'age' => 9,
        'grade' => '4th Grade',
        'parent_name' => 'David Chen',
        'parent_email' => 'david@example.com',
        'subjects' => ['Mathematics', 'Science'],
        'progress' => 92,
        'last_activity' => '2024-01-23 14:15:00',
        'status' => 'active'
    ],
    [
        'id' => 'student_003',
        'name' => 'Emma Wilson',
        'age' => 7,
        'grade' => '2nd Grade',
        'parent_name' => 'Lisa Wilson',
        'parent_email' => 'lisa@example.com',
        'subjects' => ['English', 'Art'],
        'progress' => 78,
        'last_activity' => '2024-01-22 16:45:00',
        'status' => 'inactive'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Students - ClassDiaspora</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .max-container { max-width: 680px; }
        .progress-bar {
            background: linear-gradient(90deg, #10b981 var(--progress), #e5e7eb var(--progress));
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php include 'includes/header.php'; ?>

    <div class="max-container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6" data-aos="fade-up">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">My Students</h1>
                    <p class="text-gray-600">Manage and track your students' progress</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600"><?= count($students) ?></div>
                        <div class="text-sm text-gray-600">Total Students</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600"><?= count(array_filter($students, fn($s) => $s['status'] === 'active')) ?></div>
                        <div class="text-sm text-gray-600">Active</div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="flex flex-col md:flex-row gap-4 mb-6">
                <div class="flex-1">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchStudents" placeholder="Search students..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                </div>
                <div class="flex space-x-2">
                    <select id="gradeFilter" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                        <option value="">All Grades</option>
                        <option value="2nd Grade">2nd Grade</option>
                        <option value="3rd Grade">3rd Grade</option>
                        <option value="4th Grade">4th Grade</option>
                    </select>
                    <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Students Grid -->
        <div class="grid grid-cols-1 gap-6" id="studentsGrid">
            <?php foreach ($students as $student): ?>
                <div class="student-card bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow" 
                     data-aos="fade-up" 
                     data-grade="<?= $student['grade'] ?>" 
                     data-status="<?= $student['status'] ?>">
                    
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-lg">
                                <?= strtoupper(substr($student['name'], 0, 1)) ?>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($student['name']) ?></h3>
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span><i class="fas fa-birthday-cake mr-1"></i>Age <?= $student['age'] ?></span>
                                    <span><i class="fas fa-graduation-cap mr-1"></i><?= htmlspecialchars($student['grade']) ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <span class="<?= $student['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?> px-2 py-1 rounded-full text-xs font-semibold">
                                <i class="fas fa-circle <?= $student['status'] === 'active' ? 'text-green-500' : 'text-gray-500' ?> mr-1"></i>
                                <?= ucfirst($student['status']) ?>
                            </span>
                            <div class="relative">
                                <button onclick="toggleStudentMenu('<?= $student['id'] ?>')" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div id="menu_<?= $student['id'] ?>" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border z-10">
                                    <button onclick="viewStudentDetails('<?= $student['id'] ?>')" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">
                                        <i class="fas fa-eye mr-2"></i>View Details
                                    </button>
                                    <button onclick="contactParent('<?= htmlspecialchars($student['parent_email']) ?>', '<?= htmlspecialchars($student['parent_name']) ?>')" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">
                                        <i class="fas fa-envelope mr-2"></i>Contact Parent
                                    </button>
                                    <button onclick="assignHomework('<?= $student['id'] ?>')" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">
                                        <i class="fas fa-tasks mr-2"></i>Assign Homework
                                    </button>
                                    <hr class="my-1">
                                    <button onclick="removeStudent('<?= $student['id'] ?>')" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                                        <i class="fas fa-trash mr-2"></i>Remove Student
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Parent Info -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <h4 class="font-medium text-gray-800 mb-2">Parent Information</h4>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-700"><?= htmlspecialchars($student['parent_name']) ?></p>
                                <p class="text-sm text-gray-600"><?= htmlspecialchars($student['parent_email']) ?></p>
                            </div>
                            <button onclick="contactParent('<?= htmlspecialchars($student['parent_email']) ?>', '<?= htmlspecialchars($student['parent_name']) ?>')" 
                                    class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Subjects -->
                    <div class="mb-4">
                        <h4 class="font-medium text-gray-800 mb-2">Enrolled Subjects</h4>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($student['subjects'] as $subject): ?>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                    <?= htmlspecialchars($subject) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Progress -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-medium text-gray-800">Overall Progress</h4>
                            <span class="text-sm font-semibold text-gray-700"><?= $student['progress'] ?>%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="progress-bar h-3 rounded-full" style="--progress: <?= $student['progress'] ?>%; width: <?= $student['progress'] ?>%;"></div>
                        </div>
                    </div>

                    <!-- Last Activity -->
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2"></i>
                            Last active: <?= date('M j, Y g:i A', strtotime($student['last_activity'])) ?>
                        </div>
                        <button onclick="viewStudentDetails('<?= $student['id'] ?>')" class="text-blue-600 hover:text-blue-800 font-medium">
                            View Details <i class="fas fa-arrow-right ml-1"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($students)): ?>
            <div class="text-center py-12">
                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No Students Yet</h3>
                <p class="text-gray-500 mb-6">Start building your class by inviting students to join your courses.</p>
                <button onclick="inviteStudents()" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-user-plus mr-2"></i>Invite Students
                </button>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();

        // Search functionality
        document.getElementById('searchStudents').addEventListener('input', filterStudents);
        document.getElementById('gradeFilter').addEventListener('change', filterStudents);
        document.getElementById('statusFilter').addEventListener('change', filterStudents);

        function filterStudents() {
            const searchTerm = document.getElementById('searchStudents').value.toLowerCase();
            const gradeFilter = document.getElementById('gradeFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const studentCards = document.querySelectorAll('.student-card');

            studentCards.forEach(card => {
                const studentName = card.querySelector('h3').textContent.toLowerCase();
                const studentGrade = card.dataset.grade;
                const studentStatus = card.dataset.status;
                
                const matchesSearch = studentName.includes(searchTerm);
                const matchesGrade = !gradeFilter || studentGrade === gradeFilter;
                const matchesStatus = !statusFilter || studentStatus === statusFilter;
                
                if (matchesSearch && matchesGrade && matchesStatus) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function toggleStudentMenu(studentId) {
            const menu = document.getElementById(`menu_${studentId}`);
            // Close all other menus
            document.querySelectorAll('[id^="menu_"]').forEach(m => {
                if (m.id !== `menu_${studentId}`) {
                    m.classList.add('hidden');
                }
            });
            menu.classList.toggle('hidden');
        }

        // Close menus when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('[onclick*="toggleStudentMenu"]')) {
                document.querySelectorAll('[id^="menu_"]').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });

        function viewStudentDetails(studentId) {
            Swal.fire({
                title: 'Student Details',
                html: `
                    <div class="text-left space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold mb-2">Recent Activities</h4>
                            <ul class="space-y-2 text-sm">
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Completed Math Assignment #5</li>
                                <li class="flex items-center"><i class="fas fa-clock text-yellow-500 mr-2"></i>Started English Reading Exercise</li>
                                <li class="flex items-center"><i class="fas fa-star text-blue-500 mr-2"></i>Earned "Math Wizard" badge</li>
                            </ul>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold mb-2">Performance Summary</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between"><span>Assignments Completed:</span><span class="font-medium">8/10</span></div>
                                <div class="flex justify-between"><span>Average Score:</span><span class="font-medium">85%</span></div>
                                <div class="flex justify-between"><span>Attendance Rate:</span><span class="font-medium">92%</span></div>
                            </div>
                        </div>
                    </div>
                `,
                width: '600px',
                confirmButtonColor: '#2563eb'
            });
        }

        function contactParent(parentEmail, parentName) {
            Swal.fire({
                title: `Contact ${parentName}`,
                html: `
                    <div class="text-left space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <input type="text" id="parentMessageSubject" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Enter subject">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea id="parentMessageContent" rows="5" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Write your message to the parent..."></textarea>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Send Message',
                confirmButtonColor: '#2563eb',
                width: '600px',
                preConfirm: () => {
                    const subject = document.getElementById('parentMessageSubject').value;
                    const content = document.getElementById('parentMessageContent').value;
                    
                    if (!subject || !content) {
                        Swal.showValidationMessage('Please fill in all fields');
                        return false;
                    }
                    
                    return { subject, content };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Message Sent!',
                        text: `Your message has been sent to ${parentName}`,
                        icon: 'success',
                        confirmButtonColor: '#2563eb'
                    });
                }
            });
        }

        function assignHomework(studentId) {
            Swal.fire({
                title: 'Assign Homework',
                html: `
                    <div class="text-left space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <select id="homeworkSubject" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="">Select subject</option>
                                <option value="Mathematics">Mathematics</option>
                                <option value="English">English</option>
                                <option value="Science">Science</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Assignment Title</label>
                            <input type="text" id="homeworkTitle" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Enter assignment title">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Instructions</label>
                            <textarea id="homeworkInstructions" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Provide clear instructions for the assignment..."></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                            <input type="date" id="homeworkDueDate" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Assign Homework',
                confirmButtonColor: '#2563eb',
                width: '600px',
                preConfirm: () => {
                    const subject = document.getElementById('homeworkSubject').value;
                    const title = document.getElementById('homeworkTitle').value;
                    const instructions = document.getElementById('homeworkInstructions').value;
                    const dueDate = document.getElementById('homeworkDueDate').value;
                    
                    if (!subject || !title || !instructions || !dueDate) {
                        Swal.showValidationMessage('Please fill in all fields');
                        return false;
                    }
                    
                    return { subject, title, instructions, dueDate };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Homework Assigned!',
                        text: 'The homework has been assigned to the student',
                        icon: 'success',
                        confirmButtonColor: '#2563eb'
                    });
                }
            });
        }

        function removeStudent(studentId) {
            Swal.fire({
                title: 'Remove Student?',
                text: 'This will remove the student from your class. They can be re-added later.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Remove Student',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Student Removed',
                        text: 'The student has been removed from your class',
                        icon: 'success',
                        confirmButtonColor: '#2563eb'
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }

        function inviteStudents() {
            Swal.fire({
                title: 'Invite Students',
                html: `
                    <div class="text-left space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Invitation Method</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="inviteMethod" value="email" class="mr-2" checked>
                                    Send email invitations
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="inviteMethod" value="link" class="mr-2">
                                    Generate invitation link
                                </label>
                            </div>
                        </div>
                        
                        <div id="emailInvite">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Parent Email Addresses</label>
                            <textarea id="parentEmails" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Enter email addresses, one per line"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Personal Message</label>
                            <textarea id="inviteMessage" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Add a personal message to your invitation..."></textarea>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Send Invitations',
                confirmButtonColor: '#2563eb',
                width: '600px'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Invitations Sent!',
                        text: 'Your invitations have been sent to the parent email addresses',
                        icon: 'success',
                        confirmButtonColor: '#2563eb'
                    });
                }
            });
        }
    </script>
</body>
</html>
