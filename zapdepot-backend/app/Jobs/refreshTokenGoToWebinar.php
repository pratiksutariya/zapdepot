<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\GoToWebinarAccounts;
use Illuminate\Support\Facades\Http;

class refreshTokenGoToWebinar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    Private $account;
    public function __construct($account)
    {
        $this->account=$account;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $account=$this->account;
            $webinar_client_id = env('GO_TO_WEBINAR_CLIENT_ID');
            $webinar_client_secret = env('GO_TO_WEBINAR_CLIENT_SECRET');
            $url="https://api.getgo.com/oauth/v2/token?grant_type=refresh_token&refresh_token=".$account->refresh_token;
            $method="POST";
            $data='';
            $headers=array('Authorization: Basic '.base64_encode("$webinar_client_id:$webinar_client_secret"),'Content-Type: application/x-www-form-urlencoded','Accept: application/json');
            $access_options = connectIntegration($url,$headers,$method,$data);
            if($access_options['status_code']==200)
            {
                $result=$access_options['result'];
                $go=GoToWebinarAccounts::find($account->id);
                $go->access_token = $result->access_token;
                $go->refresh_token = $result->refresh_token;
                $go->account_key = $result->account_key;
                $go->organizer_key = $result->organizer_key;
                $go->update();
            }
    }
}
