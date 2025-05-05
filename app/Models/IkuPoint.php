<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IkuPoint extends Model
{
    protected $table = 'iku_point';

    protected $fillable = [
        'form_iku_id',
        'point_name',
        'base',
        'stretch',
        'satuan',
        'polaritas',
        'bobot',
    ];

    public function formIku()
    {
        return $this->belongsTo(FormIku::class, 'form_iku_id');
    }
}
