@extends('layouts.sidebar_collapse_admin')
@section('admin-content')
<style>
.error {
    display: none !important;
}
.red_border {
    border: 1px solid #e50b0b;
}
</style>

@php
$adminId 			= Session::get('adminId');
$adminRoll 		= Session::get('admin_type');
@endphp




<div class="row">
<form method="post" action="" class="needs-validation" id="supplier-inward_stock-form" novalidate enctype="multipart/form-data">
  <div class="col-12 mb-3">
    <div class="commonBox"> 
      <!--<div class="arrowUpDown2"> <span class="arrowDown"><i class="fas fa-arrow-alt-circle-down"></i></span> </div>-->
      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
          <div class="supplierWrap">
            <div class="invArea">
              <ul class="d-flex flex-wrap align-items-center">
                <li class="invAreaInf">Invoice Number</li>
                <li class="invAreaVal">
                  <input type="text" name="invoice_no" id="invoice_no" class="form-control input-1" required="required">
                </li>
              </ul>
              <ul class="d-flex flex-wrap align-items-center">
                <li class="invAreaInf">Purchase Date</li>
                <li class="invAreaVal">
                  <input type="date" name="purchase_date" id="purchase_date" class="form-control input-1" required="required">
                </li>
              </ul>
              <ul class="d-flex flex-wrap align-items-center">
                <li class="invAreaInf">Inward Date</li>
                <li class="invAreaVal">
                  <input type="date" name="inward_date" id="inward_date" class=" form-control input-1" required="required">
                </li>
              </ul>

              @if($adminRoll==1)
              <ul class="d-flex flex-wrap align-items-center">
                <li class="invAreaInf">Store</li>
                <li class="invAreaVal">
                  <select class="form-control custom-select form-control-select" id="store_id" name="store_id" required="required">
                    <option value="">Select Store</option>
                    @foreach($data['store'] as $store)
                    <option value="{{$store->id}}">{{$store->name}}</option>
                    @endforeach
                  
                  </select>
                </li>
              </ul>
              @else
              <input type="hidden" id="store_id" name="store_id" value="{{$adminId}}">
              @endif
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
          <div class="supplierDetails relative">
            <h4>Additional Details</h4>
            
            <div class="noteAreaInner">
              <textarea name="additional_note" id="additional_note" cols="30" rows="10" placeholder="Additional Note"></textarea>
            </div>
            <div class="noteAreaInner">
              <input type="text" name="payment_ref_no" id="payment_ref_no" class="form-control input-1" placeholder="Ref No">
            </div>
            
          </div>
        </div>
        
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
          <div class="supplierDetails relative">
            <h4>Payment Details</h4>
            
            
            <div class="invArea mb-3">
              <ul class="d-flex flex-wrap align-items-center">
                <li class="invAreaInf">Payment Mode</li>
                <li class="invAreaVal">
                  <select class="form-control custom-select form-control-select" id="payment_method" name="payment_method" required="required">
                    <option value="">Select payment method</option>
                    <option value="cheque">Cheque</option>
                    <option value="net_banking">Net Banking</option>
                    <option value="cash">Cash</option>
                  </select>
                </li>
              </ul>
              <ul class="d-flex flex-wrap align-items-center">
                <li class="invAreaInf">Payment Date</li>
                <li class="invAreaVal">
                  <input type="date" name="payment_date" id="payment_date" class="form-control input-1" required="required">
                </li>
              </ul>
              
            </div>
            <div class="uploadDiv"> <a href="javascript:;" class="uploadBtnMd" id="upload_excel"><i class="fas fa-upload"></i> Click Here To Upload excel</a>
              <p></p>
            </div>
            @if (isset($data['inward_stock_type']) && $data['inward_stock_type'] == 'edit')
            
            @else
            {{-- <div class="noteAreaInner">
              <button type="submit" class="btn btn-primary w-100">Submit <i class="fas fa-paper-plane"></i></button>
            </div> --}}
            @endif </div>
        </div>
      </div>
    </div>
  </div>
</form>


<form method="post" action="" class="needs-validation" id="supplier-inward_stock-product-form" novalidate enctype="multipart/form-data">
 
  {{-- <input type="hidden" name="invoice_no" id="input-supplier_invoice_no" />
  <input type="hidden" name="invoice_purchase_date" id="input-supplier_invoice_purchase_date" />
  <input type="hidden" name="invoice_inward_date" id="input-supplier_invoice_inward_date" /> --}}
  <input type="hidden" name="qty_total" id="input-supplier_qty_total" />
  <input type="hidden" name="sub_total" id="input-supplier_sub_total" />
  <input type="hidden" name="gross_amount" id="input-supplier_gross_amount" />
  <input type="hidden" name="gross_total_amount" id="input-gross_total_amount" />
  {{-- <input type="hidden" name="tax_amount" id="input-supplier_tax_amount" /> --}}
  {{-- <input type="hidden" name="shipping_note" id="input-supplier_shipping_note" />
  <input type="hidden" name="additional_note" id="input-supplier_additional_note" /> --}}
  

  <div class="col-12 mb-3">
    <div class="commonBox purcheseDetails vTop">
      {{-- <div class="arrowUpDown open_supplier_form"> <span class="arrowUp"><i class="fas fa-arrow-alt-circle-up"></i></span> </div> --}}
      
      <div class="table-responsive">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <td><h5>No. of Items</h5>
                <span class="d-block" id="no_of_items"></span></td>
              <td><h5>Total Qty</h5>
                <span class="d-block" id="qty_total"></span></td>
              <td><h5>Gross Total</h5>
                <span class="d-block" id="sub_total"></span></td>
              <td><h5>Grand Total</h5>
                  <span class="d-block" id="gross_total_amount"></span></td>
               
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-12">
    <div class="commonBox"> 
      <div class="enterBarcode mb-3">
        <div class="row">
          <div class="col">
            <div class="form-group relative enterBarcodeSrc m-0">
              <input type="text" name="product_search" id="product_search" class="form-control input-1" autocomplete="off" placeholder="Enter Barcode/Product Code/Product Name">
              <button type="button" class="searchBtn"><i class="fas fa-search"></i></button>
              <ul id="product_search_result">
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="inwordTableWrap">
        <div class="table-responsive forTableHeight">
          <table class="inwordTable table-striped table-hover table mb-0">
            <thead>
              <tr>
                <th><i class="fas fa-times"></i></th>
                <th>Barcode</th>
                <th>The Brand</th>
                <th>Dosage Form</th>
                <th>Company</th>
                <th>Drugstore name</th>
                <th>Quantity</th>
                <th>No per package</th>
                <th>Net Price</th>
                <th>Price</th>
                <th>Bonous</th>
                <th>US/IQ rate</th>
                <th>Total Quantity</th>
                <th>Sell Price</th>
                <th>Profit</th>
                <th>Actual % of profit</th>
              </tr>
            </thead>
            <tbody id="product_record_sec">
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  
  <div class="inwardStockBtm" id="inwardStockSubmitBtmSec" style="display:none">
      <div class="form-group relative formBox m-0">
        <button type="button" class="saveBtn saveBtnMd" id="inwardStockSubmitBtm">Save <i class="fas fa-paper-plane ml-2"></i></button>
      </div>
  </div>
  
</form>
<div class="modal fade" id="paymentDetailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Recipient:</label>
            <input type="text" class="form-control" id="recipient-name">
          </div>
          <div class="form-group">
            <label for="message-text" class="col-form-label">Message:</label>
            <textarea class="form-control" id="message-text"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Send message</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="changeAddressModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body"> ... </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade bd-example-modal-lg" id="newProductItemsModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog" style="max-width:95%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Product Add</h5>
      </div>
      <div class="modal-body">
        <div class="table-responsive forTableHeight">
          <table class="inwordTable table-striped table-hover table mb-0">
            <thead>
              <tr>
                <th>Barcode</th>
                <th>Bottle/case</th>
                <th>In case</th>
                <th>Total Qty</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Brand Name</th>
                <th>Batch No</th>
                <th>Measure</th>
                <th>Strength</th>
                <th>In B.L</th>
                <th>In LPL</th>
                <th>Retailer margin</th>
                <th>Round Off</th>
                <th>SP Fee</th>
                <th>MRP</th>
                <th>Total Amount</th>
              </tr>
            </thead>
            <tbody id="new_product_record_sec">
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="saveBtn" id="addProductSubmitBtm">Submit <i class="fas fa-paper-plane ml-2"></i></button>
      </div>
    </div>
  </div>
</div>
<div style="display:none;">
  <form method="post" action="{{ route('admin.purchase.product_stock_upload') }}" class="needs-validation" id="invoice_upload-form" novalidate enctype="multipart/form-data">
    @csrf
    <input name="inward_stock_file" id="upload_excel_input" style="display:none" type="file">
  </form>
</div>


@endsection




@section('scripts') 
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script> 
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script> 
<script src="{{ url('assets/admin/js/cHVyY2hhc2VfaW53YXJkX3N0b2Nr.js') }}"></script>
<script>
  
$(document).on('click', '#upload_excel', function(e) {
	$('#upload_excel_input').click();
});

$(document).on('change','#upload_excel_input',function(){
	$("#invoice_upload-form").submit()
});
</script> 

@endsection 