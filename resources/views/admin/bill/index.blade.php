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
		<form action="">
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-12">
                    <h4>Bill</h4>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                    <div class="form-group">
                        <div class="position-relative">
                            <input type="text" class="form-control" id="bill_no" name="bill_no"
                                value="{{request()->input('bill_no')}}" autocomplete="off" placeholder="Enter bill No.">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                    <div class="form-group">
                        <div class="position-relative">
                            <input type="text" class="form-control" id="customer_no" name="customer_no"
                                value="{{request()->input('customer_no')}}" autocomplete="off" placeholder="Customer phone number ">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                    <div class="form-group">
                        <button class="saveBtn-2" type="submit">Search</button>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                </div>
            </div>
        </form>
	</div>
</div>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <x-alert />

                <div class="table-responsive dataTable-design">
                    <table id="user-table" class="table table-bordered">
                        <thead>
                            <th>Bill No.</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Bill Date</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>
                            @foreach ($data['latest_bill'] as $key=>$item)
                                <tr>
                                    <td>{{($item->bill_no)}}</td>
                                    <td>
                                        @if ($item->customer_id!=0)
                                            {{$item->customer->customer_name}}
                                        @else
                                            Walk in customer
                                        @endif
                                    </td>
                                    <td>{{number_format($item->pay_amount)}}</td>
                                    <td>{{date('m-d-Y', strtotime(str_replace('.', '/', $item->created_at)))}}</td>
                                    <td>
                                        @php
                                            $dateToCheck = strtotime(date('Y-m-d', strtotime(str_replace('.', '/', $item->created_at))));
                                            $startDate = strtotime(date('Y-m-d', strtotime('-'.$data['settingdata']->return_bill_time.' days')));
                                            $endDate = strtotime(date('Y-m-d'));
                                        @endphp
                                        @if($dateToCheck >= $startDate && $dateToCheck <= $endDate)
                                            <a href="{{url('admin/pos/billedit')}}/{{$item->id}}" class="btn btn-primary btn-sm">Edit</a>
                                        @else
                                            <a href="javascript:void(0)" class="btn btn-secondary btn-sm disabled_btn" disabled>Date over</a>
                                        @endif

                                        <a href="{{url('admin/pos/billdelete')}}/{{$item->id}}" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</a>


                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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



	$('#download_report').on("click",function(){
		var report_type = $('#report_type').val();
		var start_date = $('input[name=start_date]').val();
		var end_date = $('input[name=end_date]').val();
		if(report_type == ''){
			Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select report type!',
            })
		}else if(start_date == '' && end_date== ''){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select date!',
            })
        }else{
			console.log('sdfd');
            var url = "{{route('admin.report.purchase.invoice_wise')}}";
			var href = url+'?start_date='+start_date+'&end_date='+end_date;

			window.open(href);
		    //$(this).attr('href',url+'?start_date='+start_date+'&end_date='+end_date);
			//window.location = window.location.href;
        }


	})
	//Start date range picker
	/* var start = moment().subtract(29, 'days');
    var end = moment();
	 */

   /*  function cb(start, end) {
        //$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        $('#reportrange').val(start.format('D-MM-YYYY') + ' - ' + end.format('D-MM-YYYY'));
		$('#start_date').val(start.format('YYYY-MM-DD'));
		$('#end_date').val(end.format('YYYY-MM-DD'));
    } */

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

</script>
@endsection
