<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointsLedger extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    protected $table = 'points_ledger';
    protected $keyType = 'string';
    public $incrementing = false;

    // The ledger is immutable — no updates, no deletes
    protected $fillable = [
        'organisation_id',
        'user_id',
        'contribution_id',
        'points',
        'action',
        'reason',
        'created_by',
    ];

    protected $casts = [
        'points' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contribution()
    {
        return $this->belongsTo(Contribution::class);
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }
}
