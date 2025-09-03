CREATE TABLE IF NOT EXISTS debts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    debtor_name VARCHAR(255) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    due_date DATE NOT NULL,
    notes TEXT,
    status ENUM('pending', 'paid') DEFAULT 'pending',
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP
);
