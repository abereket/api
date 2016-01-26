<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobSkills extends Model{
    use SoftDeletes;
    protected $table = 'job_skills';
    protected $fillable = ['id','job_id','name','deleted_at'];
    protected $dates = ['deleted_at'];
}