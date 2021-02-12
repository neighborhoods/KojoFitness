<?php
declare(strict_types=1);

error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

const NUMBER_OF_MODELS = 100000;

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

// models to be worked on
// $pdo->exec(<<<EOM
// CREATE TABLE models (
//     id BIGSERIAL PRIMARY KEY,
//     is_being_worked_on BOOLEAN NOT NULL DEFAULT false
// );
// EOM
// );
//
// // units of work to do on models
// $pdo->exec(<<<EOM
// CREATE TABLE work_queue (
//     id BIGSERIAL PRIMARY KEY,
//     model_id BIGINT NOT NULL
// );
// EOM
// );
//
// // table for associating a dynamically scheduled job's ID with a unit of work to do
// $pdo->exec(<<<EOM
// CREATE TABLE delegator_worker_job_data (
//     id BIGSERIAL PRIMARY KEY,
//     model_id BIGINT NOT NULL,
//     job_id BIGINT NOT NULL
// );
// EOM
// );

$modelCreationStatements = str_repeat('INSERT INTO models DEFAULT VALUES;', NUMBER_OF_MODELS);

$pdo->exec($modelCreationStatements);
