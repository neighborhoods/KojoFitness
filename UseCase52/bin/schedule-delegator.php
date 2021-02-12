<?php
declare(strict_types=1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

$dataSourceName = sprintf(
    '%s:dbname=%s;host=%s',
    $_ENV['DATABASE_ADAPTER'],
    $_ENV['DATABASE_NAME'],
    $_ENV['DATABASE_HOST']
);

$pdo = new \PDO(
    $dataSourceName,
    $_ENV['DATABASE_USERNAME'],
    $_ENV['DATABASE_PASSWORD'],
    [
        \PDO::ATTR_PERSISTENT => true,
        \PDO::ATTR_EMULATE_PREPARES => false,
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
    ]
);

$pdo->exec(<<<EOM
INSERT INTO kojo_job
    (
     type_code,
     name,
     priority,
     importance,
     work_at_date_time,
     next_state_request,
     assigned_state,
     previous_state,
     worker_uri,
     worker_method,
     can_work_in_parallel,
     last_transition_date_time,
     last_transition_micro_time,
     times_worked,
     times_retried,
     times_held,
     times_crashed,
     times_panicked,
     created_at_date_time,
     completed_at_date_time,
     delete_after_date_time
    )
SELECT
       type_code,
       name,
       default_importance,
       default_importance,
       NOW(),
       'working',
       'waiting',
       'new',
       worker_uri,
       worker_method,
       can_work_in_parallel,
       NOW(),
       EXTRACT(MICROSECONDS FROM NOW()),
       0,
       0,
       0,
       0,
       0,
       NOW(),
       NULL,
       NULL
FROM kojo_job_type
WHERE type_code = 'delegator';
EOM
);
