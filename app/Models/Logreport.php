<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logreport extends Model
{
    use HasFactory;

    protected $table = 'log_report';
    protected $dates = ['deleted_at'];
    protected $guarded = [];


    public function user(){
        return $this->hasOne(User::class,'id', 'user_id');
    }

}
