# Game Plan: Cherry-Pick Organization Updates from Charts Branch

## Initial Situation

We have organization updates (Kerala state addition and team updates) accidentally committed to the Charts feature branch. These changes need to be moved to a more appropriate branch under the Automated Reports epic.

### Current Structure:
```
main
|
+--epic/SNG-42-AutomatedReports
|    |
|    +--feature/SNG-47-Charts
|         |
|         +--[c40d005] Organization Updates ⚠️ Wrong Location
|         +--v1.1.0-coverage-team-updates (tag)
|
```

## Target Structure

We want to move the organization updates to a new feature branch under the Automated Reports epic:

```
main
|
+--epic/SNG-42-AutomatedReports
|    |
|    +--feature/SNG-47-Charts (Charts only)
|    |    
|    +--feature/SNG-48-org-updates
|         |
|         +--[c40d005] Organization Updates ✅ Correct Location
|         +--v1.1.0-coverage-team-updates (tag)
|
```

## Step-by-Step Procedure

1. Switch to epic branch and update:
```bash
git checkout epic/SNG-42-AutomatedReports
git pull origin epic/SNG-42-AutomatedReports
```

2. Create new feature branch:
```bash
git checkout -b feature/SNG-48-org-updates
```

3. Cherry-pick the organization updates:
```bash
git cherry-pick c40d005bd5ec959e92e5d58a5585a96f3d95e53a
```

4. Move the tag:
```bash
# Delete old tag locally and remotely
git tag -d v1.1.0-coverage-team-updates
git push --delete origin v1.1.0-coverage-team-updates

# Create new tag at new location
git tag -a v1.1.0-coverage-team-updates -m "Enhanced Coverage and Team Updates"
git push origin v1.1.0-coverage-team-updates
```

5. Clean up Charts branch:
```bash
git checkout feature/SNG-47-Charts
git revert c40d005bd5ec959e92e5d58a5585a96f3d95e53a
git push origin feature/SNG-47-Charts
```

## Benefits and Best Practices

### Benefits
- **Clear Feature Separation**: Each branch maintains single responsibility
- **Proper Organization**: Changes live in appropriate feature branches
- **Clean History**: Maintains clear git history with proper branching
- **Version Control**: Tags and commits properly organized

### Best Practices
1. **Branch Naming**:
- Use descriptive prefixes (feature/, epic/, etc.)
- Include ticket numbers (SNG-XX)
- Keep names concise but meaningful

2. **Commit Organization**:
- Keep related changes together
- Separate unrelated changes into different branches
- Use meaningful commit messages

3. **Tag Management**:
- Use descriptive tag names
- Include version numbers
- Add detailed tag messages

4. **Branch Hierarchy**:
- Maintain proper parent-child relationships
- Keep feature branches under appropriate epics
- Regular synchronization with parent branches

