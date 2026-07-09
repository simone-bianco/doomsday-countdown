# Domain Scope & Security

## Load When
writing queries/mutations with ownership or project/domain boundaries.

Read/list scope and write/delete scope must match. Do not fall back to caller/user-only scope if the UI displays project/domain records. Stop on unclear authorization, tenant boundary, or destructive operation.
