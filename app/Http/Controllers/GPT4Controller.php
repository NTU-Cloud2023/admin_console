<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\UserModel;
use App\Models\BallType;
use App\Models\Court;
use App\Models\CourtCurrent;
use App\Models\UserMessage;

class GPT4Controller extends Controller
{
    public function askGPT4ChatModel()
    {

        $courtCurrents = CourtCurrent::with('court')
            ->get()
            ->map(function($item) {
                $courtName = $item->court ? $item->court->name : '未知球场';
                return implode(',', ['球場名:'.$courtName, '時段人數:'.$item->headcount, 'unix-timestamp:'.$item->timestamp]);
            })
            ->join('; ');
        
        $userMessages = UserMessage::all()->map(function($item) {
            return implode(',', [$item->message]);
        })->join('; ');

        $question = "你是個商業顧問，我是球場管理者。看完這些資料，給我一些總結，例如幾點幾分，何時球場最熱門、最喜歡用系統的人等等。要直接講結論，不要說你的過程。最後給我一點商業建議。";
        $data = "球場歷史情況: $courtCurrents | 用戶訊息: $userMessages";
        $inputForGPT4 = $question . "\n" . $data;

        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'model' => 'gpt-4-1106-preview', // 修改为GPT-4模型
                'messages' => [
                    ['role' => 'user', 'content' => $inputForGPT4]
                ],
                'temperature' => 1,
                'max_tokens' => 385, // 根据需要调整
                'top_p' => 1,
                'frequency_penalty' => 0,
                'presence_penalty' => 0
            ]
        ]);
        
        $body = json_decode($response->getBody(), true);
        return response()->json([
            'response' => $body
        ]);
        
        
    }

    public function askGPT3ChatModel()
    {

        $courtCurrents = CourtCurrent::with('court')
        ->get()
        ->map(function($item) {
            $courtName = $item->court ? $item->court->name : '未知球場';
            return implode(',', ['球場名:'.$courtName, '時段人數'.$item->headcount, 'unix-timestamp:'.$item->timestamp]);
        })
        ->join('; ');
        $userMessages = UserMessage::all()->map(function($item) {
            return implode(',', [$item->message]);
        })->join('; ');
        


        $question = "你是個商業顧問，我是球場管理者。看完這些資料，給我一些總結，例如幾點幾分，何時球場最熱門、最喜歡用系統的人等等。要直接講結論，不要說你的過程。最後給我一點商業建議。";
        $data = "球場歷史情況: $courtCurrents | 用戶訊息: $userMessages";
        $inputForGPT4 = $question . "\n" . $data;
        


        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo-16k',
                'messages' => [
                    ['role' => 'user', 'content' => $inputForGPT4]
                ],
                'temperature' => 1,
                'max_tokens' => 256,
                'top_p' => 1,
                'frequency_penalty' => 0,
                'presence_penalty' => 0
            ]
        ]);
        
        $body = json_decode($response->getBody(), true);
        return response()->json([
            'response' => $body
        ]);
        
        
    }
        
}
