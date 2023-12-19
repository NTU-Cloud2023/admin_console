<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aws\Sqs\SqsClient;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use App\Models\UserModel;
use App\Models\UserMessage;
use App\Models\Court;
use App\Services\QueueService;

use Illuminate\Support\Facades\Log;
use DateTime;

class OrderController extends Controller
{
    protected $queueService;

    public function __construct(QueueService $queueService)
    {
        $this->queueService = $queueService;
    }

    private function adjustToPreviousHour($timestamp) {
        $date = new DateTime();
        $date->setTimestamp($timestamp);
        $date->setTime($date->format('H'), 0, 0);
        return $date->getTimestamp();
    }

    public function createOrder($spaceId, Request $request)
    {
        $userId = $request->input('userId');
        $rawTimestamp = $request->input('timestamp');

        // Adjust the timestamp to the previous hour
        $adjustedTimestamp = $this->adjustToPreviousHour($rawTimestamp);

        // Retrieve user information using UserModel
        $user = UserModel::find($userId);
        $nickName = $user ? $user->nick_name : '不存在的用戶';

        // Retrieve Court information
        $court = Court::find($spaceId);
        $courtName = $court ? $court->name : '未知的球場';

        // Prepare order data with the adjusted timestamp
        $orderData = [
            'spaceId' => $spaceId,
            'timestamp' => $adjustedTimestamp,
            'userId' => $userId,
            'nickName' => $nickName,
        ];

        // Initialize DynamoDB client
        $dynamoDbClient = new DynamoDbClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
        ]);

        try {
            // Save the order in DynamoDB with a conditional expression
            $dynamoDbClient->putItem([
                'TableName' => 'ntu_order_queue',
                'Item' => [
                    'CourtID' => ['N' => (string)$orderData['spaceId']],
                    'TimestampUserID' => ['S' => $orderData['timestamp']."-".$orderData['userId']],
                    'Timestamp' => ['N' => (string)$orderData['timestamp']],
                    'UserId' => ['N' => (string)$orderData['userId']],
                    'NickName' => ['S' => $orderData['nickName']],
                    'Status' => ['S' => 'Pending'],
                ],
                'ConditionExpression' => 'attribute_not_exists(CourtID) AND attribute_not_exists(TimestampUserID)'
            ]);

            // Log the order creation in UserMessage
            $message = "新的預定：【訂單 #" . $orderData['timestamp']."-".$orderData['userId'] . "】，使用者 【" . $nickName . "】 預訂了 【" . $courtName . "】";
            UserMessage::create([
                'user_id' => $userId,
                'message' => $message,
                'message_timestamp' => now()->timestamp
            ]);

            // If successful, send a message to SQS
            $sqsClient = new SqsClient([
                'region' => env('AWS_DEFAULT_REGION'),
                'version' => 'latest',
            ]);
            $sqsMessage = [
                'courtId' => $spaceId,
                'timestamp' => $adjustedTimestamp,
                'userId' => $userId,
                'nickName' => $nickName,
            ];
            $sqsClient->sendMessage([
                'QueueUrl' => 'https://sqs.ap-northeast-1.amazonaws.com/881643529096/ntu_order_queue',
                'MessageBody' => json_encode($sqsMessage),
            ]);

        } catch (DynamoDbException $e) {
            return response()->json([
                'message' => 'Order already exists',
                'error' => $e->getMessage()
            ], 409);
        }

        return response()->json([
            'message' => 'Order processed successfully',
            'data' => $orderData
        ], 202);
    }


    public function totalQueue()
    {
        $messageCount = $this->queueService->getTotalQueueCount();
        
        return response()->json([
            'message' => 'Total messages in queue',
            'count' => $messageCount
        ], 200);
    }
    
    public function processQueue()
    {

        // step 1: 受理訂單
        $messages = $this->queueService->receiveMessage(1);
        $courts = []; // Initialize the courts array
        $step1 = true;
        Log::info($messages);
        Log::info('queue is empty?' . empty($messages));
        if(empty($messages)||count($messages)==0){
            $step1 = false;
        }
        if($step1){
            foreach ($messages as $message) {
                $messageBody = json_decode($message['Body'], true);
                // Check if 'spaceId' exists in the message body
                if (!isset($messageBody['courtId'])) {
                    Log::error("Missing 'spaceId' in the message body");
                    continue; // Skip this message and continue with the next one
                }
                $courtId = $messageBody['courtId'];
                $timestamp = $messageBody['timestamp'];
                $TimestampUserID = $messageBody['timestamp'] . '-' . $messageBody['userId'];
                // Initialize DynamoDB client
                $dynamoDbClient = new DynamoDbClient([
                    'region' => env('AWS_DEFAULT_REGION'),
                    'version' => 'latest',
                ]);
        
                try {
                    $result = $dynamoDbClient->getItem([
                        'TableName' => 'ntu_order_queue',
                        'Key' => [
                            'CourtID' => ['N' => $courtId],
                            'TimestampUserID' => ['S' => $TimestampUserID],
                        ]
                    ]);
        
                    if (isset($result['Item'])) {
                        $formattedItem = $this->formatDynamoDbItem($result['Item']);
                        Log::info($formattedItem);
        
                        $courtId = $formattedItem['CourtID'];
                        if (!isset($courts[$courtId])) {
                            $courts[$courtId] = [];
                        }
                        $courts[$courtId][] = $formattedItem;
        
                        // Update the status in DynamoDB
                        $updateResult = $dynamoDbClient->updateItem([
                            'TableName' => 'ntu_order_queue',
                            'Key' => [
                                'CourtID' => ['N' => $courtId],
                                'TimestampUserID' => ['S' => $TimestampUserID],
                            ],
                            'UpdateExpression' => 'set #status = :s',
                            'ExpressionAttributeNames' => ['#status' => 'Status'],
                            'ExpressionAttributeValues' => [
                                ':s' => ['S' => 'Waiting']
                            ],
                        ]);
                    }
                    // 受理訂單，準備進入配對
                    // Log the order creation in UserMessage
                    $message_history = "訂單已受理，等待配對中：【訂單 #" . $TimestampUserID . "】，使用者 【" . $formattedItem['NickName'] . "】";
                    UserMessage::create([
                        'user_id' => $formattedItem['UserId'],
                        'message' => $message_history,
                        'message_timestamp' => now()->timestamp
                    ]);
        
                    // Delete the message from the queue after successful processing
                    try {
                        $this->queueService->deleteMessage($message['ReceiptHandle']);
                    } catch (Exception $e) {
                        Log::error("Error deleting message from SQS: " . $e->getMessage());
                    }
                    
                } catch (DynamoDbException $e) {
                    Log::error("DynamoDB Exception: " . $e->getMessage());
                }
            }
            Log::info($courts);
        }
        

        // step 2: 媒合訂單
        $dynamoDbClient = new DynamoDbClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
        ]);
        try {
            // Using query method and GSI to retrieve order information from DynamoDB
            $result = $dynamoDbClient->query([
                'TableName' => 'ntu_order_queue',
                'IndexName' => 'Status-index', // Specify GSI
                'KeyConditionExpression' => '#status = :statusVal',
                'ExpressionAttributeNames' => [
                    '#status' => 'Status' // Replacing reserved keyword with a placeholder
                ],
                'ExpressionAttributeValues' => [
                    ':statusVal' => ['S' => 'Waiting'] // Define the value for the placeholder
                ],
            ]);
            $formattedResult = [];
            foreach ($result['Items'] as $item) {
                $formattedItem = [];
                foreach ($item as $key => $value) {
                    // Assuming all values are either 'N' (number) or 'S' (string)
                    $formattedItem[$key] = $value[array_key_first($value)];
                }
                $formattedResult[] = $formattedItem;
            }
        
            // Log the formatted result
            Log::info(json_encode($formattedResult, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        
            
        } catch (DynamoDbException $e) {
            // Handle DynamoDB exception
            return response()->json([
                'error' => 'An error occurred while retrieving orders',
                'details' => $e->getMessage()
            ], 500, ['Content-Type' => 'application/json;charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        }

        // return response()->json($formattedResult, 200, ['Content-Type' => 'application/json;charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        // 按照球場分類
        // Initialize an array to hold the categorized data
        $categorizedData = [];
        // Iterate through each order and categorize by CourtID
        foreach ($formattedResult as $order) {
            $courtId = $order['CourtID'];

            // Initialize the array for this courtId if not already set
            if (!isset($categorizedData[$courtId])) {
                $categorizedData[$courtId] = [];
            }
            // Add the order to the corresponding courtId category
            $categorizedData[$courtId][] = $order;
        }
        Log::info($categorizedData);

        // 依照球場id找尋場地中的in_game，並把滿足需求的成員拉出來 (同球場 同timestamp)

        $satisfiedOrders = [];

        foreach (array_keys($categorizedData) as $courtId) {
            // Find the court by its ID
            $court = Court::find($courtId);
            if ($court) {
                $inGame = $court->in_game;
        
                // Group orders by timestamp
                $ordersByTimestamp = [];
                foreach ($categorizedData[$courtId] as $order) {
                    $timestamp = $order['Timestamp'];
                    if (!isset($ordersByTimestamp[$timestamp])) {
                        $ordersByTimestamp[$timestamp] = [];
                    }
                    $ordersByTimestamp[$timestamp][] = $order;
                }
        
                // Check each group of orders to find a satisfying group
                foreach ($ordersByTimestamp as $timestamp => $orders) {
                    if (count($orders) >= $inGame) {
                        // If this group satisfies the in_game condition, select these orders
                        $satisfiedOrders[$courtId] = $orders;
                        Log::info("Court ID: $courtId, Timestamp: $timestamp, Satisfied Orders: " . json_encode($orders));
                        break; // Stop checking further as we found a satisfying group
                    }
                }
        
                // Log if no group meets the in_game condition
                if (!isset($satisfiedOrders[$courtId])) {
                    Log::info("Court ID: $courtId does not have a timestamp group meeting the in_game value.");
                }
            } else {
                // Log if the court is not found
                Log::error("Court not found with ID: $courtId");
            }
        }
        
        // Log or return the satisfied orders
        Log::info($satisfiedOrders);
        

        // step 3: 發出通知，並修改dynamodb裡面Status值變成successed，且把他的隊友的nickname寫到key Party 裡面
        foreach ($satisfiedOrders as $courtId => $orders) {
            // Collect the nicknames of all members in this court
            $nicknames = array_column($orders, 'NickName');
            $nicknamesString = implode('、', $nicknames);
            foreach ($orders as $order) {
                // Prepare the notification message
                $notification = "訂單已成功處理：【訂單 #" . $order['TimestampUserID'] . "】，你本場的隊友為【" . $nicknamesString . "】";

                // e.g., sending email, SMS, push notification, etc.
                UserMessage::create([
                    'user_id' => $order['UserId'],
                    'message' => $notification,
                    'message_timestamp' => now()->timestamp
                ]);
                // Log the notification
                Log::info("Notification sent to User ID: " . $order['UserId'] . " - " . $notification);

                // Update the status in DynamoDB to 'successed' and add Party attribute
                try {
                    $updateResult = $dynamoDbClient->updateItem([
                        'TableName' => 'ntu_order_queue',
                        'Key' => [
                            'CourtID' => ['N' => (string)$courtId],
                            'TimestampUserID' => ['S' => $order['TimestampUserID']],
                        ],
                        'UpdateExpression' => 'set #status = :s, #party = :p',
                        'ExpressionAttributeNames' => [
                            '#status' => 'Status',
                            '#party' => 'Party'
                        ],
                        'ExpressionAttributeValues' => [
                            ':s' => ['S' => 'Successed'],
                            ':p' => ['SS' => $nicknames] // Using SS for string set
                        ],
                    ]);
                    Log::info("Order status updated to 'successed' with party for Order ID: " . $order['TimestampUserID']);
                } catch (DynamoDbException $e) {
                    Log::error("DynamoDB Exception on updating status and party: " . $e->getMessage());
                }
            }
        }

        //return 
        return response()->json([
            'message' => 'Order processed successfully'
        ], 200);
    }
    

 
    
    private function formatDynamoDbItem($item)
    {
        $formattedItem = [];
        foreach ($item as $key => $value) {
            $valueType = array_key_first($value);
            $formattedItem[$key] = $value[$valueType];
        }
        return $formattedItem;
    }
    
    
    
}
