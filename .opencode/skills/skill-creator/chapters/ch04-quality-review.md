# Quality Review

## Load When
reviewing a skill for slop or bad discovery behavior.

Reject overbroad descriptions, monolithic roots, generated generic glossaries, stale missing-skill links, duplicated chapters, unsupported claims, and nested template `SKILL.md` files that discovery may treat as real skills.


## Bookization threshold

Use a character-count diagnostic before converting many skills.

- `>= 4000` total characters: book-to-skill by default.
- `< 4000` total characters: single-file by default.
- Book below 4000 only when it is a catalog/reference where chapter cherry-picking materially reduces load.
- Keep above 4000 single-file only when the content is a linear procedure with no useful independent sections.
