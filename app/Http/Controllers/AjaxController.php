<?php

namespace App\Http\Controllers;

use App\Helper\Media;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Abcdefg;
use App\Models\Material;
use App\Models\Service;
use App\Models\Size;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\MasterProducts;
use App\Models\VendorCode;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\StockTransferHistory;

use App\Models\Company;
use App\Models\Dosage;
use App\Models\Drugstore;



use App\Models\Counter;
use App\Models\StockTransferCounterHistory;
use App\Models\CounterWiseStock;


use App\Models\SupplierGst;
use App\Models\ProductRelationshipSize;

use App\Models\PurchaseInwardStock;
use App\Models\InwardStockProducts;

use App\Models\BranchStockProducts;
use App\Models\BranchStockProductSellPrice;

use App\Models\RestaurantFloor;
use App\Models\FloorWiseTable;
use App\Models\TableBookingHistory;
use App\Models\BarProductSizePrice;
use App\Models\Customer;

use App\Models\BranchStockRequest;

use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

use Auth;
use DB;


class AjaxController extends Controller {

	public function ajaxpost(Request $request){
		$action = $request->action;
		$this->{'ajaxpost_' . $action}($request);
	}

	public function ajaxpost_get_product_by_brandId($request) {
		$brand_id	= $request->brand_id;
		$res = Product::where('brand_id',$brand_id)->get();

		$result=[];

		foreach($res as $row){
			$result[]=array(
				'id'=>$row->id,
				'product_name'=>$row->product_name,
			);
		}

		$return_data['result']	= $result;
		$return_data['status']	= 1;

		echo json_encode($return_data);
	}

	public function ajaxpost_requested_stock($request) {
		$stock_id	= trim($request->stock_id);
		$store_id	= trim($request->store_id);
		
		
		//$BranchStockRequestResult=BranchStockRequest::where('stock_id',$stock_id)->where('from_store_id',$store_id)

		$branchStockRequestResult=BranchStockRequest::where('stock_id',$stock_id)->where('status',1)->where('from_store_id',$store_id)->groupBy('to_store_id')->selectRaw('sum(r_qty) as t_qty, to_store_id, product_id')->get();

		//print_r($branchStockRequestResult);exit;

		$result=[];
		foreach($branchStockRequestResult as $row){
			$result[]=array(
				'to_store_id'	=> $row->to_store_id,
				't_qty'			=> $row->t_qty,
				'product_id'	=> $row->product_id,
				'store_name'	=> $row->user->name,

			);
		}

		$return_data['status']	= 1;
		$return_data['result']	= $result;

		//print_r($result);exit;
		echo json_encode($return_data);
		exit;
	}

	public function ajaxpost_getProductByKeyup(Request $request){
        $search = $request->search;
        $result = Product::select('id','product_barcode','brand')
                            ->where('brand', 'LIKE', "%{$search}%")
                            ->orWhere('product_barcode', 'LIKE', "%{$search}%")
                            ->take(20)->get();
        $return_data['result']	= $result;
		$return_data['status']	= 1;
		echo json_encode($return_data);
    }

	public function ajaxpost_requested_stock_accept(Request $request){
        $stock_id = $request->stock_id;

		//print_r($_POST);exit;

		if($stock_id!=''){
			$result = BranchStockRequest::where('id',$stock_id)->get();

			//print_r($result);exit;
			
			if(count($result)>0){
				$req_qty			= $result[0]->r_qty;
				$product_id			= $result[0]->product_id;
				$product_barcode	= $result[0]->product_barcode;
				$from_store_id		= $result[0]->from_store_id;
				$to_store_id		= $result[0]->to_store_id;

				$product_mrp		= $result[0]->product_mrp;
				$net_price			= $result[0]->net_price;
				$selling_price		= $result[0]->selling_price;

				$branchProductStockResult=BranchStockProducts::where('product_mrp',$product_mrp)->where('branch_id',$to_store_id)->where('product_id',$product_id)->get();
				$avaibleStock=0;
				$prev_qty=0;
				$update_qty=0;
				if(count($branchProductStockResult)>0){
					$avaibleStock +=isset($branchProductStockResult[0]->t_qty)?$branchProductStockResult[0]->t_qty:0;
					$prev_qty +=isset($branchProductStockResult[0]->t_qty)?$branchProductStockResult[0]->t_qty:0;

					$update_qty +=$avaibleStock;
					$update_qty +=$req_qty;

					$branch_stock_id=isset($branchProductStockResult[0]->id)?$branchProductStockResult[0]->id:'';
					BranchStockProducts::where('id', $branch_stock_id)->update(['t_qty' => $update_qty]);
				}else{
					$productInfo=Product::where('id',$product_id)->first();
					// $product_mrp	= isset($productInfo->product_mrp)?$productInfo->product_mrp:0;
					// $net_price		= isset($productInfo->net_price)?$productInfo->net_price:0;
					// $selling_price	= isset($productInfo->selling_price)?$productInfo->selling_price:0;

					$branchStocktData=array(
						'branch_id'			=> $to_store_id,
						'product_id'		=> $product_id,
						'product_barcode'	=> $product_barcode,
						't_qty'  			=> $req_qty,
						'product_mrp'  		=> $product_mrp,
						'net_price'			=> $net_price,
						'selling_price'		=> $selling_price,
					);
					BranchStockProducts::create($branchStocktData);
				}

				BranchStockRequest::where('id', $stock_id)->update(['status' =>2]);

				StockTransferHistory::where('product_mrp', $product_mrp)->where('branch_id', $from_store_id)->where('transfer_to', $to_store_id)->where('product_id', $product_id)->update(['status' =>2]);

				$return_data['status']	= 1;
				echo json_encode($return_data);exit;
				
			}

		}

		exit;
    }
	public function ajaxpost_requested_stock_reject(Request $request){
        $stock_id = $request->stock_id;

		if($stock_id!=''){
			$result = BranchStockRequest::where('id',$stock_id)->get();
			if(count($result)>0){
				$req_qty			= $result[0]->r_qty;
				$product_id			= $result[0]->product_id;
				$product_barcode	= $result[0]->product_barcode;
				$from_store_id		= $result[0]->from_store_id;
				$to_store_id		= $result[0]->to_store_id;

				$product_mrp		= $result[0]->product_mrp;
				$net_price			= $result[0]->net_price;
				$selling_price		= $result[0]->selling_price;



				$branchProductStockResult=BranchStockProducts::where('product_mrp',$product_mrp)->where('branch_id',$from_store_id)->where('product_id',$product_id)->get();

				$avaibleStock=0;
				$update_qty=0;
				if(count($branchProductStockResult)>0){
					$avaibleStock +=isset($branchProductStockResult[0]->t_qty)?$branchProductStockResult[0]->t_qty:0;
					$update_qty +=$avaibleStock;
					$update_qty +=$req_qty;
					$branch_stock_id=isset($branchProductStockResult[0]->id)?$branchProductStockResult[0]->id:'';
					BranchStockProducts::where('id', $branch_stock_id)->update(['t_qty' => $update_qty]);
				}

				BranchStockRequest::where('id', $stock_id)->update(['status' =>3]);
				StockTransferHistory::where('product_mrp', $product_mrp)->where('branch_id', $from_store_id)->where('transfer_to', $to_store_id)->where('product_id', $product_id)->update(['status' =>3]);
				
				$return_data['status']	= 1;
				echo json_encode($return_data);exit;
			}

		}

		exit;
    }

	public function ajaxpost_requested_stock_accept_old(Request $request){
        $stock_id = $request->stock_id;

		//print_r($_POST);exit;

		if($stock_id!=''){
			$result = BranchStockRequest::where('id',$stock_id)->get();
			
			if(count($result)>0){
				$req_qty			= $result[0]->r_qty;
				$product_id			= $result[0]->product_id;
				$product_barcode	= $result[0]->product_barcode;
				$from_store_id		= $result[0]->from_store_id;
				$to_store_id		= $result[0]->to_store_id;

				$branchProductStockResult=BranchStockProducts::where('branch_id',$to_store_id)->where('product_id',$product_id)->get();
				$avaibleStock=0;
				$prev_qty=0;
				if(count($branchProductStockResult)>0){
					$avaibleStock +=isset($branchProductStockResult[0]->t_qty)?$branchProductStockResult[0]->t_qty:0;
					$prev_qty +=isset($branchProductStockResult[0]->t_qty)?$branchProductStockResult[0]->t_qty:0;
				}
				
				if($avaibleStock<$req_qty){
					$return_data['status']	= 0;
					$return_data['msg']		= 'Stock qty is lower then req qty!';
					echo json_encode($return_data);exit;

				}else{
					$fromBranchProductStockResult=BranchStockProducts::where('branch_id',$from_store_id)->where('product_id',$product_id)->get();
					$storeAvaibleStock=0;
					if(count($fromBranchProductStockResult)>0){
						$storeAvaibleStock +=isset($fromBranchProductStockResult[0]->t_qty)?$fromBranchProductStockResult[0]->t_qty:0;
					}
					$storeAvaibleStock +=$req_qty;
					$avaibleStock -=$req_qty;

					$sell_price_id=isset($fromBranchProductStockResult[0]->id)?$fromBranchProductStockResult[0]->id:'';
					BranchStockProducts::where('id', $sell_price_id)->update(['t_qty' => $storeAvaibleStock]);

				
					$deduct_stock_id=isset($branchProductStockResult[0]->id)?$branchProductStockResult[0]->id:'';
					BranchStockProducts::where('id', $deduct_stock_id)->update(['t_qty' => $avaibleStock]);

					$stocktransferData=array(
						'stock_id'		=> $deduct_stock_id,
						'branch_id'  	=> $to_store_id,
						'product_id'  	=> $product_id,
						'prev_qty'		=> $prev_qty,
						't_qty'  		=> $req_qty,
						'transfer_to'  	=> $from_store_id,
					);
					//print_r($stocktransferData);exit;
					
					StockTransferHistory::create($stocktransferData);

					BranchStockRequest::where('id', $stock_id)->update(['status' =>2]);

					

					$return_data['status']	= 1;
					echo json_encode($return_data);exit;
				}
			}

		}

		exit;
    }
	
	/*COUNTER POS*/
	public function ajaxpost_update_stock_product_qty($request) {
		$branch_id	= $request->branch_id;
		$product_id	= $request->product_id;
		$size_id	= $request->size_id;
		$stock_id	= $request->stock_id;
		$qty		= $request->qty;
		
		
		$productRelationshipSizeResult=ProductRelationshipSize::where('product_id',$product_id)->where('size_id',$size_id)->get();
		$product_mrp=isset($productRelationshipSizeResult[0]->cost_rate)?$productRelationshipSizeResult[0]->cost_rate:'';						
		$barcode=isset($productRelationshipSizeResult[0]->product_barcode)?$productRelationshipSizeResult[0]->product_barcode:'';
		$barcode2=isset($productRelationshipSizeResult[0]->barcode2)?$productRelationshipSizeResult[0]->barcode2:'';
		$barcode3=isset($productRelationshipSizeResult[0]->barcode3)?$productRelationshipSizeResult[0]->barcode3:'';
		
		
		
		
		
		$branch_product_stock_info=BranchStockProducts::where('id',$stock_id)->get();
		
		if(count($branch_product_stock_info)>0){
			$branch_product_stock_sell_price_info=BranchStockProductSellPrice::where('stock_id',$branch_product_stock_info[0]->id)->where('selling_price',$product_mrp)->where('stock_type','counter')->get();
			$sell_price_id=isset($branch_product_stock_sell_price_info[0]->id)?$branch_product_stock_sell_price_info[0]->id:'';
			
			if($sell_price_id!=''){
				BranchStockProductSellPrice::where('id', $sell_price_id)->where('stock_type', 'counter')->update(['c_qty' => $qty]);
			}else{
				$branchProductStockSellPriceData=array(
					'stock_id'		=> $branch_product_stock_info[0]->id,
					'w_qty'  		=> 0,
					'c_qty'  		=> $qty,
					'selling_price'	=> $product_mrp,
					'offer_price'  	=> 0,
					'product_mrp'  	=> $product_mrp,
					'stock_type'  	=> 'counter',
					'created_at'	=> date('Y-m-d')
				);
				
				BranchStockProductSellPrice::create($branchProductStockSellPriceData);
			}
		}
		
		$return_data['status']	= 1;
		echo json_encode($return_data);
		
	}
	/*END COUNTER POS*/
	
	public function ajaxpost_set_update_stock($request) {
		$prev_w_qty		= $request->prev_w_qty;
		$prev_c_qty		= $request->prev_c_qty;
		$new_w_qty		= $request->new_w_qty;
		$new_c_qty		= $request->new_c_qty;
		$stock_id		= $request->stock_id;
		$price_id		= $request->price_id;
		$transfer_to	= $request->transfer_to;
		
		BranchStockProductSellPrice::where('id', $price_id)->where('stock_id', $stock_id)->where('stock_type', 'counter')->update(['w_qty' => $new_w_qty,'c_qty' => $new_c_qty]);
		
		$stocktransferData=array(
			'stock_id'		=> $stock_id,
			'price_id'  	=> $price_id,
			'prev_w_qty'  	=> $prev_w_qty,
			'prev_c_qty'	=> $prev_c_qty,
			'new_w_qty'  	=> $new_w_qty,
			'new_c_qty'  	=> $new_c_qty,
			'transfer_to'  	=> $transfer_to,
		);
		//print_r($stocktransferData);exit;
		StockTransferHistory::create($stocktransferData);
		
		
		$return_data['status']	= 1;
		echo json_encode($return_data);exit;
	}
	
	
	


	public function ajaxpost_get_sell_product_search($request) {
		$search				= $request->search;
		$searchTerm 		= $search;
		$reservedSymbols 	= ['-', '+', '<', '>', '@', '(', ')', '~'];
		$searchTerm 		= str_replace($reservedSymbols, ' ', $searchTerm);
		$searchValues 		= preg_split('/\s+/', $searchTerm, -1, PREG_SPLIT_NO_EMPTY);

		//print_r($searchValues);exit;
		$store_id	= Session::get('store_id');
		$queryInwardStockProduct = InwardStockProducts::query();
		$queryInwardStockProduct->where('branch_id',$store_id);
		$queryInwardStockProduct->with('product',function($q) use ($search){
			$q->where('product_barcode', 'like', "%{$search}%")
			->orWhere('brand', 'like', '%' . $search . '%')->take(20);
		});
		$res= $queryInwardStockProduct->groupBy('product_id')->get();
		//dd($res);
		/* $res=MasterProducts::query()->where('product_name', 'LIKE', "%{$search}%")->orWhere('product_barcode', $search)->orWhere('barcode2', $search)->orWhere('barcode3', $search)->take(20)->get(); */
		$result=[];

		if(count($res) > 0){
			foreach($res as $row){
				if($row->product){
				$result[]=array(
					//'id'				=> $row->id,
					'id'				=> @$row->product->id,
					'product_barcode'	=> @$row->product->product_barcode,
					'product_name'		=> @$row->product->product_name,
					//'product_size'		=> $row->size->name,
				);
				}
			}
		}
		

		//print_r($result);exit;

		$return_data['result']	= $result;
		$return_data['status']	= 1;

		echo json_encode($return_data);
	}
	
	public function ajaxpost_get_sell_product_barcode_search($request) {
		$search				= $request->search;
		$result=[];
		if($search!=''){
			$store_id	= Session::get('store_id');
			$queryProduct = Product::query();
			$queryProduct->where('product_barcode',$search);
			$queryProduct->whereHas('inwardStockProduct',function($q) use ($store_id){
				$q->where('branch_id', $store_id);
			});
			$res= $queryProduct->first();
			//dd($res);
			if($res){
				$result[]=array(
					'id'				=> isset($res->id)?$res->id:'',
					'product_barcode'	=> isset($res->product_barcode)?trim($res->product_barcode):'',
					'product_name'		=> isset($res->product_name)?trim($res->product_name):'',
					//'product_size'		=> isset($res[0]->size->name)?trim($res[0]->size->name):''
				);
			}
		}
		
		$return_data['result']	= $result;
		$return_data['status']	= 1;

		echo json_encode($return_data);
	}

	public function ajaxpost_get_sell_product_byId($request) {
		$search_id	= $request->product_id;
		//echo $search_id;die;
		$product = Product::find($search_id);
		//dd($product);
		$branch_id=Session::get('store_id');
		//echo $branch_id;die;
		$return_data=[];
		$product_result=[];
		if(isset($product->id)){
			if($product->id!=''){
				$brand_slug		= $product->slug;
				$category_id	= $product->category_id;
				$subcategory_id	= $product->subcategory_id;
				$product_barcode	= $product->product_barcode;

				//$item_result = Product::where('slug',$brand_slug)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->get();
				$product_id	 = $product->id;

				$branch_stock_product_result = BranchStockProducts::where('branch_id',$branch_id)->where('product_id',$product_id)->get();
				//dd($branch_stock_product_result);
				$branch_stock_product_id	= isset($branch_stock_product_result[0]->id)?$branch_stock_product_result[0]->id:'';

				if($branch_stock_product_id!=''){
					/* $branch_product_stock_sell_price_info=BranchStockProductSellPrice::where('stock_id',$branch_stock_product_id)->where('stock_type','counter')->get(); */
					if(count($branch_stock_product_result)>0){
						$item_prices=[];
						foreach($branch_stock_product_result as $row){
							$item_prices[]=array(
								'price_id'		=> $row->id,
								'selling_price'	=> $row->selling_price,
								'offer_price'	=> 0,
								'product_mrp'	=> $row->product_mrp,
								't_qty'			=> $row->t_qty,
								'product_name'	=> $row->product->product_name,
								'brand_name'	=> $row->product->brand,
								'product_barcode'	=> $row->product->product_barcode,
								'selling_by_name'	=> $row->product->selling_by_name,
								'product_expiry_date'	=> $row->product_expiry_date,
								//'c_qty'			=> $row->c_qty,
								'net_price'					=> $row->net_price,
								'is_chronic'				=> $row->is_chronic,
								'chronic_amount'			=> $row->chronic_amount,
							);
						}

						$product_result=array(
							'product_id'				=> $product_id,
							'branch_stock_product_id'	=> $branch_stock_product_id,
							'product_barcode'			=> $product_barcode,
							'brand_name'				=> $product->brand,
							'product_name'				=> $product->product_name,
							'item_prices'				=> $item_prices,
						);
						$return_data['status']	= 1;
						$return_data['product_result']	= $product_result;
					}else{
						$return_data['status']	= 0;
					}
				}else{
					$return_data['status']	= 0;
				}
			}else{
				$return_data['status']	= 0;
			}
		}else{
			$return_data['status']	= 0;
		}
		//dd($return_data);
		echo json_encode($return_data);
	}

	public function ajaxpost_get_product($request) {
		$search	= $request->search;
		$searchTerm =$search;
		$reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
		$searchTerm = str_replace($reservedSymbols, ' ', $searchTerm);
		$searchValues = preg_split('/\s+/', $searchTerm, -1, PREG_SPLIT_NO_EMPTY);

		//print_r($searchValues);exit;

		//$res=Product::query()->where('product_barcode', 'LIKE', "%{$search}%")->take(20)->get();
		//print_r($size_result);exit;

		$res = Product::where(function ($q) use ($searchValues) {
			foreach ($searchValues as $value) {
				$q->where('product_barcode', 'like', "%{$value}%")
				->orWhere('brand', 'like', '%' . $value . '%');
			}
		})->take(20)->get();

		$result=[];

		foreach($res as $row){
			$result[]=array(
				'id'=>$row->id,
				'product_barcode'=>$row->product_barcode,
				'brand'=>$row->brand,
			);
		}

		$return_data['result']	= $result;
		$return_data['status']	= 1;

		echo json_encode($return_data);
	}
	public function ajaxpost_get_product_by_scanner($request){
		$barcode	= $request->barcode;
		$product = Product::where('product_barcode', $barcode)->first();
		$status=1;
		$product_result=[];
		if($product){
			$product_result=array(
				'product_id'		=> $product->id,
				'product_name'			=> $product->product_name,
			);
		}else{
			$status=0;
		}
		
		$return_data['result']	= $product_result;
		$return_data['status']	= $status;
		echo json_encode($return_data);

	}
	public function ajaxpost_get_product_byId($request) {
		$product_id	= $request->product_id;
		$res = Product::find($product_id);
		
		$product_result=[];
		if(isset($res->id)){
			if($res->id!=''){
				$product_result=array(
					'product_id'		=> $res->id,
					'barcode'			=> $res->product_barcode,
					'product_name'		=> $res->product_name,
					'brand_name'		=> $res->brand,
					'dosage'			=> $res->dosage_name,
					'company'			=> $res->company_name,
					'drugstore'			=> $res->drugstore_name,
					'quantity'			=> $res->total_qty,
					'package'			=> $res->no_package,
					'selling_by'		=> $res->selling_by_name,
					'selling_type'		=> $res->selling_by,
					'net_price'			=> $res->net_price,
					'price'				=> $res->cost_price,
					'bonous'			=> $res->bonous,
					'rate'				=> $res->cost_rate,
					'total_quantity'	=> $res->total_qty,
					'sell_price'		=> $res->selling_price,
					'profit'			=> $res->profit_amount,
					'profit_percent'	=> $res->profit_percent,
					'is_chronic'		=> ucfirst(trans($res->is_chronic)),
				);
			}
		}
		$status=0;
		if(count($product_result)>0){
			$status=1;
		}
		$return_data['result']	= $product_result;
		$return_data['status']	= $status;
		echo json_encode($return_data);
	}

	public function ajaxpost_get_suppliers($request) {
		$search	= $request->search;
		$searchTerm =$search;
		$reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
		$searchTerm = str_replace($reservedSymbols, ' ', $searchTerm);
		$searchValues = preg_split('/\s+/', $searchTerm, -1, PREG_SPLIT_NO_EMPTY);

		//print_r($searchValues);exit;



		$res = Supplier::where(function ($q) use ($searchValues) {
			foreach ($searchValues as $value) {
				$q->orWhere('company_name', 'like', "%{$value}%");
				$q->orWhere('first_name', 'like', "%{$value}%");
				$q->orWhere('last_name', 'like', "%{$value}%");
			}
		})->get();

		$result=[];


		foreach($res as $row){
			$result[]=array(
				'id'=>$row->id,
				'company_name'	=> $row->company_name,
				'first_name'	=> $row->first_name,
				'last_name'		=> $row->last_name,
			);

		}

		$return_data['result']	= $result;
		$return_data['status']	= 1;

		echo json_encode($return_data);
	}

	public function ajaxpost_get_supplier_byId($request) {
		$company_id	= $request->company_id;
		$res = Supplier::find($company_id);
		$supplierGst=SupplierGst::where('supplier_id',$company_id)->get();
		$return_data['supplier']		= $res;
		$return_data['supplier_gst']	= $supplierGst;
		$return_data['status']			= 1;
		echo json_encode($return_data);
	}

	public function ajaxpost_get_warehouse($request) {
		$search	= $request->search;
		$searchTerm =$search;
		$reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
		$searchTerm = str_replace($reservedSymbols, ' ', $searchTerm);
		$searchValues = preg_split('/\s+/', $searchTerm, -1, PREG_SPLIT_NO_EMPTY);

		//print_r($searchValues);exit;

		$res = Warehouse::where(function ($q) use ($searchValues) {
			foreach ($searchValues as $value) {
				$q->orWhere('company_name', 'like', "%{$value}%");
			}
		})->get();

		$result=[];


		foreach($res as $row){
			$result[]=array(
				'id'=>$row->id,
				'company_name'	=> $row->company_name,
			);
		}

		$return_data['result']	= $result;
		$return_data['status']	= 1;

		//print_r($return_data);exit;

		echo json_encode($return_data);
	}

	public function ajaxpost_get_warehouse_byId($request) {
		$company_id	= $request->company_id;
		$res = Warehouse::find($company_id);
		$return_data['warehouse']		= $res;
		$return_data['status']			= 1;
		echo json_encode($return_data);
	}



	public function ajaxpost_add_new_product($request) {
		$inward_stock	= $request->inward_stock;

		$product_invoice_items	= [];

		if(isset($inward_stock)){
			if(count($inward_stock)>0){
				for($i=0;count($inward_stock)>$i;$i++){
					$barcode		= $inward_stock[$i]['new_product_barcode'];
					$bottle_case	= $inward_stock[$i]['new_product_bottle_case'];
					$in_case		= $inward_stock[$i]['p_new_product_in_case'];
					$category		= $inward_stock[$i]['new_product_category'];
					$sub_category	= $inward_stock[$i]['new_product_sub_category'];
					$brand_name		= $inward_stock[$i]['new_brand_name'];
					$batch_no		= $inward_stock[$i]['new_batch_no'];
					$measure		= $inward_stock[$i]['new_measure'];
					$strength		= $inward_stock[$i]['new_strength'];
					$bl				= $inward_stock[$i]['new_bl'];
					$lpl			= $inward_stock[$i]['new_lpl'];
					$retailer_margin= $inward_stock[$i]['new_retailer_margin'];
					$round_off		= $inward_stock[$i]['new_round_off'];
					$sp_fee			= $inward_stock[$i]['new_sp_fee'];
					$product_mrp	= $inward_stock[$i]['new_product_mrp'];
					$total_cost		= $inward_stock[$i]['new_product_total_cost'];

					$size='';
					if($measure!=''){
						$size=rtrim($measure, '.');
						$size=strtolower($size);
					}

					//echo '<pre>';print_r($size);exit;

					$category_title=trim($category);
					$category_result=Category::where('name',$category_title)->get();
					if(count($category_result)>0){
						$category_id=isset($category_result[0]->id)?$category_result[0]->id:0;
					}else{
						$feature_data=array(
							'name'  		=> $category_title,
							'created_at'	=> date('Y-m-d')
						);
						$feature=Category::create($feature_data);
						$category_id=$feature->id;
					}



					$size_result=Size::where('name',$size)->get();
					if(count($size_result)>0){
						$size_id=isset($size_result[0]->id)?$size_result[0]->id:0;
					}else{
						$feature_data=array(
							'name'  		=> $size,
							'created_at'	=> date('Y-m-d')
						);
						$feature=Size::create($feature_data);
						$size_id=$feature->id;
					}

					$type_result=Subcategory::where('name',$sub_category)->get();
					if(count($type_result)>0){
						$subcategory_id=isset($type_result[0]->id)?$type_result[0]->id:0;
					}else{
						$feature_data=array(
							'name'  		=> $sub_category,
							'created_at'	=> date('Y-m-d')
						);
						$feature=Subcategory::create($feature_data);
						$subcategory_id=$feature->id;
					}



					$brand_slug	= Media::create_slug(trim($brand_name));

					$brand_result=Brand::where('slug',$brand_slug)->get();
					if(count($brand_result)>0){
						$brand_id=isset($brand_result[0]->id)?$brand_result[0]->id:0;
					}else{
						$feature_data=array(
							'name'  		=> $brand_name,
							'slug'			=> $brand_slug,
							'created_at'	=> date('Y-m-d')
						);
						$feature=Brand::create($feature_data);
						$brand_id=$feature->id;
					}

					$product_result=Product::where('slug',$brand_slug)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->get();
					$n=Product::count();
					$product_barcode=str_pad($n + 1, 5, 0, STR_PAD_LEFT);

					if($barcode!=''){
						$product_barcode=$barcode;
					}

					if(count($product_result)>0){
						$product_id=$product_result[0]->id;
					}else{
						$product = Product::create([
							'product_name' 		=> $brand_name,
							'slug' 				=> $brand_slug,
							'product_barcode'	=> $product_barcode,
							'default_qty' 		=> 1,
							'category_id' 		=> $category_id,
							'brand_id' 			=> $brand_id,
							'subcategory_id' 	=> $subcategory_id
						]);
						$product_id=$product->id;
						//$product_id=3000+$i;
					}

					$product_size_result=ProductRelationshipSize::where('product_id',$product_id)->where('size_id',$size_id)->get();
					if(count($product_size_result)>0){
					}else{
						$size_cost_data=array(
							'product_id'  			=> $product_id,
							'size_id'  				=> $size_id,
							'cost_rate'  			=> $product_mrp,
							'product_mrp'  			=> $product_mrp,
							'strength'  			=> $strength,
							'retailer_margin'  		=> $retailer_margin,
							'round_off'  			=> $round_off,
							'special_purpose_fee'  	=> $sp_fee,
							'free_discount_percent' => 0,
							'free_discount_amount'  => 0,
							'created_at'			=> date('Y-m-d')
						);
						ProductRelationshipSize::create($size_cost_data);
					}

					$total_qty=$bottle_case*$in_case;

					$product_invoice_items[]=array(
						'product_id'		=> $product_id,
						'product_barcode'	=> $product_barcode,
						'category'			=> $category_title,
						'category_id'		=> $category_id,
						'sub_category'		=> $sub_category,
						'subcategory_id'	=> $subcategory_id,
						'brand_name'		=> trim($brand_name),
						'brand_slug'		=> $brand_slug,
						'measure'			=> $size,
						'size_id'			=> $size_id,
						'batch_no'			=> trim($batch_no),
						'strength'			=> trim($strength),
						'retailer_margin'	=> trim($retailer_margin),
						'round_off'			=> trim($round_off),
						'sp_fee'			=> trim($sp_fee),
						'total_cases'		=> $in_case,
						'in_cases'			=> $bottle_case,
						'qty'				=> $total_qty,
						'bl'				=> $bl,
						'lpl'				=> $lpl,
						'product_mrp'		=> trim($product_mrp),
						'unit_cost'			=> trim($product_mrp),
						'total_cost'		=> trim($total_cost)
					);






					$current_year=date('Y');
					$product_result=MasterProducts::where('product_name',$brand_name)->where('year',$current_year)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->where('size_id',$size_id)->get();

					if(count($product_result)>0){
					}else{
						$master_data=array(
							'product_barcode'  		=> $product_barcode,
							'product_name'  		=> $brand_name,
							'slug'					=> $brand_slug,
							'mrp'  					=> $product_mrp,
							'category_id'  			=> $category_id,
							'size_id'  				=> $size_id,
							'brand_id'  			=> $brand_id,
							'subcategory_id'  		=> $subcategory_id,
							'strength'  			=> $strength,
							'retailer_margin'  		=> $retailer_margin,
							'round_off'  			=> $round_off,
							'special_purpose_fee'  	=> $sp_fee,
							'qty'  					=> $in_case,
							'year'  				=> $current_year,
							'created_at'			=> date('Y-m-d')
						);

						MasterProducts::create($master_data);
					}

				}
			}
		}

		$return_data['result']	= $product_invoice_items;
		$return_data['success']	= 0;
		echo json_encode($return_data);

	}

	public function ajaxpost_add_inward_stock($request) {

	DB::beginTransaction();

	try {
		//dd($request->all());
		$branch_id=Auth::user()->id;
		
		$inward_stock	= $request->inward_stock;
		//echo '<pre>';print_r($inward_stock);exit;

		

		if($inward_stock['invoice_no']!=''){
			$invoice_no=$inward_stock['invoice_no'];
			$count=PurchaseInwardStock::where('invoice_no',$invoice_no)->count();
			//$count=0;
			if($count>0){
				$return_data['msg']		= 'This invoice already exists!';
				$return_data['status']	= 0;
				echo json_encode($return_data);exit;
			}else{

				$product_total_profit=0;
				$productItemErrorMsg=[];
				if(isset($inward_stock['product_detail'])){
					if(count($inward_stock['product_detail'])>0){
						for($i=0;count($inward_stock['product_detail'])>$i;$i++){
							$product_total_profit += isset($inward_stock['product_detail'][$i]['product_profit'])?$inward_stock['product_detail'][$i]['product_profit']:0;
							$product_quantity	= isset($inward_stock['product_detail'][$i]['product_quantity'])?$inward_stock['product_detail'][$i]['product_quantity']:0;
							if($product_quantity==0 || $product_quantity==''){
								$productItemErrorMsg[]='Quantity should not be empty or 0!';
							}
							$product_price	= isset($inward_stock['product_detail'][$i]['product_price'])?$inward_stock['product_detail'][$i]['product_price']:0;
							if($product_price==0 || $product_price==''){
								$productItemErrorMsg[]='Price should not be empty or 0!';
							}
							$product_sellPrice	= isset($inward_stock['product_detail'][$i]['product_sellPrice'])?$inward_stock['product_detail'][$i]['product_sellPrice']:0;
							if($product_sellPrice==0 || $product_sellPrice==''){
								$productItemErrorMsg[]='Sell Price should not be empty or 0!';
							}
							$product_expiry_date	= isset($inward_stock['product_detail'][$i]['product_expiry_date'])?$inward_stock['product_detail'][$i]['product_expiry_date']:'';
							if($product_expiry_date==''){
								$productItemErrorMsg[]='Product Expiry Date should not be empty!';
							}
						}
					}else{
						$return_data['msg']		= 'items not exists!';
						$return_data['status']	= 0;
						echo json_encode($return_data);exit;
					}
				}else{
					$return_data['msg']		= 'items not exists!';
					$return_data['status']	= 0;
					echo json_encode($return_data);exit;
				}

				if(count($productItemErrorMsg)>0){
					$return_data['msg']		= $productItemErrorMsg[0];
					$return_data['status']	= 0;
					echo json_encode($return_data);exit;
				}
				
				$branch_id=$inward_stock['store_id'];
				$purchaseStockData=array(
					'branch_id'  		=> $inward_stock['store_id'],
					'supplier_id'  		=> $inward_stock['supplier_id'],
					'supplier_name'  	=> $inward_stock['supplier_name'],
					'invoice_no'  		=> $inward_stock['invoice_no'],
					'purchase_date'  	=> $inward_stock['invoice_purchase_date'],
					// 'inward_date'  		=> $inward_stock['invoice_inward_date'],
					'payment_method'  	=> $inward_stock['payment_method'],
					'payment_debt_day'  => $inward_stock['payment_debt_day'],
					'payment_date'  	=> $inward_stock['payment_date'],
					'payment_discount'  => $inward_stock['payment_discount'],
					'payment_ref_no'  	=> $inward_stock['payment_ref_no'],
					'total_qty'  		=> $inward_stock['total_qty'],
					'gross_amount'  	=> $inward_stock['gross_amount'],
					'sub_total'  		=> $inward_stock['sub_total'],
					'additional_note'  	=> $inward_stock['additional_note'],
					'total_amount'  	=> $inward_stock['total_amount'],
					'currency_type'  	=> $inward_stock['payment_currency_type'],
					'rate'  			=> $inward_stock['payment_currency_rate'],
					'total_profit'  	=> $product_total_profit,
					'total_profit_percent' => str_replace('%', '', $inward_stock['total_profit_percent']),
				);
				//echo '<pre>';print_r($purchaseStockData);exit;
				$purchaseInwardStock	= PurchaseInwardStock::create($purchaseStockData);
				$purchaseInwardStockId	= $purchaseInwardStock->id;
				//echo '<pre>';print_r($purchaseStockData);exit;

				//$purchaseInwardStockId=2;

				
				
				if(isset($inward_stock['product_detail'])){
					if(count($inward_stock['product_detail'])>0){
						$qty_total_net_price = 0;
						$qty_total_sell_price = 0;
						$qty_total_profit = 0;
						for($i=0;count($inward_stock['product_detail'])>$i;$i++){
							$product_id=$inward_stock['product_detail'][$i]['product_id'];
							$product_info=Product::find($inward_stock['product_detail'][$i]['product_id']);

							$prev_stock=isset($product_info->stock_qty)?$product_info->stock_qty:0;
							$current_stock=0;
							$current_stock += $prev_stock;
							$current_stock += $inward_stock['product_detail'][$i]['product_totalQuantity'];

							$product_barcode=$inward_stock['product_detail'][$i]['product_barcode'];

							$product_expiry_date=$inward_stock['product_detail'][$i]['product_expiry_date'];



							$product_isChronic = $inward_stock['product_detail'][$i]['product_isChronic'];
							$product_mrp = 0;
							if($product_isChronic == 'Yes'){
								$product_mrp		= isset($inward_stock['product_detail'][$i]['chronic_amount'])? $inward_stock['product_detail'][$i]['chronic_amount']:0;
							}else{
								$product_mrp		= isset($inward_stock['product_detail'][$i]['product_price'])?$inward_stock['product_detail'][$i]['product_price']:0;
							}
							
							$product_bonous		= isset($inward_stock['product_detail'][$i]['product_bonous'])?$inward_stock['product_detail'][$i]['product_bonous']:0;
							$product_qty		= isset($inward_stock['product_detail'][$i]['product_totalQuantity'])?$inward_stock['product_detail'][$i]['product_totalQuantity']:0;

							$product_brand		= isset($inward_stock['product_detail'][$i]['product_brand'])?$inward_stock['product_detail'][$i]['product_brand']:0;
							$product_brand_id	= isset($product_info->brand_id)?$product_info->brand_id:0;
							$product_dosage		= isset($inward_stock['product_detail'][$i]['product_dosage'])?$inward_stock['product_detail'][$i]['product_dosage']:0;
							$product_dosage_id	= isset($product_info->dosage_id)?$product_info->dosage_id:0;
							$product_company	= isset($inward_stock['product_detail'][$i]['product_company'])?$inward_stock['product_detail'][$i]['product_company']:0;
							$product_company_id	= isset($product_info->company_id)?$product_info->company_id:0;
							//$product_drugstore	= isset($inward_stock['product_detail'][$i]['product_drugstore'])?$inward_stock['product_detail'][$i]['product_drugstore']:0;
							//$drugstore_id		= isset($product_info->drugstore_id)?$product_info->drugstore_id:0;

							$product_package	= isset($inward_stock['product_detail'][$i]['product_package'])?$inward_stock['product_detail'][$i]['product_package']:0;
							$net_price			= isset($inward_stock['product_detail'][$i]['product_netPrice'])?$inward_stock['product_detail'][$i]['product_netPrice']:0;
							$product_rate		= isset($inward_stock['product_detail'][$i]['product_rate'])?$inward_stock['product_detail'][$i]['product_rate']:0;

							$selling_price		= isset($inward_stock['product_detail'][$i]['product_sellPrice'])?$inward_stock['product_detail'][$i]['product_sellPrice']:0;
							$product_profit		= isset($inward_stock['product_detail'][$i]['product_profit'])?$inward_stock['product_detail'][$i]['product_profit']:0;
							$product_profitPercent	= isset($inward_stock['product_detail'][$i]['product_profitPercent'])?$inward_stock['product_detail'][$i]['product_profitPercent']:0;

							$product_sellingBy		= isset($inward_stock['product_detail'][$i]['product_sellingBy'])?$inward_stock['product_detail'][$i]['product_sellingBy']:'Pack';
							
							$branchProductStockResult=BranchStockProducts::where('product_mrp',$product_mrp)->whereDate('product_expiry_date','=',$product_expiry_date)->where('branch_id',$branch_id)->where('product_id',$product_id)->get();
							if(count($branchProductStockResult)>0){
								$sell_price_id=isset($branchProductStockResult[0]->id)?$branchProductStockResult[0]->id:'';

								$sell_price_t_qty = 0;
								$sell_price_t_qty +=isset($branchProductStockResult[0]->t_qty)?$branchProductStockResult[0]->t_qty:0;
								$sell_price_t_qty +=$product_qty;
								BranchStockProducts::where('id', $sell_price_id)->update(['t_qty' => $sell_price_t_qty]);
							}else{
								$sell_price_t_qty = 0;
								$sell_price_t_qty +=$product_qty;

								$branchProductStockSellPriceData=array(
									'branch_id'			=> $branch_id,
									'product_id'  		=> $product_id,
									'product_barcode'  	=> $product_barcode,
									't_qty'  			=> $sell_price_t_qty,
									'selling_price'		=> str_replace(',', '', $selling_price),
									'product_mrp'  		=> $product_mrp,
									'net_price'  		=> str_replace(',', '', $net_price),
									'product_expiry_date'  		=> date("Y-d-m", strtotime('01/'.$product_expiry_date)),
									'is_chronic'  		=> $inward_stock['product_detail'][$i]['product_isChronic'],
									'chronic_amount'  	=> str_replace(',', '', $inward_stock['product_detail'][$i]['chronic_amount']) ? str_replace(',', '', $inward_stock['product_detail'][$i]['chronic_amount']) : 0,
								);
								BranchStockProducts::create($branchProductStockSellPriceData);
								//echo '<pre>';print_r($branchProductStockSellPriceData);exit;
							}

							$inward_stock_product=array(
								'inward_stock_id'	=> $purchaseInwardStockId,
								'branch_id'  		=> $branch_id,
								'product_id'  		=> $product_id,
								'brand'  			=> $inward_stock['product_detail'][$i]['product_brand'],
								'product_name'  			=> $inward_stock['product_detail'][$i]['product_name'],
								'dosage'  			=> $inward_stock['product_detail'][$i]['product_dosage'],
								'company'  			=> $inward_stock['product_detail'][$i]['product_company'],
								'drugstore'  		=> $branch_id,
								'product_qty'  		=> $inward_stock['product_detail'][$i]['product_quantity'],
								'total_qty'  		=> $inward_stock['product_detail'][$i]['product_totalQuantity'],
								'no_package'  		=> $inward_stock['product_detail'][$i]['product_package'],
								'net_price_befour_discount' => $inward_stock['product_detail'][$i]['product_totalNetPrice'],
								'discount_cost'  	=> $inward_stock['product_detail'][$i]['product_discountCost'],
								//'discount_persent'  => $inward_stock['product_detail'][$i]['product_discount'],
								'is_chronic'  		=> $inward_stock['product_detail'][$i]['product_isChronic'],
								'net_price'  		=> str_replace(',', '', $inward_stock['product_detail'][$i]['product_netPrice']),
								'product_mrp'  		=> $product_mrp,
								'chronic_amount'  	=> str_replace(',', '', $inward_stock['product_detail'][$i]['chronic_amount']) ? str_replace(',', '', $inward_stock['product_detail'][$i]['chronic_amount']) : 0,
								'option_product_mrp'=> $inward_stock['product_detail'][$i]['product_isChronic'] == 'Yes' ? $inward_stock['product_detail'][$i]['product_price'] : 0,
								'cost_price'  		=> $inward_stock['product_detail'][$i]['product_price'],
								'cost_rate'  		=> $inward_stock['product_detail'][$i]['product_rate'],
								'bonous'  			=> $inward_stock['product_detail'][$i]['product_bonous'],
								'selling_price'		=> str_replace(',', '', $inward_stock['product_detail'][$i]['product_sellPrice']),
								'profit_amount'  	=> $inward_stock['product_detail'][$i]['product_profit'],
								'profit_percent'  	=> str_replace('%', '', $inward_stock['product_detail'][$i]['product_profitPercent']),
								'chronic_amount_percentage'  	=> str_replace('%', '', $inward_stock['product_detail'][$i]['chronic_amount_percentage']),
								'product_expiry_date'  	=> date("Y-d-m", strtotime('01/'.$inward_stock['product_detail'][$i]['product_expiry_date'])),
								'selling_by'		=> $product_sellingBy,
								'qty_total_net_price'		=> $inward_stock['product_detail'][$i]['product_totalQuantity'] * str_replace(',', '', $inward_stock['product_detail'][$i]['product_netPrice']),
								'qty_total_sell_price'		=> $inward_stock['product_detail'][$i]['product_totalQuantity'] * str_replace(',', '', $inward_stock['product_detail'][$i]['product_sellPrice']),
								'qty_total_profit'		=> $inward_stock['product_detail'][$i]['product_totalQuantity'] * $inward_stock['product_detail'][$i]['product_profit'],
							);
							InwardStockProducts::create($inward_stock_product);
							//echo '<pre>';print_r($inward_stock_product);exit;
							$qty_total_net_price += $inward_stock['product_detail'][$i]['product_totalQuantity'] * str_replace(',', '', $inward_stock['product_detail'][$i]['product_netPrice']);
							$qty_total_sell_price += $inward_stock['product_detail'][$i]['product_totalQuantity'] * str_replace(',', '', $inward_stock['product_detail'][$i]['product_sellPrice']);
							$qty_total_profit += $inward_stock['product_detail'][$i]['product_totalQuantity'] * $inward_stock['product_detail'][$i]['product_profit'];
						}
						PurchaseInwardStock::where('id',$purchaseInwardStockId)->update(['qty_total_net_price' => $qty_total_net_price,'qty_total_sell_price' => $qty_total_sell_price,'qty_total_profit' => $qty_total_profit]);
					}
				}


			




			}
		}
		
		DB::commit();

		$return_data['msg']		= 'Successfully added';
		$return_data['status']	= 1;
		echo json_encode($return_data);
		
	} catch (\Exception $e) {
		DB::rollback();
		$return_data['msg']		= 'Something went wrong';
		$return_data['status']	= 0;
		echo json_encode($e->getMessage());
	}

	}
	
	
	public function daily_product_purchase_history(){
		$branch_id				= Session::get('branch_id');
		$purchase_date_result 	= InwardStockProducts::where('branch_id',$branch_id)->where('is_new','Y')->orderBy('id', 'asc')->first();
		$purchase_start_date	= isset($purchase_date_result->created_at)?date('Y-m-d',strtotime($purchase_date_result->created_at)):'';
		
		//print_r($branch_id);exit;
		
		$items=[];
		
		if($purchase_start_date!=''){
			$current_date=date('Y-m-d');
			
			$category_result		= InwardStockProducts::select('category_id')->whereBetween('created_at', [$current_date." 00:00:00", $current_date." 23:59:59"])->distinct()->get();
			$sub_category_result 	= InwardStockProducts::select('subcategory_id')->whereBetween('created_at', [$current_date." 00:00:00", $current_date." 23:59:59"])->distinct()->get();
			$size_result 			= InwardStockProducts::select('size_id')->whereBetween('created_at', [$current_date." 00:00:00", $current_date." 23:59:59"])->distinct()->get();
			$product_result 		= InwardStockProducts::select('product_id')->whereBetween('created_at', [$current_date." 00:00:00", $current_date." 23:59:59"])->distinct()->get();
			
			//echo '<pre>';print_r($category_result);exit;
			
			foreach($category_result as $cat_row){
				$category_id=$cat_row->category_id;
				
				foreach($sub_category_result as $sub_cat_row){
					$subcategory_id=$sub_cat_row->subcategory_id;
					foreach($size_result as $size_row){
						$size_id=$size_row->size_id;
						foreach($product_result as $product_row){
							$product_id=$product_row->product_id;
							
							$purchase_result = InwardStockProducts::selectRaw('sum(total_ml) as total_ml,sum(product_qty) as product_qty,product_mrp')->whereBetween('created_at', [$current_date." 00:00:00", $current_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->where('size_id',$size_id)->where('product_id',$product_id)->where('is_new','Y')->get();
							
							$date_wise_total_ml	  	= isset($purchase_result[0]->total_ml)?$purchase_result[0]->total_ml:0;
							
							//echo '<pre>';print_r($purchase_result);exit;
							//echo $product_id.'-'.$date_wise_total_ml.'</br>';
							
							//$purchase_result=[];
							
							if($date_wise_total_ml>0){
								$date_wise_total_ml	  	= isset($purchase_result[0]->total_ml)?$purchase_result[0]->total_ml:0;
								$date_wise_total_qty	= isset($purchase_result[0]->product_qty)?$purchase_result[0]->product_qty:0;
								$product_mrp			= isset($purchase_result[0]->product_mrp)?$purchase_result[0]->product_mrp:0;
									
								$closing_stock 		= $date_wise_total_qty;
								$closing_stock_ml 	= $date_wise_total_ml;
									
								$last_purchase_history_result = DailyProductPurchaseHistory::select('closing_stock', 'closing_stock_ml')->where('branch_id',$branch_id)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->where('size_id',$size_id)->where('product_id',$product_id)->orderBy('id', 'DESC')->first();
								$last_purchase_total_ml	  	= isset($last_purchase_history_result->closing_stock_ml)?$last_purchase_history_result->closing_stock_ml:'';
								$last_purchase_total_qty	= isset($last_purchase_history_result->closing_stock)?$last_purchase_history_result->closing_stock:'';
								if($last_purchase_total_qty!=''){
									$closing_stock 		= $last_purchase_total_qty+$date_wise_total_qty;
									$closing_stock_ml 	= $last_purchase_total_ml+$date_wise_total_ml;
								}
								$check_purchase_history_result = DailyProductPurchaseHistory::select('id', 'total_qty', 'total_ml', 'closing_stock', 'closing_stock_ml')->whereBetween('created_at', [$current_date." 00:00:00", $current_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->where('size_id',$size_id)->where('product_id',$product_id)->orderBy('id', 'DESC')->get();
								
								//echo '<pre>';print_r($check_purchase_history_result);exit;
								
								$productRelationshipSizeResult=ProductRelationshipSize::where('product_id',$product_id)->where('size_id',$size_id)->get();
								$strength_no =isset($productRelationshipSizeResult[0]->strength)?$productRelationshipSizeResult[0]->strength:'';
								$strength=$strength_no;
								if($strength_no==''){
									$strength=0;
								}
								
								
								if(count($check_purchase_history_result)>0){
									$total_qty	= $date_wise_total_qty+$check_purchase_history_result[0]->total_qty;
									$total_ml	= $date_wise_total_ml+$check_purchase_history_result[0]->total_ml;
									
									/*$items[]=array(
										'is_new'			=>'update',
										'branch_id'  		=> $branch_id,
										'category_id'		=> $category_id,
										'subcategory_id'	=> $subcategory_id,
										'product_id'  		=> $product_id,
										'size_id'  			=> $size_id,
										'total_qty' 		=> $total_qty,
										'total_ml' 			=> $total_ml,
										'closing_stock' 	=> $closing_stock,
										'closing_stock_ml' 	=> $closing_stock_ml
									);*/
									
									DailyProductPurchaseHistory::where('id',$check_purchase_history_result[0]->id)->update(['total_qty' => $total_qty,'total_ml' => $total_ml,'closing_stock' => $closing_stock,'closing_stock_ml' => $closing_stock_ml,'strength' => $strength]);
										
									InwardStockProducts::where('branch_id',$branch_id)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->where('size_id',$size_id)->where('product_id',$product_id)->update(['is_new' => 'N']);
								}else{
									$purchase_data=array(
										'branch_id'  		=> $branch_id,
										'category_id'		=> $category_id,
										'subcategory_id'	=> $subcategory_id,
										'product_id'  		=> $product_id,
										'size_id'  			=> $size_id,
										'total_qty'  		=> $date_wise_total_qty,
										'total_ml'  		=> $date_wise_total_ml,
										'closing_stock'  	=> $closing_stock,
										'closing_stock_ml'  => $closing_stock_ml,
										'product_mrp'		=> $product_mrp,
										'strength'			=> $strength
									);
									DailyProductPurchaseHistory::create($purchase_data);
									InwardStockProducts::where('branch_id',$branch_id)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->where('size_id',$size_id)->where('product_id',$product_id)->update(['is_new' => 'N']);
									
									
									/*$items[]=array(
										'is_new'			=>'new',
										'branch_id'  		=> $branch_id,
										'category_id'		=> $category_id,
										'subcategory_id'	=> $subcategory_id,
										'product_id'  		=> $product_id,
										'size_id'  			=> $size_id,
										'total_qty'  		=> $date_wise_total_qty,
										'total_ml'  		=> $date_wise_total_ml,
										'closing_stock'  	=> $closing_stock,
										'closing_stock_ml'  => $closing_stock_ml,
										'product_mrp'		=> $product_mrp
									);*/
									
								}
							}
						}
					}
				}
			}
			
			//echo '<pre>';print_r($items);exit;	
			//echo 'success';exit;	
		}else{
			//echo 'Not data found';exit;
		}	
	}

	

	public function ajaxpost_check_product_barcode($request) {
		$product_barcode	= trim($request->product_barcode);
		$product_id			= trim($request->product_id);
		
		$product_result=Product::where('product_barcode',$product_barcode)->get();
		
		if(count($product_result)>0){
			if($product_id!=''){
				if($product_result[0]->id!=$product_id){
					$return_data['msg']		= 'This barcode already exists!';
					$return_data['status']	= 0;
				}
			}else{
				$return_data['msg']		= 'This barcode already exists!';
				$return_data['status']	= 0;
			}
			
		}else{
			$return_data['status']	= 1;
		}
		echo json_encode($return_data);
	}
	
	public function ajaxpost_set_feature_option($request) {
		$product_type	= $request->product_type;
		$feature_title	= $request->feature_title;

		$return_data	= [];

		$return_data['msg']		= 'Something went wrong. Please try later!';
		$return_data['status']	= 0;
		if($product_type=='brand'){
			$count=Brand::where('name',$feature_title)->count();
			if($count>0){
				$return_data['msg']		= 'This Brand already exists!';
				$return_data['status']	= 0;
			}else{
				$brand_slug	= Media::create_slug(trim($feature_title));
				$feature_data=array(
					'name'  		=> $feature_title,
					'slug'  		=> $brand_slug,
					'created_at'	=> date('Y-m-d')
				);
				$feature=Brand::create($feature_data);
				$feature_id=$feature->id;

				$return_data['val']		= $feature_id;
				$return_data['title']	= $feature_title;
				$return_data['msg']		= 'Successfully added';
				$return_data['status']	= 1;

			}
		}
		else if($product_type=='dosage'){
			$count=Dosage::where('name',$feature_title)->count();
			if($count>0){
				$return_data['msg']		= 'This Dosage already exists!';
				$return_data['status']	= 0;
			}else{
				$feature_data=array(
					'name'  		=> $feature_title,
					'created_at'	=> date('Y-m-d')
				);
				$feature=Dosage::create($feature_data);
				$feature_id=$feature->id;

				$return_data['val']		= $feature_id;
				$return_data['title']	= $feature_title;
				$return_data['msg']		= 'Successfully added';
				$return_data['status']	= 1;

			}
		}
		else if($product_type=='company'){
			$count=Company::where('name',$feature_title)->count();
			if($count>0){
				$return_data['msg']		= 'This Company already exists!';
				$return_data['status']	= 0;
			}else{
				$feature_data=array(
					'name'  		=> $feature_title,
					'created_at'	=> date('Y-m-d')
				);
				$feature=Company::create($feature_data);
				$feature_id=$feature->id;

				$return_data['val']		= $feature_id;
				$return_data['title']	= $feature_title;
				$return_data['msg']		= 'Successfully added';
				$return_data['status']	= 1;

			}
		}
		else if($product_type=='drugstore'){
			$count=Drugstore::where('name',$feature_title)->count();
			if($count>0){
				$return_data['msg']		= 'This Drugstore already exists!';
				$return_data['status']	= 0;
			}else{
				$feature_data=array(
					'name'  		=> $feature_title,
					'created_at'	=> date('Y-m-d')
				);
				$feature=Drugstore::create($feature_data);
				$feature_id=$feature->id;

				$return_data['val']		= $feature_id;
				$return_data['title']	= $feature_title;
				$return_data['msg']		= 'Successfully added';
				$return_data['status']	= 1;

			}
		}
		//print_r($return_data);exit;
		echo json_encode($return_data);
	}

	public function ajaxpost_get_stock_branch_stock_productId($request) {
		//dd($request);
		$branch_stock_product_id = $request->branch_stock_product_id;
		$return_data = [];
		if($branch_stock_product_id !=''){
			$branchStockProduct = BranchStockProducts::where('id',$branch_stock_product_id)->first();
			if($branchStockProduct){
				$return_data['stock'] = $branchStockProduct->t_qty;
				$product_result=array(
					'product_id'				=> $branchStockProduct->product_id,
					'branch_stock_product_id'	=> $branchStockProduct->id,
					'product_barcode'			=> $branchStockProduct->product->product_barcode,
					'brand_name'				=> $branchStockProduct->product->brand,
					'product_name'				=> $branchStockProduct->product->product_name,
					'selling_by_name'			=> $branchStockProduct->product->selling_by_name,
					'selling_price'				=> $branchStockProduct->selling_price,
					'product_mrp'				=> $branchStockProduct->product_mrp,
					't_qty'						=> $branchStockProduct->t_qty,
					'net_price'					=> $branchStockProduct->net_price,
					'is_chronic'				=> $branchStockProduct->is_chronic,
					'chronic_amount'			=> $branchStockProduct->chronic_amount,
				);
				$return_data['product_result']	= $product_result;
				$return_data['status']	= 1;
			}else{
				$return_data['status']	= 0;
			}
			
		}
		echo json_encode($return_data);
	}
	public function ajaxpost_store_customer_details($request){
		dd($request->all());
	}
	public function ajaxpost_get_supplier_by_name($request){
		//dd($request->all());
		$supplier_name = $request->supplier_name;
        $result = Supplier::where('company_name', 'LIKE', "%{$supplier_name}%")
                            //->orWhere('owner_name', 'LIKE', "%{$supplier_name}%")
                            ->take(20)->get();
        $return_data['result']	= $result;
		$return_data['status']	= 1;
		echo json_encode($return_data);
	}
	public function ajaxpost_get_customer_by_search($request){
		//dd($request->all());
		$customer_search_val = $request->customer_search_val;
        $result = Customer::where('customer_name', 'LIKE', "%{$customer_search_val}%")
                            ->orWhere('customer_mobile', 'LIKE', "%{$customer_search_val}%")
                            ->take(20)->get();
        $return_data['result']	= $result;
		$return_data['status']	= 1;
		echo json_encode($return_data);
	}


	public function ajaxpost_update_inward_stock($request) {

		
		
		// DB::beginTransaction();
	
		// try {
			//dd($request->all());
			$branch_id=Auth::user()->id;
			
			$inward_stock	= $request->inward_stock;
			//echo '<pre>';print_r($inward_stock);exit;
			
			$purchaseId = $inward_stock['purchaseId'];

			// echo $purchaseId;exit;
			
			if($inward_stock['invoice_no']!=''){
				$invoice_no=$inward_stock['invoice_no'];
				$count=PurchaseInwardStock::where('invoice_no',$invoice_no)->whereNotIn('id', [$purchaseId])->count();
				//$count=0;
				if($count>0){
					$return_data['msg']		= 'This invoice already exists!';
					$return_data['status']	= 0;
					echo json_encode($return_data);exit;
				}else{
					
					$product_total_profit=0;
					$productItemErrorMsg=[];
					if(isset($inward_stock['product_detail'])){
						if(count($inward_stock['product_detail'])>0){
							for($i=0;count($inward_stock['product_detail'])>$i;$i++){
								$product_total_profit += isset($inward_stock['product_detail'][$i]['product_profit'])?$inward_stock['product_detail'][$i]['product_profit']:0;
								$product_quantity	= isset($inward_stock['product_detail'][$i]['product_quantity'])?$inward_stock['product_detail'][$i]['product_quantity']:0;
								if($product_quantity==0 || $product_quantity==''){
									$productItemErrorMsg[]='Quantity should not be empty or 0!';
								}
								$product_price	= isset($inward_stock['product_detail'][$i]['product_price'])?$inward_stock['product_detail'][$i]['product_price']:0;
								if($product_price==0 || $product_price==''){
									$productItemErrorMsg[]='Price should not be empty or 0!';
								}
								$product_sellPrice	= isset($inward_stock['product_detail'][$i]['product_sellPrice'])?$inward_stock['product_detail'][$i]['product_sellPrice']:0;
								if($product_sellPrice==0 || $product_sellPrice==''){
									$productItemErrorMsg[]='Sell Price should not be empty or 0!';
								}
								$product_expiry_date	= isset($inward_stock['product_detail'][$i]['product_expiry_date'])?$inward_stock['product_detail'][$i]['product_expiry_date']:'';
								if($product_expiry_date==''){
									$productItemErrorMsg[]='Product Expiry Date should not be empty!';
								}
							}
						}else{
							$return_data['msg']		= 'items not exists!';
							$return_data['status']	= 0;
							echo json_encode($return_data);exit;
						}
					}else{
						$return_data['msg']		= 'items not exists!';
						$return_data['status']	= 0;
						echo json_encode($return_data);exit;
					}
	
					if(count($productItemErrorMsg)>0){
						$return_data['msg']		= $productItemErrorMsg[0];
						$return_data['status']	= 0;
						echo json_encode($return_data);exit;
					}
					
					$branch_id=$inward_stock['store_id'];
					$purchaseStockData=array(
						'branch_id'  		=> $inward_stock['store_id'],
						'supplier_id'  		=> $inward_stock['supplier_id'],
						'supplier_name'  	=> $inward_stock['supplier_name'],
						'invoice_no'  		=> $inward_stock['invoice_no'],
						'purchase_date'  	=> $inward_stock['invoice_purchase_date'],
						// 'inward_date'  		=> $inward_stock['invoice_inward_date'],
						'payment_method'  	=> $inward_stock['payment_method'],
						'payment_debt_day'  => $inward_stock['payment_debt_day'],
						'payment_date'  	=> $inward_stock['payment_date'],
						'payment_discount'  => $inward_stock['payment_discount'],
						'payment_ref_no'  	=> $inward_stock['payment_ref_no'],
						'total_qty'  		=> $inward_stock['total_qty'],
						'gross_amount'  	=> $inward_stock['gross_amount'],
						'sub_total'  		=> $inward_stock['sub_total'],
						'additional_note'  	=> $inward_stock['additional_note'],
						'total_amount'  	=> $inward_stock['total_amount'],
						'currency_type'  	=> $inward_stock['payment_currency_type'],
						'rate'  			=> $inward_stock['payment_currency_rate'],
						'total_profit'  	=> $product_total_profit,
						'total_profit_percent' => str_replace('%', '', $inward_stock['total_profit_percent']),
					);
					//echo '<pre>';print_r($purchaseStockData);exit;
					$purchaseInwardStock	= PurchaseInwardStock::where('id', $purchaseId)->update($purchaseStockData);
					// $purchaseInwardStockId	= $purchaseInwardStock->id;
					//echo '<pre>';print_r($purchaseStockData);exit;
	
					//$purchaseInwardStockId=2;


					
					$checkoldinwardstock = InwardStockProducts::where('inward_stock_id', $purchaseId)->get();

					// print_r($checkoldinwardstock->toArray());exit;
	
					
					
					if(isset($inward_stock['product_detail'])){
						if(count($inward_stock['product_detail'])>0){
							$qty_total_net_price = 0;
							$qty_total_sell_price = 0;
							$qty_total_profit = 0;
							for($i=0;count($inward_stock['product_detail'])>$i;$i++){
								$product_id=$inward_stock['product_detail'][$i]['product_id'];

								$product_info=Product::find($inward_stock['product_detail'][$i]['product_id']);
	
								$prev_stock=isset($product_info->stock_qty)?$product_info->stock_qty:0;
								$current_stock=0;
								$current_stock += $prev_stock;
								$current_stock += $inward_stock['product_detail'][$i]['product_totalQuantity'];
	
								$product_barcode=$inward_stock['product_detail'][$i]['product_barcode'];
	
								$product_expiry_date=$inward_stock['product_detail'][$i]['product_expiry_date'];
	
	
	
								$product_isChronic = $inward_stock['product_detail'][$i]['product_isChronic'];
								$product_mrp = 0;
								if($product_isChronic == 'Yes'){
									$product_mrp		= isset($inward_stock['product_detail'][$i]['chronic_amount'])? $inward_stock['product_detail'][$i]['chronic_amount']:0;
								}else{
									$product_mrp		= isset($inward_stock['product_detail'][$i]['product_price'])?$inward_stock['product_detail'][$i]['product_price']:0;
								}
								
								$product_bonous		= isset($inward_stock['product_detail'][$i]['product_bonous'])?$inward_stock['product_detail'][$i]['product_bonous']:0;
								$product_qty		= isset($inward_stock['product_detail'][$i]['product_totalQuantity'])?$inward_stock['product_detail'][$i]['product_totalQuantity']:0;
	
								$product_brand		= isset($inward_stock['product_detail'][$i]['product_brand'])?$inward_stock['product_detail'][$i]['product_brand']:0;
								$product_brand_id	= isset($product_info->brand_id)?$product_info->brand_id:0;
								$product_dosage		= isset($inward_stock['product_detail'][$i]['product_dosage'])?$inward_stock['product_detail'][$i]['product_dosage']:0;
								$product_dosage_id	= isset($product_info->dosage_id)?$product_info->dosage_id:0;
								$product_company	= isset($inward_stock['product_detail'][$i]['product_company'])?$inward_stock['product_detail'][$i]['product_company']:0;
								$product_company_id	= isset($product_info->company_id)?$product_info->company_id:0;
								//$product_drugstore	= isset($inward_stock['product_detail'][$i]['product_drugstore'])?$inward_stock['product_detail'][$i]['product_drugstore']:0;
								//$drugstore_id		= isset($product_info->drugstore_id)?$product_info->drugstore_id:0;
	
								$product_package	= isset($inward_stock['product_detail'][$i]['product_package'])?$inward_stock['product_detail'][$i]['product_package']:0;
								$net_price			= isset($inward_stock['product_detail'][$i]['product_netPrice'])?$inward_stock['product_detail'][$i]['product_netPrice']:0;
								$product_rate		= isset($inward_stock['product_detail'][$i]['product_rate'])?$inward_stock['product_detail'][$i]['product_rate']:0;
	
								$selling_price		= isset($inward_stock['product_detail'][$i]['product_sellPrice'])?$inward_stock['product_detail'][$i]['product_sellPrice']:0;
								$product_profit		= isset($inward_stock['product_detail'][$i]['product_profit'])?$inward_stock['product_detail'][$i]['product_profit']:0;
								$product_profitPercent	= isset($inward_stock['product_detail'][$i]['product_profitPercent'])?$inward_stock['product_detail'][$i]['product_profitPercent']:0;
	
								$product_sellingBy		= isset($inward_stock['product_detail'][$i]['product_sellingBy'])?$inward_stock['product_detail'][$i]['product_sellingBy']:'Pack';
								
								$checkinwardstock = InwardStockProducts::where('inward_stock_id', $purchaseId)->where('product_id', $product_id)->first();
								if(!empty($checkinwardstock)){
									
									$oldstockcheck = BranchStockProducts::where('product_mrp',$checkinwardstock->product_mrp)->whereDate('product_expiry_date','=',$checkinwardstock->product_expiry_date)->where('branch_id',$checkinwardstock->branch_id)->where('product_id',$checkinwardstock->product_id)->first();
									// print_r("$oldstockcheck");exit;
									if($oldstockcheck->t_qty < $product_qty){
										$qty = ($product_qty - $oldstockcheck->t_qty);
										$sell_price_t_qty = ($oldstockcheck->t_qty + $qty);
									}elseif($oldstockcheck->t_qty > $product_qty){
										$qty = ($oldstockcheck->t_qty - $product_qty);
										$sell_price_t_qty = ($oldstockcheck->t_qty - $qty);
									}elseif($oldstockcheck->t_qty == $product_qty){
										$sell_price_t_qty = $oldstockcheck->t_qty;
									}

									$branchProductStockSellPriceData=array(
										'branch_id'			=> $branch_id,
										'product_id'  		=> $product_id,
										'product_barcode'  	=> $product_barcode,
										't_qty'  			=> $sell_price_t_qty,
										'selling_price'		=> str_replace(',', '', $selling_price),
										'product_mrp'  		=> $product_mrp,
										'net_price'  		=> str_replace(',', '', $net_price),
										'product_expiry_date'  	=> date("Y-d-m", strtotime('01/'.$product_expiry_date)),
									);
									BranchStockProducts::where('id', $oldstockcheck->id)->update($branchProductStockSellPriceData);



								}else{

									$branchProductStockResult=BranchStockProducts::where('product_mrp',$product_mrp)->whereDate('product_expiry_date','=',date("Y-d-m", strtotime('01/'.$product_expiry_date)))->where('branch_id',$branch_id)->where('product_id',$product_id)->get();
									if(count($branchProductStockResult)>0){
										$sell_price_id=isset($branchProductStockResult[0]->id)?$branchProductStockResult[0]->id:'';
		
										$sell_price_t_qty = 0;
										$sell_price_t_qty +=isset($branchProductStockResult[0]->t_qty)?$branchProductStockResult[0]->t_qty:0;
										$sell_price_t_qty +=$product_qty;
										BranchStockProducts::where('id', $sell_price_id)->update(['t_qty' => $sell_price_t_qty]);
									}else{
										$sell_price_t_qty = 0;
										$sell_price_t_qty +=$product_qty;
		
										$branchProductStockSellPriceData=array(
											'branch_id'			=> $branch_id,
											'product_id'  		=> $product_id,
											'product_barcode'  	=> $product_barcode,
											't_qty'  			=> $sell_price_t_qty,
											'selling_price'		=> str_replace(',', '', $selling_price),
											'product_mrp'  		=> $product_mrp,
											'net_price'  		=> str_replace(',', '', $net_price),
											'product_expiry_date'  		=> date("Y-d-m", strtotime('01/'.$product_expiry_date)),
										);
										BranchStockProducts::create($branchProductStockSellPriceData);

										//echo '<pre>';print_r($branchProductStockSellPriceData);exit;
									}

								}


								
	
								$inward_stock_product=array(
									'inward_stock_id'	=> $purchaseId,
									'branch_id'  		=> $branch_id,
									'product_id'  		=> $product_id,
									'brand'  			=> $inward_stock['product_detail'][$i]['product_brand'],
									'product_name'  			=> $inward_stock['product_detail'][$i]['product_name'],
									'dosage'  			=> $inward_stock['product_detail'][$i]['product_dosage'],
									'company'  			=> $inward_stock['product_detail'][$i]['product_company'],
									'drugstore'  		=> $branch_id,
									'product_qty'  		=> $inward_stock['product_detail'][$i]['product_quantity'],
									'total_qty'  		=> $inward_stock['product_detail'][$i]['product_totalQuantity'],
									'no_package'  		=> $inward_stock['product_detail'][$i]['product_package'],
									'net_price_befour_discount' => $inward_stock['product_detail'][$i]['product_totalNetPrice'],
									'discount_cost'  	=> $inward_stock['product_detail'][$i]['product_discountCost'],
									//'discount_persent'  => $inward_stock['product_detail'][$i]['product_discount'],
									'is_chronic'  		=> $inward_stock['product_detail'][$i]['product_isChronic'],
									'net_price'  		=> str_replace(',', '', $inward_stock['product_detail'][$i]['product_netPrice']),
									'product_mrp'  		=> $product_mrp,
									'chronic_amount'  	=> str_replace(',', '', $inward_stock['product_detail'][$i]['chronic_amount']) ? str_replace(',', '', $inward_stock['product_detail'][$i]['chronic_amount']) : 0,
									'option_product_mrp'=> $inward_stock['product_detail'][$i]['product_isChronic'] == 'Yes' ? $inward_stock['product_detail'][$i]['product_price'] : 0,
									'cost_price'  		=> $inward_stock['product_detail'][$i]['product_price'],
									'cost_rate'  		=> $inward_stock['product_detail'][$i]['product_rate'],
									'bonous'  			=> $inward_stock['product_detail'][$i]['product_bonous'],
									'selling_price'		=> str_replace(',', '', $inward_stock['product_detail'][$i]['product_sellPrice']),
									'profit_amount'  	=> $inward_stock['product_detail'][$i]['product_profit'],
									'profit_percent'  	=> str_replace('%', '', $inward_stock['product_detail'][$i]['product_profitPercent']),
									'chronic_amount_percentage'  	=> str_replace('%', '', $inward_stock['product_detail'][$i]['chronic_amount_percentage']),
									'product_expiry_date'  	=> date("Y-d-m", strtotime('01/'.$inward_stock['product_detail'][$i]['product_expiry_date'])),
									'selling_by'		=> $product_sellingBy,
									'qty_total_net_price'		=> $inward_stock['product_detail'][$i]['product_totalQuantity'] * str_replace(',', '', $inward_stock['product_detail'][$i]['product_netPrice']),
									'qty_total_sell_price'		=> $inward_stock['product_detail'][$i]['product_totalQuantity'] * str_replace(',', '', $inward_stock['product_detail'][$i]['product_sellPrice']),
									'qty_total_profit'		=> $inward_stock['product_detail'][$i]['product_totalQuantity'] * $inward_stock['product_detail'][$i]['product_profit'],
								);

								
								if(!empty($checkinwardstock)){
									InwardStockProducts::where('inward_stock_id', $purchaseId)->where('product_id', $product_id)->update($inward_stock_product);
								}else{
									InwardStockProducts::create($inward_stock_product);
								}

								$newProductId[] = $inward_stock['product_detail'][$i]['product_id'];






								//echo '<pre>';print_r($inward_stock_product);exit;
								$qty_total_net_price += $inward_stock['product_detail'][$i]['product_totalQuantity'] * str_replace(',', '', $inward_stock['product_detail'][$i]['product_netPrice']);
								$qty_total_sell_price += $inward_stock['product_detail'][$i]['product_totalQuantity'] * str_replace(',', '', $inward_stock['product_detail'][$i]['product_sellPrice']);
								$qty_total_profit += $inward_stock['product_detail'][$i]['product_totalQuantity'] * $inward_stock['product_detail'][$i]['product_profit'];
							}

							$matchingProducts = [];
							foreach ($checkoldinwardstock as $product) {
								if (in_array($product['product_id'], $newProductId)) {
									// $matchingProducts[] = $product['product_id'];
								}else{
									$matchingProducts[] = $product['product_id'];
								}
							}
							if(count($matchingProducts)>0){
								for ($i=0; $i < count($matchingProducts); $i++) { 

									$checkinwardstockfetch = InwardStockProducts::where('inward_stock_id', $purchaseId)->where('product_id', $matchingProducts[$i])->first();
									
									// print_r($checkinwardstockfetch);exit;
									$oldstockcheck = BranchStockProducts::where('product_mrp',$checkinwardstockfetch->product_mrp)->whereDate('product_expiry_date','=',$checkinwardstockfetch->product_expiry_date)->where('branch_id',$checkinwardstockfetch->branch_id)->where('product_id',$checkinwardstockfetch->product_id)->first();
									

									$sell_price_t_qty = ($oldstockcheck->t_qty - $checkinwardstockfetch->total_qty);

									$branchProductStockSellPriceData=array(
										'branch_id'			=> $branch_id,
										'product_id'  		=> $product_id,
										'product_barcode'  	=> $product_barcode,
										't_qty'  			=> $sell_price_t_qty,
										'selling_price'		=> str_replace(',', '', $selling_price),
										'product_mrp'  		=> $product_mrp,
										'net_price'  		=> str_replace(',', '', $net_price),
										'product_expiry_date'  	=> date("Y-d-m", strtotime('01/'.$product_expiry_date)),
									);
									if($sell_price_t_qty==0){
										BranchStockProducts::where('id', $oldstockcheck->id)->delete();
									}else{
										BranchStockProducts::where('id', $oldstockcheck->id)->update($branchProductStockSellPriceData);
									}
									
									InwardStockProducts::where('inward_stock_id', $purchaseId)->where('product_id', $matchingProducts[$i])->delete();


								}
							}

							PurchaseInwardStock::where('id',$purchaseId)->update(['qty_total_net_price' => $qty_total_net_price,'qty_total_sell_price' => $qty_total_sell_price,'qty_total_profit' => $qty_total_profit]);
						}

						
						



					}
	
	
				
	
	
	
	
				}
			}
			
			DB::commit();
	
			$return_data['msg']		= 'Successfully added';
			$return_data['status']	= 1;
			echo json_encode($return_data);
			
		// } catch (\Exception $e) {
		// 	DB::rollback();
		// 	$return_data['msg']		= 'Something went wrong';
		// 	$return_data['status']	= 0;
		// 	echo json_encode($e->getMessage());
		// }
	
		}


		public function ajaxpost_get_top_sell_product_search($request) {
			$search				= $request->search;
			$searchTerm 		= $search;
			$reservedSymbols 	= ['-', '+', '<', '>', '@', '(', ')', '~'];
			$searchTerm 		= str_replace($reservedSymbols, ' ', $searchTerm);
			$searchValues 		= preg_split('/\s+/', $searchTerm, -1, PREG_SPLIT_NO_EMPTY);
	
			//print_r($searchValues);exit;
			$store_id	= Session::get('store_id');

			$res = Product::select('products.id', 'products.product_name', 'products.product_barcode', DB::raw('COUNT(sell_stock_products.id) as sales_count'))
					->leftJoin('sell_stock_products', 'products.id', '=', 'sell_stock_products.product_id')
					->where('sell_stock_products.branch_id', $store_id)
					->where('products.product_name', 'like', "%{$search}%")
					->orWhere('products.product_barcode', 'like', '%' . $search . '%')
					->groupBy('products.id', 'products.product_name')
					->orderBy('sales_count', 'desc')
					->limit(10)
					->get();

			// dd($res);

			$result=[];
	
			if(count($res) > 0){
				foreach($res as $row){
					
					$result[]=array(
						//'id'				=> $row->id,
						'id'				=> @$row->id,
						'product_barcode'	=> @$row->product_barcode,
						'product_name'		=> @$row->product_name,
						//'product_size'		=> $row->size->name,
					);
					
				}
			}
			
	
			//print_r($result);exit;
	
			$return_data['result']	= $result;
			$return_data['status']	= 1;
	
			echo json_encode($return_data);
		}

}