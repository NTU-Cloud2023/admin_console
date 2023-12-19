<?php
namespace App\Services;

use Aws\Sqs\SqsClient;

class QueueService
{
    protected $sqsClient;
    protected $queueUrl;

    public function __construct()
    {
        $this->sqsClient = new SqsClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
        ]);
        $this->queueUrl = 'https://sqs.ap-northeast-1.amazonaws.com/881643529096/ntu_order_queue'; // Set your Queue URL
    }

    public function getTotalQueueCount()
    {
        try {
            $result = $this->sqsClient->getQueueAttributes([
                'QueueUrl' => $this->queueUrl,
                'AttributeNames' => ['ApproximateNumberOfMessages']
            ]);

            return $result->get('Attributes')['ApproximateNumberOfMessages'];

        } catch (\Exception $e) {
            // Handle exception
            return 0;
        }
    }

    public function receiveMessage($maxNumberOfMessages = 1)
    {
        try {
            $result = $this->sqsClient->receiveMessage([
                'QueueUrl' => $this->queueUrl,
                'MaxNumberOfMessages' => $maxNumberOfMessages
            ]);
    
            return $result->get('Messages');
    
        } catch (\Exception $e) {
            // Handle exception
            return $e;
        }
    }
    
    

    public function deleteMessage($receiptHandle)
    {
        try {
            $this->sqsClient->deleteMessage([
                'QueueUrl' => $this->queueUrl,
                'ReceiptHandle' => $receiptHandle
            ]);
        } catch (\Exception $e) {
            // Handle exception
        }
    }

    public function purgeQueue()
    {
        try {
            $this->sqsClient->purgeQueue([
                'QueueUrl' => $this->queueUrl
            ]);
            return "Queue purged successfully.";
        } catch (\Exception $e) {
            // Handle exception
            return $e->getMessage();
        }
    }
    
    public function sendMessage($messageBody)
    {
        try {
            $result = $this->sqsClient->sendMessage([
                'QueueUrl' => $this->queueUrl,
                'MessageBody' => $messageBody
            ]);

            return $result; // Return result or true, based on your requirement

        } catch (\Exception $e) {
            // Handle exception
            return false; // Or handle the exception as per your requirement
        }
    }
}
