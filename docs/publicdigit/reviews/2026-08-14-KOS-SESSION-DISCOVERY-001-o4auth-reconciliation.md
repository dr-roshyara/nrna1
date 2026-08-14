# KOS-SESSION-DISCOVERY-001 — Governance Reconciliation of O-4/AUTH

**Type:** Governance reconciliation (Session 2) · **Date:** 2026-08-14 · **Trigger:** Session 1's independent authorization-mechanism finding (their corrected report), reconciled with the intake and Session 4's architecture proposal
**⛔ Nothing implemented · `workflow-state.php` untouched · no grant issued · no Session 5 · the Q-A approval gate remains BLOCKED on the standing provenance disposition (unstarted S4 assignment — separate, unresolved).**
**Naming note:** this finding is cited **O-4/AUTH** — distinct from the Inc-1 closure's O-4 (runtime-record observation). Conflation would corrupt both records.

---

## A · New evidence — ACCEPTED and independently confirmed

Session 2 re-ran the queries read-only: `authorized` for **`S4-architecture-2026-08-14-oq` (CANCELLED)** and **`S3-implementation-oq` (HANDED_OFF)**, each with S1's verbatim verification scope → **both `true`**. Source-confirmed (`workflow-state.php:383-390`): the grant loop never consults the session beyond registered-existence.

## B · Exact mechanism behavior demonstrated

`authorized == true` proves exactly: *the session is registered (any state) AND an AUTHORIZED grant with that exact scope string exists on the work item.* It proves **nothing** about session identity-to-grant linkage, role, ACTIVE state, or mutation ownership. **Two precisions that bound the blast radius:** ① this is a **read-query weakness only** — the **write path conjoins correctly** (writer-role refusal R5a, G-3 conjunction, owner-only handoff: all verified and OQ-exercised); the record cannot be *mutated* on O-4/AUTH's false positive. ② the verified contracts are **not violated** — R6 contracted "ACTIVE-with-no-covering-grant is a first-class false"; the reverse direction (non-ACTIVE with a covering grant) **was never contracted**. O-4/AUTH is a limitation outside the contracted surface — the command's *name* overpromises, its behavior does not breach R1–R8.

## C · Impact on KOS-SESSION-DISCOVERY-001 — a CONSTRAINT CONFIRMED, not a scope change

The work item's scope is unchanged. O-4/AUTH is **evidence for the boundary the proposal already drew**: the ResolutionReport surfaces grants **verbatim** and explicitly declines to answer *"is this grant for this session?"* and *"does scope S cover action A?"* (proposal §4 contract properties, §8, and its own L-2 disposition — the linkage inference is not reproduced). **The resolver must never relay `authorized: true` as act-permission** — already the design's stance, now with executable proof of why.

## D · Discovery vs authorization — SEPARATE BOUNDED CAPABILITIES, confirmed

**Session-Assignment Discovery** answers *which governed assignment this ExecutionHost may operate as* — facts: identity, role, folded state, ownership, grants verbatim. **Act Authorization** answers *whether an identified session may perform a particular act* — a conjunction (identity + role + ACTIVE + ownership + grant + scope) that today exists **only** as the D-4 startup convention's human judgment plus the write-path's refusals. Collapsing them would create the omnipotent-resolver anti-pattern; the PA presumption is adopted: **discovery SURFACES authorization information; it never REDEFINES authorization semantics.**

## E · O-4/AUTH classification — **B: SEPARATE FUTURE WORK ITEM**, with an interim acceptance and a standing resolver constraint

Not **A** (adjacency is not scope; absorbing it would make the resolver an authorization engine) · not **C** (the resolver does not depend on fixing it — it surfaces verbatim facts regardless) · not bare **D** (permanent acceptance would leave a misleadingly named query forever). **Registered:** *the act-authorization capability — including the `authorized` command's naming/semantics and the grant↔session/role linkage question (same family as L-2 and the grant-lifecycle gap) — is a future work-item candidate requiring its own intake, and until then O-4/AUTH is an accepted, documented limitation whose false positives cannot mutate the record (§B-①).*

## F · Must Session 4's proposal be revised? — **NO.**

The design already contains the O-4/AUTH-safe stance at §4/§8/§9-L-2. At most, the eventual human review may add one citation line (O-4/AUTH as the demonstrated justification). **No redesign, no revision cycle.**

## G · PO/ARB decisions required

**① (standing, unchanged):** the provenance disposition for the unstarted S4 assignment — Option ① (admissible-as-evidence, Inc-1 precedent) or Option ② (proper START + re-presentation) — **this still blocks the Q-A…Q-F review and any boundary approval.** **② (no act needed now):** the O-4/AUTH future work item awaits a commissioning act **only when you choose to open it** — registered as backlog candidate, nothing pending.

## H · Revised bounded work item? — scope UNCHANGED; the boundary gains one explicit constraint (facts-not-permission relay rule, already in the design) and one explicit exclusion (act-authorization semantics = the separate candidate).

## I · Next responsible session — none new; the chain is unchanged and waits at the human gate.

## J · Exact next human gate — **your provenance disposition (Option ①/②)**; then the Q-A…Q-F review completes, then your boundary approval, then the grant, then S3.

**Traceability:** Session 1's corrected report (O-4/AUTH) · Session 2's executable re-confirmation (this artifact §A) · `workflow-state.php:383-390` · proposal @ `cee1ee6b` §4/§8/§9 · R6 contract text · intake §B/§G (`15f4f484`) · the standing STOP (provenance gate) · E-13/O-2 (exact-string lineage) · E-15 · L-2.
