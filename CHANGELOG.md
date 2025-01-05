# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.4] - 2025-01-06

### Added
- Causewise Summary Report with SQL file integration

### Enhanced
- Year selection in reports with 2024 as default

### Improved
- Database query handling with proper methods

## [1.0.3] - 2025-01-05

### Added
- Production deployment checklist:
    - Comprehensive guide for deployment
    - Security configuration steps
    - Database setup instructions
    - Feature flags management
    - Environmental variables guide
    - File permissions checklist
    - Backup procedures
- Configurable feature warnings system:
    - Centralized configuration in config.php
    - Per-feature enable/disable flags
    - Customizable warning messages
    - Configurable alert styles
    - Optional dismissible alerts
    - Applied to login, register and contact pages
    - Consistent styling with Bootstrap alerts
    - Clear user feedback about feature status
### Enhanced
- Made toast notification system configurable:

### Improved
- Enhanced carousel image sizing and responsiveness:
    - Better fit within screen dimensions
    - Maintained aspect ratio and quality
    - Improved mobile display
- Enhanced social media links:
    - Links now open in new tabs
    - Added security attributes
    - Consistent behavior across the site
- Made toast notification system configurable:
    - Added configuration options for toast messages
    - Reduced code duplication with "coming soon" tags
    - Configurable message content and duration
    - Flexible toast type options (info, warning, success)

## [1.0.2] - 2025-01-04

### Bug Fixes
- Fixed PHP version compatibility issues in Database and Reports modules
- Improved path resolution for includes across environments
- Added debug mode and improved error handling in reports
- Updated DATABASE class to support older PHP versions

### Deployment
- Added FTP deployment system:
    - Automated deployment script
    - Environment-based configuration
    - Secure credential management
    - File exclusion system

### Code Organization and Configuration
- Added centralized initialization system:
    - New init.php for consistent application bootstrapping
    - Standardized includes across all pages
    - Centralized configuration loading
- Enhanced configuration system:
    - Separate config template for easy setup
    - Environment-specific base paths
    - Production vs local URL handling
- Updated all pages to use new initialization system
- Improved project structure and documentation

## [1.0.1] - 2025-01-01

### Reports Module
- Added Appeal Summary report functionality:
    - Year-wise filtering for appeal data
    - Transaction matching analysis
    - Status-wise appeal summaries
    - Appeals without transactions tracking
- Implemented database infrastructure:
    - Singleton Database connection class
    - Secure configuration management
    - Query execution utilities
- Enhanced reports organization:
    - Centralized reports landing page
    - Modular report components
    - Consistent styling across reports
    - Analytics dashboard integration

## [1.0.0] - 2024-12-31
### UI/UX Updates
- Enhanced footer implementation:
    - Added sticky footer functionality
    - Maintained consistent green theme
    - Improved responsive behavior
    - Better user experience on all page lengths
    - Integrated social media icons (Facebook, Twitter, Instagram)
- Modified body structure for flexible content layout:
    - Added flex column display
    - Set minimum viewport height
    - Better content distribution
- Updated organization branding:
    - Changed name from SHaDE-nextGen to SHaDE
    - Added full form: Share, Help and ADorE
    - Updated all references for consistency
- Added toast notification system:
    - Work-in-progress status indicator
    - Auto-show on page load
    - Dismissible notifications
    - Consistent green theme styling
- Enhanced navigation tooltips:
    - Added informative tooltips for Login/Register links
    - Clear user feedback about upcoming functionality
    - Improved user experience with status indicators
### UI/UX Updates
- Enhanced visual content:
    - Added meaningful charity and education images to carousel
    - Improved carousel captions with semi-transparent backgrounds
    - Added detailed alt text for accessibility
    - Updated image descriptions to reflect actual initiatives

### Technical Details
- Added Unsplash images for authentic representation
- Updated download script for specific images
- Enhanced carousel caption styling
- Improved image naming convention
### Added
- Initial project setup with Bootstrap integration
- Directory structure:
- `/assets/` - Static files directory
    - `/css/` - Bootstrap CSS and custom styles
    - `/js/` - Bootstrap JavaScript files
    - `/images/` - Carousel images and other media
- `/includes/` - PHP components
    - `header.php` - Navigation bar and common header
    - `footer.php` - Common footer components
- `/pages/` - Individual page templates

- Core Pages:
- `index.php` - Home page featuring:
    - Bootstrap Jumbotron with welcome message
    - Carousel showcasing key features
    - Responsive layout with green theme
- `pages/about.php` - Organization overview and mission
- `pages/contact.php` - Contact form and information
- `pages/reports.php` - Reports dashboard and statistics
- `pages/login.php` - User authentication form
- `pages/register.php` - New user registration

- Features:
- Responsive navigation with Bootstrap
- Green theme implementation
- Local Bootstrap asset integration
- Mobile-friendly layout
- Contact form with validation
- User authentication forms
- Reports dashboard structure

- Documentation:
- `README.md` - Project documentation
- `ReadMe-Jira-EpicVsStory-GithubBranches.md` - Git workflow guide
- `CHANGELOG.md` - Project history
- `LICENSE` - Project license

### Organization Updates
- Added actual team member information:
    - Leadership roles and responsibilities
    - Technical team composition
    - Administrative team members
- Updated organizational history and facts:
    - Founded in 2008
    - 200+ current members
    - Tamil Nadu-based operations
    - Multi-state beneficiary coverage
    - Partnerships with Indian NGOs
    - International funding sources

### Technical Details
- Bootstrap 5 integration
- PHP 7+ compatibility
- Local asset management:
    - Added Bootstrap Icons local setup
    - Configured font files and CSS
    - Optimized asset loading
- Modular PHP structure with includes
- Form validation implementation
- Responsive design principles
- User feedback mechanisms:
    - Toast notifications
    - Navigation tooltips
    - Status indicators
## [0.1.0] - 2024-08-01

### Added
- Initial repository setup
- Git branching strategy documentation
- Basic project structure
- Development environment configuration

