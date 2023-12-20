<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Suppliercreditpay extends Model
{
    use HasFactory;


    protected $table = 'supplier_credit_pay';
    protected $dates = ['deleted_at'];
    protected $guarded = [];

}
