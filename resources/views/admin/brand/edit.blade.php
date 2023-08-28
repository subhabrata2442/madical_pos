@extends('layouts.admin')
@section('admin-content')
<form method="post" action="{{ route('admin.brand.edit', [base64_encode($data['brand']->id)]) }}" class="needs-validation" id="brand-form" novalidate enctype="multipart/form-data">
  @csrf
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body greybg">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                  <label for="name" class="form-label required">Brand Name</label>
                  <input type="text" class="form-control admin-input" id="name" name="name" value="{{ old('name', $data['brand']->name) }}"  autocomplete="off" required>
                  @error('name')
                    <div class="error admin-error">{{ $message }}</div>
                  @enderror
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12">
    <button class="commonBtn-btnTag" type="submit">Submit </button>
  </div>
</form>
@endsection

@section('scripts') 
{{-- <script src="{{ url('assets/admin/js/supplier.js') }}"></script>  --}}
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script> 
<script>



$(document).ready(function() {
    $("#brand-form").validate({
        rules: {
            name: "required",
           
        },
        messages: {
            //promo: "Required",
        },
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
            var formData = new FormData($(form)[0]);
            $.ajax({
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                url: "{{ route('admin.brand.edit', [base64_encode($data['brand']->id)]) }}",
                dataType: 'json',
                data: formData,
                success: function(data) {
                    if (data[0].success == 1) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Brand Updated successfully',
                            showConfirmButton: false,
                            timer: 2500
                        });
                        
                    } else {
                      Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data[0].error_message,
                        })
                    }
                    //$("html, body").animate({ scrollTop: 0 }, "slow");
                },
                beforeSend: function() {
                    $(".loadingSpan").show();
                },
                complete: function() {
                    $(".loadingSpan").hide();
                }
            });
        }
    });
});


</script> 
@endsection 