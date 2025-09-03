-- Alter transaction_date column to DATETIME to store both date and time
ALTER TABLE transactions MODIFY COLUMN transaction_date DATETIME;
