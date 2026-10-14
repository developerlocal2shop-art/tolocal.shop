<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectoryType extends Model
{
    use HasFactory;
    protected $table = 'directory_type';
    protected $fillable = [
        'directory_id', 
        'category_id',
        'input_type',
        'column_name',
        'column_value',
        'label',
        'block_num'
    ];
}
