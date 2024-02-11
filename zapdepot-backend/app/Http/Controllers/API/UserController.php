<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Validator;
use App\Mail\SendMail;
use App\Models\Zap;
use App\Models\GohighlevelAccounts;
use App\Models\GoToWebinarAccounts;
use App\Models\GoogleAccount;
use App\Models\ActiveCampaignAccounts;
use Mail;

class UserController extends BaseController
{
    public function addAdminUser(Request $request)
    {
        try {
            if ($request->id) {
                $validator = Validator::make($request->all(), [
                    'email' => 'required|email|unique:users,email,' . $request->id,
                    'password' => 'required',
                    'first_name' => 'required',
                    'last_name' => 'required',
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'email' => 'required|email|unique:users',
                    'password' => 'required',
                    'first_name' => 'required',
                    'last_name' => 'required',
                ]);
            }

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }
            if ($request->id) {
                $user = User::find($request->id);
            } else {
                $user = new User();
            }
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->city = $request->city;
            $user->country = $request->country;
            $user->postal_code = $request->postal_code;
            $user->password = Hash::make($request->password);
            $user->visible_password = $request->password;
            $user->parent_id = Auth::id();
            if ($user->save()) {
                $role = Role::find(2);
                $user->assignRole($role);
                $receiver_email = $request->email;
                $details = [
                    'title' => '',
                    'body' => '',
                    'fname' => $request->first_name,
                    'lname' => $request->last_name,
                    'uname' => $request->email,
                    'upass' => $request->password,
                ];
                \Mail::to([$receiver_email])->send(new SendMail($details));
            }
            return $this->sendResponse([], 'User added successfully.', 200);
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function getAdminUser(Request $request)
    {
        try {
            $user = User::where('parent_id', Auth::id())->latest()->paginate(10);
            return $this->sendResponse($user, '', 200);
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function getSingleUser($id)
    {
        try {
            $user = User::find($id);
            if ($user) {
                return $this->sendResponse($user, '', 200);
            } else {
                return $this->sendError('No record Found.', [], 400);
            }
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function deleteUser($id)
    {
        try {
            $user = User::where('id', $id)->delete();
            return $this->sendResponse([], 'deleted', 200);
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function updateUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email,' .Auth::id(),
                'first_name' => 'required',
                'last_name' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }
            $user = User::find(Auth::id());
            $user->first_name=$request->first_name;
            $user->last_name=$request->last_name;
            $user->email=$request->email;
            $user->city=$request->city;
            $user->country=$request->country;
            $user->postal_code=$request->postal_code;
            $user->save();
            $user_data=$user->only([
                'first_name',
                'last_name',
                'email',
                'country',
                'city',
                'postal_code',
                'parent_id'
            ]);
            $user_data['type']=$user->roles->first()->name;
            return $this->sendResponse($user_data, 'updated', 200);
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function updateUserPassword(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'currrent_password' => 'required',
                'new_password' => 'required',
                'confirm_password' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }
            $user=User::find(Auth::id());
            if($user->visible_password!=$request->currrent_password){
                return $this->sendError('current password mismatch.', [], 500);
            }
            $user->visible_password=$request->new_password;
            $user->password=Hash::make($request->new_password);
            $user->save();
            return $this->sendResponse([], 'updated', 200);
        }catch (\Throwable $e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }

    public function getUserDash(){
        try {
            $data = [];
            $data['zap'] = Zap::where('user_id', Auth::id())->count();
            $goheigh = GohighlevelAccounts::where('user_id', Auth::id())->count();
            $goheighcount = $goheigh ? $goheigh : 0;
            $camp = ActiveCampaignAccounts::where('user_id', Auth::id())->count();
            $campcount = $camp ? $camp : 0;
            $webinar = GoToWebinarAccounts::where('user_id', Auth::id())->count();
            $googleAccounts = GoogleAccount::where('user_id', Auth::id())->count();
            $googleAccountscounts = $googleAccounts ? $googleAccounts : 0;
            $webinarcount = $webinar ? $webinar : 0;
            $data['app'] = $goheighcount + $campcount + $webinarcount + $googleAccounts;
            return $this->sendResponse($data, '', 200);
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }
    public function getAdminDash(){
        try {
            $data = [];
            $data['zapdata'] = Zap::count();
            $goheigh = GohighlevelAccounts::count();
            $goheighcount = $goheigh ? $goheigh : 0;
            $camp = ActiveCampaignAccounts::count();
            $campcount = $camp ? $camp : 0;
            $webinar = GoToWebinarAccounts::count();
            $webinarcount = $webinar ? $webinar : 0;
            $data['appdata'] = $goheighcount + $campcount + $webinarcount;
            return $this->sendResponse($data, '', 200);
        } catch (\Throwable$e) {
            return $this->sendError('Internal Server Error.', $e->getMessage(), 500);
        }
    }

}
