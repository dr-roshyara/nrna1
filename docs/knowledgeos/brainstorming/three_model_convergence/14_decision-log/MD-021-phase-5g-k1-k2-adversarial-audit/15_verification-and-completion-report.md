# Phase 5G — Verification and Completion Report

## Raw-source spot-checks (24 performed; requirement was ≥20, covering both K-1 and K-2 primary sources)

| # | Category | Claim re-checked | Raw source | Result |
|---|---|---|---|---|
| 1 | K-1 primary source | §49.29 8-item boxed result | seq 0630 | **Faithful**, re-confirmed verbatim |
| 2 | K-1 primary source | §49.30 formal tuple, $S$="states", $T$="temporal/event structure" | seq 0630 | **Faithful** — resolved an internal adversarial suspicion (§03) |
| 3 | K-1 primary source | §49.31 derived-structure equations | seq 0630 | **Faithful**, re-confirmed verbatim |
| 4 | K-1 ratification | "RATIFIED — FA-4 D-FA-6... 50 attack classes" | seq 1006 | **Faithful** |
| 5 | K-1↔K-1-B citation test | No "D285-1" citation found in D285-6 or D285-7 | seq 1007, seq 1008 (targeted grep) | **Confirmed negative** — 0 hits |
| 6 | K-1↔K-1-B citation test | D285-7 cites D285-6 by name ("Under the projection result (D285-6)") | seq 1008 | **Faithful**, confirmed positive — the one explicit package cross-reference found |
| 7 | K-2 primary source | `K=(𝒜,ℛ)` surface tuple | seq 1006 §1 | **Faithful** |
| 8 | K-2 primary source | D285-1's own Assertion unpacking (6 fields, no Observation) | seq 1006 §2 | **Faithful**, re-confirmed verbatim |
| 9 | K-2 primary source | D285-6's own Assertion unpacking (`{Proposition,Entity,Observation}`+`{id,c,t,Π}`) | seq 1007 §3 | **Faithful**, re-confirmed verbatim — **confirmed genuinely different from #8** |
| 10 | Projection map | Full primitive-by-primitive mapping table | seq 1007 §3 | **Faithful** |
| 11 | Three-level equality spec | Structural FALSE / semantic TRUE / observational FALSE | seq 1007 §5 | **Faithful** |
| 12 | Computability blocker | `Qualify: Observation×Policy→Evidence` has no body, "Step 170" | seq 1007 §4a | **Faithful** |
| 13 | Minimality (K-2) | "not minimal (272A §27)" | seq 1006 §1 (table) | **Faithful** |
| 14 | Minimality (K-2, second source) | "`(𝒜,ℛ)` must NOT be concluded to be the minimal operational kernel" | seq 1008 | **Faithful** |
| 15 | K-2's own operation set | `𝒪_sem`, 19 candidates, 5 families, Step 272A | seq 1008 | **Faithful**; Step 272A file existence confirmed at expected path (full content not re-read) |
| 16 | K-3's own collapse finding | "`Σ` must be derived ⇒ collapses toward K-2" | seq 1008 | **Faithful** |
| 17 | K-4 status | "superseded by ~120 steps" | seq 1006 (table) | **Faithful** |
| 18 | K-5 status | "REJECTED (FA-4)" | seq 1006 (table) | **Faithful** |
| 19 | K-6 status | "restores replay — the minimal repair to K-2" | seq 1008 | **Faithful** |
| 20 | K-7 status | "presupposes `Ω`, `D_t`, `Z_t`, undefined" | seq 1006 (table) | **Faithful** |
| 21 | Observation recovery | Sañjaya construction, six-value vocabulary | seq 0979 | **Faithful**, re-confirmed verbatim |
| 22 | Observation recovery, dating | Origin 2026-08-26, predating the D285 package (2026-08-31) by 5 days | seq 0979 (filename date), seq 1006/1007/1008 (header date) | **Faithful** — confirmed the Sañjaya construction pre-existed and was not manufactured for the D285 package |
| 23 | "Two different objects wearing one letter" | K_t ≠ the knowledge state | seq 1006 §4 | **Faithful** |
| 24 | DDD-pattern absence test | No "bounded context"/"anti-corruption layer"/governance-rule language found describing K-1/K-2 as a specified DDD relationship | seq 1006, seq 1007, seq 1008 (full re-read, targeted search) | **Confirmed negative** — supports the DDD downgrade in `09` |

**Two genuine findings from this phase's own spot-checking, both already disclosed in the relevant
analytical files**: (a) the D285-1/D285-6 Assertion-unpacking variance (checks #8/#9, disclosed in
`04`/`11`); (b) the D285-7→D285-6 explicit citation (checks #5/#6, disclosed in `02`/`10`).

## Verification suite

- `resume.py` → `CONSISTENT` (unchanged). `resume_mathematical.py` → `CONSISTENT` (unchanged).
- `classification-register.tsv` → 0 non-pending rows, unchanged.
- Model A (5), Model B (5), Phase 3 (5), Phase 4 (5), Phase 5A (4), Phase 5B (7), Phase 5C (7), Phase
  5D (9), Phase 5E (12), Phase 5F (13) — **72 files total** — confirmed present and untouched.
- D285-1, D285-6, D285-7 (the three corpus source files under audit) — confirmed **not modified**
  (no write access used against any of these paths at any point this phase).
- Filesystem scope: only `14_decision-log/MD-021-phase-5g-k1-k2-adversarial-audit/` (this directory,
  15 files) plus governance-record updates were written this phase.
- No global classification was changed anywhere.

## Completion criteria (self-checked)

1. All 9 Phase-5F claims registered and independently tested — ✅ (`01`–`12`).
2. K-1/K-1-B identity question resolved using the user's own sharper mathematical distinction, not
   Phase 5F's own reasoning — ✅ (`02`).
3. K-1 and K-2 both independently re-reconstructed mathematically, `NOT EVIDENCED` where warranted —
   ✅ (`03`, `04`).
4. Projection formally specified (domain/codomain/mapping/totality/computability/loss) — ✅ (`05`).
5. "Semantic equality" tested against 5 named, operationally defined equivalence types, not accepted
   at face value — ✅ (`06`).
6. Observation/State each tested against the required H1–H4 hypothesis set — ✅ (`07`).
7. Entity/Proposition/Relation re-audited independently, no cross-contamination between primitives —
   ✅ (`08`).
8. DDD claim tested against actual DDD-pattern evidence requirements, downgraded where unsupported —
   ✅ (`09`).
9. Provenance re-audited by citation type, chronological proximity never used as sole grounds — ✅
   (`10`).
10. D285-1/D285-6 internal tension re-audited, frozen sources not repaired — ✅ (`11`).
11. K-1 through K-7 re-verified against primary source — ✅ (`12`).
12. Required final adjudication matrix produced — ✅ (`13`).
13. Open questions and required evidence stated explicitly — ✅ (`14`).
14. ≥20 raw-source spot-checks, covering both K-1 and K-2 sources — ✅ (24 performed, this file).
15. Both resume scripts pass; register unchanged; all 72 frozen prior-phase files confirmed
    untouched; D285-1/D285-6/D285-7 confirmed unmodified; only the authorized Phase-5G location
    modified — ✅.
16. No canonical Kernel, no four-model convergence, no Phase 5H begun — ✅.

## Final Phase 5G status

**COMPLETE.**

**Which Phase-5F claims survived**: observational-equivalence FALSE; Observation-as-layering-gap;
Entity/Proposition/Relation correspondences; all seven K-1..K-7 dispositions.

**Which were weakened**: "semantic equality TRUE" → partial correspondence over a declared, unverified
subset; the blanket "definable, not computable" projection claim → precised to the `Observation`
component specifically; the DDD "Customer/Supplier, ACL-attempted" framing → a mathematical projection
is evidenced, a DDD architectural context mapping is not independently established.

**Which were strengthened**: K-1↔K-1-B "formal equivalence" → **demonstrated identity (qualified)** —
the one claim this audit moved upward, using the user's own sharper mathematical logic rather than
Phase 5F's own.

**Which remain unresolved**: `State`'s verification-lane status; the D285-1/D285-6 Assertion-unpacking
tension; `claim-registry`/`concern-map.md`'s own location; full external verification of K-1↔K-1-B
identity beyond textual-reuse inference.

**No classification was changed. No Model A/B/Phase-3/Phase-4/Phase-5A/Phase-5B/Phase-5C/Phase-5D/
Phase-5E/Phase-5F artifact was modified. D285-1/D285-6/D285-7 were not modified. No four-model or
cross-model convergence was performed. No unified or canonical Kernel was constructed. No
implementation was performed.**

**PHASE 5G COMPLETE — AWAITING SEPARATE EXPLICIT AUTHORIZATION.**

No later phase is implied by this completion. Phase 5H, global reclassification, four-model
convergence, unified Kernel construction, cross-model synthesis, repair of any frozen artifact, and
implementation all remain unauthorized and untouched.
