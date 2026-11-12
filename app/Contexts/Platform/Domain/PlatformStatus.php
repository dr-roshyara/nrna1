<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Domain;

/**
 * Platform status of a served organisation: operational, NOT the organisation's constitutional life (D-193 §3.2).
 * Release 1 values only (D-185; D-193 F-07). `suspended` and `closed` are a later release; their business
 * triggers are not decided (D-193 §3.2), so they are deliberately absent.
 */
enum PlatformStatus: string
{
    /** Application and founding evidence recorded; no technical operation (D-185). */
    case Pending = 'pending';
    /** Counterparty verified and service-operation grant recorded: database creation, migration, founding records. */
    case Provisioning = 'provisioning';
    /** May be routed, while the schema is current. */
    case Operational = 'operational';
}
