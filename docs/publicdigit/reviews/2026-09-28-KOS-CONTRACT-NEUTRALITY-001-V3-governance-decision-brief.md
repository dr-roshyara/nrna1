# `KOS-CONTRACT-NEUTRALITY-001` — V-3 governance decision brief: D-4/D-5 vs. D-1 sequencing

**Work item:** `KOS-CONTRACT-NEUTRALITY-001` · **Date:** 2026-09-28
**Assignment:** lane `S5-architecture-pass1-evidence-reconciliation`, decision-preparation
pass (no new grant — nothing tracked is modified)
**Performer, self-declared, not attestable (`INV-ATTR-2`/`G-2`):**
`claude-code-session:e8f324f1-25ea-4281-a95a-483401312e3e`
**Follows:** `2026-09-28-...-V3-decision-gate-reconciliation.md`. Per explicit direction: this
brief is **neutral** — it does not recommend Option A or Option B, and it does not select a
Model A/B/C representation for `D-1`.

> ⛔ **Non-authoritative preparation only.** No `expected.json` change · no code change · no
> test change · no governance/workflow transition · no PO/ARB submission sent by this pass —
> this document is *prepared for* that submission, not a submission itself. Verified before
> and after: `git status --short scripts/ tests/ .claude/runtime` shows no change.

---

## 1 · Current V-3 decision state

| Decision | Current status | What is already settled | What remains |
|---|---|---|---|
| `D-1` | **Adopted** (representation vocabulary), **not incorporated** into `expected.json`, **not implemented** | Semantic disposition (in scope, §0 of the 2026-09-04 proposal); the proposed fact kind `IndeterminateBehaviourReference` and its L4 consequence, adopted 2026-09-04 | Contract-text incorporation; the exclusion-evidence-record representation (A/B/C, still unselected); implementation; verification; acceptance |
| `D-4` | **Adopted** (enumerated scope), **not incorporated**, **not implemented** | The closed list (`call_user_func`, `call_user_func_array`, literal `[$this,'name']` only) and its out-of-scope boundary, adopted 2026-09-04 | Contract-text incorporation; implementation; verification; acceptance — **no representation question**, per §2 below |
| `D-5` | **Adopted** (vocabulary), **not incorporated**, **not implemented** | `QualifierKind::ExplicitCallableDispatch`, adopted 2026-09-04 | Same as `D-4` — incorporation, implementation, verification, acceptance |
| `INV-L3-5` scope over the exclusion-evidence record | **Derived this pass** (2026-09-28 reconciliation, §4) from existing canonical registration | Does not govern the exclusion-evidence record (see that document for the full deduction) — **not yet ratified by a human/PO-ARB act; proposed, not decided** | Human acceptance, or an explicit escalation if disputed |
| Model `A`/`B`/`C` (D-1's exclusion-evidence representation) | **Unresolved** | `A` is compatible with every registered invariant *if* the above derivation is accepted; `B` is dominated by `A`; `C` adds a real, new, cross-adapter `factId` obligation | Selection — an engineering design choice, not a compliance question (see §5) |
| `D-4`/`D-5` split from `D-1` | **Unresolved governance preference** | Technically and contractually permitted (2026-09-28 reconciliation §3) | The human/PO-ARB choice this brief exists to prepare |

**Language discipline, applied throughout this brief:** "adopted" means a vocabulary was
recorded as the architecture-of-record; it does not mean incorporated into `expected.json`,
and it does not mean implemented. No representation is called "accepted" — `D-1`'s fact kind
and `D-5`'s qualifier were *adopted as vocabulary*; the exclusion-evidence-record question
(A/B/C) was never part of that adoption and remains open regardless of it.

## 2 · Why `D-4`/`D-5` and `D-1` are treated separately in this brief

`D-4`/`D-5` never reach the exclusion-evidence-record question at all: their scope guarantees
a real, literal method name at every in-scope call site (`2026-09-04-...-proposal.md` §2 —
*"the target method name **is** a literal and representable today"*), so an in-scope dispatch
is recorded on the **existing** `BehaviourReference` type, unmodified. `D-1` is the only one
of the three that cannot be represented on the existing type at all (`proposal.md` §1.1: the
optional/absent-field and stated-limitation options were both rejected; only a new fact kind
remains admissible) — and that new fact kind is exactly where the exclusion-evidence-record
question (A/B/C) originates. This separation is not asserted here; it is read directly from
the already-adopted proposal text.

## 3 · Option A — independent incorporation

```
D-4/D-5
   |
contract incorporation  (a Governance act amending expected.json._variant_decisions_pinned)
   |
implementation design
   |
RED -> GREEN -> independent VERIFY -> ACCEPT

D-1
   |
representation decision (A/B/C)      <- proceeds on its own timeline, unblocked by D-4/D-5
   |
later incorporation
```

**What becomes unblocked:** dynamic-member expected evidence for the `D-4`/`D-5` construct
family specifically (per the standing gate language already in force, `2026-08-19-...-
decisions-registration.md` Amendment 1 §A1.2 — evidence production, once incorporated,
becomes permitted for that category; conformance assertion still separately requires
implementation and acceptance). `D-4`/`D-5` implementation, verification and acceptance can
proceed without waiting on `D-1`'s representation debate.

**What remains open:** `D-1` stays exactly where it is today — adopted vocabulary, no
incorporation, no implementation — until its own representation question is separately
resolved. `V-3` as a whole does not close under this option (closing `V-3` requires all
adopted decisions incorporated and implemented, per `2026-09-04-...-ADOPTED.md` §5).

**Contract amendment required:** one narrower amendment to `expected.json._variant_decisions_
pinned.intra_class_calls` (or an adjacent pinned key) covering only `D-4`/`D-5`'s enumerated
scope and vocabulary — smaller in surface than a combined amendment.

**Traceability consequence:** two separate Governance acts and two separate implementation/
verification/acceptance cycles on the historical record, rather than one — this repository's
existing pattern already has multiple `AMD` amendments on this single commission, so a second
incorporation act is not itself unusual (2026-09-27 incorporation brief, §3, Option 1 note).

## 4 · Option B — bundled incorporation

```
D-1 representation (A/B/C)
        +
D-4/D-5
        |
one contract amendment
        |
implementation
        |
RED -> GREEN -> VERIFY -> ACCEPT
```

**Why the bundle is simpler from a traceability perspective:** `D-1`/`D-4`/`D-5` were adopted
together, in one act (`2026-09-04-...-ADOPTED.md`), from one proposal; a single incorporation
amendment mirrors that history and produces one contract-text change to review, one
implementation slice, one verification pass, and one acceptance record — fewer governance
acts to track for the same eventual scope.

**What remains blocked until `D-1` is resolved:** under this option, `D-4`/`D-5` — which are
otherwise ready today, per §2 — wait on `D-1`'s representation question purely because they
were adopted in the same act, not because of any technical or semantic dependency between
them.

**Whether an actual semantic dependency requires the bundle — checked, not assumed:** none
found. The 2026-08-19 decisions-registration's own explicit non-splitting rule
(`R1.2`: *"D-2 and D-3 must not be split into contradictory decisions"*) is stated **only**
for the `D-2`↔`D-3` pair and is not extended, in that document or any later one, to
`D-1`↔`D-4`/`D-5`. No document establishes that incorporating `D-4`/`D-5` before `D-1` would
produce a contradictory or unstable contract state. The bundle's case rests on traceability
convenience, not on a demonstrated dependency.

## 5 · Evidence vs. preference, kept explicit

```
FACT:
D-4/D-5 always have literal target names within their adopted scope
(2026-09-04-...-proposal.md §2, §3.2 of the underlying determination).

FACT:
INV-L3-5's registered text names BehaviourReference's constructor specifically;
INV-L3-6's registered text places exclusion concepts outside L3 entirely
(2026-08-18-...-implementation-architecture.md §4.4).

DERIVED:
D-4/D-5 do not require the D-1 exclusion-evidence representation (Model A/B/C) to be
resolved before they can be incorporated.

DERIVED:
Model A does not violate any invariant registered in the canonical record, given the
INV-L3-5 scope finding above.

GOVERNANCE CHOICE:
Whether to incorporate D-4/D-5 independently of D-1 (Option A) or bundle them (Option B).
Evidence permits either; nothing compels one over the other.

GOVERNANCE CHOICE (secondary, logically prior):
Whether to accept the INV-L3-5 scope derivation above without a separate PO/ARB ruling on
it, or to escalate that derivation itself for ratification before relying on it.

ENGINEERING DESIGN CHOICE:
Whether D-1 eventually uses Model A, B, or C for its exclusion-evidence representation —
not decided by this brief, not decided by the reconciliation pass, and not compelled by
any invariant now that the INV-L3-5 question is resolved in A's favor.
```

## 6 · Model A/B/C — status, not a ranking

Restated from the 2026-09-28 reconciliation, without selecting one:

- **Model A** (`target: null`): legally compatible with the registered invariants under the
  `INV-L3-5` finding above; minimal change; requires a nullable `target` and consumers that
  tolerate `null` (`AnalyseCohesion::observe()`'s current unconditional read would need a
  null-check — confirmed by reading that file).
- **Model B** (omit the `target` key): also legally compatible under the same finding, but
  strictly weaker than A against the current consumer contract — it breaks
  `AnalyseCohesion::observe()`'s unconditional key read outright rather than merely requiring
  a null-check (confirmed, same file).
- **Model C** (`factId` reference): generalizes cleanly to future fact kinds and reuses an
  already-documented mechanism, but introduces a new, real, mandatory-and-unique cross-adapter
  obligation on `factId` for **every** `BehaviourReference`, and touches all six existing
  `ExclusionReason` emission paths if applied uniformly (`GraphBuilder.php`, confirmed by
  direct reading) — not merely the new `D-1` case.

**The governing statement, not a ranking:** the invariant question no longer selects the
representation. Selecting among A/B/C is now an explicit architectural design decision,
to be made on engineering merits (ergonomics, generality, consumer impact) once someone is
authorized to make it — not before.

## 7 · Next question after the governance decision (not executed now)

```
[if Option A chosen]                          [if Option B chosen]
D-4/D-5 contract incorporation                 D-1 representation (A/B/C) resolved first
        |                                               |
implementation design                          D-1 + D-4/D-5 combined contract incorporation
        |                                               |
RED -> GREEN -> independent VERIFY -> ACCEPT   implementation
        |                                               |
D-1 remains separate, own timeline             RED -> GREEN -> VERIFY -> ACCEPT
```

Neither path is executed by this brief. Both are recorded so the human can see one step past
the immediate decision without this pass performing that step.

## 8 · Specification-completeness check on the adopted `D-4`/`D-5` vocabulary

Verified directly against `2026-09-04-...-V3-D1-D4-D5-proposal.md` (the proposal `ADOPTED`
"exactly as proposed," per the ADOPTED record) and `...-ADOPTED.md`:

| Item | Verified specified? | Detail |
|---|---|---|
| `D-4` closed dispatch list | ✅ Yes | `call_user_func`, `call_user_func_array`, both matched only in literal `[$this, 'name']` form — no distinction drawn between the two functions beyond that shared boundary (a decision, not a gap). |
| `call_user_func` vs. `call_user_func_array` treatment | ✅ Yes — identical | Both are in-scope under the same literal-form test; the proposal draws no semantic distinction between them. |
| Literal `[$this,'name']` boundary | ✅ Yes — narrow and explicit | First element must be `$this` specifically (not `self::`, not `static::`, not another variable receiver); second element a literal string. |
| Explicit exclusions outside adopted scope | ✅ Yes, itemized | Runtime-assembled/variable/property-held/concatenated/returned callables; `Closure::fromCallable`/`Closure::call`/`Closure::bind`; `__call`/`__callStatic`; container/event-dispatcher/reflection/framework dispatch; any non-literal target name — declared a limitation, not silently unhandled. |
| `D-5` vocabulary (`ExplicitCallableDispatch`) | ✅ Yes | Defined, rejected alternatives recorded with reasons (§3 of the proposal). |
| `D-1`'s `IndeterminateBehaviourReference` — disposition, exclusion behaviour | ✅ Yes | No `targetMethodName` field; fixed `NotDeterminable`; mandatory-total in whatever it carries; no provenance fields. |
| `D-1`'s `IndeterminateBehaviourReference` — **exact schema / attribute list** | 🔴 **Genuinely underspecified, disclosed by the proposal itself** | Proposal §1.2, verbatim: *"attribute count/exact schema is a downstream architecture-review detail."* This is an intentional, disclosed deferral, not an oversight — but it is a real gap, not filled here. |
| Whether `IndeterminateBehaviourReference` would carry a `factId`-equivalent field | 🔴 **Not addressed anywhere in the adopted record** | The proposal predates the 2026-09-27 exclusion-evidence-record question entirely (adopted 2026-09-04; the representation question was first raised 2026-09-27). If Model C is ever chosen, this new fact kind's own `factId` provisioning is an open design point the adopted vocabulary does not speak to. |

**Nothing above is filled by invention.** Both flagged gaps are reported as gaps, consistent
with the instruction not to manufacture a resolution.

---

## STOP

No `expected.json` change. No code change. No test change. No governance/workflow transition.
No PO/ARB submission sent. **Next actor: the human** — choose Option A or Option B (§3/§4), or
request the two disclosed schema gaps (§8) be resolved first if either blocks the choice.

**Traceability:** `2026-09-28-...-V3-decision-gate-reconciliation.md` ·
`2026-09-28-...-V3-exclusion-evidence-sufficiency-pass.md` · `2026-09-27-...-V3-incorporation-
decision-brief.md` · `2026-09-27-...-V3-representation-boundary-experiment.md` ·
`2026-09-04-...-V3-D1-D4-D5-ADOPTED.md` · `2026-09-04-...-V3-D1-D4-D5-proposal.md` (read in
full this pass — the primary new source) · `2026-08-19-...-v3-decisions-registration.md`
(+Amendment 1, R1.2) · `2026-08-18-...-implementation-architecture.md` §4.4.
