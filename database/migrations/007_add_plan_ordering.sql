ALTER TABLE investment_plans ADD COLUMN sort_order INT DEFAULT 0 AFTER status;
ALTER TABLE investment_plans ADD COLUMN is_popular TINYINT(1) DEFAULT 0 AFTER sort_order;
