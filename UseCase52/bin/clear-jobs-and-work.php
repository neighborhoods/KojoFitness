<?php
declare(strict_types=1);

error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

const NUMBER_OF_MODELS = 40;

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

$pdo->exec('DELETE FROM work_queue;');
$pdo->exec('DELETE FROM delegator_worker_job_data;');
$pdo->exec('DELETE FROM kojo_job;');
