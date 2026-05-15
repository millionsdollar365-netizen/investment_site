<?php
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
    <title>Settings - <?php echo SITE_NAME; ?> Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-red-700 shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-white"><?php echo SITE_NAME; ?> Admin</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/admin/" class="text-white hover:text-gray-200">Dashboard</a>
                    <span class="text-white"><?php echo htmlspecialchars($admin['username']); ?></span>
                    <form action="/api/admin/logout.php" method="POST" style="display:inline"><button type="submit" class="bg-white text-red-600 px-4 py-2 rounded font-semibold">Logout</button></form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-3xl font-bold mb-8">Platform Settings</h2>

        <div id="settingsList" class="bg-white rounded shadow overflow-hidden">
            <div class="p-6 text-center text-gray-500">Loading...</div>
        </div>
    </div>

    <!-- Edit Setting Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded shadow-lg p-8 max-w-md w-full">
            <h3 class="text-2xl font-bold mb-4">Edit Setting</h3>
            <form id="editForm" class="space-y-4">
                <input type="hidden" name="key" id="editKey">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Key</label>
                    <input type="text" id="editKeyDisplay" disabled class="mt-1 w-full px-4 py-2 border rounded bg-gray-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Value</label>
                    <input type="text" name="value" id="editValue" required class="mt-1 w-full px-4 py-2 border rounded">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-red-600 text-white py-2 rounded font-semibold">Save</button>
                    <button type="button" onclick="hideEditModal()" class="flex-1 bg-gray-300 text-gray-800 py-2 rounded font-semibold">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        async function loadSettings() {
            const res = await fetch('/api/admin/settings.php');
            const data = await res.json();
            const container = document.getElementById('settingsList');

            if (!data.success || !data.data.settings.length) {
                container.innerHTML = '<div class="p-6 text-center text-gray-500">No settings found.</div>';
                return;
            }

            container.innerHTML = `
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Key</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Value</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.data.settings.map(s => `
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-6 py-4 font-mono text-sm">${escHtml(s.setting_key)}</td>
                                <td class="px-6 py-4">${escHtml(s.setting_value)}</td>
                                <td class="px-6 py-4">
                                    <button onclick="editSetting('${escHtml(s.setting_key)}', '${escHtml(s.setting_value).replace(/'/g, "\\'")}')" class="text-red-600 hover:underline font-semibold">Edit</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>`;
        }

        function editSetting(key, value) {
            document.getElementById('editKey').value = key;
            document.getElementById('editKeyDisplay').value = key;
            document.getElementById('editValue').value = value;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function hideEditModal() { document.getElementById('editModal').classList.add('hidden'); }

        document.getElementById('editForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const res = await fetch('/api/admin/settings.php', { method: 'POST', body: formData });
            const data = await res.json();
            alert(data.message);
            if (data.success) {
                hideEditModal();
                loadSettings();
            }
        });

        function escHtml(s) { const d = document.createElement('div'); d.textContent = String(s); return d.innerHTML; }

        loadSettings();
    </script>
</body>
</html>
