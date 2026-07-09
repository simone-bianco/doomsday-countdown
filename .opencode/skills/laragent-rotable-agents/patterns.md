# Patterns

## Rotable Base
Inject key before parent init; register usage after response.

## Deterministic Tool Runner
Shared service runner used by both `#[Tool]` method and CLI command.

## Write Guard
`effectiveWrite = requestedWrite && runtimeAllowed`.
