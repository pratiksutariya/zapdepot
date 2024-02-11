<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Zap;
use App\Models\ZapDetail;
use App\Models\Contacts;
use App\Models\Zaplog;
use Auth;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\Models\GoToWebinarAccounts;
use App\Models\User;
use App\Jobs\getGohighlevelContact;
use App\Jobs\getActiveCampaignContact;
use App\Jobs\getWebinarContact;
use App\Jobs\sendContactToGohighlevel;
use App\Jobs\sendContactToActiveCampaign;
use App\Jobs\sendContactToWebinarAccount;
use Illuminate\Support\Facades\Http;

class Zapcontroller extends BaseController
{
    public function getAllzaps()
    {
        try {
            $data = Zap::where('user_id', Auth::id())->latest()->paginate(10);
            return $this->sendResponse($data, 'success.', 200);
        } catch (\Throwable $e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function addzap(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }
            $zind = array();
            $zind['name'] = $request->name;
            $zind['user_id'] = Auth::id();
            $zind['status'] = 0; 
            $zind['get_val'] = 0; 
            $data = Zap::create($zind); 
            return $this->sendResponse($data, 'added', 200);
        } catch (\Throwable $e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function deleteZap($id)
    {
        try {
            $delete = Zap::where('id', $id)->delete();
            return $this->sendResponse([], 'deleted', 200);
        } catch (\Throwable $e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function updateStatusZap(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required',
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }
            $z = Zap::find($request->id);
            $z->status = $request->status;
            if($request->status == 0){
                $z->data_transfer_status = 0;
            }
            $z->save();
            $msg = $request->status == 0 ? 'Zap deactive' : 'Zap active'; 
            saveZapLog($z->id , $msg ,$z->user_id);
            return $this->sendResponse([], 'updated', 200);
        } catch (\Throwable $e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function updatenameZap(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }
            $z = Zap::find($request->id);
            $z->name = $request->name;
            $z->save();
            return $this->sendResponse([], 'updated', 200);
        } catch (\Throwable $e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function getZap($id)
    {
        try {
            $data = Zap::where('id', $id)->first();
            if ($data) {
                if ($data->sender_id) {
                    $zapd = ZapDetail::where('zap_id', $data->id)
                        ->where('event_type', 'sender')
                        ->first();
                    if ($zapd) {
                        $data->sender_action_type = $zapd->action_type;
                        if ($zapd->action_type == 'list') {
                            $data->sender_tag_list_id = $zapd->list_id;
                        } else {
                            $data->sender_tag_list_id = $zapd->tag_id;
                        }
                    }
                }
                if ($data->receiver_id) {
                    $zapd = ZapDetail::where('zap_id', $data->id)
                        ->where('event_type', 'receiver')
                        ->first();
                    if ($zapd) {
                        $data->receiver_action_type = $zapd->action_type;
                        if ($zapd->action_type == 'list') {
                            $data->receiver_tag_list_id = $zapd->list_id;
                        } else {
                            $data->receiver_tag_list_id = $zapd->tag_id;
                        }
                    }
                }
                return $this->sendResponse($data, 'success', 200);
            } else {
                return $this->sendError('No Data Found.', [], 404);
            }
        } catch (\Throwable $e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function updateZap(Request $request)
    {  
        try {
            $validator = Validator::make($request->all(), [
                'sender_name' => 'required',
                'receiver_name' => 'required',
                'sender_id' => 'required',
                'receiver_id' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }
            $zap = Zap::find($request->id);
            if ($zap) {
                $zap->sender_name = $request->sender_name;
                $zap->receiver_name = $request->receiver_name;
                $zap->sender_id = $request->sender_id;
                $zap->receiver_id = $request->receiver_id;
                $zap->sender_tag_list_id = $request->sender_tag_list_id;
                $zap->receiver_tag_list_id = $request->receiver_tag_list_id;
                $zap->status = 1;
                $zap->update();
                ZapDetail::where('zap_id', $zap->id)->delete();
                if ($request->sender_tag_list_id) {
                    $zapd = new ZapDetail();
                    $zapd->zap_id = $zap->id;
                    $zapd->event_type = 'sender';
                    $zapd->interation_type = $request->sender_name;
                    $zapd->action_type = $request->sender_action_type;
                    if ($request->sender_action_type == 'list') {
                        $zapd->list_id = $request->sender_tag_list_id;
                    } else {
                        $zapd->tag_id = $request->sender_tag_list_id;
                    }
                    $zapd->save();
                }
                if ($request->receiver_tag_list_id) { 
                    $zapd = new ZapDetail();
                    $zapd->zap_id = $zap->id;
                    $zapd->event_type = 'receiver';
                    $zapd->interation_type = $request->receiver_name;
                    $zapd->action_type = $request->receiver_action_type;
                    if ($request->receiver_action_type == 'list') {
                        $zapd->list_id = $request->receiver_tag_list_id;
                    } else {
                        $zapd->tag_id = $request->receiver_tag_list_id;
                    }
                    $zapd->save();
                } 
                saveZapLog($zap->id , "Updated Zap",$zap->user_id);
                return $this->sendResponse([], 'updated', 200);
            } else {
                return $this->sendError('No data found.', $e->getMessage(), 404);
            }
        } catch (\Throwable $e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function dis()
    {
        $zap = Zap::find(2);
        getGohighlevelContact::dispatch($zap);
        $data['firstName'] = 'raj';
        $data['lastName'] = 'patel';
        $data['phone'] = '18887324197';
        $data['email'] = 'sssaini@gmail.com';
        $data['receiver_id'] = 4;
        $data['zap_id'] = $zap->id;
        sendContactToActiveCampaign::dispatch($data);
    }
    public function goheight_to_webinar()
    {
        $zaps = Zap::where('sender_name','LIKE','gohighlevel')->where('receiver_name','LIKE','webinar_account')->where('status','=',1)->get();
        foreach ($zaps as $zap) {
            getGohighlevelContact::dispatch($zap);
            $datas = Contacts::where('resource', 'gohighlevel')->get();
            foreach ($datas as $d){
                $data['firstName'] = $d->firstname;
                $data['lastName'] = $d->lastname;
                $data['email'] = $d->email;
                $data['receiver_id'] = $zap->receiver_id;
                $data['zap_id'] = $zap->id;
                sendContactToWebinarAccount::dispatch($data);
            }
        }
    }
    public function goheight_to_activecamp(){
        $zaps = Zap::where('sender_name','LIKE','gohighlevel')->where('receiver_name', 'LIKE','active_campaign')->where('status','=',1)->get();
        foreach ($zaps as $zap) {
            getGohighlevelContact::dispatch($zap);
            $datas = Contacts::where('resource', 'gohighlevel')->get();
            foreach ($datas as $d) {
                $data['firstName'] = $d->firstname;
                $data['lastName'] = $d->lastname;
                $data['phone'] = $d->phone;
                $data['email'] = $d->email;
                $data['receiver_id'] = $zap->receiver_id;
                $data['zap_id'] = $zap->id;
                sendContactToActiveCampaign::dispatch($data);
            }
        }
    }
    public function activecamp_to_goheight(){
        $zaps = Zap::where('sender_name', 'LIKe','active_campaign')->where('receiver_name', 'LIKE' ,'gohighlevel')->where('status','=',1)->get();
        foreach ($zaps as $zap) {
            getActiveCampaignContact::dispatch($zap);
            $datas = Contacts::where('resource', 'active_campaign')->get();
            foreach ($datas as $d) {
                $data['firstName'] = $d->firstname;
                $data['lastName'] = $d->lastname;
                $data['name'] = $d->name;
                $data['phone'] = $d->phone;
                $data['email'] = $d->email;
                $data['receiver_id'] = $zap->receiver_id;
                $data['zap_id'] = $zap->id;
                sendContactToGohighlevel::dispatch($data);
            }
        }
    }
    public function activecamp_to_webinar(){
        $zaps = Zap::where('sender_name','LIKE', 'active_campaign')->where('receiver_name', 'LIKE','webinar_account')->where('status','=',1)->get();
        foreach ($zaps as $zap) {
            // getActiveCampaignContact::dispatch($zap);
            $datas = Contacts::where('resource', 'active_campaign')->get();
            foreach ($datas as $d) {
                $data['firstName'] = $d->firstname ? $d->firstname : '-';
                $data['lastName'] = $d->lastname ? $d->lastname : '-';
                $data['email'] = $d->email ? $d->email : '-';
                $data['receiver_id'] = $zap->receiver_id;
                $data['zap_id'] = $zap->id;
                sendContactToWebinarAccount::dispatch($data);
            }
        }
    }
    public function webinar_to_goheight(){ 
        $web=GoToWebinarAccounts::all();
        $key = connectionKey(); 
        foreach($web as $account){
            $webinar_client_id = $key['GO_TO_WEBINAR_CLIENT_ID'];
            $webinar_client_secret = $key['GO_TO_WEBINAR_CLIENT_SECRET'];
 
            $url="https://api.getgo.com/oauth/v2/token?grant_type=refresh_token&refresh_token=".$account->refresh_token;
            $method="POST";
            $data='';
            $headers=array('Authorization: Basic '.base64_encode("$webinar_client_id:$webinar_client_secret"),'Content-Type: application/x-www-form-urlencoded','Accept: application/json');
            $access_options = connectIntegration($url,$headers,$method,$data);
            if($access_options['status_code']==200)
            {
                $result=$access_options['result'];
                $go=GoToWebinarAccounts::find($account->id);
                $go->access_token = $result->access_token;
                $go->refresh_token = $result->refresh_token;
                $go->account_key = $result->account_key;
                $go->organizer_key = $result->organizer_key;
                $go->update();
            }

        }
    }

    public function GetContact(Request $request) {
        try {  
            $page = $request->page;  
            $data = Contacts::leftJoin("zaps","zaps.id","=","contacts.zap_id")->where('contacts.user_id',Auth::id())->select("contacts.*","zaps.name as zap_name"
                // DB::raw(
                // 'DATE("d-m-Y H:i a",strtotime("contacts.created_at"))as date')
                
                )->orderBy('id', 'DESC')->paginate(20);
            if($request->search) {
                $search = $request->search;
                $data = Contacts::leftJoin("zaps","zaps.id","=","contacts.zap_id")->where('contacts.user_id',Auth::id())->select("contacts.*",
                // DB::raw(
                //     "DATE('Y-m-d H:i a',strtotime('contacts.created_at'))as date"
                // ),
                "zaps.name as zap_name")->where('zap_id' , $search)->orderBy('id', 'DESC')->paginate(20);  
            }  
          return $this->sendResponse($data, 'success.', 200);
        } catch (\Throwable $e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }

    public function getZapsLogs($id) {
        try {
          $data = Zaplog::where('zap_id' , $id)->latest()->paginate(10);
          return $this->sendResponse($data, 'success.', 200);
        } catch (\Throwable $e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
}
