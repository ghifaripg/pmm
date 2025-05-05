<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormKontrakManajemen extends Model
{
    protected $table = 'form_kontrak_manajemen';

    protected $fillable = [
        'kontrak_id',
        'sasaran_id',
        'kpi_name',
        'target',
        'satuan',
        'milestone',
        'esgc',
        'polaritas',
        'bobot',
        'du',
        'dk',
        'do',
    ];

    public function kontrak()
    {
        return $this->belongsTo(KontrakManajemen::class, 'kontrak_id', 'kontrak_id');
    }

    public function sasaran()
    {
        return $this->belongsTo(SasaranStrategis::class, 'sasaran_id');
    }
}
