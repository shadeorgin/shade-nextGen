# Chart.js Implementation Guide for PHP

## Table of Contents
1. [Introduction](#introduction)
2. [Setup](#setup)
3. [Basic Usage](#basic-usage)
4. [Examples](#examples)
5. [Best Practices](#best-practices)

## Introduction
This guide explains how to implement Chart.js with PHP to create dynamic, responsive charts in your web applications. Chart.js is a flexible JavaScript charting library that can be easily integrated with PHP backend systems.

## Setup

### 1. Include Chart.js
Add Chart.js to your project using either CDN or npm:

```html
<!-- Via CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Or via npm -->
npm install chart.js
```

### 2. PHP Setup
Ensure you have a PHP environment ready (PHP 7.0+).

## Basic Usage

### PHP Data Preparation
```php
<?php
// Example of preparing data in PHP
$data = [
    'labels' => ['January', 'February', 'March'],
    'datasets' => [
        [
            'label' => 'Sales',
            'data' => [12, 19, 3]
        ]
    ]
];

// Convert to JSON for JavaScript
$chartData = json_encode($data);
?>
```

### HTML/JavaScript Implementation
```html
<canvas id="myChart"></canvas>
<script>
const ctx = document.getElementById('myChart').getContext('2d');
const data = <?php echo $chartData; ?>;

new Chart(ctx, {
    type: 'line',
    data: data,
    options: {
        responsive: true
    }
});
</script>
```

## Examples

### 1. Line Chart with Database Data
```php
<?php
// Fetch data from database
$query = "SELECT month, sales FROM monthly_sales";
$result = $mysqli->query($query);

$labels = [];
$sales = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['month'];
    $sales[] = $row['sales'];
}

$chartData = [
    'labels' => $labels,
    'datasets' => [
        [
            'label' => 'Monthly Sales',
            'data' => $sales,
            'borderColor' => 'rgb(75, 192, 192)',
            'tension' => 0.1
        ]
    ]
];
?>
```

### 2. Bar Chart with Dynamic Data
```php
<?php
// Dynamic data generation
$categories = ['Food', 'Transport', 'Entertainment'];
$expenses = [250, 100, 175];

$chartData = [
    'labels' => $categories,
    'datasets' => [
        [
            'label' => 'Monthly Expenses',
            'data' => $expenses,
            'backgroundColor' => [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)'
            ]
        ]
    ]
];
?>
```

## Best Practices

1. **Data Sanitization**
- Always sanitize data before passing it to JavaScript
- Use appropriate PHP functions like `htmlspecialchars()` for string data

2. **Performance**
- Cache database queries when possible
- Limit the amount of data points to maintain performance
- Consider using AJAX for large datasets

3. **Responsive Design**
- Always set the responsive option to true
- Use appropriate container sizing
- Consider setting maintainAspectRatio based on your needs

4. **Error Handling**
```php
<?php
try {
    $chartData = json_encode($data);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('JSON encoding failed');
    }
} catch (Exception $e) {
    // Handle error appropriately
    error_log($e->getMessage());
}
?>
```

