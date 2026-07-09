# Patterns

## DTO-First Mutation
Define Data → generate types/rules → initialize SmartForm → submit route → controller delegates to service.

## File Upload
`form.transform(data => ({ ...data, file }))` then submit with `forceFormData: true`.
