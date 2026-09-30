# `KOS-CONTRACT-NEUTRALITY-001` — D-1 `IndeterminateBehaviourReference` schema sufficiency pass

**Work item:** `KOS-CONTRACT-NEUTRALITY-001` · **Date:** 2026-09-28
**Assignment:** lane `S5-architecture-pass1-evidence-reconciliation`, research/architecture
pass (no new grant — nothing tracked is modified)
**Performer, self-declared, not attestable (`INV-ATTR-2`/`G-2`):**
`claude-code-session:e8f324f1-25ea-4281-a95a-483401312e3e`
**Follows:** `2026-09-28-...-V3-governance-decision-brief.md` §8, which disclosed that the
adopted `D-1` vocabulary deliberately deferred its exact schema.

> ⛔ **Non-authoritative research only.** No `expected.json` change · no Domain/adapter/test
> change · no governance transition · no PO/ARB submission. Verified before and after:
> `git status --short scripts/ tests/ .claude/runtime` shows no change.

---

## 1 · Executive finding

The adopted `D-1` semantics are more fully specified by the underlying architecture
determination than the 2026-09-04 proposal's own "downstream architecture-review detail"
disclaimer suggested — most of a minimal schema is derivable directly from already-decided
text. But this pass finds **one genuinely new gap** the prior passes did not surface: the
draft invariant that was meant to govern this whole area (`INV-L3-8`, §11 of the
determination) **was never adopted** — it exists only inside a document explicitly marked
`PROPOSAL ONLY`, and the 2026-09-04 `ADOPTED` act never mentions it. Separately, this pass
finds the current source code (a 2026-09-27 comment in `PhpFactExtractor.php`, unrelated to
V-3 governance) already treats `self::$m()`/`static::$m()` as within "D-1's existing
boundary" — a scope claim no governance document actually rules on. **Classification: B —
partially specified.** Full reasoning in §11-§12.

## 2 · Authoritative D-1 reconstruction

| Question | Evidence | Established answer |
|---|---|---|
| Why is `D-1` in scope? | Decisions-registration 2026-08-19, `D-1`: *"an observed behavioural reference whose target method name is computed and therefore not determinable... must remain distinguishable from 'reference not observed'"* | The contract's own never-merge clause (`expected.json._variant_decisions_pinned.intra_class_calls`, 2026-08-16) already requires seen-and-excluded ≠ never-seen; `D-1` settles that this applies to `$this->$m()`. |
| What source construct does it represent? | Determination §1.1-1.3: `$this->$m()`, confirmed by direct extractor reading (this pass and the prior sufficiency pass) to be currently **unextracted entirely** — the `T_STRING` guard at `PhpFactExtractor.php:523` fails for a `T_VARIABLE` member token, so today's binding produces **no fact of any kind**. | A method invocation whose receiver is known (denotes the analysed unit) but whose **method name token is a variable, not a literal**. |
| Why can ordinary `BehaviourReference` not represent it? | Determination §2.3: *"`targetMethodName` is a mandatory non-nullable string with no value. Filling it requires a sentinel (forbidden by `INV-L3-5`) or the variable's source text (forbidden by `INV-4`/`INV-L3-7`)."* Independently re-verified this pass against the **registered** (not paraphrased) `INV-L3-5` text (§4.4 of the implementation-architecture registration): *"All six `BehaviourReference` attributes are always present; no default, no 'unknown'"*, enforced by *"constructor totality."* A nullable/optional `targetMethodName` on some instances is precisely the "default/unknown" case this already-registered invariant forbids — not merely the proposal's opinion. | Confirmed independently: no legal string exists, and the type's own registered invariant forbids relaxing the field to admit one. |
| What semantic information must be preserved? | Determination §2.4, three-state rule: NOT OBSERVED / OBSERVED·DETERMINABLE / OBSERVED·NOT DETERMINABLE, none collapsible into another. | The fact of observation itself, and the declaring method's identity (supplied by `GraphBuilder`'s enclosing loop, not by the fact — confirmed by re-reading `GraphBuilder.php`, which labels exclusion entries by the enclosing `$label`, not by anything read off the reference). |
| What information must explicitly remain unknown? | Proposal §1.2: *"carries **no** `targetMethodName` field of any kind — there is nothing legal to put there."* | The target method's identity — **absent as a field**, not present-as-null. |
| What information must NOT be invented? | `INV-4`/`INV-L3-7` (no provenance — no offset, token, or source lexeme); `D-1`'s own text (*"must NOT use fake method names, sentinel strings, raw PHP source, parser objects"*). | No source-text fallback, no sentinel, no synthetic name. |
| What is its L4 consequence? | Proposal §1.3, re-verified this pass by reading current `EdgeRules.php` in full: `EdgeRules::verdict()` accepts only `BehaviourReference`; a new fact kind has **no consumer** without an added rule. Proposed rule: always `exclude(NotDeterminable)`. | A new, trivial, unconditional `EdgeRules` branch — confirmed mechanically sufficient (fixed by construction, no runtime condition to evaluate). |
| What is its L5 consequence? | Determination §2.6, re-verified this pass: metric-neutral — an excluded reference never becomes an edge, so `Lcom4::compute()` is unaffected regardless of representation chosen. | No L5 consequence of any kind. |
| What provenance requirement is already stated? | `INV-4`/`INV-L3-7`, unchanged, applies to this fact kind exactly as to `BehaviourReference` — proposal §1.2: *"carries no offset, token, or source lexeme."* | None beyond what every L3 fact already forbids; no *new* provenance requirement is introduced. |

**Distinguishing the three layers, as instructed:**
- **Adopted semantics** (2026-09-04, binding as architecture-of-record): in-scope disposition;
  new fact kind required (not optional-field, not stated-limitation); fixed `NotDeterminable`;
  no `targetMethodName` field; mandatory-total in whatever it carries; no provenance; trivial
  `EdgeRules` rule.
- **Proposed implementation vocabulary**: the specific name `IndeterminateBehaviourReference`
  (a naming choice, not itself load-bearing).
- **Deferred design decisions, per the proposal's own text**: "attribute count/exact schema."
  This pass narrows, but does not eliminate, that deferral — see §5, §12.

## 3 · Why `D-1` requires a separate fact kind — independently re-derived, not merely cited

**Question asked exactly as specified:** is `D-1` semantically a normal behaviour reference
with an unknown target, or a different kind of observation that cannot legally inhabit the
ordinary `BehaviourReference` schema?

**Answer: the latter, and this pass confirms it independently of the proposal's own
reasoning**, using only the **registered** invariant text (not the proposal's citation of it):

1. `INV-L3-5` (registered, §4.4 of the implementation-architecture document, re-read this
   pass): *"All six `BehaviourReference` attributes are always present; no default, no
   'unknown'"* — this is a **closed, already-decided rule about `BehaviourReference`
   specifically**, not a design preference. A version of `BehaviourReference` where
   `targetMethodName` is sometimes absent/null is a version where the totality invariant no
   longer holds for that type — full stop, independent of any opinion about elegance.
2. There is no third option evidenced anywhere in the record: the determination's own §8
   enumerates exactly three admissible answers ((a) optional field, (b) new fact kind, (c)
   stated limitation), and (a) is barred by (1) above, (c) is barred by `D-1`'s own disposition
   (already decided: IN SCOPE, meaning it must be represented, not declared unrepresentable).
   **Only (b) survives**, by elimination against already-decided text, not by preference.

**Conclusion, independently confirmed:** `D-1` is not "a normal behaviour reference with an
unknown target." A normal behaviour reference, by its own registered invariant, cannot have
an unknown target at all — that is precisely what `INV-L3-5` rules out. `D-1` is a
categorically different kind of observation — "a behaviour was referenced, but nothing about
*which* one may be legally recorded" — and DDD-wise this is a distinction of kind (a
different domain concept), not of degree (a variant of the same concept with one field
relaxed). This matches, and independently corroborates, the proposal's own conclusion in
§1.2 — this pass arrives at the same place by re-deriving from the registered invariant
directly rather than trusting the proposal's citation of it.

## 4 · Minimum information content

| Candidate field | Classification | Evidence | Why |
|---|---|---|---|
| A field naming the target method | **NOT JUSTIFIED — explicitly forbidden** | Proposal §1.2; `INV-4`/`INV-L3-7`; `D-1`'s own text barring sentinels/source text | No legal value exists; the field's *absence* is what preserves totality, not a null value in a present field. |
| A stored `Determinability` attribute | **DERIVED, not REQUIRED as a stored field** | Proposal §1.2 itself: *"a fixed determinability marker... it does not need a variable determinability attribute"* — re-verified this pass: since the fact kind's very existence at a site already means "not determinable" (that's the only reason this kind is used instead of ordinary `BehaviourReference`), the value is reconstructable from the type tag alone. | Storing a field whose value is invariant across every instance of a type is redundant; `EdgeRules`'s new branch can dispatch on the fact's *class*, needing no field read. This is a genuine minimality finding: the proposal's own text already says this, but does not draw the REQUIRED/DERIVED distinction explicitly — this pass makes it explicit. |
| The declaring method's identity | **NOT REQUIRED on the fact itself** | Re-read `GraphBuilder.php` this pass: every exclusion entry's `method` field is populated from the **enclosing loop's `$label`**, not from anything read off the `BehaviourReference`/`IndeterminateBehaviourReference` instance. `MethodFacts` already groups behaviour references by their declaring method (`$method->behaviourReferences`) before `GraphBuilder` ever inspects an individual reference. | Confirmed structurally: no existing or plausible new fact kind needs to self-identify its declaring method, because the container (`MethodFacts`) already establishes that. |
| A provenance/`factId`-equivalent field | **OPTIONAL / PROVENANCE — not REQUIRED by `D-1`'s own semantics** | See §5 — analyzed separately, as instructed. | `D-1`'s adopted semantics (§2 above) say nothing about provenance beyond the standing `INV-4`/`INV-L3-7` prohibition that already applies to every L3 fact. Nothing in the adopted record makes provenance-identity part of `D-1`'s *own* requirement. |
| A qualifier-form field (distinguishing `$this->$m()` from `self::$m()`/`static::$m()`) | **GENUINELY OPEN — flagged, not resolved** | See §12. The governance record's `D-1` discussion is worded around `$this->$m()`; current source (`PhpFactExtractor.php:574`, a 2026-09-27 engineering comment unrelated to V-3 governance) already treats `self::$m()`/`static::$m()` as "D-1's existing boundary" without a governance act saying so. | Not fabricated as a requirement — reported as a scope question no adopted document actually answers. |

**Minimum schema this evidence supports, stated plainly:** an `IndeterminateBehaviourReference`
fact kind that (a) exists as a distinct type (its type tag *is* its determinability
information — no field needed), (b) carries no target-naming field of any kind, and
(c) carries nothing else the adopted record requires. Everything beyond that is either
provenance (§5, optional) or the open qualifier-scope question (§12, unresolved) — **not**
part of the semantic kernel `D-1` itself establishes.

## 5 · `factId` analysis

**The two questions kept explicitly separate, as instructed:**

```
D-1 L3 fact
       |
       +-- semantic identity   <- what D-1's adopted disposition actually requires (§2-4)
       |
       +-- provenance identity
                    |
                    +-- exclusion evidence   <- the SEPARATE Model A/B/C question
```

**Does `D-1` itself require a `factId`?** No evidence found that it does. The adopted `D-1`
proposal (§1.2) lists exactly four constraints on the new fact kind's shape (no
`targetMethodName`; fixed `NotDeterminable`; mandatory-total in whatever it carries; no
provenance/offset/token/lexeme) — a `factId` is not among them, and the proposal was written
2026-09-04, **three weeks before** the exclusion-evidence-record question (`factId`'s
candidate role in Model C) was first raised, 2026-09-27. There is no possible reading under
which the adopted `D-1` record anticipated or required it.

**Is `factId` only relevant to the later exclusion-evidence question?** On current evidence,
yes. `BehaviourReference::$factId` exists today (`?string $factId = null`) precisely as *"a
separate provenance record joined one-way"* (its own docblock) — it is a general-purpose,
optional provenance hook available to *any* L3 fact kind, not something `D-1`'s semantics
specifically call for. Whether the new `IndeterminateBehaviourReference` kind should *also*
expose a `factId`-shaped field is entirely a downstream consequence of **which** exclusion-
evidence model (A/B/C) is eventually chosen — if C, yes, trivially (for consistency with
every other fact kind); if A or B, no, it is not needed at all.

**Conclusion — do not conclude `D-1` requires `factId`; do not conclude it is irrelevant
either:** the correct statement is that `factId`'s relevance to `D-1` is **entirely
conditional** on a decision (`A`/`B`/`C`) this pass and its predecessor both explicitly
declined to make. `D-1`'s own semantic kernel (§4) does not require it.

## 6 · Invariant applicability

| Invariant | Applies to `D-1`? | Why | Consequence |
|---|---|---|---|
| `INV-L3-1` (closed vocabulary, no free text) | ✅ Yes | Every field `IndeterminateBehaviourReference` would carry (there being none beyond the type tag itself, per §4) is either absent or closed-vocabulary by construction — nothing free-text is proposed anywhere in the record. | No new obligation; automatically satisfied by the minimal schema in §4. |
| `INV-L3-2` (identity stability) | ⚪ Not directly — this invariant governs `UnitIdentity`, a different concept (§4.3 of the registration) | `D-1` concerns a behaviour-reference *site*, not a declared unit's identity. | Not applicable; no consequence. |
| `INV-L3-3` (kind completeness — every declaration is emitted) | ⚪ Not directly — governs `UnitKind` emission, a different layer | Same reasoning as `INV-L3-2`. | Not applicable. |
| `INV-L3-4` (determinability separation — `NotDeterminable` only where genuinely undeterminable) | ✅ Yes, and satisfied by construction | `D-1`'s fact kind exists *only* for genuinely-undeterminable sites (per its own adopted disposition) — it cannot be misused for a determinable-but-different-unit case, because that case is already representable as an ordinary `BehaviourReference` with `TargetUnitRelation::DenotesOtherUnit`. | No new obligation. |
| `INV-L3-5` (totality of `BehaviourReference`'s six attributes) | ❌ **Does not apply to the new fact kind at all** — it is registered specifically over `BehaviourReference`'s constructor (§4.4 of the registration, re-confirmed §3 above) | `IndeterminateBehaviourReference` is, by design, a **different type**; `INV-L3-5`'s literal text and enforcement mechanism (constructor totality) name `BehaviourReference` specifically. | **Important, disclosed correction of an implicit assumption in the 09-04 proposal itself**: the proposal's §1.2 says the new kind must be *"mandatory-total in whatever it does carry, per `INV-L3-5`"* — but `INV-L3-5`, read literally, does not extend to this new type at all. The proposal's intent (totality is a good general design principle) is sound; its citation of `INV-L3-5` specifically as the governing rule is imprecise. This does not change the recommended design — a new closed-vocabulary type should still be internally total, as ordinary good L3 design — but it means this is a **design convention being followed voluntarily**, not a registered invariant being obeyed. Worth flagging precisely because §4 above already found the same imprecision pattern in the exclusion-evidence-record question. |
| `INV-L3-6` (no verdicts in L3) | ✅ Yes | `IndeterminateBehaviourReference` must not itself carry an `ExclusionReason` or any edge/verdict information — confirmed nothing in the adopted proposal proposes this. | No new obligation; automatically satisfied. |
| `INV-4`/`INV-L3-7` (provenance non-decisional / no source leak) | ✅ Yes, explicitly restated by the proposal itself (§1.2) | Same rule that already binds every L3 fact. | No new obligation beyond the standing rule. |

## 7 · L4 consequence

Verified directly against the **current** `EdgeRules.php` (re-read in full this pass,
unchanged from the prior sufficiency pass — same hash logic):

- **Does `IndeterminateBehaviourReference` reach `EdgeRules` today?** No — `EdgeRules::verdict()`'s
  signature accepts only `BehaviourReference`; a fact of any other type is not callable
  through it without a new method or an added type-check branch.
- **Does it have an L4 verdict?** Not today; the proposal's §1.3 correctly identifies this as
  a needed addition, not something "downstream" that can be silently assumed.
- **Is it excluded before target matching?** By design (fixed `NotDeterminable`), yes — no
  target-matching step (`GraphBuilder::matchingLabels()`) would ever run for it, since it
  carries nothing to match.
- **What evidence must remain observable?** Per Decision 13.7 and `CohesionGraph`'s own
  docblock, only that a site was seen and excluded, with its reason — exactly what §2-4 above
  already established as the semantic kernel.
- **Does it require a target at all?** No — confirmed, §4.
- **Does it require a target placeholder?** No — explicitly forbidden (§2, §4).
- **Does it need a separate `EdgeRules` branch?** Yes — confirmed, since `verdict()`'s current
  signature cannot accept it; some dispatch mechanism (a new method, a union-type parameter,
  or a type-check) is required. **Which exact mechanism is not specified anywhere** — a small,
  genuinely open implementation-design question, distinct from the schema question this pass
  addresses, noted for completeness rather than resolved here (implementation is out of scope
  for this pass regardless).
- **Does `ExclusionReason::NotDeterminable` already express the required semantics?** Yes —
  confirmed by reading `ExclusionReason.php`: the existing case's docblock (*"the target
  cannot be determined in this scope"*) already covers this meaning exactly; no new
  `ExclusionReason` case is implied or needed.

## 8 · L5 consequence

Re-confirmed this pass, no new finding beyond the determination's own §2.6: metric-neutral by
construction, because an excluded reference of any kind never contributes an edge to
`GraphBuilder`'s output, and `Lcom4::compute()` operates only on the node/edge sets. No node
effect, no edge effect, no metric effect. The only effect is on the **exclusion-evidence**
list itself — which is exactly the separate question in §9-§10 and the prior reconciliation
pass, not this pass's concern.

## 9 · Candidate schemas

Evidence-supported candidates only — no field invented merely to produce alternatives:

```
Candidate S1 — minimal (this pass's finding, §4)
  IndeterminateBehaviourReference: no stored fields beyond what the type tag itself conveys.
  Determinability recovered from the type; no targetMethodName; no provenance.

Candidate S2 — minimal + provenance identity
  S1, plus an optional ?string $factId = null field, mirroring BehaviourReference's own
  existing (currently-unconsumed) field, added purely for structural consistency across
  L3 fact kinds — NOT because D-1's own semantics require it (§5).

Candidate S3 — minimal + explicit qualifier-form metadata
  S1 (or S2), plus a closed-vocabulary field distinguishing which own-unit-denoting
  receiver form triggered the fact (e.g. an analogue of QualifierKind restricted to
  {InstanceReceiver, SelfKeyword, StaticKeyword}) — ONLY IF the open scope question in
  §12 (does D-1 cover self::$m()/static::$m(), not only $this->$m()?) is answered YES
  and PO/ARB wants that distinction preserved in evidence, rather than collapsed.
```

| Candidate | Semantic completeness | Contract impact | Provenance | L4 compatibility | Open questions |
|---|---|---|---|---|---|
| **S1** | Complete for everything `D-1`'s adopted disposition actually requires (§2-4) | Smallest possible amendment surface | None (matches "no provenance" reading of the proposal literally) | Trivial fixed-exclude rule, no field reads needed | None beyond §12's scope question, which S1 does not attempt to answer either way |
| **S2** | Same as S1 semantically; adds a hook for the *separate* exclusion-evidence-record question | Same contract surface as S1 for `D-1` itself; only matters if Model C is later chosen for the evidence record | Optional, structurally consistent with `BehaviourReference` | Same as S1 | Whether adding an unused field now is worth the consistency, given §5's finding that it is not required by `D-1` itself |
| **S3** | Only if §12's scope question resolves to "D-1 covers self::/static:: too, and the distinction must survive as evidence" | Larger — a new closed-vocabulary field is itself a contract-visible schema element | Same as S1/S2 | Slightly more L4 logic (a branch per receiver form, still trivially fixed-exclude) | Directly depends on §12, unresolved |

**No ranking, no selection**, per instruction.

## 10 · Separation from exclusion-evidence Model A/B/C

```
D-1 L3 schema  (this pass — candidates S1/S2/S3, §9)
        |
L4 verdict     (a new EdgeRules branch, always exclude(NotDeterminable) — §7, uncontested)
        |
excluded-evidence representation  (Model A/B/C — 2026-09-28 reconciliation pass, NOT this pass)
```

`D-1`'s L3 schema and the exclusion-evidence record's representation are **independent
design axes**: any of S1/S2/S3 is compatible with any of A/B/C (S2's optional `factId` is
what Model C would *use*, but choosing S2 does not commit to Model C, and choosing S1 does
not rule Model C out — a `factId`-bearing field could be added to `Indeterminate
BehaviourReference` later, at the point Model C is actually adopted, without revisiting this
schema question). This pass does not conflate the two questions at any point above.

## 11 · Sufficiency classification

**B — partially specified.**

**What is already established by existing adopted evidence (no further decision needed):**
the disposition (in scope); the necessity of a new, distinct fact kind rather than a relaxed
`BehaviourReference` (independently re-confirmed, §3); the absence of any target-naming field;
the non-necessity of a stored determinability field (§4, a minimality finding this pass adds);
the non-necessity of provenance/`factId` as part of `D-1`'s own semantics (§5); full
invariant-applicability (§6); the L4 consequence in principle (a trivial, unconditional
exclude rule — §7); the L5 consequence (none — §8).

**What remains a genuine, undecided design choice (§12):**
1. Whether `D-1`'s scope, as actually intended, covers only `$this->$m()` (the governance
   record's explicit framing) or also `self::$m()`/`static::$m()` (current source code's own,
   ungoverned assumption) — this determines whether Candidate S3's qualifier-form field is
   needed at all.
2. The exact mechanism by which `EdgeRules::verdict()` would accept a second fact type (new
   method, union parameter, or type-check branch) — an implementation-design detail, correctly
   out of scope for a schema pass, noted so it is not silently assumed trivial.

Neither gap blocks understanding `D-1`'s semantic kernel (§4 is complete and evidence-backed).
Both gaps block writing a final, ratifiable schema without a further, narrow decision.

## 12 · Exact remaining architecture question

Two, kept separate, phrased so neither presupposes an answer:

> **(i)** Does `D-1`'s adopted disposition cover only `$this->$m()`, or does it also cover
> `self::$m()`/`static::$m()` — as current, ungoverned source code (`PhpFactExtractor.php:574`)
> already assumes? This is a scope question, not a representation question — answering it
> does not require choosing S1/S2/S3 or A/B/C, but the answer determines whether S3's extra
> field is ever needed.

> **(ii)** [Implementation-design, not schema — noted, not escalated]: what is the cleanest
> mechanism for `EdgeRules::verdict()` to dispatch on two fact types? This does not need a
> governance decision; it is ordinary engineering judgment at implementation time, listed here
> only so it is not mistaken for a resolved detail.

**Neither question is sent to PO/ARB by this pass.** Question (i) is flagged for the human's
attention because it is a genuine governance-adjacent scope gap the existing record does not
address (a piece of production code's comment currently asserts a scope decision no governed
act ever made); question (ii) is explicitly not that kind of question at all.

## 13 · STOP condition

**Established, not re-litigated:** `D-1` requires a new, distinct L3 fact kind (independently
re-confirmed, §3); its minimal schema (S1) carries no fields beyond its own type identity
(§4); `factId`/provenance is conditional on the separate Model A/B/C question, not required
by `D-1` itself (§5); `INV-L3-5` literally does not extend to the new fact kind, correcting an
imprecise citation in the adopted proposal itself (§6) — mirroring, for a second time, the
same "registered scope vs. code-comment paraphrase" gap this thread's prior pass found for the
exclusion-evidence record.

**Genuinely undecided:** whether `D-1`'s scope covers `self::$m()`/`static::$m()` (§12-i);
which schema candidate (S1/S2/S3) to adopt, contingent partly on that scope answer and partly
on the still-separate Model A/B/C choice.

**What must NOT happen before those are resolved:** no `IndeterminateBehaviourReference` class
created; no `EdgeRules` branch added; no `expected.json` amendment drafted as though a schema
were final; no assumption that S1 is "the" answer merely because it is minimal — minimality is
a property this pass found, not a preference this pass is asserting.

---

## 14 · Traceability

`2026-09-28-...-V3-governance-decision-brief.md` §8 (the disclosed gap this pass answers) ·
`2026-09-28-...-V3-decision-gate-reconciliation.md` (the `INV-L3-5`/`INV-L3-6` registered-text
finding, reused and extended here to a second question) · `2026-09-28-...-V3-exclusion-
evidence-sufficiency-pass.md` · `2026-09-04-...-V3-D1-D4-D5-ADOPTED.md` · `2026-09-04-...-
V3-D1-D4-D5-proposal.md` §1 (read in full, both this pass and the reconciliation pass) ·
`2026-08-19-...-V3-architecture-determination.md` (read in full this pass — the primary new
source: §1-§2, §4, §7-§9, §11 draft `INV-L3-8`) · `2026-08-19-...-v3-decisions-registration.md`
(+Amendment 1) · `2026-08-18-...-implementation-architecture.md` §4.2/§4.4 (canonical
`BehaviourReference` attribute table and invariant registry, re-read this pass) · source
re-read this pass: `BehaviourReference.php`, `EdgeRules.php`, `ExclusionReason.php`,
`GraphBuilder.php`, `PhpFactExtractor.php` (lines 519-630, incl. the `self::$m()` comment at
line 574) · grep confirming `INV-L3-8` exists only in the unadopted draft document, nowhere
else in the repository · `S5-architecture-pass1-evidence-reconciliation` lane record.
