<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'department';
    protected $primaryKey = 'department_id';
    public $timestamps = false;

    protected $fillable = ['department_name', 'department_username'];

    public function users()
{
    return $this->belongsToMany(User::class, 're_user_department', 'department_id', 'user_id')
        ->withPivot('department_role');
}

}
