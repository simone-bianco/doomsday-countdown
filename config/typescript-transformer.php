<?php

declare(strict_types=1);

return [
    'auto_discover_types' => [
        app_path(),
        base_path('packages/simone-bianco/laravel-key-rotator'),
    ],
    'collectors' => [
        Spatie\LaravelData\Support\TypeScriptTransformer\DataTypeScriptCollector::class,
        Spatie\TypeScriptTransformer\Collectors\DefaultCollector::class,
        Spatie\TypeScriptTransformer\Collectors\EnumCollector::class,
    ],
    'transformers' => [
        Spatie\LaravelData\Support\TypeScriptTransformer\DataTypeScriptTransformer::class,
        Spatie\LaravelTypeScriptTransformer\Transformers\SpatieStateTransformer::class,
        Spatie\TypeScriptTransformer\Transformers\EnumTransformer::class,
        Spatie\TypeScriptTransformer\Transformers\SpatieEnumTransformer::class,
        Spatie\LaravelTypeScriptTransformer\Transformers\DtoTransformer::class,
    ],
    'default_type_replacements' => [
        DateTime::class => 'string',
        DateTimeImmutable::class => 'string',
        Carbon\CarbonInterface::class => 'string',
        Carbon\CarbonImmutable::class => 'string',
        Carbon\Carbon::class => 'string',
    ],
    'output_file' => resource_path('js/types/generated.d.ts'),
    'writer' => Spatie\TypeScriptTransformer\Writers\ModuleWriter::class,
    'formatter' => null,
    'transform_to_native_enums' => false,
    'transform_null_to_optional' => false,
];
