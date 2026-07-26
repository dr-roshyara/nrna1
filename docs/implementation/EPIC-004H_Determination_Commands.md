# EPIC-004H Determination Commands

**Kind:** Tactical DDD artifact №7 (one artifact → ARB review → refine → freeze → next). **Role:** implementation architect from the frozen baseline.
**Entry condition (ARB, binding):** *every proposed Command must represent a business intention being expressed toward the aggregate, tracing through the derivation chain (ADP). If removing the Command would not weaken the expression of a legitimate business intention, it does not become a Command.*
**Litmus (ARB, binding):** a command answers *"what business intention is being expressed toward the aggregate?"* — never *"which method exists?"* Direction: business occurrence → business intention → command; **never** public method → command.
**The honest shape of this artifact:** the confirmed context carries exactly **one** implemented command — and, per ASP, the audit's other job is recording the deliberate command-silences with rationale, including one genuinely interesting find: **a dormant implemented mechanism (`finalize()`) with no command, no caller, and no expressed intention behind it.**

---

## 1. Derivation: intentions expressible toward the ruling record

| Source (frozen chain) | Candidate intention | Litmus |
|---|---|---|
| Q-1 (the authority decides) + T-5/T-6 + INV-4 | "Issue the binding ruling I have decided" | **Yes** — the constitutional authority's directive toward the record |
| INV-1 (progression) | "Prepare a draft record" | **No** — mechanism, not intention (nobody in the business directs drafting) |
| INV-2 (finality) | "Close/finalize the record" | **Not today** — no one expresses it (see §3) |
| Declare-failure truth | "Declare the evidence insufficient" | **Yes, but** — the intention is expressed toward the *proceeding concern*, not this record (PM cluster) |
| TP-2 (Contestation requests, never creates) | "Request a determination" | **Yes, but** — a request *for* adjudication, not a command *toward* the record; the loop's head (PM cluster) |
| Policy 1 / forward-only | "Revoke / amend the ruling" | **Constitutionally forbidden as an intention toward this record** (see §3) |

## 2. Confirmed: the one command

### `IssueDeterminationCommand` — CONFIRMED, unchanged

- **Business intention:** the constitutional authority directs that the binding ruling it has decided be issued as the permanent record. The command carries the *decided* content (outcome, legitimacy, reason — decided upstream per K1/ADR-T17/Q-1) plus the full attribution and basis (authority, jurisdiction, evidence, challenge, contested outcome, occurred-at).
- **Traces (ADP):** Q-1 (authority decides; Adjudication records) → T-4/T-5/T-6 → INV-4/INV-5/INV-6 → the fixation the `DeterminationIssued` event embodies. Full lineage, no bypass.
- **Removal test:** the authority's intention to issue becomes inexpressible — the loop's only entrance to the record disappears.
- **Boundary note (recorded, not designed):** the command's handler seat (the issuance boundary) also discharges INV-B1 — consistent with the frozen R-3 finding.

## 3. Rejected and deferred — command silences, defended (ASP)

- **`PrepareDeterminationCommand` — REJECTED.** The public-method→command anti-pattern in its purest form: `prepare()` exists as a factory step inside the issuance flow, but no one in the business *directs* drafting — preparation is mechanism serving the issue intention, invisible to the domain. The absence of this command is now a decision on the record.
- **`FinalizeDeterminationCommand` — REJECTED today, with a paired reversal condition — and an honest finding, now put through the ARB's dormant-mechanism trichotomy.** `finalize()` is an implemented aggregate method with **no production caller and no command anywhere** (the EPIC-003 inventory found zero callers). Per the litmus, a method's existence cannot justify a command. **The trichotomy forced (per ARB — the methodology must make this question answerable, not decide it in advance):**
  1. *Implementation convenience to remain internal?* — the evidence argues no: `finalize()` was deliberately designed and test-pinned (Round 50-07 v1.2; `finalize_transitions_issued_to_final_without_event`), not incidental plumbing.
  2. *Dead code / obsolete capability?* — also no: INV-2 and T-1 make finality a **business concept** ("finality is absolute"); the state is constitutionally meaningful.
  3. *A missing business intention never explicitly modeled?* — **the evidence leans here.** The Final state exists for a business reason, but *who* closes the record, *when*, and *on what trigger* has never been modeled. **And there is a concrete candidate hiding in the open questions: Q-2.** A determination plausibly becomes final when the window to challenge *it* closes (TargetType includes Determination; the Evidence Preservation Window's adjudication-horizon arithmetic needs exactly this moment). If so, finality's trigger is the Contestation Window's closure — which would make Q-2's answer the missing intention's owner.
  **Recorded finding for the ARB:** the dormant mechanism is most plausibly a missing business intention whose definition is entangled with Q-2 — *answering Q-2 may simultaneously supply finalize()'s intention, the FinalizeDetermination command, and the DeterminationFinalized event.* Flagged, not decided. **Paired reversal condition stands:** the intention, the command, and the event return together through ARB review.
- **`RevokeDeterminationCommand` / `AmendDeterminationCommand` — REJECTED constitutionally.** The intention "change the ruling" is real in the business — but it is **never expressible toward the existing record**: forward-only (ADR-T8), absolute finality (INV-1/2), and Constitutional Policy 1 route that intention as a **new challenge → new determination → superseding publication**. The business changes outcomes by superseding, never by revising. This is the most important piece of negative knowledge in the artifact: the *absence* of a revocation command is a constitutional feature, on the record with its grounds.
- **`DeclareFailureCommand` — DEFERRED to the Emergent Design Cluster.** A genuine business intention ("I declare this evidence insufficient") — but expressed toward the *proceeding* concern, which the confirmed aggregate does not realize; its seat is the PM design. The cluster grows to four members: **FailureDeclared (event) + DeclareFailure (command) + EvidenceSet (VO) + the R-4-expanded seat** — one missing concept, four symptoms, opened together.
- **`RequestDeterminationCommand` — DEFERRED to the Emergent Design Cluster (the loop's head).** TP-2's "Contestation requests, never creates" implies a request-shaped interaction — but *how a routed challenge's request becomes an issuance* is precisely the orchestration the PM design owns. Recording it here would design the PM's inbound edge prematurely.

## 4. Open items carried

### Emergent Design Cluster (formal record, per ARB format)

> **Parent:** the Adjudication **Process Manager** (ruled realization of the proceeding concern; not yet designed)
> **Contains:** · `AdjudicationFailureDeclared` (event, deferred at №6) · `DeclareFailureCommand` (command, deferred at №7) · `EvidenceSet` / admission-set concept (VO, deferred at №5) · the **R-4-expanded fixation seat** (invariant seat, deferred at №3/№4) · the **loop-head request shape** (`RequestDetermination` — the PM's inbound edge, deferred at №7)
> **Reason:** all five become meaningful only when judgment orchestration is modeled — they are not independent unfinished questions but symptoms of the one missing design object. **They shall be opened together, as the PM design's opening agenda.**

- Additional cluster-adjacent finding (this artifact): the dormant `finalize()` trichotomy leans toward a missing intention **entangled with Q-2**, not with the PM — a second, distinct gravitational center (finality/window), recorded separately so the two are not conflated.
- The paired finality reversal condition (event + command travel together).
- Q-2 · Jurisdiction semantics — unchanged.

## Self-review

Exactly one command confirmed, with full ADP lineage and its removal test ✅ · every candidate faced the intention litmus; no candidate was derived from a method's existence — and the one place that temptation was strongest (`finalize()`, an implemented method with no caller) was named as a finding rather than converted into a command ✅ · ASP applied: every silence carries grounds; the revocation silence is recorded as a constitutional feature with its supersession route ✅ · the entry condition discriminated (three rejections, two deferrals) — Methodological Fitness Rule satisfied ✅ · the cluster's growth (3 → 4 members + the loop-head shape) is surfaced as evidence, not buried in TODOs ✅ · no code designed or modified; no handler, method, or API described beyond the recorded boundary note ✅.

**Stop condition: STOP.** Commands only. Repositories (artifact №8) and Domain Services (№9) begin on explicit ARB opening, after this artifact's review → refine → freeze.

---
*Frozen inputs: `EPIC-004G` (events; ASP/ADP adopted at its freeze) · `EPIC-004F` (VOs) · `EPIC-004E` (invariants) · `EPIC-004D` (truths) · `EPIC-004_Q1_Authority_Resolution.md` · ADR-T8/T17 · Constitutional Policy 1 · Implementation evidence: EPIC-003 inventory (IssueDeterminationCommand; finalize()'s zero callers).*
