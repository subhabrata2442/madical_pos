@extends('layouts.admin')
@section('admin-content')
<form method="post" action="{{ route('admin.updatesetting') }}" class="needs-validation" novalidate enctype="multipart/form-data">
  @csrf
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body greybg">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                 <label for="name" class="form-label required">Sale Return Duration</label>

                 <select name="return_bill_time" id="return_bill_time" class="form-control admin-input">
                    <option value="">Select day</option>
                    @for ($i = 1; $i <=30; $i++)
                        <option value="{{$i}}" @if ($i==$data['settingdata']->return_bill_time) selected @endif>{{$i}} Day</option>
                    @endfor

                 </select>
                  </div>
            </div>
        </div>
      </div>
      <input type="hidden" name="id" value="{{$data['settingdata']->id}}">
      <div class="col-12 text-center">
        <button class="commonBtn-btnTag" type="submit">Submit </button>
      </div>
    </div>
  </div>

</form>
@endsection

@section('scripts')
<script src="{{ url('assets/admin/js/supplier.js') }}"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script>

$(document).on('click','#supplier_additional_details_btn',function(){
	 $('#supplier_additional_details_sec').toggle();
});

$(document).ready(function() {
    $("#brand-form").validate({
        rules: {
            supplier_company_name: "required",
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
                url: "{{ route('admin.brand.add') }}",
                dataType: 'json',
                data: formData,
                success: function(data) {
                    if (data[0].success == 1) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Brand Created successfully',
                            showConfirmButton: false,
                            timer: 2500
                        });
						window.location.replace("{{ route('admin.brand.list') }}");
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data[0].error_message,
                        })
                        //alert(data[0].error_message);
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
