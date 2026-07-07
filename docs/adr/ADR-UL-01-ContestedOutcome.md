# ADR-UL-01 — Ubiquitous Language Evolution: `ContestedOutcome` (from `TargetRef`)

**Class:** ADR-UL (Ubiquitous Language) · **Status:** Accepted (ARB-ratified 2026-07-08) · **Context:** Contestation (+ consumed by Adjudication, Election).
**Drives:** ADR-PL-01 (published-language evolution) — per **ER-06** (Ubiquitous Language before Published Language).
**Supersedes in-IDD discovery:** PB-004 DD-4/DD-4a/DD-4b (now closed; the concept + glossary live here as permanent architecture knowledge).

## Context
Contestation's `Challenge` held `TargetRef: string` — *"a reference to an election outcome or a prior determination; reference only, carries no vote content."* An opaque string = **Primitive Obsession** hiding a business concept. PB-004 (Election Reaction) needed to know *which Election* a determination affects; investigation (ER-02) showed no materialized `electionId` anywhere, yet a Challenge is intrinsically scoped to exactly one Election. The gap was a **domain-model** gap, not a transport gap.

## Decision
Adopt the concept the certified vocabulary already names. **BDR v1.1 (immutable):** *"adjudicate a **contested outcome** → binding determination."* → the domain concept is **`ContestedOutcome`** (constitutional term, not invented).

- **`ContestedOutcome`** — an authoritative outcome of the constitutional process that a standing-holder may legally challenge within the appeal window. Today: an **Election Result** or a **prior Determination** (extensible via `TargetType`).
- **`ContestedOutcomeRef`** (Value Object; replaces `TargetRef:string`) — identifies the `ContestedOutcome` a Challenge contests: `{ ElectionId electionId · TargetType type(ElectionResult | Determination | …future) · TargetId targetId }`. Reference only; **no vote content** (anonymity). Named `…Ref` to match sibling VOs (`RaiserStandingRef`); domain-aligned over generic "Target" (only 7 code references — rename cost is negligible).
- **Invariant (now explicit):** a `Challenge` is scoped to **exactly one Election** (via its `ContestedOutcomeRef.electionId`); therefore so is its `Determination`.

## Glossary (Contestation ubiquitous language — authoritative)
- **Challenge** — a standing-holder's formal, time-bounded contest of a `ContestedOutcome`.
- **ContestedOutcome** — *(certified term, BDR v1.1)* an authoritative outcome of the constitutional process that may be legally challenged (today: Election Result | prior Determination).
- **Election Result** — the certified outcome of an election (a `ContestedOutcome` owned by Voting/Election).
- **Determination** — a binding ruling issued by Adjudication on a Challenge (itself a `ContestedOutcome` when challenged).
- **ContestedOutcomeRef** — the VO identifying the `ContestedOutcome` a Challenge contests: `ElectionId · TargetType · TargetId`; reference only, no vote content.
- **ElectionId** — identity of the single Election a `ContestedOutcome` belongs to.
- **TargetType** — which kind of `ContestedOutcome` (`ElectionResult | Determination | …future`).

## Consequences
- Contestation refactors `TargetRef:string → ContestedOutcomeRef` VO (`Challenge`, `ChallengeRaised`, tests — 4 files).
- `electionId` becomes first-class domain data via the VO — read from the ubiquitous language, never "requested by Election."
- Anonymity preserved: `type`/`targetId` are Result/Determination references only (the constitutional guard forbids voter tokens).
- Enables ADR-PL-01 (`DeterminationIssued v2` carries `ContestedOutcomeRef`), then PB-004.

## Alternatives rejected
- Add `electionId` to the event (transport-first) — treats the symptom, not the concept.
- Invented name `ContestableDecision` — not grounded in certified vocabulary (violates "the domain names the software").
- Read-model lookup as primary — introduces eventual consistency where a domain fact already exists.

**Traceability:** BDR v1.1 · Round50 · ER-02 · ER-06 · ADR-T5/T16 · PB-004 IDD (DD-4 closed).
