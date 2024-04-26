<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreeImage extends Model
{
    use HasFactory;

    protected $table = 'tree_image';
    public $timestamps = false;

    protected $fillable = [
        'id_tree',
        'image_index',
        'file_type',
        'id_user',
        'upload_date'
    ];

}
