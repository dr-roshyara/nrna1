# OWD-12 — real PHP corpus audit: construct census + spot-checks, no new findings

**Context:** OWD-10 confirmed robustness (0 crashes) across the real `app/` corpus but
never construct-audited it the way OWD-1 did for Python · **Date:** 2026-09-28
**Phase:** validation/discovery. **No production code changed.**

---

## Method

Same corpus as OWD-10 (this project's own real application, `app/`, 1,629 `.php`
files). Two passes: (1) a construct-frequency census via PHP's own `token_get_all()`,
mirroring OWD-1's spirit for Python; (2) the full `PhpFactExtractor` →
`AnalyseCohesion::observe()` pipeline, as in OWD-10, plus manual inspection of the
patterns the census flagged as never specifically stress-tested in this investigation.

## Result 1 — robustness reconfirmed

0 crashes, 1,629/1,629 files, 1,639 total units, 1,517 analysed — consistent with
OWD-10.

## Result 2 — construct frequency (never previously measured for this corpus)

```
function/method/closure   1519 files   6817 occurrences
class declarations         1399 files   2165 occurrences
use (import or trait use)  1247 files   5604 occurrences
readonly properties         577 files    910 occurrences
static keyword               324 files    759 occurrences
arrow functions (fn)          131 files    300 occurrences   (OWD-10's corpus)
interfaces                    122 files    122 occurrences
nullsafe operator              111 files    292 occurrences
enums                           96 files     97 occurrences
match expressions               91 files    140 occurrences
first-class-callable/variadic   36 files     49 occurrences
abstract classes                16 files     28 occurrences
traits                            12 files     12 occurrences
```

**Enums (96 files) and `match` expressions (91 files) heavily overlap** — this
codebase's dominant enum idiom is a backed enum with a `match ($this) { self::Case
=> ... }`-shaped method, never previously exercised end-to-end by anything in R1–R6 or
OWD-1–11.

## Result 3 — spot-checks against real code, not synthetic guesses

- **Real enum+match+`$this` file**
  (`app/Application/Election/Capabilities/CapabilityDenialReason.php`): confirmed by
  direct execution — a bare `$this` (no `->` follows, used only as the `match`
  subject) and a `self::CaseName` reference (no `(` follows, not a call) both
  correctly produce zero facts. `LCOM4 = 1` for the single-method enum — trivially
  correct.
- **Real trait** (`app/Shared/Domain/Concerns/RecordsEvents.php`): an array-append
  property write (`$this->recordedEvents[] = $event;`) is correctly classified as a
  state access, not a call, despite the trailing bracket-like token. Both methods
  correctly connect via the shared property (`LCOM4 = 1`). One pre-existing, already-
  known, harmless characteristic reconfirmed: repeated touches of the same property
  by the same method produce duplicate identical edges (the same non-deduplication
  behaviour already disclosed in OWD-7/OWD-8's `iterweekdays` case) — not a new
  finding.
- **Extreme `LCOM4` outliers** (32, 33, 33): `Models/Election.php` (122 nodes, 143
  edges), `Models/User.php` (88 nodes, 80 edges), `Http/Controllers/
  ElectionManagementController.php` (38 nodes, 6 edges). All three are exactly the
  well-known Laravel "fat model"/"fat controller" pattern — many largely-independent
  scopes, relationships, accessors, or action methods sharing little state. Legitimate
  signal, not a pipeline defect — same conclusion OWD-6/OWD-9 reached for CPython's
  own outliers.
- **First-class-callable syntax**: a naive regex check for a real usage example
  initially "found" one in `SitemapController.php` — on inspection, the match was
  inside a `//` comment, not real code. `PhpFactExtractor` correctly excludes comments
  via PHP's own tokenizer (comments never enter `$this->sig`), so this was a flaw in
  my spot-check method, not a finding. This construct was already tested and closed
  (`first_class_callables`, classification A) earlier in this investigation; not
  re-opened.

## Classification

**A — confirmatory.** No new candidate defect found. Two genuinely novel real-corpus
patterns (enum+match+`$this`, real trait shape) preserved as permanent regression
tests since nothing like them existed in the test suite before, even though both are
correct as-is.

## Scope honesty

This is a **spot-check audit**, not an exhaustive semantic verification of all 1,629
files — the construct census is exhaustive (every file tokenized), but manual
inspection was targeted at the patterns the census flagged as novel (enums/match,
traits, extreme outliers), not at every one of the 1,517 analysed units individually.

## Production changes

**None.**

**Traceability:** `app/` (real corpus, 1,629 files, token-level census + full pipeline
run) · `CapabilityDenialReason.php`, `RecordsEvents.php` (real files inspected
directly) · `RealCorpusPhpConstructRegressionTest.php` (new, 2/2 green, full Cohesion
suite 175/175) · `2026-09-28-KOS-PHP-OWD10-implementation.md` (prior robustness check,
same corpus).
