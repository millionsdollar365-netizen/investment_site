/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * Dashboard-Specific JavaScript
 */

// ==================== DASHBOARD DATA LOADING ====================

/**
 * Load user dashboard data
 */
async function loadUserDashboard() {
    try {
        const response = await fetch('/api/user/dashboard.php');
        const data = await response.json();

        if (!data.success) {
            showAlert('Failed to load dashboard data', 'error');
            return;
        }

        const dashboard = data.data;

        // Update stats
        document.getElementById('statBalance').textContent = formatCurrency(dashboard.wallet.balance);
        document.getElementById('statInterest').textContent = formatCurrency(dashboard.wallet.interest_balance);
        document.getElementById('statInvestments').textContent = dashboard.active_investments;

        // Initialize charts and tables
        initializeChart(dashboard);
        loadRecentTransactions(dashboard.recent_transactions);
        loadActiveInvestments(dashboard.investments);

    } catch (error) {
        console.error('Error loading dashboard:', error);
        showAlert('Error loading dashboard data', 'error');
    }
}

/**
 * Load recent transactions
 */
function loadRecentTransactions(transactions = []) {
    const container = document.getElementById('recentTransactionsTable');
    if (!container) return;

    if (!transactions || transactions.length === 0) {
        container.innerHTML = '<tr><td colspan="4" class="text-center text-gray-500">No transactions yet</td></tr>';
        return;
    }

    container.innerHTML = transactions.slice(0, 5).map(tx => `
        <tr>
            <td>${sanitizeHtml(tx.type)}</td>
            <td>${formatCurrency(tx.amount)}</td>
            <td><span class="badge badge-${getStatusClass(tx.status)}">${sanitizeHtml(tx.status)}</span></td>
            <td>${formatDate(tx.created_at)}</td>
        </tr>
    `).join('');
}

/**
 * Load active investments list
 */
function loadActiveInvestments(investments = []) {
    const container = document.getElementById('activeInvestmentsTable');
    if (!container) return;

    if (!investments || investments.length === 0) {
        container.innerHTML = '<tr><td colspan="6" class="text-center text-gray-500">No active investments</td></tr>';
        return;
    }

    container.innerHTML = investments.map(inv => `
        <tr>
            <td>${sanitizeHtml(inv.plan_name)}</td>
            <td>${formatCurrency(inv.amount)}</td>
            <td>${inv.percentage}%</td>
            <td>${formatCurrency(inv.earned)}</td>
            <td>${inv.days_left} days</td>
            <td><span class="badge badge-success">${sanitizeHtml(inv.status)}</span></td>
        </tr>
    `).join('');
}

/**
 * Initialize investment chart (simple bar chart using ASCII/Text)
 */
function initializeChart(dashboard) {
    const chartContainer = document.getElementById('investmentChart');
    if (!chartContainer) return;

    const plans = dashboard.plans_breakdown || [];
    
    if (plans.length === 0) {
        chartContainer.innerHTML = '<p class="text-center text-gray-500">No investment data</p>';
        return;
    }

    // Create simple visual representation
    const maxAmount = Math.max(...plans.map(p => p.amount));
    const scale = 100 / maxAmount;

    chartContainer.innerHTML = plans.map(plan => `
        <div class="mb-4">
            <div class="flex justify-between mb-1">
                <span class="text-sm font-medium">${sanitizeHtml(plan.name)}</span>
                <span class="text-sm text-gray-600">${formatCurrency(plan.amount)}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: ${plan.amount * scale}%"></div>
            </div>
        </div>
    `).join('');
}

/**
 * Get status badge class
 */
function getStatusClass(status) {
    const classes = {
        'completed': 'success',
        'pending': 'warning',
        'rejected': 'danger',
        'active': 'info',
        'success': 'success',
        'failed': 'danger'
    };
    return classes[status] || 'info';
}

// ==================== INVESTMENT ACTIONS ====================

/**
 * Create new investment
 */
async function createInvestment(planId, amount) {
    const result = await apiCall('/api/investments/create.php', 'POST', {
        plan_id: planId,
        amount: amount
    });

    if (result && result.success) {
        showAlert('Investment created successfully!', 'success');
        setTimeout(() => {
            window.location.reload();
        }, 1500);
    }
    return result;
}

/**
 * Load available investment plans
 */
async function loadInvestmentPlans() {
    const result = await apiCall('/api/investments/plans.php');
    
    if (!result || !result.success) {
        showAlert('Failed to load investment plans', 'error');
        return [];
    }

    const container = document.getElementById('investmentPlans');
    if (!container) return result.data;

    container.innerHTML = (result.data || []).map(plan => `
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-xl font-bold mb-2">${sanitizeHtml(plan.name)}</h3>
            <p class="text-gray-600 mb-4">${sanitizeHtml(plan.description)}</p>
            <div class="mb-4">
                <p class="text-2xl font-bold text-blue-600">${plan.percentage}% / ${plan.duration} days</p>
                <p class="text-sm text-gray-500">Min: ${formatCurrency(plan.minimum_amount)}</p>
            </div>
            <button onclick="showInvestmentForm(${plan.id}, '${sanitizeHtml(plan.name)}')" 
                    class="w-full bg-blue-600 text-white py-2 rounded font-semibold">
                Invest Now
            </button>
        </div>
    `).join('');

    return result.data || [];
}

/**
 * Show investment form modal
 */
function showInvestmentForm(planId, planName) {
    const modal = document.createElement('div');
    modal.id = 'investmentModal';
    modal.className = 'modal-overlay active';
    modal.innerHTML = `
        <div class="modal">
            <h2 class="text-2xl font-bold mb-4">Invest in ${sanitizeHtml(planName)}</h2>
            <form id="investmentForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Investment Amount</label>
                    <input type="number" id="investmentAmount" name="amount" 
                           min="0.01" step="0.01" required class="w-full px-4 py-2 border rounded">
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded font-semibold">Invest</button>
                    <button type="button" onclick="closeInvestmentModal()" 
                            class="flex-1 bg-gray-300 text-gray-800 py-2 rounded font-semibold">Cancel</button>
                </div>
            </form>
        </div>
    `;
    document.body.appendChild(modal);

    document.getElementById('investmentForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const amount = document.getElementById('investmentAmount').value;
        await createInvestment(planId, amount);
        closeInvestmentModal();
    });
}

/**
 * Close investment modal
 */
function closeInvestmentModal() {
    const modal = document.getElementById('investmentModal');
    if (modal) modal.remove();
}

// ==================== TRANSACTION ACTIONS ====================

/**
 * Create deposit
 */
async function createDeposit(amount, method) {
    const result = await apiCall('/api/deposits/create.php', 'POST', {
        amount: amount,
        payment_method: method
    });

    if (result && result.success) {
        showAlert('Deposit request created. Awaiting approval.', 'success');
        setTimeout(() => {
            window.location.reload();
        }, 1500);
    }
    return result;
}

/**
 * Create withdrawal
 */
async function createWithdrawal(amount, method, address) {
    const result = await apiCall('/api/withdrawals/create.php', 'POST', {
        amount: amount,
        payment_method: method,
        payment_address: address
    });

    if (result && result.success) {
        showAlert('Withdrawal request created. Awaiting approval.', 'success');
        setTimeout(() => {
            window.location.reload();
        }, 1500);
    }
    return result;
}

/**
 * Load user transactions
 */
async function loadTransactions(page = 1) {
    const result = await apiCall(`/api/user/transactions.php?page=${page}`);

    if (!result || !result.success) return;

    const container = document.getElementById('transactionsTable');
    if (!container) return;

    const transactions = result.data.transactions || [];
    container.innerHTML = transactions.map(tx => `
        <tr>
            <td>#${sanitizeHtml(tx.id)}</td>
            <td>${sanitizeHtml(tx.type)}</td>
            <td>${formatCurrency(tx.amount)}</td>
            <td><span class="badge badge-${getStatusClass(tx.status)}">${sanitizeHtml(tx.status)}</span></td>
            <td>${formatDate(tx.created_at)}</td>
        </tr>
    `).join('');

    // Pagination
    renderPagination(result.data.pagination, (p) => loadTransactions(p));
}

/**
 * Render pagination controls
 */
function renderPagination(pagination, callback) {
    const container = document.getElementById('paginationControls');
    if (!container) return;

    const { current_page, total_pages } = pagination;
    let html = '';

    if (current_page > 1) {
        html += `<button onclick="arguments[0].currentTarget.onclick = ${callback}(${current_page - 1})" class="px-3 py-1 rounded border">Previous</button>`;
    }

    for (let i = 1; i <= total_pages; i++) {
        if (i === current_page) {
            html += `<button disabled class="px-3 py-1 rounded bg-blue-600 text-white">${i}</button>`;
        } else {
            html += `<button onclick="arguments[0].currentTarget.onclick = ${callback}(${i})" class="px-3 py-1 rounded border">${i}</button>`;
        }
    }

    if (current_page < total_pages) {
        html += `<button onclick="arguments[0].currentTarget.onclick = ${callback}(${current_page + 1})" class="px-3 py-1 rounded border">Next</button>`;
    }

    container.innerHTML = html;
}

// ==================== REFERRAL SYSTEM ====================

/**
 * Load referral info
 */
async function loadReferralInfo() {
    const result = await apiCall('/api/user/referrals.php');

    if (!result || !result.success) return;

    const data = result.data;
    const container = document.getElementById('referralInfo');
    
    if (container) {
        container.innerHTML = `
            <div class="space-y-4">
                <div class="bg-blue-50 p-4 rounded">
                    <p class="text-sm text-gray-600">Your Referral Link</p>
                    <div class="flex gap-2 mt-2">
                        <input type="text" value="${sanitizeHtml(data.referral_link)}" readonly class="flex-1 px-3 py-2 border rounded bg-white">
                        <button onclick="copyToClipboard('${sanitizeHtml(data.referral_link)}')" class="bg-blue-600 text-white px-4 py-2 rounded">Copy</button>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white p-4 rounded shadow">
                        <p class="text-gray-600">Total Referrals</p>
                        <p class="text-2xl font-bold">${data.total_referrals}</p>
                    </div>
                    <div class="bg-white p-4 rounded shadow">
                        <p class="text-gray-600">Commission Earned</p>
                        <p class="text-2xl font-bold text-green-600">${formatCurrency(data.commission_earned)}</p>
                    </div>
                </div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Referral</th>
                            <th class="text-left py-2">Status</th>
                            <th class="text-right py-2">Commission</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${(data.referrals || []).map(ref => `
                            <tr class="border-b">
                                <td class="py-2">${sanitizeHtml(ref.name)}</td>
                                <td class="py-2"><span class="badge badge-${ref.status === 'active' ? 'success' : 'warning'}">${sanitizeHtml(ref.status)}</span></td>
                                <td class="text-right py-2">${formatCurrency(ref.commission)}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
    }
}

// ==================== PROFILE MANAGEMENT ====================

/**
 * Load user profile
 */
async function loadUserProfile() {
    const result = await apiCall('/api/user/profile.php');

    if (!result || !result.success) return;

    const profile = result.data;
    const container = document.getElementById('profileInfo');

    if (container) {
        container.innerHTML = `
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium">Full Name</label>
                    <p class="text-lg">${sanitizeHtml(profile.first_name + ' ' + profile.last_name)}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium">Email</label>
                    <p class="text-lg">${sanitizeHtml(profile.email)}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium">Phone</label>
                    <p class="text-lg">${sanitizeHtml(profile.phone || 'Not provided')}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium">Account Status</label>
                    <p class="text-lg"><span class="badge badge-${profile.status === 'active' ? 'success' : 'warning'}">${sanitizeHtml(profile.status)}</span></p>
                </div>
                <div>
                    <label class="block text-sm font-medium">Member Since</label>
                    <p class="text-lg">${formatDate(profile.created_at)}</p>
                </div>
            </div>
        `;
    }
}

// ==================== DOM READY ====================

document.addEventListener('DOMContentLoaded', () => {
    // Auto-load dashboard if page contains dashboard elements
    if (document.getElementById('statBalance')) {
        loadUserDashboard();
    }

    // Auto-load investment plans if needed
    if (document.getElementById('investmentPlans')) {
        loadInvestmentPlans();
    }

    // Auto-load referral info
    if (document.getElementById('referralInfo')) {
        loadReferralInfo();
    }

    // Auto-load profile
    if (document.getElementById('profileInfo')) {
        loadUserProfile();
    }
});
