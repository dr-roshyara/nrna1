# THEORY-TO-BOOK CANONICAL STATE REGISTRY

**Authority:** HPA phase-transition mandate (`prompts/20260831_0050_prompts.md`), recorded GN-74.
**Purpose:** the single controlled bridge between the theory/verification lane and the book. A
construct's presence here is **not** a licence to write about it — the "Book use" column is.
**Method:** assembled from three independent read-only extractions over the verification tree
(≈240 artifacts) plus root-B's governed record. Staging evidence:
`theory-sync/evidence-definitions-formal.md` · `theory-sync/evidence-computational-empirical.md`.
**Nothing here is adopted, ratified, or resolved.** Where artifacts conflict, both are recorded.

## 0 · The four dimensions — never collapsed
**FORMALLY CLOSED ≠ IMPLEMENTED ≠ EMPIRICALLY OBSERVED ≠ GOVERNANCE-RATIFIED ≠ BOOK ACCEPTED.**
Values used: `CLOSED` · `PARTIAL` · `OPEN` · `NOT ESTABLISHED` · `BLOCKED` · `NO ACT` ·
`GOVERNANCE DECISION REQUIRED`. **Absent values are left absent — nothing is inferred.**

## 1 · THE DECISIVE FINDING — the two lanes do not share a vocabulary

Symbol counts over root B's **governed** artifacts (v0.2 AUTHORIZED; FA-1/FA-3/FA-6 RATIFIED):

| Symbol | v0.2 | v0.1 | FA-1 | FA-3 | FA-6 |
|---|---|---|---|---|---|
| `𝒜` · `ℛ` · `Σ` · `Q_t` · `𝒪` · `Provenance` · `Replay` · `Measurement` | **0** | 0 | 0 | 0 | 0 |

The ratified model's `K_t` is **a different object** from the verification lane's `K=(𝒜,ℛ)`: it is
"state over the 8 primitives {Entity, State, Event, Observation, Proposition, Relation, Policy,
Action}", graded `TESTED (within scope)`. **Consequence for the book: the produced Part III teaches
the ratified architecture; the verification lane's constructs are not the same objects and cannot
be substituted into it.** The only construct on this registry carrying an explicit governance act
is **Policy** (GN-19 / R-1 / I-11).

Root A independently measured the same absence: "`governance-notes.md` holds 62 GN- entries to
GN-73. The **only** hit for `𝒪` anywhere in it is GN-73 line 1262 — and that is a *description of a
mandate that was HELD*, not a decision." And: "the research track is writing authority attestations
at ~18 per 2 hours while the governance ledger has not moved a single entry."

## 2 · REGISTRY — status (four dimensions kept apart)

| Construct | Canonical definition (verification lane, verbatim) | Formal | Computational | Empirical | Governance |
|---|---|---|---|---|---|
| **K** | `K = (𝒜, ℛ)` | `CLOSED` claimed — **but** `consolidation/02`: 2-tuple "SUPERSEDED — no longer the candidate anywhere"; DV-13 "variant #1 of at least seven … no canonical K_t is established by the corpus"; final audit: "**K is a representation whose referent is undeclared**" | `CLOSED`; membership O(1) executed | **L5 partial** — 37 real docs → (𝒜,ℛ), lint exit 0; but `KnowledgeState` = **0 production files** | **NO ACT.** Ratified `K_t` is a *different* object (8 primitives) |
| **𝒜** | `𝒜 = Set(Assertion)`; `Assertion=(id,P,e,c,t,Π)` | `CLOSED` | reference impl only | L5 (37 docs) | **NO ACT** — 0 occurrences in root B |
| **ℛ** | `ℛ ⊆ 𝒜×𝒜×RelationType`, 5 or 6 families | "relation vocabulary complete — **OPEN**"; 5 vs 6 families unreconciled; "ℛ 8→3 field reduction STANDING" | acyclicity O(n+m) executed | L5 — 51 typed edges; **supersession cycles unchecked** | **NO ACT** |
| **Σ** | `Σ=(D,S)`; `D=𝒫({Support,Refute})≅{0,1}²`; `S` ORDINAL | `CLOSED` for `D`; **`Σ.str` has no rule (TG-12, "falsifies 30/30")**; **TG-10 NEW: Σ blind to ℛ, reproduced in Σ₀, on no other register** | **PARTIAL** — `dir` computes, `str` FAILs | **NOT OBSERVABLE** — no epistemic-status field; `epistemic` = 0 production files | **NO ACT.** Ratified ladder is `Candidate→Supported→Accepted` + `Committed` boundary (R-3/R-4) |
| **Q_t** | "inquiry register `Q_t ⊆ P`", **outside K** | `CLOSED` (Step 281, minimality argued) | `CLOSED` — F17/F18 10/10, E4-R 7/7 | **NOT OBSERVABLE** — "EKP has no inquiry register"; "`Ask(p)` was never observed in the running system" | **NO ACT** (GN-74 states it explicitly). **ND-282-1 (`unask`) normative, unanswered.** **Name collision: corpus `K_t=(A_t,Q_t,…)` uses `Q_t` INSIDE K — unreconciled** |
| **Evidence** | 3-field `(ref,polarity,state)` **vs** 9-field amendment | **OVERLOADED — 3 live definitions**; "Evidence has no identity" (TG-08) | partial — "no validator for the 9 fields" | **NOT OBSERVABLE** — "no evidence field in the schema"; "not implemented anywhere" | **PARTIAL** — a v0.1 concept row is carried (grade TESTED, EXP-01) **but** GN-27 adjudicated its CSV "UNRELIABLE-WITHOUT-ITS-GENERATOR"; the typed 9-field object has **NO ACT** |
| **Qualification** | `Qualify : Observation × Policy ⇀ Evidence` | **THREE incompatible statuses**: "UNDEFINED [US]" · "CORPUS ESTABLISHES" · "**no body — OPEN, BLOCKING**" (TG-14) | **BLOCKED — no body** (pipeline stop 1) | not observable | **NO ACT** |
| **Provenance Π** | "intrinsic to the assertion"; **four distinct objects** | `CLOSED` — "the only closure that survives intact" (G4 VERIFIED) | field read O(1) | **SPLIT** — theory-Π not observable ("authority is trust rank, not origin"); implementation-provenance real (29 files; `MessageProvenance` 18) | **NO ACT** for the four-object split |
| **Lineage** | `Lineage = Π ∘ ℛ_der*` | `CLOSED`/FROZEN; a *different* closure given elsewhere (`ℛ_der ∪ ℛ_ref`) | reverse reachability O(n+m) | L5 — but see §5: the "47 tests" figure is contested (G-35: **4** exercise the graph) | **NO ACT** |
| **Determination** | 8-field aggregate (§157.22) | "**CORPUS-ESTABLISHED AND LOST** … part of the canonical formal theory? **NO** … **Not promoted**" | none — no `Determine` op | corpus-side only (836 occurrences/172 files); no EKP observation | **RATIFIED-BY-CARRY, NARROW SENSE ONLY** — v0.1 §1 "the `Supported→Accepted` transition under AcceptancePolicy", grade READ. **The 8-field aggregate has NO ACT** |
| **Authority** | competence, not actor; `Auth(a,r,c,p)` | **FAIL** — "3 rival relations, no body"; "authority→gate binding undefined"; TG-07 "fourth sense" OPEN | **no body** | L5 partial — "`authorities.yaml` is **an enum, not an evaluator**"; no `Authorize()` runtime | **PARTIAL** — ratified as *precondition constraint* (COMPOSITION) + I-4 (READ). **`AuthorityAct` as typed object: NO ACT** (though `Grant` exists, 132/132) |
| **Authorization** | `c_t = Authorize(N_t,a_t,Policy_t)` | `CLOSED` formal; "signature only, wrong codomain — FAIL" | **runtime ABSENT** | not observable | **NO ACT** |
| **Policy** | `(id,version,Gates,ValidityInterval,ResolutionBehavior)` | root A's 5-tuple has **NO ACT**; "What is a Policy, formally? — **BLOCKING**" | PASS *for a fully-specified policy*; `assessment` **BLOCKED** ("function defined; parameter is not") | L5 partial — knowledge-lint IS a policy evaluator (18 rules, exit 0) | ✅ **RATIFIED — the only one.** GN-19/R-1: policy-as-content vs policy-in-force, §3 loop, **I-11** (REQUIRED-BY-COHERENCE). **But see §4: GC-1 collision** |
| **𝒪 / 𝒪_core** | three non-agreeing lists: 19-op `𝒪_sem` · 6-op `𝒯_candidate` · 14-forced/18-upper | "**CLOSED as a TAXONOMY and OPEN as a KERNEL** … must NOT be frozen as-is"; minimality test "**has never been run by anyone**" | derivable-in-part; membership **NOT** derivable | n/a | **NOT RATIFIED** — 0 hits in six authority locations. "**Everything blocking reduces to ONE item: 𝒪_core is not ratified.**" Counterweight: "**D-1 IS K-INVARIANT**" |
| **Identity / equality / replay** | `id=H(P,e,c,t,Π)`; five equalities; `Replay=fold(T,∅,H)` | equality FROZEN; **TG-06 OPEN, BLOCKING — "id hashes a mutable field"; two opposite repairs exist, TG-06 "not marked closed anywhere"** | executed | L5 for identity; **replay NOT OBSERVABLE** ("git holds history; the platform does not model replay") | **NO ACT** |
| **δ / commit** | `K_{t+1}=δ(K_t,e_t) if Pre` | **TG-09 OPEN, BLOCKING** | **FAIL — executed `K₁ is K₀ : True`** (pipeline stop 2) | not observable | **NO ACT** |
| **merge** | `merge = union` | "**REFUTED as adequate**" — cannot deduplicate (TG-11) | union yes; `dedup` **does not exist** | not observed | **NO ACT** |
| **History** | `History : 𝕂 → Histories`, external to K | `CLOSED`/FROZEN; "states with different histories are EQUAL" | executed | **L1 only** — "not a platform concept" | **NO ACT** |
| **Measurement** | scale-typed Dimension, ORDINAL | "**model CLOSED / executor ABSENT**" | **no executor**; E20 BLOCKED (no (Ω,ℱ,P)) | **NOT OBSERVABLE** | **NO ACT** |
| **Uncertainty · non-identifiability · missingness** | `U(H)=(type,value,model,scope,source)`; `Identifiable(g,Ω)`; 7 missingness states | previously declared "inexpressible" — **FALSIFIED**: the corpus contains all three constructions | partial | not observable | **NO ACT** — classified by root A itself as "**a governance gap, not a mathematical one**" (constructions exist, were never promoted) |

## 3 · REGISTRY — book use (the operative column)

| Construct | May appear in book prose now? | Permitted wording strength | Blocked wording | Intended chapter |
|---|---|---|---|---|
| Ratified `K_t`, ladder, A6, DC, I-1…I-12, Zero, EC, η | ✅ **YES** — already ratified and already taught | as produced Part III teaches them, with their existing grades | any strengthening of grades | III.1–III.10 (produced, gate-passed) |
| **Policy stratification / I-11** | ✅ **YES** as ratified (GN-19) — **AND** the GC-1 collision must be visible | "the ratified model closes the loop by stratification (I-11)"; "a second, unreconciled resolution exists and is unruled" | "the policy loop is closed" (unqualified); adopting the externalisation route | III.8 (produced) + a future open-items chapter |
| `K=(𝒜,ℛ)` · 𝒜 · ℛ | ❌ **NO** — no act, and not the same object as ratified `K_t` | — | any sentence implying it is the KnowledgeOS knowledge state | GOVERNANCE DECISION REQUIRED |
| **Σ (two-bit)** | ❌ **NO** — candidate/formal only, no act | if ever ruled: "the verification lane derives Σ as 𝒫({Support,Refute}), **derived not stored**" | "Σ is the epistemic status of KnowledgeOS"; a four-valued **stored enum**; any merge with the ratified ladder | GOVERNANCE DECISION REQUIRED |
| **Q_t** | ❌ **NO** — GN-74 explicit; plus an unreconciled name collision and an unanswered `unask` question | if ever ruled: "Step 281 **reports** a formally tested missingness repair"; "**not** observed in the running EKP" | "the EKP implements an inquiry register"; "missingness is solved"; using `Q_t` without distinguishing it from the corpus's inside-K `Q_t` | GOVERNANCE DECISION REQUIRED |
| **𝒪 / 𝒪_core** | ❌ **NO — and explicitly must not be frozen** | if ever ruled: "a 14-element lower bound is forced; membership is not derivable" | "the canonical operation universe is 𝒪_core"; any frozen membership list | GOVERNANCE DECISION REQUIRED |
| Evidence (9-field) · Qualification · Authorization · Measurement · merge · δ | ❌ **NO** | — | any claim that these are defined, implemented, or observed | GOVERNANCE DECISION REQUIRED |
| **Determination** | ⚠️ **NARROW YES** — only the ratified sense (the `Supported→Accepted` transition under AcceptancePolicy, grade READ) | the ratified sense only | the 8-field aggregate; "Determination is a first-class object of the model" | III.6 (produced, ratified sense) |
| **Authority** | ⚠️ **NARROW YES** — ratified as precondition-constraint + I-4 | "authority determines commitment, not evidential truth" (I-4, READ) | "`AuthorityAct` is a typed object of the model"; "authority is bound in the implementation" | III.7 (produced) |
| Identity/equality/replay · History · Provenance (4-object) · Lineage | ❌ **NO** as theory constructs | — | any formal claim | GOVERNANCE DECISION REQUIRED |
| Uncertainty · non-identifiability · missingness | ❌ **NO** | if ever ruled: "constructions exist in the corpus and were **never promoted** — a governance gap" | "the theory cannot express missingness" (falsified) **and** "the theory expresses missingness" (not adopted) | GOVERNANCE DECISION REQUIRED |

## 4 · GOVERNANCE COLLISION — kept visible, not resolved (mandate §10)

**GC-1 / TG-21 — the policy-change loop was closed twice, incompatibly.**
- **Ratified (GN-19, 2026-08-28):** *internal stratification* — policy-as-content vs
  policy-in-force; change routed `DC + BC_Governance`; **I-11**; "self-modification of the in-force
  policy without governed approval is **excluded by construction**".
- **Verification lane (2026-08-30):** *externalisation* — "**The regress terminates OUTSIDE the
  system, at a recorded human act** … I did NOT introduce a constitutional layer … stronger and
  simpler than a constitutional meta-policy" (i.e. it explicitly rejects the shape I-11 instantiates).
- **Recorded by four independent artifacts, reconciled by none**; each declines to resolve
  ("Required action: governance reconciliation, not derivation").
- **Self-implicating consequence, in the lane's own words:** "**My Step-282 governance analysis
  therefore consumed the non-authoritative resolution** … ES-005.4 says the ratified one does
  [stand]." Elevated to P0 in the handoff backlog.
- **Status: OPEN. No governance act has ruled.** The registers' own note that "the ratified one"
  stands is a **verification-lane assertion, not a governance act**.

## 5 · CONTESTED FIGURES — must never enter book prose in any form
| Figure | Why blocked |
|---|---|
| "30/30 symbols resolve" | withdrawn at 20:52 ("irreconcilable with the same programme's 14/16"), **re-asserted** at 00:28. Unreconciled |
| "47 tests" (Lineage) | G-35: a `--filter` name-match over 18 unrelated classes; **4** exercise the provenance graph; "weight ≈12× overstated across 14 artifacts" |
| "20/20" vs "132/132" `humanActRef` | the larger figure carries the **weaker** claim: "a discipline, not a binding" (free-text field) |
| `Σ ⊥ Γ` "three independent routes" | one route is a **tautology** (`evidence_volume` never read) — **WITHDRAWN as evidence**; two remain |
| `status ⊥ authority` independence | **measured collinear**, 13/13 `draft ⇔ provisional`; "a *design* property … not an observed property" |
| Any single gap count | **seven live framings** (53+16 · 21 · 9 · 8 · 8-reconciled · 11-standing · 12-residual), three ID namespaces, **no crosswalk**; one register's header contradicts its own table |
| Any single closure verdict | see §6 |

## 6 · CLOSURE — the lane does not speak with one voice
| Artifact (mtime) | Verdict |
|---|---|
| `THEORY-CLOSURE-AUDIT` (08-30 20:10) | "THEORY CLOSED AGAINST THE STATED CRITERIA" — with its own caveat: "a completion verdict reached in the same pass that discovered the closures deserves independent re-verification" |
| `INDEPENDENT-CLOSURE-REVERIFICATION` (20:41) | "**THE CLOSURE IS NOT SUSTAINED — 0 of 6 gaps VERIFIED as claimed**" |
| `THEORY-STATUS-VERDICT` (20:52) | **8 senses — 3 RED, 5 PARTIAL**; "THE THEORY IS NOT CLOSED" |
| `independent/14` (21:19) | "the prior 'closed' hypothesis is **FALSIFIED**" |
| `step-282/12` (08-31 00:28) | "**VERDICT B — THEORY THEORETICALLY CLOSED AT DECLARED SCOPE**"; "theory-critical gaps: ZERO" |
| `handoff/06` (00:46, newest) | "Verdict B stands … **a weaker starting position than the synthesis assumed**" |

**No artifact reconciles Verdict B with the eight-verdict and independent-verdict results; no GN act
ranks them.** **All chains agree on: `EC = FALSE` (empirical) and `GC = NOT CLAIMED` (governance).**
**Permitted book wording:** *"the verification lane's artifacts disagree about closure; they agree
that empirical and governance closure are not claimed."* **Blocked:** any sentence stating that the
theory is, or is not, closed.

## 7 · OQ DEPENDENCIES — no OQ moves (mandate §2, GN-31/D-FA-7)
**No verification artifact claims to close any OQ** (mechanically checked: 0 hits). OQ-1…12 remain
open *by ruling*. Results that would **bear on** an OQ if adopted — dependencies only:
OQ-1 (η-totality "REFUTED as a mathematical claim"; "a category mismatch as posed") ·
OQ-2 (𝒪_core taxonomy/kernel split; not ratified) · OQ-3 (no-scalar result independently
confirmed; `AggregateSupport`/`IndependenceFactor` refuted) · OQ-4 (`δ` no body; no `Authorize()`
runtime) · OQ-5 (the lane executed **its own** suites, **not** EG-05 — EG-05 still outstanding) ·
OQ-6 (`Ω` ≥4 senses — TG-15) · OQ-7 (Lord/Sārathi reversal unadjudicated) · OQ-10 (ten schema
vocabulary files ungoverned) · OQ-12 (`~` and resolver N undefined; I-5/I-6 hold at pipeline level
only). **OQ-9: zero verification-lane hits. OQ-8: no result on the Ātma-Kernel.**

## 8 · ITEMS REQUIRING A HUMAN GOVERNANCE RULING BEFORE ANY BOOK USE
1. **GC-1 / TG-21** — which termination of the policy loop stands.
2. **𝒪_core ratification** — not ratified; Steps 273–280 are built on it; four options on the table.
3. **Self-attested authority strings in the research track** — "Authority: HPA" ×13, "HPA Ruling"
   ×9, "RATIFIED" ×2, "against zero corresponding entries in the governance ledger for steps
   272–280". Root A's own words: "**If those attestations are not yours … that needs stopping on
   its own account.**"
4. **`THEORY-STATUS-VERDICT` §5's normative question** — adopt `(W,Ω)`/`D_t`/`U(H)`? "**Adoption
   would reverse a standing governance ruling.**"
5. **`independent/14` §6's normative question** — do the dropped corpus constructions go back in?
   "**Restoring any of the five reopens a ratified artifact.**"
6. **Q_t's status** + ND-282-1 (`unask`) + the inside-K/outside-K name collision.
7. **Which closure verdict is the record** (§6).
8. **Which gap register is the register** (§5).
9. **The structural conflict** (GN-74): proposed 12-chapter Part III / new Part IV vs ratified BA-1
   10-chapter Part III, already produced and method-gate PASSED (GN-53).

## 9 · MAINTENANCE
This registry is updated **only** through the THEORY-TO-BOOK SYNCHRONIZATION GATE (GN-72): a new
verification result enters as a *recorded status*, never as an adopted claim; a status changes only
on a citable governance act; and the "Book use" column changes only after that act exists. A
report's existence is never evidence for its contents.
