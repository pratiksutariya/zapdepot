<?php

namespace App\Console;

use App\Http\Controllers\API\IntegrationController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Zap;
use App\Models\GohighlevelAccounts;
use App\Models\GoToWebinarAccounts;
use App\Jobs\getGohighlevelContact;
use App\Jobs\getGoogleSheetsData; 
use App\Jobs\googleAccountsRefreshToken;
use App\Jobs\refreshTokenGohighLevel;
use App\Jobs\refreshTokenGoToWebinar;
use App\Jobs\getActiveCampaignContact;
use App\Jobs\getGohighlevelContactsSingle;
use App\Jobs\getWebinarContact;
use Illuminate\Support\Facades\Log;
use App\Models\GoogleAccount; 
use Illuminate\Support\Stringable; 

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected $commands = [
        Commands\WebinarCommand::class,
        Commands\DispatchJob::class,
        Commands\GoHighLevelJob::class, 
        Commands\refreshTokenGoToWebinar::class,
        Commands\refreshTokenGohighLevel::class,
        Commands\WebinarAccountJob::class,
        Commands\GoHighLevelSingleJob::class,
        Commands\ActiveCampaignJob::class,  //activeCampaign:call
    ]; 
    protected function schedule(Schedule $schedule)
    {
        // \Log::info("success-oksssssssss");
        $schedule->command('webinar:call')
                 ->hourly();
        // $schedule->command('inspire')->hourly();
        //  $googleAccounts = GoogleAccount::where('refresh_token' , '!=' , "")->get();
        // \Log::info($googleAccounts);

        // foreach($googleAccounts as $account) {
        //         $schedule->job(new googleAccountsRefreshToken($account))->everyMinute();
        // } 
        // \Log::info("success-ok");
       
        \DB::statement("SET SQL_MODE=''");
        $zaps = Zap::where("status",1)
        ->whereNotNull('sender_id')
        ->whereNotNull('receiver_id')
        ->groupBy("sender_name")
        ->get(); 
        foreach($zaps as $zap){ 
            // if(($zap->sender_name=='gohighlevel')&&($zap->receiver_name=='webinar_account')){
            //     $schedule->call(function () {
            //         app(\App\Http\Controllers\Zapcontroller::class)->goheight_to_webinar();
            //     })->everyMinute();
            // }else if(($zap->sender_name=='gohighlevel')&&($zap->receiver_name=='active_campaign')){
            //     $schedule->call(function () {
            //         app(\App\Http\Controllers\Zapcontroller::class)->goheight_to_activecamp();
            //     })->everyMinute();
            // }else if(($zap->sender_name=='active_campaign')&&($zap->receiver_name=='gohighlevel')){
            //     $schedule->call(function () {
            //         app(\App\Http\Controllers\Zapcontroller::class)->activecamp_to_goheight();
            //     })->everyMinute();
            // }else if(($zap->sender_name=='active_campaign')&&($zap->receiver_name=='webinar_account')){
            //     $schedule->call(function () {
            //         app(\App\Http\Controllers\Zapcontroller::class)->activecamp_to_webinar();
            //     })->everyMinute();
            // }else if(($zap->sender_name=='webinar_account')&&($zap->receiver_name=='gohighlevel')){
            //     $schedule->call(function () {
            //         app(\App\Http\Controllers\Zapcontroller::class)->webinar_to_goheight();
            //     })->everyMinute();
            // }  
            if($zap->sender_name=='active_campaign'){
                // \Log::info("active_campaign./....");
                $schedule->command('activeCampaign:call')->everyMinute()
                // $schedule->job(new getActiveCampaignContact($zap))->everyMinute()
                ->onSuccess(function () use($zap) { 
                    if($zap->data_transfer_status == 1){
                        $zap->update(['data_transfer_status' => 0]);
                    }  
                    // \Log::info("active_campaign Success");
                })
                ->onFailure(function () {
                    // \Log::info("active_campaign Failure");
                });
            }
            if($zap->sender_name=='gohighlevel'){
                // \Log::info("gohighlevel./....");  
                //  $schedule->job(new getGohighlevelContact($zap))->everyMinute()
                $schedule->command('gohighlevel:call')->everyMinute() 
                 ->onSuccess(function () use($zap) { 
                    if($zap->data_transfer_status == 1){ 
                        $zap->update(['data_transfer_status' => 0]);
                    }  
                    // \Log::info("gohighlevel Success");
                })
                ->onFailure(function () {
                    // \Log::info("gohighlevel Failure");
                });
            }
            if($zap->sender_name=='gohighlevel_single'){
                // \Log::info("gohighlevel_single./...."); 
                
                $schedule->command('gohighlevelsingle:call')->everyMinute()
                // $schedule->job(new getGohighlevelContactsSingle($zap))->everyMinute()
                ->onSuccess(function () use($zap) { 
                    if($zap->data_transfer_status == 1){
                        $zap->update(['data_transfer_status' => 0]);
                    }  
                    // \Log::info("gohighlevel_single Success");
                })
                ->onFailure(function () {
                    // \Log::info("gohighlevel_single Failure");
                });
            }
            if($zap->sender_name=='webinar_account'){
                // \Log::info("webinar_account./....");
                $schedule->command('gotowebinar:call')->everyMinute()
                // $schedule->job(new getWebinarContact($zap))->everyMinute()
                ->onSuccess(function () use($zap) { 
                    if($zap->data_transfer_status == 1){
                        $zap->update(['data_transfer_status' => 0]);
                    }  
                    // \Log::info("webinar_account Success");
                })
                ->onFailure(function () {
                    // \Log::info("webinar_account Failure");
                });
            }
            if($zap->sender_name=='google_sheet') { 
                $schedule->command('dispatch:call')->everyMinute()
                ->onSuccess(function () use($zap) {
                    if($zap->data_transfer_status == 1){
                        $zap->update(['data_transfer_status' => 0]);
                    }  
                    // \Log::info("Success");
                })
                ->onFailure(function () {
                    // \Log::info("Failure");
                }); 
            }
        }
        
        //refresh token
        $schedule->command('refreshtokengohighlevel:call')->everyMinute();
        // $schedule->job(new refreshTokenGohighLevel($a))->everySixHours();
        $schedule->command('refreshtokenwebinar:call')->everyMinute();
        // $schedule->job(new refreshTokenGoToWebinar($w))->hourly();
        
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
