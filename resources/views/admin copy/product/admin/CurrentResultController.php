<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Session;
use Carbon;
use Auth;
use Image;
use DB;
use Helpers;
use Hash;
use App\Common;
use App\Payments;
use App\Withdraw_request;
use App\Balance_request;
use App\Time_slots;
use App\User_bit_transaction;
use App\Avality_time_date_slots;

class CurrentResultController extends Controller {
	
	protected $paymentsModel;
	protected $withdrawRequestModel;
	protected $balanceRequestModel;
	protected $timeSlotsModel;
	protected $userBitTransactionModel;
	protected $avalityTimeDateSlotsModel;
	
	public function __construct(){
		$this->paymentsModel 				= new Payments;
		$this->withdrawRequestModel 		= new Withdraw_request;
		$this->balanceRequestModel 			= new Balance_request;
		$this->timeSlotsModel 				= new Time_slots;
		$this->userBitTransactionModel 		= new User_bit_transaction;
		$this->avalityTimeDateSlotsModel 	= new Avality_time_date_slots;
    }
	
    public function currentTimeSlotResult($slug){
		
		$title 			= "Time Slot Result";
        $breadcumbs 	= "Time Slot Result";
        $active 		= $slug;
		
		$meta_data 		= array();
        $search 		= Input::get('s');
        $cur_page 		= Input::get('pg');
        $cur_page 		= $cur_page == '' ? 1 : $cur_page;
        $per_page		= 20;
        $limit_start	= ($cur_page - 1) * $per_page;
		
        $param 					= array();
		$param['type'] 			= $slug;
		$param['play_type'] 	= 1;
		$param['current_date'] 	= date('Y-m-d');
        $param['search'] 		= $search;
        $param['cur_page'] 		= $cur_page;
        $param['per_page'] 		= $per_page;
        $param['limit_start']	= $limit_start;
		
		$param2 				= array();
		$param2['type'] 		= $slug;
		$param2['play_type'] 	= 2;
		$param2['current_date'] = date('Y-m-d');
        $param2['search'] 		= $search;
        $param2['cur_page'] 	= $cur_page;
        $param2['per_page'] 	= $per_page;
        $param2['limit_start']	= $limit_start;
		
		$time_slot_result_list = $this->timeSlotsModel->getCurrentTimeSlotResult($param);
		//$time_prev_result_list = $this->timeSlotsModel->getPrevTimeSlotResult($param);
		
		$time_slot_result2_list = $this->timeSlotsModel->getCurrentTimeSlotResult($param2);
		//$time_prev_result2_list = $this->timeSlotsModel->getPrevTimeSlotResult($param2);
		
		//echo '<pre>';print_r($time_slot_result_list);exit;
		
		return view('admin.current_time_slot_result', compact('title','active','breadcumbs','time_slot_result_list','time_slot_result2_list'));
    }
	
	public function transactions_result($play_type,$type,$date_slot,$time_slot){
		
		$title 			= "Transactions Result";
        $breadcumbs 	= "Transactions Result";
        $active 		= $type;
		
		$play_type_id	= 1;
		if(isset($play_type)){
			if($play_type=='single'){
				$play_type_id = 1;
			}else{
				$play_type_id = 2;
			}
		}
		
		$play_type_title='SINGLE';
		if($play_type_id==2){
			$play_type_title='PATTI';
		}
		
		
		
		$meta_data 		= array();
        $search 		= Input::get('s');
        $cur_page 		= Input::get('pg');
        $cur_page 		= $cur_page == '' ? 1 : $cur_page;
        $per_page		= 20;
        $limit_start	= ($cur_page - 1) * $per_page;
        $param = array();
		$param['type'] 			= $type;
		$param['play_type'] 	= $play_type_id;
		$param['date_slot'] 	= $date_slot;
		$param['time_slot'] 	= $time_slot;
        $param['search'] 		= $search;
        $param['cur_page'] 		= $cur_page;
        $param['per_page'] 		= $per_page;
        $param['limit_start']	= $limit_start;
		
		//echo '<pre>';print_r($param);exit;
		
		$game_result = $this->userBitTransactionModel->getCurrentTransactions($param);
				
		//echo '<pre>';print_r($game_result);exit;
		
		return view('admin.user_bit_transactions', compact('title','active','breadcumbs','game_result','play_type_title'));
    }
	
	public function current_transaction_bid_result($play_type,$type,$date_slot,$time_slot){
		
		$title 			= "Transactions Result";
        $breadcumbs 	= "Transactions Result";
        $active 		= $type;
		
		$play_type_id	= 1;
		if(isset($play_type)){
			if($play_type=='single'){
				$play_type_id = 1;
			}else{
				$play_type_id = 2;
			}
		}
		
		$play_type_title='SINGLE';
		if($play_type_id==2){
			$play_type_title='PATTI';
		}
		
		
		
		$meta_data 		= array();
        $search 		= Input::get('s');
        $cur_page 		= Input::get('pg');
        $cur_page 		= $cur_page == '' ? 1 : $cur_page;
        $per_page		= 20;
        $limit_start	= ($cur_page - 1) * $per_page;
        $param = array();
		$param['type'] 			= $type;
		$param['play_type'] 	= $play_type_id;
		$param['date_slot'] 	= $date_slot;
		$param['time_slot'] 	= $time_slot;
        $param['search'] 		= $search;
        $param['cur_page'] 		= $cur_page;
        $param['per_page'] 		= $per_page;
        $param['limit_start']	= $limit_start;
		
		//echo '<pre>';print_r($param);exit;
		
		$game_result = $this->userBitTransactionModel->getCurrentBidTransactions($param);
				
		//echo '<pre>';print_r($game_result);exit;
		
		return view('admin.user_bit_current_transactions', compact('title','active','breadcumbs','game_result','play_type_title'));
    }
	
	public function save_result_request(Request $request){
		$period_id		= Input::get('period_id');
		$type_id		= Input::get('type_id');
		$play_type		= Input::get('play_type');
		$date_slot_id	= Input::get('date_slot_id');
		$time_slot_id	= Input::get('time_slot_id');
		$win_number		= Input::get('win_number');
		
		$validator = Validator::make($request->all(), [
			'win_number'		=> 'required|string|max:255'
        ]);
		$error_html='';
		if($validator->fails()){
			$errors=$validator->errors()->all();
			foreach($errors as $er){
				$error_html .=$er;
			}
        }
		
		$check = Common::getSingelData($where=['period'=>$period_id],$table='win_period',$data=['id'],'id','ASC');
		if($error_html!=''){
			Session::flash('error', $error_html);
		}else{
			if(!empty($check)){
				$data=array(
					'win_number'	=> $win_number,
					'updated_at'	=> date('Y-m-d H:i:s')
				);
				
				Common::updateData($table="win_period", "period", $period_id, $data);
				Session::flash('success', 'Successfully Updated data.');
				
			}else{
				$data=array(
					'win_number'	=> $win_number,
					'period'		=> $period_id,
					'type_id'		=> $type_id,
					'play_type'		=> $play_type,
					'date_slot_id'	=> $date_slot_id,
					'time_slot_id'	=> $time_slot_id,
					'created_at'	=> date('Y-m-d H:i:s')
				);
				//echo '<pre>';print_r($data);exit;
				
				Common::insertData($table="win_period", $data);
				Session::flash('success', 'Successfully Insert data.');
			}
		}
		
        return redirect::back();
	}
	
	
}