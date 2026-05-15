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
    <title>Manage Users - <?php echo SITE_NAME; ?> Admin</title>
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
                    <a href="/api/admin/logout.php" class="bg-white text-red-600 px-4 py-2 rounded font-semibold">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-3xl font-bold mb-8">Manage Users</h2>

        <div class="bg-white p-4 rounded shadow mb-6 flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" id="searchInput" placeholder="Name or email..." class="w-full px-4 py-2 border rounded" onkeyup="debounceSearch()">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="statusFilter" onchange="loadUsers()" class="px-4 py-2 border rounded">
                    <option value="">All</option>
                    <option value="active">Active</option>
                    <option value="suspended">Suspended</option>
                    <option value="banned">Banned</option>
                </select>
            </div>
        </div>

        <div id="usersList" class="bg-white rounded shadow overflow-hidden">
            <div class="p-6 text-center text-gray-500">Loading...</div>
        </div>

        <div id="pagination" class="flex justify-center gap-2 mt-6"></div>
    </div>

    <!-- User Detail Modal -->
    <div id="detailModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded shadow-lg p-8 max-w-3xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold">User Detail</h3>
                <button onclick="hideDetailModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            <div id="detailContent">Loading...</div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded shadow-lg p-8 max-w-md w-full">
            <h3 class="text-2xl font-bold mb-4">Edit User</h3>
            <form id="editForm" class="space-y-4">
                <input type="hidden" name="id" id="editUserId">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="editStatus" class="mt-1 w-full px-4 py-2 border rounded">
                        <option value="active">Active</option>
                        <option value="suspended">Suspended</option>
                        <option value="banned">Banned</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Balance ($)</label>
                    <input type="number" name="balance" id="editBalance" step="0.01" class="mt-1 w-full px-4 py-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Interest Balance ($)</label>
                    <input type="number" name="interest_balance" id="editInterest" step="0.01" class="mt-1 w-full px-4 py-2 border rounded">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-red-600 text-white py-2 rounded font-semibold">Save</button>
                    <button type="button" onclick="hideEditModal()" class="flex-1 bg-gray-300 text-gray-800 py-2 rounded font-semibold">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let searchTimeout;

        function debounceSearch() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => loadUsers(), 300);
        }

        async function loadUsers(page = 1) {
            currentPage = page;
            const search = document.getElementById('searchInput').value;
            const status = document.getElementById('statusFilter').value;
            const params = new URLSearchParams({ page, limit: 20 });
            if (search) params.set('search', search);
            if (status) params.set('status', status);

            const res = await fetch(`/api/admin/users.php?${params}`);
            const data = await res.json();
            const container = document.getElementById('usersList');

            if (!data.success || !data.data.users.length) {
                container.innerHTML = '<div class="p-6 text-center text-gray-500">No users found.</div>';
                document.getElementById('pagination').innerHTML = '';
                return;
            }

            container.innerHTML = `
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">ID</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Balance</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">KYC</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Joined</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.data.users.map(u => `
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-6 py-4">#${u.id}</td>
                                <td class="px-6 py-4">${escHtml(u.first_name + ' ' + u.last_name)}</td>
                                <td class="px-6 py-4">${escHtml(u.email)}</td>
                                <td class="px-6 py-4">$${parseFloat(u.balance).toFixed(2)}</td>
                                <td class="px-6 py-4"><span class="px-2 py-1 rounded text-xs font-semibold ${u.status === 'active' ? 'bg-green-100 text-green-800' : u.status === 'suspended' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'}">${u.status}</span></td>
                                <td class="px-6 py-4"><span class="px-2 py-1 rounded text-xs ${u.kyc_status === 'verified' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">${u.kyc_status}</span></td>
                                <td class="px-6 py-4 text-sm">${u.created_at}</td>
                                <td class="px-6 py-4">
                                    <button onclick="viewUser(${u.id})" class="text-blue-600 hover:underline mr-2">View</button>
                                    <button onclick="editUser(${u.id}, '${u.status}', ${u.balance}, ${u.interest_balance})" class="text-red-600 hover:underline">Edit</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>`;

            const totalPages = Math.ceil(data.data.total / data.data.limit);
            let pagHtml = '';
            for (let i = 1; i <= totalPages; i++) {
                pagHtml += `<button onclick="loadUsers(${i})" class="px-4 py-2 rounded ${i === currentPage ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-800'}">${i}</button>`;
            }
            document.getElementById('pagination').innerHTML = pagHtml;
        }

        async function viewUser(id) {
            const res = await fetch(`/api/admin/user-detail.php?id=${id}`);
            const data = await res.json();
            const container = document.getElementById('detailContent');

            if (!data.success) {
                container.innerHTML = '<p class="text-red-600">Failed to load user.</p>';
            } else {
                const u = data.data.user;
                container.innerHTML = `
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div><span class="text-gray-600">Name:</span> ${escHtml(u.first_name + ' ' + u.last_name)}</div>
                        <div><span class="text-gray-600">Email:</span> ${escHtml(u.email)}</div>
                        <div><span class="text-gray-600">Balance:</span> $${parseFloat(u.balance).toFixed(2)}</div>
                        <div><span class="text-gray-600">Interest:</span> $${parseFloat(u.interest_balance).toFixed(2)}</div>
                        <div><span class="text-gray-600">Status:</span> ${u.status}</div>
                        <div><span class="text-gray-600">KYC:</span> ${u.kyc_status}</div>
                    </div>
                    <h4 class="font-bold mb-2">Investments (${data.data.investments.length})</h4>
                    ${data.data.investments.length ? `
                    <table class="w-full text-sm mb-4">
                        <thead class="bg-gray-50"><tr><th class="px-3 py-2 text-left">Plan</th><th class="px-3 py-2 text-left">Amount</th><th class="px-3 py-2 text-left">Profit</th><th class="px-3 py-2 text-left">Status</th></tr></thead>
                        <tbody>${data.data.investments.map(inv => `<tr class="border-t"><td class="px-3 py-2">${escHtml(inv.plan_name)}</td><td class="px-3 py-2">$${parseFloat(inv.amount).toFixed(2)}</td><td class="px-3 py-2">$${parseFloat(inv.total_profit).toFixed(2)}</td><td class="px-3 py-2">${inv.status}</td></tr>`).join('')}</tbody>
                    </table>` : '<p class="text-gray-500 mb-4">None</p>'}
                    <h4 class="font-bold mb-2">Deposits (${data.data.deposits.length})</h4>
                    ${data.data.deposits.length ? `
                    <table class="w-full text-sm mb-4">
                        <thead class="bg-gray-50"><tr><th class="px-3 py-2 text-left">Amount</th><th class="px-3 py-2 text-left">Method</th><th class="px-3 py-2 text-left">Status</th></tr></thead>
                        <tbody>${data.data.deposits.map(d => `<tr class="border-t"><td class="px-3 py-2">$${parseFloat(d.amount).toFixed(2)}</td><td class="px-3 py-2">${escHtml(d.payment_method)}</td><td class="px-3 py-2">${d.status}</td></tr>`).join('')}</tbody>
                    </table>` : '<p class="text-gray-500 mb-4">None</p>'}
                    <h4 class="font-bold mb-2">Withdrawals (${data.data.withdrawals.length})</h4>
                    ${data.data.withdrawals.length ? `
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50"><tr><th class="px-3 py-2 text-left">Amount</th><th class="px-3 py-2 text-left">Bank</th><th class="px-3 py-2 text-left">Status</th></tr></thead>
                        <tbody>${data.data.withdrawals.map(w => `<tr class="border-t"><td class="px-3 py-2">$${parseFloat(w.amount).toFixed(2)}</td><td class="px-3 py-2">${escHtml(w.bank_name)}</td><td class="px-3 py-2">${w.status}</td></tr>`).join('')}</tbody>
                    </table>` : '<p class="text-gray-500">None</p>'}
                `;
            }
            document.getElementById('detailModal').classList.remove('hidden');
        }

        function editUser(id, status, balance, interestBalance) {
            document.getElementById('editUserId').value = id;
            document.getElementById('editStatus').value = status;
            document.getElementById('editBalance').value = balance;
            document.getElementById('editInterest').value = interestBalance;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function hideDetailModal() { document.getElementById('detailModal').classList.add('hidden'); }
        function hideEditModal() { document.getElementById('editModal').classList.add('hidden'); }

        document.getElementById('editForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const res = await fetch('/api/admin/update-user.php', { method: 'POST', body: formData });
            const data = await res.json();
            alert(data.message);
            if (data.success) {
                hideEditModal();
                loadUsers(currentPage);
            }
        });

        function escHtml(s) { const d = document.createElement('div'); d.textContent = String(s); return d.innerHTML; }

        loadUsers();
    </script>
</body>
</html>
