<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScanHistory extends Model
{
    protected $fillable = [
        'barcode',
        'format',
        'mode',
        'status',
        'reason',
        'passcode_id',
    ];

    public function passcode(): BelongsTo
    {
        return $this->belongsTo(Passcode::class);
    }
}
