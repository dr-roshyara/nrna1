# Registration — PHP L4/L5 bounded exception · O-EKS-1 · corrected Track-1 authorization

**Registered by:** Governance, on the delivered PO/ARB act · 2026-08-18

## 1 · Why this correction exists — the governance loop closed

When Track 1 was authorized, its pipeline placed the existing Python capability at L4/L5. Governance recorded two things: that this **superseded** AMD1's sole-language clause, and that the reuse premise was **untested** — *"map the existing Python scripts and identify the exact L3 input contract they require; report the fit, do not assume it."*

**The implementer did exactly that and the premise failed** (`440fe7b8`): ***the existing Python capability cannot consume the accepted L3 model*** — and it **stopped before writing code.** This act is the correction that follows.

*Recorded because the sequence is the point: the untested premise was flagged, tested, and disproved before any code existed.*

## 2 · PHP L4/L5 bounded implementation exception · **REGISTERED**

For the current PHP-only implementation: **PHP binding in PHP · L3 produced by the PHP binding · L4 cohesion semantics in PHP · L5 LCOM4 in PHP · no PHP→Python transport required.**

> **The exception changes implementation topology only. It does NOT change the semantic architecture.**

**Unchanged and binding:** L3 remains **the conformance boundary** · L4/L5 remain **language-neutral in semantic responsibility** · **L4/L5 may not inspect PHP source text, spelling, tokens, AST nodes, parser-specific objects, or provenance** · provenance stays separate from semantic facts · **declared specification / expected evidence remains normative.**

### ⭐ The distinction this turns on

**Language-neutral in *semantic responsibility* is not the same as *implemented in a neutral language*.** A layer written in PHP that **cannot reach any PHP-specific input** is neutral in the sense the architecture requires — and the design's own pre-delivery gate is the test, which now binds the implementation, not just the drawing.

**The exception does NOT:** add Java · add Python as a current implementation language · create a transport contract · authorize retirement of legacy paths · **establish PHP as the definition of the language-neutral model.**

## 3 · The earlier supersession is **WITHDRAWN**

Governance recorded that the original Track-1 grant narrowed AMD1. **This correction restores AMD1 in full** — PHP is again the sole implementation language for the current implementation, and the common-engine direction returns to **FUTURE ONLY**. The accepted design's **§11.1** (`connectedComponents` moves up to L5 on the PHP side) and **§11.2** (`lcom4_collector.py` out of current scope) are **reinstated, not amended** — matching Architecture's own reconciliation (`70fc25c6`).

## 4 · O-EKS-1 — neutral observation vocabulary · **REGISTERED AS AN OBSERVATION ONLY**

**Verified:** `scripts/observations/ObservationRuntime.php:48` reads `'subject' => $obs['class']`.

**The observation:** the ACL placement is **architecturally correct** — mapping at the boundary is right — but the upstream field name `class` is **now semantically too narrow**, because the analysed-unit model includes **class · enum · trait · anonymous class**. A field named `class` carrying an enum is a name that has quietly outlived its meaning.

⛔ **Not modified in this act.** ⛔ **The L3 model and the implementation architecture are NOT changed because of this observation.** **Routed to a future governed artifact-update task**, as an artifact-update / Knowledge Engineering concern.

## 5 · Corrected Track-1 authorization · **ISSUED**

**Track 1 = PHP Adapter + PHP L3 + PHP L4/L5 LCOM4.** No Python analytical implementation is current scope. Registered as `G-KOS-CONTRACT-IMPL-TRACK1-AMD1`, replacing the original grant's pipeline; **all other Track-1 terms stand** — one PHP file per analysis, the accepted L3 model, qualifier kind, determinability, OQ-1 identity, the decided semantics, the prohibitions, and independent verification as a separate actor.

**The lane is ACTIVE and already started**, so the correction lands mid-assignment — but **before any code**, because the implementer stopped at the fit assessment.

## 6 · ⭐ Conformance guard · **STANDING**

> **Do not permit the implementation to generate its own expected evidence and then use that output as the specification oracle.**

Declared expected evidence remains **the normative authority**. Any expected-evidence creation or update requires the **separately governed artifact-update authorization** (`G-KOS-CONTRACT-ARTIFACT-UPDATE`), which remains **unexercised**.

*This closes the last route by which an implementation could become its own specification — the failure mode this work item has been guarding against since the first FAIL verdict.*

**Traceability:** the PO/ARB act 2026-08-18 · fit assessment `440fe7b8` · Architecture reconciliation `70fc25c6` · `G-KOS-CONTRACT-IMPL-TRACK1` + AMD1 · `IMPL-ARCH-AMD1` (restored) · accepted design §11.1/§11.2/§21 · `ObservationRuntime.php:48` · `G-KOS-CONTRACT-ARTIFACT-UPDATE`
