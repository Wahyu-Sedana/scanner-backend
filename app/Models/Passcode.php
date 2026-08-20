<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Passcode extends Model
{
    protected $fillable = [
        'code',
        'label',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    public function scanHistories(): HasMany
    {
        return $this->hasMany(ScanHistory::class);
    }
}
