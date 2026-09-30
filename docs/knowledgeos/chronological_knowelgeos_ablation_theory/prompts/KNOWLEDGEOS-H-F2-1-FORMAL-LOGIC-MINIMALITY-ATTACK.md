# KNOWLEDGEOS — H-F2-1 FORMAL LOGIC & MINIMALITY ATTACK

| | |
|---|---|
| **Kind** | a mathematical, logical and model-theoretic attack on the analysis-level `[E]` proposal H-F2-1 (`prompts/KNOWLEDGEOS-F2-ACCELERATION-PASS.md`, `34844193b`). ⚠ authority: generated |
| **Commission** | human, 2026-09-26: "KNOWLEDGEOS — H-F2-1 FORMAL LOGIC & MINIMALITY ATTACK", plus *"Continue to work for next step as senior researcher and mathematician, statistician and ddd architect and expert of computer logic and theory of logic."* |
| **Instrument** | `analysis/h_f2_1/model.py` (sha256 `ed931840…0510f`), output `analysis/h_f2_1/results.json` (sha256 `f7b75848…8660`). Deterministic, standard library only, no corpus input; ≈ 20 s runtime. Reproduce with `python3 model.py > results.json` |
| **Corpus** | ⛔ none read. F0018 not re-read. No Phase-2 record, no release request |
| **Status language** | *"Exhaustive finite-state verification for the declared finite instances"* (§4). Never "proved by computer" |

**Correction of wording adopted from the review.** H-F2-1 is **an `[E]`-derived F2 candidate formalization, built from reconstructed corpus objects, pending source-fidelity and external falsification.** It is not "a genuine F2 KnowledgeOS hypothesis" in the sense of an established corpus claim. The F2 Acceleration Pass overstated this.

---

## 1. Formal system

**Sets:**
- P = {generated, derived};
- S = {authoritative, provisional, historical};
- (E, ≤) a finite poset;
- G = {0, 1}, the grant flag (an abstraction of the append-only act log);
- 𝒰 ⊆ 2^E, the family of admissible bars.

**State:** x = (p, s, e, g, u) ∈ **X = P × S × E × G × 2^E**.

The bar u is carried **in the state**. The F2 Acceleration Pass had it as a fixed parameter, which made its constancy an *unstated* assumption. Carrying it in the state is what lets bar constancy (A6) be tested (§2, H-9).

**Atomic steps:** x →_κ x′ with κ ∈ {GOV, EVID, EVIDREF, WORK, COMP}.
- EVIDREF is a refutation step, split out explicitly (hidden assumption H-5).
- **T = T_G ∪ T_E ∪ T_R ∪ T_W ∪ T_C.**

**Predicate:** Promote(x) ⟺ e ∈ u ∧ g = 1.

**Axioms (atomic-step constraints):**

| # | Formal statement |
|---|---|
| A0 | 𝒰 = Up(E, ≤) (bars are up-sets) |
| A1 | ∀ steps: p′ = p |
| A2e | κ = GOV ⇒ e′ = e |
| A3g | κ ∈ {EVID, EVIDREF} ⇒ g′ = g |
| A3s | κ ∈ {EVID, EVIDREF} ⇒ s′ = s |
| A3m | κ = EVID ⇒ e ≤ e′; κ = EVIDREF ⇒ e′ ≤ e |
| A4 | no step has κ = COMP (composites are sequences of atomic steps) |
| A5e, A5g, A5s | κ = WORK ⇒ e′ = e, g′ = g, s′ = s |
| **A6** | **∀ steps: u′ = u** (the bar is constant) |

The F2 pass's A3 splits into A3g/A3s/A3m, and its A5 into A5e/A5g/A5s, so that each can be ablated separately.

**Propositions:**

| # | Statement (for every u ∈ 𝒰) |
|---|---|
| **D1** | from e ∉ u, no GOV-only sequence reaches e ∈ u |
| **D2** | from g = 0, no {EVID, EVIDREF, WORK}-only sequence reaches g = 1 |
| **D3** | every trajectory from (e ∉ u, g = 0) to Promote contains ≥ 1 evidential step (EVID or EVIDREF) **and** ≥ 1 GOV step |
| **D3+** | … and the evidential steps include ≥ 1 **non-refutation** step (the standing was *earned*) |
| **D5** *(new; §9)* | Promote is stable under EVID steps |
| **D6** | p is constant along every trajectory |
| **D4** | (static) no injective map P × S → P ⊔ S |
| **NV** *(non-vacuity)* | some trajectory from (e ∉ u, g = 0) reaches Promote |

---

## 2. Provenance of every axiom, and hidden assumptions

| Axiom | Source basis | Origin | Empirically testable? |
|---|---|---|---|
| A0 | none. It was introduced to remove the undefined bar (OQ-9) | **[E]** | yes: are the corpus's bars (ES-006.1 `n ≥ 2`, P-4, CAP-001) upward-closed? |
| A1 | T-0014 *"Nothing is a transition in P"*; T-0056 *"PROVENANCE … does not progress"* | [S] (the reconstruction's logical_form over [C] prose) | yes (F-A1) |
| A2e | T-0013 *"no ruling makes an observation Replicated"*; T-0056 GOVERNANCE-ACT | [S] | yes (F-A2) |
| A3g | T-0013 *"no evidence makes a document frozen"* | [S] | yes |
| A3s | T-0013 (same), for standing | [S] | yes, but **irrelevant to every proposition** (§6) |
| A3m | T-0056 / F0018 def *"reversible only by refutation"* | [S] + **[E]** (reading refutation as a *decrease-only* step, H-5) | yes |
| A4 | T-0056 COMPOSITE *"crossing the above"*; F0018 *"four kinds of change … wearing ten mechanisms"* | **[E] reading** of "crossing" as *sequence* rather than atomic combination | yes (F-A4) |
| A5e/g/s | T-0056 WORK-EXECUTION *"moved by time and effort; has a clock"* | **[E]** (silence read as locality) | yes (F-A5) |
| **A6** | **none. Found by this attack** (H-9) | **[E]** | **yes, and pointed:** the corpus's bar is itself governance canon (ES-006.1), so a canon amendment *is* a governance act that changes u |

**Hidden assumptions:**

| # | Assumption | Treatment |
|---|---|---|
| H-1 | each mechanism step has exactly one kind | **formalized:** one κ per atomic step. A mechanism with two kinds must be split into steps, or it is a COMP step (A4) |
| H-2 | composites are not atomic transactions | **formalized** as A4; [E] |
| H-3 | the "grant" is a flag, not a count | **formalized:** G = {0, 1}. Revocation is permitted *only* by GOV (A3g, A5g), so D2 and D3 are unaffected |
| H-4 | grants are item-local (an act on k′ does not grant k) | **[E] labelled; not modelled** (single item) |
| H-5 | refutation is a decrease-only evidential step | **formalized:** EVIDREF + A3m; [E] |
| H-6 | promotion is a **state predicate**, not a GOV event | **formalized** as Promote(x). ⚠ If the corpus's "grant" *is* the promotion act, D3's GOV-part becomes definitional, while its evidential part stays substantive. **[O]** |
| H-7 | steps are interleaved; there are no simultaneous steps | **formalized** (a trajectory is a sequence); true concurrency collapses into A4 |
| H-8 | (E, ≤) is a chain | **removed:** tested on non-total posets (§8) |
| **H-9** | **the bar does not change during a trajectory** | **formalized as A6.** It turns out to be **necessary** for D1, D3, D3+ and D5 (§6) |
| H-10 | promotion does not depend on p or s | **formalized** (Promote uses only e, g, u), so standing and provenance are dynamically inert (§6, §11) |

---

## 3. Finite-state model

Instances (independently verified by `model.py`; |X| = 2 · 3 · |E| · 2 · 2^|E|):

| Instance | E | Order | |X| | Why it is included |
|---|---|---|---|---|
| **chain3** | {n0, n1, n2} | n0 < n1 < n2 (the recorded P-10 chain) | **288** | the recorded example |
| **V** | {⊥, a, b} | ⊥ < a, ⊥ < b; a ∥ b | 288 | a non-total order |
| **diamond** | {⊥, a, b, ⊤} | ⊥ < a, b < ⊤; a ∥ b | 768 | incomparable middle elements |
| **antichain2** | {a, b} | no strict order | 96 | the degenerate control |

**The F2 pass's "36 states" is corrected.** 36 = |P| · |S| · |E| · |G| counts the state *without* the bar. With the bar made a state component, as H-9 requires, chain3 has 288 states. The 36-state count remains correct for the A6-fixed sub-model with one bar.

**Method:**
- For each axiom set, construct the **maximal** step relation permitted, over all kinds and all target states.
- Check every proposition by breadth-first search over (state, flags). This also yields the shortest countermodel.

**Soundness of the method:**
- every proposition is universal over trajectories;
- every axiom only removes steps or bars;
- so every proposition is **monotone** in the axiom set, and the maximal relation is the hardest case.

---

## 4. D1–D6 verification (full axiom set)

| Instance | D1 | D2 | D3 | D3+ | D5 | D6 | NV |
|---|---|---|---|---|---|---|---|
| chain3 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| V | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| diamond | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| antichain2 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | **✗ (vacuous)** |

**Status:** exhaustive finite-state verification for the declared finite instances.

**Non-vacuity witness (chain3):** `(e=n0, g=0, U={n2}) –GOV→ (g=1) –EVID→ (e=n2, g=1)`, i.e. Promote.

**The antichain is the control.** With no comparable pairs, A3m freezes e, promotion is unreachable, and **every** proposition holds vacuously. **Non-vacuity is therefore a required condition of any verified claim**: (E, ≤) must contain a comparable pair across the bar.

**Proof ideas, general for any finite poset (by hand):**
- **D1:** GOV steps fix e (A2e) and u (A6), so e ∉ u persists.
- **D2:** only GOV and COMP can change g (A3g, A5g), and COMP is excluded (A4).
- **D3:** a trajectory must change g from 0 to 1, so by D2's argument it contains a GOV step. It must also move e into u with u fixed (A6); only evidential steps change e (A2e, A5e, A4).
- **D3+:** if u is an up-set (A0) and EVIDREF only decreases e (A3m), a refutation step cannot enter u from outside: e′ ≤ e and e′ ∈ u would imply e ∈ u. So the step entering u is a non-refutation EVID step.
- **D5:** e ≤ e′ (A3m), u is an up-set (A0), g′ = g (A3g) and u′ = u (A6).
- **D6:** by induction on A1.

**D4:** |P × S| = 6 > |P ⊔ S| = 5. Of the 5⁶ = 15,625 maps, **0** are injective (enumerated). Pigeonhole.

---

## 5. Counterexample search (chain3; the shortest countermodels from `results.json`)

| Removed | Breaks | Countermodel |
|---|---|---|
| **A6** | D1 | `(e=n0, U={}) –GOV→ (e=n0, U={n0})`: **governance moves the bar onto the item** |
| **A6** | D3 | `(e=n0, g=0, U={}) –GOV→ (e=n0, g=1, U={n0})`: **promotion with no evidential step** |
| A2e | D1 | `(e=n0, U={n2}) –GOV→ (e=n2)`: a ruling changes evidential standing |
| A4 | D3 | `(e=n0, g=0, U={n2}) –COMP→ (e=n2, g=1)`: one atomic step does both |
| A5e | D3 | `… –GOV→ (g=1) –WORK→ (e=n2)`: time or effort alone raises evidence |
| A3g | D2 | `(g=0) –EVID→ (g=1)`: evidence alone grants |
| A5g | D2 | `(g=0) –WORK→ (g=1)`: work alone grants |
| **A0** | D3+ | `(e=n1, g=0, U={n0}) –GOV→ (g=1) –EVIDREF→ (e=n0)`: **promotion by refutation** under a non-monotone bar |
| **A0** | D5 | `(e=n0, g=1, U={n0}) –EVID→ (e=n1)`: **more evidence un-promotes** under a non-monotone bar |
| A3m | D3+ | `… –EVIDREF→ (e=n2)`: a "refutation" that raises standing |
| A3m | D5 | `(e=n1, g=1, U={n1, n2}) –EVID→ (e=n0)`: an unmarked decrease |
| A1 | D6 | `(p=gen) –GOV→ (p=der)`: provenance rewritten |

**Countermodels over the full axiom set: none**, in all four instances.

---

## 6. Axiom-ablation matrix

Identical for chain3, V and diamond. ✗ marks the proposition that fails when the axiom is removed.

| Removed | D1 | D2 | D3 | D3+ | D5 | D6 |
|---|---|---|---|---|---|---|
| A0 | ✓ | ✓ | ✓ | ✗ | ✗ | ✓ |
| A1 | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ |
| A2e | ✗ | ✓ | ✗ | ✗ | ✓ | ✓ |
| A3g | ✓ | ✗ | ✗ | ✗ | ✗ | ✓ |
| **A3s** | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| A3m | ✓ | ✓ | ✓ | ✗ | ✗ | ✓ |
| A4 | ✓ | ✓ | ✗ | ✗ | ✓ | ✓ |
| A5e | ✓ | ✓ | ✗ | ✗ | ✓ | ✓ |
| A5g | ✓ | ✗ | ✗ | ✗ | ✓ | ✓ |
| **A5s** | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| **A6** | ✗ | ✓ | ✗ | ✗ | ✗ | ✓ |

**Minimal axiom sets.** Each is unique: the set of single-removal-necessary axioms also suffices, verified by re-running on that set alone.

| Proposition | Minimal axiom set |
|---|---|
| **D1** | **{A2e, A6}** |
| **D2** | **{A3g, A5g}** |
| **D3** | **{A2e, A3g, A4, A5e, A5g, A6}** |
| **D3+** | D3's set ∪ **{A0, A3m}** |
| **D5** | **{A0, A3g, A3m, A6}** |
| **D6** | **{A1}** |

**Axioms needed by no proposition:** **A3s, A5s** (standing locality), and **A1 outside D6**.

On antichain2 the minimal-set computation fails as expected (the necessary set for D3 does not suffice), because the full set is vacuous. This confirms the non-vacuity requirement (§4).

---

## 7. (Folded into §2)

The hidden-assumption search is in §2, H-1…H-10. Its three consequential findings are:
- **H-9 / A6:** bar constancy is necessary;
- **H-6:** state vs event reading of promotion;
- **H-10:** standing and provenance are dynamically inert.

---

## 8. Partial-order test

**D1–D6 hold with identical minimal sets on chain3, V and diamond.**
- **D1, D2, D3 need no order at all.** Their minimal sets contain neither A0 nor A3m. They hold for **arbitrary** bar sets u ⊆ E and any evidential dynamics.
- **D3+ and D5 need** (E, ≤) to be a poset, u an up-set and evidential steps monotone. **A chain is not required** (V and diamond pass).
- **Limitation:** non-vacuity requires at least one comparable pair across the bar.

---

## 9. A0 monotonicity attack

- **A0 is not needed for D1–D3.** The core claim *"promotion requires both evidence and governance"* holds for **every** bar, monotone or not. The F2 pass's move of "removing `bar` by quantifying over up-sets" was **stronger than necessary**. For D3, `bar` needs no assumption at all.
- **A0 is necessary, with A3m, for exactly two propositions:**
  - **D3+ (earned promotion):** without A0, an item can be promoted **by refutation** (countermodel §5);
  - **D5 (stability):** without A0, **accumulating evidence can un-promote** an item.
- **Consequence:** A0 is **removed from H-F2-1's core** (D1–D3) and retained only in the **stability extension** (D3+, D5). There it becomes a sharp empirical question with a pathology as its falsifier: *does the corpus ever describe promotion by refutation, or demotion by additional evidence?*

---

## 10. D3 non-tautology analysis

```text
A2e ─┐                       (GOV keeps e)
A5e ─┤ only EVID changes e ──┐
A4  ─┤ (no atomic COMP)      ├──▶ D3: promotion ⇒ ∃ EVID ∧ ∃ GOV
A3g ─┤ only GOV changes g ───┤
A5g ─┘                       │
A6  ─── u fixed ─────────────┘
```

| Question | Answer |
|---|---|
| Is D3 equivalent to any single axiom? | **no.** Each of its six axioms is individually necessary (§6), so D3 is not a restatement of one of them |
| Is D3 deep? | **no.** It follows by a two-step frame argument from locality constraints. Its **scientific content lies in the locality axioms**, which are empirical claims about mechanisms. The derivation's value is that it **makes the corpus slogan's dependencies explicit and minimal**. *"Evidence earns; governance grants; promotion requires both"* is true in exactly those systems that satisfy these six locality constraints |
| Does D3 hold under H-6's event reading? | the GOV part becomes definitional; the evidential part remains a consequence of {A2e, A5e, A4, A6} |

---

## 11. D4 conditional analysis

**Conditional statement:**

> **IF** (i) authority's intended semantics is the product P × S, **AND** (ii) it is represented by exactly one field, **AND** (iii) that field's range is P ⊔ S (the 5 recorded values), **THEN** no representation is injective. Some (p, s) pairs, including T-0014's own *derived × authoritative*, are unrepresentable.

- **Premise (i)** is T-0014's [S] reading.
- **Premises (ii) and (iii)** need the schema, which is `not_recoverable_without_reread` (F0018 record).
- **Live alternative:** T-0014's source terms include `attestation`, *"a transition in S"*. If the schema stores standing through attestation records, (ii) fails and D4 does not apply. **[O], needs test T-A.**
- **Weight:** a schema-adequacy condition. D4 does **not** show that KnowledgeOS needs two coordinates. Only corpus records of *co-occurring* (p, s) combinations would show that.
- **Also from §6:** standing (S) plays **no role** in D1–D5, and provenance (P) only in D6. The dynamic theory (T-0013 + T-0056) and the authority decomposition (T-0014) are **logically separable**:
  - **H-F2-1a (dynamics):** D1–D3, D3+, D5;
  - **H-F2-1b (authority representation):** D4, D6.

  They should be recorded and tested as **two** candidates, not one.

---

## 12. DDD interpretation (no bounded context, no aggregate)

| Coordinate | Interpretation | Kind |
|---|---|---|
| p | origin fact fixed at creation (value-object-like, immutable) | source concept (T-0014, T-0056 "PROVENANCE") |
| s | trust state changed by acts | source concept (T-0014) |
| e | evidential position that is earned | source concept (T-0013 "evidential standing") |
| g | grant presence | **[E] analytical coordinate.** ⚠ **Possibly the same concept as s** (a grant may *be* the act that sets standing). If g ≡ [s = authoritative], then A3s/A5s replace A3g/A5g in every minimal set, symmetrically. **[O], an identification question for the source** |
| u | the bar in force | **[E] analytical coordinate.** In the source, the bar is governance *canon* (ES-006.1), i.e. a **rule**, not an item state. A6 formalizes a two-level distinction (object-level acts vs meta-level rule changes) that the source has not been shown to draw |

**The three architectures stay distinct:**
- the **source** architecture (ES-006.1 canon, the schemas);
- the **reconstructed** architecture (T-0013, T-0014, T-0056);
- this **expert formal model** (X, T, A0–A6).

---

## 13. ML boundary

- **Not used, and not useful here:** the problem is finite, symbolic and exhaustively decidable.
- **Later role** (after a corpus-read release), as a **candidate generator only**, frozen before reading. It would flag text that may describe:
  - an A2e violation (a ruling changing evidential standing);
  - an **A6 violation** (a bar or canon change applied to pending items);
  - an A5 violation (expiry-driven changes);
  - a D3+/D5 pathology (promotion by refutation; demotion by more evidence).
- Every flag is verified by human complete-file reading (MP §27).

---

## 14. Final classification

> ## **A — `F2-FORMAL-CANDIDATE`**, for the **revised** statement H-F2-1-R below, with **low deductive depth** stated explicitly.

**Why A, and not D or B or C:**

| Criterion | Evidence |
|---|---|
| complete formal statement | §1 |
| explicit assumptions | A0–A6 plus H-1…H-10, each labelled [S] or [E] (§2) |
| explicit falsifiers | §5 gives a concrete violating step for each necessary axiom |
| internally coherent and non-vacuous | no countermodel under the full set on four instances, and a promotion witness exists (§4) |
| not tautological | D3 needs six independent axioms, none equivalent to D3 (§10) |
| **not C** | no internal contradiction |
| **not B** | no undefined term remains in the core. H-6 and the g/s identification are **interpretation** questions, recorded [O] |

**The honest qualification:** the deductive layer is shallow. The candidate's scientific weight rests on its **empirical locality axioms**. It is an F2 candidate because it is well-formed and falsifiable, not because it is deep.

**H-F2-1-R (the revised candidate):**

| Part | Content |
|---|---|
| **Core, H-F2-1a** | {A2e, A3g, A4, A5e, A5g, **A6**} ⇒ **D1, D2, D3**, for **any** bar set. A0 is removed from the core |
| **Stability extension** | + {A0, A3m} ⇒ **D3+, D5** |
| **Separate candidate, H-F2-1b** | {A1} ⇒ D6 (tautological); the D4 conditional (§11) |
| **Dropped as inert** | A3s, A5s (unless g ≡ s is adopted, in which case they replace A3g/A5g) |
| **Required condition** | non-vacuity: a comparable pair across the bar |

---

## 15. Exact conditions for requesting the next research release

**Listed, not requested (commission §16):**
1. **Human review** of this attack and of the revision H-F2-1-R (core / stability / H-F2-1b split; A0 demoted; A6 added).
2. **An independent re-check of the model.** A verifier re-implements `model.py` from §1 alone and reproduces §4–§6. This run is **SELF**.
3. **Decisions recorded as open** (not taken here):
   - H-6 (promotion as state vs event);
   - the g ≡ s identification;
   - A6's two-level reading (object-level acts vs rule changes).
4. **A pre-registered expected-findings list** for test T-A, for each of:
   - F-A2e;
   - F-A3g;
   - F-A4;
   - F-A5e/g;
   - **F-A6** (*a bar or canon change affecting pending items*);
   - the D3+/D5 pathologies;
   - D4 premises (ii)/(iii) (the attestation alternative).
5. **Then the release requests** (human decisions, per the F2 pass §11):
   - recording H-F2-1a and H-F2-1b as Phase-2 `[E]` records;
   - a corpus-read release for T-A (fidelity, F0018 and the schemas);
   - later, corpus access for T-B (out-of-sample mechanisms; C-7 for chronological progression).

---

**H-F2-1 FORMAL LOGIC & MINIMALITY ATTACK COMPLETE — NO CORPUS READ, NO C-M EXECUTION, NO NEW ARCHITECTURE, NO GOVERNANCE DECISION.**

---

## Correction note (2026-09-26, appended; original text above left unchanged)

The state counts in the instance table and in the text (*"chain3 has 288 states"*) are the **enumerated** space: all bars, before admissibility.

**Admissible states under the full axiom set (A0 in force: up-set bars only):**

| Instance | Admissible (with A0) | Enumerated (without A0) |
|---|---|---|
| chain3 | 144 | 288 |
| V | 180 | 288 |
| diamond | 288 | 768 |
| antichain2 | 96 | 96 |

- Found by the fresh verifier (`analysis/h_f2_1_verifier/`).
- **No truth value, ablation result or minimal set changes**, because `model.py` filters admissibility at start states and (v1.1) at successor states.
- See `prompts/KNOWLEDGEOS-H-F2-1-R-SCIENTIFIC-CLOSURE-PASS.md` §2.3.

---

## Declarative note: operation typing (2026-09-26, appended; no proof, axiom, minimal set or instrument changed)

**H-1′ (typing totality).** D3 and D3+ are asserted for trajectories every step of which has an established kind.
- A source-described operation whose kind cannot be established without its effect is **UNKNOWN**. This is an epistemic status of the evidence record, **not** a sixth kind and not a formal state; there is no three-valued logic.
- A step constrained by no kind-specific axiom is exactly the −A4 case of §6. That row loses D3 and D3+ only, on chain3, V and diamond.
- Therefore D1, D2, D5 and D6 do not depend on H-1′.

**Kind-free axioms.** A0 is a state constraint; A1 and A6 constrain every step. Their tests (F-A6, F-A0) need no typing.

**Evidence of no formal change** (re-run 2026-09-26):
- `model.py` reproduces `results.json` byte-identically (sha256 `75b58856…`);
- `verifier.py` reproduces `verifier_results.json` byte-identically (sha256 `372cf3e7…`).

**Source:** `prompts/KNOWLEDGEOS-T-A-OPERATION-TYPING-METHODOLOGY-REVIEW.md` §B; T-A pre-registration r3 §3.0 (i).
