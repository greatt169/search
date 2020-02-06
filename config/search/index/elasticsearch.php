<?php

return [
    'hosts' => env('ELASTICSEARCH_HOSTS'),
    'prefix' => env('ELASTICSEARCH_PREFIX'),
    'log_save' => env('ELASTICSEARCH_FULL_LOG_SAVE') == 'true',
    'dev_log_channel' => 'elasticsearch_dev',
];