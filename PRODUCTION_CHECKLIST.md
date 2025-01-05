# Production Deployment Checklist

## Pre-Deployment Checklist
- [ ] Backup all production data
- [ ] Review and update all configuration files
- [ ] Check all feature flags
- [ ] Validate database connection settings
- [ ] Test in staging environment
- [ ] Verify SSL certificates
- [ ] Check server requirements

## Configuration Settings
### Database Configuration
- [ ] Update database credentials in `config.php`:
```php
$DB_CONFIG = [
    'host' => 'production_host',
    'username' => 'production_user',
    'password' => 'strong_password',
    'database' => 'production_db',
    'port' => '3306',
    'ssl' => true
];
```

### Feature Flags
- [ ] Review and update feature warnings in `config.php`:
```php
$FEATURE_WARNINGS = [
    'enabled' => false,  // Disable development warnings
    'features' => [
        'login' => ['enabled' => false],
        'register' => ['enabled' => false],
        'contact' => ['enabled' => false]
    ]
];
```

### Environmental Variables
- [ ] Set environment to 'production':
```php
define('ENVIRONMENT', 'production');
```
- [ ] Configure error reporting:
```php
error_reporting(E_ERROR);
display_errors(false);
```

## Security Settings
### File Permissions
```bash
# Set correct file permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod 400 config.php
chmod 400 .env
```

### Security Headers
- [ ] Enable HTTPS
- [ ] Set secure headers in `.htaccess`:
```apache
Header set X-Content-Type-Options "nosniff"
Header set X-Frame-Options "SAMEORIGIN"
Header set X-XSS-Protection "1; mode=block"
Header set Strict-Transport-Security "max-age=31536000; includeSubDomains"
```

### Access Control
- [ ] Review and restrict admin access
- [ ] Enable IP whitelisting if required
- [ ] Configure rate limiting
- [ ] Set up fail2ban

## Database Security
- [ ] Create dedicated production database user
- [ ] Grant minimum required privileges
- [ ] Enable SSL for database connection
- [ ] Regular backup schedule
- [ ] Configure backup retention policy

## Backup Procedures
```bash
# Daily database backup
mysqldump -u [user] -p [database] > backup_$(date +%Y%m%d).sql

# File backup
tar -czf backup_$(date +%Y%m%d).tar.gz /path/to/application

# Set backup rotation
find /backup/path -name "backup_*.sql" -mtime +7 -delete
find /backup/path -name "backup_*.tar.gz" -mtime +7 -delete
```

## Deployment Steps
1. **Pre-Deployment**
- [ ] Create deployment backup
- [ ] Put site in maintenance mode
- [ ] Test rollback procedure

2. **Configuration Update**
- [ ] Swap development config with production config
- [ ] Update database credentials
- [ ] Set correct file permissions
- [ ] Configure error logging

3. **Security Implementation**
- [ ] Enable HTTPS
- [ ] Set security headers
- [ ] Configure firewall rules
- [ ] Enable monitoring

4. **Post-Deployment**
- [ ] Test all critical functionality
- [ ] Verify logging is working
- [ ] Check error reporting
- [ ] Monitor system resources
- [ ] Test backup system
- [ ] Document deployment

## Monitoring Setup
- [ ] Configure error logging
- [ ] Set up performance monitoring
- [ ] Enable security audit logging
- [ ] Configure alert notifications

## Rollback Plan
1. Activate maintenance mode
2. Restore from backup:
```bash
mysql -u [user] -p [database] < backup_[date].sql
tar -xzf backup_[date].tar.gz -C /path/to/application
```
3. Verify restoration
4. Update DNS if needed
5. Deactivate maintenance mode

Keep this checklist updated with any specific requirements or changes for your production environment.

