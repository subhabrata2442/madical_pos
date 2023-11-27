<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Events\StockAlert;
use Pusher\Pusher;


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
}
