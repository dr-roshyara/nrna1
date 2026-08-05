# PKS Phase II — Controlled Change Plan (CCP-1)

| | |
|---|---|
| **Kind** | **Change-propagation design.** Answers *what exactly must change, in what order, under whose authority* — where AIA-1 answered *what becomes authorized*. **Designs the change set; executes none of it.** |
| **Authority** | Generated — never authoritative without human review. **No artifact is edited, no correction applied, nothing promoted, no methodology touched, no strategic knowledge reinterpreted.** |
| **Status** | **EXECUTED — an outcome-conditional plan. 16 change items · 5 work packages · 8 exit criteria. STOP.** |
| **Commission** | Controlled Change Plan Commission CCP-1 (PA, 2026-07-28) — *"Authority authorizes change; architects design the propagation; editors implement only pre-planned changes."* |
| **Inputs (only these)** | **AFV-1 · AIA-1 · AD-1 · Strategic Model (M0–M8) · DAR-1.** No future edits, corrected artifacts, or promotion decisions consulted. |
| **Placement** | `docs/implementation/`, ahead of any editorial act. |
| **Post-execution evidence (added 2026-07-30; the plan itself is NOT corrected)** | **CCP-R1, disposed ACCEPT:** this plan's claim that *"no item requires an editor to choose, compose, or interpret"* was **falsified in five places by the execution it designed** — C-06 supplied a *form* rather than a wording · C-12's provenance sentence was unfinished · C-13 did not specify the omission **count** its new row invalidated · C-19..C-21 were given **no history row** (§11.6 covers C-09/C-15/C-16 only) · and no item required **retention of superseded wordings**. **Each produced a downstream defect; PD-F1 was Major and promotion-blocking.** **All five are remediated in the artifacts they affected. This plan is not amended** — its record stands as the execution evidence. **Diagnosis: the plan achieved LEVEL 1 (textual) determinism and did not guarantee LEVEL 2 (operational) determinism — *editors never infer obligations that are not explicitly specified*. CI-1 completely protects Level 1; Level 2 was never its subject.** **The general lesson is PMR-7, ROUTED TO MCA — not adopted here.** *Record: `PKS_Phase_II_CCP1_Controlled_Change_Integrity_Review.md`.* |

**Governing principle:** *every modification must carry **Authority · traceability · minimal scope · a defined owner**. Nothing propagates implicitly.*

---

## 0. A sequencing note on this commission itself (recorded, not resolved)

**The commission's sequence diagram places CCP-1 *after* the AFV-F4 disposition; its own status table records AFV-F4 as still pending, and its input list contains no disposition record — because none exists.** The PA's directing prose is unambiguous the other way: *"I would introduce a new commission **before any edits**."*

**Resolution adopted, and it produces the more useful artifact:** this plan is **outcome-conditional**. Every change item is classified by *which disposition outcome brings it into existence*, so the plan is executable the moment the disposition is issued — under **any** of the three outcomes — without a further planning act. **Derived:** planning conditionally is strictly stronger than planning after the fact, because AIA-1 established that the three outcomes are asymmetric; a plan written after ACCEPT would silently omit the REJECT branch's obligations.

**No disposition is assumed, implied, or recommended here.** *(AIA-1 §11 carries the advisory; this commission carries none.)*

---

## 1. Executive Summary

**The lawful change set is 16 items across 4 artifacts, executable as 5 bounded packages, of which one package is conditional on the disposition outcome and one may proceed immediately.**

| | |
|---|---|
| **Total change items** | **16** — 12 mandatory · 3 conditional (outcome-dependent) · 1 conditional-on-REJECT |
| **Artifacts changed** | **AD-1 · C4-1 · M7** (three), plus one Authority record under REJECT |
| **Artifacts explicitly NOT changed** | **KBI-1 · C4-2 · AFV-1 · AIA-1 · M0–M6 · M8 · DAR-1 · MCA · CDR · Checkpoint · RET-1 · the PMR register** (§5.2 states why for each) |
| **May proceed immediately** | **Package E** — M7's three DAR-1 folds, wholly independent of the disposition |
| **Blocked until disposition** | Packages A, C, D |
| **Recommended to wait despite being unblocked** | **Package B** (§4.3) — so AD-1 is amended exactly once |

**Derived — the plan's two load-bearing ordering rules, neither of which an editor should have to invent:**

1. **AD-1 before C4-1, always.** C4-1 renders AD-1; editing a representation before its source re-introduces the drift the representation verification just removed. **The transformation direction dictates the edit direction.**
2. **AD-1 amended exactly once.** Its mandatory and conditional edits are merged into a single amendment act (§4.3), which also means one Disposition History row rather than two and one consistency check rather than two.

**Derived — the constraint that makes this plan safe to hand to an editor:** **no change item requires editor-authored substance.** Every one of the 16 items has its replacement wording already supplied by a finding record or by the strategic model itself (§7.1). Where wording is *not* pre-supplied, the item is not in this plan.

---

## 2. Scope

**In scope:** the change inventory · classification by necessity and outcome-dependency · the dependency graph and its ordering rules · the propagation matrix · bounded work packages with preconditions, owners, prohibitions, and exit checks · configuration-integrity rules · objective exit criteria · execution constraints.

**Out of scope, stated explicitly:** **taking the disposition** (Authority's; AIA-1 §11 holds the advisory) · **executing any change** · deciding OQ-PKS-7 · re-verifying any finding's merits (settled at AFV-1, C4-2, KBI-1) · promotion · methodology.

---

## 3. Change Inventory

*Classification: **Mandatory** = required by a recorded finding independent of the disposition · **Conditional** = brought into existence by a specific disposition outcome · **Sequence-bound** = mandatory but constrained in *when* it may be applied.*

### 3.1 AD-1 (Architecture Definition)

| # | Change | Source finding | Class | Wording pre-supplied by |
|---|---|---|---|---|
| **C-01** | Qualify AR-1's placement with the OQ-PKS-7 contingency | AFV-F4 | **Conditional — ACCEPT only** | **M6 §9's impact statement** (verbatim-in-substance) |
| **C-02** | Restate the issuance constraint as *placement-not-determined*; correct AP-1's second clause | AFV-F1 | **Mandatory** | AFV-1 §6.1's recommended disposition |
| **C-03** | Narrow DR-1 to authority, **or** add the L4-8 derivation for the evidence clause | AFV-F2 | **Mandatory** | AFV-1 §7.1 (both options stated) |
| **C-04** | Qualify AP-3's status as undetermined, in the pattern AP-4 already uses | AFV-F3 | **Mandatory** | AFV-1 §8.1; AP-4 as the internal model |
| **C-05** | Correct AR-1's label to the governed name, or mark it an architectural label for the CBC-3 seam | AFV-F5 | **Mandatory · SEQUENCE-BOUND to Package A** | AFV-1 §11 (AIA-F3 sets the sequencing) |
| **C-06** | Add a namespace declaration for AC-/AR-/SB-/IB-/AP-/DR- | KBI-F2 | **Mandatory · CONSTRAINED** | The PMR register's declaration as the precedent form. **Must not characterize AR-1's placement** |
| **C-07** | Reconcile the input list with the traceability matrix re: RET-1 | KBI-F3 | **Mandatory** | KBI-1 §5 |
| **C-08** | Record the issuance-placement question as an added open question | AFV-F1 (second limb) | **Mandatory** | AFV-1 §6.1. **The only item that *adds* content rather than correcting it** — see §7.2 |
| **C-09** | Disposition History row recording this amendment | Program practice (L1-7 forward-only) | **Mandatory, derived** | The rows already present on AD-1, M8, C4-1 |

### 3.2 C4-1 (Architecture Views)

| # | Change | Source | Class | Wording pre-supplied by |
|---|---|---|---|---|
| **C-10** | Reconcile the three out-of-input-set labels — recommended remedy: widen C4-1's input declaration to the artifacts AD-1 itself cites as origins | VR-1 | **Mandatory** | C4-2 §9 (both options stated; recommendation given) |
| **C-11** | Restore *"never a **Verdict**"* in the Knowledge Structure View | VR-2 | **Mandatory — MUST NOT BE SKIPPED** | C4-2 §5.1, verbatim |
| **C-12** | Add a §7 provenance row for the AR-negation list (source: the C4-1 commission text) | VR-3 | **Mandatory** | C4-2 §9 |
| **C-13** | Add a KO-10 row recording DR-2's intentional non-rendering | VR-4 | **Mandatory** | C4-2 §9 |
| **C-14** | Annotate AR-1's placement with the contingency, so the views assert no more than AD-1 does | AIA-F2 | **Conditional — ACCEPT only; consequent of C-01** | C-01's applied wording |
| **C-15** | Disposition History row | Program practice | **Mandatory, derived** | — |

### 3.3 M7 (Strategic Relationship Model)

| # | Change | Source | Class |
|---|---|---|---|
| **C-16** | Apply the three authorized DAR-1 editorial folds (R-1's withdrawn pattern name · R-3b's constraint restatement · R-4's withdrawn pattern name), plus a Disposition History row | DAR-1 §§5–7; KBI-F4 | **Mandatory · INDEPENDENT — may proceed immediately** |

### 3.4 Conditional on REJECT only

| # | Change | Source | Class |
|---|---|---|---|
| **C-17** | An Authority record stating that the one-corpus reading is adopted architecturally, with an impact statement and ARB notice | AIA-F1 | **Conditional — REJECT only.** **Not an editorial item** — an Authority act, listed because it is part of the lawful change set under that branch |

### 3.5 Outcome dependency, consolidated

| Disposition outcome | Items in force |
|---|---|
| **ACCEPT** | C-01 … C-16 (all sixteen) |
| **REJECT** | C-02 … C-13, C-15, C-16 **+ C-17**; **C-01 and C-14 void** (nothing to qualify) |
| **DEFER** | C-02, C-03, C-04, C-06, C-07, C-08, C-09 **(AD-1 partial only)** · C-10 … C-13, C-15 · C-16. **C-01, C-05, C-14 held**; **AD-1 remains *Governance Review Required*; the baseline is not promotable** |

**Derived:** **C-05 (AR-1's label) is held under DEFER** — the one item whose availability changes across all three branches, because it is sequence-bound to the contested element.

---

## 4. Dependency Graph

### 4.1 The graph

```
                    ┌──────────────────────────────────────┐
  (independent) ──▶ │  PACKAGE E — M7 (C-16)               │ ──▶ may run at any time
                    └──────────────────────────────────────┘

  AFV-F4 DISPOSITION  (Authority act — not in this plan)
            │
            ▼
  ┌──────────────────────────────────────────────────────────┐
  │  PACKAGE A + PACKAGE B — AD-1, ONE amendment act          │
  │  A: C-01 (ACCEPT only) · C-05 (sequence-bound)             │
  │  B: C-02 · C-03 · C-04 · C-06 · C-07 · C-08   (+ C-09)     │
  └────────────────────────┬─────────────────────────────────┘
                           │  ORDERING RULE 1: source before representation
                           ▼
  ┌──────────────────────────────────────────────────────────┐
  │  PACKAGE C — C4-1, ONE amendment act                      │
  │  C-10 · C-11 · C-12 · C-13 · C-14 (ACCEPT only) (+ C-15)  │
  └────────────────────────┬─────────────────────────────────┘
                           ▼
  ┌──────────────────────────────────────────────────────────┐
  │  PACKAGE D — Promotion Readiness Verification (no edits)  │
  └──────────────────────────────────────────────────────────┘
```

### 4.2 Ordering Rule 1 — AD-1 before C4-1 (mandatory, derived)

**C4-1 is a rendering of AD-1.** Editing the representation before its source would (i) risk re-introducing the drift C4-2 just removed, and (ii) make C-14 unverifiable, since it must annotate what C-01 actually says. **The transformation direction dictates the edit direction.** This is not a preference; reversing it breaks the fidelity relation the two artifacts stand in.

### 4.3 Ordering Rule 2 — AD-1 amended exactly once (recommended, with the trade-off stated)

Package B is **not blocked** by the disposition (AIA-1 §8 authorized all six items). Executing it early is therefore *lawful*. **It is nonetheless recommended to wait**, for three reasons: AR-1 would otherwise be edited twice (AIA-F3 argues against this for the label; the same logic applies to the artifact); two Disposition History rows would record what is one logical amendment; and the internal-consistency check would run twice.

**The trade-off, stated rather than hidden:** waiting costs nothing downstream — **no artifact, package, or decision is blocked by AD-1's editorial state alone**, because AD-1's promotion blocker is the *disposition*, not the corrections. **If the disposition is delayed indefinitely (DEFER), Package B should be released on its own** rather than held hostage to a decision that may not come.

### 4.4 Safe parallelism

| Work | Parallel-safe? |
|---|---|
| **Package E (M7) alongside anything** | ✅ **Yes.** Different artifact, different findings, no shared element. AIA-1 §8 classified all three folds *Unaffected* |
| Package B alongside Package A | ✅ Safe (same act is recommended) — but **never alongside Package C** |
| Package C alongside Package A/B | ❌ **No.** Ordering Rule 1 |
| Package D alongside any editing | ❌ **No.** It verifies a settled state |

---

## 5. Propagation Matrix

### 5.1 Artifacts that change

| Artifact | Propagation | What changes | What must NOT change |
|---|---|---|---|
| **AD-1** | **Direct** | §5 (AP-1 clause 2, AP-3), §6/§6.2 (AR-1 placement + label), §10A (DR-1 scope), §11 (RET-1 row, namespace declaration), §12 (added question), header (history row) | Every element identifier · the bijective element mapping · all five elements' existence · the six existing open questions · every other principle and dependency rule |
| **C4-1** | **Indirect** (consumes AD-1) | Knowledge Structure View (Verdict restoration), §3 or the input declaration (labels), §7 (provenance row), §8 (KO-10), L2 (AR-1 annotation, ACCEPT only), header | All three views' structure · all eight arrows · every KO justification · DP-1..DP-7 · the five fidelity verdicts |
| **M7** | **Direct, independent** | §5's R-1/R-3b/R-4 entries, §1's summary line, the context-map annotations, header | Every dependency, direction, ownership statement, evidence citation, structural-layer grade, U-1..U-4, §6.1's evidence matrix, §7.3's observation |

### 5.2 Artifacts that do NOT change — with the reason for each

| Artifact | Why not |
|---|---|
| **KBI-1** | Its AD-1 readiness classification is **superseded, not corrected.** This program's supersession is **forward-only** (L1-7); a prior review is never edited to agree with a later one. **KBI-1's caution was correct** — it raised KBI-F1 precisely to close this gap |
| **C4-2** | Its PASS verdict **stands and remains correct** (AIA-1 §6). C4-1 faithfully rendered AD-1 as written; the AR-1 defect reached it by inheritance |
| **AFV-1 · AIA-1** | Verification records. **Findings are applied to the *object*, never to the record that raised them**; the object's Disposition History carries the application |
| **M0–M6 · M8 · DAR-1** | **AIA-F4:** the strategic layer never contained the presupposition. M8 carries OQ-PKS-7 with its impact statement intact |
| **MCA · CDR · Checkpoint · RET-1** | No certification, methodology, or retrospective statement depends on AR-1's placement. **Certification state is unaffected** |
| **PMR register** | Contents are candidates by design; no entry is touched |

**Derived — §5.2 is the section that most protects the baseline.** Left unstated, an editor applying findings could reasonably "tidy" KBI-1's superseded classification or annotate C4-2's verdict, and either act would breach forward-only supersession or contradict a correct verification.

---

## 6. Editorial Work Packages

### Package E — M7 independent folds *(may proceed immediately)*

- **Contents:** C-16.
- **Precondition:** none. AIA-1 §8 classified all three folds *Unaffected*.
- **Owner class:** editor, bounded.
- **Prohibited:** touching any dependency, direction, ownership statement, evidence citation, grade, uncertainty marker, the §6.1 evidence matrix, or the §7.3 observation. **Only the three withdrawn/replaced pattern names and the summary line.**
- **Exit check:** M7 §5 asserts no pattern name that DAR-1 withdrew; R-3b reads as a constraint; a history row records the application; nothing else differs.

### Package A + B — AD-1 single amendment *(precondition: disposition issued)*

- **Contents:** C-01 (ACCEPT only) · C-02 · C-03 · C-04 · C-05 · C-06 · C-07 · C-08 · C-09.
- **Precondition:** the AFV-F4 disposition exists. *(Under DEFER: run **B-only** — C-02, C-03, C-04, C-06, C-07, C-08, C-09 — per §4.3.)*
- **Owner class:** editor, bounded, working **only** from pre-supplied wording (§7.1).
- **Prohibited:** deciding OQ-PKS-7 in any direction · characterizing AR-1's placement inside C-06's namespace declaration · adding, removing, splitting, or merging any element · altering any identifier · adding any architectural content beyond C-08's recorded question · resolving any of AD-1's six existing open questions.
- **Exit check:** the bijective mapping is intact (five elements, unchanged identifiers) · AP-1 clause 2 and AP-3 carry their qualifications · DR-1's scope matches its derivation · AR-1's label uses the governed name or is marked an architectural label · a namespace declaration exists and is silent on placement · §12 holds seven questions · under ACCEPT, AR-1's placement carries the M6 §9 contingency · one history row.

### Package C — C4-1 synchronization *(precondition: Package A+B complete)*

- **Contents:** C-10 · C-11 · C-12 · C-13 · C-14 (ACCEPT only) · C-15.
- **Precondition:** AD-1 amended and its exit check passed. **Ordering Rule 1.**
- **Owner class:** editor, bounded.
- **Prohibited:** redrawing any view · adding or removing any box, arrow, or region · adding a mechanism to any arrow · rendering AR-1 or AR-2 as a container · introducing an actor · introducing tactical, deployment, or technology content.
- **Exit check:** *"never a Verdict"* is restored (**C-11 verified explicitly — this is the one item whose omission would propagate a governed-term drift into promotion**) · every label's provenance is recorded or the input declaration is widened · §7 has the negation-list row · §8 has KO-10 · under ACCEPT, AR-1's rendering asserts no more than AD-1 does · all three views otherwise byte-comparable in structure · one history row.

### Package D — Promotion Readiness Verification *(no edits)*

- **Contents:** verify §8's exit criteria; re-derive the promotion-readiness classification of AD-1, C4-1, M7, and the baseline.
- **Precondition:** Packages A+B, C, E complete.
- **Owner class:** verification commission (separately commissioned; **not** designed here).
- **Prohibited:** any edit; any promotion.
- **Exit check:** §8 fully satisfied, or the failing criterion named.

---

## 7. Configuration Integrity Rules

*Binding on every package. **Derived** from the program's own recorded discipline.*

| # | Rule | Source of the discipline |
|---|---|---|
| **CI-1** | **No editor-authored substance.** Every applied wording must come from the finding record or the strategic model. **If wording is not pre-supplied, the item is not in this plan and must be escalated, not invented** | The commission's own success criterion: no editor makes architectural decisions during execution |
| **CI-2** | **No identifier changes.** AC-1 · AC-2 · XD-1 · AR-1 · AR-2 · SB-1/2 · IB-1 · AP-1..10 · DR-1..8 · A1..A8 · KO-1..9 · U-1..4 all retain their identifiers. **C-06 *declares* the existing namespaces; it does not rename them** | M4: collisions occur exactly where register discipline lapses |
| **CI-3** | **No vocabulary drift.** Terminology is frozen. **C-11 is a restoration *toward* the governed term** — the only vocabulary movement permitted anywhere in this plan | Terminology freeze in force since M6 |
| **CI-4** | **No traceability loss.** Every amended statement retains or gains its traceability row; C-07 and C-12 *increase* traceability | AD-1 §11 · C4-1 §7 |
| **CI-5** | **No governance leakage.** Editors may not resolve an open question, alter a disposition, promote anything, or add architecture. **Under ACCEPT, C-01's wording restores an open question and must not read as answering one** | AIA-1's contamination concern |
| **CI-6** | **Forward-only.** Amendments are additive with a history row; originals are not rewritten to look correct | L1-7; the M6 §14 amendment precedent |
| **CI-7** | **Bijection preserved.** No package may add, remove, split, or merge an architectural element | AFV-1 §10A.1 |

### 7.1 Wording provenance — verified for all 16 items

**Every change item's replacement wording already exists**: C-01 in M6 §9 · C-02/C-03/C-04/C-05 in AFV-1 §§6.1, 7.1, 8.1, 11 · C-06 in the PMR register's precedent form · C-07 in KBI-1 §5 · C-08 in AFV-1 §6.1 · C-10..C-13 in C4-2 §9 · C-14 as C-01's applied text · C-16 in DAR-1 §§5–7 · C-09/C-15 by the existing history rows' form. **Derived: no item requires an editor to compose substance, which is what makes CI-1 enforceable rather than aspirational.**

### 7.2 The one item that adds rather than corrects

**C-08** adds an open question to AD-1 §12. **It is the only item that increases content**, and it does so in the conservative direction — recording uncertainty rather than resolving it. **Constraint:** its wording must come verbatim-in-substance from AFV-1 §6.1, and it must state the placement as *undetermined*, never as *decided either way*.

---

## 8. Exit Criteria

| # | Criterion | Objectively checkable by |
|---|---|---|
| **X-1** | The AFV-F4 disposition exists and states what it does **not** decide | The disposition record |
| **X-2** | Under REJECT: C-17's Authority record exists with impact statement and ARB notice | The record |
| **X-3** | All in-force change items applied; every held item recorded as held with its reason | Package exit checks (§6) |
| **X-4** | CI-1..CI-7 hold in every amended artifact | Diff inspection against §7 |
| **X-5** | **C-11 verified applied** (governed-term restoration) | Direct read of the Knowledge Structure View |
| **X-6** | AD-1 and C4-1 assert the same architecture; C4-1 asserts nothing AD-1 does not | Cross-read of AD-1 §6/§6.2 against C4-1 L2 |
| **X-7** | M7 asserts no pattern name DAR-1 withdrew | Direct read of M7 §5 |
| **X-8** | Promotion-readiness re-derived per artifact and at baseline level | Package D |

**Derived:** X-6 is the criterion that would catch the failure mode this whole plan exists to prevent — a representation asserting more than its source after both were edited.

---

## 9. Execution Constraints

**Permitted:** applying an in-force change item using its pre-supplied wording · adding a Disposition History row · recording a held item as held.

**Prohibited (any package):** editing an artifact not in §5.1 · touching **KBI-1, C4-2, AFV-1, AIA-1** (§5.2) · deciding OQ-PKS-7 · redrawing a view · adding or removing any architectural element or arrow · introducing tactical, API, deployment, or technology content · reopening Strategic Discovery · modifying methodology · promoting anything · **inventing wording (CI-1)**.

**Escalation rule:** if an editor finds that an item's pre-supplied wording does not fit the target text, **stop and escalate** — the mismatch is a finding, not an invitation to compose. **Derived:** this is the single rule that keeps execution from becoming design.

---

## 10. The Controlled Change Plan

| Step | Act | Precondition | Blocking? |
|---|---|---|---|
| **0** | **Package E** — M7's three DAR-1 folds | none | No — may run now, in parallel with everything |
| **1** | **The AFV-F4 disposition** *(Authority; not in this plan)* | AIA-1 read, incl. AIA-F1's asymmetry | **Yes** — gates steps 2–4 |
| **1a** | Under REJECT: **C-17**'s Authority record | step 1 = REJECT | Yes for promotion |
| **2** | **Package A + B** — AD-1, one amendment *(B-only under DEFER)* | step 1 | Yes — gates step 3 |
| **3** | **Package C** — C4-1 synchronization | step 2 exit check passed | Yes — gates step 4 |
| **4** | **Package D** — Promotion Readiness Verification *(separately commissioned)* | steps 0, 2, 3 | Yes — gates promotion |
| **5** | Promotion *(separately commissioned)* | X-1..X-8 satisfied | — |

**Derived — what this plan achieves against the commission's success criteria:** every change carries verification or Authority provenance (§3, §7.1) · the propagation path is complete before implementation (§4, §5) · **no editor must make an architectural decision during execution** (CI-1 + the escalation rule + wording pre-supplied for all 16 items) · the baseline updates through deterministic bounded packages with the bijection and every boundary preserved (CI-2, CI-7, §6's prohibitions).

**What this plan deliberately does not do:** take the disposition · execute any package · assume an outcome · design Package D's method.

---

## 11. Amendment 1 — resolving ERV-F1 · F2 · F3 (+ F4 · F5 · F6)

**Standing:** additive amendment applied under the Controlled Execution Commission (PA, 2026-07-28), which authorized *"Resolve ERV-F1, F2, F3."* **Plan-level only** — no artifact edited, no finding re-litigated, no new finding created. §§1–10 stand as issued; this section governs on conflict.

### 11.1 ERV-F1 — the three undetermined items, now SELECTED with rationale

*Each selection is recorded with its reason so it is traceable rather than arbitrary. **After this amendment no change item offers the editor a choice.***

| Item | **Selected wording** | Rejected option | Rationale for the selection |
|---|---|---|---|
| **C-03** | **Add the L4-8 derivation** for DR-1's evidence clause — retaining the rule and supplying its missing derivation: *a derived artifact carrying no independent semantic identity contributes no independent evidence* | Narrowing DR-1 to authority only | **Minimal-change principle.** AFV-F2's defect was an *absent derivation*, not a wrong rule; supplying the derivation repairs exactly what was wrong, whereas narrowing would change the rule's content — a larger act than the finding requires |
| **C-05** | **Use the governed name — "Normative Governance region"** | Marking it an architectural label for the CBC-3 seam | **Terminology-freeze discipline**, and consistency with C-11: restoring a frozen term is preferred over adding an explanatory marker. Simpler, and it removes the defect rather than annotating it |
| **C-10** | **Reword the three labels**, routing each through AD-1's traceability matrix, which already names M6/M8 as the origins of the elements they label | Widening C4-1's input declaration | **Required by ERV-F3** (below) — widening alters a commission-derived constraint. Rewording is fully editorial and equally complete |

### 11.2 ERV-F2 — the omitted fidelity re-read, now INSERTED

**New step C-18 (Mandatory):** a **bounded fidelity confirmation** — re-read AFV-1 §§5.1, 6.1, 7.1, 8.1 against the amended AD-1 and record whether each corrected statement landed as the finding specified. **Not a re-run of AFV-1**; scope is those four sections only.

**Ordering (this is the point of the finding):** **C-18 is a PRECONDITION of Package C.** Package C's precondition now reads: *"Package A+B complete **and C-18 recorded as passed**."* Without it, a mis-applied fidelity correction propagates into C4-1 with no verification left downstream.

**Owner:** the executing commission may perform C-18 as a bounded confirmation; **if any of the four sections does not confirm, STOP and escalate** — a non-confirmation is a finding, not something to fix in flight.

### 11.3 ERV-F3 — C-10's owner class, now CORRECT

C-10's remedy is reassigned to *reword the three labels* (§11.1), which is **fully editorial**. **C4-1's input declaration is NOT to be widened by any package** — it originates in the C4-1 commission's instruction, and altering it is not an editorial act. *(If the program later wants the declaration widened, that is the commissioning authority's act, not this plan's.)*

### 11.4 ERV-F4 — rollback, now STATED

**Rollback is UNAVAILABLE. CI-6 (forward-only) permits supersession only:** a mis-applied edit is corrected by a further recorded amendment, never reverted. **Consequence, stated because it changes how an executor should treat uncertainty: an editor who is unsure must escalate *before* editing, not repair afterwards** — there is no afterwards. This makes the escalation rule (§9) the plan's primary error-prevention control, not a fallback.

### 11.5 ERV-F5 — Package C's precondition under DEFER, now UNAMBIGUOUS

**Under DEFER, Package C does not run.** Its items (C-10..C-13, C-15) are **held** with the rest of the disposition-dependent set, because C-14's availability is unknown and editing C4-1 twice is forbidden by the single-amendment discipline. *(Supersedes §3.5's DEFER row, which listed them as in force.)*

### 11.6 ERV-F6 — history-row content, now SPECIFIED

Every history row added by C-09, C-15, or C-16 **must state**: (a) the commission under which the change was applied; (b) the change items applied, by identifier; (c) **that the change was applied *after* the artifact's prior verification, naming it** — so a reader cannot mistake the amended version for the verified one; (d) that no other content changed.

**Specifically for C4-1:** the new row must record that the C4-2 corrections **were subsequently applied**, since the existing row states *"recommended, not applied here"* and — being forward-only — is not edited.

### 11.7 Amendment effect on determinism

**All 17 items are now deterministic.** Twelve were already (§8.3 of ERV-1); three are selected here; C-18 is newly specified with a defined scope and stop rule; the history-row items are specified in §11.6. **No item requires an editor to choose, compose, or interpret.**

---

## 12. Amendment 2 — ARB M8 review findings (2026-07-28)

**Standing:** additive. The ARB approved M8 (**0 Critical · 0 Major · 4 Minor**) and recommended four refinements. **M8 is on §5.2's not-to-change list and is classified *Promotion Ready*, so these are recorded as governed change items rather than applied ad hoc** — an unplanned edit to a Promotion-Ready artifact is the "convenience edit" §4's minimality review forbids, and it would change a readiness classification outside any work package.

### 12.1 Reviewer Finding 1 — NOT APPLICABLE to M8; routed to the Authority instead

**The reviewer proposed:** changing *"issue verdicts that feed authority without being authority"* → *"issue evidence-based verdicts that inform authority without constituting authority."* *(Wording discipline, reviewer-refined: **the reviewer proposes; the project disposes.** A review observation is advice until an Authority disposition accepts it — see §12.6.)*

**Why it must not be applied to M8.** The phrase is **M6 §7.4's frozen wording** — CBC-1's purpose statement, accepted by Authority Disposition §2.1 — quoted by M8 at two places (§1 and §3's table). **M8's function is faithful assembly.** Altering the phrase in M8 alone would make M8 **misquote its own frozen source**, which is precisely the defect class C4-2 recorded as **VR-2**: a governed phrase altered by prose-smoothing in a downstream artifact, classified Moderate for exactly this reason. **Applying ARB Finding 1 naively would create a VR-2-class defect in the act of improving the prose.**

**Applying the proposed refinement directly to M8 would therefore create a VR-2-class defect in the act of improving the prose.**

**The improvement is nonetheless real** ("feed" is looser than "inform"; "without being" is looser than "without constituting"). **It must therefore be made at the source or not at all** — and the source is a frozen artifact whose UL statement was accepted by Authority disposition.

**Recorded as HELD item H-1, owner = Authority:** amend M6 §7.4's CBC-1 purpose statement via the §14 Checkpoint Amendment mechanism (additive, original preserved), then propagate to M8 as a consequent quotation update. **Not scheduled in any package.** *(Note: the underlying source of the phrase is P-8 — "knowledge as input to authority, never as authority" — which already uses the stronger formulation the ARB prefers. That strengthens the case for the amendment and is evidence the Authority should have.)*

### 12.2 New change items

| # | Change | Target | Class | Wording pre-supplied by | Deterministic? |
|---|---|---|---|---|---|
| **C-19** | Add to §6: *"Candidate seam is a governance state, not a domain model element."* | M8 §6 | **Mandatory** | ARB Finding 2 verbatim; substantively derived from **MCR-2** (candidate seam = a formal SDM state distinct from a bounded context) | ✅ single sentence, one location |
| **C-20** | In §3's table, change the expressed-knowledge core's status to *"**Intentionally** unpartitioned **region** — acknowledged, not disposed"* | M8 §3 | **Mandatory** | ARB Finding 3; and **M6's own wording** — *"deliberately left unpartitioned"* (M6 §0, §12) | ✅ one word, one cell |
| **C-21** | Add **IBC-1 (Implementation Boundary Contract)** to §12's Next Steps, between Promotion and any implementation act | M8 §12 | **Mandatory** | ARB Finding 4; **already decided** — see §12.3 | ✅ one list entry |

**Note on C-20 (recorded so the finding is not over-credited):** M8 **§1 already states** *"deliberately left unpartitioned, because no evidence partitions its interior."* The ARB's concern — that a future reader might think the region was simply not analyzed — is **already addressed in the executive summary and absent only from §3's table row.** C-20 closes that one gap.

### 12.3 ARB Finding 4 — already decided, and further along than the review knew

The ARB recommends inserting **Promotion → IBC-1 → Implementation Authorization**. **This is already the program's decided position:** the Authority ruled **Option B, named Implementation Handover Governance** (Knowledge Methodology → governed handover → Implementation Methodology), with a binding scope guard on the handover artifact's contents. **IBC-1 exists as a reviewed draft** and carries an independent architecture review with verdict **REVISE** (3 Critical · 11 Major · 5 Minor · 2 Suggestions).

**Convergence worth recording:** the ARB's *sequencing* is independently confirmed by that review's Critical finding **IBC-C1** — IBC-1 declared AD-1 a certified baseline while AD-1 is *Governance Review Required*. **IBC-1 cannot be issued before the baseline is promoted**, which is exactly the ordering the ARB proposes. **Two reviewers reached the same ordering from opposite directions**: the ARB from what M8 should stage, the IBC-1 review from what IBC-1 may claim.

### 12.4 Determinism check on the new items (ERV standard, self-applied)

Per ERV-1 §12(5) — *re-verify only what changed*. **C-19, C-20, C-21 each have exactly one pre-supplied wording, one target location, and no alternative option.** None requires an editor to choose, compose, or interpret. **H-1 is not a package item and is owned by the Authority.** §11.7's claim — *no item offers the editor a choice* — therefore continues to hold across all 20 items.

### 12.5 Consequence for §5.2 and for M8's readiness

**M8 moves off the not-to-change list for this change set only**, with the reason recorded: three additive clarifications requested by the ARB in its approval, none altering a claim or a quotation. **M8's promotion-readiness classification must be re-derived after C-19..C-21** (a Package D input, not a new commission). **§5.2's other five artifact classes are unaffected.**

**Sequencing:** C-19..C-21 are **independent of the AFV-F4 disposition** and touch no artifact in Packages A+B or C. They may be executed with **Package E** or as their own bounded act — **subject to §12.6.**

### 12.6 A missing step, corrected — findings require ACCEPTANCE before they become change items

**The reviewer raised this, and the reviewer is right. Checked honestly against the record: this governance is *not* recorded elsewhere — §12.2 went straight from *finding* to *change item*, skipping the acceptance disposition.**

**Why that is a real gap and not a formality.** This program established the pattern at **DAR-1**: a review *recommends*, an Authority disposition *accepts / rejects / defers*, and only then does editorial application follow. Every other finding set in the baseline has an acceptance record — VF-1/2/3 at DAR-1; F-M6CR-1..4 at the Authority Disposition; the MCRs at the CDR. **Findings 2, 3, and 4 have none.** The audit question the reviewer names — *was the finding accepted?* — currently has no answer for C-19..C-21, only *how would it be implemented?*

**Correction applied:** **C-19, C-20, and C-21 are reclassified from *Mandatory* to *PENDING ACCEPTANCE*.** They are prepared and deterministic, and they **may not execute** until an Authority disposition accepts the underlying findings. **H-1 likewise requires acceptance of Finding 1's routing** before any source amendment is prepared.

**Proportionality, recorded so this does not become a new procedural layer:** the required act is **a one-line disposition per finding (accept / reject / defer)**, not a commission and not a report. **It can ride with the AFV-F4 disposition**, which is already the outstanding Authority act. *(This respects the reviewer's own earlier caution against turning every safeguard into a mandatory phase: the audit trail gains a step; the process does not gain a stage.)*

**Corrected chain, now matching the DAR-1 precedent:**

> Review finding → **Authority disposition (accept / reject / defer)** → change item → bounded execution → readiness re-derivation

**Derived:** the gap arose because the reviewer's verdict (*APPROVE with 4 Minor*) reads like an acceptance and is not one — **it is the disposition of the artifact, not of the findings.** Approving M8 and accepting a refinement to M8 are separate acts, and the first does not imply the second.

---

*Traceability: executes the Controlled Change Plan Commission CCP-1 (PA, 2026-07-28) · inputs limited to AFV-1 · AIA-1 · AD-1 · M0–M8 · DAR-1 · records the commission's own sequencing tension (§0) and resolves it by planning **outcome-conditionally** across ACCEPT / REJECT / DEFER · 16 change items inventoried and classified · dependency graph with two derived ordering rules (source-before-representation; single-amendment) · propagation matrix incl. **six artifact classes explicitly NOT changed, each with its reason** · five bounded work packages with preconditions, owners, prohibitions, and exit checks · seven configuration-integrity rules with wording-provenance verified for every item · eight objective exit criteria · **no artifact edited, no correction applied, no disposition taken or assumed, nothing promoted, methodology untouched, no strategic knowledge reinterpreted** · same-lineage act, T-2 carried. **STOP.***

> **Controlled Change Plan complete. Sixteen change items across three artifacts, executable as five bounded packages: M7's folds may proceed immediately; AD-1 is amended exactly once after the disposition; C4-1 follows AD-1 because a representation may never be edited ahead of its source; promotion-readiness verification follows all editing. Every item's wording is pre-supplied, so no editor need author substance — and if the wording does not fit, the rule is to escalate, not to compose. No artifact was edited and no disposition was taken or assumed. The next act is the AFV-F4 disposition, separately commissioned.**
