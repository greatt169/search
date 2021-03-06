<?php

use Bramus\Monolog\Formatter\ColoredLineFormatter;

return [
    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */
    'default' => env('LOG_CHANNEL', 'stack'),
    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "custom", "stack"
    |
    */
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
        ],
        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
        ],
        'elasticsearch_full' => [
            'driver' => 'single',
            'path' => storage_path('logs/es_full.log'),
            'level' => 'debug',
        ],
        'elasticsearch_dev' => [
            'driver' => 'stack',
            'channels' => ['elasticsearch_dev_file', 'elasticsearch_dev_stdout'],
        ],
        'elasticsearch_dev_slack' => [
            'driver' => 'slack',
            'url' => env('ES_LOG_SLACK_WEBHOOK_URL'),
            'username' => 'SearchPoint Log',
            'level' => 'info',
        ],
        'elasticsearch_dev_file' => [
            'driver' => 'single',
            'path' => storage_path('logs/es_dev.log'),
            'level' => 'debug',
        ],
        'elasticsearch_dev_stdout' => [
            'driver' => 'single',
            'formatter' => ColoredLineFormatter::class,
            'formatter_with' => [
                'format' => "[%datetime%] %level_name%: %message%\n",
            ],
            'path' => 'php://stdout',

        ],
        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
            'days' => 7,
        ],
        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'info',
        ],
        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],
        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],
    ],
];