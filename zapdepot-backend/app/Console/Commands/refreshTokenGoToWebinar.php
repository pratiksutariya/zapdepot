<?php

namespace App\Console\Commands;

use Illuminate\Console\Command; 
use App\Models\GoToWebinarAccounts;

class refreshTokenGoToWebinar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refreshtokenwebinar:call';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'refreshTokenGoToWebinar call';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $web=GoToWebinarAccounts::all();
            // \Log::info('-------------GoToWebinarAccounts---------------');
            if($web) {
                foreach($web as $account) {
                    $webinar_client_id = '2cbf7ce6-09ae-4106-a391-6df9a4dac8c3';
                    $webinar_client_secret = 'NQ69ykoRCybeAqWR5YTT1BEU';
                    $url="https://api.getgo.com/oauth/v2/token?grant_type=refresh_token&refresh_token=".$account->refresh_token;
                    $method="POST";
                    $data='';
                    $headers=array('Authorization: Basic '.base64_encode("$webinar_client_id:$webinar_client_secret"),'Content-Type: application/x-www-form-urlencoded','Accept: application/json');
                    $access_options = connectIntegration($url,$headers,$method,$data);
                    if($access_options['status_code']==200)
                    {
                        // \Log::info("message if...");
                        $result=$access_options['result'];  
                        $account->update([
                            'access_token' => $result->access_token,
                            'refresh_token' => $result->refresh_token,
                            'account_key' => $result->account_key,
                            'organizer_key' => $result->organizer_key
                        ]);  
                    }
                    // \Log::info("message out if...");
                }
            }
        } catch (\Throwable $e) {
        //    \Log::info("something went wrong.....");
        }
    }
}
