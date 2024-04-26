<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasFactory;
    use HasApiTokens;

    protected $table = 'user';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'email',
        'pass_hash',
        'username',
        'creation_date',
        'id_user_type'
    ];

    /*
    public function trees() {
        return $this->hasMany(Tree::class);
    }
     */
}