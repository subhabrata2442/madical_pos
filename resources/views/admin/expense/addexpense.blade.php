@extends('layouts.admin')
@section('admin-content')
<div class="row">
  <div class="col-12">
    <form method="post" action="{{ route('admin.expense.addexpense') }}" class="needs-validation" novalidate enctype="multipart/form-data">
      @csrf
      <input type="hidden" id="product_id" value="">
      <div class="card">
        <div class="row">
          <x-alert />
          <div class="col-md-4">
            <div class="form-group">
                <label for="category_id" class="form-label">Select Category</label>
                <select name="category_id" id="category_id" class="form-control form-inputtext" required>
                    <option value="">Select Category</option>
                    @foreach ($data['category'] as $key=>$item)
                        <option value="{{$item->id}}">{{$item->category_name}}</option>
                    @endforeach
                </select>

                @error('category_id')
                <div class="error admin-error">{{ $message }}</div>
                @enderror
            </div>
          </div>

            @if (Auth::user()->role == 1)
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                    <div class="form-group">
                        <label for="storelist" class="form-label">Select Store</label>
                            <select name="branch_id" id="branch_id" class="form-control form-inputtext" required>
                                <option value="">Select Store</option>
                                @foreach ($data['storelist'] as $key=>$storeitem)
                                    <option value="{{$storeitem->id}}">{{$storeitem->name}}</option>
                                @endforeach
                            </select>
                    </div>
                </div>
			@endif

          <div class="col-md-4">
            <div class="form-group">
              <label for="amount" class="form-label">Amount</label>
              <input type="text" class="form-control admin-input isnumber" id="amount" name="amount" value="{{ old('amount') }}" required  autocomplete="off" placeholder="Amount">
              @error('amount')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label for="expense_date" class="form-label">Date</label>
              <input type="date" class="form-control admin-input" id="expense_date" name="expense_date" value="{{ old('expense_date') }}" required  autocomplete="off">
              @error('expense_date')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
                <label for="product_barcode" class="form-label">Notes</label>
                <textarea class="form-control" name="notes" id="notes" cols="5" rows="2"></textarea>

                @error('category_id')
                <div class="error admin-error">{{ $message }}</div>
                @enderror
            </div>
          </div>
          <div class="col-12">
            <button class="commonBtn-btnTag" type="submit">Submit </button>
          </div>

        </div>
      </div>
    </form>
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
