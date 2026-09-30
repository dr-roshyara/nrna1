# KOS L3 language-neutrality experiment (research report)

**Context:** `KOS-CONTRACT-NEUTRALITY-001` adapter-architecture investigation, continued
**Date:** 2026-09-27 · **Performer (self-declared, not attestable):** `claude-code-session:e8f324f1-...`

> ⛔ Non-authoritative. `expected.json` untouched, no governed file touched, no production
> code touched, no PO/ARB request. Everything below ran as throwaway scratch PHP outside the
> repository; verified before/after via `git status` that nothing in `nrna1` changed.

---

## 1 · Business conclusion

**Yes — the existing `L3`/`L4`/`L5` core can plausibly become language-independent, and this
is now demonstrated, not just argued.** Hand-built facts representing Python semantics passed
cleanly through the real, unmodified `EdgeRules`/`GraphBuilder`/`Lcom4` pipeline, and a
genuine (if tiny) cross-language parity case — real PHP extractor vs. a from-scratch Python
adapter — produced **identical** node/edge structure and metric value. The core model is not
merely "PHP-neutral by absence of a counter-example"; it accepted a real counter-example.
The gap is not in `L4`/`L5`. It is in (a) the not-yet-built extraction layer for any second
language, and (b) a few specific, now-identified places where the *vocabulary* still carries
PHP idiom, and (c) the one representational hole (`V-3`/`D-1`) that turns out to be
core-model-general, not PHP-specific.

## 2 · Current architecture

Unchanged from the prior report (§3-§6 there): PHP-only `L3`→`L4`→`L5`, Python collector
fully separate with no shared representation, no port/interface exists. Not repeated here.

## 3 · Experimental evidence

**Experiment 1 — L3 neutrality, 5 hand-built cases**, run through the real
`GraphBuilder::build()` and `Lcom4::compute()`, zero PHP-specific code touched:

| Case | Python construct | Nodes | Edges | LCOM4 | Expected |
|---|---|---|---|---|---|
| A | `def f(self): self.g()` / `def g(self): pass` | f,g | f-g (behaviour) | **1** | 1 ✅ |
| B | `def f(self): return self.value` | f | none | **1** | 1 ✅ |
| C | f,g share state `x`; h isolated | f,g,h | f-g (state) | **2** | 2 ✅ |
| D | two independent no-op methods (control) | f,g | none | **2** | 2 ✅ |
| E | `getattr(self, name)()` (dynamic dispatch) | — | — | — | see §5 |

**Experiment 5 — true minimal cross-language parity**, PHP fixture through the **real,
unmodified** `PhpFactExtractor::extract()` vs. a from-scratch ~25-line throwaway Python
"adapter" (line/indentation-based, not a real parser) for the identical construct:

```
PHP:    nodes=f,g  edges=[["f","g","behaviour"]]  LCOM4=1
Python: nodes=f,g  edges=[["f","g","behaviour"]]  LCOM4=1
Node count match: YES · Edge structure match: YES · LCOM4 match: YES
```

**A real, disclosed failure along the way, worth keeping in the record:** the *first* version
of the toy Python adapter (a single greedy regex) had a genuine parsing bug — it merged
method `g`'s definition into method `f`'s captured body, undercounting nodes (1 instead of 2)
and producing **zero edges instead of one**. **Its `LCOM4` value was still `1` — coincidentally
matching the correct answer, for the wrong reason** (a 1-node no-edge graph and a 2-node
1-edge graph both have exactly one connected component). This is a direct, real instance of
the principle stated in the research brief: *metric equality is necessary, not sufficient,
evidence of semantic parity.* Comparing node/edge structure — not just the final number —
caught a bug the metric alone would have hidden.

## 4 · Results

All 4 executable cases (A-D) passed exactly as predicted. The true-parity experiment (§3)
passed after fixing the toy adapter's bug — and the bug itself is informative (§3, §6).
Case E was not executable as a positive case (see §5) — its result is a confirmed
non-representability, not a pass/fail on constructed facts.

## 5 · Falsified assumptions

- **None of the "L3 might be secretly PHP-shaped at the structural level" hypotheses
  survived.** `GraphBuilder`/`EdgeRules`/`Lcom4` accepted Python-semantic facts with zero
  special-casing, zero errors, zero incorrect results.
- **The assumption that Case E (dynamic dispatch) would reveal something *new* about
  Python specifically was falsified** — it reveals the *same* gap already found for PHP's
  `$this->$m()` (§6), which is actually a stronger, more useful result: it shows the gap is
  a **core-model** limitation, not a PHP peculiarity, without needing to build a real Python
  extractor to learn that.

## 6 · Confirmed assumptions

- `L4`/`L5` genuinely operate without knowing facts came from PHP — confirmed by execution,
  not merely by the domain classes containing no PHP references (the distinction the
  research brief correctly insisted on, §"IMPORTANT DISTINCTION" — that stronger claim is
  now backed by a real test, not an absence-of-counterexample argument).
- `V-3`'s `D-1` representational gap (no legal way to record "observed but the identity
  is a computed value, with no legal name to give it") is **not PHP-specific** — confirmed
  directly: constructing the Python analogue (`getattr(self, name)()`) requires the exact
  same choice — accept an empty-string sentinel (violates `D-1`'s own prohibition) or leave
  it unrepresented — using the identical `BehaviourReference` type, no PHP code involved.
- `Lcom4::compute()` is confirmed, by its own docblock and by direct reading, to receive
  **only** `(node set, edge set)` — no unit kind, no qualifier, no source of any kind. This is
  the most language-neutral layer in the system, by construction, not by argument.

## 7 · Canonical model gaps (evidence-backed, not the D-1/D-4/D-5 gap — that's separate, see §11)

- **`QualifierKind::SelfKeyword` is a false-cognate trap for a Python adapter author.** In
  this codebase it means PHP's `self::` (static/late-binding self-reference) — but Python's
  own keyword `self` is semantically PHP's `$this` (`InstanceReceiver`), not `self::`. A
  Python adapter naively mapping `self` → `SelfKeyword` by name-matching would misclassify
  every ordinary instance method call. **Confirmed by reading the enum and its usage in
  `EdgeRules`, not merely suspected.**
- **`UnitKind`'s cases (`ClassUnit`, `TraitUnit`, `EnumUnit`, `InterfaceUnit`,
  `AnonymousClass`) mirror PHP's *declaration keywords*, not a language-neutral taxonomy.**
  Python has classes but no `trait` keyword (mixins are structurally just classes); Python's
  `enum.Enum` is a class with special metaclass behavior, not a distinct grammatical
  declaration. A Python adapter would face a real interpretive choice here — not a blocking
  one, but a genuine mapping decision, not a mechanical one.
- **`FactSet`'s own docblock states, as a *decided* invariant (`OQ-2`): "the analysis scope
  is exactly ONE PHP SOURCE FILE."** This is a named, explicit PHP-specific assumption at
  the aggregate-identity level (not just naming) — narrower in impact than the two above, but
  worth listing precisely because it's a *decided* rule, not an implementation accident.

None of these three are blocking — all are mapping decisions or renaming/generalization
work, not structural incompatibilities. That itself is evidence for, not against, the
neutrality hypothesis.

## 8 · Adapter contract (minimum semantic contract, derived from actual type signatures — not invented)

For each analysed unit, an adapter for any language must supply:
- **Unit-level:** a kind (from a closed vocabulary — currently PHP-declaration-shaped, §7),
  a stable identity, a declared name, and its methods.
- **Per method:** an identity (name), whether it has a body, its state accesses, its
  behaviour references.
- **Per state access:** a property/attribute name (string, mandatory) and an access mode
  (`Direct`/`Nullsafe` — the latter is itself a PHP-specific operator; a language without
  null-safe access would only ever emit `Direct`, which is not a problem, just a fact).
- **Per behaviour reference:** a target name (string, mandatory, no legal "unknown" value —
  this is where `D-1`'s gap lives), a qualifier kind, a target-unit relation, a reference
  mode (`Invocation`/`CallableReference`), an access mode, and a determinability.

Every one of these fields is required *by the type signatures themselves* — none was
invented for this report. This is the actual minimum contract any second-language adapter
must fill in, derived from code, not designed from scratch.

## 9 · Python readiness

**Structurally close for the tested subset; not close for real coverage.** A ~25-line,
non-robust, single-construct-family script already produces conforming facts for the
simplest possible case (§3). Getting from there to *anything* resembling real Python source
coverage requires: real tokenization/parsing (not regex — §3's first-attempt bug is exactly
why), handling Python's actual method/attribute resolution rules (which differ from PHP's in
ways not yet explored here — e.g., Python has no visibility keywords, uses dunder/name-
mangling conventions instead), and resolving the `UnitKind`/`SelfKeyword` mapping questions
in §7. **Readiness for a real adapter: low. Readiness for further *falsification* experiments
of this same cheap kind: high** — more constructs can be hand-tested this way before any
real parser is justified.

## 10 · Cross-language parity — has it been demonstrated?

**Yes, for the first time, for one minimal construct.** Section 3's Experiment 5 is a genuine
instance of `PHP source → PHP adapter → L3 → same L4/L5` and `Python source → Python
adapter → L3 → same L4/L5`, with the canonical representation (not just the metric)
compared and found identical. **This is smaller in scope than "cross-language parity" as a
general claim** — it covers exactly one construct family (a single direct method call). It
is not evidence about state access, exclusions, multi-class files, or anything in §7's gap
list. **The stage that remains: extending this same method (hand-verified small adapters,
representation-level comparison, not metric-only) to the constructs in §7 and to `V-3`'s own
already-known-hard case, before any claim of general parity would be warranted.**

## 11 · Architecture recommendation

Confirmed, not merely repeated: `D-1`/`D-4`/`D-5` belong at the `L3` canonical layer (§6's
Case E result directly supports this — the same gap, same layer, same fix, regardless of
source language). The three §7 vocabulary items are lower-urgency, non-blocking renaming/
mapping work — worth tracking, not worth stopping for. **No change to `expected.json`,
`PhpFactExtractor`, or the `D-1`/`D-4`/`D-5` incorporation status is recommended by this
report** — that question is exactly where it was left (open, pending PO/ARB, per the prior
reports), and this experiment doesn't change its urgency, only its evidentiary grounding.

## 12 · Next smallest experiment (exactly one)

**Hand-construct the `V-3`/`D-1` case for Python (`getattr(self, name)()`) all the way
through — including attempting the `IndeterminateBehaviourReference` scratch fix already
proposed for PHP — and confirm it resolves the Python case identically to the PHP one, with
zero new code beyond what `D-1`'s adopted proposal already specifies.** This is the single
cheapest remaining test that would confirm (or falsify) that `V-3`'s fix, once built, is
truly a one-time, language-neutral fix rather than something that will need re-solving per
language — directly answering the question this whole adapter investigation was raised to
protect against.

---

## Epistemic ledger

**`FACT`** (executed or directly read, this session): all of §3's numeric results ·
`SelfKeyword`≠Python's `self` · `UnitKind`'s cases are PHP-declaration-shaped ·
`FactSet`'s `OQ-2` is a decided PHP-file-scope invariant · `Lcom4::compute()`'s signature
takes only nodes/edges.

**`DERIVED`** (follows deductively from `FACT`, not separately executed): the minimum
adapter contract (§8) · that `V-3`'s gap is core-model-general (from Case E's structural
identity to the PHP case, not from running a real Python extractor).

**`HYPOTHESIS`** (plausible, not tested here): that real Python source parsing would reveal
no *further* structural mismatches beyond §7's three items — genuinely untested beyond the
one hand-built construct family.

**`FALSIFIED`**: "L4/L5 might be secretly PHP-coupled" — direct counter-evidence obtained ·
"metric agreement alone would have told us the toy adapter worked" — directly disproved by
the first adapter attempt's own bug (§3, §6).

**`UNKNOWN`**: whether Python's actual method-resolution semantics (properties, descriptors,
`__getattr__`, multiple inheritance/MRO) map cleanly onto the current `L3` relations at all
— none of this was touched by the minimal experiment run here.

**STOP after this report.** No `expected.json` change. No PO/ARB request. No production
Python adapter started. `D-1`/`D-4`/`D-5` contract-incorporation work remains paused.

**Traceability:** `2026-09-27-KOS-adapter-architecture-status-reconstruction.md` (this
directory, prior report) · `2026-09-27-...-V3-representation-boundary-experiment.md` ·
source read/executed directly: `BehaviourReference.php`, `StateAccess.php`, `MethodFacts.php`,
`DeclaredUnit.php`, `FactSet.php`, `QualifierKind.php`, `UnitKind.php`, `EdgeRules.php`,
`GraphBuilder.php`, `Lcom4.php`, `PhpFactExtractor.php` (via its real `extract()` entry
point) · scratch experiments (session scratchpad, not part of the repository).
