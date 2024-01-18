@php
$adminId 			= Session::get('adminId');
$adminRoll 		= Session::get('admin_type');


$permission=array();
$rolePermissionResult		= App\Models\UserRolePermission::where('user_id',$adminId)->orderBy('id', 'asc')->get();

foreach($rolePermissionResult as $row){
	$permission[]=$row->role_id;
}
$page_permission=array();

if($adminId!=1){
  $roleWisePermissionResult		= App\Models\RoleWisePermission::where('branch_id',$adminId)->orderBy('id', 'asc')->get();
  foreach($roleWisePermissionResult as $row){
    $page_permission[]=$row->get_slug->slug;
  }
}else{
  $roleWisePermissionResult		= App\Models\RoleSubPermission::get();
  foreach($roleWisePermissionResult as $row){
    $page_permission[]=$row->slug;
  }

}

//echo '<pre>';print_r($page_permission);exit;


@endphp

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ route('admin.dashboard') }}" class="brand-link d-flex align-items-center"> <img src="{{ asset('assets/img/fire-logo.png') }}" alt="Logo" class="brand-image img-circle"> <span class="brand-text font-weight-light"> <img class="img-block logo-dark" src="{{ asset('assets/img/text-logo.png') }}" alt=""> </span> </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item"> <a href="{{ route('admin.dashboard') }}" class="nav-link @if (\Route::currentRouteName() == 'admin.dashboard') active @endif"> <i class="nav-icon fas fa-tachometer-alt"></i>
          <p> Dashboard </p>
          </a> </li>

        {{-- <li class="nav-item"> <a href="{{ route('admin.customer.list') }}" class="nav-link @if (\Route::currentRouteName() == 'admin.customer.list') active @endif"> <i class="nav-icon fas fa-users"></i>
            <p> Customer </p>
            </a>
        </li> --}}

        <li class="nav-item"> <a href="{{ route('admin.product.list') }}" class="nav-link @if (\Route::currentRouteName() == 'admin.product.list') active @endif"> <i class="fas fa-list nav-icon"></i>
            <p>Products</p>
            </a>
        </li>
        <li class="nav-item @if (strpos(Route::currentRouteName(), 'admin.purchase') !== false) menu-open @endif"> <a href="#" class="nav-link @if (strpos(Route::currentRouteName(), 'admin.purchase') !== false) parent-active @endif"> <i class="fas fa-cart-plus nav-icon"></i>
            <p>Purchase <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item"> <a href="{{ route('admin.purchase.inward_stock') }}" class="nav-link @if (\Route::currentRouteName() == 'admin.purchase.inward_stock') active @endif"> <i class="fas fa-plus-circle nav-icon"></i>
                    <p>Purchase Order</p>
                    </a>
                </li>
                <li class="nav-item"> <a href="{{ route('admin.purchase.inward_list') }}" class="nav-link @if (\Route::currentRouteName() == 'admin.purchase.inward_list') active @endif"> <i class="fas fa-list nav-icon"></i>
                    <p>Purchase List</p>
                    </a>
                </li>
                <li class="nav-item"> <a href="{{ route('admin.purchase.price_history') }}" class="nav-link @if (\Route::currentRouteName() == 'admin.purchase.price_history') active @endif"> <i class="fas fa-list nav-icon"></i>
                    <p>Price History</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item @if (strpos(Route::currentRouteName(), 'admin.pos') !== false) menu-open @endif"> <a href="#" class="nav-link @if (strpos(Route::currentRouteName(), 'admin.pos') !== false) parent-active @endif"> <i class="fas fa-shopping-cart nav-icon"></i>
            <p>Sale <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item"> <a href="{{ route('admin.pos.pos_create') }}" class="nav-link @if (\Route::currentRouteName() == 'admin.pos.pos_create') active @endif"> <i class="fas fa-plus-circle nav-icon"></i>
                <p>Create Order</p>
                </a>
                </li>
                <li class="nav-item"> <a href="{{ route('admin.pos.bill') }}" class="nav-link @if (\Route::currentRouteName() == 'admin.pos.bill') active @endif"> <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p> Return Bill </p>
                    </a>
                </li>
            </ul>
        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
