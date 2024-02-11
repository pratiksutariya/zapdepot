<?php

namespace App\Jobs;
 
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

// use Illuminate\Contracts\Queue\ShouldBeUnique;

use App\Jobs\sendContactToActiveCampaign;
use App\Jobs\sendContactToGohighlevel;
use App\Jobs\sendContactGohighlevelSingle;
use App\Jobs\sendContactToWebinarAccount;
use App\Jobs\sendGoogleSheetsData;

use App\Models\GoogleAccount;



class getGoogleSheetsData implements ShouldQueue 
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $zap;
    public function __construct($zap)
    {
        $this->zap = $zap;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info("come in....");
        $zapValue = $this->zap;
        \Log::info($this->zap);
        $account = GoogleAccount::find($zapValue->sender_id);
        if ($account) {
            $zapData = ZapDetail::where('zap_id', $zapValue->id)->where('event_type', 'sender')->where('interation_type', 'google_sheet')->first();
            $google_sheet_id = $zapData->tag_id;
            $url = "https://sheets.googleapis.com/v4/spreadsheets/" . $google_sheet_id . "/values/Sheet1!A:B";
            $method = "GET";
            $headers = array(
                'Authorization: Bearer ' . $account->access_token,
                'Content-Type: application/json'
            );
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $account->access_token,
                'Content-Type: application/json',
            ])->get($url);
            if ($response->status() == 200) {
                $sheets = $response->object()->values;
                $responseArray = [];
                foreach ($sheets as $values) {
                    if ($values[0] != 'email' || $values[1] != 'name') {
                        $valueArray['email'] = $values[0];
                        $valueArray['firstName'] = $values[1];
                        $valueArray['zap_id'] = $zapValue->id;
                        $valueArray['receiver_id'] = $zapValue->receiver_id;
                        $valueArray['user_id'] = $zapValue->user_id;
                        $valueArray['name'] = $values[1];
                        $valueArray['lastName'] = isset($values[2]) ? $values[2] : '';
                        $valueArray['phone'] = isset($values[3]) ? $values[3] : '';
                        if ($zapValue->receiver_name == 'webinar_account') {
                            sendContactToWebinarAccount::dispatch($valueArray);
                        } else if ($zapValue->receiver_name == 'gohighlevel') {
                            sendContactToGohighlevel::dispatch($valueArray);
                        } else if ($zapValue->receiver_name == 'active_campaign') {
                            sendContactToActiveCampaign::dispatch($valueArray);
                        } else if ($zapValue->receiver_name == 'gohighlevel_single') {
                            sendContactGohighlevelSingle::dispatch($valueArray);
                        } else if ($zapValue->receiver_name == 'google_sheet') {
                            sendGoogleSheetsData::dispatch($valueArray);
                            \Log::info("success-ok-googels-------------------");
                        }
                    }
                }
            }
        }
    }
    public function failed()
    {
       Log::info("message.......faild");
    }
}
