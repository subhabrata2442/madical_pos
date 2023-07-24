<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Input;
use Session;
use Carbon;
use Auth;
use Image;
use DB;
use Helpers;
use Hash;
use Config;
use App\User;
use App\Common;
use App\UpiAccounts;

class UpiController extends Controller {
	protected $upiAccountModel;
	public function __construct(){
		$this->upiAccountModel	= new UpiAccounts;
    }
    public function upi(){
		$title 			= "UPI list";
        $breadcumbs 	= "UPI list";
        $active 		= "upi";
		$meta_data 		= array();
        $search 		= Input::get('s');
        $cur_page 		= Input::get('pg');
        $cur_page 		= $cur_page == '' ? 1 : $cur_page;
        $per_page		= 20;
        $limit_start	= ($cur_page - 1) * $per_page;
        $param = array();
        $param['search'] 		= $search;
        $param['cur_page'] 		= $cur_page;
        $param['per_page'] 		= $per_page;
        $param['limit_start']	= $limit_start;

		$upi_list = $this->upiAccountModel->get_data($param);
		//echo '<pre>';print_r($upi_list);exit;
		return view('admin.upi/index', compact('title','active','breadcumbs','upi_list'));
    }
	public function addUpi(){
		$title 			= "Add UPI";
        $breadcumbs 	= "Add UPI";
        $active 		= "upi";
		$data			= [];
		return view('admin.upi/form', compact('title','active','breadcumbs','data'));
    }
	public function editUpi($id){
		$title 			= "Edit UPI";
        $breadcumbs 	= "Edit UPI";
        $active 		= "upi";
		$data = UpiAccounts::where('id',$id)->first();

		return view('admin.upi/form', compact('title','active','breadcumbs','data'));
    }

	public function saveUpiRequest(Request $request){
		$upi_id			= Input::get('upi_id');
		$upi 			= Input::get('upi');
		$name			= Input::get('name');
		$type 			= Input::get('type');
		$key_id 		= Input::get('key_id');
		$key_secret 	= Input::get('key_secret');
		$merchant_id 	= Input::get('merchant_id');
		$merchant_key 	= Input::get('merchant_key');

		$status 		= Input::get('status');

		$validator = Validator::make($request->all(), [
			'type'		=> 'required',
			'name' 		=> 'required',
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
			if($type=='upi'){
				$upiAccount = new UpiAccounts;
				$upiAccount->upi		= $upi;
    			$upiAccount->title 		= $name;
				$upiAccount->key_id		= $upi;
				$upiAccount->key_secret	= $upi;
				$upiAccount->status 	= $status;
				$upiAccount->type		= $type;
    			$upiAccount->save();

			}elseif($type=='razorpay'){
				$upiAccount = new UpiAccounts;
				$upiAccount->upi		= $key_id;
    			$upiAccount->title 		= $name;
				$upiAccount->key_id		= $key_id;
				$upiAccount->key_secret	= $key_secret;
				$upiAccount->status 	= $status;
				$upiAccount->type		= $type;
    			$upiAccount->save();
			}elseif($type=='payu'){
				$upiAccount = new UpiAccounts;
				$upiAccount->upi		= $merchant_id;
    			$upiAccount->title 		= $name;
				$upiAccount->key_id		= $merchant_id;
				$upiAccount->key_secret	= $merchant_key;
				$upiAccount->status 	= $status;
				$upiAccount->type		= $type;
    			$upiAccount->save();
			}

			Session::flash('success', 'Successfully Saved data.');
		}
		return redirect('administrator/upi');
	}

	public function deleteUpi() {
		$upi_id = Input::get('id');
		if ($upi_id != null) {
			$check = DB::table('upi_accounts')->where('status','Y')->whereRaw('FIND_IN_SET('.$upi_id.',id)')->first();
			if(!empty($check)){
				Session::flash('error', 'Can not be deleted as this upi is active !');
			}else{
				Common::deleteData('upi_accounts','id',$upi_id);
				Session::flash('success', 'Successfully deleted data.');
			}
		}else{
			Session::flash('error', 'Something wrong please try again !');
		}
		return redirect::back();
	}

	public function upi_active_request() {
		$upi_id = Input::get('id');
		$upi_status = Input::get('status');
		if ($upi_id != null) {
			if($upi_status=='N'){
				Common::updateData($table="upi_accounts", "id", $upi_id, array('status'=>$upi_status,'updated_at'=>date('Y-m-d H:i:s')));
			}else{
				//Common::updateData($table="upi_accounts", "status", 'Y', array('status'=>'N','updated_at'=>date('Y-m-d H:i:s')));
				Common::updateData($table="upi_accounts", "id", $upi_id, array('status'=>'Y','updated_at'=>date('Y-m-d H:i:s')));
			}
			Session::flash('success', 'Save successfully.');
		}else{
			Session::flash('error', 'Something wrong please try again !');
		}
        return redirect::back();
    }
	public function withdraw_reject_request() {
		$withdraw_id = Input::get('id');
		if ($withdraw_id != null) {
			$withdraw_result = $this->withdrawRequestModel->where('id',$withdraw_id)->first();
			$user_id	= isset($withdraw_result->user_id)? $withdraw_result->user_id:'';
			$amount		= isset($withdraw_result->amount)? $withdraw_result->amount:'';

			$wallet_data 	= Common::getSingelData($where=['user_id'=>$user_id],$table='user_wallet',$data=['balance'],'id','ASC');
			$balance_gross 	= isset($wallet_data->balance)? $wallet_data->balance:'0';
			$wallet_amount = 0;
			$wallet_amount+= $balance_gross;
			$wallet_amount+= $amount;
			Common::updateData($table="user_wallet", "user_id", $user_id, array('balance'=>$wallet_amount,'updated_at'=>date('Y-m-d H:i:s')));

			Common::updateData($table="withdraw_request", "id", $withdraw_id, array('status'=>'rejected','updated_at'=>date('Y-m-d H:i:s')));
			$transactionsData=array(
				'user_id'   	=> $user_id,
				'description'   => 'Withdraw request rejected',
				'amount'		=> $amount,
				'type'			=> 4,
				'status'		=> 'paid',
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
				'title' => strtoupper('Withdraw request rejected')
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
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
			$response = curl_exec($ch);
			Session::flash('success', 'Withdraw request rejected successfully.');
		}else{
			Session::flash('error', 'Something wrong please try again !');
		}
        return redirect::back();
    }
	public function balance_request(){
		$title 			= "Transfer List";
        $breadcumbs 	= "Transfer List";
        $active 		= "balance_request";
		$meta_data 		= array();
        $search 		= Input::get('s');
        $cur_page 		= Input::get('pg');
        $cur_page 		= $cur_page == '' ? 1 : $cur_page;
        $per_page		= 20;
        $limit_start	= ($cur_page - 1) * $per_page;
        $param = array();
        $param['search'] 		= $search;
        $param['cur_page'] 		= $cur_page;
        $param['per_page'] 		= $per_page;
        $param['limit_start']	= $limit_start;
		$balance_request_list = $this->balanceRequestModel->get_data($param);
		//echo '<pre>';print_r($balance_request_list);exit;
		return view('admin.balance_request', compact('title','active','breadcumbs','balance_request_list'));
    }

	public function balance_accept_request() {
		$balance_id = Input::get('id');
		if ($balance_id != null) {
			$payment_data 	= Common::getSingelData($where=['id'=>$balance_id],$table='balance_request',$data=['amount','user_id'],'id','ASC');
			$payment_gross 	= isset($payment_data->amount)? $payment_data->amount:'0';
			//$offer_data 	= Common::getSingelData($where=['price'=>$payment_gross],$table='discount',$data=['discount_amount'],'id','ASC');
			//$offer_gross 	= isset($offer_data->discount_amount)? $offer_data->discount_amount:'0';
			$user_id		= isset($payment_data->user_id)? $payment_data->user_id:'0';
			$wallet_data 	= Common::getSingelData($where=['user_id'=>$user_id],$table='user_wallet',$data=['balance'],'id','ASC');
			$balance_gross 	= isset($wallet_data->balance)? $wallet_data->balance:'0';
			$wallet_amount = 0;
			$wallet_amount+= $balance_gross;
			$wallet_amount+= $payment_gross;
			//$wallet_amount+= $offer_gross;
			//print_r($wallet_amount);exit;
			Common::updateData($table="user_wallet", "user_id", $user_id, array('balance'=>$wallet_amount,'updated_at'=>date('Y-m-d H:i:s')));
			Common::updateData($table="balance_request", "id", $balance_id, array('status'=>'completed','updated_at'=>date('Y-m-d H:i:s')));
			$transactionsData=array(
				'user_id'   	=> $user_id,
				'description'   => 'Add money',
				'amount'		=> $payment_gross,
				'available_bal'	=> $wallet_amount,
				'is_transaction_view'	=> 1,
				'type'			=> 3,
				'status'		=> 'paid',
				'date_slot'		=> date('Y-m-d')
			);
			$this->userTransactionsModel::create($transactionsData);
			$SERVER_API_KEY = Config::get('notification.SERVER_API_KEY');
			$token_list = User::whereNotNull('device_token')->where('id',$user_id)->get()->toArray();
			$msg = array(
				'body'  =>  'Amount-'.$payment_gross." .Tap to refresh.",
				'title' => strtoupper('Balance added successfully')
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
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
			$response = curl_exec($ch);
			Session::flash('success', 'Balance added successfully.');
		}else{
			Session::flash('error', 'Something wrong please try again !');
		}
        return redirect::back();
    }
	public function balance_reject_request() {
		$balance_id = Input::get('id');
		if ($balance_id != null) {
			$payment_data 	= Common::getSingelData($where=['id'=>$balance_id],$table='balance_request',$data=['amount','user_id'],'id','ASC');
			$payment_gross 	= isset($payment_data->amount)? $payment_data->amount:'0';
			//$offer_data 	= Common::getSingelData($where=['price'=>$payment_gross],$table='discount',$data=['discount_amount'],'id','ASC');
			//$offer_gross 	= isset($offer_data->discount_amount)? $offer_data->discount_amount:'0';
			$user_id		= isset($payment_data->user_id)? $payment_data->user_id:'0';
			$wallet_data 	= Common::getSingelData($where=['user_id'=>$user_id],$table='user_wallet',$data=['balance'],'id','ASC');
			$balance_gross 	= isset($wallet_data->balance)? $wallet_data->balance:'0';
			$wallet_amount = 0;
			$wallet_amount+= $balance_gross;
			//$wallet_amount+= $payment_gross;
			//$wallet_amount+= $offer_gross;
			//print_r($wallet_amount);exit;
			//Common::updateData($table="user_wallet", "user_id", $user_id, array('balance'=>$wallet_amount,'updated_at'=>date('Y-m-d H:i:s')));
			Common::updateData($table="balance_request", "id", $balance_id, array('status'=>'rejected','updated_at'=>date('Y-m-d H:i:s')));
			$transactionsData=array(
				'user_id'   	=> $user_id,
				'description'   => 'Balance rejected',
				'amount'		=> $payment_gross,
				'available_bal'	=> $wallet_amount,
				'type'			=> 3,
				'status'		=> 'rejected',
				'date_slot'		=> date('Y-m-d')
			);
			$this->userTransactionsModel::create($transactionsData);
			$SERVER_API_KEY = Config::get('notification.SERVER_API_KEY');
			$token_list = User::whereNotNull('device_token')->where('id',$user_id)->get()->toArray();
			$msg = array(
				'body'  =>  'Amount-'.$payment_gross." .Tap to refresh.",
				'title' => strtoupper('Balance rejected')
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
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
			$response = curl_exec($ch);
			Session::flash('success', 'Balance rejected successfully.');
		}else{
			Session::flash('error', 'Something wrong please try again !');
		}
        return redirect::back();
    }
	public function payment_cancel_request(){
		$title 			= "";
        $breadcumbs 	= "Recharge Account";
        $active 		= "payment";
		return view('admin.payment_cancel', compact('title','active','breadcumbs'));
    }
	public function payment_success_request(){
		$title 			= "";
        $breadcumbs 	= "Payment method";
        $active 		= "payment";
		$payment_unique_id	= Session::get('payment_unique_id');
		if($payment_unique_id!=''){
			$payment_data 	= Common::getSingelData($where=['unique_id'=>$payment_unique_id],$table='payments',$data=['payment_gross'],'payment_id','ASC');
			$payment_gross 	= isset($payment_data->payment_gross)? $payment_data->payment_gross:'0';

			Common::updateData($table="payments", "unique_id", $payment_unique_id, array('status'=>'paid','updated_at'=>date('Y-m-d H:i:s')));
			$user_id		= Session::get('adminId');
			$wallet_data 	= Common::getSingelData($where=['user_id'=>$user_id],$table='user_wallet',$data=['balance'],'id','ASC');
			$balance_gross 	= isset($wallet_data->balance)? $wallet_data->balance:'0';
			$wallet_amount = 0;
			$wallet_amount+= $balance_gross;
			$wallet_amount+= $payment_gross;
			Common::updateData($table="user_wallet", "user_id", $user_id, array('balance'=>$wallet_amount,'updated_at'=>date('Y-m-d H:i:s')));
		}
		return redirect('administrator/pay-success');
    }
	public function payment_success_view(){
		$title 			= "Payment method";
        $breadcumbs 	= "Payment method";
        $active 		= "payment";
		return view('admin.payment_success', compact('title','active','breadcumbs'));
    }
	public function balance_request_report(){
		$title 			= "Transfer List";
        $breadcumbs 	= "Transfer List";
        $active 		= "balance_request_report";
		$meta_data 		= array();
        $search 		= Input::get('s');
        $cur_page 		= Input::get('pg');
        $cur_page 		= $cur_page == '' ? 1 : $cur_page;
        $per_page		= 20;
        $limit_start	= ($cur_page - 1) * $per_page;
        $param = array();
        $param['search'] 		= $search;
		$fromdate 				= Input::get('fromdate');
		$todate 				= Input::get('todate');
		$param['fromdate'] 		= Input::get('fromdate');
		$param['todate'] 		= Input::get('todate');
		$param['status'] 		= 'completed';
        $param['cur_page'] 		= $cur_page;
        $param['per_page'] 		= $per_page;
        $param['limit_start']	= $limit_start;
		$balance_request_list = $this->balanceRequestModel->get_report_data($param);

		$totalAmount=0;
		foreach($balance_request_list['records'] as $row){
			$totalAmount +=$row->amount;
		}
		//echo '<pre>';print_r($totalAmount);exit;
		return view('admin.balance_request_report', compact('title','active','breadcumbs','fromdate','todate','totalAmount','balance_request_list'));
    }
	public function withdraw_request_report(){
		$title 			= "Withdraw List";
        $breadcumbs 	= "Withdraw List";
        $active 		= "withdraw_request_report";
		$meta_data 		= array();
        $search 		= Input::get('s');
        $cur_page 		= Input::get('pg');
        $cur_page 		= $cur_page == '' ? 1 : $cur_page;
        $per_page		= 20;
        $limit_start	= ($cur_page - 1) * $per_page;
        $param = array();
        $param['search'] 		= $search;
		$fromdate 				= Input::get('fromdate');
		$param['fromdate'] 		= Input::get('fromdate');
        $param['cur_page'] 		= $cur_page;
        $param['per_page'] 		= $per_page;
        $param['limit_start']	= $limit_start;
		$withdraw_request_list = $this->withdrawRequestModel->get_report_data($param);
		$totalAmount=0;
		foreach($withdraw_request_list['records'] as $row){
			$totalAmount +=$row->amount;
		}
		//echo '<pre>';print_r($withdraw_request_list);exit;
		return view('admin.withdraw_request_report', compact('title','active','breadcumbs','fromdate','totalAmount','withdraw_request_list'));
    }

	public function transactions_history_report(){
		$title 			= "Transactions history";
        $breadcumbs 	= "Transactions history";
        $active 		= "transactions_history_report";
		$meta_data 		= array();
        $user_id 		= Input::get('user_id');
		$number 		= Input::get('number');
        $param = array();
        $param['user_id']	= '';
		$param['number']	= $number;
		$transactions_request_list = Transactions::get_transaction_history($param);

		$userlist		= User::where('status',1)->where('user_type',2)->orderBy('id', 'desc')->get();

		//echo '<pre>';print_r($userlist);exit;


		return view('admin.transactions_history', compact('title','active','breadcumbs','transactions_request_list','userlist'));
    }
}