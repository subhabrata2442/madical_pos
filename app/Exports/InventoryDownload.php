<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Session;
use Auth;
use DB;

use App\Models\BranchStockProducts;

class InventoryDownload implements FromView
{
    protected $brand;
    protected $product_barcode;
    protected $store_id;
    protected $order_by;

    public function __construct($brand, $product_barcode, $store_id, $order_by)
    {
        $this->brand = $brand;
        $this->product_barcode = $product_barcode;
        $this->store_id = $store_id;
        $this->order_by = $order_by;
    }

    public function view(): View
    {

        $branch_id=Auth::user()->id;
            $user_role=Auth::user()->role;
            if($user_role==1){
			    $queryStockProduct = BranchStockProducts::with(['user', 'product'])->select('product_id', DB::raw('SUM(t_qty) as t_qty'), 'branch_id', 'product_barcode')->groupBy('product_id');
            }else{
                $queryStockProduct = BranchStockProducts::select('product_id', DB::raw('SUM(t_qty) as t_qty'), 'branch_id', 'product_barcode')->groupBy('product_id')->where('branch_id', $branch_id);
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

            if($this->order_by!='') {
                if ($this->order_by=='htw') {
                    $queryStockProduct->orderBy('t_qty', 'DESC');
                }else{
                    $queryStockProduct->orderBy('t_qty', 'ASC');
                }

			}



        return view('admin.exportsexcel.inventory_download', [
            'stockProducts' => $queryStockProduct->paginate(10000)
        ]);

    }
}
