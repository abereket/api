<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reference extends Model{
    use SoftDeletes;
    protected $table = 'references';

    protected $fillable = ['user_id','candidate_id','first_name','last_name','email','company_with_candidate','position'];

    protected $dates = ['deleted_at'];
}