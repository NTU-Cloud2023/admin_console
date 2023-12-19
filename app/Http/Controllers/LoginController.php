<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;
use App\Models\UserModel; 

class LoginController extends Controller
{

    protected $client;

    public function __construct()
    {
        $this->client = new CognitoIdentityProviderClient([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
        ]);
    }

    public function checkLogin(Request $request)
    {
        // get code from cognito
        $code = $request->input('code'); 

        // use Guzzle client to send request to Cognito
        $guzzleClient = new Client();
        $response = $guzzleClient->post('https://sportu.auth.ap-northeast-1.amazoncognito.com/oauth2/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => env('COGNITO_CLIENT_ID'),
                'redirect_uri' => 'https://admin.chillmonkey.tw/checklogin',
                'code' => $code,
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);

        $tokens = json_decode((string) $response->getBody(), true);

        // get user data from Cognito by access token
        $userInfoResponse = $guzzleClient->get('https://sportu.auth.ap-northeast-1.amazoncognito.com/oauth2/userInfo', [
            'headers' => [
                'Authorization' => 'Bearer ' . $tokens['access_token']
            ]
        ]);

        $userInfo = json_decode((string) $userInfoResponse->getBody(), true);

        $email = $userInfo['email'];
        $nickname = $userInfo['nickname'] ?? '標準用戶'; // use default nickname if there is no nickname from Cognito
        // check user exist or not
        $user = UserModel::where('email', $email)->first();

        if (!$user) {
            // if user not exitsted, create a new user in database
            UserModel::create([
                'nick_name' => $nickname,
                'email' => $email
            ]);
        }


        // put user information in session
        Session::put('userInfo', $userInfo);
        Session::put('isLoggedIn', true);

        // redirect to /home page
        return redirect('/home');
    }

    public function frontCheckLogin(Request $request)
    {
        // Frontend will send code to backend for login purpose
        // ** this function may not work **
        $code = $request->input('code'); 

        // use Guzzle client to get token from Cognito
        $guzzleClient = new Client();
        $response = $guzzleClient->post('https://sportu.auth.ap-northeast-1.amazoncognito.com/oauth2/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => env('FRONTEND_COGNITO_CLIENT_ID'),
                'redirect_uri' => 'http://localhost:3000/callback', // don't forget change redirect_uri
                'code' => $code,
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);

        $tokens = json_decode((string) $response->getBody(), true);

        // get user information by access token
        $userInfoResponse = $guzzleClient->get('https://sportu.auth.ap-northeast-1.amazoncognito.com/oauth2/userInfo', [
            'headers' => [
                'Authorization' => 'Bearer ' . $tokens['access_token']
            ]
        ]);

        $userInfo = json_decode((string) $userInfoResponse->getBody(), true);

        $email = $userInfo['email'];
        $nickname = $userInfo['nickname'] ?? '標準用戶'; // use default nickname if there is no nickname from Cognito

        // check user exist or not
        $user = UserModel::where('email', $email)->first();

        if (!$user) {
            // if user not exitsted, create a new user in database
            $user = UserModel::create([
                'nick_name' => $nickname,
                'email' => $email
            ]);
        }

        // return json
        return response()->json([
            'success' => true,
            'message' => '前端登入成功',
            'userInfo' => $user,
        ]);
    }


    public function index()
    {
        // return "hi";
        return view('login');
    }
    
    public function logout(){
        Session::forget('user');
        Session::forget('isLoggedIn');
        return redirect('/');
    }

}
