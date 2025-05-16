<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'department';
    protected $primaryKey = 'department_id';
    public $timestamps = false;

    protected $fillable = [
        'department_name',
        'department_username',
        'division_id',
        'director_id',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function director()
    {
        return $this->belongsTo(Director::class, 'director_id');
    }
}
