# Git Rebase Guide

## 1. Introduction to Git Rebase

Git rebase is a powerful command that lets you modify your commit history by changing the base commit from which your branch extends. Unlike merging, which creates a new commit to combine changes, rebasing moves or combines a sequence of commits to a new base commit, creating a linear and cleaner history.

### Key Benefits:
- Maintains a linear project history
- Eliminates unnecessary merge commits
- Keeps feature branches up-to-date with main branch
- Allows for cleaner, more organized commit history

## 2. Example Scenario

Let's look at a real-world example from our current project:

### Current State:
```
a8df511 - (HEAD -> feature/SNG-47-Charts) Heatmap leaflet.json halfway
2b6d0ff - HeatMap - India - SVG - Half way
104cf37 - (tag: v1.0.7-charts) fix(analytics): Fix Monthly CR vs DR chart...
```

Visualized as:
```
            feature/SNG-47-Charts
                    ↓
104cf37 <- 2b6d0ff <- a8df511 [HEAD]
↑
v1.0.7-charts
```

### After Rebase (adding new fixes):
```
                            feature/SNG-47-Charts
                                    ↓
104cf37 <- [new fixes] <- 2b6d0ff <- a8df511 [HEAD]
↑
v1.0.7-charts
```

## 3. Step-by-Step Interactive Rebase Guide

### Basic Rebase Command:
```bash
git rebase -i 104cf37
```

### Interactive Rebase Steps:
1. **Start the Rebase**:
```bash
git rebase -i 104cf37
```

2. **Editor Opens with Commits**:
```
pick 2b6d0ff HeatMap - India - SVG - Half way
pick a8df511 Heatmap leaflet.json halfway

# Commands:
# p, pick = use commit
# r, reword = use commit, but edit the commit message
# e, edit = use commit, but stop for amending
# s, squash = use commit, but meld into previous commit
# d, drop = remove commit
```

3. **Save and Exit**: The rebase will begin
4. **Make Your Changes**: After the rebase pauses
5. **Continue Rebase**: After making changes
```bash
git add .
git commit -m "Your fix message"
git rebase --continue
```

## 4. Best Practices and Warnings

### Best Practices:
- Always create a backup branch before rebasing
- Keep rebases small and focused
- Communicate with team members when rebasing shared branches
- Write clear commit messages
- Test after rebasing

### ⚠️ Warnings:
- Never rebase commits that have been pushed to public branches
- Always use `--force-with-lease` instead of `--force` when pushing rebased branches
- Resolve conflicts carefully during rebase
- Keep your local branch updated to minimize conflicts
- Backup your work before complex rebases

## 5. Common Troubleshooting Tips

### Abort a Rebase
If things go wrong during a rebase:
```bash
git rebase --abort
```

### Fix Conflicts
When conflicts occur:
1. Fix conflicts in your editor
2. Stage resolved files: `git add <file>`
3. Continue rebase: `git rebase --continue`

### Undo a Rebase
To return to pre-rebase state:
```bash
git reset --hard ORIG_HEAD
```

### Push After Rebase
Force push with lease (safer than force push):
```bash
git push --force-with-lease
```

### Common Issues:
1. **"Cannot rebase: You have unstaged changes"**
- Solution: Commit or stash changes before rebasing

2. **"branch is behind after rebase"**
- Solution: Force push with `--force-with-lease`

3. **"Unable to rebase due to conflicts"**
- Solution: Resolve conflicts manually, then continue

### Remember:
- Keep your commits atomic and focused
- Always verify your changes after rebasing
- When in doubt, create a backup branch
- Communication is key when working with shared branches

