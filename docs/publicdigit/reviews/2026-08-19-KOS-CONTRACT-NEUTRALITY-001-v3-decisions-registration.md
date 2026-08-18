# Decision Registration — **V-3 decisions `D-1` … `D-5`** and the amended artifact-update gate

**Registered by:** Governance, on the delivered PO/ARB act · **2026-08-19**
**Work item:** `KOS-CONTRACT-NEUTRALITY-001` Track 1 · **Subject:** independent-verification finding `V-3`
**Recording process, self-declared, NOT attestable** (`INV-ATTR-2`/`G-2`): `claude-code-session:1c8b041b`.

> ⚠️ **Disclosed, because it bears on what this act may and may not do.** This process authored the Track-1 binding (`4c6c1dac`) and the earlier V-3 determination (`99aeac7c`) that Governance classified as **evidence, not authority**. It is **barred by name** from `S4-architecture-v3-determination` (seq 38: *"REQUIRED: a process OTHER THAN the Track-1 implementer"*). **Recording a delivered decision is a GOVERNANCE act and is not that assignment** — the recording duty is discharged by whichever process holds the work. ⛔ **Nothing below is decided by this process; every disposition is the PO/ARB's, transcribed.**

> **Reading disclosed for correction:** the act is headed `RECORD:` and closes *"STOP after recording the five decisions"*, and its `D-3` asserts `D-2`'s outcome as settled fact (*"Because D-2 is OUT OF CURRENT SCOPE"*). **It is therefore read as PERFORMATIVE**, notwithstanding the words *"Recommended disposition"*. **If any disposition was intended as a recommendation awaiting selection, say so and this registration is corrected.**

---

## Premises verified BEFORE recording — not assumed

**Governance checks the act's own stated grounds rather than transcribing them.** All `OBSERVED`:

| | Premise asserted by the act | Verification |
|---|---|---|
| **P-a** | *"dynamic method behavior is explicitly addressed by the contract"* | ✅ **TRUE** — `_variant_decisions_pinned.intra_class_calls` names *"dynamic method names (`$this->$name()`)"* verbatim |
| **P-b** | *"dynamic property access is not explicitly present in the pinned semantic decisions"* | ✅ **TRUE** — **no pinned decision mentions dynamic property access at all**; `_variant` says only *"edges = shared instance variable OR intra-class call"*, with no determinability language on the state side |
| **P-c** | *"no current golden fixture exercises it"* | ✅ **TRUE** — none of the ten fixtures contains a dynamic property access |
| **P-d** | `D-1`'s required semantics are expressible | 🟡 **PARTLY** — `QualifierKind::ComputedTarget` and `Determinability::NotDeterminable` **already exist in `L3`**; **the target-name half does not** — see `⚠️ F-D1` below |

---

# `D-2` — dynamic property access `$this->$p` · ✅ **DECIDED: OUT OF CURRENT SCOPE / DECLARED LIMITATION**

> **Recorded verbatim:** *"Dynamic property access is not currently part of the cohesion contract. It must not be silently interpreted as either an observed deterministic property access or an observed `NotDeterminable` access. Future inclusion requires a separate contract decision."*

**Rationale as given, and verified (P-a, P-b, P-c).**
⛔ **The prohibition is two-sided and that is the substance of it:** the absence may not be read as a *deterministic* access **nor** as a *not-determinable* one. **It is outside the contract, which is a third thing.**
**Recorded first because `D-3` depends on it.**

# `D-3` — state-access representation · ✅ **DECIDED: NOT APPLICABLE TO CURRENT SCOPE**

> **Recorded:** *"Do not create a new `StateAccess` determinability channel in this act."*

⭐ **This closes, by scoping rather than by modelling, the gap the earlier determination flagged and could not resolve** — that `StateAccess` carries no `Determinability` attribute. **The PO/ARB has removed the question rather than answered it, which is the cheaper and more honest disposal while `D-2` stands.**

# `D-1` — computed method name `$this->$m()` · ✅ **DECIDED: IN SCOPE**

> **Recorded:** an **observed behavioural reference** whose target method name is **computed** and therefore **not determinable** from the available facts.
> **Required semantic outcome:** reference **observed** · target **computed/unknown** · `determinability = NotDeterminable` · **must remain distinguishable from *"reference not observed"***.
> ⛔ **Must NOT use:** fake method names · sentinel strings · raw PHP source · parser objects.

**This answers the `V-3a` question returned by the verification and by the earlier determination. The verifier's finding is upheld: *seen-and-excluded* must be distinguishable from *unseen*.**

### ⚠️ `F-D1` — a consequence the act's closing sentence under-states, recorded so Governance routes it correctly

The act says *"The exact representation change is implementation work after the decision."* **That is true of one half and not of the other:**

| Half | Status |
|---|---|
| qualifier + determinability | ✅ **already expressible** — `ComputedTarget` and `NotDeterminable` exist in `L3` today. **Pure binding work** |
| **the target method NAME** | 🔴 **NOT expressible.** `BehaviourReference::$targetMethodName` is a **mandatory, non-nullable `string`** (`INV-L3-5`, totality: no default, no "unknown"), **and `D-1` forbids fake names and sentinel strings.** ⇒ **there is nothing legal to put in that field for a computed target** |

> ⇒ **`D-1` cannot be discharged by binding work alone.** Making the target name absent-but-legal touches the `L3` model's totality invariant, which is an **accepted-architecture** matter, not implementation discretion. **Governance's translation step must route the two halves separately.** ⛔ **No representation is proposed here.**

# `D-4` — library dispatch · ✅ **DECIDED: BOUNDED ENUMERATION ONLY**

> **Recorded principle:** *"Only explicitly enumerated and evidence-supported dispatch mechanisms are in scope."*
> ⛔ *"Do not define an open-ended 'understand PHP runtime/library semantics' capability."*
> *"The Architecture proposal may be used as the evidence base for the initial bounded list. If the list is rejected: the contract must explicitly state the limitation."*

**This answers the `V-3b` scope question. ✅ The principle is decided; ⬜ the LIST is not — it does not yet exist.** **Producing it is architecture work under `G-KOS-CONTRACT-V3-ARCH`.**

# `D-5` — dispatch vocabulary · ✅ **DECIDED IN PRINCIPLE · ⬜ selection OUTSTANDING**

> **Recorded:** *"If the dispatch scope is accepted: choose a language-neutral `L3` representation / `QualifierKind`. **Do not use PHP API names as domain vocabulary.**"*

✅ **The constraint is decided and is a real one:** no `QualifierKind::CallUserFunc` or any other PHP-API-derived value. ⬜ **The vocabulary itself is NOT chosen by this act**, and **Governance does not choose it** — that is architecture work, and it interacts with the standing `OPEN-1` record that the `L3` vocabulary is already PHP-derived in its first version.

---

# Artifact-update gate — ✅ **AMENDED as delivered**

| | Condition, recorded |
|---|---|
| ✅ | **ordinary existing-fixture evidence may continue** |
| ⛔ | **`V-3`-affected evidence may NOT be authored until the relevant `V-3` decisions are settled** |
| ⛔ | **no evidence may be used to assert conformance for an unresolved semantic category** |
| ⛔ | **the artifact-update assignment remains UNCREATED** until Governance records the post-decision authorization |

**Applied to the five decisions:** `D-2`/`D-3` are settled, so **dynamic-property evidence is not merely blocked — it is out of scope and must not be authored at all.** `D-1` is settled in disposition but **`F-D1` leaves its representation open**, so `D-1`-affected evidence stays blocked. `D-4`/`D-5` remain blocked pending the list and the vocabulary.

---

# Consequence for the open architecture lane — **narrowed, not moot**

`S4-architecture-v3-determination` is REGISTERED (seq 38) and HANDED_OFF (seq 39), **START not performed**. **The PO/ARB has now decided the dispositions that lane was commissioned to analyse.** ⇒ **its remaining scope is the work `D-4` and `D-5` delegate:** the enumerated dispatch list with its evidence, and the language-neutral vocabulary — **plus `F-D1`'s target-name representation question.** ⚠️ **The seq-38 bar stands: a process other than the Track-1 implementer.**

---

# What this act does NOT do

⛔ **No implementation** · ⛔ **no `expected.json` change** · ⛔ **no fixture change** · ⛔ **no artifact-update assignment created** · ⛔ **no implementation correction authorized** · ⛔ **no legacy calculator retired** · ⛔ **Track-1 acceptance NOT reopened — it stands within its authorized implementation scope** · ⛔ **no representation chosen for `D-1`** · ⛔ **no dispatch list enumerated** · ⛔ **no vocabulary selected** · ⛔ **13.3 and 13.5 untouched** · ⛔ **Architecture D not reopened.**

**Verified after recording:** `scripts/`, `app/`, `tests/` clean; `Lcom4Collector.php` `4136519b…`, `lcom4_collector.py` `5acb0e13…`, `expected.json` `173ab4ec…` unchanged.

---

**FIVE DECISIONS RECORDED · STOPPING.**
**Next actor: Governance — translate `D-1`…`D-5` into the appropriate specification and implementation authorization**, routing `F-D1`'s two halves separately and leaving the `D-4`/`D-5` work to the architecture lane under its standing separation bar.

**Traceability:** the PO/ARB act 2026-08-19 (recorded verbatim above) · `V-3` in `2026-08-18-…-track1-independent-verification.md` · the earlier V-3 determination `99aeac7c` (**evidence, not authority**) · `expected.json` `_variant` and `_variant_decisions_pinned.intra_class_calls` (PO/ARB 2026-08-16) · accepted implementation architecture `adc5c8e8` (`INV-L3-5` totality) · `BehaviourReference.php:21` · `QualifierKind`, `Determinability` · `G-KOS-CONTRACT-ARTIFACT-UPDATE` (AUTHORIZED, UNEXERCISED) · `G-KOS-CONTRACT-V3-ARCH` · lane seq 38–39 · `OPEN-1` (`AMD4`) · `INV-ATTR-2`/`G-2`.
