<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;
use App\Helper\Media;
use App\Models\Company;

class CompanyController extends Controller
{
    public function list(Request $request)
    {
        try {

            if ($request->ajax()) {
                $companies = Company::orderBy('id', 'desc')->get();
				return DataTables::of($companies)
                    ->addColumn('name', function ($row) {
                        return $row->name;
                    })
                    ->addColumn('action', function ($row) {
                        $dropdown = '<a class="dropdown-item" href="' . route('admin.company.edit', [base64_encode($row->id)]) . '">Edit</a>
                        <a class="dropdown-item delete-item" href="#" id="delete_company" data-url="' . route('admin.company.delete', [base64_encode($row->id)]) . '">Delete</a>';

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
                    ->rawColumns(['action'])
                    ->make(true);
            }

            $data = [];
            $data['heading'] = 'Company List';
            $data['breadcrumb'] = ['Company', 'List'];
            return view('admin.company.list', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }

    public function add(Request $request)
    {
		//dd($request->all());
        try {
            if ($request->isMethod('post')) {
				
                $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:company',
                ]);
                if ($validator->fails()) {
                    $errors=$validator->errors()->all();
					$error_html='';
					foreach($errors as $er){
						$error_html .=$er;
					}
					$return_data['success'] = 0;
					$return_data['error_message'] = $error_html;
					return response()->json([$return_data]);
                }
				
				//print_r($_POST);exit;
				
				
				$company_slug	= Media::create_slug(trim($request->name));
				$company_data=array(
					'name'  => $request->name,
					'slug'			=> $company_slug,
					'created_at'	=> date('Y-m-d')
				);
				Company::create($company_data);
				$return_data['success'] = 1;
				return response()->json([$return_data]);;
            }
            $data = [];
            $data['heading'] = 'Company Add';
            $data['breadcrumb'] = ['Company', 'Add'];
            return view('admin.company.add', compact('data'));
        } catch (\Exception $e) {
            $return_data['success'] = 0;
			$return_data['error_message'] = 'Something went wrong. Please try later. ' . $e->getMessage();
        }
    }
    public function edit(Request $request, $id)
    {
        try {
            $company_id = base64_decode($id);
			if ($request->isMethod('post')) {
				
                $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:company,name,'.$company_id,
                ]);
				
                if ($validator->fails()) {
                    $errors=$validator->errors()->all();
					$error_html='';
					foreach($errors as $er){
						$error_html .=$er;
					}
					$return_data['success'] = 0;
					$return_data['error_message'] = $error_html;
					return response()->json([$return_data]);
                }
				$company_slug	= Media::create_slug(trim($request->name));

				$company_data=array(
					'name'  => $request->name,
					'slug'			=> $company_slug,
					'updated_at'	=> date('Y-m-d')
				);
				
				Company::find($company_id)->update($company_data);
				
				$return_data['success'] = 1;
				return response()->json([$return_data]);;
            }
            
            $data = [];
            $data['heading'] = 'Company Edit';
            $data['breadcrumb'] = ['Company', 'Edit'];
            $data['company'] = Company::find($company_id);			
            return view('admin.company.edit', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }
    public function delete($id)
    {
        try {
            $id = base64_decode($id);
            Company::find($id)->delete();
            return redirect()->back()->with('success', 'Company deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }
}
