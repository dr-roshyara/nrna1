# Commission: theory turn on `Req` and the interface assumption `A` (PREPARED — NOT ISSUED)

| Field | Value |
|---|---|
| Status | **ISSUED** by the Principal's dispatch, reported to the Governance Engineer 2026-11-01T15:06:25Z ("run the turn before reading resumes; I sent the commission"). **Sequence: the turn runs before reading resumes at S0303.** *(Was: prepared, not issued; approved for preparation only, research record 2026-11-01T02:31:51Z.)* |
| Prepared by | the Governance Engineer (session `57ddce41…`, the active Governance Engineer under Option A), 2026-11-01, after the consolidation S0278–S0302 was settled (stage 2 `e3ac4df54`; condition 1) |
| Built from | **repository records only**: the addendum's seven conditions; Rulebook v1.4 §1, §12b; `06-theory/manuscript/chapter_09/09.10-required-capabilities.md`, `09.11-kernel-type-from-req.md`, `09.01-kernel-scaffold.md`; `review/TURN1-REQ/SETTLEMENT.md`; `BOOK-OUTLINE.md` L44, L179; `review/BLOCKERS-S0278-S0302.md`; Ch. 9 `TODO.md` T9-01, T9-02; the advisory review's §6.2 template (`METHODOLOGY-EFFICIENCY-REVIEW-20261101.md`, advisory) and its assessment (`ASSESSMENT-OF-METHODOLOGY-EFFICIENCY-REVIEW-20261101.md`). **The "pre-execution report" named as the approval's basis is not in the repository and was not used** |

---

## The commission (to the Professor)

You are commissioned, by the Principal's dispatch, to run **one theory turn under Rulebook v1.4 §12b** on the kernel's required capabilities `Req` and the interface assumption `A`. Rulebook v1.4 stays in force; nothing here changes a rule.

### 1. Start from what the book already has — consume and extend, do not redo

- **`Req` already exists:** §9.10, version 2, settled at theory turn 1 (2026-10-20; `review/TURN1-REQ/SETTLEMENT.md`): Req = (Q, O), core questions Q1–Q4 and inputs O1–O4, each with grounds, purpose, inputs, scope, identity and observable consequences; disproof conditions F-ins, F-min, F-vac (§9.10.1). `[CON-0188 · provisional]`.
- **The kernel type from Req:** §9.11 (turn 2); the scaffold §9.1 (Kernel(M) = P ∧ Req; Minimal(M); the toy-model table, §9.1 L57–72); kernel membership per item (turn 7).
- **What the book still lists as missing:** `BOOK-OUTLINE.md` L179 — "the required capabilities of KnowledgeOS itself (T9-01), the load-bearing undefined object: six claims defer to it (§5.5 C3, §6.5 C1, §9.3, §9.4 C7, §9.5 C1, C5)", with candidates (distribution §9.5; bootstrap §9.2; the record and fold §6.5; Zero-all / Zero-blocking §5.4), "how to establish a capability (S0164 D5 E1–E10)", "the state space (F13, T9-02)"; and L44 — "the derivation from primitives to laws as one chain … minimality of the primitives was never tested".
- **First task:** state precisely **how §9.10's Req relates to L179's "missing" item** — whether L179 is stale against §9.10, or names capabilities §9.10 does not cover, and which of the six deferring claims §9.10 already answers. Do not restate §9.10 as if it were new.

### 2. The research question (§12b: question first; disproof fixed before the work)

**Relative to which requirement set `Req` and which interface assumption `A` is "a minimal kernel" well posed, and what does the book's evidence establish about it?**

- **What must be explained:** the six claims that defer to the required capabilities (L179); the minimality notion of §9.1 (Min; Kernel(M) = P ∧ Req).
- **What would disprove the answer:** fix it before the derivation, in the form §9.10.1 uses (insufficiency, redundancy, vacuity), extended to `A` (a granularity at which the claimed result fails).

### 3. Deliverables (condition 4; bounded)

1. **`Req`:** each required capability stated, with its evidence (corpus source line, book claim, or Principal act), and its relation to §9.10's Q1–Q4 and O1–O4 (same item, extension, or new). Each new capability through the **three boundary questions** as three separate analyses (necessary? must the kernel provide it? can an application provide it without breaking kernel invariants?).
2. **`A`:** the system boundary and the mechanism granularity, stated separately and explicitly.
3. **Candidate primitives:** examined with their current status; **the current status is not evidence of necessity**. **The primitive set is not finalised** by this turn.
4. **A traceable derivation** for every step claimed (proof-step table; `proof_step_check.py` supports the table, never replaces it).
5. **Necessity tests and counterexamples:** for each candidate, a model in which removing it makes `Req` unsatisfiable, or a countermodel; the trivial cases (empty mechanism set, refuse-all, unchanged state).
6. **Minimality, scoped to `(A, Req)`:** what is established and at what strength (proof within stated premises; bounded model check within its bound; corpus evidence about these sources). **Do not claim a unique minimal kernel** unless a structure theorem is proved; report the minimal candidates found. **No selection criterion among minimal candidates is adopted** (that is a design decision for the Principal).
7. **Unresolved questions:** what the evidence cannot decide, each routed to the Principal in one sentence with its options and a copy-ready confirmation line.

### 4. Assumptions and limits (conditions 2, 4, 5)

- **Domain:** engineering knowledge is carried as an **explicit, labelled assumption** (the Principal's direction, I-013 D2), **not** as an adopted amendment to `RESEARCH-PURPOSE.md`, which is not edited; the "organisational knowledge" wording (L14) stays an **open** issue.
- **T9-02 (the state space) is not absorbed** into this turn. If `Req` cannot be stated without it, **stop and escalate** to the Principal rather than merge it.
- The decided theory applies: T10-01 (b), T9-19 with Q-A/Q-B, T9-20 (no guard on adoption), T10-21, Q-C.
- **Not authorised by this commission:** the faster-reading experiment; P1/P2; Rulebook v1.5; the stage-1 package; any architecture activation; any implementation; any change to the Verification Auditor's mandate.

### 5. How (Rulebook v1.4 §12b)

- Up to **8 sources** by logged search, read `topic-full`, or passages as `branch-consult` with a theory-map snapshot first (`close_source.py --open-branch`) and **predictions recorded before index order reaches them** (§12b). **The reading position does not move.**
- Record the turn's own time in the four categories (Reading · Drafting · Settlement · Rework), as for windows (condition 7).

### 6. Review (condition 3: option (a))

- **Three reviewers, blind-first, each independent; none sees another's report before all are saved:** the two §12b reviewers on **different models** (`kos-lane-reviewer`), **plus one additional independent theory reviewer** (`kos-theory-reviewer`).
- Commissions list the files each may open; `00-access/` and other reviews are forbidden; the rules line includes the outside-authors rule.
- **The Verification Auditor's mandate is not expanded**; the auditor takes no part.

### 7. Output and settlement

- The result goes into the book (Ch. 9; a new section or a new version of §9.10, as the turn's question requires) and into `BOOK-OUTLINE.md` (L44, L179); TODO rows T9-01 and those the deferring claims carry are updated.
- Reports saved verbatim with sha256; a settlement with every finding dispositioned, R3 on every accepted correction.
- Report to the Principal: **theoretical progress first**, then procedure (the Principal's instruction 4 of 2026-10-31).

---

## For the Principal: the dispatch

- **Sequence (yours to decide):** run this turn **before** reading resumes at S0303 (the §12b practice for critical-path turns: "reading resumes when the chain is settled or the Principal decides"), or in parallel with reading. *Recommended: before*, since the reading position does not move during a turn and the advisory review estimates one to two days.
- **To dispatch:** send the section "The commission (to the Professor)" above, and tell the Governance Engineer it was sent. It is not reported as issued until then (condition 6).
