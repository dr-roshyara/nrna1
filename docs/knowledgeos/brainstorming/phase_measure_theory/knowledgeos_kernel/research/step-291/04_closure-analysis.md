# 04 — Closure Analysis (mandate §§3, 6, 7)

## §7 THE CENTRAL DISTINCTION — enumeration ≠ closure

> **§7: *"Do NOT assume finite enumeration = closure, and do NOT assume absence of enumeration = non-closure."***

**Four candidate closure mechanisms, tested against primary text:**

| Mechanism | Evidence | Verdict |
|---|---|---|
| **finite enumeration** | `256.2` nine · `259.7` eight more · `277` *"classification **CLOSED** · minimality **OPEN**"* | 🟡 **PARTIAL** — a **candidate list** (14 forced, upper bound 18), never a membership rule |
| **a closed type / schema / interface** | **`259.7`'s five-kind classification** + explicit signatures (`Assess : K × X → Assessment`, `Authorize : Actor × Action × Policy → Decision`) | ✅ **PRESENT** — and `277` calls **classification CLOSED** |
| **a declared admissibility predicate** | **`259.8`**: operations enter the test *"**if and only if** the corpus establishes them as state-changing"*; `42.9`/`42.41` `Admissible(o,K)` | ⚠️ **PRESENT IN FORM, ABSENT IN CONTENT** — named, not evaluable (`G1`) |
| **a governance boundary** | `261.23` cond. 2 and `259` both treat registry closure as a **precondition** | ⚠️ **ASSERTED, never discharged** |

$$\boxed{\begin{array}{c}\textbf{What is missing is NOT an enumeration. A SCHEMA exists and } 277 \textbf{ calls classification CLOSED.}\\ \textbf{What is missing is a MEMBERSHIP RULE saying which operations are MANDATORY.}\end{array}}$$

> ⚠️ **This materially narrows Steps 288–291's own language.** Those steps said *"`𝒪` is not closed"*
> and treated it as one undifferentiated blocker. **Precisely: `𝒪`'s *classification* is closed
> (`277`); its *mandatory membership* is not.** And *mandatory* is exactly what `258.9`, `258.20`,
> `258.21` and `258.31` all quantify over. **The blocker is narrower and better-named than reported.**

## §6 The observation universe — classification A–E, with evidence

| Case | Test | Evidence |
|---|---|---|
| **A** explicitly closed | every permitted observation enumerated | 🔴 **NO** — none enumerated anywhere |
| **B** schema-closed, not enumerated | a type/schema bounds what can be observed | 🟡 **PARTIAL** — `259.7` kind 5 *names* *"observation operations"* and gives **no schema for them** |
| **C** semantically bounded, not formally closed | an intended boundary, no exhaustive definition | ✅ **CLOSEST FIT** |
| **D** open | no defensible boundary | 🔴 **NO** — `261.21` asserts one exists |
| **E** undetermined | insufficient evidence | ⚠️ **applies to the schema question** |

### ⭐ And a self-reference inside `261.21`
It **defines** `𝒪_K` as *"the **closed set** of permitted state observations"* — and then, boxed in the
same section: *"**`𝒪_K` is not yet completely closed**."*
$$\boxed{\text{The definition presupposes exactly what the caveat withdraws.}}$$
**Classification: `DOCUMENTARY` + `FORMAL`.** *(Not a contradiction in the `G-67` sense — the caveat is
explicit and adjacent, so the text is self-aware. But the definition cannot be used as written.)*

**Operations: closer to case B** (a five-kind schema exists; mandatory membership does not).
**Observations: case C**, with the schema question at **E**.

## §3 Is closure of `𝒪`/`𝒯` NECESSARY for the candidate `≡_K` to be DEFINED?

**The six properties §3 requires be kept apart — measured separately:**

| Property | Needs `𝒪`/`𝒯` closed? |
|---|---|
| **definability** | 🔴 **NO** — `261.21`'s formula is **well-formed for any set `𝒪_K`** |
| **totality** | ✅ yes — a total predicate needs `𝒪_K`'s extension |
| **decidability** | ✅ yes — and `012 §35` bounds it **independently** of `𝒪_K` |
| **canonicality** | ✅ yes — *"which `𝒪_K`"* **is** the normative question |
| **computability** | ✅ yes — needs the extension **and** each `O` evaluable |
| **operational completeness** | ✅ yes — needs `𝒯`'s membership |

$$\boxed{\equiv_K \textbf{ is DEFINABLE without closing } \mathcal O/\mathcal T. \textbf{ Its EXTENSION is blocked; its DEFINITION is not.}}$$

⚠️ **Correction to Steps 289–290:** they said *"`≡` is blocked on `𝒪_K`"* without qualification.
**Precisely: `≡`'s extension, totality, decidability, canonicality and computability are blocked.
Its definability is not.** **Five of six, not six of six** — and the one that survives is the one that
matters for whether the *register* is coherent, which is why `N-1A` was decidable at all.

## §3 Blocker classification

| Blocker | Class |
|---|---|
| `𝒪`'s mandatory-membership rule | **NORMATIVE** |
| `𝒪_K`'s extension | **NORMATIVE** |
| the `𝒪` glyph overload | **DOCUMENTARY** |
| `261.21`'s self-reference | **DOCUMENTARY** |
| no schema for kind 5 (observation operations) | **TYPE-THEORETIC** |
| `Admissible(o,K)` named but not evaluable | **FORMAL/DERIVATIONAL** → `G1` |
| `𝒯`'s membership | **NORMATIVE** |
| `δ`'s commit case | **FORMAL/DERIVATIONAL** |

$$\boxed{\textbf{Not one blocker is an ENGINEERING blocker. Two are DOCUMENTARY and could be repaired without any decision.}}$$

## STATUS
**CORPUS** the four mechanisms; `277`'s classification-closed/minimality-open split; `261.21`'s
self-reference · **MEASURED** the six-property separation · **DERIVED** *"not enumerated" ≠ "not
closed"*; the missing item is a **mandatory-membership rule** · **NORMATIVE** `𝒪`, `𝒯`, `𝒪_K` membership
· **DOCUMENTARY** 2 blockers · **G1** `Admissible`
