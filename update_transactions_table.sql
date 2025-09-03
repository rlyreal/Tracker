-- Modify transaction_date column to store datetime instead of just date
ALTER TABLE transactions MODIFY COLUMN transaction_date DATETIME;
