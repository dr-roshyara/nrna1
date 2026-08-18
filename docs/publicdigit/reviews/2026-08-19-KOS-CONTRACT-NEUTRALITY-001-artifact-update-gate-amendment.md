# Registration — the artifact-update gate: expired premise replaced by the V-3 precondition

**Registered by:** Governance, on the delivered PO/ARB `RECORD` act · 2026-08-19
**Work item:** `KOS-CONTRACT-NEUTRALITY-001` · **grant:** `G-KOS-CONTRACT-ARTIFACT-UPDATE-AMD1` (AUTHORIZED)
**⚠ Grants are append-only.** The parent grant still carries its original rationale on the record and **must be read together with this amendment**, which supersedes that rationale.

---

## 1 · The premise that expired

The parent grant withheld the artifact-update assignment on one stated ground:

> *"The expected node and edge sets cannot be written until `OQ-1`, `OQ-2` and `OQ-4` are settled… creating the assignment now would authorize writing expectations against an undecided identity scheme."*

**All three are now settled**, verified against the record before amending:

| | Disposition | Where |
|---|---|---|
| **OQ-1** identity rule | **SETTLED** — ratified, with `OPEN-1` recorded | `AMD4` |
| **OQ-2** analysis scope | **SETTLED** — one PHP source file | `AMD2` |
| **OQ-4** extraction transport | **DISPOSED / DEFERRED** — not applicable to current scope; deferred to a future binding's own work item | `AMD2` |

The final implementation architecture the parent grant recommended waiting for also exists and was accepted. **The waiting condition had therefore run out**, and the hold was continuing by inertia rather than by reason — which is precisely the state in which a gate silently stops protecting anything.

## 2 · The precondition now in force

> **"V-3 Architecture determination must be settled before expected node/edge evidence is authored or the artifact-update assignment is executed."**

**Reason, as given in the act:** the determinability semantics must be settled before expected evidence is written; **otherwise the evidence layer could encode the very ambiguity `V-3` exists to resolve.**

*Governance note: this is the parent grant's own logic advanced by one decision. It refused to authorize expectations written against an undecided **identity** scheme; the identical objection now holds for an undecided **determinability** semantics. The gate did not weaken — its reason was replaced with a live one.*

## 3 · The downstream consequence, in the exact form required

> **"V-3's ruling retires the conditional portion of Verification verdicts B and C. It determines whether a bounded correction is owed against an already-accepted Track-1 delivery; it does not reopen the acceptance."**

**This wording is load-bearing and was chosen over a broader one.** Track 1 was accepted on 2026-08-18 *within its authorized implementation scope*, with the acceptance's own boundary recorded: *"acceptance does NOT mean conformance has been established."* Verdicts **B** and **C** were PASS WITH NOTES **because** `V-3` could not be decided by verification. `V-3` therefore settles a conditional slice of two verdicts — it does not re-adjudicate the delivery.

## 4 · The registered sequence

```
Track 1 implementation                    ✅ delivered
Independent Verification                  ✅ reported
PO/ARB acceptance within scope            ✅ recorded
        ↓
V-3 Architecture determination            ← NEXT · registered, NOT STARTED
        ↓
PO/ARB decision on V-3
        ↓
Artifact-update commission                ← gated by §2
        ↓
Implementation correction, if required
        ↓
Independent Verification of the correction
        ↓
Future declared conformance evidence
```

## 5 · Unchanged and still in force

The parent grant's operative prohibition — **"DO NOT LET IMPLEMENTATION SILENTLY CHANGE EXPECTATIONS"** · the artifact update is its own governed act with its own assignment and may not be folded into an implementation slice · the recorded consequence set it must eventually cover (trait and enum as analysed units · nullsafe edges requiring **declared** expected evidence per 13.5, because differential comparison cannot detect a shared blindness · anonymous-class identity · the 13.3 qualifier rules changing edge sets **on both sides** · 13.7 making `expected.json`'s ten fixture rows one of three assertion layers).

## 6 · Not done — as the act directs

⛔ Track-1 acceptance not reopened · ⛔ no implementation authorized · ⛔ no expected evidence created · ⛔ **no artifact-update assignment created** · ⛔ implementation not modified · ⛔ **`S4-architecture-v3-determination` NOT STARTED** (state: `CREATED`).

**Next actor: Human PO/ARB → `START S4-architecture-v3-determination`.**

## 7 · One divergence returned to the PO/ARB before START

The prospective Architecture briefing lists **eight** deliverable sections; the registered grant `G-KOS-CONTRACT-V3-ARCH` lists **ten**. The briefing **adds** *downstream correction consequences* and a richer DDD test (bounded context · consequence for L4 · consequence for L5 · consequence for 13.7 evidence), and **drops two standalone sections the original commission required**: **proposed invariant wording** and **proposed library-dispatch scope wording**.

**Why this is worth one line rather than silent reconciliation:** those two sections are the drafted text the PO/ARB would ratify. Without them the determination can conclude what the rule *should* be while handing back no wording to adopt — and the decision would need a further drafting act. **Governance has amended nothing here**; the grant and the briefing are recorded as they stand, and the reconciliation belongs to the START act.

**Traceability:** the PO/ARB `RECORD` act 2026-08-19 · `G-KOS-CONTRACT-ARTIFACT-UPDATE` (parent, `OQ-3`) · `G-KOS-CONTRACT-V3-ARCH` · `AMD2` (OQ-2, OQ-4) · `AMD4` (OQ-1, `OPEN-1`) · Track-1 acceptance registration · independent verification `50d55d26` (verdicts B, C; `V-3`) · Decisions 13.3 · 13.5 · 13.7 · Decision 1 · `G-1` (closure is a governance act) · `G-3` (START requires the human act)
