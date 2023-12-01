@extends('layouts.admin')
@section('admin-content')
<div class="row">
   <div class="col-12">
      <div class="card">
         <form method="post" action="{{ route('admin.store.add') }}" class="needs-validation" id="user-form" novalidate
            enctype="multipart/form-data">
            @csrf
            <div class="row">
               <x-alert />
               <div class="col-md-6">
                  <div class="form-group">
                     <label for="full_name" class="form-label">Store Name</label>
                     <input type="text" class="form-control admin-input" id="full_name" name="full_name" value=""
                        required autocomplete="off">
                     @error('full_name')
                     <div class="error">{{ $message }}</div>
                     @enderror </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label for="Email" class="form-label">Email</label>
                     <input type="text" class="form-control admin-input" id="Email" name="email" value="" required
                        autocomplete="off">
                     @error('email')
                     <div class="error">{{ $message }}</div>
                     @enderror </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label for="phone" class="form-label">Phone</label>
                     <input type="text" class="form-control admin-input" id="phone" name="phone" value="" required
                        autocomplete="off">
                     @error('phone')
                     <div class="error">{{ $message }}</div>
                     @enderror </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label for="password" class="form-label">Password</label>
                     <input type="text" class="form-control admin-input" id="password" name="password" autocomplete="off" required>
                     @error('password')
                     <div class="error admin-error">{{ $message }}</div>
                     @enderror </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label for="password" class="form-label">Address</label>
                     <input type="text" class="form-control admin-input" id="address" name="address"
                        autocomplete="off" required>
                     @error('address')
                     <div class="error admin-error">{{ $message }}</div>
                     @enderror </div>
               </div>

            </div>

            <div class="row">
               <div class="col-12">
                 <div class="manage-role-type-section">
                   <h3>Manage Role</h3>
                   <table border="0" width="100%" cellpadding="6" cellspacing="2">
                     <tbody>
                       <tr>
                         <td width="20%" bgcolor="#f3f3f3" class="center bold leftAlign">Module Name</td>
                         <td width="10%" bgcolor="#f3f3f3" class="center bold">View</td>
                         <td width="10%" bgcolor="#f3f3f3" class="center bold">Add</td>
                         <td width="10%" bgcolor="#f3f3f3" class="center bold">Edit</td>
                         <td width="10%" bgcolor="#f3f3f3" class="center bold">Delete</td>
                         <td width="10%" bgcolor="#f3f3f3" class="center bold">Download</td>
                         <td width="10%" bgcolor="#f3f3f3" class="center bold">Print</td>
                         <td width="10%" bgcolor="#f3f3f3" class="center bold">Upload</td>
                       </tr>
                     </tbody>

                     <tbody id="sproduct_detail_record">
                       @php($count=0)
                       @foreach ($data['store_role'] as $value)
                       <tr class="trborderbottom" id="module_{{$count}}">
                         <td bgcolor="#f3f3f3" class="leftAlign" style="text-transform:uppercase;">
                           <b class="redcolor">{{$value['title']}}</b>
                           <input type="checkbox" onclick="pagesCheck({{$count}})" name="roll_ids[{{$count}}]"
                             id="ModuleName_{{$count}}" value="{{$value['roll_id']}}" <?php if($value['is_checked']=='Y'){ echo 'checked';} ?>>
                         </td>
                         <td bgcolor="#f3f3f3" class="center titleover mouseover{{$count}}">View</td>
                         <td bgcolor="#f3f3f3" class="center titleover mouseover{{$count}}">Add</td>
                         <td bgcolor="#f3f3f3" class="center titleover mouseover{{$count}}">Edit</td>
                         <td bgcolor="#f3f3f3" class="center titleover mouseover{{$count}}">Delete</td>
                         <td bgcolor="#f3f3f3" class="center titleover mouseover{{$count}}">Download</td>
                         <td bgcolor="#f3f3f3" class="center titleover mouseover{{$count}}">Print</td>
                         <td bgcolor="#f3f3f3" class="center titleover mouseover{{$count}}">Upload</td>
                       </tr>
                       @foreach ($value['sub_roll'] as $subRoll)
                       <tr class="trborderbottom" id="RoleValues_{{$count}}">
                         <td bgcolor="#f3f3f3" class="leftAlign" id="submoduleid_{{$subRoll['roll_id']}}">
                           {{$subRoll['title']}}
                           <input type="hidden" name="employee_role_permission_id[{{$count}}][]"
                             value="{{$subRoll['roll_id']}}">
                         </td>
                         <td class="center" onmouseover="checkboxOver({{$count}})" onmouseleave="checkboxleave({{$count}})"
                           id="view_{{$subRoll['roll_id']}}">
                           @if ($subRoll['is_view']=='N')
                           <input type="checkbox" class="" name="" disabled="">
                           @else
                           <input type="checkbox" class="per_view_{{$count}}" name="view[{{$count}}][{{$subRoll['roll_id']}}]"
                             id="view_chk_{{$subRoll['roll_id']}}" onclick="indvCheck({{$subRoll['roll_id']}},{{$count}},0)"
                             value="1"  <?php if($subRoll['is_view_chk']=='Y'){ echo 'checked';} ?>>
                           @endif
                         </td>
                         <td class="center" onmouseover="checkboxOver({{$count}})" onmouseleave="checkboxleave({{$count}})"
                           id="add_{{$subRoll['roll_id']}}">
                           @if ($subRoll['is_add']=='N')
                           <input type="checkbox" class="" name="" disabled="">
                           @else
                           <input type="checkbox" class="per_add_{{$count}}" name="add[{{$count}}][{{$subRoll['roll_id']}}]"
                             id="add_chk_{{$subRoll['roll_id']}}" value="2" <?php if($subRoll['is_add_chk']=='Y'){ echo 'checked';} ?>>
                           @endif
                         </td>
                         <td class="center" onmouseover="checkboxOver({{$count}})" onmouseleave="checkboxleave({{$count}})"
                           id="edit_{{$subRoll['roll_id']}}">
                           @if ($subRoll['is_edit']=='N')
                           <input type="checkbox" class="" name="" disabled="">
                           @else
                           <input type="checkbox" class="per_edit_{{$count}}" name="edit[{{$count}}][{{$subRoll['roll_id']}}]"
                             id="edit_chk_{{$subRoll['roll_id']}}" value="3" <?php if($subRoll['is_edit_chk']=='Y'){ echo 'checked';} ?>>
                           @endif
                         </td>
                         <td class="center" onmouseover="checkboxOver({{$count}})" onmouseleave="checkboxleave({{$count}})"
                           id="del_{{$subRoll['roll_id']}}">
                           @if ($subRoll['is_delete']=='N')
                           <input type="checkbox" class="" name="" disabled="">
                           @else
                           <input type="checkbox" class="per_delete_{{$count}}" name="delete[{{$count}}][{{$subRoll['roll_id']}}]"
                             id="del_chk_{{$subRoll['roll_id']}}" value="4" <?php if($subRoll['is_delete_chk']=='Y'){ echo 'checked';} ?>>
                           @endif

                         </td>
                         <td class="center" onmouseover="checkboxOver({{$count}})" onmouseleave="checkboxleave({{$count}})"
                           id="exprt_{{$subRoll['roll_id']}}">
                           @if ($subRoll['is_download']=='N')
                           <input type="checkbox" class="" name="" disabled="">
                           @else
                           <input type="checkbox" class="per_export_{{$count}}" name="download[{{$count}}][{{$subRoll['roll_id']}}]"
                             id="export_chk_{{$subRoll['roll_id']}}" value="5" <?php if($subRoll['is_download_chk']=='Y'){ echo 'checked';} ?>>
                           @endif

                         </td>
                         <td class="center" onmouseover="checkboxOver({{$count}})" onmouseleave="checkboxleave({{$count}})"
                           id="print_{{$subRoll['roll_id']}}">
                           @if ($subRoll['is_print']=='N')
                           <input type="checkbox" class="" name="" disabled="">
                           @else
                           <input type="checkbox" class="per_print_{{$count}}" name="print[{{$count}}][{{$subRoll['roll_id']}}]"
                             id="print_chk_{{$subRoll['roll_id']}}" value="6" <?php if($subRoll['is_print_chk']=='Y'){ echo 'checked';} ?>>
                           @endif

                         </td>
                         <td class="center" onmouseover="checkboxOver({{$count}})" onmouseleave="checkboxleave({{$count}})"
                           id="upload_{{$subRoll['roll_id']}}">
                           @if ($subRoll['is_upload']=='N')
                           <input type="checkbox" class="" name="" disabled="">
                           @else
                           <input type="checkbox" class="per_upload_{{$count}}" name="upload[{{$count}}][{{$subRoll['roll_id']}}]"
                             id="upload_chk_{{$subRoll['roll_id']}}" value="7" <?php if($subRoll['is_upload_chk']=='Y'){ echo 'checked';} ?>>
                           @endif

                         </td>
                       </tr>
                       @endforeach

                       @php($count++)
                       @endforeach

                     </tbody>
                   </table>

                 </div>
               </div>
             </div>

            <div class="row">
               <div class="col-12">
                  <div class="col-12 text-center mt-4 mb-4">
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
      if ($("#role_" + id + ".active").length > 0) {
         $("#role_" + id).removeClass('active');
         $('#role-icon_' + id).removeClass('fa-unlock').addClass('fa-lock');
         //console.log('remove',id);
      } else {
         $("#role_" + id).addClass('active');
         $('#role-icon_' + id).removeClass('fa-lock').addClass('fa-unlock');
         //console.log('add',id);
      }
      var manage_role_input = '';
      $('.manage_role_li.active').map(function() {
         var active_roll_id = $(this).find('.manage-role').data('id');
         manage_role_input += '<input type="hidden" name="roll[]" value="' + active_roll_id + '">';
      });
      $('#manage-role-input-section').html(manage_role_input);
   });

   function pagesCheck(value) {
      if ($('#ModuleName_' + value).prop("checked") == true) {
         $('.per_view_' + value).prop('checked', true);
         $('.per_add_' + value).prop('checked', true);
         $('.per_edit_' + value).prop('checked', true);
         $('.per_delete_' + value).prop('checked', true);
         $('.per_export_' + value).prop('checked', true);
         $('.per_print_' + value).prop('checked', true);
         $('.per_upload_' + value).prop('checked', true);
         // $('.home_navigation_data_id_'+value).prop('disabled',false);
      } else {
         $('.per_view_' + value).prop('checked', false);
         $('.per_add_' + value).prop('checked', false);
         $('.per_edit_' + value).prop('checked', false);
         $('.per_delete_' + value).prop('checked', false);
         $('.per_export_' + value).prop('checked', false);
         $('.per_print_' + value).prop('checked', false);
         $('.per_upload_' + value).prop('checked', false);
         // $('.home_navigation_data_id_'+value).prop('disabled',true);
      }
   }

   function checkboxOver(id) {
      //console.log('checkboxOver',id);
      $('.mouseover' + id).css('text-indent', '0px');
   }

   function checkboxleave(id) {
      //console.log('checkboxleave',id);
      $('.mouseover' + id).css('text-indent', '-9999px');
   }

   function indvCheck(data_id, id, parent) {
      if ($('#view_chk_' + data_id).prop("checked") == true) {
         $('#ModuleName_' + id).prop('checked', true);
      }
   }
 </script>
@endsection
