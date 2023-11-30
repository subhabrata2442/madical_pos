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
				<h4>Low stock product</h4>
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

            @if (Auth::user()->role == 1)
            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
				<div class="form-group">
					<label for="customer_last_name" class="form-label">Select Store</label>
					<div class="position-relative">
						<select name="branch_id" id="branch_id" class="form-control form-inputtext">
                            <option value="">Select Store</option>
                            @foreach ($data['storelist'] as $key=>$storeitem)
                                <option value="{{$storeitem->id}}" @if(isset($_GET['branch_id'])) @if($_GET['branch_id']==$storeitem->id) selected @endif @endif>{{$storeitem->name}}</option>
                            @endforeach
                        </select>
					</div>

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
                    <li>
                        @php
                            $branch_id = '';
                            if (isset($_GET['branch_id'])) {
                                $branch_id = $_GET['branch_id'];
                            }
                        @endphp
						<a href="{{ url('admin/report/low_stock_product_download?branch_id='.$branch_id) }}" class="btn btn-primary">Download Excel</a>
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
      <div class="table-responsive custom-table">
        <table id="" class="table table-bordered text-nowrap">
			<thead>
				<th scope="col">Sl NO.</th>
                <th scope="col">Brand</th>
				{{-- <th scope="col">Product Name</th> --}}
				<th scope="col">In Stock</th>

				<th scope="col">Product Barcode</th>
				<th scope="col">Dosage</th>
				<th scope="col">Selling By</th>
			</thead>
			<tbody>
				@forelse ($data['low_stock'] as $key=>$purchase)
                    @if($purchase->t_qty <= $purchase->product->alert_product_qty)
                        <tr>
                            <td>{{($key+1)}}</td>
                            <td>{{$purchase->product->brand}}</td>
                            {{-- <td>{{$purchase->product->product_name}}</td> --}}
                            <td>{{$purchase->t_qty}}</td>
                            <td>{{$purchase->product->product_barcode}}</td>
                            <td>{{$purchase->product->dosage_name}}</td>
                            <td>{{$purchase->product->selling_by_name}}</td>
                        </tr>
                    @endif
				@empty
					<tr ><td colspan="6"> No data found </td></tr>
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
    });
</script>

@endsection
