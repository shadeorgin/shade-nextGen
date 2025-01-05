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
1. Deploy the code to the production server under /SHaDE-nextGen directory
2. Copy includes/config.template.php to config.php if not exists
3. In config.php, set `IS_PRODUCTION` to `true`:
```php
define('IS_PRODUCTION', true);
define('PROD_BASE_PATH', '/SHaDE-nextGen');
define('LOCAL_BASE_PATH', '');
```
This will set BASE_URL to '/SHaDE-nextGen' for production.

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
└── reports/         # Reports module
    └── includes/    # Reports-specific utilities
```

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
The project includes an automated FTP deployment system located in the `ftp` directory.

### Quick Start
1. Navigate to ftp directory:
```bash
cd ftp
```

2. Set up deployment configuration:
```bash
cp .env.template .env
# Edit .env with your FTP credentials
```

3. Run deployment:
```bash
./deploy.sh
```

For detailed deployment instructions, see the [FTP Deployment Guide](ftp/README.md).
