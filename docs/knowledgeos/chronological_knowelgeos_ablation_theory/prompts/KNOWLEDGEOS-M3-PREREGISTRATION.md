# M3: consolidated transition model and its computational attack (pre-registration)

| | |
|---|---|
| Status | **Frozen at commit before `m3_check.py` exists.** Not canonical; authority: none. M0/M1/M2 unchanged |
| Basis | development data F-LOG-0117…0124 (all corpus facts cited there). **Nothing here is new empirical evidence**; the attack produces the observation templates that could falsify M3 |
| Structure | M3 is **compositional**: three sub-models checked separately. Whether they compose is itself open |

## 1. Rules carried from M2, with corrections
- Frame⁺(ACCEPT) = {acceptance, work-lifecycle}. **Closure happens only by acceptance.**
- Frame⁺(ADOPT) = {status, annotation-role}.
- **Direct vs derived:** the frame axiom `d ∉ Frame⁺(o) ⇒ S'_d = S_d` covers direct effects only. A guard that becomes true on the new state is *derived*: it is computed, never stored.
- PERMIT(activity) replaces PERMIT-CONSIDERATION.
- **Four-act separation:** permission, authorization, commissioning and execution are separate relations (R-79).
- **Conditional authorization:** authorization ∈ {none, conditional, full}. conditional → full only when its proviso is satisfied (R-72).
- **Issuer-parametric status:** rulings issued by the human authority are governing at issue. Rulings issued by the ARB Chief start PREPARED (R-81 annotation).

## 2. Sub-models (MODEL-ASSUMPTION)

### A. Work acts
- **Items:** 4B, 4C, 4D, §4 (the parent) and 8.
- **Per-item coordinates:** permission {no, yes} · authorization {none, conditional, full} · commissioned {no, yes} · execution {not-started, executing, done} · acceptance {no, yes} · lifecycle {open, closed}.
- **Global coordinate:** board_acts {pending, done} (PROMOTION + ALLOCATION, per R-79 step 1).

| Operation | Guard | Frame⁺ |
|---|---|---|
| PERMIT(x) | — | {permission} |
| AUTHORIZE-COND(4B) | — | {authorization} (R-72) |
| SATISFY-PROVISO(4B) | board_acts = done | {authorization}: conditional → full |
| BOARD-ACTS | — | {board_acts} |
| AUTHORIZE(4C) | acceptance(4B) = yes | {authorization} |
| AUTHORIZE(4D) | acceptance(4C) = yes | {authorization} |
| AUTHORIZE(8) | lifecycle(§4) = closed | {authorization} |
| COMMISSION(x) | — | {commissioned} |
| START(x) | authorization(x) = full | {execution} |
| FINISH(x) | — | {execution} |
| ACCEPT(x) for x ∈ {4B, 4C, 4D, 8} | execution = done | {acceptance, lifecycle} |
| ACCEPT(§4) | 4B, 4C and 4D all accepted | {acceptance, lifecycle} |

- AUTHORIZE(4C) and AUTHORIZE(4D) follow "authorized from implementation evidence after its predecessor is accepted" (R-72).
- AUTHORIZE(8) follows R-79.
- ACCEPT(§4) follows "§WP-4 closes by acceptance" (R-79/R-87).

### B. Ruling status and authority
- **Rulings:** r1, r2.
- **Coordinates:** issuer {human, chief} · status {none, PREPARED, ADOPTED, HELD, WITHDRAWN} · registry {unused, used, retired} · text {t0} (never rewritten) · annotations (an append-only count) · superseded_by {none, r2} · delegation {none}.

| Operation | Guard / effect |
|---|---|
| ISSUE(r, issuer) | a human issuer gives ADOPTED; the Chief gives PREPARED |
| ADOPT(r, actor) | guard: actor = Authority ∧ status ∈ {PREPARED, HELD} |
| HOLD(r) | PREPARED → HELD |
| WITHDRAW(r) | PREPARED → WITHDRAWN, and the registry becomes retired |
| ANNOTATE(r) | annotations + 1 |
| SUPERSEDE(r2, r1) | an explicit act: r2 ADOPTED, then superseded_by(r1) = r2 |

### C. Evidence targeting
- **Targets:** decision, implementation.
- **Coordinates:** standing(t) {valid, questioned-by-evidence, invalidated} · evidence(t) {none, contra}.
- **Operations:**
  - INTAKE-CONTRA(t), with Frame⁺ {evidence(t)};
  - an explicit REVISE(t), with Frame⁺ {standing(t)}, guarded by evidence(t) = contra.

## 3. Invariants (each a safety property over all reachable states / traces)

| ID | Invariant | Source basis |
|---|---|---|
| I-A1 | no execution without full authorization | R-79, R-72 |
| I-A2 | permission never implies authorization. A state with permission = yes ∧ authorization = none is reachable, and START is disabled there | R-79 |
| I-A3 | lifecycle = closed ⇒ acceptance = yes | R-43 / R-62, R-72, R-87 |
| I-A4 | acceptance(4B) does not imply acceptance(§4) | R-72 citing R-69 |
| I-A5 | AUTHORIZE never changes acceptance or lifecycle | R-72, R-87 |
| I-B1 | no self-adoption: a Chief-issued ruling becomes ADOPTED only by an Authority act | R-81 … R-86 |
| I-B2 | adoption creates no delegation: after any ADOPT, a new Chief-issued ruling is PREPARED | R-86, R-87, R-88, R-89 |
| I-B3 | superseded_by changes only by SUPERSEDE, never by inference | R-77 |
| I-B4 | text is never rewritten; annotations only grow | R-53, R-62, R-81 … R-91 |
| I-B5 | WITHDRAWN ⇒ registry retired; HELD ⇒ not retired | R-90, R-91 |
| I-B6 | a human-issued ruling is never PREPARED | R-81 annotation; 1t P3′ |
| I-C1 | evidence about the implementation never changes the standing of the decision | R-91, R-53, R-77, R-85 |

## 4. The attack (what the checker must compute)
1. Every invariant is checked over all reachable states of its sub-model.
2. **Relaxation counterexamples:** for each invariant, remove the guard or frame that enforces it, and compute the **shortest violating trace**. That trace is the **observation template**: the record a source would have to contain to falsify M3.
3. **Sequence check** (development consistency, not a test): the shortest path to START(8) is compared with R-79's "SEQUENCE AFFIRMED". The comparison is: board acts → 4B RED…ACCEPT → 4C → 4D → §4 closes by acceptance → 8 authorized and started.
4. **Liveness:** START(8) is reachable (the model is not over-constrained), and ACCEPT(§4) is reachable.
5. **Observability ranking** of each template by source family:
   - rulings register (records authorizations, adoptions);
   - session logs (record starts, execution);
   - **git history** (mechanical timestamps).

   The next read is the template that is observable in a *different* source family with mechanical ordering.
6. Selftest plus a mutation test. Deterministic output with its hash.
