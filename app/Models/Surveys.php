<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Surveys extends Model{
    use SoftDeletes;
    protected $table     = 'surveys';

    protected $fillable  = ['job_id','user_id','name','deleted_at'];

    protected $dates     = ['deleted_at'];
}