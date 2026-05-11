<?php

declare(strict_types=1);

namespace App\Models;

use App\Contexts\Geography\Domain\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Region extends Model
{
    protected $fillable = ['code', 'name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(
            Country::class,
            'region_country',
            'region_id',
            'country_code',
            'id',
            'code'
        );
    }
}
