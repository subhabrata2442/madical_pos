@extends('layouts.admin')
@section('admin-content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <form method="post" action="{{ route('admin.embloyees.add') }}" class="needs-validation" id="user-form" novalidate
        enctype="multipart/form-data">
        @csrf
        <div class="row">
          <x-alert />
          <div class="col-md-6">
            <div class="form-group">
              <label for="full_name" class="form-label">Embloyee Name</label>
              <input type="text" class="form-control admin-input" id="full_name" name="full_name" value="" required
                autocomplete="off">
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
          <div class="col-md-12">
            <div class="form-group">
              <label for="address" class="form-label">Address</label>
              <input type="text" class="form-control admin-input" id="address" name="address" value="" required
                autocomplete="off">
              @error('address')
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
              <input type="text" class="form-control admin-input" id="password" name="password" autocomplete="off">
              @error('password')
              <div class="error admin-error">{{ $message }}</div>
              @enderror </div>
          </div>

        </div>

        <div class="row">
          <div class="col-12">
            <div class="manage-role-type-section">
              <h3>Manage Role</h3>
              {{-- <ul class="d-flex justify-content-center">
                @foreach ($data['role'] as $value)
                <li id="role_{{$value->id}}" class="manage_role_li">
                  <button type="button" class="manage-role-btn manage-role" data-id="{{$value->id}}"> <span
                      class="manage-role-icon"><i class="fa fa-lock"
                        id="role-icon_{{$value->id}}"></i></span>{{ $value->title }} </button>
                </li>
                @endforeach
              </ul> --}}
              <div style="display: none" id="manage-role-input-section"></div>

              <table border="0" width="100%" cellpadding="6" cellspacing="2">
                <tbody>
                  <tr>
                    <td width="20%" bgcolor="#f3f3f3" class="center bold leftAlign">name</td>
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
                  <tr class="trborderbottom" id="module_1">
                    <td bgcolor="#f3f3f3" class="leftAlign" style="text-transform:uppercase;"><b
                        class="redcolor">Dashboard</b>&nbsp;&nbsp;<input type="checkbox" onclick="pagesCheck(1)"
                        name="ModuleName_1" id="ModuleName_1" value="1">&nbsp;&nbsp;<input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id1" value=""></td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover1">View</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover1">Add</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover1">Edit</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover1">Delete</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover1">Download</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover1">Print</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover1">Upload</td>
                  </tr>
                  <tr class="trborderbottom" id="module_2">
                    <td bgcolor="#f3f3f3" class="rightAlign" style="text-transform:uppercase;"><b
                        class="redcolor">Sales</b>&nbsp;&nbsp;<input type="checkbox" onclick="pagesCheck(2)"
                        name="ModuleName_2" id="ModuleName_2" value="2">&nbsp;&nbsp;<input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id2" value=""></td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover2" style="text-indent: -9999px;">View</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover2" style="text-indent: -9999px;">Add</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover2" style="text-indent: -9999px;">Edit</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover2" style="text-indent: -9999px;">Delete</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover2" style="text-indent: -9999px;">Download
                    </td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover2" style="text-indent: -9999px;">Print</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover2" style="text-indent: -9999px;">Upload</td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_2">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_1">Sales / B2C Bill&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_2" name="home_navigation_data_id_1"
                        id="home_navigation_data_id_1" value="1"><input type="hidden" name="employee_role_permission_id"
                        id="employee_role_permission_id1" value=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="view_1"><input
                        type="checkbox" class="per_view_2" name="view_1" onclick="indvCheck(1,2,0)"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="add_1"><input
                        type="checkbox" class="per_add_2" name="add_1"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="edit_1"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="del_1"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="exprt_1"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="print_1"><input
                        type="checkbox" class="per_print_2" name="print_1"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="upload_1">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_2">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_2">Sales / B2B Bill
                      (Add-On)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_2"
                        name="home_navigation_data_id_2" id="home_navigation_data_id_2" value="2"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id2" value=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="view_2"><input
                        type="checkbox" class="per_view_2" name="view_2" onclick="indvCheck(2,2,0)"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="add_2"><input
                        type="checkbox" class="per_add_2" name="add_2"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="edit_2"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="del_2"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="exprt_2"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="print_2"><input
                        type="checkbox" class="per_print_2" name="print_2"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="upload_2">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_2">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_3">View Bills&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_2" name="home_navigation_data_id_3"
                        id="home_navigation_data_id_3" value="3"><input type="hidden" name="employee_role_permission_id"
                        id="employee_role_permission_id3" value=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="view_3"><input
                        type="checkbox" class="per_view_2" name="view_3" onclick="indvCheck(3,2,0)"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="add_3"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="edit_3"><input
                        type="checkbox" class="per_edit_2" name="edit_3"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="del_3"><input
                        type="checkbox" class="per_delete_2" name="delete_3"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="exprt_3"><input
                        type="checkbox" class="per_export_2" name="export_3"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="print_3"><input
                        type="checkbox" class="per_print_2" name="print_3"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="upload_3">
                      <input type="checkbox" class="per_upload_2" name="upload_3"></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_2">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_4">Consignment Bill
                      (Add-On)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_2"
                        name="home_navigation_data_id_4" id="home_navigation_data_id_4" value="4"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id4" value=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="view_4"><input
                        type="checkbox" class="per_view_2" name="view_4" onclick="indvCheck(4,2,0)"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="add_4"><input
                        type="checkbox" class="per_add_2" name="add_4"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="edit_4"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="del_4"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="exprt_4"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="print_4"><input
                        type="checkbox" class="per_print_2" name="print_4"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="upload_4">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_2">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_5">Challan(Consignment)
                      (Add-On)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_2"
                        name="home_navigation_data_id_5" id="home_navigation_data_id_5" value="5"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id5" value=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="view_5"><input
                        type="checkbox" class="per_view_2" name="view_5" onclick="indvCheck(5,2,0)"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="add_5"><input
                        type="checkbox" class="per_add_2" name="add_5"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="edit_5"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="del_5"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="exprt_5"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="print_5"><input
                        type="checkbox" class="per_print_2" name="print_5"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="upload_5">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_2">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_6">View Challans(Consignment)
                      (Add-On)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_2"
                        name="home_navigation_data_id_6" id="home_navigation_data_id_6" value="6"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id6" value=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="view_6"><input
                        type="checkbox" class="per_view_2" name="view_6" onclick="indvCheck(6,2,0)"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="add_6"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="edit_6"><input
                        type="checkbox" class="per_edit_2" name="edit_6"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="del_6"><input
                        type="checkbox" class="per_delete_2" name="delete_6"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="exprt_6"><input
                        type="checkbox" class="per_export_2" name="export_6"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="print_6"><input
                        type="checkbox" class="per_print_2" name="print_6"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="upload_6">
                      <input type="checkbox" class="per_upload_2" name="upload_6"></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_2">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_7">Franchise Bill
                      (Add-On)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_2"
                        name="home_navigation_data_id_7" id="home_navigation_data_id_7" value="7"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id7" value=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="view_7"><input
                        type="checkbox" class="per_view_2" name="view_7" onclick="indvCheck(7,2,0)"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="add_7"><input
                        type="checkbox" class="per_add_2" name="add_7"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="edit_7"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="del_7"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="exprt_7"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="print_7"><input
                        type="checkbox" class="per_print_2" name="print_7"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="upload_7">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_2">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_8">View Franchise Bills
                      (Add-On)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_2"
                        name="home_navigation_data_id_8" id="home_navigation_data_id_8" value="8"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id8" value=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="view_8"><input
                        type="checkbox" class="per_view_2" name="view_8" onclick="indvCheck(8,2,0)"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="add_8"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="edit_8"><input
                        type="checkbox" class="per_edit_2" name="edit_8"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="del_8"><input
                        type="checkbox" class="per_delete_2" name="delete_8"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="exprt_8"><input
                        type="checkbox" class="per_export_2" name="export_8"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="print_8"><input
                        type="checkbox" class="per_print_2" name="print_8"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="upload_8">
                      <input type="checkbox" class="per_upload_2" name="upload_8"></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_2">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_9">Sales Return&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_2" name="home_navigation_data_id_9"
                        id="home_navigation_data_id_9" value="9"><input type="hidden" name="employee_role_permission_id"
                        id="employee_role_permission_id9" value=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="view_9"><input
                        type="checkbox" class="per_view_2" name="view_9" onclick="indvCheck(9,2,0)"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="add_9"><input
                        type="checkbox" class="per_add_2" name="add_9"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="edit_9"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="del_9"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="exprt_9"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="print_9"><input
                        type="checkbox" class="per_print_2" name="print_9"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="upload_9">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_2">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_10">Returned Products&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_2" name="home_navigation_data_id_10"
                        id="home_navigation_data_id_10" value="10"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id10" value=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="view_10"><input
                        type="checkbox" class="per_view_2" name="view_10" onclick="indvCheck(10,2,0)"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="add_10"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="edit_10"><input
                        type="checkbox" class="per_edit_2" name="edit_10"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="del_10"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="exprt_10">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="print_10">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="upload_10">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_2">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_11">Customer Source&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_2" name="home_navigation_data_id_11"
                        id="home_navigation_data_id_11" value="11"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id11" value=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="view_11"><input
                        type="checkbox" class="per_view_2" name="view_11" onclick="indvCheck(11,2,0)"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="add_11"><input
                        type="checkbox" class="per_add_2" name="add_11"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="edit_11"><input
                        type="checkbox" class="per_edit_2" name="edit_11"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="del_11"><input
                        type="checkbox" class="per_delete_2" name="delete_11"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="exprt_11">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="print_11">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="upload_11">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_2">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_12">Customers&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_2" name="home_navigation_data_id_12"
                        id="home_navigation_data_id_12" value="12"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id12" value=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="view_12"><input
                        type="checkbox" class="per_view_2" name="view_12" onclick="indvCheck(12,2,0)"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="add_12"><input
                        type="checkbox" class="per_add_2" name="add_12"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="edit_12"><input
                        type="checkbox" class="per_edit_2" name="edit_12"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="del_12"><input
                        type="checkbox" class="per_delete_2" name="delete_12"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="exprt_12">
                      <input type="checkbox" class="per_export_2" name="export_12"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="print_12">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="upload_12">
                      <input type="checkbox" class="per_upload_2" name="upload_12"></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_2">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_13">Customer Balance&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_2" name="home_navigation_data_id_13"
                        id="home_navigation_data_id_13" value="13"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id13" value=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="view_13"><input
                        type="checkbox" class="per_view_2" name="view_13" onclick="indvCheck(13,2,0)"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="add_13"><input
                        type="checkbox" class="per_add_2" name="add_13"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="edit_13"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="del_13"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="exprt_13">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="print_13">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="upload_13">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_2">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_14">Portal Balance&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_2" name="home_navigation_data_id_14"
                        id="home_navigation_data_id_14" value="14"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id14" value=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="view_14"><input
                        type="checkbox" class="per_view_2" name="view_14" onclick="indvCheck(14,2,0)"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="add_14"><input
                        type="checkbox" class="per_add_2" name="add_14"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="edit_14"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="del_14"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="exprt_14">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="print_14">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="upload_14">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_2">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_15">Customer Receipts&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_2" name="home_navigation_data_id_15"
                        id="home_navigation_data_id_15" value="15"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id15" value=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="view_15"><input
                        type="checkbox" class="per_view_2" name="view_15" onclick="indvCheck(15,2,0)"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="add_15"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="edit_15"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="del_15"><input
                        type="checkbox" class="per_delete_2" name="delete_15"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="exprt_15">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="print_15">
                      <input type="checkbox" class="per_print_2" name="print_15"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="upload_15">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_2">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_16">Referral Points
                      (Add-On)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_2"
                        name="home_navigation_data_id_16" id="home_navigation_data_id_16" value="16"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id16" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="view_16"><input
                        type="checkbox" class="per_view_2" name="view_16" onclick="indvCheck(16,2,0)"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="add_16"><input
                        type="checkbox" class="per_add_2" name="add_16"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="edit_16"><input
                        type="checkbox" class="per_edit_2" name="edit_16"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="del_16"><input
                        type="checkbox" class="per_delete_2" name="delete_16"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="exprt_16">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="print_16">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="upload_16">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_2">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_17">Loyalty Setup
                      (Add-On)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_2"
                        name="home_navigation_data_id_17" id="home_navigation_data_id_17" value="17"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id17" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="view_17"><input
                        type="checkbox" class="per_view_2" name="view_17" onclick="indvCheck(17,2,0)"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="add_17"><input
                        type="checkbox" class="per_add_2" name="add_17"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="edit_17"><input
                        type="checkbox" class="per_edit_2" name="edit_17"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="del_17"><input
                        type="checkbox" class="per_delete_2" name="delete_17"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="exprt_17">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="print_17">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="upload_17">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_2">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_18">Promotional SMS
                      (Add-On)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_2"
                        name="home_navigation_data_id_18" id="home_navigation_data_id_18" value="18"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id18" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="view_18"><input
                        type="checkbox" class="per_view_2" name="view_18" onclick="indvCheck(18,2,0)"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="add_18"><input
                        type="checkbox" class="per_add_2" name="add_18"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="edit_18"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="del_18"><input
                        type="checkbox" class="per_delete_2" name="delete_18"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="exprt_18">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="print_18">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="upload_18">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_2">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_85">Transactional SMS
                      (Add-On)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_2"
                        name="home_navigation_data_id_85" id="home_navigation_data_id_85" value="85"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id85" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="view_85"><input
                        type="checkbox" class="per_view_2" name="view_85" onclick="indvCheck(85,2,0)"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="add_85"><input
                        type="checkbox" class="per_add_2" name="add_85"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="edit_85"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="del_85"><input
                        type="checkbox" class="per_delete_2" name="delete_85"></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="exprt_85">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="print_85">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(2)" onmouseleave="checkboxleave(2)" id="upload_85">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="module_3">
                    <td bgcolor="#f3f3f3" class="rightAlign" style="text-transform:uppercase;"><b
                        class="redcolor">Purchase</b>&nbsp;&nbsp;<input type="checkbox" onclick="pagesCheck(3)"
                        name="ModuleName_3" id="ModuleName_3" value="3">&nbsp;&nbsp;<input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id3" value=""></td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover3" style="text-indent: -9999px;">View</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover3" style="text-indent: -9999px;">Add</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover3" style="text-indent: -9999px;">Edit</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover3" style="text-indent: -9999px;">Delete</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover3" style="text-indent: -9999px;">Download
                    </td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover3" style="text-indent: -9999px;">Print</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover3" style="text-indent: -9999px;">Upload</td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_3">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_19">FMCG Inward Stock&nbsp;&nbsp;
                      <input
                        type="hidden" class="home_navigation_data_id_3" name="home_navigation_data_id_19"
                        id="home_navigation_data_id_19" value="19">
                        <input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id19" value=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="view_19"><input
                        type="checkbox" class="per_view_3" name="view_19" onclick="indvCheck(19,3,0)"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="add_19"><input
                        type="checkbox" class="per_add_3" name="add_19"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="edit_19"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="del_19"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="exprt_19">
                      <input type="checkbox" class="per_export_3" name="export_19"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="print_19">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="upload_19">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_3">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_20">Garment Inward Stock&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_3" name="home_navigation_data_id_20"
                        id="home_navigation_data_id_20" value="20"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id20" value=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="view_20"><input
                        type="checkbox" class="per_view_3" name="view_20" onclick="indvCheck(20,3,0)"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="add_20"><input
                        type="checkbox" class="per_add_3" name="add_20"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="edit_20"><input
                        type="checkbox" class="per_edit_3" name="edit_20"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="del_20"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="exprt_20">
                      <input type="checkbox" class="per_export_3" name="export_20"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="print_20">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="upload_20">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_3">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_21">Unique Inward Stock&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_3" name="home_navigation_data_id_21"
                        id="home_navigation_data_id_21" value="21"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id21" value=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="view_21"><input
                        type="checkbox" class="per_view_3" name="view_21" onclick="indvCheck(21,3,0)"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="add_21"><input
                        type="checkbox" class="per_add_3" name="add_21"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="edit_21"><input
                        type="checkbox" class="per_edit_3" name="edit_21"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="del_21"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="exprt_21">
                      <input type="checkbox" class="per_export_3" name="export_21"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="print_21">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="upload_21">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_3">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_22">View Inward Stock&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_3" name="home_navigation_data_id_22"
                        id="home_navigation_data_id_22" value="22"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id22" value=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="view_22"><input
                        type="checkbox" class="per_view_3" name="view_22" onclick="indvCheck(22,3,0)"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="add_22"><input
                        type="checkbox" class="per_add_3" name="add_22"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="edit_22"><input
                        type="checkbox" class="per_edit_3" name="edit_22"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="del_22"><input
                        type="checkbox" class="per_delete_3" name="delete_22"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="exprt_22">
                      <input type="checkbox" class="per_export_3" name="export_22"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="print_22">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="upload_22">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_3">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_23">Issue PO (Add-On)&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_3" name="home_navigation_data_id_23"
                        id="home_navigation_data_id_23" value="23"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id23" value=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="view_23"><input
                        type="checkbox" class="per_view_3" name="view_23" onclick="indvCheck(23,3,0)"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="add_23"><input
                        type="checkbox" class="per_add_3" name="add_23"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="edit_23"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="del_23"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="exprt_23">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="print_23">
                      <input type="checkbox" class="per_print_3" name="print_23"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="upload_23">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_3">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_24">Unique Batch No Issue PO
                      (Add-On)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_3"
                        name="home_navigation_data_id_24" id="home_navigation_data_id_24" value="24"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id24" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="view_24"><input
                        type="checkbox" class="per_view_3" name="view_24" onclick="indvCheck(24,3,0)"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="add_24"><input
                        type="checkbox" class="per_add_3" name="add_24"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="edit_24"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="del_24"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="exprt_24">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="print_24">
                      <input type="checkbox" class="per_print_3" name="print_24"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="upload_24">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_3">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_25">View PO (Add-On)&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_3" name="home_navigation_data_id_25"
                        id="home_navigation_data_id_25" value="25"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id25" value=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="view_25"><input
                        type="checkbox" class="per_view_3" name="view_25" onclick="indvCheck(25,3,0)"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="add_25"><input
                        type="checkbox" class="per_add_3" name="add_25"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="edit_25"><input
                        type="checkbox" class="per_edit_3" name="edit_25"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="del_25"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="exprt_25">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="print_25">
                      <input type="checkbox" class="per_print_3" name="print_25"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="upload_25">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_3">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_26">Suppliers&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_3" name="home_navigation_data_id_26"
                        id="home_navigation_data_id_26" value="26"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id26" value=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="view_26"><input
                        type="checkbox" class="per_view_3" name="view_26" onclick="indvCheck(26,3,0)"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="add_26"><input
                        type="checkbox" class="per_add_3" name="add_26"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="edit_26"><input
                        type="checkbox" class="per_edit_3" name="edit_26"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="del_26"><input
                        type="checkbox" class="per_delete_3" name="delete_26"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="exprt_26">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="print_26">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="upload_26">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_3">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_27">Amount Payable to
                      Supplier&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_3"
                        name="home_navigation_data_id_27" id="home_navigation_data_id_27" value="27"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id27" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="view_27"><input
                        type="checkbox" class="per_view_3" name="view_27" onclick="indvCheck(27,3,0)"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="add_27"><input
                        type="checkbox" class="per_add_3" name="add_27"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="edit_27"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="del_27"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="exprt_27">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="print_27">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="upload_27">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_3">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_28">Supplier Payment
                      Receipt&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_3"
                        name="home_navigation_data_id_28" id="home_navigation_data_id_28" value="28"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id28" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="view_28"><input
                        type="checkbox" class="per_view_3" name="view_28" onclick="indvCheck(28,3,0)"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="add_28"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="edit_28"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="del_28"><input
                        type="checkbox" class="per_delete_3" name="delete_28"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="exprt_28">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="print_28">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="upload_28">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_3">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_29">Debit Note&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_3" name="home_navigation_data_id_29"
                        id="home_navigation_data_id_29" value="29"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id29" value=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="view_29"><input
                        type="checkbox" class="per_view_3" name="view_29" onclick="indvCheck(29,3,0)"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="add_29"><input
                        type="checkbox" class="per_add_3" name="add_29"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="edit_29"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="del_29"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="exprt_29">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="print_29">
                      <input type="checkbox" class="per_print_3" name="print_29"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="upload_29">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_3">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_30">View Debit Note&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_3" name="home_navigation_data_id_30"
                        id="home_navigation_data_id_30" value="30"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id30" value=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="view_30"><input
                        type="checkbox" class="per_view_3" name="view_30" onclick="indvCheck(30,3,0)"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="add_30"><input
                        type="checkbox" class="per_add_3" name="add_30"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="edit_30"><input
                        type="checkbox" class="per_edit_3" name="edit_30"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="del_30"><input
                        type="checkbox" class="per_delete_3" name="delete_30"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="exprt_30">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="print_30">
                      <input type="checkbox" class="per_print_3" name="print_30"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="upload_30">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_3">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_31">Expense Manager
                      (Add-On)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_3"
                        name="home_navigation_data_id_31" id="home_navigation_data_id_31" value="31"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id31" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="view_31"><input
                        type="checkbox" class="per_view_3" name="view_31" onclick="indvCheck(31,3,0)"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="add_31"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="edit_31"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="del_31"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="exprt_31">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="print_31">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="upload_31">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_3">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_32">Expense Report
                      (Add-On)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_3"
                        name="home_navigation_data_id_32" id="home_navigation_data_id_32" value="32"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id32" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="view_32"><input
                        type="checkbox" class="per_view_3" name="view_32" onclick="indvCheck(32,3,0)"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="add_32"><input
                        type="checkbox" class="per_add_3" name="add_32"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="edit_32"><input
                        type="checkbox" class="per_edit_3" name="edit_32"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="del_32"><input
                        type="checkbox" class="per_delete_3" name="delete_32"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="exprt_32">
                      <input type="checkbox" class="per_export_3" name="export_32"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="print_32">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="upload_32">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_3">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_93">Category wise Expense Report
                      (Add-On)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_3"
                        name="home_navigation_data_id_93" id="home_navigation_data_id_93" value="93"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id93" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="view_93"><input
                        type="checkbox" class="per_view_3" name="view_93" onclick="indvCheck(93,3,0)"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="add_93"><input
                        type="checkbox" class="per_add_3" name="add_93"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="edit_93"><input
                        type="checkbox" class="per_edit_3" name="edit_93"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="del_93"><input
                        type="checkbox" class="per_delete_3" name="delete_93"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="exprt_93">
                      <input type="checkbox" class="per_export_3" name="export_93"></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="print_93">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(3)" onmouseleave="checkboxleave(3)" id="upload_93">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="module_4">
                    <td bgcolor="#f3f3f3" class="rightAlign" style="text-transform:uppercase;"><b
                        class="redcolor">Inventory</b>&nbsp;&nbsp;<input type="checkbox" onclick="pagesCheck(4)"
                        name="ModuleName_4" id="ModuleName_4" value="4">&nbsp;&nbsp;<input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id4" value=""></td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover4" style="text-indent: -9999px;">View</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover4" style="text-indent: -9999px;">Add</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover4" style="text-indent: -9999px;">Edit</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover4" style="text-indent: -9999px;">Delete</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover4" style="text-indent: -9999px;">Download
                    </td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover4" style="text-indent: -9999px;">Print</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover4" style="text-indent: -9999px;">Upload</td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_4">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_33">Products Profile&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_4" name="home_navigation_data_id_33"
                        id="home_navigation_data_id_33" value="33"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id33" value=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="view_33"><input
                        type="checkbox" class="per_view_4" name="view_33" onclick="indvCheck(33,4,0)"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="add_33"><input
                        type="checkbox" class="per_add_4" name="add_33"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="edit_33"><input
                        type="checkbox" class="per_edit_4" name="edit_33"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="del_33"><input
                        type="checkbox" class="per_delete_4" name="delete_33"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="exprt_33">
                      <input type="checkbox" class="per_export_4" name="export_33"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="print_33">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="upload_33">
                      <input type="checkbox" class="per_upload_4" name="upload_33"></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_4">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_34">Stock Audit&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_4" name="home_navigation_data_id_34"
                        id="home_navigation_data_id_34" value="34"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id34" value=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="view_34"><input
                        type="checkbox" class="per_view_4" name="view_34" onclick="indvCheck(34,4,0)"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="add_34"><input
                        type="checkbox" class="per_add_4" name="add_34"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="edit_34"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="del_34"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="exprt_34">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="print_34">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="upload_34">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_4">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_35">Make Kit (Add-On)&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_4" name="home_navigation_data_id_35"
                        id="home_navigation_data_id_35" value="35"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id35" value=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="view_35"><input
                        type="checkbox" class="per_view_4" name="view_35" onclick="indvCheck(35,4,0)"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="add_35"><input
                        type="checkbox" class="per_add_4" name="add_35"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="edit_35"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="del_35"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="exprt_35">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="print_35">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="upload_35">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_4">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_36">View Kit (Add-On)&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_4" name="home_navigation_data_id_36"
                        id="home_navigation_data_id_36" value="36"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id36" value=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="view_36"><input
                        type="checkbox" class="per_view_4" name="view_36" onclick="indvCheck(36,4,0)"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="add_36"><input
                        type="checkbox" class="per_add_4" name="add_36"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="edit_36"><input
                        type="checkbox" class="per_edit_4" name="edit_36"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="del_36"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="exprt_36">
                      <input type="checkbox" class="per_export_4" name="export_36"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="print_36">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="upload_36">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_4">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_37">Kit Inward (Add-On)&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_4" name="home_navigation_data_id_37"
                        id="home_navigation_data_id_37" value="37"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id37" value=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="view_37"><input
                        type="checkbox" class="per_view_4" name="view_37" onclick="indvCheck(37,4,0)"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="add_37"><input
                        type="checkbox" class="per_add_4" name="add_37"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="edit_37"><input
                        type="checkbox" class="per_edit_4" name="edit_37"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="del_37"><input
                        type="checkbox" class="per_delete_4" name="delete_37"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="exprt_37">
                      <input type="checkbox" class="per_export_4" name="export_37"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="print_37">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="upload_37">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_4">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_38">View Kit Inward
                      (Add-On)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_4"
                        name="home_navigation_data_id_38" id="home_navigation_data_id_38" value="38"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id38" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="view_38"><input
                        type="checkbox" class="per_view_4" name="view_38" onclick="indvCheck(38,4,0)"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="add_38"><input
                        type="checkbox" class="per_add_4" name="add_38"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="edit_38"><input
                        type="checkbox" class="per_edit_4" name="edit_38"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="del_38"><input
                        type="checkbox" class="per_delete_4" name="delete_38"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="exprt_38">
                      <input type="checkbox" class="per_export_4" name="export_38"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="print_38">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="upload_38">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_4">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_39">Damage/Used Products&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_4" name="home_navigation_data_id_39"
                        id="home_navigation_data_id_39" value="39"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id39" value=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="view_39"><input
                        type="checkbox" class="per_view_4" name="view_39" onclick="indvCheck(39,4,0)"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="add_39"><input
                        type="checkbox" class="per_add_4" name="add_39"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="edit_39"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="del_39"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="exprt_39">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="print_39">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="upload_39">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_4">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_40">Barcode Printing&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_4" name="home_navigation_data_id_40"
                        id="home_navigation_data_id_40" value="40"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id40" value=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="view_40"><input
                        type="checkbox" class="per_view_4" name="view_40" onclick="indvCheck(40,4,0)"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="add_40"><input
                        type="checkbox" class="per_add_4" name="add_40"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="edit_40"><input
                        type="checkbox" class="per_edit_4" name="edit_40"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="del_40"><input
                        type="checkbox" class="per_delete_4" name="delete_40"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="exprt_40">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="print_40">
                      <input type="checkbox" class="per_print_4" name="print_40"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="upload_40">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_4">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_41">Discount Master
                      (Add-On)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_4"
                        name="home_navigation_data_id_41" id="home_navigation_data_id_41" value="41"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id41" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="view_41"><input
                        type="checkbox" class="per_view_4" name="view_41" onclick="indvCheck(41,4,0)"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="add_41"><input
                        type="checkbox" class="per_add_4" name="add_41"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="edit_41"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="del_41"><input
                        type="checkbox" class="per_delete_4" name="delete_41"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="exprt_41">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="print_41">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="upload_41">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_4">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_42">View Flat Discount Products
                      (Add-On)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_4"
                        name="home_navigation_data_id_42" id="home_navigation_data_id_42" value="42"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id42" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="view_42"><input
                        type="checkbox" class="per_view_4" name="view_42" onclick="indvCheck(42,4,0)"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="add_42"><input
                        type="checkbox" class="per_add_4" name="add_42"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="edit_42"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="del_42"><input
                        type="checkbox" class="per_delete_4" name="delete_42"></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="exprt_42">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="print_42">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(4)" onmouseleave="checkboxleave(4)" id="upload_42">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="module_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" style="text-transform:uppercase;"><b
                        class="redcolor">Reports</b>&nbsp;&nbsp;<input type="checkbox" onclick="pagesCheck(5)"
                        name="ModuleName_5" id="ModuleName_5" value="5">&nbsp;&nbsp;<input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id5" value=""></td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover5" style="text-indent: -9999px;">View</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover5" style="text-indent: -9999px;">Add</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover5" style="text-indent: -9999px;">Edit</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover5" style="text-indent: -9999px;">Delete</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover5" style="text-indent: -9999px;">Download
                    </td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover5" style="text-indent: -9999px;">Print</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover5" style="text-indent: -9999px;">Upload</td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" style="color:#f00 !important;font-weight:bold !important;"
                      id="submoduleid_45">Sales&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_5"
                        name="home_navigation_data_id_45" id="home_navigation_data_id_45" value="45"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id45" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_45"><input
                        type="checkbox" class="per_view_5" name="view_45" id="parent_1" onclick="indvCheck(45,5,0)">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_45"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_45"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_45"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_45">
                      <input type="checkbox" class="per_export_5" name="export_45"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_45">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_45">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_48">Product Wise Sale
                      Report&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_5"
                        name="home_navigation_data_id_48" id="home_navigation_data_id_48" value="48"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id48" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_48"><input
                        type="checkbox" class="per_view_5" name="view_48" onclick="indvCheck(48,5,1)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_48"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_48"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_48"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_48">
                      <input type="checkbox" class="per_export_5" name="export_48"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_48">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_48">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_97">Customers Birthdays&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_5" name="home_navigation_data_id_97"
                        id="home_navigation_data_id_97" value="97"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id97" value=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_97"><input
                        type="checkbox" class="per_view_5" name="view_97" onclick="indvCheck(97,5,4)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_97"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_97"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_97"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_97">
                      <input type="checkbox" class="per_export_5" name="export_97"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_97">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_97">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" style="color:#f00 !important;font-weight:bold !important;"
                      id="submoduleid_46">Purchase&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_5"
                        name="home_navigation_data_id_46" id="home_navigation_data_id_46" value="46"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id46" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_46"><input
                        type="checkbox" class="per_view_5" name="view_46" id="parent_2" onclick="indvCheck(46,5,0)">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_46"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_46"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_46"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_46">
                      <input type="checkbox" class="per_export_5" name="export_46"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_46">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_46">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_49">Sale GST Report (%)
                      Wise&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_5"
                        name="home_navigation_data_id_49" id="home_navigation_data_id_49" value="49"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id49" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_49"><input
                        type="checkbox" class="per_view_5" name="view_49" onclick="indvCheck(49,5,1)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_49"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_49"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_49"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_49">
                      <input type="checkbox" class="per_export_5" name="export_49"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_49">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_49">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_98">Customers Child
                      Birthdays&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_5"
                        name="home_navigation_data_id_98" id="home_navigation_data_id_98" value="98"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id98" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_98"><input
                        type="checkbox" class="per_view_5" name="view_98" onclick="indvCheck(98,5,4)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_98"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_98"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_98"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_98">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_98">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_98">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" style="color:#f00 !important;font-weight:bold !important;"
                      id="submoduleid_47">Inventory&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_5"
                        name="home_navigation_data_id_47" id="home_navigation_data_id_47" value="47"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id47" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_47"><input
                        type="checkbox" class="per_view_5" name="view_47" id="parent_3" onclick="indvCheck(47,5,0)">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_47"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_47"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_47"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_47">
                      <input type="checkbox" class="per_export_5" name="export_47"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_47">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_47">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_50">Stock Report&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_5" name="home_navigation_data_id_50"
                        id="home_navigation_data_id_50" value="50"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id50" value=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_50"><input
                        type="checkbox" class="per_view_5" name="view_50" onclick="indvCheck(50,5,3)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_50"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_50"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_50"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_50">
                      <input type="checkbox" class="per_export_5" name="export_50"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_50">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_50">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_51">Supplier wise Sale
                      Report&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_5"
                        name="home_navigation_data_id_51" id="home_navigation_data_id_51" value="51"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id51" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_51"><input
                        type="checkbox" class="per_view_5" name="view_51" onclick="indvCheck(51,5,1)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_51"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_51"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_51"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_51">
                      <input type="checkbox" class="per_export_5" name="export_51"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_51">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_51">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_99">Product Expiry&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_5" name="home_navigation_data_id_99"
                        id="home_navigation_data_id_99" value="99"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id99" value=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_99"><input
                        type="checkbox" class="per_view_5" name="view_99" onclick="indvCheck(99,5,4)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_99"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_99"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_99"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_99">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_99">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_99">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_52">Price Master&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_5" name="home_navigation_data_id_52"
                        id="home_navigation_data_id_52" value="52"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id52" value=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_52"><input
                        type="checkbox" class="per_view_5" name="view_52" onclick="indvCheck(52,5,3)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_52"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_52"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_52"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_52">
                      <input type="checkbox" class="per_export_5" name="export_52"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_52">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_52">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_100">Customers
                      Outstanding&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_5"
                        name="home_navigation_data_id_100" id="home_navigation_data_id_100" value="100"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id100" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_100">
                      <input type="checkbox" class="per_view_5" name="view_100" onclick="indvCheck(100,5,4)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_100"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_100">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_100"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_100">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_100">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_100">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_53">Damage/Used&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_5" name="home_navigation_data_id_53"
                        id="home_navigation_data_id_53" value="53"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id53" value=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_53"><input
                        type="checkbox" class="per_view_5" name="view_53" onclick="indvCheck(53,5,3)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_53"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_53"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_53"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_53">
                      <input type="checkbox" class="per_export_5" name="export_53"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_53">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_53">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_94">Collection Report&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_5" name="home_navigation_data_id_94"
                        id="home_navigation_data_id_94" value="94"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id94" value=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_94"><input
                        type="checkbox" class="per_view_5" name="view_94" onclick="indvCheck(94,5,1)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_94"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_94"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_94"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_94">
                      <input type="checkbox" class="per_export_5" name="export_94"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_94">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_94">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_101">Supplier Payments&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_5" name="home_navigation_data_id_101"
                        id="home_navigation_data_id_101" value="101"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id101" value=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_101">
                      <input type="checkbox" class="per_view_5" name="view_101" onclick="indvCheck(101,5,4)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_101"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_101">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_101"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_101">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_101">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_101">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_54">Damage Used Product &nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_5" name="home_navigation_data_id_54"
                        id="home_navigation_data_id_54" value="54"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id54" value=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_54"><input
                        type="checkbox" class="per_view_5" name="view_54" onclick="indvCheck(54,5,3)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_54"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_54"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_54"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_54">
                      <input type="checkbox" class="per_export_5" name="export_54"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_54">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_54">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_95">Sale Report&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_5" name="home_navigation_data_id_95"
                        id="home_navigation_data_id_95" value="95"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id95" value=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_95"><input
                        type="checkbox" class="per_view_5" name="view_95" onclick="indvCheck(95,5,1)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_95"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_95"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_95"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_95">
                      <input type="checkbox" class="per_export_5" name="export_95"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_95">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_95">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_55">Inward Report (Product
                      Wise)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_5"
                        name="home_navigation_data_id_55" id="home_navigation_data_id_55" value="55"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id55" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_55"><input
                        type="checkbox" class="per_view_5" name="view_55" onclick="indvCheck(55,5,2)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_55"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_55"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_55"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_55">
                      <input type="checkbox" class="per_export_5" name="export_55"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_55">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_55">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_56">Inward Report (Invoice
                      Wise)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_5"
                        name="home_navigation_data_id_56" id="home_navigation_data_id_56" value="56"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id56" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_56"><input
                        type="checkbox" class="per_view_5" name="view_56" onclick="indvCheck(56,5,2)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_56"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_56"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_56"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_56">
                      <input type="checkbox" class="per_export_5" name="export_56"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_56">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_56">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_57">Debit Note Report&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_5" name="home_navigation_data_id_57"
                        id="home_navigation_data_id_57" value="57"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id57" value=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_57"><input
                        type="checkbox" class="per_view_5" name="view_57" onclick="indvCheck(57,5,2)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_57"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_57"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_57"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_57">
                      <input type="checkbox" class="per_export_5" name="export_57"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_57">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_57">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_58">Inward/GST(%) Wise
                      Report&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_5"
                        name="home_navigation_data_id_58" id="home_navigation_data_id_58" value="58"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id58" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_58"><input
                        type="checkbox" class="per_view_5" name="view_58" onclick="indvCheck(58,5,2)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_58"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_58"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_58"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_58">
                      <input type="checkbox" class="per_export_5" name="export_58"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_58">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_58">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_59">Credit Note Report&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_5" name="home_navigation_data_id_59"
                        id="home_navigation_data_id_59" value="59"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id59" value=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_59"><input
                        type="checkbox" class="per_view_5" name="view_59" onclick="indvCheck(59,5,1)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_59"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_59"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_59"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_59">
                      <input type="checkbox" class="per_export_5" name="export_59"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_59">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_59">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_91">Expense Report
                      (Add-On)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_5"
                        name="home_navigation_data_id_91" id="home_navigation_data_id_91" value="91"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id91" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_91"><input
                        type="checkbox" class="per_view_5" name="view_91" onclick="indvCheck(91,5,2)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_91"><input
                        type="checkbox" class="per_add_5" name="add_91"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_91"><input
                        type="checkbox" class="per_edit_5" name="edit_91"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_91"><input
                        type="checkbox" class="per_delete_5" name="delete_91"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_91">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_91">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_91">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_60">Profit &amp; Loss Report(Product
                      Wise)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_5"
                        name="home_navigation_data_id_60" id="home_navigation_data_id_60" value="60"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id60" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_60"><input
                        type="checkbox" class="per_view_5" name="view_60" onclick="indvCheck(60,5,1)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_60"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_60"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_60"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_60">
                      <input type="checkbox" class="per_export_5" name="export_60"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_60">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_60">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_68">Profit &amp; Loss Report(Bill
                      Wise)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_5"
                        name="home_navigation_data_id_68" id="home_navigation_data_id_68" value="68"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id68" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_68"><input
                        type="checkbox" class="per_view_5" name="view_68" onclick="indvCheck(68,5,1)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_68"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_68"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_68"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_68">
                      <input type="checkbox" class="per_export_5" name="export_68"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_68">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_68">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_62">Low Stock Report&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_5" name="home_navigation_data_id_62"
                        id="home_navigation_data_id_62" value="62"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id62" value=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_62"><input
                        type="checkbox" class="per_view_5" name="view_62" onclick="indvCheck(62,5,3)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_62"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_62"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_62"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_62">
                      <input type="checkbox" class="per_export_5" name="export_62"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_62">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_62">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_67">Product Aging Report&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_5" name="home_navigation_data_id_67"
                        id="home_navigation_data_id_67" value="67"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id67" value=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_67"><input
                        type="checkbox" class="per_view_5" name="view_67" onclick="indvCheck(67,5,3)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_67"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_67"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_67"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_67">
                      <input type="checkbox" class="per_export_5" name="export_67"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_67">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_67">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_63">Product Summary
                      Report&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_5"
                        name="home_navigation_data_id_63" id="home_navigation_data_id_63" value="63"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id63" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_63"><input
                        type="checkbox" class="per_view_5" name="view_63" onclick="indvCheck(63,5,3)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_63"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_63"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_63"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_63">
                      <input type="checkbox" class="per_export_5" name="export_63"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_63">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_63">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_64">Stock Audit Report&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_5" name="home_navigation_data_id_64"
                        id="home_navigation_data_id_64" value="64"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id64" value=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_64"><input
                        type="checkbox" class="per_view_5" name="view_64" onclick="indvCheck(64,5,3)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_64"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_64"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_64"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_64">
                      <input type="checkbox" class="per_export_5" name="export_64"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_64">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_64">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_69">GSTR Reports&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_5" name="home_navigation_data_id_69"
                        id="home_navigation_data_id_69" value="69"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id69" value=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_69"><input
                        type="checkbox" class="per_view_5" name="view_69" onclick="indvCheck(69,5,1)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_69"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_69"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_69"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_69">
                      <input type="checkbox" class="per_export_5" name="export_69"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_69">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_69">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_65">Stock Audit Report (Batch
                      Wise)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_5"
                        name="home_navigation_data_id_65" id="home_navigation_data_id_65" value="65"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id65" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_65"><input
                        type="checkbox" class="per_view_5" name="view_65" onclick="indvCheck(65,5,3)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_65"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_65"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_65"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_65">
                      <input type="checkbox" class="per_export_5" name="export_65"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_65">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_65">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" style="color:#f00 !important;font-weight:bold !important;"
                      id="submoduleid_96">Reminders&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_5"
                        name="home_navigation_data_id_96" id="home_navigation_data_id_96" value="96"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id96" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_96"><input
                        type="checkbox" class="per_view_5" name="view_96" id="parent_4" onclick="indvCheck(96,5,0)">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_96"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_96"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_96"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_96">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_96">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_96">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_66">Item report by store (by
                      size)&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_5"
                        name="home_navigation_data_id_66" id="home_navigation_data_id_66" value="66"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id66" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_66"><input
                        type="checkbox" class="per_view_5" name="view_66" onclick="indvCheck(66,5,3)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_66"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_66"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_66"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_66">
                      <input type="checkbox" class="per_export_5" name="export_66"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_66">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_66">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_70">Top Selling Products&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_5" name="home_navigation_data_id_70"
                        id="home_navigation_data_id_70" value="70"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id70" value=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_70"><input
                        type="checkbox" class="per_view_5" name="view_70" onclick="indvCheck(70,5,1)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_70"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_70"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_70"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_70">
                      <input type="checkbox" class="per_export_5" name="export_70"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_70">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_70">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_71">Top Customers&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_5" name="home_navigation_data_id_71"
                        id="home_navigation_data_id_71" value="71"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id71" value=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_71"><input
                        type="checkbox" class="per_view_5" name="view_71" onclick="indvCheck(71,5,1)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_71"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_71"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_71"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_71">
                      <input type="checkbox" class="per_export_5" name="export_71"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_71">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_71">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_5">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_72">Customer Transaction&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_5" name="home_navigation_data_id_72"
                        id="home_navigation_data_id_72" value="72"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id72" value=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="view_72"><input
                        type="checkbox" class="per_view_5" name="view_72" onclick="indvCheck(72,5,1)"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="add_72"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="edit_72"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="del_72"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="exprt_72">
                      <input type="checkbox" class="per_export_5" name="export_72"></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="print_72">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(5)" onmouseleave="checkboxleave(5)" id="upload_72">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="module_6">
                    <td bgcolor="#f3f3f3" class="rightAlign" style="text-transform:uppercase;"><b
                        class="redcolor">Company</b>&nbsp;&nbsp;<input type="checkbox" onclick="pagesCheck(6)"
                        name="ModuleName_6" id="ModuleName_6" value="6">&nbsp;&nbsp;<input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id6" value=""></td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover6" style="text-indent: -9999px;">View</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover6" style="text-indent: -9999px;">Add</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover6" style="text-indent: -9999px;">Edit</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover6" style="text-indent: -9999px;">Delete</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover6" style="text-indent: -9999px;">Download
                    </td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover6" style="text-indent: -9999px;">Print</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover6" style="text-indent: -9999px;">Upload</td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_6">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_73">Company Profile&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_6" name="home_navigation_data_id_73"
                        id="home_navigation_data_id_73" value="73"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id73" value=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="view_73"><input
                        type="checkbox" class="per_view_6" name="view_73" onclick="indvCheck(73,6,0)"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="add_73"><input
                        type="checkbox" class="per_add_6" name="add_73"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="edit_73"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="del_73"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="exprt_73">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="print_73">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="upload_73">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_6">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_89">Product Features&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_6" name="home_navigation_data_id_89"
                        id="home_navigation_data_id_89" value="89"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id89" value=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="view_89"><input
                        type="checkbox" class="per_view_6" name="view_89" onclick="indvCheck(89,6,0)"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="add_89"><input
                        type="checkbox" class="per_add_6" name="add_89"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="edit_89"><input
                        type="checkbox" class="per_edit_6" name="edit_89"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="del_89"><input
                        type="checkbox" class="per_delete_6" name="delete_89"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="exprt_89">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="print_89">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="upload_89">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_6">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_74">GST Slabs&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_6" name="home_navigation_data_id_74"
                        id="home_navigation_data_id_74" value="74"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id74" value=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="view_74"><input
                        type="checkbox" class="per_view_6" name="view_74" onclick="indvCheck(74,6,0)"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="add_74"><input
                        type="checkbox" class="per_add_6" name="add_74"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="edit_74"><input
                        type="checkbox" class="per_edit_6" name="edit_74"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="del_74"><input
                        type="checkbox" class="per_delete_6" name="delete_74"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="exprt_74">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="print_74">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="upload_74">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_6">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_84">Product Age Range&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_6" name="home_navigation_data_id_84"
                        id="home_navigation_data_id_84" value="84"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id84" value=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="view_84"><input
                        type="checkbox" class="per_view_6" name="view_84" onclick="indvCheck(84,6,0)"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="add_84"><input
                        type="checkbox" class="per_add_6" name="add_84"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="edit_84"><input
                        type="checkbox" class="per_edit_6" name="edit_84"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="del_84"><input
                        type="checkbox" class="per_delete_6" name="delete_84"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="exprt_84">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="print_84">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="upload_84">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_6">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_75">Manage Backup&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_6" name="home_navigation_data_id_75"
                        id="home_navigation_data_id_75" value="75"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id75" value=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="view_75"><input
                        type="checkbox" class="per_view_6" name="view_75" onclick="indvCheck(75,6,0)"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="add_75"><input
                        type="checkbox" class="per_add_6" name="add_75"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="edit_75"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="del_75"><input
                        type="checkbox" class="per_delete_6" name="delete_75"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="exprt_75">
                      <input type="checkbox" class="per_export_6" name="export_75"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="print_75">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="upload_75">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_6">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_76">Employees&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_6" name="home_navigation_data_id_76"
                        id="home_navigation_data_id_76" value="76"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id76" value=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="view_76"><input
                        type="checkbox" class="per_view_6" name="view_76" onclick="indvCheck(76,6,0)"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="add_76"><input
                        type="checkbox" class="per_add_6" name="add_76"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="edit_76"><input
                        type="checkbox" class="per_edit_6" name="edit_76"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="del_76"><input
                        type="checkbox" class="per_delete_6" name="delete_76"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="exprt_76">
                      <input type="checkbox" class="per_export_6" name="export_76"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="print_76">
                      <input type="checkbox" class="per_print_6" name="print_76"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="upload_76">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_6">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_77">Cash Book&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_6" name="home_navigation_data_id_77"
                        id="home_navigation_data_id_77" value="77"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id77" value=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="view_77"><input
                        type="checkbox" class="per_view_6" name="view_77" onclick="indvCheck(77,6,0)"></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="add_77"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="edit_77"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="del_77"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="exprt_77">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="print_77">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(6)" onmouseleave="checkboxleave(6)" id="upload_77">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="module_7">
                    <td bgcolor="#f3f3f3" class="rightAlign" style="text-transform:uppercase;"><b class="redcolor">Store
                        (Add On)</b>&nbsp;&nbsp;<input type="checkbox" onclick="pagesCheck(7)" name="ModuleName_7"
                        id="ModuleName_7" value="7">&nbsp;&nbsp;<input type="hidden" name="employee_role_permission_id"
                        id="employee_role_permission_id7" value=""></td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover7" style="text-indent: -9999px;">View</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover7" style="text-indent: -9999px;">Add</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover7" style="text-indent: -9999px;">Edit</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover7" style="text-indent: -9999px;">Delete</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover7" style="text-indent: -9999px;">Download
                    </td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover7" style="text-indent: -9999px;">Print</td>
                    <td bgcolor="#f3f3f3" class="center titleover mouseover7" style="text-indent: -9999px;">Upload</td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_7">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_78">Store Profile&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_7" name="home_navigation_data_id_78"
                        id="home_navigation_data_id_78" value="78"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id78" value=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="view_78"><input
                        type="checkbox" class="per_view_7" name="view_78" onclick="indvCheck(78,7,0)"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="add_78"><input
                        type="checkbox" class="per_add_7" name="add_78"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="edit_78"><input
                        type="checkbox" class="per_edit_7" name="edit_78"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="del_78"><input
                        type="checkbox" class="per_delete_7" name="delete_78"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="exprt_78">
                      <input type="checkbox" class="per_export_7" name="export_78"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="print_78">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="upload_78">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_7">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_79">View Store&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_7" name="home_navigation_data_id_79"
                        id="home_navigation_data_id_79" value="79"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id79" value=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="view_79"><input
                        type="checkbox" class="per_view_7" name="view_79" onclick="indvCheck(79,7,0)"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="add_79"><input
                        type="checkbox" class="per_add_7" name="add_79"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="edit_79"><input
                        type="checkbox" class="per_edit_7" name="edit_79"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="del_79"><input
                        type="checkbox" class="per_delete_7" name="delete_79"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="exprt_79">
                      <input type="checkbox" class="per_export_7" name="export_79"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="print_79">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="upload_79">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_7">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_92">View Store PO&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_7" name="home_navigation_data_id_92"
                        id="home_navigation_data_id_92" value="92"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id92" value=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="view_92"><input
                        type="checkbox" class="per_view_7" name="view_92" onclick="indvCheck(92,7,0)"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="add_92"><input
                        type="checkbox" class="per_add_7" name="add_92"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="edit_92"><input
                        type="checkbox" class="per_edit_7" name="edit_92"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="del_92"><input
                        type="checkbox" class="per_delete_7" name="delete_92"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="exprt_92">
                      <input type="checkbox" class="per_export_7" name="export_92"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="print_92">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="upload_92">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_7">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_80">Stock Transfer&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_7" name="home_navigation_data_id_80"
                        id="home_navigation_data_id_80" value="80"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id80" value=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="view_80"><input
                        type="checkbox" class="per_view_7" name="view_80" onclick="indvCheck(80,7,0)"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="add_80"><input
                        type="checkbox" class="per_add_7" name="add_80"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="edit_80"><input
                        type="checkbox" class="per_edit_7" name="edit_80"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="del_80"><input
                        type="checkbox" class="per_delete_7" name="delete_80"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="exprt_80">
                      <input type="checkbox" class="per_export_7" name="export_80"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="print_80">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="upload_80">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_7">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_81">View Stock Transfer&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_7" name="home_navigation_data_id_81"
                        id="home_navigation_data_id_81" value="81"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id81" value=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="view_81"><input
                        type="checkbox" class="per_view_7" name="view_81" onclick="indvCheck(81,7,0)"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="add_81"><input
                        type="checkbox" class="per_add_7" name="add_81"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="edit_81"><input
                        type="checkbox" class="per_edit_7" name="edit_81"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="del_81"><input
                        type="checkbox" class="per_delete_7" name="delete_81"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="exprt_81">
                      <input type="checkbox" class="per_export_7" name="export_81"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="print_81">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="upload_81">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_7">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_82">View Stock Transfer
                      Detail&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_7"
                        name="home_navigation_data_id_82" id="home_navigation_data_id_82" value="82"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id82" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="view_82"><input
                        type="checkbox" class="per_view_7" name="view_82" onclick="indvCheck(82,7,0)"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="add_82"><input
                        type="checkbox" class="per_add_7" name="add_82"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="edit_82"><input
                        type="checkbox" class="per_edit_7" name="edit_82"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="del_82"><input
                        type="checkbox" class="per_delete_7" name="delete_82"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="exprt_82">
                      <input type="checkbox" class="per_export_7" name="export_82"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="print_82">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="upload_82">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_7">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_83">Stock Transfer Inward&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_7" name="home_navigation_data_id_83"
                        id="home_navigation_data_id_83" value="83"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id83" value=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="view_83"><input
                        type="checkbox" class="per_view_7" name="view_83" onclick="indvCheck(83,7,0)"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="add_83"><input
                        type="checkbox" class="per_add_7" name="add_83"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="edit_83"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="del_83"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="exprt_83">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="print_83">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="upload_83">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_7">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_86">Store Return Products&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_7" name="home_navigation_data_id_86"
                        id="home_navigation_data_id_86" value="86"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id86" value=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="view_86"><input
                        type="checkbox" class="per_view_7" name="view_86" onclick="indvCheck(86,7,0)"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="add_86"><input
                        type="checkbox" class="per_add_7" name="add_86"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="edit_86"><input
                        type="checkbox" class="per_edit_7" name="edit_86"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="del_86"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="exprt_86">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="print_86">
                      <input type="checkbox" class="per_print_7" name="print_86"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="upload_86">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_7">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_87">View Return Products&nbsp;&nbsp;<input
                        type="hidden" class="home_navigation_data_id_7" name="home_navigation_data_id_87"
                        id="home_navigation_data_id_87" value="87"><input type="hidden"
                        name="employee_role_permission_id" id="employee_role_permission_id87" value=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="view_87"><input
                        type="checkbox" class="per_view_7" name="view_87" onclick="indvCheck(87,7,0)"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="add_87"><input
                        type="checkbox" class="per_add_7" name="add_87"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="edit_87"><input
                        type="checkbox" class="per_edit_7" name="edit_87"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="del_87"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="exprt_87">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="print_87">
                      <input type="checkbox" class="per_print_7" name="print_87"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="upload_87">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                  <tr class="trborderbottom" id="RoleValues_7">
                    <td bgcolor="#f3f3f3" class="rightAlign" id="submoduleid_88">Manage Return
                      Products&nbsp;&nbsp;<input type="hidden" class="home_navigation_data_id_7"
                        name="home_navigation_data_id_88" id="home_navigation_data_id_88" value="88"><input
                        type="hidden" name="employee_role_permission_id" id="employee_role_permission_id88" value="">
                    </td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="view_88"><input
                        type="checkbox" class="per_view_7" name="view_88" onclick="indvCheck(88,7,0)"></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="add_88"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="edit_88"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="del_88"><input
                        type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="exprt_88">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="print_88">
                      <input type="checkbox" class="" disabled="" name=""></td>
                    <td class="center" onmouseover="checkboxOver(7)" onmouseleave="checkboxleave(7)" id="upload_88">
                      <input type="checkbox" class="" disabled="" name=""></td>
                  </tr>
                </tbody>
              </table>

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
</script>
@endsection