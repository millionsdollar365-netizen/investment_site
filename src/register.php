<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * User Registration Page
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/security.php';

requireLogout();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - <?php echo SITE_NAME; ?></title>
    <meta name="csrf-token" content="<?php echo Security::getCsrfToken(); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded shadow p-8">
            <h2 class="text-2xl font-bold text-center mb-6">Create Account</h2>

            <form id="registerForm" class="space-y-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" id="first_name" name="first_name" required class="mt-1 w-full px-4 py-2 border rounded">
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required class="mt-1 w-full px-4 py-2 border rounded">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" required class="mt-1 w-full px-4 py-2 border rounded">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" required class="mt-1 w-full px-4 py-2 border rounded">
                </div>

                <div>
                    <label for="referral_code" class="block text-sm font-medium text-gray-700">Referral Code (Optional)</label>
                    <input type="text" id="referral_code" name="referral_code" class="mt-1 w-full px-4 py-2 border rounded">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-semibold">Register</button>
            </form>

            <p class="text-center mt-4">
                Already have an account? <a href="/login.php" class="text-blue-600">Login here</a>
            </p>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(e.target);
            formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
            const response = await fetch('/api/auth/register.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert('Registration successful! Please login.');
                window.location.href = '/login.php';
            } else {
                alert(data.message);
            }
        });
    </script>
</body>
</html>
