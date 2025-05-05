<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Define the table if different from the default (optional)
    protected $table = 'users';

    // Define the fillable fields (id, name, and password)
    protected $fillable = ['username', 'nama', 'password', 'department_id'];

    // If you are using custom timestamps (created_at and updated_at),
    // you can disable them by setting this to false
    public $timestamps = true;  // Set false if your table doesn't have timestamps

    public function departments()
    {
        return $this->belongsToMany(Department::class, 're_user_department', 'user_id', 'department_id')
            ->withPivot('department_role');
    }

}
