<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class StoreDetails extends Model
{
    use HasFactory;
   //use SoftDeletes;
	
	protected $table = 'store_details';
	protected $guarded	= [];

}