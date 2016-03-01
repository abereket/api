<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveySkills extends Model{
    use SoftDeletes;
    protected $table = "survey_skills";
    protected $fillable = ['user_id','survey_id','skill_name','deleted_at'];

    protected $dates = ['deleted_at'];
 }