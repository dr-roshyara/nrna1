<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Condition;

/**
 * The gate-interval classification — DERIVED on recorded facts, never stored as
 * authoritative state (EM-GOV-068; EM-ARCH-001 P-2, DD-1: storing it would create
 * the determiner the rule excludes).
 *
 *  Open           — reached, undecided, threshold mathematically achievable on
 *                   recorded facts; in progress; NO recovery period runs; unbounded
 *                   in time by construction (EM-OPEN-053, PO-classified non-blocking)
 *  DecidedPass    — accepts ≥ required
 *  DecidedFailure — satisfaction impossible BY DECISION (at 3/2: two objections — R-F2)
 *  Unachievable   — mathematical impossibility on recorded facts (vacancy arithmetic),
 *                   which is never a decision and never a decided failure
 *
 * This set is CLOSED: no "unsatisfiable-in-fact" classification exists and no actor
 * can convert waiting into impossibility (D-5; EM-OPEN-109, blocked on new policy).
 * OPEN is not HALTED, not a timeout, and has nothing to hang one on (G-3; D-4).
 */
enum GateIntervalState: string
{
    case Open = 'open';
    case DecidedPass = 'decided_pass';
    case DecidedFailure = 'decided_failure';
    case Unachievable = 'unachievable';
}
