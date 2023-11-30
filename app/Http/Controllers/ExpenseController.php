<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ExpenseCategory;
use App\Models\Expense;
use App\Models\User;

use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Auth;



class ExpenseController extends Controller
{
    public function category(){
        $data = [];
		// $data['zero_stock'] = $zero_stock;
		$data['heading'] = 'Expense Category';
		$data['breadcrumb'] = ['Expense Category', '', 'List'];
		$data['category']   =ExpenseCategory::orderBy('id', 'DESC')->paginate(10);

		return view('admin.expense.category', compact('data'));
    }

	public function add(Request $request){
		$validator = Validator::make($request->all(), [
			'category_name' => 'required',
		]);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$insert_data=array(
			'category_name'  => $request->category_name,
		);

		if($request->id!=''){
			$data_insert=ExpenseCategory::where('id', $request->id)->update($insert_data);
			return redirect()->back()->with('success', 'category updated successfully');
		}else{
			$data_insert=ExpenseCategory::create($insert_data);
			return redirect()->back()->with('success', 'category created successfully');
		}
	}

	public function delete($id)
    {
        try {
            $id = base64_decode($id);
            ExpenseCategory::find($id)->delete();
            return redirect()->back()->with('success', 'Category deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }

	public function expenselist(Request $request){
		$data = [];
		$data['heading'] = 'Expense List';
		$data['breadcrumb'] = ['Expense List', '', 'List'];


		$branch_id=Auth::user()->id;
		$user_role=Auth::user()->role;
		if($user_role==1){
			$expenserec = Expense::with('expensecategory', 'user')->orderBy('id', 'DESC');
		}else{
			$expenserec = Expense::with('expensecategory', 'user')->orderBy('id', 'DESC')->where('branch_id',$branch_id);
		}

		if(!empty($request->get('start_date')) && !empty($request->get('end_date'))){
			if($request->get('start_date') == $request->get('end_date')){
				$expenserec->whereDate('expense_date', $request->get('start_date'));
			}else{
				$expenserec->whereBetween('expense_date', [$request->get('start_date'), $request->get('end_date')]);
			}
		}

		if(!empty($request->get('category_id'))){
			$expenserec->where('category_id', $request->get('category_id'));
		}

		if(!empty($request->get('branch_id'))){
			$expenserec->where('branch_id', $request->get('branch_id'));
		}

		$data['expenselist'] = $expenserec->paginate(20);
		$data['totalexpense'] =$expenserec->sum('amount');
		$data['category']   =ExpenseCategory::orderBy('id', 'DESC')->get();
		$data['storelist']  = User::with('get_role')->where('role',2)->where('parent_id',0)->orderBy('id', 'desc')->get();

		return view('admin.expense.expenselist', compact('data'));
	}

	public function addexpense(Request $request){
		try {
			$created_by=Auth::user()->id;

            if (Auth::user()->role == 1){
                $store_id = $request->branch_id;
            }else{
                $store_id = Session::get('store_id');
            }


            if ($request->isMethod('post')) {
				//dd($request->all());
                $validator = Validator::make($request->all(), [
					'category_id' 		=> 'required',
					'amount' 		=> 'required',
                    'expense_date' 		=> 'required',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }


				$insert_data=array(
					'category_id'	=> $request->category_id,
					'amount'		=> $request->amount,
					'branch_id'		=> $store_id,
					'notes'			=> $request->notes,
                    'created_by'	=> $created_by,
                    'expense_date'	=> $request->expense_date,
				);
				$data_insert=Expense::create($insert_data);

				return redirect()->back()->with('success', 'Expense Added successfully');

			}

			$data = [];
            $data['heading'] 		= 'Expense Add';
            $data['breadcrumb'] 	= ['Expense', 'Add'];
			$data['category']   =ExpenseCategory::orderBy('id', 'DESC')->get();
            $data['storelist']  = User::with('get_role')->where('role',2)->where('parent_id',0)->orderBy('id', 'desc')->get();

			return view('admin.expense.addexpense', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
	}

	public function expenseedit($id, Request $request){
		try {
			$created_by=Auth::user()->id;

            if (Auth::user()->role == 1){
                $store_id = $request->branch_id;
            }else{
                $store_id = Session::get('store_id');
            }

			$expance_id = base64_decode($id);
            if ($request->isMethod('post')) {
				//dd($request->all());
                $validator = Validator::make($request->all(), [
					'category_id' 		=> 'required',
					'amount' 		=> 'required',
                    'expense_date' 		=> 'required',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }


				$insert_data=array(
					'category_id'	=> $request->category_id,
					'amount'		=> $request->amount,
					'notes'			=> $request->notes,
                    'expense_date'  => $request->expense_date,
                    'branch_id'		=> $store_id,
				);
				$data_insert=Expense::where('id', $expance_id)->update($insert_data);

				return redirect()->back()->with('success', 'Expense updated successfully');

			}

			$data = [];
            $data['heading'] 		= 'Expense Add';
            $data['breadcrumb'] 	= ['Expense', 'Add'];
			$data['category']   = ExpenseCategory::orderBy('id', 'DESC')->get();
			$data['expances']   = Expense::where('id', $expance_id)->first();
            $data['storelist']  = User::with('get_role')->where('role',2)->where('parent_id',0)->orderBy('id', 'desc')->get();

			return view('admin.expense.editexpense', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
	}

	public function expensdelete($id)
    {
        try {
            $id = base64_decode($id);
            Expense::find($id)->delete();
            return redirect()->back()->with('success', 'Expense deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }

}
