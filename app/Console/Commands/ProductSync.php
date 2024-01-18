<?php

namespace App\Console\Commands;

use App\Models\RoleWisePermission;
use App\Models\StoreDetails;
use App\Models\User;
use App\Models\UserRolePermission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Dosage;
use App\Models\Company;
use DB;

class ProductSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize live server database to  local db.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $livebrands = Brand::on('live_server_connection')->get();

        if ($livebrands) {
            foreach ($livebrands as $livebrand) {
                $localbrand = Brand::updateOrCreate([
                    'id' => $livebrand->id,
                ], [
                    'name'          => $livebrand->name,
                    'slug'          => $livebrand->slug,
                    'deleted_at'    => $livebrand->deleted_at,
                ]);
            }
        }

        $livedosages = Dosage::on('live_server_connection')->get();

        if ($livedosages) {
            foreach ($livedosages as $livedosage) {
                $localdosage = Dosage::updateOrCreate([
                    'id' => $livedosage->id,
                ], [
                    'name'          => $livedosage->name,
                    'slug'          => $livedosage->slug,
                    'deleted_at'    => $livedosage->deleted_at,
                ]);
            }
        }


        $livecompanys = Company::on('live_server_connection')->get();

        if ($livecompanys) {
            foreach ($livecompanys as $livecompany) {
                $localcompanys = Company::updateOrCreate([
                    'id' => $livecompany->id,
                ], [
                    'name'          => $livecompany->name,
                    'slug'          => $livecompany->slug,
                ]);
            }
        }

        $liveproducts = Product::on('live_server_connection')->get();

        if ($liveproducts) {
            foreach ($liveproducts as $liveproduct) {
                $localroduct = Product::updateOrCreate([
                    'id' => $liveproduct->id,
                ], [
                    'uqc_id'          => $liveproduct->uqc_id,
                    'product_name'          => $liveproduct->product_name,
                    'product_barcode'          => $liveproduct->product_barcode,
                    'product_code'          => $liveproduct->product_code,
                    'brand'          => $liveproduct->brand,
                    'brand_id'          => $liveproduct->brand_id,
                    'slug'          => $liveproduct->slug,
                    'is_chronic'          => $liveproduct->is_chronic,
                    'common_items'          => $liveproduct->common_items,
                    'dosage_name'          => $liveproduct->dosage_name,
                    'dosage_id'          => $liveproduct->dosage_id,
                    'company_name'          => $liveproduct->company_name,
                    'company_id'          => $liveproduct->company_id,
                    'drugstore_name'          => $liveproduct->drugstore_name,
                    'drugstore_id'          => $liveproduct->drugstore_id,
                    'default_qty'          => $liveproduct->default_qty,
                    'total_qty'          => $liveproduct->total_qty,
                    'no_package'          => $liveproduct->no_package,
                    'selling_by'          => $liveproduct->selling_by,
                    'selling_by_name'          => $liveproduct->selling_by_name,
                    'product_mrp'          => $liveproduct->product_mrp,
                    'cost_price'          => $liveproduct->cost_price,
                    'cost_rate'          => $liveproduct->cost_rate,
                    'selling_price'          => $liveproduct->selling_price,
                    'net_price'          => $liveproduct->net_price,
                    'bonous'          => $liveproduct->bonous,
                    'profit_amount'          => $liveproduct->profit_amount,
                    'profit_percent'          => $liveproduct->profit_percent,
                    'offer_price'          => $liveproduct->offer_price,
                    'category_id'          => $liveproduct->category_id,
                    'subcategory_id'          => $liveproduct->subcategory_id,
                    'sku_code'          => $liveproduct->sku_code,
                    'days_before_product_expiry'          => $liveproduct->days_before_product_expiry,
                    'alert_product_qty'          => $liveproduct->alert_product_qty,
                    'product_desc'          => $liveproduct->product_desc,
                    'product_note'          => $liveproduct->product_note,
                    'cost_gst_percent'          => $liveproduct->cost_gst_percent,
                    'cost_gst_amount'          => $liveproduct->cost_gst_amount,
                    'sell_gst_percent'          => $liveproduct->sell_gst_percent,
                    'sell_gst_amount'          => $liveproduct->sell_gst_amount,
                    'image'          => $liveproduct->image,
                    'image_caption'          => $liveproduct->image_caption,
                    'color_id'          => $liveproduct->color_id,
                    'stock_qty'          => $liveproduct->stock_qty,
                ]);
            }
        }


        \Log::info("Live product synchronized with the local server.");
    }
}
