-- policy_renewal.sql
-- Database creation and tables for Policy Renewal Reminder System

CREATE DATABASE IF NOT EXISTS policy_renewal;
USE policy_renewal;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'officer', 'viewer') NOT NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Policies table
CREATE TABLE policies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    policy_number VARCHAR(50) NOT NULL UNIQUE,
    client_name VARCHAR(100) NOT NULL,
    insurance_type VARCHAR(100) NOT NULL,
    premium_amount DECIMAL(10,2) NOT NULL,
    start_date DATE NOT NULL,
    renewal_date DATE NOT NULL,
    status ENUM('Active', 'Expired', 'Pending Renewal') NOT NULL DEFAULT 'Active',
    created_by INT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Documents table
CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    policy_id INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    file_type VARCHAR(20) NOT NULL,
    uploaded_by INT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (policy_id) REFERENCES policies(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, role, status) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active');

-- Insert a sample policy officer (password: officer123)
INSERT INTO users (username, password, role, status) VALUES
('officer1', '$2y$10$kqQq3RfE5bH6iJUOvcQHs.iPYb3T6Gg4NjV5GJPovIMwzGMaR1Lm2', 'officer', 'active');
