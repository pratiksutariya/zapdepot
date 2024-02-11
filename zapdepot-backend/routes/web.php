<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\IntegrationController; 

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

Route::get('Artisan-Command', function () {
    // \Artisan::call('make:command ActiveCampaignJob');
    // \Artisan::call('make:command refreshTokenGohighLevel');
    \Artisan::call('make:command refreshTokenGoToWebinar');
    // \Artisan::call('make:command GoHighLevelSingleJob');
    // \Artisan::call('make:command WebinarAccountJob');

    // $data['art'] = "Hello!";
  
    // // \Artisan::call('migrate');
    //  \Artisan::call('composer dump-autoload');
    //  shell_exec('composer update');
    //  \Artisan::call('make:model sheetEntryCount');
    

    // \Artisan::call('make:model GoogleAccount -m ');
	echo "done";
    // dd($dd);

});


Route::get('redis_test', [IntegrationController::class, 'redis_test'])->name('redirect.redis_test');
Route::get('redis_test', [IntegrationController::class, 'dummyFunction']);
Route::get('sheet', [IntegrationController::class, 'sheets'])->name('sheets');
Route::get('refresh-get', [IntegrationController::class, 'refreshGet'])->name('sheets');
Route::get('google-integration', [IntegrationController::class, 'GoogleSheetIntegration'])->name('google-integration');
Route::get('google-integration-send', [IntegrationController::class, 'GoogleSheetIntegrationSend'])->name('google-integration');
