<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SellInwardStock;

class BillController extends Controller
{
    public function bill(){
        $store_id	= Session::get('store_id');

        // $latest_bill = SellInwardStock::where('payment_date', $currentYear)->sum('sub_total');

    }
}
