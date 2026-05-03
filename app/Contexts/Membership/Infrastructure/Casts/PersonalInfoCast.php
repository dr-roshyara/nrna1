<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Casts;

use App\Contexts\Membership\Domain\ValueObjects\Email;
use App\Contexts\Membership\Domain\ValueObjects\PersonalInfo;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * PersonalInfoCast
 *
 * Custom Eloquent cast for PersonalInfo value object.
 * Converts between database JSON and PersonalInfo value object.
 */
class PersonalInfoCast implements CastsAttributes
{
    /**
     * Cast the given value to PersonalInfo value object
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return PersonalInfo|null
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?PersonalInfo
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            $data = is_string($value)
                ? json_decode($value, true, 512, JSON_THROW_ON_ERROR)
                : $value;

            if (!is_array($data) || !isset($data['full_name'], $data['email'])) {
                // Log warning but return null to avoid breaking
                Log::warning('Invalid PersonalInfo data in database', [
                    'key' => $key,
                    'value' => $value,
                    'model' => get_class($model),
                    'model_id' => $model->getKey(),
                ]);
                return null;
            }

            return new PersonalInfo(
                fullName: $data['full_name'],
                email: new Email($data['email']),
                phone: $data['phone'] ?? null
            );
        } catch (\JsonException $e) {
            Log::error('Failed to decode PersonalInfo JSON', [
                'key' => $key,
                'value' => $value,
                'error' => $e->getMessage(),
                'model' => get_class($model),
                'model_id' => $model->getKey(),
            ]);
            return null;
        } catch (\InvalidArgumentException $e) {
            Log::error('Invalid PersonalInfo value object construction', [
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
     * @param  PersonalInfo|null  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof PersonalInfo) {
            throw new \InvalidArgumentException(
                'The given value is not a PersonalInfo instance'
            );
        }

        return json_encode($value->toArray());
    }
}
