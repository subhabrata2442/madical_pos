<?php
$adminId 			= Session::get('adminId');
$adminRoll 		= Session::get('admin_type');

// $total_notification=0;
// $pending_s_count =\App\Models\BranchStockRequest::where('to_store_id',$adminId)->where('status',1)->count();
// $pending_s_result=\App\Models\BranchStockRequest::where('status',1)->where('to_store_id',$adminId)->groupBy('from_store_id')->selectRaw('sum(r_qty) as t_qty, from_store_id, product_id')->get();
// $total_notification +=$pending_s_count;

    $admin_type = Session::get('admin_type');
    $store_id	= Session::get('store_id');

    if($admin_type==1){
        $pending_s_count =\App\Models\Notification::where('is_seen','0')->count();
        $pending_s_result=\App\Models\Notification::where('is_seen','0')->get();
    }else if($admin_type=2){
        $pending_s_count =\App\Models\Notification::where('store_id',$store_id)->where('is_seen','0')->count();
        $pending_s_result=\App\Models\Notification::where('store_id',$store_id)->where('is_seen','0')->get();
    }


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
        <span class="badge badge-danger navbar-badge totalNoti">{{$pending_s_count}}</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
        {{-- <span class="dropdown-item dropdown-header">0 Notifications</span> --}}
        <div class="dropdown-divider"></div>

        {{-- <a href="" class="dropdown-item">
          <i class="fas fa-envelope mr-2"></i> asdasdasdasd
          <span class="float-right text-muted text-sm">3 mins</span>
        </a> --}}
        <input type="hidden" id="totalunreadnotification" value="{{$pending_s_count}}">
        <div class="appendnotification">
            @if (count($pending_s_result)>0)
                @foreach ($pending_s_result as $itempending_s_result)
                @php
                    $urls = '';
                    if($itempending_s_result->type=='stock-alert'){
                        $urls = url('/').'/admin/report/low_stock_product?id='.$itempending_s_result->branch_stock_id;
                    }else if($itempending_s_result->type=='product-expiry'){
                        $urls = url('/').'/admin/report/near_expiry_stock?id='.$itempending_s_result->id;
                    }else if($itempending_s_result->type=='stock-transfer'){
                        $urls = url('/').'/admin/purchase/stock-transfer';
                    }
                @endphp

                    <a href="{{$urls}}" onclick="seenNotification('{{$itempending_s_result->id}}')" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> {{$itempending_s_result->msg}}
                        {{-- <span class="float-right text-muted text-sm">3 mins</span> --}}
                    </a>
                @endforeach

                <a href="{{ route('admin.allnotification') }}" class="dropdown-item dropdown-footer">See All Notifications</a>
            @else
                <span class="dropdown-item dropdown-header zeronoti">0 Notifications</span>
            @endif
        </div>

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
          {{-- <li class="nav-item" style="margin-right:5px;"> <a href="{{ route('admin.setting') }}">Settings</a> </li> --}}
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

