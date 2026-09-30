# OWD-15 — PHP: interfaces, abstract classes, readonly properties, full trait coverage

**Context:** closing the spot-check gaps OWD-12's broader census left open · **Date:** 2026-09-28
**Phase:** validation/discovery. **No production code changed.**

---

## What OWD-12 counted but never individually verified

Interfaces (122 files), abstract classes (16 files), and readonly properties (577
files) were counted in OWD-12's construct census but never spot-checked against real
code. Only 1 of the 12 real trait files was inspected. This session closes those gaps.

## Results

- **Real interface** (`app/Contracts/PaymentGateway.php`): correctly `kind=
  InterfaceUnit`, `analysed=no` — `UnitEligibility` excludes it, on real code, for the
  first time confirmed (previously only synthetic-fixture-tested). All three method
  signatures correctly `hasBody=false`.
- **Real abstract class** (`app/Exceptions/Voting/VotingException.php`): correctly
  `kind=ClassUnit`, `analysed=yes`, and — the more interesting confirmation — its
  bodyless abstract method (`getUserMessage`) **is still counted as a node** (3 nodes
  total), matching `GraphBuilder`'s own documented rule ("Bodyless declarations ARE
  nodes") verified live on real code for the first time.
- **All remaining 11 real trait files** (the 12th match, `VoterSlugStep.php`, turned
  out to be documentation prose with a stray `.php` extension, not real source —
  confirmed by inspection; the tokenizer-based extractor didn't crash on it either,
  a minor incidental robustness point, not a finding): all processed cleanly, LCOM4
  values ranging 1–6, all plausible for their role (auth-scoping concerns, event
  recorders, vote-storage helpers).
- **Real readonly-property class** (`app/Domain/Election/Events/NominationCompleted.php`):
  a zero-method value-object/event class, correctly `LCOM4 = 0` — matches the existing,
  already-tested "a unit with no analysable methods has value zero" rule.

## Classification

**A — confirmatory.** No new candidate defect found across any of the four checked
construct categories.

## Production changes

**None.**

## Updated PHP real-corpus coverage tally

```
OWD-10: 1,629 files, full pipeline, 0 crashes (robustness only)
OWD-12: construct census + enum/match/one-trait/outlier spot-checks
OWD-15: interface, abstract-class, all-12-traits, readonly-property spot-checks
= every construct category OWD-12's census flagged as "counted but unverified" is now
  individually confirmed correct on real code.
```

**Traceability:** `app/Contracts/PaymentGateway.php`, `app/Exceptions/Voting/
VotingException.php`, 11 real trait files, `app/Domain/Election/Events/
NominationCompleted.php` (all read/executed directly) ·
`2026-09-28-KOS-PHP-OWD12-real-corpus-audit.md` (the gaps this closes).
