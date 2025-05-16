<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $table = 'division';
    protected $primaryKey = 'division_id';
    public $timestamps = false;

    protected $fillable = [
        'division_name',
        'division_username',
        'director_id',
    ];

    public function director()
    {
        return $this->belongsTo(Director::class, 'director_id');
    }

    public function departments()
    {
        return $this->hasMany(Department::class, 'division_id');
    }
}
