# `exec/` — Executable evidence for the independent gap-discovery session

Every claim marked `EXECUTED` in documents 00–17 traces to a program here.
Nothing in this directory is architecture. It is a set of witnesses.

## Run everything

```bash
for f in exec/exp_*.py; do echo "== $f"; python3 "$f"; done
```

No dependencies beyond the Python standard library. `exp_ekp_bridge.py` additionally reads the
live repository (`docs/knowledge/`, `scripts/knowledge-lint.php`) — its numbers change if the
repository changes.

## Files

| File | Purpose |
|---|---|
| `kos_kernel.py` | Reference implementation of the corpus's **terminal** model: `K=(𝒜,ℛ)`, `A=(id,P,e,c,t,Π)`, four candidate equalities, a four-operation `𝒪`, `T`, and `replay`. |
| `exp_congruence.py` | EXP-1…3 — state/history congruence. Content-only fails; provenance-in-assertion is not the repair; `K=(𝒜,ℛ)` sufficiency depends on `𝒪`. |
| `exp_measurement.py` | EXP-4…7 — Roberts admissibility. Ordinal averaging flips; `AggregateSupport` and `IndependenceFactor` refuted as measurements; confidence is not a probability. |
| `exp_identity.py` | EXP-8…10b — `A∈K` and `K₁=K₂` under four equalities; merge algebra with an **exhaustive** associativity search. |
| `exp_ontology.py` | EXP-16…18 — definitional dependency graph: cycle detection, class-C reachability, and Step 254's removal test. |
| `exp_assertion.py` | EXP-19…20 — expressiveness of `P=(E,D,V)` against ten corpus-required propositions; the founding-problem check. |
| `exp_sigma.py` | EXP-21…23 — Σ necessity by decision signature; axis decomposition; irreducibility. |
| `exp_provenance.py` | EXP-24…29 — the mandate's six provenance/lineage/history tests. |
| `yaml_lite.py` | Dependency-free reader for the two flat EKP vocabulary files. |
| `OUT-*.txt` | Transcripts, regenerated from the scripts. |

## Reproduced from outside this directory

```bash
php scripts/knowledge-lint.php                                   # 37 documents, all pass
php scripts/knowledge-lint.php --profile=structural --root=docs/knowledge
php artisan test --filter=Lineage                                # 47 passed (125 assertions)
php artisan test --filter=KnowledgeOs                            # 10 passed (34 assertions)
python3 docs/knowledgeos/reviews/synthesis/analysis/mathematical-tests/zero_reference.py
python3 docs/knowledgeos/reviews/synthesis/analysis/mathematical-tests/exp01_recheck.py
python3 docs/knowledgeos/reviews/synthesis/analysis/mathematical-tests/ladder_dc_reference.py
```

## Corrections made during the session

Recorded rather than hidden:

1. A hand-picked latest-wins merge triple came out **associative**; the narrative asserting
   non-associativity was replaced by an exhaustive search (`EXP-10b`), which found 4 genuine
   counterexamples.
2. An apparent dangling relation target (`PKG-IMPLEMENT-AGGREGATE`) was a **parser artefact** — the
   card is a `.yaml` package, not a `.md`. Withdrawn after fixing the parser.
3. Two apparent `single_authoritative` violations were an artefact of this session using
   `knowledge_type` as a proxy for `topic`. Under the linter's own condition the population is 0.
   Withdrawn.
