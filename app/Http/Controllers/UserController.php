<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel; 
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;

class UserController extends Controller
{
    public function userInfo($userId){
        // Get user information from database using model
        $user = UserModel::find($userId);
        // Determine whether the user exists
        if ($user) {
            // If the user exists, return JSON of user information
            return response()->json([
                'success' => true,
                'data' => $user
            ],200,['Content-Type' => 'application/json;charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        } else {
            // If the user does not exist, return an error message
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    }

    public function userInfoByEmail($userEmail){
        // Get user information from database using model
        $user = UserModel::where('email', $userEmail)->first();
        // Determine whether the user exists
        if ($user) {
            // If the user exists, return JSON of user information
            return response()->json([
                'success' => true,
                'data' => $user
            ], 200, ['Content-Type' => 'application/json;charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        } else {
            // If the user does not exist, return an error message
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    }
    
    
    public function userHistory($userId){
        // Initialize the DynamoDB client
        $dynamoDbClient = new DynamoDbClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
        ]);
    
        try {
            // Retrieve order information from DynamoDB using the query method and GSI
            $result = $dynamoDbClient->query([
                'TableName' => 'ntu_order_queue',
                'IndexName' => 'UserId-index', // 指定 GSI
                'KeyConditionExpression' => 'UserId = :userId',
                'ExpressionAttributeValues' => [
                    ':userId' => ['N' => (string)$userId]
                ]
            ]);
            
    
            if (isset($result['Items']) && count($result['Items']) > 0) {
                $formattedItems = array_map(function ($item) {
                    $formattedItem = [];
                    foreach ($item as $key => $value) {
                        // The data type returned by DynamoDB is an array containing a single key, where the key name represents the type (such as N or S)
                        $type = array_keys($value)[0];
                        $formattedItem[$key] = $value[$type] === 'N' ? (int)$value[$type] : $value[$type];
                    }
                    return $formattedItem;
                }, $result['Items']);
    
                // If an order is found, return the formatted order data
                return response()->json([
                    'success' => true,
                    'data' => $formattedItems
                ], 200, ['Content-Type' => 'application/json;charset=UTF-8'], JSON_UNESCAPED_UNICODE);
            } else {
                // If the order is not found, a prompt message will be returned.
                return response()->json([
                    'success' => false,
                    'message' => 'No orders found for the user'
                ], 404, ['Content-Type' => 'application/json;charset=UTF-8'], JSON_UNESCAPED_UNICODE);
            }
            
        } catch (DynamoDbException $e) {
            // Handling DynamoDB exceptions
            return response()->json([
                'error' => 'An error occurred while retrieving orders',
                'details' => $e->getMessage()
            ], 500, ['Content-Type' => 'application/json;charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        }
    }
    
    
    
}
