<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * Admin Login Page
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/admin-session.php';
require_once __DIR__ . '/../includes/security.php';

requireAdminLogout();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?php echo SITE_NAME; ?></title>
    <meta name="csrf-token" content="<?php echo Security::getCsrfToken(); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/assets/js/app.js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded shadow p-8">
            <h2 class="text-2xl font-bold text-center mb-2">Admin Portal</h2>
            <p class="text-center text-gray-600 mb-6">Restricted Access Only</p>

            <form id="adminLoginForm" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" required class="mt-1 w-full px-4 py-2 border rounded">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" required class="mt-1 w-full px-4 py-2 border rounded">
                </div>

                <button type="submit" class="w-full bg-red-600 text-white py-2 rounded font-semibold">Login</button>
            </form>

            <p class="text-center mt-4 text-sm text-gray-600">
                <a href="/" class="text-blue-600 hover:underline">Back to Home</a>
            </p>
        </div>
    </div>

    <script>
        document.getElementById('adminLoginForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(e.target);
            formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
            const response = await fetch('/api/admin/login.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                window.location.href = '/admin/';
            } else {
                alert(data.message);
            }
        });
    </script>
</body>
</html>
