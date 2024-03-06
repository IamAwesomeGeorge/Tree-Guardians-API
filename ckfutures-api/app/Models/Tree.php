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
        'creation_date',
        'id_user',
        'species',
        'latitude',
        'longitude',
        'health_status',
        'circumference',
        'planted',
        'height',
        'is_deleted'
    ];

}
