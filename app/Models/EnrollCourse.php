<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollCourse extends Model
{
    use HasFactory;

    protected $table = 'enrollCourses';
    protected $primaryKey = 'id';
    protected $fillable = [
        'enroll_id', 'course_id'
    ];
}
