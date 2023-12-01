<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Session;

use App\Models\BranchStockProducts;
use App\Models\PurchaseInwardStock;
use Auth;

class PurchaseInvoiceWiseDownload implements FromView
{
    protected $invoice;
    protected $start_date;
    protected $end_date;
    protected $store_id;

    public function __construct($invoice, $start_date, $end_date, $store_id)
    {
        $this->invoice = $invoice;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->store_id = $store_id;
    }

    public function view(): View
    {

        $branch_id=Auth::user()->id;
		$user_role=Auth::user()->role;
		if($user_role==1){
			$purchase 	= PurchaseInwardStock::where('invoice_no','!=','');
		}else{
			$purchase 	= PurchaseInwardStock::where('invoice_no','!=','')->where('supplier_id',$branch_id);
		}
		//$sales = SellInwardStock::where('invoice_no','!=','');
		if($this->start_date!='' && $this->end_date!=''){
			if($this->end_date == $this->end_date){
				$purchase->whereDate('purchase_date', $this->end_date);
			}else{
				$purchase->whereBetween('purchase_date', [$this->end_date, $this->end_date]);
			}
		}
		if($this->invoice!=''){

			$purchase->where('invoice_no',  $this->invoice);

		}

        if($this->store_id!=''){

			$purchase->where('branch_id', $this->store_id);

		}

		$purchase->orderBy('id', 'desc')->get();

        // dd($purchase->paginate(1000000));

        return view('admin.exportsexcel.purchase_invoice_wise_download', [
            'purchasess' => $purchase->paginate(1000000)
        ]);

    }
}
