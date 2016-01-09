<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailVerification extends Model{
    use SoftDeletes;

    protected $table = "email_verifications";
    protected $fillable =['verification_type','token','user_id','is_verified','expired_at','deleted_at'];

    protected $dates = ['deleted_at'];
}