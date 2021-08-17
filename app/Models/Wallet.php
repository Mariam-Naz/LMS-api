<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $table = 'wallets';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'balance'
    ];

    public function User()
    {
        return $this->belongsTo('App/Models/User', 'user_id', 'id');
    }
}
