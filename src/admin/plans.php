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
    <title>Investment Plans - <?php echo SITE_NAME; ?> Admin</title>
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-3xl font-bold mb-8">Investment Plans</h2>

        <div id="plansList" class="bg-white rounded shadow overflow-hidden">
            <div class="p-6 text-center text-gray-500">Loading...</div>
        </div>
    </div>

    <script>
        async function loadPlans() {
            const res = await fetch('/api/admin/plans.php');
            const data = await res.json();
            const container = document.getElementById('plansList');

            if (!data.success || !data.data.plans.length) {
                container.innerHTML = '<div class="p-6 text-center text-gray-500">No plans found.</div>';
                return;
            }

            container.innerHTML = `
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">ID</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Description</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Min Amount</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Max Amount</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Duration (Days)</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Daily ROI %</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.data.plans.map(p => `
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-6 py-4">#${p.id}</td>
                                <td class="px-6 py-4 font-semibold">${escHtml(p.name)}</td>
                                <td class="px-6 py-4 text-sm">${escHtml(p.description || '—')}</td>
                                <td class="px-6 py-4">$${parseFloat(p.min_amount).toLocaleString()}</td>
                                <td class="px-6 py-4">$${parseFloat(p.max_amount).toLocaleString()}</td>
                                <td class="px-6 py-4">${p.duration_days}</td>
                                <td class="px-6 py-4">${parseFloat(p.daily_roi).toFixed(2)}%</td>
                                <td class="px-6 py-4"><span class="px-2 py-1 rounded text-xs font-semibold ${p.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">${p.status}</span></td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>`;
        }

        function escHtml(s) { const d = document.createElement('div'); d.textContent = String(s); return d.innerHTML; }

        loadPlans();
    </script>
</body>
</html>
