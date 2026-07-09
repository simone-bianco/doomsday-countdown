# Patterns

## DTO-First Backend
Data → generated contracts → controller → service.

## Scope Mirror
Apply the same domain boundary in list/read and write/delete.

## Async Handoff
Controller dispatches job, returns 202, job broadcasts result.
