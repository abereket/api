<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyResults extends Model{
    use SoftDeletes;
    protected $table = "survey_results";
    protected $fillable = ['id','user_id','job_id','survey_id','rating','years_of_experience','deleted_at'];
    protected $dates = ['deleted_at'];
}