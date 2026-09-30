# OWD-4 — property target resolution (Q2): resolved semantically, not yet implemented

**Context:** OWD-3 follow-up · **Date:** 2026-09-27 · **FROZEN: 2026-09-28**
**Phase:** research only. **No production code changed.** `GraphBuilder`/`Lcom4`
untouched, no heuristic target resolution added, per explicit scope.

> **Status: FROZEN, no implementation authorized.** Confirmed clean before freezing:
> `git status` scoped to `Domain`/`Infrastructure` shows only the pre-existing,
> already-authorized R1/R5/OWD-3 diffs — nothing from this report. 148/148 Cohesion
> tests green. Held as a documented, characterized precision gap (§ Recommendation)
> until/unless edge-trail precision for the property read/write case becomes materially
> important enough to authorize separately.

---

## Research question

> What is the correct canonical representation of Python property semantics for
> KnowledgeOS's current cohesion contract — specifically, what does a source-level
> `self.value` reference denote when both a getter and setter exist?

## The key reframe

Before running the fixture matrix: is Q2 actually *ambiguous*, or only *unresolved by the
current adapter*? Checked directly — `extract_facts.py` never inspects `node.ctx`
(Python AST's Load/Store/Del marker) anywhere, for any construct, not only properties.

**Python's own descriptor protocol is fully deterministic here**: a plain read
(`self.value`) invokes only `fget`; an assignment (`self.value = x`) invokes only
`fset`. There is no runtime ambiguity in the language at all. The open question OWD-3
correctly left unresolved is therefore not "which is semantically correct" — it is
"the current adapter doesn't track the one piece of information (read vs. write context)
that would let it answer deterministically."

## Fixture matrix, real execution against the current (OWD-3-fixed) implementation

| Case | Result |
|---|---|
| 1. Getter only | Unambiguous already (no collision) — not re-tested, no change from OWD-2/3 |
| **2. Getter+setter, third method READS `self.value`** | `reader` connects to **both** `value#0` (getter) **and** `value#1` (setter) — **the setter edge is factually false**: a read never invokes `fset` |
| **2b. Getter+setter, third method WRITES `self.value = x`** | `writer` connects to **both** — **the getter edge is factually false**: a write never invokes `fget` |
| 5+6. Getter calls `helper_g`, setter calls `helper_s` (independently) | Clean, correct: `value#0→helper_g`, `value#1→helper_s`, zero cross-contamination — edges *originating from* a specific occurrence's own body are never ambiguous, only edges *targeting* a colliding name from outside are |
| 3. Setter-only via `value = property(fset=_set_value)` (no `@property` decorator) | **Separate, low-priority gap**: not recognized as a property at all (`property_names` is built purely from `@property`-decorated defs); `self.value = 5` becomes a plain, incorrect `StateAccess("value")` instead of a behavioural reference to `_set_value` |
| 9. Getter/setter, different state | Already correctly handled by OWD-3 (no forced connection unless something else links them) |
| 10. Getter/setter, same state | Already correctly handled by OWD-3 (real edge, not accidental) |

## Answering the ten-case table directly

- **Cases 1, 9, 10**: already resolved correctly by OWD-3. No further work.
- **Cases 2, 2b, 4, 7, 8** (all the same underlying question — what does a third
  method's read/write of a colliding property name denote): **resolved semantically**
  (read → getter only, write → setter only, by the language's own descriptor protocol),
  **not yet resolved in the implementation** (the adapter doesn't emit read/write context
  for any attribute access, so `GraphBuilder`'s "connect to every candidate" fallback is
  currently the only honest thing it can do with the information it's given).
- **Case 3**: a distinct, real, separate finding (property-without-decorator idiom is
  unrecognized) — not part of Q2, not pursued further here (rare relative to the
  decorator idiom; OWD-1's corpus census found `.setter` decorators but no evidence of
  the bare `property()` call form).
- **Cases 5, 6**: confirm the ambiguity is one-directional — only *incoming* references
  to a colliding name are affected; a getter/setter's own outgoing calls are never
  ambiguous.

## Classification

**Not C, not D, not E** in the sense of "the current representation is wrong" — the
underlying `Domain` vocabulary (`BehaviourReference`) does not yet carry the one fact
(read vs. write context) that would let target resolution be exact instead of
conservative. This is closest to **C — canonical representation insufficiency**, but
narrowly scoped: not a missing *concept*, a missing *field* on an already-correct
concept, directly analogous in shape to R1 (a real, minimal, evidence-justified L3
extension), not a new kind of vocabulary.

## What is NOT being proposed

Per this phase's explicit scope: no `GraphBuilder`/`Lcom4` change, no heuristic
resolution, no PHP-derived design. The correction, if authorized, belongs where the
information actually originates — **the adapter, at extraction time** — since only
`extract_facts.py` (Python: `node.ctx`) and, symmetrically, `PhpFactExtractor` (PHP has
no getter/setter-same-name idiom, so this may be a Python-only field in practice, not a
forced cross-language addition) can observe whether an access is a read or a write.

## Remaining uncertainty

The exact mechanism is not designed here (explicitly out of scope for this phase):
whether read/write context becomes a new `BehaviourReference`/`StateAccess` field
consumed by `GraphBuilder`'s existing `matchingLabels()`, or something else. That design
question, and whether it's worth the complexity given the real-corpus frequency (~20
occurrences, OWD-1), is for a future authorized slice, not decided here.

## Recommendation

Hold this as a documented, characterized, real (not merely theoretical) precision gap.
It is narrower and less urgent than R1/R3/R5/OWD-3 (those produced wrong `LCOM4` values;
this produces extra, factually-false edges in the evidence trail while `LCOM4` itself may
often come out the same, since the getter/setter are typically already connected via
shared backing-field state regardless). Recommend authorizing implementation only if/when
edge-trail precision for this specific construct becomes materially important — not
automatically.

**No code changed. Stopping here per phase scope — no implementation without a separate,
explicit authorization.**

**Traceability:** `extract_facts.py` (confirmed no `ctx` handling anywhere) · four new
fixtures run against the real, unmodified (OWD-3) implementation, this session ·
`2026-09-27-KOS-PYTHON-OWD3-identity-model-resolution.md` §6 (the question this report
answers) · `2026-09-27-KOS-PYTHON-OWD3-implementation.md` (the fix this builds on,
unmodified by this report).
