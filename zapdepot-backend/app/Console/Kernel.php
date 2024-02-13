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
        Commands\RefreshAweber::class,
        Commands\AweberJobs::class,
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
            if($zap->sender_name=='active_campaign'){
                $schedule->command('activeCampaign:call')->everyMinute()
                ->onSuccess(function () use($zap) { 
                    if($zap->data_transfer_status == 1){
                        $zap->update(['data_transfer_status' => 0]);
                    }  
                })
                ->onFailure(function () {
                });
            }
            if($zap->sender_name=='gohighlevel'){
                $schedule->command('gohighlevel:call')->everyMinute() 
                 ->onSuccess(function () use($zap) { 
                    if($zap->data_transfer_status == 1){ 
                        $zap->update(['data_transfer_status' => 0]);
                    }  
                })
                ->onFailure(function () {
                });
            }
            if($zap->sender_name=='gohighlevel_single'){
                $schedule->command('gohighlevelsingle:call')->everyMinute()
                ->onSuccess(function () use($zap) { 
                    if($zap->data_transfer_status == 1){
                        $zap->update(['data_transfer_status' => 0]);
                    }  
                })
                ->onFailure(function () {
                });
            }
            if($zap->sender_name=='webinar_account'){
                $schedule->command('gotowebinar:call')->everyMinute()
                ->onSuccess(function () use($zap) { 
                    if($zap->data_transfer_status == 1){
                        $zap->update(['data_transfer_status' => 0]);
                    }  
                })
                ->onFailure(function () {
                });
            }
            if($zap->sender_name=='google_sheet') { 
                $schedule->command('dispatch:call')->everyMinute()
                ->onSuccess(function () use($zap) {
                    if($zap->data_transfer_status == 1){
                        $zap->update(['data_transfer_status' => 0]);
                    }  
                })
                ->onFailure(function () {
                }); 
            }
            if($zap->sender_name=='aweber') { 
                $schedule->command('aweber:job')->everyMinute()
                ->onSuccess(function () use($zap) {
                    if($zap->data_transfer_status == 1){
                        $zap->update(['data_transfer_status' => 0]);
                    }  
                })
                ->onFailure(function () {
                }); 
            }
        }
        
        $schedule->command('refreshtokengohighlevel:call')->everyMinute();
        $schedule->command('refreshtokenwebinar:call')->everyMinute();
        $schedule->command('refreshAweber:call')->everyMinute();
        
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
