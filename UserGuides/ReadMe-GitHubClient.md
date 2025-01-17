# GitHub CLI (gh) Usage Guide

## Table of Contents
1. [Introduction](#introduction)
2. [Installation](#installation)
3. [Authentication](#authentication)
4. [Command Structure](#command-structure)
5. [Common Workflows](#common-workflows)
6. [Real-World Examples](#real-world-examples)

## Introduction

GitHub CLI (`gh`) is a command-line tool that brings GitHub functionality to your terminal, enabling streamlined workflows and automation of common tasks.

```
GitHub CLI
├── Authentication
│   ├── Login
│   └── Token Management
├── Pull Requests
│   ├── Create
│   ├── View
│   ├── Edit
│   └── Review
├── Issues
│   ├── Create
│   ├── List
│   └── Comment
└── Repository
    ├── Clone
    ├── Fork
    └── Create
```

## Installation

### MacOS
```bash
brew install gh
```

### Verification
```bash
gh --version
# gh version 2.64.0 (2024-12-18)
```

## Authentication

Authentication Flow:
```
Start
  │
  ▼
gh auth login
  │
  ├── Select auth method
  │   ├── HTTPS
  │   └── SSH
  │
  ├── Choose account
  │   ├── GitHub.com
  │   └── Enterprise
  │
  └── Token storage
      └── Secure keychain
```

### Setup
```bash
gh auth login
gh auth status  # Verify connection
```

## Command Structure

Basic Command Pattern:
```
gh <command> <subcommand> [flags]

Examples:
gh pr create
gh pr view
gh issue list
```

## Common Workflows

### Pull Request Management
```
PR Workflow
├── Creation
│   gh pr create --base <branch> --title "title" --body "description"
│
├── Review
│   ├── gh pr view
│   ├── gh pr list
│   └── gh pr checks
│
├── Modification
│   ├── gh pr edit
│   ├── gh pr comment
│   └── gh pr ready
│
└── Completion
    ├── gh pr merge
    ├── gh pr close
    └── gh pr delete
```

## Real-World Examples

### Creating a Pull Request (from our org updates)
```bash
gh pr create \
  --base epic/SNG-42-AutomatedReports \
  --title "feat: Reorganize state coverage and team updates" \
  --body "## Overview
Moving organization updates from Charts branch..."

# Adding Reviewers
gh pr edit 8 --add-reviewer username

# Viewing PR
gh pr view 8 --web
```

### Managing Reviews
```
Review Process
├── Request Review
│   gh pr edit <number> --add-reviewer <username>
│
├── View Status
│   gh pr view <number>
│
└── Open in Browser
    gh pr view <number> --web
```

### Common Operations
```bash
# List open PRs
gh pr list

# Check PR status
gh pr status

# View PR diff
gh pr diff <number>
```

### Best Practices
1. Always include descriptive titles
2. Use markdown in PR descriptions
3. Reference issues and other PRs
4. Add appropriate reviewers
5. Include testing notes

## Tips and Tricks

1. Quick PR Creation:
```
Branch --> PR
  │
  ├── Develop feature
  ├── Commit changes
  └── gh pr create
```

2. Review Workflow:
```
PR Review
  │
  ├── gh pr checkout <number>
  ├── Review changes locally
  ├── gh pr comment
  └── gh pr review
```

3. Automation Integration:
```
CI/CD Flow
  │
  ├── gh pr create
  ├── Automated checks run
  ├── gh pr checks
  └── gh pr merge --auto
```
