@extends('layouts.admin_dashboard')
@section('mainContent')

@php
$branch_id 			= Session::get('branch_id');
$company_name		= App\Models\Common::get_user_settings($where=['option_name'=>'company_name'],$branch_id);
$company_address	= App\Models\Common::get_user_settings($where=['option_name'=>'company_address'],$branch_id);
$is_branch 			= Session::get('is_branch');


//print_r($company_name);exit;

@endphp

@php
$adminId 			= Session::get('adminId');
$adminRoll 		= Session::get('admin_type');


$permission=array();
$rolePermissionResult		= App\Models\UserRolePermission::where('user_id',$adminId)->orderBy('id', 'asc')->get();

foreach($rolePermissionResult as $row){
	$permission[]=$row->role_id;
}
$page_permission=array();

if($adminId!=1){
  $roleWisePermissionResult		= App\Models\RoleWisePermission::where('branch_id',$adminId)->orderBy('id', 'asc')->get();
  foreach($roleWisePermissionResult as $row){
    $page_permission[]=$row->get_slug->slug;
  }
}else{
  $roleWisePermissionResult		= App\Models\RoleSubPermission::get();
  foreach($roleWisePermissionResult as $row){
    $page_permission[]=$row->slug;
  }

}

//echo '<pre>';print_r($page_permission);exit;


@endphp
<header>
  <div class="container-fluid p-h-40">
    <div class="row justify-content-between align-items-center">
      <div class="col-auto logo"> {{$company_name}}<small>{{$company_address}}</small> </div>
      <div class="col-auto logOut"> <a href="{{ route('admin.auth.logout') }}"><i class="fas fa-lock"></i>Logout</a> </div>
    </div>
  </div>
</header>
<section class="artReleases">
  <div class="container-fluid p-h-40">
    <div class="row">
      <div class="col-lg-5 col-md-5 col-sm-7 col-12">
        <div class="posCarouselArea">
          <div class="owl-carousel owl-theme posCarousel">
            <div class="item">
              <div class="pcInner">
                <h4>Today's</h4>
                <div class="pcInnerTop w-100">
                  <ul class="d-flex">
                    <li style="background: #4e50df;"><span>{{$data['totalSalesToday']}}</span>Sale $</li>
                    <li style="background: #5c74dd;"><span>{{$data['totalProfitToday']}}</span>Net Profit $</li>
                    {{-- <li style="background: #506aad;"><span>0.00</span>Sale Return ₹</li> --}}
                  </ul>
                </div>
                {{-- <div class="pcInnerBtm w-100">
                  <div class="pcInnerBtmInner row row-cols-3">
                    <div class="col pcInnerBtmBox">
                      <div class="pibbInner"><span>Cash</span> 0</div>
                    </div>
                    <div class="col pcInnerBtmBox">
                      <div class="pibbInner"><span>Card</span> 0</div>
                    </div>
                    <div class="col pcInnerBtmBox">
                      <div class="pibbInner"><span>Cheque</span> 0</div>
                    </div>
                    <div class="col pcInnerBtmBox">
                      <div class="pibbInner"><span>Wallet</span> 0</div>
                    </div>
                    <div class="col pcInnerBtmBox">
                      <div class="pibbInner"><span>Unpaid Amt.</span> 0</div>
                    </div>
                    <div class="col pcInnerBtmBox">
                      <div class="pibbInner"><span>Net Banking</span> 0</div>
                    </div>
                    <div class="col pcInnerBtmBox">
                      <div class="pibbInner"><span>Credit Not</span> 0</div>
                    </div>
                    <div class="col pcInnerBtmBox">
                      <div class="pibbInner"><span>Debit Note</span> 0</div>
                    </div>
                  </div>
                </div> --}}
              </div>
              <div class="pcInner pciType-2">
                <h4>This Month</h4>
                <div class="pcInnerTop w-100">
                  <ul class="d-flex">
                    <li style="background: #e68b8b;"><span>{{$data['totalSalesthismonth']}}</span>Sale $</li>
                    <li style="background: #506aad;"><span>{{$data['totalProfitthismonth']}}</span>Net Profit $</li>
                    {{-- <li style="background: #ea9354;"><span>0.00</span>Sale Return $</li> --}}
                  </ul>
                </div>
                {{-- <div class="pcInnerBtm w-100">
                  <div class="pcInnerBtmInner row row-cols-3">
                    <div class="col pcInnerBtmBox">
                      <div class="pibbInner"><span>Cash</span> 0</div>
                    </div>
                    <div class="col pcInnerBtmBox">
                      <div class="pibbInner"><span>Card</span> 0</div>
                    </div>
                    <div class="col pcInnerBtmBox">
                      <div class="pibbInner"><span>Cheque</span> 0</div>
                    </div>
                    <div class="col pcInnerBtmBox">
                      <div class="pibbInner"><span>Wallet</span> 0</div>
                    </div>
                    <div class="col pcInnerBtmBox">
                      <div class="pibbInner"><span>Unpaid Amt.</span> 0</div>
                    </div>
                    <div class="col pcInnerBtmBox">
                      <div class="pibbInner"><span>Net Banking</span> 0</div>
                    </div>
                    <div class="col pcInnerBtmBox">
                      <div class="pibbInner"><span>Credit Not</span> 0</div>
                    </div>
                    <div class="col pcInnerBtmBox">
                      <div class="pibbInner"><span>Debit Note</span> 0</div>
                    </div>
                  </div>
                </div> --}}
              </div>
            </div>
            <div class="item">
              <div class="billArea">
                <h4>LATEST BILLS</h4>
                <div class="billBox">
                  <table class="table table-striped table-success">
                    <thead>
                      <tr>
                        <th scope="col">Bill No.</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['latestBill'] as $latestBillitem)
                            <tr>
                                <td>{{$latestBillitem->bill_no}}</td>
                                @if (!empty($latestBillitem->customer))
                                    <td>{{$latestBillitem->customer->customer_name}}</td>
                                @else
                                    <td>Walk in customer</td>
                                @endif

                                <td>{{$latestBillitem->total_qty}}</td>
                                <td>{{$latestBillitem->sub_total}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
                </div>
                <div class="billBox">
                  <h4>TOP SELLING PRODUCTS</h4>
                  <table class="table table-striped table-success">
                    <thead>
                      <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Brand</th>
                        <th scope="col">Barcode</th>
                        <th scope="col">In Stock</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['top_products'] as $top_productsitem)
                            <tr>
                                <td>{{$top_productsitem['product_name']}}</td>
                                <td>{{$top_productsitem['brand']}}</td>
                                <td>{{$top_productsitem['product_barcode']}}</td>
                                <td>{{$top_productsitem['t_qty']}}</td>
                            </tr>
                        @endforeach

                    </tbody>
                  </table>
                </div>
                <div class="billBox">
                  <h4>LOW / OUT OF STOCK PRODUCTS</h4>
                  <table class="table table-striped table-success">
                    <thead>
                      <tr>
                        <th scope="col">Product Name</th>
                        <th scope="col">Brand</th>
                        <th scope="col">Barcode</th>
                        <th scope="col">In Stock</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['low_stock'] as $low_stockitem)
                        <tr>
                            <td>{{$low_stockitem->product->product_name}}</td>
                            <td>{{$low_stockitem->product->brand}}</td>
                            <td>{{$low_stockitem->product->product_barcode}}</td>
                            <td>{{$low_stockitem->t_qty}}</td>
                        </tr>
                      @endforeach

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-7 col-md-7 col-sm-7 col-12">
        <div class="row metroBoxArea">
          <div class="col-lg-3 col-md-3 col-sm-4 col-12 "> <a href="{{ route('admin.store.list') }}" data-href="{{ route('admin.store.list') }}" data-is_branch="{{$is_branch}}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn" style="background: #198699;"><span>Stores</span><img src="{{ url('assets/admin/new/images/icon/3.svg') }}" alt=""/></a> </div>

          <div class="col-lg-2 col-md-2 col-sm-4 col-12"> <a href="{{ route('admin.supplier.list') }}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn" style="background: #910b95;"><span>Supplier</span><img src="{{ url('assets/admin/new/images/icon/9.svg') }}" alt=""/></a> </div>

          <div class="col-lg-2 col-md-2 col-sm-4 col-12"> <a href="{{ route('admin.brand.list') }}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn" style="background: #2e7fe6;"><span>Brands</span><img src="{{ url('assets/admin/new/images/icon/brand.svg') }}" alt=""/></a> </div>

          {{-- <div class="col-lg-2 col-md-2 col-sm-4 col-12"> <a href="{{ route('admin.purchase.inward_stock') }}"  data-href="{{ route('admin.purchase.inward_stock') }}" data-is_branch="{{$is_branch}}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn" style="background: #d64f35;"><span>Purchase</span><img src="{{ url('assets/admin/new/images/icon/6.svg') }}" alt=""/></a> </div> --}}
          <div class="col-lg-2 col-md-2 col-sm-4 col-12"> <a href="{{ route('admin.dosage.list') }}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn" style="background: #d64f35;"><span>Dosages</span><img src="{{ url('assets/admin/new/images/icon/dosage.svg') }}" alt=""/></a> </div>

          <div class="col-lg-3 col-md-3 col-sm-4 col-12"> <a href="{{ route('admin.company.list') }}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn " style="background: #e77272;"><span>Companies </span><img src="{{ url('assets/admin/new/images/icon/company.svg') }}" alt=""/></a> </div>

          {{-- <div class="col-lg-3 col-md-3 col-sm-4 col-12"> <a href="#"  data-href="#" data-is_branch="{{$is_branch}}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn" style="background: #f76ce1;"><span>Stock Transfer</span><img src="{{ url('assets/admin/new/images/icon/5.svg') }}" alt=""/></a> </div> --}}
          <div class="col-lg-2 col-md-2 col-sm-4 col-12"> <a href="{{ route('admin.product.list') }}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn" style="background: #f76ce1;"><span>Products</span><img src="{{ url('assets/admin/new/images/icon/5.svg') }}" alt=""/></a> </div>

          <div class="col-lg-2 col-md-2 col-sm-4 col-12"> <a href="{{ route('admin.purchase.inward_stock') }}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn" style="background: #b81249;"><span>Purchase</span><img src="{{ url('assets/admin/new/images/icon/6.svg') }}" alt=""/></a> </div>

        @if ($adminRoll == 1)
          <div class="col-lg-3 col-md-3 col-sm-4 col-12"> <a href="javascript:void(0)" class="metroBox d-flex align-items-center justify-content-center wow zoomIn disabled_home_btn" style="background: #203966;"><span>POS</span><img src="{{ url('assets/admin/new/images/icon/8.svg') }}" alt=""/></a> </div>
        @else
          <div class="col-lg-3 col-md-3 col-sm-4 col-12"> <a href="{{ route('admin.pos.pos_create') }}" data-href="{{ route('admin.pos.pos_create') }}" data-is_branch="{{$is_branch}}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn" style="background: #203966;"><span>POS</span><img src="{{ url('assets/admin/new/images/icon/8.svg') }}" alt=""/></a> </div>
        @endif

          <div class="col-lg-3 col-md-3 col-sm-4 col-12"> <a href="{{ route('admin.purchase.stock.transfer') }}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn" style="background: #910b95;"><span>Stock Transfer</span><img src="{{ url('assets/admin/new/images/icon/stock-transer.svg') }}" alt=""/></a> </div>

          <div class="col-lg-2 col-md-2 col-sm-4 col-12"> <a href="{{ route('admin.report.purchase.invoice_wise') }}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn" style="background: #e08300;"><span>Purchase Report</span><img src="{{ url('assets/admin/new/images/icon/11.svg') }}" alt=""/></a> </div>

          <div class="col-lg-3 col-md-3 col-sm-4 col-12"> <a href="{{ route('admin.report.purchase.product_wise') }}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn" style="background: #0f54b2;"><span>Product Wise Report</span><img src="{{ url('assets/admin/new/images/icon/productwise-report.svg') }}" alt=""/></a> </div>

          <div class="col-lg-2 col-md-2 col-sm-4 col-12"> <a href="{{ route('admin.report.sales.item') }}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn" style="background: #198699;"><span>Invoice Wise Sales</span><img src="{{ url('assets/admin/new/images/icon/invoice-wise-sales-report.svg') }}" alt=""/></a> </div>



          <div class="col-lg-2 col-md-2 col-sm-4 col-12"> <a href="{{ route('admin.report.sales.report.product.wise') }}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn" style="background: #506aad;"><span>Product Wise Sales</span><img src="{{ url('assets/admin/new/images/icon/12.svg') }}" alt=""/></a> </div>

          <div class="col-lg-2 col-md-2 col-sm-4 col-12"> <a href="{{ route('admin.report.top_selling_products') }}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn" style="background: #052d72;"><span>Top selling products</span><img src="{{ url('assets/admin/new/images/icon/top-selling-product.svg') }}" alt=""/></a> </div>

          <div class="col-lg-3 col-md-3 col-sm-4 col-12"> <a href="{{ route('admin.report.low_stock_product') }}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn" style="background: #8ca32f;"><span>Low stock product</span><img src="{{ url('assets/admin/new/images/icon/low-stock-product.svg') }}" alt=""/></a> </div>

          <div class="col-lg-2 col-md-2 col-sm-4 col-12"> <a href="{{ route('admin.report.zero_stock_product') }}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn" style="background: #e77272;" ><span>Zero stock product</span><img src="{{ url('assets/admin/new/images/icon/zero-stock-product.svg') }}" alt=""/></a> </div>
          <div class="col-lg-2 col-md-2 col-sm-4 col-12"> <a href="{{ route('admin.report.near_expiry_stock') }}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn" style="background: #2e7fe6;" ><span>Stock near expiry</span><img src="{{ url('assets/admin/new/images/icon/11.svg') }}" alt=""/></a> </div>
        @if ($adminRoll == 1)
            <div class="col-lg-3 col-md-3 col-sm-4 col-12"> <a href="{{ route('admin.expense.category') }}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn" style="background: #b81249;" ><span>Expense Category</span><img src="{{ url('assets/admin/new/images/icon/expenses-catagory.svg') }}" alt=""/></a> </div>
        @endif
          <div class="col-lg-3 col-md-3 col-sm-4 col-12"> <a href="{{ route('admin.expense.expenselist') }}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn" style="background: #f76ce1;" ><span>Expense List</span><img src="{{ url('assets/admin/new/images/icon/expenses-list.svg') }}" alt=""/></a> </div>

          <div class="col-lg-2 col-md-2 col-sm-4 col-12"> <a href="{{ route('admin.expense.addexpense') }}" class="metroBox d-flex align-items-center justify-content-center wow zoomIn" style="background: #e08300;" ><span>Add Expense</span><img src="{{ url('assets/admin/new/images/icon/add-expenses.svg') }}" alt=""/></a> </div>

        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal fade dbModal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <div class="row">
          <div class="col-6"> <a href="javascript:;" data-href="{{ route('admin.pos.pos_create') }}" data-is_branch="Y" class="check_url_permision gotoPos"> <img src="{{ url('assets/admin/images/off-counter.svg') }}" alt=""> <span>Off Counter</span> </a> </div>
          <div class="col-6"> <a href="javascript:;" data-href="#" data-is_branch="{{$is_branch}}" class="check_url_permision gotoPos"> <img src="{{ url('assets/admin/images/bar-cum-restaurannt-01.svg') }}" alt=""> <span>Bar Cum Restaurant</span> </a> </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.16/dist/sweetalert2.all.min.js"></script>
<script>
$(document).on('click','.check_url_permision',function(){
	var is_branch	= $(this).data('is_branch');
	var href		= $(this).data('href');
	if(is_branch=='Y'){
		//window.location.replace(href);
		window.location.href = href;
	}else{
		Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'you don\'t have permission to access!'
        });
	}


});
</script>
@endsection
