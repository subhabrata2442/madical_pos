<?php
$adminId 			= Session::get('adminId');
$adminRoll 		= Session::get('admin_type');

$total_notification=0;
$pending_s_count =\App\Models\BranchStockRequest::where('to_store_id',$adminId)->where('status',1)->count();
$pending_s_result=\App\Models\BranchStockRequest::where('status',1)->where('to_store_id',$adminId)->groupBy('from_store_id')->selectRaw('sum(r_qty) as t_qty, from_store_id, product_id')->get();
$total_notification +=$pending_s_count;



?>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item"> <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
          class="fas fa-bars"></i></a> </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto me-3">
    <li class="nav-item dropdown">
      <a class="nav-link noti-nav-link" data-toggle="dropdown" href="javascript:;" aria-expanded="false">
        <i class="far fa-bell"></i>
        <span class="badge badge-danger navbar-badge">{{$total_notification}}</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
        <span class="dropdown-item dropdown-header">{{$total_notification}} Notifications</span>
        <div class="dropdown-divider"></div>
        @if(count($pending_s_result)>0)
        @foreach($pending_s_result as $srrow)
        <a href="{{ route('admin.purchase.stock.transferRequest') }}" class="dropdown-item">
          <i class="fas fa-envelope mr-2"></i> {{$srrow->t_qty}} New Stock request from {{ Str::limit($srrow->store->name, 5) }}
          <span class="float-right text-muted text-sm">3 mins</span>
        </a>
        @endforeach
        @endif
        {{-- <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-users mr-2"></i> 8 friend requests
          <span class="float-right text-muted text-sm">12 hours</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-file mr-2"></i> 3 new reports
          <span class="float-right text-muted text-sm">2 days</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a> --}}
      </div>
    </li>
  </ul>

  <div class="profileDd ">

    <a href="javascript:void(0)" class="toggleDropDown"><span><img src="{{ asset('uploads/avatar/avatar5.png') }}"
          alt=""></span> {{ \Auth::user()->name }} <i class="fas fa-caret-down"></i></a>
    <div class="profileDdInner displayHide">
      <div class="pdTop">
        <span>
          <img src="{{ asset('uploads/avatar/avatar5.png') }}" alt="">

          {{-- <img src="{{ asset('' . \Auth::user()->avatar) }}" alt=""> --}}
        </span>
        <h5>{{ \Auth::user()->name }}</h5>
        <h5>{{ \Auth::user()->email }}</h5>
        <!--<a href="{{ route('admin.profile') }}">View Profile</a>-->
      </div>
      <div class="pdBottom">
        <ul class="navbar-nav flex-wrap">
          <li class="nav-item" style="margin-right:5px;"> <a href="{{ route('admin.auth.logout') }}">Logout</a> </li>
          <li class="nav-item" style="margin-right:5px;"> <a href="{{ route('admin.setting') }}">Settings</a> </li>
          <!--<li class="d-flex justify-content-between align-items-center">
            <div class="moveName"> <span>Night mode</span> </div>
            <div>
              <ul>
                <li class="nav-item day-night-switch">
                  <input type="checkbox" class="dark_mode_toggle_checkbox" id="toggle_checkbox">
                  <label for="toggle_checkbox">
                  <div id="star">
                    <div class="star" id="star-1"><i class="fas fa-star"></i></div>
                    <div class="star" id="star-2"><i class="fas fa-star"></i></div>
                  </div>
                  <div id="moon"></div>
                  </label>
                </li>
              </ul>
            </div>
          </li>-->
        </ul>
      </div>
    </div>
  </div>
</nav>
<!-- /.navbar -->


<script>
  $(document).on('click','.noti-nav-link',function(){
    $(this).parent().toggleClass("show").find(".dropdown-menu").toggleClass("show");
  });
</script> 

