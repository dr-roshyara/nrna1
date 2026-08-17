# Verification #2 Report
## `KOS-ARCH-BASELINE-003` — BC-7 Domain Model **Refinement** Proposal

**Assignment:** `S1-verification-bc7-refinement-v2` · role **verification** · **ACTIVE, mutation owner** (record seq 13 REGISTER · seq 14 HANDOFF · **seq 15 human START**) · **Grant:** `G-KOS-ARCHBASE3-VERIFY2` (AUTHORIZED) · **2026-08-17**
**Subject:** `KOS-ARCH-BASELINE-003-domain-model-refinement.md` (delivered 22:05 under seq 12) · **Producer:** `S4-architecture-bc7-refinement-2` (HANDED_OFF)

> ## RECOMMENDATION — **OPTION A · APPROVE FOR PO/ARB ACCEPTANCE**, subject to **two mechanical corrections** (§5)
> **All three refinement findings are independently SUPPORTED.** F-1/C-1 **SUPPORTED**; C-2 **SUPPORTED** (the T-1 restatement is a valid domain invariant, and stronger than the text it replaces); C-3's classification **(D) mis-scoped invariant statement — CONFIRMED**, on evidence I derived before reading the refinement's reasoning. **Nothing in the model, the mechanism, or any invariant requires change** — which is why Option B would be disproportionate.
>
> **But the refinement is not error-free.** One of its own `Observed` measurements is **FALSIFIED** by independent census — *"non-canonical keys anywhere in the estate: 0"* is wrong; there are **20** — and one proposed replacement (**DP-6**) is an **incomplete edit instruction** that would drop a table cell if applied literally. **Neither damages a finding; the falsified measurement in fact strengthens T-1b.** Both must be corrected before the artifact enters the canon, for the same reason `KOS-ARCH-BASELINE-001` was corrected today: a false high-confidence `Observed` claim in an accepted document is a defect in its own right.
>
> **No acceptance is performed. Nothing was modified. This report does not close its own assignment.**

```
─────────────────────────────────────────────────────────────────────────────
 PROCESS IDENTITY — SELF-DECLARED, as the seq-13 executionContext requires
 What I can evidence (Observed, self-declared):
   · I authored NO part of: the BC-7 domain model proposal · the refinement
     (either lane) · Verification #1 · ADR-AIP-03 · CAP-14 · BASELINE-002.
   · I am not the acceptance authority and claim no acceptance power.
   · Process: claude-code session 34210a39-b8a4-42ca-b458-42630fe4b510.
 Prior work in THIS estate, disclosed because it is real (different work items):
   · KOS-ARCH-BASELINE-001 Phase A Verification #1 and Verification #3
   · KOS-ATTR-ARCH-001 rev-3 independent architecture review
   · EM-IMPL-002 (Election lane): Phase 1B doubles review; GREEN-1 author
   None of these is a BC-7 artifact. Two are USED as evidence below, and that
   is disclosed at each point of use.
 Prior BC-7 exposure, stated against the PO's "cannot be established as
 absent" standard: limited to (a) BASELINE-002 session-log summaries and
 (b) Verification #1's headline verdict as it appears in a commit subject.
 BOTH are inputs the grant expressly authorizes me to read in full, so
 neither is disqualifying exposure. I had NOT read the proposal, the
 refinement, or the V#1 report before this assignment.
 ⚠️ MODEL SEPARATION — Unknown, and material. The refinement declares its
   author as "Claude Opus 5 (1M context)". My system prompt identifies this
   process as Fable 5, while a /model selection in this session named
   "Opus 5 (1M context)" and said it applies to new sessions. I cannot
   attest which model executes this turn. IF it is the same, PROCESS
   separation is still real but MODEL separation is nil — a shared blind
   spot is then possible. The PO/ARB can establish this; I cannot.
 LIMITATION (INV-ATTR-2/G-2): all of the above is DECLARED, not attestable.
   The record cannot attribute execution to a process.
─────────────────────────────────────────────────────────────────────────────
```

**Method:** re-derivation first, comparison second. Every load-bearing claim was recomputed from `workflow-state.php` and from a fresh census of all 15 live records **before** its treatment in the refinement was consulted, so agreement below is convergence, not deference. **Mutation forbidden and not performed:** no record written, no state altered, no throwaway copy needed (every check was a read).

**One open item in the record, carried rather than glossed.** Record seq 15 states that the PO's instruction *"APPROVED: Amend Verification #2 grant before registration"* arrived after registration **with no amendment content**, so this lane works under the grant as registered. **If the PO intended additional scope, this report is incomplete against that intent** — and the amendment, being append-only, would still need appending.

---

# 1 · Scope and evidence inspected

**Inspected (all within the grant's permitted set):**

| Evidence | What was done with it |
|---|---|
| The refinement proposal (312 lines) | the subject — read in full, each of its 9 proposed replacements checked against its target |
| Verification #1 report (103 lines) | read in full; checked for faithful representation by the refinement |
| The original BC-7 domain model proposal (379 lines) | **the 9 replacement target lines extracted verbatim** and compared with the refinement's quotations |
| `.claude/scripts/workflow-state.php` | `authorized`, `append`, `grant`, `foldSessions`, `assertTransitionAllowed`, `identity`, HANDOFF/START/COMPLETE/CONTINUATION cases — re-derived, not accepted |
| **All 15 live work-item records** | **independent census: 130 transitions · 48 grants**, cross-tabulated; key census; the two `architecture` recorders inspected individually |
| Workflow record `KOS-ARCH-BASELINE-003` | assignment/grant chain, seq 13–15, the VERIFY2 scope verbatim |
| **Accepted** Phase A baseline v1.1 (`I-5`, `I-7`, ownership table) | the canonical invariant texts C-3 turns on — **disclosed:** I verified this artifact myself in a prior assignment |

**Not inspected, by grant:** `ADR-AIP-03` as a decision input · `CAP-14` scope · the `ADR-AIP-04` role question · implementation architecture (none exists). **Not reopened:** OQ-9, the aggregate decision, the BC-7 boundary.

---

# 2 · Evidence classification

| # | Statement | Class | Basis |
|---|---|---|---|
| 1 | `authorized` requires `--session`, refuses an unregistered session, then decides by grant-status + scope equality **with no session reference in the verdict** | **Observed** | source, re-derived line by line |
| 2 | `START` contains **zero** grant references | **Observed** | source |
| 3 | `assertTransitionAllowed` **folds the record as its first act**, so a submitter never supplies the state its transition is judged against | **Observed** (structural) | source |
| 4 | `append` has **no key whitelist** — a submitted key is persisted as given | **Observed** | source + §3 evidence below |
| 5 | `grant` overwrites `registeredBy`; `append` overwrites `seq` | **Observed** | source |
| 6 | `foldSessions` reads only its defined fields; **no derived key is ever read from a record** | **Observed** | source |
| 7 | 15 records · **130** transitions · **48** grants, all AUTHORIZED, all `registeredBy: governance`, **0** missing `humanActRef` | **Observed** | my census (2026-08-17, after seq 15) |
| 8 | **0 of 130** transitions carry a derived key (`mutationOwner`/`state`/`workItemState`/`assignmentState`) | **Observed** | my key census |
| 9 | Cross-tab: REGISTER 37/37 governance · START 37/37 human · COMPLETE 16/16 governance · CANCEL 1 governance · STOP 1 human · **HANDOFF 36 governance + 2 architecture** | **Observed** | my cross-tabulation |
| 10 | Both `architecture` recorders are **HANDOFF**, and in both the **`from` lane is the recording lane itself** | **Observed** | record inspection, both instances |
| 11 | **20 stored keys the mechanism never reads** — `note` ×16 (COMPLETE), `humanAct` ×3 (COMPLETE), `reason` ×1 (CANCEL) | **Observed** | my key census + source grep (`note` appears nowhere in the mechanism) |
| 12 | I-5 canonical text: *"Only Governance writes authority, and only registering a recorded human act"* | **Observed** | accepted Phase A baseline v1.1 §8 |
| 13 | I-7: process identity is evidential, never authorising; **no code path reads process identity for a gate** | **Observed** | accepted baseline + my own prior identity-API scan of every script |
| 14 | `identity` returns the **last** AUTHORIZED `grantId` by an overwriting loop, with **no session filter** — a work-item-scoped value inside a session-scoped answer | **Observed** | source (N-1 independently confirmed) |
| 15 | `recordedBy` has **two different referents by type** — act *origin* on START, acting *role* on REGISTER/COMPLETE/HANDOFF | **Observed** | 6 of 37 STARTs are `recordedBy: human` while their own `humanAct` text states Governance performed the registration |
| 16 | The refinement's process claims (read order; commission unread; candidates tested not inherited) | **Declared** | its own §0; unverifiable in principle |
| 17 | Whether this report and the refinement share a model | **Unknown** | disclosed above |

---

# 3 · Findings by F-1/C-1, C-2, C-3

## 3.1 · F-1 / C-1 — **SUPPORTED**

**Re-derived independently.** `case 'authorized'` does exactly four things, in order: requires `--session` (absent ⇒ usage error, exit 64, *not an answer*); folds and **refuses** when the session is absent from the Session Registry (exit 65, `'unknown session'`); computes `∃ g : g.status = AUTHORIZED ∧ g.scope = requestedScope`; emits the boolean. **The verdict step contains no session reference of any kind** — not state, role, ownership, or predecessor.

The three commission questions, answered from that derivation:

| Question | Finding |
|---|---|
| Does session existence participate **only** as an answerability/lifecycle precondition? | **YES.** It gates *whether the question is answered at all*, never *what the answer is*. |
| Does authority meaning remain **Governance-owned**? | **YES.** BC-7 matches an **opaque string by equality** and never parses it; the refinement preserves this verbatim and §10.1's rule (*no operation of the form "may X do Y?"*) is untouched. **Equality-matching is not interpretation.** |
| Does grant evaluation interpret lifecycle state? | **NO.** Zero lifecycle inputs to the verdict. |

**The refinement's handling is correct and better than V#1's.** V#1 identified the falsity; the refinement adds the distinction that makes the repair sound — *answerability precondition* vs *verdict function* vs *consistency invariant* — and correctly finds that **the third does not exist** (`START` has zero grant references, re-confirmed). Its §3.2 conclusion is also right and is the non-obvious part: **F-1 strengthens the one-root recommendation and weakens RA-4**, because a genuine cross-part read makes the two parts *less* separable. It draws that inference **without reopening OQ-9** — correct restraint.

**Site enumeration verified.** The falsified claim does appear **three** times (proposal lines 135, 137, 307), and **all three replacement targets are quoted verbatim and located correctly** — I extracted each line and compared character by character. Neither input document enumerated them; V#1 named the defect, the refinement found its full extent.

## 3.2 · C-2 — **SUPPORTED**

**Is *"derived state has exactly one producer — the fold"* a valid domain invariant? YES**, and I confirmed each of its three parts from source rather than from wording:

| Part | Independent verification |
|---|---|
| **T-1a** — a submitted transition never supplies the state it is validated against | **CONFIRMED, structural.** `assertTransitionAllowed` calls `foldSessions($record)` as its **first statement**, then tests the submitted transition against the computed state. The submitter cannot reach the premise. |
| **T-1b** — a derived value carried on a transition has **no standing** | **CONFIRMED.** `foldSessions` reads only `type`, `session`, `role`, `predecessor`, `executionContext`, `from`, `to`. A `mutationOwner` key would be inert. |
| **T-1c** — writer obligation, not refusal: no derived projection is written | **CONFIRMED by census: 0 of 130.** |

**The four-way distinction the commission asks about is drawn correctly**, and matches source exactly: **refusal** operates on type/required fields/ownership/stickiness/writer class — and there is **no key whitelist**, so a derived key would be *stored, not refused*; **overwrite** is real but confined to two mechanism-assigned values (`seq`, `registeredBy`); **non-consultation** is the fold's definition; **writer obligation** is the practice measurement. **The refinement is right that the invariant holds by non-consultation, not by refusal at the boundary** — and right that this is a *strengthening*: the restated form rests on the fold's definition, which no future write can falsify, while the original absolute rested on a census a single write could break.

**Its self-criticism is also correct:** the original T-1 was not merely undemonstrated but **false as an absolute** (a derived key *is* accepted as input in the sense of being stored), and the defect really does appear at **three** sites — T-1 (240), §5.4 (208) and **DP-6 (279)** — with DP-6 unnamed by V#1. All three targets verified present and correctly located.

### 🔴 One refinement measurement FALSIFIED — and it strengthens the finding it sits under

The refinement's §2 evidence table asserts **"Non-canonical keys anywhere in the estate — 0 — key census against the vocabulary."** **This is false.** My independent key census finds **20 keys the mechanism never reads**:

| Key | Where | Read by the mechanism? |
|---|---|---|
| `note` | **16 × COMPLETE** | **No** — the string `note` appears nowhere in `workflow-state.php` |
| `humanAct` | **3 × COMPLETE** | No — canonical for START; COMPLETE's precondition never reads it |
| `reason` | **1 × CANCEL** | No — canonical for STOP; CANCEL's branch reads nothing |

**Why this does not damage C-2, and why it must still be corrected.** It is the **empirical proof of T-1b**: the refinement argued that a submitted non-vocabulary key *would* be stored and *would* be inert; the estate demonstrates that it *is*, twenty times over, with no effect on any derived value (and **0 of 130** derived keys — T-1c stands). So the invariant is *better* evidenced than the refinement claims. **But the cell is a false `Observed` measurement**, and it is the second specimen of the refinement's own **OQ-12** (invariants should carry an enforcement class): `note` is a live instance of *stored-but-never-consulted*, which is exactly the category the refinement says is invisible at the point of statement.

**Effect on the proposal: none.** The false cell lives in the refinement's evidence table and is **not carried into any of the nine proposed replacement texts** — I checked. So it blocks nothing; it must be corrected in the refinement record itself.

## 3.3 · C-3 — classification **(D) mis-scoped invariant statement: CONFIRMED**

I derived the four sub-questions from source and the **accepted** baseline before reading §5's reasoning.

| Sub-question | Finding | Evidence |
|---|---|---|
| Does **I-5 govern authority writes only**? | **YES — decisive.** Canonical text in the **accepted** Phase A baseline v1.1: *"**Only Governance writes authority**, and only registering a recorded human act"*, owner Governance, evidence `G-2` + grant practice. It is a rule about the **grant register**. | accepted baseline §8 |
| Are **lifecycle transitions separate from authority records**? | **YES.** The two-record rule (I-4): `transitions[]` and `grants[]` share no fields and have disjoint writer rules — `grant` refuses any writer role but `governance`; `append` has no such check. | source + schema |
| Does **`recordedBy` represent authority ownership**? | **NO.** T-4 keeps attribution a claim; I-7 makes process identity evidential only — and **no code path reads a recorder identity for any gate** (a scan I ran myself in a prior assignment, disclosed). | source + accepted baseline |
| Are **HANDOFF writers conforming lifecycle actors**? | **YES, in both instances.** `Inv C` refuses any handoff not from the current mutation owner; my inspection shows **both** `architecture`-recorded HANDOFFs have `from` = the recording lane itself. They are not merely permitted — they are **the actor the contract requires**. | source + record inspection |

**Therefore: (A) invariant violation — NO** (I-5 is not reached by a lifecycle transition at all; measuring it there is the very conflation I-4 forbids). **(B) missing mechanism enforcement — NO** (no rule exists that only Governance may record lifecycle transitions; `recordedBy` is constrained only on COMPLETE and CONTINUATION, and the mechanism attributes those checks *verbatim* to **G-1** and **Inv E**, not I-5 — I read both refusal strings. Adopting (B) would invent a rule that would make the `Inv C`-required actor a forbidden recorder). **(C) missing conceptual distinction — NO as a diagnosis** (the distinction exists twice already, in T-4 and I-7; what is missing is its *application* in one table cell). **⇒ (D), and the defect is in the proposal's I-5 row, not in the invariant, the mechanism, or the model.** The refinement's D.1–D.5 are all sound; D.4 in particular is verified — a HANDOFF confers nothing (`$owner = null`; ownership passes only at START, 37/37 human-recorded under the G-3 conjunction), so the path from *"an architecture lane recorded a transition"* to *"authority was written by a non-Governance actor"* **does not exist in the mechanism**.

### 🟡 One addition C-3 should carry — a ubiquitous-language defect neither document names

**`recordedBy` does not have one meaning.** On a **START** it names the act's **origin** (the human), while Governance performs the registration; on **REGISTER/COMPLETE/CANCEL** it names the **acting role**; on the two `architecture` HANDOFFs it names the **performing lane's role**. **Evidence: 6 of 37 STARTs are `recordedBy: human` while their own `humanAct` text states that Governance recorded the START** (e.g. `KOS-ARCH-BASELINE-001` seq 3: *"Registered by Governance … registering a START is a GOVERNANCE act"*). A reader asking *"who registered this transition?"* gets the wrong answer from the field on **37 of 37** STARTs.

This **reinforces (D)** — a field with an unstable referent is exactly what invites an invariant to be measured against the wrong part — and it is **not captured** by the refinement's rewritten **OQ-10**, which asks whether `RecordedBy` is a *closed vocabulary* but not whether it has *one referent*. **Recommendation: OQ-10 should carry both questions.** I do not propose wording — that would be modelling.

---

# 4 · Strategic boundary protection — all six CONFIRMED unchanged

| Item | Finding |
|---|---|
| **BC-7 owns lifecycle + recorded orchestration history** | **UNCHANGED.** No replacement text alters the boundary; §3.3 preserves *"Governance owns authority meaning"* verbatim. |
| **Governance owns authority meaning** | **UNCHANGED** — and *strengthened* in the I-5 row replacement, which states total enforcement where I-5 applies (my census: **48/48** grants `registeredBy: governance`, **48/48** with a `humanActRef`). |
| **CAP-14** | **UNCHANGED.** Not referenced except to state it is untouched. |
| **`ADR-AIP-03`** | **UNCHANGED, not reopened.** Not read as a decision input, not cited as amendable. |
| **`ADR-AIP-04`** | **STILL DEFERRED.** No role-model decision made or implied; OQ-1 carried untouched. |
| **`WorkItem` aggregate root** | **UNCHANGED.** Not re-argued; F-1 marginally strengthens it; RA-1…RA-6 and OQ-9 stand as the proposal left them. |

**Falsification attempted on this section specifically:** I searched the refinement for any statement that *amends* rather than *cites* a Governance-owned artifact. Found none — I-5's text is untouched (only the proposal's row *about* it changes), and T-4/I-7 are cited as already holding the C-3 distinction, not amended.

---

# 5 · Remaining risks

| # | Risk | Severity |
|---|---|---|
| **R-1** | **The false `Observed` cell** (*"0 non-canonical keys"*; actual 20). A false high-confidence measurement entering the canon is the exact defect class `KOS-ARCH-BASELINE-001` was corrected for today. **Correct the refinement's §2 cell; the finding it supports needs no change and is better evidenced without it.** | **must fix — but blocks nothing downstream** |
| **R-2** | **DP-6's replacement is an incomplete edit instruction.** Proposal line 279 has **three** cells (`| DP-6 | text | `Observed` + **`Proposed`** as T-1 |`); the refinement quotes and replaces only **two**, leaving the class cell's fate unstated. Applied literally it would **drop the class marker** — and since T-1 is being restated as part-`Observed`/part-`Proposed`, that cell needs an explicit value. | **must fix before applying** |
| **R-3** | **`recordedBy`'s unstable referent** is not carried by OQ-10 (§3.3). | should add |
| **R-4** | **Census drift is already live:** the refinement measured 126/47, I measure **130/48**. Every proportion holds (0 derived keys; `CONTINUATION`/`FAIL` still 0; all grants AUTHORIZED) and only denominators moved — but the refinement's own **N-2** recommendation (date every census) should be adopted, or the next reader re-litigates the same numbers. | advisory |
| **R-5** | **The unsupplied VERIFY2 grant amendment** (record seq 15). If the PO intended extra scope, this report is incomplete against it. | PO to resolve |
| **R-6** | **All independence disclosures remain `Declared`, not attestable** (INV-ATTR-2) — the refinement's, V#1's, and **mine**; plus **model separation between this report and the refinement is `Unknown`** (§0). This is the standing G-2 root gap, not a defect of this work. | standing |
| **R-7** | **N-1/OQ-13 (`authorizationLinkage`) is a real modelling smell left open** — with **48/48 grants AUTHORIZED**, `identity` returns the *same* last `grantId` for every session in a record. Correctly raised and correctly not answered by a lane forbidden to model. | correctly deferred |

---

# 6 · Architectural recommendation

> # OPTION A — **APPROVE FOR PO/ARB ACCEPTANCE**
> **subject to R-1 and R-2, which are corrections to a measurement and to an edit instruction — not architecture revisions.**

**Why not Option B.** Option B would return the work for *architecture* refinement, and **no architectural work is outstanding**: all three findings are upheld on independent evidence; the mechanism is conformant; I-5, T-4, I-7, DP-3, I-4 and the aggregate decision all stand unamended; the nine replacement texts are correctly targeted (8 quoted verbatim, 1 incomplete); and the two defects I found are a wrong number in an evidence table and a missing table cell in one replacement. **Returning the model for redesign on that basis would be disproportionate — and the refinement's own reasoning for choosing (D) applies to this recommendation too: the cheapest true repair is the correct one.**

**What I would put on the PO/ARB's desk, in order:** ① correct §2's non-canonical-key cell (and note that the correction *strengthens* T-1b, with `note`×16 as the specimen) · ② complete DP-6's replacement with its class cell before applying · ③ decide whether OQ-10 also carries the `recordedBy`-referent question · ④ then the acceptance act on the proposal **as refined**, and the separate closures of the refinement lane and this verification lane.

**No acceptance authority is granted or claimed.** The PO/ARB remains the final decision authority.

---

# 7 · Restrictions honoured

**No redesign** — where I found a defect I stated it and stopped; I proposed no wording, no alternative model, no aggregate change. **No replacement architecture.** **Nothing modified** — not the refinement, not Verification #1, not the proposal, not any record: this report is the only file I wrote, and **mutation was neither performed nor needed** (every check was a read; no throwaway copy was required). **`ADR-AIP-03` not reopened · `CAP-14` unchanged · `ADR-AIP-04` not decided · no implementation authorized · no new architecture decision introduced.** **No self-certification** — my own independence is `Declared` and my model separation `Unknown`, both disclosed in §0. **This report does not complete its own assignment** — the `COMPLETE` on `S1-verification-bc7-refinement-v2` is a Governance act on the PO/ARB's authority.

---

**Traceability:** grant `G-KOS-ARCHBASE3-VERIFY2` (scope verbatim; five question groups; the "no acceptance authority" and "stop after report delivery" clauses) · record `KOS-ARCH-BASELINE-003` seq 13/14/15 (REGISTER · HANDOFF `…-domain-model-refinement.md` · human START, with the unresolved amendment note) · **subject:** `KOS-ARCH-BASELINE-003-domain-model-refinement.md` · **inputs:** `KOS-ARCH-BASELINE-003-verification-report.md` (V#1: F-1, C-1, C-2, C-3, O-1, O-2) · `KOS-ARCH-BASELINE-003-bc7-domain-model-proposal.md` (target lines 45, 135, 137, 208, 237, 240, 279, 307, 354 extracted verbatim) · **primary re-derivation:** `.claude/scripts/workflow-state.php` (`authorized`, `append`, `grant`, `foldSessions`, `assertTransitionAllowed`, `identity`, HANDOFF/START/COMPLETE/CONTINUATION) and **15 live records / 130 transitions / 48 grants**, censused 2026-08-17 after seq 15 · **accepted** Phase A baseline v1.1 (`I-5`, `I-7`, §5.4 ownership) · invariants I-2, I-4, I-5, I-7, I-10, T-1, T-2, T-3, T-4 · policies DP-3, DP-6, G-1, `Inv C`, `Inv E` · `INV-ATTR-2` · `R-34` / `P-2` · `ES-005.4` · `V-3` (the resolver's handoff blindness — `fold` used throughout, never the resolver).

---

> # Independent Verification #2 — evidence and recommendation only · no acceptance · this session does not complete its own assignment
