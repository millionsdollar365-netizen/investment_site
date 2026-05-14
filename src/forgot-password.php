<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * Forgot password (shell)
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
    <title>Forgot Password - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded shadow p-8">
            <h2 class="text-2xl font-bold text-center mb-6">Reset password</h2>

            <form id="forgotForm" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" required class="mt-1 w-full px-4 py-2 border rounded">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-semibold">Send reset link</button>
            </form>

            <p class="text-center mt-4">
                <a href="/login.php" class="text-blue-600">Back to login</a>
            </p>
        </div>
    </div>

    <script>
        document.getElementById('forgotForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const response = await fetch('/api/auth/forgot-password.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            alert(data.message || (data.success ? 'Check your email.' : 'Something went wrong.'));
        });
    </script>
</body>
</html>
