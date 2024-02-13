<?php

namespace App\Console\Commands;
 
use App\Models\sheetEntryCount; 
use App\Models\GoogleAccount;
use App\Models\ZapDetail;
use Illuminate\Support\Facades\Http;
use App\Models\Zap;
use Illuminate\Console\Command; 

class DispatchJob extends Command
{

    protected $signature = 'dispatch:call';

    protected $description = 'Dispatch call';
 
    public function handle()
    {
        // \Log::info("come in...");

        try {
            $zap = Zap::where(['status' => 1, "sender_name" => 'google_sheet']) 
                ->whereNotNull('sender_id')
                ->whereNotNull('receiver_id')
                ->get(); 
            foreach ($zap as $zapValue) {
                $account = GoogleAccount::find($zapValue->sender_id);
                // \Log::info($zapValue);
                if ($account) {
                    $zapData = ZapDetail::where('zap_id', $zapValue->id)->where('event_type', 'sender')->where('interation_type', 'google_sheet')->first(); 
                    $google_sheet_id = $zapData->tag_id;
                    $url = "https://sheets.googleapis.com/v4/spreadsheets/" . $google_sheet_id . "/values/Sheet1!A:B";
                    // \Log::info($url);
                    // $method = "GET"; 
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $account->access_token,
                        'Content-Type: application/json',
                    ])->get($url); 
                    if ($response->status() == 200) { 
                        $sheets = @$response->object()->values;  
                        // \Log::info($sheets);
                        
                        if (($sheets != null) && ($sheets != [])) {
                            // \Log::info("if....");
                            $sheetEntry = sheetEntryCount::where(['zap_id' => $zapValue->id,"sender_tag_list_id" => $zapValue->sender_tag_list_id,"receiver_tag_list_id" => $zapValue->receiver_tag_list_id])->first(); 
                            $sCount = ($sheetEntry != "") ? (int)$sheetEntry->count : 0;  
                            // if($zapValue->receiver_name=='google_sheet') {

                            $sheets = array_slice($sheets, ($sCount + 1)); 
                            $updateCount = $sCount + count($sheets);
                            if(1 <= count($sheets)){ 
                                $zapValue->update(['data_transfer_status' => 1]);
                            }  
                            if($sheetEntry){
                                $sheetEntry->update(['count' => $updateCount]);
                            }else{
                                sheetEntryCount::create(['zap_id' => $zapValue->id,'count' => $updateCount,"sender_tag_list_id" => $zapValue->sender_tag_list_id,"receiver_tag_list_id" => $zapValue->receiver_tag_list_id]);
                            }

                            // }  
                            foreach ($sheets as $values) { 
                                // \Log::info("values....."); 
                                if ($values[0] != 'email' || $values[1] != 'name') {
                                    $valueArray['email'] = $values[0];
                                    $valueArray['firstName'] = $values[1];
                                    $valueArray['zap_id'] = $zapValue->id;
                                    $valueArray['receiver_id'] = $zapValue->receiver_id;
                                    $valueArray['user_id'] = $zapValue->user_id;
                                    $valueArray['name'] = $values[1];
                                    $valueArray['lastName'] = isset($values[2]) ? $values[2] : '';
                                    $valueArray['phone'] = isset($values[3]) ? $values[3] : '';

                                    if ($zapValue->receiver_name == 'webinar_account') {
                                        // \Log::info("success-ok-webinar_account-------------------");
                                        WebinarAccount($valueArray);
                                        // sendContactToWebinarAccount::dispatch($valueArray);
                                    } else if ($zapValue->receiver_name == 'gohighlevel') { 
                                        goHighLevel($valueArray);
                                        // \Log::info("success-ok-gohighlevel-------------------");
                                        // sendContactToGohighlevel::dispatch($valueArray);
                                    } else if ($zapValue->receiver_name == 'active_campaign') {
                                    ActiveCampign($valueArray);
                                        // \Log::info("success-ok-active_campaign-------------------");
                                        // sendContactToActiveCampaign::dispatch($valueArray);
                                    } else if ($zapValue->receiver_name == 'gohighlevel_single') {
                                        // \Log::info("success-ok-gohighlevel_single-------------------");
                                        GohighlevelSingle($valueArray);
                                        // sendContactGohighlevelSingle::dispatch($valueArray);
                                    } else if ($zapValue->receiver_name == 'google_sheet') {
                                        GoogleSheet($valueArray);
                                        // \Log::info("success-ok-googels-------------------");
                                    } else if ($zdata->receiver_name == 'aweber') {
                                        $senddata['receiver_tag_list_id']=$zdata->receiver_tag_list_id ? $zdata->receiver_tag_list_id : null;
                                        AweberSendData($valueArray);
                                    }
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
