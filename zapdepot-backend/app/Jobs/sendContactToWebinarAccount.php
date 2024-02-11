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
use App\Models\ZapDetail;
use Illuminate\Support\Facades\Log;

class sendContactToWebinarAccount implements ShouldQueue
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
        Log::info('send-web');
        $user_data=$this->details;
        $web=GoToWebinarAccounts::find($user_data['receiver_id']);
        $tags=ZapDetail::where('zap_id',$user_data['zap_id'])->where('event_type','receiver')->where('interation_type','webinar_account')->first();
        if($tags){
            $array=["email"=>$user_data['email'],'firstName'=>$user_data['firstName'],'lastName'=>$user_data['lastName']];
            $url="https://api.getgo.com/G2W/rest/v2/organizers/$web->organizer_key/webinars/$tags->tag_id/registrants?resendConfirmation=false";
			$response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $web->access_token,
                'Content-Type' => 'application/json',
			])->post($url,$array);
        Log::info($response);

        }
    }
}
