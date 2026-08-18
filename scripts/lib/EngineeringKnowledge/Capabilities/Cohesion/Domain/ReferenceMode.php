<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/** L3 — invocation versus a first-class callable reference. CLOSED VOCABULARY. */
enum ReferenceMode
{
    case Invocation;
    case CallableReference;
}
