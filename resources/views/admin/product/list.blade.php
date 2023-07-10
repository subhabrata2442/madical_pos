@extends('layouts.admin')
@section('admin-content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <x-alert />
      {{-- <form method="post" action="{{ route('admin.product.product_upload') }}" class="needs-validation" id="product_upload-form" novalidate enctype="multipart/form-data">
        @csrf
        <div class="col-md-6">
          <div class="form-group">
            <div class="upload-btn file-upload">
              <label for="product_upload" class="custom-file-upload fileInfo file-drop">Upload </label>
              <input id="product_upload_file" type="file" name="product_upload_file">
            </div>
          </div>
        </div>
      </form> --}}

      <div class="table-responsive dataTable-design">
        <table id="product_list" class="table table-bordered">
          <thead>
            <th>Brand Name</th>
            <th>Dosage Form</th>
            <th>Company Name</th>
            <th>Drugstore Name</th>
            <th>MRP</th>
            <th>Stock QTY</th>
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

$(document).on('change','#product_upload_file',function(){
	this.form.submit();
});

$(document).on('change','#bar_product_upload_file',function(){
	this.form.submit();
});

$(document).on('change','#product_stock_upload_file',function(){
	this.form.submit();
});

$(function() {

	var table = $('#product_list').DataTable({
		processing: true,
		serverSide: true,
		searchDelay: 350,
		ajax: "{{ route('admin.product.list') }}",
		columns: [
			{
				data: 'brand',
				name: 'brand'
			},	
			{
				data: 'dosage_name',
				name: 'dosage_name'
			},
			{
				data: 'company_name',
				name: 'company_name',
			},
			{
				data: 'drugstore_name',
				name: 'drugstore_name'
			},
			{
				data: 'mrp',
				name: 'mrp'
			},
			{
				data: 'qty',
				name: 'qty'
			}
		]
	});

});
</script> 
@endsection 