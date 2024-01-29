<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;

use App\Models\Logreport;
use Carbon\Carbon;
use Str;




class Authenticate extends Controller
{

    public function login(Request $request)
    {
        try {

            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'email' => 'required|email',
                    'password' => 'required',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                $email = $request->email;
                $password = $request->password;

                $remember = false;
                if ($request->remember_me) {
                    $remember = true;
                }

                //print_r($_POST);exit;

				$authenticated = Auth::attempt([
                    'email' 	=> $email,
					'password'  => $password,
					'status' 	=> 1,
                ],$remember);

                //print_r($authenticated);exxit;

				if ($authenticated) {
					$user = User::where([['email', '=', $email],['status', '=', '1']])->first();

					$userId 	= $user->id;
					$userType 	= $user->role;
					$userEmail 	= $user->email;
					$userName 	= $user->name;

                    $store_id 	= $user->id;
                    if($userType==3){
                        $store_id 	= $user->parent_id;
                    }

                    Session::put('store_id', $store_id);
					Session::put('adminId', $userId);
					Session::put('admin_type', $userType);
					Session::put('admin_email', $userEmail);
					Session::put('admin_userName', $userName);

                    // log table

                    $currentDateTime = Carbon::now();
                    $login_date_time = $currentDateTime->toDateTimeString();
                    $logdata = [
                        'user_id' => $user->id,
                        'user_ip' => $_SERVER['REMOTE_ADDR'],
                        'login_date_time' => $login_date_time,
                        'activity'=> 'Login',
                    ];
                    Logreport::create($logdata);

                    return redirect('admin/dashboard');
                } else {
                    return redirect()->back()->with('error', 'Wrong credentials');
                }
            }

            if(Auth::user()){
                return redirect('admin/dashboard');
            }else{
                return view('auth.authenticate.login');
            }


        } catch (\Exception $e) {
            // dd($e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ');
        }
    }

    public function register(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'full_name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'ph_no' => 'required|unique:users,phone',
                    'password' => 'required|confirmed',
                ], [
                    'confirmed' => 'The password confirmation not matched.'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                $otp = $this->sendMailOtp($request->email);
                // Session::put('email_otp', $otp);
                $arr = $request->all();
                $arr['otp'] = encrypt($otp);
                // dd(encrypt($arr));
                return redirect()->route('auth.email_verification', ['data' => encrypt($arr)]);
                // dd('done');
            }

            // dd($this->generateOtp(6, 4));
            return view('authenticate.register');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }
    public function email_verification(Request $request, $data)
    {
        // dd(decrypt($data));

        $data = decrypt($data);
        return view('authenticate.verifyEmail', compact('data'));
    }
    public function verifyAndRegister(Request $request)
    {
        // dd($request->all());
        try {
            if ($request->user_otp != decrypt($request->otp)) {
                return response(['success' => 0, 'error' => 'Otp mismatched !']);
            } else {
                User::create([
                    'name' => $request->full_name,
                    'email' => $request->email,
                    'phone' => $request->ph_no,
                    'email_verified_at' => date('Y-m-d H:i:s'),
                    'password' => Hash::make($request->password),
                    'role' => 7,
                    'status' => 1,
                ]);
                return response(['success' => 1, 'msg' => 'You have resigtered successfully !']);
            }
        } catch (\Exception $e) {
            return response(['success' => 0, 'error' => 'Something went wrong. Please try later ' . $e->getMessage()]);
        }
    }
    public function email_resend_otp(Request $request)
    {

        $otp = $this->sendMailOtp($request->email);
        $arr['otp'] = encrypt($otp);
        return response(['success' => 1, 'data' => $arr]);
    }
    public function generateOtp()
    {
        do {
            $num = sprintf('%06d', mt_rand(100000, 999999));
        } while (preg_match("~^(\d)\\1\\1\\1|(\d)\\2\\2\\2$|0000~", $num));
        return $num;
    }
    public function sendMailOtp($email)
    {
        $otp = $this->generateOtp();
        Mail::to($email)->send(new EmailVerification($otp));
        return $otp;
    }


	public function permission_denied(){
		return view('auth.authenticate.permission_denied');
	}


    public function logout()
    {


        // log table

        $currentDateTime = Carbon::now();
        $login_date_time = $currentDateTime->toDateTimeString();
        $logdata = [
            'user_id' => Auth::user()->id,
            'user_ip' => $_SERVER['REMOTE_ADDR'],
            'logout_date_time' => $login_date_time,
            'activity'=> 'Logout',
        ];

        // dd($logdata);
        Logreport::create($logdata);

        Session::flush();

        Auth::logout();

        return redirect()->route('auth.login');
    }

    public function forget_password()
    {
        return view('authenticate.forget_pass');
    }

    public function changepassword()
    {

        $data = [];

        $data['heading'] = 'Change Password';
        $data['breadcrumb'] = ['Change Password', 'List'];
        return view('auth.authenticate.changepassword', compact('data'));
    }


    public function save_changepassword(Request $request){

        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user_id=Auth::user()->id;
        $store_data=array(
            'password'  =>  Hash::make($request->password),
        );
        $store=User::where('id', $user_id)->update($store_data);
        return redirect()->back()->with('success', 'Password changed successfully');

    }

    public function forgotpassword(Request $request){
        $email = $request->email;

        $usercheck = User::where('email', $email)->first();

        try {

            if(empty($usercheck)){
                echo 0;
            }else{

                $new_password = Str::random(6);

                $mailsend = \Mail::send('emails.forgotpassword', ['new_password' => $new_password], function($message) use($email){
                    $message->to($email);
                    $message->subject('Forgot Password');
                });


                $store_data=array(
                    'password'  =>  Hash::make($new_password),
                );
                $store=User::where('id', $usercheck->id)->update($store_data);

                echo 1;

            }

        } catch (\Exception $e) {
            return response(['success' => 0, 'error' => 'Something went wrong. Please try later ' . $e->getMessage()]);
        }
    }


    public function changeusername()
    {

        $data = [];

        $user_id=Auth::user()->id;

        $user_details=User::where('id', $user_id)->first();

        $data['heading'] = 'Change Username';
        $data['user_details'] = $user_details;
        $data['breadcrumb'] = ['Change Username', 'List'];
        return view('auth.authenticate.changeusername', compact('data'));
    }

    public function save_changeusername(Request $request){
        $user_id=Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $email_check = User::where('id', '!=', $user_id)->where('email', $request->email)->first();

        if(!empty($email_check)){
            return redirect()->back()->with('warning', 'Username changed successfully');
        }else{
            // echo 1;exit;
            $store_data=array(
                'email'  =>  $request->email,
            );
            $store=User::where('id', $user_id)->update($store_data);
            return redirect()->back()->with('success', 'Username changed successfully');
        }




    }

}
