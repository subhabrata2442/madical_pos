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
				<h4>{{$data['supplier']->company_name}} Credit List</h4>
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
                            <th>Amount</th>
                            <th>Date</th>
                        </thead>
                        <tbody>
                            @foreach ($data['purchaseInwardStock'] as $key=>$item)
                                <tr>
                                    <td>{{($key+1)}}</td>
                                    <td>{{number_format($item->qty_total_net_price)}}</td>
                                    <td>{{$item->payment_date}}</td>
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

</script>
@endsection
