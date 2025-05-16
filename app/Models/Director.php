<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    protected $table = 'director';
    protected $primaryKey = 'director_id';
    public $timestamps = false;

    protected $fillable = [
        'director_name',
        'director_username',
    ];

    public function divisions()
    {
        return $this->hasMany(Division::class, 'director_id');
    }

    public function departments()
    {
        return $this->hasMany(Department::class, 'director_id');
    }
}
