<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



class ExpenseController extends Controller
{
    public function category(){
        $data = [];
		// $data['zero_stock'] = $zero_stock;
		$data['heading'] = 'Invoice Wise Purchase List';
		$data['breadcrumb'] = ['Invoice Wise Purchase', '', 'List'];

		return view('admin.expense.category', compact('data'));
    }
}
