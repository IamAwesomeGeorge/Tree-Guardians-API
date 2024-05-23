<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeLog extends Model
{
    use HasFactory;

    protected $table = 'change_log';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'date',
        'old_values',
        'new_values',
        'table_name',
        'operation',
        'approved',
        'approved_by'
    ];
}
