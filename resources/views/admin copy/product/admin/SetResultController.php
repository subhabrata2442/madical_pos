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
use App\Game_type;
use DataTables;
use Config;
use App\News_board;
class SetResultController extends Controller {
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
        $param 					= array();
		$param['type'] 			= $slug;
		$param['current_date'] 	= date('Y-m-d');
		$time_slot_result_list = $this->timeSlotsModel->getCurrentTimeSlot($param);
		//echo '<pre>';print_r($time_slot_result_list);exit;
		return view('admin.set_result.time_slot_result', compact('title','active','breadcumbs','time_slot_result_list','slug'));
    }
	public function prevTimeSlotResult(){
		$slug 		= 'main-bajar';
		$title 			= "Time Slot Result";
        $breadcumbs 	= "Time Slot Result";
        $active 		= $slug;
		$current_date	= isset($_GET['date_slot'])?$_GET['date_slot']:date('Y-m-d');
        $param 					= array();
		$param['type'] 			= $slug;
		$param['current_date'] 	= $current_date;
		$time_slot_result_list = $this->timeSlotsModel->getCurrentTimeSlot($param);
		$date_slot_list = [];
		$prev_date_result= DB::table('user_bit_transaction')->select('date_slot_id')->distinct()->orderby('date_slot_id', 'DESC')->take(15)->get();
		foreach($prev_date_result as $row){
			$date_slot_info = DB::table('avality_time_date_slots')->select('date')->where('id',$row->date_slot_id)->first();
			$date_slot = isset($date_slot_info->date)?$date_slot_info->date:'';
			if($date_slot!=''){
				$date_slot_list[] = $date_slot;
			}
		}
		//echo '<pre>';print_r($date_slot_list);exit;
		return view('admin.set_result.prev_time_slot_result', compact('title','active','breadcumbs','time_slot_result_list','date_slot_list','current_date','slug'));
    }
	public function save_result_request(Request $request){
		//echo 'test';exit;
		$SERVER_API_KEY 	= Config::get('notification.SERVER_API_KEY');
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
					$total_win_amount=$payment_gross*100;
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
			// Start Push Notification //
			$game = Game_type::where('id',$request['game_type_id'])->first();
			$token_list = User::whereNotNull('device_token')->get()->toArray();
			$baji_slot_arr=array('1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>1,'10'=>2,'11'=>3,'12'=>4,'13'=>5,'14'=>6,'15'=>7,'16'=>8,'17'=>1,'18'=>2,'19'=>1,'20'=>2);
			$time_slot = DB::table('time_slots')->where('game_type_id',$game_type_id)->where('id',$time_slot_id)->first();
			News_board::create([
				'title'=> strtoupper($game->name.' BAJI '.$baji_slot_arr[$time_slot->id]),
				'content' => "Single - ".$single_win_number." Patti - ".$patti_win_number,
				'status' => 1
			]);
			$msg = array
			(
				'body'  => "Single - ".$single_win_number." Patti - ".$patti_win_number,
				'title' => strtoupper($game->name.' BAJI '.$baji_slot_arr[$time_slot->id]),
			);
			$data = $msg;
            $data['notification_foreground'] = true;
			$fields = array
					(
						'registration_ids'  => array_column($token_list,'device_token'),
						'notification'      => $msg,
						'data'=> $data
					);
			$dataString = json_encode($fields);
			$headers = [
				'Authorization: key=' . $SERVER_API_KEY,
				'Content-Type: application/json',
			];
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
			$response = curl_exec($ch);
			// End Push Notification //
		}
        return redirect::back();
	}
}