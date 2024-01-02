<?php

namespace App\Http\Controllers;

use App\Mail\PurchaseOrderSupplier;
use App\Models\Product;
use App\Models\ProductSupplier;
use App\Models\PurchaseOrder;
use App\Models\PurchaseProduct;
use App\Models\Supplier;
use App\Models\PurchaseInwardStock;
use App\Models\InwardStockProducts;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;
use DB;
use PDF;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Helper\Media;
use App\Models\BranchStockProducts;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\SellInwardStock;
use App\Models\SellStockProducts;
use App\Models\Size;
use App\Models\Subcategory;


use App\Models\Counter;
use App\Models\StockTransferHistory;
use App\Models\StockTransferCounterHistory;
use App\Models\CounterWiseStock;
use App\Models\DailyProductSellHistory;
use App\Models\OpeningStockProducts;
use App\Models\ProductRelationshipSize;
use App\Models\DailyProductPurchaseHistory;
use App\Models\Warehouse;
use App\Models\Common;
use App\Models\Company;
use App\Models\DailyStockTransferHistory;
use App\Models\Dosage;
use App\Models\User;
use Carbon\Carbon;
Use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
Use Illuminate\Support\Str;
use Auth;

use App\Exports\NearExpiryStockDownload;
use App\Exports\TopSellingProductDownload;
use App\Exports\LowStockProductDownload;
use App\Exports\PurchaseInvoiceWiseDownload;
use App\Exports\PurchaseProductWiseDownload;
use App\Exports\InvoiceWiesSaleDownload;
use App\Exports\ProductWiesSaleDownload;
use App\Exports\ZeroStockProductDownload;
use App\Exports\InventoryDownload;
use Maatwebsite\Excel\Facades\Excel;



class ReportController extends Controller
{

	public function purchase(Request $request){
		$branch_id=Auth::user()->id;
		$user_role=Auth::user()->role;
		//$purchase 	= PurchaseInwardStock::where('supplier_id',$branch_id)->orderBy('id', 'desc')->get();
		//echo '<pre>';print_r($purchase);exit;
	   try {
		   if ($request->ajax()) {
				if($user_role==1){
					$purchase 	= PurchaseInwardStock::orderBy('id', 'desc')->get();
				}else{
					$purchase 	= PurchaseInwardStock::where('supplier_id',$branch_id)->orderBy('id', 'desc')->get();
				}


			   return DataTables::of($purchase)
				   ->addColumn('invoice_no', function ($row) {
					  return '<a class="td-anchor" href="'.route('admin.report.stock_product.list', [base64_encode($row->id)]) .'" target="_blank">' . $row->invoice_no . '</a>';

				   })
				   ->addColumn('inward_date', function ($row) {
					   return date('d-m-Y', strtotime($row->inward_date));
				   })
				   ->addColumn('purchase_date', function ($row) {
					   return date('d-m-Y', strtotime($row->purchase_date));
				   })
				   ->addColumn('total_qty', function ($row) {
					   return $row->total_qty;
				   })
				   ->addColumn('sub_total', function ($row) {
					   return number_format($row->sub_total,2);
				   })
				   ->addColumn('action', function ($row) {
					   $dropdown = '<a class="dropdown-item" href="'.route('admin.report.stock_product.list', [base64_encode($row->id)]) .'" target="_blank">View</a>
					   <a class="dropdown-item delete-item" id="delete_inward_stock" href="#" data-url="' . route('admin.purchase.inward-stock.delete', [base64_encode($row->id)]) . '">Delete</a>';

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

				   ->rawColumns(['invoice_no','action'])
				   ->make(true);
		   }
		   $data = [];
		   $data['heading'] = 'Purchase Order List';
		   $data['breadcrumb'] = ['Stock', 'Purchase Order', 'List'];

		   return view('admin.report.purchase', compact('data'));
	   } catch (\Exception $e) {
		   return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
	   }
   }

   public function invoiceWisePurchaseReport(Request $request){
		$branch_id=Auth::user()->id;
		$user_role=Auth::user()->role;
		if($user_role==1){
			$purchase 	= PurchaseInwardStock::where('invoice_no','!=','');
		}else{
			$purchase 	= PurchaseInwardStock::where('invoice_no','!=','')->where('branch_id',$branch_id);
		}
		//$sales = SellInwardStock::where('invoice_no','!=','');
		if(!empty($request->get('start_date')) && !empty($request->get('end_date'))){
			if($request->get('start_date') == $request->get('end_date')){
				$purchase->whereDate('purchase_date', $request->get('start_date'));
			}else{
				$purchase->whereBetween('purchase_date', [$request->get('start_date'), $request->get('end_date')]);
			}
		}
		if(!empty($request->get('invoice'))){

			$purchase->where('invoice_no', $request->get('invoice'));

		}

        if(!empty($request->get('store_id'))){

			$purchase->where('branch_id', $request->get('store_id'));

		}

		$purchase->orderBy('id', 'desc')->get();

        $profitpersent = 0;
        if($purchase->sum('qty_total_sell_price')!=''){
            $totalsell = $purchase->sum('qty_total_sell_price');
            $totalnet_price = $purchase->sum('qty_total_net_price');
            $profitpersent = ((($totalsell - $totalnet_price)/$totalnet_price)*100);
        }

		$data = [];
		$data['total_profit'] = $purchase->sum('qty_total_profit');
		$data['profitpersent'] = $profitpersent;
		$data['total_qty'] = $purchase->sum('total_qty');
		$data['total_invoice'] = $purchase->count();
		$data['purchases'] = $purchase->paginate(10);
		$data['heading'] = 'Invoice Wise Purchase List';
		$data['breadcrumb'] = ['Invoice Wise Purchase', '', 'List'];
        $data['storeUsers'] = User::select('id','name','email','phone')->where('role',2)->get();

		return view('admin.report.invoice_wise_purchase_list', compact('data'));
   }
   	public function stockProductList(Request $request,$inward_stock_id){

		//$purchase = InwardStockProducts::where('inward_stock_id',base64_decode($inward_stock_id))->limit(1)->offset(0)->orderBy('id', 'desc')->get();
		//echo '<pre>';print_r($purchase[0]->product->brand);exit;
		$purchase_inward_stock = PurchaseInwardStock::where('id',base64_decode($inward_stock_id))->first();

		try {
			if ($request->ajax()) {
				//echo base64_decode($inward_stock_id);die;
				$purchase = InwardStockProducts::where('inward_stock_id',base64_decode($inward_stock_id))->orderBy('id', 'desc')->get();
				return DataTables::of($purchase)
					->addColumn('barcode', function ($row) {
						return $row->product->product_barcode;
					})
					->addColumn('product_qty', function ($row) {
						return $row->product_qty;
					})
					->addColumn('no_package', function ($row) {
						return $row->no_package;
					})
					->addColumn('brand', function ($row) {
						return $row->brand;
					})
					->addColumn('product_name', function ($row) {
						return $row->product_name;
					})
					->addColumn('dosage', function ($row) {
						return $row->dosage;
					})
					->addColumn('company', function ($row) {
						return $row->company;
					})
					->addColumn('drugstore', function ($row) {
						return $row->drugstore;
					})
					->addColumn('net_price', function ($row) {
						return $row->net_price;
					})
					->addColumn('product_mrp', function ($row) {
						return $row->product_mrp;
					})
					->addColumn('cost_rate', function ($row) {
						return $row->cost_rate;
					})
					->addColumn('bonous', function ($row) {
						return $row->bonous;
					})
					->addColumn('selling_price', function ($row) {
						return $row->selling_price;
					})
					->addColumn('profit_amount', function ($row) {
						return $row->profit_amount;
					})
					->addColumn('profit_percent', function ($row) {
						return $row->profit_percent;
					})
					->addColumn('is_chronic', function ($row) {
						return $row->is_chronic;
					})
					->rawColumns([])
					->make(true);

			}

			$data = [];
			$data['heading'] = 'Stock Product List';
			$data['breadcrumb'] = ['Stock', 'Product', 'List'];
			$data['purchase_inward_stock'] = $purchase_inward_stock;
			$data['inward_stock_id'] = $inward_stock_id;
			return view('admin.report.stock_product_list', compact('data'));
		} catch (\Exception $e) {
			return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
		}
	}
	public function productWisePurchaseReport(Request $request){
		//dd($request->all());
		$data = [];

        $branch_id=Auth::user()->id;
		$user_role=Auth::user()->role;
		if($user_role==1){
		    $queryProduct = InwardStockProducts::query();
        }else{
            $queryProduct = InwardStockProducts::where('branch_id', $branch_id);
        }

		//Add sorting
		$queryProduct->orderBy('id', 'desc');
		if(!empty($request->get('start_date')) && !empty($request->get('end_date'))){
			if($request->get('start_date') == $request->get('end_date')){
				$queryProduct->whereDate('created_at', $request->get('start_date'));
			}else{
				$queryProduct->whereBetween('created_at', [$request->get('start_date'), $request->get('end_date')]);
			}
		}
		/* if(!is_null($request['customer_id'])) {
			$queryProduct->whereHas('sellInwardStock',function($q) use ($request){
				return $q->where('customer_id',$request['customer_id']);
			});
		} */
		if(!is_null($request['invoice_id'])) {
			$queryProduct->whereHas('purchaseInwardStock',function($q) use ($request){
				return $q->where('id',$request['invoice_id']);
			});
		}
		if(!is_null($request['product_id'])) {
			$queryProduct->where('product_id',$request['product_id']);
		}
		if(!is_null($request['brand'])) {
			$queryProduct->whereHas('product',function($q) use ($request){
				return $q->where('brand_id',$request['brand']);
			});
		}
		if(!is_null($request['dosage'])) {
			$queryProduct->whereHas('product',function($q) use ($request){
				return $q->where('dosage_id',$request['dosage']);
			});
		}
		if(!is_null($request['company'])) {
			$queryProduct->whereHas('product',function($q) use ($request){
				return $q->where('company_id',$request['company']);
			});
		}

        if(!is_null($request['store_id'])) {
			$queryProduct->where('branch_id', $request['store_id']);
		}

		$total_qty =  $queryProduct->sum('product_qty');
		$total_profit =  $queryProduct->sum('profit_amount');
		$profit_percent =  $queryProduct->sum('profit_percent');

        $profitpersent = 0;
        if($profit_percent!=0){
            $profitpersent = ($profit_percent/$queryProduct->count());
        }
		//$total_cost =  0;
		$all_product = $queryProduct->get();
		$products = $queryProduct->paginate(10);
		$data['heading']    = 'Product Purchase List';
		$data['breadcrumb'] = ['Purchase', '', 'List'];
		$data['products']   = $products;
		$data['total_invoice']  = count(array_unique(array_column($all_product->toArray(), 'inward_stock_id')));
		$data['total_qty']  = $total_qty;
		$data['total_profit'] = $total_profit;
		$data['profitpersent'] = $profitpersent;
		$data['brands'] = Brand::all();
		$data['dosages'] = Dosage::all();
		$data['companies']      = Company::all();
        $data['storeUsers'] = User::select('id','name','email','phone')->where('role',2)->get();
		return view('admin.report.product_wise_purchase_report', compact('data'));
	}
	public function inventory(Request $request){

		// $queryStockProduct = BranchStockProducts::select('product_id', DB::raw('SUM(t_qty) as total_quantity'))->groupBy('product_id');

        // $results = $queryStockProduct->paginate(10);

        // dd($results);

		try{
			$data = [];

            $branch_id=Auth::user()->id;
            $user_role=Auth::user()->role;

            $data['store_name'] = 'All Store';

            if($user_role==1){
			    $queryStockProduct = BranchStockProducts::with(['user', 'product'])->select('product_id', DB::raw('SUM(t_qty) as t_qty'), 'branch_id', 'product_barcode')->groupBy('product_id');
            }else{
                $queryStockProduct = BranchStockProducts::select('product_id', DB::raw('SUM(t_qty) as t_qty'), 'branch_id', 'product_barcode')->groupBy('product_id')->where('branch_id', $branch_id);

                $store_details = User::where('id', $branch_id)->first();
                $data['store_name'] = $store_details->name;
            }

			// $allStockProduct = $queryStockProduct->where('branch_id',$branch_id);

			if(!is_null($request['product_barcode'])) {
				$queryStockProduct->where('product_barcode',$request['product_barcode']);
			}
			if(!is_null($request['brand'])) {
				$queryStockProduct->whereHas('product',function($q) use ($request){
					return $q->where('brand','LIKE','%'.$request['brand'].'%');
				});
			}
			if(!is_null($request['drugstore'])) {
				$queryStockProduct->whereHas('product',function($q) use ($request){
					return $q->where('drugstore_name','LIKE','%'.$request['drugstore'].'%');
				});
			}
			if(!is_null($request['product_id'])) {
				$queryStockProduct->where('product_id',$request['product_id']);
			}

            if(!is_null($request['store_id'])) {
				$queryStockProduct->where('branch_id',$request['store_id']);

                $store_details = User::where('id', $request['store_id'])->first();
                $data['store_name'] = $store_details->name;
			}

            if(!is_null($request['order_by'])) {
                if ($request['order_by']=='htw') {
                    $queryStockProduct->orderBy('t_qty', 'DESC');
                }else{
                    $queryStockProduct->orderBy('t_qty', 'ASC');
                }

			}

			// if(!is_null($request['category'])) {
			//     $queryStockProduct->whereHas('product',function($q) use ($request){
			//         return $q->where('category_id',$request['category']);
			//     });
			// }
			// $allStockProduct = $queryStockProduct->with('stockProduct')->get();

			//echo '<pre>';print_r($allStockProduct);exit;

            // dd($queryStockProduct->paginate(10));

			$stockProducts 		= $queryStockProduct->paginate(10);
			$data['heading']    = 'Sales List';
			$data['breadcrumb'] = ['Sales', '', 'List'];
			$data['products']   = $stockProducts;
			// $allStockProductArr = $allStockProduct->toArray();

			$data['total_qty']  	= 0;
			$data['total_cost'] 	= 0;
			$data['categories'] 	= Category::all();
			$data['sub_categories']	= Subcategory::all();
            $data['storeUsers'] = User::select('id','name','email','phone')->where('role',2)->get();
			//$data['brands']      	= Brand::all();
			return view('admin.report.inventory', compact('data'));
		}catch (\Exception $e) {
			return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
		}
	}




	/*Stock Transfer Report Pdf*/
	public function itemWiseSaleStockTransferReportPdf(Request $request){
		$branch_id		= Session::get('branch_id');
		$satrt_date 	= $request->start_date.' 00:00:00';
		$end_date 		= $request->end_date.' 23:59:00';
        $items = [];


		//echo '<pre>';print_r($branch_id);exit;


        $queryProduct = DailyStockTransferHistory::with('category')->groupBy(['category_id'])->whereBetween('created_at', [$satrt_date, $end_date])->where('branch_id',$branch_id);
		if(isset($request->category) && !empty($request->category)){
			$queryProduct->where('category_id',$request->category);
		}
		$group_cat_products=$queryProduct->get();


		//echo '<pre>';print_r($group_cat_products);exit;

        foreach($group_cat_products as $group_cat_product){
            //$items['category'][] =  $group_cat_product->category->name;

            $query_sub_cat_products = DailyStockTransferHistory::
                        where('category_id',$group_cat_product->category->id)
						->where('branch_id',$branch_id)
                        ->whereBetween('created_at', [$satrt_date, $end_date])
                        ->groupBy(['subcategory_id']);
			if(isset($request->subcategory_id) && !empty($request->subcategory_id)){
					$query_sub_cat_products->where('subcategory_id',$request->subcategory_id);
			}
			$group_sub_cat_products =	$query_sub_cat_products->get();




            foreach($group_sub_cat_products as $group_sub_cat_product){
                //$items['category'][$group_cat_product->category->name][] = $group_sub_cat_product->subCategory->name;
				//echo '<pre>';print_r($group_sub_cat_product->category_id);exit;
                $sales_products = DailyStockTransferHistory::where('subcategory_id',$group_sub_cat_product->subCategory->id)
					->where('branch_id',$branch_id)
                    ->where('category_id',$group_sub_cat_product->category_id)
                    ->whereBetween('created_at', [$satrt_date, $end_date])
                    ->groupBy(['product_id','size_id'])
                    ->selectRaw('product_id,product_mrp,sum(total_qty) as total_bottles,sum(total_ml) as total_ml,sum(product_mrp * total_qty) as total_ammount')
					// ->select(DB::raw('sum(product_mrp * total_qty) as total_ammount'))
					//->selectRaw('product_name,product_mrp,sum(product_qty) as total_bottles,sum(total_cost) as total_ammount,sum(size_ml) as total_ml')
                    ->get();
			    //echo '<pre>';print_r($sales_products);exit;
                $items[$group_cat_product->category->name][$group_sub_cat_product->subCategory->name]    = $sales_products;

				//echo '<pre>';print_r($items);exit;
            }
        }
        /*$payment_type_ammount = SellInwardStock::whereBetween('payment_date', [$satrt_date, $end_date])
                        ->groupBy(['payment_method'])
                        ->selectRaw('payment_method,sum(pay_amount) as total_payment')
                        ->get();*/

		$company_name		= Common::get_user_settings($where=['option_name'=>'company_name'],$branch_id);
		$company_address	= Common::get_user_settings($where=['option_name'=>'company_address'],$branch_id);
		$address2			= Common::get_user_settings($where=['option_name'=>'company_address2'],$branch_id);
		$company_licensee	= Common::get_user_settings($where=['option_name'=>'company_licensee'],$branch_id);

		$payment_type_ammount=0;

        $data = [];
        $data['items'] = $items;
        $data['shop_name'] = $company_name;
        $data['shop_address'] = $address2;
        $data['from_date'] = Carbon::create($request->start_date)->format('d-M-Y');
        $data['to_date'] = Carbon::create($request->end_date)->format('d-M-Y');
        $data['payment_type_ammount'] = $payment_type_ammount;

		//echo '<pre>';print_r($data);exit;



        $pdf = PDF::loadView('admin.pdf.stock-transfer.item-wise-sales-report', $data);
         //echo "<pre>";print_r($items);die;
		return $pdf->stream(now().'-invoice.pdf');

    }

	/*Stock Transfer Report Pdf*/

















	public function check_counter_sell(Request $request){
		$branch_id	= Session::get('branch_id');
		//$start_date = date('Y-m-d',strtotime('2021-09-01'));

		//http://127.0.0.1:8000/admin/check_counter_sell

		$sell_result 	= SellStockProducts::where('branch_id',$branch_id)->orderBy('id', 'asc')->first();
		$start_date		= isset($sell_result->created_at)?date('Y-m-d',strtotime($sell_result->created_at)):'';

		$size_cost_data_show=[];
		if($start_date!=''){
			$current_date=date('Y-m-d');
			$diff 		= strtotime($current_date) - strtotime($start_date);
			$total_day	= round($diff / 86400);

			for($i=0;$total_day>$i;$i++){
				$sell_date				= date('Y-m-d', strtotime("+".$i." day", strtotime($start_date)));


				//print_r($prev_sell_date);exit;



				$category_result 		= SellStockProducts::select('category_id')->whereBetween('created_at', [$sell_date." 00:00:00", $sell_date." 23:59:59"])->distinct()->get();
				$sub_category_result 	= SellStockProducts::select('subcategory_id')->whereBetween('created_at', [$sell_date." 00:00:00", $sell_date." 23:59:59"])->distinct()->get();
				$size_result 			= SellStockProducts::select('size_id')->whereBetween('created_at', [$sell_date." 00:00:00", $sell_date." 23:59:59"])->distinct()->get();
				$product_result 		= SellStockProducts::select('product_id')->whereBetween('created_at', [$sell_date." 00:00:00", $sell_date." 23:59:59"])->distinct()->get();

				//echo '<pre>';print_r($product_result);exit;

				foreach($category_result as $cat_row){
					$category_id=$cat_row->category_id;
					foreach($sub_category_result as $sub_cat_row){
						$subcategory_id=$sub_cat_row->subcategory_id;
						foreach($size_result as $size_row){
							$size_id=$size_row->size_id;

							foreach($product_result as $product_row){
								$product_id=$product_row->product_id;

								$dateWise_sell_result = SellStockProducts::selectRaw('sum(total_ml) as total_ml,sum(product_qty) as total_qty,barcode,product_mrp')->whereBetween('created_at', [$sell_date." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->where('size_id',$size_id)->where('product_id',$product_id)->get();

								$total_sell_ml = isset($dateWise_sell_result[0]->total_ml)?$dateWise_sell_result[0]->total_ml:'0';

								//print_r($total_sell_ml);exit;

								if($total_sell_ml>0){
									$openingStockProductResult = OpeningStockProducts::where('branch_id',$branch_id)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->where('size_id',$size_id)->where('product_id',$product_id)->first();
									$start_opening_stock_ml	= isset($openingStockProductResult->total_ml)?$openingStockProductResult->total_ml:'0';
									$start_opening_stock	= isset($openingStockProductResult->product_qty)?$openingStockProductResult->product_qty:'0';

									$all_sell_result = SellStockProducts::selectRaw('sum(total_ml) as total_ml,sum(product_qty) as total_qty,barcode')->whereBetween('created_at', [$start_date." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->where('size_id',$size_id)->where('product_id',$product_id)->get();

									$end_total_sell		= isset($all_sell_result[0]->total_ml)?$all_sell_result[0]->total_ml:'0';
									$end_total_qty_sell	= isset($all_sell_result[0]->total_qty)?$all_sell_result[0]->total_qty:'0';

									$prev_sell_date		= date('Y-m-d', strtotime("-1 day", strtotime($sell_date)));

									$prev_datewise_sell_result = DailyProductSellHistory::where('branch_id',$branch_id)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->where('size_id',$size_id)->where('product_id',$product_id)->orderBy('id', 'DESC')->first();
									//echo $prev_sell_date.'--'.$sell_date.'</br>';
									//echo '<pre>';print_r($prev_datewise_sell_result);exit;


									$prev_closing_stock	  = isset($prev_datewise_sell_result->closing_stock)?$prev_datewise_sell_result->closing_stock:'';
									$prev_closing_stock_ml= isset($prev_datewise_sell_result->closing_stock_ml)?$prev_datewise_sell_result->closing_stock_ml:'';
									//echo $prev_closing_stock_ml.'</br>';

									$check_datewise_sell_result = DailyProductSellHistory::whereBetween('created_at', [$sell_date." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->where('size_id',$size_id)->where('product_id',$product_id)->first();

									//print_r($check_datewise_sell_result);exit;


									$check_sell_id		  = isset($check_datewise_sell_result->id)?$check_datewise_sell_result->id:'';

									$opening_stock 		= $start_opening_stock;
									$opening_stock_ml	= $start_opening_stock_ml;


									$barcode		= isset($dateWise_sell_result[0]->barcode)?$dateWise_sell_result[0]->barcode:'0';
									$product_mrp	= isset($dateWise_sell_result[0]->product_mrp)?$dateWise_sell_result[0]->product_mrp:'0';
									$total_sell		= isset($dateWise_sell_result[0]->total_ml)?$dateWise_sell_result[0]->total_ml:'0';
									$total_qty_sell	= isset($dateWise_sell_result[0]->total_qty)?$dateWise_sell_result[0]->total_qty:'0';

									if($prev_closing_stock_ml!=''){
										$opening_stock 		= $prev_closing_stock;
										$opening_stock_ml 	= $prev_closing_stock_ml;
									}

									$closing_stock		= $opening_stock-$total_qty_sell;
									$closing_stock_ml	= $opening_stock_ml-$total_sell;

									if($check_sell_id!=''){
										$size_cost_data=array(
											'branch_id'  		=> $branch_id,
											'category_id'		=> $category_id,
											'subcategory_id'	=> $subcategory_id,
											'product_barcode'	=> $barcode,
											'product_id'  		=> $product_id,
											'size_id'  			=> $size_id,
											'total_ml'  		=> $total_sell,
											'total_qty'  		=> $total_qty_sell,
											'opening_stock'  	=> $opening_stock,
											'closing_stock'  	=> $closing_stock,
											'opening_stock_ml'  => $opening_stock_ml,
											'closing_stock_ml' 	=> $closing_stock_ml,
											'product_mrp'		=> $product_mrp,
											'created_at' 		=> $sell_date." 18:25:26",
											'updated_at' 		=> $sell_date." 18:25:26"
										);

										//echo '<pre>';print_r($size_cost_data);exit;
									}else{

										//echo '<pre>';print_r($prev_datewise_sell_result);exit;

										$size_cost_data=array(
											'branch_id'  		=> $branch_id,
											'category_id'		=> $category_id,
											'subcategory_id'	=> $subcategory_id,
											'product_barcode'	=> $barcode,
											'product_id'  		=> $product_id,
											'size_id'  			=> $size_id,
											'total_ml'  		=> $total_sell,
											'total_qty'  		=> $total_qty_sell,
											'opening_stock'  	=> $opening_stock,
											'closing_stock'  	=> $closing_stock,
											'opening_stock_ml'  => $opening_stock_ml,
											'closing_stock_ml' 	=> $closing_stock_ml,
											'created_at' 		=> $sell_date." 18:25:26",
											'updated_at' 		=> $sell_date." 18:25:26"
										);

										DailyProductSellHistory::create($size_cost_data);
										//echo '<pre>';print_r($size_cost_data);exit;






										$size_cost_data_show[]=array(
											'sell_date'  		=> $sell_date,
											'branch_id'  		=> $branch_id,
											'category_id'		=> $category_id,
											'subcategory_id'	=> $subcategory_id,
											'product_barcode'	=> $barcode,
											'product_id'  		=> $product_id,
											'size_id'  			=> $size_id,
											'total_ml'  		=> $total_sell,
											'total_qty'  		=> $total_qty_sell,
											'opening_stock'  	=> $opening_stock,
											'closing_stock'  	=> $closing_stock,
											'opening_stock_ml'  => $opening_stock_ml,
											'closing_stock_ml' 	=> $closing_stock_ml,
											'created_at' 		=> $sell_date." 18:25:26",
											'updated_at' 		=> $sell_date." 18:25:26"
										);

										//DailyProductSellHistory::create($size_cost_data);
									}
								}
							}
						}
					}
				}
			}
		}

		echo '<pre>';print_r($size_cost_data_show);exit;
	}





    public function sales(Request $request)
    {

        DB::beginTransaction();
        try {
            $data = [];

            $data['heading'] 		= 'Add Order';
            $data['breadcrumb'] 	= ['Stock', 'Purchase Order', 'Add'];
            $data['supplier'] 		= Supplier::all();
            $data['product'] 		= Product::all();

            return view('admin.report.sales_report', compact('data'));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }

	public function e_report(Request $request){
		$result=[];
		$categories 	= Category::where('food_type',1)->get();
		$branch_id		= Session::get('branch_id');
		if(count($categories)>0){
			foreach($categories as $row){
				$category_id=$row->id;
				$sub_cat_result=[];


				$cat_opening_balance = 0;
				$cat_receipt_balance = 0;
				$cat_sales			 = 0;
				$cat_closing_balance = 0;
				$cat_prevYear_closing_balance=0;

				$catehoty_wise_subcategory=Product::select('subcategory_id')->distinct()->where('category_id',$row->id)->get();
				if(count($catehoty_wise_subcategory)>0){
					foreach($catehoty_wise_subcategory as $sub_row){

						$subcategory_id	 = $sub_row->subcategory_id;
						$opening_balance = 0;
						$receipt_balance = 0;
						$sales			 = 0;
						$closing_balance = 0;
						$prevYear_closing_balance=0;


						$first_day_this_month = date('Y-m-01',strtotime($request->start_date));
						$last_day_this_month  = date('Y-m-t',strtotime($request->start_date));

						$dateS = date('Y-m-d',strtotime($first_day_this_month));
						$dateE = date('Y-m-d',strtotime($last_day_this_month));


						$datewise_sell_result = DailyProductSellHistory::where('branch_id',$branch_id)->orderBy('id', 'asc')->first();

						$start_date = isset($datewise_sell_result->created_at)?date('Y-m-d',strtotime($datewise_sell_result->created_at)):'';

						if($start_date!=''){
							$dateP = date('Y-m-t', strtotime('last month'));
							$prevYeardateS = date('Y-m-01',strtotime('-1 year'));
							$prevYeardateE = date('Y-m-t',strtotime('-1 year'));

							$prevYear_sell_result = SellStockProducts::selectRaw('sum(total_ml) as total_ml')->whereBetween('created_at', [$start_date." 00:00:00", $prevYeardateE." 23:59:59"])->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->get();
							$prevYear_total_sell=isset($prevYear_sell_result[0]->total_ml)?$prevYear_sell_result[0]->total_ml:'0';

							$prevYear_purchase_result = InwardStockProducts::selectRaw('sum(total_ml) as total_ml')->whereBetween('created_at', [$start_date." 00:00:00", $prevYeardateE." 23:59:59"])->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->get();
							$prevYear_receipt_balance=isset($prevYear_purchase_result[0]->total_ml)?$prevYear_purchase_result[0]->total_ml:'0';

							if($prevYear_total_sell=0 && $prevYear_receipt_balance!=0){
								$prevYear_closing_balance=$prevYear_receipt_balance-$prevYear_total_sell;
							}

							$prev_purchase_result = InwardStockProducts::selectRaw('sum(total_ml) as total_ml')->whereBetween('created_at', [$start_date." 00:00:00", $dateP." 23:59:59"])->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->get();
							$prev_receipt_balance=isset($prev_purchase_result[0]->total_ml)?$prev_purchase_result[0]->total_ml:'0';

							$prev_sell_result = SellStockProducts::selectRaw('sum(total_ml) as total_ml')->whereBetween('created_at', [$start_date." 00:00:00", $dateP." 23:59:59"])->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->get();
							$prev_total_sell=isset($prev_sell_result[0]->total_ml)?$prev_sell_result[0]->total_ml:'0';

							$sell_result = SellStockProducts::selectRaw('sum(total_ml) as total_ml')->whereBetween('created_at', [$start_date." 00:00:00", $dateE." 23:59:59"])->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->get();
						$total_sell=isset($sell_result[0]->total_ml)?$sell_result[0]->total_ml:'0';

							$purchase_result = InwardStockProducts::selectRaw('sum(total_ml) as total_ml')->whereBetween('created_at', [$start_date." 00:00:00", $dateE." 23:59:59"])->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->get();
							$receipt_balance=isset($purchase_result[0]->total_ml)?$purchase_result[0]->total_ml:'0';

							if($prev_receipt_balance==0){
								$opening_balance=$receipt_balance;
							}
							if($opening_balance!=0 && $total_sell!=0){
								$closing_balance=$opening_balance-$total_sell;
							}

							if($opening_balance!=0){
								$opening_balance=$opening_balance/1000;
							}
							if($receipt_balance!=0){
								$receipt_balance=$receipt_balance/1000;
							}
							if($total_sell!=0){
								$total_sell=$total_sell/1000;
							}
							if($closing_balance!=0){
								$closing_balance=$closing_balance/1000;
							}
							if($prevYear_closing_balance!=0){
								$prevYear_closing_balance=$prevYear_closing_balance/1000;
							}

							$cat_opening_balance += $opening_balance;
							$cat_receipt_balance += $receipt_balance;
							$cat_sales			 += $total_sell;
							$cat_closing_balance += $closing_balance;
							$cat_prevYear_closing_balance +=$prevYear_closing_balance;

							$sub_cat_result[]=array(
							'category_id'		=> $sub_row->subcategory_id,
							'category_name'		=> $sub_row->subcategory->name,
							'opening_balance'	=> $opening_balance,
							'receipt_balance'	=> $receipt_balance,
							'total_sell'		=> $total_sell,
							'closing_balance'	=> $closing_balance,
							'prevYear_closing_balance'=> $prevYear_closing_balance
						);


						}
					}
				}


				//echo '<pre>';print_r($sub_cat_result);exit;

				 //Subcategory::all();

				 $category_img='';
				 if($row->id==1){
					 $category_img=url('images/imfl.png');
				 }else if($row->id==2){
					 $category_img=url('images/os.png');
				 }else if($row->id==3){
					 $category_img=url('images/cs.png');
				 }else if($row->id==4){
					 $category_img=url('images/osbl.png');
				 }


				$result[]=array(
					'category_id'	=> $row->id,
					'category_name'	=> $row->name,
					'opening_balance'=>$cat_opening_balance,
					'receipt_balance'	=> $cat_receipt_balance,
					'total_sell'		=> $cat_sales,
					'closing_balance'	=> $cat_closing_balance,
					'prevYear_closing_balance'=> $cat_prevYear_closing_balance,
					'category_img'	=> $category_img,
					'sub_category'	=> $sub_cat_result
				);
			}
		}

		$company_name		= Common::get_user_settings($where=['option_name'=>'company_name'],$branch_id);
		$company_address	= Common::get_user_settings($where=['option_name'=>'company_address'],$branch_id);
		$address2			= Common::get_user_settings($where=['option_name'=>'company_address2'],$branch_id);
		$company_licensee	= Common::get_user_settings($where=['option_name'=>'company_licensee'],$branch_id);


		$data['result'] 			= $result;
        $data['trader_id_no'] 		= '';
		$data['licensee_no'] 		= $company_licensee;
        $data['shop_address'] 		= $company_address;
		$data['shop_address2'] 		= $address2;
		$data['shop_name'] 			= $company_name;
		$data['month'] 				= date('F Y',strtotime($request->start_date));



		//echo '<pre>';print_r($data);exit;

		$pdf = PDF::loadView('admin.pdf.e-report', compact('data'));
		return $pdf->stream(now().'-e-report.pdf');
	}
	public function stock_transfer_e_report(Request $request){
		$branch_id		= Session::get('branch_id');

		$categories 	= Category::whereIn('id', ['1','2','4'])->get();
		//$categories 	= Category::whereIn('id', ['2'])->get();
		//echo '<pre>';print_r($categories);exit;

		$openingStockProductsDateresult 	= OpeningStockProducts::where('branch_id',$branch_id)->orderBy('id', 'asc')->first();
		$opening_start_date					= isset($openingStockProductsDateresult->created_at)?date('Y-m-d',strtotime($openingStockProductsDateresult->created_at)):'';

		$imfl_osbi_os_result=[];
		if(count($categories)>0){
			foreach($categories as $row){
				$category_id=$row->id;
				$sub_cat_result=[];


				$cat_opening_balance = 0;
				$cat_receipt_balance = 0;
				$cat_sales			 = 0;
				$cat_closing_balance = 0;
				$cat_prevYear_closing_balance=0;

				//$category_wise_subcategory=Product::select('subcategory_id')->distinct()->where('category_id',$row->id)->get();


				$category_wise_imfl_subcategory = Subcategory::whereIn('id', ['4','5', '7', '8', '9','12','13','14'])->orderBy('sort','asc')->get();
				//$category_wise_imfl_subcategory = Subcategory::whereIn('id', ['9'])->orderBy('sort','asc')->get();

				//echo '<pre>';print_r($category_wise_subcategory);exit;

				if(count($category_wise_imfl_subcategory)>0){
					foreach($category_wise_imfl_subcategory as $sub_row){

						$subcategory_id	 = $sub_row->id;
						$opening_balance = 0;
						$receipt_balance = 0;
						$sales			 = 0;
						$closing_balance = 0;
						$prevYear_closing_balance=0;

						//print_r($subcategory_id);exit;


						$first_day_this_month = date('Y-m-01',strtotime($request->start_date));
						$last_day_this_month  = date('Y-m-t',strtotime($request->start_date));

						$start_date = date('Y-m-d',strtotime($first_day_this_month));
						$end_date 	= date('Y-m-d',strtotime($last_day_this_month));

						if($start_date!=''){
							//$dateP = date('Y-m-t', strtotime('last month'));
							//$prevYeardateS = date('Y-m-01',strtotime('-1 year'));
							//$prevYeardateE = date('Y-m-t',strtotime('-1 year'));

							$openingStockProductResult = OpeningStockProducts::selectRaw('sum(total_ml) as total_opening_ml')->where('branch_id',$branch_id)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->first();
							$total_opening_stock	= isset($openingStockProductResult->total_opening_ml)?$openingStockProductResult->total_opening_ml:'0';
							//echo '<pre>';print_r($datewise_start_opening_stock);exit;

							$start_sell_result 	= DailyStockTransferHistory::where('branch_id',$branch_id)->orderBy('id', 'asc')->first();
							$sell_start_date 	= isset($start_sell_result->created_at)?date('Y-m-d',strtotime($start_sell_result->created_at)):'';

							$start_sell_result 		= DailyProductPurchaseHistory::where('branch_id',$branch_id)->orderBy('id', 'asc')->first();
							$purchase_start_date	= isset($start_sell_result->created_at)?date('Y-m-d',strtotime($start_sell_result->created_at)):'';

							//echo '<pre>';print_r($start_date);exit;



							$sell_result = DailyStockTransferHistory::selectRaw('sum(total_ml) as total_sell_ml')->whereBetween('created_at', [$sell_start_date." 00:00:00", $end_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->first();
							$total_sell_stock		= isset($sell_result->total_sell_ml)?$sell_result->total_sell_ml:0;
							//echo '<pre>';print_r($total_sell_stock);exit;

							$sell_result = DailyStockTransferHistory::selectRaw('sum(total_ml) as total_sell_ml')->whereBetween('created_at', [$start_date." 00:00:00", $end_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->first();
							$month_wise_total_sell_stock	= isset($sell_result->total_sell_ml)?$sell_result->total_sell_ml:0;
							//echo '<pre>';print_r($month_wise_total_sell_stock);exit;


							$purchase_history_result 	= DailyProductPurchaseHistory::selectRaw('sum(total_ml) as total_purchase_ml')->whereBetween('created_at', [$purchase_start_date." 00:00:00", $end_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->orderBy('id', 'DESC')->first();
							$total_purchase_stock =isset($purchase_history_result->total_purchase_ml)?$purchase_history_result->total_purchase_ml:'0';

							$month_purchase_history_result 	= DailyProductPurchaseHistory::selectRaw('sum(total_ml) as total_purchase_ml')->whereBetween('created_at', [$start_date." 00:00:00", $end_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->orderBy('id', 'DESC')->first();
							$month_wise_total_purchase_stock =isset($month_purchase_history_result->total_purchase_ml)?$month_purchase_history_result->total_purchase_ml:'0';

							//echo '<pre>';print_r($total_purchase_stock);exit;





							$opening_balance			= 0;
							$receipt_balance			= 0;
							$total_sell					= $total_sell_stock;
							$closing_stock				= (($total_purchase_stock+$total_opening_stock)-$total_sell);
							$opening_stock				= ($closing_stock-$month_wise_total_purchase_stock)+$total_sell;
							$receipt_balance			= $month_wise_total_purchase_stock;

							if($total_sell>0){
								$closing_stock				= (($total_purchase_stock+$total_opening_stock)-$total_sell);
								$opening_stock				= ($closing_stock-$month_wise_total_purchase_stock)+$total_sell;
								//echo 'opening_stock-'.$opening_stock.' closing_stock-'.$closing_stock.'</br>';
							}

							if($total_sell_stock>0){
								//echo 'month_wise_total_purchase_stock-'.$month_wise_total_purchase_stock.'</br>';
								if($month_wise_total_purchase_stock==0){
									$opening_stock				= (($total_purchase_stock+$total_opening_stock)-$total_sell);
									//echo 'opening_stock-'.$opening_stock.' closing_stock-'.$closing_stock.'</br>';
								}

							}
							//DONT-DELETE_THIS__FOLDER

							//echo '<pre>';print_r($closing_stock);exit;

							$prevYear_closing_balance	=0;

							$opening_stock=$closing_stock+$month_wise_total_sell_stock;

							//echo '<pre>';print_r($opening_stock);exit;



							$cat_opening_balance += $opening_stock;
							$cat_receipt_balance += $receipt_balance;
							$cat_sales			 += $month_wise_total_sell_stock;
							$cat_closing_balance += $closing_stock;
							//$cat_prevYear_closing_balance +=$prevYear_closing_balance;

							$sub_cat_result[]=array(
								'sub_category_id'	=> $sub_row->id,
								'sub_category_name'	=> $sub_row->name,
								'opening_balance'	=> $opening_stock/1000,
								'receipt_balance'	=> $receipt_balance/1000,
								'total_sell'		=> $month_wise_total_sell_stock/1000,
								'closing_balance'	=> $closing_stock/1000,
								'prevYear_closing_balance'=> $prevYear_closing_balance,

								/*'test_total_sell'		=> $total_sell_stock,
								'total_opening_stock'	=> $total_opening_stock,
								'total_purchase_stock'	=> $total_purchase_stock,
								'month_wise_total_purchase_stock'	=> $month_wise_total_purchase_stock,*/

							);

							//echo '<pre>';print_r($sub_cat_result);exit;


						}
					}
				}
				$imfl_osbi_os_result[]=array(
					'category_id'	=> $row->id,
					'category_name'	=> $row->title,
					'opening_balance'	=>$cat_opening_balance/1000,
					'receipt_balance'	=> $cat_receipt_balance/1000,
					'total_sell'		=> $cat_sales/1000,
					'closing_balance'	=> $cat_closing_balance/1000,
					'prevYear_closing_balance'=> $cat_prevYear_closing_balance,
					//'category_img'	=> $category_img,
					'sub_category'	=> $sub_cat_result
				);
			}
		}

		//echo '<pre>';print_r($imfl_osbi_os_result);exit;


		$categories 	= Category::whereIn('id', ['3'])->get();
		$cs_result=[];
		if(count($categories)>0){
			foreach($categories as $row){
				$category_id=$row->id;
				$sub_cat_result=[];


				$cat_opening_balance = 0;
				$cat_receipt_balance = 0;
				$cat_sales			 = 0;
				$cat_closing_balance = 0;
				$cat_prevYear_closing_balance=0;

				//$category_wise_subcategory=Product::select('subcategory_id')->distinct()->where('category_id',$row->id)->get();


				$category_wise_cs_subcategory = Subcategory::whereIn('id', ['1','17', '18', '20'])->orderBy('sort','asc')->get();

				//echo '<pre>';print_r($category_wise_cs_subcategory);exit;
				$sub_cat_result=[];
				if(count($category_wise_cs_subcategory)>0){
					foreach($category_wise_cs_subcategory as $sub_row){

						$subcategory_id	 = $sub_row->id;
						$opening_balance = 0;
						$receipt_balance = 0;
						$sales			 = 0;
						$closing_balance = 0;
						$prevYear_closing_balance=0;

						//print_r($subcategory_id);exit;


						$first_day_this_month = date('Y-m-01',strtotime($request->start_date));
						$last_day_this_month  = date('Y-m-t',strtotime($request->start_date));

						$start_date = date('Y-m-d',strtotime($first_day_this_month));
						$end_date 	= date('Y-m-d',strtotime($last_day_this_month));

						if($start_date!=''){
							//$dateP = date('Y-m-t', strtotime('last month'));
							//$prevYeardateS = date('Y-m-01',strtotime('-1 year'));
							//$prevYeardateE = date('Y-m-t',strtotime('-1 year'));

							$openingStockProductResult = OpeningStockProducts::selectRaw('sum(total_ml) as total_opening_ml')->where('branch_id',$branch_id)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->first();
							$total_opening_stock	= isset($openingStockProductResult->total_opening_ml)?$openingStockProductResult->total_opening_ml:'0';
							//echo '<pre>';print_r($datewise_start_opening_stock);exit;

							$start_sell_result 	= DailyStockTransferHistory::where('branch_id',$branch_id)->orderBy('id', 'asc')->first();
							$sell_start_date 	= isset($start_sell_result->created_at)?date('Y-m-d',strtotime($start_sell_result->created_at)):'';

							$start_sell_result 		= DailyProductPurchaseHistory::where('branch_id',$branch_id)->orderBy('id', 'asc')->first();
							$purchase_start_date	= isset($start_sell_result->created_at)?date('Y-m-d',strtotime($start_sell_result->created_at)):'';



							$sell_result = DailyStockTransferHistory::selectRaw('sum(total_ml) as total_sell_ml')->whereBetween('created_at', [$sell_start_date." 00:00:00", $end_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->first();
							$total_sell_stock		= isset($sell_result->total_sell_ml)?$sell_result->total_sell_ml:0;

							$sell_result = DailyStockTransferHistory::selectRaw('sum(total_ml) as total_sell_ml')->whereBetween('created_at', [$start_date." 00:00:00", $end_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->first();
							$month_wise_total_sell_stock	= isset($sell_result->total_sell_ml)?$sell_result->total_sell_ml:0;


							$purchase_history_result 	= DailyProductPurchaseHistory::selectRaw('sum(total_ml) as total_purchase_ml')->whereBetween('created_at', [$purchase_start_date." 00:00:00", $end_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->orderBy('id', 'DESC')->first();
							$total_purchase_stock =isset($purchase_history_result->total_purchase_ml)?$purchase_history_result->total_purchase_ml:'0';

							$month_purchase_history_result 	= DailyProductPurchaseHistory::selectRaw('sum(total_ml) as total_purchase_ml')->whereBetween('created_at', [$start_date." 00:00:00", $end_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->orderBy('id', 'DESC')->first();
							$month_wise_total_purchase_stock =isset($month_purchase_history_result->total_purchase_ml)?$month_purchase_history_result->total_purchase_ml:'0';

							//echo '<pre>';print_r($total_purchase_stock);exit;





							$opening_balance			= 0;
							$receipt_balance			= 0;
							$total_sell					= $total_sell_stock;
							$closing_stock				= (($total_purchase_stock+$total_opening_stock)-$total_sell);
							$opening_stock				= ($closing_stock-$month_wise_total_purchase_stock)+$total_sell;
							$receipt_balance			= $month_wise_total_purchase_stock;

							if($total_sell>0){
								$closing_stock				= (($total_purchase_stock+$total_opening_stock)-$total_sell);
								$opening_stock				= ($closing_stock-$month_wise_total_purchase_stock)+$total_sell;
							}

							if($total_sell_stock>0){
								if($month_wise_total_purchase_stock==0){
									$opening_stock				= (($total_purchase_stock+$total_opening_stock)-$total_sell);
								}

							}
							//DONT-DELETE_THIS__FOLDER

							//echo '<pre>';print_r($closing_stock);exit;

							$prevYear_closing_balance	=0;

							$opening_stock=$closing_stock+$month_wise_total_sell_stock;



							$cat_opening_balance += $opening_stock;
							$cat_receipt_balance += $receipt_balance;
							$cat_sales			 += $month_wise_total_sell_stock;
							$cat_closing_balance += $closing_stock;
							//$cat_prevYear_closing_balance +=$prevYear_closing_balance;

							$sub_cat_result[]=array(
								'sub_category_id'	=> $sub_row->id,
								'sub_category_name'	=> $sub_row->name,
								'opening_balance'	=> $opening_stock/1000,
								'receipt_balance'	=> $receipt_balance/1000,
								'total_sell'		=> $month_wise_total_sell_stock/1000,
								'closing_balance'	=> $closing_stock/1000,
								'prevYear_closing_balance'=> $prevYear_closing_balance,

								/*'test_total_sell'		=> $total_sell_stock,
								'total_opening_stock'	=> $total_opening_stock,
								'total_purchase_stock'	=> $total_purchase_stock,
								'month_wise_total_purchase_stock'	=> $month_wise_total_purchase_stock,*/

							);

							//echo '<pre>';print_r($sub_cat_result);exit;


						}
					}
				}
				$cs_result[]=array(
					'category_id'	=> $row->id,
					'category_name'	=> $row->title,
					'opening_balance'	=>$cat_opening_balance/1000,
					'receipt_balance'	=> $cat_receipt_balance/1000,
					'total_sell'		=> $cat_sales/1000,
					'closing_balance'	=> $cat_closing_balance/1000,
					'prevYear_closing_balance'=> $cat_prevYear_closing_balance,
					//'category_img'	=> $category_img,
					'sub_category'	=> $sub_cat_result
				);
			}
		}






		$subcategory_id	 = 3;
		$opening_balance = 0;
		$receipt_balance = 0;
		$sales			 = 0;
		$closing_balance = 0;
		$prevYear_closing_balance=0;

		$first_day_this_month = date('Y-m-01',strtotime($request->start_date));
		$last_day_this_month  = date('Y-m-t',strtotime($request->start_date));

		$start_date = date('Y-m-d',strtotime($first_day_this_month));
		$end_date 	= date('Y-m-d',strtotime($last_day_this_month));

		$beer_result=[];
		if($start_date!=''){
			$openingStockProductResult = OpeningStockProducts::selectRaw('sum(total_ml) as total_opening_ml')->where('branch_id',$branch_id)->where('subcategory_id',$subcategory_id)->first();
			$total_opening_stock	= isset($openingStockProductResult->total_opening_ml)?$openingStockProductResult->total_opening_ml:'0';


			$start_sell_result 	= DailyStockTransferHistory::where('branch_id',$branch_id)->orderBy('id', 'asc')->first();
			$sell_start_date 	= isset($start_sell_result->created_at)?date('Y-m-d',strtotime($start_sell_result->created_at)):'';

			$start_sell_result 		= DailyProductPurchaseHistory::where('branch_id',$branch_id)->orderBy('id', 'asc')->first();
			$purchase_start_date	= isset($start_sell_result->created_at)?date('Y-m-d',strtotime($start_sell_result->created_at)):'';

			$sell_result = DailyStockTransferHistory::selectRaw('sum(total_ml) as total_sell_ml')->whereBetween('created_at', [$sell_start_date." 00:00:00", $end_date." 23:59:59"])->where('branch_id',$branch_id)->where('subcategory_id',$subcategory_id)->first();
			$total_sell_stock		= isset($sell_result->total_sell_ml)?$sell_result->total_sell_ml:0;

			$sell_result = DailyStockTransferHistory::selectRaw('sum(total_ml) as total_sell_ml')->whereBetween('created_at', [$start_date." 00:00:00", $end_date." 23:59:59"])->where('branch_id',$branch_id)->where('subcategory_id',$subcategory_id)->first();
			$month_wise_total_sell_stock	= isset($sell_result->total_sell_ml)?$sell_result->total_sell_ml:0;


			$purchase_history_result 	= DailyProductPurchaseHistory::selectRaw('sum(total_ml) as total_purchase_ml')->whereBetween('created_at', [$purchase_start_date." 00:00:00", $end_date." 23:59:59"])->where('branch_id',$branch_id)->where('subcategory_id',$subcategory_id)->orderBy('id', 'DESC')->first();
			$total_purchase_stock =isset($purchase_history_result->total_purchase_ml)?$purchase_history_result->total_purchase_ml:'0';

			$month_purchase_history_result 	= DailyProductPurchaseHistory::selectRaw('sum(total_ml) as total_purchase_ml')->whereBetween('created_at', [$start_date." 00:00:00", $end_date." 23:59:59"])->where('branch_id',$branch_id)->where('subcategory_id',$subcategory_id)->orderBy('id', 'DESC')->first();
			$month_wise_total_purchase_stock =isset($month_purchase_history_result->total_purchase_ml)?$month_purchase_history_result->total_purchase_ml:'0';

			$opening_balance			= 0;
			$receipt_balance			= 0;
			$total_sell					= $total_sell_stock;
			$closing_stock				= (($total_purchase_stock+$total_opening_stock)-$total_sell);
			$opening_stock				= ($closing_stock-$month_wise_total_purchase_stock)+$total_sell;
			$receipt_balance			= $month_wise_total_purchase_stock;

			if($total_sell>0){
				$closing_stock				= (($total_purchase_stock+$total_opening_stock)-$total_sell);
				$opening_stock				= ($closing_stock-$month_wise_total_purchase_stock)+$total_sell;
			}

			if($total_sell_stock>0){
				if($month_wise_total_purchase_stock==0){
					$opening_stock	= (($total_purchase_stock+$total_opening_stock)-$total_sell);
				}
			}

			$opening_stock=$closing_stock+$month_wise_total_sell_stock;

			$prevYear_closing_balance	=0;
			$beer_result=array(
				'category_id'		=> 3,
				'category_name'		=> 'Beer',
				'opening_balance'	=> $opening_stock/1000,
				'receipt_balance'	=> $receipt_balance/1000,
				'total_sell'		=> $month_wise_total_sell_stock/1000,
				'closing_balance'	=> $closing_stock/1000,
				'prevYear_closing_balance'=> $prevYear_closing_balance,
			);
		}




		$os_result=[];
		$sub_cat_result=[];
		$cat_opening_balance = 0;
		$cat_receipt_balance = 0;
		$cat_sales			 = 0;
		$cat_closing_balance = 0;
		$cat_prevYear_closing_balance=0;
		$category_os_subcategory = Subcategory::whereIn('id', ['10','6'])->orderBy('sort','asc')->get();
		//echo '<pre>';print_r($category_os_subcategory);exit;
		$sub_cat_result=[];
		if(count($category_os_subcategory)>0){
			foreach($category_os_subcategory as $sub_row){
				$subcategory_id	 = $sub_row->id;
				$opening_balance = 0;
				$receipt_balance = 0;
				$sales			 = 0;
				$closing_balance = 0;
				$prevYear_closing_balance=0;

				$first_day_this_month = date('Y-m-01',strtotime($request->start_date));
				$last_day_this_month  = date('Y-m-t',strtotime($request->start_date));

				$start_date = date('Y-m-d',strtotime($first_day_this_month));
				$end_date 	= date('Y-m-d',strtotime($last_day_this_month));

				if($start_date!=''){
					$openingStockProductResult = OpeningStockProducts::selectRaw('sum(total_ml) as total_opening_ml')->where('branch_id',$branch_id)->where('subcategory_id',$subcategory_id)->first();
					$total_opening_stock	= isset($openingStockProductResult->total_opening_ml)?$openingStockProductResult->total_opening_ml:'0';

					$start_sell_result 	= DailyStockTransferHistory::where('branch_id',$branch_id)->orderBy('id', 'asc')->first();
					$sell_start_date 	= isset($start_sell_result->created_at)?date('Y-m-d',strtotime($start_sell_result->created_at)):'';

					$start_sell_result 		= DailyProductPurchaseHistory::where('branch_id',$branch_id)->orderBy('id', 'asc')->first();
					$purchase_start_date	= isset($start_sell_result->created_at)?date('Y-m-d',strtotime($start_sell_result->created_at)):'';
					$sell_result = DailyStockTransferHistory::selectRaw('sum(total_ml) as total_sell_ml')->whereBetween('created_at', [$sell_start_date." 00:00:00", $end_date." 23:59:59"])->where('branch_id',$branch_id)->where('subcategory_id',$subcategory_id)->first();
					$total_sell_stock		= isset($sell_result->total_sell_ml)?$sell_result->total_sell_ml:0;

					$sell_result = DailyStockTransferHistory::selectRaw('sum(total_ml) as total_sell_ml')->whereBetween('created_at', [$start_date." 00:00:00", $end_date." 23:59:59"])->where('branch_id',$branch_id)->where('subcategory_id',$subcategory_id)->first();
					$month_wise_total_sell_stock	= isset($sell_result->total_sell_ml)?$sell_result->total_sell_ml:0;


					$purchase_history_result 	= DailyProductPurchaseHistory::selectRaw('sum(total_ml) as total_purchase_ml')->whereBetween('created_at', [$purchase_start_date." 00:00:00", $end_date." 23:59:59"])->where('branch_id',$branch_id)->where('subcategory_id',$subcategory_id)->orderBy('id', 'DESC')->first();
					$total_purchase_stock =isset($purchase_history_result->total_purchase_ml)?$purchase_history_result->total_purchase_ml:'0';

					$month_purchase_history_result 	= DailyProductPurchaseHistory::selectRaw('sum(total_ml) as total_purchase_ml')->whereBetween('created_at', [$start_date." 00:00:00", $end_date." 23:59:59"])->where('branch_id',$branch_id)->where('subcategory_id',$subcategory_id)->orderBy('id', 'DESC')->first();
					$month_wise_total_purchase_stock =isset($month_purchase_history_result->total_purchase_ml)?$month_purchase_history_result->total_purchase_ml:'0';

					$opening_balance			= 0;
					$receipt_balance			= 0;
					$total_sell					= $total_sell_stock;
					$closing_stock				= (($total_purchase_stock+$total_opening_stock)-$total_sell);
					$opening_stock				= ($closing_stock-$month_wise_total_purchase_stock)+$total_sell;
					$receipt_balance			= $month_wise_total_purchase_stock;

					if($total_sell>0){
						$closing_stock				= (($total_purchase_stock+$total_opening_stock)-$total_sell);
						$opening_stock				= ($closing_stock-$month_wise_total_purchase_stock)+$total_sell;
					}
					if($total_sell_stock>0){
						if($month_wise_total_purchase_stock==0){
							$opening_stock				= (($total_purchase_stock+$total_opening_stock)-$total_sell);
							}
						}

						$opening_stock=$closing_stock+$month_wise_total_sell_stock;

						$prevYear_closing_balance	=0;
						$cat_opening_balance += $opening_stock;
						$cat_receipt_balance += $receipt_balance;
						$cat_sales			 += $month_wise_total_sell_stock;
						$cat_closing_balance += $closing_stock;

						$sub_cat_result[]=array(
							'sub_category_id'	=> $sub_row->id,
							'sub_category_name'	=> $sub_row->name,
							'opening_balance'	=> $opening_stock/1000,
							'receipt_balance'	=> $receipt_balance/1000,
							'total_sell'		=> $month_wise_total_sell_stock/1000,
							'closing_balance'	=> $closing_stock/1000,
							'prevYear_closing_balance'=> $prevYear_closing_balance,
						);
					}
				}
		}

		$category_id	 = 1;
		$opening_balance = 0;
		$receipt_balance = 0;
		$sales			 = 0;
		$closing_balance = 0;
		$prevYear_closing_balance=0;
		$first_day_this_month = date('Y-m-01',strtotime($request->start_date));
		$last_day_this_month  = date('Y-m-t',strtotime($request->start_date));

		$start_date = date('Y-m-d',strtotime($first_day_this_month));
		$end_date 	= date('Y-m-d',strtotime($last_day_this_month));
		if($start_date!=''){
			$openingStockProductResult = OpeningStockProducts::selectRaw('sum(total_ml) as total_opening_ml')->where('branch_id',$branch_id)->where('category_id',$category_id)->where('strength',50)->first();
			$total_opening_stock	= isset($openingStockProductResult->total_opening_ml)?$openingStockProductResult->total_opening_ml:'0';

			$start_sell_result 	= DailyStockTransferHistory::where('branch_id',$branch_id)->where('category_id',$category_id)->where('strength',50)->orderBy('id', 'asc')->first();

			$sell_start_date 	= isset($start_sell_result->created_at)?date('Y-m-d',strtotime($start_sell_result->created_at)):'';

			$start_sell_result 		= DailyProductPurchaseHistory::where('branch_id',$branch_id)->where('category_id',$category_id)->where('strength',50)->orderBy('id', 'asc')->first();
			$purchase_start_date	= isset($start_sell_result->created_at)?date('Y-m-d',strtotime($start_sell_result->created_at)):'';
			$sell_result = DailyStockTransferHistory::selectRaw('sum(total_ml) as total_sell_ml')->whereBetween('created_at', [$sell_start_date." 00:00:00", $end_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->where('strength',50)->first();
			//echo '<pre>';print_r($sell_result);exit;
			$total_sell_stock		= isset($sell_result->total_sell_ml)?$sell_result->total_sell_ml:0;

			$sell_result = DailyStockTransferHistory::selectRaw('sum(total_ml) as total_sell_ml')->whereBetween('created_at', [$start_date." 00:00:00", $end_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->where('strength',50)->first();
			$month_wise_total_sell_stock	= isset($sell_result->total_sell_ml)?$sell_result->total_sell_ml:0;


			$purchase_history_result 	= DailyProductPurchaseHistory::selectRaw('sum(total_ml) as total_purchase_ml')->whereBetween('created_at', [$purchase_start_date." 00:00:00", $end_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->where('strength',50)->orderBy('id', 'DESC')->first();
			$total_purchase_stock =isset($purchase_history_result->total_purchase_ml)?$purchase_history_result->total_purchase_ml:'0';

			$month_purchase_history_result 	= DailyProductPurchaseHistory::selectRaw('sum(total_ml) as total_purchase_ml')->whereBetween('created_at', [$start_date." 00:00:00", $end_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->where('strength',50)->orderBy('id', 'DESC')->first();
			$month_wise_total_purchase_stock =isset($month_purchase_history_result->total_purchase_ml)?$month_purchase_history_result->total_purchase_ml:'0';

			$opening_balance			= 0;
			$receipt_balance			= 0;
			$total_sell					= $total_sell_stock;
			$closing_stock				= (($total_purchase_stock+$total_opening_stock)-$total_sell);
			$opening_stock				= ($closing_stock-$month_wise_total_purchase_stock)+$total_sell;
			$receipt_balance			= $month_wise_total_purchase_stock;

			if($total_sell>0){
				$closing_stock				= (($total_purchase_stock+$total_opening_stock)-$total_sell);
				$opening_stock				= ($closing_stock-$month_wise_total_purchase_stock)+$total_sell;
			}
			if($total_sell_stock>0){
				if($month_wise_total_purchase_stock==0){
					$opening_stock				= (($total_purchase_stock+$total_opening_stock)-$total_sell);
					}
				}

				$opening_stock=$closing_stock+$month_wise_total_sell_stock;

				$prevYear_closing_balance	=0;
				$cat_opening_balance += $opening_stock;
				$cat_receipt_balance += $receipt_balance;
				$cat_sales			 += $month_wise_total_sell_stock;
				$cat_closing_balance += $closing_stock;

					$sub_cat_result[]=array(
						'sub_category_id'	=> 0,
						'sub_category_name'	=> '50°UP Foreign Liquor',
						'opening_balance'	=> $opening_stock/1000,
						'receipt_balance'	=> $receipt_balance/1000,
						'total_sell'		=> $month_wise_total_sell_stock/1000,
						'closing_balance'	=> $closing_stock/1000,
						'prevYear_closing_balance'=> $prevYear_closing_balance,
					);
				}


		$os_result[]=array(
			'category_id'		=> $row->id,
			'category_name'		=> 'Other Spirits',
			'opening_balance'	=> ($cat_opening_balance/1000),
			'receipt_balance'	=> $cat_receipt_balance/1000,
			'total_sell'		=> $cat_sales/1000,
			'closing_balance'	=> $cat_closing_balance/1000,
			'prevYear_closing_balance'=> $cat_prevYear_closing_balance,
			'sub_category'		=> $sub_cat_result
		);


		$report_result=array(
			'imfl_osbi_os_result'	=> $imfl_osbi_os_result,
			'os_result'				=> $os_result,
			'cs_result'				=> $cs_result,
			'beer_result'			=> $beer_result,

		);


	///1000


		//echo '<pre>';print_r($os_result);exit;






		//echo '<pre>';print_r($cs_result);exit;




		$company_name		= Common::get_user_settings($where=['option_name'=>'company_name'],$branch_id);
		$company_address	= Common::get_user_settings($where=['option_name'=>'company_address'],$branch_id);
		$address2			= Common::get_user_settings($where=['option_name'=>'company_address2'],$branch_id);
		$company_licensee	= Common::get_user_settings($where=['option_name'=>'company_licensee'],$branch_id);


		$data['result'] 			= $report_result;
        $data['trader_id_no'] 		= '';
		$data['licensee_no'] 		= $company_licensee;
        $data['shop_address'] 		= $company_address;
		$data['shop_address2'] 		= $address2;
		$data['shop_name'] 			= $company_name;
		$data['month'] 				= date('F Y',strtotime($request->start_date));



		//echo '<pre>';print_r($data);exit;

		$pdf = PDF::loadView('admin.pdf.stock-transfer.e-report', compact('data'));
		return $pdf->stream(now().'-e-report.pdf');
	}

	public function stockTransferbrandReport(Request $request)
	{
		$branch_id		= Session::get('branch_id');
		$first_day_this_month = date('Y-m-01',strtotime($request->start_date));
		$last_day_this_month  = date('Y-m-t',strtotime($request->start_date));

		$product_id  = $request->product_id;

		$product_info = Product::select('product_name')->where('id',$product_id)->first();

		if($product_id!=''){
			$dateS	= date('Y-m-d',strtotime($first_day_this_month));
			$dateE	= date('Y-m-d',strtotime($last_day_this_month));

			$diff	= strtotime($dateE) - strtotime($dateS);
			$total_day	= round($diff / 86400);

			$product_size_result = ProductRelationshipSize::select('size_id')->where('product_id',$product_id)->distinct()->get();

			//echo '<pre>';print_r($product_size_result);exit;

			$openingStockProductsDateresult 	= OpeningStockProducts::where('branch_id',$branch_id)->orderBy('id', 'asc')->first();
			$opening_start_date					= isset($openingStockProductsDateresult->created_at)?date('Y-m-d',strtotime($openingStockProductsDateresult->created_at)):'';



			$result=[];

			for($i=0;$total_day>=$i;$i++){
				$sell_date = date('Y-m-d', strtotime("+".$i." day", strtotime($dateS)));
				$sell_date_slug = date('Ymd', strtotime("+".$i." day", strtotime($dateS)));
				$currentdayslug	= date('Ymd', strtotime("+1 day"));

				if($sell_date_slug==$currentdayslug){
					break;
				}


				$stock_result=[];
				foreach($product_size_result as $size_row){
					$size_id=$size_row->size_id;

					$datewise_sell_result = DailyStockTransferHistory::whereBetween('created_at', [$sell_date." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('size_id',$size_id)->where('product_id',$product_id)->first();


					//$openingStockProductResultTillDate = OpeningStockProducts::whereBetween('created_at', [$dateS." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('size_id',$size_id)->where('product_id',$product_id)->first();
					//$start_opening_stock_tillDate	= isset($openingStockProductResult->product_qty)?$openingStockProductResult->product_qty:'0';

					$openingStockProductResult = OpeningStockProducts::whereBetween('created_at', [$opening_start_date." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('size_id',$size_id)->where('product_id',$product_id)->first();

					//$start_opening_stock_ml	= isset($openingStockProductResult->total_ml)?$openingStockProductResult->total_ml:'0';
					$start_opening_stock	= isset($openingStockProductResult->product_qty)?$openingStockProductResult->product_qty:'0';



					$opening_stock	= isset($datewise_sell_result->opening_stock)?$datewise_sell_result->opening_stock:0;
					$total_stock	= $opening_stock;
					$total_sale		= isset($datewise_sell_result->total_qty)?$datewise_sell_result->total_qty:0;
					$closing_stock	= isset($datewise_sell_result->closing_stock)?$datewise_sell_result->closing_stock:0;

					$prev_sell_date_result 		= DailyStockTransferHistory::where('branch_id',$branch_id)->first();
					$opening_sell_start_date	= isset($prev_sell_date_result->created_at)?date('Y-m-d',strtotime($prev_sell_date_result->created_at)):'';


					$prev_datewise_sell_result = DailyStockTransferHistory::whereBetween('created_at', [$opening_sell_start_date." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('size_id',$size_id)->where('product_id',$product_id)->orderBy('id', 'DESC')->first();

					$prev_sell_stock =isset($prev_datewise_sell_result->closing_stock)?$prev_datewise_sell_result->closing_stock:'';

					$purchase_history_result 	= DailyProductPurchaseHistory::select('id', 'total_qty', 'total_ml', 'closing_stock', 'closing_stock_ml')->whereBetween('created_at', [$sell_date." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('size_id',$size_id)->where('product_id',$product_id)->orderBy('id', 'DESC')->first();

					$purchase_stock =isset($purchase_history_result->total_qty)?$purchase_history_result->total_qty:'0';





					if($purchase_stock>0){
						//echo 'ddd-'.$sell_date.'--'.$purchase_stock.'-'.$opening_stock.'-';
						if($opening_stock>0){
							$opening_stock=$opening_stock-$purchase_stock;
						}
						if($closing_stock==0){
							$opening_stock=$start_opening_stock;
							$total_stock=$purchase_stock+$start_opening_stock;
							$closing_stock=$purchase_stock+$start_opening_stock;
						}

					}

					//$prev_sell_date_result 		= DailyStockTransferHistory::where('branch_id',$branch_id)->where('category_id',$category_id)->whereNotIn('subcategory_id', [3])->first();
					//$opening_sell_start_date	= isset($prev_sell_date_result->created_at)?date('Y-m-d',strtotime($prev_sell_date_result->created_at)):'';


					$purchase_history_last_result 	= DailyProductPurchaseHistory::select('id', 'total_qty', 'total_ml', 'closing_stock', 'closing_stock_ml')->whereBetween('created_at', [$dateS." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('size_id',$size_id)->where('product_id',$product_id)->orderBy('id', 'DESC')->first();

					$last_purchase_stock =isset($purchase_history_last_result->total_qty)?$purchase_history_last_result->total_qty:'0';

					//print_r($dateS);exit;

					//echo $sell_date.'-'.$last_purchase_stock.'</br>';
					//$start_opening_stock=0;

					if($prev_sell_stock!=''){
						//echo 'ddd-'.$sell_date.'--'.$prev_sell_stock.'-'.$opening_stock.'</br>';
						if($opening_stock==0 && $closing_stock==0){
							$opening_stock	= $prev_sell_stock;
							$total_stock	= $prev_sell_stock+$purchase_stock;
							$closing_stock	= $prev_sell_stock+$purchase_stock;
						}
					}else{
						if($start_opening_stock>0){
							if($opening_stock==0 && $closing_stock==0){
								$opening_stock	= $start_opening_stock;
								if($purchase_stock>0){
									$total_stock	= $start_opening_stock+$purchase_stock;
									$closing_stock	= $start_opening_stock+$purchase_stock;
								}else{
									$opening_stock	= $start_opening_stock+$last_purchase_stock;
									$total_stock	= $start_opening_stock+$last_purchase_stock;
									$closing_stock	= $start_opening_stock+$last_purchase_stock;
								}
							}
						}else{
							if($opening_stock==0 && $closing_stock==0){
								$opening_stock	= $start_opening_stock;
								if($purchase_stock>0){
									$total_stock	= $purchase_stock;
									$closing_stock	= $purchase_stock;
								}else{
									$opening_stock	= $last_purchase_stock;
									$total_stock	= $last_purchase_stock;
									$closing_stock	= $last_purchase_stock;
								}
							}
						}
					}

					/*if($opening_stock>0){
						echo 'iii-'.$sell_date.'--';
						if($closing_stock==0){
							$closing_stock=$opening_stock;
						}
					}*/



					$inwardStockProductResult = InwardStockProducts::whereBetween('created_at', [$sell_date." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('size_id',$size_id)->where('product_id',$product_id)->orderBy('id', 'DESC')->get();


					$source_info='';
					$warehouse_info='';
					$batch_no='';
					if(count($inwardStockProductResult)>0){
						foreach($inwardStockProductResult as $inwardPRow){
							if($inwardPRow->invoice->warehouse_id!=''){
								$warehouse_result=Warehouse::where('id',$inwardPRow->invoice->warehouse_id)->first();
								$warehouse_info	.= isset($warehouse_result->company_name)?$warehouse_result->company_name:0;
							}

							$source_info .=$inwardPRow->invoice->tp_no;
							$batch_no .=$inwardPRow->batch_no;
						}
					}

					//echo '<pre>';print_r($);exit;

					$stock_result[]=array(
						'size_id'			=> $size_row->size_id,
						'size_name'			=> $size_row->size->ml,
						'opening_stock'		=> $opening_stock,
						'purchase_stock'	=> $purchase_stock,
						'total_stock'		=> $total_stock,
						'total_sale'		=> $total_sale,
						'closing_stock'		=> $closing_stock,
						'source_info'		=> $source_info,
						'warehouse_info'	=> $warehouse_info,
						'batch_no'			=> $batch_no,
					);
				}
				$result[]=array(
					'sell_date'		=> $sell_date,
					'stock_result'	=> $stock_result
				);
			}


			$company_name		= Common::get_user_settings($where=['option_name'=>'company_name'],$branch_id);
			$company_address	= Common::get_user_settings($where=['option_name'=>'company_address'],$branch_id);
			$address2			= Common::get_user_settings($where=['option_name'=>'company_address2'],$branch_id);
			$company_licensee	= Common::get_user_settings($where=['option_name'=>'company_licensee'],$branch_id);

        	$data['result'] 		= $result;
        	$data['shop_name'] 		= $company_name;
			$data['brand_name'] 	= isset($product_info->product_name)?$product_info->product_name:'';
        	$data['shop_address'] 	= $address2;
			$data['month'] 			= date('F Y',strtotime($request->start_date));

			//echo '<pre>';print_r($result);exit;

			$pdf = PDF::loadView('admin.pdf.stock-transfer.brand-register', compact('data'));
			return $pdf->stream(now().'-brand.pdf');




			//echo '<pre>';print_r($result);exit;






			//$product_result=Product::where('id',$product_id)->first();

			//echo '<pre>';print_r($dateS);exit;



		}



	}

	public function brand_report(Request $request)
	{
		$branch_id		= Session::get('branch_id');
		$first_day_this_month = date('Y-m-01',strtotime($request->start_date));
		$last_day_this_month  = date('Y-m-t',strtotime($request->start_date));

		$product_id  = $request->product_id;

		$product_info = Product::select('product_name')->where('id',$product_id)->first();

		if($product_id!=''){

			$dateS	= date('Y-m-d',strtotime($first_day_this_month));
			$dateE	= date('Y-m-d',strtotime($last_day_this_month));

			$diff	= strtotime($dateE) - strtotime($dateS);
			$total_day	= round($diff / 86400);

			$product_size_result = ProductRelationshipSize::select('size_id')->where('product_id',$product_id)->distinct()->get();

			//echo '<pre>';print_r($product_size_result);exit;

			$result=[];

			for($i=0;$total_day>=$i;$i++){
				$sell_date = date('Y-m-d', strtotime("+".$i." day", strtotime($dateS)));
				$sell_date_slug = date('Ymd', strtotime("+".$i." day", strtotime($dateS)));
				$currentdayslug	= date('Ymd', strtotime("+1 day"));

				if($sell_date_slug==$currentdayslug){
					break;
				}
				$stock_result=[];
				foreach($product_size_result as $size_row){
					$size_id=$size_row->size_id;

					$datewise_sell_result = DailyProductSellHistory::whereBetween('created_at', [$sell_date." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('size_id',$size_id)->where('product_id',$product_id)->first();

					$opening_stock	= isset($datewise_sell_result->opening_stock)?$datewise_sell_result->opening_stock:0;
					$total_stock	= $opening_stock;
					$total_sale		= isset($datewise_sell_result->total_qty)?$datewise_sell_result->total_qty:0;
					$closing_stock	= isset($datewise_sell_result->closing_stock)?$datewise_sell_result->closing_stock:0;

					$purchase_history_result 	= DailyProductPurchaseHistory::select('id', 'total_qty', 'total_ml', 'closing_stock', 'closing_stock_ml')->whereBetween('created_at', [$sell_date." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('size_id',$size_id)->where('product_id',$product_id)->orderBy('id', 'DESC')->first();

					$purchase_stock =isset($purchase_history_result->total_qty)?$purchase_history_result->total_qty:'0';
					if($purchase_stock>0){
						$opening_stock=$opening_stock-$purchase_stock;
					}

					$inwardStockProductResult = InwardStockProducts::whereBetween('created_at', [$sell_date." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('size_id',$size_id)->where('product_id',$product_id)->orderBy('id', 'DESC')->get();


					$source_info='';
					$warehouse_info='';
					$batch_no='';
					if(count($inwardStockProductResult)>0){
						foreach($inwardStockProductResult as $inwardPRow){
							if($inwardPRow->invoice->warehouse_id!=''){
								$warehouse_result=Warehouse::where('id',$inwardPRow->invoice->warehouse_id)->first();
								$warehouse_info	.= isset($warehouse_result->company_name)?$warehouse_result->company_name:0;
							}

							$source_info .=$inwardPRow->invoice->tp_no;
							$batch_no .=$inwardPRow->batch_no;
						}
					}

					//echo '<pre>';print_r($);exit;

					$stock_result[]=array(
						'size_id'			=> $size_row->size_id,
						'size_name'			=> $size_row->size->ml,
						'opening_stock'		=> $opening_stock,
						'purchase_stock'	=> $purchase_stock,
						'total_stock'		=> $total_stock,
						'total_sale'		=> $total_sale,
						'closing_stock'		=> $closing_stock,
						'source_info'		=> $source_info,
						'warehouse_info'	=> $warehouse_info,
						'batch_no'			=> $batch_no,
					);
				}
				$result[]=array(
					'sell_date'		=> $sell_date,
					'stock_result'	=> $stock_result
				);
			}


			$company_name		= Common::get_user_settings($where=['option_name'=>'company_name'],$branch_id);
			$company_address	= Common::get_user_settings($where=['option_name'=>'company_address'],$branch_id);
			$address2			= Common::get_user_settings($where=['option_name'=>'company_address2'],$branch_id);
			$company_licensee	= Common::get_user_settings($where=['option_name'=>'company_licensee'],$branch_id);

        	$data['result'] 		= $result;
        	$data['shop_name'] 		= $company_name;
			$data['brand_name'] 	= isset($product_info->product_name)?$product_info->product_name:'';
        	$data['shop_address'] 	= $address2;
			$data['month'] 			= date('F Y',strtotime($request->start_date));

			//echo '<pre>';print_r($result);exit;

			$pdf = PDF::loadView('admin.pdf.brand-register', compact('data'));
			return $pdf->stream(now().'-brand.pdf');




			//echo '<pre>';print_r($result);exit;






			//$product_result=Product::where('id',$product_id)->first();

			//echo '<pre>';print_r($dateS);exit;



		}





		//print_r($product_id);exit;




	}

	public function test_report(Request $request)
    {
		//http://127.0.0.1:8000/admin/report/invoice/test_report


		/*$total_sell_result = SellStockProducts::get();

		foreach($total_sell_result as $row){
			$size_ml=trim(str_replace('ml', '', $row->size_ml));
			$total_ml=(int)$size_ml*(int)$row->product_qty;
			SellStockProducts::where('id', $row->id)->update(['total_ml' => $total_ml]);
		}

		exit;

		$total_sell_result = InwardStockProducts::get();

		foreach($total_sell_result as $row){
			$size_ml=trim(str_replace('ml', '', $row->size_ml));
			$total_ml=(int)$size_ml*(int)$row->product_qty;
			InwardStockProducts::where('id', $row->id)->update(['total_ml' => $total_ml]);
		}

		exit;*/
		/*$start_date = date('Y-m-d',strtotime('2021-09-01'));

		$total_month=31;
		$result=[];
		for($i = 1; $i <= $total_month; $i++){
			$dateE = date('Y-m-d',strtotime('2022-10-'.$i));
			$opening	= 0;
			$purchase	= 0;
			$sale		= 0;
			$closing	= 0;

			$dateP=date('Y-m-d', strtotime('-1 day', strtotime($dateE)));
			//$dateP='2022-10-10';
			//print_r($dateP);exit;

			$prev_purchase_result = InwardStockProducts::selectRaw('sum(total_ml) as total_ml')->whereBetween('created_at', [$start_date." 00:00:00", $dateP." 23:59:59"])->get();
			$prev_purchase_balance=isset($prev_purchase_result[0]->total_ml)?$prev_purchase_result[0]->total_ml:'0';

			$prev_sell_result = SellStockProducts::selectRaw('sum(total_ml) as total_ml')->whereBetween('created_at', [$start_date." 00:00:00", $dateP." 23:59:59"])->get();
			$prev_total_sell=isset($prev_sell_result[0]->total_ml)?$prev_sell_result[0]->total_ml:'0';


			//echo '<pre>';print_r($prev_purchase_balance);exit;

			//exit;

			$current_purchase_result = InwardStockProducts::selectRaw('sum(total_ml) as total_ml')->whereBetween('created_at', [$dateE." 00:00:00", $dateE." 23:59:59"])->get();
			$purchase=isset($current_purchase_result[0]->total_ml)?$current_purchase_result[0]->total_ml:'0';


			$current_sell_result = SellStockProducts::selectRaw('sum(total_ml) as total_ml')->whereBetween('created_at', [$dateE." 00:00:00", $dateE." 23:59:59"])->get();
			$sale=isset($current_sell_result[0]->total_ml)?$current_sell_result[0]->total_ml:'0';

			//$closing=





			$opening=$prev_purchase_balance-$prev_total_sell;
			if($purchase!=0){
				$opening= +$purchase;
			}
			$closing=$opening-$sale;







			$result[]=array(
				'date'		=> $dateE,
				'opening'	=> $opening,
				'purchase'	=> $purchase,
				'sale'		=> $sale,
				'closing'	=> $closing,
				//'purchase_balance'	=> $prev_purchase_balance,
				//'total_sell'		=> $total_sell

			);
		}

		echo '<pre>';print_r($result);exit;


		exit;*/

		$result=[];
		$categories = Category::where('food_type',1)->get();

		//echo '<pre>';print_r($categories);exit;


		if(count($categories)>0){
			foreach($categories as $row){
				$category_id=$row->id;
				$sub_cat_result=[];


				$cat_opening_balance = 0;
				$cat_receipt_balance = 0;
				$cat_sales			 = 0;
				$cat_closing_balance = 0;
				$cat_prevYear_closing_balance=0;



				$catehoty_wise_subcategory=Product::select('subcategory_id')->distinct()->where('category_id',$row->id)->get();
				if(count($catehoty_wise_subcategory)>0){
					foreach($catehoty_wise_subcategory as $sub_row){

						$subcategory_id	 = $sub_row->subcategory_id;
						$opening_balance = 0;
						$receipt_balance = 0;
						$sales			 = 0;
						$closing_balance = 0;
						$prevYear_closing_balance=0;

						//$month = Carbon::create($request->start_date)->month;

						$first_day_this_month = date('Y-m-01');
						$last_day_this_month  = date('Y-m-t');

						$dateS = date('Y-m-d',strtotime($first_day_this_month));
						$dateE = date('Y-m-d',strtotime($last_day_this_month));

						$start_date = date('Y-m-d',strtotime('2021-09-01'));

						//$dateS = date('Y-m-d',strtotime('2022-10-01'));
						//$dateE = date('Y-m-d',strtotime('2022-10-01'));

						$dateP = date('Y-m-t', strtotime('last month'));
						//echo $previous_month.'|'.$dateS.'|'.$dateE;exit;


						$prevYeardateS = date('Y-m-01',strtotime('-1 year'));
						$prevYeardateE = date('Y-m-t',strtotime('-1 year'));

						//$previous_year= date("Y",strtotime("-1 year"));

						//echo '<pre>';print_r($prevYeardateE);exit;



						$prevYear_sell_result = SellStockProducts::selectRaw('sum(total_ml) as total_ml')->whereBetween('created_at', [$start_date." 00:00:00", $prevYeardateE." 23:59:59"])->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->get();
						$prevYear_total_sell=isset($prevYear_sell_result[0]->total_ml)?$prevYear_sell_result[0]->total_ml:'0';

						$prevYear_purchase_result = InwardStockProducts::selectRaw('sum(total_ml) as total_ml')->whereBetween('created_at', [$start_date." 00:00:00", $prevYeardateE." 23:59:59"])->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->get();
						$prevYear_receipt_balance=isset($prevYear_purchase_result[0]->total_ml)?$prevYear_purchase_result[0]->total_ml:'0';

						if($prevYear_total_sell=0 && $prevYear_receipt_balance!=0){
							$prevYear_closing_balance=$prevYear_receipt_balance-$prevYear_total_sell;
						}

						$prev_purchase_result = InwardStockProducts::selectRaw('sum(total_ml) as total_ml')->whereBetween('created_at', [$start_date." 00:00:00", $dateP." 23:59:59"])->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->get();
						$prev_receipt_balance=isset($prev_purchase_result[0]->total_ml)?$prev_purchase_result[0]->total_ml:'0';

						$prev_sell_result = SellStockProducts::selectRaw('sum(total_ml) as total_ml')->whereBetween('created_at', [$start_date." 00:00:00", $dateP." 23:59:59"])->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->get();
						$prev_total_sell=isset($prev_sell_result[0]->total_ml)?$prev_sell_result[0]->total_ml:'0';

						//echo '<pre>';print_r($prev_sell_result);exit;


						//DB::enableQueryLog();
						//var_dump($result, DB::getQueryLog());

						$sell_result = SellStockProducts::selectRaw('sum(total_ml) as total_ml')->whereBetween('created_at', [$start_date." 00:00:00", $dateE." 23:59:59"])->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->get();
						$total_sell=isset($sell_result[0]->total_ml)?$sell_result[0]->total_ml:'0';

						$purchase_result = InwardStockProducts::selectRaw('sum(total_ml) as total_ml')->whereBetween('created_at', [$start_date." 00:00:00", $dateE." 23:59:59"])->where('category_id',$category_id)->where('subcategory_id',$subcategory_id)->get();
						$receipt_balance=isset($purchase_result[0]->total_ml)?$purchase_result[0]->total_ml:'0';


						if($prev_receipt_balance==0){
							$opening_balance=$receipt_balance;

						}
						if($opening_balance!=0 && $total_sell!=0){
							$closing_balance=$opening_balance-$total_sell;
						}

						if($opening_balance!=0){
							$opening_balance=$opening_balance/1000;
						}
						if($receipt_balance!=0){
							$receipt_balance=$receipt_balance/1000;
						}
						if($total_sell!=0){
							$total_sell=$total_sell/1000;
						}
						if($closing_balance!=0){
							$closing_balance=$closing_balance/1000;
						}
						if($prevYear_closing_balance!=0){
							$prevYear_closing_balance=$prevYear_closing_balance/1000;
						}
						$cat_opening_balance += $opening_balance;
						$cat_receipt_balance += $receipt_balance;
						$cat_sales			 += $total_sell;
						$cat_closing_balance += $closing_balance;
						$cat_prevYear_closing_balance +=$prevYear_closing_balance;



						$sub_cat_result[]=array(
							'category_id'		=> $sub_row->subcategory_id,
							'category_name'		=> $sub_row->subcategory->name,
							'opening_balance'	=> $opening_balance,
							'receipt_balance'	=> $receipt_balance,
							'total_sell'		=> $total_sell,
							'closing_balance'	=> $closing_balance,
							'prevYear_closing_balance'=> $prevYear_closing_balance
						);
					}
				}


				//echo '<pre>';print_r($sub_cat_result);exit;

				 //Subcategory::all();

				 $category_img='';
				 if($row->id==1){
					 $category_img=url('images/imfl.png');
				 }else if($row->id==2){
					 $category_img=url('images/os.png');
				 }else if($row->id==3){
					 $category_img=url('images/cs.png');
				 }else if($row->id==4){
					 $category_img=url('images/osbl.png');
				 }


				$result[]=array(
					'category_id'	=> $row->id,
					'category_name'	=> $row->name,
					'opening_balance'=>$cat_opening_balance,
					'receipt_balance'	=> $cat_receipt_balance,
					'total_sell'		=> $cat_sales,
					'closing_balance'	=> $cat_closing_balance,
					'prevYear_closing_balance'=> $cat_prevYear_closing_balance,
					'category_img'	=> $category_img,
					'sub_category'	=> $sub_cat_result
				);
			}
		}

		//echo '<pre>';print_r($result);exit;

		/*require_once '../mpdf/vendor/autoload.php';
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML((string)view('admin.pdf.e-report', $result));
		$mpdf->Output();*/

		$pdf = PDF::loadView('admin.pdf.e-report', compact('result'));
		return $pdf->stream(now().'-e-report.pdf');

		//return $pdf->download('invoice.pdf');


		echo '<pre>';print_r($pdf);exit;







	}

	public function invoice_report(Request $request)
    {
        try {
            if ($request->ajax()) {
                $purchaseOrder = PurchaseOrder::where('status', '0')->orderBy('id', 'desc')->get();
                return DataTables::of($purchaseOrder)
                    ->addColumn('sup_code', function ($row) {
                        return $row->supplier_dtl->sup_code;
                    })
                    ->addColumn('order_no', function ($row) {
                        return '<a class="td-anchor" href="' . route('admin.stock.purchase-order.edit', [base64_encode($row->id)]) . '">' . $row->order_no . '</a>';
                    })
                    ->addColumn('sup_name', function ($row) {
                        return $row->supplier_dtl->sup_name;
                    })
                    ->addColumn('order_date', function ($row) {
                        return date('d-m-Y', strtotime($row->order_date));
                    })
                    ->addColumn('delivery_date', function ($row) {
                        return date('d-m-Y', strtotime($row->delivery_date));
                    })
                    ->addColumn('delivery_name', function ($row) {
                        return $row->delivery_name;
                    })
                    ->addColumn('city', function ($row) {
                        return $row->city;
                    })
                    ->addColumn('state', function ($row) {
                        return $row->state;
                    })
                    ->addColumn('post_code', function ($row) {
                        return $row->post_code;
                    })
                    ->addColumn('status', function ($row) {
                        $status = '';
                        if ($row->status == 3) {
                            $status = '<span class="badge badge-info">Receipt</span>';
                        }
                        if ($row->status == 2) {
                            $status = '<span class="badge badge-prinary">Placed</span>';
                        }
                        if ($row->status == 1) {
                            $status = '<span class="badge badge-success">completed</span>';
                        }
                        if ($row->status == 0) {
                            $status = '<span class="badge badge-warning">Pending</span>';
                        }
                        return $status;
                    })
                    ->addColumn('action', function ($row) {
                        $dropdown = '<a class="dropdown-item" href="' . route('admin.stock.purchase-order.edit', [base64_encode($row->id)]) . '">Edit</a>
                        <a class="dropdown-item delete-item" href="#" id="delete_product" data-url="' . route('admin.stock.purchase-order.delete', [base64_encode($row->id)]) . '">Delete</a>
                        <a class="dropdown-item" href="' . route('admin.stock.purchase-order.view', [base64_encode($row->id)]) . '" >View</a>
                        <a class="dropdown-item" href="' . route('admin.stock.purchase-order.complete', [base64_encode($row->id)]) . '" >Complete Order</a>
                        <a class="dropdown-item" href="' . route('admin.stock.purchase-order.printInvoice', [base64_encode($row->id)]) . '" >Download Invoice</a>
                        ';

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
                    ->rawColumns(['action', 'status', 'order_no'])
                    ->make(true);
            }
            $data = [];
            $data['heading'] = 'Purchase Order List';
            $data['breadcrumb'] = ['Stock', 'Purchase Order', 'List'];

            return view('admin.report.sales_invoice_report', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }


    public function list(Request $request)
    {
        try {
            if ($request->ajax()) {
                $purchaseOrder = PurchaseOrder::where('status', '0')->orderBy('id', 'desc')->get();
                return DataTables::of($purchaseOrder)
                    ->addColumn('sup_code', function ($row) {
                        return $row->supplier_dtl->sup_code;
                    })
                    ->addColumn('order_no', function ($row) {
                        return '<a class="td-anchor" href="' . route('admin.stock.purchase-order.edit', [base64_encode($row->id)]) . '">' . $row->order_no . '</a>';
                    })
                    ->addColumn('sup_name', function ($row) {
                        return $row->supplier_dtl->sup_name;
                    })
                    ->addColumn('order_date', function ($row) {
                        return date('d-m-Y', strtotime($row->order_date));
                    })
                    ->addColumn('delivery_date', function ($row) {
                        return date('d-m-Y', strtotime($row->delivery_date));
                    })
                    ->addColumn('delivery_name', function ($row) {
                        return $row->delivery_name;
                    })
                    ->addColumn('city', function ($row) {
                        return $row->city;
                    })
                    ->addColumn('state', function ($row) {
                        return $row->state;
                    })
                    ->addColumn('post_code', function ($row) {
                        return $row->post_code;
                    })
                    ->addColumn('status', function ($row) {
                        $status = '';
                        if ($row->status == 3) {
                            $status = '<span class="badge badge-info">Receipt</span>';
                        }
                        if ($row->status == 2) {
                            $status = '<span class="badge badge-prinary">Placed</span>';
                        }
                        if ($row->status == 1) {
                            $status = '<span class="badge badge-success">completed</span>';
                        }
                        if ($row->status == 0) {
                            $status = '<span class="badge badge-warning">Pending</span>';
                        }
                        return $status;
                    })
                    ->addColumn('action', function ($row) {
                        $dropdown = '<a class="dropdown-item" href="' . route('admin.stock.purchase-order.edit', [base64_encode($row->id)]) . '">Edit</a>
                        <a class="dropdown-item delete-item" href="#" id="delete_product" data-url="' . route('admin.stock.purchase-order.delete', [base64_encode($row->id)]) . '">Delete</a>
                        <a class="dropdown-item" href="' . route('admin.stock.purchase-order.view', [base64_encode($row->id)]) . '" >View</a>
                        <a class="dropdown-item" href="' . route('admin.stock.purchase-order.complete', [base64_encode($row->id)]) . '" >Complete Order</a>
                        <a class="dropdown-item" href="' . route('admin.stock.purchase-order.printInvoice', [base64_encode($row->id)]) . '" >Download Invoice</a>
                        ';

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
                    ->rawColumns(['action', 'status', 'order_no'])
                    ->make(true);
            }
            $data = [];
            $data['heading'] = 'Purchase Order List';
            $data['breadcrumb'] = ['Stock', 'Purchase Order', 'List'];
            return view('admin.purchase_order.list', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }

    public function complete_order_list(Request $request)
    {
        try {
            if ($request->ajax()) {
                $purchaseOrder = PurchaseOrder::where('status', '1')->orderBy('id', 'desc')->get();
                return DataTables::of($purchaseOrder)
                    ->addColumn('sup_code', function ($row) {
                        return $row->supplier_dtl->sup_code;
                    })
                    ->addColumn('order_no', function ($row) {
                        return $row->order_no;
                    })
                    ->addColumn('sup_name', function ($row) {
                        return $row->supplier_dtl->sup_name;
                    })
                    ->addColumn('order_date', function ($row) {
                        return date('d-m-Y', strtotime($row->order_date));
                    })
                    ->addColumn('delivery_date', function ($row) {
                        return date('d-m-Y', strtotime($row->delivery_date));
                    })
                    ->addColumn('delivery_name', function ($row) {
                        return $row->delivery_name;
                    })
                    ->addColumn('city', function ($row) {
                        return $row->city;
                    })
                    ->addColumn('state', function ($row) {
                        return $row->state;
                    })
                    ->addColumn('post_code', function ($row) {
                        return $row->post_code;
                    })
                    ->addColumn('status', function ($row) {
                        $status = '';
                        if ($row->status == 3) {
                            $status = '<span class="badge badge-info">Receipt</span>';
                        }
                        if ($row->status == 2) {
                            $status = '<span class="badge badge-prinary">Placed</span>';
                        }
                        if ($row->status == 1) {
                            $status = '<span class="badge badge-success">completed</span>';
                        }
                        if ($row->status == 0) {
                            $status = '<span class="badge badge-warning">Pending</span>';
                        }
                        return $status;
                    })
                    ->addColumn('action', function ($row) {
                        // $dropdown = '<a class="dropdown-item" href="' . route('admin.stock.purchase-order.edit', [base64_encode($row->id)]) . '">Edit</a>
                        // <a class="dropdown-item delete-item" href="#" id="delete_product" data-url="' . route('admin.stock.purchase-order.delete', [base64_encode($row->id)]) . '">Delete</a>';
                        $dropdown = '<a class="dropdown-item" href="' . route('admin.stock.purchase-order.view', [base64_encode($row->id)]) . '" >View</a>
                        <a class="dropdown-item" href="' . route('admin.stock.purchase-order.printInvoice', [base64_encode($row->id)]) . '" >Download Invoice</a>
                        ';

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
                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            $data = [];
            $data['heading'] = 'Complete Purchase Order List';
            $data['breadcrumb'] = ['Stock', 'Purchase Order', 'Complete Order'];
            return view('admin.purchase_order.complete_order_list', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }
    public function edit(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $id = base64_decode($id);
            // dd($id);
            if ($request->isMethod('post')) {
                // dd($request->all());
                $validator = Validator::make($request->all(), [
                    'supplier_code' => 'required',
                    // 'supplier_invoice_date' => 'required|date',
                    'delivery_name' => 'required',
                    'address_line_1' => 'required',
                    'address_line_2' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    'post_code' => 'required',
                    'order_date' => 'required|date',
                    'delivery_date' => 'required|date',
                    // 'discount' => 'required',
                    'product_price' => 'required|array|min:1',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                $purchase = PurchaseOrder::find($id)->update([
                    'supplier_id' => $request->supplier_code,
                    'supplier_ref' => $request->supplier_ref,
                    // 'supplier_invoice_date' => $request->supplier_invoice_date,
                    'delivery_name' => $request->delivery_name,
                    'address_one' => $request->address_line_1,
                    'address_two' => $request->address_line_2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'post_code' => $request->post_code,
                    'order_date' => $request->order_date,
                    'delivery_date' => $request->delivery_date,
                    'delivery_charge' => $request->delivery_charge ?? 0,
                    // 'discount' => $request->discount,
                ]);
                PurchaseProduct::where('purchase_order_id', $id)->delete();
                $purchase_product = [];
                $sub_total = $request->delivery_charge ?? 0;
                foreach ($request->product_id as $key => $value) {
                    $purchase_product[$key] = [
                        'purchase_order_id' => $id,
                        'product_id' => $value,
                        'price' => $request->product_price[$key],
                        'qty' => $request->product_qty[$key],
                        'discount' => $request->product_discount[$key],
                        'tax_rate' => $request->product_purchase_tax_rate[$key],
                        'total' => $request->product_sub_total[$key],
                        'comments' => $request->product_comment[$key],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    $sub_total += $request->product_sub_total[$key];
                }

                // dd($supplier_product);
                PurchaseProduct::insert($purchase_product);

                PurchaseOrder::find($id)->update([
                    'sub_total' => $sub_total,
                ]);
                DB::commit();
                return redirect()->back()->with('success', 'Purchase order updated successfully');
            }
            $data = [];

            $data['breadcrumb'] = ['Stock', 'Purchase Order', 'Edit'];
            $data['supplier'] = Supplier::all();
            $data['product'] = Product::all();
            $data['purchase_order'] = PurchaseOrder::with('supplier_dtl', 'purchase_product', 'purchase_product.product_dtl')->find($id);
            $data['heading'] = $data['purchase_order']->order_no;
            return view('admin.purchase_order.edit', compact('data'));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }
    public function delete(Request $request)
    {
    }
    public function view(Request $request, $id)
    {
        try {
            $id = base64_decode($id);
            $data = [];
            // $data['heading'] = 'View Purchase Order';
            $data['breadcrumb'] = ['Stock', 'Purchase Order', 'View'];
            $data['purchase_order'] = PurchaseOrder::with('supplier_dtl', 'purchase_product', 'purchase_product.product_dtl')->find($id);
            $data['heading'] = $data['purchase_order']->order_no;
            return view('admin.purchase_order.view', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }


    public function get_product_supplier_dtl(Request $request)
    {
        $supplier_id = $request->supplier_id;
        $product_id = $request->product_id;

        $product_dtl = Product::find($product_id);
        $prod_supplier = ProductSupplier::where(['supplier_id' => $supplier_id, 'product_id' => $product_id])->first();
        // dd($prod_supplier, $product_dtl, $supplier_id, $product_id);
        $response = [];

        $response['product_code'] = $product_dtl->product_code;
        $response['product_desc'] = $product_dtl->product_desc;
        $response['availibility'] = $product_dtl->available_qty;
        $response['purchase_tax_rate'] = $product_dtl->purchase_tax_rate;

        if (!empty($prod_supplier)) {
            $response['qty'] = $prod_supplier->supp_min_order_qty;
            $response['price'] = $prod_supplier->supp_purchase_price;
            $response['supplier_product_code'] = $prod_supplier->supp_product_code;
        } else {
            $response['qty'] = $prod_supplier->min_order_qty;
            $response['price'] = $prod_supplier->default_purchase_price;
            $response['supplier_product_code'] = $prod_supplier->product_code;
        }
        return response(['success' => true, 'data' => $response]);
    }

    public function print_invoice(Request $request, $id)
    {
        try {
            $id = base64_decode($id);
            $data = [];
            $data['purchase_order'] = PurchaseOrder::with('supplier_dtl', 'purchase_product', 'purchase_product.product_dtl')->find($id);

            $pdf = PDF::loadView('admin.purchase_order.invoice', compact('data'));

            return $pdf->download('invoice.pdf');
            // return view('admin.purchase_order.invoice', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }

    public function complete_order(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $id = base64_decode($id);
            PurchaseOrder::find($id)->update([
                'status' => 1
            ]);
            $purchase_product = PurchaseProduct::where('purchase_order_id', $id)->get();
            foreach ($purchase_product as $key => $value) {
                $product = Product::find($value->product_id);
                $stock = $product->available_qty + $value->qty;
                $product->available_qty = $stock;
                $product->save();
            }
            DB::commit();
            return redirect()->route('admin.stock.purchase-order.CompleteOrderList')->with('success', 'Order completed successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }
    public function send_email(Request $request)
    {
        DB::beginTransaction();
        try {
            $to_email = explode(',', $request->to_email)[0];
            $cc_email = explode(',', $request->cc_email);
            $subject = $request->subject;
            $message = $request->message;
            $send_copy = $request->send_copy;

            $data = [];
            $data['subject'] = $subject;
            $data['message'] = $message;
            $data['send_copy'] = $send_copy;

            $data['purchase_order'] = PurchaseOrder::with('supplier_dtl', 'purchase_product', 'purchase_product.product_dtl')->find(1);

            $pdf = PDF::loadView('admin.purchase_order.invoice', compact('data'));
            Storage::put('uploads/purchase_order/invoice-' . date('Y-m-d H:i:s') . '.pdf', $pdf->output());
            // return $pdf->save(public_path() . '/myfile.pdf');

            Mail::to($to_email)->cc($cc_email)->send(new PurchaseOrderSupplier($data));

            DB::commit();
            return response(['success' => true]);
            // return redirect()->route('admin.stock.purchase-order.CompleteOrderList')->with('success', 'Order completed successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }

     //purchase report added by palash


	 public function counterPurchase(Request $request){
		 $branch_id	= Session::get('branch_id');
        try {
            if ($request->ajax()) {
				$purchase = PurchaseInwardStock::where('invoice_stock','counter')->where('supplier_id',$branch_id)->orderBy('id', 'desc')->get();
                return DataTables::of($purchase)
                    ->addColumn('invoice_no', function ($row) {
                       return '<a class="td-anchor" href="'.route('admin.report.stock_product.list', [base64_encode($row->id)]) .'" target="_blank">' . $row->invoice_no . '</a>';

                    })
                    ->addColumn('inward_date', function ($row) {
                        return date('d-m-Y', strtotime($row->inward_date));
                    })
                    ->addColumn('tp_no', function ($row) {
                        return $row->tp_no;
                    })
                    ->addColumn('purchase_date', function ($row) {
                        return date('d-m-Y', strtotime($row->purchase_date));
                    })
                    ->addColumn('supplier_id', function ($row) {
                        return $row->supplier->company_name;
                    })
                    ->addColumn('total_qty', function ($row) {
                        return $row->total_qty;
                    })
                    ->addColumn('sub_total', function ($row) {
                        return number_format($row->sub_total,2);
                    })

                    ->addColumn('action', function ($row) {
                        /*$dropdown = '<a class="dropdown-item" href="'.route('admin.purchase.inward_stock.update', [base64_encode($row->id)]) .'">Edit</a>
                        <a class="dropdown-item delete-item" id="delete_inward_stock" href="#" data-url="' . route('admin.purchase.inward-stock.delete', [base64_encode($row->id)]) . '">Delete</a>';*/

						$dropdown = '<a class="dropdown-item" href="#" href="'.route('admin.report.stock_product.list', [base64_encode($row->id)]) .'" target="_blank">View</a>
                        <a class="dropdown-item delete-item" id="delete_inward_stock" href="#" data-url="' . route('admin.purchase.inward-stock.delete', [base64_encode($row->id)]) . '">Delete</a>';

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

                    ->rawColumns(['invoice_no','action'])
                    ->make(true);
            }
            $data = [];
            $data['heading'] = 'Purchase Order List';
            $data['breadcrumb'] = ['Stock', 'Purchase Order', 'List'];

            return view('admin.report.purchase', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }







    public function invoicePdf(){

        $data=[];
        $data['shop_details'] = [
            'name' => 'INDULGENCE',
            'address1'=>'Padarpan Abasan, Block A , Beside Madhyamgram Municipality',
            'address2' => 'Madhyamgram Kolkata - 700129',
            'phone'=>'(+91)93301 41544',
        ];
        $data['customer_details'] = [
            'name'=> 'Farhana Sultana',
            'mobile'=> '7003923969',
            'address'=> 'India',
        ];
        $data['invoice_details'] = [
            'invoice_no'=> 'INV407/22-23',
            'invoice_date'=> '16-08-2022',
            'gstin'=> '19CTIPS9692B1ZZ',
            'place'=> 'West Bengal',
            'branch'=> 'Baguihati',
            'cashier_name'=> 'Mrs Roy Suchandra',
        ];
        $data['items'] = [
            [
                'particulars'=> 'MB LIQ MATTE 12 AS',
                'qty'=> '1',
                'mrp'=> '349',
                'offer_price'=> '349',
                'disc_price'=> '34.9',
                'final_price'=> '314.1',
            ],
            [
                'particulars'=> 'MB LIQ MATTE 15 AS',
                'qty'=> '1',
                'mrp'=> '349',
                'offer_price'=> '349',
                'disc_price'=> '34.9',
                'final_price'=> '314.1',
            ]
        ];
        $data['total'] =[
            'total_qty'=> '2',
            'total_disc'=> '849',
            'total_price'=> '9880',
        ];

        $data['gst'] =[
            'gst_val' =>'18',
            'taxable_amt'=> '8373.21',
            'cgst_rate'=> '9',
            'cgst_amt'=> '753.59',
            'sgst_rate'=> '9',
            'sgst_amt'=> '753.59',
            'total_amt'=> '9880.39',
        ];
        $data['total_amt_in_word'] = ucwords(Media::getIndianCurrency($data['total']['total_price']));
        $data['payment_method'] = 'Cash';
        $pdf = PDF::loadView('admin.pdf.invoice', $data);
        return $pdf->download('invoice.pdf');
    }

    public function salesProduct(Request $request){
        /* if(isset($request->id)){
            echo base64_decode($request->id);die;
        } */
        try {
            if ($request->ajax()) {
                //echo base64_decode($inward_stock_id);die;
                $products = SellStockProducts::where('product_id','!=','');

                if(!empty($request->get('start_date')) && !empty($request->get('end_date'))){
                    if($request->get('start_date') == $request->get('end_date')){
                        $products->whereDate('created_at', $request->get('start_date'));
                    }else{
                        $products->whereBetween('created_at', [$request->get('start_date'), $request->get('end_date')]);
                    }
                }
                if(isset($request->id) && !empty($request->id)){
                    $sell_inward_stock_id = base64_decode($request->id);
                    $products->where('inward_stock_id',$sell_inward_stock_id);
                }
                //echo  $products;die;
                $products->orderBy('id', 'desc')->get();

                //echo "<pre>";print_r($request->all());die;
                return DataTables::of($products)

                /* ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('date_search'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains(date('d-m-Y', strtotime($row['created_at'])), date('d-m-Y', strtotime($request->get('date_search')))) ? true : false;
                        });
                    }

                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['created_at']), Str::lower($request->get('search')))){
                                return true;
                            }else if (Str::contains(Str::lower($row['name']), Str::lower($request->get('search')))) {
                                return true;
                            }

                            return false;
                        });
                    }

                }) */

                    ->addColumn('product_name', function ($row) {
                        return $row->product_name;
                    })
                    ->addColumn('barcode', function ($row) {
                        return $row->barcode;
                    })
                    ->addColumn('created_at', function ($row) {
                        return date('d-m-Y', strtotime($row->created_at));
                    })

                    ->addColumn('brand_name', function ($row) {
                        return $row->brand_name;
                    })

                    ->addColumn('product_qty', function ($row) {
                        return $row->product_qty;
                    })
                    ->addColumn('product_mrp', function ($row) {
                        return $row->product_mrp;
                    })
                    ->addColumn('total_cost', function ($row) {
                        return number_format($row->total_cost,2) ;
                    })
                    ->rawColumns([])
                    ->make(true);
            }

            $data = [];
            $data['heading'] = 'Sales Product List';
            $data['breadcrumb'] = ['Sales', 'Product', 'List'];
            $data['item_id'] =$request->get('id') ? $request->get('id') : '';
            return view('admin.report.sales_product_list', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }

    public function salesProductDownload(Request $request){
        //dd($request->all());
        if($request->has('start_date') && $request->start_date != '' && $request->has('end_date') && $request->end_date != ''){
            if($request->start_date == $request->end_date){
                $sales_products = SellStockProducts::whereDate('created_at', $request->start_date)->get();
            }else{
                $sales_products = SellStockProducts::whereBetween('created_at', [$request->start_date, $request->end_date])->get();
            }

        }else{
            $sales_products = SellStockProducts::all();
        }
        $content = "";
        foreach ($sales_products as $product) {
        $content .= '01/2007/0003|'.date('d-m-Y h:i', strtotime($product->created_at)).'|'.$product->barcode .'|'.substr($product->size->name,0,-4).'|'.$product->product_mrp.'|'.$product->product_qty;
        $content .= "\n";
        }

        // file name that will be used in the download
        $fdate = ($request->has('date') && $request->date != '') ? $request->date : Carbon::now();
        $fileName = $fdate."-Filter-Sell.txt";

        // use headers in order to generate the download
        $headers = [
        'Content-type' => 'text/plain',
        'Content-Disposition' => sprintf('attachment; filename="%s"', $fileName),
        //'Content-Length' => sizeof($content)
        ];

        // make a response, with the content, a 200 response code and the headers
        return Response::make($content, 200, $headers);
    }

    public function salesItems(Request $request){
        try {

            $branch_id=Auth::user()->id;
            $user_role=Auth::user()->role;
            if($user_role==1){
                $sales = SellInwardStock::where('invoice_no','!=','');
            }else{
                $sales = SellInwardStock::where('invoice_no','!=','')->where('branch_id', $branch_id);
            }


                if(!empty($request->get('start_date')) && !empty($request->get('end_date'))){
                    if($request->get('start_date') == $request->get('end_date')){
                        $sales->whereDate('sell_date', $request->get('start_date'));
                    }else{
                        $sales->whereBetween('sell_date', [$request->get('start_date'), $request->get('end_date')]);
                    }
                }
                if(!empty($request->get('invoice'))){

                    $sales->where('invoice_no', $request->get('invoice'));

                }
                if(!empty($request->get('store_id'))){

                    $sales->where('branch_id', $request->get('store_id'));

                }

                if(!empty($request->get('customer_id'))){

                    $sales->where('customer_id', $request->get('customer_id'));

                }

            $sales->orderBy('id', 'desc')->get();

                $profitpersent = 0;
                if($sales->count()!=0){
                    if($sales->sum('pay_amount')!=''){
                        $totalsell = $sales->sum('pay_amount');
                        $totalnet_price = $sales->sum('net_price');
                        $profitpersent = ((($totalsell - $totalnet_price)/$totalnet_price)*100);
                    }
                }else{
                    $totalsell = 0;
                    $totalnet_price = 0;
                    $profitpersent = 0;
                }


            $data = [];
            $data['total_ammount'] = $sales->sum('pay_amount');
            $data['profitpersent'] = $profitpersent;
            $data['total_qty'] = $sales->sum('total_qty');
            $data['total_invoice'] = $sales->count();
            $data['sales'] = $sales->paginate(10);
            $data['storeUsers'] = User::select('id','name','email','phone')->where('role',2)->get();
            $data['heading'] = 'Invoice Wise Sales List';
            $data['breadcrumb'] = ['Invoice Wise Sales', '', 'List'];

            $data['customer_list'] = Customer::orderBy('id', 'desc')->get();

            return view('admin.report.sales_item_list', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }

    public function itemWiseSaleReportPdf(Request $request){
		$satrt_date = $request->start_date.' 00:00:00';
		$end_date 	= $request->end_date.' 23:59:00';
        $items = [];

        $group_cat_products = SellStockProducts::with('category')->groupBy(['category_id'])->whereBetween('created_at', [$satrt_date, $end_date])->get();



        foreach($group_cat_products as $group_cat_product){
            //$items['category'][] =  $group_cat_product->category->name;
            $group_sub_cat_products = SellStockProducts::
                        where('category_id',$group_cat_product->category->id)
                        ->whereBetween('created_at', [$satrt_date, $end_date])
                        ->groupBy(['subcategory_id'])
                        ->get();
            foreach($group_sub_cat_products as $group_sub_cat_product){
                //$items['category'][$group_cat_product->category->name][] = $group_sub_cat_product->subCategory->name;

                $sales_products = SellStockProducts::where('subcategory_id',$group_sub_cat_product->subCategory->id)
                    ->where('category_id',$group_sub_cat_product->category_id)
                    ->whereBetween('created_at', [$satrt_date, $end_date])
                    ->groupBy(['product_id','size_id'])
                    ->selectRaw('product_name,product_mrp,sum(product_qty) as total_bottles,sum(total_cost) as total_ammount,sum(size_ml) as total_ml')
                    ->get();
                $items[$group_cat_product->category->name][$group_sub_cat_product->subCategory->name]    = $sales_products;
            }
        }
        $payment_type_ammount = SellInwardStock::whereBetween('payment_date', [$satrt_date, $end_date])
                        ->groupBy(['payment_method'])
                        ->selectRaw('payment_method,sum(pay_amount) as total_payment')
                        ->get();

        $data = [];
        $data['items'] = $items;
        $data['shop_name'] = 'BAZIMAT';
        $data['shop_address'] = 'West Chowbaga Kolkata - 700105 West Bengal';
        $data['from_date'] = Carbon::create($request->start_date)->format('d-M-Y');
        $data['to_date'] = Carbon::create($request->end_date)->format('d-M-Y');
        $data['payment_type_ammount'] = $payment_type_ammount;

        $pdf = PDF::loadView('admin.pdf.item-wise-sales-report', $data);
         //echo "<pre>";print_r($items);die;
		return $pdf->stream(now().'-invoice.pdf');

    }

    public function productWiseSaleReport(Request $request){
        try {
            //echo $request->get('start_date');die;
            //dd($request->all());
            $data = [];

            $branch_id=Auth::user()->id;
            $user_role=Auth::user()->role;
            if($user_role==1){
                $queryProduct = SellStockProducts::query();
            }else{
                $queryProduct = SellStockProducts::where('branch_id', $branch_id);
            }

            //Add sorting
            $queryProduct->orderBy('id', 'desc');
            if(!empty($request->get('start_date')) && !empty($request->get('end_date'))){
                if($request->get('start_date') == $request->get('end_date')){
                    $queryProduct->whereDate('created_at', $request->get('start_date'));
                }else{
                    $queryProduct->whereBetween('created_at', [$request->get('start_date'), $request->get('end_date')]);
                }
            }
            if(!is_null($request['customer_id'])) {
                $queryProduct->whereHas('sellInwardStock',function($q) use ($request){
                    return $q->where('customer_id',$request['customer_id']);
                });
            }
            if(!is_null($request['invoice_id'])) {
                $queryProduct->whereHas('sellInwardStock',function($q) use ($request){
                    return $q->where('id',$request['invoice_id']);
                });
            }
            if(!is_null($request['product_id'])) {
                $queryProduct->where('product_id',$request['product_id']);
            }
            if(!is_null($request['brand'])) {
                $queryProduct->whereHas('product',function($q) use ($request){
                    return $q->where('brand_id',$request['brand']);
                });
            }
            if(!is_null($request['dosage'])) {
                $queryProduct->whereHas('product',function($q) use ($request){
                    return $q->where('dosage_id',$request['dosage_id']);
                });
            }
            if(!is_null($request['company'])) {
				$queryProduct->whereHas('product',function($q) use ($request){
                    return $q->where('company_id',$request['company']);
                });
            }

            if(!is_null($request['store_id'])) {
                $queryProduct->where('branch_id', $request['store_id']);
            }

            // $profitpersent = 0;
            // if($queryProduct->sum('pay_amount')!=''){
            //     $totalsell = $queryProduct->sum('pay_amount');
            //     $totalnet_price = $queryProduct->sum('net_price');
            //     $profitpersent = ((($totalsell - $totalnet_price)/$totalnet_price)*100);
            // }

            $total_qty =  $queryProduct->sum('product_qty');
            $total_cost =  $queryProduct->sum('total_cost');
            $all_product = $queryProduct->get();
            $products = $queryProduct->paginate(10);
            $data['heading']    = 'Sales List';
            $data['breadcrumb'] = ['Sales', '', 'List'];
            $data['products']   = $products;
            $data['total_invoice']  = count(array_unique(array_column($all_product->toArray(), 'inward_stock_id')));
            $data['total_qty']  = $total_qty;
            $data['total_cost'] = $total_cost;
            $data['brands'] = Brand::all();
			$data['dosages'] = Dosage::all();
			$data['companies']      = Company::all();
            $data['storeUsers'] = User::select('id','name','email','phone')->where('role',2)->get();
            //$data['request'] = $request;
            return view('admin.report.product_wise_sales_report', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }





	public function stockTransferReport(Request $request){
        try {
            //echo $request->get('start_date');die;
            //dd($request);
            $data = [];
			$branch_id		= Session::get('branch_id');
            $branch_id		= Session::get('branch_id');
			$queryProduct = StockTransferHistory::select('stock_transfer_history.*','branch_stock_products.product_barcode')->leftJoin('branch_stock_products', function($join) {
						$join->on('stock_transfer_history.stock_id', '=', 'branch_stock_products.id');
					})->leftJoin('products', function($join) {
						$join->on('branch_stock_products.product_id', '=', 'products.id');
					})->where('stock_transfer_history.branch_id', $branch_id)->where('branch_stock_products.stock_type', 'counter');
			if(!is_null($request['product_id'])) {
                $queryProduct->where('branch_stock_products.product_id',$request['product_id']);
            }
			if(!is_null($request['category'])) {
                $queryProduct->where('products.category_id',$request['category']);
            }
            if(!is_null($request['sub_category'])) {
                $queryProduct->where('products.subcategory_id',$request['sub_category']);
            }
            if(!is_null($request['size'])) {
                $queryProduct->where('branch_stock_products.size_id',$request['size']);
            }
			if(!empty($request->get('start_date')) && !empty($request->get('end_date'))){
                if($request->get('start_date') == $request->get('end_date')){
                    $queryProduct->whereDate('stock_transfer_history.created_at', $request->get('start_date'));
                }else{
                    $queryProduct->whereBetween('stock_transfer_history.created_at', [$request->get('start_date'), $request->get('end_date')]);
                }
            }
            $all_product 	= $queryProduct->orderBy('id', 'DESC')->get();
            $products 		= $queryProduct->paginate(10);


			//echo '<pre>';print_r($products);exit;

			$total_qty	= 0;
			$total_cost	= 0;


            $data['heading']    = 'Stock Transfer List';
            $data['breadcrumb'] = ['Stock Transfer', '', 'List'];
            $data['products']   = $products;
            $data['total_invoice']  = count(array_unique(array_column($all_product->toArray(), 'inward_stock_id')));
            $data['total_qty']  = $total_qty;
            $data['total_cost'] = $total_cost;
            $data['categories'] = Category::where('food_type',1)->get();
            $data['sizes']      = Size::all();
            $data['sub_categories']      = Subcategory::all();
            //$data['request'] = $request;
			//echo '<pre>';print_r($data);exit;
            return view('admin.report.stockTransferReport', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }

    public function getCustomerByKeyup(Request $request){
        $search = $request->search;
        //$result = Customer::select('customer_fname','customer_last_name','id')->whereRaw("concat(customer_fname, ' ', customer_last_name) like '%" .$search. "%' ")->take(20)->get();
        $result = Customer::select('customer_name','customer_mobile','id')
		->where('customer_name', 'LIKE', "%{$search}%")
		->orWhere('customer_mobile', 'LIKE', "%{$search}%")
		->take(20)->get();
        $return_data['result']	= $result;
		$return_data['status']	= 1;
		echo json_encode($return_data);
    }
    public function getSaleInvoiceByKeyup(Request $request){
        $search = $request->search;
        $result = SellInwardStock::select('id','invoice_no')->where('invoice_no', 'LIKE', "%{$search}%")->take(20)->get();
        $return_data['result']	= $result;
		$return_data['status']	= 1;
		echo json_encode($return_data);
    }
    public function getPurchaseInvoiceByKeyup(Request $request){
        $search = $request->search;
        $result = PurchaseInwardStock::select('id','invoice_no')->where('invoice_no', 'LIKE', "%{$search}%")->take(20)->get();
        $return_data['result']	= $result;
		$return_data['status']	= 1;
		echo json_encode($return_data);
    }
    public function getProductByKeyup(Request $request){
        $search = $request->search;
        $result = Product::select('id','product_barcode','brand','product_name')
                            ->where('brand', 'LIKE', "%{$search}%")
                            ->orWhere('product_barcode', 'LIKE', "%{$search}%")
                            ->take(20)->get();
        $return_data['result']	= $result;
		$return_data['status']	= 1;
		echo json_encode($return_data);
    }



    public function monthWiseReportPdf(Request $request){
        $branch_id		= Session::get('branch_id');
        $supplier_id	= Session::get('adminId');
        $month = Carbon::create($request->start_date)->month;
        $total_date = Carbon::create($request->start_date)->daysInMonth;
        $year = Carbon::create($request->start_date)->year;
        $previous_month = Carbon::create($request->start_date)->subMonth()->format('m');
        $last_twelve_years = Carbon::create($request->start_date)->subYear()->format('Y-m-d');
        //echo $previous_month;die;
        $start_date = $last_twelve_years;
        $items=[];
        $categories = Category::where('food_type',1)->get();
            for($i = 1; $i <= $total_date; $i++){
                //$items[]= $year.'-'.$month.'-'.$i;
                foreach($categories as $category){
                    $dateE = $year.'-'.$month.'-'.$i;
                    $opening	= 0;
                    $purchase	= 0;
                    $sale		= 0;
                    $closing	= 0;

                    $dateP=date('Y-m-d', strtotime('-1 day', strtotime($dateE)));

                    $prev_purchase_result = InwardStockProducts::where('category_id',$category->id)
                    ->where('branch_id',$branch_id)->selectRaw('sum(total_ml) as total_ml')->whereBetween('created_at', [$start_date." 00:00:00", $dateP." 23:59:59"])->get();
                    $prev_purchase_balance=isset($prev_purchase_result[0]->total_ml)?$prev_purchase_result[0]->total_ml:'0';

                    $prev_sell_result = SellStockProducts::where('category_id',$category->id)
                    ->where('branch_id',$branch_id)->selectRaw('sum(total_ml) as total_ml')->whereBetween('created_at', [$start_date." 00:00:00", $dateP." 23:59:59"])->get();
                    $prev_total_sell=isset($prev_sell_result[0]->total_ml)?$prev_sell_result[0]->total_ml:'0';


                    //echo '<pre>';print_r($prev_purchase_balance);exit;

                    //exit;

                    $current_purchase_result = InwardStockProducts::where('category_id',$category->id)
                    ->where('branch_id',$branch_id)->selectRaw('sum(total_ml) as total_ml')->whereBetween('created_at', [$dateE." 00:00:00", $dateE." 23:59:59"])->get();
                    $purchase=isset($current_purchase_result[0]->total_ml)?$current_purchase_result[0]->total_ml:'0';


                    $current_sell_result = SellStockProducts::where('category_id',$category->id)
                    ->where('branch_id',$branch_id)->selectRaw('sum(total_ml) as total_ml')->whereBetween('created_at', [$dateE." 00:00:00", $dateE." 23:59:59"])->get();
                    $sale=isset($current_sell_result[0]->total_ml)?$current_sell_result[0]->total_ml:'0';
                    $opening=$prev_purchase_balance-$prev_total_sell;
                    if($purchase!=0){
                        $opening= +$purchase;
                    }
                    $closing=$opening-$sale;


                    $items[$dateE][]=array(
                        'date'		=> $dateE,
                        'opening'	=> ($opening/1000),
                        'purchase'	=> ($purchase/1000),
                        'sale'		=> ($sale/1000),
                        'closing'	=> ($closing/1000),
                        //'purchase_balance'	=> $prev_purchase_balance,
                        //'total_sell'		=> $total_sell

                    );

                }
                if($year.'-'.$month.'-'.$i == Carbon::now()->format('Y-m-d')){
                    break ;
                }
            }

            //echo "<pre>";print_r($items);die;

        $data = [];
        $date = Carbon::createFromDate($year, $month, 1);
        $data['month'] = $date->format('F Y');
        $data['items'] = $items;
        $data['categories'] = $categories;
        $data['shop_name'] = 'BAZIMAT';
        $data['shop_address'] = 'West Chowbaga Kolkata - 700105 West Bengal';
        $data['from_date'] = Carbon::create($request->start_date)->format('d-M-Y');
        $data['to_date'] = Carbon::create($request->end_date)->format('d-M-Y');

       /*  $pdf = PDF::loadView('admin.pdf.month-wise-report', $data,
            [
                //'title' => 'Certificate',
                'format' => 'A4-L',
                'orientation' => 'L'
            ]); */
        $pdf = PDF::loadView('admin.pdf.month-wise-report',
        $data,
        [],
        [
          //'title' => 'Certificate',
          //'format' => 'A4-L',
          'mode' => 'utf-8',
          'format' => [190, 380],
          'orientation' => 'L'
        ]);
         //echo "<pre>";print_r($items);die;
		return $pdf->stream(now().'-invoice.pdf');
    }

	public function monthWiseStockTransferReportPdf(Request $request){
		$branch_id		= Session::get('branch_id');
		$first_day_this_month = date('Y-m-01',strtotime($request->start_date));
		$last_day_this_month  = date('Y-m-t',strtotime($request->start_date));

		$items=[];
		if($request->start_date!=''){
			$dateS	= date('Y-m-d',strtotime($first_day_this_month));
			$dateE	= date('Y-m-d',strtotime($last_day_this_month));
			$diff	= strtotime($dateE) - strtotime($dateS);
			$total_day	= round($diff / 86400);

			//$categories = Category::where('food_type',1)->where('id',3)->get();
			$categories = Category::whereIn('id', ['1', '2', '4'])->get();

			//echo '<pre>';print_r($categories);exit;


			$openingStockProductsDateresult 	= OpeningStockProducts::where('branch_id',$branch_id)->orderBy('id', 'asc')->first();
			$opening_start_date					= isset($openingStockProductsDateresult->created_at)?date('Y-m-d',strtotime($openingStockProductsDateresult->created_at)):'';

			//echo '<pre>';print_r($opening_start_date);exit;






			for($i=0;$total_day>=$i;$i++){
				$sell_date = date('Y-m-d', strtotime("+".$i." day", strtotime($dateS)));
				$sell_date_slug = date('Ymd', strtotime("+".$i." day", strtotime($dateS)));
				$currentdayslug	= date('Ymd', strtotime("+1 day"));
				//echo $sell_date.'</br>';
				$fl_bulk_litre_opening=0;
				$fl_bulk_litre_prchase=0;
				$fl_bulk_litre_sale=0;
				$fl_bulk_litre_closing=0;

				$categorie_data=[];


				foreach($categories as $category){
					$category_id = $category->id;

					$openingStockProductResult = OpeningStockProducts::selectRaw('sum(total_ml) as total_opening_ml')->whereBetween('created_at', [$opening_start_date." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->whereNotIn('subcategory_id', [3])->first();
					$datewise_start_opening_stock	= isset($openingStockProductResult->total_opening_ml)?$openingStockProductResult->total_opening_ml:'0';

					//echo '<pre>';print_r($datewise_start_opening_stock);exit;




					$datewise_sell_result = DailyStockTransferHistory::selectRaw('sum(opening_stock_ml) as total_opening_stock_ml,sum(total_ml) as total_sell_ml,sum(closing_stock_ml) as total_closing_stock_ml')->whereBetween('created_at', [$sell_date." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->whereNotIn('subcategory_id', [3])->first();

					//echo '<pre>';print_r($sell_date);exit;

					$datewise_opening_stock		= isset($datewise_sell_result->total_opening_stock_ml)?$datewise_sell_result->total_opening_stock_ml:0;
					$datewise_total_sale		= isset($datewise_sell_result->total_sell_ml)?$datewise_sell_result->total_sell_ml:0;
					$datewise_closing_stock		= isset($datewise_sell_result->total_closing_stock_ml)?$datewise_sell_result->total_closing_stock_ml:0;

					$opening_stock	= $datewise_opening_stock;

					$purchase_history_result 	= DailyProductPurchaseHistory::selectRaw('sum(total_ml) as total_purchase_ml,sum(closing_stock_ml) as total_closing_stock_ml')->whereBetween('created_at', [$sell_date." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->whereNotIn('subcategory_id', [3])->orderBy('id', 'DESC')->first();

					//echo '<pre>';print_r($purchase_history_result);exit;

					$datewise_total_purchase =isset($purchase_history_result->total_purchase_ml)?$purchase_history_result->total_purchase_ml:'0';
					$purchase_stock	= $datewise_total_purchase;

					$prev_sell_date_result 		= DailyStockTransferHistory::where('branch_id',$branch_id)->first();
					$opening_sell_start_date	= isset($prev_sell_date_result->created_at)?date('Y-m-d',strtotime($prev_sell_date_result->created_at)):'';
					//echo '<pre>';print_r($opening_sell_start_date);exit;

					//->where('category_id',$category_id)->whereNotIn('subcategory_id', [3])


					$prev_datewise_sell_result = DailyProductPurchaseHistory::selectRaw('sum(total_ml) as total_purchase_ml')->whereBetween('created_at', [$dateS." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->whereNotIn('subcategory_id', [3])->orderBy('id', 'DESC')->first();
					//echo '<pre>';print_r($dateS);exit;

					$prev_datewise_total_purchase =isset($prev_datewise_sell_result->total_purchase_ml)?$prev_datewise_sell_result->total_purchase_ml:'0';
					$prev_purchase_stock	= $prev_datewise_total_purchase;


					$prev_sell_date				= date('Y-m-d', strtotime("-1 day", strtotime($sell_date)));
					$prev_datewise_sell_result 	= DailyStockTransferHistory::selectRaw('sum(total_ml) as total_sell_ml')->whereBetween('created_at', [$opening_sell_start_date." 00:00:00", $prev_sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->whereNotIn('subcategory_id', [3])->first();
					$prev_datewise_total_sale	= isset($prev_datewise_sell_result->total_sell_ml)?$prev_datewise_sell_result->total_sell_ml:0;

					//echo 'prev_datewise_total_sale-'.$prev_datewise_total_sale.'opening_stock'.$opening_stock.'</br>';




					//echo '<pre>';print_r($prev_datewise_total_sale);exit;
					$total_sale		= $datewise_total_sale;
					$closing_stock	= (($datewise_start_opening_stock+$prev_purchase_stock)-$total_sale);
					$opening_stock	= ($closing_stock-$purchase_stock)+$total_sale;
					if($total_sale>0){
						$closing_stock	= (($datewise_start_opening_stock+$prev_purchase_stock)-$total_sale);
						$opening_stock	= ($closing_stock-$purchase_stock)+$total_sale;
					}

					if($prev_datewise_total_sale>0){
						//echo 'datewise_start_opening_stock'.$datewise_start_opening_stock.'</br>';
						$check_opening_stock=$datewise_start_opening_stock+$prev_purchase_stock;
						$opening_stock=$check_opening_stock-$prev_datewise_total_sale;
						$closing_stock=($opening_stock+$purchase_stock)-$total_sale;
					}

					$test_opening_stock=$datewise_start_opening_stock+$prev_purchase_stock;

					if($prev_datewise_total_sale>0){
						$test_opening_stock=$test_opening_stock-$prev_datewise_total_sale;
					}

					//echo 'opening_stock'.$opening_stock.'</br>';




					$test_closing_stock=$test_opening_stock-$total_sale;



					$fl_bulk_litre_opening +=$opening_stock/1000;
					$fl_bulk_litre_prchase +=$purchase_stock/1000;
					$fl_bulk_litre_sale +=$total_sale/1000;
					$fl_bulk_litre_closing +=$closing_stock/1000;





					$categorie_data[]=array(
						'category_id'	=> $category->id,
						'category_name'	=> $category->name,
						'opening_stock'	=> $opening_stock/1000,
						'purchase_stock'=> $purchase_stock/1000,
						'total_sale'	=> $total_sale/1000,
						'closing_stock'	=> $closing_stock/1000,

						/*'datewise_start_opening_stock'=>$datewise_start_opening_stock,
						'prev_purchase_stock'=> $prev_purchase_stock,

						'test_opening_stock'=>$test_opening_stock,
						'test_closing_stock'=>$test_closing_stock,
						'prev_datewise_total_sale'=>$prev_datewise_total_sale,*/




					);
				}


				//echo '<pre>';print_r($categorie_data);exit;






				$cs_bulk_litre_opening=0;
				$cs_bulk_litre_prchase=0;
				$cs_bulk_litre_sale=0;
				$cs_bulk_litre_closing=0;


				$category_id = 3;

				$openingStockProductResult = OpeningStockProducts::selectRaw('sum(total_ml) as total_opening_ml')->whereBetween('created_at', [$opening_start_date." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->first();
					$datewise_start_opening_stock	= isset($openingStockProductResult->total_opening_ml)?$openingStockProductResult->total_opening_ml:'0';
				$datewise_sell_result = DailyStockTransferHistory::selectRaw('sum(opening_stock_ml) as total_opening_stock_ml,sum(total_ml) as total_sell_ml,sum(closing_stock_ml) as total_closing_stock_ml')->whereBetween('created_at', [$sell_date." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->first();

					//echo '<pre>';print_r($datewise_sell_result);exit;

				$datewise_opening_stock		= isset($datewise_sell_result->total_opening_stock_ml)?$datewise_sell_result->total_opening_stock_ml:0;
				$datewise_total_sale		= isset($datewise_sell_result->total_sell_ml)?$datewise_sell_result->total_sell_ml:0;
				$datewise_closing_stock		= isset($datewise_sell_result->total_closing_stock_ml)?$datewise_sell_result->total_closing_stock_ml:0;

				$opening_stock	= $datewise_opening_stock;

				$purchase_history_result 	= DailyProductPurchaseHistory::selectRaw('sum(total_ml) as total_purchase_ml,sum(closing_stock_ml) as total_closing_stock_ml')->whereBetween('created_at', [$sell_date." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->orderBy('id', 'DESC')->first();

					//echo '<pre>';print_r($purchase_history_result);exit;

				$datewise_total_purchase =isset($purchase_history_result->total_purchase_ml)?$purchase_history_result->total_purchase_ml:'0';
				$purchase_stock	= $datewise_total_purchase;


				$prev_datewise_sell_result = DailyProductPurchaseHistory::selectRaw('sum(total_ml) as total_purchase_ml')->whereBetween('created_at', [$dateS." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->orderBy('id', 'DESC')->first();

				$prev_datewise_total_purchase =isset($prev_datewise_sell_result->total_purchase_ml)?$prev_datewise_sell_result->total_purchase_ml:'0';
				$prev_purchase_stock	= $prev_datewise_total_purchase;

				$prev_sell_date_result 		= DailyStockTransferHistory::where('branch_id',$branch_id)->first();
				$opening_sell_start_date	= isset($prev_sell_date_result->created_at)?date('Y-m-d',strtotime($prev_sell_date_result->created_at)):'';


				$prev_sell_date				= date('Y-m-d', strtotime("-1 day", strtotime($sell_date)));
				//echo '<pre>';print_r($prev_sell_date);exit;
				$prev_datewise_sell_result 	= DailyStockTransferHistory::selectRaw('sum(total_ml) as total_sell_ml')->whereBetween('created_at', [$opening_sell_start_date." 00:00:00", $prev_sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('category_id',$category_id)->first();
				$prev_datewise_total_sale	= isset($prev_datewise_sell_result->total_sell_ml)?$prev_datewise_sell_result->total_sell_ml:0;




					//echo '<pre>';print_r($datewise_total_purchase);exit;
				$total_sale		= $datewise_total_sale;
				$closing_stock	= (($datewise_start_opening_stock+$prev_purchase_stock)-$total_sale);
				$opening_stock	= ($closing_stock-$purchase_stock)+$total_sale;
				if($total_sale>0){
					$closing_stock	= (($datewise_start_opening_stock+$prev_purchase_stock)-$total_sale);
					$opening_stock	= ($closing_stock-$purchase_stock)+$total_sale;
				}

				if($prev_datewise_total_sale>0){
					$check_opening_stock=$datewise_start_opening_stock+$prev_purchase_stock;
					$opening_stock=$check_opening_stock-$prev_datewise_total_sale;
					$closing_stock=($opening_stock+$purchase_stock)-$total_sale;
				}

				$test_opening_stock=$datewise_start_opening_stock+$prev_purchase_stock;

				if($prev_datewise_total_sale>0){
					$test_opening_stock=$test_opening_stock-$prev_datewise_total_sale;
				}




				$test_closing_stock=$test_opening_stock-$total_sale;



				$cs_bulk_litre_opening +=$opening_stock/1000;
				$cs_bulk_litre_prchase +=$purchase_stock/1000;
				$cs_bulk_litre_sale +=$total_sale/1000;
				$cs_bulk_litre_closing +=$closing_stock/1000;


				$beer_bulk_litre_opening=0;
				$beer_bulk_litre_prchase=0;
				$beer_bulk_litre_sale=0;
				$beer_bulk_litre_closing=0;


				$subcategory_id = 3;

				$openingStockProductResult = OpeningStockProducts::selectRaw('sum(total_ml) as total_opening_ml')->whereBetween('created_at', [$opening_start_date." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('subcategory_id',$subcategory_id)->first();
					$datewise_start_opening_stock	= isset($openingStockProductResult->total_opening_ml)?$openingStockProductResult->total_opening_ml:'0';




				$datewise_sell_result = DailyStockTransferHistory::selectRaw('sum(opening_stock_ml) as total_opening_stock_ml,sum(total_ml) as total_sell_ml,sum(closing_stock_ml) as total_closing_stock_ml')->whereBetween('created_at', [$sell_date." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('subcategory_id',$subcategory_id)->first();

					//echo '<pre>';print_r($datewise_sell_result);exit;

				$datewise_opening_stock		= isset($datewise_sell_result->total_opening_stock_ml)?$datewise_sell_result->total_opening_stock_ml:0;
				$datewise_total_sale		= isset($datewise_sell_result->total_sell_ml)?$datewise_sell_result->total_sell_ml:0;
				$datewise_closing_stock		= isset($datewise_sell_result->total_closing_stock_ml)?$datewise_sell_result->total_closing_stock_ml:0;

				$opening_stock	= $datewise_opening_stock;

				$purchase_history_result 	= DailyProductPurchaseHistory::selectRaw('sum(total_ml) as total_purchase_ml,sum(closing_stock_ml) as total_closing_stock_ml')->whereBetween('created_at', [$sell_date." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('subcategory_id',$subcategory_id)->orderBy('id', 'DESC')->first();

					//echo '<pre>';print_r($purchase_history_result);exit;

				$datewise_total_purchase =isset($purchase_history_result->total_purchase_ml)?$purchase_history_result->total_purchase_ml:'0';
				$purchase_stock	= $datewise_total_purchase;


				$prev_datewise_sell_result = DailyProductPurchaseHistory::selectRaw('sum(total_ml) as total_purchase_ml')->whereBetween('created_at', [$dateS." 00:00:00", $sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('subcategory_id',$subcategory_id)->orderBy('id', 'DESC')->first();

				$prev_datewise_total_purchase =isset($prev_datewise_sell_result->total_purchase_ml)?$prev_datewise_sell_result->total_purchase_ml:'0';
				$prev_purchase_stock	= $prev_datewise_total_purchase;

				$prev_sell_date_result 		= DailyStockTransferHistory::where('branch_id',$branch_id)->first();
				$opening_sell_start_date	= isset($prev_sell_date_result->created_at)?date('Y-m-d',strtotime($prev_sell_date_result->created_at)):'';

				//print_r($opening_sell_start_date);exit;;

				$prev_sell_date				= date('Y-m-d', strtotime("-1 day", strtotime($sell_date)));
				$prev_datewise_sell_result 	= DailyStockTransferHistory::selectRaw('sum(total_ml) as total_sell_ml')->whereBetween('created_at', [$opening_sell_start_date." 00:00:00", $prev_sell_date." 23:59:59"])->where('branch_id',$branch_id)->where('subcategory_id',$subcategory_id)->first();
				$prev_datewise_total_sale	= isset($prev_datewise_sell_result->total_sell_ml)?$prev_datewise_sell_result->total_sell_ml:0;



				$test_opening_stock2=0;
					//echo '<pre>';print_r($datewise_total_purchase);exit;
				$total_sale		= $datewise_total_sale;
				$closing_stock	= (($datewise_start_opening_stock+$prev_purchase_stock)-$total_sale);
				$opening_stock	= ($closing_stock-$purchase_stock)+$total_sale;
				if($total_sale>0){
					$closing_stock	= (($datewise_start_opening_stock+$prev_purchase_stock)-$total_sale);
					$opening_stock	= ($closing_stock-$purchase_stock)+$total_sale;

				}

				if($prev_datewise_total_sale>0){
					$check_opening_stock=$datewise_start_opening_stock+$prev_purchase_stock;
					$opening_stock=$check_opening_stock-$prev_datewise_total_sale;

					$closing_stock=($opening_stock+$purchase_stock)-$total_sale;
				}

				$test_opening_stock=$datewise_start_opening_stock+$prev_purchase_stock;

				if($prev_datewise_total_sale>0){
					$test_opening_stock=$test_opening_stock-$prev_datewise_total_sale;
				}




				$test_closing_stock=$test_opening_stock-$total_sale;



				$beer_bulk_litre_opening +=$opening_stock/1000;
				$beer_bulk_litre_prchase +=$purchase_stock/1000;
				$beer_bulk_litre_sale +=$total_sale/1000;
				$beer_bulk_litre_closing +=$closing_stock/1000;


				$items[]=array(
					'sell_date'					=> date('d-M-Y',strtotime($sell_date)),
					'fl_bulk_litre_opening'		=> $fl_bulk_litre_opening,
					'fl_bulk_litre_prchase'		=> $fl_bulk_litre_prchase,
					'fl_bulk_litre_sale'		=> $fl_bulk_litre_sale,
					'fl_bulk_litre_closing'		=> $fl_bulk_litre_closing,

					'cs_bulk_litre_opening'		=> $cs_bulk_litre_opening,
					'cs_bulk_litre_prchase'		=> $cs_bulk_litre_prchase,
					'cs_bulk_litre_sale'		=> $cs_bulk_litre_sale,
					'cs_bulk_litre_closing'		=> $cs_bulk_litre_closing,

					'beer_bulk_litre_opening'		=> $beer_bulk_litre_opening,
					'beer_bulk_litre_prchase'		=> $beer_bulk_litre_prchase,
					'beer_bulk_litre_sale'			=> $beer_bulk_litre_sale,
					'beer_bulk_litre_closing'		=> $beer_bulk_litre_closing,

					'test_opening_stock'=>$test_opening_stock,
					'test_opening_stock2'=>$test_opening_stock2



				);

				if($sell_date == Carbon::now()->format('Y-m-d')){
					break ;
				}
			}
		}


		//echo '<pre>';print_r($items);exit;

		$company_name		= Common::get_user_settings($where=['option_name'=>'company_name'],$branch_id);
		$company_address	= Common::get_user_settings($where=['option_name'=>'company_address'],$branch_id);
		$address2			= Common::get_user_settings($where=['option_name'=>'company_address2'],$branch_id);
		$company_licensee	= Common::get_user_settings($where=['option_name'=>'company_licensee'],$branch_id);

        $data = [];
        $date = Carbon::createFromDate($dateS);
        $data['month'] = $date->format('F Y');
        $data['items'] = $items;
        //$data['categories'] = $categories;
        $data['shop_name'] = $company_name;
        $data['shop_address'] = $address2;
        $data['from_date'] = Carbon::create($request->start_date)->format('d-M-Y');
        $data['to_date'] = Carbon::create($request->end_date)->format('d-M-Y');


		//echo '<pre>';print_r($data);exit;

       /*  $pdf = PDF::loadView('admin.pdf.month-wise-report', $data,
            [
                //'title' => 'Certificate',
                'format' => 'A4-L',
                'orientation' => 'L'
            ]); */
        $pdf = PDF::loadView('admin.pdf.stock-transfer.month-wise-report',
        $data,
        [],
        [
          //'title' => 'Certificate',
          //'format' => 'A4-L',
          'mode' => 'utf-8',
          'format' => [190, 380],
          'orientation' => 'L'
        ]);
         //echo "<pre>";print_r($items);die;
		return $pdf->stream(now().'-invoice.pdf');
    }

	public function top_selling_products(Request $request){

		$admin_type = Session::get('admin_type');
		// echo $admin_type;exit;
		if($admin_type==1){
            if(!empty($request->get('branch_id'))){
                $topSellingProducts = Product::select('products.id', 'products.product_name', 'products.product_barcode', 'products.brand', 'products.dosage_name', 'products.selling_by_name', DB::raw('COUNT(sell_stock_products.id) as sales_count'))
                    ->leftJoin('sell_stock_products', 'products.id', '=', 'sell_stock_products.product_id')
                    ->where('sell_stock_products.branch_id', $request->get('branch_id'))
                    ->groupBy('products.id', 'products.product_name')
                    ->orderBy('sales_count', 'desc')
                    ->limit(50)
                    ->get();
            }else{
                $topSellingProducts = Product::select('products.id', 'products.product_name', 'products.product_barcode', 'products.brand', 'products.dosage_name', 'products.selling_by_name', DB::raw('COUNT(sell_stock_products.id) as sales_count'))
                    ->leftJoin('sell_stock_products', 'products.id', '=', 'sell_stock_products.product_id')
                    ->groupBy('products.id', 'products.product_name')
                    ->orderBy('sales_count', 'desc')
                    ->limit(50)
                    ->get();
            }
		}else{
			$store_id	= Session::get('store_id');
			$topSellingProducts = Product::select('products.id', 'products.product_name', 'products.product_barcode', 'products.brand', 'products.dosage_name', 'products.selling_by_name', DB::raw('COUNT(sell_stock_products.id) as sales_count'))
				->leftJoin('sell_stock_products', 'products.id', '=', 'sell_stock_products.product_id')
				->where('sell_stock_products.branch_id', $store_id)
				->groupBy('products.id', 'products.product_name')
				->orderBy('sales_count', 'desc')
				->limit(50)
				->get();
		}




		$result=[];
		if(count($topSellingProducts) > 0){
			foreach($topSellingProducts as $row){
				if($admin_type==1){
					$t_qty = BranchStockProducts::where('product_id', $row->id)->sum('t_qty');
				}else{
					$t_qty = BranchStockProducts::where('product_id', $row->id)->where('branch_id', $store_id)->sum('t_qty');
				}
				$result[]=array(
					'id'				=> $row->id,
					'product_barcode'	=> $row->product_barcode,
					'product_name'		=> $row->product_name,
					't_qty'				=> $t_qty,
					'brand'				=>  $row->brand,
					'dosage_name'		=>  $row->dosage_name,
					'selling_by_name'	=>  $row->selling_by_name,
				);

			}
		}

		// print_r($result);exit;

		$data = [];
		$data['top_products'] = $result;
		$data['heading'] = 'Invoice Wise Purchase List';
		$data['breadcrumb'] = ['Invoice Wise Purchase', '', 'List'];
        $data['storelist']  = User::with('get_role')->where('role',2)->where('parent_id',0)->orderBy('id', 'desc')->get();

		return view('admin.report.top_selling_products', compact('data'));
	}

	public function low_stock_product(Request $request){

		$admin_type = Session::get('admin_type');

        if(isset($request->id)){
            if($admin_type==1){
                $low_stock = BranchStockProducts::with('product')->where('id', $request->id)->get();
            }else{
                $store_id	= Session::get('store_id');
                $low_stock = BranchStockProducts::with('product')->where('id', $request->id)->where('branch_id', $store_id)->get();
            }
        }else{
            if($admin_type==1){
                if(!empty($request->get('branch_id'))){
                    $low_stock = BranchStockProducts::with('product')->where('branch_id', $request->get('branch_id'))->get();
                }else{
                    $low_stock = BranchStockProducts::with('product')->get();
                }
            }else{
                $store_id	= Session::get('store_id');
                $low_stock = BranchStockProducts::with('product')->where('branch_id', $store_id)->get();
                if(!empty($request->get('product_name'))){
                    $low_stock = BranchStockProducts::with('product')->where('branch_id', $store_id)->where('product_id', $request->get('product_name'))->get();
                }
            }
        }
		// dd($low_stock);
		$data = [];
		$data['low_stock'] = $low_stock;
		$data['heading'] = 'Invoice Wise Purchase List';
		$data['breadcrumb'] = ['Invoice Wise Purchase', '', 'List'];
        $data['product_list']  = Product::orderBy('id', 'desc')->get();
        $data['storelist']  = User::with('get_role')->where('role',2)->where('parent_id',0)->orderBy('id', 'desc')->get();

		return view('admin.report.low_stock_product', compact('data'));
	}

	public function zero_stock_product(Request $request){
		$admin_type = Session::get('admin_type');
		if($admin_type==1){
            if(!empty($request->get('store_id'))){
			    // $zero_stock = BranchStockProducts::with('product')->where('branch_id', $request->get('store_id'))->where('t_qty', '0')->get();
                // $zero_stock = BranchStockProducts::with(['user', 'product'])->select('product_id', DB::raw('SUM(t_qty) as total_qty'), 'branch_id', 'product_barcode')->where('branch_id', $request->get('store_id'))->where('total_qty', '0')->groupBy('product_id')->get();

                $zero_stock = BranchStockProducts::with(['user', 'product'])
                            ->select('product_id', DB::raw('SUM(t_qty) as total_qty'), 'branch_id', 'product_barcode')
                            ->where('branch_id', $request->get('store_id'))
                            ->groupBy('product_id')
                            ->havingRaw('SUM(t_qty) = 0')
                            ->get();

            }else{
                // $zero_stock = BranchStockProducts::with('product')->where('t_qty', '0')->get();
                $zero_stock = BranchStockProducts::with(['user', 'product'])
                            ->select('product_id', DB::raw('SUM(t_qty) as total_qty'), 'branch_id', 'product_barcode')
                            ->groupBy('product_id')
                            ->havingRaw('SUM(t_qty) = 0')
                            ->get();
            }
		}else{
			$store_id	= Session::get('store_id');
			// $zero_stock = BranchStockProducts::with('product')->where('t_qty', '0')->where('branch_id', $store_id)->get();
            $zero_stock = BranchStockProducts::with(['user', 'product'])
                            ->select('product_id', DB::raw('SUM(t_qty) as total_qty'), 'branch_id', 'product_barcode')
                            ->where('branch_id', $store_id)
                            ->groupBy('product_id')
                            ->havingRaw('SUM(t_qty) = 0')
                            ->get();
		}

		$data = [];
		$data['zero_stock'] = $zero_stock;
		$data['heading'] = 'Invoice Wise Purchase List';
		$data['breadcrumb'] = ['Invoice Wise Purchase', '', 'List'];
        $data['storelist']  = User::with('get_role')->where('role',2)->where('parent_id',0)->orderBy('id', 'desc')->get();

		return view('admin.report.zero_stock_product', compact('data'));
	}

    public function near_expiry_stock(Request $request){
        $admin_type = Session::get('admin_type');

        if(isset($request->id)){
            if($admin_type==1){

                if(!empty($request->get('store_id'))){
                    $nearExpiryStock = InwardStockProducts::with('product')->where('branch_id', $request->get('store_id'))->where('id', $request->id)->whereDate('product_expiry_date', '>=', now())
                    ->whereDate('product_expiry_date', '<=', now()->addDays(60))
                    ->get();
                }else{
                    $nearExpiryStock = InwardStockProducts::with('product')->where('id', $request->id)->whereDate('product_expiry_date', '>=', now())
                    ->whereDate('product_expiry_date', '<=', now()->addDays(60))
                    ->get();
                }
            }else{
                $store_id	= Session::get('store_id');
                $nearExpiryStock = InwardStockProducts::with('product')->where('id', $request->id)->where('branch_id', $store_id)->whereDate('product_expiry_date', '>=', now())
                ->whereDate('product_expiry_date', '<=', now()->addDays(60))
                ->get();
            }
        }else{
            if($admin_type==1){
                if(!empty($request->get('store_id'))){
                    $nearExpiryStock = InwardStockProducts::with('product')->where('branch_id', $request->get('store_id'))->whereDate('product_expiry_date', '>=', now())
                    ->whereDate('product_expiry_date', '<=', now()->addDays(60))
                    ->get();
                }else{
                    $nearExpiryStock = InwardStockProducts::with('product')->whereDate('product_expiry_date', '>=', now())
                    ->whereDate('product_expiry_date', '<=', now()->addDays(60))
                    ->get();
                }
            }else{
                $store_id	= Session::get('store_id');
                $nearExpiryStock = InwardStockProducts::with('product')->where('branch_id', $store_id)->whereDate('product_expiry_date', '>=', now())
                ->whereDate('product_expiry_date', '<=', now()->addDays(60))
                ->get();
            }
        }




        // dd($nearExpiryStock);

        $data = [];
		$data['nearExpiryStock'] = $nearExpiryStock;
		$data['heading'] = 'Near Expiry Stock';
		$data['breadcrumb'] = ['Near Expiry Stock', '', 'List'];
        $data['storelist']  = User::with('get_role')->where('role',2)->where('parent_id',0)->orderBy('id', 'desc')->get();

		return view('admin.report.near_expiry_stock', compact('data'));

    }

    public function near_expiry_stock_download(Request $request){
        $store_id = $request->store_id;
        return Excel::download(new NearExpiryStockDownload($store_id), 'Stock-near-expiry.xlsx');
    }

    public function top_selling_product_download(Request $request){

        $branch_id = $request->branch_id;

        return Excel::download(new TopSellingProductDownload($branch_id), 'top-selling-products.xlsx');
    }

    public function low_stock_product_download(Request $request){
        $branch_id = $request->branch_id;
        return Excel::download(new LowStockProductDownload($branch_id), 'low-stock-product.xlsx');
    }

    public function purchase_invoice_wise_download(Request $request){
        $invoice = $request->invoice;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $store_id = $request->store_id;
        return Excel::download(new PurchaseInvoiceWiseDownload($invoice, $start_date, $end_date, $store_id), 'purchase-invoice-wise-report.xlsx');
    }


    public function purchase_product_wise_download(Request $request){
        $company = $request->company;
        $dosage = $request->dosage;
        $brand = $request->brand;

        $invoice = $request->invoice;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $store_id = $request->store_id;
        return Excel::download(new PurchaseProductWiseDownload($company, $dosage, $brand, $invoice, $start_date, $end_date, $store_id), 'purchase-product-wise-report.xlsx');
    }

    public function invoice_wies_sale_download(Request $request){

        $invoice = $request->invoice;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $store_id = $request->store_id;

        return Excel::download(new InvoiceWiesSaleDownload($invoice, $start_date, $end_date, $store_id), 'invoice-wise-sales-report.xlsx');
    }

    public function product_wise_sales_download(Request $request){

        $company = $request->company;
        $dosage = $request->dosage;
        $brand = $request->brand;

        $invoice = $request->invoice;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $store_id = $request->store_id;

        return Excel::download(new ProductWiesSaleDownload($company, $dosage, $brand, $invoice, $start_date, $end_date, $store_id), 'product-wise-sales-report.xlsx');
    }

    public function zero_stock_product_download(Request $request){

        $store_id = $request->store_id;

        return Excel::download(new ZeroStockProductDownload($store_id), 'zero-stock-product-report.xlsx');
    }


    public function inventory_download(Request $request){

        $brand = $request->brand;
        $product_barcode = $request->product_barcode;
        $store_id = $request->store_id;
        $order_by = $request->order_by;

        return Excel::download(new InventoryDownload($brand, $product_barcode, $store_id, $order_by), 'inventory-report.xlsx');

    }

    public function get_productexperylist(Request $request){
        $store_id = $request->store_id;
        $product_id = $request->product_id;

        if($store_id=='0'){
            // echo "asd 0";
            $queryStockProduct = BranchStockProducts::select('product_id', DB::raw('SUM(t_qty) as t_qty'), 'branch_id', 'product_barcode', 'product_expiry_date')->where('product_id', $product_id)->groupBy('product_expiry_date')->get();
        }else{
            // echo "asd 1";
            $queryStockProduct = BranchStockProducts::select('product_id', DB::raw('SUM(t_qty) as t_qty'), 'branch_id', 'product_barcode', 'product_expiry_date')->where('product_id', $product_id)->where('branch_id', $store_id)->groupBy('product_expiry_date')->get();
        }

        // dd($queryStockProduct);

        $html = '';
        foreach ($queryStockProduct as $key => $item) {
            $html .= '<tr>
                        <td>'.($key+1).'</td>
                        <td>'.date('m-Y', strtotime(str_replace('.', '/', $item->product_expiry_date))).'</td>
                        <td>'.$item->t_qty.'</td>
                    </tr>';
        }

        return response()->json([
            'status' => 1,
            'html'=>$html,
        ]);

    }




}
