<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TeamMember extends Model{

     use SoftDeletes;
     protected $table = 'team_members';

     protected $fillable =['id','team_id','user_id','deleted_at'];

    protected $dates = ['deleted_at'];
 }

