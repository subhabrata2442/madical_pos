<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\PurchaseInwardStock;
use App\Models\BranchStockProducts;
use App\Models\InwardStockProducts;
use App\Models\SellInwardStock;
use App\Models\Customer;
use App\Models\SellStockProducts;
use App\Models\SellInwardTenderedChangeAmount;

class SyncDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize local database with live server.';

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
        // Fetch data from offline database
        //$localData = User::all();

        // Fetch data from online database
        //$onlineData = User::on('live_server_connection')->get();

        /* foreach ($localData as $data) {
            User::on('live_server_connection')->updateOrCreate([
                'id' => $data->id,
            ], [
                // Update the column values accordingly
                'name' => $data->name,
                //'column2' => $data->column2,
                // ...
            ]);
        } */

        // Fetch data from offline database
        $localPurchaseInwardStock = PurchaseInwardStock::all();
        $localBranchProductStock = BranchStockProducts::all();
        //Update or create purchase inword stock in live server
        foreach ($localPurchaseInwardStock as $data) {
            $purchase_inward_stock = PurchaseInwardStock::on('live_server_connection')->updateOrCreate([
                'branch_id' => $data->branch_id,
                'invoice_no' => $data->invoice_no,
            ], [
                // Update the column values accordingly

                'supplier_id'          => $data->supplier_id,
                'supplier_name'      => $data->supplier_name,
                'purchase_date'      => $data->purchase_date,
                // 'inward_date'  		=> $inward_stock['invoice_inward_date'],
                'payment_method'      => $data->payment_method,
                'payment_debt_day'  => $data->payment_debt_day,
                'payment_date'      => $data->payment_date,
                'payment_discount'  => $data->payment_discount,
                'payment_ref_no'      => $data->payment_ref_no,
                'total_qty'          => $data->total_qty,
                'gross_amount'      => $data->gross_amount,
                'sub_total'          => $data->sub_total,
                'additional_note'      => $data->additional_note,
                'total_amount'      => $data->total_amount,
                'currency_type'      => $data->currency_type,
                'rate'              => $data->rate,
                'total_profit'      => $data->total_profit,
                'total_profit_percent' => $data->total_profit_percent,
                //'column2' => $data->column2,
                // ...
            ]);
            //get InwardStockProducts data form local
            $localInwardStockProducts =  InwardStockProducts::where('inward_stock_id', $data->id)->where('branch_id', $data->branch_id)->get();
            foreach ($localInwardStockProducts as $inwardStockProductsdata) {
                InwardStockProducts::on('live_server_connection')->updateOrCreate([
                    'branch_id' => $inwardStockProductsdata->branch_id,
                    'inward_stock_id' => $purchase_inward_stock->id,
                ], [
                    // Update the column values accordingly
                    'product_id'          => $inwardStockProductsdata->product_id,
                    'brand'              => $inwardStockProductsdata->brand,
                    'product_name'      => $inwardStockProductsdata->product_name,
                    'dosage'              => $inwardStockProductsdata->dosage,
                    'company'              => $inwardStockProductsdata->company,
                    'drugstore'          => $inwardStockProductsdata->drugstore,
                    'product_qty'          => $inwardStockProductsdata->product_qty,
                    'total_qty'          => $inwardStockProductsdata->total_qty,
                    'no_package'          => $inwardStockProductsdata->no_package,
                    'net_price_befour_discount' => $inwardStockProductsdata->net_price_befour_discount,
                    'discount_cost'      => $inwardStockProductsdata->discount_cost,
                    //'discount_persent'  => $inward_stock['product_detail'][$i]['product_discount'],
                    'is_chronic'          => $inwardStockProductsdata->is_chronic,
                    'net_price'          => $inwardStockProductsdata->net_price,
                    'product_mrp'          => $inwardStockProductsdata->product_mrp,
                    'chronic_amount'      => $inwardStockProductsdata->chronic_amount,
                    'option_product_mrp' => $inwardStockProductsdata->option_product_mrp,
                    'cost_price'          => $inwardStockProductsdata->cost_price,
                    'cost_rate'          => $inwardStockProductsdata->cost_rate,
                    'bonous'              => $inwardStockProductsdata->bonous,
                    'selling_price'        => $inwardStockProductsdata->selling_price,
                    'profit_amount'      => $inwardStockProductsdata->profit_amount,
                    'profit_percent'      => $inwardStockProductsdata->profit_percent,
                    'chronic_amount_percentage'      => $inwardStockProductsdata->chronic_amount_percentage,
                    'product_expiry_date'      => $inwardStockProductsdata->product_expiry_date,
                    'selling_by'        => $inwardStockProductsdata->selling_by,
                    'qty_total_net_price'        => $inwardStockProductsdata->qty_total_net_price,
                    'qty_total_sell_price'        => $inwardStockProductsdata->qty_total_sell_price,
                    'qty_total_profit'        => $inwardStockProductsdata->qty_total_profit,
                ]);
            }
        }
        //Update or create Branch stock product in live server
        foreach ($localBranchProductStock as $data) {
            BranchStockProducts::on('live_server_connection')->updateOrCreate([
                'product_mrp' => $data->product_mrp,
                'product_expiry_date' => $data->product_expiry_date,
                'branch_id' => $data->branch_id,
                'product_id' => $data->product_id,
            ], [
                // Update the column values accordingly
                'product_barcode'      => $data->product_barcode,
                't_qty'              => $data->t_qty,
                'selling_price'        => $data->selling_price,
                'product_mrp'          => $data->product_mrp,
                'net_price'          => $data->net_price,
                'product_expiry_date'          => $data->product_expiry_date,
                'is_chronic'          => $data->is_chronic,
                'chronic_amount'      => $data->chronic_amount,
            ]);
        }

        //For sales
        //Local data fetach
        $localSellInwardStock = SellInwardStock::all();
        foreach ($localSellInwardStock as $sellStockData) {
            //get local customer details
            $liveCustomer_id = 0;
            if ($sellStockData->customer_id != 0) {
                $localCustomer = Customer::where('id', $sellStockData->customer_id)->first();
                $liveCustomer = Customer::on('live_server_connection')->updateOrCreate([
                    'customer_mobile' => $localCustomer->customer_mobile,
                ], [
                    // Update the column values accordingly
                    'customer_name' => $localCustomer->customer_name,
                    //'column2' => $data->column2,
                    // ...
                ]);
                $liveCustomer_id = $liveCustomer->id;
            }
            //Create or update sellinwardstock in live server
            $liveSellInwardStock = SellInwardStock::on('live_server_connection')->updateOrCreate([
                'branch_id' => $sellStockData->branch_id,
                'invoice_no' => $sellStockData->invoice_no,
            ], [
                // Update the column values accordingly
                'customer_id'                 => $liveCustomer_id,
                'bill_no'                     => $sellStockData->bill_no,
                'invoice_no'                 => $sellStockData->invoice_no,
                'sell_date'                 => $sellStockData->sell_date,
                'sell_time'                 => $sellStockData->sell_time,
                //'stock_type' 				=> $sellStockData->stock_type,
                'total_qty'                 => $sellStockData->total_qty,
                'gross_amount'                 => $sellStockData->gross_amount,
                'tax_amount'                 => $sellStockData->tax_amount,
                'discount_amount'             => $sellStockData->discount_amount,
                'sub_total'                 => $sellStockData->sub_total,
                'round_off_amount'             => $sellStockData->round_off_amount,
                'gross_total_amount'        => $sellStockData->gross_total_amount,
                'special_discount_percent'    => $sellStockData->special_discount_percent,
                'special_discount_amt'         => $sellStockData->special_discount_amt,
                'pay_amount'                 => $sellStockData->pay_amount,
                'tendered_due_amount'         => $sellStockData->tendered_due_amount,
                'tendered_amount'             => $sellStockData->tendered_amount,
                'tendered_change_amount'     => $sellStockData->tendered_change_amount,
                'payment_method'             => $sellStockData->payment_method,
                'payment_date'                 => $sellStockData->payment_date,
                'charge_amount'             => $sellStockData->charge_amount,
                //'created_at'				=> date('Y-m-d'),
                'net_price'             => $sellStockData->net_price,
                'profit_price'             => $sellStockData->profit_price,
            ]);



            $localSellStockProducts =  SellStockProducts::where('inward_stock_id', $sellStockData->id)->where('branch_id', $sellStockData->branch_id)->get();
            foreach ($localSellStockProducts as $sellStockProductsData) {
                SellStockProducts::on('live_server_connection')->updateOrCreate([
                    'branch_id' => $sellStockProductsData->branch_id,
                    'product_id' => $sellStockProductsData->product_id,
                    'inward_stock_id' => $liveSellInwardStock->id,
                ], [
                    // Update the column values accordingly
                    'product_stock_id'  => 0, //later implement with BranchStockProducts
                    'barcode'            => $sellStockProductsData->barcode,
                    'product_name'      => $sellStockProductsData->product_name,
                    'brand_name'      => $sellStockProductsData->brand_name,
                    'price_id'          => $sellStockProductsData->price_id,
                    'category_id'          => $sellStockProductsData->category_id,
                    'subcategory_id'      => $sellStockProductsData->subcategory_id,
                    'product_qty'        => $sellStockProductsData->product_qty,
                    'discount_percent'  => $sellStockProductsData->discount_percent,
                    'discount_amount'      => $sellStockProductsData->discount_amount,
                    'product_mrp'        => $sellStockProductsData->product_mrp,
                    'unit_price'          => $sellStockProductsData->unit_price,
                    'offer_price'          => $sellStockProductsData->offer_price,
                    'total_cost'        => $sellStockProductsData->total_cost,
                ]);
            }

            $localSellInwardTenderedChangeAmount = SellInwardTenderedChangeAmount::where('sell_inward_stock_id', $sellStockData->id)->first();
            if ($localSellInwardTenderedChangeAmount) {
                SellInwardTenderedChangeAmount::on('live_server_connection')->updateOrCreate([
                    'sell_inward_stock_id' => $liveSellInwardStock->id,
                ], [
                    // Update the column values accordingly
                    'type'                  => $localSellInwardTenderedChangeAmount->type,
                    'rupee_val'              => $localSellInwardTenderedChangeAmount->rupee_val,
                    'qty'                    => $localSellInwardTenderedChangeAmount->qty,
                    'amount'                  => $localSellInwardTenderedChangeAmount->amount,
                    'created_at'            => $localSellInwardTenderedChangeAmount->created_at,
                ]);
            }
        }

        //$this->info('Local database synchronized with the live server.');

        \Log::info('Local database synchronized with the live server.');
    }
}
