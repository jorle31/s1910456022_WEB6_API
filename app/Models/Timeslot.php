<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Timeslot extends Model
{
    use HasFactory;

    protected $fillable = ['from', 'until', 'date', 'status', 'is_booked', 'service_id'];

    public function service() : BelongsTo {
        return $this->belongsTo(Service::class);
    }

    public function timeslotAgreement() : HasOne {
        return $this->hasOne(TimeslotAgreement::class);
    }
}
