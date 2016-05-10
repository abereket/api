<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReferenceResult extends Model{
    use SoftDeletes;
    protected $table = 'reference_results';

    protected $fillable = ['id','user_id','reference_id','skill_id','comments','deleted_at'];

    protected $dates = ['deleted_at'];
}