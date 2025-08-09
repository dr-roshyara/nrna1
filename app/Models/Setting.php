<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'description', 'type'];

    protected $casts = [
        'value' => 'string',
    ];

    /**
     * Get setting value with caching
     */
    public static function getValue(string $key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set setting value and clear cache
     */
    public static function setValue(string $key, $value, string $description = null): self
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'description' => $description ?? "Setting for {$key}",
                'type' => gettype($value)
            ]
        );

        Cache::forget("setting.{$key}");
        
        return $setting;
    }

    /**
     * Check if setting exists and is true
     */
    public static function isEnabled(string $key): bool
    {
        $value = self::getValue($key, false);
        return in_array(strtolower($value), ['true', '1', 'yes', 'on']);
    }

    /**
     * Toggle boolean setting
     */
    public static function toggle(string $key): bool
    {
        $current = self::isEnabled($key);
        $new = !$current;
        self::setValue($key, $new ? 'true' : 'false');
        return $new;
    }
}