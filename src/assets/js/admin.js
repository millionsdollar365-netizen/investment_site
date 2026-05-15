/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * Admin-Specific JavaScript
 */

// ==================== ADMIN DASHBOARD ====================

/**
 * Load admin dashboard data
 */
async function loadAdminDashboard() {
    try {
        const response = await fetch('/api/admin/dashboard.php');
        const data = await response.json();

        if (!data.success) {
            showAlert('Failed to load admin dashboard', 'error');
            return;
        }

        const dashboard = data.data;

        // Update dashboard stats
        document.getElementById('statTotalUsers').textContent = dashboard.users.total;
        document.getElementById('statActiveUsers').textContent = dashboard.users.active + ' active';
        document.getElementById('statPendingDeposits').textContent = dashboard.deposits.pending_count;
        document.getElementById('statPendingDepositsAmount').textContent = formatCurrency(dashboard.deposits.pending_amount);
        document.getElementById('statPendingWithdrawals').textContent = dashboard.withdrawals.pending_count;
        document.getElementById('statPendingWithdrawalsAmount').textContent = formatCurrency(dashboard.withdrawals.pending_amount);
        document.getElementById('statTotalBalance').textContent = formatCurrency(dashboard.balances.total);
        document.getElementById('statTotalInvested').textContent = formatCurrency(dashboard.investments.total_amount);

    } catch (error) {
        console.error('Error loading admin dashboard:', error);
        showAlert('Error loading dashboard data', 'error');
    }
}

// ==================== USER MANAGEMENT ====================

/**
 * Load users list
 */
async function loadUsers(page = 1, search = '') {
    const url = `/api/admin/users.php?page=${page}${search ? '&search=' + encodeURIComponent(search) : ''}`;
    const result = await apiCall(url);

    if (!result || !result.success) {
        showAlert('Failed to load users', 'error');
        return;
    }

    const container = document.getElementById('usersTable');
    if (!container) return;

    const users = result.data.users || [];
    container.innerHTML = users.map(user => `
        <tr>
            <td>#${user.id}</td>
            <td>${sanitizeHtml(user.first_name + ' ' + user.last_name)}</td>
            <td>${sanitizeHtml(user.email)}</td>
            <td><span class="badge badge-${user.status === 'active' ? 'success' : 'warning'}">${sanitizeHtml(user.status)}</span></td>
            <td>${formatCurrency(user.total_balance)}</td>
            <td>${formatDate(user.created_at)}</td>
            <td>
                <button onclick="viewUserDetail(${user.id})" class="text-blue-600 hover:underline">View</button>
                <button onclick="toggleUserStatus(${user.id}, '${user.status === 'active' ? 'suspended' : 'active'}')" 
                        class="text-yellow-600 hover:underline ml-2">
                    ${user.status === 'active' ? 'Suspend' : 'Activate'}
                </button>
            </td>
        </tr>
    `).join('');

    // Pagination
    renderAdminPagination(result.data.pagination, (p) => loadUsers(p, search));
}

/**
 * View user detail
 */
async function viewUserDetail(userId) {
    const result = await apiCall(`/api/admin/user-detail.php?user_id=${userId}`);

    if (!result || !result.success) {
        showAlert('Failed to load user details', 'error');
        return;
    }

    const user = result.data;
    const modal = document.createElement('div');
    modal.id = 'userDetailModal';
    modal.className = 'modal-overlay active';
    modal.innerHTML = `
        <div class="modal max-w-2xl">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">${sanitizeHtml(user.first_name + ' ' + user.last_name)}</h2>
                <button onclick="closeUserDetailModal()" class="text-gray-500 hover:text-gray-700">✕</button>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="text-sm text-gray-600">Email</label>
                    <p class="font-semibold">${sanitizeHtml(user.email)}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Status</label>
                    <p class="font-semibold"><span class="badge badge-${user.status === 'active' ? 'success' : 'warning'}">${sanitizeHtml(user.status)}</span></p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Total Balance</label>
                    <p class="font-semibold">${formatCurrency(user.wallet.balance)}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Interest Balance</label>
                    <p class="font-semibold text-green-600">${formatCurrency(user.wallet.interest_balance)}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Total Invested</label>
                    <p class="font-semibold">${formatCurrency(user.total_invested)}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Member Since</label>
                    <p class="font-semibold">${formatDate(user.created_at)}</p>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded mb-4">
                <h3 class="font-bold mb-2">Recent Transactions</h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Type</th>
                            <th class="text-left py-2">Amount</th>
                            <th class="text-left py-2">Status</th>
                            <th class="text-left py-2">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${(user.recent_transactions || []).map(tx => `
                            <tr class="border-b">
                                <td class="py-2">${sanitizeHtml(tx.type)}</td>
                                <td class="py-2">${formatCurrency(tx.amount)}</td>
                                <td class="py-2"><span class="badge badge-${getStatusClass(tx.status)}">${sanitizeHtml(tx.status)}</span></td>
                                <td class="py-2">${formatDate(tx.created_at)}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>

            <div class="flex gap-4">
                <button onclick="closeUserDetailModal()" class="flex-1 bg-gray-300 text-gray-800 py-2 rounded font-semibold">Close</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

/**
 * Close user detail modal
 */
function closeUserDetailModal() {
    const modal = document.getElementById('userDetailModal');
    if (modal) modal.remove();
}

/**
 * Toggle user status (suspend/activate)
 */
async function toggleUserStatus(userId, newStatus) {
    if (!confirm(`Are you sure you want to ${newStatus === 'active' ? 'activate' : 'suspend'} this user?`)) return;

    const result = await apiCall('/api/admin/update-user.php', 'POST', {
        user_id: userId,
        status: newStatus
    });

    if (result && result.success) {
        showAlert(`User ${newStatus === 'active' ? 'activated' : 'suspended'} successfully`, 'success');
        loadUsers();
    }
}

// ==================== DEPOSIT MANAGEMENT ====================

/**
 * Load pending deposits
 */
async function loadDeposits(page = 1) {
    const url = `/api/admin/deposits.php?page=${page}`;
    const result = await apiCall(url);

    if (!result || !result.success) {
        showAlert('Failed to load deposits', 'error');
        return;
    }

    const container = document.getElementById('depositsTable');
    if (!container) return;

    const deposits = result.data.deposits || [];
    container.innerHTML = deposits.map(dep => `
        <tr>
            <td>#${dep.id}</td>
            <td>${sanitizeHtml(dep.user_name)}</td>
            <td>${formatCurrency(dep.amount)}</td>
            <td>${sanitizeHtml(dep.payment_method)}</td>
            <td><span class="badge badge-${getStatusClass(dep.status)}">${sanitizeHtml(dep.status)}</span></td>
            <td>${formatDate(dep.created_at)}</td>
            <td>
                ${dep.status === 'pending' ? `
                    <button onclick="approveDeposit(${dep.id})" class="text-green-600 hover:underline">Approve</button>
                    <button onclick="rejectDeposit(${dep.id})" class="text-red-600 hover:underline ml-2">Reject</button>
                ` : '-'}
            </td>
        </tr>
    `).join('');

    renderAdminPagination(result.data.pagination, (p) => loadDeposits(p));
}

/**
 * Approve deposit
 */
async function approveDeposit(depositId) {
    if (!confirm('Approve this deposit?')) return;

    const result = await apiCall('/api/admin/approve-deposit.php', 'POST', {
        deposit_id: depositId
    });

    if (result && result.success) {
        showAlert('Deposit approved', 'success');
        loadDeposits();
    }
}

/**
 * Reject deposit
 */
async function rejectDeposit(depositId) {
    const reason = prompt('Rejection reason:');
    if (!reason) return;

    const result = await apiCall('/api/admin/reject-deposit.php', 'POST', {
        deposit_id: depositId,
        reason: reason
    });

    if (result && result.success) {
        showAlert('Deposit rejected', 'success');
        loadDeposits();
    }
}

// ==================== WITHDRAWAL MANAGEMENT ====================

/**
 * Load pending withdrawals
 */
async function loadWithdrawals(page = 1) {
    const url = `/api/admin/withdrawals.php?page=${page}`;
    const result = await apiCall(url);

    if (!result || !result.success) {
        showAlert('Failed to load withdrawals', 'error');
        return;
    }

    const container = document.getElementById('withdrawalsTable');
    if (!container) return;

    const withdrawals = result.data.withdrawals || [];
    container.innerHTML = withdrawals.map(wit => `
        <tr>
            <td>#${wit.id}</td>
            <td>${sanitizeHtml(wit.user_name)}</td>
            <td>${formatCurrency(wit.amount)}</td>
            <td>${sanitizeHtml(wit.payment_method)}</td>
            <td><span class="badge badge-${getStatusClass(wit.status)}">${sanitizeHtml(wit.status)}</span></td>
            <td>${formatDate(wit.created_at)}</td>
            <td>
                ${wit.status === 'pending' ? `
                    <button onclick="approveWithdrawal(${wit.id})" class="text-green-600 hover:underline">Approve</button>
                    <button onclick="rejectWithdrawal(${wit.id})" class="text-red-600 hover:underline ml-2">Reject</button>
                ` : '-'}
            </td>
        </tr>
    `).join('');

    renderAdminPagination(result.data.pagination, (p) => loadWithdrawals(p));
}

/**
 * Approve withdrawal
 */
async function approveWithdrawal(withdrawalId) {
    if (!confirm('Approve this withdrawal?')) return;

    const result = await apiCall('/api/admin/approve-withdrawal.php', 'POST', {
        withdrawal_id: withdrawalId
    });

    if (result && result.success) {
        showAlert('Withdrawal approved', 'success');
        loadWithdrawals();
    }
}

/**
 * Reject withdrawal
 */
async function rejectWithdrawal(withdrawalId) {
    const reason = prompt('Rejection reason:');
    if (!reason) return;

    const result = await apiCall('/api/admin/reject-withdrawal.php', 'POST', {
        withdrawal_id: withdrawalId,
        reason: reason
    });

    if (result && result.success) {
        showAlert('Withdrawal rejected', 'success');
        loadWithdrawals();
    }
}

// ==================== INVESTMENT MANAGEMENT ====================

/**
 * Load investments
 */
async function loadInvestments(page = 1) {
    const url = `/api/admin/investments.php?page=${page}`;
    const result = await apiCall(url);

    if (!result || !result.success) {
        showAlert('Failed to load investments', 'error');
        return;
    }

    const container = document.getElementById('investmentsTable');
    if (!container) return;

    const investments = result.data.investments || [];
    container.innerHTML = investments.map(inv => `
        <tr>
            <td>#${inv.id}</td>
            <td>${sanitizeHtml(inv.user_name)}</td>
            <td>${sanitizeHtml(inv.plan_name)}</td>
            <td>${formatCurrency(inv.amount)}</td>
            <td>${inv.percentage}%</td>
            <td>${inv.duration} days</td>
            <td><span class="badge badge-${getStatusClass(inv.status)}">${sanitizeHtml(inv.status)}</span></td>
            <td>${formatDate(inv.created_at)}</td>
        </tr>
    `).join('');

    renderAdminPagination(result.data.pagination, (p) => loadInvestments(p));
}

// ==================== PLANS MANAGEMENT ====================

/**
 * Load investment plans
 */
async function loadPlans() {
    const result = await apiCall('/api/admin/plans.php');

    if (!result || !result.success) {
        showAlert('Failed to load plans', 'error');
        return;
    }

    const container = document.getElementById('plansTable');
    if (!container) return;

    const plans = result.data || [];
    container.innerHTML = plans.map(plan => `
        <tr>
            <td>#${plan.id}</td>
            <td>${sanitizeHtml(plan.name)}</td>
            <td>${plan.percentage}%</td>
            <td>${plan.duration} days</td>
            <td>${formatCurrency(plan.minimum_amount)}</td>
            <td><span class="badge badge-${plan.status === 'active' ? 'success' : 'warning'}">${sanitizeHtml(plan.status)}</span></td>
            <td>
                <button onclick="editPlan(${plan.id})" class="text-blue-600 hover:underline">Edit</button>
                <button onclick="togglePlanStatus(${plan.id}, '${plan.status === 'active' ? 'inactive' : 'active'}')" 
                        class="text-yellow-600 hover:underline ml-2">
                    ${plan.status === 'active' ? 'Deactivate' : 'Activate'}
                </button>
            </td>
        </tr>
    `).join('');
}

/**
 * Toggle plan status
 */
async function togglePlanStatus(planId, newStatus) {
    if (!confirm(`Are you sure?`)) return;

    const result = await apiCall('/api/admin/plans.php', 'POST', {
        plan_id: planId,
        status: newStatus
    });

    if (result && result.success) {
        showAlert('Plan updated successfully', 'success');
        loadPlans();
    }
}

/**
 * Edit plan (placeholder)
 */
function editPlan(planId) {
    showAlert('Edit plan feature coming soon', 'info');
}

// ==================== SETTINGS ====================

/**
 * Load admin settings
 */
async function loadSettings() {
    const result = await apiCall('/api/admin/settings.php');

    if (!result || !result.success) {
        showAlert('Failed to load settings', 'error');
        return;
    }

    const settings = result.data;
    const container = document.getElementById('settingsForm');

    if (container) {
        container.innerHTML = Object.keys(settings).map(key => `
            <div class="mb-4">
                <label class="block text-sm font-medium">${key.replace(/_/g, ' ')}</label>
                <input type="text" name="${key}" value="${sanitizeHtml(settings[key])}" 
                       class="w-full px-4 py-2 border rounded">
            </div>
        `).join('');
    }
}

// ==================== PAGINATION ====================

/**
 * Render admin pagination
 */
function renderAdminPagination(pagination, callback) {
    const container = document.getElementById('paginationControls');
    if (!container || !pagination) return;

    const { current_page, total_pages } = pagination;
    let html = '<div class="flex gap-2">';

    if (current_page > 1) {
        html += `<button onclick="(()=>{${callback}(${current_page - 1})})()" class="px-3 py-1 rounded border">Previous</button>`;
    }

    html += '</div>';
    container.innerHTML = html;
}

/**
 * Get status class
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

// ==================== SEARCH ====================

/**
 * Setup admin search functionality
 */
function setupAdminSearch(field) {
    const searchInput = document.getElementById(field + 'Search');
    if (searchInput) {
        searchInput.addEventListener('input', debounce((e) => {
            if (field === 'user') {
                loadUsers(1, e.target.value);
            }
        }, 500));
    }
}

// ==================== DOM READY ====================

document.addEventListener('DOMContentLoaded', () => {
    // Auto-load admin dashboard if page contains dashboard elements
    if (document.getElementById('statTotalUsers')) {
        loadAdminDashboard();
    }

    // Setup search
    setupAdminSearch('user');

    // Auto-load page-specific data
    if (document.getElementById('usersTable')) {
        loadUsers();
    }
    if (document.getElementById('depositsTable')) {
        loadDeposits();
    }
    if (document.getElementById('withdrawalsTable')) {
        loadWithdrawals();
    }
    if (document.getElementById('investmentsTable')) {
        loadInvestments();
    }
    if (document.getElementById('plansTable')) {
        loadPlans();
    }
    if (document.getElementById('settingsForm')) {
        loadSettings();
    }
});
