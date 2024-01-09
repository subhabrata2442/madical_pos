@extends('layouts.admin')
@section('admin-content')
<div class="row">
  <div class="col-12">
    <div class="card">
        <form action="" method="get" id="filter">
            <div class="row">
                {{-- <div class="col-lg-3 col-md-3 col-sm-12 col-12">
                    <div class="form-group">
                        <label for="customer_last_name" class="form-label">Product Name</label>
                        <div class="position-relative">
                            <input type="text" class="form-control" id="search_product" name="product_name"
                                value="{{request()->input('product_name')}}" autocomplete="off">
                        </div>
                    </div>
                </div> --}}

                <div class="col-lg-3 col-md-3 col-sm-12 col-12">
                    <div class="form-group">
                        <label for="customer_name" class="form-label">Name</label>
                        <div class="position-relative">
                            <input type="text" class="form-control" id="customer_name" name="customer_name"
                                value="{{request()->input('customer_name')}}" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-12">
                    <div class="form-group">
                        <label for="customer_mobile" class="form-label">Mobile No.</label>
                        <div class="position-relative">
                            <input type="text" class="form-control" id="customer_mobile" name="customer_mobile"
                                value="{{request()->input('customer_mobile')}}" autocomplete="off">
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-12 col-12">
                    <label for="" class="form-label">Search</label>
                    <ul class="saveSrcArea">
                        {{-- <li> <a href="javascript:?" class="saveBtn-2 reset-btn" id="reset">Reset</i></a> </li> --}}
                        <li>
                            <button class="saveBtn-2" type="submit">Search <i
                                    class="fas fa-arrow-circle-right"></i></button>
                        </li>
                    </ul>
                </div>
            </div>
        </form>

    </div>
    <div class="card">
      <x-alert />
      <div class="table-responsive dataTable-design">
        <table id="customer_list" class="table table-bordered">
          <thead>
          	<th>Name</th>
            <th>Mobile No.</th>
           </thead>
          <tbody>
            @foreach ($data['customer_list'] as $item)
                <tr>
                    <td>{{$item->customer_name}}</td>
                    <td>{{$item->customer_mobile}}</td>
                </tr>
            @endforeach
          </tbody>
        </table>
        {{ $data['customer_list']->appends($_GET)->links() }}
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
{{-- <script type="text/javascript">
$(function() {

	var table = $('#customer_list').DataTable({
		processing: true,
		serverSide: true,
		searchDelay: 350,
		ajax: "{{ route('admin.customer.list') }}",
		columns: [
			{
				data: 'customer_fname',
				name: 'customer_fname',
			},
			{
				data: 'customer_email',
				name: 'customer_email'
			},
			{
				data: 'customer_mobile',
				name: 'customer_mobile'
			},
			{
				data: 'gender',
				name: 'gender',
			},
			{
				data: 'customer_gstin',
				name: 'customer_gstin'
			},
			{
				data: 'date_of_birth',
				name: 'date_of_birth'
			},
			{
				data: 'action',
				name: 'action',
				orderable: false,
				searchable: false
			},

		]
	});

});
</script> --}}
@endsection
