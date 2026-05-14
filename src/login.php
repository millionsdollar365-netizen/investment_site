<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * User Login Page
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';

requireLogout();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded shadow p-8">
            <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

            <form id="loginForm" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" required class="mt-1 w-full px-4 py-2 border rounded">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" required class="mt-1 w-full px-4 py-2 border rounded">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-semibold">Login</button>
            </form>

            <p class="text-center mt-2">
                <a href="/forgot-password.php" class="text-sm text-blue-600">Forgot password?</a>
            </p>

            <p class="text-center mt-4">
                Don't have an account? <a href="/register.php" class="text-blue-600">Register here</a>
            </p>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const response = await fetch('/api/auth/login.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                window.location.href = '/dashboard/';
            } else {
                alert(data.message);
            }
        });
    </script>
</body>
</html>
