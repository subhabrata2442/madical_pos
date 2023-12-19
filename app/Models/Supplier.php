<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $guarded = [];


    public function PurchaseInwardStock(): HasMany
    {
        return $this->hasMany(PurchaseInwardStock::class,'supplier_id', 'id');
    }

    public function Suppliercreditpay(): HasMany
    {
        return $this->hasMany(Suppliercreditpay::class,'supplier_id', 'id');
    }

}
