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
use App\Models\ZapDetail;
use Illuminate\Support\Facades\Log;

class sendContactGohighlevelSingle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    Private $details;

    public function __construct($details)
    {
        $this->details=$details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('send-goheigh-single-data');
        $user_data=$this->details;
        $url = "https://api.msgsndr.com/contacts/";
        $data=GohighlevelAccounts::find($user_data['receiver_id']);
        if($data){
            $array=['email'=>$user_data['email'],'locationId'=>$data->location_id,'name'=>$user_data['name'],'phone'=>$user_data['phone']];
			$response = Http::withHeaders([
				'Authorization' => 'Bearer '.$data->access_token,
				'Content-Type' => 'application/json',
				   'Version'=>'2021-04-15'
			])->post($url,$array);
            // Log::info(json_decode(json_encode($response->object()),true));
        }
    }
}
