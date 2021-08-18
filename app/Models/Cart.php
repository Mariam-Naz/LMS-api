<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'package_id', 'courses', 'payment_method', 'is_paid', 'transaction_id'
    ];

}
