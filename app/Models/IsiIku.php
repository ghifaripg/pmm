<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IsiIku extends Model
{
    protected $table = 'isi_iku';

    protected $fillable = [
        'iku',
        'proker',
        'pj',
    ];

    public function forms()
    {
        return $this->hasMany(FormIku::class, 'isi_iku_id');
    }
}
