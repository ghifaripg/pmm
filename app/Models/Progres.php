<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progres extends Model
{
    protected $table = 'progres';
    public $timestamps = false;

    protected $fillable = [
        'iku_id',
        'user_id',
        'status',
        'need_discussion',
        'meeting_date',
        'notes',
        'created_at'
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship to IKU
    public function iku()
    {
        return $this->belongsTo(Iku::class, 'iku_id', 'iku_id');
    }
}
