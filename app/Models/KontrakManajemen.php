<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontrakManajemen extends Model
{
    protected $table = 'kontrak_manajemen';
    protected $primaryKey = 'kontrak_id';
    public $incrementing = false; // karena kontrak_id bukan integer
    protected $keyType = 'string';

    protected $fillable = [
        'kontrak_id',
        'year',
    ];

    public function sasaranStrategis()
    {
        return $this->hasMany(SasaranStrategis::class, 'kontrak_id', 'kontrak_id');
    }

    public function forms()
{
    return $this->hasMany(FormKontrakManajemen::class, 'kontrak_id', 'kontrak_id');
}

}
