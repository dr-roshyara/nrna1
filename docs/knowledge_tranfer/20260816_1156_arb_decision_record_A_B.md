# ARB Decision Record — A and B

### Ownership of engineering governance · Two kinds of assessment outcome

| | |
|---|---|
| **Kind** | ⭐ **DECISION RECORD + technical translation.** ⛔ *Architecture records the decision and derives its consequences. It does not make the decision* |
| **Recorded from** | The ARB's statement of **2026-08-16**, §*"What I would record now"* |
| **Status of the decisions** | **RECORDED** — as stated by the ARB. ⚠️ *A report of an act is not the act: if a separate ratification ceremony is required, this record is its input, not its substitute* |
| **Status of the translation** | **PROPOSED** — Architecture's derivation, subject to ARB correction |
| **Presentation rule adopted** | ⭐ **Business language first; technical identifiers appear only as supporting evidence** — ARB standing instruction, 2026-08-16 |
| **Date** | 2026-08-16 |

---

# The chain this record follows

```
TECHNICAL EVIDENCE  →  ARCHITECTURE INTERPRETATION  →  BUSINESS DECISION QUESTION
                                                              ↓
                                        HUMAN / ARB DECISION   ← §1, §2
                                                              ↓
                                              TECHNICAL CONSEQUENCE   ← §3, §4
```

---

# 1 · Decision A — Ownership of engineering governance

## The decision, as recorded

> ### **Common engineering knowledge and governance capabilities require explicit ownership. Product-specific mechanisms remain product-owned.**

**Business shape:** split ownership.

```
Reusable engineering capability   →  platform ownership
Product-specific rule or check    →  product ownership
```

## What made this decidable

A substantial body of engineering governance and quality mechanisms has **no declared business owner** — including the one authority rule the organisation has fully realised end to end. Nobody is currently accountable for it.

---

# 2 · Decision B — Two kinds of assessment outcome

## The decision, as recorded

> ### **Verification outcomes and recommendation-effectiveness outcomes are different business concepts and must not be merged into one verdict vocabulary.**

## What made this decidable

Two mechanisms both produce something called an outcome, and they answer different questions:

| | Question answered | Who may issue it |
|---|---|---|
| **Verification outcome** | *Did this check produce an acceptable result?* | ⭐ **machine may issue some; three outcomes are reserved to human review and the reservation is enforced** |
| **Recommendation effectiveness** | *Did an accepted recommendation actually improve the measured result?* | machine issues all of them, from thresholds |

⭐ **They share no implementation, no type, and no import.** The single word they have in common is coincidence of vocabulary, not a shared concept.

> ⛔ **The evidence rules out one option specifically: a blind merge.** The verification concept reserves three outcomes to human acts and enforces that reservation mechanically. A union with a concept that reserves nothing would silently destroy the reservation — the distinction between *a machine assessed this* and *a person certified this*.

---

# 3 · Technical consequences of Decision A

⭐ **The decision resolves the ownership question without requiring anyone to assign owners to individual mechanisms one by one.**

## 3.1 The decomposition it implies — already the organisation's own model

Split ownership is not applied *per mechanism*. It is applied **inside** each mechanism, using the decomposition the platform already defined:

```
Every mechanism decomposes into three parts:

   METHOD      the reusable engineering rule        →  PLATFORM ownership
   BINDING     the product-specific configuration   →  PRODUCT ownership
   EVIDENCE    what happened in this project        →  PRODUCT ownership
```

**Worked, on the three systems the inventory found:**

| System | Method — platform | Binding + Evidence — product |
|---|---|---|
| **Design & security quality gates** | the *shape* of a threshold gate; the fail-closed rule | design-token rules · component baselines · role definitions · tenant identity model · page and translation conventions |
| **Knowledge platform services** | ⭐ card validation · graph generation · placement derivation · identifier integrity · link classification | the placement configuration · the register list · this repository's documents |
| **Engineering observation runtime** | ⭐ the trigger contract · collectors · the rules engine · the outcome and assessment pipeline | ⭐ the five recommendation rules and their thresholds · the observation streams |

> ### ⭐ **The answer to *"who owns forty-five mechanisms?"* is not forty-five ownership assignments. It is one decomposition applied three times.**

## 3.2 What Decision A settles

| Question | Now answerable |
|---|---|
| Who is accountable for the reusable engineering rules? | ⭐ **the platform owner** — a single accountable party |
| Who is accountable for this product's thresholds and configurations? | the product team |
| Where does the fully-realised authority rule belong? | ⭐ **platform** — it is domain-free, configuration-free and evidence-free |
| Does anything need to move? | ⛔ **No.** Ownership is declared, not relocated. *A directory exists only when its first artifact arrives* |

## 3.3 What Decision A does **not** settle

- **Whether the platform is one system or two control planes.** The organisation runs two enforcement regimes — one triggered by the AI session, one triggered by commits — that do not reference each other. Decision A gives both an owner; it does not say whether they are one platform or two.
- **Which mechanism wins when two disagree.** Still open — the domain-purity case.
- **Identifier governance.** Deferred by the ARB to after this decision (§5).

---

# 4 · Technical consequences of Decision B

## 4.1 The two concepts stay separate, permanently

| | Verification outcome | Recommendation effectiveness |
|---|---|---|
| Question | *did the check pass?* | *did the change help?* |
| Reserved outcomes | ⭐ **yes — enforced** | none |
| Merge permitted | ⛔ **no** | ⛔ **no** |
| Supersession permitted | ⛔ **no** — neither replaces the other | |

## 4.2 The principle generalises — and it settles three further collisions

⭐ **Decision B states a rule, not just a ruling on one pair:** *concepts that answer different questions do not share a vocabulary.* Applied to the outcome vocabularies already in circulation:

| Vocabulary | Answers | Verdict under Decision B |
|---|---|---|
| Verification outcome | did this check pass? | ⭐ **its own concept** |
| Recommendation effectiveness | did the change help? | ⭐ **its own concept** |
| **Rule-relationship classification** *(do two rules overlap, refine, conflict?)* | how do two rules relate? | ⭐ **its own concept — a machine finding** |
| **Governance disposition** *(what did the authority decide about a conflict?)* | what was decided? | ⭐ **its own concept — a human act** |

> ### ⭐⭐ **Decision B pre-empts the largest reconciliation risk in the remaining programme.** Step 5 was scoped to *"eliminate vocabulary collisions."* Decision B establishes that **four of these are not collisions at all** — they are four concepts that happen to produce short verdict-like tokens. **Step 5 shrinks accordingly, and one specific error is now ruled out in advance: merging a machine finding with a human decision.**

## 4.3 What remains open, and its kind

| Open item | Kind |
|---|---|
| What each concept is **named** | ⭐ **governance act** — vocabulary changes are first-class governed events |
| Whether the effectiveness outcomes should become a typed value rather than free text | ⛔ **engineering question, not a business one** — it changes no meaning |
| Whether the effectiveness concept should reserve any outcome to a human act | ⭐ **business question** — worth asking, and not asked today |

---

# 5 · Deferred by the ARB

The trace found that one identifier is in use for two unrelated things in two different records, and that the series it belongs to is not governed at all.

**The ARB has correctly deferred this.** The business question underneath it is:

> **Do we want one governed vocabulary for architectural principles, or several independently governed ones?**

⭐ That question belongs after Decision A, because **it is an ownership question wearing an identifier's clothes** — a register needs an owner before it can be governed.

⚠️ **One protective note in the meantime:** the rule the ARB proposes to make canonical is currently identified by a token that already means something else. **Until the register question is settled, that rule should be cited by its statement, not by its number.**

---

# 6 · The maturation model — recorded as the pattern

⭐ **The ARB's instruction: this becomes the pattern for future architectural rules, rather than designing another abstract mechanism.**

```
   BUSINESS PRINCIPLE          what the organisation requires
          ↓
   GOVERNED DEFINITION         written once, with an owner
          ↓
   DOMAIN MODEL                expressed as a type, not prose
          ↓
   MECHANICAL ENFORCEMENT      the system refuses the violation
          ↓
   INDEPENDENT VERIFICATION    a test proves the refusal happens
```

| | |
|---|---|
| ⭐ **Existence proof** | **one rule** completes this chain today — the authority rule traced on 2026-08-16 |
| ⛔ **Everything else** | nineteen other contracts stop at *checked*, *conventioned*, or *reminded* |
| ⭐ **Consequence** | **the pattern is copied, not designed.** A worked example exists; no new mechanism needs inventing |

> ### ⭐ **This is the first time the organisation can point at a rule and say: *that is what a finished architectural invariant looks like here.***

---

# 7 · What is now unblocked

| | |
|---|---|
| ⭐ **Closed by Decision A** | who is accountable for engineering governance capability |
| ⭐ **Closed by Decision B** | whether the two outcome concepts merge — **and three further vocabulary questions, pre-emptively** |
| ⚠️ **Still open, ARB** | which mechanism wins when two disagree · whether the two enforcement regimes are one platform or two · what happens to the undelivered platform-wide gate |
| ⛔ **Deferred by ARB** | identifier governance — after Decision A |
| ⭐ **Next in sequence** | Rule Model, then Authority Model |

---

*Recorded 2026-08-16 from the ARB's statement of the same date. §§1–2 are the ARB's decisions in the ARB's own words; §§3–6 are Architecture's derivation and are `PROPOSED`. Supporting technical evidence: the Script-to-Capability Evidence Inventory and the invariant trace, both 2026-08-16. ⛔ **Nothing moved · nothing renamed · no owner assigned to any individual mechanism · no file written outside `docs/knowledge_tranfer/`.***

***Decisions RECORDED · translation PROPOSED.***
