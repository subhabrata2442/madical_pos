@extends('layouts.admin')
@section('admin-content')

@php
$selected_permission=array();
foreach($data['role_wise_permission'] as $row){
	$selected_permission[]=$row->permission_id;
}

//print_r($selected_permission);exit;

@endphp


<div class="row">
  <div class="col-12">
    <div class="card">
      <x-alert />
      <div class="table-responsive dataTable-design">
        <div class="card">
          <form method="post" action="{{ route('admin.user.role_save_update') }}" class="needs-validation" id="user-form" novalidate enctype="multipart/form-data">
          <input type="hidden" name="role_id" value="{{$data['role_id']}}" />
            @csrf
            <div class="row"> @foreach ($data['permission'] as $value)
              <div class="col-md-6">
                <div class="form-group">
                  <label for="password" class="form-label" id="role_{{ $value->id }}">{{ $value->title }}</label>
                  <input type="checkbox" name="permission[]" id="role_{{ $value->id }}" value="{{ $value->id }}" <?php if(isset($selected_permission)){if (in_array($value->id, $selected_permission)){ echo 'checked="checked"';}} ?>  />
                </div>
              </div>
              @endforeach </div>
            <div class="row">
              <div class="col-12">
                <button class="commonBtn-btnTag" type="submit">Submit </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts') 
<script type="text/javascript">
</script> 
@endsection 