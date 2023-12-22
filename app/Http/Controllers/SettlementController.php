<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settlement;
use Illuminate\Support\Facades\Session;

Use Illuminate\Support\Str;
use Auth;
use App\Models\User;



class SettlementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function settlement(Request $request){

        // $branch_id=Auth::user()->id;
		$user_role=Auth::user()->role;

		if($user_role==1){
			$settlement 	= Settlement::where('total_settlement_amount','!=','')->orderBy('admin_approved', 'DESC');
		}else{
            $store_id	= Session::get('store_id');
			$settlement = Settlement::where('total_settlement_amount','!=','')->where('store_id',$store_id)->orderBy('admin_approved', 'DESC');
		}


		if(!empty($request->get('start_date')) && !empty($request->get('end_date'))){
			if($request->get('start_date') == $request->get('end_date')){
				$settlement->whereDate('created_at', $request->get('start_date'));
			}else{
				$settlement->whereBetween('created_at', [$request->get('start_date'), $request->get('end_date')]);
			}
		}

        if(!empty($request->get('store_id'))){

			$settlement->where('store_id', $request->get('store_id'));

		}

		$settlement->orderBy('id', 'desc')->get();

        $all_settlement = $settlement->paginate(10);

        $data = [];

		$data['all_settlement'] = $all_settlement;
		$data['heading'] = 'Settlement';
		$data['breadcrumb'] = ['Settlement', '', 'List'];
        $data['storelist']  = User::with('get_role')->where('role',2)->where('parent_id',0)->orderBy('id', 'desc')->get();

		return view('admin.settlement.index', compact('data'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function settlement_approve($id){
        $id = base64_decode($id);

        Settlement::where('id', $id)->update(['admin_approved'=>'1']);
        return redirect()->back()->with('success', 'Status approved successfully');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // echo 1;exit;
        // dd($request->all());
        $data_note = array();
        foreach ($request->note_name as $key => $value) {
            $data_note[] = [
                $value=>$request->note_count[$key],
            ];
        }

        $branch_id		= Session::get('store_id');
        $data = [
            'store_id'=>$branch_id,
            'note_amount'=> json_encode($data_note),
            'total_settlement_amount'=>$request->total_settlement_amount,
        ];

        Settlement::create($data);

        return redirect()->back()->with('success', 'Settlement Added successfully for '.date('d-m-Y'));




    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
