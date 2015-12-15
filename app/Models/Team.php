<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model{
    use SoftDeletes;
    protected $table     = 'teams';
    protected $fillable  = ['id','uuid','name','agency_id','category','deleted_at'];

    protected $dates = ['deleted_at'];
}