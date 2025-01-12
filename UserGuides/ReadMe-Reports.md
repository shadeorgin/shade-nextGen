# Analytics Dashboard Reports Guide

## Overview
This guide provides detailed information about the various reports available in the Analytics Dashboard, including their visualizations and control options.

## Reports

### 1. Appeals Distribution Report
**Purpose**: Visualizes the distribution of appeals across different status categories.

**Visualization**:
- Bar chart representation
- Interactive legend - click to show/hide specific data series
- X-axis: Appeal status categories
- Y-axis: Two metrics displayed
    * Number of Appeals
    * Total Amount (₹)

**Controls**: No specific toggle controls available

### 2. Geographic Distribution Report
**Purpose**: Displays the regional distribution of data across states.

**Visualization**:
- Horizontal bar chart for improved readability
- Interactive legend to toggle visibility of different metrics
- Metrics displayed:
    * Number of Beneficiaries per state
    * Number of Appeals per state

**Controls**: No specific toggle controls available

### 3. Category Distribution Report
**Purpose**: Shows the distribution of beneficiaries across different categories.

**Visualization**:
- Interactive legend for toggling category visibility
- Dual visualization options:
    * Simple View: Pie chart showing beneficiary count by category
    * Detailed View: Bar chart showing beneficiary count by category and type
- Color-coded categories
- Right-aligned legend

**Controls**:
- Toggle button: "Show Details/Show Simple View"
    * Switches between simple and detailed visualization modes

### 4. Monthly Transaction Count Report
**Purpose**: Tracks transaction trends over time.

**Visualization**:
- Interactive legend for toggling different transaction series
- Two display modes:
    * Simple View: Single line chart of total transactions per month
    * Detailed View: Multiple line chart showing transactions by target account
- Month labels in short format (Jan, Feb, etc.)
- Distinct colors for different target accounts in detailed view

**Controls**:
- Toggle button: "Show Details/Show Simple View"
    * Switches between simple and detailed visualization modes

### 5. Monthly CR vs DR Report
**Purpose**: Compares monthly credit (C) and debit (D) transactions with net balance trend.

**Visualization**:
- Hybrid visualization combining:
    * Bar chart for Credit (C) in green and Debit (D) in red
    * Line chart overlay for Net Balance trend in blue
    * Monthly progression on X-axis
    * Amount scale on Y-axis (₹)

**Interactive Features**:
- Click legend items to toggle:
    * Credit amount bars
    * Debit amount bars
    * Net balance line
- Hover over elements for detailed values
- Developer mode shows debug information panel

**Controls**:
- Year selector at dashboard top
- Toggleable legends for each data series
- Debug accordion in development environment

## Common Features
All reports include the following features:

- **Year Selection**: Filter at the top to select specific years
- **Responsive Design**: Charts maintain aspect ratio across different screen sizes
- **Consistent Typography**:
    * Legend text: 16px
    * Axis labels: 16px
    * Titles: 18px
- **Interactive Elements**: 
    - **Interactive Elements**: 
        * Hover functionality for detailed data points
        * Interactive legends where clicking a legend item removes that specific data series from the visualization while keeping others visible
        * Legend items can be clicked again to restore visibility
    - **Navigation**: Back button to return to main reports page

    Note: The interactive legend behavior applies to all charts - clicking any legend item will remove that specific data series from view while keeping others visible. This allows for focused analysis of specific metrics.
## Using the Dashboard
1. Select the desired report from the main dashboard
2. Use the year selector to filter data as needed
3. For reports with toggle controls, use the "Show Details/Show Simple View" button to switch between visualization modes
4. Hover over data points for detailed information
5. Use the back button to return to the main dashboard

