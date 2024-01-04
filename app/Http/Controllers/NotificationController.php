<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Events\StockAlert;
use Pusher\Pusher;
use App\Models\Notification;
use App\Models\InwardStockProducts;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class NotificationController extends Controller
{
    public function pushertest(){
        return view('pushertest');
    }

    public function test(){
        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true
        );
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $data =[
            'message' => 'asdasdasdasd',
        ];

        $notify = 'stockalert-channel';
        $pusher->trigger($notify, 'stockalert-event-send-meesages', $data);

        // event(new StockAlert('asdasdasdasd'));


    }

    public function product_expiry_notification(){

        $nearExpiryStock = InwardStockProducts::with('product', 'user')->whereDate('product_expiry_date', '>=', now())
        ->whereDate('product_expiry_date', '<=', now()->addDays(60))
        ->get();



        foreach ($nearExpiryStock as $key => $value) {

            $checkNotification = Notification::where('inwardStock_id', $value->id)->first();



            if(empty($checkNotification)){

                $options = array(
                    'cluster' => env('PUSHER_APP_CLUSTER'),
                    'encrypted' => true
                );
                $pusher = new Pusher(
                    env('PUSHER_APP_KEY'),
                    env('PUSHER_APP_SECRET'),
                    env('PUSHER_APP_ID'),
                    $options
                );

                $message = 'Product expiry, '.$value->product->product_name.' - '.date('Y-m', strtotime(str_replace('.', '/', $value->product_expiry_date)));
                $urls = 'admin/report/near_expiry_stock?id='.$value->id;

                $data =[
                    'message' => $message,
                    'store_id'=>$value->user->id,
                    'urls'=>$urls,
                ];

                $notify = 'stockalert-channel';
                $pusher->trigger($notify, 'stockalert-event-send-meesages', $data);

                $datainsert = [
                    'type'=> 'product-expiry',
                    'store_id'=>$value->user->id,
                    'msg'=> $message,
                    'product_id'=> $value->product->id,
                    'inwardStock_id'=>$value->id,
                    'urls'=>$urls,
                ];
                Notification::create($datainsert);

                echo 1;

            }

        }

    }


    public function seenNotification(Request $request){
        $data = [
            'is_seen'=>'1',
        ];
        Notification::where('id', $request->ids)->update($data);
        // echo 1;
    }

    public function allnotification(){

        $branch_id=Auth::user()->id;
		$user_role=Auth::user()->role;
		if($user_role==1){

            $notification = Notification::orderBy('id', 'DESC')->paginate(20);
        }else{
            $notification = Notification::where('store_id', $branch_id)->orderBy('id', 'DESC')->paginate(20);
        }

        $data = [];
        $data['notification'] = $notification;
        $data['heading'] = 'Brand List';
        $data['breadcrumb'] = ['Brand', 'List'];
        return view('admin.notification', compact('data'));

    }


    public function get_notificationheader(){

        $admin_type = Session::get('admin_type');
        $store_id	= Session::get('store_id');

        if($admin_type==1){
            $pending_s_count =Notification::where('is_seen','0')->count();
            $pending_s_result=Notification::where('is_seen','0')->orderBy('id', 'DESC')->limit(6)->get();
        }else if($admin_type=2){
            $pending_s_count =Notification::where('store_id',$store_id)->where('is_seen','0')->count();
            $pending_s_result=Notification::where('store_id',$store_id)->where('is_seen','0')->orderBy('id', 'DESC')->limit(6)->get();
        }

        $html = '';

        if (count($pending_s_result)>0){

                foreach ($pending_s_result as $itempending_s_result){

                    $urls = route('admin.allnotification');




                    $html .= '<a href="'.$urls.'" onclick="seenNotification(\''.$itempending_s_result->id.'\')" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> '.$itempending_s_result->msg.'
                    </a>';


                }


                $html .= '<a href="'.route('admin.allnotification').'" class="dropdown-item dropdown-footer">See All Notifications</a>';
        }else{

            $html .= '<a href="'.route('admin.allnotification').'" class="dropdown-item dropdown-footer">See All Notifications</a>';
        }


        return response()->json([
            'status' => 1,
            'html'=>$html,
        ]);

    }


}
