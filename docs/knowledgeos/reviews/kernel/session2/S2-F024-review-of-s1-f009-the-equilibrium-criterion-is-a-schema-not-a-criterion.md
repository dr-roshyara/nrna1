# S2-F024 · The equilibrium criterion has an unbound variable — it is a criterion *schema*, and it can generate any of the eight formulations

## Finding class

CHALLENGE ×3 · CONFIRMATION ×2 · NEW FINDING (event-completeness) · TYPE ERROR

## Source Session-1 artifact

`session1/S1-F009-kernel-as-boundary-not-component-and-the-equilibrium-criterion.md` — Findings 1–4.

---

## Level 1 — Extraction fidelity

**UNVERIFIED.** Corpus sealed to this session; quotation fidelity and selection completeness cannot be tested from here (`S2-F021.1`).

✅ **What Session 1 did that deserves recording.** It pre-empted the reasoning-base problem `S2-F020` had to raise elsewhere:

> *"This document reaches that framing on 08-23, **after v1.1 (08-22), so it is not independent of it**; ⟦PROVENANCE⟧ **no priority claim is made**."*

And it declined to inflate the provenance class: *"⟦PROVENANCE: partly UNCERTAIN⟧ — the register … matches model-generated analysis rather than project ruling"*, confidence *"medium"*. That is the discipline `S2-F015` and `S2-F019` found missing in earlier artifacts, applied here without prompting.

⚠ Nothing in Levels 2–3 implies any part of Level 1 was verified.

## Level 2 — Analytical validity

### `.1` The equilibrium criterion is not a criterion — **CHALLENGE (primary finding)**

> *"The Kernel owns everything necessary to protect **the aggregate invariant** during admission, and nothing more."*

Session 1 flags the term: *"the criterion's own key term ('the aggregate invariant', singular) is **not specified**."* Correct — and the consequence is larger than a missing definition.

**There is no "the aggregate invariant."** Law carries **eleven** (`INV-KOS-IDENTITY-001 · DIMENSION · AUTHORITY · DECISION · PROJECTION · VERIFICATION · FAILURE · CONTRADICTION · UNKNOWN · AGENCY · HISTORY`). The criterion therefore contains a **free variable**, and its output is entirely determined by the substitution:

| Substitute | What the criterion then admits |
|---|---|
| `INV-KOS-IDENTITY-001` | identity assignment in; confidence, justification sufficiency arguably out |
| `INV-KOS-HISTORY-001` | history recording in; state determination arguably out |
| `S1-F012`'s conjunctive *co-consistency across identity + state + confidence + justification + evidence + history* | **everything in** — the god-object F007 warns against |
| `INV-KOS-VERIFICATION-001` alone | close to the minimal rule-evaluator F008 rejects as too small |

So the criterion is a **schema**, not a decision procedure. It has the grammatical form of a membership test and cannot decide a single membership question until the invariant is named — and naming it *is* the architectural decision the criterion appears to be deriving.

**This explains something Session 1 records as a virtue.** `S1-F009` says the criterion *"directly addresses the `S1-F007` ↔ `S1-F008` opposition."* It appears to, because **it is compatible with both** — under different substitutions it yields the small Kernel and the large one. Compatibility with two opposed positions is not resolution of them; it is silence wearing a decision's clothing.

⚠ **Not a claim that the criterion is wrong.** *Protect an invariant, and nothing more* is a sound principle and matches `KCON-012`'s form. The finding is that **it is not yet applicable**, and its apparent power to settle disputes is an artifact of its underspecification.

### `.2` "Boundary, not component" reverts to component language — **TYPE ERROR, unflagged**

Finding 2 asserts the category: *"The Kernel is a **domain boundary, not a component.** It is the set of rules and invariants that govern admission."*

Then, in the same finding: *"Its implementation may be **in** the aggregate"* and *"the Kernel is **the part of** the aggregate …"*.

**"The part of X that…" is component language.** A set of rules is not a part of an aggregate; it is a constraint the aggregate satisfies. The document asserts one ontological category and elaborates in the other, and `S1-F009` transcribes both without noticing the switch — the more surprising because the artifact's own headline is the category claim.

Consequence: `S1-F011`'s *"the Kernel is not a 'thing'"* and `S1-F012`'s *"the domain **space** within which"* may be agreeing with F009's **assertion** while disagreeing with its **elaboration**. Any convergence claim across the three needs to say which half it converges on.

### `.3` *"The corpus's only place where the core act is chosen against explicitly stated alternatives"* — **literally true, rhetorically overstated**

`S1-F011` tests four boundary candidates before selecting; `S1-F012` compares hypothesis D against A/B/C with reasons. Neither is about *the core act* specifically, so the claim survives on its literal terms.

But the implied distinction — *this is the only traceable choice-against-alternatives in the corpus* — is false. The **method** appears in at least three documents. Defensible restatement: *the only place the **core act** is chosen against stated alternatives; the method itself recurs.*

### `.4` The F007/F008 correction propagates — **RECURRENCE**

Per `S2-F023.1` the *"too large / too small"* opposition largely dissolves (one principle on disjoint sets; residue is *Adjudication ≠ Execution*). So Finding 3's claim to address it inherits the correction: it addresses a **narrower** problem than stated, and `.1` shows it does not settle even that.

### `.5` Projection excluded without argument — **RECURRENCE, no new finding**

Covered at `S2-F017` Part 4: the three exclusions (`F009`, `F011`, `F012`) share one prompt lineage and are one exclusion restated. `S1-F009`'s observation that it is *"excluded here without argument"* stands and remains interesting on its own.

## Level 3 — Implementation relevance

### Candidate A · Identity uniqueness and immutability (Finding 4)

**Kernel admission test.** *Remove it:* history is unattributable, supersession chains do not resolve, evidence links float free, and *"what did we know at time T"* becomes unanswerable. That is not inconvenience — it destroys **reconstructibility**. **Passes the test.**

**But it is not new.** `INV-KOS-IDENTITY-001` already carries it.
**Verdict → DO NOT IMPLEMENT (already protected).** `ES-005.4`.
**What would we lose?** Nothing — the protection stands without action.

### Candidate B · Admission must be **event-complete** — the one genuinely open item here

Finding 4 quotes: the Kernel's job is *"to **produce `KnowledgeCreated`** when admission is successful—and to produce **nothing (or a rejection event)** when admission fails."*

⚠ **"Nothing" and "a rejection event" are not interchangeable outcomes**, and the source offers them as alternatives in one parenthesis. `S1-F009` transcribes the phrase and draws no consequence. The consequence is concrete:

- With a **rejection event**, a refused candidate is a recorded fact — auditable, countable, reviewable.
- With **nothing**, a refused admission is **indistinguishable from an admission never attempted, and from one lost in transit.**

**1 · Need?** Plausibly yes. **2 · Where?** Domain/Core, with a Port consequence — the *outcome contract* of the admission gate. **Not** a new Kernel member; a completeness property of an existing one.

**3 · Verdict → NEEDS FURTHER EVIDENCE.** The precise question, and it is checkable: **does law require every admission attempt to terminate in exactly one recorded outcome?** §8 carries `KnowledgeRejected`, so refusals *can* be recorded. But ⟨Z-1⟩ holds that *absence of a constitutive prerequisite is not an epistemic state* — which appears to license a **pre-constitutive refusal that produces no record at all.** If so, there is a class of failed attempts that leaves no trace, and that class is exactly where "nothing" lives.

⚠ **I do not resolve this.** Whether the ⟨Z-1⟩ refusal should be recorded is an architectural question and **NOT ADJUDICATED.** I record the question, its location, and the loss.

**5 · What would we lose?** The ability to answer *"was this ever proposed and refused?"* — an **authority and governance** property, not a feature. Unrecoverable after the fact: an unrecorded refusal cannot be reconstructed later from anything.

**6 · Smallest thing, if ever warranted?** The gate's outcome set is **closed and total** — every attempt yields one of a declared finite set of recorded outcomes, with no unrecorded branch. A contract property, not a subsystem. Stated so the cheap option is on the record before an event-store proposal arrives.

### Candidate C · The equilibrium criterion itself

**Verdict → PRESERVE AS KNOWLEDGE ONLY**, and per `.1` **only once its invariant is bound.** As written it is a review prompt (*which invariant does this membership protect?*) — genuinely useful in that form and not implementable in any form.

## DDD interpretation

`Admission` → CANDIDATE DOMAIN ACT (one of seven, chosen). `KnowledgeCreated` → CANDIDATE DOMAIN EVENT; the **outcome-completeness** property is the buildable part, not the event. *Identity unique and immutable* → CANDIDATE INVARIANT, already law. `Kernel` → category **contested within the artifact itself** (`.2`): asserted as invariant-set, elaborated as aggregate-part.

## Question type · Architectural altitude

Finding 1 **ontology/act** · Finding 2 **ontology** · Finding 3 **boundary/extent** · Finding 4 **domain event + invariant**. Altitudes: Domain/Core throughout, with Finding 3 at Kernel altitude and Candidate B touching the Port.

## Convergence / contradiction assessment

- vs `S1-F011`, `S1-F012` on *boundary not component* → **FRAMING INHERITANCE** (shared prompt lineage, `S1-F011`'s own note), and per `.2` possibly agreement on the assertion with disagreement on the elaboration. **Not independent arrival.**
- vs v1.1 §16 → **DEPENDENT** by Session 1's own dating; no priority claimed. ✅
- vs `S1-F012`'s equilibrium → **two criteria, different bounds** (invariant-protection vs act-completeness). Session 1 records this in `S1-F012`; `.1` shows F009's side cannot be evaluated until its variable is bound.
- vs `S1-F010` → **not a contradiction** (`S2-F018`): different question.

## Kernel relevance

Finding 3 is the only Kernel-altitude claim, and `.1` shows it currently decides nothing. Candidate B is a **completeness property of an existing kernel responsibility**, not a new member. **No new Kernel membership is supported by this artifact.**

## Implementation verdict

**A → DO NOT IMPLEMENT (already protected).** **B → NEEDS FURTHER EVIDENCE** (question named, loss concrete). **C → PRESERVE AS KNOWLEDGE ONLY.** **Zero IMPLEMENT.**

## Standing hypothesis test (§17)

*Does the artifact strengthen "KnowledgeOS preserves and reconstructs epistemic states rather than storing a primitive called Knowledge"?*

**Strengthens the preservation half.** F009's INCLUDES list is `identity assignment · evidence preservation · justification preservation · state determination · confidence assignment · history recording · constitutional evaluation` — **not one storage verb**, and the EXCLUDES list is entirely *doing* verbs (interpret, reason, project, workflow). A boundary described only in preservation and determination terms is evidence for the hypothesis.

**Neutral on the no-primitive half.** `Knowledge` remains the thing admitted; nothing here dissolves it into relationships or projections. **Not adopted.**

## What evidence would change this verdict

For `.1`: any source text naming *which* invariant the criterion protects — that alone converts the schema into a criterion. For Candidate B: the law's answer on whether a ⟨Z-1⟩ pre-constitutive refusal is recorded. For `.2`: the source's own resolution of set-of-rules versus part-of-aggregate.

## Open questions

Which invariant does the equilibrium protect? · Is the Kernel an invariant set or an aggregate part — the artifact says both · Is an unrecorded refusal admissible? · Why is Projection excluded without argument (three times, one lineage)?

## Status

**OPEN.** Criterion reclassified as a schema · one internal type error unflagged by the source artifact · one materially new implementation question raised and **not adjudicated** · Session 1's provenance handling confirmed as exemplary.
