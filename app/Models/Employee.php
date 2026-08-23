<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'personal_email',
        'phone',
        'passcode_id',
    ];

    public function passcode(): BelongsTo
    {
        return $this->belongsTo(Passcode::class);
    }
}
