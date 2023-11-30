<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Session;

use App\Models\Product;
use App\Models\BranchStockProducts;
use DB;

class TopSellingProductDownload implements FromView
{
    protected $branch_id;

    public function __construct($branch_id)
    {
        $this->branch_id = $branch_id;
    }

    public function view(): View
    {

        $admin_type = Session::get('admin_type');
		// echo $admin_type;exit;
		if($admin_type==1){
            if($this->branch_id!=''){
                $topSellingProducts = Product::select('products.id', 'products.product_name', 'products.product_barcode', 'products.brand', 'products.dosage_name', 'products.selling_by_name', DB::raw('COUNT(sell_stock_products.id) as sales_count'))
                    ->leftJoin('sell_stock_products', 'products.id', '=', 'sell_stock_products.product_id')
                    ->where('sell_stock_products.branch_id', $this->branch_id)
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

        return view('admin.exportsexcel.top_selling_product_download', [
            'result' => $result
        ]);

    }
}
