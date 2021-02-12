<?php
declare(strict_types=1);

error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

// change these values to simulate different workloads
// (e.g. small number of models + large amount of work = contention)
// (e.g. large number of models + large amount of work = room for parallelization)
const NUMBER_OF_MODELS = 1000;
const NUMBER_OF_UNITS_OF_WORK = 10000;

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

$createdWorkUnits = 0;

while ($createdWorkUnits < NUMBER_OF_UNITS_OF_WORK) {
    $modelId = rand(1, NUMBER_OF_MODELS);

    $pdo->exec("INSERT INTO work_queue (model_id) VALUES ($modelId);");

    $createdWorkUnits++;
}
