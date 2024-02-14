
<?php
use App\Models\Zaplog;
use App\Models\GoogleAccount;
use App\Models\ZapDetail;
use App\Models\GohighlevelAccounts;
use App\Models\ActiveCampaignAccounts;
use App\Models\GoToWebinarAccounts; 
use App\Models\AweberAccount; 
use App\Models\ErrorLog; 
use Illuminate\Support\Facades\Http;

    if (!function_exists('connectIntegration')) {
        function connectIntegration($url, $headers, $method, $data)
        {
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

            // print_r($ch);
            $response = curl_exec($ch);
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
                return $data;
            } else {
                $result = json_decode($response);
                $data = [];
                $data['status'] = 'true';
                $data['status_code'] = $http_status;
                $data['result'] = $result;
                return $data;
            }
        }
    }

    if (!function_exists('saveZapLog')) {
        function saveZapLog($id , $msg,$user_id = "")
        {
            $zapsLog = new Zaplog();
            $zapsLog->zap_id = $id; 
            $zapsLog->user_id = $user_id; 
            $zapsLog->detail = $msg;
            $zapsLog->save();
        }
    }
    function connectionKey()
    {
        $input = [];
        //WEBINAR
        $input['GO_TO_WEBINAR_CLIENT_ID'] = '2cbf7ce6-09ae-4106-a391-6df9a4dac8c3';
        $input['GO_TO_WEBINAR_CLIENT_SECRET'] = 'NQ69ykoRCybeAqWR5YTT1BEU';
        $input['GO_TO_WEBINAR_REDIRECT_URL'] = 'https://zapdepot.io/integration/add/gotowebinar';
        // $input['GO_TO_WEBINAR_REDIRECT_URL'] = 'http://localhost:8080/integration/add/gotowebinar';
        //GOOGLE
        $input['GOOGLE_CLIENT_ID'] = '225769364371-ksq7d27qces7lqs5h9gta2ss9q2fmb84.apps.googleusercontent.com';
        $input['GOOGLE_SECRATE_KEY'] = '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';
        $input['GOOGLE_API_KEY'] = 'AIzaSyCIqEFUKH_XVJtwCDlYGaOVio9mUI_aYig';
        $input['GOOGLE_REDIRECT_URL'] = 'http://zapdepot.io/integration/add/googleAccount';
        // $input['GOOGLE_API_KEY'] = 'http://localhost:8080/integration/add/googleAccount';
        //GOHIGHLEVEL
        $input['GOHIGHLEVEL_CLIENT_ID'] = '62256ce0688c9632a80d915b-l0ghgtys';
        $input['GOHIGHLEVEL_CLIENT_SECRET'] = '7a62012c-dbad-4b08-aa50-34e0e95264c9';
        $input['set_mobile_no'] = '7536987532';
        $input['location_id'] = 'cRp5gxVy2DanQjwC2nbg';
        $input['GOHIGHLEVEL_REDIRECT_URL'] = 'https://zapdepot.io/integration/add/gohighlevel';
        $input['GOHIGHLEVEL_API_KEY'] = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJsb2NhdGlvbl9pZCI6ImNScDVneFZ5MkRhblFqd0MybmJnIiwiY29tcGFueV9pZCI6Im02eG9Pa1dGcEpXMlNLOVF3WGlLIiwidmVyc2lvbiI6MSwiaWF0IjoxNjcwNTcyNzE1NTk5LCJzdWIiOiJVVndQZWIzc1JQcDZleU9aZkJvSSJ9.x8B-YDIVp9NDDzMC-vqJ_9rv00iYII97EuS6hpq6FNE';
        // $input['GOHIGHLEVEL_API_KEY'] = 'http://localhost:8080/integration/add/gohighlevel';

        $input['AWEBER_CLIENT_ID'] = "0alKdCUBpyz9V4hlGOeh6XsLv160JC4H";
        $input['AWEBER_CLIENT_SECRET_KEY'] = "MYepVEfYr0mPmEthnXH4pA99A08Z8fN1";
        $input['AWEBER_REDIRECT_URL'] = "http://localhost:8080/integration/add/aweber";
        return $input;
    }
 
    function GoogleSheet($data)
    {
        try {
            Log::info('google-sheet'); 
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
            }
            
            } catch (\Throwable$e) { 
                \Log::info($e->getMessage());
            }
    }
 
    function ActiveCampign($user_data){
        try {  
            Log::info('active-campign'); 
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
                if($response->status() != 200 || $response->status() != 201) {
                    $res = $response->object();
                    $message_array = array(
                        "user_id" => $user_data['user_id'],
                        "error_log" => $res->message ? $res->message : 'Something went wrong.',
                        "zap_id" => $user_data['zap_id'],
                        "type" => "Active campign",
                   );

                   add_error_log($message_array);
                }     
            }
        
        } catch (\Throwable$e) {
            \Log::info($e->getMessage());
        }
    }

    function goHighLevel($user_data) {
        try { 
            Log::info('Go-high-level'); 
            $url = "https://rest.gohighlevel.com/v1/contacts/";
            $data = GohighlevelAccounts::find($user_data['receiver_id']);
            if ($data) {
                $tags = ZapDetail::where('zap_id', $user_data['zap_id'])->where('event_type', 'receiver')->where('interation_type', 'gohighlevel')->pluck('tag_id');
                $array = ['email' => $user_data['email'], 'firstName' => $user_data['firstName'],'lastName' => $user_data['lastName'], 'phone' => $user_data['phone'], 'tags' => $tags];
                
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $data->api_key,
                    'Content-Type' => 'application/json',
                ])->post($url, $array); 
                if ($response->status() == 201 || $response->status() == 200) {
                    $rdata = $response->object();
                    $contact = $rdata->contact;
                    $tags = ZapDetail::where('zap_id', $user_data['zap_id'])->where('event_type', 'receiver')->where('interation_type', 'gohighlevel')->first(); 
                } 
                if($response->status() != 200 || $response->status() != 201) {
                    $res = $response->object();
                    $message_array = array(
                        "user_id" => $user_data['user_id'],
                        "error_log" => $res->message ? $res->message : 'Something went wrong.',
                        "zap_id" => $user_data['zap_id'],
                        "type" => "Ready Funnel Pro",
                   );

                   add_error_log($message_array);
                }     
            }
        
        } catch (\Throwable$e) {
            \Log::info($e->getMessage());
        }
    }
    
    function WebinarAccount($user_data){
        try {
            Log::info('webinar-send');     
            $web=GoToWebinarAccounts::find($user_data['receiver_id']);   
            $tags=ZapDetail::where('zap_id',$user_data['zap_id'])->where('event_type','receiver')->where('interation_type','webinar_account')->first(); 
            if($tags){ 
                $lastName = ($user_data['lastName'] == "") ? $user_data['firstName'] : $user_data['lastName'];
                $array=["email"=>$user_data['email'],'firstName'=>$user_data['firstName'],'lastName'=>$lastName];
                
                $url="https://api.getgo.com/G2W/rest/v2/organizers/$web->organizer_key/webinars/$tags->tag_id/registrants?resendConfirmation=false"; 
            
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $web->access_token,
                    'Content-Type' => 'application/json',
                ])->post($url,$array);
                if($response->status() != 200 || $response->status() != 201) {
                    $res = $response->object();
                    $message_array = array(
                        "user_id" => $user_data['user_id'],
                        "error_log" => $res->message ? $res->message : 'Something went wrong.',
                        "zap_id" => $user_data['zap_id'],
                        "type" => "Webinar",
                   );

                   add_error_log($message_array);
                }        
            } 
        } catch (\Throwable$e) {
            \Log::info($e->getMessage());
        }
    }

    function GohighlevelSingle($user_data){
        try {   
            Log::info('send-goheigh-single-data'); 
            $url = "https://api.msgsndr.com/contacts/";
            $data=GohighlevelAccounts::find($user_data['receiver_id']);
            if($data){
                // print_r($user_data);
                $headers = array(
                    'Authorization' => 'Bearer '.$data->access_token,
                    'Content-Type' => 'application/json',
                    'Version'=>'2021-04-15'
                );
                $array=['email'=>$user_data['email'],'locationId'=>$data->location_id,'name'=>$user_data['name'],'phone'=>$user_data['phone']];
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer '.$data->access_token,
                    'Content-Type' => 'application/json',
                    'Version'=>'2021-04-15'
                ])->post($url,$array);
                // dd($response->status() , $response , $response->object());
                if($response->status() != 200 || $response->status() != 201) {
                    $res = $response->object();
                    $message_array = array(
                        "user_id" => $user_data['user_id'],
                        "error_log" => $res->message ? $res->message : 'Something went wrong.',
                        "zap_id" => $user_data['zap_id'],
                        "type" => "Go High Level",
                   );

                   add_error_log($message_array);
                }
            }
        } catch (\Throwable$e) {
            \Log::info($e->getMessage());
        }
    }

    function AweberSendData($user_data){
        try {
            // dd($user_data);    
            Log::info('send-aweber'); 
            $r_acc_data=AweberAccount::find($user_data['receiver_id']);
            $url="https://api.aweber.com/1.0/accounts/$r_acc_data->account_id/lists/".$user_data['receiver_tag_list_id']."/subscribers";
            $method="POST";
            $data=array(
                "email" => $user_data['email'],
                "name" => $user_data['name'],
                "update_existing" => "true",
            );
            $data = http_build_query($data);
            $headers=array('Authorization: Bearer '.$r_acc_data->access_token , 'Content-Type' => 'application/x-www-form-urlencoded');
            $response = connectIntegration($url,$headers,$method,$data);
            if($response['status'] == "false" || $response['status'] == false) {
                //    dd(1);
                   $message_array = array(
                        "user_id" => $user_data['user_id'],
                        "error_log" => $response['message'] ? $response['message'] : 'Something went wrong.',
                        "zap_id" => $user_data['zap_id'],
                        "type" => "aweber",
                   );
                   add_error_log($message_array); 
            }
        } catch (\Throwable$e) {
            \Log::info($e->getMessage());
        }
    }


    function add_error_log($error_log) {
        $log = new ErrorLog;
        $log->user_id = $error_log['user_id'];
        $log->error_log = $error_log['error_log'];
        $log->zap_id = $error_log['zap_id'];
        $log->integration_type = $error_log['type'];
        $log->save();
    }
