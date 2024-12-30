# Grep Context Control Options Guide

## Overview
When searching through code or logs with grep, you often need to see the surrounding context of a match. Grep provides three powerful options for controlling how much context to display around matching lines.

## Basic Context Control Options

### 1. After Context (-A)
Shows N lines after the match
```bash
grep -A 3 "pattern" file.txt  # Shows 3 lines after each match
```

### 2. Before Context (-B)
Shows N lines before the match
```bash
grep -B 2 "pattern" file.txt  # Shows 2 lines before each match
```

### 3. Context on Both Sides (-C)
Shows N lines before and after the match
```bash
grep -C 2 "pattern" file.txt  # Shows 2 lines before and after
```

## Practical Examples

### 1. Debugging Stack Traces
```bash
# Find error and show next 10 lines of stack trace
grep -A 10 "Exception" error.log
```

### 2. Code Review
```bash
# Find function definitions and their implementations
grep -A 5 "function.*(" file.php

# Find class definitions with their properties
grep -B 2 -A 5 "class.*{" *.php
```

### 3. Configuration Analysis
```bash
# Find database configuration block
grep -C 3 "DB_HOST" .env*
```

## Real Development Scenarios

### 1. Database Schema Analysis
```bash
# Find ENUM definitions and their values
grep -A 5 "ENUM" schema.sql

# Find table creation with indexes
grep -A 10 "CREATE TABLE" *.sql
```

### 2. Log Analysis
```bash
# Find failed login attempts with context
grep -B 2 -A 1 "Login failed" auth.log

# Track request flow around errors
grep -C 5 "ERROR" application.log
```

### 3. Code Refactoring
```bash
# Find method usage with implementation
grep -A 10 "function updateUser" *.php

# Find related test cases
grep -B 1 -A 3 "@test.*user" *Test.php
```

## Tips and Tricks

### 1. Multiple Pattern Matching
```bash
# Find patterns with different context lengths
grep -A 2 "pattern1" file.txt | grep -B 1 "pattern2"
```

### 2. Separator Control
```bash
# Custom separator between contexts
grep -A 3 --group-separator="=========" "pattern" file.txt
```

### 3. Combining with Other Options
```bash
# Case-insensitive search with context
grep -i -C 2 "error" log.txt

# Line numbers with context
grep -n -A 3 "TODO" *.php
```

## Common Patterns and Best Practices

### 1. Optimal Context Length
- Stack traces: -A 10 to -A 15
- Function definitions: -A 5
- Configuration blocks: -C 3
- Error messages: -C 2

### 2. File Type Specific
```bash
# PHP Files
grep -A 5 "class.*{" --include="*.php" -r .

# Log Files
grep -B 1 -A 5 "ERROR" --include="*.log" -r /var/log/
```

### 3. Version Control Integration
```bash
# Find changes in git history
git grep -A 3 "pattern" $(git rev-list --all)

# Search in specific branch
git grep -C 2 "pattern" branch_name
```

## Common Use Cases Table

| Scenario | Command | Use Case |
|----------|---------|----------|
| Stack Traces | `grep -A 10 "Exception"` | Debug error logs |
| Function Context | `grep -C 5 "function"` | Code review |
| Config Blocks | `grep -B 2 -A 5 "config"` | System setup |
| API Endpoints | `grep -A 3 "@Route"` | API documentation |
| Database Queries | `grep -C 3 "SELECT"` | Query optimization |

## Troubleshooting

### 1. Large Output Management
```bash
# Pipe to less for better navigation
grep -A 5 "pattern" large_file.log | less -R

# Save context output to file
grep -C 3 "pattern" source.log > context_output.txt
```

### 2. Common Issues
- Too much context: Adjust -A/-B/-C numbers
- Missing matches: Check case sensitivity (-i)
- Pattern not found: Verify regex syntax
- Overlapping contexts: Use --group-separator

