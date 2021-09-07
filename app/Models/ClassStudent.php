<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassStudent extends Model
{
    use HasFactory;

    protected $table = 'classstudents';
    protected $primaryKey = 'id';
    protected $fillable = [
        'class_id', 'student_id', 'status'
    ];

    // public function Company()
    // {
    //     return $this->belongsTo('App/Models/Company', 'company_id', 'id');
    // }
}
