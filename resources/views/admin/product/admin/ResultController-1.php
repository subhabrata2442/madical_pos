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
use App\User_wallet;
use App\Payments;
use App\Withdraw_request;
use App\Balance_request;
use App\Time_slots;
use App\Category;
use App\Play_type;
use App\Game_type;
use App\Transactions;
use App\User_bit_transaction;
use App\Avality_time_date_slots;
use App\Win_price_calculation;
use Config;
class ResultController extends Controller {
	protected $paymentsModel;
	protected $withdrawRequestModel;
	protected $balanceRequestModel;
	protected $timeSlotsModel;
	protected $userBitTransactionModel;
	protected $avalityTimeDateSlotsModel;
	protected $userTransactionsModel;
	public function __construct(){
		$this->paymentsModel 				= new Payments;
		$this->withdrawRequestModel 		= new Withdraw_request;
		$this->balanceRequestModel 			= new Balance_request;
		$this->timeSlotsModel 				= new Time_slots;
		$this->userBitTransactionModel 		= new User_bit_transaction;
		$this->avalityTimeDateSlotsModel 	= new Avality_time_date_slots;
		$this->userTransactionsModel		= new Transactions;
		$this->middleware('auth');
        $this->middleware(function ($request, $next) {
        $this->id = Auth::user()->id;
		 Helpers::set_elescope_entries($this->id);
        return $next($request);
       });
		
		
		
		
    }
	
	
	/*+++++++++++++++++++++++Dev+++++++++++++++++++++++++++++++++++*/
	public function dev_game_result(){
		$title 			= 'Total Bet';
        $breadcumbs 	= 'Total Bet';
        $active 		= 'showbid';
		$meta_data 		= array();
        $search 		= Input::get('s');
		$cat_id 		= Input::get('cat_id');
		$slot_id 		= Input::get('slot_id');
		$play_type_id 	= Input::get('type_id');
		$date 			= Input::get('date');
        $cur_page 		= Input::get('pg');
		$current_date 	= '';
		if(isset($_GET['date'])){
			if($date!=''){
				$current_date = $date;
			}
		}
        $cur_page 		= $cur_page == '' ? 1 : $cur_page;
        $per_page		= 20;
        $limit_start	= ($cur_page - 1) * $per_page;
        $param 					= array();
		$param['search'] 		= $search;
		$param['cat_id'] 		= $cat_id;
		$param['slot_id'] 		= $slot_id;
		$param['play_type_id'] 	= $play_type_id;
		$param['current_date'] 	= $current_date;
        $param['cur_page'] 		= $cur_page;
        $param['per_page'] 		= $per_page;
        $param['limit_start']	= $limit_start;
		$result 				= $this->userBitTransactionModel->getDevTotalSingleBidResult($param);
		
		
		echo '<pre>';print_r($param);exit;
		return view('admin.playhistory/showbid2', compact('title','active','breadcumbs','category_list','type_list','time_slots','result','current_date'));
		
		
		
    }
	
	/*+++++++++++++++++++++++Dev+++++++++++++++++++++++++++++++++++*/
	
	
	
	
	
	public function showbiddetails(){
		$title 			= 'Total Bet';
        $breadcumbs 	= 'Total Bet';
        $active 		= 'showbid';
		$meta_data 		= array();
        $search 		= Input::get('s');
		$cat_id 		= Input::get('cat_id');
		$slot_id 		= Input::get('slot_id');
		$play_type_id 	= Input::get('type_id');
		$date 			= Input::get('date');
        $cur_page 		= Input::get('pg');
		$current_date 	= '';
		if(isset($_GET['date'])){
			if($date!=''){
				$current_date = $date;
			}
		}
        $cur_page 		= $cur_page == '' ? 1 : $cur_page;
        $per_page		= 20;
        $limit_start	= ($cur_page - 1) * $per_page;
        $param 					= array();
		$param['search'] 		= $search;
		$param['cat_id'] 		= $cat_id;
		$param['slot_id'] 		= $slot_id;
		$param['play_type_id'] 	= $play_type_id;
		$param['current_date'] 	= $current_date;
        $param['cur_page'] 		= $cur_page;
        $param['per_page'] 		= $per_page;
        $param['limit_start']	= $limit_start;
		$result 				= $this->userBitTransactionModel->getTotalSingleBidResult($param);
		$category_list 			= Category::get();
		$type_list     			= Play_type::get();
		$time_slots				= [];
		if($cat_id!=''){
			$time_slots	= Time_slots::where('category_id',$cat_id)->get();
		}
		//echo '<pre>';print_r($result);exit;
		return view('admin.playhistory/showbid2', compact('title','active','breadcumbs','category_list','type_list','time_slots','result','current_date'));
		
		
		
    }
	public function showbid(){
		$title 			= 'Total Bet';
        $breadcumbs 	= 'Total Bet';
        $active 		= 'showbid';
		$meta_data 		= array();
        $search 		= Input::get('s');
		$cat_id 		= Input::get('cat_id');
		$slot_id 		= Input::get('slot_id');
		$play_type_id 	= Input::get('type_id');
		$date 			= Input::get('date');
        $cur_page 		= Input::get('pg');
		$current_date 	= '';
		if(isset($_GET['date'])){
			if($date!=''){
				$current_date = $date;
			}
		}
        $cur_page 		= $cur_page == '' ? 1 : $cur_page;
        $per_page		= 20;
        $limit_start	= ($cur_page - 1) * $per_page;
        $param 					= array();
		$param['search'] 		= $search;
		$param['cat_id'] 		= $cat_id;
		$param['slot_id'] 		= $slot_id;
		$param['play_type_id'] 	= $play_type_id;
		$param['current_date'] 	= $current_date;
        $param['cur_page'] 		= $cur_page;
        $param['per_page'] 		= $per_page;
        $param['limit_start']	= $limit_start;
		$result 				= $this->userBitTransactionModel->getTotalBidResult($param);
		$category_list 			= Category::get();
		$type_list     			= Play_type::get();
		$time_slots				= [];
		if($cat_id!=''){
			$time_slots	= Time_slots::where('category_id',$cat_id)->get();
		}
		//echo '<pre>';print_r($result);exit;
		return view('admin.playhistory/showbid', compact('title','active','breadcumbs','category_list','type_list','time_slots','result','current_date'));
    }
	public function history(){
		$title 			= 'Bet History';
        $breadcumbs 	= 'Bet History';
        $active 		= 'history';
		$meta_data 		= array();
        $search 		= Input::get('s');
		$cat_id 		= Input::get('cat_id');
		$date 			= Input::get('date');
        $cur_page 		= Input::get('pg');
		$current_date 	= '';
		if(isset($_GET['date'])){
			if($date!=''){
				$current_date = $date;
			}
		}else{
			$current_date = date('Y-m-d');
		}
        $cur_page 		= $cur_page == '' ? 1 : $cur_page;
        $per_page		= 20;
        $limit_start	= ($cur_page - 1) * $per_page;
        $param 					= array();
		$param['search'] 		= $search;
		$param['cat_id'] 		= $cat_id;
		$param['current_date'] 	= $current_date;
        $param['cur_page'] 		= $cur_page;
        $param['per_page'] 		= $per_page;
        $param['limit_start']	= $limit_start;
		$result 				= $this->userBitTransactionModel->getHistoryResult($param);
		$category_list 			= Category::get();
		$type_list     			= Play_type::get();
		$time_slots				= [];
		if($cat_id!=''){
			$time_slots	= Time_slots::where('category_id',$cat_id)->get();
		}
		//echo '<pre>';print_r($result);exit;
		return view('admin.playhistory/history', compact('title','active','breadcumbs','category_list','type_list','time_slots','result','current_date'));
    }
	
	/*public function history(){
		$title 			= 'Bet History';
        $breadcumbs 	= 'Bet History';
        $active 		= 'history';
		$meta_data 		= array();
        $search 		= Input::get('s');
		$cat_id 		= Input::get('cat_id');
		$slot_id 		= Input::get('slot_id');
		$play_type_id 	= Input::get('type_id');
		$date 			= Input::get('date');
        $cur_page 		= Input::get('pg');
		$current_date 	= '';
		if(isset($_GET['date'])){
			if($date!=''){
				$current_date = $date;
			}
		}else{
			$current_date = date('Y-m-d');
		}
        $cur_page 		= $cur_page == '' ? 1 : $cur_page;
        $per_page		= 20;
        $limit_start	= ($cur_page - 1) * $per_page;
        $param 					= array();
		$param['search'] 		= $search;
		$param['cat_id'] 		= $cat_id;
		$param['slot_id'] 		= $slot_id;
		$param['play_type_id'] 	= $play_type_id;
		$param['current_date'] 	= $current_date;
        $param['cur_page'] 		= $cur_page;
        $param['per_page'] 		= $per_page;
        $param['limit_start']	= $limit_start;
		$result 				= $this->userBitTransactionModel->getHistoryResult($param);
		$category_list 			= Category::get();
		$type_list     			= Play_type::get();
		$time_slots				= [];
		if($cat_id!=''){
			$time_slots	= Time_slots::where('category_id',$cat_id)->get();
		}
		//echo '<pre>';print_r($result);exit;
		return view('admin.playhistory/history', compact('title','active','breadcumbs','category_list','type_list','time_slots','result','current_date'));
    }*/
	public function indivisual(){
		$title 			= 'Indivisual Bet History';
        $breadcumbs 	= 'Indivisual Bet History';
        $active 		= 'indivisual';
		$meta_data 		= array();
        $search 		= Input::get('s');
		$cat_id 		= Input::get('cat_id');
		$number 		= Input::get('number');
		$date 			= Input::get('date');
		$todate 		= Input::get('todate');
        $cur_page 		= Input::get('pg');
		$current_date 	= '';
		if(isset($_GET['date'])){
			if($date!=''){
				$current_date = $date;
			}
		}
        $cur_page 		= $cur_page == '' ? 1 : $cur_page;
        $per_page		= 20;
        $limit_start	= ($cur_page - 1) * $per_page;
        $param 					= array();
		$param['search'] 		= $search;
		$param['cat_id'] 		= $cat_id;
		$param['number'] 		= $number;
		$param['current_date'] 	= $current_date;
		$param['todate'] 		= $todate;
        $param['cur_page'] 		= $cur_page;
        $param['per_page'] 		= $per_page;
        $param['limit_start']	= $limit_start;
		$result 				= $this->userBitTransactionModel->getIndivisualResult($param);
		$category_list 			= Category::get();
		$type_list     			= Play_type::get();
		$time_slots				= [];
		if($cat_id!=''){
			$time_slots	= Time_slots::where('category_id',$cat_id)->get();
		}
		
		//$userWalletInfo=User_wallet::where('user_id',2)->first();
		//$walletBalance=isset($userWalletInfo->balance)?$userWalletInfo->balance:'0';
		
		
		//echo '<pre>';print_r($result);exit;
		return view('admin.playhistory/indivisual', compact('title','active','breadcumbs','category_list','type_list','time_slots','result','current_date','todate'));
    }
	
	public function userBetHistory(){
		$title 			= 'User Bet History';
        $breadcumbs 	= 'User Bet History';
        $active 		= 'user_bet_history';
		$meta_data 		= array();
        $search 		= Input::get('s');
		$cat_id 		= Input::get('cat_id');
		$slot_id 		= Input::get('slot_id');
		$play_type_id 	= Input::get('type_id');
		$phone_number 	= Input::get('phone_number');
		$date 			= Input::get('date');
        $cur_page 		= Input::get('pg');
		$current_date 	= '';
		if(isset($_GET['date'])){
			if($date!=''){
				$current_date = $date;
			}
		}else{
			$current_date = date('Y-m-d');
		}
        $cur_page 		= $cur_page == '' ? 1 : $cur_page;
        $per_page		= 20;
        $limit_start	= ($cur_page - 1) * $per_page;
        $param 					= array();
		$param['search'] 		= $search;
		$param['cat_id'] 		= $cat_id;
		$param['slot_id'] 		= $slot_id;
		$param['play_type_id'] 	= $play_type_id;
		$param['phone_number'] 	= $phone_number;
		$param['current_date'] 	= $current_date;
        $param['cur_page'] 		= $cur_page;
        $param['per_page'] 		= $per_page;
        $param['limit_start']	= $limit_start;
		$result 				= $this->userBitTransactionModel->getUserBetHistoryResult($param);
		$category_list 			= Category::get();
		$type_list     			= Play_type::get();
		$time_slots				= [];
		if($cat_id!=''){
			$time_slots	= Time_slots::where('category_id',$cat_id)->get();
		}
		
		$game_status_login=Session::get('game_bet_history_login');
		$is_login='N';
		if($game_status_login=='Y'){
			$is_login='Y';
		}
		//echo '<pre>';print_r($is_login);exit;
		
		return view('admin.playhistory/userBetHistory', compact('title','active','breadcumbs','category_list','type_list','time_slots','result','current_date','is_login'));
    }
	
	public function deleteUserBet() {
		//echo 'ff';exit;
		$bet_id = Input::get('id');
		if ($bet_id != null) {
			$check = Common::getSingelData($where=['id'=>$bet_id],$table='user_bit_transaction',$data=['*'],'id','ASC');
			$bid_number	= isset($check->number)? $check->number:'';
			$user_id	= isset($check->user_id)? $check->user_id:'';
			$amount		= isset($check->amount)? $check->amount:'';
			
			$wallet_data 	= Common::getSingelData($where=['user_id'=>$user_id],$table='user_wallet',$data=['balance'],'id','ASC');
			$balance_gross 	= isset($wallet_data->balance)? $wallet_data->balance:'0';
			$wallet_amount = 0;
			$wallet_amount+= $balance_gross;
			$wallet_amount+= $amount;
			
			Common::updateData($table="user_wallet", "user_id", $user_id, array('balance'=>$wallet_amount,'updated_at'=>date('Y-m-d H:i:s')));
			Common::deleteData('user_bit_transaction','id',$bet_id);
			
			$transactionsData=array(
				'user_id'   	=> $user_id,
				'description'   => 'Cancel bid request, no.' .$bid_number,
				'amount'		=> $amount,
				'type'			=> 1,
				'status'		=> 'paid',
				'is_transaction_view'	=> 1,
				'date_slot'		=> date('Y-m-d')
			);
			$this->userTransactionsModel::create($transactionsData);


			$transactionsData=array(
				'user_id'   	=> $user_id,
				'description'   => 'Amount Created',
				'amount'		=> $amount,
				'available_bal'	=> $wallet_amount,
				'type'			=> 4,
				'status'		=> 'paid',
				'is_transaction_view'	=> 1,
				'date_slot'		=> date('Y-m-d')
			);
			$this->userTransactionsModel::create($transactionsData);
			
			$SERVER_API_KEY = Config::get('notification.SERVER_API_KEY');
			$token_list = User::whereNotNull('device_token')->where('id',$user_id)->get()->toArray();
			$msg = array(
				'body'  => 'Amount-'.$amount." .Tap to refresh.",
				'title' => strtoupper('Accepted cancel bid request')
			);
			$data = $msg;
            $data['notification_foreground'] = true;
			$data['priceRefresh'] = 1;
			$fields = array(
				'registration_ids'  => array_column($token_list,'device_token'),
				'notification'      => $msg,
				'data'				=> $data
			);
			$dataString = json_encode($fields);
			$headers = [
				'Authorization: key=' . $SERVER_API_KEY,
				'Content-Type: application/json',
			];
			/*$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
			$response = curl_exec($ch);*/
			
			
			//print_r($wallet_amount);exit;
			
			
			
			
			
			
			Session::flash('success', 'Date deleted successfully.');
        }else{
			Session::flash('error', 'Something wrong please try again !');
		}
        return redirect::back();
    }
	
	
	
	public function dailyReport(){
		$title 			= 'Daily Report';
        $breadcumbs 	= 'Daily Report';
        $active 		= 'daily';
		$meta_data 		= array();
        $search 		= Input::get('s');
		$cat_id 		= Input::get('cat_id');
		$date 			= Input::get('date');
        $cur_page 		= Input::get('pg');
		$current_date 	= '';
		if(isset($_GET['date'])){
			if($date!=''){
				$current_date = $date;
			}
		}else{
			$current_date = date('Y-m-d');
		}
        $cur_page 		= $cur_page == '' ? 1 : $cur_page;
        $per_page		= 20;
        $limit_start	= ($cur_page - 1) * $per_page;
        $param 					= array();
		$param['search'] 		= $search;
		$param['cat_id'] 		= $cat_id;
		$param['current_date'] 	= $current_date;
        $param['cur_page'] 		= $cur_page;
        $param['per_page'] 		= $per_page;
        $param['limit_start']	= $limit_start;
		$result 				= $this->userBitTransactionModel->getDailyHistoryResult($param);
		$category_list 			= Category::get();
		$type_list     			= Play_type::get();
		$time_slots				= [];
		if($cat_id!=''){
			$time_slots	= Time_slots::where('category_id',$cat_id)->get();
		}
		
		//Session::put('game_status_login', 'N');
		
		$game_status_login=Session::get('game_status_login');
		
		$is_login='N';
		if($game_status_login=='Y'){
			$is_login='Y';
		}

		//echo '<pre>';print_r($result);exit;
		return view('admin.playhistory/daily_history',compact('title','active','breadcumbs','category_list','type_list','time_slots','result','current_date','is_login'));
    }
	
	public function monthlyReport(){
		$title 			= 'Monthly Report';
        $breadcumbs 	= 'Monthly Report';
        $active 		= 'monthly';
		$meta_data 		= array();
        $search 		= Input::get('s');
		$cat_id 		= Input::get('cat_id');
		$fromdate 		= Input::get('fromdate');
		$todate 		= Input::get('todate');
        $cur_page 		= Input::get('pg');
		
		
        $cur_page 		= $cur_page == '' ? 1 : $cur_page;
        $per_page		= 20;
        $limit_start	= ($cur_page - 1) * $per_page;
        $param 					= array();
		$param['search'] 		= $search;
		$param['cat_id'] 		= $cat_id;
		$param['fromdate'] 		= $fromdate;
		$param['todate'] 		= $todate;
        $param['cur_page'] 		= $cur_page;
        $param['per_page'] 		= $per_page;
        $param['limit_start']	= $limit_start;
		$result 				= $this->userBitTransactionModel->getMonthlyHistoryResult($param);
		$category_list 			= Category::get();
		$type_list     			= Play_type::get();
		$time_slots				= [];
		if($cat_id!=''){
			$time_slots	= Time_slots::where('category_id',$cat_id)->get();
		}
		$game_status_login=Session::get('game_status_login');
		
		$is_login='N';
		if($game_status_login=='Y'){
			$is_login='Y';
		}
		//echo '<pre>';print_r($result);exit;
		return view('admin.playhistory/monthly_history', compact('title','active','breadcumbs','category_list','type_list','time_slots','result','fromdate','todate','is_login'));
    }
	
	public function gameStatusLogin(Request $request){
		$password	= Input::get('password');
		$oldPass	= 'Dip@123';
		if($password==$oldPass){
			Session::put('game_status_login', 'Y');
			$return_data['success'] = 1;
		}else{
			$return_data['success'] = 0;
		}
		echo json_encode($return_data);
	}
	
	public function gameBetStatusLogin(Request $request){
		$password	= Input::get('password');
		$oldPass	= 'Dipbet@123456';
		if($password==$oldPass){
			Session::put('game_bet_history_login', 'Y');
			$return_data['success'] = 1;
		}else{
			$return_data['success'] = 0;
		}
		echo json_encode($return_data);
	}
	
	
	
	
	
	public function currentTimeSlotResult($id){
		$title 			= 'Result';
        $breadcumbs 	= 'Result';
		$category_info 	= Category::where('id',$id)->first();
		$active 		= isset($category_info->slug)?$category_info->slug:'result';
		$category_id	= $id;
		$meta_data 		= array();
        $search 		= Input::get('s');
        $cur_page 		= Input::get('pg');
        $cur_page 		= $cur_page == '' ? 1 : $cur_page;
        $per_page		= 20;
        $limit_start	= ($cur_page - 1) * $per_page;
        $param 					= array();
		$param['search'] 		= $search;
		$param['cat_id'] 		= $id;
        $param['cur_page'] 		= $cur_page;
        $param['per_page'] 		= $per_page;
        $param['limit_start']	= $limit_start;
		$result		= Time_slots::getTimeSlotResult($param);
		$type_list  = Play_type::get();
		
		$totalSlot=isset($result[0]['time_slot'])?count($result[0]['time_slot']):'0';
		
		//echo '<pre>';print_r($result);exit;
		return view('admin.set_result/index', compact('title','active','breadcumbs','result','type_list','category_id'));
    }
	public function save_result_request(Request $request){
		
		$category_id	= Input::get('category_id');
		$win_number		= Input::get('win_number');
		$slot_period	= Input::get('period_id');
		$type			= Input::get('type_id');
		$game_id		= Input::get('game_id');
		$date_slot		= Input::get('date_slot');
		$time_slot_id	= Input::get('time_slot_id');
		$time_id		= Input::get('time_id');
		echo '<pre>';print_r($_POST);exit;
		/*$validator = Validator::make($request->all(), [
			"win_number.*"=> "required|string|min:0"
        ]);*/
		$validator = Validator::make($request->all(), [
			'category_id'	=> 'required',
			'time_slot_id' 	=> 'required',
        ]);
		
		if($validator->fails()){
			$errors=$validator->errors()->all();
			$error_html='';
			foreach($errors as $er){
				$error_html .='<span>'.$er.'</span></br>';
			}
			Session::flash('error', $error_html);
			return redirect::back();
        }else{
			
			$baziResult 		= Game_type::where('id', $game_id)->where('category_id', $category_id)->first();
			$bazi_title			= isset($baziResult->name)?$baziResult->name:'';
			$categotyResult 	= Category::where('id', $category_id)->first();
			$played_game_title	= isset($categotyResult->name)?$categotyResult->name:'';
			
			$description='Won '.$played_game_title.' '.$bazi_title;
			
			
			
			
			//echo '<pre>';print_r($description);exit;
			for($i=0;count($slot_period)>$i;$i++){
				$period_id	= $slot_period[$i];
				$type_id	= $type[$i];
				$number		= $win_number[$i];
				
				if($number!=''){
					$price_calculation	= Win_price_calculation::where('category_id',$category_id)->where('play_type',$type_id)->first();
					
					if($type_id==4){
						$price_calculation	= Win_price_calculation::where('category_id',$category_id)->where('play_type',2)->first();
					}
					
					$calculation_type	= isset($price_calculation->calculation_type)?$price_calculation->calculation_type:1;
					$calculation_value	= isset($price_calculation->value)?$price_calculation->value:'9.5';
					$userBitTransactionResult	= $this->userBitTransactionModel->select('*', DB::raw('SUM(amount) as total_amount'))->where('period_id',$period_id)->where('number',$number)->where('status',1) ->groupBy('user_id')->get();
					foreach($userBitTransactionResult as $row){
						$payment_gross	= $row->total_amount;
						$total_win_amount =0;
						if($payment_gross!=''){
							if($payment_gross>0){
								if($calculation_type==1){
									$total_win_amount=$payment_gross*$calculation_value;
								}elseif($calculation_type==2){
									$percent = $calculation_value;
									$discount_value = ($payment_gross / 100) * $percent;
									$total_win_amount = $payment_gross + $discount_value;
								}
							}
						}
						
						$play_type_result=Play_type::where('id',$type_id)->first();
						$play_type_title=isset($play_type_result->name)? $play_type_result->name:'';
						
						$description='Won '.$played_game_title.' '.$bazi_title.' '.$play_type_title.' '.$number.'('.$total_win_amount.')';
						//print_r($description);exit;
						
						$user_id		= $row->user_id;
						$wallet_data 	= Common::getSingelData($where=['user_id'=>$user_id],$table='user_wallet',$data=['balance'],'id','ASC');
						$balance_gross 	= isset($wallet_data->balance)? $wallet_data->balance:'0';
						$wallet_amount = 0;
						$wallet_amount+= $balance_gross;
						$wallet_amount+= $total_win_amount;
						Common::updateData($table="user_wallet", "user_id", $user_id, array('balance'=>$wallet_amount,'updated_at'=>date('Y-m-d H:i:s')));
						
						$transactionsData=array(
							'user_id'   	=> $user_id,
							'description'   => $description,
							'amount'		=> $total_win_amount,
							'available_bal'	=> $wallet_amount,
							'type'			=> 1,
							'status'		=> 'paid',
							'is_transaction_view'	=> 1,
							'date_slot'		=> date('Y-m-d')
						);
						
						Transactions::create($transactionsData);	
						
					}
					$check = Common::getSingelData($where=['period'=>$period_id],$table='win_period',$data=['id','win_number'],'id','ASC');
					if(!empty($check)){
						$userBitTransactionResult	= $this->userBitTransactionModel->select('*', DB::raw('SUM(amount) as total_amount'))->where('period_id',$period_id)->where('number',$check->win_number)->where('status',1) ->groupBy('user_id')->get();
						foreach($userBitTransactionResult as $row){
							$payment_gross	= $row->total_amount;
							$total_win_amount =0;
							if($payment_gross!=''){
								if($payment_gross>0){
									if($calculation_type==1){
										$total_win_amount=$payment_gross*$calculation_value;
									}elseif($calculation_type==2){
										$percent = $calculation_value;
										$discount_value = ($payment_gross / 100) * $percent;
										$total_win_amount = $payment_gross + $discount_value;
									}
								}
							}
							$user_id		= $row->user_id;
							$wallet_data 	= Common::getSingelData($where=['user_id'=>$user_id],$table='user_wallet',$data=['balance'],'id','ASC');
							$balance_gross 	= isset($wallet_data->balance)? $wallet_data->balance:'0';
							$wallet_amount = 0;
							$wallet_amount+= $balance_gross;
							$wallet_amount-= $total_win_amount;
							Common::updateData($table="user_wallet", "user_id", $user_id, array('balance'=>$wallet_amount,'updated_at'=>date('Y-m-d H:i:s')));
							
							
							$transactionsData=array(
								'user_id'   			=> $user_id,
								'description'   		=> 'Amount Debited',
								'amount'				=> $total_win_amount,
								'available_bal'			=> $wallet_amount,
								'type'					=> 1,
								'is_transaction_view'	=> 1,
								'date_slot'				=> date('Y-m-d')
							);
							Transactions::create($transactionsData);
							
							
							
						}
						$win_data=array(
							'win_number'	=> $number,
							'updated_at'	=> date('Y-m-d H:i:s')
						);
						Common::updateData($table="win_period", "period", $period_id, $win_data);
					}else{
						
						if($type_id=='3'){
							$cctime_slot_id	= DB::table('time_slots')->where('id', '<', $time_slot_id)->max('id');
							$ccgame_id		= DB::table('game_type')->where('id', '<', $game_id)->max('id');
							$win_data=array(
								'period'		=> $period_id,
								'category_id'	=> $category_id,
								'game_id'		=> $ccgame_id,
								'play_type'		=> $type_id,
								'date_slot'		=> $date_slot,
								'time_slot_id'	=> $cctime_slot_id,
								'win_number'	=> $number,
								'created_at'	=> date('Y-m-d H:i:s')
							);
							Common::insertData($table="win_period", $win_data);	
						}else{
							$win_data=array(
								'period'		=> $period_id,
								'category_id'	=> $category_id,
								'game_id'		=> $game_id,
								'play_type'		=> $type_id,
								'date_slot'		=> $date_slot,
								'time_slot_id'	=> $time_slot_id,
								'win_number'	=> $number,
								'created_at'	=> date('Y-m-d H:i:s')
							);
							Common::insertData($table="win_period", $win_data);
						}
						
						
					}
					$this->userBitTransactionModel::where('period_id', $period_id)->update(['win_status' =>'loss']);
					$this->userBitTransactionModel::where('period_id', $period_id)->where('number', $number)->update(['win_status' =>'win']);
				}
			}
			
			
			if($category_id==3){
				$win_result_data=array(
				'single'	=> $win_number[0],
				'patti'		=> $win_number[1],
				);
				
				$ch1 = curl_init();
				$headers1 = array(
					'Accept: application/json',
					'Content-Type: application/json'
				);
				
				$curlurl='https://ffbombay.com/result_create_api.php?game=FFMAX&patti='.$win_number[1].'&single='.$win_number[0].'&date='.$date_slot.'&time_id='.$time_id;
				$curldata=array(
					'ip'			=> 'curl',
					'url'			=> $curlurl,
					'created_at'	=> date('Y-m-d H:i:s')
				);
				Common::insertData('telescope_entries',$curldata);
				
				$result_data_payload = json_encode($win_result_data);
				curl_setopt($ch1, CURLOPT_URL, 'https://ffbombay.com/result_create_api.php?game=FFMAX&patti='.$win_number[1].'&single='.$win_number[0].'&date='.$date_slot.'&time_id='.$time_id);
				curl_setopt($ch1, CURLOPT_POST, true);
				curl_setopt($ch1, CURLOPT_POSTFIELDS, $result_data_payload);
				curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers1);
				curl_setopt($ch1, CURLOPT_HEADER, 0);
				curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch1, CURLOPT_TIMEOUT, 300);
				$info = curl_exec($ch1);
				curl_close ($ch1);
			}
			if($category_id==4){
				$win_result_data=array(
				'single'	=> $win_number[0],
				'patti'		=> $win_number[1],
				);
				
				$ch1 = curl_init();
				$headers1 = array(
					'Accept: application/json',
					'Content-Type: application/json'
				);
				
				$curlurl='https://ffbombay.com/result_create_api.php?game=FFBOMBAY&patti='.$win_number[1].'&single='.$win_number[0].'&date='.$date_slot.'&time_id='.$time_id;
				$curldata=array(
					'ip'			=> 'curl',
					'url'			=> $curlurl,
					'created_at'	=> date('Y-m-d H:i:s')
				);
				Common::insertData('telescope_entries',$curldata);
				
				$result_data_payload = json_encode($win_result_data);
				curl_setopt($ch1, CURLOPT_URL, 'https://ffbombay.com/result_create_api.php?game=FFBOMBAY&patti='.$win_number[1].'&single='.$win_number[0].'&date='.$date_slot.'&time_id='.$time_id);
				curl_setopt($ch1, CURLOPT_POST, true);
				curl_setopt($ch1, CURLOPT_POSTFIELDS, $result_data_payload);
				curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers1);
				curl_setopt($ch1, CURLOPT_HEADER, 0);
				curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch1, CURLOPT_TIMEOUT, 300);
				$info = curl_exec($ch1);
				curl_close ($ch1);
			}
			if($category_id==7){
				$win_result_data=array(
				'single'	=> $win_number[0],
				'patti'		=> $win_number[1],
				);
				
				$ch1 = curl_init();
				$headers1 = array(
					'Accept: application/json',
					'Content-Type: application/json'
				);
				
				$curlurl='https://ffbombay.com/result_create_api.php?game=FFNAGA&patti='.$win_number[1].'&single='.$win_number[0].'&date='.$date_slot.'&time_id='.$time_id;
				$curldata=array(
					'ip'			=> 'curl',
					'url'			=> $curlurl,
					'created_at'	=> date('Y-m-d H:i:s')
				);
				Common::insertData('telescope_entries',$curldata);
				
				$result_data_payload = json_encode($win_result_data);
				curl_setopt($ch1, CURLOPT_URL, 'https://ffbombay.com/result_create_api.php?game=FFNAGA&patti='.$win_number[1].'&single='.$win_number[0].'&date='.$date_slot.'&time_id='.$time_id);
				curl_setopt($ch1, CURLOPT_POST, true);
				curl_setopt($ch1, CURLOPT_POSTFIELDS, $result_data_payload);
				curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers1);
				curl_setopt($ch1, CURLOPT_HEADER, 0);
				curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch1, CURLOPT_TIMEOUT, 300);
				$info = curl_exec($ch1);
				curl_close ($ch1);
			}
			
			
			
			
			
			
			$SERVER_API_KEY = Config::get('notification.SERVER_API_KEY');
			$game 		= Game_type::where('id',$game_id)->first();
			$category 	= Category::where('id',$category_id)->first();
			$title      = $category->name.' '.$game->name;
			
			$item_per_page	= 500;
			$total_records = User::where('device_token','!=','null')->where('user_type',2)->count();
			$v = ($total_records/$item_per_page);
			if($v > (int)$v)
			$total_pages = (int)$v + 1;
			else
			$total_pages = (int)$v;
			
			if($win_number[2]!=''){
				$msg = array(
					'body'  => " Single ~ ".$win_number[0]." Patti ~ ".$win_number[1]." Jodi ~ ".$win_number[2]." CP ~ ".$win_number[3],
					'title' => strtoupper($title)
				);
			}else{
				$msg = array(
					'body'  => " Single ~ ".$win_number[0]." Patti ~ ".$win_number[1]." CP ~ ".$win_number[3],
					'title' => strtoupper($title)
				);
			}
			
			$data = $msg;
			
			$headers = [
					'Authorization: key=' . $SERVER_API_KEY,
					'Content-Type: application/json',
				];
			
			
			
			for($i=0;$total_pages>$i;$i++){
				$limit_start	= $i* $item_per_page;
				$token_list = User::select('device_token','id')->where('device_token','!=','null')->offset($limit_start)->limit($item_per_page)->orderBy('id', 'asc')->get()->toArray();
				
				$data['notification_foreground'] = true;
				$fields = array(
					'registration_ids'  => array_column($token_list,'device_token'),
					'notification'      => $msg,
					'data'=> $data
				);
				$dataString = json_encode($fields);
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
				$response = curl_exec($ch);
				curl_close($ch);
			}
		}
		Session::flash('success', 'Successfully Saved data.');
		return redirect::back();
	}	
}