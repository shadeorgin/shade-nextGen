# Git Tags Guide

## Table of Contents
- [Introduction](#introduction)
- [Benefits of Tags](#benefits-of-tags)
- [Types of Tags](#types-of-tags)
- [Version Numbering](#version-numbering)
- [Tag Naming Conventions](#tag-naming-conventions)
- [Common Operations](#common-operations)
- [Tag Relationships](#tag-relationships)
- [Tags vs Releases](#tags-vs-releases)
- [Best Practices](#best-practices)
- [Creating Releases](#creating-releases)
- [Advanced Operations](#advanced-operations)
- [Troubleshooting](#troubleshooting)
- [Real-World Workflows](#real-world-workflows)

## Introduction
Git tags provide a way to mark specific points in your repository's history as important. They are typically used to mark release points (v1.0, v2.0, etc.) or significant milestones in your project.

## Benefits of Tags
- **Versioning**: Clear identification of releases and versions
- **Navigation**: Easy reference to specific commits
- **Documentation**: Historical tracking of significant changes
- **Deployment**: Reliable points for production releases
- **Rollback**: Safe points to revert to if needed

## Types of Tags

### Lightweight Tags
- Simple pointer to a specific commit
- Just a name/tag and commit checksum
- Example:
```bash
git tag v1.0.13
```

### Annotated Tags (Recommended)
- Stored as full objects in Git database
- Contains tagger name, email, date, and message
- Can be signed and verified
- Example from our project:
```bash
git tag -a "v1.0.15-enhanced-carousel-flow" -m "Enhanced carousel organization with Reports Overview and improved content flow"
```

## Version Numbering
We follow Semantic Versioning (SemVer):
```
v{MAJOR}.{MINOR}.{PATCH}-{DESCRIPTOR}
```
Examples from our project:
- `v1.0.14-ui-enhancements-and-coverage`
- `v1.0.15-enhanced-carousel-flow`

Where:
- MAJOR: Breaking changes
- MINOR: New features, backward compatible
- PATCH: Bug fixes, backward compatible
- DESCRIPTOR: Optional suffix describing the changes

## Tag Naming Conventions
```
prefix-version-descriptor
```
Examples:
- Release tags: v1.0.0
- Feature tags: v1.0.15-enhanced-carousel-flow
- Hotfix tags: v1.0.13-hotfix-security

## Common Operations

### Creating Tags
```bash
# Annotated tag
git tag -a "v1.0.15-enhanced-carousel-flow" -m "Enhanced carousel organization"

# Lightweight tag
git tag v1.0.15
```

### Pushing Tags
```bash
# Push specific tag
git push origin v1.0.15-enhanced-carousel-flow

# Push all tags
git push origin --tags
```

### Viewing Tags
```bash
# List all tags
git tag -l

# Show tag details
git show v1.0.15-enhanced-carousel-flow
```

### Verifying Tags
```bash
# View remote tags
git ls-remote --tags origin

# Verify tag sync
git fetch --tags
git tag -n
```

## Tag Relationships

```
                    main branch
●───●───●───●───●───●
    │   │       │   │
    │   │       │   └── v1.0.15-enhanced-carousel-flow
    │   │       └────── v1.0.14-ui-enhancements
    │   └────────────── v1.0.13
    └──────────────────── v1.0.12

Legend:
● = commit
│ = tag reference
```

## Tags vs Releases
### Tags
- Lightweight references to specific commits
- Local to the Git repository
- Quick to create and manage
- Used for version tracking

### Releases
- Built on top of tags
- Include additional artifacts (binaries, notes)
- GitHub/GitLab specific feature
- More formal, user-facing
- Can include release notes and artifacts

## Creating Releases

### GitHub Releases
1. **Create Release from Tag**
```bash
# First create and push a tag
git tag -a "v1.0.15-enhanced-carousel-flow" -m "Enhanced carousel organization"
git push origin v1.0.15-enhanced-carousel-flow

# Then create release on GitHub:
1. Go to GitHub repository
2. Click "Releases" tab
3. Click "Draft a new release"
4. Choose the tag
5. Add release title and notes
6. Attach build artifacts
```

### GitLab Releases
```bash
# Create release via GitLab API
curl --request POST \
    --header "PRIVATE-TOKEN: <your_access_token>" \
    "https://gitlab.example.com/api/v4/projects/1/releases" \
    --data '{
    "name": "Enhanced Carousel v1.0.15",
    "tag_name": "v1.0.15-enhanced-carousel-flow",
    "description": "Enhanced carousel organization",
    "assets": {
        "links": [
        {
            "name": "Documentation",
            "url": "https://example.com/docs"
        }
        ]
    }
    }'
```

### Release Components
1. **Release Title**: "v1.0.15 Enhanced Carousel Flow"
2. **Release Notes**:
```markdown
## What's New
- Enhanced carousel organization
- Added Reports Overview slide
- Improved slide sequence for better user flow

## Technical Changes
- Updated carousel navigation
- Optimized image loading
- Enhanced mobile responsiveness
```

3. **Release Artifacts**:
- Minified JavaScript bundle
- CSS stylesheets
- Documentation PDFs
- Test coverage reports

## Advanced Operations

### Signing Tags
```bash
# Create GPG key
gpg --gen-key

# Configure Git to use your GPG key
git config --global user.signingkey <your-key-id>

# Create signed tag
git tag -s "v1.0.15" -m "Signed release v1.0.15"

# Verify signed tag
git tag -v v1.0.15
```

### Managing Tags
```bash
# Delete local tag
git tag -d v1.0.15-test

# Delete remote tag
git push origin :refs/tags/v1.0.15-test

# Rename tag
git tag new-tag old-tag
git tag -d old-tag
git push origin new-tag
git push origin :refs/tags/old-tag
```

## Troubleshooting

### Common Issues
1. **Tag Already Exists**
```bash
# Force update existing tag
git tag -fa v1.0.15 -m "Updated tag message"
git push origin --force v1.0.15
```

2. **Missing Tags After Clone**
```bash
# Fetch all tags
git fetch --tags
git fetch --all --tags
```

3. **Tag and Build Mismatch**
```bash
# Verify tag points to correct commit
git rev-list -n 1 v1.0.15

# Check tag details
git show v1.0.15
```

## Real-World Workflows

### Feature Release Workflow
```bash
# 1. Finish feature development
git checkout feature/SNG-47-Charts
git commit -m "feat: Complete carousel enhancements"

# 2. Create and push tag
git tag -a "v1.0.15-enhanced-carousel-flow" -m "Enhanced carousel organization"
git push origin v1.0.15-enhanced-carousel-flow

# 3. Create GitHub/GitLab release
# Follow platform-specific release process

# 4. Update documentation
git add CHANGELOG.md README.md
git commit -m "docs: Update documentation for v1.0.15"
git push origin feature/SNG-47-Charts
```

### Hotfix Release Workflow
```bash
# 1. Create hotfix branch
git checkout -b hotfix/v1.0.16 main

# 2. Make fixes
git commit -m "fix: Critical bug in carousel navigation"

# 3. Create hotfix tag
git tag -a "v1.0.16-hotfix-carousel" -m "Fix carousel navigation"
git push origin v1.0.16-hotfix-carousel

# 4. Merge back to main
git checkout main
git merge hotfix/v1.0.16
git push origin main
```

## Best Practices
1. **Use Annotated Tags** for releases
- Include meaningful messages
- Provide context for future reference

2. **Consistent Versioning**
- Follow semantic versioning
- Use descriptive suffixes for clarity

3. **Tag Timing**
- Tag after successful testing
- Tag before deploying to production
- Create tags for significant features

4. **Documentation**
- Update CHANGELOG.md with each tag
- Include tag in documentation updates

5. **Tag Management**
- Don't delete or move published tags
- Push tags immediately after creation
- Verify tags after pushing

6. **Naming Conventions**
- Use consistent prefixes (v1.0.0)
- Include descriptive suffixes when relevant
- Keep names concise but meaningful

