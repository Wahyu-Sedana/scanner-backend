<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Passcode extends Model
{
    protected $fillable = [
        'code',
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

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }
}
