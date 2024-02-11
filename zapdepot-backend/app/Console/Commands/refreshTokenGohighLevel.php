<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GohighlevelAccounts;

class refreshTokenGohighLevel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refreshtokengohighlevel:call';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'refreshTokenGohighLevel call';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    { 
        try {
            $goHighLevel = GohighlevelAccounts::all();
            // \Log::info('-------------refreshTokenGohighLevel---------------');
            if($goHighLevel) {
                foreach($goHighLevel as $account) {
                    // \Log::info("refreshTokenGohighLevel...."); 
                    $key = connectionKey();
                    $client_id= $key['GOHIGHLEVEL_CLIENT_ID'];
                    //  \Log::info($client_id);
                    $client_secret= $key['GOHIGHLEVEL_CLIENT_SECRET'];
                    //  \Log::info($client_secret);
                    $url = "https://api.msgsndr.com/oauth/token";
                    $method = "POST";
                    $headers = array(
                        'Content-Type: application/x-www-form-urlencoded'
                        );
                    $data = 'client_id='.$client_id.'&client_secret='.$client_secret.'&grant_type=refresh_token&refresh_token='.$account->refresh_token; 
            
                    $getaccesstoken = connectIntegration($url, $headers, $method, $data);
                    // \Log::info("............result............");
                    // \Log::info($getaccesstoken);
                    
                    if ($getaccesstoken['status_code'] == 200) {
                        $result=$getaccesstoken['result']; 
                        $input['access_token'] = $result->access_token;
                        $input['refresh_token'] = $result->refresh_token;
                        $input['location_id'] = $result->locationId;
                        $input['label'] = $account->label;
                        $input['user_id'] = $account->user_id; 
                        $account->update($input);
                    }

                }
            }
        } catch (\Throwable $e) {
        //    \Log::info("something went wrong.....");
        }
    }
}
