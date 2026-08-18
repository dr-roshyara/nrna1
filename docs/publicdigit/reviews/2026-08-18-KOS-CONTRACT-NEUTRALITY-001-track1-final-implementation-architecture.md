# `KOS-CONTRACT-NEUTRALITY-001` Track 1 — **final implementation architecture** (PHP adapter + PHP-specific `L4`/`L5`)

**Date:** 2026-08-18 · **Type:** 🟡 **ARCHITECTURE RECONCILIATION — PROPOSAL. Authorizes nothing, retires nothing, modifies nothing.**
**Reconciles:** the accepted implementation architecture (`adc5c8e8`, accepted `c6c4f984`) with the Track-1 **fit assessment** (`440fe7b8`) and the PO/ARB's **PHP-LCOM4 bounded exception**.

> **Producing process, self-declared, NOT attestable** (`INV-ATTR-2`/`G-2`): `claude-code-session:1c8b041b`. **Prior position:** authored the parsing evaluation, the accepted implementation architecture and the fit assessment. **`R-34`/`P-2`: must not verify or accept this.**

## ⚠️ Two record discrepancies, disclosed before the content — neither resolved here

| | Discrepancy | Consequence |
|---|---|---|
| **D-1** | 🔴 **The PHP-LCOM4 bounded exception is NOT in the workflow record.** Verified: 34 transitions, 20 grants, **no grant or amendment carries it.** It exists as a delivered PO/ARB act in conversation | **It supersedes parts of `G-KOS-CONTRACT-IMPL-TRACK1` (the Python leg) and reinstates parts of `AMD1`.** **Registering it is a Governance act** and must precede implementation authorization |
| **D-2** | 🔴 **No architecture assignment exists for this reconciliation.** The open assignment is `S3-implementation-track1-php-adapter` (role `implementation`, mutation owner since seq 34) | This document is produced **under the implementation assignment**, on the PO/ARB's reaffirmed direction. **Per `R8` a reconciliation of this kind belongs in an architecture assignment; registering one is a Governance act.** Raised twice, reaffirmed twice, proceeding and recording |

**The AD-1…AD-5 dispositions in §2 are recorded as the PO/ARB delivered them, verbatim. ⛔ They are not registered, and this document does not register them.**

---

# 1 · Current authoritative decisions

| | Decision | Class |
|---|---|---|
| Architecture **D** · conformance boundary **`L3 → L5`** · **B3** extraction · **C-as-completion** | ✅ **DECIDED** |
| **13.3** stratified qualifier rules incl. the bucket ruling · **13.5** four silences · **13.7** node+edge+metric | ✅ **DECIDED** |
| Analysis scope = **one PHP source file** (`OQ-2`) · **`OQ-1`** declaration-path identity ratified (`AMD4`) | ✅ **DECIDED** |
| **Declared specification + expected evidence is normative; implementation agreement is diagnostic** | ✅ **DECIDED** |
| **PHP is the sole current implementation language** (`AMD1`) | ✅ **DECIDED** |
| ⭐ **PHP-specific `L4`/`L5` — an EXPLICIT BOUNDED EXCEPTION for current scope** | ✅ **DECIDED by the PO/ARB · 🔴 NOT REGISTERED (D-1)** |
| **`OPEN-1`** — the `L3` vocabulary is PHP-derived in its first version; generality untested (`AMD4`) | ⬜ **OPEN** |
| One common language-neutral `L4`/`L5` engine; future non-PHP bindings | 🔵 **FUTURE ONLY** |

**Scope of claim, unchanged and not weakened by the exception (`AMD2`):** current work proves **the PHP binding conforms to the model**; ⛔ **it does not prove the model is neutral.**

---

# 2 · `AD-1` … `AD-5` reconciliation

> **Dispositions delivered by the PO/ARB and recorded verbatim. Architecture states the consequence of each; it decides none of them.**

## `AD-1` — `OQ-4` ✅ **DECIDED**
> *"No PHP→Python transport is required in current scope. Mark future non-PHP binding/runtime transport as FUTURE."*

**Consequence:** the boundary the fit assessment found unauthorized **no longer exists in current scope.** ⛔ **No transport is designed here** — no JSON, stdin/stdout, files, HTTP, protobuf or any other mechanism. **`OQ-4` returns to FUTURE, where `AMD2` placed it.**

## `AD-2` — `L3` transport contract ✅ **DECIDED**
> *"Not required for the current PHP-only implementation. The `L3` model remains an in-process boundary."*

⭐ **Consequence, and it is the cleanest result of this reconciliation: the accepted architecture's §14.1 — *"B3 runs IN-PROCESS… no subprocess, no token dump, no serialized transport"* — is CORRECT AGAIN.** **The fit assessment found §14.1 contradicted; the contradiction is resolved by removing the Python leg, not by amending the design.** ✅ **No amendment to §14.1 is required or proposed.**

## `AD-3` — architecture consistency ✅ **DECIDED**
> *"PHP Adapter → L3 → PHP L4/L5. No Python analytical runtime is required."*

**Consequence — three sections of the accepted design are REINSTATED rather than superseded:**

| Accepted design | Status after `AD-3` |
|---|---|
| **§11.1** — `connectedComponents()` **MOVES UP to `L5` unchanged**, on the PHP side | ✅ **reinstated** — it was correct |
| **§11.2** — `lcom4_collector.py` **out of current implementation scope** | ✅ **reinstated** |
| **§14.1** — in-process, no serialized transport | ✅ **reinstated** |
| **§1.1** — scope-of-claim assuming one binding | ✅ **reinstated** |

> ⭐ **`INFERRED`, and worth stating plainly: the accepted architecture did not need correcting. The Track-1 authorization's Python leg was the deviation, and the fit assessment is what proved it unworkable.** The reconciliation is largely a **confirmation**, plus the exception framing in §3.

## `AD-4` — reuse of the Python capability ✅ **DECIDED**
> *"Do NOT force reuse."*

**Classified honestly, from the fit assessment's line-cited evidence (`OBSERVED`):**

| Part | Disposition |
|---|---|
| scanner / extraction path (`blank_noise`, `find_classes`, `find_methods`, the four regexes) | 🔴 **NOT reusable for current Track 1** — it is the layer **B3 supersedes**, and it consumes PHP source |
| PHP-specific semantic logic (`analyse_method`, incl. `own_as_written` at `:186`) | 🔴 **NOT reusable** — fuses extraction with the `L4` decision and collapses the distinctions 13.3 forbids merging |
| `connected_components` / generic graph logic | 🟡 **conceptually reusable · FUTURE implementation material · does NOT determine current architecture** |
| `interpretation`, the three literal sets | ✅ semantics survive as accepted rules |

> ⭐ **And a fact that makes the question moot for current scope (`OBSERVED`): the PHP side ALREADY has the union-find** — `Lcom4Collector.php:142–178`, which §11.1 moves up to `L5` unchanged. ⇒ **nothing needs to be taken from the Python file at all.** ⛔ **No code is copied automatically, and none is copied here.**

## `AD-5` — location of `L4`/`L5` ✅ **DECIDED**
> *"Current answer: PHP L4/L5. Future common Python L4/L5 remains a future architectural direction only."*

---

# 3 · The current PHP exception architecture — **what is and is not excepted**

```
PHP source (one file)
      ↓  PHP Adapter — PHP-specific extraction (B3)          ← PHP ubiquitous language
      ↓
   L3 FACT MODEL  ══════ conformance boundary ══════          ← neutral ubiquitous language
      ↓
   PHP L4 semantic interpretation                             ← implemented in PHP
      ↓
   PHP L5 LCOM4
      ↓
   Declared conformance evidence
```

> ## ⭐ **The distinction the whole exception turns on, and it must not be blurred:**
> ### **"PHP-specific `L4`/`L5`" means IMPLEMENTED IN PHP. It does NOT mean SEMANTICALLY PHP-DEPENDENT.**
>
> ✅ **Excepted:** `L4`/`L5` is written in PHP, in the same process and repository as the binding, rather than as a separate language-neutral engine.
> ⛔ **NOT excepted — every one of these still binds:** `L4` may not inspect PHP syntax · no source text, spelling, tokens, AST nodes or parser objects reachable from `L4`/`L5` · provenance stays separate · the `L3` vocabulary stays the neutral model · conformance stays declared.
>
> **If the exception were read the other way, Architecture D would be abandoned rather than realized.**

## 3.1 🔴 The risk this exception creates, named because the evidence already shows it

**The fit assessment established (`OBSERVED`) that the Python collector's failure mode was *context fusion inside one function*: `analyse_method` performs extraction **and** the `L4` decision, so the boundary has nowhere to exist.**

> ### **Co-locating the binding and the engine in one language recreates exactly that temptation.** One repository, one language, one process, **two bounded contexts.** **The gates in §14 exist for this and for nothing else.**

---

# 4 · PHP Adapter boundary 🟡 PROPOSED

**Responsibility:** PHP source → `L3` facts. **Nothing else.**

| Stage | Responsibility |
|---|---|
| `T1` tokenize | the language's own lexer — grammar-exact |
| `T2` declare | `DeclaredUnit`s and body extents; **every declaration emitted, including interfaces** |
| `T3` members | methods declared in each unit's own body |
| `T4` observe | `StateAccess` and `BehaviourReference` sites |
| `T5` classify | `QualifierKind` · `TargetUnitRelation` · `ReferenceMode` · `AccessMode` · `Determinability` · `UnitIdentity` |

⛔ **The adapter does NOT:** decide an edge · decide unit eligibility · compute connected components · compute LCOM4 · author or consult expected evidence.
✅ **PHP knowledge that lives here and nowhere else:** tokens · attributes · heredoc/nowdoc · interpolation · PHP-mode boundaries · the identifier charset · the five name kinds · the `use` alias map · `enum`/`trait`/`interface` keywords · nullsafe.

---

# 5 · `L3` fact model 🟡 PROPOSED — unchanged from the accepted design

**`DeclaredUnit`** { `UnitKind` · `UnitIdentity` (declaration path, `AMD4`) · `DeclaredName` } · **`MethodDeclaration`** { `MethodIdentity` · `hasBody` } · **`StateAccess`** { `PropertyName` · `AccessMode` } · **`BehaviourReference`** { `TargetMethodName` · `QualifierKind` · `TargetUnitRelation` · `ReferenceMode` · `AccessMode` · `Determinability` } · **`Provenance`**, joined one-way by `factId`, **never reachable from `L4`**.

## 5.1 ⛔ The four states that must never collapse

| State | Meaning |
|---|---|
| **not the own class** | determinable, and it denotes a different unit → `ExclusionReason::NotTheAnalysedUnit` |
| **not determinable** | the target cannot be determined within the scope → `ExclusionReason::NotDeterminable` |
| **another class** | the same as the first, and it must carry the same reason — never a third silent bucket |
| **excluded by semantic rule** | `OutOfFrame` (`parent`) · `AliasedSpelling` (LIMITATION) · `CallableNotInvocation` |

> ⭐ **The fit assessment measured all four collapsing into a single `continue` in the Python model (`:182`, `:185`, `:187`).** **Here they are distinct values of a closed `ExclusionReason` enum, each reported in evidence.** ✅ **Merging them requires editing the enum, which is a contract change.**

---

# 6 · PHP `L4` semantics 🟡 PROPOSED

**Unit eligibility (13.5):** `Class` · `AnonymousClass` · `Enum` · `Trait` → eligible · `Interface` → **seen, recorded, NOT eligible.**

**Edge rule table (13.3), first match wins** — unchanged from the accepted design §7.2: `CallableReference` → excluded · `ComputedTarget`/`Undetermined` → `NotDeterminable` · `ParentKeyword` → `OutOfFrame` · `AliasedName` → `AliasedSpelling` (LIMITATION) · `self`/`static` → include · `InstanceReceiver` → include · unqualified/fully-qualified/relative **denoting the analysed unit** → include · the same **denoting another unit** → `NotTheAnalysedUnit` · `QualifiedName` → `NotTheAnalysedUnit`.

**Node/edge construction:** nodes = eligible unit's methods minus `__construct`/`__destruct`; **state edges** from shared `PropertyName` (both access modes count — 13.5); **behaviour edges** from included references whose target is a node here, otherwise retained with `TargetNotDeclaredHere`.

⛔ **`L4` receives `Fact` only.** ✅ **Testable in PHP: `L4` unit tests construct `L3` facts directly and run with no source, no tokens and no AST in the process.** *(The accepted design's layer-C test survives the exception intact — this is what keeps it real.)*

---

# 7 · PHP `L5` LCOM4 🟡 PROPOSED — ⛔ no new metric

Connected components over `(node set, edge set)` by union-find; `0` nodes ⇒ `0`; interpretation strings unchanged. **`L5` receives only the graph** — no unit kind, no qualifier, no source.
✅ **`Lcom4Collector.php:142–178` already implements this and moves up unchanged** (accepted §11.1). ⚠️ **It must additionally expose the graph, not only the count** — 13.7 requires node and edge sets as evidence.

---

# 8 · Conformance architecture ✅ DECIDED

**Normative authority:** declared specification **+** expected `L3` facts **+** expected `L4` graph **+** expected `L5` metric.
⛔ **The existing PHP calculator is NOT the specification** (Decision 1). ⛔ **Implementation agreement is diagnostic.** ⛔ **Final-metric equality alone is insufficient.**

**Three assertion levels per fixture**, as accepted §9. **Nullsafe is asserted against the declaration**, because differential comparison cannot detect a shared blindness.

⚠️ **`OPEN` — the erosion risk restated:** expected evidence **must be authored from the specification**, never generated from the implementation. **If generated, the oracle silently becomes the implementation** — the failure this whole work item exists to prevent. **See Gate 4: it passes by rule, not by structure.**

---

# 9 · Legacy calculator migration 🟡 PROPOSED

**`Lcom4Collector.php` in its current form is `LEGACY / TRANSITIONAL`.** ⛔ **Not retired. No retirement performed or authorized here.**

**Retirement criteria — all five:** the new PHP path conforms to the declared specification · **independent verification passes** (a separate actor and assignment) · declared evidence is complete, covering all nine decided rules including nullsafe · migration acceptance occurs · **a separate Governance authorization permits retirement.**

⚠️ **During the compatibility period the legacy and new paths will disagree by design** — the new path implements decided rules the legacy one predates (measured: the legacy side misses enums and traits entirely, misses nullsafe, and collides anonymous identities). **Divergence is expected and must not be read as regression;** that expectation belongs in the migration authorization.

---

# 10 · Python capability disposition

**`lcom4_collector.py`: out of current implementation scope** (`AMD1` §11.2, reinstated by `AD-3`). ⛔ **Not reusable for Track 1** (`AD-4`, §2). ⛔ **Not retired — `AMD1`: *"out of current implementation scope is not deletion, and any disposition of it is a separate act."*** ⛔ **No code copied.**

🔵 **FUTURE:** its `connected_components` remains conceptually relevant if a common engine is ever built. **It does not determine current architecture, and current architecture does not depend on it (Gate 5).**

---

# 11 · Language-neutral EKS boundary ⭐

## 11.1 `OBSERVED` — the anti-corruption layer already exists, and it is in the right place

```php
// scripts/observations/ObservationRuntime.php:48
'subject'       => $obs['class'],
```

> **The collector emits `class`; `ObservationRuntime` renames it to `subject` before it enters the evidence and rules layer.** ✅ **`subject` is a neutral concept; `class` is not.** **The translation point is correct and already implemented.**

## 11.2 ⚠️ But the upstream vocabulary is PHP-shaped — and 13.5 has already broken it

**The collector's observation key is `class`.** Under 13.5 the analysed unit may be an **enum**, a **trait** or an **anonymous class**. ⇒ **`class` is now factually wrong as a field name, not merely inelegant.**

🟡 **PROPOSED consequence, not performed:** the emitted record should carry a neutral **unit identity + unit kind**, and `ObservationRuntime`'s mapping to `subject` stays as the ACL. ⛔ **Changing the emitted record changes `expected.json`'s shape — that is `G-KOS-CONTRACT-ARTIFACT-UPDATE` territory (AUTHORIZED, UNEXERCISED) and is not done here.**

## 11.3 What must never cross into language-neutral EKS

⛔ **`PhpClass` / `PhpMethod` as universal EKS concepts · `PhpToken` outside the adapter · PHP AST nodes outside the adapter · raw PHP source outside the adapter.**
✅ **EKS consumes:** facts · evidence · conformance · provenance · verification · decisions · architecture · knowledge artifacts — **and `subject`, which it already does.**

## 11.4 The context map, stated in DDD terms — **unchanged by the exception**

```
  PHP Language Binding  ──conformist to──▶  Cohesion Analysis  ──published language──▶  EKS / Evidence
  (PHP ubiquitous lang)                     (neutral ubiquitous lang)        ▲          (subjects, evidence)
   tokens, name kinds,                       units, methods,                 │
   attributes, heredoc                       references, edges          ACL: ObservationRuntime:48
```

> ⭐ **The exception changes the DEPLOYMENT TOPOLOGY, not the CONTEXT MAP.** **Two bounded contexts that now share a runtime and a language are still two bounded contexts.** **The `L3` model is the published language between them, and the binding is conformist to it — the binding must produce what the model defines, never the reverse.**

---

# 12 · Future language-binding direction 🔵 FUTURE ONLY

```
🔵 FUTURE  <language> binding  ──▶  the SAME L3 Fact Model  ──▶  a common L4/L5 engine
```

**A future binding is a separate governed work item.** ⛔ **Not designed · no transport created · current Track 1 does not depend on it (Gate 6).**
✅ **What FUTURE work would add, and the only thing it would add: when a second binding produces the same `L3` facts, the neutrality claim of §1 becomes empirically demonstrable instead of architectural.**
⚠️ **`OPEN-1` bears on this** (`AMD4`): the vocabulary is PHP-derived in its first version; a future binding **must test** whether it generalizes, and any generalization is a **contract amendment**, not a model gap.

---

# 13 · Implementation sequence 🟡 PROPOSED

```
1  PHP L3 fact model            types, closed vocabularies, invariants INV-L3-1..7
2  PHP B3 adapter               T1–T5 producing L3 facts
3  PHP L4 semantic layer        eligibility + the 13.3 rule table + graph construction
4  PHP L5 LCOM4                 union-find (moves up from Lcom4Collector.php:142–178) + graph exposure
5  declared expected evidence   authored FROM THE SPECIFICATION — separate act under the artifact-update grant
6  conformance tests            layers A–E; layer C runs with no PHP in the process
7  comparison against legacy    diagnostic only; divergence expected
8  delivery to independent verification
```

⛔ **No Python implementation is added at any step.** **Each step needs its own authorization.**

---

# 14 · Architectural gates

| | Gate | Result | Basis |
|---|---|---|---|
| **1** | All PHP parsing knowledge inside the adapter | ✅ **PASS** | §4 — the PHP-knowledge list is exhaustive and adapter-local |
| **2** | `L3` contains no parser-specific objects | ✅ **PASS** | §5 — closed vocabularies and identities only; provenance separate |
| **3** | `L4`/`L5` contains no raw PHP source or parser objects | ✅ **PASS** | §6 — `Fact`-only input; **provable by the synthetic-fact test, which survives the exception** |
| **4** | Declared expected evidence independent of implementation output | ⚠️ **PASSES BY RULE, NOT BY STRUCTURE** | §8 — nothing mechanically prevents generating evidence from the implementation. **The weakest gate; named as such** |
| **5** | The implementation does not depend on the old Python collector | ✅ **PASS** | §10 — and trivially so: the PHP side already has the union-find |
| **6** | Extensible for future bindings without introducing them now | ✅ **PASS** | §12 — the seam is the `L3` model plus module separation; nothing future is built |

**No gate fails. Gate 4's weakness is recorded rather than smoothed** — it is the same erosion risk the accepted design flagged, and the exception does not change it.

---

# 15 · Open issues

| | Issue | Class |
|---|---|---|
| **D-1** | 🔴 **The PHP-LCOM4 bounded exception is not registered.** It supersedes the Track-1 grant's Python leg and reinstates `AMD1`'s position | **must precede implementation authorization** — Governance |
| **D-2** | 🔴 **No architecture assignment exists for this reconciliation** | Governance |
| **`OPEN-1`** | The `L3` vocabulary is PHP-derived in its first version; generality untested (`AMD4`) | ⬜ **OPEN**, carried forward |
| **`O-EKS-1`** | The emitted observation key is `class`; under 13.5 the unit may be an enum, trait or anonymous class ⇒ **factually wrong**. The ACL at `ObservationRuntime:48` is correct; the upstream vocabulary is not | 🟡 **PROPOSED** — artifact-update grant territory |
| **`O-EVID-1`** | Gate 4 passes by rule only. **Expected evidence generated from the implementation makes the implementation the oracle** | ⬜ **OPEN** — needs an authoring discipline in the specification |
| **`OQ-3`** | The artifact-update step remains **AUTHORIZED and UNEXERCISED**; no assignment created | routed, not open here |

---

# 16 · Implementation handoff

**What the Implementation Engineer receives when authorized:** a fixed pipeline (§3) · an adapter boundary with an exhaustive PHP-knowledge list (§4) · the `L3` model with its four non-collapsible states (§5) · the `L4` rule table (§6) · the `L5` source already present in the repository (§7) · the conformance authority (§8) · six gates, one of them known-weak (§14).

**What is NOT yet available and must precede a start:** registration of the exception (**D-1**) · a corrected implementation authorization reflecting **PHP `L4`/`L5`, not Python** · a separate act for the declared expected evidence (`OQ-3`).

---

**RECONCILIATION DELIVERED · STOPPING.**
⛔ **No production code · no PHP collector modified · no Python collector modified · no fixtures · no `expected.json` · no Python transport · no Java · no architecture reopened · no 13.3 or 13.5 reopened · no verification · no acceptance · no legacy retirement.**
**Next actor: 🔵 Governance / PO-ARB — register the exception, review this reconciliation, and issue the separate implementation authorization. Then Implementation begins Track 1.**

**Traceability:** accepted implementation architecture `adc5c8e8` (accepted `c6c4f984`) §1.1 · §7.2 · §9 · §11.1 · §11.2 · §14.1 · fit assessment `440fe7b8` (the line-cited Python evidence) · `G-KOS-CONTRACT-IMPL-TRACK1` · `G-KOS-CONTRACT-IMPL-ARCH` + `AMD1`…`AMD4` · `G-KOS-CONTRACT-ARTIFACT-UPDATE` · Decisions 13.1 · 13.3 · 13.5 · 13.7 · Decision 1 · `OQ-1` · `OQ-2` · `OPEN-1` · `scripts/observations/Lcom4Collector.php:142–178` · `scripts/observations/ObservationRuntime.php:48` · `scripts/observations/lcom4_collector.py:182,185,186,187,201` · `R-34`/`P-2` · `G-1` · `R8` · `INV-ATTR-2`.
