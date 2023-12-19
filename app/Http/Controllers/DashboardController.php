<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\QueueService;
use App\Models\Court;
use App\Models\CourtCurrent;
use App\Models\UserMessage;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\DynamoDbClient;

class DashboardController extends Controller
{
    protected $queueService;

    public function __construct(QueueService $queueService)
    {
        $this->queueService = $queueService;
    }


    private function adjustToPreviousHour($timestamp) {
        $date = new \DateTime();
        $date->setTimestamp($timestamp);
        $date->setTime($date->format('H'), 0, 0);
        return $date->getTimestamp();
    }

    public function showHome()
    {
        if (Session::get('isLoggedIn')) {
            // Use QueueService to get the queue count
            $queueCount = $this->queueService->getTotalQueueCount();
    
            // Get total capacity of all courts
            $totalCapacity = $this->getTotalCourtCapacity();
    
            // 獲取當前時間的前一個整點小時
            $previousHourTimestamp = $this->adjustToPreviousHour(time());
            $allCourts = Court::with('ballType')->get();

            // get spaces exists or not
            if ($allCourts->isEmpty()) {
                return response()->json(['message' => 'No courts found'], 404);
            }
            $courts = $allCourts->map(function ($court) use ($previousHourTimestamp) {
                $courtCurrent = CourtCurrent::where('court_id', $court->id)
                                            ->where('timestamp', $previousHourTimestamp)
                                            ->first();
    
                // get headcount, return 0 if headcount record is null
                $court->headcount = $courtCurrent ? $courtCurrent->headcount : 0;
    
                return $court;
            });

            // DynamoDB client init
            $dynamoDbClient = new DynamoDbClient([
                'region' => env('AWS_DEFAULT_REGION'),
                'version' => 'latest',
            ]);
            
            try {
                // query DynamoDB table 'Successed' record
                $successResult = $dynamoDbClient->query([
                    'TableName' => 'ntu_order_queue',
                    'IndexName' => 'Status-index',
                    'KeyConditionExpression' => '#status = :statusVal',
                    'ExpressionAttributeNames' => [
                        '#status' => 'Status',
                    ],
                    'ExpressionAttributeValues' => [
                        ':statusVal' => ['S' => 'Successed'],
                        ],
                    ]);
                // sum together
                $successfulMatchesCount = count($successResult['Items']);

                // query DynamoDB table 'Waiting' record
                $waitingResult = $dynamoDbClient->query([
                    'TableName' => 'ntu_order_queue',
                    'IndexName' => 'Status-index',
                    'KeyConditionExpression' => '#status = :statusVal',
                    'ExpressionAttributeNames' => [
                        '#status' => 'Status',
                    ],
                    'ExpressionAttributeValues' => [
                        ':statusVal' => ['S' => 'Waiting'],
                    ],
                ]);
                $waitingMatchesCount = count($waitingResult['Items']);

                // query DynamoDB table 'Failed' record
                $failedResult = $dynamoDbClient->query([
                    'TableName' => 'ntu_order_queue',
                    'IndexName' => 'Status-index',
                    'KeyConditionExpression' => '#status = :statusVal',
                    'ExpressionAttributeNames' => [
                        '#status' => 'Status',
                    ],
                    'ExpressionAttributeValues' => [
                        ':statusVal' => ['S' => 'Failed'],
                    ],
                ]);
                $failedMatchesCount = count($failedResult['Items']);
    
            } catch (DynamoDbException $e) {
                Log::error("DynamoDB Exception: " . $e->getMessage());
                $successfulMatchesCount = 0; 
                $waitingMatchesCount = 0;
                $failedMatchesCount = 0; 
            }

                        
            // get UserMessage data
            $userMessages = UserMessage::orderBy('message_timestamp', 'desc')->take(10)->get(); // get the newest 10
    
            // send data to view
            return view('welcome', compact('queueCount', 'totalCapacity', 'courts', 'userMessages', 'successfulMatchesCount', 'waitingMatchesCount', 'failedMatchesCount'));
        } else {
            return redirect('/');
        }
    }
    

    // 新增一個方法來獲取所有球場的總容量
    protected function getTotalCourtCapacity()
    {
        return Court::sum('capacity'); // 假設球場容量儲存在 capacity 欄位
    }
}
