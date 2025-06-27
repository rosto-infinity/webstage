<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'arrival_time',
        'departure_time',
        'late_minutes',
        'absent',
        'late',
    ];
   

protected $casts = [
    'late_minutes' => 'integer',
    'absent' => 'boolean',
    'late' => 'boolean'
];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
     public function absenceReason()
    {
        return $this->belongsTo(AbsenceReason::class);
    }
}
