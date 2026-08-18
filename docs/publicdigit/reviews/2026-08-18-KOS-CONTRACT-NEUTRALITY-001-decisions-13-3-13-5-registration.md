# Decision Registration — 13.3 (NEW-5 / qualifier semantics) and 13.5 (contract silences)

**Registered by:** Governance, on the delivered PO/ARB act · 2026-08-18
**Evidence base verified before registering:** the semantic clarification proposal with its **AMD1 amendment delivered** — probe `N13` (`Sub\Fq::b()`, no leading backslash, ≥2 segments, no alias) **with a sensitivity control arm** `N13-c` proving the probe can fire. **Both decisions rest on measured evidence, not on the proposal's argument alone.**

---

## ✅ Decision 13.3 — NEW-5 / qualifier semantics · **DECIDED: THE STRATIFIED RULE**

> **The behavioural-dependency clause governs what constitutes an edge. The language binding must preserve qualifier kind, and the contract rules the interpretation per qualifier kind.**

**The precedence question is answered:** behavioural dependency defines *what an edge is*; **qualifier kind is preserved by the binding and interpreted by the contract** — neither clause simply defeats the other. This is `13.1`'s stratification applied to the specific case rather than fought against.

### The adopted qualifier rules

| Qualifier kind | Ruling |
|---|---|
| unqualified own name | **INCLUDE** |
| `self` | **INCLUDE** |
| `static` | **INCLUDE** |
| `parent` | **EXCLUDE** — out of frame |
| **qualified unaliased** | **EXCLUDE** — *not the own class* |
| fully-qualified | **INCLUDE** |
| relative | **INCLUDE** |
| aliased | **EXCLUDE** — **LIMITATION** |
| other-class | **EXCLUDE** |
| dynamic/computed | **EXCLUDE** — *not determinable* |

### ⭐ The bucket ruling — the part the evidence demanded

> **"The qualified case is determinable and must not be classified as 'not determinable'."**

This adopts the proposal's own central warning verbatim in effect. Its words: *the exclusion is "not the own class", **not** "not determinable" and **not** a declared limitation — the target is fully determined from file-local text*, and using the not-determinable bucket for a determinable case *"corrupts the very distinction the contract insists must never be merged."*

**Consequence:** the proposal's earlier blanket row — *"every qualified, fully-qualified, relative or aliased form → no edge — NOT DETERMINABLE"* — **is superseded by this ruling.** Three of those four kinds are now INCLUDE or a named LIMITATION, and the fourth is excluded on a different ground.

### Decision status, as stated

**DECISION** for the semantic rules · **LIMITATION** for the aliased case — the two are kept distinguishable, which the contract requires and which the estate has enforced since the first LCOM4 correction.

---

## ✅ Decision 13.5 — Contract silences · **ALL FOUR DECIDED**

| Silence | Ruling |
|---|---|
| **Anonymous classes** | **DECIDED — analysed unit.** Requires **a stable unique identity within the analysed scope** |
| **Nullsafe `?->`** | **DECIDED — behavioural dependency / edge.** ⚠️ **Must be covered by declared expected evidence, because differential comparison cannot detect the shared current blindness** |
| **Enum / interface / trait** | **DECIDED SEPARATELY: enum = analysed unit · trait = analysed unit · interface = NOT an analysed unit** |
| **Fully-qualified first-class callables** | **DECIDED — the existing `first_class_callables` rule remains sufficient; they remain excluded** |

**⭐ The nullsafe clause is the sharpest thing in this act:** where both implementations share a blindness, **agreement proves nothing** — so the evidence must be *declared expected*, not merely differential. That is `O-1`/`O-2` and the *agreement ≠ neutrality* finding turned into a contract requirement.

**Enum/trait/interface decided *separately* rather than as a family** — the three were silent together but are not one question, and the ruling splits them.

---

## What these decisions do NOT do — stated in the act

> **"These decisions do not themselves modify `expected.json`, fixtures, or implementation artifacts. Those consequences require a separate governed act."**

**Registered as binding.** The rulings change what the contract *means*; propagating them into artifacts is separate work under its own authorization. Note the scale of that downstream work: `fully-qualified: INCLUDE` and `relative: INCLUDE` are **not** what the PHP reference currently does uniformly (it resolves `\Fq` but refuses `\App\Fq` — the proposal's *"string comparison whose correctness is an accident of namespace declaration"*), so **both implementations may need to change, not just Python.**

## ⬜ Parsing architecture selection — **NOT MADE BY THIS ACT**

The act instructs that the selection now be made *"from the Architecture evaluation"* — but **names no option.** The standing decision reserves the choice: *"keep PO/ARB as the decision authority."*

**Governance does not select on the PO/ARB's behalf.** The evaluation recommends **D** (stratify the contract; conformance boundary at the fact model) with **B** for the language layer and **C-as-completion**, and **rejects A as an architecture** — and it deliberately selected nothing. **The gate 13.3/13.5 guarded is now open; the selection awaits one line naming the option.**

⛔ **No implementation is authorized by this act.**

**Traceability:** the PO/ARB act 2026-08-18 · proposal + AMD1 (`1a366c67`, `dab0f65c`; probe `N13` and control `N13-c` §2.9) · Decisions 13.1/13.7 registration · Decision 1 (PHP not authoritative) · parsing evaluation `d2859e91` · `O-1`/`O-2` · breadth report `17e4f066`
