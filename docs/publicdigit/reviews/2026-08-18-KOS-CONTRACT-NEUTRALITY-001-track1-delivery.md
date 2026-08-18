# `KOS-CONTRACT-NEUTRALITY-001` Track 1 — **delivery**

**Assignment:** `S3-implementation-track1-php-adapter` · **Grants:** `G-KOS-CONTRACT-IMPL-TRACK1` **+ AMD1** (PHP L4/L5 bounded exception)
**Date:** 2026-08-18 · **Producing process, self-declared, NOT attestable** (`INV-ATTR-2`): `claude-code-session:1c8b041b`
**`R-34`/`P-2`: this process does not verify or accept its own work. Independent verification is a separate actor and assignment.**

## 1 · Delivered — **IMPLEMENTED**

| Component | Location |
|---|---|
| **B3 PHP language binding** | `Cohesion/Infrastructure/Php/PhpFactExtractor.php` |
| **L3 fact model** (7 closed vocabularies + 6 value objects) | `Cohesion/Domain/` |
| **L4 cohesion semantics** — eligibility, the 13.3 rule table, graph construction | `Cohesion/Domain/{UnitEligibility,EdgeRules,EdgeVerdict,GraphBuilder,CohesionGraph}.php` |
| **L5 LCOM4** | `Cohesion/Domain/{Lcom4,Interpretation}.php` |
| **Application service** emitting the three evidence levels | `Cohesion/Application/AnalyseCohesion.php` |
| **Tests** | `tests/Unit/Cohesion/` — **55 tests, 105 assertions, all green** |
| **Developer guide** | `developer_guide/cohesion/` |

**22 production classes. No existing file was modified.**

## 2 · Decided semantics — **IMPLEMENTED**

**13.5:** anonymous class · enum · trait = analysed units · **interface = seen, recorded, NOT analysed** · nullsafe `?->` = behavioural edge · FQ first-class callable excluded.
**13.3:** all ten qualifier kinds preserved and interpreted per the adopted table, including the bucket ruling — `NotTheAnalysedUnit` and `NotDeterminable` are **distinct enum values**.
**`OQ-1`:** deterministic declaration-path identity (`F/anon#1/anon#1`); the runtime label `(anonymous)` is **rejected by the constructor**.
**`OQ-2`:** analysis scope is one PHP source file; all name resolution is file-local.

## 3 · Conformance status — ⚠️ **NOT ASSERTED, and deliberately so**

> **Declared expected evidence does not exist yet, and creating it requires `G-KOS-CONTRACT-ARTIFACT-UPDATE` (AUTHORIZED, UNEXERCISED).**
> ⛔ **No expected evidence was authored, generated or adopted.** The standing conformance guard — *"do not permit the implementation to generate its own expected evidence and then use that output as the specification oracle"* — is honoured by producing none.

**What was run instead: a DUAL-RUN DIAGNOSTIC (M-3), which is not a conformance result.**

| | Result |
|---|---|
| Ten fixtures, existing units | **10 / 10 identical** across `expected.json`, the legacy calculator and the new path |
| Divergence | **1 — `trait-user.php` gains a unit `SomeBehavior` = 1** |

> ⭐ **The single divergence is the one the architecture predicted:** 13.5 makes a trait an analysed unit, so `trait-user.php` **gains a second observation**. **Predicted in the accepted design's `OQ-3`, measured here.** ✅ **Divergence by design, not regression.** ⛔ **`expected.json` was NOT modified** — sha256 `173ab4ec…` unchanged.

## 4 · Delivery gates — verified mechanically, not asserted

```
Domain/ references Infrastructure, token_get_all, T_*, PhpParser?   → none        ✅
Application/ references the binding?                                → none        ✅
token_get_all confined to Infrastructure/Php/PhpFactExtractor.php   → yes          ✅
expected.json / fixtures / both collectors modified?                → none        ✅
Python dependency introduced?                                       → none        ✅
Expected evidence generated from implementation output?             → none created ✅
Unauthorized semantic decision introduced?                          → none        ✅
```

**`CohesionSemanticsTest` (19 tests) constructs L3 facts by hand and runs L4/L5 with no PHP source, tokens, AST or provenance in the process.** ⭐ **That is the pre-delivery gate restated as an executable test: if a cohesion rule ever needed a spelling, the file could not be written.**

**No regressions:** existing `Lcom4CollectorTest`, `Lcom4VerificationSuiteTest`, `ObservationRuntimeTest` — **15 tests, 71 assertions, green.** The wider Unit suite's pre-existing failures (e.g. `VoterSlugService::__construct` arity) are unrelated; **zero reference the Cohesion namespace.**

## 5 · Two corrections the language's own lexer forced — recorded, not hidden

**① A heredoc's `$this->b()` is a PROPERTY READ, not an invocation.** Measured: PHP interpolates `$this->b`, and the trailing `()` is `T_ENCAPSED_AND_WHITESPACE` — literal text, not parentheses. **My first test expected "no facts" and was wrong.** *This is precisely why the AST reference reported 2 on `K1` while the regex scanner fabricated an edge and reported 1.* A **nowdoc** interpolates nothing and correctly yields no facts.

**② An alias only denotes the analysed unit when it resolves to it.** `use App\Fq as Ali;` with a **global** `class Fq` denotes **another** unit. **My first test expected `DenotesAnalysedUnit` and was wrong.** Both cases are now covered.

**And one real implementation defect, found by the attribute test:** skipping a method body must not alter the brace depth, because **both** of its braces are skipped. The first version decremented, so every method after the first was lost.

## 6 · Legacy-path migration note

**`Lcom4Collector.php` remains `LEGACY / TRANSITIONAL`, untouched, and is NOT the specification** (Decision 1). ⛔ **Not retired; retirement is a separate governed act after independent verification.** The two paths **will disagree by design** during the compatibility period — the new path implements decided rules the legacy one predates (it misses enums and traits entirely, misses nullsafe, and collides anonymous identities). **§3's single divergence is the first instance.**

## 7 · Known gaps and remaining work

| | Item | Class |
|---|---|---|
| **G-1** | **Declared expected evidence** — the conformance authority | **NOT IMPLEMENTED** — requires `G-KOS-CONTRACT-ARTIFACT-UPDATE` |
| **G-2** | Conformance test layer E asserting L3 facts / L4 graph / L5 metric against declarations | **NOT IMPLEMENTED** — blocked on G-1 |
| **G-3** | Integration with the observation runner and `ObservationRuntime` ACL (`O-EKS-1`: the emitted key is `class`, but a unit may be an enum/trait/anonymous) | **OPEN** — artifact-update territory; **deliberately untouched** |
| **G-4** | Attributes in every grammatical position; BOM / non-UTF-8; `goto`; PHP 8.4 property hooks; 8.5 syntax | **OPEN** — untested, and saying so is required |
| **G-5** | Provenance record (`factId` is carried on `BehaviourReference`; no provenance store is built) | **OPEN** — the boundary is right, the store is not built |
| **G-6** | `OPEN-1` — the L3 vocabulary is PHP-derived in its first version | **OPEN** (`AMD4`), carried |
| **G-7** | A future non-PHP binding producing the same L3 facts | **FUTURE** |

## 8 · Stop

**STOPPING.** ⛔ **No independent verification · no acceptance · no legacy retirement · no work item closed · no expected evidence created · no artifact modified.**
**Next actor: Independent Verification (a separate actor and assignment). Then PO/ARB acceptance.**

**Traceability:** `G-KOS-CONTRACT-IMPL-TRACK1` + `AMD1` · accepted implementation architecture `adc5c8e8` · Track-1 reconciliation `70fc25c6` · fit assessment `440fe7b8` · Decisions 13.1/13.3/13.5/13.7 · Decision 1 · `OQ-1`/`OQ-2`/`OPEN-1` · `G-KOS-CONTRACT-ARTIFACT-UPDATE` (unexercised) · `R-34`/`P-2` · `G-1`.
