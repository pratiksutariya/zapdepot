<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\GohighlevelAccounts;
use App\Models\GoMeta;
use App\Models\ZapDetail;
use Illuminate\Support\Facades\Http;
use App\Models\Contacts;
use App\Jobs\sendContactToActiveCampaign;
use App\Jobs\sendContactToGohighlevel;
use App\Jobs\sendContactGohighlevelSingle;
use Illuminate\Support\Facades\Log;

class getGohighlevelContactsSingle implements ShouldQueue
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
        Log::info('get-goheigh-single-data');
        $zdata=$this->zaps;
        saveZapLog($zdata->id , 'Run Zap For Get Go High Level single Accounts data' );
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
                $rdata=$response->object();
                if(count($rdata->contacts)){
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
                            if($zdata->receiver_name=='gohighlevel'){
                                sendContactToGohighlevel::dispatch($senddata);
                            }elseif($zdata->receiver_name=='active_campaign'){
                                // $senddata['firstName']=$datac->firstName;
                                // $senddata['lastName']=$datac->lastName;
                                sendContactToActiveCampaign::dispatch($senddata);
                            }elseif($zdata->receiver_name=='gohighlevel_single'){
                                sendContactGohighlevelSingle::dispatch($senddata);
                            }
                            else if($zdata->receiver_name=='webinar_account'){
                                sendContactToWebinarAccount::dispatch($senddata);
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
