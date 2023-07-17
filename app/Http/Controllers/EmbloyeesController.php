<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\SupplierCompanyMobile;
use App\Models\SupplierBank;
use App\Models\SupplierGst;
use App\Models\SupplierContactDetails;
use App\Models\RolePermission;
use App\Models\RoleWisePermission;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Hash;
use Auth;

class EmbloyeesController extends Controller
{
    public function list(Request $request)
    {
        try {
            $parent_id=Auth::user()->id;
			//$users = User::with('get_role')->where('role',2)->where('parent_id',$parent_id)->orderBy('id', 'desc')->get();
			//echo '<pre>';print_r($users);exit;

            if ($request->ajax()) {
                $users = User::with('get_role')->where('role',2)->where('parent_id',$parent_id)->orderBy('id', 'desc')->get();
                return DataTables::of($users) 
                    ->addColumn('name', function ($row) {
                        return $row->name;
                    })
                    ->addColumn('email', function ($row) {
                        return $row->email;
                    })
                    ->addColumn('ph_no', function ($row) {
                        return $row->phone;
                    }) 
                    ->addColumn('status', function ($row) {
                        $status = '';
                        if ($row->status == 0) {
                            $status = '<span class="badge badge-danger">Inactive</span>';
                        }
                        if ($row->status == 1) {
                            $status = '<span class="badge badge-success">Active</span>';
                        }
                        return $status;
                    })
                    ->addColumn('action', function ($row) {
						// <a class="dropdown-item" href="#" id="delete_user" data-url="' . route('admin.store.delete', [base64_encode($row->id)]) . '">Delete</a>
                        $dropdown = '<a class="dropdown-item" href="' . route('admin.store.edit', [base64_encode($row->id)]) . '">Edit</a>
                        ';
                        if ($row->status == 1) {
                            $dropdown .= '<a class="dropdown-item" id="change_status" href="#" data-status="0"  data-url="' . route('admin.store.changeStatus', [base64_encode($row->id), 0]) . '">Block</a>';
                        } else {
                            $dropdown .= '<a class="dropdown-item" id="change_status" href="#" data-status="1" data-url="' . route('admin.store.changeStatus', [base64_encode($row->id), 1]) . '">Unblock</a>';
                        }
                        $btn = '<div class="dropdown">
                                    <div class="actionList " id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg style="cursor: pointer;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sliders dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>

                                    </div>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        ' . $dropdown . '
                                    </div>
                                </div>
                                ';

                        return $btn;
                    })
                    ->rawColumns(['action','status'])
                    ->make(true);
            }

            $data = [];
            $data['heading'] = 'Store List';
            $data['breadcrumb'] = ['Stores', 'List'];
            return view('admin.embloyees.list', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }

    public function add(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'email' 	=> 'required|email|unique:users,email',
                    'phone' 	=> 'required|numeric|unique:users,phone',
                    'full_name' => 'required',
					//'roll' 		=> 'required',
					'password' 	=> 'required',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

				$store_data=array(
					'name'  		=> $request->full_name,
					'email'  		=> $request->email,
					'phone'			=> $request->phone,
					'password'		=>  Hash::make($request->password),
					'role'   		=> 2,
					'status'		=> 1,
				);
				//echo '<pre>';print_r($store_data);exit;
				
				$store=User::create($store_data);
				$store_id=$store->id;

				if(isset($request->roll)){
					if(count($request->roll)>0){	
						RoleWisePermission::where('branch_id',$store_id)->delete();
						for($i=0;count($request->roll)>$i;$i++){
							$store_roll_data=array(
								'branch_id'  		=> $store_id,
								'role_id'  			=> 2,
								'permission_id'  	=> $request->roll[$i],
							);
							//echo '<pre>';print_r($store_roll_data);exit;
							RoleWisePermission::create($store_roll_data);
						}
					}
				}
				return redirect()->back()->with('success', 'Store Added successfully');
            }
            $data = [];

            $data['heading'] = 'Embloyees Add';
            $data['breadcrumb'] = ['Embloyees', 'Add'];
			$data['role']= RolePermission::where('status',1)->orderBy('id', 'asc')->get();

			//echo '<pre>';print_r($data);exit;
			
            return view('admin.embloyees.add', compact('data'));
        } catch (\Exception $e) {
            $return_data['success'] = 0;
			$return_data['error_message'] = 'Something went wrong. Please try later. ' . $e->getMessage();
        }
    }
    
    public function edit(Request $request, $id)
    {
        try {
            $store_id = base64_decode($id);
			if ($request->isMethod('post')) {
				
			$validator = Validator::make($request->all(), [
				'email' 	=> 'required|email|unique:users,email,' . $store_id,
				'phone' 	=> 'required|numeric|unique:users,phone,' . $store_id,
				'full_name' => 'required',
			]);
			
			if($request->password!=''){
				$validator = Validator::make($request->all(), [
				'password' => 'min:6|required_with:password_confirm|same:password_confirm',
				]);
			}
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			$array = [
				'name' 	=> $request->full_name,
				'email' => $request->email,
				'phone' => $request->phone
			];

			//print_r($array);exit;

			
			if($request->password!=''){
				$array['password']=Hash::make($request->password_confirm);
			}

			User::find($store_id)->update($array);

			if(isset($request->roll)){
				if(count($request->roll)>0){
					RoleWisePermission::where('branch_id',$store_id)->delete();
					for($i=0;count($request->roll)>$i;$i++){
						$store_roll_data=array(
							'branch_id'  		=> $store_id,
							'role_id'  			=> 2,
							'permission_id'  	=> $request->roll[$i]
						);
						//echo '<pre>';print_r($store_roll_data);exit;
						RoleWisePermission::create($store_roll_data);
					}
				}
			}
			return redirect()->back()->with('success', 'Store Updated successfully');
        }
            
            $data = [];
            $data['heading'] 	= 'Embloyees Edit';
            $data['breadcrumb'] = ['Embloyees', 'Edit'];
            $data['store'] 	= User::find($store_id);
			$data['role']= RolePermission::where('status',1)->orderBy('id', 'asc')->get();
			$data['roleWisePermission']= RoleWisePermission::where('branch_id',$store_id)->orderBy('id', 'asc')->get();

			//echo '<pre>';print_r($data['roleWisePermission']);exit;
						
            return view('admin.embloyees.edit', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }
    public function delete($id)
    {
        try {
            $id = base64_decode($id);
            Supplier::find($id)->delete();
			SupplierCompanyMobile::where('supplier_id',$id)->delete();
			SupplierBank::where('supplier_id',$id)->delete();
			SupplierGst::where('supplier_id',$id)->delete();
			SupplierContactDetails::where('supplier_id',$id)->delete();
			
            return redirect()->back()->with('success', 'Supplier deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }

	public function change_status($id, $status)
    {
        try {
            $id = base64_decode($id);
            User::find($id)->update([
                'status' => $status
            ]);
            if ($status == 0)
                return redirect()->back()->with('success', 'Store blocked successfully');
            else
                return redirect()->back()->with('success', 'Store unblocked successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }
}