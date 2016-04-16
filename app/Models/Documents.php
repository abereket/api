<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Documents extends Model{
    use SoftDeletes;
    protected $table = 'documents';
    protected $fillable = ['id','user_id','name','path','type','deleted_at'];

    protected $dates = ['deleted_at'];
}