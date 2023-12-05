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
</style>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<div class="srcBtnWrap">
	<div class="card">
		<div class="row align-items-center justify-content-between">
			<div class="col-auto">
				<h4>Product Wise Purchase</h4>
			</div>
			<div class="col d-flex invoiceAmout justify-content-center">
				<ul class="d-flex">
					<li>Total Invoice : <span>{{$data['total_invoice']}}</span></li>
					<li>Total Qty : <span>{{$data['total_qty']}}</span></li>
					<li>Total Profit : <span>{{number_format($data['total_profit'],2)}}</span></li>
					<li>Profit : <span>{{number_format($data['profitpersent'],2)}}%</span></li>
					<!-- <li>advanced Search : <span>0</span></li> -->
				</ul>
			</div>
			<div class="col-auto">
				<a href="javascript:;" class="searchDropBtn">Advance Search <i class="fas fa-chevron-circle-down"></i></a>
			</div>
		</div>
	</div>
</div>
<div class="card toggleCard">
	<form action="" method="get" id="filter">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-12 col-12">
				<div class="form-group">
					<label for="date_search" class="mr-3">Date Filter</label>
					<input type="text" class="form-control" name="datefilter" id="reportrange" placeholder="Select Date" autocomplete="off" value="{{request()->input('datefilter')}}">
					<input type="hidden" name="start_date" id="start_date" value="{{request()->input('start_date')}}">
					<input type="hidden" name="end_date" id="end_date" value="{{request()->input('end_date')}}">
				</div>
			</div>
				{{-- <div class="col-lg-3 col-md-3 col-sm-12 col-12">
					<div class="form-group">
						<label for="customer_last_name" class="form-label">By Customer Name / Mobile</label>
						<div class="position-relative">
							<input type="text" class="form-control" id="search_customer" name="customer" value="{{request()->input('customer')}}" autocomplete="off">
							<ul id="search_customer_list" class="auto_search_result">
						</div>
						<input type="hidden" name="customer_id" id="customer_id" value="{{request()->input('customer_id')}}">
					</div>
				</div> --}}
			<div class="col-lg-3 col-md-3 col-sm-12 col-12">
				<div class="form-group">
					<label for="customer_last_name" class="form-label">Invoice No.</label>
					<div class="position-relative">
						<input type="text" class="form-control" id="search_sale_invoice" name="invoice" value="{{request()->input('invoice')}}" autocomplete="off">
						<ul id="search_sale_invoice_list" class="auto_search_result">
					</div>
					<input type="hidden" id="invoice_id" name="invoice_id" value="{{request()->input('invoice_id')}}">
				</div>
			</div>

			<div class="col-lg-3 col-md-3 col-sm-12 col-12">
				<div class="form-group">
					<label for="" class="form-label">Select Brand</label>
					<select class="form-control custom-select form-control-select" id="" name="brand">
						<option value="">Select Brand</option>
						@forelse ($data['brands'] as $brand)
							<option value="{{$brand->id}}" {{request()->input('brand') == $brand->id ? 'selected' : ''}}>{{$brand->name}}</option>
						@empty

						@endforelse
					</select>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-12 col-12">
				<div class="form-group">
					<label for="" class="form-label">Select Dosage Form</label>
					<select class="form-control custom-select form-control-select" id="" name="dosage">
						<option value="">Select Dosage Form</option>
						@forelse ($data['dosages'] as $dosage)
							<option value="{{$dosage->id}}" {{request()->input('dosage') == $dosage->id ? 'selected' : ''}}>{{$dosage->name}}</option>
						@empty

						@endforelse
					</select>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-12 col-12">
				<div class="form-group">
					<label for="" class="form-label">Select Company</label>
					<select class="form-control custom-select form-control-select" id="" name="company">
						<option value="">Select Company</option>
						@forelse ($data['companies'] as $company)
							<option value="{{$company->id}}" {{request()->input('company') == $company->id ? 'selected' : ''}}>{{$company->name}}</option>
						@empty

						@endforelse
					</select>
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
			{{-- <div class="col-lg-3 col-md-3 col-sm-12 col-12">
				<div class="form-group">
					<label for="" class="form-label">Select Brand</label>
					<select class="form-control custom-select form-control-select" id="">
						<option value="">Select 1</option>
					</select>
				</div>
			</div> --}}

			<div class="col-12">
				<ul class="saveSrcArea d-flex align-items-center justify-content-center mb-2">
					<li>
						<a href="javascript:?" class="saveBtn-2 reset-btn" id="reset">Reset</i></a>
					</li>
					<li>
						<button class="saveBtn-2" type="submit">Search <i class="fas fa-arrow-circle-right"></i></button>
					</li>
					{{-- <li class="d-flex align-items-center">
						<div>
							<select class="form-control custom-select form-control-select" id="report_type">
								<option value=""> Select Report Type</option>
								<option value="item_wise_sales_report"> Item Wise sales report</option>
								<option value="month_wise_report"> Month Wise report</option>
                                <option value="brand_wise_report"> Brand Wise report</option>
                                <option value="e_report"> E-Report</option>
								<option value="text_download"> Text Download</option>
							</select>
						</div>
						<div>
							<button type="button" id="download_report" class="srcBtnWrapGo"><i class="fas fa-download"></i></button>
						</div>
					</li> --}}

                    <li>
                        @php
                            $company = '';
                            $dosage = '';
                            $brand = '';
                            $invoice = '';
                            $start_date = '';
                            $end_date = '';
                            $store_id = '';

                            if (isset($_GET['company'])) {
                                $company = $_GET['company'];
                            }
                            if (isset($_GET['dosage'])) {
                                $dosage = $_GET['dosage'];
                            }
                            if (isset($_GET['brand'])) {
                                $brand = $_GET['brand'];
                            }
                            if (isset($_GET['invoice'])) {
                                $invoice = $_GET['invoice'];
                            }
                            if (isset($_GET['start_date'])) {
                                $start_date = $_GET['start_date'];
                            }
                            if (isset($_GET['end_date'])) {
                                $end_date = $_GET['end_date'];
                            }
                            if (isset($_GET['store_id'])) {
                                $store_id = $_GET['store_id'];
                            }
                        @endphp
						<a href="{{ url('admin/report/purchase_product_wise_download?company='.$company.'&dosage='.$dosage.'&brand='.$brand.'&invoice='.$invoice.'&start_date='.$start_date.'&end_date='.$end_date.'&store_id='.$store_id) }}" class="btn btn-primary">Download Excel</a>
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
	  {{-- <div class="d-flex justify-content-between align-items-center mb-4">
		<form method="get" id="search-form" class="form-inline" role="form">
			<input type="hidden" name="item_id" value="{{$data['item_id']}}" id="item_id">
			<input type="hidden" name="start_date" id="start_date" value="">
			<input type="hidden" name="end_date" id="end_date" value="">
			<div class="form-group">
				<label for="date_search" class="mr-3">Date</label>
				<input type="text" class="form-control" name="datefilter" id="reportrange" placeholder="Select Date" autocomplete="off">
			</div>
			<button type="submit" class="btn btn-primary ml-3">Search</button>
		</form>
		<a href="javascript:;" id="download" data-date="" class="downloadBtn"><i class="fas fa-download"></i> Download</a>
	  </div> --}}

      <div class="table-responsive custom-table">
        <table id="" class="table table-bordered text-nowrap">
          <thead>
			<th scope="col">Invoice No</th>
            <th scope="col">Purchase Date</th>
			<th scope="col">Store</th>
			<th scope="col">Supplier</th>
            <th scope="col">Product Name</th>
            <th scope="col">Brand Name</th>
            <th scope="col">Product Barcode</th>
            <th scope="col">Dosage</th>
            <th scope="col">Selling By</th>
            <th scope="col">Company Name</th>
            <th scope="col">Qty</th>
            <th scope="col">Net Price</th>
            <th scope="col">Selling Price</th>
            <th scope="col">Profit Amount</th>
           </thead>
          <tbody>
			@forelse ($data['products'] as $product)
			<tr>
				<td>{{@$product->purchaseInwardStock->invoice_no}}</td>
				<td>{{date('d-m-Y', strtotime($product->purchaseInwardStock->purchase_date))}}</td>
				<td>{{@$product->store->name}}</td>
				<td>{{$product->purchaseInwardStock->supplier_name}}</td>
				<td>{{$product->product_name}}</td>
				<td>{{$product->brand}}</td>
				<td>{{$product->product->product_barcode}}</td>
				<td>{{$product->dosage}}</td>
				<td>{{$product->selling_by}}</td>
				<td>{{$product->company}}</td>
				<td>{{$product->product_qty}}</td>
				<td>{{number_format($product->net_price,2)}}</td>
				<td>{{number_format($product->selling_price,2)}}</td>
				<td>{{number_format($product->profit_amount,2)}}</td>
			</tr>
			@empty
				<tr ><td colspan="13"> No data found </td></tr>
			@endforelse

          </tbody>
        </table>
		{{ $data['products']->appends($_GET)->links() }}
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
@if( Request::has('datefilter'))
    <script>
	$(".toggleCard").css("display", "block");
	</script>
@endif
<script type="text/javascript">

$(function() {





    $('#reportrange').daterangepicker({
        //startDate: start,
        //endDate: end,
		autoUpdateInput: false,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    //cb(start, end);

	$('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
      	$(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
	  	$('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
		$('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
  	});
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
                url: '{{route('admin.ajax.purchase-invoice-list')}}',
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
                        var name = response.result[i]['product_name']+ ' / ' + response.result[i]['product_barcode'];
                        $("#search_product_list").append("<li value='" + id + "'>" + name + "</li>");
                    }
                    // binding click event to li
                    $("#search_product_list li").bind("click", function() {
                        $('.loader_section').show();
						var cname = $(this).text();
						//console.log(cname);
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

</script>
@endsection
