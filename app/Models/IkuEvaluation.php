<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IkuEvaluation extends Model
{
    protected $table = 'iku_evaluations';

    protected $fillable = [
        'user_id',
        'iku_id',
        'point_id',
        'year',
        'month',
        'polaritas',
        'bobot',
        'satuan',
        'base',
        'target_bulan_ini',
        'target_sdbulan_ini',
        'realisasi_bulan_ini',
        'realisasi_sdbulan_ini',
        'percent_target',
        'percent_year',
        'ttl',
        'adj',
        'penyebab_tidak_tercapai',
        'program_kerja',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function iku()
    {
        return $this->belongsTo(FormIku::class, 'iku_id'); // form_iku table
    }

    public function point()
    {
        return $this->belongsTo(IkuPoint::class, 'point_id');
    }
}
