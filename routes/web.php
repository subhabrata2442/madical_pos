<?php

use App\Http\Controllers\Authenticate;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\EmbloyeesController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\WaiterController;
use App\Http\Controllers\ManageTableController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\DosageController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CredithistoryController;
use App\Http\Controllers\SettlementController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// authentication routes
Route::match(['GET', 'POST'], '/', [Authenticate::class, 'login'])->name('auth.login');
Route::match(['GET', 'POST'], '/register', [Authenticate::class, 'register'])->name('auth.register');
Route::match(['GET'], '/email/verification/{data}', [Authenticate::class, 'email_verification'])->name('auth.email_verification');
Route::match(['POST'], '/email/resend-otp', [Authenticate::class, 'email_resend_otp'])->name('auth.email_resend_otp');
Route::match(['POST'], '/registration', [Authenticate::class, 'verifyAndRegister'])->name('auth.verify_register');
Route::match(['GET', 'POST'], '/forget-password', [Authenticate::class, 'forget_password'])->name('auth.fogetPass');

Route::match(['GET', 'POST'], '/permission_denied', [Authenticate::class, 'permission_denied'])->name('auth.permission_denied');


// end Route


//Route::match(['GET'], '/daily-product-sell-history', [CronController::class, 'daily_product_sell_history'])->name('daily_product_sell_history');
Route::match(['GET', 'POST'], '/daily_product_sell_history/{id}', [CronController::class, 'daily_product_sell_history'])->name('daily_product_sell_history');
Route::match(['GET', 'POST'], '/daily_product_purchase_history/{id}', [CronController::class, 'daily_product_purchase_history'])->name('daily_product_purchase_history');


Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
	Route::match(['GET'], '/logout', [Authenticate::class, 'logout'])->name('auth.logout');
	//Route::match(['GET'], '/setting', [UserController::class, 'setting'])->name('auth.setting');

	Route::match(['GET'], '/dashboard', [DashboardController::class, 'index'])->name('dashboard');


	// Route::get('/dashboard', function () {
	//     $data = [];
	//     $data['heading'] = 'Dashboard';
	//     $data['breadcrumb'] = ['Dashboard'];
	//     return view('admin/dashboard', compact('data'));
	// })->name('dashboard');
	//->middleware('checkPermission:all,normal_user');

	/* // user route
    Route::match(['GET'], '/users', [UserController::class, 'list'])->name('users.list')->middleware('role:admin');
    Route::match(['GET'], '/users/manage-role/{id}', [UserController::class, 'manage_role'])->name('users.manageRole')->middleware('role:admin');
    Route::match(['GET'], '/users/set-role/{id}/{role_id}', [UserController::class, 'set_role'])->name('users.setRole')->middleware('role:admin');
    Route::match(['GET', 'POST'], '/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit')->middleware('role:admin');
    Route::match(['GET'], '/users/delete/{id}', [UserController::class, 'delete'])->name('users.delete')->middleware('role:admin');
    Route::match(['GET'], '/users/change-status/{id}/{status}', [UserController::class, 'change_status'])->name('users.changeStatus')->middleware('role:admin');*/
	// end user route
	Route::match(['GET'], '/setting', [UserController::class, 'profile'])->name('setting')->middleware('role:all,normal_user');
	Route::match(['GET'], '/profile', [UserController::class, 'profile'])->name('profile')->middleware('role:all,normal_user');
	Route::match(['GET', 'POST'], '/profile/edit', [UserController::class, 'profile_edit'])->name('profile.edit')->middleware('role:all,normal_user');


	Route::match(['GET'], '/check_counter_sell', [ReportController::class, 'check_counter_sell'])->name('ReportController');

	Route::prefix('pos')->name('pos.')->middleware('checkPermission:18')->group(function () {

		Route::match(['GET'], '/create_order', [PosController::class, 'pos_create'])->name('pos_create');
		Route::match(['POST'], '/create', [PosController::class, 'create'])->name('create');
		Route::match(['GET'], '/print_invoice', [PosController::class, 'print_invoice'])->name('print_invoice');

		// Route::match(['GET'], '/pos_type', [PurchaseOrderController::class, 'pos_type'])->name('pos_type');

		// Route::match(['GET'], '/demo_page_1', [PurchaseOrderController::class, 'demo_page_1'])->name('demo_page_1');
		// Route::match(['GET'], '/demo_page_2', [PurchaseOrderController::class, 'demo_page_2'])->name('demo_page_2');
		// Route::match(['GET'], '/demo_page_3', [PurchaseOrderController::class, 'demo_page_3'])->name('demo_page_3');
		// Route::match(['GET'], '/demo_page_4', [PurchaseOrderController::class, 'demo_page_4'])->name('demo_page_4');
		// Route::match(['GET'], '/demo_page_5', [PurchaseOrderController::class, 'demo_page_5'])->name('demo_page_5');


		//Route::match(['GET'], '/pos_payment_method', [PurchaseOrderController::class, 'pos_payment_method'])->name('pos_payment_method');

		// Route::match(['GET'], '/bar_dine_in_table_booking', [PurchaseOrderController::class, 'bar_dine_in_table_booking'])->name('bar_dine_in_table_booking');
		// Route::match(['GET'], '/bar_dine_in_table_booking/create_order/{id}', [PurchaseOrderController::class, 'bar_dine_in_table_booking_create_order'])->name('bar_dine_in_table_booking_create_order');

		// Route::match(['GET', 'POST'], '/print_ko_products', [PurchaseOrderController::class, 'print_ko_product'])->name('print_ko_product');
		// Route::match(['GET'], '/print_ko_products/download', [PurchaseOrderController::class, 'download_print_ko_product']);
		// Route::match(['GET'], '/print_bar_invoice/download', [PurchaseOrderController::class, 'print_bar_invoice']);
		// Route::match(['POST'], '/bar_create', [PurchaseOrderController::class, 'bar_create'])->name('bar_create');


		Route::match(['GET'], '/print_invoice', [PurchaseOrderController::class, 'print_invoice'])->name('print_invoice');
		//Route::match(['GET'], '/today-sales-product/download', [PurchaseOrderController::class, 'todaySalesProductDownload']);
		/*Route::match(['GET', 'POST'], '/list', [CustomerController::class, 'list'])->name('list');
        Route::match(['GET', 'POST'], '/edit/{id}', [CustomerController::class, 'edit'])->name('edit');
        Route::match(['GET', 'POST'], '/delete/{id}', [CustomerController::class, 'delete'])->name('delete');
        */
		// Route::match(['GET'], '/brand-register', [PurchaseOrderController::class, 'pdfBrandRegister'])->name('brand_register');
		// Route::match(['GET'], '/monthwise-report', [PurchaseOrderController::class, 'pdfMonthwiseReport'])->name('monthwise_report');
		// Route::match(['GET'], '/item-wise-sales-report', [PurchaseOrderController::class, 'pdfItemWiseSalesReport'])->name('pdf3');
		// Route::match(['GET'], '/e-report', [PurchaseOrderController::class, 'pdfEReport'])->name('pdf4');


        // Route::match(['POST'], '/settlement_add', [SettlementController::class, 'store'])->name('asdddd');


	});

    Route::match(['POST'], '/settlement_add', [SettlementController::class, 'store'])->name('settlement_add');

	Route::prefix('customer')->name('customer.')->group(function () {
		Route::match(['GET', 'POST'], '/add', [CustomerController::class, 'add'])->name('add');
		Route::match(['GET', 'POST'], '/list', [CustomerController::class, 'list'])->name('list');
		Route::match(['GET', 'POST'], '/edit/{id}', [CustomerController::class, 'edit'])->name('edit');
		Route::match(['GET', 'POST'], '/delete/{id}', [CustomerController::class, 'delete'])->name('delete');
	});

	Route::prefix('user')->name('user.')->group(function () {
		Route::match(['GET', 'POST'], '/add', [UserController::class, 'add'])->name('add');
		Route::match(['GET', 'POST'], '/list', [UserController::class, 'list'])->name('list');
		Route::match(['GET', 'POST'], '/edit/{id}', [UserController::class, 'edit'])->name('edit');
		Route::match(['GET', 'POST'], '/delete/{id}', [UserController::class, 'delete'])->name('delete');

		Route::match(['GET'], '/manage-user-role/{id}', [UserController::class, 'manage_user_role'])->name('manageUserRole');
		Route::match(['GET'], '/set-role/{id}/{role_id}', [UserController::class, 'set_role'])->name('setRole');
		Route::match(['GET'], '/users/change-status/{id}/{status}', [UserController::class, 'change_status'])->name('changeStatus');



		Route::match(['GET'], '/manage-role', [UserController::class, 'manage_role'])->name('manageRole');
		Route::match(['GET'], '/role_update/{id}', [UserController::class, 'role_update'])->name('roleUpdate');
		Route::match(['POST'], '/role_update/', [UserController::class, 'role_save_update'])->name('role_save_update');

		//Route::match(['GET'], '/users/change-status/{id}/{status}', [UserController::class, 'change_status'])->name('users.changeStatus')->middleware('role:admin');

		//Route::match(['GET'], '/manage-role/{id}', [UserController::class, 'manage_role'])->name('users.manageRole')->middleware('role:admin');
		//Route::match(['GET'], '/set-role/{id}/{role_id}', [UserController::class, 'set_role'])->name('users.setRole')->middleware('role:admin');

	});

	Route::prefix('supplier')->name('supplier.')->group(function () {
		Route::match(['GET', 'POST'], '/add', [SupplierController::class, 'add'])->name('add');
		Route::match(['GET', 'POST'], '/list', [SupplierController::class, 'list'])->name('list');
		Route::match(['GET', 'POST'], '/edit/{id}', [SupplierController::class, 'edit'])->name('edit');
		Route::match(['GET', 'POST'], '/delete/{id}', [SupplierController::class, 'delete'])->name('delete');
	});

	Route::prefix('product')->name('product.')->middleware('checkPermission:1')->group(function () {
		Route::match(['GET', 'POST'], '/add', [ProductController::class, 'add'])->name('add');
		Route::match(['GET', 'POST'], '/product_upload', [ProductController::class, 'product_upload'])->name('product_upload');
		Route::match(['GET', 'POST'], '/bar_product_price_upload', [ProductController::class, 'bar_product_price_upload'])->name('bar_product_price_upload');
		Route::match(['GET', 'POST'], '/product_stock_upload', [ProductController::class, 'product_stock_upload'])->name('product_stock_upload');
		Route::match(['GET', 'POST'], '/list', [ProductController::class, 'list'])->name('list');
		Route::match(['GET', 'POST'], '/edit/{id}', [ProductController::class, 'edit'])->name('edit');
		Route::match(['GET', 'POST'], '/delete/{id}', [ProductController::class, 'delete'])->name('delete');
		Route::match(['POST'], '/product_excel_import', [ProductController::class, 'product_excel_import'])->name('product_excel_import');
	});

	Route::prefix('purchase')->name('purchase.')->middleware('checkPermission:2')->group(function () {
		Route::match(['GET', 'POST'], '/invoice_upload', [PurchaseOrderController::class, 'invoice_upload'])->name('invoice_upload');

		Route::match(['GET', 'POST'], '/inward_stock', [PurchaseOrderController::class, 'create_order'])->name('inward_stock');
		Route::match(['GET', 'POST'], '/inward_list', [PurchaseOrderController::class, 'list_order'])->name('inward_list');
		Route::match(['GET', 'POST'], '/inward_edit/{id}', [PurchaseOrderController::class, 'edit_order'])->name('inward_edit');
		Route::match(['GET', 'POST'], '/material_inward', [PurchaseOrderController::class, 'material_inward'])->name('material_inward');
		Route::match(['GET', 'POST'], '/supplier_bill', [PurchaseOrderController::class, 'supplier_bill'])->name('supplier_bill');
		Route::match(['GET', 'POST'], '/debitnote', [PurchaseOrderController::class, 'debitnote'])->name('debitnote');

		Route::match(['GET', 'POST'], '/update-inward-stock/{id}', [PurchaseOrderController::class, 'updateInwardStock'])->name('inward_stock.update');
		Route::match(['GET', 'POST'], '/update-inward-stock/delete/{id}', [PurchaseOrderController::class, 'deleteInwardStock'])->name('inward-stock.delete');
		Route::match(['GET'], '/ajax-get', [PurchaseOrderController::class, 'ajaxPurchaseById'])->name('list.ajax');

		Route::match(['GET', 'POST'], '/stock-transfer', [PurchaseOrderController::class, 'stockTranfer'])->name('stock.transfer');
		Route::match(['GET', 'POST'], '/stock-transfer-request', [PurchaseOrderController::class, 'stockTranferRequest'])->name('stock.transferRequest');
		Route::match(['GET', 'POST'], '/opening-stock', [PurchaseOrderController::class, 'setOpeningStock'])->name('opening_stock');

		Route::match(['GET', 'POST'], '/product_stock_upload', [PurchaseOrderController::class, 'product_stock_upload'])->name('product_stock_upload');
	});

	Route::prefix('report')->name('report.')->middleware('checkPermission:3')->group(function () {
		// Route::match(['GET'], '/sales', [ReportController::class, 'sales'])->name('sales');
		// Route::prefix('invoice')->name('invoice.')->group(function () {
		// 	Route::match(['GET'], '/report', [ReportController::class, 'invoice_report'])->name('invoice_report');
		// 	Route::match(['GET'], '/test_report', [ReportController::class, 'test_report'])->name('test_report');
		// });
		// Route::prefix('counter')->name('counter.')->group(function () {
		// 	Route::match(['GET'], '/purchase', [ReportController::class, 'counterPurchase'])->name('counter_purchase');
		// });
		Route::match(['GET'], '/sales/sales-product', [ReportController::class, 'salesProduct'])->name('sales.product');
		// Route::match(['GET'], '/sales-product/download', [ReportController::class, 'salesProductDownload'])->name('sales.product.download');

		Route::match(['GET'], '/purchase', [ReportController::class, 'purchase'])->name('purchase');
		Route::match(['GET'], '/purchase/invoice-wise', [ReportController::class, 'invoiceWisePurchaseReport'])->name('purchase.invoice_wise');
		Route::match(['GET'], '/stock-product-list/{slug}', [ReportController::class, 'stockProductList'])->name('stock_product.list');
		Route::match(['GET'], '/purchase/product-wise', [ReportController::class, 'productWisePurchaseReport'])->name('purchase.product_wise');
		Route::match(['GET'], '/inventory', [ReportController::class, 'inventory'])->name('inventory');

		// Route::match(['GET'], '/reminders', [ReportController::class, 'reminders'])->name('reminders');
		Route::match(['GET'], '/sales/sales-item', [ReportController::class, 'salesItems'])->name('sales.item');
		Route::match(['GET'], '/item-wise-sales-report', [ReportController::class, 'itemWiseSaleReportPdf'])->name('sales.product.item_wise');
		Route::match(['GET'], '/product-wise-sales-report', [ReportController::class, 'productWiseSaleReport'])->name('sales.report.product.wise');
		// Route::match(['GET'],'/stock-transfer-report', [ReportController::class, 'stockTransferReport'])->name('sales.report.stock_transfer');
		// Route::match(['GET'],'/month-wise-pdf', [ReportController::class, 'monthWiseReportPdf'])->name('product.month_wise');
		// Route::match(['GET'], '/brand_report', [ReportController::class, 'brand_report'])->name('sales.product.product_wise');
		// Route::match(['GET'], '/e-report', [ReportController::class, 'e_report'])->name('sales.product.e_report');
		// Route::match(['GET'],'/item-wise-sales-stock-transfer-report', [ReportController::class, 'itemWiseSaleStockTransferReportPdf'])->name('sales.product.item_wise_stock-transfer');
		// Route::match(['GET'],'/brand-wise-sales-stock-transfer-report', [ReportController::class, 'stockTransferbrandReport'])->name('sales.product.brand_wise_stock-transfer');
		// Route::match(['GET'],'/month-wise-stock-transfer-pdf', [ReportController::class, 'monthWiseStockTransferReportPdf'])->name('product.month_wise_stock_transfer');
		// Route::match(['GET'], '/stock-transfer-e-report', [ReportController::class, 'stock_transfer_e_report'])->name('sales.product.stock_transfer_e_report');
		//Route::match(['GET', 'POST'], '/list', [ProductController::class, 'list'])->name('list');
		//Route::match(['GET', 'POST'], '/edit/{id}', [ProductController::class, 'edit'])->name('edit');
		//Route::match(['GET', 'POST'], '/delete/{id}', [ProductController::class, 'delete'])->name('delete');

		Route::match(['GET'], '/top_selling_products', [ReportController::class, 'top_selling_products'])->name('top_selling_products');
		Route::match(['GET'], '/low_stock_product', [ReportController::class, 'low_stock_product'])->name('low_stock_product');
		Route::match(['GET'], '/zero_stock_product', [ReportController::class, 'zero_stock_product'])->name('zero_stock_product');
		Route::match(['GET'], '/near_expiry_stock', [ReportController::class, 'near_expiry_stock'])->name('near_expiry_stock');

		Route::match(['GET'], '/near_expiry_stock_download', [ReportController::class, 'near_expiry_stock_download'])->name('near_expiry_stock_download');
		Route::match(['GET'], '/top_selling_product_download', [ReportController::class, 'top_selling_product_download'])->name('top_selling_product_download');
		Route::match(['GET'], '/low_stock_product_download', [ReportController::class, 'low_stock_product_download'])->name('low_stock_product_download');
		Route::match(['GET'], '/purchase_invoice_wise_download', [ReportController::class, 'purchase_invoice_wise_download'])->name('purchase_invoice_wise_download');
		Route::match(['GET'], '/purchase_product_wise_download', [ReportController::class, 'purchase_product_wise_download'])->name('purchase_product_wise_download');
		Route::match(['GET'], '/invoice_wies_sale_download', [ReportController::class, 'invoice_wies_sale_download'])->name('invoice_wies_sale_download');
		Route::match(['GET'], '/product_wise_sales_download', [ReportController::class, 'product_wise_sales_download'])->name('product_wise_sales_download');
		Route::match(['GET'], '/zero_stock_product_download', [ReportController::class, 'zero_stock_product_download'])->name('zero_stock_product_download');
		Route::match(['GET'], '/inventory_download', [ReportController::class, 'inventory_download'])->name('inventory_download');
	});



	Route::prefix('store')->name('store.')->middleware('checkPermission:5')->group(function () {
		Route::match(['GET', 'POST'], '/add', [StoreController::class, 'add'])->name('add');
		Route::match(['GET', 'POST'], '/list', [StoreController::class, 'list'])->name('list');
		Route::match(['GET', 'POST'], '/edit/{id}', [StoreController::class, 'edit'])->name('edit');
		Route::match(['GET', 'POST'], '/delete/{id}', [StoreController::class, 'delete'])->name('delete');
		Route::match(['GET'], '/users/change-status/{id}/{status}', [StoreController::class, 'change_status'])->name('changeStatus');
	});
	Route::prefix('brand')->name('brand.')->group(function () {
		Route::match(['GET', 'POST'], '/add', [BrandController::class, 'add'])->name('add');
		Route::match(['GET', 'POST'], '/list', [BrandController::class, 'list'])->name('list');
		Route::match(['GET', 'POST'], '/edit/{id}', [BrandController::class, 'edit'])->name('edit');
		Route::match(['GET', 'POST'], '/delete/{id}', [BrandController::class, 'delete'])->name('delete');
		Route::match(['GET'], '/users/change-status/{id}/{status}', [BrandController::class, 'change_status'])->name('changeStatus');
	});
	Route::prefix('dosage')->name('dosage.')->group(function () {
		Route::match(['GET', 'POST'], '/add', [DosageController::class, 'add'])->name('add');
		Route::match(['GET', 'POST'], '/list', [DosageController::class, 'list'])->name('list');
		Route::match(['GET', 'POST'], '/edit/{id}', [DosageController::class, 'edit'])->name('edit');
		Route::match(['GET', 'POST'], '/delete/{id}', [DosageController::class, 'delete'])->name('delete');
		Route::match(['GET'], '/users/change-status/{id}/{status}', [DosageController::class, 'change_status'])->name('changeStatus');
	});
	Route::prefix('company')->name('company.')->group(function () {
		Route::match(['GET', 'POST'], '/add', [CompanyController::class, 'add'])->name('add');
		Route::match(['GET', 'POST'], '/list', [CompanyController::class, 'list'])->name('list');
		Route::match(['GET', 'POST'], '/edit/{id}', [CompanyController::class, 'edit'])->name('edit');
		Route::match(['GET', 'POST'], '/delete/{id}', [CompanyController::class, 'delete'])->name('delete');
		Route::match(['GET'], '/users/change-status/{id}/{status}', [CompanyController::class, 'change_status'])->name('changeStatus');
	});

	Route::prefix('embloyees')->name('embloyees.')->middleware('checkPermission:6')->group(function () {
		Route::match(['GET', 'POST'], '/add', [EmbloyeesController::class, 'add'])->name('add');
		Route::match(['GET', 'POST'], '/list', [EmbloyeesController::class, 'list'])->name('list');
		Route::match(['GET', 'POST'], '/edit/{id}', [EmbloyeesController::class, 'edit'])->name('edit');
		Route::match(['GET', 'POST'], '/delete/{id}', [EmbloyeesController::class, 'delete'])->name('delete');
		Route::match(['GET'], '/users/change-status/{id}/{status}', [EmbloyeesController::class, 'change_status'])->name('changeStatus');
	});

	Route::prefix('restaurant')->name('restaurant.')->group(function () {
		Route::prefix('waiter')->name('waiter.')->group(function () {
			Route::match(['GET', 'POST'], '/add', [WaiterController::class, 'add'])->name('add');
			Route::match(['GET', 'POST'], '/list', [WaiterController::class, 'list'])->name('list');
			Route::match(['GET', 'POST'], '/edit/{id}', [WaiterController::class, 'edit'])->name('edit');
			Route::match(['GET', 'POST'], '/delete/{id}', [WaiterController::class, 'delete'])->name('delete');
		});

		Route::prefix('table')->name('table.')->group(function () {
			Route::match(['GET', 'POST'], '/add', [ManageTableController::class, 'add'])->name('add');
			Route::match(['GET', 'POST'], '/list', [ManageTableController::class, 'list'])->name('list');
			Route::match(['GET', 'POST'], '/edit/{id}', [ManageTableController::class, 'edit'])->name('edit');
			Route::match(['GET', 'POST'], '/delete/{id}', [ManageTableController::class, 'delete'])->name('delete');
		});
		Route::prefix('product')->name('product.')->group(function () {
			/* Route::match(['GET', 'POST'], '/add', [ManageTableController::class, 'add'])->name('add'); */
			Route::match(['GET', 'POST'], '/list', [ProductController::class, 'restaurantProductList'])->name('list');
			/* Route::match(['GET', 'POST'], '/edit/{id}', [ManageTableController::class, 'edit'])->name('edit');
            Route::match(['GET', 'POST'], '/delete/{id}', [ManageTableController::class, 'delete'])->name('delete'); */
		});
	});

	Route::get('/invoice', [ReportController::class, 'invoicePdf'])->name('sale_pdf');

	//Ajax get customer by name type
	Route::get('/get-customer-by-name', [ReportController::class, 'getCustomerByKeyup'])->name('ajax.customer-list');
	Route::get('/get-sale-invoice-by-name', [ReportController::class, 'getSaleInvoiceByKeyup'])->name('ajax.sale-invoice-list');
	Route::get('/get-purchase-invoice-by-name', [ReportController::class, 'getPurchaseInvoiceByKeyup'])->name('ajax.purchase-invoice-list');
	Route::get('/get-product-by-name', [ReportController::class, 'getProductByKeyup'])->name('ajax.sale-product');


	Route::prefix('expense')->name('expense.')->group(function () {
		Route::match(['GET', 'POST'], '/add', [ExpenseController::class, 'add'])->name('add');
		Route::match(['GET', 'POST'], '/category', [ExpenseController::class, 'category'])->name('category');
		Route::match(['GET', 'POST'], '/delete/{id}', [ExpenseController::class, 'delete'])->name('category.delete');
		Route::match(['GET', 'POST'], '/expenselist', [ExpenseController::class, 'expenselist'])->name('expenselist');
		Route::match(['GET', 'POST'], '/addexpense', [ExpenseController::class, 'addexpense'])->name('addexpense');

		Route::match(['GET', 'POST'], '/expenseedit/{id}', [ExpenseController::class, 'expenseedit'])->name('expenseedit');
		Route::match(['GET', 'POST'], '/expensdelete/{id}', [ExpenseController::class, 'expensdelete'])->name('expensdelete');
	});

	Route::match(['GET'], '/allnotification', [NotificationController::class, 'allnotification'])->name('allnotification');

	Route::match(['GET'], '/credit_history', [CredithistoryController::class, 'index'])->name('credit_history');
	Route::match(['post'], '/paymentcreditadd', [CredithistoryController::class, 'store'])->name('paymentcreditadd');
	Route::match(['get'], '/suppliercredithistory_modal/{supplier_id}', [CredithistoryController::class, 'suppliercredithistory_modal'])->name('suppliercredithistory_modal');
	Route::match(['get'], '/supplierpaymenthistory_modal/{supplier_id}', [CredithistoryController::class, 'supplierpaymenthistory_modal'])->name('supplierpaymenthistory_modal');
	Route::match(['get'], '/suppliercredithistory/{supplier_id}', [CredithistoryController::class, 'suppliercredithistory'])->name('suppliercredithistory');
	Route::match(['get'], '/supplierpaymenthistory/{supplier_id}', [CredithistoryController::class, 'supplierpaymenthistory'])->name('supplierpaymenthistory');
});


// Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/ajaxpost', [App\Http\Controllers\AjaxController::class, 'ajaxpost']);
Route::post('/ajaxpost', [App\Http\Controllers\AjaxController::class, 'ajaxpost']);

Route::match(['GET'], '/print', [PosController::class, 'print_invoice'])->name('print');

Route::match(['GET'], '/pushertest', [NotificationController::class, 'pushertest'])->name('pushertest');
Route::match(['GET'], '/test', [NotificationController::class, 'test'])->name('test');

Route::match(['GET'], '/product_expiry_notification', [NotificationController::class, 'product_expiry_notification'])->name('product_expiry_notification');

Route::match(['GET'], '/seenNotification', [NotificationController::class, 'seenNotification'])->name('seenNotification');


// Route::get('test', function () {
//     event(new App\Events\StockAlert('Someone'));
//     return "Event has been sent!";
// });
