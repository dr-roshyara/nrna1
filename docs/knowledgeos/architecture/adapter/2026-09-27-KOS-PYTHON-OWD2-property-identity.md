# OWD-2 — Python-native property getter/setter identity investigation

**Context:** OWD-1 follow-up · **Date:** 2026-09-27
**Phase:** characterization only. **No production code changed.**

> Note on "PHP vs. language-neutral": there is only ONE `Domain` (L3/L4/L5) implementation
> in this system — written in PHP because the KnowledgeOS *tool itself* is a PHP
> application, fed by both the PHP and Python adapters. "Is this PHP-shaped?" therefore
> means "was this shared implementation only ever exercised against PHP input, where the
> scenario in question is structurally impossible?" — not "is there a separate PHP model
> to compare against." That distinction is investigated directly below (§7).

---

## 1. Python semantic observation

`@property` + `@x.setter` is examined on its own terms, not reduced immediately to
"duplicate method name":

- The getter and setter are two genuinely separate Python function objects, each with its
  own code, own local scope, own potential dependencies.
- At the source level, both are written as `def value(...):` — the SAME identifier is
  reused because that is how the decorator protocol composes them: `@value.setter`
  literally calls the `property` object bound to the name `value` (created by the
  preceding `@property`-decorated `def value`) and reassigns `value` to a NEW property
  object combining both functions.
- At runtime, after the class body finishes executing, the class has exactly ONE
  attribute named `value` — a single `property` descriptor object holding both functions
  (`.fget`, `.fset`). The two source-level `def value` blocks collapse into one runtime
  member.
- Neither function is independently callable as an ordinary method (`a.value()` does not
  call either directly) — both are reached only through the attribute-access protocol
  (`a.value` triggers `fget`; `a.value = x` triggers `fset`).
- The getter and setter CAN have entirely independent bodies and dependencies (fixture 3
  below) — nothing in the language forces them to be related beyond sharing a name.

## 2. Candidate semantic models (not chosen yet)

- **A — two executable nodes**, distinctly tracked (`value#get`, `value#set`), independent
  cohesion participants.
- **B — one logical property node**, getter and setter treated as facets of one thing;
  their facts are unioned.
- **C — two nodes plus an explicit belongs-to-property relation**, distinct but linked.

## 3–4. Minimal fixtures, Python-native L3/L4/L5 (real execution, unmodified adapter/Domain)

| Fixture | L3 (per-method facts) | L4 (nodes/edges) | L5 |
|---|---|---|---|
| 1. Getter only | `value`: stateAccesses=[_value] | nodes=[value], edges=[] | 1 |
| 2. Getter+setter, same backing field | both `value` entries: stateAccesses=[_value] | nodes=[value,value], edges=[] | **1** |
| 3. Getter/setter, DIFFERENT state (`_a`/`_b`) | entry 0: [_a]; entry 1: [_b] | nodes=[value,value], edges=[] | **1** |
| 4. Getter calls another method | entry 0: behaviourRef→compute; entry 1: [_value] | nodes=[value,value,compute], edges=[[value,compute,behaviour]] | 1 |
| 5. Setter calls another method | entry 0: [_value]; entry 1: behaviourRef→notify | nodes=[value,value,notify], edges=[[value,notify,behaviour]] | 1 |
| 6. No shared dependency + unrelated method | both `value` entries: nothing; `unrelated`: [_z] | nodes=[value,value,unrelated], edges=[] | **2** |
| 7. Getter+setter+third method sharing `_value` | (as fixture 2) + `other`: [_value] | nodes=[value,value,other], edges=**[[value,other,state]] (only ONE edge)** | 1 |

**Fixture 3 is decisive**: getter and setter share ZERO state (`_a` vs. `_b`, no overlap
at all) and produce ZERO edges — yet `LCOM4 = 1`, not the 2 that "two genuinely unrelated
nodes" would produce under a naive count. **Fixture 6 confirms it independently**: two
completely empty nodes plus one unrelated node give `LCOM4 = 2`, not 3 — the two empty
nodes are being counted as ONE component with no edge ever connecting them.

## 5. Identity analysis (Step 4 — inspecting the actual implementation, only after the Python evidence above was gathered)

Read directly, `Lcom4.php`:
```php
$parent = array_combine($nodes, $nodes);
```
`$nodes` is a PHP **list** (duplicates allowed) — but `array_combine` builds an
**associative array keyed by node name**. `array_combine(['value','value'], [...])`
silently collapses to ONE key. Every node sharing a name is therefore given the SAME
union-find root from the very first line, before any edge is ever processed — this alone
explains fixture 3 and fixture 6 without any edge logic at all.

Separately, read `GraphBuilder.php`'s state-edge loop:
```php
if (isset($propertyOwner[$property])) {
    if ($propertyOwner[$property] !== $name) { $edges[] = [...]; }
} else {
    $propertyOwner[$property] = $name;
}
```
This is keyed by **method name**, not method identity/instance. In fixture 2/7, the
getter claims ownership of `_value` first; when the setter (same name `'value'`) later
touches `_value`, the check `$propertyOwner['_value'] !== $name` compares `'value' !==
'value'` — **false** — so the setter's touch is silently treated as "the same owner
touching its own property again," suppressing what should be a legitimate edge between
two physically distinct methods. This is why fixture 7 shows only **one** edge
(`value`↔`other`), not two: the setter's independent claim on `_value` was invisibly
absorbed into the getter's.

**Two distinct, compounding defects, not one**, and neither implements any of Models
A/B/C: (1) `Lcom4`'s union-find silently merges same-named nodes regardless of any real
edge; (2) `GraphBuilder`'s ownership tracking silently suppresses a same-named method's
own state touch as if it were the other's. Together they produce behavior that
*coincidentally* resembles Model B in some cases (fixture 2, 7) but actively
contradicts it in others (fixture 3, where two nodes with disjoint state are forced
together — Model B would still correctly reflect the union `{_a, _b}` on one merged
node, not silently hide the fact that nothing is shared).

## 6. Is unique node identity a fundamental requirement, or an inherited artifact?

Neither `Lcom4.php` nor `GraphBuilder.php`'s docblocks say anything about name
uniqueness — it is not a stated design decision anywhere in this codebase. It is an
**unexamined, undocumented invariant** the implementation silently depends on
(`array_combine`'s key-collapsing behavior; name-keyed ownership tracking).

## 7. Only now, PHP as comparative evidence

Checked directly: PHP enforces unique method names within a class at the language level
— `Fatal error: Cannot redeclare A::value()`. **PHP cannot produce this scenario, ever.**
This is not "PHP has a different, correct answer for this case" — PHP has *no case at
all* to compare against. The invariant was safe for every PHP input not because anyone
designed around PHP's rules, but because PHP structurally cannot violate it. This is the
first construct in the whole investigation with **no PHP analogue whatsoever** to test
against — confirmed, not assumed.

## 8. Classification: **D**

Precise wording, not "PHP-shaped code": **a shared `Domain` implementation contains an
identity invariant (node identity = node name) that was never explicitly specified
anywhere, and happened to hold for all PHP input, but is not language-neutral.** Not
because PHP's semantics were deliberately encoded into `Lcom4`/`GraphBuilder`, but
because the shared implementation was **only ever exercised against inputs where unique
node identity holds by construction**, and
Python's property idiom is the first adapter output that violates it. The bug was always
latent; nothing before this investigation could have triggered it.

## 9. Materiality

Confirmed, real, and non-trivial: fixture 3 (genuinely unrelated getter/setter bodies)
produces `LCOM4 = 1` when they share nothing at all — a real undercount, not merely an
imprecise evidence trail. ~20 `.setter`-style decorators occur across the OWD-1 corpus
census (real, professionally-written code), so this is not a purely theoretical construct.

## 10. Remaining uncertainty

Which of Models A/B/C should replace the current accidental behavior is **not decided
here** — and it is a genuine open question, not merely an implementation choice:
- Model A requires `GraphBuilder`/`Lcom4` to stop using bare names as connectivity/
  ownership keys (a real, identity-bearing node concept, e.g. an index or synthetic
  identity, alongside the reported name) — a `Domain`-level change.
- Model B requires a decision about what it MEANS to "union" two methods' facts, and
  whether that is even a coherent cohesion-analysis operation, or a category error
  (a "property" isn't a method; treating it as one merged node conflates two different
  kinds of thing).
- Model C requires a new relation concept entirely, which the "no new vocabulary unless
  falsification demands it" discipline (established since R1) would need separate,
  strong justification for.
- It is not yet established whether this question is even Python-specific in its
  eventual answer, or whether it exposes something about method-identity-as-string more
  generally that could recur elsewhere.

## 11. Whether an implementation change is justified

**Not decided here.** The defect is real and material (§9), but per the explicit
instruction, no implementation is authorized by this characterization — the model
question (§10) must be resolved first, deliberately, not defaulted into by whichever of
A/B/C happens to be easiest to code.

**No production code changed. Stopping here — no further experiment started
automatically.**

**Traceability:** `Lcom4.php`, `GraphBuilder.php` (read directly, decisive evidence) ·
seven fixtures run against the real, unmodified `PythonSemanticFactProvider` and shared
`AnalyseCohesion`/`Domain` pipeline (this session) · direct PHP-language confirmation
that duplicate method names are a fatal error · OWD-1 report (real-corpus frequency,
same directory).
