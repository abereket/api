<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model{
    protected $table     = 'teams';
    protected $fillable  = ['id','uuid','name','agency_id','category','deleted_at'];
}