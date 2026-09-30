# OWD-3 — resolving the identity model: A, B, or C?

**Context:** OWD-2 follow-up · **Date:** 2026-09-27
**Phase:** characterization + recommendation only. **No production code changed.**

---

## 1. Recovering the existing contract's own definition of "node" — before choosing a model

Three independent, pre-existing textual sources, all already in the codebase before this
investigation touched anything:

- `GraphBuilder.php`'s own docblock: *"Nodes : **methods declared in the unit's own body**,
  minus lifecycle methods."*
- `Lcom4.php`'s own docblock: *"the number of connected components in the **method
  graph**."*
- `expected.json`'s `_variant` line, the metric's own academic citation: *"Hitz &
  Montazeri LCOM4: connected components **over methods**; edges = shared instance
  variable OR intra-class call."*

All three, independently, say the same thing: **a node is a declared method**, not a
"logical member" or "property." None of the eight pinned decisions ever redefines this.
This directly answers the model question from already-existing text, not new inference:

## 2. Model resolution: **A**

A Python `@property` getter and its `@x.setter` are two separately declared `def` blocks
in the class body — each is exactly what `GraphBuilder`'s own docblock means by "a
method declared in the unit's own body." **Model A is not a new design choice being
introduced here — it is what the existing, already-accepted contract already says**, once
"declared method" is read literally rather than assumed to mean "uniquely-named method."

**Model B is rejected**, not merely deprioritized: treating getter+setter as one merged
node would require inventing a "logical property" concept the existing contract never
mentions, and would actively hide a genuine signal fixture 3 (OWD-2) surfaced — a getter
and setter touching completely disjoint state (`_a` vs. `_b`) is arguably real evidence of
*low* cohesion (two unrelated concerns sharing only a name), exactly the kind of signal
LCOM4 exists to detect. Merging them into one node would erase that signal, not merely
simplify it.

**Model C is unnecessary**: it would require a new relation concept (`belongs-to-
property`) to link two nodes the existing definition already treats as separate and
equal — no falsification in this investigation has shown existing vocabulary
insufficient, only that *identity bookkeeping* (not vocabulary) is broken.

## 3. What the defect actually is, restated precisely under Model A

Not "should there be one node or two" (answer: two, per §2) — the defect is purely that
`Lcom4::compute()` and `GraphBuilder`'s property-ownership tracking use the **reported
name string** as if it were a **unique node identity**, when the contract never equates
the two. `MethodFacts` already correctly emits two independent entries (confirmed, OWD-2)
— the bug is entirely downstream, in `Domain`, at the connectivity-bookkeeping layer.

## 4. Predicted effect of the minimal correction (reasoned, NOT implemented or executed)

Sketch: key connectivity (`Lcom4`'s union-find, `GraphBuilder`'s `$propertyOwner`) by each
method's **position/occurrence**, not its bare name; continue reporting the same name
string as the evidence label (disambiguated only when a real collision occurs, e.g.
`value#0`/`value#1`, otherwise unchanged). Reasoning through OWD-2's fixtures against
this sketch, without touching any file:

| Fixture | Current (buggy) | Predicted post-fix |
|---|---|---|
| 3 (disjoint state, no third method) | `edges=[]`, LCOM4=1 | `edges=[]`, **LCOM4=2** — two genuinely unrelated executable bodies, correctly separate |
| 6 (both empty + unrelated) | `edges=[]`, LCOM4=2 | `edges=[]`, **LCOM4=3** — three genuinely independent bodies |
| 2 (both touch `_value`, no third method) | `edges=[]`, LCOM4=1 (accidental) | **`edges=[[value#0,value#1,state]]`, LCOM4=1** — same number, now for the true reason: a real edge, not an array-key collision |
| 7 (getter+setter+other, all touch `_value`) | 1 edge (setter's touch silently absorbed), LCOM4=1 | **2 edges** (`value#0↔value#1`, `value#0↔other`), LCOM4=1 — same number, now with a complete, honest edge set |

Note the pattern: **every case where the current output happens to be numerically
"reasonable" (2, 7) becomes reasonable for the right reason; every case where it was
wrong (3, 6) becomes correct.** This is not a coincidence-preserving patch — it's a
principled one that happens to leave two of four fixtures numerically unchanged.

## 5. Why this is safe for every existing PHP fixture, without needing to re-verify each one

PHP structurally cannot produce two same-named declared methods (confirmed, OWD-2, §7) —
therefore for every PHP input that exists or could exist, "keyed by occurrence" and
"keyed by name" are **identical** (each name already occurs exactly once). The correction
is a strict generalization, not a behavior change, for the entire existing PHP-verified
test suite. This is a testable claim, not merely an assertion — the full regression suite
would confirm it directly if/when implemented.

## 6. Remaining uncertainty, explicitly not resolved here

**Target resolution for behaviour references** is a related, harder question this
characterization deliberately does not answer: if a third method calls `self.value`
(a plain attribute read, not a call), it should — by Python's own runtime semantics —
resolve to the **getter only**, never the setter. The current `BehaviourReference`
matching (`in_array($ref->targetMethodName, $nodes, true)`) has no concept of "read
targets the getter, write targets the setter" — disambiguating edges by occurrence index
does not, by itself, solve which occurrence a given reference should connect to. This is
a genuinely separate question from "how many nodes exist" and is **not authorized or
scoped by this report**.

## 7. Recommendation

**Model A is resolved, with high confidence, directly from the existing contract's own
words** — not a preference among equally-plausible options. What remains open (§6) is
narrower and harder than the question this report set out to answer, and deliberately
not addressed here.

**No implementation performed.** If you authorize it, the correction (occurrence-keyed
connectivity in `GraphBuilder.php`/`Lcom4.php`, disambiguated evidence labels only on
collision) is ready to implement under the same RED→GREEN discipline as R1/R3/R5 —
predictions above would become RED assertions, verified against the unmodified code
first, then made GREEN. The read-target-vs-write-target question (§6) would remain
explicitly out of that slice's scope, to be characterized separately if it later proves
material.

**Stopping here — no implementation started without explicit authorization.**

**Traceability:** `GraphBuilder.php`, `Lcom4.php`, `expected.json` (docblocks/citation,
read directly — the model-resolving evidence) · OWD-2 report and its seven fixtures (same
directory, decisive evidence this builds on) · `PropertyIdentityCharacterizationTest.php`
(existing, unmodified, still the executable record of the current buggy behavior).
