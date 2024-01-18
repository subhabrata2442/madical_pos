@extends('layouts.front')
@section('front-content')
{{-- <section class="loginWrap d-flex flex-wrap" style="background: url({{ asset('assets/img/login-bg-md.jpg') }}) no-repeat center center;"> --}}
    <section class="loginWrap d-flex">

    <div class="loginWrapLeft d-flex flex-wrap align-items-end" style="background: url({{ asset('assets/img/login-bg-md2.jpg') }}) no-repeat center center;">
        <img src="{{ asset('assets/img/left-img2.png') }}" alt="">
      </div>
  <div class="loginWrapRight d-flex flex-wrap align-items-center justify-content-center" style="background: url({{ asset('assets/img/login-bg-right2.jpg') }}) no-repeat center center;">

    <div class="loginFormFild">
      <form class="" method="post" action="{{ route('auth.do_login') }}" autocomplete="off">
      @csrf
      <div class="posLogo">
        <span class="mb-4"><img src="{{ asset('assets/img/pos-logo.png') }}" alt=""></span>
        <x-alert />
      </div>
        <!-- <h3>Pos System</h3> -->
        <div class="logtextBox">
          <div class="form-group position-relative add-lft-icon"> <span class="left-icon"> <i class="fas fa-user"></i> </span>
            <input type="text" name="email" class="form-control inpyt-style" placeholder="Email" autocomplete="new-email" value="{{ old('email') }}">
            @error('email')
            <div class="error">{{ $message }}</div>
              @enderror
          </div>
          <div class="form-group position-relative add-lft-icon add-rgt-icon add_eye"> <span class="left-icon"> <i class="fas fa-key"></i> </span>
            <input type="password" class="form-control inpyt-style pass_input" id="password-field" placeholder="Password" name="password" autocomplete="password-field">
            <span class="rgt-icon pass_eye" toggle="#password-field"> <i class="fas fa-eye-slash toggle-password"></i> </span> @error('password')
            <div class="error">{{ $message }}</div>
            @enderror
          </div>
          <div class="d-flex align-items-center justify-content-center">
            {{-- <li class="checkbox chk-wrap">
              <input type="checkbox" id="keep-me-logged" name="remember_me" value="1">
              <label for="keep-me-logged">keep me logged</label>
            </li> --}}
            <li><a href="javascript:;" class="forget-pass" data-bs-toggle="modal" data-bs-target="#exampleModal">forget password</a></li>
          </div>
        </div>
        <div class="log-reg-btn-wrap d-flex justify-content-center">
          <button type="submit" class="log-reg-btn">log in</button>
        </div>
      </form>
    </div>
  </div>
  <!-- <div class="loginWrapLeft d-flex flex-wrap align-items-end">
    <img src="{{ asset('assets/img/left-img.png') }}" alt="">
  </div> -->

@if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1:8000')
    <div class="data-sync-wrap">
        <div class="data-sync-txt">
            {{--<a href="#" class="data-sync-btn"><i class="fas fa-sync"></i>click to data sync</a>--}}
            <button type="button" class="data-sync-btn" id="database_sync_btn"><i class="fas fa-sync data_sync"></i>click to data sync</button>
        </div>

    </div>
@endif

</section>

<div class="modal fade editPassword" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Forget Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('auth.forgotpassword') }}" method="POST" class="needs-validation" novalidate id="forgotpasswordForm">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <input type="email" class="form-control" id="" placeholder="Enter Email" name="email" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="resetBtn">Submit</button>
            </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>

<script>
    $(document).on('click', '.pass_eye', function(){
        if($(this).closest('.add_eye').find(".pass_input").attr('type')=='password'){
        $(this).closest('.add_eye').find(".pass_input").attr('type','text');
        $(this).closest('.add_eye').find(".fa-eye-slash").addClass("fa-eye").removeClass("fa-eye-slash");
        }else{
        $(this).closest('.add_eye').find(".pass_input").attr('type', 'password');
        $(this).closest('.add_eye').find(".fa-eye").removeClass("fa-eye").addClass("fa-eye-slash");
        }
    });


    $("#forgotpasswordForm").validate({
        rules: {},
        messages: {},
        errorElement: "em",
        errorPlacement: function(error, element) {},
        highlight: function(element, errorClass, validClass) {},
        unhighlight: function(element, errorClass, validClass) {},
        submitHandler: function(form) {
            var formData = new FormData($(form)[0]);
            $.ajax({
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                url: $('#forgotpasswordForm').attr('action'),
                dataType: 'json',
                data: formData,
                success: function(data) {

                    if(data==0){
                        Swal.fire({
                            title: 'User not found!',
                            icon: 'error',
                            showDenyButton: false,
                            showCancelButton: false,
                            allowOutsideClick: false
                        });
                    }else if(data==2){
                        Swal.fire({
                            title: 'Something went wrong!',
                            icon: 'error',
                            showDenyButton: false,
                            showCancelButton: false,
                            allowOutsideClick: false
                        });
                    }else if(data==1){
                        Swal.fire({
                            title: 'Password reset successfully please check your email',
                            icon: 'success',
                            showDenyButton: false,
                            showCancelButton: false,
                            allowOutsideClick: false
                        });
                    }

                },
                beforeSend: function() {
                    $('#resetBtn').html('Please Wait...');
                    $('#resetBtn').attr('disabled','disabled');
                },
                complete: function() {

                    $('#resetBtn').html('Submit');
                    $("#resetBtn").prop("disabled", false);


                }
            });
        }
    });


        $(document).on("click","#database_sync_btn",function() {
            $('.data_sync').addClass('fa-spin');
            $('#database_sync_btn').attr('disabled','disabled');
            $.ajax({
                    type: "GET",
                    cache: false,
                    url: '{{route('database_sync')}}',
                success: function(data) {
                    $('.data_sync').removeClass('fa-spin');
                    $('#database_sync_btn').removeAttr('disabled');
                },
                beforeSend: function() {
                    $('.data_sync').addClass('fa-spin');
                },
                complete: function() {
                    $('.data_sync').removeClass('fa-spin');
                    $('#database_sync_btn').removeAttr('disabled');
                }
            });
        });


</script>

<style>
    .data-sync-wrap {
        position: fixed;
        right: 5px;
        bottom: 20px;
        z-index: 2;
    }
    .data-sync-btn {
        display: block;
        border-radius: 5px;
        font-size: 1rem;
        background-color: #039;
        color: #fff !important;
        height: 40px;
        line-height: 40px;
        padding: 0 1rem;
        border: none;
        box-shadow: none;
    }
    .data-sync-btn svg, .data-sync-btn i {
        margin-right: 0.5rem;
    }
</style>

@endsection




