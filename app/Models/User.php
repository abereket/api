<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class User extends Model{

    protected $table    = 'users';
    protected $fillable = ['id','uuid','first_name','last_name','email','password','type','verified','deleted_at'];
}