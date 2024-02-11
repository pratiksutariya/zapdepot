<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;
use Auth;

class AuthController extends BaseController
{
    public function login(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors(),422);       
            }
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
                $user = Auth::user();
                $user_data=$user->only([
                                        'first_name',
                                        'last_name',
                                        'email',
                                        'country',
                                        'city',
                                        'postal_code',
                                        'parent_id'
                                    ]);
                $accesstoken =  $user->createToken('authToken')->accessToken;
                $user_data['type']=$user->roles->first()->name;
                $data['user_data']=$user_data;
                $data['accesstoken']=$accesstoken;
                return $this->sendResponse($data, 'User login successfully.',200);
            } 
            else{ 
                return $this->sendError('Unauthorised.', ['error'=>'Unauthorised'],400);
            } 
            }catch(\Throwable $e){
                return $this->sendError('Internal Server Error.',$e->getMessage(),500);
            }
    }
    public function logout(){
        try
            {
                $auth_user = Auth::user()->token();
                $auth_user->revoke();
                return $this->sendResponse([], 'User logout successfully.',200);
            }
        catch(\Throwable $e)
        {
            return $this->sendError('Internal Server Error.',$e->getMessage(),500);
        }
    }
}
