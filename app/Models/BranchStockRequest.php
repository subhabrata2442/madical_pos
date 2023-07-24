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
	
	

}