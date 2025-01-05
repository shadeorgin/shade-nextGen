# SHADE Project URL Structure Documentation

This guide documents the URL structures and navigation paths for both local development and production environments of the SHADE project.

## Directory Structures

### Local Development Environment
```
project/
├── includes/
│   ├── header.php
│   ├── init.php
│   └── footer.php
├── reports/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
└── config/
    └── config.php
```

### Production Environment
```
/var/www/html/shade-nextGen/
├── includes/
│   ├── header.php
│   ├── init.php
│   └── footer.php
├── reports/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
└── config/
    └── config.php
```

## URL Patterns

### Local Environment
- Base URL: `http://localhost/shade-nextGen`

Navigation Paths:
- Home: `http://localhost/shade-nextGen/`
- About: `http://localhost/shade-nextGen/about`
- Contact: `http://localhost/shade-nextGen/contact` (Coming Soon)
- Reports: `http://localhost/shade-nextGen/reports`
- Login: `http://localhost/shade-nextGen/login` (Coming Soon)
- Register: `http://localhost/shade-nextGen/register` (Coming Soon)

### Production Environment
- Base URL: `http://shade.org.in/shade-nextGen`

Navigation Paths:
- Home: `http://shade.org.in/shade-nextGen/`
- About: `http://shade.org.in/shade-nextGen/about`
- Contact: `http://shade.org.in/shade-nextGen/contact` (Coming Soon)
- Reports: `http://shade.org.in/shade-nextGen/reports`
- Login: `http://shade.org.in/shade-nextGen/login` (Coming Soon)
- Register: `http://shade.org.in/shade-nextGen/register` (Coming Soon)

## Example URLs

### Local Examples
1. Home Page:
```
http://localhost/shade-nextGen/
```
2. Reports:
```
http://localhost/shade-nextGen/reports
```

### Production Examples
1. Home Page:
```
http://shade.org.in/shade-nextGen/
```
2. Reports:
```
http://shade.org.in/shade-nextGen/reports
```

## Environment-Specific Configurations

### Local Environment
- Configuration file: `config/config.php`
- Local database settings
- Development mode settings
- Local file paths

### Production Environment
- Configuration file: `config/config.php`
- Production database credentials
- Production mode settings
- Absolute file paths for shade.org.in

## Common Issues and Troubleshooting

### File Paths
- Check if paths in config.php are correctly set for each environment
- Verify file permissions in production
- Ensure all includes use the correct path resolution

### Database Connection
- Verify database credentials in config files
- Check database server connectivity
- Ensure proper database user permissions

### URL Resolution
- Confirm .htaccess configuration
- Check for proper base path in all links
- Verify file and directory permissions

## Notes
- Always use relative paths in PHP includes
- Keep configuration files updated for each environment
- Regular backup of production data
- Update this documentation when adding new paths or features
- Test all paths in local before deploying to production

## Navigation Structure
The SHADE project implements a consistent navigation structure across both local and production environments:

1. **Main Navigation**
- Home: Landing page with project overview
- About: Project information and details
- Contact: Contact form and information (Coming Soon)
- Reports: Access to all project reports
- Login: User authentication (Coming Soon)
- Register: New user registration (Coming Soon)

2. **URL Pattern Convention**
- All URLs maintain consistency between environments
- Base path differs: `localhost/shade-nextGen` vs `shade.org.in/shade-nextGen`
- Clean URL structure without file extensions
- Feature availability indicated with "Coming Soon" status

3. **Access Control**
- Public access: Home, About
- Authentication required: Reports (when implemented)
- Under development: Contact, Login, Register
