# Handbook Volume 1 — Executive Program Overview

**Program:** NRNA DDD Trustworthiness Research Program
**Audience:** a senior architect understanding the project in one sitting.
**Status:** canonical. See Volume 0 for the index; the `ddd-program-state` memory for live state.
**Date:** 2026-06-25

---

## Vision

Enable a large, cross-border **diaspora organisation** (NRNA — a voluntary, transnational membership body, **not** a sovereign state) to run elections that are **secure, anonymous, verifiable, and *constitutionally trustworthy over decades***, without the courts, free press, and rival institutions that mature states rely on.

## Mission

Translate **constitutional intent** into **software** through a disciplined, auditable, multi-layer pipeline that never collapses legal design, governance design, and implementation into one another.

## Why the project exists / the problem it solves

Online elections for a diaspora body face a problem deeper than cryptography: **"who watches the watchmen?"** When one sovereign body (the Membership Assembly) sets the rules, runs elections, certifies them, and appoints the bodies that check them, the architecture lacks an independent vantage point — and a *young* organisation has none of the external correctives (courts, press, parties) that contain this risk elsewhere. The program designs the **governance architecture** that makes such elections trustworthy despite that.

## Expected scientific contributions

1. **A constitutional-architecture metamodel** — a stable governance layer separating constitutional reasoning from software design (Volume 0 §1; the program's most durable contribution).
2. **GRP (Governance Recursion Point)** — single-source architectures contain ineliminable self-reference points, *surrounded and observed, not closed.*
3. **Composite governance architecture** — the unit of governance design is the *composite* (a coordinated selection across orthogonal mechanism dimensions); target properties like capture-resistance are **emergent**, not additive.
4. **A predictive, falsifiable research methodology** for governance design (Observation → Prediction → Falsifier → Evidence; lifecycle; saturation).

## Expected software contributions (future, gated)

A multi-tenant, anonymous, verifiable online election platform whose governance bodies (appointment, oversight, challenge, finality, jurisdiction) are realized from the validated governance architecture — built via Strategic then Tactical DDD, hexagonal/clean architecture, TDD-first. **Not started; deliberately gated.**

## Current maturity

- **Constitutional layer: COMPLETE.** 38C-15 ruled **Option B (Functional Independence) + permanent safeguards S-1..S-5**, as a *governance value judgment* (not evidence-compelled; external-expert validation n=0). Option A deselected-not-refuted. Meta-CVI managed, not eliminated.
- **Capability layer (Pass 1): COMPLETE + validated.** 5 safeguards → 33 capabilities → 5 emergent families; EGCP working lifecycle model; GRP-01 confirmed as the deeper invariant; SYN-03 validated coherence.
- **Mechanism layer (Pass 2): IN PROGRESS.** Methodology mature & frozen (P2-00/17/18). **F-OBS** and **F-AUTH** complete; **F-PROC/F-THR/F-REV** remain. Composite-architecture principle is an *emerging* (provisional) finding pending the remaining families.
- **DDD layer: NOT STARTED (gated).**

## Remaining work

1. **Pass 2:** F-PROC (next, hostile test) → F-THR → F-REV (hub, last — resolves the appointment/removal recursion).
2. **Pass-2 cross-family synthesis** (P2-SYN-FINAL): convergence matrix, universal patterns/exclusions, methodology saturation; decide whether the composite principle and a "Governance Pattern" layer graduate.
3. **Shared-capability consolidation**, then the **Strategic DDD** gate opens.
4. Strategic DDD → Tactical DDD → implementation → verification.

## Success criteria

- Every governance design **traceable** to a constitutional property and the 38C-15 ruling.
- All provisional findings either **graduated with evidence** or explicitly **narrowed/refuted** — none silently assumed.
- The DDD model derived from a **validated capability + mechanism architecture**, not from intuition.
- The audit trail (sealed hypotheses, errata, commits, rejected alternatives) **survives external review**: a reviewer in five years can reconstruct *what was decided, why, on what evidence, and what would have changed it.*
- Anonymity and the other invariants (Volume 0 §5) **never violated**.

## High-level roadmap

```
[DONE] Constitution → Ruling (Option B + S-1..S-5)
[DONE] Capabilities → Families → Validation
[DONE] Mechanism methodology (frozen) → F-OBS → F-AUTH → methodology validation
[NOW ] F-PROC (hostile) → F-THR → F-REV
[NEXT] Pass-2 synthesis → shared-capability consolidation
[GATE] Strategic DDD → Context Mapping → Tactical DDD
[FUT ] Hexagonal/Clean implementation → TDD → verification
```

## The one-paragraph summary

NRNA is building trustworthy online diaspora elections. A constitutional ruling chose **Functional Independence with permanent safeguards**, accepting a recorded residual risk (Meta-CVI, *managed not eliminated*) rather than restructuring into multiple sovereigns. Those safeguards were decomposed into **capabilities**, organized into **families**, validated as a coherent architecture, and are now being realized as **composite governance mechanisms** under a frozen, falsifiable methodology. **No software has been designed yet** — by deliberate discipline, the governance architecture must be complete and validated first. The program's lasting output may be less the platform than the **method**: a traceable pipeline from constitutional intent to software that refuses to skip layers.

---

*Handbook Volume 1 — Executive Program Overview — ISSUED*
*Next: Volume 6 (Research Methodology), then the constitutional/capability/mechanism volumes. Strategic DDD GATED.*
