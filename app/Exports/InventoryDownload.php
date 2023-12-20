<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Session;
use Auth;

use App\Models\BranchStockProducts;

class InventoryDownload implements FromView
{
    protected $brand;
    protected $product_barcode;
    protected $store_id;

    public function __construct($brand, $product_barcode, $store_id)
    {
        $this->brand = $brand;
        $this->product_barcode = $product_barcode;
        $this->store_id = $store_id;
    }

    public function view(): View
    {

        $branch_id=Auth::user()->id;
            $user_role=Auth::user()->role;
            if($user_role==1){
			    $queryStockProduct = BranchStockProducts::query();
            }else{
                $queryStockProduct = BranchStockProducts::where('branch_id',$branch_id);
            }


			if($this->product_barcode!='') {
				$queryStockProduct->where('product_barcode',$this->product_barcode);
			}
			if($this->brand!='') {
				$queryStockProduct->whereHas('product',function($q) use ($request){
					return $q->where('brand','LIKE','%'.$this->brand.'%');
				});
			}
            if($this->store_id!='') {
				$queryStockProduct->where('branch_id',$this->store_id);
			}



        return view('admin.exportsexcel.inventory_download', [
            'stockProducts' => $queryStockProduct->paginate(10000)
        ]);

    }
}
