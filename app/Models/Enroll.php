<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enroll extends Model
{
    use HasFactory;

    protected $table = 'enrolls';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'package_id', 'start_date', 'end_date', 'date_range', 'active'
    ];

     public function enrollCourses()
    {
        return $this->hasMany(EnrollCourse::class,'enroll_id', 'id');
    }
}
