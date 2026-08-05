# PKS Phase II — M4: Identity & Lifecycle Model

| | |
|---|---|
| **Kind** | Strategic Modeling artifact (WP M4) — models identity semantics and lifecycle structure for the validated concept set. **Strategic level only: no entity design, no schemas, no numbering procedures, no tooling, no renumbering.** |
| **Authority** | Generated — never authoritative without human review. Model recommendations; the batch checkpoint disposes. |
| **Status** | **ACCEPTED WITH REFINEMENTS — part of the confirmed M2+M3+M4 strategic baseline.** |
| **Disposition History** | 2026-07-28: commissioned by the PA ("work in plan mode with DDD mindset"); work plan approved in Plan Mode with ARB reviewer endorsement (9.9/10) + two suggestions folded (identity-classification step; semantic-vs-representational distinction) + one caution (per-kind stays a working hypothesis until evaluated). Same date: execution reviewed pre-checkpoint (9.9/10, "execution approved"); one wording refinement applied — register-as-namespace held as *preferred explanatory model under the current corpus*, not definitive ontology; reviewer directed the batch checkpoint to focus on three substance questions (register-as-namespace strength · semantic/representational across all kinds · axes-explanation completeness), not execution quality. Same date: batch checkpoint executed (F-BCP-1 mapping correction · Q3 register-scope precision · Q4 carrier-identity nuance · F-BCP-3 grading requiring no text change); recommended dispositions confirmed by the PA/DA (intake record, session log 2026-07-28); checkpoint amendments folded additively (see Checkpoint Amendments section); status transitioned EXECUTION COMPLETE → ACCEPTED WITH REFINEMENTS. *(First artifact under the post-M3 documentation convention: Status carries current state only; this row carries chronology.)* |
| **Commission** | `docs/plans/20260728-1624-pks-strategic-modeling-plan.md` WP M4 · work plan `.claude/plans/shiny-hopping-nest.md` (approved) · M3-accepted baseline in force (B = working model, reversal condition active — "any contradiction satisfying the recorded reversal condition must be surfaced rather than reconciled"). |
| **Placement** | `docs/implementation/`, beside M0–M3. |

---

## Executive Summary

**Identity (OQ-PKS-1):** the per-kind working hypothesis was evaluated against the identity classification of all 17 validated kinds and is **SUPPORTED WITH ONE REFINEMENT** (Medium-High): identity scope is a per-kind property, **and the namespace unit that makes per-kind identity coherent is the governed register** — the observed collisions are *register-discipline defects* (instances minted outside any register), not scheme defects. Three identity modes emerged from classification. **Semantic identity ≠ representational identity** is made explicit; one kind (Guide step) currently has *only* representational identity — a named liability. **Lifecycle:** a three-class structure (pre-authority → authoritative → superseded-class terminal) with the four orthogonal axes preserved and the M2 evolution canon as the transition vocabulary — **no concrete vocabulary selected** (OQ-9 stays ARB-owned); the structure additionally *explains* the 8-vocabulary fragmentation: several vocabularies encode different **axes**, not different states. **OQ-CM-1..4:** all four disposed with records (resolved-kernel / boundary-routed / dormant / transformed). **M3-handed verification: PASS** — conformance rides existing identity modes; no new scheme needed.

---

## Part 1 — Identity Model (OQ-PKS-1)

### 1.1 Identity Classification (per the folded suggestion — classify before hypothesis fit)

For each validated kind: durable identity required? · why · global / scoped / inherited · intrinsic or assigned. **(Observed:** identity column evidence from M0/M1; **Derived:** the mode groupings.)

| Kind | Durable id? | Scope observed | Intrinsic/assigned | Mode |
|---|---|---|---|---|
| Decision | Yes — cited across supersession (T17 cited after T23) | Global within its **register** (ADR-T·, D-·, ADR-MP-· are distinct governed registers) | Assigned ordinal | **1 — durable-global (register-namespaced)** |
| Rule | Yes — canonical-home discipline depends on it | Global (ES/ER/EP registers) | Assigned | **1** |
| Invariant | Yes — frozen-input chains cite individual invariants | Global within carrier-register (CI/BI/INV) | Assigned | **1** |
| Work item | Yes — boards/DoD reference | Global (PB/EPIC) | Assigned | **1** |
| Contract | Yes — rows cited independently of catalog | Global row identity + orthogonal version (SchemaVersion) | Assigned | **1** (versioned variant) |
| Finding | Within its run — **run-scoping is stated design**, not accident | Run/register-scoped (≥6 F-spaces, deliberately) | Assigned | **2 — scoped-assigned** |
| Observation | Within its register/input-pack | Register-scoped (O-n) | Assigned | **2** |
| Risk | Within its register | Register-scoped (R-1..R-7) | Assigned | **2** |
| Ruling | Within the rulings register | Register-scoped (R-nn) | Assigned | **2** |
| Verdict | Per run/gate — identity via the run record | Run-scoped | Assigned (often unnamed) | **2** |
| Question | Per register/document | Register-scoped in coherent use; **drifted** (OQ- overloaded across docs) | Assigned | **2** (with drift liability) |
| Candidate | Per dossier/inbox | Scoped | Assigned/header-marked | **2** |
| Term | Yes — the **name is the identity**; change is a governed event (ADR-UL) | Global within the UL | **Intrinsic** | **3 — intrinsic/name-as-identity** |
| Model element | Named per element within its frozen model | Model-scoped | Intrinsic (name) | **3** |
| Exception record | Keyed by **what it excepts** (file+element) | Target-derived | **Intrinsic-composite** | **3** |
| Charter grant | Carrier-document identity | Per-charter | Carrier | **3** (carrier variant) |
| Guide step | Filename+ordinal only — **representational identity doubling as semantic identity** | Area-scoped | Assigned-positional | **3\*** — flagged liability, §1.3 |

*Abstraction check: classification describes observed identity behavior; no numbering scheme proposed.*

### 1.2 Hypothesis evaluation

**Working hypothesis (from the plan, held as hypothesis until here):** identity scope is a per-kind property, not a repo-wide uniform choice.

**Falsification test executed:** does any kind mix scopes incoherently *within itself*? The Decision kind is the hardest case — five numbering spaces. **Result (Observed → Derived):** ADR-T·, ADR-MP-·, and D-· are each internally coherent *governed registers* (deliberate namespacing, each with its own file, discipline, and citation form). The genuine collisions — three distinct decisions each named "ADR-004"/"ADR-005"/"ADR-001" across folders — are all instances minted **outside any governed register** (bare, unregistered series). The R-nn Risk/Ruling collision is *cross-kind*, and each register is coherent internally; the collision bites only when citations omit register context.

**Verdict: SUPPORTED WITH REFINEMENT — Medium-High.** Identity scope is per-kind, **and the register is the namespace unit** — held as **the preferred explanatory model under the current corpus, not a definitive ontology of identity** (reviewer refinement, same family as M3's methodological-preference wording: the claim is what the evidence currently best supports, and the batch checkpoint should deliberately test it). Kinds with a governed register are coherent; identity defects occur precisely where register discipline lapses. The refinement was formed *during* evaluation (post-hoc risk — recorded as T-10), which is why the verdict is Medium-High, not High.

### 1.3 Semantic vs representational identity (per the folded suggestion)

- **Semantic identity** — what makes a knowledge item *the same item* across citations, carriers, and supersession: kind + register + ordinal (mode 1/2) or governed name / target key (mode 3). **Observed:** ES-004.2's "cite durable ids, not dated filenames" is exactly this distinction stated as a rule.
- **Representational identity** — filenames, carrier documents, serializations. Never semantic identity — with one measured exception: **Guide step**, whose *only* identity is its filename+position. **Derived:** a kind with representational-only identity cannot be cited stably or survive carrier reorganization — plausibly connected to its weakest-in-corpus conformance (6/31 areas), noted as correlation, not established cause.
- **Worked evidence:** ADR-T17 retains semantic identity while superseded; the frozen Blueprint still encoding T17's position is *representational staleness*, not a semantic-identity defect — the corpus already exhibits the separation this model names.

### 1.4 Identity survival under the evolution canon *(all Observed precedents, stated as model semantics)*

| Operation | Identity behavior |
|---|---|
| Supersede | Predecessor **keeps** its identity as history; successor mints new (T23/T17) |
| Version/re-issue | Identity constant; version increments orthogonally (SchemaVersion; Blueprint v1.0→v1.1) |
| Split | Successors mint new; predecessor retains id as pointer (D-12) |
| Consolidate | Canonical home keeps id; restatements become pointers |
| Demote/void/reclassify | Identity constant; status or kind changes by recorded act (Q-3) |
| Derive/regenerate | Derived artifacts carry **no independent semantic identity** (regenerated, never hand-edited) |

**Not done (per plan):** no new numbering scheme, no renumbering, no remediation of the named collision liabilities — those are II.B/backlog. The liabilities carried forward: bare-ADR unregistered series · OQ- overload · R-nn cross-kind ambiguity · Guide-step representational-only identity.

*Abstraction check: semantics only; remediation explicitly excluded.*

## Part 2 — Lifecycle Structure Model

**(Observed inputs:** the five operating lifecycle principles · 8 declared vocabularies + ~60 ad-hoc tokens · L-4's dual-axes precedent ("record status ⊥ methodology maturity — do not conflate") · the M2 two-tier evolution canon.**)**

### 2.1 The structure *(Derived — each element cites its principle)*

**Three state classes** (structure, not vocabulary):

1. **Pre-authority** — the item exists and binds nothing (authored/generated/draft/proposed/candidate-type states). Entry: creation, by any author including AI. Exit: **only via a recorded human decision event** (completion-by-reviewer principle; "the transition happens at acceptance, unmistakably, and nowhere else").
2. **Authoritative** — the item binds (approved/adopted/frozen/accepted-type states). Frozen ≠ final: change continues via governed operations (versioned re-issue), never silently.
3. **Superseded-class terminal** — supersession-shaped, never deletion-shaped (superseded/void/retired/archived/historical-type states). Entry: explicit act with record; **no in-place revocation exists** (Observed: "authority is revocable only forward").

**Four orthogonal axes preserved:** authority ⊥ status ⊥ maturity ⊥ adoption (Observed principle: "status: approved + authority: generated is valid"). A state class locates an item on the *status* axis only.

**Transitions are the evolution canon:** the 14 operational operations map onto class boundaries — promote crosses 1→2 · supersede/void/retire/archive cross 2→3 (or 1→3 for rejected-with-record) · refine/amend operate within class 1 (and within 2 only via version/re-issue) · reclassify changes kind while identity and class hold · derive creates class-independent projections. The 3 hypothetical operations (merge · invalidation · expiry) remain outside the structure until their admission triggers fire.

### 2.2 What the structure explains *(Derived — the load-bearing finding of Part 2)*

The 8-vocabulary fragmentation is not eight competing answers to one question — **several vocabularies encode different *axes*, not different states.** AKB's classes (Immutable/Frozen/Living/Generated/Historical) mix the status axis with the *authority* axis; the knowledge-release ladder (L-6) is largely the *maturity* axis; ticket lifecycle (L-5) is a *work-item status* vocabulary; L-4 already knew this ("do not conflate"). The chaos discovery measured is what a 3-class × 4-axis space looks like when projected onto single flat vocabularies. **Consequence for OQ-9 (surfaced, not resolved):** the ARB's kind→vocabulary mapping becomes tractable — each kind needs a vocabulary *per axis it uses*, hung on the three classes. M4 supplies the structure; the mapping remains the ARB's.

*Abstraction check: structure and explanation only; no vocabulary adopted, no per-kind mapping performed.*

## Part 3 — OQ-CM Dispositions (each with its record)

| OQ | Disposition | Record |
|---|---|---|
| **CM-1** (raw vs aggregated Observation ingestion) | **RESOLVED — strategic kernel** | Both raw and aggregated captures are **Observations** (K-10); aggregation is a *derivation* producing derived Observations and, where criteria apply, Verdicts — consistent with M3's composition. Identity: mode 2 (register/run-scoped), per §1.1. Representational remainder (formats, thresholds, storage) → **routed to II.B**, recorded |
| **CM-2** (cross-product inheritance mechanics) | **RESOLVED-CONCEPT / BOUNDARY-ROUTED** | Inherited criteria enter the PKS **as criteria** — Rules/Invariants carrying upstream provenance (the observed bind-not-fork constitution already governs the boundary). The EKA↔product boundary aspects are **not decided here** — existing platform constitution + any residue to the ARB. Surfaced, routed |
| **CM-3** (automated invalidation thresholds) | **CLOSED-AS-DORMANT** | Conditioned on Invalidation, which M2 classified **hypothetical** with an admission trigger. Dormant until that trigger fires; if it does, threshold semantics enter as *criteria feeding conformance verdicts* (coherence per M3). Reactivation authority: the reviewer disposing the trigger evidence |
| **CM-4** (minimal Draft metadata vs velocity) | **CLOSED-AS-TRANSFORMED** | Presupposed the unadopted single lifecycle. Surviving strategic kernel — *what must minimally exist for an item to enter the knowledge system* — is answered by this model: **identity (per its kind's mode) + kind + state class (entering at pre-authority)**. The velocity tension stays as evidence (the EKP stall); representation of minimal metadata → **II.B**, recorded |

## Part 4 — M3-Handed Verification

**Claim to verify:** conformance assessment needs no new identity scheme (it rides Observation/Verdict identity).

**Verification against §1.1:** conformance inputs are Observations — mode 2, register/run-scoped, exactly how discovery's own conformance measurements existed (the 6/31 count lived in a sweep register). Conformance outputs are Verdicts — mode 2, run-scoped via the run record, exactly how the closure verification's verdicts exist. Criteria are Rules/Invariants — mode 1. **No conformance-specific identity mode is required; no gap found. VERIFICATION: PASS.** No reversal-condition-relevant contradiction encountered.

*Abstraction check (Parts 3–4): dispositions and verification at model level; nothing designed.*

## Surfacing Register update

**No new governance questions.** Two reinforcements: **OQ-9** — Part 2 supplies the class×axis structure that makes the ARB's kind→vocabulary mapping tractable; the mapping itself remains ARB-owned · **OQ-PKS-3** — unchanged from M3's reinforcement. OQ-2/OQ-7 again did not bite (still expected at M6, per plan prediction — three-for-three so far).

## Threats to Validity (T-1..T-9 carry; new)

| # | Threat | Mitigation | Residual |
|---|---|---|---|
| T-10 *(new)* | **Post-hoc refinement** — the register-as-namespace refinement was formed during evaluation, after seeing the ADR data | Verdict capped at Medium-High (not High); the refinement is stated as part of the hypothesis outcome, not as an independent finding | A pre-registered hypothesis run on a second corpus would be the clean test (compounds T-1/T-5) |
| T-11 *(new)* | **Explanatory seduction** — §2.2's axes-explain-the-fragmentation finding is elegant, and elegance invites overweighting | Labeled Derived; grounded in L-4's in-corpus "do not conflate" precedent rather than invented | The explanation's completeness is untested — some ad-hoc tokens may fit no axis |

## Self-Assessment

Commissioned scope executed — OQ-PKS-1 evaluated with falsification test and honest refinement ✅ · all four OQ-CM questions disposed with records (resolved/routed/dormant/transformed — nothing silently dropped) ✅ · lifecycle structure without vocabulary selection (OQ-9 intact and made more tractable) ✅ · M3 verification PASS, stated ✅ · semantic/representational distinction explicit; one representational-only kind flagged ✅ · no entity design, no schemas, no renumbering, no tooling ✅ · working hypothesis held as hypothesis until evaluated (plan caution honored) ✅ · No-False-Symmetry: the per-kind identity verdict rests on identity evidence (the register/collision analysis), not on M3's per-kind precedent ✅ · new documentation convention applied (Status = current state; Disposition History separate) ✅ · methodology observation (identity-classification-before-fit mirrored M3's checkpoint productively — second operational instance of the classify-before-fit shape) → **retrospective inbox; noted as a potential second instance for that capture's evidence bar, the retrospective decides** ✅.

---

## Checkpoint Amendments (additive; authority-confirmed 2026-07-28)

**1. F-BCP-1 — transition-mapping correction.** Checkpoint finding (quoted): *"M4's mapping sentence reads 'refine/amend operate within class 1 (and within 2 only via version/re-issue).' But M2's own canon carries the amend-additively precedent (BDR v1.1 delta — 'append-only; v1.0 table unchanged'): a within-authoritative-class change that is not a version re-issue."* **Amendment (the historical wording in §2.1 stands unedited):** authoritative-class change occurs via **version/re-issue or amend-additively (append-only delta)**; refine operates within class 1.

**2. Q3 precision — register-as-namespace scope.** Checkpoint result (quoted-in-substance): the register-necessity claim holds **for assigned-ordinal identity (modes 1–2)** — where every observed collision lives; **mode-3 intrinsic/name-based kinds cohere through governed naming or carrier-constitution instead** (Charter grant coheres registerless, by design). The preferred-explanatory-model standing and Medium-High confidence are unchanged; the scope is now explicit.

**3. Q4 nuance — carrier-linked identity, two forms.** Carrier-linked identity exists **by design** (Charter grant: the document is constitutive) and **by neglect** (Guide step: the flagged liability). The semantic ≠ representational distinction holds across all kinds with this nuance.

*(F-BCP-3 — axes explanation graded 6-of-8 — required no text change; the grading and the two named exceptions (L-7 verdict-flavored; L-8 a progress vocabulary) live in the checkpoint report as the evidence detail behind §2.2's "largely.")*

**Authority chain:** batch checkpoint recommendations (2026-07-28) → PA/DA confirmation ("yes", intake record in session log 2026-07-28) → these amendments.

---

*Traceability: executes WP M4 of `docs/plans/20260728-1624-pks-strategic-modeling-plan.md` (APPROVED) · work plan approved in Plan Mode with two folded suggestions + one caution (session log 2026-07-28) · inputs: M0 glossary identity column · M1 register (validated kinds, G-14 conformance datum) · M2 evolution canon + §10 conventions · M3 accepted baseline (composition; verification handed) · item 1 §1.1/Q5 (ES-004.2 rule, vocabularies, principles, L-4 precedent) · dossier §4a (OQ-CM-1..4). **STOP — M2+M3+M4 batch checkpoint per plan cadence; M5 opens only after its disposition.***
