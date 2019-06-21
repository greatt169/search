<?php

return [
    'hosts' => env('ELASTICSEARCH_HOSTS'),
    'prefix' => env('ELASTICSEARCH_PREFIX'),
    'log_save' => env('ELASTICSEARCH_LOG_SAVE') == 'true',
];