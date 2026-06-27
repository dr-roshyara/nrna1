<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ReceiptCode extends Model
{
    use HasUuids;

    protected $fillable = ['election_id', 'receipt_code', 'reverified_at'];

    protected $casts = [
        'reverified_at' => 'datetime',
    ];

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function isReverified(): bool
    {
        return !is_null($this->reverified_at);
    }

    public function markAsReverified(): void
    {
        $this->update(['reverified_at' => now()]);
    }
}
