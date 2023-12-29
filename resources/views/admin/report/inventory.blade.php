@extends('layouts.admin')
@section('admin-content')
<style>
	.content-header{
		display: none !important;
	}
	.custom-table table tbody tr:nth-child(odd){
		background: #f5f5f5;
	}
	.custom-table table tbody tr:nth-child(even){
		background: #f5e9e3;
	}
	.auto_search_result {
		list-style: none;
		padding: 0px;
		width: 100%;
		position: absolute;
		margin: 0;
		max-height: 300px;
		overflow-y: auto;
		z-index: 99999;
		top: 100%;
		background: #cfcff0;
	}
	.auto_search_result li {
		background: lavender;
		padding: 7px 15px;
		margin-bottom: 1px;
		font-size: 13px;
	}
	.auto_search_result li:hover {
		cursor: pointer;
		background: cadetblue;
		color: white;
	}
	.reset-btn{
		background-color: #d3681b;
	}
	input.qty_update {
    width: 70px;
}
</style>
<div class="srcBtnWrap">
  <div class="card">
    <div class="row align-items-center justify-content-between">
      <div class="col-auto">
        <h4>Stock Inventory</h4>
      </div>
      <div class="col d-flex invoiceAmout justify-content-center">
        <ul class="d-flex">
            <li>Search By: {{$data['store_name']}}</span></li>
        </ul>
    </div>
      <div class="col-auto"> <a href="javascript:;" class="searchDropBtn">Advance Search <i class="fas fa-chevron-circle-down"></i></a> </div>
    </div>
  </div>
</div>
<div class="card toggleCard">
  <form action="" method="get" id="filter">
    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-12 col-12">
        <div class="form-group">
          <label for="customer_last_name" class="form-label">Product Name</label>
          <div class="position-relative">
            <input type="text" class="form-control" id="search_product" name="brand" value="{{request()->input('brand')}}" autocomplete="off">
          </div>
          <input type="hidden" id="product_id" name="product_id" value="{{request()->input('product_id')}}">
        </div>
      </div>
      {{-- <div class="col-lg-3 col-md-3 col-sm-12 col-12">
        <div class="form-group">
          <label for="drugstore" class="form-label">Drugstore name</label>
          <div class="position-relative">
            <input type="text" class="form-control" id="drugstore" name="drugstore" value="{{request()->input('drugstore')}}" autocomplete="off">
          </div>
        </div>
      </div> --}}
      <div class="col-lg-3 col-md-3 col-sm-12 col-12">
        <div class="form-group">
          <label for="product_barcode" class="form-label">Barcode</label>
          <div class="position-relative">
            <input type="text" class="form-control" id="product_barcode" name="product_barcode" value="{{request()->input('product_barcode')}}" autocomplete="off">
          </div>
        </div>
      </div>

        @if (Auth::user()->role == 1)
            <div class="col-lg-3 col-md-3 col-sm-12 col-12">
                <div class="form-group">
                    <label for="" class="form-label">Select Store</label>
                    <select class="form-control custom-select form-control-select" id="" name="store_id">
                        <option value="">Select Store</option>
                        @forelse ($data['storeUsers'] as $store)
                            <option value="{{$store->id}}" {{request()->input('store_id') == $store->id ? 'selected' : ''}}>{{$store->name}}</option>
                        @empty

                        @endforelse
                    </select>
                </div>
            </div>
        @endif

        <div class="col-lg-3 col-md-3 col-sm-12 col-12">
            <div class="form-group">
                <label for="" class="form-label">Stock</label>
                <select class="form-control custom-select form-control-select" id="" name="order_by">
                    <option value="">Stock</option>
                    <option value="htw" {{request()->input('order_by') == 'htw' ? 'selected' : ''}}>High to Low</option>
                    <option value="lth" {{request()->input('order_by') == 'lth' ? 'selected' : ''}}>Low to High</option>
                </select>
            </div>
        </div>

      {{-- <div class="col-lg-2 col-md-2 col-sm-12 col-12">
        <div class="form-group">
          <label for="" class="form-label">Select Category</label>
          <select class="form-control custom-select form-control-select" id="" name="category">
            <option value="">Select Category</option>
						@forelse ($data['categories'] as $category)
            <option value="{{$category->id}}" {{request()->input('category') == $category->id ? 'selected' : ''}}>{{$category->name}}</option>
						@empty
						@endforelse
          </select>
        </div>
      </div> --}}


      <div class="col-12">
        <ul class="saveSrcArea d-flex align-items-center justify-content-center mb-2">
          <li> <a href="javascript:?" class="saveBtn-2 reset-btn" id="reset">Reset</i></a> </li>
          <li>
            <button class="saveBtn-2" type="submit">Search <i class="fas fa-arrow-circle-right"></i></button>
          </li>
          <li>
            @php

                $brand = '';
                $product_barcode = '';
                $store_id = '';
                $order_by = '';

                if (isset($_GET['brand'])) {
                    $brand = $_GET['brand'];
                }
                if (isset($_GET['product_barcode'])) {
                    $product_barcode = $_GET['product_barcode'];
                }
                if (isset($_GET['store_id'])) {
                    $store_id = $_GET['store_id'];
                }
                if (isset($_GET['order_by'])) {
                    $order_by = $_GET['order_by'];
                }
            @endphp
            <a href="{{ url('admin/report/inventory_download?brand='.$brand.'&product_barcode='.$product_barcode.'&store_id='.$store_id.'&order_by='.$order_by) }}" class="btn btn-primary">Download Excel</a>
        </li>
        </ul>
      </div>
    </div>
  </form>
</div>
<div class="row">
  <div class="col-12">
    <div class="card">
      <x-alert />
      <div class="table-responsive custom-table accordion table-with-accrodian" id="stockInventory">
        <table id="" class="table table-bordered text-nowrap">
          <thead>
            {{-- <th scope="col">Store</th> --}}
            <th scope="col">#</th>
            <th scope="col">Barcode</th>
            <th scope="col">Brand</th>
            <th scope="col">Dosage Form</th>
            <th scope="col">Company</th>
            {{-- <th scope="col">Drugstore name</th> --}}
            <th scope="col">Total Stock</th>
            {{-- <th scope="col">Net Price</th>
            <th scope="col">Price</th>
            <th scope="col">US/IQ rate</th>
            <th scope="col">Sell Price</th> --}}
          </thead>
          <tbody>

          @forelse ($data['products'] as $key=>$Stock_product)
          @php
          $product_mrp=isset($Stock_product->stockProduct->product_mrp)?number_format($Stock_product->stockProduct->product_mrp,2):'-';
          $c_qty=isset($Stock_product->stockProduct->c_qty)?$Stock_product->stockProduct->c_qty:'-';
          $w_qty=isset($Stock_product->stockProduct->w_qty)?$Stock_product->stockProduct->w_qty:'-';

          $admin_type = Session::get('admin_type');


            if($admin_type==1){
                if(request()->input('store_id')){
                    $store_id = request()->input('store_id');
                }else{
                    $store_id = '0';
                }
            }else{
                $store_id = Session::get('store_id');
            }

          @endphp
          <tr>
            {{-- <td>{{@$Stock_product->user->name}}</td> --}}
            <td style="width: 60px">
                <button class="accordion-button collapsed" onclick="get_productexperylist('{{$key}}', '{{$store_id}}', '{{@$Stock_product->product->id}}')" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne{{$key}}" aria-expanded="true" aria-controls="collapseOne{{$key}}">
                </button>
            </td>
            <td>{{@$Stock_product->product_barcode}}</td>
            <td>{{@$Stock_product->product->brand}}</td>
            <td>{{@$Stock_product->product->dosage_name}}</td>
            <td>{{@$Stock_product->product->company_name}}</td>
            {{-- <td>{{@$Stock_product->product->drugstore_name}}</td> --}}
            <td>{{@$Stock_product->t_qty}}</td>
            {{-- <td>{{@$Stock_product->product->net_price}}</td>
            <td>{{@$Stock_product->product->product_mrp}}</td>
            <td>{{@$Stock_product->product->product_mrp}}</td>
            <td>{{@$Stock_product->product->selling_price}}</td> --}}
          </tr>
          <tr>
            <td colspan="6" class="details-box">
                <div id="collapseOne{{$key}}" class="accordion-collapse collapse" aria-labelledby="headingOne{{$key}}" data-bs-parent="#stockInventory">
                    <div class="accordion-body">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Sl No.</th>
                                    <th>Expiry date</th>
                                    <th>Total Stock</th>
                                </tr>
                            </thead>
                            <tbody class="appendexperyproduct{{$key}}">
                                {{-- <tr>
                                    <td>2</td>
                                    <td>2</td>
                                    <td>2</td>
                                </tr> --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </td>
          </tr>
          @empty
          <tr >
            <td colspan="10"> No data found </td>
          </tr>
          @endforelse
            </tbody>

        </table>
        {{ $data['products']->appends($_GET)->links() }} </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
@if( Request::has('product'))
<script>
	$(".toggleCard").css("display", "block");
	</script>
@endif
<script type="text/javascript">
$(document).ready(function() {
    $(".qty_update").click(function() {
        $(this).select();
    });

    $(document).on('keyup', '.qty_update', function(e) {
        var code = e.keyCode || e.which;
        var qty = $(this).val();

        var branch_id = $(this).data('branch_id');
        var product_id = $(this).data('product_id');
        var size_id = $(this).data('size_id');
        var stock_id = $(this).data('stock_id');

        if (code == 13) {
            $.ajax({
                url: prop.ajaxurl,
                type: 'post',
                data: {
                    branch_id: branch_id,
                    product_id: product_id,
                    size_id: size_id,
                    stock_id: stock_id,
                    qty: qty,
                    action: 'update_stock_product_qty',
                    _token: prop.csrf_token
                },
                dataType: 'json',
                success: function(response) {
                    Swal.fire(
                        'Success!',
                        'Qty Successfully updated!',
                        'success'
                    )
                }
            });
        }
    });
});
</script>
<script type="text/javascript">
$(function() {
	//End Date range picker
	$('.searchDropBtn').on("click",function(){
		$(".toggleCard").slideToggle();
	})

	//Cusomer List
	$("#search_customer").keyup(function() {
		var search = $(this).val();
		if (search != "") {
            $.ajax({
                url: '{{route('admin.ajax.customer-list')}}',
                type: 'get',
                data: {
                    search: search,
                    _token: prop.csrf_token
                },
                dataType: 'json',
                success: function(response) {
                    var len = response.result.length;
                    $("#search_customer_list").empty();
                    for (var i = 0; i < len; i++) {
                        var id = response.result[i]['id'];
                        var name = response.result[i]['customer_fname'] + ' ' + response.result[i]['customer_last_name'];
                        $("#search_customer_list").append("<li value='" + id + "'>" + name + "</li>");
                    }
                    // binding click event to li
                    $("#search_customer_list li").bind("click", function() {
                        $('.loader_section').show();
						var cname = $(this).text();
    					var cid = $(this).val();
						$('#search_customer').val(cname);
						$('#customer_id').val(cid);
						$("#search_customer_list").empty();
                    });
                }
            });
        }
	});

	//Invoice List
	$("#search_sale_invoice").keyup(function() {
		var search = $(this).val();
		if (search != "") {
            $.ajax({
                url: '{{route('admin.ajax.sale-invoice-list')}}',
                type: 'get',
                data: {
                    search: search,
                    _token: prop.csrf_token
                },
                dataType: 'json',
                success: function(response) {
                    var len = response.result.length;
                    $("#search_sale_invoice_list").empty();
                    for (var i = 0; i < len; i++) {
                        var id = response.result[i]['id'];
                        var name = response.result[i]['invoice_no'];
                        $("#search_sale_invoice_list").append("<li value='" + id + "'>" + name + "</li>");
                    }
                    // binding click event to li
                    $("#search_sale_invoice_list li").bind("click", function() {
                        $('.loader_section').show();
						var cname = $(this).text();
    					var cid = $(this).val();
						$('#search_sale_invoice').val(cname);
						$('#invoice_id').val(cid);
						$("#search_sale_invoice_list").empty();
                    });
                }
            });
        }
	});

	//Product List
	$("#search_product").keyup(function() {
		var search = $(this).val();
		if (search != "") {
            $.ajax({
                url: '{{route('admin.ajax.sale-product')}}',
                type: 'get',
                data: {
                    search: search,
                    _token: prop.csrf_token
                },
                dataType: 'json',
                success: function(response) {
                    var len = response.result.length;
                    $("#search_product_list").empty();
                    for (var i = 0; i < len; i++) {
                        var id = response.result[i]['id'];
                        var name = response.result[i]['product_name']+ ' ' + response.result[i]['product_barcode'];
                        $("#search_product_list").append("<li value='" + id + "'>" + name + "</li>");
                    }
                    // binding click event to li
                    $("#search_product_list li").bind("click", function() {
                        $('.loader_section').show();
						var cname = $(this).text();
						console.log(cname);
    					var cid = $(this).val();
						$('#search_product').val(cname);
						$('#product_id').val(cid);
						$("#search_product_list").empty();
                    });
                }
            });
        }
	});
	//reset form
	$("#reset").click(function() {
		$('#filter').trigger("reset");
		window.location = window.location.href.split("?")[0];
	});
});


    function get_productexperylist(key, store_id, product_id){
        $.ajax({
        url: "{{url('admin/get_productexperylist')}}",
        type: "get",
        data: {
            product_id: product_id,
            store_id: store_id,
            _token: "<?php echo csrf_token(); ?>",
        },
        dataType: "json",
        success: function(response) {
            if (response.status == 1) {
                $(".appendexperyproduct"+key).html(response.html);
            }else{

            }
        },
    });
    }

</script>
@endsection
