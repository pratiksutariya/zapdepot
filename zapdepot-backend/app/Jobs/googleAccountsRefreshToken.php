<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\GoogleAccount;


class googleAccountsRefreshToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
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
        \Log::info("Hello");
        $account=$this->account;
        $client_id=env('GOOGLE_CLIENT_ID');
        $client_secret=env('GOOGLE_SECRATE_KEY');
        $redirect_uri=env('GOOGLE_REDIRECT_URL');
        $url = "https://oauth2.googleapis.com/token";
        $method = "POST";
        $headers = array();
        $data = array(
               'response_type' => 'token', 
               'client_id' => $client_secret, 
               'client_secret' => $client_secret, 
               'redirect_uri' => $redirect_uri, 
               'refreshToken' => $account->refresh_token, 
               'grant_type' => 'refresh_token'
            );
            $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => $method,
                    CURLOPT_POSTFIELDS => $data,
                    CURLOPT_HTTPHEADER => $headers,
                    CURLOPT_FAILONERROR => true
                ));

                // print_r($ch);
                $response = curl_exec($ch);
                // dd($response);
                $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if (curl_errno($ch)) {
                    $error_msg = curl_error($ch);
                }
                curl_close($ch);            
                if (isset($error_msg)) {
                    $data = [];
                    $data['status'] = 'false';
                    $data['status_code'] = $http_status;
                    $data['message'] = $error_msg;
                    return response()->json(['status' => false, 'message' => $error_msg], 400);
                } else {
                    $result = json_decode($response);
                    $data = [];
                    $data['status'] = 'true';
                    $data['status_code'] = $http_status;
                    $data['result'] = $result;
                    // dd($data['result']);
                    $userData = GoogleAccount::find($account->id);
                    $userData->access_token = $data['result']->access_token;
                    $userData->refresh_token = $user->refresh_token;
                    $userData->save();
                    // return redirect('https://softwebwork.com/'. $userData->username .'/google-settings');
                    \Log::info (11);
                    // \Log::info($userData."Cron is working fine!");

                }
    }
}
