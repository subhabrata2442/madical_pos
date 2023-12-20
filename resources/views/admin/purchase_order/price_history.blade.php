@extends('layouts.admin')
@section('admin-content')
@php
$user_role=Auth::user()->role;
@endphp

<div class="row">
	<div class="col-12">
		<div class="card">
			<x-alert />
			<div class="table-responsive dataTable-design">
				<table id="product_list" class="table table-bordered">
					<thead>
						<th>Barcode</th>
						<th>Brand Name</th>
						{{-- <th>Product Name</th> --}}
						<th>Dosage Form</th>
						<th>Company Name</th>
						<th>No per package</th>
						<th>Selling by</th>
						<th>Is Chronic</th>
						<th>Action</th>
					</thead>
					<tbody>
						@forelse ($data['products'] as $product)
						<tr>
							<td>{{$product->product_barcode}}</td>
							<td>{{$product->brand}}</td>
							{{-- <td>{{$product->product_name}}</td> --}}
							<td>{{$product->dosage_name}}</td>
							<td>{{$product->company_name}}</td>
							<td>{{$product->no_package}}</td>
							<td>{{$product->selling_by_name}}</td>
							<td>{{ ucfirst(trans($product->is_chronic)) }}</td>
							{{-- <td>{{$product->drugstore_name}}</td> --}}
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
										<a class="dropdown-item" href="javascript:void(0)" onclick="priceHistory('{{$product->id}}')">Price History</a>
									</div>
								</div>
							</td>
						</tr>
						@empty
						<tr>
							<td colspan="6"> No data found </td>
						</tr>
						@endforelse
					</tbody>
				</table>
				{{ $data['products']->appends($_GET)->links() }}
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
                    <th>Price</th>
                    <th>Purchase Date</th>
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

<script>
    function priceHistory(product_id){
        $.ajax({
            url: "{{url('admin/purchase/pricehistory_product')}}/"+product_id,
            type: "get",
            data: {
                product_id: product_id,
                _token: "<?php echo csrf_token(); ?>",
            },
            dataType: "json",
            success: function(response) {
                if (response.status == 1) {
                    $("#modal_paymenthistory").modal('show');
                    $(".paymentHistory").html(response.html);
                    $(".appendtitle").html(response.product_name);
                }else{

                }
            },
        });
    }
</script>

@endsection
