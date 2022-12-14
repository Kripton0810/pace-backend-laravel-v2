<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

use Illuminate\Http\File;
use Illuminate\Http\Response;
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
            'portfolio_url'=>'url',
            'gender'=>'alpha|required',
            'regional_language'=>'alpha',
            'password'=>'min:8|required'

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
                try {
                    $auth->createUser($userProperties);
                    $auth->sendEmailVerificationLink($req->email);
                    // $link = $auth->getEmailVerificationLink($req->email);
                    unset($req['password']);
                    $req['login_as']= 'web';

                    $create_user = User::create($req->all());
                    return response()->json(["message"=>"user created and verification mail is send to the user","status"=>true,"data"=>$create_user],201);
                } catch (\Throwable $th) {
                    //throw $th;
                    return response()->json(["message"=>$th->getMessage(),"status"=>false],400);
                }
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
    public function loginManually(Request $req)
    {
        //get the email and validate it and then
        $rules = ([
            'email'=>'email|required',
            'password'=>'required'
        ]);
        $validation = Validator::make($req->all(),$rules);
        if($validation->fails())
        {
            return response()->json(["message"=>$validation->messages(),"status"=>false],400);
        }
        try {
                $auth = app('firebase.auth');
                $email = $req->email;
                $clearTextPassword = $req->password;
                $signInResult = $auth->signInWithEmailAndPassword($email, $clearTextPassword);
                if($signInResult)
                {
                    $user = $auth->getUserByEmail($req->email);
                    if($user->emailVerified)
                    {
                        $customToken = $auth->createCustomToken($user->uid);
                        $customTokenString = $customToken->toString();
                        $user_from_db = User::where('email',$email)->first();
                        unset($user_from_db['id']);
                        unset($user_from_db['login_as']);
                        unset($user_from_db['phone_number']);
                        unset($user_from_db['alternate_phone_number']);
                        unset($user_from_db['description']);
                        unset($user_from_db['github']);

                        unset($user_from_db['insta_id']);
                        unset($user_from_db['fb_id']);
                        unset($user_from_db['portfolio_url']);
                        unset($user_from_db['created_at']);
                        unset($user_from_db['updated_at']);
                        unset($user_from_db['linkedin']);

                        return response()->json(["message"=>"User found successfully!!!","data"=>$user_from_db,"custom_token"=>$customTokenString,"status"=>true],200);
                    }
                    else
                    {
                        return response()->json(["message"=>"Email Id is not verified","status"=>false],403);
                    }
                }
                else
                {
                    return response()->json(["message"=>"Email Id or password not valid","status"=>false],403);
                }
        } catch (\Throwable $th) {
            return response()->json(["message"=>"Email Id or password not valid","error_message"=>$th->getMessage(),"status"=>false],403);
        }

    }
    public function resendVerificationMail(Request $req)
    {
        $rules = ([
            'email'=>'email|required'
        ]);
        $validation = Validator::make($req->all(),$rules);
        if($validation->fails())
        {
            return response()->json(["message"=>$validation->messages(),"status"=>false],400);
        }
        $auth = app('firebase.auth');
        $test = $auth->sendEmailVerificationLink($req->email);
        // if($test)
        // {
        return response()->json(["message"=>"Email Id verification link send to ".$req->email." also check spam","status"=>true],200);
        // }
        // else
        // {
        //     return response()->json(["message"=>"server busy try again later","status"=>false],503);
        // }


    }

    public function resetPassword(Request $req)
    {
        //get token
        // try {
        //     $auth = app('firebase.auth');
        //     $newPassword = $req->new_password;
        //     $rules = ([
        //         'new_password'=>'min:8|required'
        //     ]);
        //     $customToken = $req->bearerToken();
        //     if($customToken==null)
        //     {
        //         return response()->json(["message"=>"Auth Token not found","status"=>false],Response::HTTP_BAD_REQUEST);
        //     }
        //     $validation = Validator::make($req->all(),$rules);
        //     if($validation->fails())
        //     {
        //         return response()->json(["message"=>$validation->messages(),"status"=>false],400);
        //     }


        //     $signInResult = $auth->signInWithCustomToken($customToken);
        //     if ($signInResult) {
        //         // $user = $auth->getUserByEmail($req->email);
        //         // $updatedUser = $auth->changeUserPassword($signInResult->uid, $newPassword);
        //         // $idTokenString = $signInResult->data()['idToken'];
        //         // $verifiedIdToken = $auth->verifyIdToken($idTokenString);
        //         // $uid = $verifiedIdToken->claims()->get('sub');
        //         // $user = $auth->getUser($uid);

        //         return $signInResult->asTokenResponse();
        //         // return response()->json(["message"=>"Password updated","status"=>true],200);
        //     } else {
        //         return response()->json(["message"=>"Auth Token not valid Try again or re-login","status"=>false],Response::HTTP_BAD_REQUEST);
        //     }



        // } catch (\Throwable $th) {
        //     //throw $th;
        //     return response()->json(["message"=>"Request error","error_message"=>$th->getMessage(),"status"=>false],Response::HTTP_BAD_REQUEST);
        // }
        $token = $req->bearerToken();
        $auth = app('firebase.auth');
        try {
            $verifiedIdToken = $auth->verifyIdToken($token);
        } catch (\Throwable $e) {
            return response()->json(['msg'=>'token expire '.$e->getMessage()], 404);
        }
        $uid = $verifiedIdToken->claims()->get('sub');
        $user = $auth->getUser($uid);

    }

    public function bearerToken()
    {
       $header = $this->header('Authorization', '');
       if (Str::startsWith($header, 'Bearer ')) {
                return Str::substr($header, 7);
       }
    }

}
