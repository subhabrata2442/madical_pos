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
				<h4>All Notifications</h4>
			</div>
		</div>
	</div>
</div>
<div class="row">
  <div class="col-12">
    <div class="card">
      <x-alert />
      <div class="table-responsive custom-table">
        <table id="" class="table table-bordered text-nowrap">
			<thead>
				<th scope="col">Sl NO.</th>
                {{-- <th scope="col">Store</th> --}}
                <th scope="col">Message</th>
				<th scope="col">Date</th>
				{{-- <th scope="col">Action</th> --}}
			</thead>
			<tbody>
				@forelse ($data['notification'] as $key=>$notification)
				<tr>

					<td>{{($key+1)}}</td>
                    {{-- <td>{{$notification->user->name}}</td> --}}
                    <td>
                        @if($notification->is_seen==1)
                            <i class="fas fa-envelope-open-text"></i>
                        @else
                            <i class="fas fa-envelope mr-2"></i>
                        @endif
                        <a href="{{url($notification->urls)}}" onclick="seenNotification('{{$notification->id}}')" style="color:#324148">{{$notification->msg}}</a>

                    </td>
					<td>{{ \Carbon\Carbon::parse($notification->created_at)}}</td>
                    {{-- <td>
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

                            @php
                                $urls = '';
                                if($notification->type=='stock-alert'){
                                    $urls = url('/').'/admin/report/low_stock_product?id='.$notification->branch_stock_id;
                                }else if($notification->type=='product-expiry'){
                                    $urls = url('/').'/admin/report/near_expiry_stock?id='.$notification->id;
                                }else if($notification->type=='stock-transfer'){
                                    $urls = url('/').'/admin/purchase/stock-transfer';
                                }
                            @endphp

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <a class="dropdown-item" href="{{$urls}}">View</a>
                            </div>
                        </div>
                    </td> --}}
				</tr>
				@empty
					<tr ><td colspan="4"> No data found </td></tr>
				@endforelse

			</tbody>
        </table>
        {{ $data['notification']->appends($_GET)->links() }}

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
    });
</script>

@endsection



