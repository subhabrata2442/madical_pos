<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Session;

use App\Models\InwardStockProducts;

class NearExpiryStockDownload implements FromView
{
    public function view(): View
    {
        $admin_type = Session::get('admin_type');

        if($admin_type==1){
            $nearExpiryStock = InwardStockProducts::with('product')->whereDate('product_expiry_date', '>=', now())
            ->whereDate('product_expiry_date', '<=', now()->addDays(60))
            ->get();
        }else{
            $store_id	= Session::get('store_id');
            $nearExpiryStock = InwardStockProducts::with('product')->where('branch_id', $store_id)->whereDate('product_expiry_date', '>=', now())
            ->whereDate('product_expiry_date', '<=', now()->addDays(60))
            ->get();
        }

        return view('admin.exportsexcel.near_expiry_stock_download', [
            'nearExpiryStock' => $nearExpiryStock
        ]);
    }



}
