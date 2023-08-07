@extends('layouts.admin')
@section('admin-content')
<div class="row">
  <div class="col-12">
    <form method="post" action="{{ route('admin.product.edit', [base64_encode($data['products']->id)]) }}"
      class="needs-validation" novalidate enctype="multipart/form-data">
      @csrf
      <input type="hidden" id="product_id" value="{{$data['products']->id}}">
      <div class="card">
        <div class="row">
          <x-alert />
          <div class="col-md-4">
            <div class="form-group">
              <label for="product_name" class="form-label">Brand Name</label>
              <input type="text" class="form-control admin-input" id="product_name" name="product_name" value="{{ $data['products']->brand }}" required autocomplete="off">
              @error('product_name')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="product_barcode" class="form-label">Product Barcode</label>
              <input type="text" class="form-control admin-input" id="product_barcode" name="product_barcode" value="{{ $data['products']->product_barcode }}" required  autocomplete="off">
              @error('product_barcode')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="sku_code" class="form-label">Product SKU</label>
              <input type="text" class="form-control admin-input" id="sku_code" name="sku_code" value="{{ $data['products']->sku_code }}"  autocomplete="off">
              @error('sku_code')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="alert_product_qty" class="form-label">Low Stock Alert</label>
              <input type="text" class="form-control admin-input" id="alert_product_qty" name="alert_product_qty" value="{{ $data['products']->alert_product_qty }}"  autocomplete="off">
              @error('alert_product_qty')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="default_qty" class="form-label">MOQ (Minimum Order Quantity)</label>
              <input type="text" class="form-control admin-input" id="default_qty" name="default_qty" value="{{ $data['products']->default_qty }}"  autocomplete="off">
              @error('default_qty')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="days_before_product_expiry" class="form-label">Alert Before Product Expiry(Days)</label>
              <input type="text" class="form-control admin-input" id="days_before_product_expiry" name="days_before_product_expiry" value="{{ $data['products']->days_before_product_expiry }}"  autocomplete="off">
              @error('days_before_product_expiry')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
          </div>
        </div>
      </div>
      
      
      
      <div class="card">
        <div class="row">
          <div class="col-md-4 plusBoxWrap relative">
            <div class="form-group">
              <label for="dosage" class="form-label">Dosage Form</label>
              <select name="dosage" id="dosage" class="form-control form-inputtext">
                <option value="">Select Dosage Form</option>
                @if(count($data['dosage'])>0)
                @foreach($data['dosage'] as $row)
                <option value="{{$row->id}}" {{ ($data['products']->dosage_id == $row->id ? "selected":"") }}>{{$row->name}}</option>
                @endforeach
                @endif
              </select>
              @error('dosage')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
            <div class="plusBox"><a href="javascript:;" class="plusBoxBtn addmoreoption" data-type="dosage" data-title="Dosage Form"><i class="fas fa-plus"></i></a></div>
          </div>
          <div class="col-md-4 plusBoxWrap relative">
            <div class="form-group">
              <label for="company" class="form-label">Company</label>
              <select name="company" id="company" class="form-control form-inputtext">
                <option value="">Select Company</option>
                @if(count($data['company'])>0)
                @foreach($data['company'] as $row)
                <option value="{{$row->id}}" {{ ($data['products']->company_id == $row->id ? "selected":"") }}>{{$row->name}}</option>
                @endforeach
                @endif
              </select>
              @error('company')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
            <div class="plusBox"><a href="javascript:;" class="plusBoxBtn addmoreoption" data-type="company" data-title="Company"><i class="fas fa-plus"></i></a></div>
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
        <button type="button" class="close modal_close_btn" data-dismiss="modal" aria-hidden="true">×</button>
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
        <div class="modal-footer"><a href="javascript:;" data-dismiss="modal" class="btn modal_close_btn">Close</a>
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