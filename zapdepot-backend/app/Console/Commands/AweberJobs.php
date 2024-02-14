<?php

namespace App\Console\Commands;
use App\Models\AweberAccount;
use Illuminate\Support\Facades\Http;
use App\Models\Contacts; 
use App\Models\Zap; 
use Illuminate\Support\Facades\Log;

use Illuminate\Console\Command;

class AweberJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aweber:job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'aweber job';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $zaps = Zap::where(['status' => 1, "sender_name" => 'aweber'])
                    ->whereNotNull('sender_id')
                    ->whereNotNull('receiver_id')
                    ->get();
            foreach ($zaps as $zap) { 
                $account = AweberAccount::find($zap->sender_id);
                if($account) {
                    $url="https://api.aweber.com/1.0/accounts/$account->account_id/lists/$zap->sender_tag_list_id/subscribers";
                    $method="GET";
                    $data='';
                    $headers=array('Authorization: Bearer '.$account->access_token);
                    $access_options = connectIntegration($url,$headers,$method,$data);
                    if($access_options && $access_options['status'] == true && $access_options['status_code'] == 200) {
                        $res_data_entries = $access_options['result']->entries;
                        foreach($res_data_entries as $key => $entry) {
                            $sdata = [];
                            $sdata[$key]['name'] = $entry->name;
                            $sdata[$key]['firstname'] = "";
                            $sdata[$key]['lastname'] = "";
                            $sdata[$key]['email'] = $entry->email;
                            $sdata[$key]['account_id'] = $account->id;
                            $sdata[$key]['resource'] = 'aweber';
                            $sdata[$key]['user_id'] = $zap->user_id;
                            $sdata[$key]['zap_id'] = $zap->id;
                            $senddata=[];
                            $senddata['firstName']=$entry->name;
                            $senddata['lastName']=$entry->name;
                            $senddata['user_id']=$zap->user_id;
                            $senddata['name']=$entry->name;
                            $senddata['email']=$entry->email;
                            $senddata['receiver_id']=$zap->receiver_id;
                            $senddata['zap_id']=$zap->id;
                            // $senddata['phone']= connectionKey()['set_mobile_no'];
                            if($zap->receiver_name=='webinar_account'){
                                WebinarAccount($senddata);
                            }else if($zap->receiver_name=='gohighlevel'){
                                goHighLevel($senddata);
                                // $senddata['phone']='';
                            }else if($zap->receiver_name=='active_campaign'){
                                ActiveCampign($senddata);
                                // $senddata['phone']='';
                            }elseif($zap->receiver_name=='gohighlevel_single'){
                                GohighlevelSingle($senddata);
                                // $senddata['phone']='';
                            }if($zap->receiver_name=='google_sheet'){
                                GoogleSheet($senddata);
                            }if($zap->receiver_name=='aweber'){
                                $senddata['receiver_tag_list_id']=$zap->receiver_tag_list_id ? $zap->receiver_tag_list_id : null;
                                AweberSendData($senddata);
                            } 
                            Contacts::insert($sdata); 
                        } 
                        
                    }
                }
            }
        } catch (\Throwable $e) {
           Log::info($e);
        }
    }
}
