# `KOS-CONTRACT-NEUTRALITY-001` Track 1 — **architectural determination on `V-3`**

**Date:** 2026-08-18 · **Type:** 🟡 **NARROW ARCHITECTURE DETERMINATION.** Not implementation · not verification · not acceptance · **Architecture D is not reopened.**
**Producing process, self-declared, NOT attestable** (`INV-ATTR-2`): `claude-code-session:1c8b041b`.

> 🔴 **Disclosed conflict, at the point of use: I am the Track-1 implementer.** `V-3` is a finding **against code I wrote** (`4c6c1dac`). A determination that the omission is acceptable would excuse my own defect. **Mitigation: §3 finds against my implementation on the primary case, and every conclusion is derived from the accepted record and from measurements anyone can reproduce.** `R-34`/`P-2`: I did not verify Track 1 and do not accept this determination.
> ⚠️ **Lane note:** no architecture assignment exists for this determination; the open lane is `S1-verification-track1-php-adapter` (seq 37). **Registering an assignment is a Governance act and I have not performed one.**

---

# 1 · `V-3` statement — verbatim from the verification

> ### 🟠 `V-3` — dynamic own-behaviour references are **UNSEEN**, where the pinned decision requires **SEEN-AND-EXCLUDED**
> *"`$this->$m()`, `call_user_func([$this,'m'])` and `$this->$p` produce **no fact and no exclusion** (`excluded=[]`), while `$x::b()` is correctly reported as `NotDeterminable`."*
> *"**Requires an Architecture determination** — the accepted `L3` model does not state whether the *binding* must emit a fact for a dynamic member, and verification must not decide it."*

✅ **The verifier correctly declined to decide it.** The question is properly here.

---

# 2 · Evidence

| | Evidence | Class |
|---|---|---|
| **E-1** | **The pinned contract (PO/ARB 2026-08-16), verbatim:** *"EXCLUDED AS NOT DETERMINABLE: calls whose target cannot be resolved from the class in isolation — dynamic method names (`$this->$name()`), callable arrays (`call_user_func([$this,'m'])`) … **a real dependency may exist, but the analysis cannot determine the target reliably**. THESE TWO EXCLUSIONS ARE DIFFERENT CLAIMS AND MUST NOT BE MERGED: the first says there is nothing to connect to; **the second admits we cannot see it**."* | **DECIDED** |
| **E-2** | **Measured asymmetry in the delivered binding**, reproduced now: `$x::b()` → `excluded=["NotDeterminable"]` · **`$this->$m()` → `excluded=[]`** · **`$this->$p` → `excluded=[]`** · **`call_user_func([$this,'b'])` → `excluded=[]`**. LCOM4 = 2 in all four | **OBSERVED** |
| **E-3** | **The accepted architecture states the principle GENERALLY but scopes the invariant NARROWLY.** §6.4 rationale: *"**Seen-and-excluded must be distinguishable from unseen in the evidence**"* — unrestricted. But `INV-L3-3`/`INV-6` read *"every **declaration** is emitted, including interfaces"* — **declarations only. No reference-completeness invariant exists.** | **OBSERVED** |
| **E-4** | `Determinability` is an **`L3` attribute** (closed enum, `INV-L3-5` mandatory), not an `L4` verdict. `ExclusionReason::NotDeterminable` is **derived from it** | **OBSERVED** |
| **E-5** | 13.7 / AMD2 Level 1 requires *"actual extracted node/unit facts **and relationship inputs**"* to match declared expected facts | **DECIDED** |

---

# 3 · `L3` semantic interpretation

**What is the domain fact?** Two facts, not one: **(a)** an own-behaviour reference site occurred; **(b)** its target is not determinable within the analysis scope. **E-1 asserts both** — *"a real dependency **may exist**, but the analysis cannot determine the target"*.

**What does `EXCLUDED AS NOT DETERMINABLE` mean in the neutral model?** ⭐ **It is an affirmative epistemic claim by the analysis about its own limits.** The contrast in E-1 is exact:

```
OUT OF FRAME       →  a claim about the DOMAIN    : "there is nothing here to connect to"
NOT DETERMINABLE   →  a claim about the ANALYSIS  : "we cannot see it"
```

> ## ⭐ **A claim cannot be made by silence. Emitting nothing asserts nothing — neither (a) nor (b).**

**Is "not emitted by the binding" semantically equivalent to "seen and excluded"? ⛔ NO — and the accepted architecture already says so.** §6.4's rationale is *"otherwise a binding that drops interfaces is indistinguishable from correct exclusion — `O-2`'s lesson applied to unit eligibility."* **The identical argument holds verbatim for references, with "drops a dynamic reference" substituted for "drops interfaces".** ⇒ **This is not a new principle; it is an unstated instance of a decided one.**

**Does `L3` own the distinction unseen / excluded / not-determinable?** ✅ **Yes, by construction (E-4):** `Determinability` is an `L3` attribute. If the binding emits nothing, `L4` has nothing to derive from, and **the "must not be merged" clause of E-1 becomes vacuous for this case** — the two exclusions are not merged with *each other*, but `NotDeterminable` is merged with *"never happened"*, which is worse.

> ⭐ **The decisive internal evidence: the binding is inconsistent with itself.** It populates `Determinability::NotDeterminable` on the static side (`$x::b()`) and never on the instance side. **The model already demonstrates the required behaviour; one path does not follow it** (E-2).

---

# 4 · Binding responsibility — ⚠️ **`V-3` contains TWO different questions and they must not share an answer**

**This is the determination's substantive contribution: the finding groups three constructs that are not architecturally alike.**

### `V-3a` — **lexically recognisable dynamic member access** · `$this->$m()` · `$this->$p`

**The binding already recognises `$this` + object operator; only the member token differs** — `T_VARIABLE` where it expects `T_STRING`. **No new knowledge is required; the site is visible at the token layer it already reads.**

> ## ✅ **DETERMINED: the binding MUST emit a `BehaviourReference` with `QualifierKind::ComputedTarget`, `TargetUnitRelation::Undetermined`, `Determinability::NotDeterminable`.**
> **Entailed by E-1 + E-3's stated principle + E-4 + E-5. It introduces no new semantic rule** — the reference remains excluded, by the same `L4` row 2 that already handles `$x::b()`. **It makes an existing decided claim expressible.**

*(`$this->$p` is the state-access analogue. The same reasoning applies; whether a non-determinable property access is a `StateAccess` with an undetermined name or a distinct fact is a representation question — **flagged, not decided here**, because the `L3` model has no `Determinability` on `StateAccess`.)*

### `V-3b` — **library-mediated dispatch** · `call_user_func([$this,'m'])` · `array_map([$this,'m'], …)` · `[$this,'m']` as a value

**This is categorically different.** Recognising it requires the binding to know **PHP standard-library calling conventions** — that `call_user_func` invokes, that `[$this,'m']` is a callable, that `array_map`'s first parameter is dispatched. ⭐ **That is not a lexical fact; it is library semantics.**

> ## ⬜ **OPEN — a genuine scope decision the contract does not settle.**
> **E-1 names `call_user_func` as an example of what is excluded. It does not follow that the binding must DETECT it** — an exclusion that is never reached is vacuous, not violated. **Obliging detection obliges library knowledge inside the binding, and the boundary of that knowledge (which functions? which parameter positions? user-defined wrappers?) has no stated limit.**
> ⛔ **I do not decide it, and I propose no mechanism.**

---

# 5 · Consequence for `L4` — **none**

`EdgeRules` row 2 already excludes `ComputedTarget`/`NotDeterminable` as `ExclusionReason::NotDeterminable`, and `GraphBuilder` already records excluded references with their reason. ✅ **`L4` requires no change under either `V-3a` or `V-3b`.** **The gap is entirely at the binding boundary.**

---

# 6 · Consequence for `L5` — **none, and this bounds the risk**

**Dynamic references are excluded under every reading, so no edge is created and no LCOM4 value changes.** ✅ **Measured: all four probes yield `value=2` today and would still yield `2`** (E-2).

> ⭐ **`V-3` is an EVIDENCE-COMPLETENESS defect, not a correctness defect.** **No published metric is wrong.** That is why the verifier rated it *"Blocks acceptance: NO for the delivery; YES for asserting conformance"* — and that rating is correct.

---

# 7 · Consequence for 13.7 evidence — **this is where it bites**

| Level | Consequence |
|---|---|
| **Level 1 — facts** | ⛔ **Declared expected facts cannot express *"a dynamic own-behaviour reference occurred here and was not determinable"*.** E-5 requires *relationship inputs* to be declarable; this one is not |
| **Level 2 — graph** | ⛔ `excludedReferences()` is empty, so the excluded-with-reason set **cannot carry the claim** |
| **Level 3 — metric** | ✅ unaffected |

> ## ⭐ **The failure mode is `O-2` exactly: declared evidence cannot distinguish**
> ### **a file with no dynamic calls** from **a file whose dynamic calls the binding failed to see.**
> **A probe that can only observe the final number cannot notice that it failed** — and here the number is identical in both worlds.

---

# 8 · Is the current contract sufficient?

| | Sufficient? |
|---|---|
| **The SEMANTICS** — dynamic own-behaviour references are excluded, as not determinable | ✅ **YES. Fully decided (E-1). ⛔ Nothing in 13.3 or 13.5 requires change, and I propose none** |
| **The `L3` EMISSION requirement for references** | 🔴 **NO — a stated gap.** The principle is stated generally (E-3) and the invariant enforcing it is scoped to *declarations* only. **`V-3a` falls in the gap** |
| **The SCOPE of library-mediated dispatch** | 🔴 **NO — not addressed anywhere.** `V-3b` |

---

# 9 · Is PO/ARB action required? — **YES, and only for two narrow things**

| | Item | Why it is not Architecture's to close alone |
|---|---|---|
| **① Ratify an invariant extension** | Add the reference analogue of `INV-L3-3`: **"Reference completeness — every own-behaviour reference site the binding recognises is emitted as a `BehaviourReference`, including those whose target is not determinable. Exclusion is `L4`'s."** | It **amends an accepted artifact** (the accepted implementation architecture). The *principle* needs no new decision — it is entailed — but **amending an accepted architecture is a ratification act** |
| **② Decide the `V-3b` scope question** | Must the binding detect **library-mediated dispatch**? | **Genuinely undecided.** It expands the binding's required knowledge from *the language* to *the standard library*, with no stated boundary |

⛔ **Nothing else.** No semantic rule changes · no metric changes · no `L4`/`L5` change · no contract text on exclusion changes.

---

# 10 · Exact decisions required

> **Decision 1 — `L3` reference completeness** *(draft wording, UNSIGNED, for the PO/ARB to adopt, amend or reject)*
>
> *"The `L3` fact model requires reference completeness: every own-behaviour reference site the language binding recognises shall be emitted as a `BehaviourReference`, including sites whose target is not determinable. Exclusion remains `L4`'s decision. This ratifies, for references, the principle the accepted architecture already states for declarations — seen-and-excluded must be distinguishable from unseen. **It changes no semantic rule and no metric.**"*

> **Decision 2 — scope of library-mediated dispatch** *(draft wording, UNSIGNED)*
>
> *"Whether the PHP binding is required to recognise own-behaviour references dispatched through standard-library callables (`call_user_func`, callable arrays, and comparable forms) is [**IN SCOPE — and the boundary of required library knowledge shall be stated** / **OUT OF SCOPE for the current binding, recorded as a declared limitation**]. Until decided, `V-3b` remains an open defect and is not implied by Decision 1."*

**Consequences if both are decided** *(stated as consequences, not requested)*: the binding gains an emission path for `V-3a`; declared expected evidence gains rows it cannot express today (**`G-KOS-CONTRACT-ARTIFACT-UPDATE`**, unexercised); the delivery's gap list gains `V-3b` if it is ruled out of scope. ⛔ **No implementation is authorized by this determination.**

---

**DETERMINATION DELIVERED · STOPPING.**
⛔ **No implementation · no expected evidence · no fixtures · no change to 13.3 or 13.5 · no new language · Architecture D not reopened · no verification · no acceptance · nothing closed.**
**Next actor: PO/ARB — Decisions 1 and 2. Implementation only after ①, and `V-3b` only after ②.**

**Traceability:** `V-3` in `2026-08-18-…-track1-independent-verification.md` · `expected.json` `_variant_decisions_pinned.intra_class_calls` (PO/ARB 2026-08-16) · accepted implementation architecture `adc5c8e8` §6.4, `INV-L3-3`, `INV-6`, §4.2 · Decisions 13.3 · 13.5 · 13.7 / `AMD2` Level 1 · `O-2` · delivered binding `4c6c1dac` (`PhpFactExtractor::factsIn()`, `classifyQualifier()`) · four probes reproduced read-only in scratchpad, not added to the repository.
