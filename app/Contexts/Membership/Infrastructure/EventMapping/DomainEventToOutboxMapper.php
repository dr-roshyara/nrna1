<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\EventMapping;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use ReflectionObject;

final class DomainEventToOutboxMapper
{
    public static function toOutboxPayload(object $domainEvent, TenantId $tenantId): array
    {
        return [
            'event_type'     => class_basename($domainEvent),
            'aggregate_type' => self::aggregateType($domainEvent),
            'aggregate_id'   => self::aggregateId($domainEvent),
            'tenant_id'      => $tenantId->value(),
            'payload'        => self::serialize($domainEvent),
        ];
    }

    private static function aggregateType(object $event): string
    {
        return match(class_basename($event)) {
            'MemberRegistered', 'MemberActivated', 'MemberSuspended', 'MemberArchived' => 'Member',
            default => 'Unknown',
        };
    }

    private static function aggregateId(object $event): string
    {
        if (method_exists($event, 'getMemberId')) {
            return $event->getMemberId()->value();
        }
        return 'unknown';
    }

    private static function serialize(object $event): array
    {
        $data = [];
        foreach ((new ReflectionObject($event))->getProperties() as $prop) {
            $prop->setAccessible(true);
            $value = $prop->getValue($event);
            if ($value !== null && method_exists($value, 'value')) {
                $value = $value->value();
            }
            $data[$prop->getName()] = $value;
        }
        return $data;
    }
}
