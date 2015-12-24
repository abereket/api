<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model{
    use SoftDeletes;
    protected $table     = 'users';
    protected $fillable  = ['id','uuid','first_name','last_name','email','password','type','verified','deleted_at'];

    protected $dates = ['deleted_at'];
}