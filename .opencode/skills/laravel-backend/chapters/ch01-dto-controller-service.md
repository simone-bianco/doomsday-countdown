# DTO → Controller → Service

        ## Load When
        creating or reviewing backend mutation/query flows.

        ## Contract
Data classes define structure and validation. Controllers receive DTO/FormRequest, call services, and return responses. Services own business rules and transactions.

## Avoid
- raw controller validation arrays
- business logic in controllers
- hidden duplicated validation
