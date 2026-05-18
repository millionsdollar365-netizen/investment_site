-- Add cryptocurrency wallet settings
-- Run this migration to support BTC, USDT, and Ethereum wallet configuration

-- Insert wallet settings into settings table (if they don't exist)
INSERT INTO settings (setting_key, setting_value, setting_description) VALUES
    ('wallet_btc', '', 'Bitcoin wallet address for deposits'),
    ('wallet_usdt', '', 'USDT wallet address for deposits'),
    ('wallet_ethereum', '', 'Ethereum wallet address for deposits')
ON DUPLICATE KEY UPDATE
    setting_key = setting_key;
