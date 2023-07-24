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
use DataTables;

class GameDateTimeController extends Controller {
	
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
	
    public function index(){
		
		$title 			= "Manage  Date & Time Slot";
        $breadcumbs 	= "Manage Date & Time Slot";
        $active 		= 'game_date_time';
		
		$date_time_slot_list = $this->timeSlotsModel->getDateTimeSlot();
		
		//echo '<pre>';print_r($date_time_slot_list);exit;
		
		return view('admin.game_date_time.date_time_slot', compact('title','active','breadcumbs','date_time_slot_list'));
    }
	
	public function save_result_request(Request $request){
		
		$single_period_id	= Input::get('single_period_id');
		$patti_period_id	= Input::get('patti_period_id');
		$game_type_id		= Input::get('game_type_id');
		$date_slot_id		= Input::get('date_slot_id');
		$time_slot_id		= Input::get('time_slot_id');
		$single_win_number	= Input::get('single_win_number');
		$patti_win_number	= Input::get('patti_win_number');
		
		$validator = Validator::make($request->all(), [
			'single_win_number'		=> 'required|string|max:255',
			//'patti_win_number'		=> 'required|string|max:255'
        ]);
		$error_html='';
		if($validator->fails()){
			$errors=$validator->errors()->all();
			foreach($errors as $er){
				$error_html .=$er;
			}
        }
		
		$userBitTransactionResult=$this->userBitTransactionModel
		->select('*', DB::raw('SUM(amount) as total_amount'))
		->where('period_id',$single_period_id)->where('number',$single_win_number)->where('status',1) ->groupBy('user_id')->get();
		
		foreach($userBitTransactionResult as $row){
			$payment_gross	= $row->total_amount;
			$total_win_amount =0;
			if($payment_gross!=''){
				if($payment_gross>0){
					$total_win_amount=$payment_gross*9;
				}
			}
			
			//print_r($payment_gross.'-'.$total_win_amount);exit;
			$user_id		= $row->user_id;
			
			$wallet_data 	= Common::getSingelData($where=['user_id'=>$user_id],$table='user_wallet',$data=['balance'],'id','ASC');
			$balance_gross 	= isset($wallet_data->balance)? $wallet_data->balance:'0';
			
			$wallet_amount = 0;
			$wallet_amount+= $balance_gross;
			$wallet_amount+= $total_win_amount;
			
			Common::updateData($table="user_wallet", "user_id", $user_id, array('balance'=>$wallet_amount,'updated_at'=>date('Y-m-d H:i:s')));
		}
		
		$userPattiBitTransactionResult=$this->userBitTransactionModel
		->select('*', DB::raw('SUM(amount) as total_amount'))
		->where('period_id',$patti_period_id)->where('number',$patti_win_number)->where('status',1) ->groupBy('user_id')->get();
		
		//echo '<pre>';print_r($userPattiBitTransactionResult);exit;
		
		foreach($userPattiBitTransactionResult as $row){
			$payment_gross	= $row->total_amount;
			$total_win_amount =0;
			if($payment_gross!=''){
				if($payment_gross>0){
					$total_win_amount=$payment_gross*10;
				}
			}
			
			//print_r($payment_gross.'-'.$total_win_amount);exit;
			$user_id		= $row->user_id;
			
			$wallet_data 	= Common::getSingelData($where=['user_id'=>$user_id],$table='user_wallet',$data=['balance'],'id','ASC');
			$balance_gross 	= isset($wallet_data->balance)? $wallet_data->balance:'0';
			
			$wallet_amount = 0;
			$wallet_amount+= $balance_gross;
			$wallet_amount+= $total_win_amount;
			
			Common::updateData($table="user_wallet", "user_id", $user_id, array('balance'=>$wallet_amount,'updated_at'=>date('Y-m-d H:i:s')));
		}
		
		$check = Common::getSingelData($where=['period'=>$single_period_id],$table='win_period',$data=['id'],'id','ASC');
		if($error_html!=''){
			Session::flash('error', $error_html);
		}else{
			if(!empty($check)){
				$single_data=array(
					'win_number'	=> $single_win_number,
					'updated_at'	=> date('Y-m-d H:i:s')
				);
				Common::updateData($table="win_period", "period", $single_period_id, $single_data);
				
				$patti_data=array(
					'win_number'	=> $patti_win_number,
					'updated_at'	=> date('Y-m-d H:i:s')
				);
				Common::updateData($table="win_period", "period", $patti_period_id, $patti_data);
				Session::flash('success', 'Successfully Updated data.');
				
			}else{
				$single_data=array(
					'win_number'	=> $single_win_number,
					'period'		=> $single_period_id,
					'type_id'		=> $game_type_id,
					'play_type'		=> 1,
					'date_slot_id'	=> $date_slot_id,
					'time_slot_id'	=> $time_slot_id,
					'created_at'	=> date('Y-m-d H:i:s')
				);
				Common::insertData($table="win_period", $single_data);
								
				$patti_data=array(
					'win_number'	=> $patti_win_number,
					'period'		=> $patti_period_id,
					'type_id'		=> $game_type_id,
					'play_type'		=> 2,
					'date_slot_id'	=> $date_slot_id,
					'time_slot_id'	=> $time_slot_id,
					'created_at'	=> date('Y-m-d H:i:s')
				);
				Common::insertData($table="win_period", $patti_data);
				
				Session::flash('success', 'Successfully Insert data.');
			}
		}
		
        return redirect::back();
	}
	
	
}