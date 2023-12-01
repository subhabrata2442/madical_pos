<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Session;

use App\Models\SellInwardStock;

class InvoiceWiesSaleDownload implements FromView
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

        $sales = SellInwardStock::where('invoice_no','!=','');
        if($this->start_date!='' && $this->end_date!=''){
            if($this->start_date == $this->end_date){
                $sales->whereDate('sell_date', $this->start_date);
            }else{
                $sales->whereBetween('sell_date', [$this->start_date, $this->end_date]);
            }
        }
        if($this->invoice!=''){

            $sales->where('invoice_no', $this->invoice);

        }
        if($this->store_id!=''){

            $sales->where('branch_id', $this->store_id);

        }

        $sales->orderBy('id', 'desc')->get();



        return view('admin.exportsexcel.invoice_wies_sale_download', [
            'sales' => $sales->paginate(10000)
        ]);

    }
}
