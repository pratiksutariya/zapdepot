<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActiveCampaignAccounts;
use App\Models\ActiveMeta;
use App\Models\ZapDetail;
use App\Models\Zap;
use App\Models\Contacts;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Log;

class ActiveCampaignJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activeCampaign:call';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ActiveCampaign description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            // \Log::info('get-active-data');
            $zap = Zap::where(['status' => 1, "sender_name" => 'active_campaign']) 
            ->whereNotNull('sender_id')
            ->whereNotNull('receiver_id')
            ->get(); 
            foreach ($zap as $zdata) { 
                saveZapLog($zdata->id , 'Run Zap For Get Active Campaign Accounts data',$zdata->user_id );
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

                                    if($zdata->receiver_name=='webinar_account'){
                                        // \Log::info("webinar_account activeCampaign,,,,,,,,,");
                                        WebinarAccount($senddata);
                                        // sendContactToWebinarAccount::dispatch($senddata);
                                    }else if($zdata->receiver_name=='gohighlevel'){
                                        // \Log::info("gohighlevel activeCampaign,,,,,,,,,");
                                        goHighLevel($senddata);
                                        // sendContactToGohighlevel::dispatch($senddata);
                                    }elseif($zdata->receiver_name=='active_campaign'){ 
                                        // \Log::info("active_campaign activeCampaign,,,,,,,,,");
                                        ActiveCampign($senddata);
                                        // sendContactToActiveCampaign::dispatch($senddata);
                                    } else if ($zdata->receiver_name == 'gohighlevel_single') {
                                        // \Log::info("gohighlevel_single activeCampaign,,,,,,,,,");
                                        GohighlevelSingle($senddata);
                                        // sendContactGohighlevelSingle::dispatch($senddata); 
                                    } else if ($zdata->receiver_name == 'google_sheet') {
                                        // \Log::info("google_sheet activeCampaign,,,,,,,,,");
                                        GoogleSheet($senddata);
                                    }

                                Contacts::insert($sdata);
                            }
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
        } catch (\Throwable $e) {
           \Log::info($e->getMessage());
        }
        
    }
}
