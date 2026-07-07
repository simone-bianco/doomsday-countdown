<?php

declare(strict_types=1);

return [
    'log_channel' => env('AI_AGENTS_LOG_CHANNEL', 'agents'),
    'max_recursion_depth' => env('AI_AGENTS_MAX_RECURSION_DEPTH', 4),
    'default_history_driver' => null,
    'stale_lock_max_age_minutes' => env('AI_AGENTS_STALE_LOCK_MAX_AGE', 15),
    'dynamic_tools' => [
        'http_timeout_seconds' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Agent Progress Reporter Labels
    |--------------------------------------------------------------------------
    |
    | Each key maps to a ProgressEventType value. Values are arrays of strings
    | — one is chosen at random each time that event is reported.
    |
    | Placeholders: {name} is replaced with the tool/event name from metadata.
    | For 'custom', {label} is replaced with the label passed in metadata.
    |
    */
    'progress' => [
        'labels' => [
            'thinking'    => [
                '🤔 Thinking...',
                '🧠 Processing...',
                '💭 Working on it...',
                '⏳ Just a moment...',
                '🔮 Reasoning...',
            ],
            'tool_call'   => [
                '🔧 Calling {name}...',
                '⚙️ Running {name}...',
                '🛠️ Using {name}...',
                '🚀 Executing {name}...',
            ],
            'tool_result' => [
                '✅ {name} done',
                '📥 Got result from {name}',
                '👍 {name} responded',
            ],
            'searching'   => [
                '🔍 Searching...',
                '🕵️ Looking it up...',
                '📖 Browsing knowledge base...',
                '🌐 Fetching data...',
                '📚 Reading sources...',
            ],
            'custom'      => [
                '{label}',
            ],
        ],
    ],
];
