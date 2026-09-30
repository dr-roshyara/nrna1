# OWD-11 — `DeclaredUnit::declaredName`: resolved (was already resolved; corrects an audit oversight)

**Context:** the last item named in `2026-09-28-KOS-CANONICAL-L3-SUFFICIENCY-AUDIT.md`
· **Date:** 2026-09-28
**Phase:** characterization + a self-correction. **No production code changed.**

---

## Starting point — a correction to my own prior work

The sufficiency audit (yesterday, this directory) listed
`DeclaredUnit::declaredName` as "still unconfirmed either way." That was wrong: a
rigorous, already-existing, already-passing test —
`DeclaredNameCharacterizationTest::test_declaredName_affects_neither_analysis_nor_reporting`
— already proves it byte-for-byte, by holding `identity` constant and varying
`declaredName` across three values (`'A'`, `'SomethingElse'`, `null`) and showing
`AnalyseCohesion::observe()` output is identical in every case. I had not checked for
this test before writing the audit. This report corrects that, rather than silently
carrying the error forward.

## What was still genuinely missing, and is now closed

The existing test proves **non-consumption** — nothing downstream reads
`declaredName`. It did not establish whether `declaredName` is merely a **redundant**
mirror of `identity` at the point adapters populate it, or whether it carries real
information `identity` alone discards. That distinction matters for judging whether
this is truly inert or a latent, currently-unused capability.

**Confirmed by direct execution** (not inferred from reading `PhpFactExtractor` alone):
```
Named class:      identity=Named            declaredName='Named'
Anonymous class:   identity=Named/anon#1     declaredName=NULL
```
`declaredName` is **not** purely redundant — for an anonymous class, PHP's real
adapter correctly sets it to `null` (the class genuinely has no name), while `identity`
is always a synthesized path (`Named/anon#1`) that cannot, by itself, distinguish
"named" from "anonymous." Python's adapter, by contrast, always sets `declaredName`
equal to the same string used for `identity` — it has no anonymous-class concept
implemented at all (not previously characterized anywhere in this investigation; noted
here, not pursued further — out of this report's scope).

## Classification

**Confirmed unconsumed — the same bucket as `MethodFacts::hasBody`,
`StateAccess::accessMode`, `BehaviourReference::accessMode`, and
`BehaviourReference::factId`.** Not a defect (no `LCOM4` error, no misattribution, no
materiality claim of any kind) — the correct classification is "required by the type
for totality, not yet exercised by current analysis," exactly the standing, already-
disclosed status those four other fields have carried since the original dependency
matrix. This is the fifth confirmed member of that set, not a new kind of finding.

## What would make it consumed (sketched, not proposed as work)

`declaredName`'s one piece of real, currently-unused information — whether a unit was
declared with a name at all — would matter only to a hypothetical future capability
that treats anonymous units differently (e.g., "exclude anonymous classes from a
report," or a corpus-frequency question about anonymous-class prevalence). No such
capability exists or has been evidenced as needed anywhere in R1–R6 or OWD-1–10.

## Recommendation

**Close this out. Remove it from the sufficiency audit's list of open items** — it was
never actually open; the audit's own text was the error. No correction to production
code is warranted or proposed: this is confirmed dead weight for totality, same as the
other four fields, not something requiring a fix.

**No production change made.**

**Traceability:** `DeclaredNameCharacterizationTest.php` (existing test cited directly;
one new test added confirming the named-vs-anonymous population divergence, both
2/2 green, full Cohesion suite 173/173) · `PhpFactExtractor.php` (`assignIdentities()`,
`new DeclaredUnit(...)` call site, read directly) · `PythonSemanticFactProvider.php`
(confirmed always redundant, no anonymous-class handling) ·
`2026-09-28-KOS-CANONICAL-L3-SUFFICIENCY-AUDIT.md` (the document being corrected).
