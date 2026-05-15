<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * Reset password (shell)
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/security.php';

requireLogout();

$token = isset($_GET['token']) ? trim($_GET['token']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set new password - <?php echo SITE_NAME; ?></title>
    <meta name="csrf-token" content="<?php echo Security::getCsrfToken(); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded shadow p-8">
            <h2 class="text-2xl font-bold text-center mb-6">New password</h2>

            <?php if ($token === ''): ?>
                <p class="text-gray-600 text-center mb-4">Invalid or missing reset link.</p>
                <p class="text-center"><a href="/forgot-password.php" class="text-blue-600">Request a new link</a></p>
            <?php else: ?>
            <form id="resetForm" class="space-y-4">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">New password</label>
                    <input type="password" id="password" name="password" required minlength="8" class="mt-1 w-full px-4 py-2 border rounded">
                </div>

                <div>
                    <label for="password_confirm" class="block text-sm font-medium text-gray-700">Confirm password</label>
                    <input type="password" id="password_confirm" name="password_confirm" required minlength="8" class="mt-1 w-full px-4 py-2 border rounded">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-semibold">Update password</button>
            </form>
            <?php endif; ?>

            <p class="text-center mt-4">
                <a href="/login.php" class="text-blue-600">Back to login</a>
            </p>
        </div>
    </div>

    <?php if ($token !== ''): ?>
    <script>
        document.getElementById('resetForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
            const response = await fetch('/api/auth/reset-password.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if (data.success) {
                alert(data.message);
                window.location.href = '/login.php';
            } else {
                alert(data.message);
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>
