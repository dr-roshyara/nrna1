# `KOS-CONTRACT-NEUTRALITY-001` Track 1 — **INDEPENDENT VERIFICATION REPORT**

**Assignment:** `S1-verification-track1-php-adapter` (seq 35 REGISTER · 36 HANDOFF · **37 START**, human act recorded)
**Grants:** `G-KOS-CONTRACT-TRACK1-VERIFY` **+ AMD1** · **Under verification:** the Track-1 delivery `4c6c1dac`, delivery record registered `2abbf79f`
**Date:** 2026-08-18 · **`R-34`/`P-2`: this report verifies. It does NOT accept, does NOT close, and repairs nothing.**

---

# 0 · Independence disclosure — **`P-2`, all four items; read before the findings**

| | Required item | Disclosure |
|---|---|---|
| **`D-a`** | **The overlap, stated plainly** | This process held the **Governance** capacity on this work item earlier today: it checked the preconditions and **registered this very verification grant and assignment**. It then received the PO/ARB `START` and now holds the **Verification** capacity. ⛔ **It did NOT implement Track 1, did not author the architecture, did not draft the semantic clarification, and performed none of the prior verifications.** |
| **`D-b`** | **The prior act, by SHA / path** | Governance act: `f56f129e` and `docs/publicdigit/reviews/2026-08-18-…-track1-verification-registration.md` (content swept into `51910203` / `8af5a421` by two concurrent sessions — recorded there). **Producer of the artifact under verification: `S3-implementation-track1-php-adapter`, self-declared `claude-code-session:1c8b041b` — a different process, which also authored the accepted architecture (`adc5c8e8`), the reconciliation (`70fc25c6`) and the fit assessment (`440fe7b8`).** |
| **`D-c`** | **Evidential status** | ⚠️ **ASSERTED, never attested** (`INV-ATTR-2`). Separation from the implementer is **self-declared**; nothing here forges an attestation, and per `INV-ATTR-1` no gate reads process identity. **A review is not independent merely because the role changed.** |
| **`D-d`** | **Independent contribution — what the producer could not check itself** | Gate 3 **re-derived by execution**, not by grep (probe 1) · a **120-combination cross-product** of L4 against the accepted rule table transcribed from the design document (probe 2) · **two two-arm control experiments** isolating extractor defects (probes 6, 16) · a **5 000-case property test** of L5 against an independently written BFS reference (probe 19) · the dual run reproduced from scratch (probe 12) · the full Unit suite executed and searched for concealment (probe 20) · artifact hashes compared against the **pre-delivery** blobs. |

> ## ⚠️ The material limitation of this review, stated first rather than buried
> **This process wrote the verification scope it is now executing.** It is therefore an independent check **of the implementation** and **not** an independent check **of the scope's adequacy**: an examination area the registration failed to name would be missing here too, and this report cannot detect that. **The bar in the grant — a *fresh* verification engineer — is met with respect to the producer and not with respect to the scope's author.** ⇒ Independence w.r.t. the implementation: **established**. Independence w.r.t. the commission: **not established, and the PO/ARB should weigh it.** *Verification proceeded rather than stopping because `R-34`'s bar is producer→verifier, and that relationship does not hold here.*

**Prior participation with the other named processes:** none — no contact with `S4-architecture-impl-arch-d-b3`, `S4b-architecture-semantic-clarification`, `S1-verification-lcom4-reference`, `S1-verification-python-stage2` or `S1-verification-stage2-breadth` beyond **reading their delivered artifacts as authoritative inputs.**

---

# 1 · Method

**Authoritative inputs, in the registered order:** the workflow record (34→37 transitions, 22 grants) · `G-KOS-CONTRACT-IMPL-TRACK1` + `AMD1` · the accepted implementation architecture `adc5c8e8` §7.2/§7.3/§9 and the reconciliation (`70fc25c6`) §3–§14 · the delivery record and its registration · the diff `4c6c1dac` · the three new test files · `Lcom4CollectorTest` / `Lcom4VerificationSuiteTest` / `ObservationRuntimeTest` · the fit assessment `440fe7b8` · Decisions **13.3 / 13.5** (`…decisions-13-3-13-5-registration.md`), **13.7**, **Decision 1** · `expected.json`'s pinned `_variant_decisions_pinned` · the future/common-engine direction (§12 FUTURE ONLY).
**All probes were throwaway, written outside the repository** (`…/scratchpad/probes`, `…/scratchpad/fx`). ⛔ **No repository file was modified by this verification.**

---

# 2 · Architecture conformance — **Gate 3 re-derived by EXECUTION**

**Probe 1 loaded `Domain/` and `Application/` by hand — no composer autoloader — with an autoload trap that aborts if anything under `Infrastructure/` is ever requested, then ran the full L4→L5 pipeline on hand-built facts.**

```
result:  node set + edge set + LCOM4 produced   ✅
         Infrastructure classes loaded          0        (trap never fired)
         files included outside Domain/Application  0
         PhpFactExtractor declared              NO
```

> ⭐ **This is a stronger claim than the delivery's static count and than Governance's confirmation of it: the neutral layers did not merely *avoid mentioning* PHP — they *ran to completion in a process where the binding did not exist*.**

**Independent static audit (mine, wider than the delivery's):** `token_get_all`, `T_*`, `PhpParser`, `rawLexeme`, `Provenance`, `preg_*`, `file_get_contents`, `Reflection` across `Domain/` + `Application/` → **ZERO matches**. `token_get_all` appears in **exactly one** production file (the binding) plus its own docblock.
**Pipeline realized as accepted:** `PHP source → B3 binding → L3 → L4 → L5`, with the exception preserved in the only form the record permits — **implemented in PHP, not semantically PHP-dependent.** No subprocess, no serialization, no transport (`grep`: `proc_open|exec|json_encode|serialize|curl|python` in the capability → none).

**Verdict A — architecture conformance: ✅ PASS.**

---

# 3 · L3 fact model — the decided concepts, and the collapses that must not happen

| Concept | State | Evidence |
|---|---|---|
| analysed-unit kind | ⚠️ **one value never produced** | `UnitKind` carries all five; **`AnonymousClass` is never emitted by the binding** → **`V-1`** |
| stable anonymous identity | ✅ | `F/anon#1/anon#1`, siblings `F/anon#1`/`F/anon#2`, top-level `anon#1`/`anon#2` — probe 8, exactly `OQ-1`/`AMD4`; `(anonymous)` rejected by the constructor |
| method identity · target method | ✅ | probes 3–11 |
| qualifier kind (10 values) | ✅ | all reachable values observed on real source — probe 11 |
| callable vs invocation | ✅ | `$this->target(...)` → `CallableReference` → `CallableNotInvocation` |
| access mode | ✅ | `?->` carried as `Nullsafe` **and** counted as an edge (13.5) |
| determinability | ⚠️ | correct from the binding; **L4 admits a second path to it** → **`V-5`** |
| property access | ✅ | `$this->p` → `StateAccess`; `$this->m()` → reference — the heredoc distinction holds |
| required name identity | ✅ | empty identity and `(anonymous)` both rejected |
| provenance separation | ✅ *(vacuously)* | `factId` exists, is **never populated**, and no provenance store is built — the separation is honoured because **there is no provenance at all** (`G-5`, declared) |

> ## ⛔ The three forbidden collapses — **tested, not assumed**
> | Must not merge | Result |
> |---|---|
> | **NOT OWN CLASS ≠ NOT DETERMINABLE** | ✅ `Sub\Fq::b()` → `NotTheAnalysedUnit`; `$x::b()` → `NotDeterminable` — **distinct values, both reported** (probes 11, 7) |
> | **ANOTHER CLASS ≠ NOT DETERMINABLE** | ✅ `\Fq::b()` from `App\Fq` → `NotTheAnalysedUnit`, never the not-determinable bucket |
> | **EXCLUDED ≠ UNSEEN** | ⚠️ **honoured for units and for every reference the binding emits — and broken for one class of reference it does not emit** → **`V-3`** |

**Verdict B — L3 semantic fidelity: ⚠️ PASS WITH NOTES** (`V-1`, `V-3`; the model, its invariants and its non-collapse guarantees are correct and demonstrated — two decided facts are not *populated*).

---

# 4 · Semantic rules — falsification attempted case by case

| Decided rule | Probe | Result |
|---|---|---|
| enum = analysed unit | 7 | ✅ `Suit` analysed, LCOM4 2 |
| trait = analysed unit | 7, 12 | ✅ `Helper`, and `SomeBehavior` in the golden fixture |
| anonymous = analysed unit | 8 | ✅ analysed, identities stable |
| **interface = NOT analysed** | 7 | ✅ **seen, recorded, `analysed=false`, `value=null`** — never silently dropped |
| nullsafe = behavioural edge | 7 | ✅ `$this?->target()` → edge, `AccessMode::Nullsafe` carried |
| FQ first-class callable excluded | 7 | ✅ `CallableNotInvocation` |
| **13.3 — all ten qualifier kinds** | 11 | ✅ unqualified-own **INCLUDE** · FQ-own **INCLUDE** · FQ-global **EXCLUDE/NotTheAnalysedUnit** · relative **INCLUDE** · **qualified `Sub\Fq::b()` → `NotTheAnalysedUnit`, NOT `NotDeterminable`** ⭐ the bucket ruling · aliased **excluded BY KIND even though its relation is `DenotesAnalysedUnit`** ⭐ · imported short name → other unit · `parent::` → `OutOfFrame` · `$x::` → `NotDeterminable` |
| one-file analysis scope | 15, 16 | ⚠️ file-local — **but wrong for a file with two `namespace` blocks** → **`V-2`** |
| declaration-path anonymous identity | 8 | ✅ exactly as ratified |

> ⭐ **`NEW-5` is measurably fixed:** `\Fq` and `\App\Fq` are now **different facts with different verdicts** — the conflation the AST reference was measured to have is gone.

---

# 5 · B3 extractor — challenged, including the implementer's own three corrections

| Construct | Result |
|---|---|
| **heredoc `$this->b()`** | ✅ **property read, `()` is literal text** — the implementer's self-correction is **CONFIRMED**, and it is the language's truth |
| **`{$this->c()}` in a heredoc** | ✅ invocation |
| **nowdoc** | ✅ interpolates nothing → **no facts** |
| `'} not a brace {'` | ✅ brace matching unaffected — all seven methods found |
| **method-body brace handling** (the one real self-reported defect) | ✅ **holds** under attributes, nested `if/while/do`, `match`, and five consecutive methods — probe 9 |
| attributes: class · method · **parameter** · stacked · containing `]` and `#[` in strings | ✅ all skipped as attribute groups, never as comments |
| PHP-mode boundaries `?> … <?php` | ✅ a `class Fake {}` written **in inline HTML produced no unit**; `{$this->notCode()}` in HTML produced no fact |
| `__halt_compiler()` | ✅ everything after it ignored |
| `goto` + label | ✅ no effect |
| **BOM** (declared OPEN/untested) | ✅ **works** — the gap is declared conservatively, which is the safe direction |
| **PHP 8.4 property hooks / `private(set)`** (declared OPEN) | ⚠️ **parse-safe, and silent**: method discovery is unaffected, but a hook body's `$this->compute()` yields **no fact**. Failure mode is silence, not corruption — the OPEN classification is accurate |
| `Foo::class` | ✅ constant fetch, not a declaration, not a reference |
| enum with backed type · enum implementing an interface · class declared inside a function · conditional class | ✅ all handled |
| **two `namespace` blocks in one file** | 🔴 **DEFECT — `V-2`** |
| **`use X as Ali;` after any `{$…}` interpolation** | 🔴 **DEFECT — `V-4`** |
| **`$this->$m()` · `call_user_func([$this,'m'])` · `$this->$p`** | 🔴 **UNSEEN — `V-3`** |

**Verdict C — B3 extraction correctness: ⚠️ PASS WITH NOTES** (`V-2` metric-affecting, `V-4` evidence-affecting, `V-3` omission; every hard lexical case in the measured defect surface is handled correctly).

---

# 6 · L4 — run on synthetic facts, then on the whole cross-product

**No PHP input is required** (probe 1). **Qualifier interpretation happens at L4** and the binding judges nothing — verified by reading `classifyQualifier()` (reports two axes, decides nothing) against `EdgeRules::verdict()` (decides, sees no syntax).

**Probe 2 — 120 combinations** of `ReferenceMode × QualifierKind × TargetUnitRelation × Determinability`, compared against the accepted table **§7.2 transcribed by me from the design document**:

```
divergences from the accepted table : 24   ← ALL of them share one cause
table-silent combinations           : 10   ← the implementation decides them anyway
```

* **All 24 divergences are facts carrying `Determinability::NotDeterminable` with a qualifier other than `ComputedTarget`.** The accepted design says **"row 2 is the only path to `NotDeterminable`"** and that **"any `Determinable` reference reaching that row is an internal contradiction the evidence must surface."** The implementation instead makes `determinability === NotDeterminable` an **independent second path** and **silently resolves** every such contradiction. **No contradiction-surfacing exists anywhere in the delivered code.** → **`V-5`**
* **Ten combinations the table does not rule** (e.g. `UnqualifiedName` + `Undetermined` + `Determinable`) are decided by the implementation as `NotTheAnalysedUnit` — an **undetermined relation collapsed into a "not this unit" claim.** Latent (the binding never emits it), and included in `V-5`.

**Edge/property rules consume facts, not syntax** ✅. **Nullsafe facts respected** ✅. **The 19-test boundary suite is real evidence but is not sufficient**: it constructs facts by hand, and every one of the 24+10 combinations above is outside it.

**Graph construction, observed (probe 4):** state edges form a **star** from the first method that touched a property (`a-b`, `a-c`; never `b-c`), and behaviour edges are a **multiset** (`d-e` three times). Metric-equivalent, **but 13.7 makes the edge set normative evidence** and neither convention is stated anywhere. → **`V-6`**

**Verdict D — L4 correctness: ⚠️ PASS WITH NOTES** (`V-5`, `V-6`; every rule reachable from the PHP binding is applied exactly as decided — the notes are latent or evidence-shape).

---

# 7 · L5 — property-tested against an independent reference

| Check | Result |
|---|---|
| **5 000 random graphs** (0–8 nodes, 0–10 edges) vs an **independently written BFS** component count | ✅ **0 mismatches** |
| empty node set | ✅ `0` |
| self-loop | ✅ `1` |
| receives only `(nodes, edges)` — no kind, no qualifier, no source | ✅ by signature and by probe 1 |
| **is the legacy calculator the oracle?** | ✅ **No.** `grep`: the capability never references `Lcom4Collector`, `lcom4_collector.py` or `expected.json` — **not in production code, not in tests** |
| edge naming a non-node | ⚠️ warnings + `TypeError`; unreachable from L4 today → **`V-7`** |

**Verdict E — L5 correctness: ✅ PASS** (with the low note `V-7`).

---

# 8 · Conformance evidence — the deferral is real, and it is honoured

⛔ **No expected evidence was created.** The delivery contains **no `.json`, no golden file, no fixture** — 28 additions, all `.php`/`.md` (§12). `expected.json`, both collectors and all ten fixtures are **byte-identical to their pre-delivery blobs**:

```
4136519b…  Lcom4Collector.php        ═ 4c6c1dac^   ✅
5acb0e13…  lcom4_collector.py        ═ 4c6c1dac^   ✅
173ab4ec…  examples/lcom4/expected.json ═ 4c6c1dac^ ✅
```

**Readiness for the future conformance layer — assessed, as instructed:**
* ✅ **The three 13.7 levels are already observable**: `AnalyseCohesion::observe()` emits unit + kind + eligibility, node set + edge set, every excluded reference **with its reason**, and the metric. **Element-wise comparison is possible today.**
* ⚠️ **Four things would make declared evidence fail or be ambiguous against a correct specification**: `V-1` (a unit's declared kind), `V-3` (references the pinned decision requires to be *recorded as excluded*), `V-2` (a wrong relation in multi-namespace files), `V-6` (edge-set shape conventions an author cannot guess).
* ⭐ **No output of this implementation could become its own oracle**: nothing writes an expectation file, there is no snapshot/regeneration path, and the test expectations are **hand-authored literals** derived from the rules (probe: read of `CohesionPipelineTest`).

**Verdict G — knowledge/conformance integrity: ✅ PASS.**

---

# 9 · Dual-run divergence — reproduced from scratch

**Probe 12 ran all ten fixtures through `expected.json`, the legacy calculator and the new path in one process:**

```
fixtures identical across all three : 9/10
existing units identical            : 10/10
divergence                          : trait-user.php — the new path ALSO reports SomeBehavior = 1
                                      (TraitUserExample = 1 in all three)
```

| Question | Answer |
|---|---|
| Is the divergence real? | ✅ **Yes**, reproduced independently |
| Caused by the decided trait-as-unit semantics? | ✅ **Yes** — 13.5 makes a trait an analysed unit; the fixture declares `trait SomeBehavior` with one method ⇒ one component |
| Is the implementation correct under the accepted contract? | ✅ **Yes.** And it does **not** breach the pinned `trait_methods: NOT resolved` limitation: `TraitUserExample`'s node set is still `ownMethod` alone |
| Is the legacy calculator the divergent implementation? | ✅ **Yes, under the contract** — it predates 13.5 and emits no trait or enum units at all. **`expected.json` is now incomplete relative to the decided contract**, which is exactly the routed `OQ-3`/artifact-update consequence |

⛔ **The legacy result was not treated as authoritative at any point in this verification** (Decision 1).

---

# 10 · Test claims — challenged, and the challenge failed to break them

| Claim | Independent result |
|---|---|
| **55 tests / 105 assertions green** | ✅ **exact** — `OK (55 tests, 105 assertions)` |
| which are new | **all three files are new in `4c6c1dac`**: `CohesionSemanticsTest` **19/34** (the boundary suite) · `PhpFactExtractionTest` **27/53** · `CohesionPipelineTest` **9/18** |
| which are existing | `Lcom4CollectorTest` + `Lcom4VerificationSuiteTest` + `ObservationRuntimeTest` → ✅ **15 tests / 71 assertions green**, exactly as claimed |
| wider-suite failures pre-existing? | Unit suite: **3 504 tests · 483 errors · 73 failures** — and **zero occurrences of `Cohesion`, `EngineeringKnowledge` or `Lcom4` anywhere in the failure output**; no delivered file name appears in any failure. Sampled causes are unrelated app-domain arity/type errors (`EloquentCommitteeAggregateRepository::__construct`, `VoterSlug…`) |
| **is any Track-1 failure hidden by filtering?** | ✅ **No.** The Cohesion tests **also run inside the `Unit` suite** (`--testsuite Unit --filter Cohesion` → 56 tests / 107 assertions, green; the extra one is the pre-existing `ObservationRuntimeTest::test_low_cohesion_change_produces_lcom4_recommendation`) |
| **"no regressions"** | ✅ **Sustained.** The diff is **additions-only** (28 `A`, and the single `M` is the implementer's own session log) — no production file was modified, so no regression vector exists. ⚠️ **Stated limitation: I did not re-run the suite at `4c6c1dac^` to baseline the counts; the claim rests on the additions-only diff and the absence of any Cohesion reference in the failures.** |

> ⚠️ **Green ≠ correct, and the suite proves it: `V-1`, `V-2`, `V-3` and the multi-namespace case are ALL untested.** `grep` over the three test files: no anonymous-class **kind** assertion, no `$this->$m()`, no `call_user_func`, no multi-`namespace` fixture. **The 55 green tests do not touch a single one of the four defects found here.**

**Verdict F — test-claim correctness: ✅ PASS** (every numeric claim is exact and nothing is concealed; the coverage gap is reported under the findings, not against the claim).

---

# 11 · Known gaps — the stated classifications, checked

| Item | Stated | Verified |
|---|---|---|
| declared expected evidence · conformance layer | **NOT IMPLEMENTED** | ✅ correct, and **deliberately deferred by the record** — `G-KOS-CONTRACT-ARTIFACT-UPDATE` is AUTHORIZED and UNEXERCISED. **Not a verification failure.** |
| `O-EKS-1` (`class` key vs enum/trait/anonymous units) | **OPEN, untouched** | ✅ `ObservationRuntime.php` untouched; the new path is **not wired into any runner** (`grep`: `AnalyseCohesion`/`PhpFactExtractor` referenced only by their own definitions and the new tests) |
| broader attribute positions | **OPEN** | ⚠️ **over-declared** — class/method/parameter/stacked/`]`-in-string all verified working |
| BOM / non-UTF-8 | **OPEN** | ⚠️ **over-declared** — BOM verified working |
| `goto` | **OPEN** | ⚠️ **over-declared** — verified working |
| PHP 8.4/8.5 syntax | **OPEN** | ✅ accurate — hooks parse safely but their bodies are invisible; the whole suite runs on **PHP 8.5.8** |
| provenance store | **OPEN** | ✅ accurate — `factId` is never populated |
| second language binding | **FUTURE** | ✅ none introduced |
| **multiple `namespace` blocks in one file** | **NOT LISTED** | 🔴 **an undeclared, metric-affecting gap — `V-2`** |

**None of the OPEN items is required by the current authorized scope**, and this report does not expand scope because a gap exists. **`V-2` is the one item that belongs on that list and is missing from it.**

---

# 12 · Unauthorized changes — none found

```
semantic / contract changes      none      fixtures                     none
expected.json                    none      legacy retirement            none (LEGACY/TRANSITIONAL, untouched)
Python transport                 none      new language support         none
unrelated infrastructure         none      files modified               1 — the implementer's own session log
```
**28 additions confined to `scripts/lib/EngineeringKnowledge/Capabilities/Cohesion/`, `tests/Unit/Cohesion/`, `developer_guide/cohesion/` and the delivery record.** The developer guide required by the Definition of Done **is present** (`00_index.md` + step guide).

**Verdict H — scope discipline: ✅ PASS.**

---

# 13 · Findings

> ⛔ **Each finding states an insufficiency and its evidence. None supplies a replacement design — that is Architecture's, not verification's.**

### 🔴 `V-2` — a file with two `namespace` blocks yields a wrong relation, a wrong edge set and a **wrong LCOM4**
* **Evidence (two arms, identical class body):** `Second\Beta` calling `\Second\Beta::b()` → **single-namespace file: `DenotesAnalysedUnit` → edge → LCOM4 = 2**; **two-namespace file: `DenotesOtherUnit` → excluded `NotTheAnalysedUnit` → no edge → LCOM4 = 3.** Mechanism observed in source: only the **first** `namespace` is recorded (`$this->namespace === ''`) and is then applied file-wide by `relationTo()`/`qualify()`.
* **Violates:** `OQ-2` (the analysis scope is **one PHP source file**, name resolution file-local) · the accepted design's `L3` `TargetUnitRelation` fact · 13.3 rows 7/8 · the binding's own claim to be grammar-exact.
* **Severity: HIGH** (changes the delivered metric) · **Reproducibility: deterministic, control arm attached** · **Blocks acceptance: NO** — no multi-namespace file exists in `app/`, `scripts/` or `tests/`, and conformance is not asserted; **but it is an UNDECLARED gap, so the delivery's gap list is incomplete on this point.**

### 🟠 `V-3` — dynamic own-behaviour references are **UNSEEN**, where the pinned decision requires **SEEN-AND-EXCLUDED**
* **Evidence:** `$this->$m()`, `call_user_func([$this,'m'])` and `$this->$p` produce **no fact and no exclusion** (`excluded=[]`), while `$x::b()` is correctly reported as `NotDeterminable`.
* **Violates:** `expected.json._variant_decisions_pinned.intra_class_calls` (**PO/ARB decision 2026-08-16**, verbatim: *"EXCLUDED AS NOT DETERMINABLE … dynamic method names (`$this->$name()`), callable arrays (`call_user_func([$this,'m'])`) … THESE TWO EXCLUSIONS ARE DIFFERENT CLAIMS AND MUST NOT BE MERGED"*) · the model's **EXCLUDED ≠ UNSEEN** principle · 13.7 Level-1/2 evidence.
* **Severity: MEDIUM-HIGH** (metric-neutral; the contract's *"we cannot see it"* claim is never made) · **Reproducible** · **Blocks acceptance: NO for the delivery; YES for asserting conformance.** ⚠️ **Requires an Architecture determination** — the accepted `L3` model does not state whether the *binding* must emit a fact for a dynamic member, and verification must not decide it.

### 🟠 `V-1` — `UnitKind::AnonymousClass` is declared, ruled on by 13.5, and **never emitted**
* **Evidence:** every anonymous class is emitted as `ClassUnit` (probe 8); `grep` finds `AnonymousClass` only in `UnitEligibility` and one hand-built test — **never in the binding**. The anonymous-class tests assert **identity strings only**, never the kind.
* **Violates:** 13.5 (anonymous decided **separately** as an analysed unit) · `INV-L3-1`/`INV-L3-3` (the kind is a fact of every declaration) · 13.7 Level 1.
* **Severity: MEDIUM** (eligibility and metric unaffected — both kinds are analysed) · **Reproducible** · **Blocks acceptance: NO for the delivery; YES for asserting conformance** — declared evidence for an anonymous unit cannot be satisfied.

### 🟡 `V-4` — any `{$…}` interpolation defeats the `use`-alias map, turning a **LIMITATION** into a **DECISION**
* **Evidence (two arms, identical `use` and call):** with a preceding complex interpolation → `Ali::b()` is `UnqualifiedName` → `NotTheAnalysedUnit`; without it → `AliasedName` → `AliasedSpelling`. Mechanism: `readNamespaceAndUses()` counts only the literal `{` for depth while counting every `}`; `T_CURLY_OPEN`/`T_DOLLAR_OPEN_CURLY_BRACES` permanently skew it (`matchBrace()` counts them — the two functions disagree).
* **Violates:** 13.3's aliased row, whose status is **`LIMITATION`, deliberately kept distinguishable from a semantic `DECISION`** · `L3` `QualifierKind` fidelity.
* **Severity: MEDIUM** (evidence-level) · **Reproducible, control arm attached** · **Blocks acceptance: NO.** ⚠️ **I could not construct a metric-affecting variant**: an alias whose short name equals the analysed unit's name is rejected by PHP itself (*"Cannot redeclare class … previously declared as local import"*), which closes the escalation path I probed for.

### 🟡 `V-5` — L4 opens a second path to `NotDeterminable` and **silently resolves** the contradictions the design says evidence must surface
* **Evidence:** 24 of 120 combinations diverge from accepted table §7.2, all of them `Determinability::NotDeterminable` with a non-`ComputedTarget` qualifier; 10 further combinations are table-silent and are decided anyway (an `Undetermined` relation becomes a `NotTheAnalysedUnit` claim).
* **Violates:** accepted design §7.2 — *"row 2 is the only path to `NotDeterminable`"* and *"any `Determinable` reference reaching that row is an internal contradiction **the evidence must surface**"*. **No surfacing mechanism was delivered.**
* **Severity: LOW-MEDIUM** (unreachable from the PHP binding; reachable from declared evidence and from any future binding — the exact scenario the neutral layer exists for) · **Reproducible** · **Blocks acceptance: NO.**

### 🟡 `V-6` — the edge set's shape is an unstated convention, and 13.7 makes the edge set normative
* **Evidence:** three methods sharing a property produce a **star** (`a-b`, `a-c`, no `b-c`); a method calling another three times produces **three identical edges**.
* **Violates:** nothing decided — and that is the finding: **13.7 requires element-wise comparison of the edge set against authored expectations, and an author cannot derive these conventions from the specification.**
* **Severity: LOW-MEDIUM** · **Reproducible** · **Blocks acceptance: NO** (bears on the conformance layer).

### ⚪ `V-7` — `CohesionGraph` accepts an edge naming a non-node; `Lcom4` then emits warnings and a `TypeError`
* **Evidence:** `new CohesionGraph(['a','b'], [['a','zzz','behaviour']])` → two `Undefined array key` warnings then `TypeError`.
* **Violates:** no stated rule; noted because L5 is the layer a future binding feeds directly.
* **Severity: LOW** · **Reproducible** · **Blocks acceptance: NO** (unreachable from `GraphBuilder`, which filters targets to nodes).

**Observation, not a finding.** `QualifiedName` is excluded as `NotTheAnalysedUnit` **even when the name does denote the analysed unit**. That is the decided rule (13.3's flat row + accepted row 9) and the implementation is **faithful**; recorded only so a later reader does not mistake fidelity for a bug.

---

# 14 · Verdicts — separate, not collapsed

| | Category | Verdict |
|---|---|---|
| **A** | **Architecture conformance** | ✅ **PASS** — Gate 3 re-derived *by execution*; the exception is realized as *implemented in PHP*, not *semantically PHP-dependent* |
| **B** | **L3 semantic fidelity** | ⚠️ **PASS WITH NOTES** — `V-1`, `V-3` |
| **C** | **B3 extraction correctness** | ⚠️ **PASS WITH NOTES** — `V-2`, `V-4`, `V-3` |
| **D** | **L4 correctness** | ⚠️ **PASS WITH NOTES** — `V-5`, `V-6` |
| **E** | **L5 correctness** | ✅ **PASS** — 0/5 000 mismatches; note `V-7` |
| **F** | **Test-claim correctness** | ✅ **PASS** — 55/105 and 15/71 exact; nothing hidden |
| **G** | **Knowledge / conformance integrity** | ✅ **PASS** — no expected evidence created, no self-oracle, artifacts byte-identical |
| **H** | **Scope discipline** | ✅ **PASS** — additions only, nothing retired, nothing wired in |

---

# 15 · Final verdict

> ### **"Can PO/ARB accept Track 1 as delivered within its authorized scope?"**
> **The evidence supports acceptance — and the decision is the PO/ARB's, not this report's.** Every claim the delivery makes was checked and **none was found false**; the delivered scope is implementation with **conformance deliberately not asserted**, and that is exactly what was delivered. **No finding falsifies the architecture, the boundary, the semantics as decided, the test claims or the scope discipline.**
> **Acceptance should carry `V-1` … `V-7` as recorded open defects, and `V-2` should be added to the delivery's gap list, which is incomplete without it.** ⛔ **Acceptance must not be read as conformance**: `V-1`, `V-2`, `V-3` and `V-6` would each defeat declared expected evidence, and the 55 green tests cover none of them.

> ### **"Does any finding require Architecture to make a separate decision before acceptance?"**
> **Yes — one, and only one: `V-3`.** Whether the **binding must emit a fact** for a dynamic own-behaviour reference (`$this->$m()`, `call_user_func([$this,'m'])`) so that L4 can report it as `NotDeterminable` is **not answered by the accepted `L3` model**, while `expected.json`'s pinned decision requires those calls to be *excluded as not determinable* — a claim that cannot be made about a reference that was never emitted. **Verification names the gap and stops; it does not resolve it.**
> **`V-5` carries a second, narrower question** — the accepted design requires fact contradictions to be *surfaced in evidence* and no mechanism was delivered; **what that mechanism is, is Architecture's to decide, not mine.**
> **`V-1`, `V-2`, `V-4`, `V-6`, `V-7` need no new decision** — the decided contract already answers them.

---

⛔ **STOPPING. Nothing was accepted · nothing was closed · no implementation, fixture or expected evidence was modified · no finding was repaired · no replacement architecture was supplied.**
**Next actor: PO/ARB.**

**Traceability:** `G-KOS-CONTRACT-TRACK1-VERIFY` + `AMD1` · assignment `S1-verification-track1-php-adapter` (seq 35/36/37) · delivery `4c6c1dac` · delivery registration `2abbf79f` · `G-KOS-CONTRACT-IMPL-TRACK1` + `AMD1` · accepted architecture `adc5c8e8` §7.2/§7.3/§9 (accepted `c6c4f984`) · reconciliation `70fc25c6` §3/§6/§14 · fit assessment `440fe7b8` · Decisions 1 · 13.1 · 13.3 · 13.5 · 13.7 · `OQ-1` · `OQ-2` · `OQ-3` · `OPEN-1` · `O-EKS-1` · `G-KOS-CONTRACT-ARTIFACT-UPDATE` (UNEXERCISED) · `expected.json` `173ab4ec…` `_variant_decisions_pinned` (PO/ARB 2026-08-16) · `R-34` · `P-2` (`D-a`–`D-d`) · `INV-ATTR-1`/`INV-ATTR-2` · probes 1–20 (throwaway, outside the repository).
