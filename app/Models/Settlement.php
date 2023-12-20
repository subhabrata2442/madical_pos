<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use HasFactory;

    protected $table = 'settlement';

    protected $guarded = [];

    public function user(){
        return $this->hasOne(User::class,'id', 'store_id');
    }

}
