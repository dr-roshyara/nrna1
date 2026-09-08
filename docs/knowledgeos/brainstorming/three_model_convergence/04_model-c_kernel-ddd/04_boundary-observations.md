# Model C1/C2 — Boundary Observations

Accounts for material not treated as C1/C2 evidence: the 39 secondary-tagged rows; the 3 historical
`kernel_ddd`/`OUT_OF_SCOPE_ROOT` rows; the C2-population investigation and its bounded findings (per
the authorization's explicit guardrail — reported, never reclassified); and data-quality anomalies,
including one self-correction of this reconstruction's own earlier claim.

---

## Secondary-tagged C1 material (39 rows, not opened as evidence)

**Main corpus (2 rows, both `PRIMARY` tier)**: seq 0052 ("research on role separation") and seq 0060
("DDD correction verification verdict") — both early EKS-era files whose primary lineage lies
elsewhere (their own `initial_primary` is not `engineering_knowledgeos`). Not opened as C1 evidence,
per the authorization's evidence-population rule (membership by primary classification, not by any
secondary tag).

**Math lane (37 rows)**: named but not opened, exactly as Model B's own `04_boundary-observations.md`
already recorded these as C1-adjacent boundary material outside Model B's own scope. Phase 4 is the
first phase authorized to open C1 evidence, but a *secondary*-tagged row is still boundary material,
not primary evidence, under this phase's own population rule — so these 37 remain unopened here too,
named only as a count.

## Historical `kernel_ddd` material (3 rows, `OUT_OF_SCOPE_ROOT`, cited for provenance only)

Seq 0001, 0005, 0009 (`docs/knowledgeos/` root, not `brainstorming/`) — per MD-011's own prior
correction, these sit outside the primary corpus entirely and are not counted toward any phase's
evidence population. They are, however, the literal evidentiary origin of the C1/C2 split (MD-006
quotes their Tier-2 findings directly: seq 0001's "the corpus may use 'kernel' in at least two
non-interchangeable senses"; seq 0005's "these are different minimality tests and may select different
sets"; seq 0009's "the corpus keeps enumerating kernel members without ever stating a membership
property"). Cited here as historical context only, consistent with their treatment throughout MD-021.

## The C2-population investigation (authorization guardrail — bounded, no reclassification)

**Scope of the investigation**: the specific corpus region seq 2330 (the sole C2 file) itself cites as
its own lineage — `docs/knowledgeos/brainstorming/kernel/`, approximately seq 2296–2354 — not an
unbounded search across all 732 C1 files or the wider corpus. This is a deliberately narrow, citation-
driven scope, chosen because it is the one region where C2-adjacent content is concentrated and
independently corroborated by the corpus's own cross-references (seq 2330 explicitly names seq 2318,
2321–2323, 2327–2328 as its own antecedents).

**Finding**: the immediate neighbors of the sole C2 file carry substantial epistemic-Kernel-adjacent
content under classifications *other* than `epistemic_knowledgeos`:

| Seq | Classification | Content relevance |
|---|---|---|
| 2317 | `meta_research` | "Knowledge Calculus" proposal, natural-deduction-style transformation rules |
| 2318 | `meta_research` | "Working Hypothesis: Knowledge Space Inside a Probability Space" — measure-theoretic foundation `𝒦⊆(Ω,ℱ,ℙ)` |
| 2319 | `meta_research` | "Independent Research Line Appears to Have Arrived at the Same Result" — corpus's own internal convergence claim between a Gita/measure-theoretic line and an engineering/DDD-derived Kernel line |
| 2321 | `meta_research` | Refutes "random variable requires a metric"; downgrades the probability-space hypothesis `H_P1` |
| 2322 | `cross_model` | Explicitly names and defines all four models (C1/C2/M/G) — a bridge document, not C1/C2 evidence itself |
| 2323 | `cross_model` | Primary-corpus commentary written **about this reconstruction project itself** — see the data-quality note below |
| 2324 | `cross_model` | Layered epistemic-state model `X→Y_t→E_t→ℱ_t→Π_t→K_t`, "two strong negative convergence" findings |
| 2328 | `engineering_knowledgeos` | "What Eleven Experiments Established" — the KR-SIM lane's own major synthesis (this row **is** C1 evidence, already counted in the 732) |
| 2330 | `epistemic_knowledgeos` | The sole confirmed C2 file |

**Disposition, per the guardrail**: every row above is reported here **exactly as classified**, with
its content relevance stated as the *reason it appears adjacent*, not as a proposal to change its
`model.primary`/`initial_primary` value. None of seq 2317–2324 is treated as C1 or C2 evidence
anywhere in this phase's artifacts. Whether this concentration of `meta_research`/`cross_model`-tagged
epistemic content should eventually be reconsidered is **explicitly left to a separately-authorized
classification-review phase** — this phase names the pattern and stops.

**Seq 2325–2327**: no per-file record exists for these three sequences (confirmed by direct query,
not merely assumed) — a small, genuine data gap immediately adjacent to the investigated cluster, not
resolved here. Recorded as a corpus-integrity anomaly, matching the register's own blank `initial_
primary` for these three rows.

## Data-quality anomalies found (and one self-correction)

**Self-correction, recorded transparently**: this reconstruction's own Phase-4 planning pass (before
this phase's own authorization) claimed a "register-vs-per-file inconsistency" at seq 2330 — that the
register's `initial_secondary` showed `"b"` while the per-file YAML showed `secondary: None`. **On
full raw-source reading during this phase, this claim is corrected: the per-file record's actual
`model.secondary` field (nested under `model:`, not a top-level `secondary` key) is `"b"`, exactly
matching the register.** The earlier claim resulted from reading the wrong field path in a prior
verification script, not from any real inconsistency in the governed records. **No anomaly exists at
seq 2330** — recorded here as a correction to this reconstruction's own prior planning-stage claim,
per this project's own evidence-preserving-correction discipline (never silently drop a prior claim;
state the correction and why).

**Genuine anomaly, unrelated to the above**: seq 2323 is primary-corpus commentary written about the
`three_model_convergence/` reconstruction project itself, appearing inside the very corpus this
project reads — a structural self-reference. Recorded as a data-quality/meta anomaly, not resolved or
investigated further here (doing so would risk exactly the kind of self-referential contamination this
reconstruction's own discipline exists to prevent).

**Genuine anomaly, unrelated to the above**: seq 2325–2327 have no per-file record (noted above).

---

## What this document does not do

- Does not reclassify seq 2317–2324, the 39 secondary-tagged rows, or any other file.
- Does not treat any `meta_research`/`cross_model`-tagged row as C1 or C2 evidence in
  `01_evidence-base.md` or `02_concept-register.md`.
- Does not resolve whether the C1/C2 classification boundary itself needs governance attention — that
  determination belongs to a separately-authorized phase.
- Does not compare any boundary observation here against Model A, Model B, or Phase 3's own findings.

**Total accounted for**: 39 secondary-tagged C1 rows + 3 historical `kernel_ddd` rows + 9 rows
examined in the C2-population investigation (1 of which, seq 2328, is already counted in the 732 C1
primary population; the other 8 are boundary-only) + 3 rows with no per-file record. No row is
double-counted as both evidence and boundary material.
