<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
 
use App\Models\GoToWebinarAccounts;
use Illuminate\Support\Facades\Http;
use App\Models\Zap;
use App\Models\ZapDetail;
use App\Models\Contacts;  

class WebinarAccountJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gotowebinar:call';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'WebinarAccount description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // \Log::info('get-web-data');
        try {
            $zap = Zap::where(['status' => 1, "sender_name" => 'webinar_account']) 
                ->whereNotNull('sender_id')
                ->whereNotNull('receiver_id')
                ->get();   
            foreach ($zap as $zdata) {
                saveZapLog($zdata->id, 'Run Zap For Get Webinar Accounts data'  ,$zdata->user_id);
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
                            if ($dataw && !empty($dataw)) { // && count($dataw)
                            // \Log::info("if...");
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
                                        $senddata['lastName']=$datac->firstName .''.$datac->lastName;
                                        $senddata['name']=$datac->lastName;
                                        $senddata['email']=$datac->email;
                                        $senddata['receiver_id']=$zdata->receiver_id;
                                        $senddata['zap_id']=$zdata->id;
                                        $senddata['phone']= connectionKey()['set_mobile_no'];
                                    
                                    if($zdata->receiver_name=='webinar_account'){
                                        // \Log::info("webinar_account for webinar...");
                                        WebinarAccount($senddata);
                                        // sendContactToWebinarAccount::dispatch($senddata);
                                    }else if($zdata->receiver_name=='gohighlevel'){
                                        // \Log::info("gohighlevel for webinar...");
                                        goHighLevel($senddata);
                                        $senddata['phone']='';
                                        // sendContactToGohighlevel::dispatch($senddata);
                                    }else if($zdata->receiver_name=='active_campaign'){
                                        // \Log::info("active_campaign for webinar...");
                                        ActiveCampign($senddata);
                                        $senddata['phone']='';
                                        // sendContactToActiveCampaign::dispatch($senddata);
                                    }elseif($zdata->receiver_name=='gohighlevel_single'){
                                        // \Log::info("gohighlevel_single for webinar...");
                                        GohighlevelSingle($senddata);
                                        $senddata['phone']='';
                                        // sendContactGohighlevelSingle::dispatch($senddata);
                                    }if($zdata->receiver_name=='google_sheet'){
                                        // \Log::info("google_sheet for webinar...");
                                        GoogleSheet($senddata);
                                        // sendContactToWebinarAccount::dispatch($senddata);
                                    }else if ($zdata->receiver_name == 'aweber') {
                                        $senddata['receiver_tag_list_id']=$zdata->receiver_tag_list_id ? $zdata->receiver_tag_list_id : null;
                                        AweberSendData($senddata);
                                    }
                                    Contacts::insert($sdata);
                                }
                            } 
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
        //    \Log::info("something went wrong.....");
        }
    }
}
