-- PRIMEAXIS INVESTMENT PLATFORM
-- Migration 002: Add Indexes and Constraints
-- Date: May 14, 2026

-- Add additional indexes for performance optimization

-- Users - add index on referred_by for referral queries
ALTER TABLE users ADD INDEX idx_referred_by (referred_by);

-- Investments - add composite index for active investments query
ALTER TABLE investments ADD INDEX idx_user_status (user_id, status);

-- Deposits - add composite index for pending approvals
ALTER TABLE deposits ADD INDEX idx_status_created (status, created_at);

-- Withdrawals - add composite index for pending approvals
ALTER TABLE withdrawals ADD INDEX idx_status_created (status, created_at);

-- Transactions - add composite index for user balance queries
ALTER TABLE transactions ADD INDEX idx_user_type (user_id, type);

-- Referrals - add composite index for referrer stats
ALTER TABLE referrals ADD INDEX idx_referrer_status (referrer_id, status);
