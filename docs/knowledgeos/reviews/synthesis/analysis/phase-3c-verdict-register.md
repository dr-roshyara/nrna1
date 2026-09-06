# PHASE 3C · Verdict Register (D-3)

**One verdict per tested relation. Vocabulary: CONFORMANT / PARTIALLY CONFORMANT / NON-CONFORMANT /
NOT ESTABLISHED / OUT OF SCOPE. Date of all verdicts: 2026-08-28. Evidence: D-2 (CF-nnn).**
**Grades preserved:** [ED] evidence-derived · [CO] composed · [RC] required-by-coherence element of v0.2.

## T-1 · Canonical concepts

| Relation tested | Verdict ⟦V⟧ | Basis |
|---|---|---|
| Knower [ED] — exists as first-class, final authority | **PARTIALLY CONFORMANT** | CF-012 (goal/IdealState ownership unevidenced) |
| Zero(K,EC) [ED/RC] — goal-gap function has a repo counterpart | **NOT ESTABLISHED** (no counterpart found; the term "Zero" exists in an incompatible third sense — naming-collision finding CF-005) | CF-005 |
| EC = η(G, IdealState) [RC] | **NOT ESTABLISHED** | CF-004/CF-005 — no counterpart found |
| Evidence ≠ acceptance; governed admission (Determination/AcceptancePolicy) [ED] | **CONFORMANT** (conceptual) | CF-013 |
| Proposal ≠ decision [ED] | **CONFORMANT** | RA §4 (engines propose, gate decides) |
| Decision Contract DC(d) 6-tuple [CO] | **NOT ESTABLISHED** | CF-014 |
| Policy-as-content vs policy-in-force (R-1) [RC] | **CONFORMANT** (content) | CF-011, caveat CF-003 |
| Epistemic ladder 3-status + Committed boundary (R-3) [RC] | **PARTIALLY CONFORMANT** | CF-006 (structural, not nominal), CF-009 (repo states richer) |

## T-2 · Invariants

| Invariant | Verdict ⟦V⟧ | Basis |
|---|---|---|
| I-1 Knower owns problem/purpose/IdealState/final authority [ED] | **PARTIALLY CONFORMANT** | CF-012 |
| I-2 Proposal ≠ decision [ED] | **CONFORMANT** | RA §3/§4 |
| I-3 SufficientKnowledge ≠ ValidDecision ≠ AuthorizedAction [ED] | **CONFORMANT** | CF-007, CF-014 |
| I-4 Authority determines commitment, not evidential truth (A6) [ED] | **CONFORMANT** | CF-007 |
| I-5 duplicate must not raise confidence; corroboration must [ED-TESTED] | **CONFORMANT** (property demanded & tested) | CSV columns; caveat CF-010 |
| I-6 dependency resolution precedes aggregation [ED-TESTED] | **NOT ESTABLISHED** (in-scope repo states no ordering rule) | CF-010 ⟦INT⟧ |
| I-7 X_t ≠ Observed(X_t) [ED] | **CONFORMANT** (as principle) | Art. 6.5; Zero doc "observation into reality" |
| I-8 no architectural assertion without evidence [ED] | **CONFORMANT** | "final rule" pervasive in Tier 1 |
| I-9 non-satisfaction typology never Boolean [ED] | **CONFORMANT** | Art. 9 + four-fold Abhāva taxonomy |
| I-10 constitution changes require approval [ED] | **PARTIALLY CONFORMANT** | CF-011 content ✓; CF-003 practice gap |
| I-11 in-force policy changes only by governed, versioned decision (R-1) [RC] | **PARTIALLY CONFORMANT** | CF-011 + CF-003 |
| I-12 covering relation, no skipping (R-4) [RC] | **PARTIALLY CONFORMANT** | CF-008 (practice ✓, axiom absent) |

## T-3 · Ladder + A6 — **PARTIALLY CONFORMANT** (CF-006, CF-007, CF-009)
## T-4 · No-skip in practice — **CONFORMANT** (CF-008 operational chain; the axiom-as-text row is in T-2/I-12)
## T-5 · Decision Contract & authorization boundary — boundary **CONFORMANT** (CF-007/CF-014); DC object **NOT ESTABLISHED** (CF-014)
## T-6 · Policy stratification — **PARTIALLY CONFORMANT** (CF-011 content conformant; CF-003 in-force tracking gap)
## T-7 · Evidence-algebra non-claim — **CONFORMANT** (neither side selects an operator) with evidence-discrepancy finding CF-010
## T-8 · Non-collapse distinctions — **CONFORMANT** (CF-007 six-way bootstrap non-collapse; Art. 1–4 anti-collapse laws; CF-016)
## T-9 · v0.2 open questions

| Open question | Status after 3C | Basis |
|---|---|---|
| η-totality / G-residual inside Zero | **OPEN, unchanged** — no repo evidence either way | CF-005 |
| Kernel membership | **OPEN** — repo has its own 11-article kernel decision; correspondence to v0.2 kernel candidates unmapped | CF-004/CF-006 |
| Lord naming collision | **OPEN, untouched** — no in-scope repo evidence | — |
| Action/execution semantics | **OPEN** — boundary-side support gained (execution outside knowledge scope), semantics still unspecified | CF-014 |

## Scope verdicts (from triage)
| Artifact | Verdict |
|---|---|
| `.claude/worktrees/kos-v11-ddd/` content | **OUT OF SCOPE** (divergence recorded, CF-001) |
| `docs/eks/` | **OUT OF SCOPE** (CF-002) |
| brainstorming corpora, synthesis workplace, session1 | **OUT OF SCOPE** (per ruling) |
