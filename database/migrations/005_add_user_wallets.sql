ALTER TABLE users ADD COLUMN wallet_btc VARCHAR(255) DEFAULT '' AFTER avatar;
ALTER TABLE users ADD COLUMN wallet_usdt VARCHAR(255) DEFAULT '' AFTER wallet_btc;
ALTER TABLE users ADD COLUMN wallet_ethereum VARCHAR(255) DEFAULT '' AFTER wallet_usdt;

ALTER TABLE withdrawals ADD COLUMN coin VARCHAR(10) DEFAULT 'btc' AFTER amount;
ALTER TABLE withdrawals ADD COLUMN wallet_address VARCHAR(255) DEFAULT '' AFTER account_holder_name;
ALTER TABLE withdrawals CHANGE bank_name bank_name VARCHAR(100) NULL;
ALTER TABLE withdrawals CHANGE account_number account_number VARCHAR(50) NULL;
ALTER TABLE withdrawals CHANGE account_holder_name account_holder_name VARCHAR(100) NULL;
