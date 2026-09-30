# H-F2-1-R — SCIENTIFIC CLOSURE PASS (verification · soundness audit · T-A pre-registration · release preparation)

| | |
|---|---|
| **Kind** | analysis report. ⚠ authority: generated. **No decision is recorded here.** |
| **Commission** | human, 2026-09-26: "H-F2-1-R SCIENTIFIC CLOSURE PASS" (deliverables A–D), plus *"Continue … as senior researcher, mathematician, statistician, DDD architect and expert of computer logic … also use your expertise in machine learning techniques. Keep optimizing the final goal, architecture and todos."* |
| **Candidate** | H-F2-1-R (`prompts/KNOWLEDGEOS-H-F2-1-FORMAL-LOGIC-MINIMALITY-ATTACK.md` §14). **Not altered by this pass** |
| **Corpus reads** | ⛔ **none.** F0018 not read; schemas not read; T-A and T-B not performed; C-M not executed |
| **ORIGIN / tags** | [F] fact · [D] derived · [C] computed on instances · [A] assumed · [O] open |

---

## 0. STOP-condition check (done first)

| STOP condition | Triggered? | Evidence |
|---|---|---|
| material verifier disagreement | **No** | §2: every truth value, every single-removal result and every minimal set agree. The only difference is a **state-count convention**, and there the verifier is right and my earlier document is wrong (§2.3) |
| unsound minimality argument | **No, with stated conditions** | §3: the argument is sound under conditions C-1…C-5. Outside those conditions it is not claimed |
| undefined core term | **No** | every term used by the propositions is defined in `analysis/h_f2_1_verifier/SPEC.md`. The verifier's ten interpretation choices are recorded in §2.4; none changes a result |
| T-A not operationalizable | **No** | `prompts/KNOWLEDGEOS-H-F2-1-R-T-A-PREREGISTRATION.md` has operational outcome definitions (§3.1), an extraction rule (§4) and fixed decision rules (§5). It depends on material whose release is a human decision (§5 below) — that is a precondition, not a failure of operationalization |

**The pass proceeds; no patching was needed.**

---

## 1. Deliverable A — the verifier

| | |
|---|---|
| Spec | `analysis/h_f2_1_verifier/SPEC.md` (sha256 `44c40fd4…`, `bc4556376`). **Results-free**: it states what to check, never the answer |
| Implementation | `analysis/h_f2_1_verifier/verifier.py` (sha256 `54d32100…`). Written from SPEC.md only, by a separate agent session |
| Output | `analysis/h_f2_1_verifier/verifier_results.json` (sha256 `372cf3e7…`) |
| Files read by the verifier | SPEC.md only (recorded in its `files_read` field) |
| Method | explicit enumeration of the state space; the **maximal step relation** R_max(Σ) as bitsets; every proposition decided by reachability (a fixed point over a finite graph); **all 2^11 axiom subsets** evaluated for minimality. It does **not** assume uniqueness or monotonicity: it checks upward closure as an output |
| Independence class | **SECONDARY_REVIEW, not INDEPENDENT.** Same model family, same session owner. It is a *fresh implementation*, not an *independent reviewer* in the ARCH sense. An INDEPENDENT re-run (another person, or another model family) remains open (HD-4) |

---

## 2. Deliverable B — verification report

### 2.1 Mechanical comparison (run 2026-09-26)

The comparison script reads both JSON files and compares:
- every full-axiom truth value (7 props × 4 instances);
- every single-removal truth value (11 axioms × 7 props × 4 instances);
- every minimal set.

**Result: `AGREEMENT (truth values, ablation, minimal sets): True`.** [C]

| Instance | Full set: D1 D2 D3 D3+ D5 D6 | NV (full) | Minimal sets (verifier = mine) |
|---|---|---|---|
| chain3 | all hold | true | D1 {A2e, A6} · D2 {A3g, A5g} · D3 {A2e, A3g, A4, A5e, A5g, A6} · D3+ = D3 ∪ {A0, A3m} · D5 {A0, A3g, A3m, A6} · D6 {A1} — **each unique** |
| V | all hold | true | identical to chain3 |
| diamond | all hold | true | identical to chain3 |
| antichain2 (control) | all hold | true | as above **except D3**: two minimal sets (§2.2) |

The −A6 countermodel for D1 is also identical in both: `U={} –GOV→ U=E` (a governance step that moves the bar). Under the verifier's tie-break it is the unique shortest witness.

### 2.2 The antichain: non-uniqueness, as predicted

On antichain2 the verifier finds two inclusion-minimal sets for D3:
- {A2e, **A3m**, A4, A5e, A6}
- {A2e, **A3g**, A4, A5e, **A5g**, A6}

My instrument reported *"necessary set {A2e, A4, A5e, A6} does not suffice"*. That is the same fact seen from the other side.

**Why it happens [D]:**
- On an antichain, A3m forces every evidential step to keep e (e ≤ e′ with no strict pairs means e′ = e).
- With A6 in force, the evidence position then can never move. Promotion from e ∉ u becomes unreachable, and D3 holds **vacuously**.
- The verifier's count of axiom subsets under which NV holds confirms it: 1984/2048 on antichain2 against 2048/2048 on the three non-degenerate instances.

**Consequence:** the antichain is a **degenerate control**. Minimality is only meaningful where NV holds. This is recorded as condition C-4 below.

### 2.3 Correction: the state count

| Instance | Admissible states **with A0** (up-set bars) | States **without A0** (all bars) | What the attack document said |
|---|---|---|---|
| chain3 | **144** | 288 | 288 |
| V | **180** | 288 | 288 |
| diamond | **288** | 768 | (768) |
| antichain2 | 96 | 96 | 96 |

- **[F] Correction:** the attack document's table (§ "Instances", the "288 states" column and the sentence "chain3 has 288 states") reports the **enumerated** space, bars unrestricted.
- **Under the full axiom set, including A0, chain3 has 144 admissible states.**
- `model.py` enumerates all bars and filters admissibility at start states and, since v1.1, at successor states. **Its decisions are therefore unaffected.** Only the printed count is the enumeration size, not the admissible size.
- A correction note has been appended to the attack document; the original text is left in place.

### 2.4 Interpretation choices (verifier) — none changes a result

| # | Verifier's reading | Same as mine? | Would the alternative change a result? |
|---|---|---|---|
| I-1 | COMP is neither GOV nor evidential for D3/D3+ | yes | no: with A4 in force there are no COMP steps. Without A4, the other reading only makes D3 *easier* to satisfy, and A4 would then be less necessary. Declared as a modelling choice |
| I-2 | "reaches Promote" = at any point of the trajectory | yes (BFS stops at first hit) | no: prefixes are trajectories |
| I-3 | "from any state" = all admissible states, not only reachable ones | yes | no: this is the stronger (universal) reading |
| I-4 | D6 ≡ no single step changes p | yes | no: exact by induction |
| I-5 | NV is excluded from minimality | yes | n/a (existential) |

---

## 3. Audit of *"Checking the maximal permitted transition relation is exact"*

The claim appears in `analysis/h_f2_1/model.py` (docstring) and the attack document. It is audited here per proposition.

### 3.1 The argument

Let Σ be an axiom set. R_max(Σ) is the set of all atomic steps x →κ x′ between admissible states that satisfy every axiom in Σ.

**Lemma 1 (reduction to R_max) [D, proved].** Suppose a proposition φ has the form *"no trajectory (or step) of R has property Bad"*, and suppose the steps of R are constrained by nothing except Σ.
- Then *for every R ⊆ R_max(Σ), φ(R)* ⟺ *φ(R_max(Σ))*.
- **Proof.**
  - (⇒) R_max is itself such an R.
  - (⇐) A bad trajectory of R is a trajectory of R_max, because R ⊆ R_max.
  - ∎

**Lemma 2 (monotonicity) [D, proved].** If Σ ⊆ Σ′, then
- the admissible states under Σ′ are a subset of those under Σ (only A0 restricts states, and it only removes them);
- R_max(Σ′) ⊆ R_max(Σ).

So a proposition of Lemma 1's form that holds under Σ holds under Σ′.
- **Proof.** Every axiom is a conjunct restricting a single step, or the state space. Adding conjuncts removes steps and states. Removing starts, states or steps removes trajectories. ∎

**Lemma 3 (uniqueness of the minimal set) [D, proved].** Let Σ_full make φ true, and let N = {a : φ fails under Σ_full ∖ {a}}. Then:
- every sufficient set T contains N;
- if N is itself sufficient, it is the **unique** inclusion-minimal sufficient set.

**Proof.**
- Suppose T is sufficient and a ∈ N but a ∉ T. Then T ⊆ Σ_full ∖ {a}.
- By Lemma 2, φ holds under Σ_full ∖ {a}. That contradicts a ∈ N.
- So N ⊆ T for every sufficient T.
- If N suffices, any minimal T satisfies N ⊆ T, and minimality then gives T = N. ∎

**The verifier checked all this independently.** It tested upward closure over all 2^11 subsets (`upward_closed_check`: true for D1–D6 on all four instances). That is the computational confirmation of Lemma 2. [C]

### 3.2 Per proposition

| Prop | Form | Lemma 1 applies? | Status of "R_max is exact" |
|---|---|---|---|
| D1 | no GOV-only path from {e ∉ u} to {e ∈ u} | yes (universal, trajectory) | **proved** |
| D2 | no {EVID, EVIDREF, WORK}-path from {g = 0} to {g = 1} | yes | **proved** |
| D3 | no path from {e ∉ u, g = 0} to Promote lacking an evidential **or** a GOV step | yes. "Lacking a class" is a property of the trajectory; the verifier decides it as reachability in the two sub-relations without that class, which is equivalent | **proved** |
| D3+ | as D3 with "EVID" | yes | **proved** |
| D5 | no EVID step from a Promote state to a non-Promote state | yes (universal, single step) | **proved** |
| D6 | no step changes p | yes | **proved** |
| NV | **there exists** R and a trajectory reaching Promote | **no — existential.** Decided instead by: NV ⟺ Promote reachable in R_max. A witness R is the set of that trajectory's steps, which satisfies Σ because each step does | **proved**, by a different argument. It is **anti-monotone**, so it is excluded from minimality |

### 3.3 Conditions under which the claim is sound (C-1…C-5)

| # | Condition | Holds here? | If violated |
|---|---|---|---|
| C-1 | every axiom is **step-local** (a constraint on one step) or a **state restriction** | yes, by construction (SPEC.md) | a global axiom (e.g. "every trajectory eventually…", fairness, determinism of R) breaks Lemma 1 for existential props and can break Lemma 2 |
| C-2 | R is otherwise **unconstrained** (any subset of R_max is a legitimate R) | yes (SPEC.md: *"A step not restricted by an axiom in force may change any component"*) | if R must be e.g. total or serial, NV's witness construction must be redone |
| C-3 | the propositions are **universal safety properties** (D1–D6) | yes | liveness-type propositions ("promotion is eventually reached") are **not** covered |
| C-4 | minimality is read only on instances where **NV holds** | yes for chain3, V, diamond; **no** for antichain2 under some Σ (§2.2) | vacuous truth yields spurious minimal sets |
| C-5 | results are **relative to the axiom granularity** (the 11 named axioms) | stated | a different decomposition (e.g. splitting A3m into EVID and EVIDREF halves, or merging A5e and A5g) can give different minimal sets. "Minimal" means **minimal among these 11**, not "logically weakest" |

### 3.4 Proven · computed for instances · assumed

| Statement | Class |
|---|---|
| Lemmas 1–3 | **proved** [D] for any finite state space satisfying C-1…C-3 |
| **Sufficiency** of each minimal set, for **arbitrary** posets (E, ≤) | **proved** by short hand arguments (§3.5) — [D] |
| **Necessity** of each axiom in each minimal set | **computed** [C] on chain3, V, diamond (shortest countermodels in both implementations). For arbitrary posets **with at least one strict pair a < b**: a **proof sketch** by embedding the countermodel (§3.5). Not mechanically checked beyond the three instances |
| **Uniqueness** of the minimal sets | **computed** [C] on the three instances. It follows from Lemma 3 wherever necessity and sufficiency hold |
| That the 11 axioms **faithfully describe** the recorded mechanisms (F0018, ES-006, schemas) | **assumed** [A]. This is exactly what **T-A** tests. Formal closure says nothing about it |
| That the state components (p, s, e, g, u) are the **right abstraction** (H-6: promotion as state vs event; g ≡ s) | **assumed** [A] / open [O] |

### 3.5 Hand arguments (arbitrary finite poset)

**Sufficiency [D]:**
- **D1 {A2e, A6}:** GOV keeps e (A2e) and the bar (A6), so e ∉ u is invariant under GOV.
- **D2 {A3g, A5g}:** in D2's kinds only EVID, EVIDREF and WORK occur, and all of them keep g.
- **D6 {A1}:** immediate.
- **D5 {A0, A3g, A3m, A6}:**
  - Promote(x) means e ∈ u and g = 1.
  - EVID gives e ≤ e′ (A3m); u is an up-set (A0) and fixed (A6), so e′ ∈ u.
  - g′ = g (A3g). So Promote(x′).
- **D3 {A2e, A3g, A4, A5e, A5g, A6}:** start from e ∉ u, g = 0, with u fixed (A6).
  - Reaching g = 1 needs a step that may change g. That is only GOV, because A3g, A5g and A4 rule the others out.
  - Reaching e ∈ u needs a step that may change e. That is only EVID or EVIDREF, because A2e, A5e and A4 rule the others out.
- **D3+ = D3 ∪ {A0, A3m}:** in addition, an EVIDREF step gives e′ ≤ e (A3m). If e′ ∈ u, then e ∈ u because u is an up-set (A0). So a refutation step never enters u from outside, and entry needs an EVID step.

**Necessity sketch (posets with a < b) [D, sketch]:** each countermodel in `results.json` / `verifier_results.json` uses at most two comparable elements and the two extreme bars ∅ / E, or the up-set ↑b. Those exist in any poset with a strict pair. For example:
- **−A0 for D5:** the bar {a}, which is not an up-set when a < b, plus EVID a → b.
- **−A6 for D1:** GOV ∅ → E.

A full mechanical check of the embedding is **not** done. It is registered as optional todo R-4 (§6).

---

## 4. Deliverable C — the T-A pre-registration

**File:** `prompts/KNOWLEDGEOS-H-F2-1-R-T-A-PREREGISTRATION.md` (`bc4556376`, sha256 `bb26614f…`). It was written **before any source reading**, and it is **not frozen**.

It meets the commissioned requirements:

| Requirement | Where |
|---|---|
| six falsifiers | §3: F-A2e, F-A3g, F-A4, F-A5e/g, **F-A6 (primary, partially out-of-sample)**, F-A0/A3m. Plus the open questions Q-H6, Q-GS, Q-D4 |
| four outcome classes | §3.1: DIRECT COUNTEREXAMPLE · SUPPORT · AMBIGUOUS · UNADDRESSED, with operational definitions |
| declared expectations | §3.2 (F-A6 predicted AMBIGUOUS or COUNTEREXAMPLE) |
| fixed decision rules | §5: counts N = S + C + A + U; no probabilities; one DIRECT COUNTEREXAMPLE refutes the axiom *for that mechanism* |
| no corpus reading | header and §7 |

**One addition from this pass (to fold into the pre-registration at HD-1, not silently now):**
- by Lemma 3, a refuted axiom that lies **outside** a proposition's minimal set leaves that proposition's proof intact;
- for example, a counterexample to A3s or A5s leaves D1–D6 intact, because standing is inert;
- a counterexample to **A6** removes the proof of **D1, D3, D3+ and D5** for the recorded mechanism.

⚠ **Precision.** Losing an axiom makes a proposition **unproved** (UNSUPPORTED), not false. It becomes **falsified** only if the source also exhibits the countermodel pattern. For −A6 that pattern is a bar change that brings a pending item across the bar without an evidential step. T-A records both levels separately:
- the axiom verdict;
- whether the countermodel pattern itself occurs.

The pre-registration's §5 should list this **axiom → affected propositions** map explicitly. It is proposed here, for the human to accept at freeze.

| Refuted axiom | Propositions whose proof is lost (for that mechanism) |
|---|---|
| A2e | D1, D3, D3+ |
| A3g | D2, D3, D3+, D5 |
| A4 | D3, D3+ |
| A5e | D3, D3+ |
| A5g | D2, D3, D3+ |
| A6 | D1, D3, D3+, D5 |
| A0 | D3+, D5 |
| A3m | D3+, D5 |
| A1 | D6 |
| A3s, A5s | **none** (inert) |

[C] on chain3/V/diamond: this is the single-removal matrix, with both implementations agreeing.

---

## 5. Deliverable D — research-release preparation (listed, **not requested**)

**Material:** pre-registration §2 (M-1…M-5) with the §2.1 version rule. Minimum: **M-1 + M-4** for H-F2-1a; add **M-2 + M-3** for H-F2-1b.

**Prerequisites, in order:**
1. **HD-1** — the human reviews and **freezes** the pre-registration, including the §4 map above. It is committed frozen **before** any release.
2. **HD-2** — a corpus-read release for M-1: a re-READ of canonical F0018 under S2 §4.3b. Optionally M-5.
3. **HD-3** — a corpus-scope decision (S2 OQ-6) for M-2…M-4, which lie outside the canonical list, with the version rule.
4. **HD-4** — the reader independence class, and whether an INDEPENDENT re-run of the verifier is required before T-A.

**Governance facts that bound this [F]:**
- L0-DEC-30's scope is spent. CAP-01 has already run.
- Any new corpus read needs a new L0 release, through the existing Research Release Check (L0-DEC-27).
- **Claude does not request it.**

---

## 6. Optimized goal, architecture and todos (ML where it helps)

### 6.1 Goal (unchanged in substance, sharpened)

Establish, **with evidence stronger than the claim**, a small set of **locality laws of knowledge promotion**. The laws say which operations can change evidence, grant and bar, and that promotion needs both an evidential step and a governance act. Each law must be:
- (i) formally closed — **now done, QUALIFIED**;
- (ii) faithful to its source mechanisms — **T-A**;
- (iii) corroborated out-of-sample — **T-B**.

### 6.2 Architecture of the research line (no new bounded context)

```
Formal layer (DONE, QUALIFIED)      Fidelity layer (T-A)            Corroboration layer (T-B)
  SPEC.md ─► model.py + verifier.py    frozen pre-reg ─► release ─►     frozen pre-reg ─► release ─►
  Lemmas 1–3 · hand proofs §3.5        human/second reader extracts     R1/R2/R3 candidate retrieval
  minimal sets · axiom→prop map        per-mechanism outcome (§3.1)     ─► adjudication ─► counts
                    └───────── axiom→prop map (§4) turns each axiom verdict into proposition verdicts ─────┘
```

### 6.3 Where machine learning helps, and where it must not

| Place | ML role | Why |
|---|---|---|
| formal proof (Lemmas, minimal sets) | **none** | exact decision procedures exist. ML adds error and no information |
| T-A extraction | **none** at decision level. Optionally a structured-extraction assistant, whose output is always adjudicated | T-A is a fidelity test on ≤ 5 files. Human or second-reader extraction is feasible and required for authority |
| **T-B candidate discovery** | **yes**, per pre-registration §6: R1 BM25 with **axiom-derived queries** (one query family per axiom and its falsifier); R2 dense embeddings; R3 a classifier trained **only on adjudicated labels** | the corpus is too large for exhaustive reading. Retrieval raises recall of counterexample candidates |
| T-B evaluation | measure **retrieval recall** (on a seeded, adjudicated audit sample) and **review cost** separately. Report a lower confidence bound on recall | a falsification search that misses counterexamples silently is the main threat. The recall bound quantifies it |
| **active learning** (new, proposed) | after the first adjudicated batch, rank the remaining candidates by classifier uncertainty × axiom-coverage deficit | cuts review cost without changing the decision rule. Pre-register it before T-B, never mid-run |

**Invariant:** ML output is **never** evidence. Only adjudicated records are.

### 6.4 Todos (optimized; Claude-side items prepare evidence only)

| # | Todo | Owner | Gate |
|---|---|---|---|
| R-1 | fold the §4 axiom → proposition map and the C-4 "NV must hold" rule into the pre-registration **at freeze** | human decides (HD-1); Claude drafts on request | HD-1 |
| R-2 | INDEPENDENT re-run of SPEC.md (another model family or a person) | human | HD-4 |
| R-3 | T-A execution | released reader | HD-1…HD-3 |
| R-4 | *(optional)* mechanical check of the embedding argument over all posets with ≤ 5 elements | Claude, if asked | none (no corpus) |
| R-5 | T-B pre-registration, including the active-learning rule and the recall bound | Claude drafts after T-A outcome | T-A done |
| R-6 | resolve H-6 and g ≡ s (they decide whether H-F2-1b is testable) | human / governance | — |

---

## 7. Re-issue conformance check (2026-09-26, second issue of the same prompt, with a senior assessment)

The prompt was re-issued unchanged, with a senior assessment attached. Deliverables A–D already existed (`5e59de97b`), so they were **checked clause by clause, not redone**.

| Clause | Status | Where / action |
|---|---|---|
| §1 status wording; a/extension/b split kept separate | met | report header; §6.1 |
| §2 fresh verifier; do not copy or inspect `model.py` | met, with one deviation | The verifier was built from `SPEC.md`, a **results-free extraction** of the attack document's formal system, not from the attack document itself. The attack document contains the results (minimal sets, countermodels); reading it would have exposed the verifier to the answers. This is stricter than the clause and is recorded as a deliberate deviation |
| §2 "not independent validation" | met | §1: SECONDARY_REVIEW |
| §3 proof vs computation vs assumption; monotonicity per proposition, per ablation and per minimality | met | §3.1–§3.4 (Lemmas 1–3, C-1…C-5) |
| §4 six falsifiers | met | pre-registration §3 |
| §5 four outcomes; non-inference | met | pre-registration §3.1 |
| §6 extraction fields | met; **r1** adds `countermodel_effect_stated` and `discovery_channel` | pre-registration §4 |
| §7 A6 object-level vs meta-level | met | pre-registration §3 (F-A6), §4 reading rule, §5 two-level consequence |
| §8 H-6 open; §9 g ≡ s open with the four searches; §10 D4 conditional | met | pre-registration Q-H6, Q-GS, Q-D4 |
| §11 counts only, no probabilities | met | pre-registration §5 |
| §12 ML discovery only; freeze config; record model / version / query / parameters | met; **r1** adds LLM-assisted retrieval (R4) and the recording rule | pre-registration §6 |
| §13 no DDD artifacts | met | none created |
| §15 STOP conditions | none triggered | §0. The object- vs meta-level distinction is **not** a new theory concept: it is required by the commission (§7) and was already recorded as A6's two-level reading (attack §2) |

**Pre-registration → REVIEW CANDIDATE r1** (sha256 `194beacb933ce2dafe5e355807ebe018e24a4c06e8161c3a5934c9c8d0c30ecd`):
- all changes are additive and listed in its §9;
- no question, prediction or outcome definition changed.

The senior assessment asks for Claude to *"freeze"* it before human review. **Claude cannot freeze a pre-registration**; freezing is part of HD-1. The equivalent within Claude's authority is done instead: the content is final and a hash is recorded, so HD-1 reviews an exact object.

**Agreement with the senior assessment:**
- Formal expansion stops here. R-4 (the embedding check) stays **optional**, and is not recommended ahead of T-A.
- A6 is the primary question.
- No inferential statistics.
- ML only for discovery.
- No DDD artifacts.

**One sharpening added (§5.1 of the pre-registration):**
- an axiom counterexample makes the dependent propositions **UNSUPPORTED**, not **FALSIFIED**;
- the difference is whether the source states the countermodel **effect**;
- without this rule, T-A would overstate its refutations.

## LOGICAL STATUS

**QUALIFIED.**
- D1, D2, D3, D3+, D5 and D6 are **proved** under their minimal axiom sets for **arbitrary finite posets**, by hand argument (§3.5).
- The reduction to the maximal transition relation, monotonicity and uniqueness of the minimal sets are **proved** under conditions C-1…C-5 (§3.3).
- **Necessity** (hence minimality) is **computed** on chain3, V and diamond, and only **sketched** for general posets with a strict pair.
- Everything is **relative to the 11-axiom granularity**.

## COMPUTATIONAL STATUS

**REPRODUCED** by a secondary review. A fresh verifier built from the results-free SPEC.md agrees on every truth value, every single-removal result and every minimal set (§2.1).
- It confirmed the antichain non-uniqueness.
- It corrected the state count: 144/180/288 admissible under A0, not 288/288/768.
- Independence class: **SECONDARY_REVIEW**. An INDEPENDENT re-run is open.

## EMPIRICAL STATUS

**UNTESTED.** No source has been read. The axioms' fidelity to F0018, ES-006 and the schemas is **assumed**, and it is exactly what T-A tests.

## H-F2-1a STATUS

**F2-FORMAL-CANDIDATE**: formally closed (QUALIFIED) and computationally reproduced, but **EMPIRICALLY UNVALIDATED**. Its critical unexamined assumption is **A6** (bar constancy), the primary falsifier F-A6.

## H-F2-1b STATUS

**CONDITIONAL.**
- D4 (pigeonhole 6 > 5) is a valid counting fact.
- Whether it bears on the recorded authority model depends on premises in M-2/M-3, which are not read, and on the open questions H-6 and g ≡ s.
- No formal or empirical claim beyond the count is made.

## NEXT HUMAN DECISION

**HD-1: review and freeze the T-A pre-registration, REVIEW CANDIDATE r1** (sha256 `194beacb…0ecd`). The §4 map and the NV rule are already folded in as the additive §5.1 (see its §9); HD-1 accepts or rejects them. Then, in order:
- **HD-2:** a corpus-read release for M-1 (re-READ of F0018);
- **HD-3:** a corpus-scope decision (S2 OQ-6) for M-2…M-4, with the version rule;
- **HD-4:** the reader independence class, and whether an INDEPENDENT verifier re-run must precede T-A.

All go through the existing Research Release Check (L0-DEC-27). **None is requested by Claude.**

## NO-GO ITEMS

- No reading of F0018, the schemas, ES-006, or any corpus file, until an L0 release exists.
- No T-A, no T-B, no C-M execution.
- No release request by Claude. No APPROVED-FOR-EXECUTION, HUMAN-DECISION record or decision switch written by Claude.
- No change to the Master Protocol, Research Architecture v1.2 or F-Series architecture. No new bounded context.
- No statement that H-F2-1 / H-F2-1-R is **validated**, **established** or **canonical**.
- No silent change to H-F2-1-R or to the unfrozen pre-registration. The §4 map is a **proposal for HD-1**.
- No ML output treated as evidence, and no ML in the formal layer.
- No minimality claim beyond the 11-axiom granularity, and no minimality read on instances where NV fails.
- No agreement between the verifier and `model.py` presented as INDEPENDENT review.

---

**H-F2-1-R SCIENTIFIC CLOSURE PASS COMPLETE — NO CORPUS READ, NO RELEASE REQUESTED, NO DECISION RECORDED, H-F2-1-R UNCHANGED.**

---

## Correction note (appended during the R-2 comparison, F-LOG-0046; original text above left unchanged)

The §2.1 table row *"antichain2 (control) | all hold | **true** | …"* is a **transcription error in this report**.
- The NV (full axiom set) value for antichain2 is **false**.
- This is the case in `analysis/h_f2_1/results.json` (`NONVACUOUS: false`), in `analysis/h_f2_1_verifier/verifier_results.json` (`NV: false`), in the attack document (§4: "✗ (vacuous)"), and in the independent R-2 result.
- §2.2 of this report already explains why: on the antichain, promotion is unreachable under the full axiom set.
- The data and every conclusion are unaffected; only the table cell was wrong.
