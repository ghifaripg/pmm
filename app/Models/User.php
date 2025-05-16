<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = ['username', 'nama', 'password', 'department_id', 'division_id', 'director_id', 'role'];
    public $timestamps = true;

    public function departments()
    {
        return $this->belongsToMany(Department::class, 're_user_department', 'user_id', 'department_id')
            ->withPivot('department_role');
    }

}
