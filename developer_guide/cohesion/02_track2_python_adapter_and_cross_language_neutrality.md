# Track 2 — Python adapter · cross-language neutrality · the `D-1` kernel extension

**Purpose.** Prove, by execution rather than assertion, that the `L3→L4→L5` boundary Track 1
built is actually language-neutral — a second adapter (Python), independently constructed,
must produce the same canonical facts for equivalent semantics without any change to
`Domain/`. Then use the one place it genuinely couldn't (`D-1`) to prove the kernel can be
*extended*, not just fed.

## Where it fits

```
scripts/lib/EngineeringKnowledge/Capabilities/Cohesion/
  Infrastructure/Python/extract_facts.py            the ONLY place Python is understood
  Infrastructure/Python/PythonSemanticFactProvider.php   deserialises extract_facts.py's JSON into Domain types — no interpretation of its own
  Domain/IndeterminateBehaviourReference.php        the one new L3 concept this track added
```

`extract_facts.py` runs as a subprocess (`proc_open('python3', ...)`), reading source on
stdin, writing one JSON object on stdout naming exactly the fields `PythonSemanticFactProvider`
needs to construct `DeclaredUnit`/`MethodFacts`/`BehaviourReference`/`StateAccess` — the real
`ast` module, not regex; the whole design bet was whether *attribute syntax* (`self.value`)
can be correctly resolved to its *language meaning* (a property-getter invocation), which
a regex reader cannot reliably do.

## The one rule to keep — same as Track 1, extended

> **`extract_facts.py` is bounded and deliberately incomplete.** It recognises exactly the
> constructs each grant/rule commissioned, named at the call site (`# Grant G-...`), not a
> general Python semantic engine. No dynamic dispatch beyond `D-1`'s narrow `getattr(self,
> name)()` form, no `__getattr__`/`__getattribute__`, no multi-inheritance MRO beyond
> next-in-line `super()`.

Growing it means adding one more narrow, evidenced recognizer — never generalizing ahead of
a measured construct.

## How the neutrality claim was actually tested

Not by reading the code and asserting it looks right — by dumping raw `L3` facts for matched
PHP/Python fixture pairs and diffing every field, not just the final `LCOM4` value (a wrong
`L3`/`L4` can still coincidentally produce a correct metric — the precedent this whole
discipline exists to avoid). **11 structurally distinct mechanisms checked this way; 9
byte-identical, 2 showed a legitimate, expected difference**: `qualifierKind` differs when
the two languages spell the same relationship differently (PHP's `self::`, Python's bare
class name or `self.`) — every field `EdgeRules` actually branches on
(`targetUnitRelation`, `determinability`, `referenceMode`) was identical in every case, and
`L4`/`L5` output was byte-for-byte the same throughout.

```php
// the pattern worth remembering when adding a construct:
// dump BehaviourReference/StateAccess fields for both languages, not just observe()'s edges/value
foreach ($unit->methods as $m) {
    foreach ($m->behaviourReferences as $r) { /* print every field */ }
}
```

Full evidence: `docs/knowledgeos/reviews/2026-09-28-KOS-L3-parity-spot-check-5-rules.md`,
`...-round-2.md`, `...-language-neutrality-research-closure.md`.

## `D-1` — the one place the existing vocabulary was genuinely insufficient

`$this->$m()` (PHP) / `getattr(self, name)()` (Python): the receiver is known, the method
name is a runtime expression, not a literal. `BehaviourReference` cannot represent this —
`targetMethodName` is mandatory and non-nullable (`INV-L3-5`), and no legal string exists
(no sentinel, no source text). The fix is a **second, distinct, zero-field** `L3` fact kind
whose type alone carries the meaning — nothing to store, because the kind exists only for
the not-determinable case:

```php
final readonly class IndeterminateBehaviourReference {}
```

`EdgeRules::verdict()` gained one unconditional branch, checked first:

```php
if ($ref instanceof IndeterminateBehaviourReference) {
    return EdgeVerdict::exclude(ExclusionReason::NotDeterminable);
}
```

Both adapters emit it identically — PHP where the existing `$this->` branch's `T_STRING`
guard fails (`T_VARIABLE` instead), Python where `getattr(self, ...)` is used as a call's own
callee. **Metric-neutral by construction**: an excluded reference of any kind never becomes
an edge, confirmed both by a dedicated test and by real-corpus execution.

Formally incorporated into `expected.json._variant_decisions_pinned.
d1_computed_method_name_representation` (2026-09-28) — the contract's existing exclusion
text already required this; the incorporation names the representation, not a new rule.

## Extending it

- **A genuinely new Python construct**: add a narrow recognizer to `extract_facts.py`, named
  by its grant/rule, following the existing style (see `_is_getattr_self_dispatch`,
  `_own_class_name_qualifier` for the pattern). Never generalize beyond what's measured.
- **A genuinely missing `L3` concept** (not just a different way to spell an existing one):
  follow `D-1`'s shape — smallest possible schema, re-derive independently whether an
  existing invariant (`INV-L3-5` etc.) actually forbids the naive fix before proposing a new
  type, real-corpus-validate in both languages before calling it done.
- **Before adding a new adapter test comparing PHP/Python**: dump raw `L3` facts first, not
  just `observe()`'s edges/value — see "How the neutrality claim was actually tested" above.

## Pitfalls

- **A `qualifierKind` difference between languages is not automatically a bug.** Check
  whether every field `EdgeRules` branches on agrees before concluding anything — three
  independent occurrences of "different spelling, same semantics" have already been found
  and confirmed correct (`static_methods`, static-dispatch-from-instance, and the general
  pattern this implies for any future qualifier-kind difference).
- **`getattr(self, 'literal_string')()` and `getattr(self, name)()` are currently treated
  identically** (both become `IndeterminateBehaviourReference`) even though the literal-name
  case's target is technically knowable — disclosed, not a bug: zero real occurrences of the
  literal form exist in either measured corpus (`2026-09-28-KOS-D4-characterization-closure.md`),
  so this was not worth resolving speculatively.
- **`D-4`/`D-5`** (library-dispatch callable, e.g. `call_user_func`) were characterized and
  explicitly **not** built — zero genuine occurrences in either language's real corpus. Do not
  treat their presence in earlier proposals as authorization to implement them; check for new
  corpus evidence first.
- The Python subprocess boundary means **no shared state or exception type** with PHP —
  `PythonSemanticFactProvider` throws `RuntimeException` on a non-zero exit or JSON decode
  failure; it never partially trusts a malformed response.

**Traceability:** `2026-09-27-KOS-adapter-research-FROZEN.md` (the frozen branch this track's
earlier findings belong to) · `2026-09-28-KOS-D1-implementation-results.md` ·
`2026-09-28-KOS-D1-implementation-contract.md` · `2026-09-28-KOS-CONTRACT-NEUTRALITY-001-D1-schema-sufficiency-pass.md`
· `2026-09-28-KOS-python-semantic-implementation-backlog.md` (full construct-by-construct
inventory) · `2026-09-28-KOS-L3-language-neutrality-research-closure.md`.
