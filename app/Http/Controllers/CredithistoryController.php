<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Suppliercreditpay;
use App\Models\PurchaseInwardStock;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class CredithistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){

        $admin_type = Session::get('admin_type');

        $data = [];
		$data['heading'] = 'Credit History';
		$data['breadcrumb'] = ['Credit History', '', 'List'];

        if($admin_type==1){
            $data['supplier'] = Supplier::with(['PurchaseInwardStock' => fn($query) => $query->where('payment_method', 'debt')])->get();

            if(!empty($request->get('branch_id'))){
                $data['supplier'] = Supplier::with(['PurchaseInwardStock' => fn($query) => $query->where('payment_method', 'debt')->where('branch_id', $request->get('branch_id'))])
                ->whereHas('PurchaseInwardStock', fn ($query) =>
                    $query->where('branch_id', $request->get('branch_id'))
                )->get();
            }

        }else{

            $store_id	= Session::get('store_id');

            $data['supplier'] = Supplier::with(['PurchaseInwardStock' => fn($query) => $query->where('payment_method', 'debt')->where('branch_id', $store_id)])
            ->whereHas('PurchaseInwardStock', fn ($query) =>
                $query->where('branch_id', $store_id)
            )->get();

        }



        $data['storelist']  = User::with('get_role')->where('role',2)->where('parent_id',0)->orderBy('id', 'desc')->get();
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


        $admin_type = Session::get('admin_type');
        if($admin_type==1){
            $store_id = $request->store_id;
        }else{
            $store_id	= Session::get('store_id');
        }



        if($request->id==''){
            $insert_data=array(
                'supplier_id'  => $request->supplier_id,
                'amount'  => $request->amount,
                'payment_date'  => $request->payment_date,
                'payment_method' => $request->payment_method,
                'store_id' => $store_id,
            );
            Suppliercreditpay::create($insert_data);
            return redirect()->back()->with('success', 'Payment added successfully');
        }else{
            $insert_data=array(
                'amount'  => $request->amount,
                'payment_date'  => $request->payment_date,
                'payment_method' => $request->payment_method,
            );
            Suppliercreditpay::where('id', $request->id)->update($insert_data);
            return redirect()->back()->with('success', 'Payment updated successfully');
        }


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

    public function suppliercredithistory_modal(Request $request){

        $supplier_id = $request->supplier_id;
        $store_id = $request->store_id;

        if($store_id!=''){
            $purchaseInwardStock = PurchaseInwardStock::where('branch_id', $store_id)->where('supplier_id', $supplier_id)->paginate(20);
        }else{
            $purchaseInwardStock = PurchaseInwardStock::where('supplier_id', $supplier_id)->paginate(20);
        }



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


    public function supplierpaymenthistory_modal(Request $request){

        $supplier_id = $request->supplier_id;
        $store_id = $request->store_id;

        if($store_id!=''){
            $suppliercreditpay = Suppliercreditpay::where('supplier_id', $supplier_id)->where('store_id', $store_id)->paginate(20);
        }else{
            $suppliercreditpay = Suppliercreditpay::where('supplier_id', $supplier_id)->paginate(20);
        }

        $supplier = Supplier::where('id', $supplier_id)->first();

        $alert_msg = 'Are you sure?';

        $html = '';
        foreach ($suppliercreditpay as $key => $item) {
            $html .= '<tr>
                        <td>'.($key+1).'</td>
                        <td>'.number_format($item->amount).'</td>
                        <td>'.$item->payment_method.'</td>
                        <td>'.$item->payment_date.'</td>
                        <td>
                            <a href="javascript:void(0)" onclick="edit_payment(\''.$item->id.'\', \''.$item->store_id.'\', \''.$item->amount.'\',\''.$item->payment_method.'\', \''.$item->payment_date.'\')"><i class="fas fa-edit"></i></a>
                            <a href="'.url('admin/debit_deletepayment').'/'.$item->id.'" onclick="return confirm(\''.$alert_msg.'\')"><i class="fas fa-trash"></i></a>
                        </td>
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

    public function debit_deletepayment($id){
        Suppliercreditpay::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Payment deleted successfully');
    }


}
