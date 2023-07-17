@extends('layouts.admin')
@section('admin-content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <form method="post" action="{{ route('admin.embloyees.add') }}" class="needs-validation" id="user-form" novalidate enctype="multipart/form-data">
        @csrf
        <div class="row">
          <x-alert />
          <div class="col-md-6">
            <div class="form-group">
              <label for="full_name" class="form-label">Store Name</label>
              <input type="text" class="form-control admin-input" id="full_name" name="full_name" value="" required autocomplete="off">
              @error('full_name')
              <div class="error">{{ $message }}</div>
              @enderror </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="Email" class="form-label">Email</label>
              <input type="text" class="form-control admin-input" id="Email" name="email" value="" required autocomplete="off">
              @error('email')
              <div class="error">{{ $message }}</div>
              @enderror </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="phone" class="form-label">Phone</label>
              <input type="text" class="form-control admin-input" id="phone" name="phone" value="" required autocomplete="off">
              @error('phone')
              <div class="error">{{ $message }}</div>
              @enderror </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="password" class="form-label">Password</label>
              <input type="text" class="form-control admin-input" id="password" name="password" autocomplete="off">
              @error('password')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
          </div>
         
        </div>

        <div class="row">
          <div class="col-12">
            <div class="manage-role-type">
              <h3>Manage Role</h3>
              <ul class="d-flex justify-content-center">
                @foreach ($data['role'] as $value)
                <li id="role_{{$value->id}}" class="manage_role_li">
                  <button type="button" class="manage-role-btn manage-role" data-id="{{$value->id}}"> <span class="manage-role-icon"><i class="fa fa-lock" id="role-icon_{{$value->id}}"></i></span>{{ $value->title }} </button>
                </li>
                @endforeach
              </ul>
              <div style="display: none" id="manage-role-input-section"></div>
              
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <button class="commonBtn-btnTag" type="submit">Submit </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  $(document).on('click', '.manage-role', function() {
      var id = $(this).data('id');
      if ($("#role_"+id+".active").length > 0){
        $("#role_"+id).removeClass('active');
        $('#role-icon_'+id).removeClass('fa-unlock').addClass('fa-lock');
        //console.log('remove',id);
      } else {
        $("#role_"+id).addClass('active');
        $('#role-icon_'+id).removeClass('fa-lock').addClass('fa-unlock');
        //console.log('add',id);
      }

      var manage_role_input='';
      $('.manage_role_li.active').map(function(){
       var active_roll_id=$(this).find('.manage-role').data('id');
       manage_role_input +='<input type="hidden" name="roll[]" value="'+active_roll_id+'">';
      });
      $('#manage-role-input-section').html(manage_role_input);

  });
</script> 
@endsection 