<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Session;

use App\Models\BranchStockProducts;

class LowStockProductDownload implements FromView
{
    protected $branch_id;

    public function __construct($branch_id)
    {
        $this->branch_id = $branch_id;
    }

    public function view(): View
    {

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
                if($this->branch_id!=''){
                    $low_stock = BranchStockProducts::with('product')->where('branch_id', $this->branch_id)->get();
                }else{
                    $low_stock = BranchStockProducts::with('product')->get();
                }
            }else{
                $store_id	= Session::get('store_id');
                $low_stock = BranchStockProducts::with('product')->where('branch_id', $store_id)->get();
            }
        }

        return view('admin.exportsexcel.low_stock_product_download', [
            'low_stock' => $low_stock
        ]);

    }
}
