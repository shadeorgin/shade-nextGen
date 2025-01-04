# SHaDE NextGen
A repo to track the nextGen version of SHaDE Website and the portal (https://shade.org.in)

## Environment Setup

### Local Development
1. Clone the repository
2. In `includes/config.php`, ensure `IS_PRODUCTION` is set to `false`:
```php
define('IS_PRODUCTION', false);
```
This will set BASE_URL to '/' for local development.

Local URLs will be:
- Home: http://localhost:8000/
- Reports: http://localhost:8000/analytics/
- Pages: http://localhost:8000/pages/

### Production Deployment
1. Deploy the code to the production server under /reports/ directory
2. In `includes/config.php`, set `IS_PRODUCTION` to `true`:
```php
define('IS_PRODUCTION', true);
```
This will set BASE_URL to '/reports/' for production.

Production URLs will be:
- Home: http://shade.org.in/reports/
- Reports: http://shade.org.in/reports/analytics/
- Pages: http://shade.org.in/reports/pages/

## Project Structure
```
/
├── assets/          # Static files (CSS, JS, images)
├── includes/        # PHP components and configuration
│   ├── config.php   # Environment and database configuration
│   ├── header.php
│   └── footer.php
├── pages/           # Page templates
└── analytics/       # Reports and analytics module
    └── includes/    # Analytics-specific utilities
```

## Development
- Use PHP 7+ for development
- Bootstrap 5 for frontend
- Local MySQL database for development
