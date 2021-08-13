<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name', 'hours', 'price_per_hour'
    ];

    // public function Company()
    // {
    //     return $this->belongsTo('App/Models/Company', 'company_id', 'id');
    // }

}
