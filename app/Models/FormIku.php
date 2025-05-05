<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormIku extends Model
{
    protected $table = 'form_iku';

    protected $fillable = [
        'iku_id',
        'sasaran_id',
        'version',
        'iku_atasan',
        'isi_iku_id',
        'target',
        'is_multi_point',
        'base',
        'stretch',
        'satuan',
        'polaritas',
        'bobot',
    ];

    public function iku()
    {
        return $this->belongsTo(Iku::class, 'iku_id', 'iku_id');
    }

    public function sasaran()
    {
        return $this->belongsTo(SasaranStrategis::class, 'sasaran_id');
    }

    public function isiIku()
    {
        return $this->belongsTo(IsiIku::class, 'isi_iku_id');
    }

    public function points()
    {
        return $this->hasMany(IkuPoint::class, 'form_iku_id');
    }
}
