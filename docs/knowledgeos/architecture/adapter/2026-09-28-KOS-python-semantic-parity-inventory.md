# KOS — Python semantic parity inventory and roadmap

**Context:** strategic pivot, human-directed (2026-09-28): pause the `KOS-CONTRACT-NEUTRALITY-001`
`D-1`/`D-4`/`D-5` governance thread (parked, not abandoned — see
`2026-09-28-KOS-CONTRACT-NEUTRALITY-001-D1-schema-sufficiency-pass.md` for its current state)
unless it blocks Python parity, and make the primary research question:

> **Does KnowledgeOS have a sufficiently language-neutral `L3` semantic kernel that
> equivalent PHP and Python constructs converge on the same canonical representation?**

— not "is LCOM4 itself optimal." **Phase:** survey/inventory. **No production code changed.**

---

## 1 · The stopping rule this inventory follows

> Investigate an `L3`/`L4` construct only when it demonstrates a language-neutral semantic
> divergence, a canonical representation gap, or a reproducible graph-construction defect.
> A metric-neutral, already-classified difference is **closed**, not a standing question.

This is not a new rule — it is a restatement of the discipline `R1`–`R6` and `OWD-1`–`OWD-15`
already followed (characterize → classify A–F → authorize → RED → GREEN). This inventory
makes that discipline's *current state* visible as one artifact, rather than scattered across
~40 dated reports.

## 2 · Method

Every row below is grounded in an actual test file's docblock and/or its associated dated
report — re-read this pass, not recalled from memory. Columns:

- **PHP** / **Python**: does a binding exist and, where relevant, what does it do.
- **Canonical `L3`**: same representation, a declared bounded difference, or genuinely unresolved.
- **Python tested?**: which test file(s) establish this, and whether by characterization
  (pins current behaviour), correction (RED→GREEN, production code changed), or experiment
  (adversarial, parity not assumed).
- **Action**: closed (A/B/E/F-classified, no further work implied) · investigate (open
  question, no disposition yet) · **STOP** (an identified risk, explicitly not resolved).

## 3 · The matrix

| Construct | PHP | Python | Canonical `L3` | Python tested? | Action |
|---|---|---|---|---|---|
| Constructor/destructor lifecycle exclusion | ✓ | ✓ | same (`MethodRole::Lifecycle`) | ✓ `MethodRoleLifecycleNormalizationTest` | **Closed** — R1, corrected |
| Own-class-name method calls (`self::`/`static::`/`OwnClass::`/`$this->`) | ✓ | ✓ | same | ✓ `OwnClassNameResolutionCorrectionTest` | **Closed** — R3, corrected (classification D) |
| State access, receiver resolution (property reads/writes) | ✓ | ✓ | same | ✓ `ClassLevelStateAccessCorrectionTest`, `ReceiverResolutionStateAccessExperimentTest` | **Closed** — R5, corrected (classification E, cross-adapter) |
| Static methods (isolated-component inflation) | ✓ | ✓ | same, pinned known limitation | ✓ `StaticMethodsRuleValidationTest` | **Closed** — R6, confirmatory (A) |
| Magic methods (no special handling beyond ctor/dtor) | ✓ | ✓ (`__init__`/`__getattr__` etc. exist, not specially handled either) | same | ✓ `MagicMethodRuntimeSemanticsExperimentTest` | **Closed** — R4, confirmatory (A) |
| Trait methods (not resolved, declared limitation) | ✓ (traits exist) | — (no direct language construct) | PHP-specific; Python has no trait syntax to test against | ✓ `TraitMixinSemanticExperimentTest` | **Closed** — R2, confirmatory (A); genuinely PHP-specific, no Python analogue expected |
| First-class callable reference (`m(...)`) vs. invocation | ✓ excluded (pinned) | ✓ tested, similar surface syntax, deliberately different semantics (`self.method` vs `self.method()`) | same distinction, correctly non-collapsed | ✓ `PythonCallableReferenceExperimentTest` | **Closed** — confirmatory |
| Property getter/setter identity (`@property`) | — (no direct PHP equivalent to test against) | ✓ | same relevant semantics, once corrected | ✓ `PropertyIdentityCharacterizationTest` → `PropertyIdentityCorrectionTest` | **Closed** — OWD-2/OWD-3, corrected (node identity keyed by occurrence) |
| Property target resolution + `property(fget,fset)` call-form idiom | — | ✓ | same, once corrected | ✓ `PropertyTargetResolutionCharacterizationTest`, `PropertyCallFormCharacterizationTest` → `PropertyTargetPrecisionCorrectionTest` | **Closed** — combined OWD-4/OWD-7, corrected |
| Nested closures / lambdas — scope pruning | ✓ (`function(){}`) | ✓ (`lambda`, nested `def`) | same, once corrected | ✓ `NestedClosureAttributionCharacterizationTest` → `NestedClosureAttributionCorrectionTest` | **Closed** — OWD-5, corrected (cross-adapter) |
| Arrow functions (`fn() => expr`) | ✓ | n/a (Python has no equivalent syntax; `lambda` already covered above) | PHP-specific attribution bug, no cross-language question | ✓ `ArrowFunctionAttributionCharacterizationTest` → `ArrowFunctionAttributionCorrectionTest` | **Closed** — OWD-10, corrected (PHP-only) |
| Async methods (`async def`) | n/a (no native async method concept in this contract's PHP scope) | ✓ | same, once corrected | ✓ `AsyncMethodInvisibilityCharacterizationTest` → `AsyncMethodVisibilityCorrectionTest` | **Closed** — OWD-13, corrected (Python-only gap; the most foundational finding of the whole OWD phase — a wholesale category exclusion) |
| Explicitly-named self-call premature `NotDeterminable` conversion | — | ✓ | same, once corrected | ✓ `PythonEvidencePrecisionCorrectionTest` | **Closed** — narrow, corrected |
| `DeclaredUnit::declaredName` consumption | ✓ (field exists both languages) | ✓ | confirmed non-consumed, by design | ✓ `DeclaredNameCharacterizationTest` | **Closed** — OWD-11, confirmatory |
| `factId` field consumption | ✓ | ✓ | confirmed non-consumed today | ✓ `UnconsumedFieldCharacterizationTest` | **Closed** — confirmatory, re-verified twice this session (V-3 passes) |
| Real corpus validation (PHP `app/`, 1,629 files; CPython stdlib, 175 files) | ✓ | ✓ | 0 crashes, construct census, spot-checks | ✓ `RealCorpusPhpConstructRegressionTest`, `OpenWorldCorpusDiscoveryCharacterizationTest` + `OWD-6/9/12/14/15` reports | **Closed** — confirmatory, both languages |
| **Dynamic attribute interception** (`__getattr__`/`__getattribute__`/PHP `__get`) | ✓ | ✓ | **declared, observable `LCOM4` difference between naive and "fully resolved" representation** | ✓ `DynamicAttributeInterceptionExperimentTest` | **Closed, but load-bearing** — classified **(B) intentional abstraction, not a canonical gap** (per the test's own docblock, referencing an accompanying A–F classification report). Named here because it is the one construct in this table that genuinely *does* reach the metric, and the disposition is a governed classification, not an oversight. |
| **Descriptor-backed attribute access** (`__get__`) | — (no direct PHP equivalent) | ✓ | genuinely no PHP analogue; representability question resolved for what exists | ✓ `PythonDescriptorExperimentTest` | **Closed** — the test's own docblock states no PHP analogue was attempted, "consistent with the diamond/MRO experiment's own discipline" — Python-only, disposed |
| Class/static dispatch (`staticmethod`/`classmethod`/instance, no inheritance) | ✓ (`static::`/`self::`) | ✓ | characterized, H1–H5 | ✓ `PythonClassStaticDispatchExperimentTest` | **Presumed closed** — this pass did not re-verify the H1–H5 verdicts line-by-line; flagged so a future pass does not skip re-checking if this row is ever relied on for a new decision |
| Single-inheritance parent dispatch (`super()`/`parent::`) | ✓ | ✓ | characterized | ✓ `PythonParentDispatchExperimentTest` | **Presumed closed**, same caveat as above |
| **Inherited-only method visibility, cross-file** | ✓ excluded (pinned: *"class analysed in isolation"*) | ✓ tested — RED-A pins current (single-file-blind) behaviour; **RED-B demonstrates a capability (multi-class visibility) the Python adapter currently lacks** | **Both languages structurally share the same single-file-isolation limitation** — this is very likely the *same* declared limitation as the `inherited_methods` pinned decision, not a divergence, but **this pass did not verify that an explicit classification letter was ever recorded for RED-B specifically** | ✓ `InheritanceCrossLanguageExperimentTest` | **Investigate — narrow.** Not re-opened as new work; flagged because RED-B's own docblock describes a capability *gap*, and this inventory could not confirm (without re-reading the full associated report, out of this pass's scope) whether that gap was ever given a governed classification or simply left as characterized. |
| Inheritance with override (Case B control) | ✓ | ✓ | **converges** — override resolution matches | ✓ `InheritanceOverrideControlExperimentTest` | **Closed** — H1 confirmed by execution |
| **Diamond inheritance / MRO** (`class D(B, C)`) | n/a — **PHP cannot express multiple inheritance at all** (`class D extends B, C` is a PHP parse error, verified directly) | ✓ | **genuine language-semantic difference (classification F)** for the construct's existence; **a real, unresolved `L3`/`L4` field-interaction risk in the *representation* of what the adapter already does emit** | ✓ `PythonDiamondMroExperimentTest` | **🛑 STOP — explicitly not fixed.** The adapter's current `super()` handling reports `ParentKeyword`/`Determinable` for a diamond case exactly as it does for single inheritance, without performing real C3 linearization — the `Determinable` label is not earned. The test's own docblock explains *why* the obvious fix (flip to `NotDeterminable`) is unsafe: `EdgeRules` checks `determinability` **before** `qualifierKind`, so the exclusion reason would silently flip from `OutOfFrame` to the less-apt `NotDeterminable`, a genuine L3/L4 field-interaction, not a simple bug. **This is the single most consequential open item this inventory surfaces** — it is exactly the kind of "reproducible graph-construction defect risk" the stopping rule (§1) says merits investigation, and it has already been found, not merely hypothesized. |
| **Computed method name** (`D-1`: `$this->$m()`) | ✓ characterized in depth this session (schema sufficiency pass) — currently **unextracted/invisible** by the binding | **❓ NOT YET CHARACTERIZED** — does Python's adapter have an equivalent gap for `getattr(self, name)()`-style dynamic dispatch? No test file addresses this. | **Unknown** — this is a real gap in cross-language coverage, not merely a PHP governance question | ✗ **none found** | **Investigate.** This is the clearest concrete next step this inventory identifies: the PHP side of `D-1` has three weeks of governance analysis behind it (culminating in today's schema sufficiency pass); the Python side has never been asked the equivalent question at all. |
| **Library-dispatch callable** (`D-4`: `call_user_func([$this,'m'])`) | ✓ characterized (enumerated scope adopted, not incorporated) | **❓ NOT YET CHARACTERIZED** — Python's closest analogue would be something like `getattr(self, name)()` or `operator.methodcaller`, neither examined | **Unknown** | ✗ **none found** | **Investigate**, same reasoning as `D-1` above — likely a *smaller* gap than `D-1` (Python's `call_user_func` analogues are less idiomatic than PHP's, per general Python style, but that is an impression, not a measured claim this pass makes) |

## 4 · What this inventory establishes, at the level the human asked for

- **The language-neutral kernel claim is strongly, not just anecdotally, supported.** Of the
  ~26 distinct constructs surveyed, **19 are closed** — same canonical representation (or a
  declared, classified, non-arbitrary difference) on both sides, most reached by adversarial
  testing that explicitly did not assume parity going in (`InheritanceOverrideControlExperimentTest`'s
  own words: *"parity is NOT assumed"*). This is real convergence evidence, earned by testing,
  not asserted.
- **Exactly one construct has an identified, unresolved representation risk that reaches the
  graph layer**: diamond/MRO inheritance. It is Python-only (no PHP analogue exists to
  diverge from), genuinely unresolved, and already flagged **STOP** by the team that found it
  — this inventory does not re-open it as new work, it surfaces it as the top standing item.
- **Exactly one construct reaches the metric and is deliberately, governedly accepted as a
  bounded difference rather than a gap**: dynamic attribute interception (classification B).
  This is the inventory's example of the discipline working as intended — a real divergence
  was found, classified, and closed, not silently absorbed or endlessly re-litigated.
- **Two constructs (`D-1`, `D-4`) have deep PHP-side governance analysis and zero Python-side
  characterization.** This is the most actionable, concrete gap this survey found — not a new
  problem, but an asymmetry in *where effort has gone* so far.

## 5 · Recommended roadmap (ranked, not executed by this pass)

1. **Characterize the Python analogue of `D-1`/`D-4`** (computed method name /
   library-dispatch callable) — a bounded, evidence-first characterization pass, same
   discipline as every `R`/`OWD` item above. This directly answers the strategic question
   (§0) for the two constructs where PHP has moved and Python hasn't, and it may retroactively
   inform the still-open `D-1` schema question (a Python characterization could reveal whether
   a language-neutral schema is achievable, which the PHP-only analysis so far cannot show).
2. **Re-open the diamond/MRO `STOP`, narrowly** — not to fix it, but to determine whether the
   identified `EdgeRules` ordering risk (`determinability` checked before `qualifierKind`)
   has any bearing on constructs already closed above, before assuming it is fully isolated.
   This is a **verification** question, not new research.
3. **Confirm RED-B's classification** (inherited-only cross-file visibility) — a small
   documentation-completeness check: does a governed classification (A–F) already exist for
   this finding, or was it left characterized-only? Five minutes of reading, not a new
   experiment.
4. **`D-1`/`D-4`/`D-5` PHP governance thread — parked, per the human's direction**, resumed
   only if item 1 above surfaces something that changes the PHP-side representation question,
   or if a future session explicitly re-prioritizes it.

## 6 · What this pass did not do

No production code changed. No test written or modified. No `expected.json` change. No
re-verification, by execution, of every cited test's current pass/fail status (all citations
are from docblocks and prior dated reports, consistent with this being a survey/inventory
pass, not a fresh characterization pass) — flagged explicitly rather than silently assumed,
per this thread's own established discipline.

**STOP after this report.** Next actor: the human, on which roadmap item (§5) to authorize
first — most concretely, whether to commission the Python-side `D-1`/`D-4` characterization
this inventory identifies as the clearest gap.

**Traceability:** all `R1`–`R6` and `OWD-1`–`OWD-15` dated reports under this directory ·
`2026-09-28-KOS-CONTRACT-NEUTRALITY-001-D1-schema-sufficiency-pass.md` (the PHP-side `D-1`
depth this inventory contrasts against Python's absence) · every test file named in §3, docblocks
re-read this pass · `2026-09-28-KOS-PYTHON-FIRST-POLICY.md`.
