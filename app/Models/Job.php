<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model{
    use SoftDeletes;
    protected $table = 'jobs';

    protected $fillable = ['id','user_id','title','company_name','link','is_fulfilled','is_closed','is_active','city','state','zip_code','deleted_at'];

    protected $dates = ['deleted_at'];
}