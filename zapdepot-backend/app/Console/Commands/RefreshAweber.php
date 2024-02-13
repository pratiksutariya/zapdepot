<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AweberAccount;

class RefreshAweber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refreshAweber:call';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'refreshAweber call';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $aweberAccount = AweberAccount::all();
            if($aweberAccount) {
                foreach($aweberAccount as $account) { 
                    $account_id = $account->id;
                    $key = connectionKey(); 
                    $url="https://auth.aweber.com/oauth2/token";
                    $method="POST";
                    $data=array('grant_type'=>'refresh_token','refresh_token'=>$account->refresh_token);
                    $headers=array('Authorization: Basic '.base64_encode($key['AWEBER_CLIENT_ID'].':'.$key['AWEBER_CLIENT_SECRET_KEY']));
                    $access_options = connectIntegration($url,$headers,$method,$data);
                    if($access_options && $access_options['status'] == true && $access_options['status_code'] == 200) {
                        $acoounts_data = AweberAccount::find($account_id);
                        $acoounts_data->access_token = $access_options['result']->access_token; 
                        $acoounts_data->refresh_token = $access_options['result']->refresh_token;
                        $acoounts_data->save();
                        \Log::info("...AweberAccount...");
                    }
                }
            }
        } catch (\Throwable $e) {
            \Log::info("something went wrong...AweberAccount..");
        }
    }
}
