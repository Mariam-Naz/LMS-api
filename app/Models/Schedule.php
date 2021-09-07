<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';
    protected $primaryKey = 'id';
    protected $fillable = [
        'class_id', 'title', 'date_range', 'start_date', 'end_date'
    ];
}
