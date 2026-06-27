<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Casts;

use App\Contexts\Membership\Domain\ValueObjects\MemberId;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * MemberIdCast
 *
 * Custom Eloquent cast for MemberId value object.
 * Converts between database string and MemberId value object.
 */
class MemberIdCast implements CastsAttributes
{
    /**
     * Cast the given value to MemberId value object
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return MemberId|null
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?MemberId
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return new MemberId((string) $value);
        } catch (\InvalidArgumentException $e) {
            Log::error('Invalid MemberId value in database', [
                'key' => $key,
                'value' => $value,
                'error' => $e->getMessage(),
                'model' => get_class($model),
                'model_id' => $model->getKey(),
            ]);
            return null;
        }
    }

    /**
     * Prepare the given value for storage
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  MemberId|null  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof MemberId) {
            throw new \InvalidArgumentException(
                'The given value is not a MemberId instance'
            );
        }

        return $value->value();
    }
}
