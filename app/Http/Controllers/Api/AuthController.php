<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Client;
use App\Models\Address;
use App\Models\ClientsToken;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LogoutRequest;
use App\Traits\ApiTrait;
use App\Traits\UploadFileTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Mail\ForgotPasswordEmail;
use Illuminate\Support\Facades\Mail;



class AuthController extends Controller
{
    use GeneralTrait, ApiTrait, UploadFileTrait;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'forgotPassword', 'resetNewPassword', 'checkPasswordCode']]);
    }

    public function register(Request $request)
    {

        if ($request->email == null) {
            $useremail = "";
        } else {
            $useremail = $request->email;
            $acount_is_delete = Client::select('id')->where('is_active', 0)->where('email', $useremail)->first();
        }
        if ($request->phone == null) {
            $userphone = "";
        } else {
            $userphone = $request->phone;
            $acount_is_delete = Client::select('id')->where('is_active', 0)->where('phone', $userphone)->first();
        }

        if ($acount_is_delete) {
            $user = Client::where("id", $acount_is_delete->id)->update(['password' => bcrypt($request->password)]);
            $credentials = $request->only(['phone', 'password', 'email']);
            $token = Auth::guard('clients')->attempt($credentials);
            $data['name'] = $request->name;
            $data['email'] = $useremail;
            $data['phone'] = $userphone;
            $data['lang'] = $request->lang;
            $data['address'] = $request->address;
            $data['is_active'] = 1;
            $user = Client::where("id", $acount_is_delete->id)->update($data);
            $data['id'] = $acount_is_delete->id;
            $data['token'] = $token;
        } else {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|between:2,300',
                'email' => 'required_if:key,2|string|email|max:100|unique:clients,email',
                'password' => 'required',
                'phone' => 'required_if:key,1|unique:clients',
                'lang' => 'required',
                'firebase_id' => 'required',
            ]);
            $validator->validate();
            if ($validator->fails()) {
                //return response()->json($validator->errors()->toJson(), 200);
                return $this->returnData('data', '', $validator->errors()->first(), false);
            }
            $user = Client::create(array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            ));
            $credentials = $request->only(['phone', 'password', 'email']);

            $token = Auth::guard('clients')->attempt($credentials);
            if (!$token) {
                return response()->json($validator->errors(), 200);
            }
            if ($user->email == null) {
                $useremail = "";
            } else {
                $useremail = $user->email;
            }
            if ($user->phone == null) {
                $userphone = "";
            } else {
                $userphone = $user->phone;
            }
            $data['name'] = $user->name;
            $data['id'] = $user->id;
            $data['email'] = $useremail;
            $data['phone'] = $userphone;
            $data['lang'] = $request->lang;
            $data['address'] = $request->address;
            $data['token'] = $token;
            // $address = new Address;
            // $address->location                   =$request->address;
            // $address->client_id                  =$user->id;
            // $address->save();
        }
        //return $this->returnData('data',[$data,$address],'success');
        return $this->returnData('data', $data, 'success');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'firebase_id' => 'required|string',
            'password' => 'required|string',
        ]);
        $validator->validate();
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //return $this->returnData('data',$request->all(),'success');



        // if (Auth::guard('clients')->attempt(['phone' => $request->phone, 'password' => $request->password]) || Auth::guard('clients')->attempt(['email' => $request->email, 'password' => $request->password]))
        if (Auth::guard('clients')->attempt(['phone' => $request->phone, 'password' => $request->password]) || Auth::guard('clients')->attempt(['email' => $request->phone, 'password' => $request->password])) {

            $user = Auth::guard('clients')->user();
            $ClientsToken = ClientsToken::updateOrCreate([
                'firebase_id'                => $request->firebase_id,

            ], [
                'client_id'               => $user->id,
            ]);

            // return $this->apiResponse(data: $ClientsToken);


            if ($user->email == null) {
                $useremail = "";
            } else {
                $useremail = $user->email;
            }
            if ($user->phone == null) {
                $userphone = "";
            } else {
                $userphone = $user->phone;
            }


            $mainpassword = $request->password;

            if ($userphone != "") {
                $credentials = ['phone' => $userphone, 'password' => $mainpassword];
                $token = Auth::guard('clients')->attempt($credentials);
                // $token = Client::where('email', $request->email)->orWhere('phone', $request->phone)->first()->createToken('test')->plainTextToken;
            } else if ($useremail != "") {
                $credentials = ['email' => $useremail, 'password' => $mainpassword];
                $token = Auth::guard('clients')->attempt($credentials);
                // $token = Client::where('email', $request->email)->orWhere('phone', $request->phone)->first()->createToken('test')->plainTextToken;
            }
            //$credentials = $request->only(['phone','password','email']);
            //return $this->returnData('data',$credentials,'success');
            if (!$token) {
                return response()->json($validator->errors(), 200);
            }



            $data['cash_back'] = $user->total_point;
            $data['name'] = $user->name;
            $data['id'] = $user->id;
            $data['email'] = $useremail;
            $data['phone'] = $userphone;
            $data['address'] = $user->address;
            $data['lang'] = $request->lang;
            $data['type'] = $user->type;
            $data['token'] = $token;
            //$user->token =$token;

            if ($user->is_active == 0) {
                return $this->returnError('E001', __('Sorry, your account has been deleted'));
            } else {
                return $this->returnData('data', $data, 'success');
            }
        } else {
            return $this->returnError('E001', __('auth.failed'));
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'newpassword'     => 'required',
                'oldpassword'  => 'required',
            ]);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            if (Auth::guard('api')->user()) {
                $id = Auth::guard('api')->user()->id;
                if (Auth::guard('clients')->attempt(['id' => $id, 'password' => $request->oldpassword])) {
                    $user = Auth::guard('clients')->user();
                    if (!$user) {
                        return $this->returnSuccessMessage(__("api.Email doesn't exist"));
                    }

                    $user->update([
                        'password'    => bcrypt($request->newpassword),
                    ]);
                    return $this->returnSuccessMessage(__('Password has been changed successfully'));
                } else {
                    return $this->returnSuccessMessage(__("api.Email doesn't exist"));
                }
            }
        } catch (\Exception $e) {
            return $this->returnError(404, $e->getMessage());
        }
    }

    public function logout(LogoutRequest $request)
    {
        ClientsToken::where(['client_id' => auth('api')->id(), 'firebase_id' => $request->firebase_token])->delete();
        // dd($request->user()
        //     ->tokens);
        //     auth('api')->user()->currentAccessToken()->delete();

        $data['message'] = 'User successfully signed out';
        return $this->returnData('data', $data, 'success');
    }

    public function userProfile()
    {
        try {
            return response()->json(auth()->user());
        } catch (\Exception $e) {
            return $this->returnError(404, $e->getMessage());
        }
    }

    public function sendWebNotification(Request $request)
    {
        $token = 'e4FXS2lXTdSZvdZuxA5a46:APA91bGx0hHJlSRwlp_xuu_xuX0SzaTM1C2kU0KoWv0Pmvh_rN5oJHAM7qkP37kTZLTbrwYDLslBehFt2h5EM-jkDPu_fEMCnbGRRcVBRQ59BefjGSc8l3QeCFhTk3X5vM6MO2qgZvnM';
        $from = "AAAAHRtT4Cg:APA91bHMIG6dzse3vOIROtJftdTTBUY5RINvxNlQeQpawd5MkNt9QKUWgj-Es2OKzf_i1U9XynPbWEdgAvYZP__PeGs25B-f_P_hx6UGHhjgjY0J2P6rZA2VryUqmq2W1x6_o9lUgoLa";
        $msg = array(
            'body'      => $request->body,
            'title'     => $request->title,
            'receiver'  => 'erw',
            'icon'      => 'https://zawdny.amlakyeg.com/public/images/setting/1644508882.png',/*Default Icon*/
            'vibrate'   => 1,
            'sound'     => "http://commondatastorage.googleapis.com/codeskulptor-demos/DDR_assets/Kangaroo_MusiQue_-_The_Neverwritten_Role_Playing_Game.mp3",
        );
        $fields = array(
            'to'        => $token,
            'notification'  => $msg
        );
        $headers = array(
            'Authorization: key=' . $from,
            'Content-Type: application/json'
        );
        //#Send Reponse To FireBase Server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        return response()->json($result);
    }

    public function index()
    {
        return view('notification');
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'client' => auth()->user()
        ]);
    }

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = Client::where('phone', $request->phone)->first();
        if ($user) {
            $code = rand(1111, 9999);
            $update = $user->update(['pin_code' => $code]);
        }
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:clients,email',
        ])->validate();

        $user = Client::where('email', $request->email)->first();

        $code = random_int(111111, 999999);
        $user->update([
            'reset_password_code' => $code
        ]);

        Mail::to($user->email)->send(new ForgotPasswordEmail($code));

        return $this->apiResponse(message: "A verification code has been sent to your email successfully.");
    }

    public function resetNewPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|exists:clients,reset_password_code',
            'password' => 'required|string|min:6|confirmed'
        ])->stopOnFirstFailure()->validate();

        $user = Client::where('reset_password_code', $request->code)->first();
        $user->update([
            'reset_password_code' => null,
            'password' => bcrypt($request->password),
        ]);

        return $this->apiResponse(message: "Password updated successfully. You can now log in with your new password.");
    }


    public function checkPasswordCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|exists:clients,reset_password_code',
        ])->stopOnFirstFailure()->validate();

        return $this->apiResponse(message: "Valid code, please send new password.");
    }
}
