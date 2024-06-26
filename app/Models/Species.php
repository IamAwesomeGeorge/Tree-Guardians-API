<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Species extends Model
{
    use HasFactory;
    use HasApiTokens;

    protected $table = 'species';
    public $timestamps = false;

    protected $fillable = [
        'species'
    ];
}