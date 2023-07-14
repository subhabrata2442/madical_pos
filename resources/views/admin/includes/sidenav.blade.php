@php
$branch_id 			= Session::get('branch_id');
$company_name		= App\Models\Common::get_user_settings($where=['option_name'=>'company_name'],$branch_id);
$company_address	= App\Models\Common::get_user_settings($where=['option_name'=>'company_address'],$branch_id);
$is_branch 			= Session::get('is_branch');


//print_r($is_branch);exit;

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

        <li class="nav-item @if (strpos(Route::currentRouteName(), 'admin.purchase') !== false) menu-open @endif"> <a href="#" class="nav-link @if (strpos(Route::currentRouteName(), 'admin.purchase') !== false) parent-active @endif"> <i class="fas fa-cart-plus"></i>
          <p>Purchase <i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item"> <a href="{{ route('admin.purchase.inward_stock') }}" class="nav-link @if (\Route::currentRouteName() == 'admin.purchase.inward_stock') active @endif"> <i class="fas fa-plus-circle nav-icon"></i>
              <p>Purchase Order</p>
              </a> </li>
          </ul>
        </li>

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

        
      </ul>
    </nav>
    <!-- /.sidebar-menu --> 
  </div>
  <!-- /.sidebar --> 
</aside>
