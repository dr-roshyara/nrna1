# KOS — Python semantic implementation backlog (discovery pass)

**Context:** follows `2026-09-28-KOS-python-semantic-parity-inventory.md`, human-directed
correction: search the existing corpus for already-discovered-but-unimplemented concepts
before proposing new characterization work. **Date:** 2026-09-28. **Phase:** discovery +
implementation design. **No production code, test, or `expected.json` change.**

> ⛔ **Read-only research.** `git status --short scripts/ tests/ .claude/runtime` shows no
> change before or after this pass.

---

## 0 · A correction, disclosed before anything else

Yesterday's parity inventory (`2026-09-28-KOS-python-semantic-parity-inventory.md`) made two
material errors, found only by this pass's wider search — disclosed here rather than
silently fixed:

1. **It claimed `D-1`'s Python analogue was "❓ NOT YET CHARACTERIZED."** This is false. A
   full branch of same-day 2026-09-27 research (`2026-09-27-KOS-D1-analytical-definition-
   decision.md`, `2026-09-27-KOS-L3-semantic-neutrality-matrix.md` Case E) directly
   constructed and tested Python's `getattr(self, name)()` analogue and concluded: *"the
   identical choice already faced for PHP's `$this->$m()`... the already-proposed fix needs
   no Python-specific variant."* `D-1` is **canonical-model-general**, confirmed, not an
   asymmetry between the languages — it is a gap that exists identically in both.
2. **It classified diamond/MRO as "🛑 STOP — explicitly not fixed,"** based only on
   `PythonDiamondMroExperimentTest.php`'s own docblock. That docblock is accurate as far as
   it goes but was **superseded, same day, by later reports** (`2026-09-27-KOS-minimal-
   semantic-contract.md`, `2026-09-27-KOS-L3-L4-L5-dependency-matrix.md`) that resolved the
   exact concern it raised — see §3 below. The test file itself was never updated to note
   this, which is why reading it in isolation (as the parity inventory did) produced a stale
   conclusion.

**Root cause of both errors, stated plainly:** the parity inventory searched `tests/Unit/
Cohesion/*.php` docblocks as its primary evidence source and did not read the full chain of
dated reports in `docs/knowledgeos/architecture/adapter/`, several of which post-date and
supersede what an individual test's docblock says in isolation. This pass corrects that by
reading the reports directly, in sequence, as instructed.

## 1 · The single most important fact this pass found

**The entire Python-adapter architecture research branch was explicitly closed by human
instruction on 2026-09-27** (`2026-09-27-KOS-adapter-research-FROZEN.md`). This is not a
research pause this pass discovered as a side effect — it is the governing fact for
everything below. The FROZEN document is itself a completed, self-consistent backlog ledger:
twelve dimensions, each with a classification (A–F, this branch's own scheme) and a status of
`Closed`. **Most of what the human's instruction asked this pass to search for already
exists, already searched, already classified, in that one document.** This pass's job is
therefore mostly *verification and reconciliation* of that existing ledger against the wider
corpus, not fresh discovery — and where it found fresh discovery (§4), that is flagged as
genuinely new, not conflated with what was already known.

## 2 · Semantic backlog matrix

Classification scheme exactly as specified (A–H). Evidence column cites the actual report;
**"Origin"** distinguishes R/OWD-series work (general adapter correctness, no governance
attached) from the `KOS-CONTRACT-NEUTRALITY-001`/V-3 thread (governed, PO/ARB-facing).

| Concept / Rule | Evidence / Origin | PHP | Python | L3 | L4 | Current state | Missing work | Class | Priority |
|---|---|---|---|---|---|---|---|---|---|
| Constructor/destructor lifecycle | R1, corrected | ✓ | ✓ | same | n/a | Implemented, validated | none | **A** | — |
| Own-class-name calls | R3, corrected | ✓ | ✓ | same | n/a | Implemented, validated | none | **A** | — |
| State access / receiver resolution | R5, corrected | ✓ | ✓ | same | n/a | Implemented, validated | none | **A** | — |
| Static methods | R6, confirmatory | ✓ | ✓ | same | n/a | Validated | none | **A** | — |
| Magic methods (no special handling) | R4, confirmatory | ✓ | ✓ | same | n/a | Validated | none | **A** | — |
| Trait methods | R2, confirmatory | ✓ | — | PHP-specific | n/a | Validated as PHP-only | none | **D** | — |
| First-class callable vs. invocation | frozen ledger, confirmatory | ✓ | ✓ | same | n/a | Validated | none | **A** | — |
| `@property` getter/setter | OWD-2/3, corrected | — | ✓ | same, corrected | n/a | Implemented, validated | none | **A** | — |
| Property target resolution + call-form | OWD-4/7, corrected | — | ✓ | same, corrected | n/a | Implemented, validated | none | **A** | — |
| Nested closures/lambdas | OWD-5, corrected | ✓ | ✓ | same, corrected | n/a | Implemented, validated | none | **A** | — |
| Arrow functions (`fn()=>`) | OWD-10, corrected | ✓ | n/a | PHP-only bug | n/a | Implemented, validated | none | **D** | — |
| Async methods | OWD-13, corrected | n/a | ✓ | Python-only gap, fixed | n/a | Implemented, validated | none | **D** | — |
| Inheritance evidence precision (non-override) | frozen ledger, corrected | ✓ (reference) | ✓ | same, corrected | n/a | Implemented (`G-KOS-CONTRACT-PYTHON-EVIDENCE-PRECISION-CORRECTION`) | none | **F** (adapter-boundary fix) | — |
| Inheritance with override | frozen ledger, confirmatory | ✓ | ✓ | same | n/a | Validated | none | **A** | — |
| Class/static dispatch (no inheritance) | frozen ledger, confirmatory | ✓ | ✓ | same | n/a | Validated (H1–H5) | none | **A** | — |
| Single-inheritance `super()`/`parent::` | frozen ledger, confirmatory | ✓ | ✓ | same | n/a | Validated | none | **A** | — |
| **Diamond/multiple-inheritance `super()`** | frozen ledger + `minimal-semantic-contract.md` | n/a (PHP parse error) | ✓ | **Gate A: full C3 MRO has no consumer anywhere in the pipeline — confirmed systematically, not case-by-case** | no change needed | Closed | none — verified this pass, see §3 | **F** | — |
| Descriptors (`__get__`) | frozen ledger, confirmatory | — | ✓ | no PHP analogue, disposed | n/a | Closed | none | **D** | — |
| Dynamic attribute interception (`__getattr__`/PHP `__get`) | frozen ledger, `D1-analytical-definition-decision.md` | ✓ | ✓ | **reaches the metric**, excluded by the explicit `magic_methods` pinned decision | n/a | Closed, governed | none | **B** | — |
| `hasBody` field | `minimal-semantic-contract.md`, `UnconsumedFieldCharacterizationTest` | ✓ | ✓ | exists, unconsumed | confirmed dead by execution | Total-by-invariant, unused by design | none — this is a documented, tested non-gap | **F** | — |
| `accessMode` field | same | ✓ | ✓ | exists, unconsumed | confirmed dead | same | none | **F** | — |
| `factId` field | same, re-verified twice this session (V-3 passes) | ✓ | ✓ | exists, unconsumed | confirmed dead | same | conditional relevance to a separate, still-open exclusion-evidence-record question (unrelated to Python parity) | **F** | — |
| `DeclaredUnit::declaredName` | OWD-11, confirmatory | ✓ | ✓ | exists, unconsumed | confirmed dead | same | none | **F** | — |
| Reflection-based dynamic invocation | `inheritance-and-reflection-classification.md` | — | — | zero real-corpus occurrences of actual dynamic invocation (all 22 `ReflectionClass` sites are property introspection) | n/a | Closed, decisively negative | none | **H → closed, not a live category** | — |
| Real-corpus validation, both languages | OWD-1/6/9/12/14/15 | ✓ | ✓ | 0 crashes, spot-checked | n/a | Validated at scale (1,629 PHP files, 175 Python files) | none | **A** | — |
| **`D-1` — computed method name** (`$this->$m()` / `getattr(self,name)()`) | `V-3` chain + `D1-analytical-definition-decision.md` + `L3-semantic-neutrality-matrix.md` Case E | ✓ characterized in depth (PHP governance, 3 weeks) | ✓ characterized (frozen branch, Case E) — **same gap, confirmed language-general** | **genuine canonical expressiveness gap — the one real, unresolved item in the whole capability** | new `EdgeRules` branch needed, trivial once the L3 fact kind exists | Vocabulary adopted (`IndeterminateBehaviourReference`), **not incorporated, not implemented, in either language** | contract incorporation (PO/ARB) → implementation → tests, for **both** adapters simultaneously (the fix is language-invariant by construction) | **E** (shared canonical gap, not adapter-specific) | **P1** |
| **`D-4` — library-dispatch callable** (`call_user_func([$this,'m'])`) | PHP: `V-3` chain. Python: **inferred by generalization from `D-1`'s result, not independently constructed and tested** | ✓ characterized (enumerated scope adopted) | **⚠ not independently tested** — treated as "the same gap, same layer, same fix" by inference (`L3-language-neutrality-experiment.md` §11), never given its own Python fixture the way `D-1` (Case E) was | presumed same as `D-1`'s, unverified independently | same as `D-1` | Vocabulary adopted, not incorporated, not implemented; **Python-side inference not independently verified** | a small, bounded confirmatory experiment: construct Python's closest analogue (e.g. a bound-method reference via `getattr` passed to a higher-order call) and check it hits the identical `IndeterminateBehaviourReference`/exclude path — **or explicitly accept the inference as sufficient, given zero real occurrences of `call_user_func` in the PHP corpus and no established Python idiom that's obviously equivalent enough to be worth constructing** | **H** (research question, narrow, low-stakes) | **P2** |
| `D-5` — dispatch vocabulary (`ExplicitCallableDispatch`) | Same as `D-4` | ✓ adopted | Same inference-only status as `D-4` | n/a — pure vocabulary | n/a | Adopted, not incorporated | Depends entirely on `D-4`'s disposition above | **H**, same caveat | **P2** (bundled with `D-4`) |
| `QualifierKind::SelfKeyword` naming | `L3-language-neutrality-experiment.md` §7 | ✓ correct behavior | ✓ correct behavior, **misleading label** — Python's own `self` keyword means PHP's `$this`, not `self::` | same underlying semantics, cosmetic mismatch only | n/a | Confirmed correct *behavior*; only the *English name* invites a false-cognate misreading by a future Python-adapter author | rename or document — a documentation/naming hygiene item, zero behavioral risk today (the *behavior* was independently confirmed correct via `StaticKeyword`, Case G) | **F** (vocabulary, non-blocking) | **P3** |
| `UnitKind` Python-specific subclass mapping (`Enum`/`Protocol`/ABC) | `L3-semantic-neutrality-matrix.md` §D | — | not yet needed | possible future generalization | n/a | No second adapter case has ever needed this distinction | none currently — explicitly *not justified by evidence yet* | **H**, deliberately not pursued | **P3** |
| `FactSet`'s one-file-scope docblock wording | Same | ✓ | ✓ | substance already language-general | n/a | Cosmetic docblock wording says "PHP source file" | reword | **F** | **P3** |

## 3 · Diamond/MRO — verified, not re-opened

Per the human's instruction §4 (Diamond/MRO section) — investigated, not fixed, per the
already-completed chain:

1. **What is currently represented in `L3`?** `ParentKeyword`/`Determinable` — identical to
   the single-inheritance case, confirmed by direct code reading (`EdgeRules.php`,
   `PythonDiamondMroExperimentTest.php`).
2. **Why is `Determinable` emitted?** The adapter's `super()` recognition never inspects
   `class_node.bases`, so it cannot distinguish single from multiple inheritance — this part
   of the original STOP's finding stands, unchanged.
3. **Does this make a false semantic claim?** Narrowly, yes: `Determinable` implies "this
   analysis knows the target," which is not literally true without real C3 resolution.
4. **Does `L4` consume that field in a way that creates an incorrect graph?** **No — this is
   what resolves the STOP.** `L4`'s only use of a `ParentKeyword` reference is to exclude it
   (`OutOfFrame`) — never to identify *which* ancestor. `targetMethodName`'s only consumer
   anywhere in the pipeline is same-unit node matching, confirmed systematically by
   `minimal-semantic-contract.md`'s full consumption audit, not by a one-off argument about
   this case specifically.
5. **Is exact C3 MRO necessary for the current analytical contract?** **No — confirmed by
   evidence, not assumed:** nothing downstream has a consumer for ancestor identity at all.
6. **Can this be solved with a smaller bounded representation?** The question dissolves — no
   representation change is needed, because nothing reads the information a bigger
   representation would add.
7. **Where does this belong — L3, adapter, or L4?** Nowhere further; the existing `L3`
   representation, though not "earning" its `Determinable` label in the strictest
   epistemically-honest sense, produces no incorrect downstream consequence anywhere in this
   pipeline. **This is a closed item, correctly closed, and this pass's own prior inventory
   was wrong to reopen it as a standing risk.**

## 4 · What this pass found beyond `D-1`/`D-4`/`D-5`/MRO (the §5-required wider search)

Searched systematically per the requested category list (dynamic dispatch, decorators,
descriptors, generators, comprehensions, closures, metaclasses, operator overloading, context
managers, iterators, protocols, etc.) against the actual corpus of dated reports and test
docblocks — **not** against general Python language knowledge first (the evidence-hierarchy
rule, §6 of the instruction).

**Found in the record, already classified, nothing new to add:** descriptors (closed, D),
dynamic attribute interception (closed, B), reflection (closed, effectively not a category).

**Found in the record but explicitly deferred, not a live gap:** `UnitKind`'s Python-specific
subclass mapping (`Enum`/`Protocol`/ABC — §D of `L3-semantic-neutrality-matrix.md`) — the
report's own words: *"possible future generalization, **not justified yet** — no second
adapter exists to be blocked by it."* One adapter now exists (Python), and it has never once
needed this distinction in any test — the deferral remains correctly justified today.

**Not found anywhere in the record, and not independently pursued by this pass either, per
the evidence-hierarchy rule:** generators, comprehensions (this project's own `_walk_own_
scope` deliberately does **not** treat comprehensions as scope boundaries, by design — cited
in the session's own R1-era work, not a gap), metaclasses, operator overloading (`__add__`
etc.), context managers (`__enter__`/`__exit__`), iterators (`__iter__`/`__next__`), dynamic
class construction (`type(...)`). **None of these has ever been raised as a candidate by any
prior report, any test, or any real-corpus finding.** Per the instruction's own §5 discipline
("distinguish known semantic backlog from interesting Python features nobody established as
relevant"), **this pass does not manufacture new investigation targets for them.** If real
Python corpus evidence (analogous to the `app/` corpus grep this investigation has repeatedly
used for PHP) ever shows one of these constructs interacting with `L3`'s consumed fields
(§`minimal-semantic-contract.md`'s table — the sharper question that report itself proposes
going forward), that would be the trigger to characterize it. None has been measured yet.

## 5 · Architectural location, for the two items with any remaining work (`D-1`, `D-4`/`D-5`)

- **Adapter responsibility:** for `D-1`, confirmed insufficient at the adapter alone — the
  gap is that no legal `L3` fact can be constructed at all, not a recognition failure the
  adapter could fix by trying harder (`PhpFactExtractor` and `extract_facts.py` both
  correctly *see* the construct; neither can *represent* it under the current type).
- **`L3` responsibility:** yes — `D-1` requires the new fact kind
  (`IndeterminateBehaviourReference`), already adopted as architecture-of-record but not
  incorporated or implemented (see `2026-09-28-KOS-CONTRACT-NEUTRALITY-001-D1-schema-
  sufficiency-pass.md` for the full, independently re-derived schema analysis — minimal
  schema carries no fields beyond the type's own identity).
- **`L4` responsibility:** a new, trivial, unconditional `EdgeRules` branch — confirmed
  metric-neutral, confirmed language-invariant (no `qualifierKind`, no source-language
  distinction needed in the rule itself).
- **`L5` responsibility:** none — confirmed metric-neutral in every report that has touched
  this question, PHP and Python alike.

## 6 · Implementation proposal — offered for `D-1` only, since it is the sole item with
sufficient, convergent, cross-language evidence to propose against (`D-4`/`D-5` remain
`P2`-gated on the narrow confirmatory experiment in §2)

1. **Semantic rule:** an observed method invocation whose receiver denotes the analysed unit
   but whose method-name token is not a literal — must be recorded as seen, with no legal
   name, excluded as `NotDeterminable`.
2. **Canonical representation:** new `L3` concept required — `IndeterminateBehaviourReference`,
   minimal schema per the schema-sufficiency pass (no stored fields beyond type identity;
   determinability derivable from the type itself).
3. **Adapter implementation:** PHP — the existing, already-identified skip site
   (`PhpFactExtractor.php:519-544`, the `T_STRING` guard) becomes the emission site instead of
   a silent skip. Python — `extract_facts.py`'s equivalent `ast.Attribute`/`ast.Call` handling
   for a `Name`-valued (not `Constant`-valued) method-name argument to `getattr`, or the
   `self.<computed>` pattern generally (exact Python AST shape not yet re-verified this pass —
   flagged as the one remaining implementation-design detail, not a research question).
4. **`L4` consequence:** one new, unconditional `EdgeRules` branch, identical for both
   languages by construction.
5. **Tests:** RED — construct the fact kind, prove `EdgeRules` currently type-rejects it;
   GREEN — the new branch accepts it and always excludes; negative control — an ordinary
   determinable call is unaffected; regression — the existing 182+ green suite stays green;
   cross-adapter parity test — both languages produce the identical exclusion record shape for
   their respective syntactic forms of the same construct.
6. **Real-corpus validation:** PHP `app/` already measured — 1 genuine site
   (`DebugVoterSlug.php:67`). Python corpus validation **not yet performed** — this pass's own
   §2 gap, distinct from `D-4`'s: `D-1` has strong cross-language *representation* evidence but
   has never been run against a real, large Python corpus (only hand-built fixtures) — this is
   exactly what `2026-09-27-KOS-adapter-research-FROZEN.md` itself names as explicitly
   out-of-branch-scope (*"Real Python-source corpus validation... If Python adapter work is
   ever taken further, that is new work"*).
7. **Falsification condition:** any Python or PHP corpus site where the proposed
   `IndeterminateBehaviourReference` path produces a different `L4`/`L5` outcome than the
   current (silent-skip) behavior for anything **other than** the exclusion record itself
   (i.e., if it ever changes a metric value, the "metric-neutral" claim this entire thread
   rests on is false and must be re-examined before proceeding).

## 7 · Prioritized roadmap

### P0 — must resolve before the Python kernel can be considered coherent
**None found.** This is itself a finding: every item that could plausibly block calling the
current kernel language-neutral is already closed (§2), confirmed by re-reading the FROZEN
ledger and independently re-verifying its diamond/MRO claim (§3). The kernel, as it stands
today, is coherent for everything tested.

### P1 — strong evidence, should implement next
- **`D-1` incorporation + implementation, both adapters together.** Reason: the only
  remaining genuine canonical-expressiveness gap in the entire capability; evidence is
  convergent across both languages (§2, §6); the schema question is now independently
  resolved to a minimal, evidence-backed shape (today's schema-sufficiency pass). Dependency:
  the still-open PO/ARB incorporation decision (`2026-09-28-...-V3-decision-gate-
  reconciliation.md`, `...-V3-governance-decision-brief.md`) — **this is a governance
  dependency, not a research one.** Estimated size: small (one new `Domain` class, one new
  `EdgeRules` branch, two adapter emission-site changes, a handful of tests) — genuinely
  bounded by every piece of analysis this thread has produced.

### P2 — characterize when real-corpus/evidence demands it
- **`D-4`/`D-5`'s Python-side independent confirmation** — narrow, bounded, cheap; not
  blocking `D-1` (§2's disposition already treats it as low-stakes, given zero real PHP
  occurrences and a sound-but-unverified inference). Dependency: none — can run standalone,
  before or after `D-1`.
- **`D-1` real-Python-corpus validation** (§6.6) — a scale check, not a new characterization;
  explicitly named by the FROZEN document as its own, separate future scope.

### P3 — explicitly deferred / out of scope
- `QualifierKind::SelfKeyword` renaming/documentation (cosmetic, zero behavioral risk).
- `UnitKind` Python-subclass mapping (`Enum`/`Protocol`/ABC) — no evidence yet justifies it.
- `FactSet` docblock wording.
- Full `C3` MRO, `__set__`/`__delete__` descriptors, a general reflection engine — all
  explicitly named by the FROZEN document as out-of-scope, evidence-checked (§3, §2) to have
  no consumer anywhere in the pipeline.
- Generators, comprehensions, metaclasses, operator overloading, context managers, iterators,
  dynamic class construction — never raised by any evidence; not pursued speculatively (§4).

## 8 · The two final architectural questions

> **What is the smallest Python semantic implementation that gives KnowledgeOS a credible
> language-neutral `L3` kernel?**

On the evidence gathered across this entire thread (R1–R6, OWD-1–15, the frozen adapter
branch, and this pass): **the kernel already has it**, with one exception. Every tested
construct converges or is a declared, governed, non-arbitrary difference. The one open
item (`D-1`) has a fully-designed, minimal, cross-language-invariant fix waiting only on a
governance decision already in front of PO/ARB. There is no additional Python-side
*research* required to reach a credible kernel — what remains is **incorporation and
implementation of one already-designed concept**, not discovery of new ones.

> **What concepts remain deliberately outside that kernel?**

Trait methods and arrow functions (genuinely PHP-specific, no Python analogue exists to
converge with); async methods were the reverse case (Python-specific, now closed); magic-
method/dynamic-attribute interception (reaches the metric, explicitly excluded by the pinned
`magic_methods` decision — a deliberate scope boundary, not an oversight); descriptors
(Python-specific, no PHP analogue, disposed); full `C3` MRO, general reflection, and
`__set__`/`__delete__` descriptors (evidence-checked to have no consumer in this pipeline —
deliberately excluded on cost/benefit grounds, not on principle, and revisitable if a future
consumer is ever added).

---

## 9 · STOP

**Concepts discovered:** none genuinely new beyond what §2/§4 already catalog — this pass's
value is reconciliation and correction (§0), not net-new discovery, which is itself the
honest finding given how thoroughly the frozen branch already searched this space.

**Existing TODO/backlog items:** consolidated in §2's matrix; only `D-1` (P1) and `D-4`/`D-5`
(P2) carry any open work.

**Already implemented concepts:** everything classified A/D/F above (19 of ~26 items across
both inventories).

**Missing Python concepts:** none found beyond `D-4`/`D-5`'s narrow, low-stakes independent-
confirmation gap.

**Missing language-neutral `L3` concepts:** exactly one — `IndeterminateBehaviourReference`,
already designed, not yet incorporated or implemented.

**Recommended implementation order:** `D-1` first (P1, governance-gated) → `D-4`/`D-5`
confirmation (P2, ungated, can run in parallel) → real-Python-corpus validation of `D-1` once
implemented (P2, sequential after implementation).

**Dependencies:** `D-1`'s implementation depends on a PO/ARB incorporation decision that
already exists as a prepared brief, not on any further research.

**The single best next implementation slice:** `D-1` — new `IndeterminateBehaviourReference`
fact kind, one `EdgeRules` branch, both adapters' emission sites, per §6.

**Should it be implemented now or characterized first?** **Characterization is complete.**
Every question this pass could ask about `D-1`'s semantics, schema, or cross-language
generality has already been answered by convergent, re-verified evidence across three weeks
and two independent research threads (the governed V-3 chain and the frozen adapter branch).
**The remaining blocker is the governance decision already sitting with PO/ARB — not more
research.** This pass does not implement anything and does not send that decision forward
itself, per the standing constraint — it reports, precisely, that research is no longer the
bottleneck.

**No implementation performed by this pass.**

---

**Traceability:** every document cited inline above · `2026-09-27-KOS-adapter-research-
FROZEN.md` (the primary source this pass reconciles against) · `2026-09-28-KOS-python-
semantic-parity-inventory.md` (corrected, §0) · `2026-09-28-KOS-CONTRACT-NEUTRALITY-001-D1-
schema-sufficiency-pass.md` · `2026-09-28-KOS-CONTRACT-NEUTRALITY-001-V3-governance-decision-
brief.md`.
