<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Expense extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'expense';
    protected $dates = ['deleted_at'];
    protected $guarded = [];


    public function expensecategory(){
		  return $this->hasOne(ExpenseCategory::class,'id', 'category_id'); 
    }

    public function user(){
      return $this->hasOne(User::class,'id', 'branch_id'); 
    }

}
