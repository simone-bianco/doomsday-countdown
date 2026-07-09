# Tools & Forced Calls

## Load When
adding `#[Tool]`, `toolRequired`, or `forceTool`.

Tool name is PHP method name. Tool delegates to deterministic service. Keep params simple and output compact. Use runtime write guards for side effects. Disable parallel tool calls for ordered/side-effecting operations.
