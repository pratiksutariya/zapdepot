<?php

namespace App\Console\Commands;

use Illuminate\Console\Command; 
use App\Models\GohighlevelAccounts;
use App\Models\GoMeta; 
use Illuminate\Support\Facades\Http;
use App\Models\Contacts; 
use App\Models\Zap; 
use Illuminate\Support\Facades\Log;

class GoHighLevelSingleJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gohighlevelsingle:call';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GoHighLevelSingle description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // \Log::info('get-goheigh-single-data'); 
        try {
            $zap = Zap::where(['status' => 1, "sender_name" => 'gohighlevel_single']) 
            ->whereNotNull('sender_id')
            ->whereNotNull('receiver_id')
            ->get(); 
            foreach ($zap as $zdata) {
                saveZapLog($zdata->id , 'Run Zap For Get Go High Level single Accounts data',$zdata->user_id );
                $account=GohighlevelAccounts::find($zdata->sender_id);
                if($account){
                    $meta=GoMeta::where('go_account_id',$account->id)->where('zap_id',$zdata->id)->first();
                    if($meta){
                        $url='https://api.msgsndr.com/contacts/?locationId='.$account->location_id.'&startAfterId='.$meta->startAfterId.'&startAfter='.$meta->startAfter.'&sortBy=date_added&order=asc&limit=20';
                    }else{
                        $url='https://api.msgsndr.com/contacts/?locationId='.$account->location_id.'&sortBy=date_added&order=asc&limit=20';
                    }
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer '.$account->access_token,
                        'Content-Type' => 'application/json',
                        'Version'=>'2021-04-15'
                    ])->get($url);
                    if($response->status()==201 || $response->status()==200){
                        // \Log::info("if...");
                        $rdata=$response->object();
                        // \Log::info(print_r($rdata));
                        if(count($rdata->contacts)){
                            // \Log::info("if 1...");
                            $sdata=[];
                            foreach($rdata->contacts as $key=>$datac){
                                if($datac->email || $datac->phone || $datac->contactName){
                                    $sdata[$key]['name']=$datac->contactName;
                                    $sdata[$key]['firstname']=$datac->firstName;
                                    $sdata[$key]['lastname']=$datac->lastName;
                                    $sdata[$key]['email']=$datac->email;
                                    $sdata[$key]['phone']=$datac->phone;
                                    $sdata[$key]['account_id']=$account->id;
                                    $sdata[$key]['user_id']=$zdata->user_id;
                                    $sdata[$key]['zap_id']=$zdata->id;
                                    $sdata[$key]['resource']='gohighlevel_single';
                                    $senddata=[];
                                    $senddata['name']=$datac->firstName." ".$datac->lastName;
                                    $senddata['firstName']=$datac->firstName;
                                    $senddata['lastName']=$datac->lastName;
                                    $senddata['email']=$datac->email;
                                    $senddata['phone']=$datac->phone;
                                    $senddata['receiver_id']=$zdata->receiver_id;
                                    $senddata['zap_id']=$zdata->id;
                                    if($zdata->receiver_name=='webinar_account'){
                                        // \Log::info("webinar_account go high level single");
                                        WebinarAccount($senddata);
                                        // sendContactToWebinarAccount::dispatch($senddata);
                                    }else if($zdata->receiver_name=='gohighlevel'){
                                        // \Log::info("gohighlevel go high level single");
                                        goHighLevel($senddata);
                                        // sendContactToGohighlevel::dispatch($senddata);
                                    }elseif($zdata->receiver_name=='active_campaign'){ 
                                        // \Log::info("active_campaign go high level single");
                                        ActiveCampign($senddata);
                                        // sendContactToActiveCampaign::dispatch($senddata);
                                    } else if ($zdata->receiver_name == 'gohighlevel_single') {
                                        // \Log::info("gohighlevel_single go high level single");
                                        GohighlevelSingle($senddata);
                                        // sendContactGohighlevelSingle::dispatch($senddata); 
                                    } else if ($zdata->receiver_name == 'google_sheet') {
                                        // \Log::info("google_sheet go high level single");
                                        GoogleSheet($senddata);
                                    }
                                    Contacts::insert($sdata);
                                } 
                            }
                            $metadata=$rdata->meta;
                            if($metadata->startAfterId && $metadata->startAfter){
                                $gdata['startAfterId']=$metadata->startAfterId;
                                $gdata['startAfter']=$metadata->startAfter;
                                $gdata['go_account_id']=$account->id;
                                $gdata['zap_id']=$zdata->id;
                                if($meta){
                                    $meta->update($gdata);
                                }else{
                                    GoMeta::Create($gdata);
                                }
                            }

                        }
                        // \Log::info("out if 1...");
                    }
                    // \Log::info("out if...");
                }
            }
        } catch (\Throwable $e) {
        //    Log::info("something went wrong.....");
        }
    }
}
