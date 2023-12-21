<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\Media;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Abcdefg;
use App\Models\Material;
use App\Models\Service;
use App\Models\Size;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\MasterProducts;
use App\Models\VendorCode;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\StockTransferHistory;
use App\Models\SellInwardStock;

use App\Models\Company;
use App\Models\Dosage;
use App\Models\Drugstore;



use App\Models\Counter;
use App\Models\StockTransferCounterHistory;
use App\Models\CounterWiseStock;


use App\Models\SupplierGst;
use App\Models\ProductRelationshipSize;

use App\Models\PurchaseInwardStock;
use App\Models\InwardStockProducts;

use App\Models\BranchStockProducts;
use App\Models\BranchStockProductSellPrice;

use App\Models\RestaurantFloor;
use App\Models\FloorWiseTable;
use App\Models\TableBookingHistory;
use App\Models\BarProductSizePrice;
use App\Models\Customer;

use App\Models\BranchStockRequest;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

use Auth;
use DB;

class BillController extends Controller
{
    public function bill(Request $request){
        $store_id	= Session::get('store_id');

        $data = [];
        $data['latest_bill'] = SellInwardStock::where('branch_id', $store_id)->orderBy('id', 'DESC')->paginate(20);

        if(!empty($request->get('bill_no'))){
            $data['latest_bill'] = SellInwardStock::where('branch_id', $store_id)->where('bill_no', $request->get('bill_no'))->orderBy('id', 'DESC')->paginate(20);
        }


        $data['heading'] = 'Bill';
        $data['breadcrumb'] = ['Bill', 'List'];
        return view('admin.bill.index', compact('data'));

    }
}
