---
source_track: TRACK-A-PHASE-MEASURE
input_artifacts: [KSME-12-TERM-DISCOVERY-REGISTRY]
derived_from: [all KSME-13/13A fork reports]
cross_track_dependency: none
---

# KSME-13A — Term Discovery Registry (extends KSME-12's; does not overwrite)

| Term | First discovered | Timestamp | Defined? | Source | Status | Thread |
|---|---|---|---|---|---|---|
| `𝒟`, `𝒞`, `𝒳` (Step 32 Authorize's raw subsets) | Step 32 §32.1/§32.4 | 2026-08-28 | Y (`𝒳`=AuthorizedAction via pipeline role); N (`𝒟`/`𝒞` independently) | primary | Partial | Authorize |
| `Promote` | Step 32 §32.5 | 2026-08-28 | Y | primary | Established | Revision-adjacent |
| `Merge_v` | Step 60 §60.44 | 2026-08-28 | N | primary | Not-found-in-search (not pursued) | Merge-adjacent |
| `Conflict(p,t)` | Step 60 §60.45-46 | 2026-08-28 | Y (typed, different arity) | primary | Unresolved vs. `Conflict(p)` | Contradiction |
| `ConflictAt(t₁)`/`ResolvedAt(t₂)` | Step 60 §60.30 | 2026-08-28 | Y | primary | Established (postcondition only) | Contradiction |
| `Authorize_Step259` | Step 259 | 2026-08-30 19:20:58 | Y (`Actor×Action×Policy→Decision`) | primary | Established, isolated | Authorize |
| `Authorize_277.20` | Step 277 | 2026-08-30 22:01:34 | Y (`Authorize(a,π,p)→Boolean`) | primary | Established, refines into 277.25 | Authorize |
| `Authorize_277.25` | Step 277 (same doc) | 2026-08-30 | Y (`Authorize(α,π,t,K)→{true,false}`) | primary | Established | Authorize |
| "the earlier corpus" (unattributed) | Step 277 line 907 | 2026-08-30 | N | primary | Not-found-in-search | Authorize (dangling) |
| `Policy`/`Authority` (internal structure) | Step 277 §277.36 | 2026-08-30 | N (source explicitly says absent) | primary | Source-explicitly-absent | Governance-dependency |
| `DomainPolicy`/`HumanAdjudication`/`StatisticalModel` | Step 60 §60.23 | 2026-08-28 | N | primary | Not-found-in-search | Resolve-dependency |
| `theory-v1.2-simulation/` (33 files, no code) | `docs/knowledgeos/research/` | 2026-09-02 (commit 2026-09-06) | Y (self-described index) | primary | Classified: prior-lane, non-Track-A | Corpus-admissibility |
| `research/knowledgeos-sim/` (kos12 codebase) | repo-root `research/` | commit 2026-09-12 | Y (real code, ~140 result files) | primary | Previously investigated as BC-02.14-20, left untouched | Corpus-admissibility |
| `BC-02.14-K-OBJECT-REGISTRY.md` | this session, pre-KSME | pre-dates KSME series | Y | `.claude/CONTEXT.md` | Established (this session's own prior artifact) | Corpus-admissibility |

## Pending

Everything under `docs/knowledgeos/research/` remains `NOT-YET-TRAVERSED (scope-blocked)` for substantive
content — only index/manifest/git-metadata was read this pass, per explicit constraint.
