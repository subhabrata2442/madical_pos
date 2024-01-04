@extends('layouts.admin_pos')
@section('admin-content')
<style>
.has-error {
    border: 1px solid #b30c0c;
}
.error {
    color: #b30c0c;
}

</style>
<div class="row">
  <div class="col-lg-8 col-md-8">
    <div class="d-flex align-items-center justify-content-between cbName">
      <div class="enterProduct d-flex align-items-center justify-content-between">
        <div class="enterProductInner d-flex">
          <input type="text" name="search_product" id="search_product" placeholder="Enter Barcode/Enter Brand Name" value="">
          <ul id="product_search_result">
          </ul>
        </div>
      </div>
      <div class="enterProduct d-flex align-items-center justify-content-between">
        <div class="enterProductInner d-flex">
          <input type="text" name="search_barcode_product" id="search_barcode_product" placeholder="Scan Barcode" value="">
        </div>
        <span><i class="fas fa-barcode"></i></span> </div>
      <div class="fullscreen_wrap">
        <ul>
          <li class="requestfullscreen"><a href="javascript:;">Fullscreen</a></li>
          <li class="exitfullscreen" style="display: none"><a href="javascript:;">Exit fullscreen</a></li>
        </ul>
      </div>
      <!--<div class="selectSale">
        <label class="switch">
          <input type="checkbox" id="sell_type">
          <span class="slider round"></span> <span class="absolute-no">Return</span> </label>
      </div>-->
      <!--<input type="button" id="fullscreen_btn" value="Fullscreen" onclick="requestFullScreen(document.body)">-->
    </div>

    <input type="hidden" name="pos_urls" id="pos_urls" value="{{ route('admin.pos.pos_create') }}">

    <form method="post" action="{{ route('admin.pos.update') }}" id="pos_create_order-form_update" novalidate enctype="multipart/form-data">
      @csrf
      <input type="hidden" class="input-2" name="sell_date" id="sell_date" value="{{$data['bill_details']->sell_date}}">
      <input type="hidden" name="sell_inward_stock_id" id="sell_inward_stock_id" value="{{$data['bill_details']->id}}">
      <input type="hidden" name="payment_method_type" id="payment_method_type-input" value="cash">
      <input type="hidden" name="stock_type" value="s">
      <div class="w-100">
        <div class="tableFixHead table-1">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <thead>
              <tr>
                <th width="6%">Barcode</th>
                <th width="25%">Brand</th>
                {{-- <th width="19%">Product</th> --}}
                <th width="4%">Selling by</th>
                <th width="10%">Stock</th>
                <th width="10%">Is cronic</th>
                <th width="11%">MRP</th>
                <th width="9%">Qty.</th>
                {{-- <th width="8%">Disc%</th> --}}
                {{-- <th width="11%">Disc Amt.</th> --}}
                <th width="8%">Unit Price</th>
                <th width="7%">Total</th>
                <th width="1%">&nbsp;</th>
              </tr>
            </thead>
            <tbody id="product_sell_record_sec">

                @foreach ($data['sellStockProducts'] as $key=>$item)

                @php
                    // dd($item);
                    $branch_stock_product = App\Models\BranchStockProducts::where('id',$item->product_stock_id)->first();
                @endphp

                <tr id="sell_product_{{$branch_stock_product->id}}" data-id="{{$key}}">
                    <input type="hidden" name="product_id[]" value="{{$branch_stock_product->id}}">
                    <input type="hidden" id="product_stock_{{$branch_stock_product->id}}" value="46">
                    <input type="hidden" name="product_total_amount[]" id="input-product_total_amount_{{$branch_stock_product->id}}" value="2000.00">
                    <input type="hidden" name="product_price_id[]" id="input-product_price_id_{{$branch_stock_product->id}}" value="{{$branch_stock_product->id}}">
                    <input type="hidden" name="product_barcode[]" id="input-product_barcode_{{$branch_stock_product->id}}" value="8547">
                    <input type="hidden" name="product_name[]" id="input-product_name_{{$branch_stock_product->id}}" value="test">
                    <input type="hidden" name="brand_name[]" id="input-brand_name_{{$branch_stock_product->id}}" value="Elviton tab">
                    <td>{{$branch_stock_product->product_barcode}}</td>
                    <td class="">{{$branch_stock_product->product->brand}}</td>
                    {{-- <td class="">{{$branch_stock_product->product->product_name}}</td> --}}
                    <td class="">{{$branch_stock_product->product->selling_by_name}}</td>
                    <td id="product_stock_td_{{$branch_stock_product->id}}">{{$branch_stock_product->t_qty}}</td>

                    <td>
                        @if ($branch_stock_product->is_chronic=='Yes')
                            <select class="select-3" name="" onchange="changeiscronic(this.value, '{{$branch_stock_product->id}}')">
                                <option value="sellprice" @if($item->product_mrp==$branch_stock_product->selling_price) selected @endif>Sell price: {{$branch_stock_product->selling_price}}</option>
                                <option value="cronicprice" @if($item->product_mrp==$branch_stock_product->chronic_amount) selected @endif>CP: {{$branch_stock_product->chronic_amount}}</option>
                            </select>
                            <input type="hidden" class="product_cronic_amount input-3" name="product_cronic_amount_[]" id="product_cronic_amount_{{$branch_stock_product->id}}" value="2500">
                        @else
                            No
                        @endif
                    </td>


                    <td id="product_unit_price_{{$branch_stock_product->id}}">{{number_format($item->product_mrp)}}</td>
                    <td><input type="number" name="product_qty[]" id="product_qty_{{$branch_stock_product->id}}" class="input-3 product_qty" value="{{$item->product_qty}}"></td>
                    <td style="display:none;"><input type="text" name="product_disc_percent[]" id="product_disc_percent_{{$branch_stock_product->id}}" class="input-3 product_disc_percent" value="0"></td>
                    <td style="display:none;"><input type="text" name="product_disc_amount[]" id="product_disc_amount_{{$branch_stock_product->id}}" class="input-3 product_disc_amount" value="0"></td>
                    <td id="product_mrp_{{$branch_stock_product->id}}">{{number_format($item->product_mrp)}}</td>
                    <input type="hidden" class="input-3" name="" id="product_unit_price_amount_old_{{$branch_stock_product->id}}" value="{{$item->product_mrp}}">
                    <input type="hidden" class="product_unit_price_amount input-3" name="product_unit_price_amount[]" id="product_unit_price_amount_{{$branch_stock_product->id}}" value="{{$item->product_mrp}}">
                    <td id="product_total_amount_{{$branch_stock_product->id}}">{{number_format($item->total_cost)}}</td>
                    <td><a href="javascript:;" onclick="remove_sell_item_edit({{$key}}, {{$item->id}});"><svg class="svg-inline--fa fa-times-circle fa-w-16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.6 313.1c4.7 4.7 4.7 12.3 0 17L338 377.6c-4.7 4.7-12.3 4.7-17 0L256 312l-65.1 65.6c-4.7 4.7-12.3 4.7-17 0L134.4 338c-4.7-4.7-4.7-12.3 0-17l65.6-65-65.6-65.1c-4.7-4.7-4.7-12.3 0-17l39.6-39.6c4.7-4.7 12.3-4.7 17 0l65 65.7 65.1-65.6c4.7-4.7 12.3-4.7 17 0l39.6 39.6c4.7 4.7 4.7 12.3 0 17L312 256l65.6 65.1z"></path></svg><!-- <i class="fas fa-times-circle"></i> Font Awesome fontawesome.com --></a></td>
                    <input type="hidden" name="product_net_price[]" id="product_net_price_{{$branch_stock_product->id}}" class="input-3" value="{{$branch_stock_product->net_price}}">
                </tr>

                @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="amountToPay d-flex w-100">
        <div class="atpLeft">
          <div class="atpLeftInner">
            <ul class="d-flex w-100">
              <li class="atpVall">Total Item -</li>
              <li class="atpinfo" id="total_quantity">0</li>
              <input type="hidden" name="total_quantity" id="total_quantity-input" value="0">
            </ul>
            <ul class="d-flex w-100">
              <li class="atpVall">Total Net Price -</li>
              <li class="atpinfo"><span id="total_net_price">$0</span></li>
              <input type="hidden" name="net_price" id="net_price" value="0">
            </ul>
            <ul class="d-flex w-100">
              <li class="atpVall">Total Sale Price -</li>
              <li class="atpinfo"><span id="total_mrp">$0</span> <small>(inclusive all taxes)</small></li>
              <input type="hidden" name="total_mrp" id="total_mrp-input" value="0">
            </ul>

            <ul class="d-flex w-100">
              <li class="atpVall">Discount -</li>
              <li class="atpinfo" id="total_discount_amount">$0</li>
              <input type="hidden" name="total_discount_amount" id="total_discount_amount-input" value="0">
            </ul>
            <ul class="d-flex w-100">
              <li class="atpVall">Charge -</li>
              <li class="atpinfo" id="extraCharge">${{$data['bill_details']->charge_amount}}</li>
            </ul>
            <ul class="d-flex w-100">
              {{-- <li class="atpVall">Tax -</li>
              <li class="atpinfo" id="tax_amount">$0.00</li> --}}
              <input type="hidden" name="tax_amount" id="tax_amount-input" value="0">
            </ul>
            <ul class="d-flex w-100 subTotal">
              <li class="atpVall">Sub Total-</li>
              <li class="atpinfo" id="sub_total_mrp">$0</li>
              <input type="hidden" name="sub_total" id="sub_total_mrp-input" value="0">
            </ul>
            {{-- <ul class="d-flex w-100">
              <li class="atpVall">Round Off -</li>
              <li class="atpinfo">
                <input type="text" name="round_off" id="round_off" class="small-input" placeholder="0" onkeydown="checkforroundoff(event,this)">
              </li>
            </ul> --}}
            <ul class="d-flex w-100 subTotal">
              <li class="atpVall">Total profit-</li>
              <li class="atpinfo" id="total_profit">$0</li>
              <input type="hidden" name="profit_price" id="profit_price" value="0">
            </ul>
            <ul class="d-flex w-100 subTotal">
              <li class="atpVall">Profit-</li>
              <li class="atpinfo" id="profitpersent">0%</li>
            </ul>

          </div>
        </div>
        {{-- <div class="atpMid d-flex justify-content-center align-items-center"> --}}
            <div class="atpMid">
          <ul>
            <li><a href="javascript:;" class="applyCharge" id="applyChargeBtn">Apply Charge</a></li>
            <li><a href="javascript:;" class="applyDiscount" id="applyDiscountBtn">Apply Discount </a></li>
          </ul>
            <div class="customerDetailsBtm">
                <ul class="d-flex flex-wrap">
                    <li>Last Bill No - <span>#{{$data['last_bill_no']}}</span></li>
                    <li>Last Bill Amount - <span>${{number_format($data['last_bill_amount'])}}</span></li>
                    <li class="ml-auto"><a href="javascript:;" class="print_off_counter_bill pt-0"><i class="fas fa-print"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="atpRight d-flex justify-content-center align-items-center">
          <div class="text-center">
            <h6>Amount to pay</h6>
            <h3 id="total_payble_amount">$0</h3>
            <input type="hidden" name="actual_amount" id="actual_amount" value="0">
            <input type="hidden" name="gross_total_amount" id="gross_total_input" value="0">
            <input type="hidden" name="gross_total_amount_cal" id="gross_total_amount-input" value="0">
            <input type="hidden" name="total_payble_amount" id="total_payble_amount-input" value="0">
            <input type="hidden" name="special_discount_percent" id="selling_special_discount_percent-input" value="{{$data['bill_details']->special_discount_percent}}">
            <input type="hidden" name="special_discount_amt" id="selling_special_discount_amt-input" value="{{$data['bill_details']->special_discount_amt}}">
            <input type="hidden" name="charge_amt" id="charge_amt-input" value="{{$data['bill_details']->charge_amount}}">
            <input type="hidden" name="tendered_amount" id="total_tendered_amount" value="0">
            <input type="hidden" name="tendered_change_amount" id="total_tendered_change_amount" value="0">
          </div>
        </div>
      </div>
      <div class="note_coin_count_sec" style="display:none"> </div>
      <div class="upi_payment_sec" style="display:none"> </div>
      <div class="card_details_payment_sec" style="display:none"> </div>
      <input type="hidden" name="customer_id" id="selected_customer_id" value="{{$data['bill_details']->customer_id}}">
    </form>
  </div>
    <div class="col-lg-4 col-md-4">
        <div class="d-flex flex-column justify-content-between h-100">
            <div class="report-wrap h-100">
                <div class="report-wrap-lft">
                    <div class="d-flex flex-column justify-content-between h-100">
                        <div class="dateSales">
                            <ul class="d-flex flex-wrap justify-content-between align-items-center">
                                <li><strong>Cashier :</strong> {{$data['supplier']->name}}</li>
                                <li class="d-flex align-items-center">
                                <p>Date:</p>
                                <?php
                                // echo date('d-m-Y');
                                ?>
                                <input type="date" class="input-2" onchange="get_sell_date(this.value)" name="sell_date_view" id="sell_date_view" value="{{$data['bill_details']->sell_date}}">
                                </li>
                            </ul>
                        </div>
                        <div class="data-sales-head radio-list">
                            <div class="form-check">
                                <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="customertype" value="walkin" @if($data['bill_details']->customer_id=='0') checked @endif>Walk in customer
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="customertype" value="regular" @if($data['bill_details']->customer_id!='0') checked @endif>Regular customer
                                </label>
                            </div>
                        </div>
                        <div class="data-sales-head customeSearch" @if($data['bill_details']->customer_id=0) style="display: none;" @endif>
                            <div class="srcArea relative">
                                <input type="text" placeholder="Search by name/contact number" class="input-2" value="" id="search_customer">
                                <div class="custom-list">
                                    <ul id="customer_search_result">
                                    </ul>
                                </div>
                                <span class="plusCircle create_customer_btn"><i class="fas fa-plus-circle"></i></span>
                            </div>
                        </div>
                        <div class="d-flex flex-column justify-content-start h-100">
                            <div class="data-sales-body d-flex flex-column justify-content-start h-100">
                                {{-- <div class="dateSales">
                                    <ul class="d-flex flex-wrap justify-content-between align-items-center">
                                        <li><strong>Cashier :</strong> {{$data['supplier']->name}}</li>
                                        <li class="d-flex align-items-center">
                                        <p>Date:</p>
                                        <?php //echo date('d-m-Y');?>
                                        <!--<input type="date" class="input-2" value="<?php echo date('d-m-Y');?>">-->
                                        </li>
                                    </ul>
                                </div> --}}
                                <div class="customerDetails">
                                {{-- <h4>Customer Details<span class="float-right" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom"><i class="fas fa-info-circle"></i></span></h4> --}}
                                <div class="customerDetailsMid" @if($data['bill_details']->customer_id='0') style="display: none;" @endif>
                                <h4>Customer Details<span class="float-right" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom"><i class="fas fa-info-circle"></i></span></h4>
                                    <ul>
                                    <li id="cd_customer_name">Customer Name : {{@$data['bill_details']->customer->customer_name}}<span></span></li>
                                    <li id="cd_customer_number">Contact Number : {{@$data['bill_details']->customer->customer_mobile}}<span></span></li>
                                    </ul>
                                </div>
                                {{-- <div class="customerDetailsBtm">
                                    <ul class="d-flex flex-wrap">
                                    <li>Last Bill No - <span>#{{$data['last_bill_no']}}</span></li>
                                    <li>Last Bill Amount - <span>${{$data['last_bill_amount']}}</span></li>
                                    <li class="ml-auto"><a href="javascript:;" class="print_off_counter_bill"><i class="fas fa-print"></i></a></li>
                                    </ul>
                                </div> --}}
                                </div>
                            </div>
                            <div class="data-sales-ftr">
                                <div class="sidebar-widget text-center">
                                <ul class="row">
                                    {{-- <li class="col-3 disabled_btn"><a href="jsvascript:;" data-bs-toggle="modal" data-bs-target="#modal-1"><span><i class="fas fa-ticket-alt"></i></span>Apply<br>
                                    Coupon</a></li>
                                    <li class="col-3 disabled_btn"><a href="#"><span><i class="fas fa-user-check"></i></span>Apply<br>
                                    Membership</a></li>
                                    <li class="col-3 disabled_btn"><a href="#"><span><i class="fas fa-calculator"></i></span>Calculator</a></li>
                                    <li class="col-3 disabled_btn"><a href="jsvascript:;" data-bs-toggle="modal" data-bs-target="#modal-2"><span><i class="fas fa-credit-card"></i></span>Reedem Credit</a></li>
                                    <li class="col-3 disabled_btn"><a href="#"><span><i class="fas fa-hand-paper"></i></span>Hold</a></li>
                                    <li class="col-3 disabled_btn"><a href="jsvascript:;" data-bs-toggle="modal" data-bs-target="#modal-3"><span><i class="fas fa-street-view"></i></span>View Hold</a></li>
                                    <li class="col-3 disabled_btn"><a href="javascript:;"><span><i class="fas fa-wallet"></i></span>Reset Bill</a></li>
                                    <li class="col-3 disabled_btn"><a href="javascript:;"><span><i class="fas fa-luggage-cart"></i></span>Today Sale</a></li> --}}
                                    @if (empty($data['settlement']))
                                        <li class="payPrint"><a href="javascript:;" class="payBtn"><span><i class="fas fa-money-check"></i></span>pay</a></li>
                                    @else
                                        <li class="payPrint disabled_btn"><a href="javascript:;" class="no_settlement"><span><i class="fas fa-money-check"></i></span>pay</a></li>
                                    @endif

                                    <li class="payPrint small-pay-btn"><a href="javascript:;" class="print_off_counter_bill"><span><i class="fas fa-print"></i></span>Print Last Bill</a></li>

                                    {{-- @php
                                        dd($data['settlement']);
                                    @endphp --}}

                                    @if (empty($data['settlement']))
                                        <li class="payPrint small-pay-btn"><a href="javascript:;" class="settlement"><span><i class="fas fa-money-bill"></i></span>Settlement</a></li>
                                    @else
                                        <li class="payPrint small-pay-btn disabled_btn"><a href="javascript:;" class="no_settlement"><span><i class="fas fa-money-bill"></i></span>Settlement</a></li>
                                    @endif

                                </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="report-wrap-rgt">
                    <div class="top-product-head"><h3>top seling product</h3></div>
                    <div class="top-product-src">
                        <input type="text" class="top-product-src-input" name="" id="top_search_product" placeholder="Enter Brand Name" value="">
                        <ul id="product_search_result_top" class="top-product-src-list">

                        </ul>
                    </div>
                    <div class="top-product">
                        <ul class="top-heading-qty">
                            <li>Name</li>
                            <li>In Stock</li>
                        </ul>
                        <ul class="top-product-list">
                          @if (count($data['topSellingProducts'])>0)
                            @foreach ($data['topSellingProducts'] as $keystop=>$topitem)

                                <li value="{{$topitem['id']}}" class="topSellProductItem">{{$topitem['brand']}}<span class="total-qty">{{$topitem['t_qty']}}</span></li>

                            @endforeach
                          @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
  {{-- <div class="col-12">
    <div class="topsellingProduct">
      <h4>Top Selling Products</h4>
      <ul class="row">
        @foreach($data['top_selling_product_result'] as $row)
        <li><a href="javascript:;" data-id="{{$row['product_id']}}" class="addTopSellingProduct"><img src="{{ url('assets/admin/images/1.png') }}" alt=""><span>{{$row['product_name']}} {{$row['product_size']}}</span></a></li>
        @endforeach

        <!-- <li><a href="javascript:;" data-id="742" class="addTopSellingProduct"><img src="{{ url('assets/admin/images/1.png') }}" alt=""><span>SEAGRAMS IMPERIAL BLUE CLASSIC GRAIN WHISKY (750  ml)</span></a></li>
        <li><a href="javascript:;" data-id="663" class="addTopSellingProduct"><img src="{{ url('assets/admin/images/1.png') }}" alt=""><span>OFFICER'S CHOICE DELUXE WHISKY (750  ml)</span></a></li>
        <li><a href="javascript:;" data-id="632" class="addTopSellingProduct"><img src="{{ url('assets/admin/images/1.png') }}" alt=""><span>Mc Dowells No.1 Luxury Premium Whisky (750  ml)</span></a></li>
        <li><a href="javascript:;" data-id="306" class="addTopSellingProduct"><img src="{{ url('assets/admin/images/1.png') }}" alt=""><span>Old Monk Lemon Rum (750  ml)</span></a></li>
        <li><a href="javascript:;" data-id="9" class="addTopSellingProduct"><img src="{{ url('assets/admin/images/1.png') }}" alt=""><span>BIRA91 GOLD WHEAT STRONG BEER (650  ml)</span></a></li>
        <li><a href="javascript:;" data-id="293" class="addTopSellingProduct"><img src="{{ url('assets/admin/images/1.png') }}" alt=""><span>McDowells No.1 Celebration Matured XXX Rum (750  ml)</span></a></li>
        <li><a href="javascript:;" data-id="40" class="addTopSellingProduct"><img src="{{ url('assets/admin/images/1.png') }}" alt=""><span>Kingfisher Strong Premium Beer (650  ml)</span></a></li>-->
      </ul>
    </div>
  </div> --}}
</div>
<div class="modal fade modalMdHeader" id="modal-applyDiscount" tabindex="-1" aria-labelledby="modal-1Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-1Label">Discount</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="applyCouponBox">
          <form action="" method="get" id="applyDiscount-form">
            <div class="mb-3">
              <label for="" class="form-label">Discount (%)</label>
              <input type="text" class="form-control number" name="special_discount_percent" id="special_discount_percent" autocomplete="off" value="{{$data['bill_details']->special_discount_percent}}">
            </div>
            <div class="mb-3">
              <label for="" class="form-label">Discount Amt</label>
              <input type="text" class="form-control number" name="special_discount_amt" id="special_discount_amt" autocomplete="off" value="{{$data['bill_details']->special_discount_amt}}">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
      <div class="modal-footer invoiceBalance">
        {{-- <h6>Total Payable: <span id="discount_total_payable">0</span></h6> --}}
      </div>
    </div>
  </div>
</div>
@php
    // dd($data['bill_details']);
@endphp
<div class="modal fade modalMdHeader" id="modal-applyCharges" tabindex="-1" aria-labelledby="modal-1Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-1Label">Apply Charge</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="applyCouponBox">
          <form action="" method="get" id="applyCharge-form">
            <div class="mb-3">
              <label for="" class="form-label">Charge Amt</label>
              <input type="text" class="form-control number" name="charge_amt" id="charge_amt" autocomplete="off" value="{{$data['bill_details']->charge_amount}}">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
      <div class="modal-footer invoiceBalance">
        {{-- <h6>Total Payable: <span id="charge_total_payable">0</span></h6> --}}
      </div>
    </div>
  </div>
</div>
{{-- Create Customer Modal --}}
<div class="modal fade modalMdHeader" id="modal_createCustomer" tabindex="-1" aria-labelledby="modal-1Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-1Label">Create New Customer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="applyCouponBox">
          <form action="{{route('admin.customer.add')}}" method="post" id="create_customer_form" class="needs-validation" novalidate="novalidate" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
              <label for="" class="form-label">Customer Name</label>
              <input type="text" class="form-control" name="customer_name" id="customer_name" autocomplete="off">
            </div>
            <div class="mb-3">
              <label for="" class="form-label">Contact Number</label>
              <input type="text" class="form-control number" name="customer_mobile" id="customer_mobile" autocomplete="off">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
{{-- End create customer modal --}}
{{-- Multiple price Modal --}}
<div class="modal fade modalMdHeader" data-bs-backdrop="static" data-bs-keyboard="false" id="modal_multiple_price_list" tabindex="-1" aria-labelledby="modal-1Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-1Label">Select Price Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
            <table class="table" id="price_item_table">
                <thead>
                    <tr>
                        <th>Brand</th>
                        {{-- <th>Product</th> --}}
                        <th>Barcode</th>
                        <th>Selling by</th>
                        <th>Stock</th>
                        <th>MRP</th>
                        <th>Expiry Date</th>
                        <th>Select</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>230KIN</td>
                        <td>Elviton tab</td>
                        <td>Elviton tab</td>
                        <td>Selling by</td>
                        <td>35</td>
                        <td>50</td>
                        <td><button type="button" class="product-select">select</button></td>
                    </tr>
                    <tr>
                        <td>230KIN</td>
                        <td>Elviton tab</td>
                        <td>Elviton tab</td>
                        <td>Selling by</td>
                        <td>35</td>
                        <td>50</td>
                        <td><button type="button" class="product-select">select</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
      </div>
      <div class="modal-footer invoiceBalance">
        <button type="button" class="close-btn" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
{{-- End multiple price modal --}}

<!--<div class="modal fade modalMdHeader" id="modal-2" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="applyCouponBox">
          <form action="" method="get">
            <div class="mb-3">
              <label for="" class="form-label">Scan Barcode/Type Number</label>
              <input type="email" class="form-control" id="">
            </div>
            <div class="mb-3">
              <table class="table table-striped" width="100%">
                <tbody>
                  <tr>
                    <td width="65%">Credit Note Date </td>
                    <td width="35%" align="right">N/A</td>
                  </tr>
                  <tr>
                    <td>Credit Amount</td>
                    <td align="right">0</td>
                  </tr>
                  <tr>
                    <td>Credit Available</td>
                    <td align="right">0</td>
                  </tr>
                  <tr>
                    <td>Apply Credit</td>
                    <td align="right"><input type="text" name="" id="" class="form-control"></td>
                  </tr>
                  <tr>
                    <td><i class="fas fa-rupee-sign"></i> <strong>Payable Amt</strong></td>
                    <td align="right"><strong>16</strong></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <button type="submit" class="btn btn-info">Apply Credit</button>
          </form>
        </div>
      </div>
      <div class="modal-footer invoiceBalance">
        <h6>Invoice Balance: <span>16</span></h6>
      </div>
    </div>
  </div>
</div>-->
<!--<div class="modal fade modalMdHeader" id="modal-3" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="holdBillList">
          <ul>
            <li> <a href="#">
              <div class="d-flex justify-content-between w-100">
                <p><strong>ORD :</strong> <span>HOLD1</span></p>
                <p><strong>Amt :</strong> <i class="fas fa-rupee-sign"></i> 16</p>
              </div>
              <p><strong>Contact Name :</strong> In Customer</p>
              <p><strong>Created On :</strong> <i class="fas fa-calendar-alt"></i> <span>2022-09-01 3:38 pm</span></p>
              </a>
              <div class="print mt-2">
                <button class="btn btn-info"><i class="fas fa-print"></i> print</button>
              </div>
            </li>
            <li> <a href="#">
              <div class="d-flex justify-content-between w-100">
                <p><strong>ORD :</strong> <span>HOLD1</span></p>
                <p><strong>Amt :</strong> <i class="fas fa-rupee-sign"></i> 16</p>
              </div>
              <p><strong>Contact Name :</strong> In Customer</p>
              <p><strong>Created On :</strong> <i class="fas fa-calendar-alt"></i> <span>2022-09-01 3:38 pm</span></p>
              </a>
              <div class="print mt-2">
                <button class="btn btn-info"><i class="fas fa-print"></i> print</button>
              </div>
            </li>
            <li> <a href="#">
              <div class="d-flex justify-content-between w-100">
                <p><strong>ORD :</strong> <span>HOLD1</span></p>
                <p><strong>Amt :</strong> <i class="fas fa-rupee-sign"></i> </p>
              </div>
              <p><strong>Contact Name :</strong> In Customer</p>
              <p><strong>Created On :</strong> <i class="fas fa-calendar-alt"></i> <span>2022-09-01 3:38 pm</span></p>
              </a>
              <div class="print mt-2">
                <button class="btn btn-info"><i class="fas fa-print"></i> print</button>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <div class="modal-footer invoiceBalance">
        <h6>Invoice Balance: <span>16</span></h6>
      </div>
    </div>
  </div>
</div>-->

<section class="payWrap"> <span class="payWrapCloseBtn paymentModalCloseBtn"><i class="fas fa-times-circle"></i></span>
  <div class="p-5">
    <div class="row">
      <div class="payWrapLeft">
        <div class="pmMenu">
          <ul>
            <li><a href="javascript:;" class="active p-method-tab" data-type="cash"><span><img src="{{ url('assets/admin/images/cash.png') }}" alt=""></span> Cash</a></li>
            <li><a href="javascript:;" class="p-method-tab" data-type="card"><span><img src="{{ url('assets/admin/images/card-1.png') }}" alt=""></span> Card</a></li>
            {{-- <li><a href="javascript:;" class="p-method-tab" data-type="gPay"><span><img src="{{ url('assets/admin/images/google-pay.png') }}" alt=""></span> Phonepe / Gpay</a></li>
            <li><a href="javascript:;" class="p-method-tab" data-type="coupon"><span><img src="{{ url('assets/admin/images/coupon.png') }}" alt=""></span> Coupon</a></li>
            <li><a href="javascript:;" class="p-method-tab" data-type="credit_card"><span><img src="{{ url('assets/admin/images/credit-card.png') }}" alt=""></span> Credit</a></li>
            <li><a href="javascript:;" class="p-method-tab" data-type="multiple_pay"><span><img src="{{ url('assets/admin/images/pay-per-click.png') }}" alt=""></span> Multiple Pay</a></li> --}}
          </ul>
        </div>
      </div>
      <div class="payWrapRight">
        <div class="pmDetails">
          <div class="cashOption tab_sec" id="cash_payment_sec">
            <div class="cashOptionTop">
              <ul class="row justify-content-center">
                <li class="col-lg-4 col-md-4 col-sm-6 col-12">
                  <div class="mb-3 cashOptionTopBox">
                    <label for="due_amount_tendering-input" class="form-label">Due Ammout</label>
                    <input type="text" class="form-control tendering-input" id="due_amount_tendering" value="0" readonly="readonly" disabled="disabled"/>
                  </div>
                </li>
                <li class="col-lg-4 col-md-4 col-sm-6 col-12">
                  <div class="mb-3 cashOptionTopBox">
                    <label for="tendered_amount" class="form-label">Tendered</label>
                    <input type="text" class="form-control tendering-input" id="tendered_amount" value="0" onkeypress="return check_character(event);">
                  </div>
                </li>
                <li class="col-lg-4 col-md-4 col-sm-6 col-12">
                  <div class="mb-3 cashOptionTopBox">
                    <label for="tendered_change_amount-input" class="form-label">Change</label>
                    <input type="text" class="form-control tendering-input" id="tendered_change_amount" value="0" readonly="readonly" disabled="disabled"/>
                  </div>
                </li>
              </ul>
            </div>
            <div class="cashOptionBtm text-center">
              <table class="table table-bordered mb-0">
                <tbody>
                  <tr>
                    <td width="20%"><a href="javascript:;" class="tendered_number_btn" data-id="1">1</a></td>
                    <td width="20%"><a href="javascript:;" class="tendered_number_btn" data-id="2">2</a></td>
                    <td width="20%"><a href="javascript:;" class="tendered_number_btn" data-id="3">3</a></td>
                    <td width="20%"><a href="javascript:;" class="tendered_plus_number_btn" data-id="1000">+1000</a></td>
                    <td width="20%"><a href="javascript:;" class="tendered_plus_number_btn" data-id="50000">+50000</a></td>
                  </tr>
                  <tr>
                    <td><a href="javascript:;" class="tendered_number_btn" data-id="4">4</a></td>
                    <td><a href="javascript:;" class="tendered_number_btn" data-id="5">5</a></td>
                    <td><a href="javascript:;" class="tendered_number_btn" data-id="6">6</a></td>
                    <td><a href="javascript:;" class="tendered_plus_number_btn" data-id="5000">+5000</a></td>
                    <td><a href="javascript:;" class="tendered_plus_number_btn" data-id=""></a></td>
                  </tr>
                  <tr>
                    <td><a href="javascript:;" class="tendered_number_btn" data-id="7">7</a></td>
                    <td><a href="javascript:;" class="tendered_number_btn" data-id="8">8</a></td>
                    <td><a href="javascript:;" class="tendered_number_btn" data-id="9">9</a></td>
                    <td><a href="javascript:;" class="tendered_plus_number_btn" data-id="10000">+10000</a></td>
                    <td><a href="javascript:;" class="tendered_plus_number_btn" data-id=""></a></td>
                  </tr>
                  <tr>
                    <td><a href="javascript:;" class="tendered_number_reset">C</a></td>
                    <td><a href="javascript:;" class="tendered_number_btn" data-id="0">0</a></td>
                    <td><a href="javascript:;" class="tendered_number_btn" data-id=".">.</a></td>
                    <td><a href="javascript:;" class="tendered_plus_number_btn" data-id="25000">+25000</a></td>
                    <td><a href="javascript:;" class="tendered_number_btn" data-id="-1"><i class="fas fa-times-circle"></i></a></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="d-flex justify-content-center">
              <ul class="d-flex">
                <li class="col-auto">
                  <button type="button" class="saveBtn-2" id="calculate_cash_payment_btn_update">Submit</button>
                </li>
                <li class="col-auto"><a href="javascript:;" class="saveBtnBdr paymentModalCloseBtn">Cancel</a></li>
              </ul>
            </div>
          </div>
          <div class="noteType tab_sec" style="display:none" id="rupee_payment_sec">
            <div class="cashOptionTop">
              <ul class="row justify-content-center">
                <li class="col-12">
                  <div class="mb-3 cashOptionTopBox">
                    <label for="rupee_due_amount_tendering" class="form-label">Due Ammout</label>
                    <span style="color: #1c0a6b;" id="rupee_due_amount_tendering">0</span> </div>
                  <input type="hidden" id="rupee_due_amount_tendering-input" value="0">
                </li>
              </ul>
            </div>
            <div class="rupeeTableMdArea">
              <div class="row">
                <div class="col-6">
                  <div class="rupeeTableMd">
                    <table class="table noBdr">
                      <tbody>
                        <tr>
                          <td><img src="{{ url('assets/admin/images/2000.jpg') }}" alt=""></td>
                          <td>x</td>
                          <td><input type="text" id="rupee_2000-input" data-type="note" class="input-1 rupee_count_input" onkeypress="return check_character(event);"></td>
                          <input type="hidden" id="rupee_2000" class="input-1 rupee_count">
                          <td>=</td>
                          <td id="amount_per_note-rupee_2000">0</td>
                        </tr>
                        <tr>
                          <td><img src="{{ url('assets/admin/images/500.jpg') }}" alt=""></td>
                          <td>x</td>
                          <td><input type="text" id="rupee_500-input" data-type="note" class="input-1 rupee_count_input" onkeypress="return check_character(event);"></td>
                          <input type="hidden" id="rupee_500" class="input-1 rupee_count">
                          <td>=</td>
                          <td id="amount_per_note-rupee_500">0</td>
                        </tr>
                        <tr>
                          <td><img src="{{ url('assets/admin/images/200.jpg') }}" alt=""></td>
                          <td>x</td>
                          <td><input type="text" id="rupee_200-input" data-type="note" class="input-1 rupee_count_input" onkeypress="return check_character(event);"></td>
                          <input type="hidden" id="rupee_200" class="input-1 rupee_count">
                          <td>=</td>
                          <td id="amount_per_note-rupee_200">0</td>
                        </tr>
                        <tr>
                          <td><img src="{{ url('assets/admin/images/100.jpg') }}" alt=""></td>
                          <td>x</td>
                          <td><input type="text" id="rupee_100-input" data-type="note" class="input-1 rupee_count_input" onkeypress="return check_character(event);"></td>
                          <input type="hidden" id="rupee_100" class="input-1 rupee_count">
                          <td>=</td>
                          <td id="amount_per_note-rupee_100">0</td>
                        </tr>
                        <tr>
                          <td><img src="{{ url('assets/admin/images/50.jpg') }}" alt=""></td>
                          <td>x</td>
                          <td><input type="text" id="rupee_50-input" data-type="note" class="input-1 rupee_count_input" onkeypress="return check_character(event);"></td>
                          <input type="hidden" id="rupee_50" class="input-1 rupee_count">
                          <td>=</td>
                          <td id="amount_per_note-rupee_50">0</td>
                        </tr>
                        <tr>
                          <td><img src="{{ url('assets/admin/images/20.jpg') }}" alt=""></td>
                          <td>x</td>
                          <td><input type="text" id="rupee_20-input" data-type="note" class="input-1 rupee_count_input" onkeypress="return check_character(event);"></td>
                          <input type="hidden" id="rupee_20" class="input-1 rupee_count">
                          <td>=</td>
                          <td id="amount_per_note-rupee_20">0</td>
                        </tr>
                        <tr>
                          <td><img src="{{ url('assets/admin/images/10.jpg') }}" alt=""></td>
                          <td>x</td>
                          <td><input type="text" id="rupee_10-input" data-type="note" class="input-1 rupee_count_input" onkeypress="return check_character(event);"></td>
                          <input type="hidden" id="rupee_10" class="input-1 rupee_count">
                          <td>=</td>
                          <td id="amount_per_note-rupee_10">0</td>
                        </tr>
                        <tr>
                          <td><img src="{{ url('assets/admin/images/5.jpg') }}" alt=""></td>
                          <td>x</td>
                          <td><input type="text" id="rupee_5-input" data-type="note" class="input-1 rupee_count_input" onkeypress="return check_character(event);"></td>
                          <input type="hidden" id="rupee_5" class="input-1 rupee_count">
                          <td>=</td>
                          <td id="amount_per_note-rupee_5">0</td>
                        </tr>
                        <tr>
                          <td><img src="{{ url('assets/admin/images/2.jpg') }}" alt=""></td>
                          <td>x</td>
                          <td><input type="text" id="rupee_2-input" data-type="note" class="input-1 rupee_count_input" onkeypress="return check_character(event);"></td>
                          <input type="hidden" id="rupee_2" class="input-1 rupee_count">
                          <td>=</td>
                          <td id="amount_per_note-rupee_2">0</td>
                        </tr>
                        <tr>
                          <td><img src="{{ url('assets/admin/images/1.jpg') }}" alt=""></td>
                          <td>x</td>
                          <td><input type="text" id="rupee_1-input" data-type="note" class="input-1 rupee_count_input" onkeypress="return check_character(event);"></td>
                          <input type="hidden" id="rupee_1" class="input-1 rupee_count">
                          <td>=</td>
                          <td id="amount_per_note-rupee_1">0</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="col-6">
                  <div class="rupeeTableMd forCoin">
                    <table class="table">
                      <tbody>
                        <tr>
                          <td><img src="{{ url('assets/admin/images/c-10.jpg') }}" alt=""></td>
                          <td>x</td>
                          <td><input type="text" id="coin_10-input" data-type="coin" class="input-1 rupee_count_input" onkeypress="return check_character(event);"></td>
                          <input type="hidden" id="coin_10" class="input-1 rupee_count">
                          <td>=</td>
                          <td id="amount_per_note-coin_10">0</td>
                        </tr>
                        <tr>
                          <td><img src="{{ url('assets/admin/images/c-5.jpg') }}" alt=""></td>
                          <td>x</td>
                          <td><input type="text" id="coin_5-input" data-type="coin" class="input-1 rupee_count_input" onkeypress="return check_character(event);"></td>
                          <input type="hidden" id="coin_5" class="input-1 rupee_count">
                          <td>=</td>
                          <td id="amount_per_note-coin_5">0</td>
                        </tr>
                        <tr>
                          <td><img src="{{ url('assets/admin/images/c-2.jpg') }}" alt=""></td>
                          <td>x</td>
                          <td><input type="text" id="coin_2-input" data-type="coin" class="input-1 rupee_count_input" onkeypress="return check_character(event);"></td>
                          <input type="hidden" id="coin_2" class="input-1 rupee_count">
                          <td>=</td>
                          <td id="amount_per_note-coin_2">0</td>
                        </tr>
                        <tr>
                          <td><img src="{{ url('assets/admin/images/c-1.jpg') }}" alt=""></td>
                          <td>x</td>
                          <td><input type="text" id="coin_1-input" data-type="coin" class="input-1 rupee_count_input" onkeypress="return check_character(event);"></td>
                          <input type="hidden" id="coin_1" class="input-1 rupee_count">
                          <td>=</td>
                          <td id="amount_per_note-coin_1">0</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-center">
              <ul class="d-flex">
                <li class="col-auto">
                  <button type="button" class="saveBtn-2" onclick="final_payment_submit('cash')">Submit</button>
                </li>
                <li class="col-auto"><a href="javascript:;" class="saveBtnBdr" onclick="backToLink('cash_payment_sec','p_tap')">Back</a></li>
              </ul>
            </div>
          </div>
          <div class="applyCoupon tab_sec" id="coupon_payment_sec" style="display: none;">
            <div class="applyCouponTop">
              <h3>Apply Coupon</h3>
              <div class="relative applyCouponInput">
                <input type="text" placeholder="Please Type Coupon Code" class="input-2"/>
                <a href="#" class="ApplyBtn">Apply</a> </div>
            </div>
            <div class="applyCouponBtm">
              <div class="mb-3 text-center invoiceBalance-2"> <span>Invoice Balance : 160</span> </div>
              <form action="get">
                <div class="mb-3">
                  <input type="text" class="form-control input-2" id="" placeholder="Coupon Name">
                </div>
                <div class="mb-3">
                  <input type="text" class="form-control input-2" id="" placeholder="Customer Coupon">
                </div>
                <div class="mb-3">
                  <ul class="d-flex">
                    <li>
                      <button type="button" class="btn btn-primary">Submit</button>
                    </li>
                    <li><a href="#" class="btn btn-outline-secondary ml-3">Cancel</a></li>
                  </ul>
                </div>
              </form>
            </div>
          </div>
          <div class="applyCoupon tab_sec" id="card_payment_sec" style="display: none;">
            <div class="applyCouponTop">
              <h3>Card Details</h3>
              <div class="mb-3">
                <label for="card_payble_amount" class="form-label">Payment Account</label>
                <input type="text" class="form-control input-2" id="card_payble_amount" placeholder="0.00">
              </div>
              <div class="mb-3">
                <label for="card_type" class="form-label">Card Type</label>
                <select class="form-select" name="card_type" id="card_type">
                  <option value="rupay">RuPay</option>
                  <option value="visa">Visa</option>
                  <option value="master_card">Master Card</option>
                  <option value="maestro">Maestro</option>
                  <option value="amex">Amex</option>
                  <option value="diner">Diner</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="card_number" class="form-label">Card No.</label>
                <input type="text" class="form-control input-2" name="card_number" id="card_number" placeholder="Last 4 digits only">
              </div>
              <div class="mb-3">
                <label for="card_invoice_number" class="form-label">Invoice No.</label>
                <input type="text" class="form-control input-2" name="card_invoice_number" id="card_invoice_number" placeholder="Last 4 digits only">
              </div>
              <div class="mb-3">

                <button type="button" class="saveBtn-2" id="calculate_card_payment_btn">Submit</button>
              </div>
            </div>
          </div>
          <div class="paymentOption tab_sec" id="gPay_payment_sec" style="display: none;">
            <div class="paymentOptionTop">
              <ul class="row">
                <li><a href="javascript:;" class="paymentmethod_btn" data-paymentmethod="paytm" id=""><img src="{{ url('assets/admin/images/paytm.jpg') }}" alt=""></a></li>
                <li><a href="javascript:;" class="paymentmethod_btn active" data-paymentmethod="phonepay"><img src="{{ url('assets/admin/images/phonepay.jpg') }}" alt=""></a></li>
                <li><a href="javascript:;" class="paymentmethod_btn" data-paymentmethod="gpay"><img src="{{ url('assets/admin/images/gpay.jpg') }}" alt=""></a></li>
                <li><a href="javascript:;" class="paymentmethod_btn" data-paymentmethod="upi"><img src="{{ url('assets/admin/images/upi.jpg') }}" alt=""></a></li>
              </ul>
            </div>
            <div class="paymentOptionInputBox">

              <div class="mb-3">
                <input type="text" class="paymentOptionInput input-2" id="upi_payble_amount" placeholder="0.00">
              </div>
              <div class="d-flex justify-content-center">
                <ul class="d-flex">
                  <li class="col-auto">
                    <input type="hidden" id="upi_paymentmethod_type" value="phonepay" />
                    <button type="button" class="saveBtn-2" id="calculate_gPay_payment_btn">Submit</button>
                  </li>
                  <li class="col-auto"><a href="javascript:;" class="saveBtnBdr paymentModalCloseBtn">Cancel</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
{{-- <iframe src="{{$data['invoice_url']}}" id="off_counter_invoice-frame" width="400" height="400" style="display:none;"></iframe> --}}
<iframe src="{{asset('storage/uploads/'.$data['invoice_url'])}}" id="off_counter_invoice-frame" width="400" height="400" style="display:none;"></iframe>



<div class="modal fade modalMdHeader" id="modal_settlement" tabindex="-1" aria-labelledby="modal-1Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title appendtitle" id="modal-1Label">Settlement</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post" action="{{ route('admin.settlement_add') }}" id="settlement_form">
        <div class="modal-body">

                @csrf
                <table class="table">
                    <tr>
                        <td style="width: 100px;">1000 </td>
                        <td style="width: 100px;"><input type="number" id="note_1" name="note_count[]" class="form-control"></td>
                        <input type="hidden" name="note_name[]" id="note_name_1" value="1000">
                        <td class="totalnote_1">0</td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">5000 </td>
                        <td style="width: 100px;"><input type="number" id="note_2" name="note_count[]" class="form-control"></td>
                        <input type="hidden" name="note_name[]" id="note_name_2" value="5000">
                        <td class="totalnote_2">0</td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">10000  </td>
                        <td style="width: 100px;"><input type="number" id="note_3" name="note_count[]" class="form-control"></td>
                        <input type="hidden" name="note_name[]" id="note_name_3" value="10000">
                        <td class="totalnote_3">0</td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">25000  </td>
                        <td style="width: 100px;"><input type="number" id="note_4" name="note_count[]" class="form-control"></td>
                        <input type="hidden" name="note_name[]" id="note_name_4" value="25000">
                        <td class="totalnote_4">0</td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">50000  </td>
                        <td style="width: 100px;"><input type="number" id="note_5" name="note_count[]" class="form-control"></td>
                        <input type="hidden" name="note_name[]" id="note_name_5" value="50000">
                        <td class="totalnote_5">0</td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">Total  </td>
                        <td style="width: 100px;"></td>
                        <input type="hidden" name="total_settlement_amount" id="total_settlement_amount" value="">
                        <td class="total_settlement_amount">0</td>
                    </tr>
                </table>

                <button class="btn btn-primary" type="submit">Submit</button>

        </div>
    </form>
      </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
  var stock_type	= "s";
  </script>

<script src="{{ url('assets/admin/js/jquery.scannerdetection.js') }}"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="{{ url('assets/admin/js/pos.js') }}"></script>
<script type="text/javascript" src="{{ url('assets/admin/js/fullscreen/jquery.fullscreen.min.js') }}"></script>
<script>

$(function () {
	  $('[data-toggle="tooltip"]').tooltip();
});


$(document).keyup(function(e){
   if(e.which==122){
       e.preventDefault();

       console.log($('.content').hasClass('full-mode'));

        if ($('.content').hasClass('full-mode')==false) {

            $('.content').addClass('full-mode');
            $('#fullscreen .requestfullscreen').hide();
            $('#fullscreen .exitfullscreen').show();

        }else{
            $('.content').removeClass('full-mode');
            $('#fullscreen .requestfullscreen').show();
            $('#fullscreen .exitfullscreen').hide();
        }

       return false;
   }
});

@if (Session::has('success'))
    $(document).ready(function() {
        Swal.fire({
            title: 'Success',
            text: '{{ Session::get('success') }}',
            icon: 'success',
        });
    });
@endif

$(document).ready(function() {
    total_cal();
    // $('#modal-applyDiscount').modal('show');
    window.onload = setTimeout(function(){
        $("#applyDiscount-form").submit();
    }, 1000);

});

</script>


@endsection
