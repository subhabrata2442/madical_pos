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

class ZeroStockProductDownload implements FromView
{
    protected $store_id;

    public function __construct($store_id)
    {
        $this->branch_id = $store_id;
    }

    public function view(): View
    {

        $admin_type = Session::get('admin_type');
		if($admin_type==1){
            if($this->branch_id!=''){
			    $zero_stock = BranchStockProducts::with('product')->where('branch_id', $this->branch_id)->where('t_qty', '0')->get();
            }else{
                $zero_stock = BranchStockProducts::with('product')->where('t_qty', '0')->get();
            }
		}else{
			$store_id	= Session::get('store_id');
			$zero_stock = BranchStockProducts::with('product')->where('t_qty', '0')->where('branch_id', $store_id)->get();
		}

        return view('admin.exportsexcel.zero_stock_product_download', [
            'zero_stock' => $zero_stock
        ]);

    }
}
