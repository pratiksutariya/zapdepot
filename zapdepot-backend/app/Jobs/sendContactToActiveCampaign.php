<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ActiveCampaignAccounts;
use Illuminate\Support\Facades\Http;
use App\Models\ZapDetail;
use App\Models\Zap;
use Illuminate\Support\Facades\Log;

class sendContactToActiveCampaign implements ShouldQueue
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
        Log::info('send-active');
        $user_data=$this->details;
        $data=ActiveCampaignAccounts::find($user_data['receiver_id']);
        if($data){
            $array=[
                    'contact'=>[
                        "email"=>$user_data['email'],
                        'firstName'=>$user_data['firstName'],
                        'lastName'=>$user_data['lastName'],
                        'phone'=>$user_data['phone']
                    ]
                ];
            $url=$data->api_url."/api/3/contact/sync";
			$response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'api-token' => $data->api_key,
			])->post($url,$array);
            if($response->status()==201 || $response->status()==200){
                $rdata=$response->object();
                $contact=$rdata->contact;
                $tags=ZapDetail::where('zap_id',$user_data['zap_id'])->where('event_type','receiver')->where('interation_type','active_campaign')->first();
                if($tags){
                    if($tags->action_type=='tag'){
                        $tag_url=$data->api_url."/api/3/contactTags";
                        $tagarray=[
                            'contactTag'=>[
                                "contact"=>$contact->id,
                                'tag'=>$tags->tag_id,
                                ]
                            ];
                            $response = Http::withHeaders([
                                'Accept' => 'application/json',
                                'Content-Type' => 'application/json',
                                'api-token' => $data->api_key,
                                ])->post($tag_url,$tagarray);
                            }else if($tags->action_type=='list'){
                                $list_url=$data->api_url."/api/3/contactLists";
                                $listarray=[
                                    'contactList'=>[
                                        "contact"=>$contact->id,
                                        'list'=>$tags->list_id,
                                        "status"=>1
                                        ]
                                    ];
                                    $response = Http::withHeaders([
                                        'Accept' => 'application/json',
                                        'Content-Type' => 'application/json',
                                        'api-token' => $data->api_key,
                                        ])->post($list_url,$listarray);
                                        
                                // Zap::where("id",$user_data['zap_id'])
                                // ->update(['data_transfer_status' => 1]);
                            }

                }
            }
        }
    }
}
