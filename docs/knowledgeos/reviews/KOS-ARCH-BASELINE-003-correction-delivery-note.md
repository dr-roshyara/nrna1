# BC-7 Refinement — Correction Delivery Note
## `KOS-ARCH-BASELINE-003` · Correction Report · Change Boundary Statement · Verification #3 Handoff

**Assignment:** `S4-architecture-bc7-correction` · role **architecture** · **ACTIVE, mutation owner** (seq 16 REGISTER · seq 17 HANDOFF from the verification lane · **seq 18 human START**) · **Grant:** `G-KOS-ARCHBASE3-CORRECT` (AUTHORIZED) · **2026-08-17**

> ## DELIVERED — three evidence corrections, nothing else. **Not verified. Not accepted. Assignment not closed.**
> **Diff scope, measured: 5 lines removed · 14 added · one file.** The proposal, the Verification #1 report and the Verification #2 report are **byte-untouched** (`git status` confirms only the refinement is modified). No redesign, no new invariant, no ADR, no code.

---

# 0 · 🔴 DISCLOSED FIRST — a divergence between the delivered prompt and the registered grant

**The correction prompt states the performer is *not* Verification #2 and describes a "fresh Architecture correction terminal". This pen IS the Verification #2 author.** I am reporting the conflict rather than resolving it silently, because the two governing statements disagree:

| Source | What it says about the performer |
|---|---|
| **The registered grant `G-KOS-ARCHBASE3-CORRECT`** | *"the PO's act **bars no one** from performing the correction, and the estate's precedent … permits the refinement author to correct its own artifact — **the safeguard is the bounded scope plus the independent review that follows, not exclusion**."* |
| **Record seq 16 `executionContext`** | *"no eligibility bar is placed on the performer … **FORWARD CONSTRAINT: whoever performs this correction MUST NOT verify it**."* |
| **The delivered prompt** | *"You are NOT … Verification #2"* · *"the **fresh** Architecture correction terminal"* |

**How this lane proceeded, and why.** The PO's START act (*"START: the BC-7 correction — when ready"*) was delivered to **this** terminal, where the record's own rule places performer compliance (*"compliance rests on where the PO/ARB starts the session"*); the grant expressly bars no one; and the three corrections are **mechanical and independently re-checkable** (a re-measurable census, a table-cell completion derived from an already-proposed classification, and a recorded question). **The START was recorded with this disclosure inside the act itself (seq 18), so the divergence is on the record, not in a reply.**

**The PO/ARB's choice is preserved at near-zero cost:** every edit is a **single-line revert**, the diff is five removed lines, and a fresh pen could reproduce the work in minutes from Verification #2's findings. **If a fresh pen is preferred, revert and re-run — nothing here is load-bearing on who typed it.** What is *not* optional is the forward constraint, which this lane accepted before the correction existed and restates in §3: **this process must not perform Verification #3.**

---

# 1 · Correction Report

## Correction 1 — evidence measurement repair (V#2 **R-1**)

| | |
|---|---|
| **Site** | refinement §2, evidence-measured-this-session table |
| **Original statement** | `| **Non-canonical keys anywhere in the estate** | **0** | key census against the vocabulary |` |
| **Corrected statement** | the row now reports **20 instances** — `note` ×16 on COMPLETE · `humanAct` ×3 on COMPLETE · `reason` ×1 on CANCEL — with the method and the measurement point (**re-measured 2026-08-17 after seq 18: 15 records · 133 transitions · 49 grants**) |
| **Evidence source** | independent re-measurement performed for this correction (not copied from Verification #2's figure, per V#2's own R-4 advisory that a census must be re-measured and dated rather than restated): key census against the mechanism's read set, plus `grep` confirming the string `note` **appears nowhere** in `.claude/scripts/workflow-state.php` |
| **Impact assessment** | **T-1 remains SUPPORTED, and is better evidenced.** The 20 instances are the *empirical proof* of **T-1b** (a non-vocabulary key is stored and inert), which the refinement had argued only hypothetically. **None of the 20 is a derived key**, so **T-1c stands unchanged at 0 of 133**. **The architectural conclusion is preserved verbatim: storage acceptance ≠ semantic authority.** No mechanism change, no key validation, no schema enforcement, no workflow-implementation change was made or implied. |

## Correction 2 — DP-6 replacement completeness (V#2 **R-2**)

| | |
|---|---|
| **Site** | refinement §4.4, item ③ |
| **Original statement** | quoted **two** cells of the target row (`| DP-6 | State is folded… |`) and supplied a **two**-cell replacement — while proposal line 279 has **three** cells (the third being `` `Observed` + **`Proposed`** as T-1 ``) |
| **Corrected statement** | the instruction now quotes the **complete three-cell row** and supplies a **complete three-cell replacement**, whose class cell reads `` **`Observed`** (T-1a/T-1b — structural, in source) + **`Proposed`** (T-1c — the writer obligation), **as T-1** `` |
| **Evidence source** | proposal line 279 extracted verbatim; the class value follows **mechanically** from T-1's own restatement in §4.4 ① (two structurally `Observed` parts + one `Proposed` obligation) — an explanatory note stating this was added so the PO/ARB can see the value is *derived, not invented* |
| **Impact assessment** | **Repairs an edit instruction that would have silently dropped a classification marker on application.** No policy created, no invariant introduced, no domain rule changed. DP-6's decision content is unchanged. |

## Correction 3 — `recordedBy` observation (V#2 **R-3**)

| | |
|---|---|
| **Site** | refinement §5.4, item ③ (the OQ-10 replacement text) |
| **Original statement** | OQ-10 asked whether the recorder class should be constrained and whether `RecordedBy` is a **closed vocabulary** — but not whether it has **one referent** |
| **Corrected statement** | OQ-10 now additionally asks *"does `recordedBy` carry ONE referent?"*, with the observed distinction recorded (**START** = act **origin**; **REGISTER/COMPLETE/CANCEL** = acting **role**; the two `architecture` HANDOFFs = performing **lane's** role) and its evidence (**6 of 37 STARTs** are `recordedBy: human` while their own `humanAct` text states Governance recorded the START — e.g. `KOS-ARCH-BASELINE-001` seq 3) |
| **Evidence source** | record census + the quoted `humanAct` text of the cited transitions |
| **Impact assessment** | **A question is recorded; nothing is decided.** No rename, no new model, no ADR, no vocabulary decision — the row states this prohibition explicitly and notes that Verification #2 declined to propose wording because that would be modelling, a restraint this correction inherits verbatim. |

## Scope discipline — what was deliberately NOT done

**No finding required redesign, so the STOP clause was never triggered.** Three items from Verification #2 lie **outside** the three authorized corrections and were left untouched, reported rather than absorbed: **R-4** (dating *all* censuses — implemented only for the one cell corrected, since a census cannot be corrected without stating when it was measured; the refinement's other denominators still stand at *its* measurement point, and its own N-2 advisory remains open) · **R-5** (the PO's *"amend the VERIFY2 grant before registration"* instruction is still dangling with no content — a Governance/PO item) · **R-7 / OQ-13** (`authorizationLinkage`) — correctly deferred, deferral not recorded as a decision. **None is a correction defect; each needs its own act.**

---

# 2 · Updated Refinement Proposal

**Delivered as the edited artifact** — `KOS-ARCH-BASELINE-003-domain-model-refinement.md` — carrying a correction banner at its head that states the three changes, their grant, and the disclosure. **Contents: corrected measurement · completed DP-6 replacement · extended OQ-10 observation. No other semantic change**, verified by diff: **5 lines removed / 14 added**, all within the three named sites plus the banner.

---

# 3 · Change Boundary Statement

> **The correction does not change:**
> **· BC-7 boundary** — BC-7 still owns workflow lifecycle and recorded orchestration history; Governance still owns authority meaning.
> **· the aggregate decision** — `WorkItem` remains the aggregate root; not re-argued, not weakened, RA-4 **not reopened**, OQ-9 untouched.
> **· the strategic model** — `KOS-ARCH-BASELINE-002`'s accepted landscape/context/capability maps are inputs, never overwritten.
> **· `ADR-AIP-03`** — unchanged and not reopened.
> **· `CAP-14`** — unchanged and not re-scoped.
> **· `ADR-AIP-04` status** — **still deferred**; no role-model decision made or implied.

**Additionally unchanged:** invariants I-1…I-10, T-2, T-3, T-4 (**I-5's text and its Governance ownership untouched** — only the proposal's *row about* it is corrected, and that correction was already proposed by the refinement, not by this pass) · rejections RA-1…RA-6 · every other open question · Verification #1 and Verification #2 reports (byte-untouched) · the original proposal (byte-untouched — **all corrections are still *proposed replacement texts* awaiting the PO/ARB, never applied to the proposal itself**) · the mechanism (`workflow-state.php`) and all production code · no new ADR was created, and none was needed.

**One record mutation was performed and is disclosed:** the **seq-18 START** of this lane, recording the PO/ARB's act verbatim with the performer disclosure inside it — per the practice accepted on this same record at seq 3 and seq 12. **No other record was written; no throwaway probe survives** (the probe copy used earlier to test the START gate was made under the verification grant's explicit permission and is not part of any record).

---

# 4 · Verification #3 Handoff Note

**Prepared for Independent Verification #3 — which this pen must not perform.**

> **`R-34`/`P-2` and the seq-16 forward constraint: the correction author cannot verify its own correction.** This process authored **both Verification #2 and this correction**, so it is doubly barred. **A fresh process is required**, and — as flagged to the PO before the correction was registered — one that is not this pen is the clean option.

**What Verification #3 should verify:**

1. **Correction accuracy** — re-measure the census independently (expect **20** non-vocabulary key instances and **0** derived keys; the totals will have drifted past 133/49, so check the *proportions*, not the denominators, and confirm the corrected cell carries its measurement point). Confirm `note` is absent from the mechanism source. Confirm DP-6's replacement now covers **all three** cells and that its class value follows T-1's restatement rather than introducing a new classification. Confirm OQ-10 records the referent question **and proposes no solution**.
2. **No unintended model drift** — the diff should be **5 removed / 14 added lines** in one file, confined to §2's census row, §4.4 ③, §5.4 ③, and the header banner. **Any hunk outside those four sites is a finding.**
3. **Constraints preserved** — re-derive §3's Change Boundary Statement rather than accepting it: aggregate decision, BC-7 boundary, strategic model, `ADR-AIP-03`, `CAP-14`, `ADR-AIP-04` deferral, and the untouched status of the proposal and both verification reports.
4. **The §0 divergence** — assess whether the performer disclosure is adequate, and say plainly if it judges the work should be redone by a fresh pen. **That verdict is legitimate and expected; the revert cost is five lines.**
5. **Falsify, don't confirm.** Verification #2 found a false `Observed` cell in a document whose author had re-derived everything else correctly. **Assume this correction contains one too, and go looking for it.**

**Next actors, in order:** **Governance** (records what it must, and closes nothing it may not) → **Independent Verification #3** → **PO/ARB acceptance** of the proposal *as refined and corrected* → the separate closures of the refinement, verification and correction lanes.

---

# 5 · Stop condition honoured

**This lane stops here.** It does **not** verify its own correction · does **not** accept anything · does **not** complete or close its own assignment (`G-1`) · records no `COMPLETE` and no onward `HANDOFF` without the PO/ARB's act. **Nothing was implemented, no code changed, no ADR created, no new architecture introduced, and no decision the record had already made was revisited.**

---

**Traceability:** grant `G-KOS-ARCHBASE3-CORRECT` (three required corrections verbatim; eligibility note; forward verification boundary) · record `KOS-ARCH-BASELINE-003` seq 16 (REGISTER, `S4-architecture-bc7-correction`) · seq 17 (HANDOFF from `S1-verification-bc7-refinement-v2`, tokenRef the V#2 report) · **seq 18 (human START, recorded by this lane with disclosure)** · source findings `KOS-ARCH-BASELINE-003-verification-2-report.md` (R-1, R-2, R-3; and R-4/R-5/R-7 left open) · corrected artifact `KOS-ARCH-BASELINE-003-domain-model-refinement.md` · inputs `KOS-ARCH-BASELINE-003-bc7-domain-model-proposal.md`, `KOS-ARCH-BASELINE-003-verification-report.md`, the accepted `KOS-ARCH-BASELINE-002` set · re-measurement of `.claude/runtime/workflow/*.json` (15 records · 133 transitions · 49 grants, 2026-08-17 after seq 18) · `.claude/scripts/workflow-state.php` (`note` absent) · `R-34` · `P-2` · `INV-ATTR-2` · `G-1` · `ES-005.4`.

---

> **Architecture correction delivered. Independent Verification #3 is required. This session does not verify its own correction, accept it, or close its assignment.**
