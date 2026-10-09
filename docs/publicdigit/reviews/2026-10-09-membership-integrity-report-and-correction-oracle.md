# Membership: Repository-Integrity Report and Executable Correction-Algebra Oracle

**Date:** 2026-10-09 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)
**Commission:** `docs/publicdigit/membership/architecture/brainstorming/20261009_2320_implementation.md`
**Status:** RESEARCH + INTEGRITY REPORT. **No production code, test, schema, configuration or database change. No PBDIGIT-266 / 264 / 267 work. Not committed** (Phase 0: no commit without authorisation).

**Epistemic labels:**
- **Observed:** source, command output or repository evidence;
- **Derived:** follows from the stated definitions;
- **Proposed:** a candidate awaiting a decision-maker;
- **Ratified:** a decision record exists.

## 0. Disagreements (stated first)

| # | Instruction | Position |
|---|---|---|
| Z-1 | Phases 1 – 2: a new full evidence matrix and theory | **Not re-produced.** They exist: the audit `…theory-synthesis-and-implementation-readiness-audit.md` (A: theory; B.1: invariant catalogue with evidence; C: traceability), its errata E-1 … E-8, and the decision record. A second matrix would drift. This document delivers **what is new**. |
| Z-2 | Phase 3 – 4: "design correction semantics" | **The semantics are already ratified** (act §3 – §4; final proof §5, §13.8, §14.2 – §14.4). What was missing is **executable, retained evidence**: the Final Freeze Audit's model (act §4: "151,959 exhaustive histories") is **not in the repository**, so the ratified correction semantics are **not reproducible** today. The ARB-MEM-01 gate needs an independent oracle for the correction slice. That oracle is the new deliverable (§2). |
| Z-3 | Progress percentages "with denominator" | Given **only where a denominator is defensible** (§4). Otherwise the status is qualitative. |

## 1. Phase 0: repository-integrity report [Observed]

| Question | Evidence | Answer |
|---|---|---|
| Current state | HEAD `ae3faec5d` on `knowelegeos-modelling`. Parent `af3614547`: other sessions committed `44e7c18b5` … `af3614547` between my commits | — |
| Did my commits delete anything? | `git show --numstat`: `95da140e5` **0 deletions** (385 insertions); `ae3faec5d` **0 deletions** (2 insertions); all research commits 0 deletions, except `0b2685c38` / `0969e05a3` (5 each, which replaced only my own lines: "Index row: pending", my review wording) | **No** |
| The "missing" README rows | the other session's `PBDIGIT-256` row was **committed by that session** in `4b8f54742` (`git show 4b8f54742 -- docs/publicdigit/backlog/README.md`). After that the working tree equals HEAD, so no diff remains | **preserved (committed by its owner)** |
| The other session's session-log blocks | "Mission 1 – 3" headings are present in HEAD (committed in `44e7c18b5`) | **preserved** |
| The other session's CONTEXT edits | still **uncommitted in the working tree** (≈ 40 lines); `ae3faec5d` added one line via a HEAD-based index | **preserved, unstaged** |
| Recovery needed | — | **None** |
| Safe to resume research | — | **Yes** |

**Method used for every shared-file commit:**
- build the blob from `HEAD:<path>` + my lines;
- commit through a temporary `GIT_INDEX_FILE`;
- `git reset -- <path>` to resync the real index.

The working-tree content of other sessions is never staged or committed.

## 2. Executable oracle of the ratified correction algebra (new)

**Files (research only):**
- `docs/publicdigit/membership/architecture/models/g6_correction_oracle.py`;
- `docs/publicdigit/membership/architecture/models/g6_checks.py`.

**What it implements [Derived from ratified definitions]:**
- the final proof §14.2: `live`, `root`, `active` (whole chain live, §15.1), `superseded`, `value`, `BaseClaim`;
- frozen consequences `cons(f)` via `g2` (trim policy, BD-REV); `LiveCut`; `ResolvedLifetime`;
- **G\*** = INV-9 ∧ INV-EP ∧ INV-COV on the resolved post-state;
- structural guards: the kernel Admit / End guards; **act N-3** refusals (Annul(Void), Correct(Annul), Correct(Void)); "Correction of a voided fact is refused"; Correct(annulled) accepted but inert.

**Domain:** one Member; valid time 0 … 4 (∞ = 5); logs of length ≤ 4; every Admit, End, Correct, Annul and Void candidate at every step.

**Results (reproducible, deterministic):**

```bash
G6_L=4 python3 -I docs/publicdigit/membership/architecture/models/g6_correction_oracle.py   # ~1 s
python3 -I docs/publicdigit/membership/architecture/models/g6_checks.py                    # needs PHP + vendor
```

| # | Question | Result | Class |
|---|---|---|---|
| O-1 | Does the oracle agree with the **real Slice 0 kernel** where both apply (Admit / End)? | **400 / 400** decision pairs agree (accept / refuse **and** membership at every t) via `kernel_admissibility.php` | **Observed**: two independent implementations agree (bounded) |
| O-2 | Accepted histories / refusals (L ≤ 4) | 14,365 accepted. Refusals: INV-9 overlap 2,655; End not after start 875; already ended 720; period not found 700; **N-3 Correct(Annul / Void) 1,475**; correction of a voided fact 575; N-3 Annul(Void) 135 | Bounded |
| O-3 | Does INV-COV ever have to refuse? (published Final Freeze claim: "the gate never fired") | **0** firings; refusal sets identical with and without INV-COV | **Bounded replication** of a ratified claim (not of its counts) |
| O-4 | Does Annul(Annul) restore the original (act §4)? | **90 / 90** | Bounded |
| O-5 | **Remedy coverage:** can every single-fact recording error in an Admit / End history be repaired by **one** ratified operation that reproduces the intended membership? (histories ≤ 3 facts) | wrong Admit date → `Correct` **124 / 124**; wrong End date → `Correct` **74 / 74**; spurious Admit → `Void` 85 / 85 and `Annul` 85 / 85; spurious End → `Void` / `Annul` 25 / 25 each. **Unremedied: 0** | Bounded. **Every error class that the kernel probes showed unremediable today is remediable under the ratified algebra** |
| O-6 | **BD-REV-ER:** can a correction absorb a whole live period (live Admit, empty lifetime)? | **Yes: 35** of 14,365 accepted histories under "allow". Minimal: `Admit(p0@0), End(p0@1), Admit(p1@1), Correct(Admit p1 → 0)`. p1's earlier start trims p0 to ∅ although p0's Admit and End remain live. Under "refuse", exactly those 35 are refused and nothing else changes | **Counterexample** to "allow" being silent: absorption is an implicit set-aside with no Annul / Void and no competent organ |

**What the oracle does NOT establish:**
- **Bounds:** behaviour beyond H = 5 / L ≤ 4 (or ≤ 3 for O-5).
- **Fact kinds:** Renew, Suspend, Restore, ChangeType (not modelled; they do not change BaseClaim, final proof §14.3).
- **Holder:** a wrong **holder** (the ratified remedy is Void + a new Admit for the correct person; the person is outside this single-Member model).
- **Authority:** the competent organ per operation (CQ-3).
- **Annul vs Void:** the distinction is not visible in resolution by design (same temporal effect; it differs in legal meaning, statistics and authority, final proof §13.8).
- **Concurrency and persistence.**
- **Counts:** they are enumerated cases, **not probabilities**.

**Independence:** the oracle is written from the final-proof text, not from the PHP code. Its agreement with the kernel (O-1) is evidence for both on the shared fragment. Remedy checks (O-5) compare two uses of the same resolver: a metamorphic relation, weaker than an independent oracle.

## 3. Consequences for the correction slice (ARB-MEM-01 gate)

1. **The semantics are implementable as ratified.** No contradiction was found between act §4, final proof §14.2 and the kernel's Admit / End behaviour (O-1, O-3, O-4).
2. **The ratified algebra closes the error gap** shown by the kernel probes (decision-readiness doc §3), within bounds (O-5). ARB-MEM-01 is therefore *satisfiable*; it is not a dead end.
3. **One semantic decision blocks the slice contract: BD-REV-ER.** Evidence: O-6. **Recommendation (Proposed): refuse absorption.** The person's earlier period would otherwise vanish without a set-aside act; refusing changes nothing else in the bounded space.
4. **Proposed contract inputs** (for a later correction-slice semantic contract, analogous to the Slice 0 contract):
   - commands `AnnulFact`, `VoidFact`, `CorrectFact` (separate kinds, final proof §5);
   - the N-3 refusals;
   - Correct cannot change the holder;
   - inert correction of an annulled fact;
   - `decidedAt` required for Annul;
   - authority per operation (CQ-3; G-AUTH-1);
   - `g2` consequences frozen at commit;
   - G\* at commit;
   - **test oracle = `g6_correction_oracle.py`** (exhaustive bounded differential tests, as Slice 0 did against its oracle).
5. **Reproducibility gap closed partially.** The retained oracle replaces the missing Final Freeze model for the Admit / End / Annul / Void / Correct fragment.

## 4. Progress (denominators only where defensible) [Observed]

| Dimension | Measure | Value |
|---|---|---|
| Ratified fact families executable in the kernel | act §3 lists 9 (Admit, Renew, ChangeType, Suspend, Restore, End, Annul, Correct, Void) | **2 / 9** |
| Slice 2 decisions ratified | Slice 2 pack D-1 … D-6 | **0 / 6** |
| Committee / authority questions decided | CQ-1 … CQ-10 | **0 / 10** |
| PBDIGIT-266 planned tests executed | the plan's 22 + 4 added rows | **0 / 26** (gate FAIL) |
| Cutover gates satisfied | C-0 … C-10 | **0 / 11** |
| Error classes remediable under the ratified algebra (bounded) | Admit / End single-fact errors | **4 / 4 classes** (418 / 418 operation checks: Void and Annul are counted separately for spurious facts) |
| Domain discovery; theory and invariants | — | qualitative: **advanced** (no defensible denominator) |
| Production readiness | — | **not ready** |

## 5. Status items (Phase 5, re-verified)

| Item | Status |
|---|---|
| `PBDIGIT-266` | gate **FAIL** (shared `nrna_test`; no dedicated DB assigned). No tests |
| `PBDIGIT-267`, `PBDIGIT-264` | not authorised |
| Slice 1b | conditionally accepted (ARB-MEM-02). No formal act; DV-1 countersignature missing |
| Identifiers | D-1 = INV-FID (Slice 1a) preserved; `S2-D1 … D6` is a proposal |
| P1 – P5 | open; the defaults are proposals |

## 6. Verified / hypothetical / next decision

- **Verified (bounded, Observed):**
  - the oracle = the kernel on Admit / End (400 / 400);
  - the INV-COV safety net never fires;
  - Annul(Annul) restores;
  - all four single-fact error classes are remediable;
  - absorption occurs (35) and is cleanly refusable.
- **Hypothetical:** behaviour beyond the bounds; the holder correction path; the authority allocation; Renew / Suspend / ChangeType interaction.
- **Next decision (Human ARB / PO): BD-REV-ER** (recommended: refuse). It is the last semantic input the correction-slice contract needs, apart from CQ-3 (authority) and CQ-9 (appeal body).
- **Single highest-value next action:** decide **BD-REV-ER**, then authorise **drafting** (not implementing) the correction-slice semantic contract with `g6_correction_oracle.py` as its differential test oracle.
- **In parallel:** a dedicated test database for `PBDIGIT-266`.
