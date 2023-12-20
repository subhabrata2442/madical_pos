<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Suppliercreditpay;
use App\Models\PurchaseInwardStock;

class CredithistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

        $data = [];
		$data['heading'] = 'Credit History';
		$data['breadcrumb'] = ['Credit History', '', 'List'];

        $data['supplier'] = Supplier::with(['PurchaseInwardStock' => fn($query) => $query->where('payment_method', 'credit')])->get();

        // dd($data['supplier']);
		return view('admin.credit_history.index', compact('data'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $insert_data=array(
			'supplier_id'  => $request->supplier_id,
            'amount'  => $request->amount,
            'payment_date'  => $request->payment_date,
            'payment_method' => $request->payment_method,
		);
        Suppliercreditpay::create($insert_data);
        return redirect()->back()->with('success', 'Payment added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function suppliercredithistory($supplier_id){
        $supplier_id = base64_decode($supplier_id);
        $purchaseInwardStock = PurchaseInwardStock::where('supplier_id', $supplier_id)->paginate(10);


        $data = [];
        $data['supplier'] = Supplier::where('id', $supplier_id)->first();
		$data['purchaseInwardStock']   = $purchaseInwardStock;

        $data['heading'] = 'Credit History';
		$data['breadcrumb'] = ['Credit History', '', 'List'];

		return view('admin.credit_history.suppliercredithistory', compact('data'));


    }

    public function supplierpaymenthistory($supplier_id){
        $supplier_id = base64_decode($supplier_id);
        $suppliercreditpay = Suppliercreditpay::where('supplier_id', $supplier_id)->paginate(10);


        $data = [];
        $data['supplier'] = Supplier::where('id', $supplier_id)->first();
		$data['suppliercreditpay']   = $suppliercreditpay;

        $data['heading'] = 'Credit History';
		$data['breadcrumb'] = ['Credit History', '', 'List'];

		return view('admin.credit_history.supplierpaymenthistory', compact('data'));


    }

    public function suppliercredithistory_modal($supplier_id){
        $purchaseInwardStock = PurchaseInwardStock::where('supplier_id', $supplier_id)->paginate(10);


        $supplier = Supplier::where('id', $supplier_id)->first();

        $html = '';
        foreach ($purchaseInwardStock as $key => $item) {
            $html .= '<tr>
                        <td>'.($key+1).'</td>
                        <td>'.number_format($item->qty_total_net_price).'</td>
                        <td>'.$item->payment_date.'</td>
                    </tr>';
        }

        return response()->json([
            'status' => 1,
            'html'=>$html,
            'supplier'=> $supplier->company_name.' Credit List',
            'allurl' =>route('admin.suppliercredithistory', [base64_encode($supplier_id)]),
            'totalrecord' => count($purchaseInwardStock),
        ]);

    }


    public function supplierpaymenthistory_modal($supplier_id){
        $suppliercreditpay = Suppliercreditpay::where('supplier_id', $supplier_id)->paginate(10);
        $supplier = Supplier::where('id', $supplier_id)->first();

        $html = '';
        foreach ($suppliercreditpay as $key => $item) {
            $html .= '<tr>
                        <td>'.($key+1).'</td>
                        <td>'.number_format($item->amount).'</td>
                        <td>'.$item->payment_method.'</td>
                        <td>'.$item->payment_date.'</td>
                    </tr>';
        }

        return response()->json([
            'status' => 1,
            'html'=>$html,
            'supplier'=> $supplier->company_name.' Payment List',
            'allurl' =>route('admin.suppliercredithistory', [base64_encode($supplier_id)]),
            'totalrecord' => count($suppliercreditpay),
        ]);
    }


}
