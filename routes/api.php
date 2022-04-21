<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/ping', function () {
    return "ping";
});

Route::post('/login', 'AuthController@login');

Route::middleware(['checkapitoken'])->group(function () {
    Route::resource('celengan', CelenganController::class);
    Route::resource('peserta', PesertaModelController::class);
    Route::resource('qurbansent', QurbanSentController::class);
    Route::get('/pesertazakatfitrahsent', 'PesertaModelController@getpesertazakatfitrahsent');
    Route::get('/pesertazakatfitrahreceived', 'PesertaModelController@getpesertazakatfitrahreceived');
    Route::get('/pesertacelengan', 'PesertaModelController@getpesertacelengan');
    Route::get('/pesertaqurbansent', 'PesertaModelController@getpesertaqurbansent');
    // Route::post('/pesertazakatfitrah', 'PesertaModelController@getpesertazakatfitrah');
    Route::resource('zakatfitrahreceived', ZakatFitrahReceivedController::class);
    Route::resource('zakatfitrahsent', ZakatFitrahSentController::class);


    Route::get('/statuspesertazakatfitrahreceived', 'ZakatFitrahReceivedController@getStatusPesertaZakatFitrahReceived');
    Route::get('/statuszakatfitrahreceived', 'ZakatFitrahReceivedController@getPesertaZakatFitrah');
    Route::get('/statuspesertazakatfitrahsent', 'ZakatFitrahSentController@getStatusPesertaZakatFitrahSent');
    Route::get('/statuszakatfitrahsent', 'ZakatFitrahSentController@getPesertaZakatFitrah');
    Route::get('/statuspesertacelengan', 'CelenganController@getPesertaCelengan');
    Route::get('/statuspesertacelenganmoneyboxsent', 'CelenganController@getPesertaStatusMoneyBoxSent');
    Route::get('/statuspesertacelenganmoneyreceived', 'CelenganController@getPesertaStatusMoneyReceived');
    Route::get('/statuspesertaqurbansent', 'QurbanSentController@getPesertaStatusQurbanSent');
    Route::get('/statuspesertaqurban', 'QurbanSentController@getPesertaQurbanSent');
    Route::get('/statistic', 'StatisticController@index');
    Route::post('/register', 'AuthController@register');
});
