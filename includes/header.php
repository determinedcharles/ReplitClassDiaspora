
<header class="bg-blue-900 text-white shadow-lg">
    <div class="max-container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <i class="fas fa-graduation-cap text-2xl text-yellow-400"></i>
                <h1 class="text-xl font-bold">ClassDiaspora</h1>
            </div>
            
            <div class="flex items-center space-x-4">
                <span class="text-sm">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <div class="relative">
                    <button onclick="toggleDropdown()" class="flex items-center space-x-2 bg-blue-800 hover:bg-blue-700 px-3 py-2 rounded-lg transition-colors">
                        <i class="fas fa-user"></i>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    
                    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border z-50">
                        <a href="dashboard.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 border-b">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                        <a href="profile.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 border-b">
                            <i class="fas fa-user mr-2"></i>Profile
                        </a>
                        <a href="quick-actions.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 border-b">
                            <i class="fas fa-bolt mr-2"></i>Quick Actions
                        </a>
                        <a href="logout.php" class="block px-4 py-2 text-red-600 hover:bg-red-50">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
function toggleDropdown() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
window.addEventListener('click', function(e) {
    const dropdown = document.getElementById('userDropdown');
    const button = e.target.closest('button[onclick="toggleDropdown()"]');
    
    if (!button && !dropdown.contains(e.target)) {
        dropdown.classList.add('hidden');
    }
});
</script>

<style>
.max-container { max-width: 680px; }
</style>
