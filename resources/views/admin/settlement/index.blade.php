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
				<h4>Settlement</h4>
			</div>
			<div class="col d-flex invoiceAmout justify-content-center">

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
            @if (Auth::user()->role == 1)
                <div class="col-lg-3 col-md-3 col-sm-12 col-12">
                    <div class="form-group">
                        <label for="" class="form-label">Select Store</label>
                        <select class="form-control custom-select form-control-select" id="" name="store_id">
                            <option value="">Select Store</option>
                            @forelse ($data['storelist'] as $store)
                                <option value="{{$store->id}}" {{request()->input('store_id') == $store->id ? 'selected' : ''}}>{{$store->name}}</option>
                            @empty

                            @endforelse
                        </select>
                    </div>
                </div>
            @endif

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
							</select>
						</div>
						<div>
							<button type="button" id="download_report" class="srcBtnWrapGo"><i class="fas fa-download"></i></button>
						</div>
					</li> --}}

                    {{-- <li>
                        @php
                            $store_id = '';
                            if (isset($_GET['store_id'])) {
                                $store_id = $_GET['store_id'];
                            }
                        @endphp
						<a href="{{ url('admin/report/near_expiry_stock_download?store_id='.$store_id) }}" class="btn btn-primary">Download Excel</a>
					</li> --}}
				</ul>
			</div>
		</div>
	</form>
</div>


<div class="row">
  <div class="col-12">
    <div class="card">
      <x-alert />
      <div class="table-responsive custom-table">
        <table id="" class="table table-bordered text-nowrap">
			<thead>
				<th scope="col">Sl NO.</th>
				<th scope="col">Store</th>
				<th scope="col">Note</th>
				<th scope="col">Total amount</th>
				<th scope="col">Date</th>
				<th scope="col">Status</th>
				<th scope="col">Action</th>
			</thead>
			<tbody>
				@forelse ($data['all_settlement'] as $key=>$purchase)
                    <tr>
                        <td>{{($key+1)}}</td>
                        <td>{{$purchase->user->name}}</td>
                        <td>
                            @if($purchase->note_amount!='')
                                @php
                                    $note_amount = json_decode($purchase->note_amount);
                                    // print_r($note_amount);
                                @endphp
                                @foreach ($note_amount as  $item)
                                    @foreach ($item as $key=>$items)
                                            {{$key}} *
                                            @if ($items!='')
                                                {{$items}}
                                            @else
                                                0
                                            @endif
                                                = {{$key*$items}}
                                            @php
                                                echo "<br>";
                                            @endphp
                                    @endforeach
                                @endforeach
                            @endif
                        </td>
                        <td>{{number_format($purchase->total_settlement_amount)}}</td>
                        <td>{{date('d-m-Y', strtotime(str_replace('.', '/', $purchase->created_at)))}}</td>
                        <td>
                            @if ($purchase->admin_approved==0)
                                Not approve
                            @else
                                Approved
                            @endif
                        </td>
                        <td>
                            @if (Auth::user()->role == 1)
                                @if ($purchase->admin_approved==0)
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
                                            <a class="dropdown-item" href="{{ route('admin.settlement_approve', [base64_encode($purchase->id)])}}">Approve</a>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </td>
                    </tr>
				@empty
					<tr><td colspan="4"> No data found </td></tr>
				@endforelse

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
        $('.searchDropBtn').on("click",function(){
            $(".toggleCard").slideToggle();
        })



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



});



</script>

@endsection
