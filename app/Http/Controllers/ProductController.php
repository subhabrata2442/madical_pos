<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\SupplierCompanyMobile;
use App\Models\SupplierBank;
use App\Models\SupplierGst;
use App\Models\SupplierContactDetails;
use App\Models\User;
use App\Helper\Media;
use App\Models\Category;
use App\Models\Color;
use App\Models\Abcdefg;
use App\Models\Material;
use App\Models\Service;
use App\Models\Size;
use App\Models\Subcategory;
use App\Models\VendorCode;
use App\Models\Measurement;
use App\Models\Product;
use App\Models\MasterProducts;
use App\Models\ProductRelationshipSize;
use App\Models\BarProductSizePrice;
use App\Models\BranchStockProducts;
use App\Models\BranchStockProductSellPrice;
use App\Models\Common;
use App\Models\Warehouse;

use App\Models\Brand;
use App\Models\Dosage;
use App\Models\Company;
use App\Models\Drugstore;
use App\Models\RoleWisePermission;

use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Auth;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\InwardStockProducts;

class ProductController extends Controller
{
	public function list(Request $request)
	{

		$branch_id = Auth::user()->id;
		$store_id = Session::get('store_id');
		$user_role = Auth::user()->role;

		if ($user_role == 1) {
			$drugstore_id = isset($request['drugstore']) ? $request['drugstore'] : 0;
		} else {
			$drugstore_id = $store_id;
		}
		//$store_id=2;;
		//echo '<pre>';print_r($store_id);exit;
		try {
			$data = [];
			$queryProduct = Product::query();
			//$products = $queryProduct->where('drugstore_id',$drugstore_id);
			if (!is_null($request['product_barcode'])) {
				$queryProduct->where('product_barcode', $request['product_barcode']);
			}
			// if (!is_null($request['product_name'])) {
			// 	$queryProduct->where('brand', $request['product_name']);
			// }
            if (!is_null($request['brand_name'])) {
				$queryProduct->where('brand', $request['brand_name']);
			}
			$products = $queryProduct->orderBy('id', 'desc')->get();

			//echo '<pre>';print_r($product);exit;

			$data['store']			= [];
			if ($user_role == 1) {
				$data['store'] 		= User::where('role', 2)->where('parent_id', 0)->where('status', 1)->get();
			}

			//echo '<pre>';print_r($data['store']);exit;

			$stockProducts 		= $queryProduct->paginate(20);
			$data['heading'] 	= 'Product List';
			$data['breadcrumb'] = ['Product', 'List'];
			$data['products']   = $stockProducts;
			//$products = $products->toArray();
			//echo '<pre>';print_r($data['store']);exit;


			return view('admin.product.list', compact('data'));
		} catch (\Exception $e) {
			return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
		}
	}

	public function add(Request $request)
	{
		try {
			$branch_id = Auth::user()->id;
			$store_id = Session::get('store_id');

			if ($request->isMethod('post')) {
				//dd($request->all());
				$validator = Validator::make($request->all(), [
					'brand' 		=> 'required',
					// 'product_name' 		=> 'required',
					'product_barcode' 	=> 'required',
					'selling_by' 		=> 'required',
					'no_package' 		=> 'required',
				]);
				if ($validator->fails()) {
					return redirect()->back()->withErrors($validator)->withInput();
				}

				$product_image = '';
				if ($file = $request->file('upload_photo')) {
					$fileData = Media::uploads($file, 'uploads/product');
					$product_image = $fileData['filePath'];
				}

				$n = Product::count();
				$uqc_id = str_pad($n + 1, 5, 0, STR_PAD_LEFT);

				$brand_name = '';
				$brand_id = $request->brand;

				$product_name = $request->product_name;
				$product_barcode = $request->product_barcode;
				$sku_code = $request->sku_code;
				$alert_product_qty = $request->alert_product_qty;
				$default_qty = $request->default_qty;
				$days_before_product_expiry = $request->days_before_product_expiry;

				$dosage_name = '';
				$dosage_id = $request->dosage;
				$company_name = '';
				$company_id = $request->company;
				$drugstore_name = '';
				$drugstore_id = $request->drugstore;

				$no_package = $request->no_package;
				$selling_by = $request->selling_by;
				$is_chronic = $request->is_chronic;

				$selling_by_name = 'Pack';
				if ($selling_by == 2) {
					$selling_by_name = 'Single item';
				}

				/* $brand_id=0;
				if($product_name!=''){
					$brand_slug 	= $this->create_slug($product_name);
					$brand_result	= Brand::where('name',$product_name)->get();
					if(count($brand_result)>0){
						$brand_id=isset($brand_result[0]->id)?$brand_result[0]->id:0;
					}else{
						$insert_data=array(
							'name'  		=> $product_name,
							'slug'  		=> $brand_slug,
						);
						$data_insert=Brand::create($insert_data);
						$brand_id=$data_insert->id;
					}
				} */
				if ($brand_id != '') {
					$brand_result = Brand::where('id', $brand_id)->first();
					$brand_name = isset($brand_result->name) ? $brand_result->name : '';
				}

				if ($dosage_id != '') {
					$dosage_result = Dosage::where('id', $dosage_id)->first();
					$dosage_name = isset($dosage_result->name) ? $dosage_result->name : '';
				}

				if ($company_id != '') {
					$company_result = Company::where('id', $company_id)->first();
					$company_name = isset($company_result->name) ? $company_result->name : '';
				}

				// if($drugstore_id!=''){
				// 	$drugstore_result=User::where('id',$drugstore_id)->first();
				// 	$drugstore_name=isset($drugstore_result->name)?$drugstore_result->name:'';
				// }

				$product_slug = $this->create_slug($brand_name . '-' . $product_name . '-' . $product_barcode);

				//echo '<pre>';print_r($product_slug);exit;

				$product_result = Product::where('product_barcode', $product_barcode)->get();
				$check_brand_product_name = Product::where('product_name', $product_name)->where('brand_id', $brand_id)->get();
				if (count($product_result) > 0) {
					return redirect()->back()->with('error', 'This barcode already exists!');
					// }else if(count($check_brand_product_name)>0){
					// 	return redirect()->back()->with('error', 'This brand and product name already exists!');
					// }
				} else {

					$insert_data = array(
						//'sku_code'				=> $sku_code,
						'uqc_id'  				=> $uqc_id,
						'product_barcode'  		=> $product_barcode,
						'brand'  				=> $brand_name,
						// 'product_name'  		=> $product_name,
						'brand_id'  			=> $brand_id,
						// 'slug'  				=> $product_slug,
						'is_chronic'  			=> $is_chronic,
						'dosage_name'  			=> $dosage_name,
						'dosage_id'  			=> $dosage_id,
						'company_name'  		=> $company_name,
						'company_id'  			=> $company_id,
						'drugstore_name'  		=> $drugstore_name,
						'drugstore_id'  		=> $drugstore_id,
						'default_qty'			=> $default_qty,
						'days_before_product_expiry' => $days_before_product_expiry,
						'alert_product_qty'		=> $alert_product_qty,
						'no_package'			=> $no_package,
						'selling_by'			=> $selling_by,
						'selling_by_name'		=> $selling_by_name,
						'common_items'		=> $request->common_items,
					);
					$data_insert = Product::create($insert_data);
					$product_id = $data_insert->id;
				}
				//echo '<pre>';print_r($insert_data);exit;

				return redirect()->back()->with('success', 'Product created successfully');
			}

			$data = [];
			$data['heading'] 		= 'Product Add';
			$data['breadcrumb'] 	= ['Product', 'Add'];
			$data['brand'] 			= Brand::all();
			$data['dosage'] 		= Dosage::all();
			$data['category'] 		= Category::all();
			$data['company'] 		= Company::orderBy('name', 'asc')->get();
			$data['brand'] 			= Brand::all();
			$data['subcategory'] 	= Subcategory::all();
			//$data['drugstore'] 		= Drugstore::all();
			$data['thumb']			= asset('images/placeholder.png');

			$data['store']			= [];

			$drugstore_id = 0;
			if ($branch_id == 1) {
				$stores 		= User::where('role', 2)->where('parent_id', 0)->where('status', 1)->get();
			} else {
				$drugstore_id 	= $store_id;
				$stores 		= User::where('id', $drugstore_id)->get();
			}

			$data['drugstore']			= $stores;
			$data['drugstore_id']	= $drugstore_id;

			//echo '<pre>';print_r($data);exit;

			return view('admin.product.add', compact('data'));
		} catch (\Exception $e) {

			return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
		}
	}

	public function edit($id, Request $request)
	{
		try {
			$branch_id = Auth::user()->id;
			$store_id = Session::get('store_id');
			$product_id = base64_decode($id);
			if ($request->isMethod('post')) {
				$validator = Validator::make($request->all(), [
					'product_barcode' 	=> 'required|unique:products,product_barcode,' . $product_id,
					'brand' 		=> 'required',
					// 'product_name' 		=> 'required',
					'selling_by' 		=> 'required',
					'no_package' 		=> 'required',
				]);

				if ($validator->fails()) {
					return redirect()->back()->withErrors($validator)->withInput();
				}

				$brand_name = '';
				$brand_id = $request->brand;

				$product_name = $request->product_name;
				$product_barcode = $request->product_barcode;
				$sku_code = $request->sku_code;
				$alert_product_qty = $request->alert_product_qty;
				$default_qty = $request->default_qty;
				$days_before_product_expiry = $request->days_before_product_expiry;

				$dosage_name = '';
				$dosage_id = $request->dosage;
				$company_name = '';
				$company_id = $request->company;
				$drugstore_name = '';
				$drugstore_id = $request->drugstore;

				$no_package = $request->no_package;
				$selling_by = $request->selling_by;
				$is_chronic = $request->is_chronic;

				$selling_by_name = 'Pack';
				if ($selling_by == 2) {
					$selling_by_name = 'Single item';
				}

				/* $brand_id=0;
				if($product_name!=''){
					$brand_slug 	= $this->create_slug($product_name);
					$brand_result	= Brand::where('name',$product_name)->get();
					if(count($brand_result)>0){
						$brand_id=isset($brand_result[0]->id)?$brand_result[0]->id:0;
					}else{
						$insert_data=array(
							'name'  		=> $product_name,
							'slug'  		=> $brand_slug,
						);
						$data_insert=Brand::create($insert_data);
						$brand_id=$data_insert->id;
					}
				} */
				if ($brand_id != '') {
					$brand_result = Brand::where('id', $brand_id)->first();
					$brand_name = isset($brand_result->name) ? $brand_result->name : '';
				}

				if ($dosage_id != '') {
					$dosage_result = Dosage::where('id', $dosage_id)->first();
					$dosage_name = isset($dosage_result->name) ? $dosage_result->name : '';
				}

				if ($company_id != '') {
					$company_result = Company::where('id', $company_id)->first();
					$company_name = isset($company_result->name) ? $company_result->name : '';
				}

				// if($drugstore_id!=''){
				// 	$drugstore_result=User::where('id',$drugstore_id)->first();
				// 	$drugstore_name=isset($drugstore_result->name)?$drugstore_result->name:'';
				// }

				$product_slug = $this->create_slug($brand_name . '-' . $product_name . '-' . $product_barcode);



				$product_slug = $this->create_slug($product_name . '-' . $product_barcode);
				$product_result = Product::where('product_barcode', $product_barcode)->where('id', '!=', $product_id)->get();
				$check_brand_product_name = Product::where('product_name', $product_name)->where('brand_id', $brand_id)->where('id', '!=', $product_id)->get();
				if (count($product_result) > 0) {
					return redirect()->back()->with('error', 'This barcode already exists!');
				} else if (count($check_brand_product_name) > 0) {
					return redirect()->back()->with('error', 'This brand and product name already exists!');
				} else {
					$update_data = array(
						//'sku_code'				=> $sku_code,
						'brand'  				=> $brand_name,
						// 'product_name'  		=> $product_name,
						'brand_id'  			=> $brand_id,
						'product_barcode'  		=> $product_barcode,
						'slug'  				=> $product_slug,
						'is_chronic'  			=> $is_chronic,
						'dosage_name'  			=> $dosage_name,
						'dosage_id'  			=> $dosage_id,
						'company_name'  		=> $company_name,
						'company_id'  			=> $company_id,
						'default_qty'			=> $default_qty,
						'days_before_product_expiry' => $days_before_product_expiry,
						'alert_product_qty'		=> $alert_product_qty,
						'no_package'			=> $no_package,
						'selling_by'			=> $selling_by,
						'selling_by_name'		=> $selling_by_name,
						'common_items'		=> $request->common_items,

					);
					Product::where('id', $product_id)->update($update_data);
				}



				return redirect()->back()->with('success', 'Product updated successfully');
			}

			$data = [];
			$data['heading'] 		= 'Product Edit';
			$data['breadcrumb'] 	= ['Product', 'Edit'];
			$data['brand'] 			= Brand::all();
			$data['dosage'] 		= Dosage::all();
			$data['category'] 		= Category::all();
			$data['company'] 		= Company::orderBy('name', 'asc')->get();
			$data['brand'] 			= Brand::all();
			$data['subcategory'] 	= Subcategory::all();
			//$data['drugstore'] 		= Drugstore::all();
			$data['thumb']			= asset('images/placeholder.png');
			$data['products']		= Product::find($product_id);

			$drugstore_id = 0;
			if ($branch_id == 1) {
				$stores 		= User::where('role', 2)->where('parent_id', 0)->where('status', 1)->get();
			} else {
				$stores 		= User::where('id', $drugstore_id)->get();
			}

			$data['drugstore']			= $stores;

			//echo '<pre>';print_r($data['products']);exit;

			return view('admin.product.edit', compact('data'));
		} catch (\Exception $e) {

			return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
		}
	}
	public function delete($id)
	{
		try {
			$id = base64_decode($id);

            $inwardStockProducts_check = InwardStockProducts::where('product_id', $id)->first();

            if(!empty($inwardStockProducts_check)){
                return redirect()->back()->with('error', 'Unable to delete! product already in purchase list.');
            }else{
                Product::find($id)->delete();
			    return redirect()->back()->with('success', 'Product deleted successfully');
            }


		} catch (\Exception $e) {
			return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
		}
	}
	public function product_stock_upload(Request $request)
	{
		$file = $request->file('product_upload_file');
		if ($file) {
			$filename = $file->getClientOriginalName();
			$extension = $file->getClientOriginalExtension();
			$tempPath = $file->getRealPath();
			$fileSize = $file->getSize();

			if ($extension != 'csv') {
				return redirect()->back()->with('error', 'Something error occurs!');
			}
			$location = 'uploads';
			$file->move($location, $filename);
			$filepath = public_path($location . "/" . $filename);

			$file = fopen($filepath, "r");
			$importData_arr = array();
			$i = 0;
			while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
				$num = count($filedata);
				if ($i == 0) {
					$i++;
					continue;
				}
				for ($c = 0; $c < $num; $c++) {
					$importData_arr[$i][] = $filedata[$c];
				}
				$i++;
			}


			//echo '<pre>';print_r($importData_arr);exit;

			$j = 0;
			$brand_data = [];
			foreach ($importData_arr as $importData) {
				$category				= $importData[0];
				$type 					= $importData[1];
				$brand_name 			= $importData[2];
				$size 					= $importData[3];
				$warehouse_stock		= $importData[4];
				$counter_stock 			= $importData[5];





				if ($category != '') {
					$brand_slug 	= $this->create_slug($brand_name);

					$category_title = trim($category);
					$category_result = Category::where('name', $category_title)->where('food_type', 1)->get();
					if (count($category_result) > 0) {
						$category_id = isset($category_result[0]->id) ? $category_result[0]->id : 0;
					} else {
						$feature_data = array(
							'name'  		=> $category_title,
							'food_type'  	=> 1,
							'created_at'	=> date('Y-m-d')
						);
						$feature = Category::create($feature_data);
						$category_id = $feature->id;
					}

					$size_id = 0;
					if ($size != '') {
						$size_arr = explode(' ', $size);
						$size_ml = isset($size_arr[0]) ? trim($size_arr[0]) : 0;

						$size_result = Size::where('ml', $size_ml)->get();

						if (count($size_result) > 0) {
							$size_id = isset($size_result[0]->id) ? $size_result[0]->id : 0;
						} else {
							$size_arr = explode(' ', $size);
							$feature_data = array(
								'name'  		=> $size,
								'ml'  			=> isset($size_arr[0]) ? trim($size_arr[0]) : 0,
								'created_at'	=> date('Y-m-d')
							);
							$feature = Size::create($feature_data);
							$size_id = $feature->id;
						}
					}

					$type_result = Subcategory::where('name', $type)->where('food_type', 1)->get();
					if (count($type_result) > 0) {
						$subcategory_id = isset($type_result[0]->id) ? $type_result[0]->id : 0;
					} else {
						$feature_data = array(
							'name'  		=> $type,
							'food_type'  	=> 1,
							'created_at'	=> date('Y-m-d')
						);
						$feature = Subcategory::create($feature_data);
						$subcategory_id = $feature->id;
					}

					$product_result = Product::where('slug', $brand_slug)->where('category_id', $category_id)->where('subcategory_id', $subcategory_id)->get();


					$ws = 0;
					if ($warehouse_stock != '') {
						$ws = $warehouse_stock;
					}

					$cs = 0;
					if ($counter_stock != '') {
						$cs = $counter_stock;
					}





					if (count($product_result) > 0) {
						$product_id = $product_result[0]->id;
						//echo '<pre>';print_r($product_result);exit;


						$productRelationshipSizeResult = ProductRelationshipSize::where('product_id', $product_id)->where('size_id', $size_id)->get();
						$product_mrp = isset($productRelationshipSizeResult[0]->cost_rate) ? $productRelationshipSizeResult[0]->cost_rate : '';

						$barcode = isset($productRelationshipSizeResult[0]->product_barcode) ? $productRelationshipSizeResult[0]->product_barcode : '';
						$barcode2 = isset($productRelationshipSizeResult[0]->barcode2) ? $productRelationshipSizeResult[0]->barcode2 : '';
						$barcode3 = isset($productRelationshipSizeResult[0]->barcode3) ? $productRelationshipSizeResult[0]->barcode3 : '';

						$product_barcode = '';
						if ($barcode != '') {
							$product_barcode = $barcode;
						}
						if ($barcode2 != '') {
							$product_barcode = $barcode2;
						}
						if ($barcode3 != '') {
							$product_barcode = $barcode3;
						}

						/*$brand_data[]=array(
								'product_id'	=> $product_id,
								'size_id'		=> $size_id,
								'brand_name'	=> $brand_name,
								'ws'			=> $ws,
								'cs'			=> $cs
							);*/
						$branch_id = Session::get('branch_id');
						$branch_product_stock_info = BranchStockProducts::where('branch_id', $branch_id)->where('product_id', $product_id)->where('size_id', $size_id)->get();
						if (count($branch_product_stock_info) > 0) {
							$branch_product_stock_sell_price_info = BranchStockProductSellPrice::where('stock_id', $branch_product_stock_info[0]->id)->where('selling_price', $product_mrp)->where('stock_type', 'counter')->get();

							//echo '<pre>';print_r($product_mrp);exit;
							$sell_price_id = isset($branch_product_stock_sell_price_info[0]->id) ? $branch_product_stock_sell_price_info[0]->id : '';

							if ($sell_price_id != '') {

								BranchStockProductSellPrice::where('id', $sell_price_id)->where('stock_type', 'counter')->update(['c_qty' => $cs]);
							} else {
								$branchProductStockSellPriceData = array(
									'stock_id'		=> $branch_product_stock_info[0]->id,
									'w_qty'  		=> 0,
									'c_qty'  		=> $cs,
									'selling_price'	=> $product_mrp,
									'offer_price'  	=> 0,
									'product_mrp'  	=> $product_mrp,
									'stock_type'  	=> 'counter',
									'created_at'	=> date('Y-m-d')
								);
								BranchStockProductSellPrice::create($branchProductStockSellPriceData);
							}
							//echo '<pre>';print_r($branch_product_stock_sell_price_info);exit;
						} else {
							$branchProductStockData = array(
								'branch_id'			=> $branch_id,
								'product_id'  		=> $product_id,
								'product_barcode'  	=> $product_barcode,
								'size_id'  			=> $size_id,
								'created_at'		=> date('Y-m-d')
							);

							$branchStockProducts = BranchStockProducts::create($branchProductStockData);
							$stock_id = $branchStockProducts->id;

							$branchProductStockSellPriceData = array(
								'stock_id'		=> $stock_id,
								'w_qty'  		=> $ws,
								'c_qty'  		=> $cs,
								'selling_price'	=> $product_mrp,
								'offer_price'  	=> 0,
								'product_mrp'  	=> $product_mrp,
								'stock_type'  	=> 'counter',
								'created_at'	=> date('Y-m-d')
							);
							BranchStockProductSellPrice::create($branchProductStockSellPriceData);
						}
					}



					/*echo '<pre>';print_r($product_result);exit;

					$brand_data[]=array(
						'barcode1'		=> $barcode1,
						'barcode2'		=> $barcode2,
						'barcode3'		=> $barcode3,
						'brand_code'	=> $brand_code,
						'brand_name'	=> $brand_name
					);*/
				}
				$j++;
			}

			echo '<pre>';
			print_r($brand_data);
			exit;
		}
	}

	public function product_upload(Request $request)
	{
		$file = $request->file('product_upload_file');
		if ($file) {
			$filename = $file->getClientOriginalName();
			$extension = $file->getClientOriginalExtension();
			$tempPath = $file->getRealPath();
			$fileSize = $file->getSize();

			if ($extension != 'csv') {
				return redirect()->back()->with('error', 'Something error occurs!');
			}
			$location = 'uploads';
			$file->move($location, $filename);
			$filepath = public_path($location . "/" . $filename);

			$file = fopen($filepath, "r");
			$importData_arr = array();
			$i = 0;
			while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
				$num = count($filedata);
				if ($i == 0) {
					$i++;
					continue;
				}
				for ($c = 0; $c < $num; $c++) {
					$importData_arr[$i][] = $filedata[$c];
				}
				$i++;
			}


			/*$j = 0;
			$brand_data=[];
			foreach ($importData_arr as $importData) {
				$company_name	= $importData[1];
				$district		= $importData[2];
				$address		= $importData[3];

				$warehouse_result=Warehouse::where('company_name',$company_name)->get();
				if(count($warehouse_result)>0){
						$warehouse_id=isset($warehouse_result[0]->id)?$warehouse_result[0]->id:0;
						$feature_data=array(
							'company_name'  	=> $company_name,
							'city'  			=> $district,
							'address'			=> $address
						);
						Warehouse::where('id', $warehouse_id)->update($feature_data);
						echo '<pre>';print_r($feature_data);exit;
					}else{
						$feature_data=array(
							'company_name'  	=> $company_name,
							'city'  			=> $district,
							'address'			=> $address
						);
						Warehouse::create($feature_data);
					}

			}

			echo '<pre>';print_r($importData_arr);exit;*/

			//echo '<pre>';print_r($importData_arr);exit;


			$j = 0;
			$brand_data = [];
			foreach ($importData_arr as $importData) {
				$barcode1				= $importData[0];
				$barcode2				= $importData[1];
				$barcode3				= $importData[2];
				$brand_code				= $importData[3];
				$category				= $importData[4];
				$type 					= $importData[5];
				$brand_name 			= $importData[6];
				$size 					= $importData[7];
				$strength				= $importData[8];
				$retailer_margin 		= $importData[9];
				$unit_price				= $importData[10];
				$special_purpose_fee	= $importData[11];
				$mrp					= $importData[12];
				$unit_qty				= $importData[13];




				if ($category != '') {
					$brand_slug 	= $this->create_slug($brand_name);

					$category_title = trim($category);
					$category_result = Category::where('name', $category_title)->where('food_type', 1)->get();
					if (count($category_result) > 0) {
						$category_id = isset($category_result[0]->id) ? $category_result[0]->id : 0;
					} else {
						$feature_data = array(
							'name'  		=> $category_title,
							'food_type'  	=> 1,
							'created_at'	=> date('Y-m-d')
						);
						$feature = Category::create($feature_data);
						$category_id = $feature->id;
					}

					$size_id = 0;
					if ($size != '') {
						$size_arr = explode(' ', $size);
						$size_ml = isset($size_arr[0]) ? trim($size_arr[0]) : 0;

						$size_result = Size::where('ml', $size_ml)->get();

						if (count($size_result) > 0) {
							$size_id = isset($size_result[0]->id) ? $size_result[0]->id : 0;
						} else {
							$size_arr = explode(' ', $size);
							$feature_data = array(
								'name'  		=> $size,
								'ml'  			=> isset($size_arr[0]) ? trim($size_arr[0]) : 0,
								'created_at'	=> date('Y-m-d')
							);
							$feature = Size::create($feature_data);
							$size_id = $feature->id;
						}
					}

					$type_result = Subcategory::where('name', $type)->where('food_type', 1)->get();
					if (count($type_result) > 0) {
						$subcategory_id = isset($type_result[0]->id) ? $type_result[0]->id : 0;
					} else {
						$feature_data = array(
							'name'  		=> $type,
							'food_type'  	=> 1,
							'created_at'	=> date('Y-m-d')
						);
						$feature = Subcategory::create($feature_data);
						$subcategory_id = $feature->id;
					}


					$brand_result = Brand::where('slug', $brand_slug)->get();
					if (count($brand_result) > 0) {
						$brand_id = isset($brand_result[0]->id) ? $brand_result[0]->id : 0;
					} else {
						$feature_data = array(
							'name'  		=> $brand_name,
							'slug'			=> $brand_slug,
							'created_at'	=> date('Y-m-d')
						);
						$feature = Brand::create($feature_data);
						$brand_id = $feature->id;
					}

					$product_result = Product::where('slug', $brand_slug)->where('category_id', $category_id)->where('subcategory_id', $subcategory_id)->get();


					//$n=Product::count();
					//$product_barcode=str_pad($n + 1, 5, 0, STR_PAD_LEFT);
					$product_barcode = '';

					if ($barcode1 != '') {
						$product_barcode = $barcode1;
					}

					if (count($product_result) > 0) {
						$product_id = $product_result[0]->id;
						$product_data = array(
							'product_barcode'	=> $product_barcode,
							'barcode2'			=> $barcode2,
							'barcode3'			=> $barcode3,
							'brand_code'		=> $brand_code,
							'category_id' 		=> $category_id,
							'brand_id' 			=> $brand_id,
							'subcategory_id' 	=> $subcategory_id
						);
						Product::where('id', $product_id)->update($product_data);
					} else {
						$product = Product::create([
							'product_name' 		=> $brand_name,
							'slug' 				=> $brand_slug,
							'product_barcode'	=> $product_barcode,
							'barcode2'			=> $barcode2,
							'barcode3'			=> $barcode3,
							'brand_code'		=> $brand_code,
							'default_qty' 		=> 1,
							'category_id' 		=> $category_id,
							'brand_id' 			=> $brand_id,
							'subcategory_id' 	=> $subcategory_id
						]);
						$product_id = $product->id;
					}

					$product_size_result = ProductRelationshipSize::where('product_id', $product_id)->where('size_id', $size_id)->get();
					//echo '<pre>';print_r($product_size_result);exit;
					if (count($product_size_result) > 0) {
						$size_cost_data = array(
							'product_barcode'		=> $product_barcode,
							'barcode2'				=> $barcode2,
							'barcode3'				=> $barcode3,
							'cost_rate'  			=> $mrp,
							'product_mrp'  			=> $mrp,
							'strength'  			=> $strength,
							'retailer_margin'  		=> $retailer_margin,
							'round_off'  			=> $unit_price,
							'special_purpose_fee'  	=> $special_purpose_fee,
							'free_discount_percent' => 0,
							'free_discount_amount'  => 0,
							'bottle_case'			=> $unit_qty
						);
						//echo '<pre>';print_r($size_cost_data);exit;
						ProductRelationshipSize::where('id', $product_size_result[0]->id)->update($size_cost_data);
					} else {
						$size_cost_data = array(
							'product_id'  			=> $product_id,
							'product_barcode'		=> $product_barcode,
							'barcode2'				=> $barcode2,
							'barcode3'				=> $barcode3,
							'size_id'  				=> $size_id,
							'cost_rate'  			=> $mrp,
							'product_mrp'  			=> $mrp,
							'strength'  			=> $strength,
							'retailer_margin'  		=> $retailer_margin,
							'round_off'  			=> $unit_price,
							'special_purpose_fee'  	=> $special_purpose_fee,
							'free_discount_percent' => 0,
							'free_discount_amount'  => 0,
							'bottle_case'			=> $unit_qty,
							'created_at'			=> date('Y-m-d')
						);
						ProductRelationshipSize::create($size_cost_data);
					}

					//echo '<pre>';print_r($size_cost_data);exit;

					$current_year = date('Y');
					$product_result = MasterProducts::where('product_name', $brand_name)->where('year', $current_year)->where('category_id', $category_id)->where('subcategory_id', $subcategory_id)->where('size_id', $size_id)->get();
					if (count($product_result) > 0) {
						$master_data = array(
							'product_barcode'		=> $product_barcode,
							'barcode2'				=> $barcode2,
							'barcode3'				=> $barcode3,
							'brand_code'			=> $brand_code,
							'product_name'  		=> $brand_name,
							'slug'					=> $brand_slug,
							'mrp'  					=> $mrp,
							'category_id'  			=> $category_id,
							'size_id'  				=> $size_id,
							'brand_id'  			=> $brand_id,
							'subcategory_id'  		=> $subcategory_id,
							'strength'  			=> $strength,
							'retailer_margin'  		=> $retailer_margin,
							'round_off'  			=> $unit_price,
							'special_purpose_fee'  	=> $special_purpose_fee,
							'qty'  					=> $unit_qty,
							'updated_at'			=> date('Y-m-d'),
						);
						//echo '<pre>';print_r($master_data);exit;
						MasterProducts::where('id', $product_result[0]->id)->update($master_data);
					} else {
						$master_data = array(
							'product_barcode'		=> $product_barcode,
							'barcode2'				=> $barcode2,
							'barcode3'				=> $barcode3,
							'brand_code'			=> $brand_code,
							'product_name'  		=> $brand_name,
							'slug'					=> $brand_slug,
							'mrp'  					=> $mrp,
							'category_id'  			=> $category_id,
							'size_id'  				=> $size_id,
							'brand_id'  			=> $brand_id,
							'subcategory_id'  		=> $subcategory_id,
							'strength'  			=> $strength,
							'retailer_margin'  		=> $retailer_margin,
							'round_off'  			=> $unit_price,
							'special_purpose_fee'  	=> $special_purpose_fee,
							'qty'  					=> $unit_qty,
							'year'  				=> $current_year,
							'created_at'			=> date('Y-m-d')
						);
						MasterProducts::create($master_data);
					}
					//echo '<pre>';print_r($product_result);exit;


					//echo $j.'</br>';

					$brand_data[] = array(
						'barcode1'		=> $barcode1,
						'barcode2'		=> $barcode2,
						'barcode3'		=> $barcode3,
						'brand_code'	=> $brand_code,
						'brand_name'	=> $brand_name,
					);
				}

				//exit;
				$j++;
			}

			//echo '<pre>';print_r($brand_data);exit;

			return redirect()->back()->with('success', 'Product created successfully');
		}
	}

	public function create_slug($string)
	{
		$replace = '-';
		$string = strtolower($string);
		//replace / and . with white space
		$string = preg_replace("/[\/\.]/", " ", $string);
		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);

		//remove multiple dashes or whitespaces
		$string = preg_replace("/[\s-]+/", " ", $string);

		//convert whitespaces and underscore to $replace
		$string = preg_replace("/[\s_]/", $replace, $string);

		//limit the slug size
		$string = substr($string, 0, 100);

		//slug is generated
		return $string;
	}

	//Product import excel
	public function product_excel_import(Request $request)
	{
		if ($request->hasFile('product_excel')) {
			$file = $request->file('product_excel');
			Excel::import(new ProductsImport, $file);
			return redirect()->back()->with('success', 'Product import successfully');
		} else {
			return redirect()->back()->with('error', 'Something error occurs!');
		}
	}
}
