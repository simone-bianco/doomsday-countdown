# Files & Package Boundaries

## Load When
working with package-owned file/RAG domains or large services.

Use package services for package-owned domains. Do not use raw `Storage` for RAG/package file operations unless verified. Split services when responsibilities diverge or files exceed maintainable size.
