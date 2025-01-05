# SHaDE NextGen
A repo to track the nextGen version of SHaDE Website and the portal (https://shade.org.in)

## Environment Setup

### Local Development
1. Clone the repository
2. Copy includes/config.template.php to includes/config.php
3. In config.php, ensure `IS_PRODUCTION` is set to `false`:
```php
define('IS_PRODUCTION', false);
define('PROD_BASE_PATH', '/SHaDE-nextGen');
define('LOCAL_BASE_PATH', '');
```
This will set BASE_URL to '' for local development.

Local URLs will be:
- Home: http://localhost:8000/
- Reports: http://localhost:8000/reports/
- Pages: http://localhost:8000/pages/

### Production Deployment
Before deploying to production, please review the [Production Deployment Checklist](PRODUCTION_CHECKLIST.md) for a comprehensive guide on:
- Configuration settings and security checks
- Database configuration
- Feature flags management
- Environmental variables
- Security settings
- File permissions
- Backup procedures

Basic deployment steps:
1. Deploy the code to the production server under /SHaDE-nextGen directory
2. Copy includes/config.template.php to config.php if not exists
3. Follow the production checklist to configure all settings
4. In config.php, set `IS_PRODUCTION` to `true`:
```php
define('IS_PRODUCTION', true);
define('PROD_BASE_PATH', '/SHaDE-nextGen');
define('LOCAL_BASE_PATH', '');
```
This will set BASE_URL to '/SHaDE-nextGen' for production.
### Component Configurations

#### Carousel Configuration
The carousel component is configured for optimal viewing:
```css
.carousel {
max-width: 1200px;  /* Contained width */
margin: auto;
}
.carousel-item img {
max-height: 500px;  /* Controlled height */
object-fit: cover;  /* Maintain aspect ratio */
}
```

#### Feature Warnings Configuration
The application includes a configurable warning system for upcoming features:
```php
$FEATURE_WARNINGS = [
    'enabled' => true,  // Master switch for all warning messages
    'features' => [
        'login' => [
            'enabled' => true,
            'message' => 'Authentication system coming soon!'
        ],
        // Add more features as needed
    ],
    'style' => 'warning',     // Bootstrap alert style
    'dismissible' => true     // Allow dismissing alerts
];
```

Available configuration options:
- Global enable/disable switch
- Per-feature configuration
- Customizable messages
- Configurable alert styles
- Optional dismissible alerts

Warnings are displayed on:
- Login page
- Register page
- Contact form
- Other feature pages as configured
#### Social Media Links
Social media links are configured to:
- Open in new tabs (target="_blank")
- Include security attributes (rel="noopener noreferrer")
- Maintain consistent behavior across pages

#### Toast Configuration
#### Report Year Selection
Reports include a standardized year selection dropdown:
- Consistent styling across all reports
- Bootstrap form-select implementation
- Clear labeling for better UX
- Automatic form submission on change

The application includes a configurable toast notification system. Configure toast messages in `config.php`:"
```php
$config['toast'] = [
    'enabled' => true,
    'message' => 'Feature coming soon!',
    'type' => 'info',
    'duration' => 5000  // milliseconds
];
```

Production URLs will be:
- Home: http://shade.org.in/SHaDE-nextGen/
- Reports: http://shade.org.in/SHaDE-nextGen/reports/
- Pages: http://shade.org.in/SHaDE-nextGen/pages/

## Project Structure
```
/
├── assets/          # Static files (CSS, JS, images)
├── includes/        # PHP components and configuration
│   ├── config.template.php  # Configuration template
│   ├── config.php   # Local configuration (not in git)
│   ├── init.php     # Application initializer
│   ├── header.php   # Common header
│   └── footer.php   # Common footer 
├── pages/           # Page templates
├── reports/         # Reports module
│   ├── includes/    # Reports-specific utilities
│   └── sql/        # SQL query files for reports
└── ftp/            # Deployment configuration
    ├── .env.template  # FTP credentials template
    ├── .ftpignore    # Files to exclude from deployment
    └── deploy.sh     # Deployment script
```

## Debugging and Compatibility
- Debug mode available in reports (controlled by DEBUG_MODE flag)
- Support for PHP 5.x and newer versions
- Environment-specific configurations with proper path handling
- Year-based filtering in reports (2024 as default)
- SQL file organization in reports/sql/
- Improved database query handling with proper methods

## Development
- Use PHP 7+ for development
- Bootstrap 5 for frontend  
- Local MySQL database for development

## File Organization
- All pages include init.php which handles:
- Configuration loading
- Database initialization
- Common header inclusion

## Deployment
The project includes an FTP deployment system in the `ftp` directory.
See [FTP Deployment Guide](ftp/README.md) for detailed deployment instructions.

Quick start:
```bash
cd ftp
cp .env.template .env    # Create and configure environment file
chmod +x deploy.sh       # Make script executable
./deploy.sh              # Run deployment
```
