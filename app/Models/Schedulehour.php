<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedulehour extends Model
{
    use HasFactory;

    protected $table = 'schedulehours';
    protected $primaryKey = 'id';
    protected $fillable = [
        'schedule_id', 'day_number', 'start_time', 'end_time'
    ];
}
