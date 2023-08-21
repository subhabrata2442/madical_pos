@extends('layouts.admin')
@section('admin-content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <x-alert />
      <div class="table-responsive dataTable-design">
        <table id="stock_product" class="table table-bordered">
          <thead>
            <th>Barcode</th>
            <th>Brand Name</th>
            <th>Product Name</th>
            <th>Dosage Form</th>
            <th>Company</th>
            <th>Drugstore name</th>
            <th>Total Quantity</th>
            <th>No per package</th>
            <th>Net Price</th>
            <th>Price</th>
            <th>Bonous</th>
            <th>US/IQ rate</th>
            <th>Sell Price</th>
            <th>Profit</th>
            <th>Actual % of profit</th>
            <th>Is Chronic</th>
           </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts') 
<script type="text/javascript">

$(function() {
	var table = $('#stock_product').DataTable({
		processing: true,
		serverSide: true,
		searchDelay: 350,
		ajax: "{{ route('admin.report.stock_product.list',$data['inward_stock_id'])}}",
		columns: [
			{
				data: 'barcode',
				name: 'barcode'
			},	
			{
				data: 'brand',
				name: 'brand'
			},	
			{
				data: 'product_name',
				name: 'product_name'
			},	
			{
				data: 'dosage',
				name: 'dosage'
			},	
			{
				data: 'company',
				name: 'company'
			},	
			{
				data: 'drugstore',
				name: 'drugstore'
			},	
			{
				data: 'product_qty',
				name: 'product_qty'
			},	
			{
				data: 'no_package',
				name: 'no_package'
			},	
			{
				data: 'net_price',
				name: 'net_price'
			},	
			{
				data: 'product_mrp',
				name: 'product_mrp'
			},	
			{
				data: 'bonous',
				name: 'bonous'
			},	
			{
				data: 'cost_rate',
				name: 'cost_rate'
			},	
			{
				data: 'selling_price',
				name: 'selling_price'
			},	
			{
				data: 'profit_amount',
				name: 'profit_amount'
			},	
			{
				data: 'profit_percent',
				name: 'profit_percent'
			},
			{
				data: 'is_chronic',
				name: 'is_chronic'
			},
		]
	});

});
</script> 
@endsection 