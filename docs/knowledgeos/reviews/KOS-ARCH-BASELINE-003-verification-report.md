# Verification Report
## KOS-ARCH-BASELINE-003 — BC-7 Governed Session Orchestration Domain Model Proposal

**Assignment:** `S1-verification-bc7-domain-model` · role **verification** · ACTIVE, mutation owner (record seq 6) · grants `G-KOS-ARCHBASE3-VERIFY` + `AMD1`
**Producer:** `S4-architecture-bc7-domain-model` (HANDED_OFF, seq 5) · **2026-08-17**

> ## RECOMMENDATION — **VERIFIED WITH NOTES**
> The tactical model is **sufficiently justified by evidence** and **consistent with the accepted strategic model**. Twelve load-bearing claims were re-derived from source and records; **eleven confirmed, one falsified** (F-1). The falsified claim is a *supporting* argument, not the aggregate decision — and correcting it **strengthens** the conclusion it was offered against. Three concerns and two observations follow. **No redesign performed. Acceptance is the PO/ARB's act.**

---

## 0 · Eligibility disclosure (`R-34` / `P-2`, stated first)

**What this process can evidence:** it produced no BC-7 domain model, no `ADR-AIP-03`, and no `KOS-ARCH-BASELINE-002/003` artifact. Those inputs were read for the first time in this assignment.
**What it cannot evidence:** that it is not the same process that ran `S4-architecture-bc7-domain-model`. Per `INV-ATTR-2` the record cannot attribute execution to a process; separation rests on where the PO/ARB started the session. **`Observed` for the first; `Unknown` for the second — recorded rather than glossed.**

---

## 1 · Scope

Verified: aggregate boundary justification · invariant ownership (T-1…T-4) · BC-7↔Governance and BC-7↔Knowledge-Engineering boundaries · ubiquitous language · domain events · policy separation.
**Not verified, by grant:** `ADR-AIP-03`, BC-7 recognition, CAP-14 ownership, Governance strategic ownership, `ADR-AIP-04` role ownership — all fixed inputs, not reopened.

## 2 · Evidence reviewed

`ADR-AIP-03` (signed Option A, verbatim decision record) · `KOS-ARCH-BASELINE-002` accepted landscape/context/capability maps · `KOS-ARCH-BASELINE-003` commission · the proposal (379 lines) · grants `G-KOS-ARCHBASE3-VERIFY` + `AMD1` · **primary re-derivation sources:** `.claude/scripts/workflow-state.php` (`foldSessions`, `assertTransitionAllowed`, the `authorized`/`identity`/`fold` commands) and **all 13 live workflow records / 121 transitions**.

## 3 · Findings

| ID | Observation | Evidence | Class |
|---|---|---|---|
| **F-1** | **The proposal's claim that `authorized` "never consults the session" is FALSE.** The command **requires** `--session` and **refuses on an unregistered session** before answering. It does not use the session *in the answer* (which is grant-scope equality alone) — but it does consult it as a **validity precondition**. | `workflow-state.php` `case 'authorized'`: `usage('authorized requires --session')`, then `refuse('unknown session')` | **Finding** |
| **C-1** | F-1 **undercuts §4.5's premise** — *"the mechanism never requires the two to be consistent with each other."* A grants-side query that refuses on a missing registry entry **is** a cross-part dependency. It is weak (existence, not consistency) but it is real, and §4.5 asserted its absence. | as F-1 | **Concern** |
| **C-2** | **T-1 is `Proposed` yet stated in absolute form** — *"no derived projection is **ever** stored **or accepted as input**."* The storage half is measured (0/13 records carry a derived key). **The "accepted as input" half is not demonstrated**: no probe is offered showing the mechanism *refuses* a submitted `mutationOwner`/`state` key. Absence in practice ≠ refusal by contract. | records key-census (below); no refusal path found in source for unknown transition keys | **Concern** |
| **C-3** | **I-5's own row records a live counter-example and does not resolve it.** The proposal notes a measured `recordedBy: "architecture"` — a non-governance, non-human writer "in the wild." That is a preserved invariant with an observed violation instance, carried as a note rather than as a finding. **Correct to disclose; under-weighted in classification.** | proposal §7 I-5 row; the instance is in the `KOS-ARCH-BASELINE-001` record | **Concern** |
| **O-1** | **`117` → `121` transitions since the proposal** (my own seq 6 among them). `CONTINUATION` and `FAIL` remain **0**, so **OQ-5's substance holds**; only the denominator drifted. Temporal, not error. | 121 transitions: REGISTER 34 · START 34 · HANDOFF 35 · COMPLETE 16 · STOP 1 · CANCEL 1 | Observation |
| **O-2** | **§4.5's honesty is itself notable.** The proposal states the evidence *against* its own choice ("the consistency boundary is wider than any positive invariant demands — an honest finding, and one that argues *for* splitting"), then keeps one root on stated grounds and records the alternative as a genuine rejected option. **This is the discipline the grant asked for**, and it is why F-1 does not overturn the decision. | proposal §4.5, §6 | Observation |

### 3.1 The eleven confirmed re-derivations

| Claim | Independent method | Result |
|---|---|---|
| `mutationOwner` is a fold projection, **never stored** | key-census across all 13 records | ✅ **0** records carry it; source refs are fold-output only (`:164`, `:349`) |
| `state` / `workItemState` never stored | same census | ✅ absent from every record; all keys are inputs, none derived |
| **START does not consult grants** | scan of the `START` case | ✅ **0** grant references |
| HANDOFF refuses a non-owner | source | ✅ `refuse('only the current mutation owner can hand off (Inv C)')` |
| Bootstrap handoff only while no owner | source | ✅ `refuse('bootstrap handoff (from=null) is only valid while no owner exists')` |
| HANDOFF is atomic across two assignments | fold: sets `from`→HANDED_OFF and `handoffsTo[to]` in one transition | ✅ confirms §4.2's decisive rejection of Assignment-as-root |
| `CONTINUATION` / `FAIL` unexercised | full transition census | ✅ 0 of 121 |
| `humanAct` non-empty on every START | record census | ✅ **34/34** |
| Session ids de-facto unique; **uniqueness not enforced globally** | REGISTER census + source | ✅ 34 ids, no duplicates — and no cross-record uniqueness check exists (OQ-4 stands) |
| **T-2** — references never dereferenced | grep of all `token`/`tokenRef`/`humanAct`/`humanActRef` uses | ✅ **emptiness checks only** (`:209,210,232,325`); no parse, no fetch |
| **T-3** — append-only in meaning | `seq` monotonicity across all records + search for mutation paths | ✅ every record `1..n` contiguous; **0** `array_splice`/`array_pop`/`unset` on transitions |

## 4 · Aggregate assessment

> ### Is the `WorkItem` aggregate justification sufficiently supported? — **YES.**

The justification rests on the correct DDD test — *an invariant that spans several instances of a candidate proves the candidate is not the root* — and the test is applied with real evidence rather than asserted:

- **Candidate B (Assignment) fails on two independent grounds, both re-derived:** I-1 is an invariant over the *set* of assignments (unstatable from inside one), and **HANDOFF is atomic between two assignments** — with Assignment as root, every handoff becomes a two-aggregate transaction. Confirmed in source.
- **Candidate C (Workflow)** is a classifier with no lifecycle and no state — correctly reclassified as a Value Object. *Types are not aggregates* is the right reason.
- **Candidate D (Human Act)** is rejected on measurement (no identity, no lifecycle in BC-7) **and** on boundary (`ES-005.4` — promoting it would duplicate a concept another context owns, and drag authority semantics into a context that provably cannot evaluate authority). **This rejection is the strongest single piece of reasoning in the proposal.**
- **All four invariants of Candidate A are internal** — nothing outside a work item is read to enforce them. Independently confirmed.

**On RA-4 (WorkItem + separate tactical aggregate(s) inside BC-7):** the proposal engages it directly in §4.5, states the evidence favouring it, and declines it on three grounds — I-4 as a *negative* spanning invariant needing a holder that can see both parts; a single write boundary; and that splitting would **relocate** rather than solve the problem, replacing an owned invariant with an unowned coupling. **F-1 strengthens this position**: a real grants→registry dependency exists, so the two parts are less separable than §4.5 itself claimed. **The justification is sufficient. The insufficiency I was invited to find is not present** — and per the grant I state that without proposing any alternative model.

## 5 · Boundary assessment

### 5.1 BC-7 ↔ Governance — **correctly separated; no leakage found**

The decisive structural evidence, re-derived: **START does not consult grants at all.** So the lifecycle gate cannot read authority, and BC-7 cannot decide permission even accidentally. Complementarily, `authorized` answers by grant-scope equality and never uses the session in its verdict (F-1 refines *how* it touches the session, not *whether* it decides with it). BC-7 **records** lifecycle; it does not **decide** permission. `T-4` keeps attribution a *claim*, never an attestation — confirmed by the total absence of identity validation.

### 5.2 BC-7 ↔ Knowledge Engineering — **correctly separated; T-2 is the mechanism**

`T-2` (references never dereferenced) is the load-bearing separation, and it is **measured, not asserted**: every `tokenRef`, `humanActRef` and grant-scope use is an emptiness check. BC-7 therefore holds *occurrence and movement*; artifact identity, lineage and meaning stay with Knowledge Engineering. **No duplication found.** Relating rather than duplicating the accepted attribution model (§10.3) honours `ES-005.4`.

### 5.3 Ubiquitous language — **belongs to BC-7; one term flagged**

`WorkItem`, `SessionAssignment`, `Transition`, `MutationOwner`, `RecordedOccurrence` are BC-7-native. `Grant` and `HumanActReference` are **correctly modelled as references to concepts owned elsewhere** — the naming makes the boundary visible rather than hiding it. **Flagged:** `Grant` is the one term doing double duty across contexts; the model mitigates this by owning only its *registration*, but a reader could still mistake BC-7 for the grant's owner. Not a violation — a legibility risk.

### 5.4 Domain events and policies

Events are modelled as domain facts with producer/meaning/consumers, without messaging infrastructure — correct per the grant. **Policy separation holds:** §9 keeps domain policies (BC-7's own) apart from governance policies (preserved, never owned), and **no mechanism in the model grants, evaluates or blocks authority** — consistent with `R-37` and with the measured reality that no gate reads a grant.

## 6 · Recommendation

> ## **VERIFIED WITH NOTES**

Sufficiently justified, strategically consistent. **F-1 must be corrected** (a stated `Observed` claim is false as written); **C-1** should be repaired in the same pass since it depends on F-1; **C-2** should either demonstrate the refusal or soften T-1's "accepted as input" half to `Proposed`/partial; **C-3** should be reclassified from note to finding. **None of these changes the aggregate decision or any boundary conclusion.**

## 7 · Restrictions

**No redesign performed** — no replacement model, no alternative aggregate, no BC-7 restructuring; where I found a claim false I said so without proposing what should replace the reasoning. **No implementation authorization.** **No ADR changes** — `ADR-AIP-03`, BC-7 recognition, CAP-14, Governance ownership and `ADR-AIP-04` untouched. **No acceptance performed** — acceptance is the PO/ARB act that follows. **The proposal was not modified.** No implementation tasks created. This report does not close its own assignment.

---

**Traceability:** grants `G-KOS-ARCHBASE3-VERIFY` + `AMD1` · record seq 5 (HANDOFF `KOS-ARCH-BASELINE-003-bc7-domain-model-proposal.md`) · seq 6 (human START, verbatim) · `ADR-AIP-03` §7 signed Option A · `KOS-ARCH-BASELINE-002` accepted maps · `workflow-state.php` (`foldSessions:118-166`, `assertTransitionAllowed:174-278`, `authorized:375-391`, `identity:354-374`) · 13 records / 121 transitions · `ES-005.4` · `R-34` · `R-37` · `P-2` Class-B · `INV-ATTR-2` · `V-3` (resolver handoff blindness — `fold` used throughout, not the resolver).

---

> **Independent verification. Not acceptance. The PO/ARB decision follows.**
