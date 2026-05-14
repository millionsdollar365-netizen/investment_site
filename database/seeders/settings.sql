-- PRIMEAXIS INVESTMENT PLATFORM
-- Platform Settings Seeder

INSERT INTO settings (setting_key, setting_value, description)
VALUES
    ('platform_name', 'Primeaxis Investment', 'Platform name'),
    ('platform_email', 'admin@primeaxisinv.com', 'Platform admin email'),
    ('referral_percentage', '5', 'Referral commission percentage'),
    ('withdrawal_fee', '0', 'Withdrawal fee amount'),
    ('deposit_fee', '0', 'Deposit fee amount'),
    ('maintenance_mode', '0', 'Enable maintenance mode (0 or 1)'),
    ('min_withdrawal', '100', 'Minimum withdrawal amount'),
    ('max_withdrawal', '50000', 'Maximum withdrawal amount'),
    ('kyc_required', '0', 'KYC verification required (0 or 1)');
