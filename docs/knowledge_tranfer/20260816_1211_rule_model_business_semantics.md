# Step 3 — The Rule Model

### Business semantics, derived from what already runs

| | |
|---|---|
| **Kind** | ⭐ **BUSINESS SEMANTICS.** ⛔ *No classes · no aggregates · no schema · no implementation · no vocabulary adopted* |
| **Commission** | ARB, 2026-08-16 — *"Do not start by designing classes or aggregates. First establish the business semantics of a Rule"* |
| **Method** | ⭐ **Answer the ARB's ten questions from the running implementations**, not from design |
| **Status** | **PROPOSED** — an ARB input |
| **Presentation** | Business language first; technical identifiers as supporting evidence |
| **Preserved, not revisited** | Decisions **A** and **B**, ruled 2026-08-16 |
| ⭐ **LANE** | ⛔⛔ **TRACK B — EXPLORATORY. OUTSIDE THE GOVERNED ARCHITECTURE LANE.** *ARB, 2026-08-16* |
| ⭐ **Lane gate** | This material may become a **candidate architectural input** only after the current Architecture Baseline is **reconstructed · independently verified · accepted**. Until then it governs nothing |
| **Revision** | ⭐ **REV 1 — 2026-08-16 13:23.** *ARB rulings recorded; definition refined; binding strength separated from enforcement consequence. Changes marked ⭐ **ARB*** |
| **Date** | 2026-08-16 |

> ## ⛔ **Lane declaration — read before anything else**
>
> **`RM-1`…`RM-4` are ruled, and they are ruled *inside Track B*.** ⛔ **Their existence is not an architecture commission.** Nothing here authorises design, schema, implementation, or a conflict engine.
>
> ⚠️ **The lane applies to this whole knowledge-transfer track, not to this document alone** — every artifact in it derives from the same unverified baseline and inherits the same status.

---

# 0 · The finding, before the ten answers

⭐ **Decision B ruled that concepts answering different questions must not share a vocabulary. Applied to the word *rule*, it produces the same result — and the organisation is already living with the consequences.**

**Seven things in this organisation are called a rule, or behave as one.** Measured:

| # | What it is called | What it actually constrains | Who may change it |
|---|---|---|---|
| **1** | **Engineering standard** | how engineering work is performed | the governance authority |
| **2** | **Ruling** | a recorded decision, append-only | the governance authority |
| **3** | **Architecture principle** | what the architecture may not do | the architecture authority |
| **4** | **Design policy** | what a capability must protect | the governance authority |
| **5** | **Recommendation rule** | when to advise a developer | ⛔ **nobody declared** |
| **6** | **Quality-gate rule** | what the product's code may look like | ⛔ **nobody declared** |
| **7** | **Document-validation rule** | what a governed document must contain | the knowledge-platform owner |
| **8** | **Permission rule** | what a tool may do | the runtime configuration |

> ### ⭐ **These are not eight variants of one concept.** Some are *decisions*, some are *constraints*, some are *heuristics*, some are *access controls*. They differ in who may issue them, what happens when they are broken, and whether a machine or a human decides.

⛔ **This document does not merge them.** Per Decision B, the first act is to establish what a Rule *is* in business terms — then to say which of the eight that definition actually covers.

---

# 1 · What is a Rule?

## The organisation's own answer, already recorded

> **"A binding behavioural norm with a single canonical home."**

⭐ **Three things are claimed there, and each is testable:**

| Claim | Meaning in business terms | Holds today? |
|---|---|---|
| **binding** | someone is obliged; it is not advice | ⚠️ **partly** — most rules in the organisation are advisory by design |
| **behavioural norm** | it constrains *what may be done*, not *what is true* | ✅ |
| **single canonical home** | it is written once; everything else points at it | ⭐ **enforced by machine** for governed documents |

## The distinction that matters most

| | A **Rule** | A **Decision** | A **Recommendation** |
|---|---|---|---|
| Answers | *what may be done, always* | *what we chose, once* | *what you might do, here* |
| Binds | everyone in scope | the thing it decided | nobody |
| Ends by | supersession or retirement | being implemented | being accepted or ignored |
| Broken means | a violation | — | nothing |

> ### ⭐⭐ **THE DEFINITION — ARB, ruled 2026-08-16 (`RM-1` APPROVED, refined)**
>
> # **A Rule is a standing, authoritative obligation governing behaviour within a defined scope and period. A deviation is non-conformance unless an authorized exception permits it.**

⭐ **The ARB's refinement improves the proposed wording in two specific ways, and both matter:**

| Proposed | ⭐ Ruled | Why the change |
|---|---|---|
| *"whose violation is a **defect**"* | *"a deviation is **non-conformance**"* | ⭐ **defect makes one consequence the only consequence.** Non-conformance is the observation; what follows from it is a separate question |
| Exception not mentioned | ⭐ *"unless an **authorized exception** permits it"* | ⭐⭐ **Exception becomes constitutive of Rule, not an addition to it** |

> ### ⭐⭐ **Consequence worth stating plainly: `RM-1` and `RM-4` are not separable.**
>
> The ruled definition makes a Rule **unstatable without its exception concept** — a deviation cannot be classified at all until we know whether an authorised exception permits it. ⛔ **Modelling Rule and deferring Exception is therefore not an option the definition allows.**

⛔ **By this definition, only three of the eight things above are Rules.** The recommendation heuristics are not — they bind nobody. The rulings are decisions. The permissions are access controls. ⭐ **`RM-1` ruled: keep Rule separate from recommendations, decisions/rulings, and permissions/access controls.**

---

# 2 · Who owns it?

| Rule kind | Owner today | Grade |
|---|---|---|
| Engineering standards | ⭐ the governance authority | declared |
| Rulings | ⭐ the governance authority | declared |
| Architecture principles | the architecture authority | declared |
| Document-validation rules | ⭐ the knowledge-platform owner — **a named individual** | declared |
| ⛔ **Quality-gate rules** | ⛔ **nobody** | absent |
| ⛔ **Recommendation rules** | ⛔ **nobody** | absent |

> ⭐ **Decision A already answers this in principle:** the reusable rule belongs to the platform; the product-specific threshold belongs to the product. **What is missing is not the principle — it is the act of writing the owner down.** Two of the six rule families have no owner at all.

---

# 3 · What does it constrain?

⭐ **Four distinct subjects, and no rule anywhere declares which one it acts on:**

```
   CODE           what may be written        (cohesion · coupling · layering · tokens)
   DOCUMENTS      what must be recorded      (cards · placement · identifiers · links)
   PROCESS        how work must proceed      (plan first · tests first · guide at the end)
   ACCESS         what a tool may do         (permissions)
```

⛔ **A rule's subject is currently inferred from where the rule lives, never from the rule itself.** That is why the same engineering principle can be enforced twice by two mechanisms that do not know about each other — the open question the ARB has already identified.

---

# 4 · Where does it apply?

> ## ⛔⛔ **No rule anywhere in the organisation states where it applies.**

**Applicability is expressed today only as an accident of implementation:**

| Mechanism | How "where" is expressed |
|---|---|
| Quality gates | a text pattern that happens to match some files |
| Document validation | a directory glob in a configuration file |
| Process reminders | a path prefix hard-coded in a script |
| Recommendation heuristics | ⛔ **nothing — they apply to whatever was observed** |

⭐ **Consequence in business terms:** nobody can ask *"which rules apply to this product / this component / this environment?"* and get an answer. **The question is not merely unanswered — it is not expressible.**

---

# 5 · When does it apply?

> ## ⛔ **Almost nothing carries a period of validity.**

| Mechanism | Temporal notion | What it actually means |
|---|---|---|
| Engineering standards | a lifecycle status *(proposed / adopted / superseded)* | ⭐ **where it is in its life — not when it binds** |
| ⭐ **Quality gates** | ⭐ **a trajectory of targets across future phases** | ⭐ *the only forward-looking temporal model in the organisation* |
| Everything else | ⛔ none | — |

⭐ **The quality gates are the interesting case.** They carry a declining series of thresholds over successive phases — a rule whose *strictness is scheduled to change*. That is a genuine temporal semantic, invented locally for one purpose, and available nowhere else.

⛔ **What no rule has:** a date from which it binds, a date after which it stops, or an answer to *"was this rule in force when that decision was taken?"*

---

# 6 · How strong is it?

> ## ⛔⛔ **Three incompatible strength scales are in use, and none can be compared with another.**

| Scale | Values | Where |
|---|---|---|
| **A** | `error` · `warning` · `info` | quality gates |
| **B** | `error` · `warning` | document validation |
| **C** | ⭐ `deny` · `ask` · `allow` | tool permissions |
| **D** | ⛔ **none** | recommendation heuristics — *all advisory by design* |
| **E** | ⛔ **prose** | engineering standards — *"must" / "should" / "may" in sentences* |

⭐ **And a separate, orthogonal switch exists that overrides all of them:** the quality gates carry a global setting deciding whether a failure actually stops anything. **Today it is set to not stop.** So a rule marked `error` does not block — its strength label and its consequence are decoupled.

> ### ⭐⭐ **This is the sharpest finding in the Rule Model.**
>
> **"How strong is this rule?" currently has no answer that survives moving between two mechanisms.** A rule that is `error` in one place, `deny` in another, and `must` in prose is not three strengths — it is **three unrelated vocabularies that happen to sort in the same direction.**

## 6.1 ⭐⭐ ARB correction — these are TWO concepts, not one broken one

⭐ **The analysis above called the `error`-that-does-not-block a decoupling defect. The ARB's ruling on `RM-2` reframes it, and the reframing is better:**

> ### **Binding strength** *(how obliged is the reader?)* **is a different concept from Enforcement consequence** *(what does the mechanism do about it today?)*.

| | **Binding strength** | **Enforcement consequence** |
|---|---|---|
| Answers | *how strongly does this oblige?* | *what happens when it is broken, right now?* |
| Owned by | ⭐ the authority that issued the Rule | the mechanism operating it |
| Changes when | the obligation changes | ⭐ **the enforcement maturity changes** |
| Example | *mandatory* | *advisory today; blocking once the gate is trusted* |

> ### ⭐ **A Rule may be MANDATORY while its current enforcement is ADVISORY. That is legitimate, not a defect.**
>
> ⛔ **What is a defect is having no way to say both.** Today one field carries both meanings, so *"we mean this seriously but do not yet block on it"* is inexpressible — and the organisation resolves the ambiguity by setting the switch to not-block, which reads as *"we do not mean it."*

⭐ **This also explains the quality gates' declining target series without calling it an anomaly:** the obligation is fixed at zero violations; the *enforcement threshold* descends toward it over phases. **Two concepts, correctly separated in one mechanism, by accident.**

**`RM-2` ruled:** ⭐ **the need for a common binding-strength concept is APPROVED. ⛔ The vocabulary is DEFERRED** — values are a governance act, not a technical choice.

---

# 7 · What evidence supports it?

| Rule kind | Carries its evidence? |
|---|---|
| Engineering standards | ⭐ **yes** — each declares the evidence of necessity that justified it |
| Rulings | ⭐ **yes** — each records the act and its date |
| Architecture principles | ⭐ **yes** — *"derived, none invented"* |
| ⛔ **Quality-gate rules** | ⛔ **no** — a threshold with no recorded reason |
| ⛔ **Recommendation rules** | ⛔ **no** — a number with no recorded reason |

> ⭐ **The governance rules are evidence-bearing; the executable rules are not.** The rules a machine actually enforces are the ones that cannot say why they exist.

---

# 8 · Who can authorise it?

| Act | Who, today |
|---|---|
| Propose a rule | anyone |
| ⭐ **Approve an engineering standard** | ⭐ the governance authority |
| ⭐ **Record a ruling** | ⭐ the governance authority |
| ⛔ **Change a quality threshold** | ⛔ **anyone who can edit a configuration file** |
| ⛔ **Change a recommendation threshold** | ⛔ **anyone who can edit a configuration file** |
| ⭐ **Emit a certification outcome** | ⭐⭐ **a human only — and this is enforced mechanically** |

> ### ⭐ **One authority boundary in the entire organisation is enforced by machine rather than by convention** — the one traced on 2026-08-16. **Every other authority statement about rules is a convention that a text editor can bypass.**

---

# 9 · How can it be superseded or excepted?

## Supersession

| Mechanism | Supersession |
|---|---|
| Engineering standards | ⭐ declared explicitly — *"supersedes X"* |
| Rulings | ⛔ **none — append-only.** *A later ruling may contradict an earlier one and both remain in force on their face* |
| Quality-gate rules | ⛔ edit in place; **history lost** |
| Recommendation rules | ⛔ edit in place; **history lost** |

## Exception

> ⭐ **Only one mechanism in the organisation implements exceptions, and it does so without calling them that.**

The quality gates carry **allowlist patterns** — *"this rule applies, except here."* That is an exception in every respect but name: it is scoped, it is recorded beside the rule, and it changes the outcome.

⛔ **What it lacks, and what a governed exception would require:** an approver, a reason, a validity period, and a way to distinguish *withdrawn* from *expired*.

> ### ⭐ **Business consequence:** the organisation grants exceptions today, silently, by editing a list. **Nobody can answer *"who approved this exception, when, and until when?"*** — because the question was never asked of the mechanism that answers it in practice.

## 9.1 ⭐⭐ The Exception principle — ARB, ruled 2026-08-16 (`RM-4` APPROVED)

> # **An exception is a separately authorized deviation from a Rule within a defined scope and validity period.**

⭐ **Four words in that sentence each close a gap measured above:**

| Word | Closes |
|---|---|
| **separately** | an exception is its own act — ⛔ not an edit to the Rule it excepts |
| **authorized** | ⛔ today: nobody; the allowlist has no approver |
| **scope** | ⛔ today: implicit in a pattern |
| **validity period** | ⛔ today: absent — which is why *expired* and *withdrawn* are indistinguishable |

## 9.2 ⭐ The Rule lifecycle — ARB, ruled (`RM-3` APPROVED)

> **What must replace the current path:**

```
   TODAY                          ⭐ RULED TARGET

   edit file                      Propose
      ↓                              ↓
   new organisational rule         Review
                                     ↓
                                  Authorize
                                     ↓
                                  Effective
```

⭐ **`RM-3` ruled: changing an authoritative Rule requires explicit authorization and a recorded reason.**

⛔ **Measured today:** a person who can edit a configuration file can change what the organisation enforces, with no record of who or why. **The widest authority gap found in the Rule Model.**

> ⚠️ **Note on scope, so the ruling is not over-applied:** `RM-3` binds **authoritative Rules**. Under `RM-1`, recommendation thresholds are *not* Rules — they bind nobody — so this lifecycle does not automatically govern them. **Whether advisory heuristics also warrant recorded change-authority is a separate question, and it is not ruled.**

---

# 10 · How do we know two Rules conflict?

> ## ⛔⛔ **Nothing anywhere in the organisation can answer this.**

**No mechanism compares two rules.** Every rule is evaluated against *the world*; none is evaluated against *another rule*.

⭐ **And §§4–6 explain precisely why it cannot be built today, in business terms:**

```
To know two rules conflict you must know:

   WHERE each applies      → §4  ⛔ not expressible
   WHEN each binds         → §5  ⛔ not recorded
   HOW STRONGLY each binds → §6  ⛔ three incomparable scales
   WHAT each obliges       → ⛔ never stated as an obligation, only as a pattern to match
```

> ### ⭐⭐ **Conflict detection is not a missing feature. It is the first thing that becomes *possible* once a rule can state where, when, how strongly, and what it obliges.**
>
> ⛔ **Building it before those four exist would produce a mechanism that compares text patterns and calls the result a conflict.**

---

# 11 · The observed minimum semantic gap

⭐ **Step 3's purpose, answered: what must a rule be able to say that it cannot say today?**

| # | The rule must be able to state | Exists today | Nearest thing that exists |
|---|---|---|---|
| **1** | ⭐ **what it obliges** *(an obligation, not a pattern)* | ⛔ **no** | a text pattern to match |
| **2** | ⭐ **where it applies** | ⛔ **no** | an accident of file paths |
| **3** | ⭐ **when it binds** | ⛔ **no** | ⚠️ a trajectory of targets, in one mechanism |
| **4** | ⭐ **how strongly it binds** | ⚠️ **three incompatible ways** | `error`/`warning`/`info` · `deny`/`ask`/`allow` · prose |
| **5** | **who authorised it** | ⚠️ governance rules only | declared in prose headers |
| **6** | **what evidence justifies it** | ⚠️ governance rules only | declared in prose headers |
| **7** | **what excepts it, and on whose authority** | ⛔ **no** | ⭐ an unnamed allowlist |
| **8** | **what it supersedes** | ⚠️ governance rules only | declared in prose headers |

> ### ⭐ **Four are absent everywhere. Three exist only in prose, on rules a machine never reads. One exists three incompatible times.**

---

# 12 · What this does **not** propose

⛔ **Explicitly, so it cannot be mistaken for design:**

no rule schema · no field list · no file format · no class · no aggregate · no vocabulary adopted for strength · no conflict algorithm · no merging of the eight rule kinds · no change to any running mechanism · no new mechanism.

---

# 13 · ⭐ The rulings — recorded 2026-08-16, **inside Track B**

| # | Ruling | Status |
|---|---|---|
| ⭐ **RM-1** | **APPROVED.** The refined definition (§1) is adopted; **Rule stays separate from recommendations, decisions/rulings, and permissions/access controls** | ruled — Track B |
| ⭐ **RM-2** | **APPROVED in part.** The *need* for one common binding-strength concept is approved; ⛔ **the vocabulary is DEFERRED.** Binding strength and enforcement consequence are **two concepts** (§6.1) | ruled — Track B |
| ⭐ **RM-3** | **APPROVED.** Changing an authoritative Rule requires explicit authorization and a recorded reason. Target lifecycle: **Propose → Review → Authorize → Effective** (§9.2) | ruled — Track B |
| ⭐ **RM-4** | **APPROVED.** An exception is a separately authorized deviation within a defined scope and validity period (§9.1) | ruled — Track B |

## 13.1 ⛔ What these rulings do **not** authorise

> **The ARB's constraint, recorded verbatim:** *"I would not let the presence of `RM-1`…`RM-4` turn into an implicit architecture commission."*

⛔ **Not authorised by these rulings:** any class · aggregate · schema · file format · database · API · conflict engine · change to a running mechanism · new mechanism · adoption of any strength vocabulary · migration of any existing rule into the new shape.

## 13.2 ⭐ What they establish — semantics only

```
Rule
 ├── obligation          what it obliges
 ├── scope               where it applies
 ├── validity            when it binds
 ├── binding strength    how strongly            ⚠️ vocabulary deferred
 ├── authority           who authorised it
 ├── evidence            what justifies it
 ├── exceptions          what permits deviation  ⭐ constitutive, not optional
 └── supersession        what replaces it
```

⛔ **Eight dimensions. No structure. No implementation. No commitment to build any of it.**

---

# 14 · Bottom line

**The organisation has rules everywhere and a Rule concept nowhere.**

Eight different things are called rules. **The three that a machine actually enforces cannot say what they oblige, where they apply, when they bind, or on whose authority** — while the three that carry all of that are prose no machine reads.

> ### ⭐ **The gap is not that our rules are too simple. It is that the rules with semantics and the rules with enforcement are two different sets, and nothing connects them.**

That is exactly the shape the organisation already knows how to close — the maturation chain it has completed once:

```
   business principle → governed definition → domain model → mechanical enforcement → independent verification
```

⭐ **Applied to Rules, that chain says: settle what a Rule means (`RM-1`), settle how strongly it can bind (`RM-2`), and only then consider what enforces it.** Conflict detection sits at the far end of that chain, not the near one.

## 14.1 ⭐ What the rulings unlock — and what stays shut

| ⭐ Unlocked | ⛔ Still shut |
|---|---|
| A Rule can now be **described** in business terms without inventing structure | ⛔ **Nothing may be built.** Track B governs nothing |
| Conflict analysis has a **precondition list** rather than a wish | ⛔ **No conflict engine.** Without scope, validity, strength and obligation it would compare text patterns and call the result a conflict — *architectural theatre* |
| Exception is **inside** the Rule concept, so it cannot be deferred into a later phase | ⛔ **No exception mechanism designed** |
| Binding strength is **separable** from enforcement maturity | ⛔ **No strength vocabulary adopted** — `RM-2` defers it deliberately |
| `RM-3` names a concrete governance gap with a target lifecycle | ⛔ **No lifecycle implemented, and none authorised** |

## 14.2 ⛔ The lane gate, restated

> **This material becomes a candidate architectural input only after the current Architecture Baseline is reconstructed, independently verified, and accepted.**

⚠️ **That gate is not near.** The baseline's own closing states it is `PROPOSED, NOT VALIDATED`, with falsification, governance adjudication and an authority decision still ahead of it. ⭐ **Until then, `RM-1`…`RM-4` are true statements about a model nobody is obliged to use.**

**Next in sequence: the Authority Model** — which §8 shows is the same gap seen from the other side, and which `RM-3` has now made unavoidable: *a lifecycle ending in "Authorize" cannot be specified until authority itself is modelled.*

---

*Step 3 of the ARB sequence. Derived from the running rule-carrying artifacts: the recommendation rules · the quality-gate rules and their enforcement settings · the document-validation rules · the tool-permission lists · the engineering standards' headers · the rulings register. Quantities measured, not estimated. Decisions A and B preserved and not revisited. ⛔ **No design · no schema · no vocabulary adopted · nothing outside `docs/knowledge_tranfer/` touched.***

***PROPOSED — an ARB input.***
