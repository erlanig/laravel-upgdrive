<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileUploadController;
use Google\Client;
use Google\Service\Drive;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [FileUploadController::class, 'index']);

Route::post('/upload', [FileUploadController::class, 'upload']);


Route::get('/test-google', function () {

    try {

        $client = new Client();

        $client->setAuthConfig(
            storage_path('app/google/credentials.json')
        );

        $client->addScope(Drive::DRIVE);

        $drive = new Drive($client);

        $about = $drive->about->get([
            'fields' => 'user'
        ]);

        return [
            'success' => true,
            'user' => $about
        ];

    } catch (\Exception $e) {

        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
});

Route::get('/google/auth', function () {

    $client = new Client();

    $client->setClientId(env('GOOGLE_CLIENT_ID'));
    $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));

    $client->setRedirectUri(
        'http://localhost:8000/google/callback'
    );

    $client->addScope(
        Google\Service\Drive::DRIVE
    );

    $client->setAccessType('offline');

    $client->setPrompt('consent');

    return redirect(
        $client->createAuthUrl()
    );
});

Route::get('/google/callback', function () {

    $client = new Client();

    $client->setClientId(env('GOOGLE_CLIENT_ID'));
    $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));

    $client->setRedirectUri(
        'http://localhost:8000/google/callback'
    );

    $token = $client->fetchAccessTokenWithAuthCode(
        request('code')
    );

    dd($token);
});

Route::get('/test-token', function () {

    $client = new Google\Client();

    $client->setClientId(env('GOOGLE_CLIENT_ID'));

    $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));

    $client->refreshToken(
        env('GOOGLE_REFRESH_TOKEN')
    );

    return [
        'expired' => $client->isAccessTokenExpired(),
        'token' => $client->getAccessToken()
    ];
});