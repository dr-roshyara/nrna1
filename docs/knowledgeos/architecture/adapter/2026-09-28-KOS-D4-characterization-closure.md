# KOS — D-4 characterization: closure

**Context:** bounded closure task, evidence already gathered this session. **Date:**
2026-09-28. **Phase:** characterization only — no implementation, no D-1 changes.

> ⛔ No production code, test, or `expected.json` change. `git status --short scripts/
> tests/ .claude/runtime` unchanged before and after — confirmed below.

---

## 1 · The D-4 question

`D-4` (PHP `call_user_func([$this, 'method'])` / `call_user_func_array(...)`): does a
literal-target-name dispatch through an indirection function occur, in either language's
real corpus, in a way that requires representing it as a distinct canonical concept?

## 2 · Adopted scope, exactly as decided (2026-09-04)

*"In scope, closed list: `call_user_func`, `call_user_func_array`, matched **only** in the
literal `[$this, 'name']` form ($this as the first element, a literal string as the
second)."* Anything else — a variable receiver, a variable method name, any other dispatch
mechanism — is explicitly **out of scope**, a declared limitation, not silently unhandled.

## 3 · PHP evidence

Re-verified this pass:

- `grep -rn "call_user_func" app` — **exactly one site**, `Google.php:129`:
  `call_user_func_array([$this->client, $method], $args)`.
- Checked against the adopted scope, field by field: receiver is `$this->client` (a
  **property**, not `$this` directly) — fails the scope's "$this as the first element"
  requirement. Method name is `$method` (a **variable**, not a literal string) — fails the
  scope's "literal string as the second element" requirement. **Fails on two independent
  grounds, not one.**
- `PhpFactExtractor.php` has **zero** `call_user_func` handling of any kind (confirmed by
  grep — empty result). `D-4` was never implemented on the PHP side; there is nothing to
  compare a Python implementation against even in principle.

**Conclusion: zero genuine `D-4` occurrences in this project's real PHP corpus.**

## 4 · Python evidence

The closest Python analogue to PHP's literal-target `call_user_func([$this,'name'])` is
`getattr(self, 'literal_name')()` — the receiver is known (`self`), the target name is a
literal string, and the indirection is through a builtin rather than direct syntax, exactly
mirroring `D-4`'s structure (as opposed to `D-1`'s, where the name itself is unknown).

An AST-level scan of the full 153-file top-level CPython 3.13.2 stdlib corpus, distinguishing
`getattr(self, <Constant string>)()` from `getattr(self, <Name/other expression>)()`:

```
LITERAL name sites (the D-4 analogue): 0
VARIABLE/computed name sites (D-1):    3   (pdb.py:864, pydoc.py:605, pydoc.py:1234)
```

**Conclusion: zero genuine `D-4` occurrences in the examined Python corpus.**

## 5 · `D-1` vs `D-4` distinction — confirmed, not merely asserted

The three real Python sites found are `D-1`, not `D-4`, on the precise ground the adopted
scope itself draws: in all three, the method name is a **variable** (`command`,
`methodname`), not a literal — exactly `D-1`'s "the name itself is unknown" shape, not
`D-4`'s "the name is known, the dispatch mechanism is indirect" shape. Verified by direct
source inspection (§4 of the prior real-corpus validation), not by pattern-matching alone.

## 6 · Literal-vs-unknown `getattr(self, ...)` observation — bounded, not escalated

Empirically confirmed this pass: the current `D-1` implementation's `_is_getattr_self_dispatch`
detector does **not** distinguish a literal-string second argument from a variable one —
`getattr(self, 'target')()` and `getattr(self, name)()` are both currently classified as
`IndeterminateBehaviourReference` (tested directly against both forms, same result). This
means a hypothetical literal-name `getattr(self, ...)()` site would today be *treated* as
`D-1` rather than recognised as `D-4`-shaped.

**This is recorded as an observation only, per instruction:**
- it may represent a finer semantic distinction than the current implementation draws;
- **no real corpus evidence — zero occurrences, either language — currently exercises it**
  (§3, §4);
- therefore it is **not an implementation requirement**. Refining the `D-1` detector to
  separately recognise a literal-name `getattr(self, ...)` call would be solving a case that,
  on all evidence gathered so far, does not occur.

## 7 · Final classification

**D-4 has no genuine occurrence in the examined PHP or Python real corpora. There is
therefore no empirical basis to implement `D-4` or `D-5` at this time.**

Stated precisely, per instruction: this is **not** a claim that `D-4` can never occur — it is
that **no current corpus evidence justifies building it.** The distinction matters: `D-2`/`D-3`
were decided "out of scope" on the same evidentiary basis (checked, not found), and the
correct record for `D-4`/`D-5` is the identical shape of finding, not a stronger or weaker
one.

## 8 · Consequence for D-5

`D-5` (the `ExplicitCallableDispatch` vocabulary) exists solely to name `D-4`'s
representation. With no evidence `D-4` needs building, `D-5` has nothing to name yet.
**`D-5` inherits `D-4`'s status entirely** — not a separate finding, a direct consequence.

## 9 · Consequence for the governance decision

This closure removes exactly the branch the human's own framing anticipated: the
`D-4`/`D-5` vs. `D-1` bundling question (Option A/B in `2026-09-28-KOS-CONTRACT-NEUTRALITY-
001-V3-governance-decision-brief.md`) no longer has two live sides to weigh. The evidence
now supports a simpler framing than either original option:

```
D-1  -> implemented, tested, real-corpus-validated in both languages -> ready for formal
        expected.json incorporation on its own
D-4  -> no genuine occurrence found, either language           -> do not build
D-5  -> depends entirely on D-4                                -> do not build
```

**This is not a decision this report makes** — incorporation remains the human's/PO-ARB's
governance checkpoint, unchanged in kind. It is a factual narrowing of what that decision now
has to weigh.

---

## STOP

No implementation performed. No `D-1` code changed. No `L3` parity work reopened. No `LCOM4`
analysis performed. No new semantic investigation started.

```
$ git status --short scripts/ tests/ .claude/runtime
(no output — clean)
```

**Traceability:** `2026-09-28-KOS-CONTRACT-NEUTRALITY-001-V3-governance-decision-brief.md`
§3, §8 (the original `D-4`/`D-5` separability evidence this closure extends) ·
`2026-09-04-...-V3-D1-D4-D5-proposal.md` §2 (adopted scope, verbatim) · `2026-09-28-KOS-D1-
implementation-results.md` (the real-corpus method and corpus path this closure reuses) ·
`app/Services/Google.php:129` · `PhpFactExtractor.php` (grepped, no `call_user_func`
handling) · CPython 3.13.2 stdlib corpus, 153 top-level files, re-scanned this pass with a
literal/variable-distinguishing AST check (session scratchpad script, not part of the
repository).
