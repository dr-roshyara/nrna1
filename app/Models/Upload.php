<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\User;
use App\Traits\BelongsToTenant;

class Upload extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'organisation_id',
        'filename',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withoutGlobalScopes();
    }
}
