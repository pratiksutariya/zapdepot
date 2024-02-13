<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GohighlevelAccounts;
use App\Models\GoMeta;
use App\Models\ZapDetail;
use App\Models\Zap;
use Illuminate\Support\Facades\Http;
use App\Models\Contacts;
use App\Jobs\sendContactToActiveCampaign;
use App\Jobs\sendContactToGohighlevel;
use App\Jobs\sendContactGohighlevelSingle;
use Illuminate\Support\Facades\Log;

class GoHighLevelJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gohighlevel:call';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GoHighLevel call';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // \Log::info('get-goheigh-data...');
        try {
            $zap = Zap::where(['status' => 1, "sender_name" => 'gohighlevel'])
            ->whereNotNull('sender_id')
            ->whereNotNull('receiver_id')
            ->get();
            foreach ($zap as $zdata) {
                // saveZapLog($zdata->id, 'Run Zap For Get Go High Level Accounts data', $zdata->user_id);
                $account = GohighlevelAccounts::find($zdata->sender_id);
                // \Log::info($account);
                if ($account) {
                    $meta = GoMeta::where('go_account_id', $account->id)->where('zap_id', $zdata->id)->first();
                    // \Log::info($meta);
                    if ($meta) {
                        $url = "https://rest.gohighlevel.com/v1/contacts/?startAfterId=" . $meta->startAfterId . "&startAfter=" . $meta->startAfter . "&limit=20";
                    } else {
                        $url = "https://rest.gohighlevel.com/v1/contacts?limit=20";
                    }
                    $zap_detail = ZapDetail::where('zap_id', $zdata->id)->where('event_type', 'sender')->where('interation_type', 'gohighlevel')->first();
                    if ($zap_detail) {
                        $url .= "&query=" . $zap_detail->tag_id;
                    }
                    // \Log::info($url);
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $account->api_key,
                    ])->get($url);
                    // \Log::info($response);
                    // dump("getGoHighLevelData");
                    // dump($response->object());
                    if ($response->status() == 200) {
                        // \Log::info("if/........");
                        $rdata = $response->object();
                        // \Log::info(print_r($rdata));
                        // dd($rdata);
                        if (count($rdata->contacts) != 0) {
                            $sdata = [];
                            $zdata->update(['data_transfer_status' => 1]);
                            foreach ($rdata->contacts as $key => $datac) {
                                if ($datac->email || $datac->phone || $datac->contactName) {
                                    $sdata[$key]['name'] = $datac->contactName;
                                    $sdata[$key]['firstname'] = $datac->firstName;
                                    $sdata[$key]['lastname'] = $datac->lastName;
                                    $sdata[$key]['email'] = $datac->email;
                                    $sdata[$key]['phone'] = $datac->phone;
                                    $sdata[$key]['account_id'] = $account->id;
                                    $sdata[$key]['user_id'] = $zdata->user_id;
                                    $sdata[$key]['zap_id'] = $zdata->id;
                                    $sdata[$key]['resource'] = 'gohighlevel';
                                    $sdata[$key]['created_at'] = date("Y-m-d H:i:s");
                                    $sdata[$key]['updated_at'] = date("Y-m-d H:i:s");

                                    $senddata = [];
                                    $senddata['firstName'] = $datac->firstName;
                                    $senddata['lastName'] = $datac->lastName;
                                    $senddata['name'] = $datac->firstName . " " . $datac->lastName;
                                    $senddata['email'] = $datac->email;
                                    $senddata['phone'] = $datac->phone;
                                    $senddata['receiver_id']= @$zdata->receiver_id;
                                    $senddata['zap_id'] = $zdata->id;
                                }
                                if ($zdata->receiver_name == 'webinar_account') {
                                    WebinarAccount($senddata);
                                    // \Log::info("webinar_account ...message");
                                    // sendContactToWebinarAccount::dispatch($senddata);
                                } else if ($zdata->receiver_name == 'gohighlevel') {
                                    goHighLevel($senddata);
                                    // \Log::info("gohighlevel ...message");
                                    // sendContactToGohighlevel::dispatch($senddata);
                                }else if ($zdata->receiver_name == 'active_campaign') {
                                    ActiveCampign($senddata);
                                    // \Log::info("active_campaign ...message");
                                    // sendContactToActiveCampaign::dispatch($senddata);
                                }else if ($zdata->receiver_name == 'gohighlevel_single') {
                                    GohighlevelSingle($senddata);
                                    // \Log::info("gohighlevel_single ...message");
                                    // sendContactGohighlevelSingle::dispatch($senddata);
                                }else if ($zdata->receiver_name == 'google_sheet') {
                                    GoogleSheet($senddata); 
                                }else if ($zdata->receiver_name == 'aweber') {
                                    $senddata['receiver_tag_list_id']=$zdata->receiver_tag_list_id ? $zdata->receiver_tag_list_id : null;
                                    AweberSendData($senddata);
                                }
                                // sendContact::dispatch($datac,$this->zaps->from_id);
                                Contacts::insert($sdata);
                               
                            }
                            $metadata = $rdata->meta;
                            if ($metadata->startAfterId && $metadata->startAfter) {
                                $gdata['startAfterId'] = $metadata->startAfterId;
                                $gdata['startAfter'] = $metadata->startAfter;
                                $gdata['go_account_id'] = $account->id;
                                $gdata['zap_id'] = $zdata->id;
                                if ($meta) {
                                    $meta->update($gdata);
                                } else {
                                    GoMeta::Create($gdata);
                                }
                            }
                        }
                    }
                    // \Log::info("out if/........");
                }
            }

        } catch (\Throwable $e) {
           \Log::info($e->getMessage());
        }
    }
}
