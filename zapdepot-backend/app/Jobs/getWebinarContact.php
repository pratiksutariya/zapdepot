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
use App\Models\Contacts;
use App\Jobs\sendContactToActiveCampaign;
use App\Jobs\sendContactToGohighlevel;
use App\Jobs\sendContactGohighlevelSingle;
use Illuminate\Support\Facades\Log;

class getWebinarContact implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    Private $zaps;
    public function __construct($zaps)
    {
        $this->zaps=$zaps;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('get-web-data');
        $zdata=$this->zaps;
        saveZapLog($zdata->id , 'Run Zap For Get Webinar Accounts data' );
        $tags = ZapDetail::where('zap_id',$zdata->id)->where('event_type','sender')->where('interation_type','webinar_account')->first();
        if($tags){
            $account=GoToWebinarAccounts::find($zdata->sender_id);
            if($account){
                $url="https://api.getgo.com/G2W/rest/v2/organizers/$account->organizer_key/webinars/$tags->tag_id/registrants";
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $account->access_token,
                    'Content-Type' => 'application/json',
                ])->get($url);
                if ($response->status()) {
                    $dataw = $response->object();
                    if ($dataw && !empty($dataw) && count($dataw)) {
                        foreach ($dataw as $key => $datac) {
                            $sdata[$key]['name'] = $datac->firstName . " " . $datac->lastName;
                            $sdata[$key]['firstname'] = $datac->firstName;
                            $sdata[$key]['lastname'] = $datac->lastName;
                            $sdata[$key]['email'] = $datac->email;
                            $sdata[$key]['account_id'] = $account->id;
                            $sdata[$key]['resource'] = 'webinar_account';
                            $sdata[$key]['user_id'] = $zdata->user_id;
                            $sdata[$key]['zap_id'] = $zdata->id;
                                $senddata=[];
                                $senddata['firstName']=$datac->firstName;
                                $senddata['lastName']=$datac->lastName;
                                $senddata['email']=$datac->email;
                                $senddata['receiver_id']=$zdata->receiver_id;
                                $senddata['zap_id']=$zdata->id;
                            if($zdata->receiver_name=='gohighlevel'){
                                $senddata['phone']='';
                                sendContactToGohighlevel::dispatch($senddata);
                            }else if($zdata->receiver_name=='active_campaign'){
                                $senddata['phone']='';
                                sendContactToActiveCampaign::dispatch($senddata);
                            }elseif($zdata->receiver_name=='gohighlevel_single'){
                                $senddata['phone']='';
                                sendContactGohighlevelSingle::dispatch($senddata);
                            }else if($zdata->receiver_name=='webinar_account'){
                                sendContactToWebinarAccount::dispatch($senddata);
                            }
                        }
                        // Contacts::insert($sdata);
                    }
                }
            }
        }
    }
}
