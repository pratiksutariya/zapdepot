<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class bkupgetGoheighLevelContact implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $zdata=$this->zaps;
        $account=GohighlevelAccounts::find($zdata->sender_id);
        if($account){
            $meta=GoMeta::where('go_account_id',$account->id)->where('zap_id',$zdata->id)->first();
            if($meta){
                $url="https://rest.gohighlevel.com/v1/contacts/?startAfterId=".$meta->startAfterId."&startAfter=".$meta->startAfter."&limit=20";
            }else{
                $url="https://rest.gohighlevel.com/v1/contacts?limit=20";
            }
            $zap_detail=ZapDetail::where('zap_id',$zdata->id)->where('event_type','sender')->where('interation_type','gohighlevel')->first();
            if($zap_detail){
                $url.="&query=".$zap_detail->tag_id;
            }
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$account->api_key,
            ])->get($url);
            if($response->status()==200){
                $rdata=$response->object();
                if(count($rdata->contacts)){
                    $sdata=[];
                    foreach($rdata->contacts as $key=>$datac){
                        if($datac->email || $datac->phone || $datac->contactName){
                            $sdata[$key]['name']=$datac->contactName;
                            $sdata[$key]['email']=$datac->email;
                            $sdata[$key]['phone']=$datac->phone;
                            $sdata[$key]['account_id']=$account->id;
                            $sdata[$key]['user_id']=$zdata->user_id;
                            $sdata[$key]['zap_id']=$zdata->id;
                            $sdata[$key]['resource']='gohighlevel';
                            $senddata=[];
                            $senddata['name']=$datac->contactName;
                            $senddata['email']=$datac->email;
                            $senddata['phone']=$datac->phone;
                            $senddata['receiver_id']=$zdata->receiver_id;
                            $senddata['zap_id']=$zdata->id;
                        if($zdata->receiver_name=='gohighlevel'){
                            sendContactToGohighlevel::dispatch($senddata);
                        }elseif($zdata->receiver_name=='gohighlevel_single'){
                            sendContactGohighlevelSingle::dispatch($senddata);
                        }elseif($zdata->receiver_name=='active_campaign'){
                            $senddata['firstName']=$datac->firstName;
                            $senddata['lastName']=$datac->lastName;
                            sendContactToActiveCampaign::dispatch($senddata);
                        }
                        }
                        // sendContact::dispatch($datac,$this->zaps->from_id);
                    }
                    Contacts::insert($sdata);
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
            }
        }
    }
}
