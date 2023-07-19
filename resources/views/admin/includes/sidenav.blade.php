@php
$adminId 			= Session::get('adminId');
$adminRoll 		= Session::get('admin_type');


$permission=array();
$roleWisePermissionResult		= App\Models\UserRolePermission::where('user_id',$adminId)->orderBy('id', 'asc')->get();

foreach($roleWisePermissionResult as $row){
	$permission[]=$row->role_id;
}

//echo '<pre>';print_r($permission);exit;


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

          
          @if ($adminRoll == '1')
            <li class="nav-item @if (strpos(Route::currentRouteName(), 'admin.store') !== false) menu-open @endif"> <a href="#" class="nav-link @if (strpos(Route::currentRouteName(), 'admin.store') !== false) parent-active @endif"> <i class="fas fa-user nav-icon"></i>
              <p>Manage stores <i class="fas fa-angle-left right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item"> <a href="{{ route('admin.store.list') }}" class="nav-link @if (\Route::currentRouteName() == 'admin.store.list') active @endif"> <i class="fas fa-list nav-icon"></i>
                  <p>List stores</p>
                  </a> </li>
                <li class="nav-item"> <a href="{{ route('admin.store.add') }}" class="nav-link @if (\Route::currentRouteName() == 'admin.store.add') active @endif"> <i class="fas fa-plus-circle nav-icon"></i>
                  <p>Add store</p>
                  </a> </li>
                {{-- <li class="nav-item"> <a href="{{ route('admin.store.manageRole') }}" class="nav-link @if (\Route::currentRouteName() == 'admin.store.manageRole') active @endif"> <i class="fas fa-plus-circle nav-icon"></i>
                  <p>Manage Role</p>
                  </a> </li>   --}}
              </ul>
            </li>
          @endif

          @if(isset($permission))
          @if(in_array(6, $permission))
            <li class="nav-item @if (strpos(Route::currentRouteName(), 'admin.embloyees') !== false) menu-open @endif"> <a href="#" class="nav-link @if (strpos(Route::currentRouteName(), 'admin.embloyees') !== false) parent-active @endif"> <i class="fas fa-user nav-icon"></i>
              <p>Manage Embloyees <i class="fas fa-angle-left right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item"> <a href="{{ route('admin.embloyees.list') }}" class="nav-link @if (\Route::currentRouteName() == 'admin.embloyees.list') active @endif"> <i class="fas fa-list nav-icon"></i>
                  <p>List Embloyees</p>
                  </a> </li>
                <li class="nav-item"> <a href="{{ route('admin.embloyees.add') }}" class="nav-link @if (\Route::currentRouteName() == 'admin.embloyees.add') active @endif"> <i class="fas fa-plus-circle nav-icon"></i>
                  <p>Add Embloyees</p>
                  </a> </li>
              </ul>
            </li>
          @endif
        @endif

        
        @if(isset($permission))
          @if(in_array(1, $permission))
            <li class="nav-item @if (strpos(Route::currentRouteName(), 'admin.product') !== false) menu-open @endif"> <a href="#" class="nav-link @if (strpos(Route::currentRouteName(), 'admin.supplier') !== false) parent-active @endif"> <i class="fas fa fa-list"></i>
              <p>Products <i class="fas fa-angle-left right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item"> <a href="{{ route('admin.product.list') }}" class="nav-link @if (\Route::currentRouteName() == 'admin.product.list') active @endif"> <i class="fas fa-list nav-icon"></i>
                  <p>List Products</p>
                  </a> </li>
                <li class="nav-item"> <a href="{{ route('admin.product.add') }}" class="nav-link @if (\Route::currentRouteName() == 'admin.product.add') active @endif"> <i class="fas fa-plus-circle nav-icon"></i>
                  <p>Add Products</p>
                  </a> </li>
              </ul>
            </li>
          @endif
        @endif


        @if(isset($permission))
          @if(in_array(2, $permission))
            <li class="nav-item @if (strpos(Route::currentRouteName(), 'admin.purchase') !== false) menu-open @endif"> <a href="#" class="nav-link @if (strpos(Route::currentRouteName(), 'admin.purchase') !== false) parent-active @endif"> <i class="fas fa-cart-plus"></i>
              <p>Purchase <i class="fas fa-angle-left right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item"> <a href="{{ route('admin.purchase.inward_stock') }}" class="nav-link @if (\Route::currentRouteName() == 'admin.purchase.inward_stock') active @endif"> <i class="fas fa-plus-circle nav-icon"></i>
                  <p>Purchase Order</p>
                  </a> </li>
              </ul>
            </li>
          @endif
        @endif

        @if(isset($permission))
          @if(in_array(3, $permission))
            <li class="nav-item @if (strpos(Route::currentRouteName(), 'admin.report') !== false) menu-open @endif"> <a href="#" class="nav-link @if (strpos(Route::currentRouteName(), 'admin.report') !== false) parent-active @endif"> <i class="fas fa-cart-plus"></i>
              <p>Report <i class="fas fa-angle-left right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item"> <a href="{{ route('admin.report.purchase') }}" class="nav-link @if (\Route::currentRouteName() == 'admin.report.purchase') active @endif"> <i class="fas fa-plus-circle nav-icon"></i>
                  <p>Purchase</p>
                  </a> </li>
                  <li class="nav-item"> <a href="{{ route('admin.report.inventory') }}" class="nav-link @if (\Route::currentRouteName() == 'admin.report.inventory') active @endif"> 
                    <i class="fas fa-warehouse nav-icon"></i>
                    <p>Inventory</p>
                    </a> 
                  </li>
              </ul>
            </li>
          @endif
        @endif

        
      </ul>
    </nav>
    <!-- /.sidebar-menu --> 
  </div>
  <!-- /.sidebar --> 
</aside>
