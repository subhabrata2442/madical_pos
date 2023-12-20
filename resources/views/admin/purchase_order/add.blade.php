@extends('layouts.sidebar_collapse_admin')
@section('admin-content')
<style>
  .error {
    display: none !important;
  }

  .red_border {
    border: 1px solid #e50b0b;
  }
  .fa-info-circle{
    cursor: pointer;
  }
</style>

@php
$adminId = Session::get('adminId');
$adminRoll = Session::get('admin_type');
@endphp

<div class="row">
  <form method="post" action="" class="needs-validation" id="supplier-inward_stock-form" novalidate
    enctype="multipart/form-data">
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
                    <input type="text" name="invoice_no" id="invoice_no" class="form-control input-1"
                      required="required">
                  </li>
                </ul>
                <ul class="d-flex flex-wrap align-items-center">
                  <li class="invAreaInf">Purchase Date</li>
                  <li class="invAreaVal">
                    <input type="date" name="purchase_date" id="purchase_date" class="form-control input-1"
                      required="required" value="<?php echo date('Y-m-d'); ?>">
                  </li>
                </ul>
                {{-- <ul class="d-flex flex-wrap align-items-center">
                  <li class="invAreaInf">Inward Date</li>
                  <li class="invAreaVal">
                    <input type="date" name="inward_date" id="inward_date" class=" form-control input-1"
                      required="required">
                  </li>
                </ul> --}}

                @if($adminRoll==1)
                <ul class="d-flex flex-wrap align-items-center">
                  <li class="invAreaInf">Store</li>
                  <li class="invAreaVal">
                    <select class="form-control custom-select form-control-select" id="store_id" name="store_id"
                      required="required">
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
                <ul class="d-flex flex-wrap align-items-center">
                    <li class="invAreaInf">Supplier</li>
                    <li class="invAreaVal relative">
                        <div class="add-plus-wrap relative">
                            <input type="text" name="supplier_name" id="supplier_name" class="form-control input-1" autocomplete="off">
                            <div class="add-plus-box"><a href="javascript:;" id="supplierAddModalBtn" class="add-plus-box-btn"><i class="fas fa-plus"></i></a></div>
                        </div>
                        <input type="hidden" name="supplier_id" id="supplier_id" value="">
                        <div class="custom-list">
                            <ul id="supplier_search_result">
                            </ul>
                        </div>
                      </li>
                  </ul>

              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-12 col-12">
            <div class="supplierDetails relative">
              <h4>Additional Details</h4>

              <div class="noteAreaInner">
                <textarea name="additional_note" id="additional_note" cols="30" rows="10"
                  placeholder="Additional Note"></textarea>
              </div>
              <div class="noteAreaInner">
                <input type="text" name="payment_ref_no" id="payment_ref_no" class="form-control input-1"
                  placeholder="Ref No">
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
                    <select class="form-control custom-select form-control-select" id="payment_method"
                      name="payment_method" required="required">
                      <option value="">Select payment method</option>
                      <option value="cheque">Cheque</option>
                      <option value="net_banking">Net Banking</option>
                      <option value="cash">Cash</option>
                      <option value="credit">Credit</option>
                    </select>
                  </li>
                </ul>
                <ul class="d-flex flex-wrap align-items-center hide" id="payment_debt_day_section">
                  <li class="invAreaInf">Payment Debt Day</li>
                  <li class="invAreaVal">
                    <input type="number" id="payment_debt_day" class="form-control input-1"
                      required="required" name="payment_debt_day" value="" >
                  </li>
                </ul>
                <ul class="d-flex flex-wrap align-items-center">
                  <li class="invAreaInf">Payment Date</li>
                  <li class="invAreaVal">
                    <input type="date" name="payment_date" id="payment_date" class="form-control input-1"
                      required="required" value="">
                  </li>
                </ul>
                <ul class="d-flex flex-wrap align-items-center">
                  {{-- <input type="hidden" id="payment_currency_usd_rate" value="1360"> --}}
                  <li class="invAreaInf">Payment Currency</li>
                  <li class="invAreaVal">
                    <select class="form-control custom-select form-control-select" id="payment_currency_type"
                      name="payment_currency_type" required="required">
                      <option value="usd">USD</option>
                      <option value="iqd">IQD </option>
                    </select>
                  </li>
                </ul>
                <ul class="d-flex flex-wrap align-items-center" id="payment_currency_usd_rate_section">
                  <li class="invAreaInf">US/IQ rate</li>
                  <li class="invAreaVal">
                    <input type="number" id="payment_currency_usd_rate" class="form-control input-1"
                      required="required" value="">
                  </li>
                </ul>
                <ul class="d-flex flex-wrap align-items-center" id="payment_discount_section">
                  <li class="invAreaInf">Discount (%)</li>
                  <li class="invAreaVal">
                    <input type="number" id="payment_discount" class="form-control input-1" value="" name="payment_discount">
                  </li>
                </ul>

              </div>
              {{-- <div class="uploadDiv"> <a href="javascript:;" class="uploadBtnMd" id="upload_excel"><i
                    class="fas fa-upload"></i> Click Here To Upload excel</a>
                <p></p>
              </div> --}}
              @if (isset($data['inward_stock_type']) && $data['inward_stock_type'] == 'edit')

              @else
              <div class="noteAreaInner">
              <button type="submit" class="btn btn-primary w-100">Submit <i class="fas fa-paper-plane"></i></button>
            </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
  <div id="search_product_div" style="display: none;">
    <div class="col-12 mb-3">
      <div class="commonBox purcheseDetails vTop">
        <div class="arrowUpDown close_supplier_form" style="display: none"> <span class="arrowUp"><i class="fas fa-arrow-alt-circle-down"></i></span> </div>
        <div class="arrowUpDown open_supplier_form" > <span class="arrowUp"><i class="fas fa-arrow-alt-circle-up"></i></span> </div>

        <div class="table-responsive">
          <table class="table table-bordered">
            <tbody>
              <tr>
                <td>
                  <h5>No. of Items</h5>
                  <span class="d-block" id="no_of_items"></span>
                </td>
                <td>
                  <h5>Total Qty</h5>
                  <span class="d-block" id="qty_total"></span>
                </td>
                <td>
                  <h5>Total Net Price</h5>
                  <span class="d-block" id="sub_total"></span>
                </td>
                <td>
                  <h5>Total Sell Price</h5>
                  <span class="d-block" id="gross_total_amount"></span>
                </td>
                <td>
                  <h5>Total Profit</h5>
                  <span class="d-block" id="total_profit"></span>
                </td>
                <td>
                  <h5>% Profit</h5>
                  <span class="d-block" id="total_profit_percent"></span>
                </td>

              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    {{-- <form id="barcode_scanner_frm"> --}}
      <div class="col-12">
        <div class="enterBarcode mb-3">
          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
              <div class="form-group relative enterBarcodeSrc m-0">
                <input type="text" name="product_search" id="product_search" class="form-control input-1"
                  autocomplete="off" placeholder="Enter Barcode/Product Code/Product Name">
                <button type="button" class="searchBtn"><i class="fas fa-search"></i></button>
                <ul id="product_search_result">
                </ul>
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
              <div class="enterProduct d-flex align-items-center justify-content-between">
                <div class="enterProductInner d-flex">
                  <input type="text" name="search_barcode_product" id="search_barcode_product" placeholder="Scan Barcode" value="">
                </div>
                <span><i class="fas fa-barcode"></i></span> </div>
            </div>
          </div>
        </div>
      </div>
    {{-- </form> --}}
  </div>
  <div class="col-12">
    <form method="post" action="" class="needs-validation" id="supplier-inward_stock-product-form" novalidate
      enctype="multipart/form-data" style="display: none">

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

        <div class="commonBox">
          {{-- <div class="enterBarcode mb-3">
            <div class="row">
              <div class="col">
                <div class="form-group relative enterBarcodeSrc m-0">
                  <input type="text" name="product_search" id="product_search" class="form-control input-1"
                    autocomplete="off" placeholder="Enter Barcode/Product Code/Product Name">
                  <button type="button" class="searchBtn"><i class="fas fa-search"></i></button>
                  <ul id="product_search_result">
                  </ul>
                </div>
              </div>
            </div>
          </div> --}}
          <div class="inwordTableWrap">
            <div class="table-responsive forTableHeight">
              <table class="inwordTable table-striped table-hover table mb-0">
                <thead>
                  <tr>
                    <th><i class="fas fa-times"></i></th>
                    {{-- <th>Barcode</th> --}}
                    <th>The Brand</th>
                    {{-- <th>Product Name</th> --}}
                    <th>Dosage Form</th>
                    <th>Company</th>
                    <th>Selling by</th>
                    {{-- <th>Drugstore name</th> --}}
                    <th>Expiry Date</th>
                    <th style="width: 80px;">Quantity</th>
                    <th>NPP</th> {{-- No per package --}}
                    <th>Net Price</th>
                    <th style="width: 80px;">Price</th>


                    {{-- <th>Discount %</th> --}}
                    <th style="width: 80px;">Bonous</th>
                    {{-- <th id="th_rate_title">US/IQ rate</th> --}}
                    <th>Total Quantity</th>
                    <th style="width: 80px;">Sell Price</th>
                    <th style="width: 80px;">CP</th>
                    <th>Profit</th>
                    <th>% Profit</th>
                    <th>Chronic %</th>
                    <th>Is Chronic</th>
                  </tr>
                </thead>
                <tbody id="product_record_sec">

                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="inwardStockBtm" id="inwardStockSubmitBtmSec" style="display:none">
          <div class="form-group relative formBox m-0">
            <button type="button" class="saveBtn saveBtnMd" id="inwardStockSubmitBtm">Save <i
                class="fas fa-paper-plane ml-2"></i></button>
          </div>
        </div>
  </div>

  </form>
  <div class="modal fade" id="paymentDetailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">New message</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span
              aria-hidden="true">&times;</span> </button>
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
  <div class="modal fade" id="changeAddressModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span
              aria-hidden="true">&times;</span> </button>
        </div>
        <div class="modal-body"> ... </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade bd-example-modal-lg" id="newProductItemsModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
          <button type="button" class="saveBtn" id="addProductSubmitBtm">Submit <i
              class="fas fa-paper-plane ml-2"></i></button>
        </div>
      </div>
    </div>
  </div>
  <div style="display:none;">
    <form method="post" action="{{ route('admin.purchase.product_stock_upload') }}" class="needs-validation"
      id="invoice_upload-form" novalidate enctype="multipart/form-data">
      @csrf
      <input name="inward_stock_file" id="upload_excel_input" style="display:none" type="file">
    </form>
  </div>
{{-- Modal Supplier add --}}
  <div class="modal fade" id="supplierAddModal" tabindex="-1"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add New Supplier</h5>
          <button type="button" class="close close_modal_btn" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        </div>
        <div class="modal-body">
          <form method="post" action="{{route('admin.supplier.add')}}" class="needs-validation" id="modal_add_supplier_frm" novalidate enctype="multipart/form-data">
            @csrf
          {{-- <form action="" method="post" id="modal_add_supplier_frm"> --}}
            <div class="row">
                <div class="col-12">
                    <div class="supplier-input-wrap">
                        <label for="supplier_company_name" class="col-form-label">Supplier Name</label>
                        <input type="text" class="form-control" id="supplier_company_name" name="supplier_company_name" required>
                      </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="supplier-input-wrap">
                        <label for="supplier_company_mobile_no" class="col-form-label">Phone No</label>
                        <input type="text" class="form-control" id="supplier_company_mobile_no" name="supplier_company_mobile_no">
                      </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="supplier-input-wrap">
                        <label for="supplier_email" class="col-form-label">Email</label>
                        <input type="text" class="form-control" id="supplier_email" name="supplier_email">
                      </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="supplier-input-wrap">
                        <label for="supplier_owner_name" class="col-form-label">Supplier Owner Name</label>
                        <input type="text" class="form-control" id="supplier_owner_name" name="supplier_owner_name">
                      </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="supplier-input-wrap">
                        <label for="supplier_business_type" class="col-form-label">Business Type</label>
                        <select name="supplier_business_type" id="supplier_business_type" class="form-control form-inputtext">
                          <option value="">Select Type</option>
                          <option value="registered">Registered</option>
                          <option value="unregistered">Unregistered</option>
                        </select>
                      </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="supplier-input-wrap">
                        <label for="supplier_address1" class="col-form-label">Address Line 1</label>
                        <input type="text" class="form-control" id="supplier_address1" name="supplier_address1">
                      </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="supplier-input-wrap">
                        <label for="supplier_address2" class="col-form-label">Address Line 2</label>
                        <input type="text" class="form-control" id="supplier_address2" name="supplier_address2">
                      </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="supplier-input-wrap">
                        <label for="state_name" class="col-form-label">State</label>
                        <input type="text" class="form-control" id="state_name" name="state_name">
                      </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="supplier-input-wrap">
                        <label for="supplier_company_area" class="col-form-label">Landmark / Area</label>
                        <input type="text" class="form-control" id="supplier_company_area" name="supplier_company_area">
                      </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                    <div class="supplier-input-wrap">
                        <label for="supplier_company_city" class="col-form-label">City</label>
                        <input type="text" class="form-control" id="supplier_company_city" name="supplier_company_city">
                      </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                    <div class="supplier-input-wrap">
                        <label for="country_name" class="col-form-label">Country</label>
                        <input type="text" class="form-control" id="country_name" name="country_name">
                      </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                    <div class="supplier-input-wrap">
                        <label for="supplier_company_zipcode" class="col-form-label">Pin / Zip Code</label>
                        <input type="text" class="form-control" id="supplier_company_zipcode" name="supplier_company_zipcode">
                      </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary close_modal_btn" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
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
  <script src="{{ url('assets/admin/js/jquery.scannerdetection.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
  <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
  <script src="{{ url('assets/admin/js/cHVyY2hhc2VfaW53YXJkX3N0b2Nr.js') }}"></script>
  <script>
    $(document).on('click', '#upload_excel', function(e) {
      $('#upload_excel_input').click();
    });
    $(document).on('change', '#upload_excel_input', function() {
      $("#invoice_upload-form").submit()
    });


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
