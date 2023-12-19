<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Court;
use App\Models\CourtCurrent;
use App\Models\UserMessage; // Assuming this is your message history model
use App\Models\UserModel;
use Aws\DynamoDb\DynamoDbClient;
use App\Services\QueueService;
use DateTime;


class MVPController extends Controller
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

    public function orderAdd(Request $request)
    {
        $userId = $request->input('userId');
        $courtId = $request->input('courtId');
        $rawTimestamp = $request->input('timestamp');
    
        // Adjust the timestamp to the previous hour
        $adjustedTimestamp = $this->adjustToPreviousHour($rawTimestamp);
        // Retrieve user and court information
        $user = UserModel::find($userId);
        $nickName = $user ? $user->nick_name : '不存在的用戶';
        $court = Court::find($courtId);
        $courtName = $court ? $court->name : '未知的球場';
    
        // Prepare order data with the adjusted timestamp
        $orderData = [
            'courtId' => $courtId,
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
                    'CourtID' => ['N' => (string)$orderData['courtId']],
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
            $this->queueService->sendMessage(json_encode($orderData));
    
            // Redirect back with a success message
            return back()->with('status', 'Order added successfully.');
    
        } catch (\Aws\DynamoDb\Exception\DynamoDbException $e) {
            // Redirect back with an error message
            return back()->with('status', 'Failed to add order: ' . $e->getMessage());
        }
    }

    public function calculateDistance(Request $request)
    {
        // 從表單接收當前位置的經緯度
        $startLatitude = $request->input('latitude');
        $startLongitude = $request->input('longitude');
    
        // 從 Model Court 中使用 ID 獲取目的地的經緯度
        $courtId = $request->input('id');
        $court = Court::find($courtId);
        $endLatitude = $court->latitude;
        $endLongitude = $court->longitude;
    
        // 構建 OSRM API 的 URL
        $url = "http://router.project-osrm.org/route/v1/walking/"; // 更改為步行路線
        $url .= $startLongitude . "," . $startLatitude . ";";
        $url .= $endLongitude . "," . $endLatitude;
        $url .= "?overview=full";
    
        // 使用 cURL 發送請求
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $response = curl_exec($ch);
        curl_close($ch);


        // 解析 JSON 響應
        $data = json_decode($response, true);

        // 計算距離和時間
        $distanceMeters = $data['routes'][0]['distance'];
        $durationSeconds = $data['routes'][0]['duration'];

        // 將距離轉換為公里（如果超過1000米）
        if ($distanceMeters > 1000) {
            $distance = round($distanceMeters / 1000, 2) . " 公里";
        } else {
            $distance = $distanceMeters . " 米";
        }

        // 將時間轉換為更友好的格式（例如，分鐘）
        $duration = round($durationSeconds / 60) . " 分鐘";

        // 從 API 響應中獲取編碼的路徑字符串
        $encodedPath = $data['routes'][0]['geometry'];

        // 使用 decodePolyline 方法解碼 Polyline
        $pathCoordinates = $this->decodePolyline($encodedPath);

        // 返回計算結果和路徑坐標
        return response()->json([
            'distance' => $distance,
            'duration' => $duration,
            'path' => $pathCoordinates,
        ]);
        // 解析 JSON 
        // 
        // return $data;
        // 距離轉換為公里（如果超過1000米）
        // $distance = $data['routes'][0]['distance'];
        // if ($distance > 1000) {
        //     $distance = round($distance / 1000, 2) . " 公里";
        // } else {
        //     $distance .= " 米";
        // }
    
        // 預計耗時（以秒為單位）
        // $duration = $data['routes'][0]['duration'] . " 秒";
    
        // 返回計算結果
        // return "路線距離: " . $distance . "\n" .
        //        "步行預計耗時: " . $duration . "\n";
    }
    public function orderClear()
    {
        $queueService = new QueueService();
        $result = $queueService->purgeQueue();
        // Clearing DynamoDB contents
        $dynamoDbResult = $this->clearDynamoDb();

        // Clearing message history from the database
        // UserMessage::truncate();
        return back()->with('status', $result);
    }


    private function clearDynamoDb()
    {
        $dynamoDbClient = new DynamoDbClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
        ]);
    
        $tableName = 'ntu_order_queue'; 
    
        try {
            // scan partition key
            $items = $dynamoDbClient->getIterator('Scan', [
                'TableName' => $tableName,
                'AttributesToGet' => ['CourtID', 'TimestampUserID'] // CourtID(Partition key), TimestampUserID(Sortkey)
            ]);
    
            foreach ($items as $item) {
                // Delete all of the DynameDB Table data (for orders)
                $dynamoDbClient->deleteItem([
                    'TableName' => $tableName,
                    'Key' => [
                        'CourtID' => $item['CourtID'],
                        'TimestampUserID' => $item['TimestampUserID']
                    ]
                ]);
            }
    
            return "DynamoDB cleared successfully.";
    
        } catch (\Exception $e) {
            // do something, but not now
            return $e->getMessage();
        }
    }

    public function orderhistoryclear(){
        // Delete messages containing "新的預定"
        UserMessage::where('message', 'like', '%新的預定%')->delete();
        UserMessage::where('message', 'like', '%訂單已受理，等待配對中%')->delete();
        UserMessage::where('message', 'like', '%訂單已成功處理%')->delete();
        CourtCurrent::query()->update(['headcount' => 0]);
        // Redirect back with a status message
        return back()->with('status', '清空history中關於訂單的部分。');
    }
    


    public function decodePolyline($encoded)
    {
        $length = strlen($encoded);
        $index = 0;
        $points = [];
        $lat = 0;
        $lng = 0;
    
        while ($index < $length) {
            $shift = $result = 0;
            do {
                $b = ord($encoded[$index++]) - 63;
                $result |= ($b & 0x1f) << $shift;
                $shift += 5;
            } while ($b >= 0x20);
            $dlat = (($result & 1) ? ~($result >> 1) : ($result >> 1));
            $lat += $dlat;
    
            $shift = $result = 0;
            do {
                $b = ord($encoded[$index++]) - 63;
                $result |= ($b & 0x1f) << $shift;
                $shift += 5;
            } while ($b >= 0x20);
            $dlng = (($result & 1) ? ~($result >> 1) : ($result >> 1));
            $lng += $dlng;
    
            $points[] = [$lat * 1e-5, $lng * 1e-5];
        }
    
        return $points;
    }
}
