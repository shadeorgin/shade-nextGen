# Chart.js Local Setup Guide

## 1. Introduction

### Why Local Setup vs CDN?

While CDN provides an easy way to include Chart.js in your project, local setup offers several advantages:

- **Reliability**: No dependency on external services
- **Offline Development**: Work without internet connection
- **Performance**: Faster load times for local development
- **Version Control**: Better control over Chart.js versions
- **Security**: No external script dependencies

## 2. Setup Steps

### 2.1 Download Process

You can download Chart.js in two ways:

#### Manual Download
```bash
# Using curl
curl -o assets/js/chart.min.js https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js
```

#### Using download_assets.sh
```bash
# The script includes Chart.js download
./download_assets.sh
```

### 2.2 Configuration

In `includes/config.php`:
```php
// Toggle between local and CDN versions
define('USE_LOCAL_CHARTJS', true);
define('CHARTJS_VERSION', '4.3.0');
```

## 3. Implementation Examples

### Local Setup Usage
```php
<?php if (USE_LOCAL_CHARTJS): ?>
    <script src="assets/js/chart.min.js"></script>
<?php else: ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@<?php echo CHARTJS_VERSION ?>/dist/chart.umd.min.js"></script>
<?php endif; ?>
```

### Basic Chart Implementation
```javascript
const ctx = document.getElementById('myChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Red', 'Blue', 'Yellow'],
        datasets: [{
            label: 'Sample Data',
            data: [12, 19, 3]
        }]
    }
});
```

## 4. Configuration Best Practices

### 4.1 File Organization
```
assets/
├── js/
│   ├── chart.min.js
│   └── chart.min.js.map (optional)
```

### 4.2 Version Management
- Keep version number in config.php
- Document version changes in CHANGELOG.md
- Test thoroughly when updating versions

### 4.3 Performance Optimization
- Load Chart.js in the footer
- Use defer attribute when possible
- Consider bundling with other JS files

## 5. Troubleshooting Tips

### Common Issues and Solutions

#### Chart Not Loading
- Verify file path is correct
- Check browser console for errors
- Ensure Chart.js loads before your chart initialization

#### Version Mismatch
- Compare local vs CDN versions
- Check browser console for compatibility warnings
- Update configuration version number

#### Performance Issues
- Verify file size and loading time
- Consider using production (minified) version
- Check browser caching settings

### Support Resources
- [Official Chart.js Documentation](https://www.chartjs.org/docs/latest/)
- [GitHub Issues](https://github.com/chartjs/Chart.js/issues)
- Project's internal documentation

## 6. Maintenance

Regular maintenance tasks:
1. Check for Chart.js updates
2. Update local files when needed
3. Test functionality after updates
4. Update version numbers in config
5. Document changes in CHANGELOG.md

