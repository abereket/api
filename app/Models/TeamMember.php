<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TeamMember extends Model{

     protected $table = 'team_members';

     protected $fillable =['id','team_id','user_id','deleted_at'];
 }

