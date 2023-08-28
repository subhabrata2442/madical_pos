<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;
use App\Helper\Media;

class BrandController extends Controller
{
    public function list(Request $request)
    {
        try {

            if ($request->ajax()) {
                $brands = Brand::orderBy('id', 'desc')->get();
				return DataTables::of($brands)
                    ->addColumn('name', function ($row) {
                        return $row->name;
                    })
                    ->addColumn('action', function ($row) {
                        $dropdown = '<a class="dropdown-item" href="' . route('admin.brand.edit', [base64_encode($row->id)]) . '">Edit</a>
                        <a class="dropdown-item delete-item" href="#" id="delete_brand" data-url="' . route('admin.brand.delete', [base64_encode($row->id)]) . '">Delete</a>';

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
            $data['heading'] = 'Brand List';
            $data['breadcrumb'] = ['Brand', 'List'];
            return view('admin.brand.list', compact('data'));
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
                    'name' => 'required|unique:brand',
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
				
				
				$brand_slug	= Media::create_slug(trim($request->name));
				$supplier_data=array(
					'name'  => $request->name,
					'slug'			=> $brand_slug,
					'created_at'	=> date('Y-m-d')
				);
				$supplier=Brand::create($supplier_data);
				$return_data['success'] = 1;
				return response()->json([$return_data]);;
            }
            $data = [];
            $data['heading'] = 'Brand Add';
            $data['breadcrumb'] = ['Brand', 'Add'];
            return view('admin.brand.add', compact('data'));
        } catch (\Exception $e) {
            $return_data['success'] = 0;
			$return_data['error_message'] = 'Something went wrong. Please try later. ' . $e->getMessage();
        }
    }
    public function edit(Request $request, $id)
    {
        try {
            $brand_id = base64_decode($id);
			if ($request->isMethod('post')) {
				
                $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:brand,name,'.$brand_id,
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
				$brand_slug	= Media::create_slug(trim($request->name));

				$brand_data=array(
					'name'  => $request->name,
					'slug'			=> $brand_slug,
					'updated_at'	=> date('Y-m-d')
				);
				
				Brand::find($brand_id)->update($brand_data);
				
				$return_data['success'] = 1;
				return response()->json([$return_data]);;
            }
            
            $data = [];
            $data['heading'] = 'Brand Edit';
            $data['breadcrumb'] = ['Brand', 'Edit'];
            $data['brand'] = Brand::find($brand_id);			
            return view('admin.brand.edit', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }
    public function delete($id)
    {
        try {
            $id = base64_decode($id);
            Brand::find($id)->delete();
            return redirect()->back()->with('success', 'Brand deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }
}
