<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tree extends Model
{
    use HasFactory;

    protected $table = 'tree';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'species',
        'latitude',
        'longitude',
        'health_status',
        'planted',
        'circumference',
        'height'
    ];

}
