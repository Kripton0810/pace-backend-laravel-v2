<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

date_default_timezone_set('Asia/kolkata');

class UserAuthController extends Controller
{
    public function userRegisterManually(Request $req)
    {
        try {

        //validate
        $rules = ([
            'first_name'=>'alpha|min:3|required',
            'mid_name'=>'alpha',
            'last_name'=>'alpha|required',
            'email'=>'email|required',
            'date_of_birth'=>'date_format:Y-m-d|before:today|required',
            'phone_number'=>'numeric|min:10|required',
            'alternate_phone_number'=>'numeric|min:10',
            'description'=>'min:50|required',
            'linkedin'=>'url',
            'github'=>'url',
            'insta_id'=>'url',
            'fb_id'=>'url',
            'portfolio_url'=>'url'

        ]);

        $validation = Validator::make($req->all(),$rules);
        if($validation->fails())
        {
            return response()->json(["message"=>$validation->messages(),"status"=>false],400);
        }
        else
        {
            $auth = app('firebase.auth');
            $data = User::where('email',$req->email)->first();

            if (isNull($data)) {
                $userProperties = [
                    'email' => $req->email,
                    'emailVerified' => false,
                    'password' => $req->password
                ];
                return $userProperties;
            }
            else
            {
                return response()->json(["message"=>"User already exist","status"=>false],400);
            }
            }
        } catch (\Throwable $th) {
            response()->json(["message"=>$th->getMessage(),"status"=>false],400);
        }

    }
    public function createCustomToken(Request $req)
    {
        $uid = $req->token;
        $additionalClaims = [
            'premiumAccount' => true
        ];
        $auth = app('firebase.auth');
        $customToken = $auth->createCustomToken($uid, $additionalClaims);

        $customTokenString = $customToken->toString();
        return response()->json(["message"=>"Token created Successfull","token"=>$customTokenString,"status"=>true],201);
    }
    public function bearerToken()
    {
       $header = $this->header('Authorization', '');
       if (Str::startsWith($header, 'Bearer ')) {
                return Str::substr($header, 7);
       }
    }

}
