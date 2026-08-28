# Session-2 Review — S1-F001

## 1 · Source artifact
`session1/S1-F001-authority-temporal-semantics-gap.md`

## 2 · What Session 1 claims
The model has a temporal gap around authority: across 20 examined grants, **0/20** carry temporal properties. Authority is recorded but not time-indexed. Session 1 classes several temporal questions as one family and marks the authority-validity question **NOT ADDRESSED**.

## 3 · Evidence classification
`0/20` is **FACT** about the artifacts examined — but see §7: it is **one schema fact observed twenty times**, not twenty observations (`S2-F002`). The *"temporal family"* grouping is **INFERENCE**. The temporal thesis itself is **HYPOTHESIS**.

## 4 · Question type
**TEMPORAL** primarily, **GOVERNANCE** decisively (who may admit, and was that party entitled *at the time*), **IDENTITY** secondarily. `S2-F004` establishes these are **three questions, not one family**: authority validity ≠ claim validity ≠ claimant stance.

## 5 · DDD / architectural altitude
`Authority` is a **Reference** in law, held by the Authority supporting context. `TemporalValidity` is a **Value Object** on the claim. Altitude: **Domain/Core**, touching **Governance**. Not a mechanism, not a measurement.

## 6 · Provenance assessment
Session 1's own measurement over the model. **POSSIBLE INDEPENDENT ARRIVAL** — it is a count, not a restatement. But `S2-F012` establishes that the *later* accumulation of this into a "fourth arrival at the temporal dimension" fails: a measured absence is not an arrival at a thesis, and items 3–4 of that tally are one author (`S2-F015`).

## 7 · Zero-lens assessment
**The decisive section.** `0/20` with three properties at *exactly* zero while representable ones vary (20/20, 13/20, 7/20) is the **signature of an absent representation, not a measured rate** (`S2-F002`). Correct classification: **UNREPRESENTED**, not *zero*, not *excluded*, not *rejected*.

And `S2-F003` sharpens Session 1's own classification: **the model does time-index the claim** (`TemporalValidity` = valid-from · valid-until · superseded-by) — it does **not** time-index the **authority**. So *NOT ADDRESSED* understates the situation: the machinery exists and is attached to the wrong object.

## 8 · Contradiction test
No contradiction alleged or found.

## 9 · Convergence test
`S1-F001` ↔ `S1-F004` ↔ `S1-F005` on temporality → **not independent** (`S2-F012`): a measurement, an external opinion, and one author's position stated twice. Defensible count: **one clear arrival, possibly two.**

## 10 · What survives
The core measurement, reclassified: **authority is recorded as a bare reference with no temporal binding, while the claim carries a full validity interval.** That asymmetry is real, checkable, and — see §12 — the most implementation-relevant result in the entire Session-1 record so far.

## 11 · What is challenged
`0/20` as a rate (`S2-F002`) · the single "temporal family" (`S2-F004`) · *NOT ADDRESSED* as the classification (`S2-F003`) · the convergence accumulation (`S2-F012`).

## 12 · Implementation relevance

### Kernel test
*Remove the ability to know whether the authority that admitted a claim was entitled to do so **at the moment of admission**.* What becomes impossible?

- A later-revoked or expired authority's past admissions become **indistinguishable** from admissions made under valid authority.
- *"Was this admission legitimate?"* becomes unanswerable retrospectively — not harder, **unanswerable**, because the information was never recorded.
- `INV-KOS-AUTHORITY-001` (*authority is assigned, never emergent*) remains satisfied at every instant and yet the **history** it protects is unauditable.

This is not "less convenient." It is the loss of a guarantee — and it is a **governance** guarantee, which is the class the Kernel exists to protect.

### Duplicate-control check — performed, and it clears
Verified directly in v1.1 (read-only; reported in §14):

| Element | What law says | Temporal binding? |
|---|---|---|
| `Authority` (§ member table, l. 211) | **Reference** — *"held by the Authority context, referenced by the aggregate"* | **none** |
| `TemporalValidity` (l. 214) | valid-from · valid-until · superseded-by, Article 11 | yes — **on the claim** |
| `INV-KOS-AUTHORITY-001` (l. 248) | assigned never emergent; source roles preserved | **no temporal clause** |
| `revok*` anywhere in v1.1 | **zero occurrences** | — |

So the concern is **not** already protected in the core model. No existing control duplicates it.

### Decision
**IMPLEMENTATION QUESTION** — and the register's strongest to date.

⚠ **Why not `IMPLEMENT`.** Applying the Zero lens to my own result: `revok` scoring zero in v1.1 does **not** establish that revocation is absent from KnowledgeOS. Law says authority is *"held by the Authority context"* — a supporting context whose internals v1.1 describes but does not specify. Temporal validity of a grant may legitimately live **there**. What I have established is narrower and still substantial: **the core model cannot express it, and nothing I can verify says the supporting context does.** Concluding `IMPLEMENT` from a grep would be exactly the *not mentioned → absent* conversion I have charged others with.

### Engineering consequence (smallest thing, not a design)
The authority reference recorded at admission **resolves to the grant's validity as of that moment** — so a later revocation cannot retroactively erase the auditability of past admissions. A binding on an existing reference, not a new member and not a subsystem. Testable: revoke a grant, then assert that admissions made before revocation remain attributable and distinguishable from those made after.

### What would be lost by not implementing
**Historical authority becomes unrecoverable.** Concretely: after any revocation, every past admission by that authority is permanently ambiguous, and no later work can repair it because the interval was never captured. Irreversible — which distinguishes this from every other candidate assessed so far.

### Existing protection, if any
`INV-KOS-AUTHORITY-001` protects *that* authority is assigned; **nothing verified protects *when*.** `TemporalValidity` protects the claim's interval, not the grant's.

## 13 · Open questions
Does the Authority supporting context hold grant validity intervals? *(the one question that decides §12)* · Are the three temporal questions of `S2-F004` separately owned? · Is claimant *stance over time* in scope at all?

## 14 · Confidence
**High** on the asymmetry and on the duplicate-control result — both verified against law. **Medium-low** on whether an implementation requirement genuinely exists, pending the Authority-context question.

⚠ **External source consulted:** v1.1 §member tables and §invariant table, via `grep`, read-only — to test whether the concern is already protected. Nothing modified. No architectural question opened or answered.

## 15 · Final Session-2 assessment
Session 1's measurement is sound; three of its four classifications are challenged, and the challenges **strengthen** the finding rather than weakening it — the machinery for temporal validity already exists and is bound to the claim rather than the grant, which is a sharper defect than a plain absence. **This is the first Session-1 artifact whose review produces a concrete, irreversible loss under non-implementation.** Recorded as `IMPLEMENTATION QUESTION`, **NOT ADJUDICATED**, with the single question that would settle it named.


---

# UPDATE · 2026-08-28 — `S1-F034` supplies the missing criterion (verdict unchanged)

`S1-F034`'s **sufficient state** — *what is the minimum that must be retained so a governed decision remains possible?* — is the criterion this finding lacked. And law makes the point sharper: forward-only history retains *"every prior state"* **of the aggregate's members**, but `Authority` is a **Reference** *"held by the Authority context, referenced by the aggregate"* (l. 211). **Retaining every prior state of the aggregate does not retain the grant's validity interval**, because the interval was never inside the aggregate to be retained. **Retention ≠ sufficiency**, and law guarantees only the first.

⚠ **The verdict does not change.** It remains `IMPLEMENTATION QUESTION`: the Authority *context* may hold the interval, and I cannot inspect it. What changes is **specificity** — the criterion for what would count as an answer is now named, and the question at §13 is unaltered.

**Convergence note:** two routes to one hole — `S1-F001` measured that the question is unanswerable; `S1-F034` states what would make it answerable. Recorded as **POSSIBLE INDEPENDENT ARRIVAL**; both reach me through Session 1, so the extraction is common to them.
