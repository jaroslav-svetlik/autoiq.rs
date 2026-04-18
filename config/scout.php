<?php

return [
    'driver' => env('SCOUT_DRIVER', 'collection'),
    'prefix' => env('SCOUT_PREFIX', ''),
    'queue' => env('SCOUT_QUEUE', false),
    'after_commit' => true,
    'chunk' => [
        'searchable' => 250,
        'unsearchable' => 250,
    ],
    'soft_delete' => false,
    'identify' => false,
    'algolia' => [
        'id' => env('ALGOLIA_APP_ID', ''),
        'secret' => env('ALGOLIA_SECRET', ''),
        'index-settings' => [],
    ],
    'meilisearch' => [
        'host' => env('MEILISEARCH_HOST', 'http://127.0.0.1:7700'),
        'key' => env('MEILISEARCH_KEY'),
        'index-settings' => [
            'listings' => [
                'filterableAttributes' => [
                    'brand',
                    'model',
                    'year',
                    'city',
                    'fuel_type',
                    'transmission',
                    'seller_type',
                    'autoiq_score',
                    'status',
                ],
                'sortableAttributes' => [
                    'created_at',
                    'price',
                    'year',
                    'mileage',
                    'autoiq_score',
                ],
                'searchableAttributes' => [
                    'title',
                    'brand',
                    'model',
                    'city',
                    'description',
                ],
                'displayedAttributes' => [
                    'id',
                    'title',
                    'brand',
                    'model',
                    'year',
                    'price',
                    'mileage',
                    'city',
                    'fuel_type',
                    'transmission',
                    'seller_type',
                    'autoiq_score',
                    'status',
                    'slug',
                ],
            ],
        ],
    ],
    'typesense' => [
        'client-settings' => [
            'api_key' => env('TYPESENSE_API_KEY', 'xyz'),
            'nodes' => [
                [
                    'host' => env('TYPESENSE_HOST', 'localhost'),
                    'port' => env('TYPESENSE_PORT', '8108'),
                    'path' => env('TYPESENSE_PATH', ''),
                    'protocol' => env('TYPESENSE_PROTOCOL', 'http'),
                ],
            ],
            'nearest_node' => [
                'host' => env('TYPESENSE_HOST', 'localhost'),
                'port' => env('TYPESENSE_PORT', '8108'),
                'path' => env('TYPESENSE_PATH', ''),
                'protocol' => env('TYPESENSE_PROTOCOL', 'http'),
            ],
            'connection_timeout_seconds' => 2,
            'healthcheck_interval_seconds' => 30,
            'num_retries' => 3,
            'retry_interval_seconds' => 1,
        ],
        'model-settings' => [],
        'import_action' => env('TYPESENSE_IMPORT_ACTION', 'upsert'),
    ],
];
