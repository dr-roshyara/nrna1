# `KOS-CONTRACT-NEUTRALITY-001` — V-3 targeted continuation: **`D-1` / `D-4` / `D-5` representation proposal**

**Work item:** `KOS-CONTRACT-NEUTRALITY-001` · **Date:** 2026-09-04
**Assignment:** lane `S5-architecture-pass1-evidence-reconciliation` (same actor, separate
authority) · **Grant:** `G-KOS-CONTRACT-V3-TARGETED-CONTINUATION`
**Performer, self-declared, not attestable (`INV-ATTR-2`/`G-2`):**
`claude-code-session:e8f324f1-25ea-4281-a95a-483401312e3e`

> ⛔ **Status: 🟡 PROPOSAL. It decides nothing. V-3 remains OPEN.** This resolves only the
> three items this grant names — `D-1` representation, `D-4` enumerated list, `D-5`
> vocabulary — and only as a recommendation for PO/ARB adoption. `D-2`/`D-3` (dynamic
> property access) are **already decided** (out of current scope) and are not reopened,
> touched, or restated here beyond what is needed to keep `D-1`'s dependency order intact.
> **No implementation, no contract (`expected.json`) change, no fixture change, no
> application/runtime code change.**

---

## 0 · What this proposal starts from (not re-derived — cited)

The exact current standing, per `2026-08-19-...-v3-decisions-registration.md` (as amended by
its Amendment 1 and restored by R1), is not in dispute and is not re-litigated here:

| Item | Standing |
|---|---|
| `D-1` disposition | ✅ **DECIDED: IN SCOPE** — an observed behavioural reference whose target method name is computed and not determinable must be recorded, distinguishable from "not observed" |
| `D-1` **representation** | 🔴 **OPEN — a contract decision** (`A1.3`/`F-D1`) — this is what §1 below resolves |
| `D-2`/`D-3` (dynamic property access) | ✅ **DECIDED: out of current scope / not applicable** — **not touched by this grant** |
| `D-4` principle | ✅ **DECIDED: bounded enumeration only** — the **list itself does not exist** — this is what §2 below resolves |
| `D-5` constraint | ✅ **DECIDED: no PHP-API-derived vocabulary** (e.g. no `QualifierKind::CallUserFunc`) — the **vocabulary itself not chosen** — this is what §3 below resolves |

The V-3 architecture determination (`2026-08-19-...-V3-architecture-determination.md`) is
**the qualified evidentiary basis** for all three (its own independence gate discharged,
`AMD1`) — its analysis is adopted where cited; its own three-way framing of `D-1` and its
drafted `D-4`/`D-5` material are the starting point, not re-derived from nothing.

---

## 1 · `D-1` — representation of an observed, not-determinable computed method name

### 1.1 The three admissible options, and why two are rejected

The determination named three admissible resolutions and barred sentinels/source-text
outright. Evaluated against the standing constraints (`INV-L3-5` totality; the never-merge
policy; `D-1`'s already-decided disposition):

| Option | Assessment |
|---|---|
| **(a) Make `targetMethodName` optional/absent** | ⛔ **REJECTED.** `INV-L3-5` exists precisely to prevent an optional/defaultable field — every consumer of `BehaviourReference` would need a null-check this invariant was designed to make unnecessary. Loosening the type for one construct reopens the totality question for the whole fact kind, not just this case — a much larger change than `D-1` asks for. |
| **(c) Declare it a stated LIMITATION (unrepresented)** | ⛔ **REJECTED — inadmissible given `D-1`'s own disposition.** `D-1` is **already decided: IN SCOPE**, requiring the site to be recorded as observed-and-excluded. Declaring it unrepresented would silently reopen that disposition, which this grant does not authorize (§0). |
| **(b) Add a distinct L3 fact kind** | ✅ **PROPOSED.** Does not touch `BehaviourReference`'s totality, does not reopen `D-1`'s disposition, requires no sentinel and no source text. See §1.2. |

### 1.2 The proposed fact kind

**`IndeterminateBehaviourReference`** — a new, closed-vocabulary L3 fact kind, distinct from
`BehaviourReference`, used exactly when a method invocation targets a behaviour of the
analysed unit whose identity cannot be determined because the **member name itself** (not
the qualifier/class) is computed.

**Why a new kind rather than a new value on the existing one:** the determination's own
central finding is that the distinguishing factor is *which part is computed* (§1.3 of the
determination) — `$c::b()` computes the **qualifier**, leaving the method name a literal, so
`BehaviourReference` can carry it (`targetMethodName='b'`) plus `QualifierKind::ComputedTarget`.
`$this->$m()` computes the **name itself** — there is no literal value admissible for that
field under `D-1`'s own prohibition on fake names and sentinels. A field that can never
legally hold a value for this case is not a field this fact should have. **A different
fact kind, not a different value, is the DDD-correct move**: the domain distinction
("I know what behaviour this is" vs. "I know a behaviour is referenced but not which one")
is a distinction of *kind*, not of *degree*, and L3's job is to make exactly that
distinction unable to collapse (§2.4 of the determination, the three-state rule).

**Minimal shape, PROPOSED (attribute count/exact schema is a downstream architecture-review
detail; the constraints below are what `D-1` actually requires and are not optional):**
- carries **no** `targetMethodName` field of any kind — there is nothing legal to put there,
  and the absence of the field (not a null value *in* the field) is what keeps totality intact;
- carries a **fixed** determinability marker equal to `NotDeterminable` (this fact kind exists
  only for the not-determinable case — it does not need a variable determinability
  attribute, because a determinable computed-name site is representable today as an ordinary
  `BehaviourReference` with a literal target, which is not this case);
- is **mandatory-total** in whatever attributes it does carry, per `INV-L3-5` — it inherits
  the invariant, it does not exempt itself from it;
- carries no offset, token, or source lexeme (`INV-4`/`INV-L3-7`).

### 1.3 L4 consequence, addressed because `D-1` cannot be silently "downstream implementation"

The determination itself flagged (§2.5) that `EdgeRules::verdict()` only accepts
`BehaviourReference` — a fact of the new kind would have **no L4 consumer** without an
addition. **Proposed:** `EdgeRules` gains a verdict rule for `IndeterminateBehaviourReference`
that **always** returns `exclude(NotDeterminable)` — trivial, because the kind's
determinability is fixed by construction (§1.2). This is metric-neutral for the same reason
the determination already established for the general case (§2.6): an excluded reference
never becomes a co-touch edge, so no LCOM4 value changes. **No new L4 *decision* is
introduced — the rule is mechanical once the fact kind exists.**

### 1.4 What this does not resolve

This is a **representation** proposal, not an implementation. Whether/how `PhpFactExtractor`
emits this new fact kind, what the binding needs to recognise a computed-member-name site,
and any test/fixture coverage are implementation and evidence work **not authorized by this
grant** (§0, containment rule) — they follow only after PO/ARB adopts a representation.

---

## 2 · `D-4` — the enumerated library-dispatch list

**Proposed: adopt the determination's §12 wording**, which is already narrowly scoped,
evidence-grounded, and consistent with `D-4`'s decided principle ("only explicitly
enumerated and evidence-supported dispatch mechanisms... do not define an open-ended
capability"):

- **In scope, closed list:** `call_user_func`, `call_user_func_array`, matched **only** in
  the literal `[$this, 'name']` form (`$this` as the first element, a literal string as the
  second).
- **Out of scope, declared as a limitation, not silently unhandled:** any callable assembled
  at runtime, held in a variable/property, built by concatenation, or returned from a call;
  `Closure::fromCallable`/`Closure::call`/`Closure::bind`; `__call`/`__callStatic`; container,
  event-dispatcher, reflection, or framework dispatch; any non-literal target name.
- **Representation:** an in-scope dispatch is recorded on the **existing** `BehaviourReference`
  type — the target method name **is** a literal and representable today (§3.3 of the
  determination) — with determinability **`NotDeterminable`** (the name is visible; that this
  particular dispatch reaches that method is not established). **Observed-and-undeterminable,
  never omitted.**
- **Knowledge boundary, held firm:** the binding may use the enumerated function names **as
  names only** — it must not resolve library runtime behaviour, load library code, or infer
  dispatch semantics from a function's name. This is what keeps the two independent change
  clocks (PHP syntax vs. library surface, §5 of the determination) from being coupled.

**Why adopt rather than re-draft:** the scope evidence (`call_user_func([$this,...])` occurs
**zero times** in `app/` and in the ten golden fixtures) argues for the narrowest defensible
list, which is exactly what §12 already is — broadening it now would not be grounded in any
evidence this pass has, and the determination's own caution ("do not broaden merely because
it is technically easy") still applies. **Nothing in this pass's evidence changes that
assessment**, so no independent re-derivation is offered where the existing analysis already
holds.

## 3 · `D-5` — vocabulary for the library-dispatch case

**The constraint that must be satisfied:** no PHP-API-derived name (`D-5`'s own text rules
out e.g. `QualifierKind::CallUserFunc`).

**The three options the determination raised, evaluated:**

| Option | Assessment |
|---|---|
| Reuse `QualifierKind::ComputedTarget` | ⛔ **REJECTED.** Misdescribes the case: for library dispatch, the **target name is a literal**, not computed — what's uncertain is whether the *dispatch mechanism* actually reaches it (§3.2 of the determination: *"more statically determinate than `$this->$m()`, not less"*). Reusing this value would conflate two different reasons for `NotDeterminable` under one label. |
| Treat as `InstanceReceiver` + `NotDeterminable` (no new case) | ⛔ **REJECTED.** Loses the fact that this required *library knowledge* to recognise at all — a plain `InstanceReceiver` reference implies ordinary syntactic resolution, which understates what the binding had to know (an enumerated dispatch idiom) to emit the fact. |
| **Add a new case** | ✅ **PROPOSED**, named to satisfy `D-5`'s language-neutrality constraint (§3.1). |

**Proposed name: `QualifierKind::ExplicitCallableDispatch`.** Rationale: the domain concept
this names is *"the target is reached through an explicitly-passed callable reference,
recognised by an enumerated, language-neutral dispatch idiom"* — not a PHP API name, and a
concept with direct analogues in other languages (e.g. attribute-based dynamic dispatch via
an explicit callable/functor argument to a higher-order call), which is what a future
non-PHP binding would need to map onto, not `call_user_func` specifically.

**Honest interaction with `OPEN-1`, not glossed over:** the standing record already notes the
`L3` vocabulary is PHP-derived in its first version. This proposal satisfies `D-5`'s literal
constraint (no PHP function name used as a vocabulary term) without claiming to resolve
`OPEN-1` generally — that is a separate, broader standing item, not something a
three-decision targeted continuation can or should absorb.

---

## 4 · What this proposal does not do

⛔ **No decision taken** — PO/ARB adoption required for all three items. **`D-2`/`D-3` not
reopened, not restated as decisions, not touched.** No `expected.json` change · no fixture
change · no application/runtime code change · no `IndeterminateBehaviourReference` class
created · no `EdgeRules` rule added · no `QualifierKind::ExplicitCallableDispatch` case added
· no test written · **Track-1 acceptance not reopened** · Architecture D not redesigned ·
V-3's lane not closed · `G-KOS-CONTRACT-V3-ARCH`/`AMD1` untouched · the artifact-update gate
(`G-KOS-CONTRACT-ARTIFACT-UPDATE` + amendments) untouched — dynamic-member expected evidence
remains blocked exactly as that gate already states, until this proposal (or a different one)
is adopted **and** "incorporated into the authoritative specification," which is a further,
separate act.

**Next actor: PO/ARB** — adopt, amend, or reject each of the three independently; none
depends on the others being decided the same way.

**Traceability:** grant `G-KOS-CONTRACT-V3-TARGETED-CONTINUATION`
(`2026-09-04-...-V3-targeted-continuation-AUTHORIZATION.md`) · decisions-registration
`2026-08-19-...-v3-decisions-registration.md` (+Amendment 1, +Restoration R1) · V-3
architecture determination `2026-08-19-...-V3-architecture-determination.md` (§8, §11, §12)
· Pass-1 evidence determination `2026-09-04-...-PASS-1-evidence-determination.md` ·
`INV-L3-5` · `INV-4`/`INV-L3-7` · `G-KOS-CONTRACT-ARTIFACT-UPDATE` (+`AMD1`/`AMD2`/`AMD3`) ·
`OPEN-1` (`AMD4`).
