<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Logreport;

class LogreportController extends Controller
{
    public function view(){

        $logreport = Logreport::orderBy('id', 'DESC')->paginate(30);

        $data = [];
		// $data['zero_stock'] = $zero_stock;
		$data['heading'] = 'Log Report';
		$data['breadcrumb'] = ['Log Report', '', 'List'];
		$data['logreport']   =$logreport;

        // dd($data['logreport']);

		return view('admin.logreport.index', compact('data'));

    }
}
