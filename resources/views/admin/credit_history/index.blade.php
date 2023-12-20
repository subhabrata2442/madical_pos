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
    .color_black{
        color:#212529;
    }
</style>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


<div class="srcBtnWrap">
	<div class="card">
		<div class="row align-items-center justify-content-between">
			<div class="col-auto">
				<h4>Credit history</h4>
			</div>
			<div class="col d-flex invoiceAmout justify-content-center">
				<ul class="d-flex">
					{{-- <li>Total Expense : {{number_format($data['totalexpense'])}}<span></span></li> --}}
				</ul>
			</div>
			{{-- <div class="col-auto">
				<a href="javascript:;" class="searchDropBtn">Advance Search <i class="fas fa-chevron-circle-down"></i></a>
			</div> --}}
		</div>
	</div>
</div>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <x-alert />
                <div class="table-responsive dataTable-design">
                    <table id="user-table" class="table table-bordered">
                        <thead>

                            <th>Sl No.</th>
                            <th>Supplier</th>
                            <th>Total Credit</th>
                            <th>Credit Pay</th>
                            <th>Credit Due</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>
                            @foreach ($data['supplier'] as $key=>$item)
                                <tr>
                                    <td>{{($key+1)}}</td>
                                    <td>{{$item->company_name}}</td>
                                    <td> <a class="color_black" href="javascript:void(0)" onclick="creditHistory('{{$item->id}}')">{{number_format($item->PurchaseInwardStock->sum('qty_total_net_price'))}}</a></td>
                                    <td> <a class="color_black" href="javascript:void(0)" onclick="paymentHistory('{{$item->id}}')">{{number_format($item->Suppliercreditpay->sum('amount'))}}</a></td>
                                    <td>{{number_format($item->PurchaseInwardStock->sum('qty_total_net_price')-$item->Suppliercreditpay->sum('amount'))}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <div class="actionList " id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <svg style="cursor: pointer;" xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-sliders dropdown-toggle" data-toggle="dropdown"
                                                    role="button" aria-expanded="false">
                                                    <line x1="4" y1="21" x2="4" y2="14"></line>
                                                    <line x1="4" y1="10" x2="4" y2="3"></line>
                                                    <line x1="12" y1="21" x2="12" y2="12"></line>
                                                    <line x1="12" y1="8" x2="12" y2="3"></line>
                                                    <line x1="20" y1="21" x2="20" y2="16"></line>
                                                    <line x1="20" y1="12" x2="20" y2="3"></line>
                                                    <line x1="1" y1="14" x2="7" y2="14"></line>
                                                    <line x1="9" y1="8" x2="15" y2="8"></line>
                                                    <line x1="17" y1="16" x2="23" y2="16"></line>
                                                </svg>
                                            </div>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <a class="dropdown-item" href="javascript:void(0)" onclick="payCredit('{{$item->id}}')">Pay</a>
                                                <a class="dropdown-item" href="javascript:void(0)" onclick="creditHistory('{{$item->id}}')">Credit history</a>
                                                <a class="dropdown-item" href="javascript:void(0)" onclick="paymentHistory('{{$item->id}}')">Payment history</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade modalMdHeader" id="modal_paycradit" tabindex="-1" aria-labelledby="modal-1Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal-1Label">Payment</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="applyCouponBox">
                <form action="{{ route('admin.paymentcreditadd') }}" method="post" id="paymentcraditform" class="needs-validation" novalidate>
                <input type="hidden" id="supplier_id" name="supplier_id" />
                    @csrf
                  <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Amount</label>
                            <input type="text" name="amount" class="form-control" placeholder="Amount" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Payment Date</label>
                            <input type="date" name="payment_date" class="form-control" value="{{date('d-m-Y')}}" required>
                        </div>
                    </div>
                  </div>


                  <button type="submit" class="btn btn-primary">Submit</button>
                </form>
              </div>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade modalMdHeader" id="modal_credithistory" tabindex="-1" aria-labelledby="modal-1Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title appendtitle" id="modal-1Label">Payment</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="" class="table table-bordered creditHistoryTable">
                    <thead>

                        <th>Sl No.</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </thead>
                    <tbody class="creditHistory">
                    </tbody>
                </table>

            </div>
          </div>
        </div>
    </div>

    <div class="modal fade modalMdHeader" id="modal_paymenthistory" tabindex="-1" aria-labelledby="modal-1Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title appendtitle" id="modal-1Label">Payment</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="" class="table table-bordered paymentHistoryTable">
                    <thead>

                        <th>Sl No.</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </thead>
                    <tbody class="paymentHistory">
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





	//reset form
	$("#reset").click(function() {
		$('#filter').trigger("reset");
		window.location = window.location.href.split("?")[0];
	});
});

function payCredit(supplier_id){
    $("#supplier_id").val(supplier_id);
    $('#modal_paycradit').modal('show');
}

function creditHistory(supplier_id){
    $.ajax({
        url: "{{url('admin/suppliercredithistory_modal')}}/"+supplier_id,
        type: "get",
        data: {
            supplier_id: supplier_id,
            _token: "<?php echo csrf_token(); ?>",
        },
        dataType: "json",
        success: function(response) {
            if (response.status == 1) {
                $("#modal_credithistory").modal('show');
                $(".creditHistory").html(response.html);
                $(".appendtitle").html(response.supplier);

                if(response.totalrecord==10){
                    var link = $('<a>', {
                        href: response.allurl,
                        text: 'View all',
                    });
                    $('.creditHistoryTable').after(link);

                }
            }else{

            }
        },
    });
}

function paymentHistory(supplier_id){
    $.ajax({
        url: "{{url('admin/supplierpaymenthistory_modal')}}/"+supplier_id,
        type: "get",
        data: {
            supplier_id: supplier_id,
            _token: "<?php echo csrf_token(); ?>",
        },
        dataType: "json",
        success: function(response) {
            if (response.status == 1) {
                $("#modal_paymenthistory").modal('show');
                $(".paymentHistory").html(response.html);
                $(".appendtitle").html(response.supplier);

                if(response.totalrecord==10){
                    var link = $('<a>', {
                        href: response.allurl,
                        text: 'View all',
                    });
                    $('.paymentHistoryTable').after(link);

                }
            }else{

            }
        },
    });
}

</script>
@endsection
