<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\User;
use App\Traits\BelongsToTenant;

class Image extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'organisation_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class)->withoutGlobalScopes();
    }
}
