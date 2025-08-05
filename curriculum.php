
<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/helpers.php';

requireAuth();

$user = getCurrentUser();
$role = $_SESSION['user_role'];

// Load curriculum from curriculum.json
$curriculum = loadJsonData('data/curriculum.json');

// Filter curriculum based on user role
if ($role === 'teacher') {
    $userCurriculum = array_filter($curriculum, function($item) use ($user) {
        return $item['teacher_id'] === $user['id'];
    });
} else {
    $userCurriculum = $curriculum; // Parents can view all curriculum
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $role === 'teacher' ? 'Manage' : 'View' ?> Curriculum - ClassDiaspora</title>
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
                    <h1 class="text-2xl font-bold text-gray-800">
                        <?= $role === 'teacher' ? 'Manage Curriculum' : 'Learning Materials' ?>
                    </h1>
                    <p class="text-gray-600">
                        <?= $role === 'teacher' ? 'Upload and organize your teaching materials' : 'Browse available learning resources' ?>
                    </p>
                </div>
                <?php if ($role === 'teacher'): ?>
                    <button onclick="uploadContent()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-upload mr-2"></i>Upload Content
                    </button>
                <?php endif; ?>
            </div>

            <!-- Filter Options -->
            <div class="flex flex-wrap gap-2 mb-6">
                <button class="filter-btn active bg-blue-600 text-white px-4 py-2 rounded-full text-sm" data-filter="all">
                    All Subjects
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
                <button class="filter-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-sm hover:bg-gray-300" data-filter="elementary">
                    Elementary
                </button>
            </div>
        </div>

        <!-- Curriculum Grid -->
        <div class="grid grid-cols-1 gap-6" id="curriculumGrid">
            <?php if (!empty($userCurriculum)): ?>
                <?php foreach ($userCurriculum as $item): ?>
                    <div class="curriculum-item bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow" data-aos="fade-up">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white mr-4">
                                        <i class="fas fa-book text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($item['title']) ?></h3>
                                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                                            <span><i class="fas fa-tag mr-1"></i><?= htmlspecialchars($item['subject']) ?></span>
                                            <span><i class="fas fa-graduation-cap mr-1"></i><?= htmlspecialchars($item['grade_level']) ?></span>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-gray-600 text-sm mb-4"><?= htmlspecialchars($item['description']) ?></p>
                            </div>
                            
                            <?php if ($role === 'teacher'): ?>
                                <div class="flex space-x-2">
                                    <button onclick="editCurriculum('<?= $item['id'] ?>')" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteCurriculum('<?= $item['id'] ?>')" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Materials -->
                        <div class="mb-4">
                            <h4 class="font-medium text-gray-800 mb-3">Learning Materials</h4>
                            <div class="space-y-2">
                                <?php foreach ($item['materials'] as $material): ?>
                                    <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                                        <div class="flex items-center">
                                            <?php if ($material['type'] === 'document'): ?>
                                                <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                                            <?php elseif ($material['type'] === 'video'): ?>
                                                <i class="fas fa-play-circle text-blue-500 mr-3"></i>
                                            <?php else: ?>
                                                <i class="fas fa-file text-gray-500 mr-3"></i>
                                            <?php endif; ?>
                                            <div>
                                                <p class="font-medium text-gray-800"><?= htmlspecialchars($material['title']) ?></p>
                                                <p class="text-sm text-gray-600"><?= ucfirst($material['type']) ?></p>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <?php if (isset($material['url'])): ?>
                                                <button onclick="window.open('<?= htmlspecialchars($material['url']) ?>', '_blank')" class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </button>
                                            <?php else: ?>
                                                <button onclick="downloadMaterial('<?= htmlspecialchars($material['filename']) ?>')" class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                Updated <?= date('M j, Y', strtotime($item['updated_at'])) ?>
                            </div>
                            <?php if ($role === 'parent'): ?>
                                <button onclick="enrollInCourse('<?= $item['id'] ?>')" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm">
                                    <i class="fas fa-plus mr-2"></i>Enroll Child
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-12">
                    <i class="fas fa-book-open text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">
                        <?= $role === 'teacher' ? 'No Content Yet' : 'No Learning Materials Available' ?>
                    </h3>
                    <p class="text-gray-500 mb-6">
                        <?= $role === 'teacher' ? 'Start by uploading your first teaching material.' : 'Check back later for new learning resources.' ?>
                    </p>
                    <?php if ($role === 'teacher'): ?>
                        <button onclick="uploadContent()" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-upload mr-2"></i>Upload Your First Content
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();

        // Filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => {
                    b.classList.remove('active', 'bg-blue-600', 'text-white');
                    b.classList.add('bg-gray-200', 'text-gray-700');
                });
                this.classList.add('active', 'bg-blue-600', 'text-white');
                this.classList.remove('bg-gray-200', 'text-gray-700');
            });
        });

        function uploadContent() {
            Swal.fire({
                title: 'Upload Learning Content',
                html: `
                    <div class="text-left space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input type="text" id="contentTitle" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Enter content title">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <select id="contentSubject" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="">Select subject</option>
                                <option value="Mathematics">Mathematics</option>
                                <option value="English">English</option>
                                <option value="Science">Science</option>
                                <option value="Social Studies">Social Studies</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Grade Level</label>
                            <select id="gradeLevel" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="">Select grade level</option>
                                <option value="Elementary">Elementary</option>
                                <option value="Middle School">Middle School</option>
                                <option value="High School">High School</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="contentDescription" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Describe the content..."></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload File</label>
                            <input type="file" id="contentFile" class="w-full border border-gray-300 rounded-lg px-3 py-2" accept=".pdf,.doc,.docx,.ppt,.pptx,.mp4,.mov">
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Upload Content',
                confirmButtonColor: '#2563eb',
                width: '600px',
                preConfirm: () => {
                    const title = document.getElementById('contentTitle').value;
                    const subject = document.getElementById('contentSubject').value;
                    const gradeLevel = document.getElementById('gradeLevel').value;
                    const description = document.getElementById('contentDescription').value;
                    const file = document.getElementById('contentFile').files[0];
                    
                    if (!title || !subject || !gradeLevel || !description) {
                        Swal.showValidationMessage('Please fill in all required fields');
                        return false;
                    }
                    
                    return { title, subject, gradeLevel, description, file };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Content Uploaded!',
                        text: 'Your learning content has been uploaded successfully',
                        icon: 'success',
                        confirmButtonColor: '#2563eb'
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }

        function editCurriculum(curriculumId) {
            Swal.fire({
                title: 'Edit Content',
                text: 'Content editing feature coming soon!',
                icon: 'info',
                confirmButtonColor: '#2563eb'
            });
        }

        function deleteCurriculum(curriculumId) {
            Swal.fire({
                title: 'Delete Content?',
                text: 'This action cannot be undone',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Content Deleted',
                        text: 'The content has been deleted',
                        icon: 'success',
                        confirmButtonColor: '#2563eb'
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }

        function downloadMaterial(filename) {
            Swal.fire({
                title: 'Download Starting',
                text: `Downloading ${filename}...`,
                icon: 'info',
                timer: 2000,
                showConfirmButton: false
            });
        }

        function enrollInCourse(courseId) {
            Swal.fire({
                title: 'Enroll Child',
                html: `
                    <div class="text-left space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Child</label>
                            <select id="childSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="">Choose a child</option>
                                <option value="child1">John Doe (Age 8)</option>
                                <option value="child2">Jane Doe (Age 10)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                            <textarea id="enrollmentNotes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Any specific requirements or notes..."></textarea>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Enroll Child',
                confirmButtonColor: '#16a34a',
                preConfirm: () => {
                    const child = document.getElementById('childSelect').value;
                    
                    if (!child) {
                        Swal.showValidationMessage('Please select a child');
                        return false;
                    }
                    
                    return { child };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Enrollment Successful!',
                        text: 'Your child has been enrolled in this course',
                        icon: 'success',
                        confirmButtonColor: '#2563eb'
                    });
                }
            });
        }
    </script>
</body>
</html>
