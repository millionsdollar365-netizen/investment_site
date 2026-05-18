<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * User Dashboard — Deposits
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
    <title>My Deposits - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/assets/css/app.css">
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
                    <form action="/api/auth/logout.php" method="POST" style="display:inline">
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold">My Deposits</h2>
            <button onclick="showCreateModal()" class="bg-green-600 text-white px-6 py-2 rounded font-semibold">New Deposit</button>
        </div>

        <div id="depositsList" class="bg-white rounded shadow overflow-hidden">
            <div class="p-6 text-center text-gray-500">Loading...</div>
        </div>
    </div>

    <!-- Create Deposit Modal -->
    <div id="createModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-40">
        <div class="bg-white rounded shadow-lg p-8 max-w-md w-full mx-4">
            <h3 class="text-2xl font-bold mb-4">New Deposit</h3>
            <form id="depositForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount (USD)</label>
                    <input type="number" name="amount" step="0.01" min="0.01" required 
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cryptocurrency</label>
                    <select name="payment_method" required 
                            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select cryptocurrency...</option>
                        <option value="btc">Bitcoin (BTC)</option>
                        <option value="usdt">USDT (Tether)</option>
                        <option value="ethereum">Ethereum (ETH)</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded font-semibold hover:bg-blue-700">Continue</button>
                    <button type="button" onclick="hideCreateModal()" class="flex-1 bg-gray-300 text-gray-800 py-2 rounded font-semibold hover:bg-gray-400">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Wallet Address Modal -->
    <div id="walletModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded shadow-lg p-8 max-w-md w-full mx-4">
            <h3 class="text-2xl font-bold mb-4 text-green-600">✓ Deposit Request Created</h3>
            
            <div class="space-y-4 mb-6">
                <div class="bg-blue-50 border-l-4 border-blue-600 p-4">
                    <p class="text-sm text-gray-600 mb-1">Please send exactly:</p>
                    <p class="text-2xl font-bold text-gray-900" id="walletAmount">$0.00</p>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded p-4">
                    <p class="text-xs text-gray-600 mb-2 uppercase font-semibold">Wallet Address</p>
                    <div class="flex items-center justify-between">
                        <code class="text-xs font-mono break-all text-gray-900" id="walletAddress">Loading...</code>
                        <button type="button" onclick="copyWallet()" class="ml-2 text-blue-600 hover:text-blue-800 font-semibold text-sm flex-shrink-0">Copy</button>
                    </div>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4">
                    <p class="text-sm text-gray-700">
                        <strong>Important:</strong> Send exactly the amount shown above. Your deposit will be credited once payment is confirmed.
                    </p>
                </div>

                <div class="bg-gray-50 p-3 rounded">
                    <p class="text-xs text-gray-600">Reference ID: <strong id="walletRef">-</strong></p>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="button" onclick="hideWalletModal()" class="flex-1 bg-gray-300 text-gray-800 py-2 rounded font-semibold hover:bg-gray-400">Done</button>
                <button type="button" onclick="copyWallet()" class="flex-1 bg-blue-600 text-white py-2 rounded font-semibold hover:bg-blue-700">Copy Address</button>
            </div>
        </div>
    </div>

    <script src="/assets/js/app.js"></script>
    <script>
        let currentWalletData = null;

        async function loadDeposits() {
            try {
                const response = await apiCall('/api/deposits/list.php', 'GET');
                const container = document.getElementById('depositsList');

                if (!response.deposits || response.deposits.length === 0) {
                    container.innerHTML = '<div class="p-6 text-center text-gray-500">No deposits yet.</div>';
                    return;
                }

                const cryptoLabels = {
                    'btc': 'Bitcoin',
                    'usdt': 'USDT',
                    'ethereum': 'Ethereum'
                };

                container.innerHTML = `
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Amount</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Crypto</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${response.deposits.map(d => `
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-6 py-4 font-semibold">$${parseFloat(d.amount).toFixed(2)}</td>
                                    <td class="px-6 py-4">${cryptoLabels[d.payment_method] || d.payment_method}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded text-sm font-medium
                                            ${d.status === 'approved' ? 'bg-green-100 text-green-800' : 
                                              d.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                              'bg-red-100 text-red-800'}">
                                            ${d.status.charAt(0).toUpperCase() + d.status.slice(1)}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">${new Date(d.created_at).toLocaleDateString()}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>`;
            } catch (error) {
                document.getElementById('depositsList').innerHTML = `<div class="p-6 text-center text-red-600">Error loading deposits: ${error}</div>`;
            }
        }

        function showCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        function hideCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
            document.getElementById('depositForm').reset();
        }

        function showWalletModal(data) {
            currentWalletData = data;
            document.getElementById('walletAmount').textContent = '$' + parseFloat(data.amount).toFixed(2);
            document.getElementById('walletAddress').textContent = data.wallet_address;
            document.getElementById('walletRef').textContent = data.reference;
            document.getElementById('walletModal').classList.remove('hidden');
        }

        function hideWalletModal() {
            document.getElementById('walletModal').classList.add('hidden');
            hideCreateModal();
            loadDeposits();
        }

        function copyWallet() {
            const address = document.getElementById('walletAddress').textContent;
            navigator.clipboard.writeText(address).then(() => {
                showAlert('Wallet address copied to clipboard!', 'success');
            });
        }

        document.getElementById('depositForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(e.target);

            try {
                const result = await apiCall('/api/deposits/create.php', 'POST', formData);
                if (result && result.success) {
                    showWalletModal(result.data);
                } else if (result) {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                showAlert(error, 'error');
            }
        });

        // Load deposits on page load
        loadDeposits();
    </script>
</body>
</html>
