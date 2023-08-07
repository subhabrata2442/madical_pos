@extends('layouts.admin')
@section('admin-content')
<div class="row">
  <div class="col-12">
    <form method="post" action="{{ route('admin.product.add') }}" class="needs-validation" novalidate enctype="multipart/form-data">
      @csrf
      <div class="card">
        <div class="row">
          <x-alert />
          <div class="col-md-4">
            <div class="form-group">
              <label for="product_name" class="form-label">Brand Name</label>
              <input type="text" class="form-control admin-input" id="product_name" name="product_name" value="{{ old('product_name') }}" required autocomplete="off">
              @error('product_name')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="product_code" class="form-label">Product Barcode</label>
              <input type="text" class="form-control admin-input" id="product_code" name="product_code"
                                    value="{{ old('product_code') }}" required  autocomplete="off">
              @error('product_code')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="sku_code" class="form-label">Product SKU</label>
              <input type="text" class="form-control admin-input" id="sku_code" name="sku_code" value="{{ old('sku_code') }}"  autocomplete="off">
              @error('sku_code')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="alert_product_qty" class="form-label">Low Stock Alert</label>
              <input type="text" class="form-control admin-input" id="alert_product_qty" name="alert_product_qty" value="{{ old('alert_product_qty') }}"  autocomplete="off">
              @error('alert_product_qty')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="default_qty" class="form-label">MOQ (Minimum Order Quantity)</label>
              <input type="text" class="form-control admin-input" id="default_qty" name="default_qty" value="{{ old('default_qty') }}"  autocomplete="off">
              @error('default_qty')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="days_before_product_expiry" class="form-label">Alert Before Product Expiry(Days)</label>
              <input type="text" class="form-control admin-input" id="days_before_product_expiry" name="days_before_product_expiry" value="{{ old('days_before_product_expiry') }}"  autocomplete="off">
              @error('days_before_product_expiry')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
          </div>
        </div>
      </div>
      <div class="card"> <a href="javascript:;" class="toggleBtn">Others Options <i class="fas fa-caret-down"></i></a> </div>
      <div class="card toggleArea" style="display: none;">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="product_desc" class="form-label">Product Description</label>
              <textarea name="product_desc" rows="3" id="product_desc" class="form-control admin-textarea">{{ old('product_desc') }}</textarea>
              @error('product_desc')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="product_note" class="form-label">Product Note</label>
              <textarea name="product_note" rows="3" id="product_note" class="form-control admin-textarea">{{ old('product_note') }}</textarea>
              @error('product_note')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
          </div>
        </div>
      </div>
      {{-- <div class="add_more_size_btn"><a href="javascript:;"><i class="fa fa-plus" aria-hidden="true"></i></a></div> --}}
      <div class="add_more_size_section" id="add_more_size_section_row">
      
        <div class="card" id="add_more_size_row_0">
          <div class="row">
           
            <div class="col-md-3">
              <div class="form-group">
                <label for="cost_rate" class="form-label">Cost Rate</label>
                <input type="text" class="form-control admin-input cost_rate" id="cost_rate_0" name="cost_rate[]" value="" required autocomplete="off">
                @error('cost_rate')
                <div class="error admin-error">{{ $message }}</div>
                @enderror </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="cost_gst_percent" class="form-label">Cost GST %</label>
                <input type="text" class="form-control admin-input cost_gst_percent" id="cost_gst_percent_0" name="cost_gst_percent[]" value="0" required autocomplete="off">
                @error('cost_gst_percent')
                <div class="error admin-error">{{ $message }}</div>
                @enderror </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="cost_gst_amount" class="form-label">Cost GST &#8377; </label>
                <input type="text" class="form-control admin-input notallowinput" id="cost_gst_amount_0" name="cost_gst_amount[]" value="0" autocomplete="off">
                @error('cost_gst_amount')
                <div class="error admin-error">{{ $message }}</div>
                @enderror </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="cost_price" class="form-label">Cost Price</label>
                <input type="text" class="form-control admin-input notallowinput" id="cost_price_0" name="cost_price[]" value="0"  autocomplete="off">
                @error('cost_price')
                <div class="error admin-error">{{ $message }}</div>
                @enderror </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="extra_charge" class="form-label">Extra Charge</label>
                <input type="text" class="form-control admin-input extra_charge" id="extra_charge_0" name="extra_charge[]" value="0"  autocomplete="off">
                @error('extra_charge')
                <div class="error admin-error">{{ $message }}</div>
                @enderror </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="profit_percent" class="form-label">Profit %</label>
                <input type="text" class="form-control admin-input profit_percent" id="profit_percent_0" name="profit_percent[]" value="0"  autocomplete="off">
                @error('profit_percent')
                <div class="error admin-error">{{ $message }}</div>
                @enderror </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="profit_amount" class="form-label">Profit &#8377; </label>
                <input type="text" class="form-control admin-input notallowinput" id="profit_amount_0" name="profit_amount[]" value="0"  autocomplete="off">
                @error('profit_amount')
                <div class="error admin-error">{{ $message }}</div>
                @enderror </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="selling_price" class="form-label">Selling Rate</label>
                <input type="text" class="form-control admin-input notallowinput" id="selling_price_0" name="selling_price[]" value="0"  autocomplete="off">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="sell_gst_percent" class="form-label">Sell GST %</label>
                <input type="text" class="form-control admin-input sell_gst_percent" id="sell_gst_percent_0" name="sell_gst_percent[]" value="0"  autocomplete="off">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="sell_gst_amount" class="form-label">Sell GST &#8377; </label>
                <input type="text" class="form-control admin-input notallowinput" id="sell_gst_amount_0" name="sell_gst_amount[]" value="0"  autocomplete="off">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="offer_price" class="form-label">Offer Price</label>
                <input type="text" class="form-control admin-input number offer_price" id="offer_price_0" name="offer_price[]" value="0"  autocomplete="off">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="product_mrp" class="form-label">Product MRP <a href="javascript:;" data-toggle="tooltip" class="pa-0 ma-0  bold" style="font-size:20px;" data-placement="top" title="Profit is calculated on Offer Price, and not on MRP.  If you do not have Offer Price then you can keep Offer Price same as MRP." data-content="" ><i class="fa fa-eye cursor"></i></a></label>
                <input type="text" class="form-control admin-input number" id="product_mrp_0" name="product_mrp[]" value="0"  autocomplete="off">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="wholesale_price" class="form-label">Wholesale Price</label>
                <input type="text" class="form-control admin-input number" id="wholesale_price_0" name="wholesale_price[]" value="0"  autocomplete="off">
              </div>
            </div>
          </div>
        </div>
        
        
      </div>
      {{-- <div class="card">
        <div class="row">
          <div class="suppliers-table table-responsive">
            <table class="table text-nowrap bt-none">
              <thead>
                <tr>
                  <th>Image</th>
                  <th>Product Image Caption</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><div class="product-image"> <a href="javascript:;" class="preview fetch_image" id="thumb-image"><img src="{{ $data['thumb'] ??  ''}}" alt="{{ $thumb ?? ''}}" width="150px"></a>
                      <input type="hidden" name="image" value="{{$data->image ?? ''}}" id="input-image">
                      <input name="upload_photo" id="upload_photo" style="display:none" onchange="preview_image(this.files)" type="file">
                    </div></td>
                  <td><input type="text" id="product_image_caption" name="product_image_caption" class="form-control admin-input"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div> --}}
      <div class="card">
        <div class="row">
          <div class="col-md-4 plusBoxWrap relative">
            <div class="form-group">
              <label for="subcategory" class="form-label">Dosage Form</label>
              <select name="subcategory" id="subcategory" class="form-control form-inputtext">
                <option value="">Select Dosage Form</option>
                @if(count($data['dosage'])>0)
                @foreach($data['dosage'] as $row)
                <option value="{{$row->id}}">{{$row->name}}</option>
                @endforeach
                @endif
              </select>
              @error('subcategory')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
            <div class="plusBox"><a href="javascript:;" class="plusBoxBtn addmoreoption" data-type="subcategory" data-title="Subcategory"><i class="fas fa-plus"></i></a></div>
          </div>
          <div class="col-md-4 plusBoxWrap relative">
            <div class="form-group">
              <label for="color" class="form-label">Company</label>
              <select name="color" id="color" class="form-control form-inputtext">
                <option value="">Select Company</option>
                @if(count($data['company'])>0)
                @foreach($data['company'] as $row)
                <option value="{{$row->id}}">{{$row->name}}</option>
                @endforeach
                @endif
              </select>
              @error('color')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
            <div class="plusBox"><a href="javascript:;" class="plusBoxBtn addmoreoption" data-type="color" data-title="Color"><i class="fas fa-plus"></i></a></div>
          </div>
          <div class="col-md-4 plusBoxWrap relative">
            <div class="form-group">
              <label for="material" class="form-label">Drugstore name</label>
              <select name="material" id="material" class="form-control form-inputtext">
                <option value="">Select Drugstore</option>
                @if(count($data['company'])>0)
                @foreach($data['company'] as $row)
                <option value="{{$row->id}}">{{$row->name}}</option>
                @endforeach
                @endif
              </select>
              @error('material')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
            <div class="plusBox"><a href="javascript:;" class="plusBoxBtn addmoreoption" data-type="material" data-title="Material"><i class="fas fa-plus"></i></a></div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="row">
          <div class="row">
            <div class="col-12">
              <button class="commonBtn-btnTag" type="submit">Submit </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="modal fade" id="addproducts_features">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title dnamic_feature_title"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
      <div class="container"></div>
      <form id="productfeaturesform">
        <input type="hidden" id="product_features_type" autocomplete="off">
        <div class="modal-body">
          <div class="input-group input-group-default floating-label">
            <label class="form-label dnamic_feature_name"> </label>
            <input class="form-control form-inputtext" autocomplete="off" name="product_feature_data_value" id="product_feature_data_value" maxlength="100" type="text" placeholder=" ">
          </div>
          <span id="sizeerr" style="color: red;font-size: 15px"></span> </div>
        <div class="modal-footer"><a href="javascript:;" data-dismiss="modal" class="btn">Close</a>
          <button type="button" id="productfeaturessave" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts') 
<script src="{{ url('assets/admin/js/product.js') }}"></script> 
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script> 
<script>
  $(document).ready(function(){
  $(".toggleBtn").click(function(){
    $(".toggleArea").slideToggle();
  });
});
</script> 
@endsection 