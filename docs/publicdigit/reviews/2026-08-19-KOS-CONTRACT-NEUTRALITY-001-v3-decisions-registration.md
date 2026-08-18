# Registration — the five V-3 decisions, OPEN for PO/ARB disposition

**Registered by:** Governance, on the delivered PO/ARB `RECORD` act · 2026-08-19
**Work item:** `KOS-CONTRACT-NEUTRALITY-001` · **basis:** the V-3 Architecture Determination (`4f429a81`), delivered under `G-KOS-CONTRACT-V3-ARCH` + `-AMD1`, assignment seq 38–40
**⚠ This registration OPENS five decisions and DECIDES NONE. No implementation, no specification change, no expected evidence, no assignment.**

> ## Track 1 remains **ACCEPTED** within its authorized implementation scope. **These decisions do not reopen acceptance.**

---

## 1 · Evidence authority — registered as binding

> **The Architecture determination is evidence / proposal material. It is NOT itself a semantic decision.**
> **The current implementation is NOT the authority.**

The determination holds to this itself: *"Five decisions. `PROPOSED` framings only — this determination selects none."* And it returns the insufficiency explicitly, as its grant required: ⛔ **the existing specification is insufficient for D-1, D-2, D-3 and D-5**; it is sufficient for D-4's *semantic scope* and insufficient only for D-4's *binding knowledge boundary*.

## 2 · ⚠️ The qualification the ARB directed be kept in the decision record

**The determination's producing process disclosed material prior participation that the independence amendment did not cover.** It authored the **semantic clarification proposal** (`dab0f65c`), whose §4 **first proposed that the L3 fact model must carry a determinability attribute**. `V-3` is a finding about exactly that attribute.

⇒ **This determination asks whether an L3 requirement that same process proposed is entailed by the accepted model.** The determination states the consequence itself rather than minimising it: it must be weighed as **possible advocacy for the drafter's own earlier proposal**, not only as neutral entailment analysis — *"a conflict of a different kind from the two named bars"* — and it records that **the PO/ARB may route the determination elsewhere on this disclosure.**

**The two named bars were satisfied:** the process is neither the Track-1 implementer (`1c8b041b`) nor the V-3 finding's author (`2da45a86`). Self-declared and **not attestable** (`INV-ATTR-2`/`G-2`).

**A self-correcting consequence, recorded because it changes an accepted decision's completeness:** the **four contract silences** enumerated under Decision 13.5 **did not include dynamic property access.** It is a **fifth** silence. ⇒ **13.5's enumeration is incomplete on the record** — `D-2` is the decision that meets it.

## 3 · The five decisions — OPEN

| # | Decision | Admissible answers, as framed |
|---|---|---|
| **D-2** | **Is dynamic property access (`$this->$p`) inside the language-neutral cohesion contract?** | **A. IN SCOPE** · **B. OUT OF SCOPE / declared limitation** (a *defer with a named trigger* was also framed). ⛔ **Do not infer the answer from current implementation behaviour.** ⛔ **The `intra_class_calls` clause must NOT be extended to state by analogy** — it governs calls, and the silence is a silence |
| **D-3** | *If D-2 = IN SCOPE:* **must `StateAccess` represent observed property access + property target not determinable as a distinct fact?** | yes · no · defer. **Must preserve `observed-but-not-determinable ≠ not observed`.** ⛔ **No sentinel that violates `INV-L3-5`.** ⚠️ **The only decision that changes an accepted L3 type AND adds an L4 rule** |
| **D-1** | **How is `$this->$m()` represented in L3?** | **(a)** `targetMethodName` **optional/absent** for computed members · **(b)** a **distinct L3 fact kind** for an undeterminable behaviour site · **(c)** a **stated LIMITATION** — unrepresentable, said so in the contract. **Must address the current non-nullability.** ⛔ **no raw PHP source into L3** (`INV-4`/`INV-L3-7`) · ⛔ **no invalid sentinel** (`INV-L3-5`) · ⛔ **do not collapse observed into absent** |
| **D-4** | **What is the current scope of PHP standard-library dispatch?** | **A.** accept the proposed bounded list (§12) · **B.** declare library dispatch outside the current binding scope **and explicitly amend the contract limitation** · **C.** another explicitly justified boundary. ⛔ **Do not silently broaden the binding.** ⚠️ **The contract already names `call_user_func`, so rejection is NOT a no-op** — it obliges a contract amendment |
| **D-5** | **Which L3 vocabulary / `QualifierKind` describes an accepted library-dispatch case?** | reuse `ComputedTarget` · add a case (e.g. `LibraryDispatch`) · `InstanceReceiver` + `NotDeterminable`. **Must remain language-neutral at L3** and ⛔ **must not import implementation-specific PHP parser terminology.** Needed only if D-4 adopts |

### 3.1 The dependency order, registered

```
D-2  is the fact in the contract at all?
  ↓
D-3  if yes, how must L3 represent it?
  ↓
D-1  how is a computed method name represented?
  ↓
D-4  which library dispatch is in scope?
  ↓
D-5  how is that dispatch represented?
```

**The rule that makes the order binding rather than cosmetic:** ⛔ **D-2 and D-3 must not be split into contradictory decisions.** D-2 settles whether the fact is semantically in scope; D-3 settles the representation **only if it is**. *Deciding representation before membership designs a data structure for a fact that may not belong to the model.*

**⚠️ An adoption constraint the determination attaches to its own draft invariant (§11):** as drafted it is **unsatisfiable for `$this->$m()` and `$this->$p` under the current L3 types**, so ⛔ **it must be adopted TOGETHER with D-1 (and D-3 if D-2 rules), never before them** — adopting it alone would create a decided invariant with no admissible implementation.

## 4 · Artifact-update consequence — the gate is narrowed, not lifted

**Recorded as `G-KOS-CONTRACT-ARTIFACT-UPDATE-AMD2`** (grants are append-only; parent + AMD1 + AMD2 must be read together):

- ✅ **expected evidence for ordinary existing fixtures is NOT blocked;**
- 🔴 **dynamic-member expected evidence REMAINS blocked;**
- ⛔ **no expected evidence may be authored for V-3 cases until the relevant decisions are settled;**
- ⛔ **the artifact-update assignment is NOT created.**

**Why the narrowing is evidence-backed rather than a relaxation.** The determination measured occurrence, and stated it carefully in both directions:

| Construct | in `app/` | in the ten golden fixtures |
|---|---:|---:|
| `$this->$…` | 3 files | **0** |
| `call_user_func([$this, …])` | 0 files | **0** |

> *"Zero occurrences is NOT evidence of no gap — the contract names the construct, so the specification gap is real. **And it is not evidence of urgency either.**"*

⇒ the golden evidence set is untouched by V-3, so **one unresolved edge case no longer freezes the whole expectation set.** AMD1's blanket wording was written before the determination existed and was wider than the evidence supported.

### 4.1 ⚠️ A residual difference returned to the PO/ARB, not resolved here

The determination's own condition (§10) is **narrower still** than this act's wording: *"YES for the golden-fixture evidence… **NO for `app/`-scoped or CONFORMANCE-ASSERTING evidence** until D-1/D-2 are answered."* The act frees *"ordinary existing fixtures"* and does not mention the conformance-asserting case.

**Consequence if left unresolved:** expected evidence authored over ordinary fixtures **but used to assert conformance** would be permitted by the act's wording and refused by the determination's. **Governance registers the act's wording as governing and flags the residue rather than choosing between them.**

## 5 · Next step, as directed

After the PO/ARB decisions: **Governance translates them into the appropriate specification / artifact-update authorization.** **Implementation follows only where a correction is actually required** — and the determination is explicit that only one case is correctable today: 🟡 **`call_user_func` only, and only if D-4 adopts**; ⛔ **the two dynamic-member cases CANNOT be corrected by implementation** before D-1/D-2/D-3, because *"any 'correction' to them before those decisions would silently invent a representation."* **Independent Verification remains a separate actor.**

## 6 · One record-state item for the PO/ARB

`S4-architecture-v3-determination` is still **`ACTIVE`** and holds mutation ownership, although its deliverable is complete and it stopped as instructed. **Governance did not hand it off** — there is no successor lane to hand off to, and closure is a governance act on a human decision (`G-1`). *This is the same lag that had to be disclosed for ADR-AIP-04's Correction #2; naming it here keeps it from becoming a discovery later.*

## 7 · Not done

No decision taken on D-1…D-5 · no contract or specification text written · no invariant adopted · no implementation authorized · no expected evidence · **no artifact-update assignment** · Track-1 acceptance not reopened · no lane closed.

**Next actor: PO/ARB — decide D-2 → D-3 → D-1 → D-4 → D-5.**

**Traceability:** the PO/ARB `RECORD` act 2026-08-19 · V-3 determination `4f429a81` (§0 independence gate incl. gate item 7 · §2 · §3.4 scale · §8 the five decisions · §9 correction consequences · §10 gate condition · §11 draft invariant · §12 draft scope wording) · `G-KOS-CONTRACT-V3-ARCH` + `-AMD1` (separation amendment `b1227c86`) · `G-KOS-CONTRACT-ARTIFACT-UPDATE` + `-AMD1` + `-AMD2` · Track-1 acceptance registration · independent verification `50d55d26` (`V-3`, verdicts B, C) · Decisions 13.1 · 13.3 · **13.5 (enumeration incomplete — a fifth silence)** · 13.7 · Decision 1 · `INV-L3-5` · `INV-4`/`INV-L3-7` · `INV-ATTR-1`/`INV-ATTR-2` · `G-1` · `G-2` · `R-34`/`P-2`

---

# ⬛ AMENDMENT A1 — the decision set re-issued · 2026-08-19

**Registered by:** Governance, on a second delivered PO/ARB act on the same day, restating the same five decisions.
**⚠ No second decision register was created.** The decisions `D-1`…`D-5` are registered **once**, above. This amendment records only what the re-issue **adds**, so the estate carries one register rather than two competing ones.

## A1.1 · Confirmed unchanged by the re-issue

Track 1 **remains accepted** within its authorized implementation scope; the decisions do not reopen it · the determination is **proposal / evidence material, not a decision** · **Track-1 implementation behaviour is not the authority** · all five decisions remain **OPEN**, none decided · `D-3` remains conditional on `D-2 = IN SCOPE` · the artifact-update assignment is **not created**.

**Two items from the earlier act are NOT restated by the re-issue and are NOT withdrawn by it — they stand:**
- ⛔ **`D-2` and `D-3` must not be split into contradictory decisions** *(the re-issue encodes it as "only if `D-2` = IN SCOPE")*;
- ⚠️ **the advocacy qualification** on the determination's producing process (§2), which the ARB directed be kept in the decision record.

## A1.2 · What the re-issue sharpens

| Decision | Added by the re-issue |
|---|---|
| **D-1** | The forbidden representations are enumerated further: ⛔ **raw PHP source** · ⛔ **parser objects** · ⛔ **fake method names** · ⛔ **ambiguous sentinel strings** — and the preserved distinction is stated as **observed reference ≠ absence** |
| **D-3** | The sentinel prohibition widens from `INV-L3-5` alone to **"a sentinel that violates existing identity/name invariants"** |
| **D-4** | The consequence pair is stated directly: **accepting defines a bounded binding responsibility · rejecting requires an explicit contract limitation.** ⛔ **Do not silently widen the scope beyond evidence** |
| **D-5** | The neutrality test is sharpened from *"no PHP parser terminology"* to **"the vocabulary must express the SEMANTIC FACT, not the PHP API name"** |

*`parser objects` is the sharpest addition: it closes a representation that "no raw source text" alone would not have — a structured parser artifact is not source text, and would have leaked the same provenance `INV-4`/`INV-L3-7` exclude.*

## A1.3 · Artifact-update gate — position unchanged, prohibitions now named

Recorded as **`G-KOS-CONTRACT-ARTIFACT-UPDATE-AMD3`**. **The gate does not move.** AMD3 records only the four prohibitions the re-issue states by name, which AMD2 carried by inheritance:

⛔ **do not execute artifact update** · ⛔ **do not modify `expected.json`** · ⛔ **do not modify fixtures** · ⛔ **do not modify implementation.**

**The wording residue from §4.1 is carried forward unresolved:** the act frees *"ordinary existing-fixture evidence"*; the determination also refuses **conformance-asserting** evidence until `D-1`/`D-2`. **The act's wording governs; the residue remains a PO/ARB question.**

## A1.4 · Not done

No decision taken on `D-1`…`D-5` · no artifact update executed · `expected.json` untouched · fixtures untouched · implementation untouched · no assignment created · Track-1 acceptance not reopened · no lane closed.

**Next actor: PO/ARB — `D-2` → `D-3` → `D-1` → `D-4` → `D-5`.**
