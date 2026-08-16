# KOS-ATTR-ARCH-001 — Stage 1 rev 3
# Independent Architecture Review

**Session `S1-verification-attr-rev3-review` · role `verification` · ACTIVE (START seq 6, `recordedBy: human`) · grant `G-KOS-ATTRARCH-REV3-REVIEW` · 2026-08-16**

> ## VERDICT — REVISE, THEN APPROVE
> **The design's shape survives independent attack.** No finding invalidates the separate-Assurance-aggregate recommendation, the claim/evidence/assessment split, the one-claim-per-(act, dimension) rule, or the dependency direction. **Two major findings (F-1, F-2) should be repaired before the ARB approves Q-B1 and Q-B6 as written** — both repairs are small and identified below. The author's own doubt W-1 was **correct**; W-2 and W-3 **resolve inside the existing model** without new concepts.
>
> This review **recommends**; every Q-decision remains the Human/ARB's. Rev 3 remains `PROPOSED — NOT APPROVED`; the aggregate remains a *leading recommendation*; bounded-context placement remains *not confirmed*. Nothing here treats either as settled, and nothing here modifies any `KOS-ATTR-ARCH-001` artifact.

```
─────────────────────────────────────────────────────────────────────────────
 PROVENANCE / P-2 DISCLOSURE — applied to this review in the model's own terms
 Reviewer involvement:  NONE in the artifacts under review. This process
                        produced none of rev 1/2/3, the errata package, the
                        business assurance model, or the P-1..P-6 material.
 Execution context:     a FRESH claude-code session (34210a39-b8a4-42ca-b458-
                        42630fe4b510), routed per the Governance
                        recommendation "route elsewhere". It is NOT the
                        preparing session and NOT claude-code-session:fbc084f0
                        — satisfying both clauses of the seq-4 executionContext.
 Review scope:          the G-KOS-ATTRARCH-REV3-REVIEW scope — attack targets
                        T-1..T-4 in ARB priority order · the author's W-1..W-3
                        · recommendations on Q-A1/Q-A2/Q-B1..B8/Q-C1.
 Not independently established: the reviewer's own separation. Per INV-ATTR-2
                        this independence claim is DECLARED — asserted here,
                        recorded in this artifact, not attestable (the G-2
                        root gap applies to this act as to every other).
                        In C4's mandatory ladder: "I claim this was
                        independent."
─────────────────────────────────────────────────────────────────────────────
```

**Out-of-bounds honored (package §2.3):** the dependency *direction*, the evidence-ownership split, the domain-fact *event test*, the epistemic actor-identity wording, and the Stage-2 deferral are **not re-litigated** here. Where a finding touches an affirmed item (F-2), it applies the affirmed test to material the pruning never processed — it does not reopen the test. `KOS-ARCH-BASELINE-001` conclusions are not presumed anywhere below.

---

## 1 · T-1 + W-1 — the aggregate boundary, and the justification that proves too much

**The author's W-1 doubt is CORRECT, and the repair is identified.**

**1.1 Where rev 3 §1.1's chain breaks.** Rev 3 restates INV-ATTR-2 as *"at every read, a claim must yield a defined outcome, defaulting to its dimension's weakest"* and concludes that Claim + Assessments + current outcome must be **transactionally consistent**. But rev 3 §2 simultaneously makes the current outcome a **derivation**:

```
current outcome(claim) := outcome of the currently-established Assessment ELSE dimension's weakest
```

A derivation with an ELSE branch is **total by construction** — no read can ever observe an undefined outcome, transaction or no transaction. So INV-ATTR-2-as-read-rule is satisfied by the pure function alone, and **as written, "INV-ATTR-2 alone is sufficient" is unsound**. The author saw this (W-1).

**1.2 What actually requires the boundary.** The derivation is total only if *"the currently-established Assessment"* denotes **at most one** assessment. If two establishments can succeed concurrently, the derivation is not stale but **ambiguous** — the claim has two "current" outcomes, i.e. no classified outcome at all. The invariant doing the work is the uniqueness rule the author suspected: **at most one established assessment per claim at a time** — protected only by **serializing establishment per claim**, which is precisely an aggregate boundary's job.

**1.3 Answer to W-1's (a)/(b)/(c):**

- **(a) — primarily YES, implied.** The approved model **presupposes** outcome-definiteness throughout in the singular: INV-ATTR-2 *"it is treated as declared"* · INV-ATTR-3 *"**the** assurance outcome must remain visible"* · §2 *"**the** classified result"* · §5 compares *"**the** current outcome"* against a requirement. A claim with two simultaneous outcomes satisfies none of these sentences. The uniqueness rule is the mechanism-level restatement of that presupposition, **not a new business rule**.
- **(b) — as a precaution, RECORD IT.** This design has already been corrected once for resting a boundary on an unadopted invariant. "Presupposed" is weaker than "stated." **Recommendation: the ARB record a one-sentence interpretation of INV-ATTR-2/3** — *"a claim has exactly one current outcome at any time; hence at most one established assessment"* — as an interpretive note on the approved model, not a new candidate invariant. Cheap, and it closes the class of error permanently.
- **(c) — partially: the boundary is differently DESCRIBED, same stored extent.** A derivation is not state and cannot be a member of a transactional boundary. The minimal boundary is **`{ Assurance Claim · its Assessments }` with establishment serialized per claim**; "current outcome" is a guaranteed *read* over it, not a *member* of it. Evidence References stay outside, unchanged.

**1.4 A wording note folded in:** rev 3 §1.2's supersession row credits atomicity with preventing an "intermediate undefined state." Under the derivation, no undefined state is reachable even without atomicity — what atomicity actually prevents is the dual-established ambiguity (fatal, above) and a transient dip to weakest between supersede and establish (harmless, and conservative in the right direction). The scenario table's conclusion stands; its stated reason should be corrected with it.

**→ Finding F-1 (MAJOR, approval-blocking as written, small repair): Q-B1's boundary is RIGHT; its justification is WRONG as stated. Repair: restate the boundary as `{Claim · Assessments}` + serialized establishment, justified by the outcome-definiteness INV-ATTR-2/3 presuppose, with that presupposition recorded explicitly by ARB act.**

---

## 2 · T-3 + W-2 — one claim per (act, dimension) vs multiple claimants

**The rule SURVIVES — because the second "claim" in W-2's scenario is not a claim.**

**2.1 The scenario, decomposed in the model's own vocabulary.** The actor asserts *"lane X performed T-123"* — an assertion **about the governed act**: a Claim. Governance asserts *"the performer is not established"* — an assertion **about the claim's epistemic status**: that the evidence permits no more than Declared. That is, by rev 2 §2's own definitions, **an Assessment** (or simply the current-outcome derivation read aloud). W-2's third option is the correct one. The two statements live at different levels of the model and never contend for one claim slot. No per-claimant splitting is needed to represent the disagreement — the disagreement **is** the claim-vs-assessment structure working.

**2.2 The harder case the package didn't pose — contradictory content.** Actor: *"lane X performed T-123"*; a third party: *"lane Y performed T-123"*. Same act, same dimension, incompatible contents. Testing the three options:

- **Claims per (act, dimension, claimant) — REJECT.** It yields multiple simultaneous current outcomes per (act, dimension), destroying exactly the definiteness INV-ATTR-2/3 presuppose (§1.3 above) and demolishing rev 3's structural guarantee of INV-ATTR-3. The cure is worse than the disease.
- **Claimant on the Assessment — REJECT.** The Assessment already carries the Assessor; conflating claimant with assessor would collapse the very P-2 distinction (who asserts vs who judges) the model exists to keep apart.
- **The model's own mechanisms — ADOPT.** The third party's statement enters as **Evidence** against the standing claim (Actor Statements are already classified as evidence, rev 2 §2 — the same treatment extends to third-party statements). Assessment weighs the contending evidence; the outcome stays weak while the contradiction stands. Where the governing content itself must change, the standing claim is **superseded by a corrected claim** — the mechanism the approved model already requires: §4's claim lifecycle ends in `superseded`, and rev 1's vocabulary named the event (`AssuranceClaimSuperseded`). One **current** claim per (act, dimension); superseded claims stand in history.

**2.3 The rule, precised:** *"one claim per (Governed Act, dimension)"* should read *"one **current** claim per (Governed Act, dimension)"*. Claimant stays on the Claim, correctly — it identifies who asserted the currently-governing content, without exclusivity against other voices, which enter as evidence or as superseding claims.

**→ Finding F-3 (MODERATE): W-2 dissolves — no model change, one precision ("current"), and an explicit statement that status-disputes are assessments and content-disputes are evidence/claim-supersession. Depends on F-2 restoring `AssuranceClaimSuperseded` (next section).**

---

## 3 · The event vocabulary — a mechanical defect the pruning left behind

**Three of rev 1's events were never dispositioned, and the pruning's arithmetic is wrong.**

Rev 1 §9 proposes **thirteen** events (Claim 2 · Evidence 4 · Assessment 4 · Outcome 2 · Escalation 1). Rev 2 §7's table dispositions **ten** and reports *"ten proposed events reduce to four domain facts."* Missing from the table entirely — neither kept, pruned, nor classified:

| Dropped event | Why it cannot stay dropped |
|---|---|
| **`AssuranceClaimSuperseded`** | the **approved** model §4 gives the Claim a lifecycle ending in `superseded`; the current vocabulary cannot express its final transition. And by the ARB-affirmed event test itself — *would another context need to know?* — it qualifies as a **domain fact** for exactly the reason `AssessmentSuperseded` does: anyone relying on the prior claim must learn it no longer governs. It is also the mechanism §2.2 above needs. **Likely a FIFTH domain fact.** |
| **`EvidenceSuperseded`** | rev 2 **§5 itself still uses it** (*"makes evidence retirement expressible (`EvidenceSuperseded`)"*), and rev 3 §1.2's evidence-retirement scenario relies on it — a design leaning on an event its own vocabulary no longer contains. Disposition recommendation: **internal** — retirement matters inside the claim's boundary and cannot silently change an outcome; consumers learn through a superseding assessment. |
| **`AssessmentRejected`** | needed to state how establishment *fails*. Disposition recommendation: **internal**, symmetric with `AssessmentProposed` — a rejected proposal never changed any current outcome, so no other context needs to know. |

**Bounds note:** this does **not** re-litigate the affirmed pruning — it applies the affirmed test to three events the pruning never processed. The affirmed test itself, applied honestly, is what promotes `AssuranceClaimSuperseded`.

**→ Finding F-2 (MAJOR, approval-blocking for Q-B6 as written): "four domain facts" is the result of an incomplete disposition. Repair: disposition all thirteen; expected outcome five facts / seven internal / one removed as redundant / one read-side.**

---

## 4 · T-2 + W-3 — the three-concept split, and who assures the assurer

**The split survives contact; the recursion W-3 fears is already priced into the approved model.**

**4.1 Collapse tests (T-2).** *Outcome as projection of Assessment* — conceded and precisely framed in rev 3 §2; no defect. *Evidence-evaluation vs Assessment* — two judgment layers (per-artifact bearing; per-claim conclusion) with different subjects; both sub-establishment steps are internal, so the layering is harmless. *Subject-as-evidence* — for the *Recorded* rung, the act's own record is the evidence for the claim about the act; the model tolerates this correctly, and the approved model's honesty note ("the same actor may produce both the claim and the record") already names its weakness. **No concept collapses into another.**

**4.2 W-3 — assessor standing.** The recursion is real and **already acknowledged by the approved baseline**: category **C4 explicitly includes "Class-B review claims"** as Independence claims. So *"this assessor has Class-B standing"* is itself an **Assurance Claim** (kind: Independence, category C4) — assessed like any other, defaulting to **Declared** under INV-ATTR-2, with C4's mandatory disclosure ladder applying at every lower outcome. The regress terminates the only way assurance regresses ever do: at a disclosed Declared or at a human acceptance. Candidate INV-ATTR-6 — if adopted — even protects the regress from shortcuts (standing assurance does not transfer to conclusion assurance; the P-4 caveat and rev 2 §5's "not the verdict itself" already point the same way). Later found deficient standing is handled by the existing pattern: it *motivates a superseding assessment* — a deliberate act, not a retroactive rewrite.

**→ Finding F-4 (MODERATE): standing needs NO first-class new concept — `ES-005.4` is satisfied by recursion into the existing Claim model. The model must SAY this in one paragraph: standing is a C4 claim; absent assessment it is Declared; the regress terminates in disclosure or a human act.**

**4.3 Two smaller gaps found while working T-2:**

- **Establishment authority is unnamed (F-6, MINOR).** Rev 3 §3 states no human act is required for establishment — but never states **who or what performs it**. Establishment is the single most consequential write in the domain (the only act that changes a current outcome). It is not a gate (P-5 intact — it changes reported evidence, not permission), but it needs a named owner and rule before implementation is ever commissioned.
- **The `AssessmentAccepted → AssessmentEstablished` rename touches approved-model text (F-8, MINOR).** The approved §4 lifecycle says `accepted / rejected`. The same reasoning that makes Q-A1 require a Governance vocabulary amendment applies to this rename; no open Q-item currently carries it. Route it with Q-A1's amendment.

---

## 5 · T-4 — indirect paths from an assurance outcome to a gate

**No live indirect path exists. Two latent channels should be named and closed by a stated constraint — not by redesign.**

Verified against `workflow-state.php`: `assertTransitionAllowed` and the grant writer read **only** `transitions[]` and `grants[]`; no assurance-shaped input exists anywhere in the engine (Inv I holds). The declared direction is clean, as the package said. The search for *indirect* paths yields:

| Path | Status |
|---|---|
| **Through the human** — a human reads a disclosed outcome and declines a START | **by design, not a violation.** P-5 forbids *automatic* blocks; assurance informing human decisions is the model's purpose. Worth stating plainly: the only gate assurance may influence is the human. |
| **Through the Governance pen (LATENT #1)** — the same role owns Governance Assurance state **and** is the sole Authority-State writer (G-2). A Governance *practice* of refusing registrations on weak outcomes would be assurance-as-gate implemented in role behavior, invisible to the dependency structure | held today **by policy only** (P-5's automatic/human line). Not a defect in rev 3 — but rev 2 §9's claim that "no transactional path exists by which [coupling] could grow" removes the *transactional* channel while this *informational/behavioral* channel remains. Name it. |
| **Through storage co-location (LATENT #2)** — rev 1's third-record-in-the-same-document placement was withdrawn, but storage placement is open until Stage 2/implementation. If assurance state ever lands in the same document the engine folds, a future precondition reading it is one refactor away | dischargeable by one stated constraint (below). |
| Through instruments (AST-016 class) · through the escalation loop · through EKS reuse | clean: P-5 report-only with no write path · requirement changes human-gated, no self-amending part · candidate INV-ATTR-6 territory, already flagged by the design. |

**→ Finding F-5 (MINOR, recommendation): record one architectural constraint — *"the input set of every workflow/authority gate is closed: `{transitions, grants}`. Extending it requires an explicit ARB act."* This converts INV-ATTR-1's protection from a property of the current code into a property of the architecture, and closes both latent channels at once.**

**→ Finding F-7 (MINOR): the Assurance Gap derivation (rev 3 §4.1) consumes the act's Business Use Category — but no ownership row anywhere allocates WHO classifies a Governed Act into C1–C5, or when. One row in the ownership table repairs it.**

---

## 6 · Findings register

| # | Severity | Finding | Repair size |
|---|---|---|---|
| **F-1** | **MAJOR** | Q-B1's justification unsound as written: INV-ATTR-2-as-read-rule is satisfied by the total derivation alone; the boundary is actually required by the unstated uniqueness rule (at most one established assessment per claim), which the approved model presupposes but never states. Boundary restated: `{Claim · Assessments}` + serialized establishment; "current outcome" is a read, not a member | one section + one ARB interpretive sentence |
| **F-2** | **MAJOR** | Event disposition incomplete: rev 1 proposed **13** events, rev 2 dispositioned **10** ("ten" is a miscount); `AssuranceClaimSuperseded` (required by approved §4 lifecycle; passes the affirmed fact test — likely a **fifth domain fact**), `EvidenceSuperseded` (still used by rev 2 §5; recommend internal), `AssessmentRejected` (recommend internal) were silently dropped | one table |
| **F-3** | MODERATE | W-2 resolved: status-disputes are Assessments, not competing claims; content-disputes are evidence + **current-claim supersession**; rule precised to one **current** claim per (act, dimension); per-claimant claims REJECTED; claimant-on-assessment REJECTED | one paragraph + one word |
| **F-4** | MODERATE | W-3 resolved recursively: assessor standing **is** a C4 Independence claim; regress terminates at disclosed Declared or human acceptance; no new concept (`ES-005.4`) | one paragraph |
| **F-5** | MINOR | Two latent assurance→gate channels (role co-location; storage co-location) held by policy, not structure; recommend the closed-gate-input-set constraint | one recorded constraint |
| **F-6** | MINOR | Establishment authority unnamed — who/what may establish an assessment | one rule |
| **F-7** | MINOR | Act→category classification ownership unallocated (Gap-derivation input) | one ownership row |
| **F-8** | MINOR | `AssessmentEstablished` rename needs the same approved-model vocabulary-amendment routing as Q-A1 (approved §4 says "accepted") | route with Q-A1 |

---

## 7 · Recommendations on the open Stage-1 questions — decisions remain the Human/ARB's

| # | Recommendation |
|---|---|
| **Q-A1** | **Approve.** The generic-takes-the-domain's-name reasoning is sound; one term touched; no requirement altered. **Carry F-8's rename in the same amendment.** |
| **Q-A2** | **Defer to Stage 2** (concurring with the ARB's inclination): programme-canonical vocabulary should not outrun the unapproved architecture that motivated it. |
| **Q-B1** | **Approve after F-1 repair.** The boundary is right; approve it as `{Claim · Assessments}` + serialized establishment, on the recorded interpretation — not on "INV-ATTR-2 alone" as currently written. |
| **Q-B2** | **Approve as precised:** one **current** claim per (Governed Act, dimension), with F-3's dispute semantics stated. |
| **Q-B3** | **Approve** (value object; derived current outcome) — the derivation's totality should cite the F-1 uniqueness rule explicitly. |
| **Q-B4** | **Approve**, with F-5's constraint recorded so the enforcement is structural on every channel, not only the transactional one. |
| **Q-B5** | **Approve** (ARB-affirmed split; nothing found against it). Disposition `EvidenceSuperseded` per F-2. |
| **Q-B6** | **Not as stated.** Approve after F-2's full disposition — expected result five domain facts, not four. |
| **Q-B7** | **Approve** (Requirement first-class; Gap derived, never gating), adding F-7's ownership row and F-6's establishment rule. |
| **Q-B8** | **Approve** — the epistemic wording is ARB-affirmed and this review found nothing against it; T-2/W-3 work above is consistent with it. |
| **Q-C1** | **Observation only, not a recommendation of substance:** a new assignment on the same work item keeps the Stage-1→Stage-2 evidence chain in one record, and the record's role set already excludes implementation. The choice is the Human's. |
| **Q-D1** | **Closed — this review is its discharge.** |

---

## 8 · What this review did not do

**No `KOS-ATTR-ARCH-001` artifact modified** (rev 3, package, errata untouched) · no target architecture designed · no Stage 2 work · **no Q-decision taken** — §7 is recommendation only · no implementation, no technology commitment · nothing qualified, adopted, or closed · **no self-certification** — this review's own independence is Declared, per the disclosure block · **its own assignment not completed** — the `COMPLETE` on `S1-verification-attr-rev3-review` is a Governance act for another pen · `KOS-ARCH-BASELINE-001` conclusions not consumed · candidate INV-ATTR-4/5/6 treated as candidates throughout.

**Sequence, unchanged per the ARB:** this review → Human/ARB decisions on Q-A1/Q-A2/Q-B1…Q-B8 → then Stage 2 against the accepted baseline.

---

**Traceability:** grant `G-KOS-ATTRARCH-REV3-REVIEW` (workflow record seq 4–6; START `recordedBy: human`, performer clause satisfied by fresh-terminal routing) · review package `d23e82d1` (T-1…T-4, W-1…W-3, §2.3 bounds) · rev 3 `6c345e4d` · rev 2 `7feec4ff` · rev 1 `8dab1be1` · Business Assurance Model rev 3 APPROVED `5ab3b4e6` (§2 singular outcome · §3 invariants · §4 lifecycles incl. claim `superseded` · §5 C4 "Class-B review claims" · §6 loop) · P-2 `8de09453` (Class-A/B; disclosure shape used above) · accepted root gap `487fce74` · running domain `workflow-state.php` (`assertTransitionAllowed` input set verified) · `ES-005.4` · `R-34` · P-5 · P-6 · placement per `G-KOS-ATTRARCH-DESIGN-AMD2` + `docs/knowledgeos/reviews/README.md`.

---

> # REVIEW DELIVERED — findings and recommendations only · all decisions remain Human/ARB · this session does not complete its own assignment

---

## Erratum E-R1 (same day, self-found, disclosed to the PO before any disposition)

F-2's projected disposition — *"five facts / seven internal / one removed / one read-side"* — sums to **14**; there are **13** events. Correct projection: **five domain facts · SIX internal** (`EvidenceObserved` · `EvidenceAssociated` · `EvidenceEvaluated` · `EvidenceSuperseded` · `AssessmentProposed` · `AssessmentRejected`) **· one removed as redundant (`OutcomeClassified`) · one read-side (`OutcomeDisclosed`)** = 13. The finding itself (three events undispositioned; "ten" a miscount; `AssuranceClaimSuperseded` a likely fifth fact) is unaffected — the defective sentence was the reviewer's *expected outcome*, not the defect evidence. Recorded as an appended erratum, never a rewrite, per the programme's supersession discipline — and noted plainly: the reviewer committed the same *class* of error (a count) its own F-2 reports. The disposition remains Architecture's to make and the ARB's to approve.
