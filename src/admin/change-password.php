<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * Admin — Change Password
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/admin-session.php';

requireAdminLogin();

$admin = getCurrentAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-blue-600"><?php echo SITE_NAME; ?> - Admin</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/admin/" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                    <span class="text-gray-700"><?php echo htmlspecialchars($admin['username']); ?></span>
                    <form action="/api/admin/logout.php" method="POST" style="display:inline">
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-md mx-auto mt-16 bg-white rounded shadow p-8">
        <h2 class="text-2xl font-bold mb-6">Change Password</h2>

        <form id="changePasswordForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                <input type="password" name="current_password" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input type="password" name="new_password" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" minlength="8" required>
                <small class="text-gray-500">Minimum 8 characters</small>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="confirm_password" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" minlength="8" required>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700">Update Password</button>
                <a href="/admin/" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded font-semibold hover:bg-gray-400 text-center">Cancel</a>
            </div>
        </form>
    </div>

    <div id="alert-container"></div>

    <script src="/assets/js/app.js"></script>
    <script>
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = new URLSearchParams(formData);

            apiCall('/api/admin/change-password.php', 'POST', data)
                .then(response => {
                    showAlert('Password changed successfully!', 'success');
                    setTimeout(() => window.location.href = '/admin/', 2000);
                })
                .catch(error => {
                    showAlert(error, 'error');
                });
        });
    </script>
</body>
</html>
