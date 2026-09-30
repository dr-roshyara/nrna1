# OWD-7 — `property(fget, fset)` call-form idiom: real, material, STOP before correction

**Context:** the low-priority gap OWD-4 flagged, re-examined with real-corpus grounding
· **Date:** 2026-09-28
**Phase:** characterization only. **No production code changed.**

---

## Correction to OWD-4's priority assessment

OWD-4 called this "rare relative to the decorator idiom" without checking. It is not
rare: within the exact 153-file top-level stdlib corpus this whole investigation has
used throughout, it occurs in **`calendar.py`, `enum.py`, and `ast.py`** — confirmed by
direct grep, not assumed.

## Recovered meaning

`NAME = property(getter, setter)` constructs a property descriptor identically, at
runtime, to `@property`/`@x.setter` — Python's descriptor protocol makes no distinction
(read → `fget`, write → `fset`). The only difference is syntax: an explicit constructor
call at class-body scope versus decorator sugar. Unlike the decorator idiom, the
property's public name and the underlying methods' names **can differ** — confirmed in
the real example below (`firstweekday` vs. `getfirstweekday`/`setfirstweekday`).

## Real example, in full context (`calendar.py`)

```python
def getfirstweekday(self):
    return self._firstweekday % 7

def setfirstweekday(self, firstweekday):
    self._firstweekday = firstweekday

firstweekday = property(getfirstweekday, setfirstweekday)

def iterweekdays(self):
    for i in range(self.firstweekday, self.firstweekday + 7):
        ...
```
`iterweekdays` and six other real methods in the same class (`itermonthdays`,
`monthdays2calendar`, etc. — confirmed by grep, not estimated) read `self.firstweekday`
repeatedly. Each read should, per the descriptor protocol, invoke `getfirstweekday()`.

## Current behavior, confirmed by direct execution on a fixture modeled on this exact shape

`extract_facts.py`'s `property_names` set is built only from `@property`-decorated
defs — it has zero awareness of this assignment-based construct. Result:
`iterweekdays`'s reads of `self.firstweekday` become a plain `StateAccess
("firstweekday")` — a property name that **nothing else in the class touches** (the
real backing state is `_firstweekday`, touched only by the getter/setter). This
silently and completely disconnects `iterweekdays` from the pair it actually depends
on.

## Materiality — real, not theoretical

```
Current:  edges=[[getfirstweekday,setfirstweekday,state]]                  LCOM4=2
Correct:  iterweekdays should connect to getfirstweekday                    LCOM4=1
```
A real `LCOM4` value error, on a fixture modeled directly on genuine, unmodified
standard-library code — the same class of materiality as R1/R3/R5/OWD-3/OWD-5.

## Classification: **D**

Adapter limitation, Python-only (no PHP equivalent construct exists to compare
against — PHP has no property-descriptor mechanism at all). The existing `Domain`
vocabulary (`property_names`-style recognition feeding a `BehaviourReference` with
`ReferenceMode::Invocation`) is already sufficient in shape; this is purely an
unrecognized extraction pattern, not a missing canonical concept.

## Interaction with OWD-4 (explicitly noted, not resolved here)

Fixing this properly — routing a *read* of `self.firstweekday` to `getfirstweekday`
and a *write* to `setfirstweekday` specifically — requires the same read/write context
information OWD-4 characterized and left frozen. A correction here would most
naturally reuse OWD-4's "connect to every candidate" fallback (both getter and setter,
undifferentiated) rather than resolving read/write, keeping this slice consistent with
the standing frozen decision rather than quietly resolving it as a side effect.

## Scope for a correction, if authorized (sketched, not implemented)

Recognize, in `extract_facts.py`, a class-body-level `ast.Assign` whose value is
`ast.Call(func=Name('property'), ...)`, where the `fget`/`fset` arguments (positional
or keyword) are bare `ast.Name` references to methods already declared in the same
class. Build a `property_name → {getter_name?, setter_name?}` map, analogous to the
existing `property_names` set, and treat `self.<property_name>` accesses the same way
`@property`-recognized names already are. **Deliberately excluded from this scope**:
lambda-valued arguments (`property(fget=lambda self: ...)`, seen in third-party code,
not in the stdlib top-level corpus), module-level monkey-patching (`Cls.attr =
property(...)` outside any class body — confirmed present in `ast.py`, structurally a
different, harder problem), and the empty `property()` case (`enum.py`'s `redirect =
property()` — no getter or setter at all, used purely for its descriptor identity,
irrelevant to cohesion analysis). All three are named, not silently ignored, and
excluded because they weren't evidenced as needed within this corpus's actual usage,
or are structurally out of what a per-unit extractor can reasonably resolve.

## Recommendation

Materially significant (confirmed real `LCOM4` error on real-code-modeled input) and
more common than previously assumed. Recommend authorizing correction under the same
RED→GREEN discipline as every prior slice, scoped exactly as above (bare-name
positional/keyword `fget`/`fset` only) — but **not implemented here**, per the standing
rule for every new finding in this investigation.

**No production change made. Stopping here — no implementation without explicit
authorization.**

**Traceability:** `calendar.py`, `enum.py`, `ast.py` (real corpus, grepped directly,
`calendar.py` read in full context) · `extract_facts.py` (confirmed `property_names`
scope, unchanged) · `PropertyCallFormCharacterizationTest.php` (new, 1/1 green, full
Cohesion suite 156/156) · `2026-09-27-KOS-PYTHON-OWD4-property-target-resolution.md`
(the frozen read/write question this correction would need to reuse, not resolve).
