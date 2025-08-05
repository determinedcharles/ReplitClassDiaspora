
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - ClassDiaspora</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .max-container { max-width: 680px; }
        .bg-primary { background-color: #1e3a8a; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-container mx-auto px-4 py-8">
        <div class="text-center mb-8" data-aos="fade-down">
            <i class="fas fa-graduation-cap text-5xl text-blue-900 mb-4"></i>
            <h1 class="text-3xl font-bold text-blue-900 mb-2">Welcome to ClassDiaspora</h1>
            <p class="text-gray-600">Choose your path to get started</p>
        </div>

        <div class="space-y-6" data-aos="fade-up" data-aos-delay="300">
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-900">
                <div class="flex items-center mb-4">
                    <i class="fas fa-chalkboard-teacher text-3xl text-blue-900 mr-4"></i>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">I'm a Teacher</h3>
                        <p class="text-gray-600">Share your expertise and connect with parents</p>
                    </div>
                </div>
                <button onclick="goToRegister('teacher')" class="w-full bg-blue-900 text-white py-3 rounded-lg hover:bg-blue-800 transition-colors">
                    Join as Teacher <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-600">
                <div class="flex items-center mb-4">
                    <i class="fas fa-users text-3xl text-green-600 mr-4"></i>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">I'm a Parent</h3>
                        <p class="text-gray-600">Find quality education for your children</p>
                    </div>
                </div>
                <button onclick="goToRegister('parent')" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors">
                    Join as Parent <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <div class="text-center mt-8" data-aos="fade-up" data-aos-delay="600">
            <p class="text-gray-600 mb-4">Already have an account?</p>
            <button onclick="window.location.href='login.php'" class="text-blue-900 border border-blue-900 px-6 py-2 rounded-lg hover:bg-blue-900 hover:text-white transition-colors">
                Sign In
            </button>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
        
        function goToRegister(role) {
            window.location.href = `register.php?role=${role}`;
        }
    </script>
</body>
</html>
