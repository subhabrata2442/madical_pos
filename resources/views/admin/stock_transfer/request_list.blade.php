@extends('layouts.admin')
@section('admin-content')
@php
$adminId = Session::get('adminId');
$adminRoll = Session::get('admin_type');
@endphp

<div class="srcBtnWrap">
    <div class="card">
        <div class="row align-items-center justify-content-between">
            <div class="col-auto">
                <h4>Stock Transfer Request</h4>
            </div>

        </div>
    </div>
</div>
<div class="card">
    <form action="" method="get" id="filter">
        <div class="row">
            @if($adminRoll==1)
            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                    <label for="customer_last_name" class="form-label">Store</label>
                    <div class="position-relative">
                        <select class="form-control custom-select form-control-select" id="store_id" name="store_id"
                            required="required">
                            <option value="">Select Store</option>
                            @foreach($data['store'] as $store)
                            <option value="{{$store->id}}" @php
                                if(isset($_GET['store_id'])){if($_GET['store_id']==$store->id){ echo
                                'selected'; }} @endphp >{{$store->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <ul class="saveSrcArea d-flex align-items-center justify-content-center mb-2">
                    <li> <a href="javascript:?" class="saveBtn-2 reset-btn" id="reset">Reset</i></a> </li>
                    <li>
                        <button class="saveBtn-2" type="submit">Search <i
                                class="fas fa-arrow-circle-right"></i></button>
                    </li>
                </ul>
            </div>
            @endif

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
                        <th scope="col">Barcode</th>
                        <th scope="col">The Brand</th>
                        <th scope="col">Dosage Form</th>
                        <th scope="col">Company</th>
                        {{-- <th scope="col">Drugstore name</th> --}}
                        <th scope="col">Request From</th>
                        <th scope="col">Total Req Quantity</th>
                        <th scope="col">Action</th>
                    </thead>
                    <tbody>
                        @forelse ($data['stock_product'] as $product_stock)
                        <tr>
                            <td>{{$product_stock->product->product_barcode}}</td>
                            <td>{{$product_stock->product->brand}}</td>
                            <td>{{$product_stock->product->dosage_name}}</td>
                            <td>{{$product_stock->product->company_name}}</td>
                            {{-- <td>{{$product_stock->product->drugstore_name}}</td> --}}
                            <td>{{$product_stock->store->name}} </br>
                                {{$product_stock->store->email}}</br>
                                {{$product_stock->store->phone}}</br>
                            </td>
                            <td>{{$product_stock->r_qty}}</td>
                            <td>
                                <a href="javascript:;" class="btn btn-success btn-block accept_btn"
                                    data-stock_id="{{$product_stock->id}}"><i class="fa fa-check"></i> Accept</a>
                                <a href="javascript:;" class="btn btn-danger btn-block reject_btn"
                                    data-stock_id="{{$product_stock->id}}"><i class="fa fa-ban"></i> Reject</a>

                            </td>
                        </tr>
                        @endforeach

                    </tbody>

                </table>
                @if(count($data['stock_product'])>0)
                {{ $data['stock_product']->appends($_GET)->links() }}
                @endif
            </div>
        </div>
    </div>
</div>

<div style="display: none;">
    <textarea class="ck-editor" style="display: none;"></textarea>
</div>

@endsection

@section('scripts')
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script>
    $(document).on('click', '.accept_btn', function() {
        var stock_id = $(this).data('stock_id');
        var title = 'Do you want to accept stock request ?';
        Swal.fire({
            title: title,
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'Save',
            denyButtonText: 'Don\'t save',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: prop.ajaxurl,
                    type: 'post',
                    data: {
                        stock_id: stock_id,
                        action: "requested_stock_accept",
                        _token: prop.csrf_token,
                    },
                    dataType: 'json',
                    success: function(response) {
                        if(response.status==0){
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.msg,
                                });

                        }else{
                            Swal.fire({
                            title: 'Stock tranfer request successfully accepted.',
                            icon: 'success',
                            showDenyButton: false,
                            showCancelButton: false,
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            } else if (result.isDenied) {}
                        });

                        }
                        
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        });
    });

    $(document).on('click', '.reject_btn', function() {
        var stock_id = $(this).data('stock_id');
        var title = 'Do you want to reject stock request ?';
        Swal.fire({
            title: title,
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'Save',
            denyButtonText: 'Don\'t save',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: prop.ajaxurl,
                    type: 'post',
                    data: {
                        stock_id: stock_id,
                        action: "requested_stock_reject",
                        _token: prop.csrf_token,
                    },
                    dataType: 'json',
                    success: function(response) {
                        if(response.status==0){
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.msg,
                                });

                        }else{
                            Swal.fire({
                            title: 'Stock tranfer request rejected.',
                            icon: 'error',
                            showDenyButton: false,
                            showCancelButton: false,
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            } else if (result.isDenied) {}
                        });

                        }
                        
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        });
    });


</script>

@endsection