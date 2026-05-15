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
    <title>Manage Withdrawals - <?php echo SITE_NAME; ?> Admin</title>
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
        <h2 class="text-3xl font-bold mb-8">Manage Withdrawals</h2>

        <div class="bg-white p-4 rounded shadow mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Status</label>
            <select id="statusFilter" onchange="loadWithdrawals()" class="px-4 py-2 border rounded">
                <option value="">All</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>

        <div id="withdrawalsList" class="bg-white rounded shadow overflow-hidden">
            <div class="p-6 text-center text-gray-500">Loading...</div>
        </div>

        <div id="pagination" class="flex justify-center gap-2 mt-6"></div>
    </div>

    <!-- Reject Reason Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded shadow-lg p-8 max-w-md w-full">
            <h3 class="text-2xl font-bold mb-4">Reject Withdrawal</h3>
            <form id="rejectForm" class="space-y-4">
                <input type="hidden" name="id" id="rejectId">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Reason</label>
                    <textarea name="reason" required rows="3" class="mt-1 w-full px-4 py-2 border rounded" placeholder="Enter rejection reason..."></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-red-600 text-white py-2 rounded font-semibold">Reject & Refund</button>
                    <button type="button" onclick="hideRejectModal()" class="flex-1 bg-gray-300 text-gray-800 py-2 rounded font-semibold">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentPage = 1;

        async function loadWithdrawals(page = 1) {
            currentPage = page;
            const status = document.getElementById('statusFilter').value;
            const params = new URLSearchParams({ page, limit: 20 });
            if (status) params.set('status', status);

            const res = await fetch(`/api/admin/withdrawals.php?${params}`);
            const data = await res.json();
            const container = document.getElementById('withdrawalsList');

            if (!data.success || !data.data.withdrawals.length) {
                container.innerHTML = '<div class="p-6 text-center text-gray-500">No withdrawals found.</div>';
                document.getElementById('pagination').innerHTML = '';
                return;
            }

            container.innerHTML = `
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">ID</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">User</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Amount</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Bank</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Account #</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.data.withdrawals.map(w => `
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-6 py-4">#${w.id}</td>
                                <td class="px-6 py-4">${escHtml(w.first_name + ' ' + w.last_name)}<br><span class="text-xs text-gray-500">${escHtml(w.email)}</span></td>
                                <td class="px-6 py-4 font-semibold">$${parseFloat(w.amount).toFixed(2)}</td>
                                <td class="px-6 py-4">${escHtml(w.bank_name)}</td>
                                <td class="px-6 py-4">${escHtml(w.account_number)}<br><span class="text-xs text-gray-500">${escHtml(w.account_holder_name)}</span></td>
                                <td class="px-6 py-4"><span class="px-2 py-1 rounded text-xs font-semibold ${w.status === 'approved' ? 'bg-green-100 text-green-800' : w.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'}">${w.status}</span></td>
                                <td class="px-6 py-4 text-sm">${w.created_at}</td>
                                <td class="px-6 py-4">
                                    ${w.status === 'pending' ? `
                                        <button onclick="approveWithdrawal(${w.id})" class="text-green-600 hover:underline mr-2 font-semibold">Approve</button>
                                        <button onclick="showRejectModal(${w.id})" class="text-red-600 hover:underline font-semibold">Reject</button>
                                    ` : '<span class="text-gray-400 text-sm">—</span>'}
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>`;

            const totalPages = Math.ceil(data.data.total / data.data.limit);
            let pagHtml = '';
            for (let i = 1; i <= totalPages; i++) {
                pagHtml += `<button onclick="loadWithdrawals(${i})" class="px-4 py-2 rounded ${i === currentPage ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-800'}">${i}</button>`;
            }
            document.getElementById('pagination').innerHTML = pagHtml;
        }

        async function approveWithdrawal(id) {
            if (!confirm('Approve this withdrawal?')) return;
            const formData = new FormData();
            formData.set('id', id);
            const res = await fetch('/api/admin/approve-withdrawal.php', { method: 'POST', body: formData });
            const data = await res.json();
            alert(data.message);
            if (data.success) loadWithdrawals(currentPage);
        }

        function showRejectModal(id) {
            document.getElementById('rejectId').value = id;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function hideRejectModal() { document.getElementById('rejectModal').classList.add('hidden'); }

        document.getElementById('rejectForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const res = await fetch('/api/admin/reject-withdrawal.php', { method: 'POST', body: formData });
            const data = await res.json();
            alert(data.message);
            if (data.success) {
                hideRejectModal();
                loadWithdrawals(currentPage);
            }
        });

        function escHtml(s) { const d = document.createElement('div'); d.textContent = String(s); return d.innerHTML; }

        loadWithdrawals();
    </script>
</body>
</html>
