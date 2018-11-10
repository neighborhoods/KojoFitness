<?php
declare(strict_types=1);

ini_set('assert.exception', '1');
error_reporting(E_ALL);
require_once __DIR__ . '/../vendor/autoload.php';

const QUEUE_ACCOUNT_ID = '272157948465';
const QUEUE_NAME = 'local-kojo-namespace-lock-v1';
const QUEUE_URL = 'https://sqs.us-east-1.amazonaws.com/' . QUEUE_ACCOUNT_ID . '/' . QUEUE_NAME;

$client = \Aws\Sqs\SqsClient::factory(
    ['region' => 'us-east-1']
);

$totalNumberOfMessagesToSend = 500;
$messageCount = 0;
$queueName = QUEUE_NAME;
while ($messageCount !== $totalNumberOfMessagesToSend) {
    try {
        $client->getQueueUrl(
            [
                'QueueName' => QUEUE_NAME,
                'QueueOwnerAWSAccountId' => QUEUE_ACCOUNT_ID,
            ]
        );
    } catch (\Aws\Sqs\Exception\SqsException $exception) {
        if ($exception->getAwsErrorCode() === 'AWS.SimpleQueueService.NonExistentQueue') {
            $client->createQueue(['QueueName' => QUEUE_NAME]);
        }
    }

    $client->sendMessage(
        [
            'QueueUrl' => QUEUE_URL,
            'MessageBody' => 'MESSAGE BODY',
        ]
    );

    ++$messageCount;
    if ($messageCount % 100 === 0) {
        echo "Wrote 100 messages to SQS, {$messageCount}/{$totalNumberOfMessagesToSend} in total to {$queueName}\n";
    }
}

return;
