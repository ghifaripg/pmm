<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iku extends Model
{
    protected $table = 'iku';
    protected $primaryKey = 'iku_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'iku_id',
        'department_name',
        'tahun',
        'created_by',
    ];

    public function formIku()
    {
        return $this->hasMany(FormIku::class, 'iku_id', 'iku_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'nama');
    }
}
