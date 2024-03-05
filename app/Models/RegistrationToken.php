<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationToken extends Model
{
    use HasFactory;

    protected $fillable = ['proposed_username', 'value', 'expired_time'];
    public function user(): belongsTo
    {
       return $this->belongsTo(User::class);
    }
}
