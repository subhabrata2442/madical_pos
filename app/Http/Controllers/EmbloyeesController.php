<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\StoreDetails;
use App\Models\RolePermission;
use App\Models\RoleWisePermission;
use App\Models\UserRolePermission;
use App\Models\RoleSubPermission;
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
                $users = User::with('get_role')->where('parent_id',$parent_id)->orderBy('id', 'desc')->get();
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
                        $adminId=Auth::user()->id;
						$page_permission=array();
						$roleWisePermissionResult = RoleWisePermission::where('branch_id',$adminId)->where('role_id',6)->orderBy('id', 'asc')->get();
						foreach($roleWisePermissionResult as $prow){
							$page_permission[]=$prow->get_slug->slug;
						}
                        $dropdown ='';
                        if(in_array('admin-embloyees-edit', $page_permission)){
							$dropdown .= '<a class="dropdown-item" href="' . route('admin.embloyees.edit', [base64_encode($row->id)]) . '">Edit</a>';
						}
                        if(in_array('admin-embloyees-delete', $page_permission)){
							$dropdown .= '<a class="dropdown-item" href="#" id="delete_user" data-url="' . route('admin.embloyees.delete', [base64_encode($row->id)]) . '">Delete</a>';
						}
						// <a class="dropdown-item" href="#" id="delete_user" data-url="' . route('admin.store.delete', [base64_encode($row->id)]) . '">Delete</a>

                        if ($row->status == 1) {
                            $dropdown .= '<a class="dropdown-item" id="change_status" href="#" data-status="0"  data-url="' . route('admin.embloyees.changeStatus', [base64_encode($row->id), 0]) . '">Block</a>';
                        } else {
                            $dropdown .= '<a class="dropdown-item" id="change_status" href="#" data-status="1" data-url="' . route('admin.embloyees.changeStatus', [base64_encode($row->id), 1]) . '">Unblock</a>';
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
            $parent_id=Auth::user()->id;
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'email' 	=> 'required|email|unique:users,email',
                    'phone' 	=> 'required|numeric|unique:users,phone',
                    'full_name' => 'required',
					//'roll' 		=> 'required',
					// 'password' 	=> 'required',

                    'password' => 'required|confirmed',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $parent_id=Auth::user()->id;
				$store_data=array(
					'name'  		=> $request->full_name,
					'email'  		=> $request->email,
					'phone'			=> $request->phone,
					'password'		=>  Hash::make($request->password),
                    'parent_id'     => $parent_id,
					'role'   		=> 3,
					'status'		=> 1,
				);
				//echo '<pre>';print_r($store_data);exit;

				$store=User::create($store_data);
				$store_id=$store->id;

                $store_details_data=array(
					'store_id'  		=> $store_id,
					'contact_email'  	=> $request->email,
					'address'			=> $request->address,
					'contact_mobile'    => $request->phone,
                    'whatsapp_no'       => $request->phone,
				);

                StoreDetails::create($store_details_data);

				$roll_ids           = $request->roll_ids;
                $roll_view_ids      = $request->view;
                $roll_add_ids       = $request->add;
                $roll_edit_ids      = $request->edit;
                $roll_delete_ids    = $request->delete;
                $roll_download_ids  = $request->download;
                $roll_print_ids     = $request->print;
                $roll_upload_ids    = $request->upload;
                $role_permission_id = $request->employee_role_permission_id;


                $rollPermision=[];
                if(isset($roll_ids)){
                    if(count($roll_ids)>0){
                        foreach($roll_ids as $key=>$val){
                            $subRollPermision=[];

                            $role_wise_permission_ids=$role_permission_id[$key];
                            for($i=0;count($role_wise_permission_ids)>$i;$i++){
                                $subRollPermisionType=[];
                                $subRollPermisionTypeIds=[];

                                $sub_roll_id=isset($role_wise_permission_ids[$i])?$role_wise_permission_ids[$i]:'';


                                $isViewPermision=isset($roll_view_ids[$key][$sub_roll_id])?$roll_view_ids[$key][$sub_roll_id]:'';
                                if($isViewPermision!=''){
                                    $subRollPermisionType[]=$isViewPermision;

                                    $subRolltypeResult= RoleSubPermission::where('role_id',$sub_roll_id)->where('type_id',1)->first();
                                    $subRolltypeId	= isset($subRolltypeResult->id)?$subRolltypeResult->id:'0';
                                    $subRollPermisionTypeIds[]=$subRolltypeId;

                                }
                                $isAddPermision=isset($roll_add_ids[$key][$sub_roll_id])?$roll_add_ids[$key][$sub_roll_id]:'';
                                if($isAddPermision!=''){
                                    $subRollPermisionType[]=$isAddPermision;
                                    $subRolltypeResult= RoleSubPermission::where('role_id',$sub_roll_id)->where('type_id',2)->first();
                                    $subRolltypeId	= isset($subRolltypeResult->id)?$subRolltypeResult->id:'0';
                                    $subRollPermisionTypeIds[]=$subRolltypeId;
                                }
                                $isEditPermision=isset($roll_edit_ids[$key][$sub_roll_id])?$roll_edit_ids[$key][$sub_roll_id]:'';
                                if($isEditPermision!=''){
                                    $subRollPermisionType[]=$isEditPermision;
                                    $subRolltypeResult= RoleSubPermission::where('role_id',$sub_roll_id)->where('type_id',3)->first();
                                    $subRolltypeId	= isset($subRolltypeResult->id)?$subRolltypeResult->id:'0';
                                    $subRollPermisionTypeIds[]=$subRolltypeId;
                                }
                                $isDeletePermision=isset($roll_delete_ids[$key][$sub_roll_id])?$roll_delete_ids[$key][$sub_roll_id]:'';
                                if($isDeletePermision!=''){
                                    $subRollPermisionType[]=$isDeletePermision;
                                    $subRolltypeResult= RoleSubPermission::where('role_id',$sub_roll_id)->where('type_id',4)->first();
                                    $subRolltypeId	= isset($subRolltypeResult->id)?$subRolltypeResult->id:'0';
                                    $subRollPermisionTypeIds[]=$subRolltypeId;
                                }
                                $isDownloadPermision=isset($roll_download_ids[$key][$sub_roll_id])?$roll_download_ids[$key][$sub_roll_id]:'';
                                if($isDownloadPermision!=''){
                                    $subRollPermisionType[]=$isDownloadPermision;
                                    $subRolltypeResult= RoleSubPermission::where('role_id',$sub_roll_id)->where('type_id',5)->first();
                                    $subRolltypeId	= isset($subRolltypeResult->id)?$subRolltypeResult->id:'0';
                                    $subRollPermisionTypeIds[]=$subRolltypeId;
                                }
                                $isPrintPermision=isset($roll_print_ids[$key][$sub_roll_id])?$roll_print_ids[$key][$sub_roll_id]:'';
                                if($isPrintPermision!=''){
                                    $subRollPermisionType[]=$isPrintPermision;
                                    $subRolltypeResult= RoleSubPermission::where('role_id',$sub_roll_id)->where('type_id',6)->first();
                                    $subRolltypeId	= isset($subRolltypeResult->id)?$subRolltypeResult->id:'0';
                                    $subRollPermisionTypeIds[]=$subRolltypeId;
                                }
                                $isUploadPermision=isset($roll_upload_ids[$key][$sub_roll_id])?$roll_upload_ids[$key][$sub_roll_id]:'';
                                if($isUploadPermision!=''){
                                    $subRollPermisionType[]=$isUploadPermision;
                                    $subRolltypeResult= RoleSubPermission::where('role_id',$sub_roll_id)->where('type_id',7)->first();
                                    $subRolltypeId	= isset($subRolltypeResult->id)?$subRolltypeResult->id:'0';
                                    $subRollPermisionTypeIds[]=$subRolltypeId;
                                }

                                //echo '<pre>';print_r($roll_adroll_edit_idsd_ids[$key][$sub_roll_id]);exit;



                                if(count($subRollPermisionType)>0){
                                    $subRollPermision[]=array(
                                        'id'            => $i,
                                        'sub_roll_id'   => $sub_roll_id,
                                        'permisionType' => $subRollPermisionType,
                                        'permisionTypeId' => $subRollPermisionTypeIds,
                                    );
                                }
                            }

                            //echo '<pre>';print_r($role_wise_permission_ids);

                            $rollPermision[]=array(
                                'main_roll_id'      => $val,
                                'main_roll_key'     => $key,
                                'subRollPermision'  => $subRollPermision
                            );
                        }
                    }
                }

                //echo '<pre>';print_r($rollPermision);exit;

                if(count($rollPermision)>0){
                    foreach($rollPermision as $row){
                        $roll_id=$row['main_roll_id'];

                        $userRolePermission=array(
                            'user_id'  		=> $store_id,
                            'role_id'  		=> $roll_id,
                        );
                        //echo '<pre>';print_r($userRolePermission);exit;
                        UserRolePermission::create($userRolePermission);

                        foreach($row['subRollPermision'] as $sRow){
                            $sub_roll_id=$sRow['sub_roll_id'];
                            foreach($sRow['permisionType'] as $key=>$val){
                                $type_id=$val;
                                $sub_permission_id=isset($sRow['permisionTypeId'][$key])?$sRow['permisionTypeId'][$key]:0;
                                $store_roll_data=array(
                                    'branch_id'  		=> $store_id,
                                    'role_id'  			=> $roll_id,
                                    'permission_id'  	=> $sub_roll_id,
                                    'sub_permission_id' => $sub_permission_id,
                                    'type_id'  	        => $type_id,
                                );
                                //echo '<pre>';print_r($store_roll_data);exit;
                                RoleWisePermission::create($store_roll_data);
                            }
                        }
                    }
                }

				return redirect()->back()->with('success', 'Store Added successfully');
            }
            $data = [];

            $data['heading'] = 'Embloyees Add';
            $data['breadcrumb'] = ['Embloyees', 'Add'];
			$data['role']= RolePermission::where('status',1)->orderBy('id', 'asc')->get();

            $storeRolePermission= UserRolePermission::where('user_id',$parent_id)->whereNotIn('role_id', [6])->orderBy('id', 'asc')->get();
            $storeRolePermissionIds=[];
            foreach($storeRolePermission as $row){
                $storeRolePermissionIds[]=$row->role_id;
            }
            //$storeRolePermissionIds[]=6;
            //echo '<pre>';print_r($storeRolePermissionIds);exit;

            $rolePermission= RolePermission::where('parent_id',0)->whereIn('id', $storeRolePermissionIds)->where('status',1)->orderBy('id', 'asc')->get();

            //echo '<pre>';print_r($rolePermission);exit;
            $store_role=[];
            foreach($rolePermission as $row){
                $sub_roll=[];

                $storeRoleWisePermission= RoleWisePermission::where('branch_id',$parent_id)->where('role_id',$row->id)->select('permission_id')->distinct()->orderBy('id', 'asc')->get();
                $storeSubRolePermissionIds=[];
                foreach($storeRoleWisePermission as $sprow){
                    $storeSubRolePermissionIds[]=$sprow->permission_id;
                }
                //echo '<pre>';print_r($storeSubRolePermissionIds);exit;

                $subRolePermission= RolePermission::where('parent_id',$row->id)->whereIn('id', $storeSubRolePermissionIds)->where('status',1)->orderBy('id', 'asc')->get();
                $is_checked='N';

                //echo '<pre>';print_r($subRolePermission);exit;

                foreach($subRolePermission as $srow){
                    $is_view    = 'N';
                    $is_add     = 'N';
                    $is_edit    = 'N';
                    $is_delete  = 'N';
                    $is_download= 'N';
                    $is_print   = 'N';
                    $is_upload  = 'N';

                    $is_view_chk    = 'N';
                    $is_add_chk     = 'N';
                    $is_edit_chk    = 'N';
                    $is_delete_chk  = 'N';
                    $is_download_chk= 'N';
                    $is_print_chk   = 'N';
                    $is_upload_chk  = 'N';



                    $viewCount= RoleSubPermission::where('role_id',$srow->id)->where('type_id',1)->count();
                    if($viewCount>0){
                        $is_view    = 'Y';
                    }
                    $addCount= RoleSubPermission::where('role_id',$srow->id)->where('type_id',2)->count();
                    if($addCount>0){
                        $is_add    = 'Y';
                    }

                    $editCount= RoleSubPermission::where('role_id',$srow->id)->where('type_id',3)->count();
                    if($editCount>0){
                        $is_edit    = 'Y';
                    }

                    $deleteCount= RoleSubPermission::where('role_id',$srow->id)->where('type_id',4)->count();
                    if($deleteCount>0){
                        $is_delete    = 'Y';
                    }

                    $downloadCount= RoleSubPermission::where('role_id',$srow->id)->where('type_id',5)->count();
                    if($downloadCount>0){
                        $is_download    = 'Y';
                    }

                    $printCount= RoleSubPermission::where('role_id',$srow->id)->where('type_id',6)->count();
                    if($printCount>0){
                        $is_print    = 'Y';
                    }

                    $uploadCount= RoleSubPermission::where('role_id',$srow->id)->where('type_id',7)->count();
                    if($uploadCount>0){
                        $is_upload    = 'Y';
                    }

                    //echo '<pre>';print_r($is_view);exit;


                    $sub_roll[]=array(
                        'roll_id'       => $srow->id,
                        'parent_id'     => $srow->parent_id,
                        'title'         => $srow->title,
                        'is_view'       => $is_view,
                        'is_add'        => $is_add,
                        'is_edit'       => $is_edit,
                        'is_delete'     => $is_delete,
                        'is_download'   => $is_download,
                        'is_print'      => $is_print,
                        'is_upload'     => $is_upload,
                        'is_view_chk'   => $is_view_chk,
                        'is_add_chk'   => $is_add_chk,
                        'is_edit_chk'   => $is_edit_chk,
                        'is_delete_chk'   => $is_delete_chk,
                        'is_download_chk'   => $is_download_chk,
                        'is_print_chk'   => $is_print_chk,
                        'is_upload_chk'   => $is_upload_chk,
                    );
                }

                $store_role[]=array(
                    'roll_id'       => $row->id,
                    'title'         => $row->title,
                    'is_checked'    => $is_checked,
                    'sub_roll'      => $sub_roll,
                );
            }

            $data['store_role'] = $store_role;

			//echo '<pre>';print_r($data['store_role']);exit;

            return view('admin.embloyees.add', compact('data'));
        } catch (\Exception $e) {
            $return_data['success'] = 0;
			$return_data['error_message'] = 'Something went wrong. Please try later. ' . $e->getMessage();
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $parent_id=Auth::user()->id;
            $store_id = base64_decode($id);
			if ($request->isMethod('post')) {

			$validator = Validator::make($request->all(), [
				'email' 	=> 'required|email|unique:users,email,' . $store_id,
				'phone' 	=> 'required|numeric|unique:users,phone,' . $store_id,
				'full_name' => 'required',
			]);

			if($request->password!=''){
				$validator = Validator::make($request->all(), [
				'password' => 'required',
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

			// print_r($array);exit;


			if($request->password!=''){
				$array['password']=Hash::make($request->password);
			}

            // print_r($array);exit;

			User::find($store_id)->update($array);

            $store_details_data=array(
                'contact_email'  	=> $request->email,
                'address'			=> $request->address,
                'contact_mobile'    => $request->phone,
                'whatsapp_no'       => $request->phone,
            );

            StoreDetails::where('store_id',$store_id)->update($store_details_data);

            $roll_ids           = $request->roll_ids;
            $roll_view_ids      = $request->view;
            $roll_add_ids       = $request->add;
            $roll_edit_ids      = $request->edit;
            $roll_delete_ids    = $request->delete;
            $roll_download_ids  = $request->download;
            $roll_print_ids     = $request->print;
            $roll_upload_ids    = $request->upload;
            $role_permission_id = $request->employee_role_permission_id;


            $rollPermision=[];
            if(isset($roll_ids)){
                if(count($roll_ids)>0){
                    foreach($roll_ids as $key=>$val){
                        $subRollPermision=[];

                        $role_wise_permission_ids=$role_permission_id[$key];
                        for($i=0;count($role_wise_permission_ids)>$i;$i++){
                            $subRollPermisionType=[];
                            $subRollPermisionTypeIds=[];

                            $sub_roll_id=isset($role_wise_permission_ids[$i])?$role_wise_permission_ids[$i]:'';


                            $isViewPermision=isset($roll_view_ids[$key][$sub_roll_id])?$roll_view_ids[$key][$sub_roll_id]:'';
                            if($isViewPermision!=''){
                                $subRollPermisionType[]=$isViewPermision;

                                $subRolltypeResult= RoleSubPermission::where('role_id',$sub_roll_id)->where('type_id',1)->first();
                                $subRolltypeId	= isset($subRolltypeResult->id)?$subRolltypeResult->id:'0';
                                $subRollPermisionTypeIds[]=$subRolltypeId;

                            }
                            $isAddPermision=isset($roll_add_ids[$key][$sub_roll_id])?$roll_add_ids[$key][$sub_roll_id]:'';
                            if($isAddPermision!=''){
                                $subRollPermisionType[]=$isAddPermision;
                                $subRolltypeResult= RoleSubPermission::where('role_id',$sub_roll_id)->where('type_id',2)->first();
                                $subRolltypeId	= isset($subRolltypeResult->id)?$subRolltypeResult->id:'0';
                                $subRollPermisionTypeIds[]=$subRolltypeId;
                            }
                            $isEditPermision=isset($roll_edit_ids[$key][$sub_roll_id])?$roll_edit_ids[$key][$sub_roll_id]:'';
                            if($isEditPermision!=''){
                                $subRollPermisionType[]=$isEditPermision;
                                $subRolltypeResult= RoleSubPermission::where('role_id',$sub_roll_id)->where('type_id',3)->first();
                                $subRolltypeId	= isset($subRolltypeResult->id)?$subRolltypeResult->id:'0';
                                $subRollPermisionTypeIds[]=$subRolltypeId;
                            }
                            $isDeletePermision=isset($roll_delete_ids[$key][$sub_roll_id])?$roll_delete_ids[$key][$sub_roll_id]:'';
                            if($isDeletePermision!=''){
                                $subRollPermisionType[]=$isDeletePermision;
                                $subRolltypeResult= RoleSubPermission::where('role_id',$sub_roll_id)->where('type_id',4)->first();
                                $subRolltypeId	= isset($subRolltypeResult->id)?$subRolltypeResult->id:'0';
                                $subRollPermisionTypeIds[]=$subRolltypeId;
                            }
                            $isDownloadPermision=isset($roll_download_ids[$key][$sub_roll_id])?$roll_download_ids[$key][$sub_roll_id]:'';
                            if($isDownloadPermision!=''){
                                $subRollPermisionType[]=$isDownloadPermision;
                                $subRolltypeResult= RoleSubPermission::where('role_id',$sub_roll_id)->where('type_id',5)->first();
                                $subRolltypeId	= isset($subRolltypeResult->id)?$subRolltypeResult->id:'0';
                                $subRollPermisionTypeIds[]=$subRolltypeId;
                            }
                            $isPrintPermision=isset($roll_print_ids[$key][$sub_roll_id])?$roll_print_ids[$key][$sub_roll_id]:'';
                            if($isPrintPermision!=''){
                                $subRollPermisionType[]=$isPrintPermision;
                                $subRolltypeResult= RoleSubPermission::where('role_id',$sub_roll_id)->where('type_id',6)->first();
                                $subRolltypeId	= isset($subRolltypeResult->id)?$subRolltypeResult->id:'0';
                                $subRollPermisionTypeIds[]=$subRolltypeId;
                            }
                            $isUploadPermision=isset($roll_upload_ids[$key][$sub_roll_id])?$roll_upload_ids[$key][$sub_roll_id]:'';
                            if($isUploadPermision!=''){
                                $subRollPermisionType[]=$isUploadPermision;
                                $subRolltypeResult= RoleSubPermission::where('role_id',$sub_roll_id)->where('type_id',7)->first();
                                $subRolltypeId	= isset($subRolltypeResult->id)?$subRolltypeResult->id:'0';
                                $subRollPermisionTypeIds[]=$subRolltypeId;
                            }

                            //echo '<pre>';print_r($roll_adroll_edit_idsd_ids[$key][$sub_roll_id]);exit;



                            if(count($subRollPermisionType)>0){
                                $subRollPermision[]=array(
                                    'id'            => $i,
                                    'sub_roll_id'   => $sub_roll_id,
                                    'permisionType' => $subRollPermisionType,
                                    'permisionTypeId' => $subRollPermisionTypeIds,
                                );
                            }
                        }

                        //echo '<pre>';print_r($role_wise_permission_ids);

                        $rollPermision[]=array(
                            'main_roll_id'      => $val,
                            'main_roll_key'     => $key,
                            'subRollPermision'  => $subRollPermision
                        );
                    }
                }
            }

            if(count($rollPermision)>0){
                RoleWisePermission::where('branch_id',$store_id)->delete();
                UserRolePermission::where('user_id',$store_id)->delete();
                foreach($rollPermision as $row){
                    $roll_id=$row['main_roll_id'];

                    $userRolePermission=array(
                        'user_id'  		=> $store_id,
                        'role_id'  		=> $roll_id,
                    );
                    //echo '<pre>';print_r($userRolePermission);exit;
                    UserRolePermission::create($userRolePermission);

                    foreach($row['subRollPermision'] as $sRow){
                        $sub_roll_id=$sRow['sub_roll_id'];
                        foreach($sRow['permisionType'] as $key=>$val){
                            $type_id=$val;
                            $sub_permission_id=isset($sRow['permisionTypeId'][$key])?$sRow['permisionTypeId'][$key]:0;
                            $store_roll_data=array(
                                'branch_id'  		=> $store_id,
                                'role_id'  			=> $roll_id,
                                'permission_id'  	=> $sub_roll_id,
                                'sub_permission_id' => $sub_permission_id,
                                'type_id'  	        => $type_id,
                            );
                            //echo '<pre>';print_r($store_roll_data);exit;
                            RoleWisePermission::create($store_roll_data);
                        }
                    }
                }
            }

            //echo '<pre>';print_r($rollPermision);exit;


			return redirect()->back()->with('success', 'Store Updated successfully');
        }

            $data = [];
            $data['heading'] 	= 'Embloyees Edit';
            $data['breadcrumb'] = ['Embloyees', 'Edit'];
            $data['store'] 	= User::find($store_id);
			$data['role']= RolePermission::where('status',1)->orderBy('id', 'asc')->get();
			$data['roleWisePermission']= RoleWisePermission::where('branch_id',$store_id)->orderBy('id', 'asc')->get();

            $storeRolePermission= UserRolePermission::where('user_id',$parent_id)->whereNotIn('role_id', [6])->orderBy('id', 'asc')->get();
            $storeRolePermissionIds=[];
            foreach($storeRolePermission as $row){
                $storeRolePermissionIds[]=$row->role_id;
            }

            $rolePermission= RolePermission::where('parent_id',0)->whereIn('id', $storeRolePermissionIds)->where('status',1)->orderBy('id', 'asc')->get();

            //echo '<pre>';print_r($rolePermission);exit;
            $store_role=[];
            foreach($rolePermission as $row){
                $sub_roll=[];

                $storeRoleWisePermission= RoleWisePermission::where('branch_id',$parent_id)->where('role_id',$row->id)->select('permission_id')->distinct()->orderBy('id', 'asc')->get();
                $storeSubRolePermissionIds=[];
                foreach($storeRoleWisePermission as $sprow){
                    $storeSubRolePermissionIds[]=$sprow->permission_id;
                }
                //echo '<pre>';print_r($storeSubRolePermissionIds);exit;



                $subRolePermission= RolePermission::where('parent_id',$row->id)->whereIn('id', $storeSubRolePermissionIds)->where('status',1)->orderBy('id', 'asc')->get();

                $is_checked='N';
                $userRolePermissionCount= UserRolePermission::where('user_id',$store_id)->where('role_id',$row->id)->count();

                //echo '<pre>';print_r($userRolePermissionCount);exit;
                if($userRolePermissionCount>0){
                    $is_checked='Y';
                }

                foreach($subRolePermission as $srow){
                    $is_view    = 'N';
                    $is_add     = 'N';
                    $is_edit    = 'N';
                    $is_delete  = 'N';
                    $is_download= 'N';
                    $is_print   = 'N';
                    $is_upload  = 'N';

                    $is_view_chk    = 'N';
                    $is_add_chk     = 'N';
                    $is_edit_chk    = 'N';
                    $is_delete_chk  = 'N';
                    $is_download_chk= 'N';
                    $is_print_chk   = 'N';
                    $is_upload_chk  = 'N';



                    $viewCount= RoleSubPermission::where('role_id',$srow->id)->where('type_id',1)->count();
                    if($viewCount>0){
                        $is_view    = 'Y';
                    }

                    $viewchkCount= RoleWisePermission::where('branch_id',$store_id)->where('role_id',$row->id)->where('permission_id',$srow->id)->where('type_id',1)->count();
                    if($viewchkCount>0){
                        $is_view_chk    = 'Y';
                    }

                    $addCount= RoleSubPermission::where('role_id',$srow->id)->where('type_id',2)->count();
                    if($addCount>0){
                        $is_add    = 'Y';
                    }
                    $addchkCount= RoleWisePermission::where('branch_id',$store_id)->where('role_id',$row->id)->where('permission_id',$srow->id)->where('type_id',2)->count();
                    if($addchkCount>0){
                        $is_add_chk    = 'Y';
                    }
                    $editCount= RoleSubPermission::where('role_id',$srow->id)->where('type_id',3)->count();
                    if($editCount>0){
                        $is_edit    = 'Y';
                    }
                    $editchkCount= RoleWisePermission::where('branch_id',$store_id)->where('role_id',$row->id)->where('permission_id',$srow->id)->where('type_id',3)->count();
                    if($editchkCount>0){
                        $is_edit_chk    = 'Y';
                    }
                    $deleteCount= RoleSubPermission::where('role_id',$srow->id)->where('type_id',4)->count();
                    if($deleteCount>0){
                        $is_delete    = 'Y';
                    }
                    $deletechkCount= RoleWisePermission::where('branch_id',$store_id)->where('role_id',$row->id)->where('permission_id',$srow->id)->where('type_id',4)->count();
                    if($deletechkCount>0){
                        $is_delete_chk    = 'Y';
                    }
                    $downloadCount= RoleSubPermission::where('role_id',$srow->id)->where('type_id',5)->count();
                    if($downloadCount>0){
                        $is_download    = 'Y';
                    }
                    $downloadchkCount= RoleWisePermission::where('branch_id',$store_id)->where('role_id',$row->id)->where('permission_id',$srow->id)->where('type_id',5)->count();
                    if($downloadchkCount>0){
                        $is_download_chk    = 'Y';
                    }
                    $printCount= RoleSubPermission::where('role_id',$srow->id)->where('type_id',6)->count();
                    if($printCount>0){
                        $is_print    = 'Y';
                    }
                    $printchkCount= RoleWisePermission::where('branch_id',$store_id)->where('role_id',$row->id)->where('permission_id',$srow->id)->where('type_id',6)->count();
                    if($printchkCount>0){
                        $is_print_chk    = 'Y';
                    }
                    $uploadCount= RoleSubPermission::where('role_id',$srow->id)->where('type_id',7)->count();
                    if($uploadCount>0){
                        $is_upload    = 'Y';
                    }
                    $uploadchkCount= RoleWisePermission::where('branch_id',$store_id)->where('role_id',$row->id)->where('permission_id',$srow->id)->where('type_id',7)->count();
                    if($uploadchkCount>0){
                        $is_upload_chk    = 'Y';
                    }
                    //echo '<pre>';print_r($is_view);exit;


                    $sub_roll[]=array(
                        'roll_id'       => $srow->id,
                        'parent_id'     => $srow->parent_id,
                        'title'         => $srow->title,
                        'is_view'       => $is_view,
                        'is_add'        => $is_add,
                        'is_edit'       => $is_edit,
                        'is_delete'     => $is_delete,
                        'is_download'   => $is_download,
                        'is_print'      => $is_print,
                        'is_upload'     => $is_upload,
                        'is_view_chk'   => $is_view_chk,
                        'is_add_chk'   => $is_add_chk,
                        'is_edit_chk'   => $is_edit_chk,
                        'is_delete_chk'   => $is_delete_chk,
                        'is_download_chk'   => $is_download_chk,
                        'is_print_chk'   => $is_print_chk,
                        'is_upload_chk'   => $is_upload_chk,
                    );
                }

                $store_role[]=array(
                    'roll_id'       => $row->id,
                    'title'         => $row->title,
                    'is_checked'    => $is_checked,
                    'sub_roll'      => $sub_roll,
                );
            }

            $data['store_role'] = $store_role;

            //echo '<pre>';print_r($data['store_role']);exit;

            return view('admin.embloyees.edit', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }
    public function delete($id)
    {
        try {
            $id = base64_decode($id);
            // Supplier::find($id)->delete();
			// SupplierCompanyMobile::where('supplier_id',$id)->delete();
			// SupplierBank::where('supplier_id',$id)->delete();
			// SupplierGst::where('supplier_id',$id)->delete();
			// SupplierContactDetails::where('supplier_id',$id)->delete();

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
