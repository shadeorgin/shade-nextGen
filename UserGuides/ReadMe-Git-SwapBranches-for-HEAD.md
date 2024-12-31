# Git Swap branches for HEAD

## git log output 

```bash
$ git status --oneline
6096dc0 (HEAD -> epic/SNG-42-AutomatedReports, origin/epic/SNG-42-AutomatedReports) feat: Website phase 1 completion
2a13238 Incremental changes
915c3b4 feat: Complete initial PHP Bootstrap web app setup
614a356 Initial Commit
fd907b2 (origin/main, origin/HEAD, main) Initial commit
```

*Status* : The latest commit is `6096dc0`, but that was not the preferred / unstable one. I wish to retain the last but one commit `2a1328` as the top most commit. 

*Q*: I wish to keep this commit `2a13238` the latest, for this repo. What should I do?

*A* : To make commit `2a13238` the latest commit in your repository, you have a few options:

1. If you want to completely discard all changes after `2a13238`:

```bash
   git reset --hard 2a13238
   git push --force origin your-branch-name
```

2. If you want to preserve the changes but reset the HEAD:

```bash
   git reset --soft 2a13238
   git stash    # This saves your current changes
```

   Later you can apply these changes with `git stash pop`

3. If you want to revert all commits after 2a13238:

```bash
   git revert --no-commit HEAD..2a13238
   git commit -m "Reverting to commit 2a13238"
```

Given your situation, I recommend option 1 since you specifically want that commit to be the latest.
```bash
git reset --hard 2a13238 && git push --force origin epic/SNG-42-AutomatedReports
```

## Conclusion

Opted the #1 option and the result is below.

### Command Output - `git push --force`

```bash
$ git push --force origin epic/SNG-42-AutomatedReports
Total 0 (delta 0), reused 0 (delta 0), pack-reused 0
To github.com:shadeorgin/shade-nextGen.git
 + 6096dc0...2a13238 epic/SNG-42-AutomatedReports -> epic/SNG-42-AutomatedReports (forced update)
```

### Command Output - `git log --oneline`

```bash
$ git log --oneline
git log --oneline
2a13238 (HEAD -> epic/SNG-42-AutomatedReports, origin/epic/SNG-42-AutomatedReports) Incremental changes
915c3b4 feat: Complete initial PHP Bootstrap web app setup
614a356 Initial Commit
fd907b2 (origin/main, origin/HEAD, main) Initial commit
```

