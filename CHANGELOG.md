# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
- Added Kerala to Primary Operations states coverage
- Added new team members:
    - Khushboo Mantri (Administrative Team and Tech Lead - Database)
    - Sabarish Mahalingam (Technology Team - Database)
### Changed
- Reorganized states listing in Primary Operations to follow geographical proximity (south to north)
- Updated Operating States count from 3+ to 4+ in Quick Facts

### Added
- Version History feature:
    - New Version History page with complete release timeline
    - Historical milestones documentation from December 2024
    - Comprehensive version tracking with feature categorization
- Navigation menu:
    - Added Version History link in main navigation
    - Improved menu organization for better accessibility
- Documentation:
    - Added comprehensive Git Tags guide:
        - Tag types and best practices
        - Version numbering guidelines
        - Tags vs Releases comparison
        - Common tag operations with examples
        - Real-world tagging workflows
    - Enhanced project documentation with practical examples
- New visual content and features:
    - Added coverage heat map visualization for 2024
    - Added automated reports preview showcase
    - Added What's New preview image for feature highlights

### Changed
- Home page enhancements:
    - Updated homepage navigation with "Home" instead of "SHaDE"
    - Enhanced carousel with latest features showcase
    - Added "What's New" section showcasing automated reports

### Enhanced
- State coverage visualization improvements:
    - Added static coverage map for 2024
    - Preserved interactive map functionality for future use
- Improved carousel organization and content:
    - Added comprehensive Reports Overview slide
    - Organized slides in logical sequence: What's New → Coverage → Reports Overview → Automated Reports
    - Enhanced visual flow and user experience

### Changed
- Analytics Dashboard: Include Generic SHaDE Appeal (AppealId 0) in Appeals Distribution chart
- Shows as "In-Progress" status in the chart
- Contributes to total appeal counts and amounts
- Maintains consistency with Transaction Summary report
## [1.0.13] - 2025-01-15
### Fixed
- Fixed duplicate SQL query in Analytics Dashboard causing syntax error
- Improved SQL query structure in appeals status distribution report

### Enhanced
- Enhanced report displays with improved badge counts:
    - Added consistent badge styling across all reports
    - Improved status-wise grouping in Beneficiary Summary
    - Added detailed count indicators for status groups
    - Standardized badge display format across Transaction and Beneficiary summaries
- Standardized table styling across reports:
    - Unified badge display format in table headers
    - Simplified count displays in table rows
    - Consistent styling between Beneficiary and Causewise summaries
    - Improved visual hierarchy in data presentation
- Improved transaction reporting with totals:
    - Added Credit, Debit, and Balance totals in Transaction Summary tables
    - Included totals for Appeal-wise, Non-Internal, and COVID-specific transactions
- Enhanced appeal filtering across reports:
    - Moved appeal filtering from TblTxDetails to TblAppealInfo table
    - Standardized SQL operators from != to <> for better compatibility
    - Included Generic SHaDE Appeal (appealId = 0) in relevant summaries
    - Excluded Misc transactions (appealId -1) from analytics
- Added new Git documentation:
    - Guide for managing multiple Git remotes
    - Guide for Git no-pager option usage

## [1.0.12] - 2025-01-15
### Enhanced
- Improved SQL queries across all reports:
    - Excluded appealId -1 (Misc transactions) from all reports for accurate analytics
    - Changed appeal filtering from TblTxDetails to TblAppealInfo table
    - Standardized SQL operators from != to <> for better compatibility
    - Enhanced JOIN conditions in transaction-related queries
    - Modified appeal filtering in:
    - Analytics Dashboard
    - Appeal Summary
    - Beneficiary Summary
    - Causewise Summary
    - Transaction Summary
    - State Coverage Maps
## [1.0.11] - 2025-01-13
### Fixed
- Fixed Monthly CR vs DR chart data display:
    - Corrected transaction type codes (C/D) to match database values
    - Fixed data aggregation in balance calculations
    - Added proper NULL handling in aggregations
    - Updated chart labels to reflect correct transaction codes

## [1.0.10] - 2025-01-16
### Added
- Enhanced Monthly CR vs DR chart features:
    - Added net balance line overlay
    - Improved transaction data visualization
    - Combined bar and line chart representation
    - Color-coded credit and debit amounts
    - Debug information panel for development
    - Fixed balance calculations for accurate reporting

## [1.0.9] - 2025-01-15
### Added
- Added interactive chart documentation with collapsible guides:
    - Chart-specific help sections with usage instructions
    - Feature and control documentation for each visualization
    - Interactive legend usage guidelines
- Enhanced user interface with per-chart help sections:
    - Collapsible documentation panels
    - Clear visibility with light background styling
    - Improved accessibility with structured information
- Improved legend interaction descriptions:
    - Clear instructions for showing/hiding data series
    - Dynamic chart updating documentation
    - Visual feedback explanations
- Added detailed chart controls and features documentation:
    - Toggle functionality explanation
    - Data visualization options
    - Interaction capabilities

## [1.0.8] - 2025-01-12
### Added
- Enhanced Monthly Transaction count chart with toggle functionality:
    - Simple view showing total transactions per month
    - Detailed view breaking down transactions by account
    - Improved visualization with line charts
    - Interactive toggle button for switching views

### Enhanced
- Reorganized chart initialization code for better maintainability
- Improved code structure following the category chart pattern
- Consistent toggle button behavior across different chart types

## [1.0.7] - 2025-01-12
### Added
- Enhanced category distribution chart with:
    - Toggle between simple and detailed views
    - Improved color scheme with 12 distinct colors
    - Simple view as default for better initial comprehension
- Analytics Dashboard with interactive charts using Chart.js
- Fixed chart configuration with standardized font sizes through config.php:
    - Configurable global font size settings via constants
    - Default legend and axis font size set to 16px for better readability
    - Default title font size set to 18px for improved visibility
- Chart.js integration guide with implementation examples
- Visual representation of appeals and beneficiary data through charts
- Chart.js local setup implementation with configuration flags:
    - Added local Chart.js file under assets/js/
    - Configuration option to toggle between local and CDN versions
    - Documentation for local setup in UserGuides

### Enhanced
- Standardized Analytics Dashboard layout:
    - Integrated common header and footer components
    - Improved UI consistency with main application
    - Better navigation integration
- Improved chart readability with configurable font sizes:
    - Consistent and easily configurable text sizes
    - Enhanced visibility through standardized sizing
    - Better user experience with legible chart elements
- Consistent "Back to Reports" button positioning:
    - Fixed left-side placement across all report pages
    - Standardized layout for better navigation experience
    - Improved user interface consistency
## [1.0.6] - 2025-01-07

### Enhanced
- Improved Transaction Summary Reports:
    - Special handling of Appeal ID 0 as Generic SHaDE Appeal
    - Updated SQL queries to properly display Generic SHaDE Appeal name and status
    - Fixed null value handling in appeal information display
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.5] - 2025-01-06

### Added
- New Beneficiary Summary Report with the following features:
    - Status-wise beneficiary statistics
    - Detailed beneficiary listing with appeal and cause information
    - Year-based filtering with consistent dropdown styling
## [1.0.4] - 2025-01-06

### Added
- Causewise Summary Report with SQL file integration

### Enhanced
- Year selection in reports with 2024 as default
- Improved year selection dropdown in Causewise Summary Report with proper styling and label

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

