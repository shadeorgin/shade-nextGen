# Jira Epic vs Story: GitHub Branching Strategy Guide

## Introduction to Jira Terminology

### Epic
- A large body of work that can be broken down into smaller tasks
- Represents a significant feature or module
- Usually takes multiple sprints to complete
- Example: User Management Module

### Story
- A smaller, specific piece of work
- Represents a single feature or functionality
- Usually completed within one sprint
- Example: User Registration Feature

## Git Branch Types and Naming Conventions

### Understanding Branch Types
- Git itself doesn't have built-in branch types
- Branch "types" are created through naming conventions
- Prefixes like `feature/` or `bugfix/` are team-defined standards

### Common Branch Prefixes
- `feature/`: New features or enhancements
- `bugfix/`: Bug fixes
- `hotfix/`: Urgent fixes for production issues
- `release/`: Release preparation
- `docs/`: Documentation updates
- `epic/`: Large feature sets (Jira alignment)

### Epic Branch Convention
- `epic/` prefix aligns with Jira's Epic concept
- Alternative prefixes for epic-level work:
- `module/`: For module-based development
- `epic-feature/`: More explicit epic relation
- `project/`: Project-level features

### Choosing Naming Conventions
- Be consistent across the project
- Make names clear and descriptive
- Ensure easy understanding for team members
- Consider integration with tracking tools
- Keep names concise but meaningful

## Branching Strategy

### Branch Hierarchy
```
main
└── epic/EPIC-KEY-description
    ├── feature/STORY-KEY-feature1
    ├── feature/STORY-KEY-feature2
    └── feature/STORY-KEY-feature3
```

### Naming Convention
- Epic Branch: `epic/EPIC-KEY-description`
- Feature Branch: `feature/STORY-KEY-description`

## Real-World Example: User Management Module

### Epic Structure
Epic: SHADE-100 (User Management Module)
- Landing/Index Page (implemented in epic branch)
- Story: SHADE-101 (User Registration)
- Story: SHADE-102 (User Profile)
- Story: SHADE-103 (User Dashboard)

### Branch Structure
```
main
└── epic/SHADE-100-user-management (contains landing page)
    ├── feature/SHADE-101-user-registration
    ├── feature/SHADE-102-user-profile
    └── feature/SHADE-103-user-dashboard
```

### Epic Branch Contents
The epic branch (`epic/SHADE-100-user-management`) contains:
1. Module landing/index page
2. Common components and utilities
3. Integration points between features
4. Module-level configuration
5. Merged features from feature branches

## Workflow and Best Practices

### Development Workflow
1. Create epic branch from main
2. Implement module landing page in epic branch
3. Create feature branches from epic branch
4. Develop features in respective branches
5. Merge completed features into epic branch
6. Test integration in epic branch
7. Merge epic branch into main when complete

### Best Practices
- Always pull latest changes before creating new branches
- Use descriptive branch names
- Keep feature branches short-lived
- Regularly merge epic branch into feature branches
- Use pull requests for code review
- Delete feature branches after merging
- Maintain clean commit history

## When to Merge into Main

Merge epic branch into main when:
1. All stories in the epic are complete
2. Code reviews are done
3. All tests pass in the epic branch
4. No conflicts with main branch
5. CI/CD pipelines succeed
6. Product owner approves the features

## Common Git Operations

### Create and Switch to Epic Branch
```bash
git checkout main
git pull
git checkout -b epic/SHADE-100-user-management
```

### Create Feature Branch
```bash
git checkout epic/SHADE-100-user-management
git pull
git checkout -b feature/SHADE-101-user-registration
```

### Merge Feature into Epic
```bash
# Update epic branch
git checkout epic/SHADE-100-user-management
git pull

# Merge feature branch
git merge feature/SHADE-101-user-registration

# Push changes
git push origin epic/SHADE-100-user-management
```

### Merge Epic into Main
```bash
# Update main branch
git checkout main
git pull

# Merge epic branch
git merge epic/SHADE-100-user-management

# Push changes
git push origin main
```

### Update Feature Branch with Epic Changes
```bash
git checkout feature/SHADE-101-user-registration
git pull
git merge epic/SHADE-100-user-management
```

