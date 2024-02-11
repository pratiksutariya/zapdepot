<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\GohighlevelAccounts;
use App\Models\ActiveCampaignAccounts;
use App\Models\Zap;
use App\Models\Contacts;
use App\Models\ZapDetail;
use App\Models\GoMeta;
use App\Jobs\sendContactToWebinarAccount;
use App\Jobs\sendContactToGohighlevel;
use App\Jobs\sendContactToActiveCampaign;
use App\Jobs\sendGoogleSheetsData;
use App\Jobs\sendContactGohighlevelSingle;
use App\Models\GoogleAccount;
use App\Models\GoToWebinarAccounts;
use App\Models\sheetEntryCount;
use Validator;
use Illuminate\Support\Facades\Redis;
use DB;

class IntegrationController extends BaseController
{
    public function addGohighlevel(Request $request)
    {
        try {
            if ($request->id) {
                $validator = Validator::make($request->all(), [
                    // 'location_id' => 'required',
                    'api_key' => 'required',
                    'label' => 'required',
                    // 'label' => 'required|unique:gohighlevel_accounts,label,'.$request->id.',id,user_id,'.Auth::id(),
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    // 'location_id' => 'required',
                    'api_key' => 'required',
                    'label' => 'required',
                    // 'label' => 'required|unique:gohighlevel_accounts,label,NULL,id,user_id,'.Auth::id(),
                ]);
            }
            if ($validator->fails()) {
                return $this->sendError('Label already taken.', $validator->errors(),444);
            } 
            $key = connectionKey(); 
            $location_id = $key['location_id']; 
            $url = "https://rest.gohighlevel.com/v1/users/?locationId=".$location_id;
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $request->api_key,

            ])->get($url); 
            
            // return $response->object();

            // $client_id= $key['GOHIGHLEVEL_CLIENT_ID'];
			// $client_secret= $key['GOHIGHLEVEL_CLIENT_SECRET']; 
            // $headers = [
            //     "Content-Type: application/x-www-form-urlencoded"
            // ];
            // $url = "https://api.msgsndr.com/oauth/token";
            // $method = "POST";
            // $data = 'client_id='.$client_id.'&client_secret='.$client_secret.'&grant_type=authorization_code&code=71ee430d7b0cdae642cdd8d5b9b0385479e92127&refresh_token=';
            // $curl = curl_init();

            // curl_setopt_array($curl, [
            // CURLOPT_URL => $url,
            // CURLOPT_RETURNTRANSFER => true,
            // CURLOPT_ENCODING => "",
            // CURLOPT_MAXREDIRS => 10,
            // CURLOPT_TIMEOUT => 0,
            // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            // CURLOPT_CUSTOMREQUEST => $method,
            // CURLOPT_POSTFIELDS => $data,
            // CURLOPT_HTTPHEADER => $headers,
            // ]);

            // $response = curl_exec($curl);
            // $err = curl_error($curl);

            // curl_close($curl);

            // if ($err) {
            // return  "cURL Error #:" . $err;
            // } else {
            // return  $response;
            // }


            // return $getaccesstoken = connectIntegration($url, $headers, $method, $data);
            // dd($getaccesstoken);
            // if ($response->status() == 200) {
            //     if ($request->id) {
            //         $g = GohighlevelAccounts::find($request->id);
            //     } else {
            //         $g = new GohighlevelAccounts();
            //     }
 
            //     $g->label = $request->label;
            //     // $g->location_id = $key['location_id'];
            //     $g->api_key = $request->api_key;
            //     $g->user_id = Auth::id();
            //     $g->type="agency";
            //     $g->save();
            //     return $this->sendResponse($g, 'Account addded.', 200);
            // }else {
            //     return $this->sendError('Please check api key', [], 445);
            // }
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function deleteGohighlevelAccount($id)
    {
        try {
            $delete = GohighlevelAccounts::where('id', $id)->delete();
            return $this->sendResponse([], 'deleted', 200);
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function getAllAccountGo()
    {
        try {
            $data = GohighlevelAccounts::where('user_id', Auth::id())->where('type','agency')->latest()->get();
            return $this->sendResponse($data, 'success.', 200);
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function getAllAccountActive()
    {
        try {
            $data = ActiveCampaignAccounts::where('user_id', Auth::id())->latest()->get();
            return $this->sendResponse($data, 'success.', 200);
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }

    public function getGoogleAccounts()
    {
        try {
            $data = GoogleAccount::where('user_id', Auth::id())->latest()->get();
            return $this->sendResponse($data, 'success.', 200);
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }

    public function getGoogleAccountsSheets(Request $request)
    {
        try { 
            $account = GoogleAccount::find($request->account_id);
            $accessToken = $account->access_token;
            $url="https://www.googleapis.com/drive/v3/files?q=mimeType = 'application/vnd.google-apps.spreadsheet'";
            $method="GET";
            $headers=array('Authorization: Bearer '.$accessToken,
            'Content-Type: application/json');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type: application/json',
            ])->get($url);
            if($response->status() == 200) {
               $sheets = $response->object(); 
               return $this->sendResponse($sheets->files, 'success.', 200);

            }else {
                return $this->sendResponse([], '', 200);
            }
            return $this->sendResponse($response, 'success.', 200);
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }

    public function deleteGoogleAccounts($id)
    {
        try {
            $data = GoogleAccount::find($id);
            $data->delete();
            return $this->sendResponse($data, 'success.', 200);
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }

    public function refreshGet()
    {
        try {
            $googleAccounts = GoogleAccount::all();
            if($googleAccounts) {
                foreach($googleAccounts as $account) {
                    $account_id = $account->id;
                    $url = "https://oauth2.googleapis.com/token";
                    $method = "POST";
                    $headers = array(); 
                    $key = connectionKey();   
                    $data = array(
                                    'response_type' => 'token', 
                                    'client_id' => $key["GOOGLE_CLIENT_ID"],
                                    'client_secret' => $key["GOOGLE_SECRATE_KEY"],
                                    'refreshToken' => $account->refresh_token,
                                    'redirect_uri' => $key["GOOGLE_REDIRECT_URL"],
                                    'grant_type' => 'refresh_token'
                                );

                    $access_options = connectIntegration($url,$headers,$method,$data);
                    if($access_options && $access_options['status'] == true && $access_options['status_code'] == 200) {
                        $acoounts_data = GoogleAccount::find($account_id);
                        $acoounts_data->access_token = $access_options['result']->access_token; 
                        $acoounts_data->refresh_token = $account->refresh_token;
                        $acoounts_data->save();
                    }

                }
            }
       
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }

    public function sheets() {
        try {

        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }

    public function getGohighlevelTags(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'account_id' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }
            $g = GohighlevelAccounts::find($request->account_id);
            if (!$g) {
                return $this->sendError('Data not found.', '', 404);
            }

            $url = "https://rest.gohighlevel.com/v1/tags/";
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $g->api_key,
            ])->get($url);
            if ($response->status() == 200) {
                $tags = $response->object();
                return $this->sendResponse($tags->tags, '', 200);
            } else {
                return $this->sendResponse([], '', 200);

            }
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function addAcitveCampaign(Request $request)
    {
        try {
            if ($request->id) {
                $validator = Validator::make($request->all(), [
                    'api_key' => 'required',
                    'api_url' => 'required',
                    'label' => 'required|unique:active_campaign_accounts,label,'.$request->id.',id,user_id,'.Auth::id(),

                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'api_url' => 'required',
                    'api_key' => 'required',
                    'label' => 'required|unique:active_campaign_accounts,label,NULL,id,user_id,'.Auth::id(),
                ]);
            }
            if ($validator->fails()) {
                return $this->sendError('Label already taken.', $validator->errors(),444);
            }
            $url = $request->api_url.'/api/3/tags';
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'api-token'=>$request->api_key

            ])->get($url);
            if ($response->getStatusCode() == 200 && $response->object()) {
                if ($request->id) {
                    $g = ActiveCampaignAccounts::find($request->id);
                } else {
                    $g = new ActiveCampaignAccounts();
                }
                $g->label = $request->label;
                $g->api_key = $request->api_key;
                $g->api_url = $request->api_url;
                $g->user_id = Auth::id();
                $g->save();
                return $this->sendResponse($g, 'Account addded.', 200);
            }else{
                return $this->sendError('Please check api key and api url', [], 445);
            }
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function deleteActiveCamAccount($id)
    {
        try {
            $delete = ActiveCampaignAccounts::where('id', $id)->delete();
            return $this->sendResponse([], 'deleted', 200);
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function getActiveCampaignTags($id){
        try {
            $data = ActiveCampaignAccounts::where('id', $id)->first();
            if($data){
                $url = $data->api_url.'/api/3/tags';
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'api-token'=>$data->api_key
                ])->get($url);
                if($response->status()==200){
                    $data=$response->object();
                    $tags=$data->tags;
                    return $this->sendResponse($tags,'Success.',200);
                }else{
                    return $this->sendError('No data found.', $e->getMessage(), 404);
                }
            }else{
                    return $this->sendError('No data found.', $e->getMessage(), 404);
            }
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function getActiveCampaignList($id){
        try {
            $data = ActiveCampaignAccounts::where('id', $id)->first();
            if($data){
                $url = $data->api_url.'/api/3/lists?limit=all';
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'api-token'=>$data->api_key
                ])->get($url);
                if($response->status()==200){
                    $data=$response->object();
                    $list=$data->lists;
                    return $this->sendResponse($list,'Success.',200);
                }else{
                    return $this->sendError('No data found.', $e->getMessage(), 404);
                }
            }else{
                    return $this->sendError('No data found.', $e->getMessage(), 404);
            }
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function redis_test(Request $request){
		try{
			$redis=Redis::connect('127.0.0.1',3306);
			return response('redis working');
		}catch(\Predis\Connection\ConnectionException $e){
			return response('error connection redis');
		}
	}

    public function googleAccount(Request $request) {
        try {
            // print_r($request->all());
            // return;
            // dd("ok");
            $user_id = Auth::id();
            $check = GoogleAccount::where('label' , $request->state)->where('user_id' , Auth::id())->first();
            
            if($check) {
                return response()->json(['status' => false, 'message' => 'This Label is already Added'], 200);
            }
            if ($request->code) {
                $url = "https://oauth2.googleapis.com/token";
                $method = "POST";
                $headers = array();
                $key = connectionKey();   
                $data = array('response_type' => 'token',
                              'client_id' => $key["GOOGLE_CLIENT_ID"],
                              'client_secret' => $key["GOOGLE_SECRATE_KEY"],
                              'redirect_uri' => $key["GOOGLE_REDIRECT_URL"],
                              'code' => $request->code,
                              'grant_type' => 'authorization_code',
                              'scope' => 'https://www.googleapis.com/auth/drive https://www.googleapis.com/auth/spreadsheets');

                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => $method,
                    CURLOPT_POSTFIELDS => $data,
                    CURLOPT_HTTPHEADER => $headers,
                    CURLOPT_FAILONERROR => true
                ));

                $response = curl_exec($ch);
                // print_r($response);
                $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if (curl_errno($ch)) {
                    $error_msg = curl_error($ch);
                }
                curl_close($ch);            
                if (isset($error_msg)) {
                    $data = [];
                    $data['status'] = 'false';
                    $data['status_code'] = $http_status;
                    $data['message'] = $error_msg;
                    return response()->json(['status' => false, 'message' => $error_msg], 400);
                } else {
                    $result = json_decode($response);
                    $data = [];
                    $data['status'] = 'true';
                    $data['status_code'] = $http_status;
                    $data['result'] = $result;
                    // dd($result);
                    $GoogleAccount = new GoogleAccount();
                    $GoogleAccount->user_id = $user_id;
                    $GoogleAccount->label = $request->state;
                    $GoogleAccount->access_token = $result->access_token;
                    $GoogleAccount->refresh_token = $result->refresh_token;
                    $GoogleAccount->save();
                    return $this->sendResponse($GoogleAccount, 'Account addded.', 200);
                    // return redirect('https://goseemyproject.com/'. $userData->username .'/google-settings');
                }
            }
        } catch (\Exception$error) {
            return response()->json(['status' => false, 'message' => $error->getMessage()], 500);
        }
    }

    public function googleAccountConnect(Request $request) {
        try {
             $user_id = Auth::id();  
             $check = GoogleAccount::where('label' , $request->label)->where('user_id' , Auth::id())->first();
             if($check) {
                return response()->json(['status' => false, 'message' => 'This Label is already Added'], 200);

             }
             $key = connectionKey();   
             $label = $request->label;
             $urlRedirect = $request->url;
             $GOOGLE_CLIENT_ID = $key["GOOGLE_CLIENT_ID"];
             $url =  'https://accounts.google.com/o/oauth2/v2/auth/oauthchooseaccount?scope=https://www.googleapis.com/auth/drive https://www.googleapis.com/auth/spreadsheets&access_type=offline&prompt=consent&include_granted_scopes=true&response_type=code&state='.$label.'&redirect_uri='.$urlRedirect.'&client_id='.$GOOGLE_CLIENT_ID;
            
             return $this->sendResponse($url,'Success.',200);
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }


    public function GoogleSheetIntegration(Request $request) {
        try {   
         
            // $zap = Zap::where(['status' => 1, "sender_name" => 'gohighlevel'])
            // ->whereNotNull('sender_id')
            // ->whereNotNull('receiver_id')
            // ->get();
            // foreach ($zap as $zdata) {
            //     // saveZapLog($zdata->id, 'Run Zap For Get Go High Level Accounts data', $zdata->user_id);
            //     $account = GohighlevelAccounts::find($zdata->sender_id);
            //     // \Log::info($account);
            //     if ($account) {
            //         $meta = GoMeta::where('go_account_id', $account->id)->where('zap_id', $zdata->id)->first();
            //         // \Log::info($meta);
            //         if ($meta) {
            //             $url = "https://rest.gohighlevel.com/v1/contacts/?startAfterId=" . $meta->startAfterId . "&startAfter=" . $meta->startAfter . "&limit=20";
            //         } else {
            //             $url = "https://rest.gohighlevel.com/v1/contacts?limit=20";
            //         }
            //         $zap_detail = ZapDetail::where('zap_id', $zdata->id)->where('event_type', 'sender')->where('interation_type', 'gohighlevel')->first();
            //         if ($zap_detail) {
            //             $url .= "&query=" . $zap_detail->tag_id;
            //         } 
            //         $response = Http::withHeaders([
            //             'Authorization' => 'Bearer ' . $account->api_key,
            //         ])->get($url);
            //         // dd($response->object());
            //         // dump("getGoHighLevelData");
            //         // dump($response->object());
            //         if ($response->status() == 200) {
            //             // \Log::info("if/........");
            //             $rdata = $response->object();
            //             // \Log::info(print_r($rdata));
            //             dd($rdata);
            //             if (count($rdata->contacts) != 0) {
            //                 $sdata = [];
            //                 $zdata->update(['data_transfer_status' => 1]);
            //                 foreach ($rdata->contacts as $key => $datac) {
            //                     if ($datac->email || $datac->phone || $datac->contactName) {
            //                         $sdata[$key]['name'] = $datac->contactName;
            //                         $sdata[$key]['firstname'] = $datac->firstName;
            //                         $sdata[$key]['lastname'] = $datac->lastName;
            //                         $sdata[$key]['email'] = $datac->email;
            //                         $sdata[$key]['phone'] = $datac->phone;
            //                         $sdata[$key]['account_id'] = $account->id;
            //                         $sdata[$key]['user_id'] = $zdata->user_id;
            //                         $sdata[$key]['zap_id'] = $zdata->id;
            //                         $sdata[$key]['resource'] = 'gohighlevel';
            //                         $sdata[$key]['created_at'] = date("Y-m-d H:i:s");
            //                         $sdata[$key]['updated_at'] = date("Y-m-d H:i:s");

            //                         $senddata = [];
            //                         $senddata['firstName'] = $datac->firstName;
            //                         $senddata['lastName'] = $datac->lastName;
            //                         $senddata['name'] = $datac->firstName . " " . $datac->lastName;
            //                         $senddata['email'] = $datac->email;
            //                         $senddata['phone'] = $datac->phone;
            //                         $senddata['receiver_id']= @$zdata->receiver_id;
            //                         $senddata['zap_id'] = $zdata->id;
            //                     }
            //                     if ($zdata->receiver_name == 'webinar_account') {
            //                         WebinarAccount($senddata);
            //                         // \Log::info("webinar_account ...message");
            //                         // sendContactToWebinarAccount::dispatch($senddata);
            //                     } else if ($zdata->receiver_name == 'gohighlevel') {
            //                         goHighLevel($senddata);
            //                         // \Log::info("gohighlevel ...message");
            //                         // sendContactToGohighlevel::dispatch($senddata);
            //                     }else if ($zdata->receiver_name == 'active_campaign') {
            //                         ActiveCampign($senddata);
            //                         // \Log::info("active_campaign ...message");
            //                         // sendContactToActiveCampaign::dispatch($senddata);
            //                     }else if ($zdata->receiver_name == 'gohighlevel_single') {
            //                         GohighlevelSingle($senddata);
            //                         // \Log::info("gohighlevel_single ...message");
            //                         // sendContactGohighlevelSingle::dispatch($senddata);
            //                     }else if ($zdata->receiver_name == 'google_sheet') {
            //                         GoogleSheet($senddata); 
            //                     }
            //                     // sendContact::dispatch($datac,$this->zaps->from_id);
            //                     Contacts::insert($sdata);
            //                     $metadata = $rdata->meta;
            //                     if ($metadata->startAfterId && $metadata->startAfter) {
            //                         $gdata['startAfterId'] = $metadata->startAfterId;
            //                         $gdata['startAfter'] = $metadata->startAfter;
            //                         $gdata['go_account_id'] = $account->id;
            //                         $gdata['zap_id'] = $zdata->id;
            //                         if ($meta) {
            //                             $meta->update($gdata);
            //                         } else {
            //                             GoMeta::Create($gdata);
            //                         }
            //                     }
            //                 }
            //             }
            //         }
            //         // \Log::info("out if/........");
            //     }
            // }
            $zaps=Zap::where('status',1)
                  ->whereNotNull('sender_id')
                  ->whereNotNull('receiver_id')
                  ->get();
            foreach($zaps as $zap){   
                if($zap->sender_name == 'google_sheet') {
                    $account = googleAccount::find($zap->sender_id);
                    if($account) {
                        // dd($account);
                        $zapData = ZapDetail::where('zap_id',$zap->id)->where('event_type','sender')->where('interation_type','google_sheet')->first();
                        $google_sheet_id = $zapData->tag_id;
                        // dd($google_sheet_id);
                        $url = "https://sheets.googleapis.com/v4/spreadsheets/".$google_sheet_id."/values/Sheet1!A:B";
                        // dd($url);
                        $method="GET";
                        $headers=array('Authorization: Bearer '.$account->access_token,
                        'Content-Type: application/json');
                        // dd($headers);
                        
                        $response = Http::withHeaders([
                            'Authorization' => 'Bearer ' . $account->access_token,
                            'Content-Type: application/json',
                            $headers,
                            ])->get($url);  
                        // dd($response->object()->values);
                        if($response->status() == 200) {
                            $sheets = @$response->object()->values;
                            $sheetEntry = sheetEntryCount::where('zap_id',$zap->id)->first(); 
                            $sCount = ($sheetEntry != "") ? (int)$sheetEntry->count : 0;  
                            if($zap->receiver_name=='google_sheet') {
                                $sheets = array_slice($sheets, ($sCount + 1));
                                // dd(count($sheets));
                                $updateCount = $sCount + count($sheets);
                                if(1 <= count($sheets)){ 
                                    $zap->update(['data_transfer_status' => 1]);
                                }  
                                if($sheetEntry){
                                    $sheetEntry->update(['count' => $updateCount]);
                                }else{
                                    sheetEntryCount::create(['zap_id' => $zap->id,'count' => $updateCount]);
                                }
                            }  
                         
                            foreach($sheets as $values) { 
                                
                            if($values[0] != 'email' || $values[1] != 'name') {
                                $valueArray['email'] = $values[0];
                                $valueArray['firstName'] = $values[1];
                                $valueArray['zap_id'] = $zap->id;
                                $valueArray['receiver_id'] = $zap->receiver_id;
                                $valueArray['user_id'] = $zap->user_id;
                                $valueArray['name'] = $values[1];
                                $valueArray['lastName'] = isset($values[2]) ? $values[2] : '';
                                $valueArray['phone'] = isset($values[3]) ? $values[3] : ''; 

                                //   $users = User::find($zap->user_id);
                                $input['email'] = $values[0];
                                $input['name'] = $valueArray['firstName'].''.$valueArray['lastName'];
                                $input['firstname'] = $valueArray['firstName'];
                                $input['lastname'] = $valueArray['lastName'];
                                $input['user_id'] = $zap->user_id;
                                $input['zap_id'] = $zap->id;
                                $input['resource'] = $zap->receiver_name;
                                $input['created_at'] = date("Y-m-d H:i:s");
                                $input['updated_at'] = date("Y-m-d H:i:s");

                                if($zap->receiver_name=='webinar_account'){
                                    // dd("webinar_account",$valueArray);
                                    WebinarAccount($valueArray);
                                    //   return "if";
                                    $input['resource'] = "webinar_account";
                                    Contacts::create($input);
                                    $zap->update(['data_transfer_status' => 1]);
                                    sendContactToWebinarAccount::dispatch($valueArray);
                                    } else if($zap->receiver_name=='gohighlevel') {
                                        // dd("gohighlevel");
                                        // dd($valueArray);
                                        $input['resource'] = "gohighlevel";
                                        Contacts::create($input);
                                        // return "if1";
                                        // dd($valueArray);
                                        $zap->update(['data_transfer_status' => 1]);
                                        sendContactToGohighlevel::dispatch($valueArray); 
                                    } else if($zap->receiver_name=='active_campaign') {
                                        // dd("active_campaign");
                                        // return "if2";
                                        $input['resource'] = "active_campaign";
                                        Contacts::create($input);
                                        $zap->update(['data_transfer_status' => 1]);
                                        // dd($valueArray);
                                        ActiveCampign($valueArray);
                                        // sendContactToActiveCampaign::dispatch($valueArray);
                                    } else if($zap->receiver_name=='gohighlevel_single') {
                                        // dd("gohighlevel_single");
                                        // return "if3";
                                        $input['resource'] = "gohighlevel_single";
                                        Contacts::create($input);
                                        $zap->update(['data_transfer_status' => 1]);
                                        sendContactGohighlevelSingle::dispatch($valueArray);
                                    } else if($zap->receiver_name=='google_sheet') { 
                                        // dd("google_sheet");
                                        // return "if4";
                                        $input['resource'] = "google_sheet";
                                        Contacts::create($input);

                                        $this->GoogleSheetIntegrationSend($valueArray);
                                        // sendGoogleSheetsData::dispatch($valueArray);
                                    }
                                    // return "ot ud";
                                }  
                            }    
                            
                        }else{
                            return $response->reason()." Access";
                        }
                    }
                }
            }
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    } 

    public function GoogleSheetIntegrationSend($data) {
        try {
           $gAccount = googleAccount::find($data['receiver_id']);
           $zapData = ZapDetail::where('zap_id',$data['zap_id'])->where('event_type','receiver')->where('interation_type','google_sheet')->first();
           if($zapData) {
                $google_sheet_id = $zapData->tag_id;
                $url="https://sheets.googleapis.com/v4/spreadsheets/$google_sheet_id/values/A1:B1:append?valueInputOption=RAW";
                $method="POST";
                $headers=array('Authorization: Bearer '.$gAccount->access_token,
                'Content-Type: application/json');
                $field=[
                        [
                          $data["name"]
                        ],
                        [
                          $data["email"]
                        ],
                    ];
                $data1["range"]="A1:B1";
                $data1["values"]=$field;
                $data1["majorDimension"]="COLUMNS";
                $new_data1=json_encode($data1);
                $access_options = connectIntegration($url,$headers,$method,$new_data1);
                // dd($access_options);
           }
           
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    } 

    public function addGohighlevelSingle(Request $request){
        try {
                $validator = Validator::make($request->all(), [
                    'code' => 'required',
                    'state' => 'required',
                ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(),444);
            }
            $type = ($request->state == "ready_funnels_pro") ? 'agency' : 'single';
            $key = connectionKey();   
			$client_id= $key['GOHIGHLEVEL_CLIENT_ID'];
			$client_secret= $key['GOHIGHLEVEL_CLIENT_SECRET'];
                $url = "https://api.msgsndr.com/oauth/token";
                $method = "POST";
                $headers = array(
				    'Content-Type: application/x-www-form-urlencoded'
				  );
                $data = 'client_id='.$client_id.'&client_secret='.$client_secret.'&grant_type=authorization_code&code='.$request->code;
                $getaccesstoken = connectIntegration($url, $headers, $method, $data);
                if ($getaccesstoken['status_code'] == 200) {
                    $data=$getaccesstoken['result'];
                    $g = new GohighlevelAccounts();
                    $g->label = $request->state;
                    $g->api_key = $key['GOHIGHLEVEL_API_KEY'];
                    $g->location_id = $data->locationId;
                    $g->access_token = $data->access_token;
                    $g->refresh_token = $data->refresh_token;
                    $g->user_id = Auth::id();
                    $g->type=$type;
                    $g->save();
                    return $this->sendResponse($g, 'Account addded.', 200);
                }else{
                     return $this->sendError('Internal Server Error.',[], 500);
                }
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function getAllAccountGoSingle(Request $request){
        try{
            $data = GohighlevelAccounts::where('user_id', Auth::id())->where('type','single')->latest()->get();
            return $this->sendResponse($data, 'success.', 200);
        }catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function connectGotoWebinar(Request $request) {
      
        try { 
            if($request->code){
                $key = connectionKey(); 
                $webinar_client_id = $key['GO_TO_WEBINAR_CLIENT_ID'];
                $webinar_client_secret = $key['GO_TO_WEBINAR_CLIENT_SECRET'];
                $webinar_redirect_uri =$key['GO_TO_WEBINAR_REDIRECT_URL'];
                // $redirect_uri = "http://localhost:8080";
                $redirect_uri = $key['GO_TO_WEBINAR_REDIRECT_URL'];
                $url="https://api.getgo.com/oauth/v2/token?redirect_uri=$redirect_uri&grant_type=authorization_code&code=".$request->code;
       
                $method="POST";
                $data='';
                $headers=array('Authorization: Basic '.base64_encode("$webinar_client_id:$webinar_client_secret"),'Content-Type: application/x-www-form-urlencoded','Accept: application/json');
                $access_options = connectIntegration($url,$headers,$method,$data); 
               
                if($access_options['status_code']==200){
                    $check = GoToWebinarAccounts::where('first_name',$access_options['result']->firstName)->where('last_name',$access_options['result']->lastName)->where('email',$access_options['result']->email)->where('user_id',Auth::id())->get();
                    if(count($check)){
                        return $this->sendResponse($check, 'Account Already exist', 'warn', 409);
                    }
                    else{
                        $web  = new GoToWebinarAccounts();
                        $web->first_name = $access_options['result']->firstName;
                        $web->last_name = $access_options['result']->lastName;
                        $web->email = $access_options['result']->email;
                        $web->refresh_token = $access_options['result']->refresh_token;
                        $web->access_token = $access_options['result']->access_token;
                        $web->account_key = $access_options['result']->account_key;
                        $web->organizer_key = $access_options['result']->organizer_key;
                        $web->user_id = Auth::id();
                        $web->save();
                        return $this->sendResponse($web, 'Account added successfully','succ', 200);
                    }
                }
                else{
                    return $this->sendError('Internal server Error.',[], 500);
                }
            }
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }

    }
    public function gowebinarAlldata(){
        try { 
            $data = GoToWebinarAccounts::where('user_id', Auth::id())->latest()->get();
            return $this->sendResponse($data, 'success.', 200);
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function deleteGotoWebinar($id){
        try {
            $delete = GoToWebinarAccounts::where('id', $id)->delete();
            return $this->sendResponse([], 'deleted', 200);
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function gotowebinarUpWebs(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'account_id' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }
            $webinar = GoToWebinarAccounts::find($request->account_id);
            if (!$webinar) {
                return $this->sendError('Data not found.', '', 404);
            }
            $key = connectionKey();  
            $webinar_client_id = $key['GO_TO_WEBINAR_CLIENT_ID'];
            $webinar_client_secret = $key['GO_TO_WEBINAR_CLIENT_SECRET'];
            $url="https://api.getgo.com/oauth/v2/token?grant_type=refresh_token&refresh_token=".$webinar->refresh_token;
            $method="POST";
            $data='';
            $headers=array('Authorization: Basic '.base64_encode("$webinar_client_id:$webinar_client_secret"),'Content-Type: application/x-www-form-urlencoded','Accept: application/json');
            $access_options = connectIntegration($url,$headers,$method,$data);  
            if($access_options['status'] != false)
            {
                $webinar->access_token = $access_options['result']->access_token;
                $webinar->refresh_token = $access_options['result']->refresh_token;
                if($webinar->save()){
                    $url="https://api.getgo.com/G2W/rest/organizers/".$webinar->organizer_key."/upcomingWebinars";
                    $method="GET";
                    $data='';
                    $headers=array('Authorization: Bearer '.$webinar->access_token);
                    $access_options = connectIntegration($url,$headers,$method,$data);
                    if($access_options['status_code']==200){
                        return $this->sendResponse($access_options['result'], 'success.', 200);
                    }
                }
            }
            else{
                return $this->sendError('Internal Server Error.',[], 500);
            }
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
}
