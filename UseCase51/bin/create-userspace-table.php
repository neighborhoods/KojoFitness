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
        \PDO::ATTR_EMULATE_PREPARES => false
    ]
);

$pdo->exec(<<<EOM
CREATE TABLE userspace_table (
    consumer_job_id BIGINT NOT NULL UNIQUE,
    event_type TEXT NOT NULL,
    is_expected BOOLEAN NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
EOM
);
