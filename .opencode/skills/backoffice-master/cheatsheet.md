# Cheatsheet

## Never
- hardcode `/backoffice`
- use native form primitives when package components exist
- use `window.confirm`
- mutate with raw `axios`/`fetch` for standard forms

## Grep
```bash
grep -R "/backoffice\|window.confirm\|axios\.\|fetch(" resources/js/Pages resources/js/Components
```
