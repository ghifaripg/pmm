<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SasaranStrategis extends Model
{
    protected $table = 'sasaran_strategis';

    protected $fillable = [
        'kontrak_id',
        'name',
        'position',
    ];

    public function kontrak()
    {
        return $this->belongsTo(KontrakManajemen::class, 'kontrak_id', 'kontrak_id');
    }
}
