<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agency extends Model{
    use SoftDeletes;
    protected $table     = 'agencies';
    protected $fillable  = ['id','uuid','name','user_id','description','deleted_at'];

    protected $dates = ['deleted_at'];
}