# BC-7 Refinement Correction — Independent Verification #3
## `KOS-ARCH-BASELINE-003` · scope: the three corrections in `58af4302` only

**2026-08-17 · Independent Architecture Verification · falsification pass**

> # 1 · EXECUTIVE VERDICT — **VERIFIED WITH NOTES**
>
> **All three corrections (R-1, R-2, R-3) are accurate, and each was re-derived from primary evidence rather than confirmed from the correction's own text.** The change boundary holds: every hunk falls inside the four declared sites, and nothing reaches BC-7 ownership, the `WorkItem` aggregate, `ADR-AIP-03`, `CAP-14` or the `ADR-AIP-04` deferral. **The central question is answered YES: storage acceptance is correctly separated from semantic authority, and the correction strengthens `T-1` rather than weakening it.**
>
> **Two precision findings, neither structural, neither warranting a further correction cycle:**
> **F-1** — the delivery note's own diff metric (*"5 removed · 14 added"*, stated three times **and offered to this verification as an acceptance criterion**) disagrees with git, which reports **16 insertions / 5 deletions**. Method-dependent: a `grep`-based count reproduces 14; `numstat` gives 16; the gap is two added blank lines.
> **F-2** — R-3's *"6 of 37 STARTs"* is **not independently reproducible**. The selection criterion is unstated; a plain reading gives **14 of 38**. The conclusion is unaffected because it rests on a verbatim-accurate specimen and on a 38-of-38 fact, both confirmed.
>
> **Both findings are the same defect class the correction was repairing** — a measured number carrying evidential weight without its method. That is now its third appearance in this programme.
>
> ⚠️ **Independence is PARTIAL and confidence is correspondingly reduced.** This process authored none of the five inputs — but **BC-7 Verification #1 was authored by this verifier's own model.** See §4.

---

## Method

Every checked claim re-derived from **primary evidence** — the runtime records, the mechanism source, the proposal at the cited line, and git itself — **never from the correction's text, the delivery note, or Verification #2**. Where the correction and Verification #2 agree, the agreement was treated as a hypothesis to test, not as corroboration. Per the commission's principle, I went looking for a second wrong statement on the assumption one exists. **I found two, both minor, and report them rather than the comfortable conclusion that the correction was clean.**

---

# 2 · Correction-by-correction assessment

## R-1 — Evidence measurement repair · **CONFIRMED**

| Check | My derivation | Result |
|---|---|---|
| Is the false *"0 non-canonical keys"* cell removed? | Diff shows the row replaced, not annotated | ✅ removed |
| Is the replacement measurement supported? | Independent key census over all 15 records: **`note` ×16 (all on COMPLETE) · `humanAct` ×3 on COMPLETE · `reason` ×1 on CANCEL = 20** | ✅ **exact match** |
| Is `note` truly absent from the mechanism? | `grep -c note .claude/scripts/workflow-state.php` → **0** | ✅ |
| Is `humanAct` on COMPLETE genuinely non-canonical? | `humanAct` is **required** on START (mechanism refuses without it) and read by no COMPLETE branch; 38 canonical uses on START, 3 inert on COMPLETE | ✅ classification sound |
| Is `reason` on CANCEL genuinely non-canonical? | `reason` is **required** on STOP; census shows exactly 1 on STOP (canonical) and 1 on CANCEL (inert) | ✅ classification sound |
| Does `T-1c` still hold at 0? | The four derived keys (`mutationOwner`, `state`, `workItemState`, `assignmentState`) appear in **zero** transitions | ✅ **0 confirmed** |
| Is the measurement point recorded? | Row carries date **and** denominators (15 records · 133 transitions · 49 grants) | ✅ |
| Reproducibility at a drifted denominator | Now **135 transitions · 50 grants**. Proportions hold exactly as the handoff predicted | ✅ |

### The central question: is storage acceptance separated from semantic authority?

**YES — and the correction is what makes the separation evidenced rather than asserted.**

The corrected row does not merely record 20 exceptions; it identifies them as **the empirical proof of `T-1b`** — a non-vocabulary key is *stored and inert*. Before the correction, `T-1b` was argued hypothetically and `T-1` rested partly on a census (*0 stored*) that any future write could falsify. After it, `T-1` rests on the fold's definition, and the census-based part is confined to `T-1c`, which genuinely is a practice measurement.

The modelling rule is stated in exactly the right shape: *"The rule is not that such a value is refused at the boundary; it is that it can never become truth."* **That is the separation.** The mechanism accepts arbitrary keys into storage and grants them no authority whatsoever.

**And the correction stays inside scope:** no key validation, no schema enforcement, no mechanism change is made or implied. I did not evaluate whether a rejecting mechanism *should* exist — that is outside this verification.

### Note R-1a (MINOR) — two denominators in one table, one dated

The corrected row states *"`T-1c` stands unchanged at **0 of 133**"*, while the adjacent **unchanged** derived-key row measures *"**0 of 126** transitions"*. Both are true at their own measurement points and both re-derive to 0 (now 135). The delivery note **discloses** this as `R-4` left deliberately out of scope. **Recorded as residual precision, not as a correction defect** — but two adjacent denominators in one table, only one of which is dated, is the condition `R-4` exists to end.

---

## R-2 — DP-6 replacement completeness · **CONFIRMED, byte-exact**

| Check | My derivation | Result |
|---|---|---|
| Does proposal line 279 have three cells? | Extracted and counted: **3** | ✅ |
| Does the correction's quoted "original" match the source? | `\| DP-6 \| State is folded from the log; derived values are never accepted as input \| `Observed` + **`Proposed`** as T-1 \|` — **verbatim identical** to line 279 | ✅ |
| Does the replacement supply all three cells? | Yes — third cell reads `` **`Observed`** (T-1a/T-1b — structural, in source) + **`Proposed`** (T-1c — the writer obligation), **as T-1** `` | ✅ |
| Was a new policy introduced? | The class value follows mechanically from `T-1`'s restatement in §4.4 ① (two structurally `Observed` parts + one `Proposed` obligation). An explanatory note states it is derived, not invented | ✅ **none** |
| Is the original decision preserved? | DP-6's decision content is unchanged; only the instruction's completeness changed | ✅ |

**This was documentation-completeness repair, exactly as scoped.** Had the incomplete instruction been applied literally, it would have dropped the class marker — a silent classification loss. The repair prevents that.

---

## R-3 — `recordedBy` referent question · **CONFIRMED on substance; one count not reproducible**

| Check | My derivation | Result |
|---|---|---|
| Is the ambiguity recorded? | OQ-10 now asks *"does `recordedBy` carry ONE referent?"* with the observed per-type distinction | ✅ |
| Is it still an open question? | Text states *"The question is recorded, not answered."* | ✅ |
| Any hidden decision — rename, new VO, closed vocabulary, Governance decision? | Explicit prohibition present: *"No solution is proposed here — no rename, no new model, no ADR, no vocabulary decision"*, and it **inherits Verification #2's restraint verbatim** on the ground that proposing wording would be modelling | ✅ **none found** |
| Is the cited specimen accurate? | `KOS-ARCH-BASELINE-001` seq 3 read directly: `recordedBy: human`, and its `humanAct` contains **verbatim** *"Registered by Governance per section 6.3 of the commission: registering a START is a GOVERNANCE act"* | ✅ **quotation exact** |
| Is the 37-of-37 claim sound? | **38 of 38 STARTs** are `recordedBy: human` (drifted from 37; proportion exact) | ✅ |

**The substantive point is proven independently:** on a START the field says `human` while the act's own text says Governance performed the registration. A reader asking *"who registered this transition?"* is answered wrongly on every START. **The referent genuinely shifts by transition type, and recording that as a question rather than a fix is the correct discipline.**

### Finding F-2 (MINOR) — *"6 of 37"* is not reproducible

The claim is that **6** STARTs are `recordedBy: human` *while their own `humanAct` states Governance recorded the START*. **I cannot reproduce 6.** A plain criterion — `humanAct` mentioning Governance — yields **14 of 38**. The stricter criterion that isolates 6 is not stated, so the figure cannot be audited.

**The conclusion is unaffected**, because it rests on the verbatim specimen and on the 38-of-38 fact, both confirmed. But the number carries evidential weight without its method — **the same defect class `R-1` was repairing**, and the same class the Phase A baseline's `V-D` identified. **A census must state its selection rule, not only its result.**

---

# 3 · Boundary integrity assessment — **CONFIRMED by re-derivation, not by accepting §3**

The handoff asked that the Change Boundary Statement be re-derived rather than trusted. It was.

| Constraint | How I checked | Result |
|---|---|---|
| **Change boundary — four declared sites** | Hunk positions mapped against section line numbers: banner (`+13,9`) · §2 (`+66`, §2 spans 51–73) · §4.4 (`+178`, `+180`, `+184`, §4.4 spans 164–191) · §5.4 (`+257`, §5.4 spans 235–260) | ✅ **all six hunks inside the four sites; no hunk outside** |
| **BC-7 boundary · Governance authority** | §7 (*"Decisions unchanged"*) is at line 271 — **beyond the last hunk at 257**, therefore untouched | ✅ unchanged |
| **`WorkItem` aggregate root · RA-4 · OQ-9** | No hunk reaches the aggregate sections | ✅ unchanged, not reopened |
| **`ADR-AIP-03` · `CAP-14` · `ADR-AIP-04` deferral** | No file matching `ADR-AIP` or `CAP-14` appears in the commit | ✅ untouched |
| **Proposal, Verification #1, Verification #2 byte-untouched** | Commit touches exactly three files — the session log, the new delivery note, and the refinement | ✅ **confirmed** |
| **`KOS-ARCH-BASELINE-002` accepted model** | Not in the commit; used as input only | ✅ not overwritten |
| **No mechanism or code change** | `workflow-state.php` not in the commit | ✅ |

### Finding F-1 (MINOR) — the delivery note's diff metric disagrees with git

The delivery note states **"5 lines removed · 14 added"** in three places — §0's *"Diff scope, measured"*, §2's *"verified by diff"*, and §4's handoff, where it is given to this verification as a **check criterion** (*"the diff should be 5 removed / 14 added lines"*).

**git reports 16 insertions / 5 deletions.** The discrepancy is method: counting `^+[^+]` reproduces **14** because it silently drops added blank lines; `numstat` gives **16**.

### Blast radius — **MEASURED, not inferred**

The ARB's criterion is the right one: *low impact if the wrong metric only describes the commit; high impact if it hides an additional semantic change.* **So I measured which two lines are unaccounted for rather than assuming they were harmless.**

Rendering all 16 added lines with line-endings made visible:

| Added line | Content | Semantic? |
|---|---|---|
| 1–5, 7–8 | banner prose | ✅ declared (banner site) |
| **6** | `>` — an empty blockquote continuation | whitespace |
| **9** | *(truly blank)* — separator before the §2 table row | **whitespace** |
| 10 | the corrected §2 census row | ✅ declared (R-1) |
| 11–13 | DP-6 instruction + both three-cell rows | ✅ declared (R-2) |
| **14** | *(truly blank)* — separator in §4.4 | **whitespace** |
| 15 | the "third cell is derived" note | ✅ declared (R-2) |
| 16 | the extended OQ-10 row | ✅ declared (R-3) |

**The two unaccounted lines are lines 9 and 14 — both pure blank separators.** `grep "^+[^+]"` matches line 6 (`>` is a character) but cannot match a bare `+`, which is exactly why it returns 14 where git returns 16.

> **Impact: LOW, and now established rather than assumed. No semantic content is concealed by the discrepancy.** All 14 non-blank insertions are inside the four declared sites, and so are both blank ones.

**What remains is a reporting defect, not a scope defect:** the figure was offered to this verification as an acceptance criterion, so a verifier using `git show --stat` — the natural method — sees a mismatch against the number it was told to expect. **That is how a whitespace discrepancy becomes a false tampering signal**, and it is the whole of the harm.

---

# 4 · Independence assessment — **PARTIAL · confidence reduced, and stated rather than glossed**

```
Am I the BC-7 proposal author?           NO   (Fable 5, 1c79b95f)
Am I the refinement author?              NO   (Fable 5, 04420fbb)
Am I the Verification #2 author?         NO   (Fable 5, 5dbf1493)
Am I the correction author?              NO   (Fable 5, 58af4302)
Am I the Verification #1 author?         ⚠️  NOT THIS PROCESS — BUT THIS MODEL.
                                              2b5ac8ab is Co-Authored-By
                                              Claude Opus 5 (1M context),
                                              which is this verifier's model.
```

**Process independence from every artifact under verification: satisfied.** This process's history in the estate is Election-lane architecture work and the Phase A baseline's Verification #2 — a **different work item**. It authored no BC-7 artifact.

**Model independence: NOT satisfied, on two counts.** BC-7 Verification #1 came from this model, and it is upstream evidence feeding the refinement whose corrections I am checking. A shared model can share a blind spot, and I may be disposed to accept a claim my own model originated. **Report, per the commission: verification confidence is reduced due to an author relationship at the model level.**

**Mitigation actually performed, so the disclosure is not merely a caveat:** every R-1/R-2/R-3 claim was re-derived from primary sources — the records, the mechanism, proposal line 279, git — and **not one conclusion was accepted from Verification #1, Verification #2, or the delivery note.** The two findings above were both produced by disagreeing with the correction, which is some evidence the pass was not merely confirmatory.

### On the §0 divergence — my required verdict

The correction author disclosed that it **is** the Verification #2 pen, against a prompt saying it should be a fresh terminal, and recorded the divergence inside the seq-18 START act rather than in a reply.

> **My verdict: the disclosure is adequate, and I do NOT judge the work should be redone by a fresh pen.**

Reasons, in order of weight: ① the registered grant expressly bars no one, and the record's own rule places performer compliance where the PO/ARB starts the session; ② **all three corrections are mechanically re-checkable, and I re-checked all three from primary evidence** — the value a fresh pen would have added is precisely what this verification supplies; ③ the divergence was disclosed *before* the work, inside the record, not defended afterwards; ④ the author barred itself from this verification and said so unprompted.

**The residual risk is not zero and is not mine to price:** a same-pen correction plus a same-model verification means two of the three independence factors in this chain are weakened. **That is the PO/ARB's judgment, and it now has both facts in front of it.**

---

# 4a · Findings table, with classification

**Classification axis 1 — defect class**, per the ARB's A/B/C/D frame. **Neither finding is D, and the distinction between A and B is where they differ.**

| | Class | Meaning | Applies |
|---|---|---|---|
| **A** | Documentation measurement error — wrong number *about* the correction | **F-1** |
| **B** | Evidence integrity defect — a claim *inside* the knowledge artifact whose evidence cannot be reproduced | **F-2** |
| **C** | Correction scope violation | ❌ **neither** — all 16 insertions fall inside the four declared sites |
| **D** | Architecture model defect | ❌ **neither** — no finding reaches a decision, boundary, invariant or classification |

**Classification axis 2 — evidence class of each verified claim**, per the commission's `Observed` / `Declared` / `Inferred` / `Unknown` requirement.

| ID | Claim | Evidence | Class | Severity | Impact |
|---|---|---|---|---|---|
| **C-1** | 20 keys the mechanism never reads (`note` ×16 · `humanAct` ×3 · `reason` ×1) | independent census of all 15 records | **`Observed`** | — | R-1 **correct** |
| **C-2** | `note` appears nowhere in the mechanism | `grep` → 0 | **`Observed`** | — | R-1 **correct** |
| **C-3** | 0 derived keys stored | census of the four derived names → absent entirely | **`Observed`** | — | `T-1c` **holds** |
| **C-4** | Proposal line 279 has three cells, quoted verbatim | line extracted and compared byte-for-byte | **`Observed`** | — | R-2 **correct** |
| **C-5** | DP-6 class value is derived from `T-1`, not invented | follows mechanically from §4.4 ①; explanatory note present | **`Inferred`** *(and correctly labelled as derived in the artifact itself)* | — | R-2 **correct** |
| **C-6** | `recordedBy: human` on a START whose own text says Governance registered it | `KOS-ARCH-BASELINE-001` seq 3 read directly; quotation **verbatim** | **`Observed`** | — | R-3 **sound** |
| **C-7** | 38 of 38 STARTs are `recordedBy: human` | census | **`Observed`** | — | R-3 proportion **holds** |
| **C-8** | OQ-10 decides nothing | explicit prohibition list present; V#2's restraint inherited verbatim | **`Observed`** | — | R-3 **correct** |
| **F-1** | *"5 removed · 14 added"* | git: **16 insertions / 5 deletions**; the 2-line gap measured as blank separators (lines 9, 14) | **`Observed` — falsified as stated; method-dependent** | **MINOR** | **Class A · LOW.** Reporting metadata only; **no semantic change concealed.** Harm is that it was handed to verification as a check criterion |
| **F-2** | *"6 of 37 STARTs"* whose `humanAct` says Governance recorded the START | not reproducible; a plain criterion gives **14 of 38**; selection rule unstated | **`Unknown` — the figure cannot be classified because its method is absent** | **MINOR** | **Class B · LOW.** Sits *inside* the corrected artifact, so it is evidence rather than metadata — but the conclusion rests on C-6 and C-7, both `Observed` |
| **R-1a** | Adjacent denominators 126 / 133 (now 135), one dated | table inspection | **`Observed`** | trivia | disclosed as `R-4`, out of scope, still open |

> **F-2 is the more serious of the two by class, though both are minor by severity.** F-1 is a wrong number *about* the correction; **F-2 is an unreproducible number *within* the knowledge being corrected.** The first is metadata hygiene; the second is the very thing R-1 was repairing, recurring one section later in the same document.

# 4b · Knowledge Engineering integrity assessment

| Dimension | Question | Finding |
|---|---|---|
| **Evidence lineage** | Could another person reproduce the correction's reasoning? | ✅ **Yes, and I did — for all three corrections, from primary sources.** R-1's census, R-2's line-279 comparison and R-3's specimen are each independently re-derivable. **One exception: F-2's "6 of 37" is not reproducible**, so that single link in the lineage is broken |
| **Reproducibility under drift** | Do the measurements survive the estate changing? | ✅ **Yes** — because the corrected cell carries its measurement point. Denominators moved 133 → 135 during this verification and the proportions held exactly. **This is R-1's most durable improvement, and it is the property the uncorrected rows still lack** (`R-1a`) |
| **Fact / decision separation** | Are facts kept apart from decisions? | ✅ **Yes, and unusually well.** R-1 records a measurement and explicitly declines to propose key rejection; R-3 records a question and lists what it is not deciding; R-2 marks its one inferred value *as* inferred so the PO/ARB can see it is derived. **No fact was promoted to a decision anywhere in the correction** |
| **Provenance** | Are authorship and reviewer roles clear? | ⚠️ **Clear but weakened.** Every artifact's author is identifiable from the record, and the correction author **disclosed its own conflict before performing the work, inside the record** (seq 18) rather than in a reply. **But two of three independence links in this chain are compromised** — same pen for V#2 and the correction, same model for V#1 and this verification (§4) |
| **Self-awareness of limits** | Does the correction state what it did not do? | ✅ R-4, R-5 and R-7/OQ-13 are named as left open, with reasons, rather than absorbed or silently dropped |

> **Knowledge integrity verdict: IMPROVED by the correction.** The artifact went from carrying a false `Observed` cell to carrying a dated, reproducible census; from an edit instruction that would have silently dropped a classification to a complete one; and from an unstated ambiguity to a recorded question. **F-1 and F-2 are regressions in precision, not in integrity** — neither reintroduces a false claim, and both are visible to any reader who checks.

# 5 · Remaining risks

| # | Risk | Materiality |
|---|---|---|
| **1** | **Third recurrence of the method-dependent-count defect** (`V-D` → `R-1` → `F-1`/`F-2`). Repairing instances one at a time has not stopped it | 🟠 **the pattern is the risk, not any instance.** A standing rule — *a census states its selection rule and its measurement point, or it is not evidence* — would address the class |
| **2** | Two adjacent denominators (126 / 133 / now 135) in one table, one dated (`R-1a`) | 🟡 disclosed as `R-4`, out of scope, still open |
| **3** | Independence weakened at two links: same pen for V#2 and the correction; same model for V#1 and this verification | 🟡 both disclosed; PO/ARB to price |
| **4** | `R-5` (the dangling *"amend the VERIFY2 grant"* instruction) and `R-7`/`OQ-13` (`authorizationLinkage`) remain open | 🟢 correctly excluded from this correction; each needs its own act |
| **5** | Denominators will keep drifting (135 today) while the document is fixed at 133 | 🟢 inherent to a dated snapshot; correctly handled by dating it |

**No risk found that touches an architecture decision.** Everything outstanding is evidence hygiene.

---

# 6 · Recommendation — *recommendation only; acceptance is the PO/ARB's act*

> ## **ACCEPT THE CORRECTION, with `F-1` and `F-2` recorded as notes.**

**Do not return it for repair.** All three authorized corrections are accurate, the boundary held, and the two findings are precision items in the *delivery note* and in one *supporting count* — neither touches a correction, an architecture decision, or a classification. Another correction cycle would cost more in lifecycle overhead than the defects cost in trust, and would itself carry a fresh chance of introducing a third-generation defect.

**Do not create a new architecture work item.** Nothing found requires redesign, and `BC-7`'s model was not in question here.

**Two items for the Governance/EKS lane rather than this correction:**
1. **The recurring census-method defect** (`F-1`, `F-2`, and its ancestors) — a candidate standing rule, since three instances across two work items is no longer a coincidence.
2. **`R-4`'s scope** — dating *all* censuses, not only corrected cells (`R-1a`).

---

## Architecture impact assessment — stated separately, because it is the question most easily conflated

> **Architecture impact: NONE.**

The verification question was deliberately **not** *"is the BC-7 model good?"* — that was Architecture's responsibility and Verification #1's subject. It was *"did the correction accurately repair the identified defects without introducing new knowledge-integrity defects?"*

Measured against that question: **the three repairs are accurate, and the two new defects are both non-architectural.** No finding touches BC-7's ownership, the `WorkItem` aggregate root, `RA-4`, `OQ-9`, the invariant set, `ADR-AIP-03`, `CAP-14`, or the `ADR-AIP-04` deferral. **No responsibility moved between BC-7 and Governance:** BC-7 still owns workflow lifecycle and recorded orchestration history; Governance still owns authorization policy and authority meaning — and R-3 is a live demonstration of that boundary being respected, since it identified an ambiguity in a Governance-owned concept and **recorded it as a question rather than modelling an answer.**

**The one substantive movement is in the opposite direction from a defect:** `T-1` is now better evidenced than before the correction. **An architecture decision was strengthened by an evidence repair without being changed — which is precisely what the correction was commissioned to achieve.**

## Amendment disclosure

**Amended after delivery at ARB review** *(disclosed rather than folded in silently)*: §4a (findings table with the A/B/C/D and `Observed`/`Declared`/`Inferred`/`Unknown` classifications), §4b (Knowledge Engineering integrity assessment), the Architecture impact assessment above, and **F-1's blast radius, which was measured rather than left inferred** — the two unaccounted lines are now identified as blank separators at added-lines 9 and 14.

**No finding was added, removed, softened or strengthened, and the verdict is unchanged.** F-1 and F-2 stand exactly as delivered; what was added is their classification and the measurement that establishes F-1's impact as LOW. Per the standing rule: this is **restatement and completion, not re-decision** — a changed verdict would have required a new verification act, not an amendment.

## What this verification did not do

No edit · no repair · no redesign · **no acceptance** — that is the PO/ARB's act · no reopening of `BC-7`'s domain model, the aggregate decision, `ADR-AIP-03`, `CAP-14` or `ADR-AIP-04` · no evaluation of whether the mechanism *should* reject non-vocabulary keys (out of scope) · no proposed wording for `OQ-10` — the same restraint the refinement and the correction both observed · no self-certification: independence is **partial and disclosed** · **this session does not complete its own assignment.**

---

**Traceability:** correction `58af4302` (subject) · delivery note `KOS-ARCH-BASELINE-003-correction-delivery-note.md` · Verification #2 `5dbf1493` · Verification #1 `2b5ac8ab` *(same model as this report)* · proposal `1c79b95f` line 279 · refinement `04420fbb`+`58af4302` · `.claude/runtime/workflow/*.json` re-measured 2026-08-17 (**15 records · 135 transitions · 50 grants**; `note` ×16 COMPLETE · `humanAct` ×3 COMPLETE · `reason` ×1 CANCEL · derived keys **0**; STARTs **38/38** `recordedBy: human`) · `.claude/scripts/workflow-state.php` (`note` absent; `humanAct` required on START; `reason` required on STOP) · `KOS-ARCH-BASELINE-001` seq 3 `humanAct` (quotation verified verbatim) · `R-34` · `P-2` · `INV-ATTR-2`.

---

> # VERIFICATION #3 DELIVERED — three corrections confirmed · two precision findings · independence partial and disclosed · acceptance is the PO/ARB's act
