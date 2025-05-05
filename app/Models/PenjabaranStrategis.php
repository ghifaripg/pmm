<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjabaranStrategis extends Model
{
    protected $table = 'penjabaran_strategis';

    protected $fillable = [
        'form_id',
        'proses_bisnis',
        'strategis',
        'pic',
    ];

    public $timestamps = false;

    public function formKontrak()
    {
        return $this->belongsTo(FormKontrakManajemen::class, 'form_id');
    }
}
