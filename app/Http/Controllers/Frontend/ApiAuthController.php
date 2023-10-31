<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\EmailVerificationNotification;
use App\Models\Customer;
use App\Models\Address;
use Carbon\Carbon;
use App\Models\User;
use Validator;
use Hash;
use Str;
use File;
use Storage;
use DB;

class ApiAuthController extends Controller
{
  
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'phone_number' => 'required|unique:users,phone',
        ]);
        if($validator->fails()){
            if($request->name == '' || $request->email == '' || $request->password == '' || $request->phone_number == ''){
                return response()->json(['status' => false, 'message' => 'Please make sure that you fill out all the required fields..', 'data' => []  ], 200);
            }else{
                $errors = $validator->errors();
                if ($errors->has('name')) {
                    return response()->json(['status' => false, 'message' => $errors->first('name'), 'data' => []  ], 200);
                }
                if ($errors->has('email')) {
                    return response()->json(['status' => false, 'message' => $errors->first('email'), 'data' => []  ], 200);
                }
                if ($errors->has('password')) {
                    return response()->json(['status' => false, 'message' => $errors->first('password'), 'data' => []  ], 200);
                }
                if ($errors->has('phone_number')) {
                    return response()->json(['status' => false, 'message' => $errors->first('phone_number'), 'data' => []  ], 200);
                }
                return response()->json(['status' => false, 'message' => 'Something went wrong', 'data' => []  ], 200);
            }
        }

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone_number,
            'password' => Hash::make($request->password),
            'verification_code' => rand(100000, 999999)
        ]);
        $user->save();

        $details = [
            'name' => $request->name,
            'subject' => 'Welcome to '.env('APP_NAME').' - Your Sparkling Journey Begins Here!',
            'body' => " <p> We are thrilled to welcome you to ".env('APP_NAME').", where elegance, style, and beauty come together to adorn your world. Thank you for choosing us as your go-to destination for all things jewelry.</p><br>
            <p>To start exploring, simply log in to your account using the credentials you provided during registration. If you have any questions or need assistance, please don't hesitate to reach out to our customer support team.</p><br>
            <p>We look forward to serving you and being a part of your jewelry story. Happy shopping, and here's to a world adorned with your unique elegance!</p>"
        ];
       
        \Mail::to($request->email)->send(new \App\Mail\SendMail($details));

        $otp = generateOTP($user);

        $data['message'] = generateOTPMessage($user->name, $otp['otp']); 
        $data['phone'] = $user->phone_number;
        
        $sendStatus = sendOTP($data);

        $customer = new Customer;
        $customer->user_id = $user->id;
        $customer->save();

        return response()->json([
            'status' => true,
            'message' => translate('Registration Successful. Please verify your Mobile number.'),
            'data' => $user->id
        ], 200);
    }

    public function login(Request $request){
        $email      = $request->email;
        $password   = $request->password;

        $user = User::whereIn('user_type', ['customer'])->where('email', $email)->first();
        if ($user != null) {
            if (Hash::check($password, $user->password)) {
                return $this->loginSuccess($user);
            } else {
                return response()->json(['status' => false, 'message' => translate('Unauthorized'),'data' => []], 200);
            }
        } else {
            return response()->json(['status' => false, 'message' => translate('User not found'), 'data' => []], 200);
        }
    }

    protected function loginSuccess($user)
    {
        $token = $user->createToken('API Token')->plainTextToken;
        return response()->json([
            'status' => true,
            'message' => translate('Successfully logged in'),
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => null,
            'user' => [
                'id' => $user->id,
                'type' => $user->user_type,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'avatar_original' => api_asset($user->avatar_original),
                'phone' => $user->phone
            ]
        ],200);
    }

    public function loginWithOTP(Request $request){
        $phone = $request->phone;

        $user = User::whereIn('user_type', ['customer'])->where('phone', $phone)->first();
        if ($user != null) {
            $otp = generateOTP($user);

            $data['message'] = generateOTPMessage($user->name, $otp['otp']); 
            $data['phone'] = $phone;

            $sendStatus = sendOTP($data);
            return response()->json([
                                'status' => true,
                                'message' => translate('An OTP has been sent to the provided mobile number. Please check your messages.'),
                                'data' => [
                                    'sent' => $sendStatus ? true : false,
                                    'user_id' => $user->id,
                                    'expiry' => date('Y-m-d H:i:s',strtotime($otp['otp_expiry']))
                                ]
                            ], 200);
        } else {
            return response()->json(['status' => false, 'message' => translate('User not found'), 'data' => []], 200);
        }
    }

    public function verifyOTP(Request $request){
        $user_id = $request->user_id;
        $otp = $request->otp;

        // || !verifyOTP($user,$otp)
        if ($user_id == '' || $otp == '') {
            return response()->json(['status'=>false,'message'=>'Invalid details.','data' => []],200);
        }else{
            $user = User::find($user_id);
            if($user){
                $verify = verifyUserOTP($user, $otp);
                if($verify){
                    return $this->loginSuccess($user);
                }else{
                    return response()->json(['status' => false, 'message' => translate('Invalid or expired OTP.'), 'data' => null], 200);
                }
            }else{
                return response()->json(['status' => false, 'message' => translate('User not found'), 'data' => []], 200);
            }
        }
    }

    public function resendOTP(Request $request){
        $user_id = $request->user_id;

        $user = User::find($user_id);
        if ($user != null) {
            $otp = generateOTP($user);

            $data['message'] = generateOTPMessage($user->name, $otp['otp']); 
            $data['phone'] = $user->phone;

            $sendStatus = sendOTP($data);
            return response()->json([
                                'status' => true,
                                'message' => translate('An OTP has been resend sent to the provided mobile number. Please check your messages.'),
                                'data' => [
                                    'sent' => $sendStatus ? true : false,
                                    'user_id' => $user->id,
                                    'expiry' => date('Y-m-d H:i:s',strtotime($otp['otp_expiry']))
                                ]
                            ], 200);
        } else {
            return response()->json(['status' => false, 'message' => translate('User not found'), 'data' => []], 200);
        }
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => translate('Successfully logged out'),
            'data' => []
        ],200);
    }

    public function user(Request $request)
    {
        $user = User::with(['addresses'])->find($request->user());
                    
        if(isset($user[0])){
            $data['id'] = $user[0]['id'] ?? '';
            $data['name'] = $user[0]['name'] ?? '';
            $data['email'] = $user[0]['email'] ?? '';
            $data['phone'] = $user[0]['phone'] ?? '';
            $dataAddress = $user[0]['addresses'] ?? [];
            $address = [];
            if($dataAddress){
                foreach($dataAddress as $adds){
                    $address[] = [
                        'id'=>$adds['id'],
                        'name'=>$adds['name'],
                        'address'=>$adds['address'],
                        'country_id'=>$adds['country_id'],
                        'country_name'=>$adds['country']['name'],
                        'state_id'=>$adds['state_id'],
                        'state_name'=>$adds['state']['name'],
                        'city_id'=>$adds['city_id'],
                        'city_name'=>$adds['city']['name'],
                        'postal_code'=>$adds['postal_code'],
                        'latitude'=>$adds['latitude'],
                        'longitude'=>$adds['longitude'],
                        'phone'=>$adds['phone'],
                        'is_default'=>$adds['set_default']
                    ];
                }
            }

            $data['address'] = $address;
            return response()->json([ 'status' => true, 'message' => 'Success', 'data' => $data],200);
        }else{
            return response()->json([ 'status' => false, 'message' => 'User details not found.', 'data' => []],200);
        }                                                           
    }

    public function updateProfile(Request $request){
        $id = $request->user()->id;
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|unique:users,email,'.$id,
            'phone_number' => 'nullable|unique:users,phone,'.$id,
        ]);
        
        if($validator->fails()){
            $errors = $validator->errors();
            if ($errors->has('email')) {
                return response()->json(['status' => false, 'message' => $errors->first('email'), 'data' => []  ], 200);
            }
            if ($errors->has('phone_number')) {
                return response()->json(['status' => false, 'message' => $errors->first('phone_number'), 'data' => []  ], 200);
            }
        }
        
        $name   = $request->name;
        $email  = $request->email;
        $phone  = $request->phone_number;
       
        $user = User::find($id);

        $old_phone = $user->phone;
        if($old_phone != $phone){
            $user->is_phone_verified = 0;
        }
        $user->phone = $phone;
        $user->name = $name;
        $user->email = $email;
        $user->save();
        return response()->json(['status' => true,'message' => 'User details updated successfully', 'data' => []],200);
    }

    public function changePassword(Request $request)
    {
        $userId = $request->user()->id;
        $user = User::find($userId);
        if (!Hash::check($request->current_password, $user->password)){
            return response()->json(['status' => false,'message' => 'Old password is incorrect', 'data' => []],200);
        }
 
        // Current password and new password same
        if (strcmp($request->get('current_password'), $request->new_password) == 0){
            return response()->json(['status' => false,'message' => 'New Password cannot be same as your current password.', 'data' => []],200);
        }

        $user->password =  Hash::make($request->new_password);
        $user->save();
        return response()->json(['status' => true,'message' => 'Password Changed Successfully', 'data' => []],200);
    }

    public function addAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'postal_code' => 'required',
            'phone' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['status' => false, 'message' => 'Please make sure that you fill out all the required fields..', 'data' => []  ], 200);
        }

        $userId = $request->user()->id;
        $user = User::find($userId);
        
        if($user){
            $address                = new Address;
            $address->user_id       = $userId;
            $address->name          = $request->name;
            $address->address       = $request->address;
            $address->country_id    = $request->country_id;
            $address->state_id      = $request->state_id;
            $address->city_id       = $request->city_id;
            $address->longitude     = $request->longitude;
            $address->latitude      = $request->latitude;
            $address->postal_code   = $request->postal_code;
            $address->phone         = $request->phone;
            $address->save();
            return response()->json(['status' => true,'message' => 'Address added Successfully', 'data' => []],200);
        }else {
            return response()->json(['status' => false, 'message' => 'User not found', 'data' => []], 200);
        }
    }

    public function updateAddress(Request $request)
    {
        $userId = $request->user()->id;
        $user = User::find($userId);
        $id = $request->address_id;
        if($user){
            $address = Address::findOrFail($id);
            $address->name          = $request->name;
            $address->address       = $request->address;
            $address->country_id    = $request->country_id;
            $address->state_id      = $request->state_id;
            $address->city_id       = $request->city_id;
            $address->longitude     = $request->longitude;
            $address->latitude      = $request->latitude;
            $address->postal_code   = $request->postal_code;
            $address->phone         = $request->phone;
            $address->save();
            return response()->json(['status' => true,'message' => 'Address updated Successfully', 'data' => []],200);
        }else {
            return response()->json(['status' => false, 'message' => 'User not found', 'data' => []], 200);
        }
    }

    public function setDefaultAddress(Request $request){
        $userId = $request->user()->id;
        $user = User::find($userId);
        $id = $request->address_id;
        if($user){
            // Update all addresses to non-default first.
            Address::where('user_id',$userId)->update(['set_default'=>0]);
            // Make the selected address default.
            $address = Address::findOrFail($id);
            $address->set_default = 1;
            $address->save();
            return response()->json(['status' => true,'message' => 'Default address set Successfully', 'data' => []],200);
        }else{
            return response()->json(['status' => false, 'message' => 'User not found', 'data' => []], 200);
        }
    }

    public function deleteAddress(Request $request){
        $userId = $request->user()->id;
        $user = User::find($userId);
        $id = $request->address_id;
        if($user){
            $address = Address::findOrFail($id);
            $address->is_deleted = 1;
            $address->save();
            return response()->json(['status' => true,'message' => 'Address deleted successfully', 'data' => []],200);
        }else{
            return response()->json(['status' => false, 'message' => 'User not found', 'data' => []], 200);
        }
    }
}
