<?php

return [
    'optimization' => [
        'enabled' => true,
        'batch_size' => 5,
        'max_memory_percentage' => 80,
        'cleanup_interval' => 3,
        'force_gc' => true,
        'close_connections' => true,
        'clear_cache' => true
    ],
    
    'monitoring' => [
        'enabled' => true,
        'log_interval' => 10,
        'alert_threshold' => 90,
        'stop_threshold' => 95
    ],
    
    'processing' => [
        'delay_between_requests' => 1,
        'max_retries' => 3,
        'timeout' => 60
    ]
];