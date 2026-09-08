# Claim Audit (required 12-claim table)

| # | MD-026 claim | Evidence actually available | Logical strength | Dependency | Maximum defensible wording | Status |
|---|---|---|---|---|---|---|
| 1 | Phase-0 boundary was explicit | MD-010/MD-011 direct text | Strong | Independent (primary source) | Unchanged | **CONFIRMED** |
| 2 | `research/` did not yet exist | Only Git-tracking date (2026-09-06) | **Weak — conflates tracking with existence** | Depends on claim 4's own chain | "Was not Git-tracked at the boundary date; prior filesystem existence cannot be established" | **NARROWED** |
| 3 | `research/` was not intentionally excluded | Absence of any exclusion statement in MD-010/MD-011 + claim 2 | Moderate (a negative finding, correctly hedged already in MD-026's own §Q2) | Partially depends on claim 2 | Unchanged, already correctly hedged as "no evidence supports" | **CONFIRMED, no change needed** |
| 4 | M0030 points to `kernel-reduction/` | Direct grep + read of raw text | Strong | Independent, machine-verifiable | Unchanged | **CONFIRMED** |
| 5 | M0030 proves actual execution there | Only the citation itself, plus trial-count/date correlation | **Weak — MD-026 already correctly capped this at `[RECONSTRUCTED PROVENANCE]`** | Depends on claim 4 | Already correctly hedged; no further narrowing needed | **CONFIRMED as already-hedged, not overstated** |
| 6 | `kernel-reduction/` is Model-B output | Same-day self-dating + M0030 citation + trial-count match | Moderate | Depends on claims 4, 5 | "Model-B-adjacent candidate with reconstructed, not documented, provenance" | **NARROWED** |
| 7 | `kernel-reduction/` is a "separate, self-governing research context" | Its own `00-INDEX.md` self-label ("research experiment — NOT canonical, NOT architecture, NOT governance") | **"Self-governing" overstates** — self-labeling as non-canonical is not the same as DDD-evidenced ownership/authority | Independent (the self-label is direct textual evidence) but narrower than claimed | "A separately organized research lane, self-labeled non-canonical; DDD bounded-context status not established" | **NARROWED** |
| 8 | `theory-v1.1-simulation/` is Model-B material | No citation link found; only subject-matter overlap | Weak, already correctly hedged in MD-026 as weaker than claim 6 | Independent of claim 6 | Already correctly hedged (`E1-OUT-OF-SCOPE, weaker`, `Outcome D`) | **CONFIRMED as already-hedged** |
| 9 | `ConflictRecord` is not evidenced (admissibly) | Exhaustive census + explicit self-disqualification of the one lead found | Strong — the disqualification is itself source-stated | Independent | Unchanged | **CONFIRMED** |
| 10 | `Θ` is not evidenced | Exhaustive census, zero hits | Strong within the searched frame | Independent | "Not evidenced within the searched frame" (already MD-026's own wording) — verify no stronger claim crept in elsewhere | **CONFIRMED, wording already correctly scoped** |
| 11 | No admission mechanism exists in the governing protocol | Direct reading of `protocol.md`, MD-010, MD-011 | Strong (absence within what was read) | Independent | "None found in the documents read" (not "none exists anywhere in the repository," which was not exhaustively verified) | **NARROWED slightly** |
| 12 | Formal corpus-boundary decision is the next action | Follows from claims 1, 3, 11 | Strong as a procedural conclusion, independent of claims 2/6/7's own overstatement | Depends on 1, 3, 11 (not on the overstated 2/6/7) | Unchanged — this conclusion survives the corrections to 2/6/7 intact | **CONFIRMED** |

## Summary

**3 of 12 claims required narrowing (2, 6, 7); 1 required a small scope qualifier (11). 8 of 12 were
already correctly hedged in MD-026's own text and survive unchanged.** The central procedural
conclusion (claim 12) is not undermined by any of the corrections — it was never load-bearing on the
overstated claims.
