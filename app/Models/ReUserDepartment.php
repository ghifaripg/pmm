<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ReUserDepartment extends Pivot
{
    protected $table = 're_user_department';

    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'department_id',
        'department_role',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }
}
