<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ActiveCampaignAccounts;
use App\Models\ActiveMeta;
use App\Models\ZapDetail;
use App\Models\Contacts;
use Illuminate\Support\Facades\Http;
use App\Jobs\sendContactToActiveCampaign;
use App\Jobs\sendContactToGohighlevel;
use App\Jobs\sendContactGohighlevelSingle;
use App\Jobs\sendContactToWebinarAccount;
use Illuminate\Support\Facades\Log;

class getActiveCampaignContact implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $zap;
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
        Log::info('get-active-data');
        $zdata = $this->zap;
        saveZapLog($zdata->id , 'Run Zap For Get Active Campaign Accounts data' );
        $account = ActiveCampaignAccounts::find($zdata->sender_id);
        if ($account) {
            $url = $account->api_url . '/api/3/contacts?orders[cdate]=ASC&limit=20';
            $meta = ActiveMeta::where('active_id', $account->id)->where('zap_id', $zdata->id)->first();
            if ($meta) {
                $url .= "&id_greater=" . $meta->id_greater;
            }
            $zap_detail = ZapDetail::where('zap_id', $zdata->id)->where('event_type', 'sender')->where('interation_type', 'active_campaign')->first();
            if ($zap_detail) {
                if ($zap_detail->action_type == 'tag') {
                    $url .= "&tagid=" . $zap_detail->tag_id;
                } else if ($zap_detail->action_type == 'list') {
                    $url .= "&listid=" . $zap_detail->list_id;
                }
            }
            $response = Http::withHeaders([
                'api-token' => $account->api_key,
            ])->get($url);
            if ($response->status()) {
                // dump($response->object());
                $dataw = $response->object();
                $ac = $dataw->contacts;
                if (count($dataw->contacts)) {
                    foreach ($dataw->contacts as $key => $datac) {
                        $sdata[$key]['name'] = $datac->firstName . " " . $datac->lastName;
                        $sdata[$key]['firstname'] = $datac->firstName;
                        $sdata[$key]['lastname'] = $datac->lastName;
                        $sdata[$key]['email'] = $datac->email;
                        $sdata[$key]['phone'] = $datac->phone;
                        $sdata[$key]['account_id'] = $account->id;
                        $sdata[$key]['resource'] = 'active_campaign';
                        $sdata[$key]['user_id'] = $zdata->user_id;
                        $sdata[$key]['zap_id'] = $zdata->id;
                            $senddata=[];
                            $senddata['firstName']=$datac->firstName;
                            $senddata['lastName']=$datac->lastName;
                            $senddata['name']=$datac->firstName." ".$datac->lastName;
                            $senddata['email']=$datac->email;
                            $senddata['phone']=$datac->phone;
                            $senddata['receiver_id']=$zdata->receiver_id;
                            $senddata['zap_id']=$zdata->id;
                        if($zdata->receiver_name=='gohighlevel'){
                            sendContactToGohighlevel::dispatch($senddata);
                        }else if($zdata->receiver_name=='active_campaign'){
                            sendContactToActiveCampaign::dispatch($senddata);
                        }elseif($zdata->receiver_name=='gohighlevel_single'){
                            sendContactGohighlevelSingle::dispatch($senddata);
                        }else if($zdata->receiver_name=='webinar_account'){
                            sendContactToWebinarAccount::dispatch($senddata);
                        }
                        // elseif($zdata->receiver_name=='gohighlevel_single'){
                        //     sendContactGohighlevelSingle::dispatch($senddata);
                        // }elseif($zdata->receiver_name=='active_campaign'){
                        //     $senddata['firstName']=$datac->firstName;
                        //     $senddata['lastName']=$datac->lastName;
                        //     sendContactToActiveCampaign::dispatch($senddata);
                        // }
                    }
                    Contacts::insert($sdata);
                    $last = last($dataw->contacts);
                    if ($last) {
                        $gdata['zap_id'] = $zdata->id;
                        $gdata['active_id'] = $account->id;
                        $gdata['id_greater'] = $last->id;
                        if ($meta) {
                            $meta->update($gdata);
                        } else {
                            ActiveMeta::Create($gdata);
                        }
                    }
                }
            }
        }
    }
}
