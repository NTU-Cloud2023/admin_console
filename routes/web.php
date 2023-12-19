<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BasketballController;
use App\Http\Controllers\VolleyballController;
use App\Http\Controllers\TennisController;
use App\Http\Controllers\BadmintonController;
use App\Http\Controllers\MVPController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GPT4Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Court;
use App\Models\CourtCurrent;
use App\Models\BallType;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});


Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::get('/checklogin', [LoginController::class, 'checkLogin']);

Route::any('/logout', [LoginController::class, 'logout'])->name('logout');

/*
iam
*/
Route::get('/frontchecklogin', [LoginController::class, 'frontCheckLogin']);


/*
Dashboard
*/
Route::get('/home', [DashboardController::class, 'showHome']);

/*
customer
*/
Route::get('/customer/overview', [CustomerController::class, 'overviewCustomer']);


/*
space
*/

/*
user
*/
Route::get('/v1/users/email/{userInfoByEmail}', [UserController::class, 'userInfoByEmail']);
Route::get('/v1/users/{userId}', [UserController::class, 'userInfo']);
Route::get('/v1/users/{userId}/history', [UserController::class, 'userHistory']);
Route::get('/v1/users/{userId}/messages', [MessageController::class, 'userMessages']);
Route::get('/v1/users/closemessage/{messageId}', [MessageController::class, 'closeMessage']);
Route::get('/v1/googlemapapi/apikey', function(){
    return response()->json(['api_key' => config('services.googlemaps.api_key')]);
});
/*
space -> basketball
*/
Route::post('/space/basketball/addSpace', [BasketballController::class, 'addSpace']);
Route::get('/space/basketball/overview', [BasketballController::class, 'overviewSpace']);
Route::get('/space/basketball/add', function () {
    return view('basketball/add');
});
Route::get('/space/basketball/edit/{spaceId}', [BasketballController::class, 'editSpaceShow']);
Route::put('/space/basketball/edit/{spaceId}', [BasketballController::class, 'editSpace']);
Route::get('/space/basketball/delete/{spaceId}', [BasketballController::class, 'deleteSpace']);

/*
space -> vollyball
*/
Route::post('/space/volleyball/addSpace', [VolleyballController::class, 'addSpace']);
Route::get('/space/volleyball/overview', [VolleyballController::class, 'overviewSpace']);
Route::get('/space/volleyball/add', function () {
    return view('volleyball/add');
});
Route::get('/space/volleyball/edit/{spaceId}', [VolleyballController::class, 'editSpaceShow']);
Route::put('/space/volleyball/edit/{spaceId}', [VolleyballController::class, 'editSpace']);
Route::get('/space/volleyball/delete/{spaceId}', [VolleyballController::class, 'deleteSpace']);

/*
space -> tennis
*/
Route::post('/space/tennis/addSpace', [TennisController::class, 'addSpace']);
Route::get('/space/tennis/overview', [TennisController::class, 'overviewSpace']);
Route::get('/space/tennis/add', function () {
    return view('tennis/add');
});
Route::get('/space/tennis/edit/{spaceId}', [TennisController::class, 'editSpaceShow']);
Route::put('/space/tennis/edit/{spaceId}', [TennisController::class, 'editSpace']);
Route::get('/space/tennis/delete/{spaceId}', [TennisController::class, 'deleteSpace']);

/*
space -> badminton
*/
Route::post('/space/badminton/addSpace', [BadmintonController::class, 'addSpace']);
Route::get('/space/badminton/overview', [BadmintonController::class, 'overviewSpace']);
Route::get('/space/badminton/add', function () {
    return view('badminton/add');
});
Route::get('/space/badminton/edit/{spaceId}', [BadmintonController::class, 'editSpaceShow']);
Route::put('/space/badminton/edit/{spaceId}', [BadmintonController::class, 'editSpace']);
Route::get('/space/badminton/delete/{spaceId}', [BadmintonController::class, 'deleteSpace']);


/*
space -> balltype
*/
Route::get('/space/balltype/overview', function () {
    $ball_types = Balltype::get();
    return view('balltype/overview', compact('ball_types'));
});


/*
API
*/

Route::get('/v1/spaces/sports', [SpaceController::class, 'sports']);
Route::get('/v1/spaces/nearby', [SpaceController::class, 'nearbySportId']);//?lat={latitude}&lng={longitude}&sportId={sportId}&count={count}
Route::get('/v1/spaces/calculate', [SpaceController::class, 'calculateSpaces']);
Route::get('/v1/spaces/{spaceId}', [SpaceController::class, 'spaceInfo']);
Route::get('/v1/spaces/{spaceId}/distance', [SpaceController::class, 'spaceDistance']); //?lat={latitude}&lng={longitude}
Route::get('/v1/spaces/{spaceId}/timeSlots', [SpaceController::class, 'freeSlots']);
Route::get('/v1/spaces', [SpaceController::class, 'listSpaces']);



/*
order
*/
Route::get('/v1/queue/total_queue', [OrderController::class, 'totalQueue']);
Route::get('/v1/queue/process', [OrderController::class, 'processQueue']);
Route::post('/v1/spaces/{spaceId}/reserve', [OrderController::class, 'createOrder']);


/*
測試db
*/
Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return 'Database connection successful.';
    } catch (\Exception $e) {
        return 'Failed to connect to the database: ' . $e->getMessage();
    }
});



/*
概念驗證
*/
Route::get('/mvp/distance', function () {
    return view('MVP/distance');
});
Route::post('/mvp/distance', [MVPController::class, 'calculateDistance']);

Route::get('/mvp/orderclear', function () {
    return view('MVP/orderclear');
});
Route::post('/mvp/orderclear', [MVPController::class, 'orderClear']);
Route::post('/mvp/orderhistoryclear', [MVPController::class, 'orderhistoryclear']);


Route::get('/mvp/orderadd', function () {
    return view('MVP/orderadd');
});
Route::post('/mvp/orderadd', [MVPController::class, 'orderAdd']);


Route::get('/mvp/messages', function () {
    return view('message/add');
});
Route::post('/mvp/messages', [MessageController::class, 'sendMessages']);


/*
GPT4
*/
Route::get('/ask-gpt4', [GPT4Controller::class, 'askGPT4ChatModel']);