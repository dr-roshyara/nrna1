# Proposed Classification Inputs (`type`, `role`) — Orthogonality · Necessity · Sufficiency

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Classification:** verification report · **Role:** Reference (evidence) · **Scope:** product-specific · **Domain:** publicdigit-programme · **Maturity:** research
*(Classification stated before placement, per the standing correction.)*
**Repository Integrity Gate:** ✅ PASSED. **No standard amended · nothing proposed for adoption.**

> **ANNOTATION (2026-08-01, ARB review — added, not rewritten):** the claim *"`session-state` **is** a Role"* is **weakened**. **Evidence supports only:** *the current implementation treats `session-state` as if it were a Scope, while its observed semantics align more closely with the **Runtime** Role defined by ES-004.3. This indicates a possible **modelling inconsistency requiring governance confirmation before correction**.* **Consequently the §Recommendation item 1 is reclassified from "defect fix" to "CANDIDATE SEMANTIC CORRECTION" — changing a registry value changes the classification model, which is a semantic act, not configuration hygiene. Architecture reports evidence; governance confirms semantics.**

---

> # RESULT — **the test does not return an unqualified yes. On the ARB's own rule, the ES-005 amendment should NOT proceed to include these inputs yet.**
>
> **Orthogonality ✅ · Necessity ⚠️ PARTIAL · Sufficiency ✅ (over two cases only).**
>
> **Necessity fails: only 2 of the 5 observed cases are explained by the absence of `type` and `role`.** I recommended these inputs before running this test. **The test does not support the recommendation as made.**

---

## Test 1 — Orthogonality ✅

**Are `type` and `role` genuinely independent of Scope, Domain, Steward, Maturity?**

### Direct demonstration from the two misplacements

| | `WP-7C_Engineering_Readiness` | `PublicDigit_Engineering_Protocol` |
|---|---|---|
| Scope | product-specific | product-specific |
| Domain | publicdigit | publicdigit |
| Steward | engineering | engineering |
| Maturity | pre-approval | pre-adoption |
| **Correct home** | **`.claude/plans/`** | **`docs/implementation/`** |

> **Two artifacts identical on all four existing properties, requiring different homes.** **The existing classification cannot distinguish them — which is the definition of a missing independent dimension.** What differs is **Role** (Runtime vs Reference) and **Type** (work plan vs proposal paper).

### And the implementation already concedes it

**The placement registry's `scope` field currently takes three values:**

```yaml
match: { scope: session-state }      # ← not a scope
match: { scope: cross-product, … }
match: { scope: product-specific }
```

> **`session-state` is not a scope.** Scope answers *"could another project adopt this unchanged?"* — `cross-product` and `product-specific` are its two answers. **`session-state` answers *what role does this play*, and ES-004.3 names that role: Runtime.**
>
> **The registry has been carrying a Role value inside the Scope field since it was written — by me.** **Orthogonality is not merely arguable; the model already needed the dimension and faked it.**

**Verdict: ✅ orthogonal, demonstrated twice — once from data, once from the implementation's own shape.**

## Test 2 — Necessity ⚠️ PARTIAL

**Can every observed misplacement be explained by the absence of these inputs?**

| # | Case | Explained by missing `type`/`role`? |
|---|---|---|
| 1 | `Layer_Verification_Rule.md` written into `engineering/` while unqualified | ❌ **No** — cross-product + research is an **unruled cell**. Type and Role would not have produced an answer, because **no rule exists for that combination at all** |
| 2 | Documentation-index candidate → PENDING | ❌ **No** — same unruled cell |
| 3 | Recurring-classification principle → PENDING | ❌ **No** — same unruled cell |
| 4 | Readiness artifact placed in a documentation root | ✅ **Yes** — Role = Runtime resolves it |
| 5 | Protocol placed in a documentation root | ✅ **Yes** — Type = proposal paper resolves it |

> ### **2 of 5. The other three are a different gap.**
>
> **Cases 1–3 are the unruled `cross-product + research` cell — the stewardship question.** Adding `type` and `role` would leave them exactly as they are: **the resolver would still return PENDING, because PENDING is an absent *rule*, not an absent *input*.**
>
> **This distinction matters and I had blurred it.** My observation presented five artifacts as one body of evidence for one gap. **They are two gaps: three cases need a ruling, two need an input.** **Conflating them would have justified an amendment with evidence that does not bear on it.**

**Verdict: ⚠️ partial — necessity holds for 2 cases, not for 5.**

## Test 3 — Sufficiency ✅ *(over two cases)*

**Would adding only these inputs resolve the observed cases without requiring further dimensions?**

| Case | Resolved by | Further dimension needed? |
|---|---|---|
| Readiness artifact | **Role = Runtime** → the runtime mount (ES-005.1, ES-004.3) | **No** |
| Protocol proposal | **Type = proposal paper** + **Maturity = pre-adoption** → `docs/implementation/`, per the `Plan_Concept` / `Placement_Rule` / `Artifact_Ownership` precedent | **No** — Maturity is already an input |

**Both inputs are load-bearing and neither is redundant: Role resolves case 4 and cannot resolve case 5; Type resolves case 5 and is not needed for case 4.**

> **Honest limit on this verdict: sufficiency is demonstrated over exactly two cases.** **Two is enough to show no *sixth* dimension was required here. It is not enough to show none ever will be.**

**Verdict: ✅ for the observed cases, on a thin base.**

## What the three tests jointly support

| Claim | Supported? |
|---|---|
| `type` and `role` are independent dimensions | ✅ **Yes — strongly** |
| The classification model is **incomplete for repository-wide placement** | ✅ **Yes** — and the `session-state` mislabelling is the proof |
| Adding `type` and `role` would fix the two misplacements | ✅ **Yes** |
| **Adding them addresses the observed evidence as a whole** | ❌ **No — three of five cases are unaffected** |
| **The amendment should proceed now** | ❌ **Not on this evidence** |

## Recommendation

> **Do not add `type` and `role` to the ES-005 amendment package yet.**
>
> **The rule the ARB set is *all three qualities, or the amendment does not proceed*. Necessity is partial, so the rule is not met** — and the honest response is to say so rather than to argue that partial necessity is enough.

**What should happen instead, in order:**

| # | |
|---|---|
| **1** | **Correct `session-state` from a Scope value to a Role value.** This is not the amendment — **it is a defect in the registry I wrote, in which a Role masquerades as a Scope.** It is a fix, and fixing it will make the missing dimension visible in the configuration rather than argued in a report |
| **2** | **Let the stewardship decision resolve cases 1–3.** They are an unruled cell, not a missing input. **Once resolved, re-run necessity against whatever cases remain** |
| **3** | **Re-run this test then.** If necessity comes back clean over the remaining evidence, the amendment proceeds with all three qualities satisfied |

**Nothing here is urgent: neither the two misplacements nor the three PENDING cases blocks any product work.**

## On the capability framing — adopted

> **The capability is *Repository Documentation Classification*. Placement is one consumer of it.**
>
> `Engineering Capability → Classification Model → Placement Resolver → Repository Structure`

**Accepted, and it sharpens the recommendation above:** a capability is extended when it cannot answer a question put to it. **It was asked five questions today; it failed two for want of an input and three for want of a ruling.** **Only the first kind is a capability gap.**

**Also recorded: this is a supporting engineering capability, not a PublicDigit business concern.** The first question is *what engineering capability is missing*, not *what document model should we build* — **and the answer here is smaller than a model: two inputs, one of which is already present under a wrong name.**

## On "three taxonomies" — corrected

**They are three independent classification *dimensions*, not three taxonomies of the same kind.** `Document Type`, `Artifact Role` and `Record Responsibility` answer different questions and can vary independently. **The looser word invited the reading that they compete or duplicate; they do not.**

---

**Traceability:** `docs/knowledge/schema/documentation-placement.yaml` (the `session-state` mislabelling) · **ES-004.3** (the four roles and their placement semantics) · **ES-005.1** (runtime mount) · **ES-005.3** (the derivation) · `docs/knowledge/schema/knowledge-types.yaml` (the type vocabulary) · `docs/pks/2026-08-01-documentation-classification-gap-observation.md` (the five cases) · `2026-08-01-artifact-classification-correction.md` (cases 4–5) · `2026-08-01-recurring-unruled-classification-finding.md` (cases 1–3) · `2026-08-01-es005-amendment-package.md` (the vehicle, unchanged). **No standard amended · no amendment extended · nothing adopted.**
