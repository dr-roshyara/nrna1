---
artifact: DECISION-SIGMA-EPISTEMIC-STATUS
mandate: "RESOLVE Σ WITHOUT FORCING A NORMATIVE GUESS" §12 (13 required sections)
date: 2026-08-30
status: **DERIVED — the question I asked was malformed; no normative choice is required for Σ itself**
authority: verifier session (adversarial, independent)
evidence_class: B (executed classification + corpus enumeration) + A (formal)
---

# Σ — Epistemic Status: Decision Paper

## 0. Headline — and a correction to my own question

**I asked the author to choose among five status vocabularies. That question was malformed, and the
refusal was correct.**

> **The "eleven competing vocabularies" were never competing. They are FIVE DIFFERENT KINDS OF THING
> crammed into one field. Once separated, the epistemic core is uniquely determined at THREE states.**

---

## 1. All discovered vocabularies (executed enumeration, 24 terms)

| Term | Files | Numbered-step span |
|---|---|---|
| Unknown | 45 | 12..242 (26 steps) |
| Supported | 24 | 8..245 (19) |
| Rejected | 21 | 1..245 (12) |
| Inferred | 18 | 9..245 (5) |
| Candidate | 18 | 8..242 (16) |
| Observed | 18 | 121..245 (5) |
| Accepted | 17 | 5..231 (11) |
| Confirmed | 16 | 1..233 (6) |
| Superseded | 11 | 25..232 (8) |
| Conflicted | 11 | 25..207 (8) |
| Approved | 11 | 24..210 (10) |
| Validated | 9 | 39..245 (7) |
| Refuted | 7 | 25..242 (6) |
| Proposed | 6 | 10..231 (3) |
| Contested | 6 | 13..214 (3) |
| Established | 5 | 23..176 (5) |
| Corroborated | 4 | 19..223 (3) |
| Verified | 4 | 121..205 (4) |
| Disputed | 4 | 20..180 (4) |
| Determined | 3 | 186..189 (2) |
| Authoritative | 2 | 120..123 (2) |
| Deprecated | 1 | 39 (1) |
| Retired | 2 | non-step only |

---

## 2–4. Semantic normalization — the decisive classification (§3, §7, §8)

**Test applied to every term: is it a property of ONE assertion, a relation between TWO, a lifecycle
marker, a governance act, or derivable from the others?**

| Category | n | Terms |
|---|---|---|
| **STATE — irreducible unary epistemic** | **3** | **Unknown · Supported · Refuted** |
| **DERIVED** | 5 | Conflicted · Observed · Established · Inferred · Confirmed |
| **RELATION (binary)** | 4 | Superseded · Contested · Corroborated · Disputed |
| **LIFECYCLE** | 4 | Proposed · Candidate · Deprecated · Retired |
| **GOVERNANCE** | 7 | Approved · Rejected · Accepted · Validated · Verified · Authoritative · Invalidated |
| **COLLISION** | 1 | Determined |

### The decisive individual findings

- **`Superseded` is a RELATION, not a state.** It requires two assertions. **Step 218.21 states this
  itself:** `C₁ --superseded--> C₂`, **not** `delete(C₁)`. Encoding it as a state value destroys the second
  operand.
- **`Deprecated` is explicitly NOT epistemic** — step-218.22 boxes `Deprecated ≠ Falsified`.
- **`Observed` and `Inferred` differ by EVIDENCE TYPE, not epistemic state.** Both mean *Supported*; they
  record *how*. That belongs in `E`, not `Σ`.
- **`Established` = `Supported` + a corroboration threshold.** No operation in the corpus distinguishes
  them. **Derived.**
- **`Determined` is the TV-F-074 collision** — a status at steps 186–189, an *aggregate* at 196.26.

---

## 5. Minimality — Σ is THREE, not four

**I proposed a four-state set (Unknown/Supported/Refuted/Conflicted). That is one too many.**

```
Conflicted(A,K) := ∃A' ∈ K : same (subject,predicate), different object,
                              overlapping validity, same context, neither Refuted
```

**`Conflicted` is computable from the state.** No operation requires it as a *stored* primitive — I
executed this construction last cycle. **Storing it duplicates derivable information and creates an
update-anomaly risk** (the stored flag can disagree with the computed one).

> **`Σ = {Unknown, Supported, Refuted}` — three states, minimal, each irreducible.**
> `Unknown` is required by AFR-10; `Refuted ≠ Unknown` by step-009's paraconsistency; `Supported` by
> everything.

---

## 6–8. The four-state and six-state candidates, and their counterexamples

| Candidate | Verdict |
|---|---|
| **4-state** (adds `Conflicted`) | **REFUTED as minimal** — `Conflicted` is derived (§5) |
| **6-state historical** (Proposed/Supported/Established/Superseded/Contested/Invalidated) | **REFUTED** — mixes **four categories**: `Proposed` is lifecycle · `Established` is derived · `Superseded` is a **relation** · `Invalidated` is **governance**. Only `Supported` is an epistemic state, and `Contested` is a relation |
| **Two orthogonal dimensions** | **CONFIRMED — see §9** |
| **3-state core + separate dimensions** | **SURVIVES** |

---

## 9. Σ ⊥ Γ — orthogonality CONFIRMED by construction

**The mandate's §1 hypothesis is correct. All four quadrants are meaningful and occur in the domain:**

| Σ (epistemic) | Γ (governance) | Real case |
|---|---|---|
| Supported | not approved | technically sound change awaiting Architecture Board sign-off |
| Unknown | permitted | experimental hypothesis explicitly allowed for trial use |
| Refuted | still authoritative | superseded standard still binding until the migration date |
| Supported | rejected | evidence is good but policy forbids the action |

**Corpus support:** step-194.19 boxes `EpistemicStrength ≠ GovernanceStatus` · step-008 separates
*acceptance* from *commitment* · step-232.4's `Validation → Assessment`.

> **One field cannot carry both without information loss. `Γ` is REQUIRED, and it is not a verifier
> invention — the corpus states the separation and then violates it by storing governance terms in `Σ`.**

---

## 10. Operational and mathematical consequences (§10)

| Object | Consequence |
|---|---|
| **Assertion** | `A = (id, P, Σ, E, τ, Π)` with `Σ ∈ {Unknown, Supported, Refuted}` |
| **Γ governance** | separate, attached to the *governance* context — not to `A` |
| **Superseded** | a **relation** in `ℛ` or in History — **not** a value of `Σ` |
| **`Valid(K)`** | the blocking sub-predicate was *state consistency*, which needed a vocabulary. **With Σ fixed at three, it becomes computable.** |
| **Merge / Supersede** | now well-typed: supersession operates on the relation, not by mutating a status field |
| **`K_t` shape** | **relations must live somewhere** — this constrains the container (see the K-state audit) |
| **UL** | 24 terms reduce to 3 states + 4 relations + 7 governance + 4 lifecycle. **The "eleven vocabularies" dissolve** |

---

## 11. Recommended canonical model

```
Σ : Assertion → {Unknown, Supported, Refuted}          epistemic, minimal, DERIVED from Assessment
Γ : Assertion × GovernanceContext → GovernanceStatus   governance, separate dimension
ℛ ⊆ A × A                                              supersedes, contests, corroborates, disputes
Lifecycle                                              proposed/deprecated/retired — metadata, not epistemic
```

**Classification: `DERIVED`** — from corpus evidence (218.21, 218.22, 194.19, 008, AFR-10, 009) plus
executed classification and minimality tests.

---

## 12. Established vs reconstructed

| Claim | Class |
|---|---|
| `Superseded` is a relation | **CORPUS ESTABLISHES** — 218.21 |
| `Deprecated ≠ Falsified` | **CORPUS ESTABLISHES** — 218.22 |
| `EpistemicStrength ≠ GovernanceStatus` | **CORPUS ESTABLISHES** — 194.19 |
| `Unknown` irreducible | **CORPUS ESTABLISHES** — AFR-10 |
| The 24 terms partition into 5 categories | **DERIVED** (executed) |
| `Conflicted` is derived, not primitive | **DERIVED** (executed construction) |
| Σ minimal at three | **DERIVED** |
| Σ ⊥ Γ | **DERIVED** + corpus-supported |
| `Γ`'s own value set | **UNRESOLVED** — see §13 |

---

## 13. What genuinely remains normative

**Not Σ.** Σ is determined at three states by the mathematics and the corpus.

**The one residue: `Γ`'s value set** — which governance statuses the domain recognises (Approved /
Rejected / Accepted / Authoritative / Invalidated / …). **That is a governance-process question, not a
mathematical one**, and it does **not** block the formal theory: `Γ` can remain a parameter while `Σ` is
fixed, and every operation I have typed depends only on `Σ`.

> **Therefore I am NOT asking the author to decide anything yet.** `Γ`'s vocabulary becomes a live question
> only when the governance model is specified — a later dependency. **The Σ blocker is closed by derivation.**

**Downstream effect: B1 is resolved. The next load-bearing gap moves to `K_t`'s shape — and §9 has just
constrained it, because relations (`ℛ`) now provably need a home.**
