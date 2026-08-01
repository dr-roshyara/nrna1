# PKS Observation — Classification Cannot Be Determined Because Type and Role Are Not Placement Inputs

**Date:** 2026-08-01 · **Kind:** operational evidence · **Domain:** PKS
**Status:** **observation and candidate pattern. NOT promoted. No taxonomy invented, no folder created.**

---

## 1. Observation

**Four times in one engineering stream, an artifact's scope and domain were determinable and its *classification* was not.** Placement was then derived from scope alone — **twice incorrectly.**

| Artifact | Scope/domain | Classification | Outcome |
|---|---|---|---|
| `Layer_Verification_Rule.md` | cross-product, research | undetermined | written into `engineering/` prematurely — **the defect that opened the thread** |
| Documentation-index rule candidate | cross-product, research | undetermined | resolver returned **PENDING**; recorded inside its evidence artifact |
| Recurring-unruled-classification principle | cross-product, research | undetermined | **PENDING**; recorded inside its evidence artifact |
| `WP-7C_Engineering_Readiness.md` | product-specific, publicdigit | **not asked** | **placed wrongly** in a documentation root; relocated to the runtime mount |
| `PublicDigit_Engineering_Protocol.md` | product-specific, publicdigit | **not asked** | **placed wrongly**; relocated to `docs/implementation/` as a proposal paper |

**Five artifacts, four commissions, one day.** **Recurrence, not accumulation.**

## 2. Evidence — three partial taxonomies exist and none is joined to placement

**No taxonomy was invented for this observation. Three already exist:**

| Vocabulary | Home | Values | Scope of application |
|---|---|---|---|
| **Document type** | `docs/knowledge/schema/knowledge-types.yaml` | 26 — `constitution · architecture · adr · aggregate · event · policy · api · implementation · quality · test · research · decision · playbook · recipe · package · prompt · guide · tutorial · runbook · reference · checklist · template · status · idea · review · portal` (+ hyphenated variants in use: `domain-model`, `state-machine`, `ddd-discovery`) | **`docs/knowledge/` only** |
| **Artifact role** | **ES-004.3** | 4 — `Runtime · Historical · Reference · Decision` | **repository-wide** |
| **Record responsibility** | **ES-004.2** | 4 — `IDD = implementation decisions · ADR = architectural decisions · retrospective = lessons · CONTEXT = current state only` | records only |

### The measurement

```
markdown documents repo-wide       2388
inside docs/knowledge/              132
carrying a declared knowledge_type   40   (1%)
UNTYPED                            2348  (98%)
```

> **A validated type vocabulary exists and covers 1% of the corpus.** **A repository-wide role vocabulary exists and is declared on nothing.** **The placement resolver reads neither** — it reads Scope, Maturity and Domain.

## 3. The gap, stated as narrowly as the evidence allows

> **The gap is not a missing taxonomy. It is that three taxonomies exist, none is repository-wide *and* declared, and none is an input to placement.**
>
> **Consequence, demonstrated twice today: the resolver cannot catch a placement error caused by artifact kind, because kind is not something it can see.** Both wrong placements were *scope-correct*. **The resolver answered correctly the only question it was asked.**

**What the evidence does not support:** that the type vocabulary is inadequate. **`checklist`, `status`, `implementation`, `review`, `reference`, `decision` plausibly cover every artifact produced in this stream** — **the vocabulary was never consulted, because it does not apply outside `docs/knowledge/` and nothing requires it.**

## 4. Where to add — the smallest change that closes it

**Not a new folder. Not a new taxonomy. Not a new standard.** **Three existing vehicles already carry this:**

| # | Addition | Existing vehicle |
|---|---|---|
| **1** | Add **`type`** and **`role`** as classification inputs alongside Scope · Steward · Maturity · Domain | **the prepared ES-005 amendment package** — already proposes adding Steward and Domain as inputs; **this is a third input to the same amendment, not a second amendment** |
| **2** | Widen the type vocabulary's scope from `docs/knowledge/` to governed documents generally | **`docs/knowledge/schema/knowledge-types.yaml`** — the vocabulary exists and is machine-validated; **only its applicability is narrow** |
| **3** | Make role derivable or declarable | **ES-004.3** already defines the four roles and their placement semantics — *Runtime must describe today's execution state*, which is exactly the rule that would have caught the readiness artifact |

> **The placement registry is already the join point.** It reads classification and returns a location. **Adding `type`/`role` extends the function's inputs; it changes no rule and creates no home.**

**Sequencing, if ever adopted:** the ES-005 amendment is **prepared and unapplied**, and applying it needs one ruling. **This finding belongs in that package rather than in a new one** — which is also why no new artifact was created for it beyond this observation.

## 5. Why this is not promoted

**Candidate pattern. PKS observation. Not KnowledgeOS.**

| Test | Result |
|---|---|
| Operational evidence | ✅ **five artifacts, four commissions, one day** |
| Repeated pattern | ✅ **recurrence across unrelated commissions, none looking for it** |
| **Cross-product confirmation** | ❌ **one repository, one corpus** |

> **One corpus is one observation, however many instances it produced.** **A model generalized from a single repository is a model fitted to that repository** — the standard already applied to the documentation-index candidate. **Promotion requires another repository to produce the same finding.**

**And a caution recorded against my own likely next move:** the temptation is to model the documentation domain fully — `Document { Type · Role · Scope · Steward · Maturity · Domain · PlacementPolicy }`. **That is a richer model than the evidence has earned.** **The evidence supports exactly two additions to an existing function. Everything beyond that is design ahead of demand**, which is the thing this programme rejects by default.

## 6. Reproduction

```bash
php scripts/doc-placement.php --list          # the inputs the resolver actually reads
grep -c '^  ' docs/knowledge/schema/knowledge-types.yaml   # the type vocabulary
```

---

**Traceability:** **ES-004.2** (record responsibilities; Engineering Plan vs Work Plan) · **ES-004.3** (the four artifact roles and their placement semantics) · **ES-005.3** (the derivation) · `docs/knowledge/schema/knowledge-types.yaml` (the existing vocabulary) · `docs/knowledge/schema/documentation-placement.yaml` (the join point) · `2026-08-01-artifact-classification-correction.md` (the two wrong placements) · `2026-08-01-recurring-unruled-classification-finding.md` (the three PENDING arrivals) · `2026-08-01-es005-amendment-package.md` (the vehicle). **No taxonomy invented · no folder created · no standard amended · nothing promoted.**
