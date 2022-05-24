<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'subtitle', 'description', 'published', 'user_id', 'subject_id'];

    public function subject() : BelongsTo {
        return $this->belongsTo(Subject::class);
    }

    public function images() : HasMany {
        return $this->hasMany(Image::class);
    }

    public function comments() : HasMany {
        return $this->hasMany(Comment::class);
    }

    public function timeslots() : HasMany {
        return $this->hasMany(Timeslot::class);
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

}
