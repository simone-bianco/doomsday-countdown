---
name: fe-to-be-flow
description: Use for frontend-backend flow DTOs, validation, requests, and form submission.
---

# Frontend-Backend Data Flow

When passing data from frontend to backend (POST/PUT/PATCH requests), follow this exact pipeline:

## Step 1: Define the DTO (Backend)
Create `app/Data/{Name}Data.php` using Spatie LaravelData:
```php
#[TypeScript]
class CreateChunkData extends Data {
    public function __construct(
        #[Required, StringType] public string $document_id,
        #[Required, StringType, Max(3000)] public string $content,
        #[Nullable] public ?array $tags,
    ) {}
}
```
MUST have `#[TypeScript]` attribute. Validation via Spatie attributes.

## Step 2: Generate Frontend Rules
```bash
php artisan form-bridge:generate
```
This auto-generates `resources/js/generated/form-rules.ts` from all Data classes.

## Step 3: Create the Form (Frontend)
```ts
import { useSmartForm } from '@simone-bianco/vue-form-core'
import { CreateChunkDataRules } from '@/generated/form-rules'
import type { CreateChunkData } from '@/types/generated'

const form = useSmartForm<CreateChunkData>({ ...CreateChunkDataRules })
form.fill({ document_id: props.documentId, content: '', tags: [] })
```

## Step 4: Create Controller + optional FormRequest (Backend)
```php
// app/Http/Controllers/Web/ChunkPageController.php
public function store(CreateChunkData $data): RedirectResponse {
    $this->chunkService->create($data);
    return back()->with('success', 'Chunk created.');
}
```

Use a dedicated `FormRequest` only for HTTP-specific concerns (authorization/pre-validation), not as mandatory default.

## Step 5: Submit from Frontend
```ts
form.post(route('chunks.store'), { preserveScroll: true })
// For file uploads:
form.transform(data => ({ ...data, image: file.value }))
    .post(route('chunks.store'), { forceFormData: true })
```

## Key Rules
- DTO FIRST, always. Define validation in one place (Spatie Data).
- NEVER write manual validation arrays in controllers.
- NEVER use raw `axios` or `fetch` for form submissions.
- Controller stays thin — business logic in Services.
- Run `form-bridge:generate` after ANY Data class change.
