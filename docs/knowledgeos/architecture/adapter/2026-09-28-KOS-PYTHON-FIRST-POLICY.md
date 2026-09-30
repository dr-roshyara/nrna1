# KnowledgeOS — Python-first development policy

**Status: STANDING POLICY, effective 2026-09-28.** Not a research finding — a
project-direction decision, recorded here for discoverability alongside the evidence
that motivated it.

---

## The decision

> **From this point forward, Python is the primary language for new KnowledgeOS
> development.**

This applies to:
- new semantic-kernel implementation
- new adapters and extraction logic
- research experiments and characterization work
- corpus analysis
- ML/statistics tooling
- validation tooling and benchmarks
- new tests
- future L3/L4/L5 development, where practical

## What this does NOT mean

**The existing PHP implementation is not being rewritten, deprecated, or treated as
lesser.** It remains:
- a valuable, working historical implementation
- the comparative/reference system during the transition
- evidence for validating semantic invariants (as it has been throughout R1–R6 and
  OWD-1–7 — every correction in this investigation was checked against PHP's behavior,
  and PHP's own behavior was itself twice found to be the site of a bug, R5's
  class-level state gap and OWD-5's nested-closure misattribution, discovered
  *because* Python's independent implementation exposed a shared assumption PHP alone
  never had reason to violate)

Do **not** migrate architecture by copying PHP classes into Python. The correct sequence,
demonstrated repeatedly in this investigation, is:

```
existing implementation (either language)
        ↓
empirical evidence (real corpus + adversarial synthetic fixtures)
        ↓
validated semantic contract (the canonical L3/L4/L5 vocabulary)
        ↓
implementation from the contract, not from the other language's source
```

## Why now, and why this is not an arbitrary preference

This is the conclusion the evidence across the whole `KOS-PYTHON-RULE-VALIDATION`/OWD
investigation converges on, not a preference asserted independent of it:

- The canonical `Domain` vocabulary has needed exactly **one** addition
  (`MethodRole`, R1) across a dozen-plus independently-tested Python-specific
  constructs — evidence the contract is close to language-neutral already, not
  evidence Python is somehow deficient relative to it.
- Two of the real bugs found in this investigation (R5, OWD-5) were **symmetric** —
  present in PHP too, undiscovered until Python's independent implementation exposed
  them. This is the concrete argument for treating Python as a first-class
  discovery instrument going forward, not merely a target for feature parity.
- The `2026-09-28-KOS-CANONICAL-L3-SUFFICIENCY-AUDIT.md` (same directory, same date)
  is the audit this policy is conditioned on: it establishes what the current L3
  contract actually requires, derived from evidence accumulated on both languages,
  before any further from-scratch Python implementation work begins.

## Target architecture

```
Python source
      ↓
Python semantic adapter
      ↓
Language-neutral L3  ─┐
      ↓                │  same canonical layer, whichever
Language-neutral L4    │  language populates it
      ↓                │
Language-neutral L5   ─┘
      ↓
statistics / ML / research tooling

(future) PHP / Java / TypeScript / ... source
      ↓
language-specific adapter
      ↓
same canonical L3
```

## Standing constraints this policy does not override

- Do not manufacture a Python production corpus where none exists — CPython's
  standard library (used throughout OWD-1–7) remains real, professionally-written
  evidence, but it is not this project's own production corpus, and that limitation
  stays explicitly disclosed, not papered over by this policy.
- Do not implement ahead of evidence — every prior slice's discipline (recover
  meaning, characterize, classify, only then implement under RED→GREEN) continues
  unchanged. This policy sets *which* language new work defaults to; it does not
  relax *how* that work gets done.
- Do not treat PHP's historical behavior as automatically correct, and do not treat
  Python's as automatically correct either — both are evidence, as this whole
  investigation's discipline has held from R1 onward.

**Traceability:** `2026-09-28-KOS-CANONICAL-L3-SUFFICIENCY-AUDIT.md` (the evidence base
this policy is conditioned on, same directory) · every R1–R6, OWD-1–7 report (same
directory) — the empirical record this decision follows from, not precedes.
