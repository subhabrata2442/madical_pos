<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class BranchStockRequest extends Model
{
    use HasFactory;
    //use SoftDeletes;
	
	protected $table = 'branch_stock_request';
	protected $guarded	= [];

    public function user(){
        return $this->hasOne(User::class,'id', 'to_store_id'); 
    }
    public function store(){
        return $this->hasOne(User::class,'id', 'from_store_id'); 
    }
    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }
    public function stockProduct(){
        return $this->hasOne(BranchStockProductSellPrice::class,'stock_id','id');
    }
    public function product_barcode(){
        return $this->hasOne(ProductRelationshipSize::class, 'product_id', 'product_id')->first();
    }
	
	

}