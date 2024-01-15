<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
class InwardStockProductsDraft extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = 'inward_stock_products_draft';
	protected $guarded	= [];

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }

}
