<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
class PurchaseInwardStockDraft extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = 'purchase_inward_stock_draft';
	protected $guarded	= [];

    public function supplier(){
        return $this->hasOne(Supplier::class,'id','supplier_id');
    }

    public function user(){
        return $this->hasOne(User::class,'id', 'branch_id');
    }

    public function inwardStockProductsdraft(){
        return $this->hasMany(InwardStockProductsDraft::class,'inward_stock_id','id');
    }


}
