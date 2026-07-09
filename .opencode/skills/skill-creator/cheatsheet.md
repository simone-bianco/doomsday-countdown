# Cheatsheet

## Hard Rules
- Root < chapter content; root routes, chapters teach.
- Normally load 1 chapter, 2 max.
- No generic “Term preserved” glossaries.
- No nested template `SKILL.md`.
- Validate `git diff --check` and skill discovery.

```bash
git diff --check -- .opencode/skills/{skill}
Get-ChildItem .opencode/skills -Recurse -Filter SKILL.md
```


## Bookization threshold

- `>= 4000` chars → book-to-skill by default.
- `< 4000` chars → single-file by default.
- Book below threshold only for catalog/reference skills.
- Single-file above threshold only for linear procedures.
