<?php

namespace App\Http\Controllers\admin;


use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Common;
use App\Discount as Discount;
use App\Telescope_entries;
use App\Visitor_logs;
use App\User;
use Helpers;
use Session;
use Carbon;
use Auth;
use Input;
use Image;
use Hash;
use PDF;
use DB;


class OrderController extends Controller {
	
	protected $discountModel;
    public function __construct(){
		$this->discountModel 		= new Discount;
    }
	
	
	public function telescope(){
		$title 			= "Telescope";
        $breadcumbs 	= "Telescope";
        $active 		= "telescope";
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
		$entries = Telescope_entries::get_data($param);
		//echo '<pre>';print_r($entries);exit;
		return view('admin.telescope', compact('title','active','breadcumbs','entries'));
    }
	
	public function visitor_log($type){
		$title 			= "Daily Log";
		if($type==1){
			$title 			= "Login Log";
		}
		
        $breadcumbs 	= "Visitor Log";
        $active 		= "visitor_log_".$type;
		
		$entries = Visitor_logs::where('type',$type)->orderby('id', 'DESC')->offset(0)->limit(500)->get();
		return view('admin.visitor_log', compact('title','active','breadcumbs','type','entries'));
    }
	
	public function userBetHistoryInfo(){
		$title 			= "History";
        $breadcumbs 	= "History";
        $active 		= "history";
		
		$search 		= Input::get('ph');
		$cat_id 		= Input::get('cat_id');
		$slot_id 		= Input::get('slot_id');
		$play_type_id 	= Input::get('type_id');
		$date 			= Input::get('date');
		
		
		
		$cur_page 		= Input::get('pg');
        $cur_page 		= $cur_page == '' ? 1 : $cur_page;
        $per_page		= 20;
        $limit_start	= ($cur_page - 1) * $per_page;
		
		$param = array();
        $param['search'] 		= $search;
		$param['cat_id'] 		= $cat_id;
		$param['date_slot'] 	= $date;
		$param['time_slot'] 	= $slot_id;
		
        $param['cur_page'] 		= $cur_page;
        $param['per_page'] 		= $per_page;
        $param['limit_start']	= $limit_start;
		$entries = Telescope_entries::get_user_bet_history($param);
		//echo '<pre>';print_r($entries);exit;
		return view('admin.userBetHistoryInfo', compact('title','active','breadcumbs','entries'));
		
	}
	
	
	
	
	
	public function upload_file_request(Request $request){
		$validator = Validator::make($request->all(), ['attachment_file' => 'required|max:2048']);
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
			 $file 				= $request->file('attachment_file');
			 $originalName		= $file->getClientOriginalName();
			 $destinationPath 	= 'public/upload/messages_files/';
			 $originalPathName	= time().'.'.$file->getClientOriginalExtension();
			 $file->move($destinationPath,$originalPathName);
			 
			 $supported_image = array('gif','jpg','jpeg','png');
			 $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
			 
			 $html='';
			 
			 if (in_array($ext, $supported_image)) {
				 $image_path	= Helpers::message_attechment_image($originalPathName);
				 $html = '<div class="fileAttechmentInner relative"> <img src="'.$image_path.'" alt=""> <a href="javascript:;"><span class="remove_attachment_file">x</span></a> </div><div class="clear"></div>';
				 } else {
					$image_path	= Helpers::message_attechment_file($originalPathName);
					$html = '<div class="fileAttechmentInnerText relative"><a href="'.$image_path.'" target="_blank">'.$originalName.' <i class="fa fa-download" aria-hidden="true"></i></a> <a href="javascript:;"><span class="remove_attachment_file">x</span></a> </div><div class="clear"></div>';
				}
				
			$return_data['success'] = 1; 
		   	$return_data['html'] = $html;
			$return_data['file_path'] = $originalPathName;
			return response()->json([$return_data]);
			 
		}
	}
	
	public function discount(){
		 $title 		= "Discount";
		 $breadcumbs 	= "Discount";
		 $active 		= 'discount';
		 
		 $discount_info 	= $this->discountModel->where('id','=',1)->first();
		 
		 return view('admin.discount', compact('title','breadcumbs','active','discount_info'));
	}
}