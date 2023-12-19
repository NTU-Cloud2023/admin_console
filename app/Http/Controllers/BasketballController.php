<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;
use App\Models\Court;
use Illuminate\Support\Facades\Log;
use Aws\LocationService\LocationServiceClient;
use App\Models\CourtCurrent;
use App\Models\UserModel;
use App\Models\UserMessage;
use App\Models\BallType;


class BasketballController extends Controller
{

    private function adjustToPreviousHour($timestamp) {
        $date = new DateTime();
        $date->setTimestamp($timestamp);
        $date->setTime($date->format('H'), 0, 0);
        return $date->getTimestamp();
    }
    
    public function addSpace(Request $request)
    {
        Log::info('addSpace 籃球執行');
        Log::info('接收到的數據', $request->all());
        // 驗證請求數據
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'capacity' => 'required|numeric',
            'address' => 'required|string|max:255',
            'eachtime' => 'required|numeric',
            'in_game' => 'required|numeric',
            'type' => 'required|numeric',
            'pic' => 'required|string'
        ]);
        Log::info($validatedData);
        // 創建並保存新的球場
        try {
            $court = Court::create($validatedData);
            Log::info('球場建立成功:', ['id' => $court->id]);
        } catch (\Exception $e) {
            Log::error('球場建立失敗:', ['error' => $e->getMessage()]);
            // 處理例外
        }
        // Log the order creation in UserMessage
        $balltype = BallType::where('type', $request->type)->first();
        $message = "新的球場被建立：【" . $request->name . "】，球種 【" . $balltype->cht_game_name . "】，管理員為 【" . session('userInfo.nickname') . "】";
        $user = UserModel::where('email', session('userInfo.email'))->first();
        UserMessage::create([
            'user_id' => $user->id,
            'message' => $message,
            'message_timestamp' => now()->timestamp
        ]);


        return redirect('/space/basketball/overview')->with('success', '球場新增成功！');

    }

    public function editSpaceShow($spaceId)
    {
        $court = Court::findOrFail($spaceId); // Fetch the court data by ID
        return view('basketball/edit', compact('court')); // Pass the court data to the view
    }


    public function editSpace(Request $request, $spaceId)
    {
        Log::info('editSpace 籃球執行');
        Log::info('接收到的數據', $request->all());
    
        // Validate request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'capacity' => 'required|numeric',
            'address' => 'required|string|max:255',
            'eachtime' => 'required|numeric',
            'in_game' => 'required|numeric',
            'type' => 'required|numeric',
            'courtImage' => 'sometimes|file|image|max:5000', // Validate the image
        ]);
    
        try {
            // Update the existing court
            $court = Court::findOrFail($spaceId);

            if ($request->hasFile('courtImage')) {
                Log::info('Image file is present.');
                $file = $request->file('courtImage');
                $filename = $file->getClientOriginalName();
                $file->move(public_path('assets/img/court'), $filename);
                $validatedData['pic'] = 'https://admin.chillmonkey.tw/assets/img/court/' . $filename;
            } else {
                Log::info('No image file received.');
            }
    
            $court->update($validatedData);
            Log::info('球場更新成功:', ['id' => $court->id]);
    
            // Optionally, handle other updates or related models
    
        } catch (\Exception $e) {
            Log::error('球場更新失敗:', ['error' => $e->getMessage()]);
            // Handle the exception
            return back()->withErrors('球場更新失敗');
        }
    
        // Redirect after successful update
        return redirect('/space/basketball/overview')->with('success', '球場更新成功！');
    }
    
    public function deleteSpace($spaceId)
    {
        try {
            // Find the court by ID
            $court = Court::findOrFail($spaceId);
    
            // Delete related records in CourtCurrent table
            CourtCurrent::where('court_id', $spaceId)->delete();
    
            // Log the deletion in UserMessage
            $user = UserModel::where('email', session('userInfo.email'))->first();
            if ($user) {
                $message = "球場被刪除：【" . $court->name . "】，管理員為 【" . session('userInfo.nickname') . "】";
                UserMessage::create([
                    'user_id' => $user->id,
                    'message' => $message,
                    'message_timestamp' => now()->timestamp
                ]);
            }
    
            // Now, delete the court
            $court->delete();
            Log::info('球場刪除成功', ['id' => $court->id]);
    
            // Redirect to a specific route with a success message
            return redirect('/space/basketball/overview')->with('success', '球場刪除成功！');
        } catch (\Exception $e) {
            Log::error('球場刪除失敗', ['error' => $e->getMessage()]);
            // Handle the exception
            return back()->withErrors('球場刪除失敗');
        }
    }

    public function overviewSpace(){
        $previousHourTimestamp = (new Court())->adjustToPreviousHour(time());

        // 取得所有類型為籃球的球場
        $basketballCourts = Court::with('ballType')->where('type', 1)->get();

        // 對於每個籃球場，取得與之相關的 CourtCurrent 記錄
        $courts = $basketballCourts->map(function ($court) use ($previousHourTimestamp) {
            $courtCurrent = CourtCurrent::where('court_id', $court->id)
                                        ->where('timestamp', $previousHourTimestamp)
                                        ->first();

            // 提取 headcount，如果為 null 則設為 0
            $court->headcount = $courtCurrent ? $courtCurrent->headcount : 0;

            return $court;
        });
        return view('basketball/overview', compact('courts'));
        
    }
    

}
