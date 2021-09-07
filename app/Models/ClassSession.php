<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    use HasFactory;

    protected $table = 'classsessions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'initiator_id', 'schedule_id'
    ];

    // public function Company()
    // {
    //     return $this->belongsTo('App/Models/Company', 'company_id', 'id');
    // }
}
