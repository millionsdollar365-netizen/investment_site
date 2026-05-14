-- PRIMEAXIS INVESTMENT PLATFORM
-- Investment Plans Seeder

INSERT INTO investment_plans (name, description, min_amount, max_amount, duration_days, daily_roi, total_return, status)
VALUES 
    ('Starter Plan', 'Perfect for beginners', 100, 999, 30, 2.5, 175, 'active'),
    ('Silver Plan', 'Standard investment option', 1000, 4999, 30, 3.0, 190, 'active'),
    ('Gold Plan', 'Premium investment option', 5000, 19999, 30, 3.5, 205, 'active'),
    ('Platinum Plan', 'Elite investment option', 20000, NULL, 30, 4.5, 235, 'active'),
    ('Quick 15 Day Plan', 'Fast returns in 15 days', 500, NULL, 15, 4.0, 160, 'active'),
    ('Extended 60 Day Plan', 'Long-term investment with higher returns', 1000, NULL, 60, 2.0, 220, 'active');
