<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = 'user';

    protected $fillable = [
        'email',
        'pass_hash',
        'username',
        'creation_date',
        'id_user_type'
    ];

    public function trees() {
        return $this->hasMany(Tree::class);
    }
}