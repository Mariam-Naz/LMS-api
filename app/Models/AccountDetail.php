<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountDetail extends Model
{
    use HasFactory;

    protected $table = 'accountdetails';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'account_type', 'payment_method', 'account_number', 'reference_name', 'reference_email'
    ];
}
