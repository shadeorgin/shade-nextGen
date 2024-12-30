# Changelog
All notable changes to the database schema will be documented in this file.

## [1.0.4] - 2024-12-30

### Fixed
- Session management in login functionality
- User CRUD operations to match database schema (first_name, last_name fields)
- Role management to use correct enum values (admin, staff, donor)
- Flash message handling in user deletion process
- Database connection consistency across all files
- Bootstrap menu navigation structure
- Local asset references instead of CDN

### Added
- Complete CRUD operations for user management
- Create functionality for new user registration
- Read functionality for user listing and profile details
- Update functionality for user information
- Delete functionality for user accounts

## [1.0.2] - 2024-12-30

### Added
- ReadMe guide for PHP Null Coalescing Operator
- Local bootstrap.min.css and bootstrap.bundle.min.js
- Configuration management system (config directory)

### Fixed
- Path handling in ReadMe guides navigation
- Markdown heading rendering in guide views
- Database schema consistency in column names
- Null value handling with coalescing operators
- Bootstrap styling and responsiveness

### Changed
- Improved markdown rendering in ReadMe guides
- Updated database queries to use correct column names 
- Reorganized configuration files into config directory

## [1.0.0] - 2024-03-21
Initial release of the charity database schema

### Added
- Users table with authentication and role management
- Appeals/Campaigns table for fundraising campaigns
- Beneficiaries table for tracking aid recipients
- Beneficiary-Appeals junction table for allocation tracking
- Donations table for tracking contributions
- Contact messages table for communication
- Reports table for system reporting with draft/published status

### Dependencies
- MySQL 5.7 or higher
- Requires execution order: database.sql followed by sample_data.sql
- PHP 7.4 or higher for web application integration

### Notes
- Schema version: 1.0.0
- Sample data version: 1.0.0
- All tables include created_at/updated_at timestamps
- Foreign key constraints and indexes implemented

## [1.0.1] - 2024-03-21

### Added
- Status field (ENUM: draft, published) to Reports table

### Changed
- Updated sample data to include report status values

