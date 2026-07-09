# Token-Efficient Structure

## Load When
designing the book-to-skill file layout.

Root `SKILL.md` contains: read protocol, core mental models, chapter index, topic index, supporting files, scope limits. Chapters are atomic and independently useful. `cheatsheet.md` is for final checks; `patterns.md` for design/refactor; `glossary.md` only for terms.


## Bookization threshold

Use a character-count diagnostic before converting many skills.

- `>= 4000` total characters: book-to-skill by default.
- `< 4000` total characters: single-file by default.
- Book below 4000 only when it is a catalog/reference where chapter cherry-picking materially reduces load.
- Keep above 4000 single-file only when the content is a linear procedure with no useful independent sections.
