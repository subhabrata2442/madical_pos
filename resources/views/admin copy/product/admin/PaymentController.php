<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;

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
use Config;
use App\User;
use App\Common;
use App\Payments;
use App\Withdraw_request;
use App\Balance_request;
use App\Win_price_calculation;
use App\User_bit_transaction;
use App\Transactions;
use App\Category;
use App\Game_type;

class PaymentController extends Controller {

	protected $paymentsModel;

	protected $withdrawRequestModel;

	protected $balanceRequestModel;

	protected $userTransactionsModel;

	public function __construct(){

		$this->paymentsModel 			= new Payments;

		$this->withdrawRequestModel 	= new Withdraw_request;

		$this->balanceRequestModel 		= new Balance_request;

		$this->userTransactionsModel	= new Transactions;

		$this->middleware('auth');

        $this->middleware(function ($request, $next) {

        $this->id = Auth::user()->id;

		 Helpers::set_elescope_entries($this->id);

        return $next($request);

       });

    }
	
	public function withdraw_request(){
		$title 			= "Withdraw List";
        $breadcumbs 	= "Withdraw List";
        $active 		= "withdraw_request";
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
		//$withdraw_request_list = $this->withdrawRequestModel->get_data2($param);
		$withdraw_request_list = $this->withdrawRequestModel->orderby('id', 'DESC')->offset(0)->limit(300)->get();
		//echo '<pre>';print_r($withdraw_request_list[0]);exit;
		return view('admin.withdraw_request2', compact('title','active','breadcumbs','withdraw_request_list'));

    }

    public function withdraw_request_2(){

		$title 			= "Withdraw List";

        $breadcumbs 	= "Withdraw List";

        $active 		= "withdraw_request";

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

		$withdraw_request_list = $this->withdrawRequestModel->get_data($param);

		//echo '<pre>';print_r($withdraw_request_list);exit;

		return view('admin.withdraw_request', compact('title','active','breadcumbs','withdraw_request_list'));

    }

	public function withdraw_accept_request() {

		$withdraw_id = Input::get('id');
		$admin_id=Auth::user()->id;

		if ($withdraw_id != null) {

			$withdraw_result = $this->withdrawRequestModel->where('id',$withdraw_id)->first();

			$user_id	= isset($withdraw_result->user_id)? $withdraw_result->user_id:'';

			$amount		= isset($withdraw_result->amount)? $withdraw_result->amount:'';

			Common::updateData($table="withdraw_request", "id", $withdraw_id, array('status'=>'completed','admin_id'=>$admin_id,'updated_at'=>date('Y-m-d H:i:s')));

			$transactionsData=array(

				'user_id'   	=> $user_id,

				'description'   => 'Withdraw successfully',

				'amount'		=> $amount,

				'type'			=> 4,

				'status'		=> 'paid',

				'date_slot'		=> date('Y-m-d')

			);

			$this->userTransactionsModel::create($transactionsData);

			$SERVER_API_KEY = Config::get('notification.SERVER_API_KEY');

			$token_list = User::whereNotNull('device_token')->where('id',$user_id)->get()->toArray();

			$msg = array(

				'body'  => 'Amount-'.$amount." .Tap to refresh.",

				'title' => strtoupper('Withdraw successfully')

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

			Session::flash('success', 'Paid successfully.');

		}else{

			Session::flash('error', 'Something wrong please try again !');

		}

        return redirect::back();

    }

	public function withdraw_reject_request() {

		$withdraw_id = Input::get('id');
		$admin_id=Auth::user()->id;

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



			Common::updateData($table="withdraw_request", "id", $withdraw_id, array('status'=>'rejected','admin_id'=>$admin_id,'updated_at'=>date('Y-m-d H:i:s')));

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





	public function balance_request_test(){

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

		$balance_request		= $this->balanceRequestModel->get_data($param);



		//echo '<pre>';print_r($balance_request_list);exit;



		$meta_data['pagination'] = array('per_page' => $per_page, 'cur_page' => $cur_page, 'total_data' => $balance_request['total_orders'], 'page_url' => url('administrator/balance_request_test'), 'additional_params' => '');



		//echo '<pre>';print_r($meta_data);exit;

		return view('admin.balance_request_test', compact('title','active','breadcumbs','meta_data','balance_request'));

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



	public function upi_balance_request(){

		$title 			= "Transfer List";

        $breadcumbs 	= "Transfer List";

        $active 		= "upi_balance_request";

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

		$balance_request_list = $this->balanceRequestModel->get_upi_data($param);

		//echo '<pre>';print_r($balance_request_list);exit;

		return view('admin.balance_upi_request', compact('title','active','breadcumbs','balance_request_list'));

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

				'description'   => 'Amount Credited',

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



	public function upi_request_report(){

		$title 			= "UPI Transfer List";

        $breadcumbs 	= "UPI Transfer List";

        $active 		= "upi_request_report";

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

		$balance_request_list = $this->balanceRequestModel->get_upi_report_data($param);



		$totalAmount=0;

		foreach($balance_request_list['records'] as $row){

			$totalAmount +=$row->amount;

		}

		//echo '<pre>';print_r($totalAmount);exit;

		return view('admin.upi_request_report', compact('title','active','breadcumbs','fromdate','todate','totalAmount','balance_request_list'));

    }



	public function upi_request_details_report(){

		$title 			= "UPI Transfer List";

        $breadcumbs 	= "UPI Transfer List";

        $active 		= "upi_request_details_report";

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

		$balance_request_list = $this->balanceRequestModel->get_upi_report_data($param);



		$totalAmount=0;

		foreach($balance_request_list['records'] as $row){

			$totalAmount +=$row->amount;

		}

		//echo '<pre>';print_r($totalAmount);exit;

		return view('admin.upi_request_details_report', compact('title','active','breadcumbs','fromdate','todate','totalAmount','balance_request_list'));

    }

	

	public function transferReport(){

		$title 			= "Transfer List";

        $breadcumbs 	= "Transfer List";

        $active 		= "transfer_report";

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

		$balance_request_list = $this->balanceRequestModel->get_transfer_report($param);

		

		$totalAmount=0;

		foreach($balance_request_list['records'] as $row){

			$totalAmount +=$row->amount;

		}

		//echo '<pre>';print_r($balance_request_list);exit;

		return view('admin.transfer_report', compact('title','active','breadcumbs','fromdate','todate','totalAmount','balance_request_list'));

    }

	

	public function withdrawReport(){

		$title 			= "Withdraw List";

        $breadcumbs 	= "Withdraw List";

        $active 		= "withdraw_report";

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

		$todate 				= Input::get('todate');

		$param['todate'] 		= Input::get('todate');

        $param['cur_page'] 		= $cur_page;

        $param['per_page'] 		= $per_page;

        $param['limit_start']	= $limit_start;

		$withdraw_request_list = $this->withdrawRequestModel->get_transfer_report($param);

		$totalAmount=0;

		foreach($withdraw_request_list['records'] as $row){

			$totalAmount +=$row->amount;

		}

		//echo '<pre>';print_r($withdraw_request_list);exit;

		return view('admin.withdraw_report', compact('title','active','breadcumbs','fromdate','todate','totalAmount','withdraw_request_list'));

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



		$userlist	= User::where('status',1)->where('user_type',2)->orderBy('id', 'desc')->get();



		//echo '<pre>';print_r($userlist);exit;





		return view('admin.transactions_history', compact('title','active','breadcumbs','transactions_request_list','userlist'));

    }

	public function suspected_report(){
		$title 			= "Suspected List";
        $breadcumbs 	= "Suspected List";
        $active 		= "suspected_report";
		$meta_data 		= array();
        $fromdate 		= Input::get('fromdate');
		$todate 		= Input::get('todate');

		$result=[];

		
		if($fromdate!='' && $todate!=''){
			$fromdate 		= $fromdate.' 00:00:00';
			$todate 		= $todate.' 23:59:59';

			$withdraw_result=Withdraw_request::where('status','completed')->where('created_at', '>=', $fromdate)->where('created_at', '<=', $todate)->groupBy('user_id')->selectRaw('*, sum(amount) as sum')->orderby('created_at', 'DESC')->orderby('id', 'DESC')->get();

			//echo '<pre>';print_r($withdraw_result);exit;

			foreach($withdraw_result as $row){
				$total_withdraw=$row->sum;
				$total_addmoney=Balance_request::where('status','completed')->where('created_at', '>=', $fromdate)->where('created_at', '<=', $todate)->where('user_id',$row->user_id)->sum('amount');
				if($total_withdraw > $total_addmoney ){
					$total_profit=$total_withdraw - $total_addmoney;
					$result[]=array(
						'user_id'			=> $row->user_id,
						'customer_name'		=> $row->getUser->name,
						'customer_phone'	=> $row->getUser->phone,
						'total_withdraw'	=> $total_withdraw,
						'total_addmoney'	=> $total_addmoney,
						'total_profit'		=> $total_profit,
					);
				}
			}

			//echo '<pre>';print_r($result);exit;

		}else{
			$withdraw_result=Withdraw_request::where('status','completed')->groupBy('user_id')->selectRaw('*, sum(amount) as sum')->orderby('created_at', 'DESC')->orderby('id', 'DESC')->get();

			foreach($withdraw_result as $row){
				$total_withdraw=$row->sum;
				$total_addmoney=Balance_request::where('status','completed')->where('user_id',$row->user_id)->sum('amount');
				if($total_withdraw > $total_addmoney ){
					$total_profit=$total_withdraw - $total_addmoney;
					$result[]=array(
						'user_id'			=> $row->user_id,
						'customer_name'		=> $row->getUser->name,
						'customer_phone'	=> $row->getUser->phone,
						'total_withdraw'	=> $total_withdraw,
						'total_addmoney'	=> $total_addmoney,
						'total_profit'		=> $total_profit,
					);
				}
			}
		}

		

		
		//echo '<pre>';print_r($result);exit;

		return view('admin.suspected_report', compact('title','active','breadcumbs','result','fromdate','todate'));
    }
	public function gameReport(){
		$title 			= "Game Report";
        $breadcumbs 	= "Game Report";
        $active 		= "game_report";
		$meta_data 		= array();
        $search 		= Input::get('s');
        $cur_page 		= Input::get('pg');
        $cur_page 		= $cur_page == '' ? 1 : $cur_page;
        $per_page		= 20;
        $limit_start	= ($cur_page - 1) * $per_page;
        $param = array();
        $param['search'] 		= $search;
		$fromdate 				= Input::get('date');
		$cat_id 				= Input::get('cat_id');
		$category_list 			= Category::get();
		
		
		$game_report_result 	= [];
		
		$gross_total_played_amount		= 0;
		$gross_total_played_win_amount	= 0;
		$gross_total_win_amount			= 0;
		
		if($cat_id!='' && $fromdate!=''){
			$single_price_calculation	= Win_price_calculation::where('category_id',$cat_id)->where('play_type',1)->first();
			$patti_price_calculation	= Win_price_calculation::where('category_id',$cat_id)->where('play_type',2)->first();
			$jodi_price_calculation	= Win_price_calculation::where('category_id',$cat_id)->where('play_type',3)->first();
			
			$single_calculation_value	= isset($single_price_calculation->value)?$single_price_calculation->value:'9.5';
			$patti_calculation_value	= isset($patti_price_calculation->value)?$patti_price_calculation->value:'120';
			$jodi_calculation_value		= isset($jodi_price_calculation->value)?$jodi_price_calculation->value:'80';
			
			//echo 'single_calculation_value'.$single_calculation_value.'-patti_calculation_value-'.$patti_calculation_value.'-jodi_calculation_value-'.$jodi_calculation_value;exit;

					
			
			
			$game_result=Game_type::where('category_id',$cat_id)->orderBy('id', 'asc')->get();
			foreach($game_result as $row){
				$total_played_win_amount=0;
				$total_win_amount=0;
				$played_amount = User_bit_transaction::where('category_id',$cat_id)->where('date_slot',$fromdate)->where('time_slot_id',$row->time_slot_id)->orderBy('id', 'desc')->sum('amount');
				$gross_total_played_amount +=$played_amount;
				
				$single_played_win_amount = User_bit_transaction::where('category_id',$cat_id)->where('date_slot',$fromdate)->where('time_slot_id',$row->time_slot_id)->where('play_type',1)->where('win_status','win')->sum('amount');
				$total_played_win_amount +=$single_played_win_amount;
				
				$patti_played_win_amount = User_bit_transaction::where('category_id',$cat_id)->where('date_slot',$fromdate)->where('time_slot_id',$row->time_slot_id)->where('play_type',2)->where('win_status','win')->sum('amount');
				$total_played_win_amount +=$patti_played_win_amount;
				
				$jodi_played_win_amount = User_bit_transaction::where('category_id',$cat_id)->where('date_slot',$fromdate)->where('time_slot_id',$row->time_slot_id)->where('play_type',3)->where('win_status','win')->sum('amount');
				$total_played_win_amount +=$jodi_played_win_amount;
				
				$cp_played_win_amount = User_bit_transaction::where('category_id',$cat_id)->where('date_slot',$fromdate)->where('time_slot_id',$row->time_slot_id)->where('play_type',4)->where('win_status','win')->sum('amount');
				$total_played_win_amount +=$cp_played_win_amount;
				
				$gross_total_played_win_amount +=$total_played_win_amount;
				
				$single_total_win_amount=0;
				$patti_total_win_amount=0;
				$jodi_total_win_amount=0;
				$cp_total_win_amount=0;
				
				if($single_played_win_amount>0){
					$single_total_win_amount=$single_played_win_amount * $single_calculation_value;
					$total_win_amount +=$single_total_win_amount;
				}
				if($patti_played_win_amount>0){
					$patti_total_win_amount=$patti_played_win_amount * $patti_calculation_value;
					$total_win_amount +=$patti_total_win_amount;
				}
				if($jodi_played_win_amount>0){
					$jodi_total_win_amount=$jodi_played_win_amount * $jodi_calculation_value;
					$total_win_amount +=$jodi_total_win_amount;
				}
				if($cp_played_win_amount>0){
					$cp_total_win_amount=$cp_played_win_amount * $patti_calculation_value;
					$total_win_amount +=$cp_total_win_amount;
				}
				
				$gross_total_win_amount +=$total_win_amount;
				
				
				
				
				
				
				//echo 'cat_id'.$cat_id.'-date_slot-'.$fromdate.'-time_slot_id-'.$row->time_slot_id;exit;;
				
				
				
				
				//echo '<pre>';print_r($user_bit_transaction_result);exit;
				$game_report_result[]=array(
					'cat_name'						=> $row->getCategory->name,
					'name'							=> $row->name,
					'time_slot'						=> $row->getTimeslots->from_time.'-'.$row->getTimeslots->to_time,
					'played_amount'					=> $played_amount,
					'total_played_win_amount'		=> $total_played_win_amount,
					'total_win_amount'				=> $total_win_amount,
					'played_win_result'=> array(
						'single_played_win_amount' 	=> $single_played_win_amount,
						'patti_played_win_amount'	=> $patti_played_win_amount,
						'jodi_played_win_amount' 	=> $jodi_played_win_amount,
						'cp_played_win_amount' 		=> $cp_played_win_amount
					),
					'total_win_result'=> array(
						'single_total_win_amount' 	=> $single_total_win_amount,
						'patti_total_win_amount'	=> $patti_total_win_amount,
						'jodi_total_win_amount' 	=> $jodi_total_win_amount,
						'cp_total_win_amount' 		=> $cp_total_win_amount
					),
				);
				
				//echo '<pre>';print_r($game_report_result);exit;	
			}
		}
		
		
		
		//echo '<pre>';print_r($game_report_result);exit;
		return view('admin.game_report', compact('title','active','breadcumbs','fromdate','category_list','game_report_result','gross_total_played_amount','gross_total_played_win_amount','gross_total_win_amount'));
    }

	public function allBetReport(){
		$title 			= "All Bet Report";
        $breadcumbs 	= "All Bet Report";
        $active 		= "all_bet_report";

		$fromdate 		= Input::get('fromdate');
		$todate 		= Input::get('todate');
		$cat_id 		= Input::get('cat_id');
		$category_list 	= Category::get();

		$game_report_result 	= [];
		
		$gross_total_played_amount		= 0;
		$gross_total_played_win_amount	= 0;
		$gross_total_win_amount			= 0;



		if($fromdate!='' && $todate!=''){
			$start_date = date('Y-m-d 00:00:00', strtotime($fromdate));
			$end_date = date('Y-m-d 23:59:59', strtotime($todate));
			$user_bet_played_result = Transactions::whereNotNull('category_id')->where('type',1)->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->select('user_id', DB::raw('sum(amount) as total_played_amount'))->groupBy('user_id')->orderBy('user_id', 'asc')->get();
		}else{
			$user_bet_played_result = []; //Transactions::whereNotNull('category_id')->where('type',1)->select('user_id', DB::raw('sum(amount) as total_played_amount'))->groupBy('user_id')->orderBy('user_id', 'asc')->get();
		}
		
		//echo '<pre>';print_r($user_bet_played_result);exit;
		

		//$user_bet_win_result = Transactions::whereNull('category_id')->where('type',1)->select('user_id', DB::raw('sum(amount) as total_played_amount'))->groupBy('user_id')->orderBy('user_id', 'asc')->get();

		
		
		
		//$result		= DB::table('balance_request')->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->sum('amount');

		$result=[];

		foreach($user_bet_played_result as $row){
			$total_played_amount	= $row->total_played_amount;

			if($fromdate!='' && $todate!=''){
				$start_date = date('Y-m-d 00:00:00', strtotime($fromdate));
				$end_date = date('Y-m-d 23:59:59', strtotime($todate));
				$user_bet_win_amount 	= Transactions::whereNull('category_id')->where('type',1)->where('user_id',$row->user_id)->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->sum('amount');
			}else{
				$user_bet_win_amount 	= Transactions::whereNull('category_id')->where('type',1)->where('user_id',$row->user_id)->sum('amount');
			}

			

			if($user_bet_win_amount>$total_played_amount){
				$played_amount	= round((float)$total_played_amount,2);
				$win_amount		= round((float)$user_bet_win_amount,2);
				$total_profit	= $win_amount-$played_amount;

				$result[]=array(
					'user_id'				=> $row->user_id,
					'customer_name'			=> $row->getUser->name,
					'customer_phone'		=> $row->getUser->phone,
					//'total_played_amount'	=> $total_played_amount,
					'total_played_amount'	=> number_format(round((float)$total_played_amount,2),2),
					//'total_win_amount'		=> $user_bet_win_amount,
					'total_win_amount'		=> number_format(round((float)$user_bet_win_amount,2),2),
					//'total_win_amount3'		=> round((float)$user_bet_win_amount,2),
					'total_profit'			=> number_format(round((float)$total_profit,2),2),
				);

			}
		}

		//echo '<pre>';print_r($result);exit;
		return view('admin.all_bet_report', compact('title','active','breadcumbs','fromdate','todate','result'));
    }

}