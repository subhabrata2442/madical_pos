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
				<h4>Top selling products</h4>
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
				<th scope="col">Product Name</th>
				<th scope="col">In Stock</th>
				<th scope="col">Brand Name</th>
				<th scope="col">Product Barcode</th>
				<th scope="col">Dosage</th>
				<th scope="col">Selling By</th>
			</thead>
			<tbody>
				@forelse ($data['top_products'] as $key=>$purchase)
				<tr>
					
					<td>{{($key+1)}}</td>
					<td>{{$purchase['product_name']}}</td>
					<td>{{$purchase['t_qty']}}</td>
					<td>{{$purchase['brand']}}</td>
					<td>{{$purchase['product_barcode']}}</td>
					<td>{{$purchase['dosage_name']}}</td>
					<td>{{$purchase['selling_by_name']}}</td>
				</tr>
				@empty
					<tr ><td colspan="3"> No data found </td></tr>
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

@endsection 