# Commission: theory turn T9-02, the state space (ISSUED)

| Field | Value |
|---|---|
| Status | **ISSUED** by the Principal's dispatch, reported to the Governance Engineer 2026-11-02T15:46:33Z ("Issued with reviewers (b)"): **three reviewers**, two `kos-lane-reviewer` on different models plus one `kos-theory-reviewer`. *(Was: PREPARED — NOT ISSUED.)* Prepared on the Principal's instruction "Prepare the T9-02 (State Space) Theory Turn" (2026-11-02; research record addendum of that date). It is issued only when the Principal dispatches it and tells the Governance Engineer so |
| Prepared by | the Governance Engineer (session `57ddce41…`), 2026-11-02 |
| Built from | **Part 1:** the Principal's own commission text, verbatim. **Part 2:** repository records only — Ch. 9 `TODO.md` T9-01–T9-04 (L11–14); `THEORY-MAP.md` F13 (L134), F13a (L136), F37 (L156); §9.1 L7–9, L22–27, L92–94; §9.10 L13, L201 (§9.10.5); §9.19 L28–35, L72, L159–174, L223, L231, §9.19.8; the U1–U6 rulings (research record 2026-11-01T17:32:50Z); `RESEARCH-PURPOSE.md` deliverables 1 and 5; Rulebook v1.4 §12b; the Principal's instruction of 2026-11-02, §§2–6 |

---

## Part 1 — The commission (the Principal's text, verbatim)

# KnowledgeOS Theory Commission: T9-02 — State Space

**Objective:** Resolve as much of the T9-02 state-space question as the available evidence permits, without assuming the conclusion.

## Your task

Investigate the state space required by the current KnowledgeOS theory.

Begin by identifying the precise unresolved question in T9-02 and reconstructing its dependency chain from the existing manuscript. Do not begin by inventing a preferred state space and then fitting the theory to it.

### Step 1 — Establish the current position

Identify:

- The current formulation of T9-02.
- The relevant definitions and requirements.
- The existing Kernel-existence argument.
- The relationship to the candidate primitives and F13.
- The decisions and open forks that constrain this work.

Cite the exact manuscript passages and permitted source evidence.

### Step 2 — Derive the state-space requirements

For every proposed state or distinction, explain:

1. What requirement makes it necessary?
2. What existing rule or theoretical result depends on it?
3. What would fail if the distinction were removed?
4. Could the same requirement be satisfied by a simpler formulation?
5. Does the distinction belong to the Kernel, the application, or remain undecided?

Do not count a state as necessary merely because it appears in the current formulation.

### Step 3 — Test alternatives

Construct the smallest meaningful examples and counterexamples needed to distinguish the candidate formulations.

Test whether each candidate:

- Satisfies the established requirements.
- Preserves the relevant invariants.
- Handles the known boundary cases.
- Introduces unnecessary distinctions.
- Depends on assumptions that have not been justified.

Where formal or computational checks are appropriate, state the model, assumptions, scope, and limitations. Do not claim that a finite test proves a general result.

### Step 4 — State the conclusion precisely

Report one of the following outcomes:

- **Resolved:** The evidence supports a justified state-space formulation.
- **Partially resolved:** Some elements are justified, but specific questions remain open.
- **Unresolved:** The available evidence does not yet support a defensible formulation.

Explain the reasons for the selected outcome.

Distinguish clearly between what the sources state, what follows from the premises, what you interpret, and what remains hypothetical.

### Step 5 — Trace the consequences

Explain precisely what the result changes—and does not change—for:

- The Kernel's type and existence.
- F13 and the knowledge-item attribute table.
- The candidate primitives.
- The existing laws and their derivation.
- Related open forks and Principal decisions.

Do not silently close related TODOs or treat an implication as an independently authorized decision.

### Step 6 — Report the remaining work

Produce a concise list of:

- Results established.
- Results rejected and why.
- Assumptions still required.
- Counterexamples not yet addressed.
- Questions requiring the Principal's decision.
- Further theory work that should be commissioned separately.

Do not begin the separate derivation-chain commission as part of this task.

## Completion criterion

The commission is complete when an independent reviewer can reconstruct the reasoning from the recorded evidence, challenge the proposed state space, and identify precisely which claims are established and which remain open.

The objective is not to produce a predetermined answer. It is to establish the strongest defensible result for T9-02 under the current research rules.

---

## Part 2 — Anchors, method and limits (added by the Governance Engineer from the records; for the Principal's review)

### 2.1 Where T9-02 stands in the book (for Step 1; dependencies, not answers)

- **T9-02** (Ch. 9 `TODO.md` L12): "The state space 𝒮 … undefined … The test used a three-part toy state. The kernel's real attributes are not fixed (F13)"; deliverable "one table of attributes, value sets and kinds". Annotated by the Req/A turn: needed to fix KnowledgeOS's mechanism pool; not for Req's core or, since U2, for Q4.
- **F13** (`THEORY-MAP.md` L134): Ch. 1's labels (provenance, evidential grade, standing, visibility, owner, identity I-0) and Ch. 3's six dimensions are two attribute systems never mapped onto each other; evidence status has 3 values in §3.1 and 4 in §1.3; each attribute needs a bearer (F37). Sub-fork **F13a** (L136): the attribution layer of an act.
- **F37** (L156), the bearer of the dimensions: **the Principal ruled that it stays a fork (DB-5).** This turn may show what each F37 branch implies for 𝒮; it does not decide F37.
- **The kernel type:** §9.1 (𝒮, M, I, A), mechanisms m : 𝒮 × X → 𝒮 ∪ {⊥}, preservation with refusal (P) (L7–9, L22–27); the revised type (𝒮, X, M, G, I_S, I_T, I_R, Req) (L92–94). §9.10.5 L201: "The state space (F13, T9-02): not a capability; it is the type's 𝒮."
- **The Req/A turn (§9.19):** Req's core is stated without T9-02 (L167); "the pool needs 𝒮 (T9-02, F13)" (L166); KnowledgeOS's minimal kernel is **not stated** because the pool is not fixed (§9.19.8); T9-03 (two notions of minimality) and T9-04 (uniqueness) wait on a fixed pool (`TODO.md` L13–14).
- **Decisions in force that bind this turn:** U1 (b), T9-02 taken up after reading resumes; U2 (a), Q4's grade is g1's class, in the kernel (what remains of G7, relating g1's classes to §1.3's and §3.1's statuses, is in scope only as a consequence to report); U3 (b), granularity and batching kept unfixed, results scoped to (A, Req); U6 (a), O1 per act kind, D clauses for five kinds still to write (T9-21, **not** in this turn); and T10-01 (b), T9-17 (b), T9-19 with Q-A/Q-B, T9-20, T10-21, Q-C.
- **The purpose:** deliverables 1 (primitives) and 5 (type and existence) of `RESEARCH-PURPOSE.md`; the test for every concept (kernel or application · minimal · stable · answers the central question).

### 2.2 Method (Rulebook v1.4 §12b, unchanged)

- **Research question and disproof conditions first,** committed before any model is run or any section drafted (the Req/A turn ran a model first; that order is not repeated).
- **Sources:** up to **8**, by logged search, read `topic-full`, or passages as `branch-consult` with a theory-map snapshot first (`close_source.py --open-branch`) and **predictions recorded before index order reaches them**. Allowed list only; sealed, hold-out and quarantine material never. **The reading position does not move** (it stays where the current window settles).
- **Labels:** Src · Ded · Int · Hyp · Ext, as the book uses them; **EA** for an engineering assumption (labelled, never evidence; I-013 D2), as at the Req/A turn. Every material claim cites its manuscript passage or source line.
- **Models:** any computational check states its model, premises, bound and limits; it supports form, not content (as §9.19 and §9.1 do). `proof_step_check.py` supports a proof-step table, never replaces it.
- **Time:** record Reading · Drafting · Waiting · Settlement · Rework, non-overlapping, active time apart from waiting (the Principal, 2026-11-01).
- **No git history, diffs, blame, historical versions or commit listings of any file**, for the Professor and every reviewer (the Principal's ruling, 2026-11-02).

### 2.3 Limits (from the Principal's instruction of 2026-11-02, §3)

- The state space is **not treated as settled** before the derivation and the review are complete.
- A candidate primitive is **not treated as accepted** without the required justification.
- **Kernel existence does not establish primitive minimality** (the two minimality notions, T9-03, are kept apart).
- **F13, F37, U1–U6 and every other open fork** are not resolved beyond what the existing decisions authorise; a question that needs one goes to the Principal, one sentence with options and a copy-ready line.
- **Not in this turn:** the derivation-chain turn (BOOK-OUTLINE L44); T9-21; any architecture or implementation work; any change to the Rulebook, reading depth, review procedure or access permissions; `RESEARCH-PURPOSE.md` is not edited.

### 2.4 Review

- **Independent review, blind-first:** each reviewer's step-1 answer saved with sha256 before any step-2 brief; reports saved verbatim with sha256 after all have returned; no reviewer sees another's report. Commissions list the files each may open; `00-access/` forbidden; the rules line includes the outside-authors rule and the git-history ban.
- **Reviewers:** *see the dispatch decision below* — either §12b's two `kos-lane-reviewer` on different models, or the Req/A turn's three (those two plus a `kos-theory-reviewer`).
- **Settlement:** every finding dispositioned; R3 on every accepted correction; the result into Ch. 9 (a new section or a new version of the affected sections), `BOOK-OUTLINE.md` and the TODO rows it bears on, **each change to a TODO row stated as a consequence, not as a decision** (Part 1, Step 5).
- **The Governance Engineer's role:** procedural compliance, source boundaries and completeness of the recorded evidence. **It does not evaluate the theory**; that is the reviewers'.

### 2.5 Report to the Principal

Theory first (Part 1, Steps 4–6), then procedure; each question for the Principal one at a time, with options, a recommendation and a copy-ready confirmation line. Reading resumes after settlement under the standing instruction and its gate (the next stop: the gate after S0402, before that consolidation).

---

## For the Principal: the dispatch

- **One choice (yours):** the reviewers. *(a) §12b as written: two `kos-lane-reviewer` on different models. (b) As at the Req/A turn: those two plus one `kos-theory-reviewer`.* **Recommended: (b).** T9-02 is on the same critical path as Req/A (it fixes the pool that the minimal kernel waits on), and the Req/A turn's third reviewer supplied two of its results (T9, T10) and the correction of its disproof criterion (C-07).
- **Timing:** the Professor settles the window in progress, then starts the turn; the reading position stays there until the turn is settled.
- **To dispatch:** send Parts 1 and 2 to the Professor with your reviewer choice, and tell the Governance Engineer. It is not reported as issued until then.
