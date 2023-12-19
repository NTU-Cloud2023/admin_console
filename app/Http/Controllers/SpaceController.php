<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BallType;
use App\Models\Court;
use App\Models\CourtLog;
use App\Models\CourtCurrent;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;

class SpaceController extends Controller
{

    private function adjustToPreviousHour($timestamp) {
        $date = new \DateTime();
        $date->setTimestamp($timestamp);
        $date->setTime($date->format('H'), 0, 0);
        return $date->getTimestamp();
    }

    public function sports()
    {
        // 拉出球種
        $ballTypes = BallType::all();

        // return json utf8
        return response()->json($ballTypes, 200, [], JSON_UNESCAPED_UNICODE);
    }


    public function spaceInfo($spaceId)
    {
        $previousHourTimestamp = (new Court())->adjustToPreviousHour(time());
    
        // 使用 Court left join balltype
        $court = Court::with('ballType')->find($spaceId);
    
        // if court not found
        if (!$court) {
            return response()->json(['message' => 'Court not found'], 404);
        }
    
        // Get the CourtCurrent record matching a specific timestamp
        $courtCurrent = CourtCurrent::where('court_id', $court->id)
                                    ->where('timestamp', $previousHourTimestamp)
                                    ->first();
    
        // Extract headcount, or set to 0 if null
        $headcount = $courtCurrent ? $courtCurrent->headcount : 0;
    
        // Create a Google Maps navigation URL
        $navUrl = "https://www.google.com/maps/dir/?api=1&destination=" . $court->latitude . "," . $court->longitude;
    
        // Build a custom response structure
        $response = [
            'id' => $court->id,
            'name' => $court->name,
            'latitude' => $court->latitude,
            'longitude' => $court->longitude,
            'capacity' => $court->capacity,
            'type' => $court->type,
            'address' => $court->address,
            'eachtime' => $court->eachtime,
            'in_game' => $court->in_game,
            'pic' => $court->pic,
            'updated_at' => $court->updated_at,
            'created_at' => $court->created_at,
            'ball_type' => $court->ballType,
            'headcount' => $headcount,
            'nav_url' => $navUrl, // Add Google Maps navigation link
        ];
    
        // Return a custom response
        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }
    

    public function spaceDistance($spaceId, Request $request)
    {
        // Get the latitude and longitude of the starting point from the request
        $startLatitude = $request->input('lat');
        $startLongitude = $request->input('lng');
        // Get the latitude and longitude of the end point from the Court model
        $court = Court::find($spaceId);
        if (!$court) {
            return response()->json(['error' => 'Court not found'], 404);
        }
        $endLatitude = $court->latitude;
        $endLongitude = $court->longitude;
    
        // Build the URL of the OSRM API
        $url = "http://router.project-osrm.org/route/v1/walking/";
        $url .= $startLongitude . "," . $startLatitude . ";";
        $url .= $endLongitude . "," . $endLatitude;
        $url .= "?overview=false";
    
        // Send a request using cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $response = curl_exec($ch);
        curl_close($ch);
    
        // Parse JSON response
        $data = json_decode($response, true);
        // Convert distance to kilometers (if more than 1000 meters)
        $distance = $data['routes'][0]['distance'];
        // if ($distance > 1000) {
        //     $distance = round($distance / 1000, 2) . " 公里";
        // } else {
        //     $distance .= " 米";
        // }
    
        // Estimated time (in seconds)
        $duration = $data['routes'][0]['duration'];
    
        // Return the calculation result
        return response()->json([
            'distance' => $distance,
            'duration' => $duration
        ]);
    }

    
    public function listSpaces()
    {
        $previousHourTimestamp = (new Court())->adjustToPreviousHour(time());

        $allCourts = Court::with('ballType')->get();

        // get spaces exists or not
        if ($allCourts->isEmpty()) {
            return response()->json(['message' => 'No courts found'], 404);
        }
        // get courtcurrent record for each spaces
        $courts = $allCourts->map(function ($court) use ($previousHourTimestamp) {
            $courtCurrent = CourtCurrent::where('court_id', $court->id)
                                        ->where('timestamp', $previousHourTimestamp)
                                        ->first();

            // get headcount, return 0 if headcount record is null
            $court->headcount = $courtCurrent ? $courtCurrent->headcount : 0;

            return $court;
        });
        // construct customize data
        $response = $courts->map(function ($court) {
            // create google map link for each courts
            $navUrl = "https://www.google.com/maps/dir/?api=1&destination=" . $court->latitude . "," . $court->longitude;
            
            // 获取与特定时间戳匹配的 CourtCurrent 记录的 headcount
            return [
                'id' => $court->id,
                'name' => $court->name,
                'latitude' => $court->latitude,
                'longitude' => $court->longitude,
                'capacity' => $court->capacity,
                'type' => $court->type,
                'address' => $court->address,
                'eachtime' => $court->eachtime,
                'in_game' => $court->in_game,
                'pic' => $court->pic,
                'updated_at' => $court->updated_at,
                'created_at' => $court->created_at,
                'ball_type' => $court->ballType,
                'headcount' => $court->headcount,
                'nav_url' => $navUrl, // Add Google Maps navigation link
            ];
        });
    
        // Returns responses for all courts
        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }
    
    

    public function nearbySportId(Request $request){
        // 從request中取得起點
        $startLatitude = $request->input('lat');
        $startLongitude = $request->input('lng');
        $sportID = $request->input('sportId');
        $count = $request->input('count');
    
        // 用sportID把相關的球場拉出來
        $courts = Court::with(['ballType', 'CourtCurrent'])
            ->where('type', $sportID)
            ->get();

        // 用相減的絕對值粗估遠近(非真實)，並添加 headcount 到第一層
        $courts = $courts->map(function ($court) use ($startLatitude, $startLongitude) {
            $latDifference = abs($court->latitude - $startLatitude);
            $lngDifference = abs($court->longitude - $startLongitude);
            $court->weight = $latDifference + $lngDifference;

            // 將 headcount 拉到第一層
            $court->headcount = $court->CourtCurrent ? $court->CourtCurrent->headcount : null;

            return $court;
        });
    
        // 控制送出給API的數量
        $sortedCourts = $courts->sortBy('weight')->take($count);
    
        // 對這些球場送osrm api 取得實際距離
        $sortedCourts->transform(function ($court) use ($startLatitude, $startLongitude) {
            $url = "http://router.project-osrm.org/route/v1/walking/" 
                . $startLongitude . "," . $startLatitude . ";"
                . $court->longitude . "," . $court->latitude
                . "?overview=false";
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            $response = curl_exec($ch);
            curl_close($ch);
    
            $data = json_decode($response, true);
            $court->actual_distance = $data['routes'][0]['distance'];
            return $court;
        });
    
        // 根據實際距離再次排序
        $sortedCourts = $sortedCourts->sortBy('actual_distance');
    
        // 把資料弄乾淨，輸出json array
        $sortedCourts = $sortedCourts->values()->all();
    
        // 用utf8 輸出，保持中文
        return response()->json($sortedCourts, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function freeSlots($spaceId, Request $request){
        $slotsString = $request->input('slots');
        $slotsArray = explode(',', $slotsString);
    
        // Create an array to hold slot objects
        $slotObjects = [];
    
        // Fetch the Court data only once outside the loop
        $court = Court::find($spaceId);
        if (!$court) {
            return response()->json(['message' => 'Court not found'], 404);
        }
    
        foreach ($slotsArray as $slot) {
            // Query CourtCurrent to get the headcount for each slot
            $courtCurrent = CourtCurrent::where('court_id', $spaceId)
                                        ->where('timestamp', $slot)
                                        ->first();
    
            $headcount = $courtCurrent ? $courtCurrent->headcount : 0;
    
            // Create an array for this slot
            $slotObject = [
                'timeslot' => $slot,
                'spaceid' => $spaceId,
                'capacity' => $court->capacity,
                'ball_type' => $court->type, // Assuming this is the correct field for ball type
                'headcount' => $headcount,
            ];
    
            $slotObjects[] = $slotObject;
        }
    
        return $slotObjects;
    }
    
    
    public function calculateSpaces(){
        //step 1，取出所有 Status 為 Successed 的內容，壓平變成json object
        // 初始化 DynamoDB 客戶端
        $dynamoDbClient = new DynamoDbClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
        ]);

        try {
            // 查詢 DynamoDB 表中所有狀態為 'Successed' 的記錄
            $result = $dynamoDbClient->query([
                'TableName' => 'ntu_order_queue',
                'IndexName' => 'Status-index',
                'KeyConditionExpression' => '#status = :statusVal',
                'ExpressionAttributeNames' => [
                    '#status' => 'Status'
                ],
                'ExpressionAttributeValues' => [
                    ':statusVal' => ['S' => 'Successed']
                ],
            ]);

            // 格式化查詢結果
            $formattedResult = [];
            foreach ($result['Items'] as $item) {
                $formattedItem = $this->formatDynamoDbItem($item);
                $formattedResult[] = $formattedItem;
            }

        } catch (DynamoDbException $e) {
            Log::error("DynamoDB Exception: " . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while retrieving orders',
                'details' => $e->getMessage()
            ], 500, ['Content-Type' => 'application/json;charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        }

        // 返回格式化結果，以便在外層進行後續步驟 2、3 和 4 的處理
        // return $formattedResult;

        //Step 2，依照球場跟timestamp分好類，統合在一起
        $categorizedData = [];
        foreach ($formattedResult as $item) {
            $courtId = $item['CourtID'];
            $timestamp = $item['Timestamp'];
    
            // 初始化對應的球場和時間戳的數據結構
            if (!isset($categorizedData[$courtId])) {
                $categorizedData[$courtId] = [];
            }
            if (!isset($categorizedData[$courtId][$timestamp])) {
                $categorizedData[$courtId][$timestamp] = [
                    'CourtID' => $courtId,
                    'Timestamp' => $timestamp,
                    'Detail' => []
                ];
            }
    
            // 將數據添加到對應的分類中
            $categorizedData[$courtId][$timestamp]['Detail'][] = $item;
        }
    
        // 把分類後的數據轉換為陣列
        $outputData = [];
        foreach ($categorizedData as $courtItems) {
            foreach ($courtItems as $timestampItems) {
                $outputData[] = $timestampItems;
            }
        }
    
        // 返回整合後的數據
        // return $outputData;
        // Step 3: 將統計數據寫入 court order 數據庫
        foreach ($outputData as $courtData) {
            foreach ($courtData['Detail'] as $order) {
                // 檢查是否已存在對應的記錄
                $existingRecord = CourtCurrent::where('court_id', $courtData['CourtID'])
                                            ->where('timestamp', $courtData['Timestamp'])
                                            ->first();

                if ($existingRecord) {
                    // 如果記錄已存在，則更新它
                    $existingRecord->update([
                        'headcount' => count($courtData['Detail']) // 更新頭數
                    ]);
                } else {
                    // 如果記錄不存在，則創建新的記錄
                    CourtCurrent::create([
                        'court_id' => $courtData['CourtID'],
                        'headcount' => count($courtData['Detail']), // 設置頭數
                        'timestamp' => $courtData['Timestamp']
                    ]);
                }
            }
        }
        return $outputData;
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
