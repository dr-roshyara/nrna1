<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Casts;

use App\Contexts\Membership\Domain\ValueObjects\MemberStatus;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * MemberStatusCast
 *
 * Custom Eloquent cast for MemberStatus value object.
 * Converts between database string and MemberStatus value object.
 */
class MemberStatusCast implements CastsAttributes
{
    /**
     * Cast the given value to MemberStatus value object
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return MemberStatus|null
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?MemberStatus
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return MemberStatus::fromString((string) $value);
        } catch (\InvalidArgumentException $e) {
            Log::error('Invalid MemberStatus value in database', [
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
     * @param  MemberStatus|null  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof MemberStatus) {
            throw new \InvalidArgumentException(
                'The given value is not a MemberStatus instance'
            );
        }

        return $value->value();
    }
}
