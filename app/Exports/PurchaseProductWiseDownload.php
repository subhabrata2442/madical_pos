<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Session;

use App\Models\InwardStockProducts;
use App\Models\PurchaseInwardStock;
use Auth;


class PurchaseProductWiseDownload implements FromView
{
    protected $company;
    protected $dosage;
    protected $brand;

    protected $invoice;
    protected $start_date;
    protected $end_date;

    public function __construct($company, $dosage, $brand, $invoice, $start_date, $end_date)
    {
        $this->company = $company;
        $this->dosage = $dosage;
        $this->brand = $brand;
        $this->invoice = $invoice;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function view(): View
    {

        //dd($request->all());
		$data = [];
		$queryProduct = InwardStockProducts::query();
		//Add sorting
		$queryProduct->orderBy('id', 'desc');
		if($this->start_date!='' && $this->end_date){
			if($this->start_date == $this->end_date){
				$queryProduct->whereDate('created_at', $this->start_date);
			}else{
				$queryProduct->whereBetween('created_at', [$this->start_date, $this->end_date]);
			}
		}
		/* if(!is_null($request['customer_id'])) {
			$queryProduct->whereHas('sellInwardStock',function($q) use ($request){
				return $q->where('customer_id',$request['customer_id']);
			});
		} */
		if($this->invoice!='') {
			$queryProduct->whereHas('purchaseInwardStock',function($q) use ($request){
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

        // dd($queryProduct->paginate(10));

        return view('admin.exportsexcel.purchase_product_wise_download', [
            'productss' => $queryProduct->paginate(10000)
        ]);

    }
}
