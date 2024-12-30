/*
Charity Database Sample Data
Version: 1.0.0
Last Updated: 2024-03-21

Dependencies:
- Requires database.sql schema version 1.0.0
- MySQL 5.7 or higher

Execution Order:
1. database.sql must be executed first to create schema
2. This file (sample_data.sql) should be executed second

Data Compatibility:
- Compatible with schema version 1.0.0
- Provides test data for all major tables
- Includes example data for testing all features
*/

-- Sample Users Data
-- Login credentials:
-- 1. Admin User
--    Email: admin@charity.org
--    Password: Admin@123 (will be hashed during login)
-- 2. Donor User
--    Email: john.doe@email.com
--    Password: JohnDoe@123 (will be hashed during login)
-- 3. Staff User
--    Email: sarah.smith@email.com
--    Password: SarahSmith@123 (will be hashed during login)
INSERT INTO users (first_name, last_name, email, password, role, created_at) VALUES
('Admin', 'User', 'admin@charity.org', '$2y$12$blqQS41UjAS.gix8oR776ekeTkeivR8MyiWM6kz2QY82/vyIkFHCa', 'admin', NOW()),
('John', 'Doe', 'john.doe@email.com', '$2y$12$A67zN4BHR3d3fSZRcjjMxeDVIcXIJzXw.jK496mpqln3jM9goqvxa', 'donor', NOW()),
('Sarah', 'Smith', 'sarah.smith@email.com', '$2y$12$kjzjPFRlu9VnSub7r2nBo.QUPVGBdukMi/7iXmPl.MXelTi.yZILW', 'staff', NOW());

-- Sample Appeals Data
INSERT INTO appeals (title, description, category, goal_amount, current_amount, start_date, end_date, status) VALUES
('Education for Underprivileged Children', 'Help provide education to 100 children from low-income families', 'education', 50000.00, 15000.00, '2024-01-01', '2024-12-31', 'active'),
('Emergency Medical Fund', 'Support critical medical treatments for those who cannot afford it', 'healthcare', 100000.00, 45000.00, '2024-01-15', '2024-06-30', 'active'),
('Disaster Relief - Flood Victims', 'Immediate assistance for families affected by recent floods', 'disaster_relief', 75000.00, 25000.00, '2024-02-01', '2024-05-31', 'active'),
('Elderly Care Program', 'Supporting senior citizens with medical care and basic necessities', 'healthcare', 30000.00, 12000.00, '2024-01-01', '2024-12-31', 'active'),
('Women Empowerment Initiative', 'Skills training and support for women entrepreneurs', 'education', 45000.00, 20000.00, '2024-02-15', '2024-08-31', 'active');

-- Sample Beneficiaries Data
INSERT INTO beneficiaries (name, type, category, contact_person, phone, email, address, created_at, status) VALUES
('Hope Children''s Home', 'organization', 'ngo', 'James Wilson', '+1-555-0123', 'contact@hopehome.org', '123 Hope Street, District 1', NOW(), 'active'),
('Sarah Johnson', 'individual', 'patient', 'Sarah Johnson', '+1-555-0124', 'sarah.j@email.com', '456 Health Ave, District 2', NOW(), 'active'),
('Flood Relief Camp - District 7', 'organization', 'ngo', 'Robert Brown', '+1-555-0125', 'relief.d7@email.com', 'District 7 Community Center', NOW(), 'active'),
('Senior Care Center', 'organization', 'institution', 'Mary Thompson', '+1-555-0126', 'senior.care@email.com', '789 Elder Lane, District 3', NOW(), 'active'),
('Women''s Skill Development Center', 'organization', 'institution', 'Linda Davis', '+1-555-0127', 'wsdc@email.com', '321 Training Road, District 4', NOW(), 'active');

-- Beneficiary-Appeals Mappings
INSERT INTO beneficiary_appeals (beneficiary_id, appeal_id, allocation_amount, created_at) VALUES
(1, 1, 10000.00, '2024-01-15'),  -- Hope Children's Home to Education appeal
(5, 1, 5000.00, '2024-02-15'),   -- Women's Skill Center to Education appeal
(2, 2, 15000.00, '2024-02-01'),  -- Sarah Johnson to Medical Fund  
(4, 2, 10000.00, '2024-01-20'),  -- Senior Care Center to Medical Fund
(3, 3, 25000.00, '2024-02-10'),  -- Flood Relief Camp to Disaster Relief
(4, 4, 10000.00, '2024-01-20'),  -- Senior Care Center to Elderly Care
(5, 5, 13000.00, '2024-02-15');  -- Women's Skill Center to Women Empowerment

-- Sample Contact Messages
INSERT INTO contact_messages (name, email, subject, message, created_at, status) VALUES
('Robert Wilson', 'robert.wilson@email.com', 'Volunteer Inquiry', 'I would like to volunteer for the education program. Please let me know how I can help.', NOW(), 'new'),
('Maria Garcia', 'maria.g@email.com', 'Donation Question', 'I have some questions about making monthly donations. Could someone contact me?', NOW(), 'read'),
('David Chen', 'david.chen@email.com', 'Partnership Proposal', 'Our organization would like to partner with you on the medical fund project.', NOW(), 'read'),
('Emily Brown', 'emily.b@email.com', 'Technical Issue', 'I''m having trouble making a donation through the website.', NOW(), 'new'),
('Michael Lee', 'michael.l@email.com', 'Thank You Message', 'Thank you for the great work you''re doing with the elderly care program!', NOW(), 'read');

-- Sample Reports
INSERT INTO reports (title, type, content, generated_by, generated_at, status) VALUES
('Q1 2024 Education Program Impact', 'activity', 'In Q1 2024, we successfully provided educational support to 45 children, with 95% attendance rate and improved academic performance.', 1, '2024-03-31', 'published'),
('Medical Fund Distribution - Feb 2024', 'financial', 'February 2024 medical fund helped 12 individuals receive critical care, with total disbursement of $25,000.', 3, '2024-02-28', 'published'),
('Flood Relief Initial Assessment', 'activity', 'Initial assessment shows 100 families needing immediate assistance. Required resources estimated at $50,000.', 1, '2024-02-10', 'draft'),
('Monthly Donor Growth Report', 'donor', 'January 2024 saw 15% increase in monthly donors, with average donation amount of $75.', 3, '2024-02-01', 'published'),
('Elderly Care Program Review', 'beneficiary', 'Program review shows successful implementation of medical check-ups and meal delivery services.', 3, '2024-03-15', 'draft');

-- Sample Donations
INSERT INTO donations (appeal_id, donor_id, amount, payment_method, transaction_reference, donation_date, status) VALUES
(1, 2, 1000.00, 'credit_card', 'TXN123456789', '2024-01-15', 'completed'),
(2, 2, 500.00, 'bank_transfer', 'BTR987654321', '2024-02-01', 'completed'),
(3, 2, 250.00, 'credit_card', 'TXN234567890', '2024-02-15', 'completed'),
(4, 2, 100.00, 'credit_card', 'TXN345678901', '2024-03-01', 'completed'),
(5, 2, 150.00, 'credit_card', 'TXN456789012', '2024-03-15', 'pending'),
(1, 2, 2000.00, 'bank_transfer', 'BTR876543210', '2024-01-20', 'completed'),
(2, 2, 1500.00, 'credit_card', 'TXN567890123', '2024-02-05', 'completed'),
(3, 2, 1000.00, 'bank_transfer', 'BTR678901234', '2024-02-20', 'completed'),
(4, 2, 750.00, 'credit_card', 'TXN789012345', '2024-03-05', 'completed'),
(5, 2, 500.00, 'bank_transfer', 'BTR765432109', '2024-03-20', 'pending');
