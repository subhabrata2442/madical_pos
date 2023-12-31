<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class InwardStockProducts extends Model
{
    use HasFactory;
    //use SoftDeletes;

	protected $table = 'inward_stock_products';
	protected $guarded	= [];

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }
	public function invoice(){
        return $this->hasOne(PurchaseInwardStock::class,'id','inward_stock_id');
    }
	public function purchaseInwardStock(){
        return $this->hasOne(PurchaseInwardStock::class,'id','inward_stock_id');
    }
    public function store(){
        return $this->hasOne(User::class,'id','branch_id');
    }

    public function user(){
        return $this->hasOne(User::class,'id', 'branch_id');
    }
}
