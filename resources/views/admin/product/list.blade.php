@extends('layouts.admin')
@section('admin-content')
@php
$user_role=Auth::user()->role;
@endphp
<div class="card">
	<form action="" method="get" id="filter">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-12 col-12">
				<div class="form-group">
					<label for="customer_last_name" class="form-label">Product Name</label>
					<div class="position-relative">
						<input type="text" class="form-control" id="search_product" name="product_name"
							value="{{request()->input('product_name')}}" autocomplete="off">
					</div>
				</div>
			</div>

			<div class="col-lg-3 col-md-3 col-sm-12 col-12">
				<div class="form-group">
					<label for="product_barcode" class="form-label">Barcode</label>
					<div class="position-relative">
						<input type="text" class="form-control" id="product_barcode" name="product_barcode"
							value="{{request()->input('product_barcode')}}" autocomplete="off">
					</div>
				</div>
			</div>

			<div class="col-12">
				<ul class="saveSrcArea d-flex align-items-center justify-content-center mb-2">
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
<div class="row">
	<div class="col-12">
		<div class="card">
			<x-alert />

			<div class="table-responsive dataTable-design">
				<table id="product_list" class="table table-bordered">
					<thead>
						<th>Barcode</th>
						<th>Brand Name</th>
						<th>Product Name</th>
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
							<td>{{$product->product_name}}</td>
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
										<a class="dropdown-item"
											href="{{ route('admin.product.edit', [base64_encode($product->id)])}}">Edit</a>
										<a class="dropdown-item delete-item" href="javascript:;" id="delete_product"
											data-url="{{route('admin.product.delete', [base64_encode($product->id)]) }}">Delete</a>
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
@endsection

@section('scripts')

@endsection