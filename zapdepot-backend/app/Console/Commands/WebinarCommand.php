<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\sendContactToActiveCampaign;
use App\Models\GoogleAccount;


class WebinarCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webinar:call';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Webinar call';

    /**
     * Execute the console command.
     *3
     * @return int
     */
    public function handle()
    {
        try {
            $googleAccounts = GoogleAccount::all();
            // \Log::info('----------------------------');
            if($googleAccounts) {
                foreach($googleAccounts as $account) {
                    $account_id = $account->id;
                    $url = "https://oauth2.googleapis.com/token";
                    $method = "POST";
                    $headers = array();
                    $data = array(
                                    'response_type' => 'token', 
                                    'client_id' => '225769364371-ksq7d27qces7lqs5h9gta2ss9q2fmb84.apps.googleusercontent.com',
                                    'client_secret' => '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',
                                    'refreshToken' => $account->refresh_token,
                                    'redirect_uri' => env('GOOGLE_REDIRECT_URL'),
                                    'grant_type' => 'refresh_token'
                                );

                    $access_options = connectIntegration($url,$headers,$method,$data);
                    if($access_options && $access_options['status'] == true && $access_options['status_code'] == 200) {
                        $acoounts_data = GoogleAccount::find($account_id);
                        $acoounts_data->access_token = $access_options['result']->access_token; 
                        $acoounts_data->refresh_token = $account->refresh_token;
                        $acoounts_data->save();
                    }

                }
            }
        } catch (\Throwable $e) {
            // \Log::info("something went wrong.....");
        }
    }
}
