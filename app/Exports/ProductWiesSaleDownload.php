<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Session;

use App\Models\SellStockProducts;

class ProductWiesSaleDownload implements FromView
{
    protected $company;
    protected $dosage;
    protected $brand;

    protected $invoice;
    protected $start_date;
    protected $end_date;

    protected $store_id;

    public function __construct($company, $dosage, $brand, $invoice, $start_date, $end_date, $store_id)
    {
        $this->company = $company;
        $this->dosage = $dosage;
        $this->brand = $brand;
        $this->invoice = $invoice;
        $this->start_date = $start_date;
        $this->end_date = $end_date;

        $this->store_id = $store_id;
    }

    public function view(): View
    {

        $data = [];
            $queryProduct = SellStockProducts::query();
            //Add sorting
            $queryProduct->orderBy('id', 'desc');
            if($this->start_date!='' && $this->end_date!=''){
                if($this->start_date == $this->end_date){
                    $queryProduct->whereDate('created_at', $this->start_date);
                }else{
                    $queryProduct->whereBetween('created_at', [$this->start_date, $this->end_date]);
                }
            }
            // if(!is_null($request['customer_id'])) {
            //     $queryProduct->whereHas('sellInwardStock',function($q) use ($request){
            //         return $q->where('customer_id',$request['customer_id']);
            //     });
            // }
            if($this->invoice!='') {
                $queryProduct->whereHas('sellInwardStock',function($q) use ($request){
                    return $q->where('id',$this->invoice);
                });
            }

            if($this->brand!='') {
                $queryProduct->whereHas('product',function($q) use ($request){
                    return $q->where('brand_id',$this->brand);
                });
            }
            if($this->dosage!='') {
                $queryProduct->whereHas('product',function($q) use ($request){
                    return $q->where('dosage_id',$this->dosage);
                });
            }
            if($this->company!='') {
				$queryProduct->whereHas('product',function($q) use ($request){
                    return $q->where('company_id',$this->company);
                });
            }

            if($this->store_id!='') {
                $queryProduct->where('branch_id', $this->store_id);
            }



        return view('admin.exportsexcel.product_wise_sales_download', [
            'queryProduct' => $queryProduct->paginate(10000)
        ]);

    }
}
