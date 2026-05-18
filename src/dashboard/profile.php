<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * User Dashboard — Profile
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin();

$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/assets/js/app.js"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-blue-600"><?php echo SITE_NAME; ?></h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/dashboard/" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                    <span class="text-gray-700"><?php echo htmlspecialchars($user['first_name']); ?></span>
                    <form action="/api/auth/logout.php" method="POST" style="display:inline"><button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Logout</button></form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-3xl font-bold mb-8">Profile</h2>

        <div class="bg-white p-6 rounded shadow mb-6">
            <h3 class="text-xl font-bold mb-4">Personal Information</h3>
            <form id="profileForm" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" required value="<?php echo htmlspecialchars($user['first_name']); ?>" class="mt-1 w-full px-4 py-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" required value="<?php echo htmlspecialchars($user['last_name']); ?>" class="mt-1 w-full px-4 py-2 border rounded">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" disabled value="<?php echo htmlspecialchars($user['email']); ?>" class="mt-1 w-full px-4 py-2 border rounded bg-gray-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" class="mt-1 w-full px-4 py-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Bio</label>
                    <textarea name="bio" rows="3" class="mt-1 w-full px-4 py-2 border rounded"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded font-semibold">Update Profile</button>
            </form>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-xl font-bold mb-4">Account Info</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-600">Referral Code:</span> <span class="font-semibold"><?php echo htmlspecialchars($user['referral_code']); ?></span></div>
                <div><span class="text-gray-600">Status:</span> <span class="font-semibold"><?php echo htmlspecialchars($user['status']); ?></span></div>
                <div><span class="text-gray-600">Joined:</span> <span class="font-semibold"><?php echo htmlspecialchars($user['created_at']); ?></span></div>
                <div><span class="text-gray-600">KYC Status:</span> <span class="font-semibold"><?php echo htmlspecialchars($user['kyc_status']); ?></span></div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('profileForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const res = await fetch('/api/user/update-profile.php', { method: 'POST', body: formData });
            const data = await res.json();
            alert(data.message);
        });
    </script>
</body>
</html>
