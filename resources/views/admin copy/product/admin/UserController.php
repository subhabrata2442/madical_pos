<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

use App\User;

use App\Common;
use App\UserDeviceTokenStatus;

use Input;

use Session;

use Carbon;

use Auth;

use Image;

use DB;

use Helpers;

use Hash;

class UserController extends Controller {

	

	protected $userModel;

	

	 public function __construct(){

		$this->userModel	= new User;

		$this->middleware('auth');

        $this->middleware(function ($request, $next) {

        $this->id = Auth::user()->id;

		 Helpers::set_elescope_entries($this->id);

        return $next($request);

       });

    }

	

   
	public function user(){
		$title 			= "Users";
        $breadcumbs 	= "Users";
        $active 		= "user";
		
		$meta_data 		= array();
        $search 		= Input::get('s');
        $cur_page 		= Input::get('pg');
        $cur_page 		= $cur_page == '' ? 1 : $cur_page;
        $per_page		= 20;
        $limit_start	= ($cur_page - 1) * $per_page;
        $param = array();
        $param['search'] 		= $search;
		$param['name']		 	= Input::get('name');
		$param['email']		 	= Input::get('email');
		$param['user_status']	= Input::get('user_status');
        $param['cur_page'] 		= $cur_page;
        $param['per_page'] 		= $per_page;
        $param['limit_start']	= $limit_start;
		
		$user_list = $this->userModel->get_users($param);
		
		//echo '<pre>';print_r($user_list);exit;
		
		return view('admin.users', compact('title','active','breadcumbs','user_list'));
    }
	public function user_old(){

		$title 			= "Users";

        $breadcumbs 	= "Users";

        $active 		= "user";

		

		$meta_data 		= array();

        $search 		= Input::get('s');

        $cur_page 		= Input::get('pg');

        $cur_page 		= $cur_page == '' ? 1 : $cur_page;

        $per_page		= 20;

        $limit_start	= ($cur_page - 1) * $per_page;

        $param = array();

        $param['search'] 		= $search;

		$param['name']		 	= Input::get('name');

		$param['email']		 	= Input::get('email');

        $param['cur_page'] 		= $cur_page;

        $param['per_page'] 		= $per_page;

        $param['limit_start']	= $limit_start;

		

		$user_list = $this->userModel->get_users($param);

		

		//echo '<pre>';print_r($user_list);exit;

		

		return view('admin.users', compact('title','active','breadcumbs','user_list'));

    }

	

	public function user_show($slug){
		 $title 		= "User Details";
		 $breadcumbs 	= "User Details";
		 $active 		= 'user';
		 $param['user_id']	= $slug;
		 $user_info 		= $this->userModel->get_user($param);
		 //echo '<pre>';print_r($user_info['records']['name']);exit;
		 return view('admin.user_details', compact('title','breadcumbs','active','user_info'));
    }

	

	public function deleteUser() {
		$user_id = Input::get('id');
		if ($user_id != null) {
			Common::deleteData('users','id',$user_id);
			Common::deleteData('user_wallet','user_id',$user_id);
			Common::deleteData('user_bit_transaction','user_id',$user_id);
			Common::deleteData('user_bank','user_id',$user_id);
			Common::deleteData('transactions','user_id',$user_id);
			Common::deleteData('balance_request','user_id',$user_id);
			Common::deleteData('withdraw_request','user_id',$user_id);
			Common::deleteData('user_device_token_status','user_id',$user_id);
			Session::flash('success', 'User deleted successfully.');
			}else{
				Session::flash('error', 'Something wrong please try again !');
			}
		return redirect::back();

    }

	public function userInactive() {
		$user_id = Input::get('id');
		if ($user_id != null) {
			Common::updateData($table="users", "id", $user_id, array('status'=>0,'updated_at'=>date('Y-m-d H:i:s')));

			$user_data 		= Common::getSingelData($where=['id'=>$user_id],$table='users',$data=['device_token'],'id','ASC');
			$device_token 	= isset($user_data->device_token)? $user_data->device_token:'';
			

			Common::deleteData('user_device_token_status','user_id',$user_id);
			
			$userData=array(
				'user_id'   	=> $user_id,
				'device_token'	=> $device_token,
				'status'		=> 'inactive',
			);
			UserDeviceTokenStatus::create($userData);

			Session::flash('success', 'User In-active successfully.');
			}else{
				Session::flash('error', 'Something wrong please try again !');
			}
			
		return redirect::back();
    }

	public function userActive() {
		$user_id = Input::get('id');
		if ($user_id != null) {
			Common::updateData($table="users", "id", $user_id, array('status'=>1,'updated_at'=>date('Y-m-d H:i:s')));
			Common::deleteData('user_device_token_status','user_id',$user_id);
			Session::flash('success', 'User Active successfully.');
			}else{
				Session::flash('error', 'Something wrong please try again !');
			}
			
		return redirect::back();
    }

 

	

}