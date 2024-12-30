/*
Charity Database Schema
Version: 1.0.0
Last Updated: 2024-03-21

Dependencies:
- MySQL 5.7 or higher
- No prior schema version dependencies (initial release)

Execution Order:
1. This file (database.sql) must be executed first
2. Follow with sample_data.sql (version 1.0.0) for test data

Schema Compatibility:
- Backward Compatibility: N/A (initial version)
- Forward Compatibility: Maintains compatibility with sample_data.sql 1.0.0
*/

-- Create the database
CREATE DATABASE IF NOT EXISTS charity_db;
USE charity_db;

-- Drop existing tables in reverse dependency order
DROP TABLE IF EXISTS reports;
DROP TABLE IF EXISTS contact_messages; 
DROP TABLE IF EXISTS donations;
DROP TABLE IF EXISTS beneficiary_appeals;
DROP TABLE IF EXISTS appeals;
DROP TABLE IF EXISTS beneficiaries;
DROP TABLE IF EXISTS users;

-- Users table for authentication
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff', 'donor') DEFAULT 'donor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Appeals/Campaigns table
CREATE TABLE IF NOT EXISTS appeals (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    category ENUM('education', 'healthcare', 'disaster_relief', 'poverty', 'environment', 'community') NOT NULL,
    goal_amount DECIMAL(12,2) NOT NULL,
    current_amount DECIMAL(12,2) DEFAULT 0.00,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Beneficiaries table
CREATE TABLE IF NOT EXISTS beneficiaries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    type ENUM('individual', 'organization') NOT NULL,
    category ENUM('student', 'patient', 'senior', 'family', 'ngo', 'institution') NOT NULL,
    contact_person VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Beneficiary Appeals junction table
CREATE TABLE IF NOT EXISTS beneficiary_appeals (
    beneficiary_id INT,
    appeal_id INT,
    allocation_amount DECIMAL(12,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (beneficiary_id, appeal_id),
    FOREIGN KEY (beneficiary_id) REFERENCES beneficiaries(id),
    FOREIGN KEY (appeal_id) REFERENCES appeals(id)
);

-- Donations table
CREATE TABLE IF NOT EXISTS donations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    donor_id INT NOT NULL,
    appeal_id INT NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    payment_method ENUM('credit_card', 'bank_transfer', 'cash') NOT NULL,
    status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    transaction_reference VARCHAR(100),
    donation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (donor_id) REFERENCES users(id),
    FOREIGN KEY (appeal_id) REFERENCES appeals(id)
);

-- Contact messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Reports table
CREATE TABLE IF NOT EXISTS reports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    type ENUM('financial', 'activity', 'donor', 'beneficiary') NOT NULL,
    content TEXT NOT NULL,
    generated_by INT NOT NULL,
    generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
    FOREIGN KEY (generated_by) REFERENCES users(id)
);

