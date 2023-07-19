<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class RoleSubPermission extends Model
{
    use HasFactory;
    //use SoftDeletes;
	
	protected $table = 'role_sub_permission';
	protected $guarded	= [];

}