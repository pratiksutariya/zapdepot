<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\GohighlevelAccounts;
use Illuminate\Support\Facades\Http;

class refreshTokenGohighLevel implements ShouldQueue
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
        Log::info("refreshTokenGohighLevel....");
        $account=$this->account;
        $client_id=env('GOHIGHLEVEL_CLIENT_ID');
        $client_secret=env('GOHIGHLEVEL_CLIENT_SECRET');
        $url = "https://api.msgsndr.com/oauth/token";
        $method = "POST";
        $headers = array(
            'Content-Type: application/x-www-form-urlencoded'
            );
        $data = 'client_id='.$client_id.'&client_secret='.$client_secret.'&grant_type=refresh_token&refresh_token='.$account->refresh_token;

        $getaccesstoken = connectIntegration($url, $headers, $method, $data);
        
        if ($getaccesstoken['status_code'] == 200) {
            $result=$getaccesstoken['result'];
            $go=GohighlevelAccounts::find($account->id);
            $go->access_token=$result->access_token;
            $go->refresh_token=$result->refresh_token;
            $go->location_id=$result->locationId;
            $go->label=$account->label;
            $go->user_id=$account->user_id;
            $go->update();
        }
    }
}
