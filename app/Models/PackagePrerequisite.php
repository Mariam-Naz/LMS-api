<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagePrerequisite extends Model
{
    use HasFactory;

    protected $table = 'packageprerequisites';
    protected $primaryKey = 'id';
    protected $fillable = [
        'package_id', 'pre_package_id'
    ];

    public function Package()
    {
        return $this->belongsTo('App/Models/Package', ['package_id','pre_package_id'], 'id');
    }
}
