<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Leave extends Model
{
    use HasFactory;

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
