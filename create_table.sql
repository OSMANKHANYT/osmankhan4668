-- Create the data table for storing personnel information
CREATE TABLE IF NOT EXISTS personnel_data (
    serial_no INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    rank VARCHAR(50) NOT NULL,
    unit VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    room_no VARCHAR(20) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    issue_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add index for faster searching
CREATE INDEX idx_name ON personnel_data(name);
CREATE INDEX idx_unit ON personnel_data(unit);
CREATE INDEX idx_issue_date ON personnel_data(issue_date);