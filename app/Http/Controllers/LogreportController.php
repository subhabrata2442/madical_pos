<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Logreport;
use Illuminate\Support\Facades\Session;
use Auth;

class LogreportController extends Controller
{
    public function view(){

        $admin_type = Session::get('admin_type');
        $branch_id=Auth::user()->id;

        if($admin_type==1){
            $logreport = Logreport::orderBy('id', 'DESC')->paginate(40);
        }else{
            $logreport = Logreport::where('user_id', $branch_id)->orderBy('id', 'DESC')->paginate(40);
        }

        $data = [];
		// $data['zero_stock'] = $zero_stock;
		$data['heading'] = 'Log Report';
		$data['breadcrumb'] = ['Log Report', '', 'List'];
		$data['logreport']   =$logreport;

        // dd($data['logreport']);

		return view('admin.logreport.index', compact('data'));

    }
}
