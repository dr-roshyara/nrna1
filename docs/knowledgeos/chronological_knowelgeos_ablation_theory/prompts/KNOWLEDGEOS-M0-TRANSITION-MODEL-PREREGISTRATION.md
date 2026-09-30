# M0 — minimal factored transition model — PRE-REGISTRATION (frozen before the verifier is written)

| | |
|---|---|
| Status | **M0, frozen at commit, before `analysis/theory/m0_check.py` exists.** Not canonical. Authority: none |
| Data | development only: the acts coded in F-LOG-0102…0116 (P1, P2, R-39, R-90, R-100, L216, L493-A/B, L205-A1…A4). **No claim from them is an EMPIRICAL-RESULT**; the next genuine case is chosen from the verifier's distinguishing traces |
| Supersedes wording in | HYPOTHESIS-DISCRIMINATION-04 §5 (corrections §0 below); note 04 is not rewritten |

## 0. Corrected lattice wording (review 2026-09-28)
- **B1:** *a single linear lifecycle order* is **REFUTED** (L493: one item CLOSED in one plane, OPEN in another; L205: coordinates change independently). *Scalar encoding* of a product state is always possible and is **not tested / irrelevant**. B2 product state and B3 multi-plane state are **candidates**.
- **C5:** **authority alone is not a sufficient discriminator of the evidence requirement** (L493 pair: same authority, same day, opposite evidence treatment). It is **not** claimed that authority never influences evidence policy.

## 1. Model  M = (O, K, R, F, A, S, E, δ)   [every component MODEL-ASSUMPTION]
- **S** = product of *candidate* coordinates (the set is OPEN; sources name different planes; not unified):
  `standing ∈ {none, candidate, adopted}` · `validation ∈ {none, validated}` · `evidence ∈ {0, 1, 2+}` · `contra ∈ {no, yes}` · `identity ∈ {observation, rule}` · `regime ∈ {open, frozen}` (a process-level coordinate) · `governance ∈ {open, closed}` · `execution ∈ {open, closed}`.
- **O** semantic operations, each with a **frame** Frame(o) ⊆ coordinates:
  INTAKE {evidence} · CONTRA {contra} · RAISE {standing} · CREATE-NORM {standing, identity} · LAPSE {standing} · VALIDATE {validation} · RECLASSIFY {identity} · FREEZE {regime} · REOPEN {regime} · GOV-CLOSE {governance} · EXEC-CLOSE {execution} · REJECT ∅.
  **Frame axiom:** `d ∉ Frame(o) ⇒ S'_d = S_d`.
- **R** procedural routes {RULING, PROMOTION, DIRECTIVE}; **A** {named, none}; **F** force of the evidence bar {IN, AMB}; **K** = the identity coordinate; **E** = evidence + contra coordinates plus an invocation flag `exception`.
- An **invocation** = (o, route, authority, effect ∈ {normative, epistemic}, exception, force).
- **δ** = frame-respecting update, enabled iff **common guard ∧ variant guard**:
  - common: authority named for RAISE / CREATE-NORM / LAPSE / FREEZE / REOPEN / GOV-CLOSE (C1); RAISE disabled while `regime = frozen`; REOPEN requires `contra = yes` (L216); EXEC-CLOSE exists only if the item has an execution close transition (L493: AST-015 has none — an item parameter).
  - **variant guards (the bar = evidence 2+ or exception)**, applied to RAISE / CREATE-NORM:
    **G-R** route: bar iff route = PROMOTION · **G-K** kind: bar iff identity = observation · **G-O** operation: bar iff o = RAISE · **G-E** effect: bar iff effect = epistemic.
  - each variant × {**force-insensitive**, **force-sensitive** (bar only if F = IN; the H4 form)} → 8 guard models.

## 2. Properties to test (the §7 questions of the review, as model checks)
P-frame (frame axiom over all transitions) · P-frame-fit (each coded development act changes only coordinates in its operation's frame; alternative FREEZE frame {standing} tested against L205-A1) · P-reach (`adopted ∧ ¬validated` reachable) · P-rev (LAPSE lowers standing; REOPEN needs contra) · P-planes (governance closed ∧ execution open reachable) · P-gate-invariance (RECLASSIFY changes the RAISE guard only under which variants) · **P-markov** (equal states ⇒ equal enabled sets; tested with the R-90 "RETIRED, not recycled" identifier rule, which reads history) · P-dist (shortest invocation traces legal under one guard model, illegal under another) · P-dev (consistency of every guard model with the coded development acts, open world: an unrecorded evidence count may take any value).

## 3. Rules
- The verifier is deterministic explicit-state enumeration (BFS). No ML, no statistics.
- Coded development acts carry their READING dependencies; a result that depends on a reading is reported as conditional.
- No amendment of §1–§2 after the verifier's first run; changes go to M1.
