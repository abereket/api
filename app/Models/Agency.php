<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model{

    protected $table    = 'agencies';
    protected $fillable = ['id','uuid','name','user_id','description','deleted_at'];
}