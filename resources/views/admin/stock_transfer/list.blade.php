@extends('layouts.admin')
@section('admin-content')
<style>
  .content-header {
    display: none !important;
  }

  .custom-table table tbody tr:nth-child(odd) {
    background: #f5f5f5;
  }

  .custom-table table tbody tr:nth-child(even) {
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

  .reset-btn {
    background-color: #d3681b;
  }

  .swal2-title {

    font-size: 1.3em;

  }
</style>
@php
$adminId = Session::get('adminId');
$adminRoll = Session::get('admin_type');
@endphp

<div class="srcBtnWrap">
  <div class="card">
    <div class="row align-items-center justify-content-between">
      <div class="col-auto">
        <h4>Stock Transfer</h4>
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
              <option value="{{$store->id}}" @php if(isset($_GET['store_id'])){if($_GET['store_id']==$store->id){ echo
                'selected'; }} @endphp >{{$store->name}}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      @endif
      <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="form-group">
          <label for="customer_last_name" class="form-label">By Product Name</label>
          <div class="position-relative">
            <input type="text" class="form-control" id="search_product" name="product"
              value="{{request()->input('product')}}" autocomplete="off">
            <ul id="search_product_list" class="auto_search_result">
            </ul>
          </div>
          <input type="hidden" name="product_id" id="product_id" value="{{request()->input('product_id')}}">
        </div>
      </div>
      <div class="col-12">
        <ul class="saveSrcArea d-flex align-items-center justify-content-center mb-2">
          <li> <a href="javascript:?" class="saveBtn-2 reset-btn" id="reset">Reset</i></a> </li>
          <li>
            <button class="saveBtn-2" type="submit">Search <i class="fas fa-arrow-circle-right"></i></button>
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
            <th scope="col">Barcode</th>
            <th scope="col">The Brand</th>
            <th scope="col">Dosage Form</th>
            <th scope="col">Company</th>
            <th scope="col">Drugstore name</th>
            <th scope="col">Total Quantity</th>
            <th scope="col">Total Req Quantity</th>
            <th scope="col">Action</th>
          </thead>
          <tbody>
            @forelse ($data['stock_product'] as $product_stock)
            @php
            $pending_r_qty =
            \App\Models\BranchStockRequest::where('stock_id',$product_stock->id)->where('from_store_id',$product_stock->branch_id)->where('status',1)->sum('r_qty');
            @endphp
            <tr>
              <td>{{$product_stock->product->product_barcode}}</td>
              <td>{{$product_stock->product->brand}}</td>
              <td>{{$product_stock->product->dosage_name}}</td>
              <td>{{$product_stock->product->company_name}}</td>
              <td>{{$product_stock->product->drugstore_name}}</td>
              <td>{{$product_stock->t_qty}}</td>
              <td>{{$pending_r_qty}}
                @if($pending_r_qty>0)
                <a class="btn btn-info btn-sm view_btn" data-stock_id="{{$product_stock->id}}" data-store_id="{{$product_stock->branch_id}}" href="javascript:;"><i class="fas fa-eye"></i></a>
                @endif
              </td>
              <td><a href="javascript:;" class="exchange_btn" data-stock_id="{{$product_stock->id}}"
                  data-price_id="{{$product_stock->product_mrp}}" data-t_qty="{{@$product_stock->t_qty}}"
                  data-store_id="{{$product_stock->branch_id}}" data-pending_r_qty="{{$pending_r_qty}}"><i
                    class="fas fa-exchange-alt"></i></a></td>

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
<div class="modal fade modalMdHeader" id="modal-applyExchange" tabindex="-1" aria-labelledby="modal-1Label"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-1Label">Stock Tranfer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="applyCouponBox">
          <form action="{{ route('admin.purchase.stock.transfer') }}" method="post" id="applyExchange-form">
            @csrf

            <input type="hidden" id="original_t_qty" name="prev_t_qty" />
            <input type="hidden" id="stock_id" name="stock_id" />
            <input type="hidden" id="input-store_id" name="store_id" />

            <div class="mb-3 d-flex align-items-center">
              <div class="modal-body-sub-head1">
                <h6>Total available stock: <strong id="prev_t_qty_label">0</strong></h6>
              </div>
              <div>
                <h6>Pending Stock Request : <strong id="pending_t_qty_label">0</strong></h6>
              </div>
            </div>

            <div class="mb-3">
              <label for="" class="form-label">Request Stock:</label>
              <input type="number" class="form-control" id="t_qty-input" name="t_qty" required />
            </div>

            <div class="mb-3">
              <label for="" class="form-label"> Store:</label>
              <select class="form-control custom-select form-control-select" id="req_store_id" name="req_store_id"
                required="required">
                <option value="">Select Store</option>
                @foreach($data['store'] as $store)
                
                <option value="{{$store->id}}" @php if(isset($_GET['store_id'])){if($_GET['store_id']==$store->id){ echo
                  'disabled'; }} @endphp >{{$store->name}}</option>
                @endforeach
              </select>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
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
  $(".toggleCard").css("display", "block");
  $('.searchDropBtn').on("click", function() {
    $(".toggleCard").slideToggle();
  });

  //Product List
	$("#search_product").keyup(function() {
    var search = $(this).val();
    if (search != "") {
        $.ajax({
            url: prop.ajaxurl,
            type: 'post',
            data: {
                search: search,
                action: "getProductByKeyup",
                _token: prop.csrf_token,
            },
            dataType: 'json',
            success: function(response) {
                var len = response.result.length;
                $("#search_product_list").empty();
                for (var i = 0; i < len; i++) {
                    var id = response.result[i]['id'];
                    var name = response.result[i]['brand'] + ' / ' + response.result[i]['product_barcode'];
                    $("#search_product_list").append("<li value='" + id + "'>" + name + "</li>");
                }
                // binding click event to li
                $("#search_product_list li").bind("click", function() {
                    var cname = $(this).text();
                    console.log(cname);
                    var cid = $(this).val();
                    $('#search_product').val(cname);
                    $('#product_id').val(cid);
                    $("#search_product_list").empty();
                });
            }
        });
    }
});

  
  $(document).on('click', '.view_btn', function() {
    var store_id = $(this).data('store_id');
    var stock_id = $(this).data('stock_id');

    $.ajax({
      url: prop.ajaxurl,
      type: "post",
      data: {
        store_id: store_id,
        stock_id: stock_id,
        action: "requested_stock",
        _token: prop.csrf_token,
      },
      dataType: "json",
      success: function (response) {
        var swal_html = '<div class="card-footer p-0"><ul class="nav flex-column">';
        if(response.result.length>0){
          for(var i=0;response.result.length>i;i++){
            swal_html += '<li class="nav-item"><a href="javascript:;" class="nav-link">'+response.result[i]['store_name']+' <span class="float-right badge bg-success">'+response.result[i]['t_qty']+'</span></a></li>';
          }
        }
        swal_html += '</ul></div>';
        Swal.fire({title:"Stock Pending Request!", html: swal_html});
      },
  });

    //var swal_html = '<div class="card-footer p-0"><ul class="nav flex-column"><li class="nav-item"><a href="#" class="nav-link">Projects <span class="float-right badge bg-primary">31</span></a></li><li class="nav-item"><a href="#" class="nav-link">Tasks <span class="float-right badge bg-info">5</span></a></li><li class="nav-item"><a href="#" class="nav-link">Completed Projects <span class="float-right badge bg-success">12</span></a></li><li class="nav-item"><a href="#" class="nav-link">Followers <span class="float-right badge bg-danger">842</span></a></li></ul></div>';
    
    


  });




  $(document).on('keyup', '.update_c_qty', function() {
    var total_qty = 0;
    var original_w_qty = $('#original_t_qty').val();
    var input_qty = $(this).val();
    if (Number(original_w_qty) < input_qty) {
      $(this).val('');
      $('#left_t_qty_label').text(original_w_qty);
      toastr.error("Entered Qty should not be greater than Stock");
      return false;
    } else {
      var total_qty = 0
      $(".update_c_qty").each(function() {
        var c_qty = $(this).val();
        if (c_qty != '') {
          total_qty += parseInt(c_qty);
        }
      });
      var new_w_qty = parseInt(original_w_qty) - parseInt(total_qty);
      $('#left_t_qty_label').text(new_w_qty);
    }
  });
  $(document).on('click', '.exchange_btn', function() {
    var stock_id = $(this).data('stock_id');
    var price_id = $(this).data('price_id');
    var t_qty = $(this).data('t_qty');
    var store_id = $(this).data('store_id');
    var pending_r_qty = $(this).data('pending_r_qty');
    $('#prev_t_qty_label').text(t_qty);
    $('#pending_t_qty_label').text(pending_r_qty);
    $('#input-store_id').val(store_id);
    $('#stock_id').val(stock_id);
    $('#t_qty-input').val('');
    $('#original_t_qty').val(t_qty);
    //$('#c_qty-input').val(c_qty);
    var total_qty = 0;
    total_qty += parseInt(t_qty);
    //if (total_qty > 0) {
    //$('#modal-applyExchange').modal('show');
    //} else {
    //toastr.error("Don't have enough stock to transfer");
    //}
    $('#modal-applyExchange').modal('show');
  });
  $(document).ready(function() {
    $("#applyExchange-form").validate({
      rules: {},
      messages: {},
      errorElement: "em",
      errorPlacement: function(error, element) {
        // Add the `help-block` class to the error element
        error.addClass("help-block");
        error.insertAfter(element);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass("has-error").removeClass("has-success");
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).addClass("has-success").removeClass("has-error");
      },
      submitHandler: function(form) {
        var t_qty = $('#t_qty-input').val();
        var store_name = $("#req_store_id option:selected").text();
        if (t_qty != 0) {
          if (t_qty < 0) {
            toastr.error("Request Stock Should not be 0");
          } else {
            var title = 'Do you want to send stock request ' + t_qty + ' Qty  From ' + store_name + '?';
            Swal.fire({
              title: title,
              showDenyButton: true,
              showCancelButton: false,
              confirmButtonText: 'Save',
              denyButtonText: 'Don\'t save',
            }).then((result) => {
              if (result.isConfirmed) {
                var action_url = document.getElementById("applyExchange-form").action;
                $.ajax({
                  url: action_url,
                  type: 'post',
                  data: $(form).serializeArray(),
                  dataType: 'json',
                  success: function(response) {
                    $('#modal-applyExchange').modal('hide');
                    Swal.fire({
                      title: 'Stock tranfer request successfully submitted.',
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
                });
              } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
              }
            });
          }
        } else {
          toastr.error("Enter Stock!");
        }
      }
    });
  });
</script>


@endsection