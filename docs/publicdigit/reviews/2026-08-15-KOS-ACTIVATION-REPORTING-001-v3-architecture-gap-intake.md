# KOS-ACTIVATION-REPORTING-001 — Truthful reporting of activation prerequisites

**Work item created:** 2026-08-15 by Governance · **Workflow:** `architecture-decision` · **Roles:** governance · architecture · implementation · verification
**State:** OPEN · **NOT commissioned** — no assignment registered, no grant issued, no session started.
**Origin:** finding **`V-3`**, carried out of `KOS-SESSION-DISCOVERY-001` at its qualification (PO/ARB decision 2026-08-15, condition 1).

> **This work item exists to hold a decision that has not been made.** It is not a repair ticket, and it does not authorize anyone to change code. **No remedy has been chosen. Choosing one is this work item's whole purpose.**

---

## 1 · Classification — preserved verbatim from the verification and governance review

> ## **ARCHITECTURE / SPECIFICATION GAP**
> **NOT an implementation defect. NOT harmless. NOT resolved.**

Established by independent verification (`b6b8b0bd`, Session 1) and upheld on independent re-derivation by Governance (`3884d81d`). **`AST-016` was qualified and adopted with this gap open and named** — qualification did **not** resolve it, and nothing in this work item may be read as implying it did.

---

## 2 · The fact

`AST-015` (`.claude/scripts/workflow-state.php`) **computes** the predecessor-handoff fact and **uses it as a gate**, but **publishes it to no one**.

- Computed: `foldSessions()` accumulates `$handoffsTo[$t['to']] = true` (`:118`, `:135`) and returns it (`:166`).
- Enforced: the `START` validator refuses on `!isset($fold['handoffsTo'][$id])` (`:235-237`) — *"START requires the predecessor's recorded handoff (token attached) — a human act alone never yields ACTIVE (G-3)"*.
- **Not exposed:** `fold` emits `workItem · workflow · roles · sessions · mutationOwner · workItemState · grants` (`:344-352`). `identity` emits `predecessor` and `state` (`:365-373`). `authorized` emits a bare boolean (`:390`). **`handoffsTo` appears in none of them.**

## 3 · The observation

`AST-016` emits `missingForActivation` as a **hardcoded constant list** whenever a session is `CREATED` (`session-resolve.php:227-231`). It consults the record for this list **not at all**, and therefore states that the predecessor handoff is missing **whether or not it is**.

**Reproduced twice independently**, most decisively by Governance's control:

```
CASE A  handoffs recorded: null→impl , impl→verif    (handoff to verif PRESENT)
CASE B  handoffs recorded: null→impl                 (handoff to verif ABSENT)

AST-016 output ................ byte-identical in both cases
AST-015 asked to START verif .. CASE A ACCEPTED (exit 0) · CASE B REFUSED (exit 65)
```

> **The information is not unknowable — it is unpublished.** AST-015 discriminates the two cases perfectly. This is a **publication** gap, not an epistemic one. Any remedy that begins *"the fact cannot be known"* is starting from a false premise.

## 4 · Scope — exactly ONE of the two enumerated prerequisites

The two items in `missingForActivation` have **different epistemic status**. This narrowing is load-bearing and must not be lost:

| Enumerated prerequisite | Entailed by `state == CREATED`? | Truthful today? |
|---|---|---|
| *"a recorded human START act"* | **YES.** `START` is the only transition that sets `ACTIVE`, nothing returns a session to `CREATED`, and a human act exists **only** as an attribute of a `START` transition — there is no separate human-act transition | ✅ **Always true. Nothing to fix. Any remedy that discards this loses real information** |
| *"a recorded predecessor HANDOFF carrying its token"* | **NO.** A `HANDOFF` sets `handoffsTo[to]` while leaving the successor `CREATED` — exactly CASE A | ❌ **May be false. This, and only this, is V-3** |

## 5 · The architectural conflict to be decided

Two **approved** duties collide, and the approved architecture never reconciled them:

- **`INV-DISC-7`** (AM-3, governed): *"The resolver attests only what the record contains… Its report must never present prose claims — its own or anyone's — as record facts."*
- **The `G-3` completeness duty:** activation requires **both** facts, and the report exists to *"make missing activation facts visible, so a session cannot claim ignorance."*
- **The consumer-never-twin rule:** *"the resolver never re-implements folding… the mechanism remains the sole interpreter of its record."* **This is why V-3 cannot be fixed inside `AST-016`** — deriving `handoffsTo` there is precisely the prohibited second interpretation.

Under AST-015's current read surface these are **jointly unsatisfiable** for the handoff item:

> **State it** → assert as a record fact something the record did not supply → **breach `INV-DISC-7`.**
> **Omit it** → under-report a real `G-3` prerequisite → **breach the completeness duty.**

**Why the architecture is silent here, precisely:** §H and AM-3 both specify the field as `missingForActivation: ["recorded human START", …]`. **The architecture named only the entailed item and left the rest inside an ellipsis.** The implementation resolved that ellipsis to include the handoff. **V-3 lives exactly in the space the architecture left open** — it is not a deviation from the specification, it is the specification's unwritten half.

## 6 · What this work item must decide

**With its own evidence**, and **without pre-narrowing the option space**, whether:

1. the authoritative mechanism should **expose** the handoff fact to delegating consumers (and if so, through which command, in what shape, and whether this changes AST-015's qualified contract);
2. the resolver's **reporting contract** should change (e.g. from *"these are missing"* to *"these are required"*);
3. **another architecturally valid solution** applies — for example separating *verified-missing* from *required-but-unverifiable*, so that neither truthful information nor a real prerequisite is lost;
4. or the limitation is **accepted and documented** as permanent.

**⚠️ A cost already identified, so it is not rediscovered late:** option 2, applied to the **whole** list, would **discard truthful information** — the human-START item is genuinely and verifiably missing whenever the state is `CREATED`. Any remedy should be assessed per-item, not on the list as a whole (§4).

**The remedies sit in different components under different grants** (AST-015's read interface vs. AST-016's report semantics). That is precisely why neither Session 1 nor Governance chose between them, and why this is a separate work item rather than a correction.

## 7 · Risk posture while this remains open

> **V-3 fails safe.** The error is **over-reporting**: it may state a prerequisite is missing when it is satisfied. It **cannot** produce the opposite error — it can never report a session as activatable, authorized, or operable when the record does not support it. `operable` is assigned solely from the mechanism-reported state, and the authorization facts are emitted with hard `UNKNOWN`s and never evaluated.
>
> **Failure mode:** a session waits or escalates unnecessarily. **Never:** a session acts without authority.

**Observed cost to date:** one wasted reconciliation cycle (`32596519`), in which a competent session filed a false defect against a sound record. *The record was right; the report was wrong.*

**🔒 BINDING CONSTRAINT — PO/ARB condition 2, 2026-08-15.**
**`AST-016` must NOT be wired into `SESSION_START` or any automatic startup path until V-3 is resolved.** Today the false line is seen occasionally, by someone who asked for it. Wired into startup it would be read by **every session at every start**, and the failure would change character from an occasional wasted cycle into **systematic misinformation**. This constraint is recorded on the asset (`registry.yaml` → `AST-016.runtime_moments`) as well as here.

## 8 · Explicitly out of scope

Not part of this work item, and not to be folded into it: `E-1` (the authoritative runtime record is untracked/gitignored) · the successor-registration bootstrap gap · branch/publishing questions · `D-1`…`D-6` · Increment-2 enforcement · any Election work.

---

## Traceability

`V-3` origin: Session 1 corrective verification `b6b8b0bd` · Governance review `3884d81d` (independent re-derivation, three refinements, CASE-A/B control) · qualification + adoption of `AST-016` 2026-08-15 · `workflow-state.php:118,135,166,235-237,344-352,365-373,390` · `session-resolve.php:156,227-231` · approved architecture §H · `AM-3`/`INV-DISC-7` · consumer-never-twin rule · `32596519` (the cycle V-3 cost) · parent work item `KOS-SESSION-DISCOVERY-001`
