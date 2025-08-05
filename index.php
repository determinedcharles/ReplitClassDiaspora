
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClassDiaspora - Connecting Teachers & Parents</title>
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#1e3a8a">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .max-container { max-width: 680px; }
        .bg-primary { background-color: #1e3a8a; }
        .text-primary { color: #1e3a8a; }
        .border-primary { border-color: #1e3a8a; }
    </style>
</head>
<body class="bg-gradient-to-b from-blue-900 to-blue-800 min-h-screen">
    <div class="max-container mx-auto px-4 py-8 text-center text-white">
        <div data-aos="fade-up" data-aos-duration="1000">
            <i class="fas fa-graduation-cap text-6xl mb-6 text-yellow-400"></i>
            <h1 class="text-4xl font-bold mb-4">ClassDiaspora</h1>
            <p class="text-xl mb-8 opacity-90">Bridging the gap between Nigerian teachers and diaspora parents</p>
            
            <div class="space-y-4 mb-12">
                <div class="flex items-center justify-center space-x-3">
                    <i class="fas fa-chalkboard-teacher text-2xl text-yellow-400"></i>
                    <span class="text-lg">Connect with qualified teachers</span>
                </div>
                <div class="flex items-center justify-center space-x-3">
                    <i class="fas fa-users text-2xl text-yellow-400"></i>
                    <span class="text-lg">Build learning communities</span>
                </div>
                <div class="flex items-center justify-center space-x-3">
                    <i class="fas fa-globe text-2xl text-yellow-400"></i>
                    <span class="text-lg">Bridge educational distances</span>
                </div>
            </div>
            
            <button onclick="proceedToWelcome()" class="bg-yellow-400 text-blue-900 px-8 py-3 rounded-full text-lg font-semibold hover:bg-yellow-300 transition-colors duration-300">
                Get Started <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
        
        function proceedToWelcome() {
            setTimeout(() => {
                window.location.href = 'welcome.php';
            }, 500);
        }

        // PWA installation
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('sw.js');
        }
    </script>
</body>
</html>
