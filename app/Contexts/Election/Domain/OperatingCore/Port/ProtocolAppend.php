<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Port;

/**
 * Driven port: the Election Protocol — the permanent record of every material
 * event, sitting OUTSIDE the progression chain (EM-GOV-005; EM-VOC-008; B-7).
 *
 * The CONTRACT carries the F-PROTO-1 properties (the storage mechanism is
 * deliberately NOT prescribed — G-6):
 *  1 durable · 2 append-only in meaning (this interface exposes nothing that
 *  erases) · 3 complete for the required material events · 4 opportunity-bound ·
 *  5 resistant to silent truncation · 6 able to record a refusal before, or
 *  independently of, any state-transition success · 7 able to distinguish
 *  lifecycle events from progression-decision events (P-2H).
 *
 * Aggregate state is always reconstructable FROM recorded facts, never richer
 * than them (P-2H; B-7). Choosing a concrete store is an implementation decision
 * gated behind its own authorization — no adapter exists in this increment.
 */
interface ProtocolAppend
{
    public function append(ProtocolEntry $entry): void;
}
