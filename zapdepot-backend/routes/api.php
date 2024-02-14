<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\IntegrationController;
use App\Http\Controllers\API\Zapcontroller;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::controller(AuthController::class)->group(function(){
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:api');
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::controller(UserController::class)->group(function(){
        Route::post('update-user', 'updateUser');
        Route::post('update-user-password', 'updateUserPassword');
        Route::get('get-dashboard-data', 'getUserDash');
        Route::group(['prefix'=>'user','middleware' =>  ['role:admin']], function () {
            Route::get('get-dashboard', 'getAdminDash');
            Route::get('get-admin-user', 'getAdminUser');
            Route::get('get-single-user/{id}', 'getSingleUser');
            Route::get('delete-user/{id}', 'deleteUser');
            Route::post('add-admin-user', 'addAdminUser');
        });
    });
    Route::controller(IntegrationController::class)->group(function(){
        Route::get('get-all-account-go','getAllAccountGo');
        Route::get('get-all-google-accounts','getGoogleAccounts');
        Route::post('get-all-google-accounts-sheets','getGoogleAccountsSheets');
        Route::get('delete-google-accounts/{id}','deleteGoogleAccounts');
        Route::get('get-all-account-go-single','getAllAccountGoSingle');
        Route::post('add-gohighlevel', 'addGohighlevel');
        Route::post('add-gohighlevel-single', 'addGohighlevelSingle');
        Route::get('delete-gohighlevel-account/{id}', 'deleteGohighlevelAccount');
        Route::post('get-gohighlevel-tags', 'getGohighlevelTags');
        Route::get('get-all-account-active','getAllAccountActive');
        Route::get('get-all-aweber','getAllAweber');
        Route::post('add-acitve-campaign', 'addAcitveCampaign');
        Route::get('delete-acitve-campaign-account/{id}', 'deleteActiveCamAccount');
        Route::get('get-active-campaign-tags/{id}','getActiveCampaignTags');
        Route::get('get-active-campaign-list/{id}','getActiveCampaignList');
        Route::get('connect-gotowebinar/{id}','connect_gotowebinar');
        Route::post('connect-gotowebinar-events','gotowebinarUpWebs');
        Route::post('connect-aweber-account-events','aweberData');
        Route::post('integration/add/gotowebinar','connectGotoWebinar');
        Route::post('integration/connect-to-google-account','googleAccountConnect');
        Route::post('integration/add-connect-to-google-account','googleAccount');
        Route::post('integration/add-connect-to-aweber-account','aweberAccount');
        Route::get('integration-get-data-gotowebinar','gowebinarAlldata');
        Route::get('delete-single-gotowebinar/{id}','deleteGotoWebinar');
        Route::get('delete-aweber-accounts/{id}','deleteAweberAccount');
    });
    Route::controller(Zapcontroller::class)->group(function(){
        Route::get('get-all-zaps','getAllzaps');
        Route::post('add-zap', 'addzap');
        Route::post('update-status-zap', 'updateStatusZap');
        Route::post('update-name-zap', 'updatenameZap');
        Route::post('update-zap', 'updateZap');
        Route::get('delete-zap/{id}', 'deleteZap');
        Route::get('get-zap/{id}', 'getZap');
        Route::get('get-all-logs','GetContact');
        Route::get('get-zap-logs/{id}','getZapsLogs');
        Route::get('clear-all-logs','clear_zap_log');
        Route::get('error-log-list','get_error_log');
        Route::get('clear-error-log-list','clear_error_log');
    });
});

Route::controller(IntegrationController::class)->group(function(){
    Route::get('integration/add/google-accounts','googleAccount');
    Route::get('refresh-tokens','refreshAweber');
    Route::get('send-aweber-data','sendAweberData');
});
Route::controller(Zapcontroller::class)->group(function(){
        Route::get('dis-job','dis');
        Route::get('goheigh-to-webinar','goheight_to_webinar');
        Route::get('goheight-to-activecamp','goheight_to_activecamp');
        Route::get('activecamp-to-goheight','activecamp_to_goheight');
        Route::get('activecamp-to-webinar','activecamp_to_webinar');
        Route::get('webinar-to-goheight','webinar_to_goheight');

});
