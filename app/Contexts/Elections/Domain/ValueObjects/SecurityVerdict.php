<?php

namespace App\Contexts\Elections\Domain\ValueObjects;

/**
 * SecurityVerdict — the outcome of the ONE security authority for a cast attempt, as a fact.
 *
 * The verdict is produced by the existing Trust → Legitimacy chain (ADR_20261005_0950
 * §3.0: VotingSecurityDecision is a role, not a new engine); the caller maps its
 * LegitimacyOutcome onto these values. No sub-reason is carried — the detail stays
 * in that chain's own logging (decision record E2-D4, option A) — with ONE narrow exception:
 * IpBindingDenied (PBDIGIT-247, PO 2026-10-06), so a voter refused by IP Binding is told why.
 * It is a denial like Denied; no other reason is exposed.
 */
enum SecurityVerdict: string
{
    case Allowed         = 'allowed';
    case Denied          = 'denied';
    case ReviewRequired  = 'review_required';   // Deferred / Investigate — denied (Q2)
    case IpBindingDenied = 'ip_binding_denied'; // PBDIGIT-247 — request IP is not the voter's captured IP
}
