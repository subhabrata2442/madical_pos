<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerDeliveryAddress;
use App\Models\CustomerAddress;
use App\Models\CustomerChildren;
use App\Helper\Media;


use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
class CustomerController extends Controller
{
    public function add(Request $request)
    {
        try {

            // dd($request->all());

            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'customer_name' 		=> 'required',
                    'customer_mobile' 	=> 'required',
                    //'sku_code' 		=> 'required',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

				//$delivery_address=$request->delivery_address;


				/* $is_same_delivery_address='N';
				if(isset($delivery_address)){
					if($delivery_address=='yes'){
						$is_same_delivery_address='Y';
					}
				} */

                $checkcustomer = Customer::where('customer_mobile', $request->customer_mobile)->first();


				$customer_data=array(
					'customer_name'  		=> $request->customer_name,
					'customer_mobile'  	=> $request->customer_mobile,
					'created_at'			=> date('Y-m-d')
				);

                if(empty($checkcustomer)){
                    $customer=Customer::create($customer_data);
				    $customer_id=$customer->id;
                }else{
                    $return_data['success'] = 0;
				    return response()->json([$return_data]);
                }



				/* $customer_address_data=array(
					'customer_id'  	=> $customer_id,
					'address'  		=> $request->customer_address,
					'area'			=> $request->customer_area,
					'city'			=> $request->customer_city,
					'pincode'		=> $request->customer_pincode,
					'state'			=> $request->customer_state_id,
					'country'		=> $request->customer_country_id,
					'created_at'	=> date('Y-m-d')
				);

				CustomerAddress::create($customer_address_data);



				$customer_delivery_address_data=array(
					'customer_id'  	=> $customer_id,
					'address'  		=> $request->customer_delivery_address,
					'area'			=> $request->customer_delivery_area,
					'city'			=> $request->customer_delivery_city,
					'pincode'		=> $request->customer_delivery_pincode,
					'state'			=> $request->customer_delivery_state_id,
					'country'		=> $request->customer_delivery_country_id,
					'created_at'	=> date('Y-m-d')
				);

				if($is_same_delivery_address=='Y'){
					CustomerDeliveryAddress::create($customer_address_data);
				}else{
					CustomerDeliveryAddress::create($customer_delivery_address_data);
				}
				$customer_id=$customer->id;

				$customer_address_data=array(
					'customer_id'  	=> $customer_id,
					'address'  		=> $request->customer_address,
					'area'			=> $request->customer_area,
					'city'			=> $request->customer_city,
					'pincode'		=> $request->customer_pincode,
					'state'			=> $request->customer_state_id,
					'country'		=> $request->customer_country_id,
					'created_at'	=> date('Y-m-d')
				);

				CustomerAddress::create($customer_address_data);



				$customer_delivery_address_data=array(
					'customer_id'  	=> $customer_id,
					'address'  		=> $request->customer_delivery_address,
					'area'			=> $request->customer_delivery_area,
					'city'			=> $request->customer_delivery_city,
					'pincode'		=> $request->customer_delivery_pincode,
					'state'			=> $request->customer_delivery_state_id,
					'country'		=> $request->customer_delivery_country_id,
					'created_at'	=> date('Y-m-d')
				);

				if($is_same_delivery_address=='Y'){
					CustomerDeliveryAddress::create($customer_address_data);
				}else{
					CustomerDeliveryAddress::create($customer_delivery_address_data);
				} */

				/* if(isset($request->child_name)){
					if(count($request->child_name)>0){
						for($i=0;count($request->child_name)>$i;$i++){
							$customer_child=array(
								'customer_id'  			=> $customer_id,
								'child_name'  			=> $request->child_name[$i],
								'child_date_of_birth'  	=> $request->child_date_of_birth[$i],
								'created_at'			=> date('Y-m-d')
							);
							CustomerChildren::create($customer_child);
						}
					}

				} */
				$return_data['customer_id'] = $customer_id;
				$return_data['customer_name'] = $request->customer_name;
				$return_data['customer_mobile'] = $request->customer_mobile;
				$return_data['success'] = 1;
				return response()->json([$return_data]);
                //return redirect()->back()->with('success', 'Customer created successfully');
            }
            $data = [];
            $data['heading'] 		= 'Add New Customer';
            $data['breadcrumb'] 	= ['Customer', 'Add'];
			//print_r($data);exit;

            return view('admin.customer.add', compact('data'));
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {

                $customer_query = Customer::query();

                if (!is_null($request['customer_name'])) {
                    $customer_query->where('customer_name', $request['customer_name']);
                }

                if (!is_null($request['customer_mobile'])) {
                    $customer_query->where('customer_mobile', $request['customer_mobile']);
                }

                $customer = $customer_query->orderBy('id', 'desc')->paginate(20);

            $data = [];
            $data['customer_list'] = $customer;
            $data['heading'] = 'Customer List';
            $data['breadcrumb'] = ['Customer', 'List'];
            return view('admin.customer.list', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }

    public function edit($id, Request $request)
    {
        try {
            $customer_id = base64_decode($id);
            if ($request->isMethod('post')) {
                // dd($request->all());
                $validator = Validator::make($request->all(), [
                    //'customer_fname' => 'required|unique:products,product_code,' . $product_id,
                    /*'default_purchase_price' => 'required|numeric',
                    'purchase_tax_rate' => 'required|numeric',
                    'min_order_qty' => 'required|numeric',
                    'product_desc' => 'required',
                    'pack_size' => 'required',*/
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

				$delivery_address=$request->delivery_address;
				$is_same_delivery_address='N';
				if(isset($delivery_address)){
					if($delivery_address=='yes'){
						$is_same_delivery_address='Y';
					}
				}

				//echo '<pre>';print_r($_POST);exit;

				$customer_data=array(
					'customer_fname'  		=> $request->customer_fname,
					'customer_last_name'  	=> $request->customer_last_name,
					'customer_email'		=> $request->customer_email,
					'customer_mobile'		=> $request->customer_mobile,
					'gender'				=> $request->gender,
					'customer_gstin'		=> $request->customer_gstin,
					'date_of_birth'			=> $request->customer_date_of_birth,
					'outstanding_duedays'   => $request->outstanding_duedays,
					'source'   				=> $request->source,
					'is_same_delivery_address' => $is_same_delivery_address,
					'created_at'			=> date('Y-m-d')
				);
				Customer::find($customer_id)->update($customer_data);

				CustomerAddress::where('customer_id',$customer_id)->delete();
				$customer_address_data=array(
					'customer_id'  	=> $customer_id,
					'address'  		=> $request->customer_address,
					'area'			=> $request->customer_area,
					'city'			=> $request->customer_city,
					'pincode'		=> $request->customer_pincode,
					'state'			=> $request->customer_state_id,
					'country'		=> $request->customer_country_id,
					'created_at'	=> date('Y-m-d')
				);
				CustomerAddress::create($customer_address_data);



				$customer_delivery_address_data=array(
					'customer_id'  	=> $customer_id,
					'address'  		=> $request->customer_delivery_address,
					'area'			=> $request->customer_delivery_area,
					'city'			=> $request->customer_delivery_city,
					'pincode'		=> $request->customer_delivery_pincode,
					'state'			=> $request->customer_delivery_state_id,
					'country'		=> $request->customer_delivery_country_id,
					'created_at'	=> date('Y-m-d')
				);
				CustomerDeliveryAddress::where('customer_id',$customer_id)->delete();
				if($is_same_delivery_address=='Y'){
					CustomerDeliveryAddress::create($customer_address_data);
				}else{
					CustomerDeliveryAddress::create($customer_delivery_address_data);
				}

				CustomerChildren::where('customer_id',$customer_id)->delete();
				if(isset($request->child_name)){
					if(count($request->child_name)>0){
						for($i=0;count($request->child_name)>$i;$i++){
							$customer_child=array(
								'customer_id'  			=> $customer_id,
								'child_name'  			=> $request->child_name[$i],
								'child_date_of_birth'  	=> $request->child_date_of_birth[$i],
								'created_at'			=> date('Y-m-d')
							);
							CustomerChildren::create($customer_child);
						}
					}

				}

                return redirect()->back()->with('success', 'Customer updated successfully');
            }
            $data = [];
            $data['heading'] = 'Customer Edit';
            $data['breadcrumb'] = ['Customer', 'Edit'];
            $data['customer'] = Customer::find($customer_id);
			$data['customer_address'] = CustomerAddress::where('customer_id',$customer_id)->first();
			$data['children'] = CustomerChildren::where('customer_id',$customer_id)->get();
			$data['delivery_address'] = CustomerDeliveryAddress::where('customer_id',$customer_id)->first();
            return view('admin.customer.edit', compact('data'));
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $id = base64_decode($id);
            Customer::find($id)->delete();
            CustomerAddress::where('customer_id', $id)->delete();
			CustomerDeliveryAddress::where('customer_id', $id)->delete();
			CustomerChildren::where('customer_id', $id)->delete();
            return redirect()->back()->with('success', 'Customer deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try later. ' . $e->getMessage());
        }
    }
}
