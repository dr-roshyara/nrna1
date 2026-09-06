# GAP RECORD — `𝒪_core` requires a **necessity/minimality proof** before any freeze

**Date:** 2026-09-06 · **Status:** `[OPEN]` gap record · **Nothing ratified. No operation set selected. No code.**
**Amends (proposal, not applied):** `G-01`'s *"HUMAN DECISION D-1"* · my own
`2026-09-06-KOS-SURVIVING-BLOCKER-DEPENDENCY-ANALYSIS.md` §9 recommendation ①.
**Raised by:** the research owner, 2026-09-06. **Verified against the corpus here.**

> ### ⚠️ **AMENDED 2026-09-06 — `D-1A` IS BLOCKED**
> §3's adequacy predicate rests on `K_min`, which is **stipulated, not derived**, and a rival basis
> with better standing exists (`GN-77`, nine capabilities, *"canonically REQUIRED"*).
> **`D-1A` must not run until Level A — capability necessity — is proved or falsified.**
> See [`…-GAP-KMIN-CAPABILITY-BASIS-UNVALIDATED.md`](2026-09-06-KOS-GAP-KMIN-CAPABILITY-BASIS-UNVALIDATED.md).


---

## 1. The correction I have to accept first

My dependency analysis concluded: *"Freeze `𝒪_core`. One act."*

$$\boxed{\textbf{No graph predecessor} \;\neq\; \textbf{No possible derivation}}$$

`[NEG]` **My recommendation conflated the two.** The step-289 graph admits an edge **only where a
cited passage states that B requires something from A** — it is a record of *already-admitted*
dependencies. That `𝒪_core` has no in-edge proves:

> **the current dependency corpus does not derive `𝒪_core` from another node**

It does **not** prove:

> ~~`𝒪_core` is intrinsically primitive and can only be established by stipulation~~

**The correction is accepted.**

---

## 2. The gap, stated

$$\boxed{\textbf{The blocker is NOT "we don't know which operation names to choose."}}$$
$$\boxed{\textbf{It is: the semantic capability basis from which a canonical vocabulary may legitimately be frozen has never been proved.}}$$

### The gap splits in two, and only the second is governance

| | question | kind |
|---|---|---|
| **Q-A** | which operational **capabilities** are mathematically necessary for the minimum kernel? | **DERIVABLE** |
| **Q-B** | which concrete **operations** are selected as the canonical vocabulary realizing them? | **governance — but only after Q-A** |

---

## 3. The formal apparatus proposed

$$\mathcal K_{\min} = \{\text{HoldState},\ \text{TransitionLegally},\ \text{RejectIllegally},\ \text{Replay}\}$$

$$\operatorname{Adeq}_K(\mathcal O) \iff \mathcal K_{\min} \subseteq \operatorname{Cap}(\mathcal O)$$

$$\operatorname{Necessary}(o, \mathcal K_{\min}) \iff \neg\,\operatorname{Adeq}_K(\mathcal O \setminus \{o\})$$

$$\mathfrak M_K = \{\mathcal O \mid \operatorname{Adeq}_K(\mathcal O) \wedge \forall o \in \mathcal O:\ \neg\operatorname{Adeq}_K(\mathcal O\setminus\{o\})\}$$

**Classification each candidate operation must receive:** *Necessary · Derivable · Equivalent ·
Optional · Contextual · Undetermined.*

**And the crucial type correction:**

$$\mathcal O_1 \neq \mathcal O_2 \;\;\not\Rightarrow\;\; \operatorname{Cap}(\mathcal O_1) \neq \operatorname{Cap}(\mathcal O_2)$$

$$\boxed{\mathcal O_{\text{core}} = \text{canonical semantic operation CLASSES}} \qquad \text{a concrete vocabulary is a REPRESENTATION of them}$$

---

## 4. ⭐ Corpus verification — and it **strengthens** the proposal

Four facts checked. All confirm the gap is real and **not already closed**.

| # | corpus fact | source | consequence |
|---|---|---|---|
| **1** | **`𝒪_core` NOT FROZEN · NOT RATIFIED** | `GN-75`, `GN-84`, `handoff/02` | the freeze has not happened |
| **2** | **"six rival minimal registries"** — *"minimal under **that** criterion; **non-unique**"*, `RECOMMENDATION PENDING FALSIFICATION` | `GN-84` R5 | $\lvert\mathfrak M\rvert > 1$ **already found — but under a different predicate** |
| **3** | **`Reachability ≠ Epistemic adequacy`** ⇒ *"it is **not** the `G-67` test"*; `\|K_min\|` = 13 / 8 | `KR-2026-09-01` §19, via `07-READINESS-DELTA` | **the ablation that ran measured the WRONG adequacy predicate** |
| **4** | *"**minimality is representation-relative**"* — the corpus explains the 5 / 13 / 8 / six divergence **itself** | `10-CONFLICT-RECORDS` | **this is exactly $\mathcal O_1 \neq \mathcal O_2 \not\Rightarrow \operatorname{Cap}(\mathcal O_1) \neq \operatorname{Cap}(\mathcal O_2)$, already established** |

> ### `[EXP]` **The decisive finding: the corpus HAS a non-uniqueness result — over reachability, not over kernel-contract adequacy.** $\operatorname{Adeq}_K$ against $\mathcal K_{\min} = \{\text{Hold, Transition, Reject, Replay}\}$ **has never been computed.**
>
> **So $\mathfrak M_K$ is genuinely uncomputed** — and the existing "six registries" result **cannot
> be reused** to answer it, because it answers a different question. The corpus says so in its own
> words: *"it is not the `G-67` test."*

### The candidate nucleus exists — and is **not** ratified

$$\mathcal O_{\text{core}} = \{\text{ASSERT},\ \text{LINK},\ \text{REVISE},\ \text{RETRACT},\ \text{ISOLATE}\}$$

appears in `…182005_final-architectural-review-knowledgeos-theory-v13.md` and
`…182015_ratification-assessment…` (both 2026-09-02).

⚠️ **Both are REVIEW/ASSESSMENT documents.** A ratification *assessment* is not a ratification, and
`GN-84` records `𝒪_core` as **NOT RATIFIED**. **Theory v1.3 does not exist; v1.2 is frozen.**

> `[REC]` The five-element set is a **candidate basis**, exactly as the owner cautions:
> $$\mathcal O_{\text{semantic nucleus}} \supseteq \{\text{ASSERT}, \text{LINK}, \text{REVISE}, \text{RETRACT}, \text{ISOLATE}\}$$
> **⊇, never =**, until every additional candidate is tested against $\mathcal K_{\min}$.

---

## 5. The capability questions each nucleus member must answer

Not *"is this operation attractive?"* but **"can $\mathcal K_{\min}$ be satisfied without this capability?"**

| operation | capability under test |
|---|---|
| `ASSERT` | can new epistemic state be introduced? |
| `REVISE` | can current standing change **while history is preserved**? |
| `RETRACT` | can standing be withdrawn **without destroying historical traceability**? |
| `ISOLATE` | can contradictory branches be preserved **without global explosion**? |
| `LINK` | can dependency / provenance / justification structure be represented? |

`ISOLATE`'s question is already partly answered elsewhere: **non-explosion and contradiction
containment are ADOPTED invariants** (Step 28 §28.42–45). That constrains, and does not settle,
whether a *separate operation* is necessary or whether containment falls out of `δ`'s admissibility.

---

## 6. `Reject` — the type distinction that must not be lost

$$\boxed{\textbf{Reject OPERATION} \;\neq\; \textbf{Rejected PROPOSITION}}$$

An operation on state · versus · an epistemic status.

$$\operatorname{RejectLegal}(K, o, \Gamma) \qquad \operatorname{RejectIllegal}(K, o, \Gamma)$$

Either $\delta(K,o,\Gamma) = \bot$, or a **recorded rejected transition** — decided by the finalized
transition contract.

> ### **It must never silently convert an *invalid operation* into a *false proposition*.**

`[EXP]` This is consistent with the corpus's own `G-17` (*refusals are unrecordable, so replay cannot
reproduce them*) and with `Rejection` being marked **CONTRADICTORY** in the required-15 list.
**Independent of `𝒪_core`; actionable in parallel.**

---

## 7. `Π ∈ ≡?` — semantic consequence ≠ governance canonicalization

The tension I reported (handoff: *"independent, can be taken now"* vs `258.31`: *"decided by whether
a mandatory op observes provenance"*) resolves into **two different acts**:

$$\operatorname{Derive}(\Pi \in \equiv \;\mid\; \mathcal O_{\text{core}}) \qquad\text{— a THEOREM, once } \mathcal O_{\text{core}} \text{ is fixed}$$
$$\operatorname{Canonicalize}(\Pi, \equiv) \qquad\text{— GOVERNANCE, and it survives the theorem}$$

> Governance may legitimately say: *"although property X follows semantically, we will expose, name
> and canonicalize it as a separate domain concept."* **Only the first is derivable.**

`[REC]` **Decision 3 must not be declared redundant** on the strength of `258.31` alone. **Both
corpus statements can be true of different acts.**

---

## 8. Breaking the `≡ ⇄ ≈ ⇄ congruence` cycle

Restore the hierarchy the theory already has, with explicit typing:

$$x \equiv_{\mathrm{sem}} y \iff \text{same semantic object under the specified identity semantics}$$
$$x \approx_{Q,\Gamma} y \iff \text{indistinguishable w.r.t. inquiry } Q \text{ in context } \Gamma$$
$$x \sim_{\mathcal O} y \iff \forall o \in \mathcal O:\; o(x) \equiv_{\mathrm{sem}} o(y)$$

$$\boxed{\equiv_{\mathrm{sem}} \;\rightarrow\; \approx_{Q,\Gamma}} \qquad \boxed{\equiv_{\mathrm{sem}} + \mathcal O \;\rightarrow\; \text{congruence}_{\mathcal O}}$$

**The reverse is not generally valid:** $x \approx_{Q,\Gamma} y \not\Rightarrow x \equiv_{\mathrm{sem}} y$.

`[EXP]` **This is already the corpus's position, twice over** — `CR-4`'s finding that the executable
tester is `≈_{Q,\Gamma,𝒪}` and **not** `≡_sem`, and the representation-adequacy result that two
representations can answer the same questions while differing in other observations.

> **The cycle is an artifact of untyped reuse of one symbol, not a genuine circularity.** ⚠️ It is
> broken **by stipulating the typing**, which is itself an act — recorded, not taken.

---

## 9. The proposed replacement for `D-1`

| act | content | kind |
|---|---|---|
| **`D-1A`** | **Operation Necessity Proof** — compute $\mathfrak M_K$ against $\mathcal K_{\min}$; classify every candidate *Necessary / Derivable / Equivalent / Optional / Contextual / Undetermined* | **DERIVATION** |
| **`D-1B`** | **Canonicalization** — if $\lvert\mathfrak M_K\rvert = 1$ the basis is **derived**; if $> 1$, governance selects a representative | **GOVERNANCE, conditional** |
| **`D-1C`** | **Freeze** $\mathcal O_{\text{core}} := \mathcal O_{\text{canonical}}$ | **GOVERNANCE** |

$$\boxed{\text{The governance freeze is the SECOND act, not the first.}}$$

The human act becomes **"choose a canonical representative among formally admissible alternatives"**
rather than **"invent the operation set."**

---

## 10. Readiness status, restated

> **NOT READY — `𝒪_core` requires a necessity/minimality proof followed by canonicalization.**

**This is not a weakening of the verdict. It is a change of the required work** — from an act of
stipulation to a derivation followed by a much narrower act.

$$\boxed{\text{derive what can be derived; govern only what cannot be derived}}$$

`[REC]` And the reason it matters beyond `𝒪_core`: freezing the current enumeration would **turn an
unresolved research question into a constitutional definition** — the precise shortcut the
programme's own governance exists to prevent.

---

## 11. What this record does NOT do

- **Does not select** any operation set. The five-element nucleus stays a **candidate** (⊇, not =).
- **Does not resolve** `Π ∈ ≡?`, and explicitly **declines** to call Decision 3 redundant.
- **Does not stipulate** the `≡ / ≈ / congruence` typing — it identifies the act.
- **Does not compute** $\mathfrak M_K$. **`D-1A` is not performed here.**
- **Does not ratify** anything, and modifies no canonical document.

**Theory v1.2 FROZEN · kernel NOT SELECTED · `𝒪_core` NOT FROZEN · no code written.**
