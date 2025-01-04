<?php
/**
 * Common utilities for reports
 */

// Configuration for default year behavior
define('USE_PREVIOUS_YEAR_AS_DEFAULT', true);  // Set this to false after a few months to revert to current year
define('SWITCH_TO_CURRENT_YEAR_MONTH', 3);     // Month when we start showing current year as default (if flag is false)

/**
 * Get default year based on configuration
 * @return int The default year to use
 */
function getDefaultYear() {
    $currentYear = date('Y');
    $currentMonth = date('n');
    
    if (USE_PREVIOUS_YEAR_AS_DEFAULT || $currentMonth < SWITCH_TO_CURRENT_YEAR_MONTH) {
        return $currentYear - 1;
    }
    return $currentYear;
}

/**
 * Format number in Indian currency format
 * @param float $number The number to format
 * @return string Formatted number with Indian separators
 */
function formatIndianCurrency($number) {
    $decimal = (string)($number - floor($number));
    $decimal = substr($decimal, 2, 2); // Get 2 decimal places
    $number = floor($number);
    
    $len = strlen($number);
    $m = '';
    $number = strrev($number);
    for($i=0;$i<$len;$i++) {
        if(($i==3 || $i==5 || $i==7 || $i==9) && $i!=0) {
            $m .=',';
        }
        $m .=$number[$i];
    }
    $result = strrev($m);
    return $result . ($decimal ? ".$decimal" : ".00");
}

/**
 * Get year range for dropdowns
 * @param int $numberOfYears Number of past years to include
 * @return array Array of years
 */
function getYearRange($numberOfYears = 5) {
    $currentYear = date('Y');
    return range($currentYear, $currentYear - $numberOfYears);
}
