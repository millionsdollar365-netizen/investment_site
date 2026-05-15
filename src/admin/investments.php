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
    <title>Manage Investments - <?php echo SITE_NAME; ?> Admin</title>
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
        <h2 class="text-3xl font-bold mb-8">All Investments</h2>

        <div class="bg-white p-4 rounded shadow mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Status</label>
            <select id="statusFilter" onchange="loadInvestments()" class="px-4 py-2 border rounded">
                <option value="">All</option>
                <option value="active">Active</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <div id="investmentsList" class="bg-white rounded shadow overflow-hidden">
            <div class="p-6 text-center text-gray-500">Loading...</div>
        </div>

        <div id="pagination" class="flex justify-center gap-2 mt-6"></div>
    </div>

    <script>
        let currentPage = 1;

        async function loadInvestments(page = 1) {
            currentPage = page;
            const status = document.getElementById('statusFilter').value;
            const params = new URLSearchParams({ page, limit: 20 });
            if (status) params.set('status', status);

            const res = await fetch(`/api/admin/investments.php?${params}`);
            const data = await res.json();
            const container = document.getElementById('investmentsList');

            if (!data.success || !data.data.investments.length) {
                container.innerHTML = '<div class="p-6 text-center text-gray-500">No investments found.</div>';
                document.getElementById('pagination').innerHTML = '';
                return;
            }

            container.innerHTML = `
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">ID</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">User</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Plan</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Amount</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Daily ROI</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Total Profit</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Period</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.data.investments.map(inv => `
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-6 py-4">#${inv.id}</td>
                                <td class="px-6 py-4">${escHtml(inv.first_name + ' ' + inv.last_name)}</td>
                                <td class="px-6 py-4">${escHtml(inv.plan_name)}</td>
                                <td class="px-6 py-4 font-semibold">$${parseFloat(inv.amount).toFixed(2)}</td>
                                <td class="px-6 py-4">$${parseFloat(inv.daily_roi).toFixed(2)}</td>
                                <td class="px-6 py-4 text-green-600 font-semibold">$${parseFloat(inv.total_profit).toFixed(2)}</td>
                                <td class="px-6 py-4"><span class="px-2 py-1 rounded text-xs font-semibold ${inv.status === 'active' ? 'bg-green-100 text-green-800' : inv.status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800'}">${inv.status}</span></td>
                                <td class="px-6 py-4 text-sm">${inv.start_date}<br>to ${inv.end_date}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>`;

            const totalPages = Math.ceil(data.data.total / data.data.limit);
            let pagHtml = '';
            for (let i = 1; i <= totalPages; i++) {
                pagHtml += `<button onclick="loadInvestments(${i})" class="px-4 py-2 rounded ${i === currentPage ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-800'}">${i}</button>`;
            }
            document.getElementById('pagination').innerHTML = pagHtml;
        }

        function escHtml(s) { const d = document.createElement('div'); d.textContent = String(s); return d.innerHTML; }

        loadInvestments();
    </script>
</body>
</html>
