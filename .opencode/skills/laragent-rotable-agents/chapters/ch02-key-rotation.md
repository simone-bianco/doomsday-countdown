# Key Rotation

## Load When
creating/reviewing rotable base agents.

Use `OpenAIKeyRotator` or verified equivalent. Pick and inject key before parent constructor/provider initialization. Register usage after response only when tokens are reported. Never expose keys to prompts, tools, logs, or artifacts.
