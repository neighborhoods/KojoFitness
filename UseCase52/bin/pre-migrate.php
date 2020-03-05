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

// for this fitness function, only the table name matters, not the schema
$pdo->exec("CREATE TABLE kojo_job_type ();");
