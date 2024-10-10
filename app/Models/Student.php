<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function department():BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function level():BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function assessments():HasMany
    {
        return $this->hasMany(Assessment::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->last_name} {$this->first_name}";
    }
}
