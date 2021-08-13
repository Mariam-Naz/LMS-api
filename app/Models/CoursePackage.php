<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePackage extends Model
{
    use HasFactory;

    protected $table = 'coursepackages';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id', 'package_id'
    ];

    public function Course()
    {
        return $this->belongsTo('App/Models/Course', 'course_id', 'id');
    }

    public function Package()
    {
        return $this->belongsTo('App/Models/Package', 'package_id', 'id');
    }
}
