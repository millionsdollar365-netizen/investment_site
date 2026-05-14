-- PRIMEAXIS INVESTMENT PLATFORM
-- Admin User Seeder
-- Creates the first super admin account

-- Note: Password is hashed using bcrypt
-- Default credentials should be changed immediately after first login
-- Username: admin
-- Email: admin@primeaxisinv.com
-- Password hash: bcrypt('admin123') - CHANGE THIS IMMEDIATELY IN PRODUCTION

INSERT INTO admin_users (username, email, password_hash, role, status)
VALUES (
    'admin',
    'admin@primeaxisinv.com',
    '$2y$12$O9jH8wZ7K3.p5L2N0mQqHeKw5RkOvU1xC8TfQ4.KwzH9jA7vT2pXi',
    'super_admin',
    'active'
) ON DUPLICATE KEY UPDATE status='active';

-- Note: The password hash above is for demonstration
-- In production, use: Security::hashPassword('yourpassword')
