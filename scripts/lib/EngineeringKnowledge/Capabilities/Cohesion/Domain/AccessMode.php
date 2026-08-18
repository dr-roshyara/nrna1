<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/**
 * L3 — direct versus nullsafe access. CLOSED VOCABULARY.
 *
 * 13.5 rules nullsafe a behavioural edge, so both values behave identically in
 * the rule table. It is carried because the RULING MUST BE ASSERTABLE in
 * declared evidence — and because differential comparison can never surface it:
 * two implementations sharing the blindness agree while both violate the rule.
 */
enum AccessMode
{
    case Direct;
    case Nullsafe;
}
