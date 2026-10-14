<?php

namespace App\Models\client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlistdirectory extends Model
{
    use HasFactory;
    protected $table = 'wislist_directory';
    protected $fillable = [
        'id',
        'user_id', 
        'directory_id',
        'created_at'
    ];
}
