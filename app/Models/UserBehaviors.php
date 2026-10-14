<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBehaviors extends Model
{
    use HasFactory;

    function logBehavior($userId, $action, $productId = null, $category = null) {
        UserBehaviors::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'category' => $category,
            'action' => $action,
        ]);
    }

}
