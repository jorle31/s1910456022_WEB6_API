<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeslotAgreement extends Model
{
    use HasFactory;

    protected $fillable = ['accepted', 'timeslot_id', 'user_id'];

    public function timeslot() : BelongsTo {
        return $this->belongsTo(Timeslot::class, 'timeslot_id');
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }
}
