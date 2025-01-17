# SHaDE NextGen

A repo to track the nextGen version of SHaDE Website and the portal (https://shade.org.in)

## Introduction

SHaDE (Share, Help and ADorE) is a non-profit organization dedicated to making a positive impact through welfare activities. Our mission is to create meaningful change by sharing resources, helping those in need, and fostering an environment of care and support in our communities.

Our latest enhancements include an interactive "What's New" section showcasing automated reporting capabilities and improved state-wise coverage visualization, making it easier to track our impact across India.

Through our online platform, we streamline and organize our welfare initiatives, making it easier for volunteers, donors, and beneficiaries to connect and collaborate effectively.
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
The carousel component is configured for optimal viewing with enhanced image handling:
```css
.carousel {
    max-width: 1200px;  /* Contained width */
    margin: auto;
}
.carousel-item img {
    max-height: 500px;  /* Controlled height */
    object-fit: cover;  /* Default image fitting */
}
.carousel-item.info-slide img {
    object-fit: contain;  /* Special handling for information slides */
    background: white;    /* Clean background for visibility */
}
```

Special configurations include:
- Adaptive image handling for different content types
- Information slides with improved readability
- Responsive design for all screen sizes
- Optimized loading for high-resolution visuals

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

## Generic SHaDE Appeal (AppealId 0)
Generic SHaDE Appeal is a special case in the system that:
- Represents general food-related appeals
- Always shown with 'In-Progress' status
- Included in all appeal-related analytics and reports
- Used for transactions that don't belong to specific appeals

### How it's handled
- In Analytics Dashboard: Shows under 'In-Progress' status in Appeals Distribution
- In Transaction Summary: Listed as "Generic SHaDE Appeal"
- For statistical purposes: Counted as a single appeal

### Development
- Login page
- Register page
- Contact form
- Other feature pages as configured
#### Chart.js Configuration
The Chart.js library can be configured to use either local files or CDN:
```php
// In config.php
define('USE_LOCAL_CHARTJS', true);  // Set to false to use CDN version
```

Local setup path:
```
assets/js/
└── chart.min.js   # Local Chart.js library
```

Usage in templates:
```php
<?php if (USE_LOCAL_CHARTJS): ?>
    <script src="<?php echo BASE_URL; ?>/assets/js/chart.min.js"></script>
<?php else: ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php endif; ?>
```

- Layout

### Analytics Dashboard Features
- Interactive charts for data visualization with detailed usage guides:
- Collapsible help sections for each chart
- Step-by-step usage instructions
- Feature-specific controls documentation
- Interactive legend functionality across all charts:
- Click legend items to show/hide specific data series
- Dynamically update chart visualization
- Maintain visibility of unselected data points
- Monthly Transaction visualization:
- Credit (C) transactions shown in green bars
- Debit (D) transactions shown in red bars
- Net balance overlay as blue line
- Interactive legend control for each metric
- Detailed tooltips with transaction amounts
- Toggleable Monthly Transaction count view:
- Simple View: Shows total transactions per month (default)
- Detailed View: Breaks down transactions by account
- Toggleable category distribution view:
- Simple View: High-level category overview (default)
- Detailed View: Detailed breakdown with subcategories
- Consistent color scheme across charts for better readability
- Intuitive user interface with embedded documentation
- Interactive help sections with visual guides
The analytics dashboard uses Chart.js for data visualization with configurable font sizes that can be customized through config.php for optimal readability:

```php
// In config.php
define('CHART_LEGEND_FONT_SIZE', 16);    // Font size for chart legends (default: 16px)
define('CHART_TITLE_FONT_SIZE', 18);     // Font size for chart titles (default: 18px)
define('CHART_AXIS_FONT_SIZE', 16);      // Font size for axis labels (default: 16px)
```

### Reports and Analytics
- Analytics Dashboard improvements:
    - Optimized SQL queries for better performance
    - Fixed syntax issues in appeals distribution queries
    - Enhanced data filtering for accurate reporting
    - Added state coverage visualization
    - Interactive and static coverage maps

- State Coverage Visualization:
    - Static heat map showing 2024 impact across India
    - Comprehensive state-wise activity tracking
    - Visual representation of beneficiary distribution
    - Future support for interactive mapping
    - Exportable coverage data for reports
- Standardized report badge indicators:
    - Status-wise counts with descriptive labels
    - Consistent styling and placement
    - Grouped status displays with count summaries
- Unified report styling:
    - Standardized header badge displays
    - Consistent count presentation
    - Clear visual hierarchy
    - Improved readability across summaries
- Comprehensive transaction summaries with:
    - Total amounts (Credit, Debit, Balance) for each summary type
    - Proper handling of Generic SHaDE Appeal transactions
    - Exclusion of miscellaneous transactions
    - Clear separation of internal and external transactions

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
            position: 'top',
            labels: {
                font: {
                    size: 16  // Standardized legend font size
                }
            }
        },
        title: {
            display: true,
            text: 'Analytics Overview',
            font: {
                size: 18  // Larger title font size
            }
        }
    },
    scales: {
        x: {
            ticks: {
                font: {
                    size: 16  // Standardized x-axis labels
                }
            }
        },
        y: {
            ticks: {
                font: {
                    size: 16  // Standardized y-axis labels
                }
            }
        }
    }
};
```

Features include:
- Responsive chart layouts
- Customizable data views
- Interactive tooltips
- CSV data export
- Dynamic data filtering

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
│   ├── includes/     # Reports-specific utilities
│   ├── analytics/    # Analytics dashboard components
│   │   ├── charts/   # Chart.js implementations
│   │   └── data/     # Data processing scripts
│   └── sql/         # SQL query files for reports
│       ├── 01_AppealSummary.sql
│       ├── 02_CausewiseSummary.sql
│       └── 03_BeneficiarywiseSummary.sql   # Beneficiary analysis queries
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

## Documentation
- [Chart.js Integration Guide](UserGuides/ReadMe-Chart.js-PHP.md) - Guide for implementing interactive charts using Chart.js in PHP applications
- [Analytics Dashboard Guide](UserGuides/analytics-dashboard.md) - Documentation for using and customizing the analytics dashboard

## File Organization
- All pages include init.php which handles:
- Configuration loading
- Database initialization
- Common header inclusion

## Deployment
The project includes an FTP deployment system in the `ftp` directory.
See [FTP Deployment Guide](ftp/README.md) for detailed deployment instructions.

## Contributing

We welcome contributions from developers of all skill levels! Here's how you can help:

1. Fork the repository
2. Create a new branch for your feature or bugfix
3. Write clear, concise commit messages
4. Test your changes thoroughly
5. Submit a Pull Request with a description of your changes

Please ensure your code follows our existing coding style and includes appropriate documentation.
For major changes, please open an issue first to discuss what you would like to change.

Quick start:
```bash
cd ftp
cp .env.template .env    # Create and configure environment file
chmod +x deploy.sh       # Make script executable
./deploy.sh              # Run deployment
```
