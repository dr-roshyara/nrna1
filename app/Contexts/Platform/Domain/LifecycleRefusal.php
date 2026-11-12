<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Domain;

/** Why a ServedOrganisation refused a lifecycle step (D-185; D-193 §3.3, F-02, F-07; delta §3.7 L-1). */
enum LifecycleRefusal: string
{
    case NotPending = 'not-pending';
    case NotProvisioning = 'not-provisioning';
    case CounterpartyNotVerified = 'counterparty-not-verified';
    case CounterpartyAlreadyVerified = 'counterparty-already-verified';
    case NoServiceOperationGrant = 'no-service-operation-grant';
    case GrantAlreadyRecorded = 'grant-already-recorded';
    case NoTechnicalOperationWhilePending = 'no-technical-operation-while-pending';
    case LocatorAlreadyAssigned = 'locator-already-assigned';
    case NoLocator = 'no-locator';
    case SchemaNotCurrent = 'schema-not-current';
    case NotReady = 'not-ready';
}
