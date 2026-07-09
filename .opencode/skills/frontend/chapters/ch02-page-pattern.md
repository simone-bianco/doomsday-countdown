# Inertia Page Pattern

        ## Load When
        building or refactoring page files.

        Pages should be small orchestrators:

```vue
<script setup>
import { Head, Deferred } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
</script>
```

Move tables/forms/sections to domain components. Use Deferred + skeletons for deferred data.
