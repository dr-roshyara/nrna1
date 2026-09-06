# 13 — Implementation Reality Check

**Mandate §18.** *"Do not count documentation as execution."*

Every row below is the result of a command this session ran. Commands are in `exec/` or reproduced
inline.

---

## 1. The correction that reframes this whole document

Step 267 built its evidence map by searching for theory objects in **PublicDigit's election
domain**: `GovernanceLineageGraph` (Membership/Committee/Constitutional), `EvidenceSet`
(Adjudication/Determination), `decisionId`, `integrityHash`, `authorities.yaml`. On that basis it
reported `K = (𝒜,ℛ)` as **IMPLEMENTATION MISSING**.

The governed-knowledge system in this repository is the **Engineering Knowledge Platform**:
`docs/knowledge/` + `scripts/knowledge-lint.php` + `scripts/knowledge-graph.php` +
`docs/knowledge/schema/`. It runs:

```
$ php scripts/knowledge-lint.php
Engineering Knowledge Platform — knowledge-lint
Scanned 37 governed documents.
✅ All documents pass.
```

**IR-1 (`EXECUTED`, CRITICAL — corrective).** `K = (𝒜, ℛ)` **is implemented and running.** Step 267's
headline empirical finding is `REFUTED`; it searched the wrong bounded context.

---

## 2. The correspondence matrix, executed

| Theory object | Formal definition | Implementation | Real instance | Executable test | Result |
|---|---|---|---|---|---|
| `Proposition (E,D,V)` | Step 262 | knowledge-card claim fields | 40 cards | `exp_ekp_bridge` EXP-11 | **partial** — a card is one document, not one `(E,D,V)` triple |
| `Assertion (id,P,e,c,t,Π)` | Step 267 | knowledge card | 40 | EXP-11 | **realized**: `knowledge_id`/`bounded_context`/`code_refs`+`test_refs`/`owner`+`authority`/`last_review` |
| `𝒜` (assertion set) | — | governed docs | **40** | EXP-11 | **realized** |
| `ℛ ⊆ 𝒜×𝒜×RelType` | Step 262 | typed frontmatter relations | **59 edges, 6 types in use** | EXP-11 | **realized** |
| `K = (𝒜,ℛ)` | Steps 262–269 | the knowledge graph | 1 | EXP-11 | **realized** — refutes Step 267 |
| referential integrity of `ℛ` | invariant | `relationship_targets_exist` (error-level) | 52 id-typed edges | EXP-15 | **HOLDS, 0 violations** |
| acyclicity of `requires`/`depends_on` | Step 209 | `circular_dependency` rule | — | linter | **enforced** |
| `Σ_lifecycle` | ARC D | `status` (8 states) | 4 in use | EXP-12/13 | **realized**, but see IR-4 |
| `Σ_source-trust` | ARC D | `authority` (5 ranks) | 4 in use | EXP-12 | **realized** |
| `Σ_epistemic` | Steps 025o, 266 | — | **0** | EXP-12/14 | **ABSENT** |
| `Evidence(O,P,C,R)` | Closure-04 | — | 0 | EXP-14 | **ABSENT** — no lint rule mentions evidence |
| `Assessment` | Step 266 | — | 0 | — | **ABSENT** |
| `History H` | Step 247 | git history (outside the model) | — | `exp_provenance` T6 | **ABSENT from the model** |
| `T` transformation | Steps 240, 259 | — | 0 | — | **ABSENT** |
| `Provenance Π` | Step 265 | `owner`, `authority`, `code_refs` | 40 | EXP-11 | **partial** — no `method`, no assertion timestamp |
| `Lineage` | derived | `derived_from` edges + `GovernanceLineageGraph` | few | `--filter=Lineage` | **realized** (see IR-6) |
| `Authority` (permission) | Step 187 | `owner`, `reviewers`, `requires_adr_to_change` | 40 | GR-4 | **partial** |
| `Policy` | Step 270 | the schema + the linter | 16 rules | EXP-14 | **partial** — structural rules only |
| `supersedes` / `superseded_by` | Steps 025m, 185 | schema keys exist | **0 in use** | EXP-15 | **UNEXERCISED** |
| `implements`, `verified_by`, `reviewed_by`, `adr`, `depends_on` | schema | keys exist | **0 in use** | EXP-15 | **UNEXERCISED** |

---

## 3. What the invariant tests actually returned

```
I1 supersedes acyclic/antisymmetric              population=0    violations=0   VACUOUS
I2 superseded_by is converse of supersedes       population=0    violations=0   VACUOUS
I3 status=superseded => superseded_by non-empty  population=0    violations=0   VACUOUS
I4 single authoritative per topic+context        population=0    violations=0   VACUOUS
I5 all relation targets resolve                  population=52   violations=0   HOLDS
```

**IR-2 (`EMPIRICALLY OBSERVED`, HIGH).** Four of five invariants pass **vacuously**. A vacuous pass is
not a pass: the real data contains no instance of the construct, so the invariant has never been
exercised by reality.

Relation types the schema offers and the live graph never uses:
`adr, depends_on, implements, reviewed_by, superseded_by, supersedes, verified_by`.

**The supersession and implementation machinery — the part of the theory that carries revision,
replacement and traceability — is entirely unexercised in the only running instance available.**
Every corpus claim about supersession, retraction and revision (Steps 025m, 185, 191, 246) has
**zero** empirical support.

**IR-3 (`EXECUTED`).** `I4` was initially reported as 2 violations by this session and then corrected:
the linter's rule fires only when an explicit `topic` is present, and no governed document declares
one. Under the linter's own condition the population is 0. The earlier number was an artefact of
this session's fallback to `knowledge_type` as a proxy, and is withdrawn.

---

## 4. The status axis is real, and its encoding is wrong

```
statuses.yaml order:  draft 1 · discovery 2 · reviewed 3 · approved 4 ·
                      baseline 5 · frozen 6 · superseded 7 · archived 8

order-rule 'advance iff order(new) > order(old)':
  approved(4)   -> superseded(7)  allowed
  frozen(6)     -> superseded(7)  allowed
  draft(1)      -> frozen(6)      ALLOWED  (skips four states)
  superseded(7) -> approved(4)    BLOCKED  (blocks legitimate un-supersession)
```

**IR-4 (`EXECUTED`, HIGH).** One integer encodes two different things — progression (1–6) and
retirement (7–8). The lifecycle needs a **covering relation** (which pairs are adjacent);
`statuses.yaml` carries none, and no lint rule checks transitions. `knowledge-lint` validates
*membership* in the vocabulary and never a *transition*, which is why the system passes.

This is the same defect the pre-existing `ladder_dc_reference.py` demonstrated for the theory's own
3-status ladder (*"the ratified 3-status ladder + Committed boundary as a covering relation (I-12):
skipping is rejected"*). **The theory got the covering relation right and the implementation did
not.**

---

## 5. The thing actually named "KnowledgeOS" in the codebase

```
scripts/observations/KnowledgeOsDoctor.php        126 lines
scripts/observations/KnowledgeOsInitPlanner.php    36 lines
$ php artisan test --filter=KnowledgeOs
Tests:  10 passed (34 assertions)
```

Its own docblock: *"KnowledgeOS doctor — pure verification of gathered environment facts … the
observation hook sat active NOWHERE until a manual diagnosis found it; this class is that diagnosis,
made repeatable."*

**IR-5 (`EXECUTED`).** It is a **git-hook / husky installation diagnostic**. It is well-written and
its tests pass; it has no relationship to the theory. Anyone grepping the codebase for "KnowledgeOS"
finds this first.

And:

```
$ grep -rl "KnowledgeState|EpistemicStatus|epistemic" app/ | wc -l
0
```

**Zero production files** in `app/` mention `KnowledgeState`, `EpistemicStatus` or even `epistemic`.

---

## 6. The provenance claim, reproduced and unpacked

```
$ php artisan test --filter=Lineage
Tests:  11 deprecated, 47 passed (125 assertions)
```

The figure in ≥15 verification artifacts reproduces **exactly**. What it covers:

- **18 test classes** match the filter.
- **One** — `GovernanceLineageGraphTest`, **4 tests** — exercises the typed provenance graph.
- The other 43 are membership-lifecycle, voting-eligibility and election-security-divergence tests
  (`DivergenceSeverityTest`, `VotingEligibilityPolicyTest`, `MembershipLineageImmutabilityTest`, …).

**IR-6 (`EXECUTED`, HIGH).** The claim *"a typed provenance graph with branching exists, is tested and
passes"* is **TRUE**, and rests on **4 tests**. The origin artefact (`EMPIRICAL-KERNEL-TEST.md`) names
exactly those four in its own table; the round number then propagated into fourteen further artifacts
as the evidence for the provenance claim. Evidential weight overstated ≈ 12×.

---

## 7. Mismatch classification

| Mismatch | Type |
|---|---|
| `Σ_epistemic` in theory, absent from implementation | **THEORY GAP** — the theory's central axis has no instance and no design for one |
| `Evidence` relation absent from implementation | **THEORY GAP** — `Relevant` is class C, so it *cannot* be implemented as specified |
| supersession machinery specified and never used | **OBSERVABILITY GAP** — the construct exists in the schema; reality produces no instances |
| `status` order conflating progression and retirement | **IMPLEMENTATION GAP** — the theory has the covering relation; the schema does not |
| `vocabulary-integrity.yaml` missing → S3 132/132 INCONCLUSIVE | **IMPLEMENTATION GAP** |
| schema vocabulary files ungoverned | **IMPLEMENTATION GAP** with a **THEORY** counterpart (`10` GR-3/GR-5) |
| `GovernanceLineageGraph` cited as `History` | **SEMANTIC GAP** — Type 3 analogy reported as Type 1 realization |
| `K` reported missing while running in the EKP | **REPRESENTATION GAP** — the search looked for class names, not for the shape |

---

## 8. What is genuinely, executably true today

Stated positively, because it is easy to lose in a gap register:

1. **A knowledge state of the theory's exact shape exists and is machine-validated.** 40 assertions,
   59 typed relations, 6 relation types, referential integrity holding, cycles checked, 37 documents
   passing a linter on every change.
2. **Two orthogonal status axes are running and schema-enforced**, with the orthogonality stated in
   the schema's own comments and visible in the data (7 distinct pairs).
3. **A typed provenance graph with branching detection exists and is tested** (4 tests).
4. **A real self-amendment rule exists** for the constitution (ADR + supersession + ARB, with
   `frozen` machine-enforced).
5. **The deepest assurance mechanism is fail-closed by construction** — *"Absence of evidence is not
   PASS"* — even though it is currently inert.

That is more than the corpus credits itself with, and it is in a different place than the corpus
looked.

---

## 9. Findings

| ID | Finding | Class | Severity |
|---|---|---|---|
| **IR-1** | `K=(𝒜,ℛ)` **is implemented and running** in the EKP (40/59, lint passing). Step 267's "IMPLEMENTATION MISSING" is refuted; it searched the election domain. | `EXECUTED` | **CRITICAL (corrective)** |
| **IR-2** | Four of five invariant checks pass **vacuously**; the entire supersession/implementation relation family is unexercised in the live graph. | `EMPIRICALLY OBSERVED` | HIGH |
| **IR-3** | This session's own `I4 = 2 violations` was an artefact of a proxy for `topic`; withdrawn. | `EXECUTED` | — |
| **IR-4** | `statuses.yaml` encodes progression and retirement in one integer; the order-rule permits `draft → frozen` and forbids un-supersession; no transition legality is checked. The theory has the covering relation; the implementation does not. | `EXECUTED` | HIGH |
| **IR-5** | The code named "KnowledgeOS" is a husky/git-hook diagnostic (10 tests passing, unrelated). Zero files in `app/` mention `KnowledgeState`, `EpistemicStatus` or `epistemic`. | `EXECUTED` | MEDIUM |
| **IR-6** | "47 tests" reproduces exactly and covers 18 unrelated classes; 4 tests exercise the provenance graph. Claim true, weight ≈12× overstated across 14 artifacts. | `EXECUTED` | HIGH |
| **IR-7** | Five substantive capabilities are genuinely running today (§8). | `EXECUTED` | — (positive) |

---

**Next:** `14-FALSIFICATION-RESULTS.md`.
