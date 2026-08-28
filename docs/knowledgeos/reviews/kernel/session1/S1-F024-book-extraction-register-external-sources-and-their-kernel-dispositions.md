# S1-F024 · Book-extraction register — what each external source contributes, and the **recurring disposition: extract the distinction, refuse the ontology**

**Finding ID:** S1-F024
**Finding class:** CONSOLIDATED REGISTER — one artifact covering the `P3` BOOK_EXTRACTION class, updated per source rather than duplicated
**Status:** OPEN · accumulating
**Implementation relevance:** **RESEARCH ONLY** for every source below unless a row says otherwise
**Provenance class:** `P3` BOOK_EXTRACTION / `P4` MODEL_INTERPRETATION of a named work

---

## Why this is one artifact and not many

⟦INFERENCE⟧ Each of these documents makes the **same structural move**: read a named external work,
extract distinctions the corpus can use, and **refuse the work's ontology as Kernel content**. Creating a
separate artifact per book would multiply near-identical findings. Genuinely distinct contributions get
their own row here; anything that changes a *model* gets its own `S1-Fxxx` artifact instead.

⚠ **Provenance rule applied throughout:** a book-derived definition is **not** a project decision. Every
row is a source claim plus a corpus disposition, never an adopted position.
⚠ ⟦VERIFIED⟧ **0 external URLs** in every document in this register — sources are named by title/author
and reasoned about, not linked.

---

## Register

### `20260823-235306` · Timothy Williamson, *Knowledge and Its Limits* — `P3`
**Framing question extracted** ⟦C⟧ *"What can KnowledgeOS legitimately treat as knowledge, evidence,
authority, uncertainty, and assurance — **and what are the limits of those claims**?"*
**Claims** ⟦C⟧ *"Knowledge is a **first-class architectural category**, not merely a computed combination
of lower-level metadata."* · ⟦C⟧ *"**All knowledge is evidence.**"*
⟦INFERENCE⟧ The first claim opposes every *derived* treatment in the corpus (`S1-F014`'s derived states,
`S1-F016`'s administrative identity). The second is stronger than anything in the corpus: if all knowledge
is evidence, the *evidence* member and the *knowledge* object partly collapse — which cuts against
`S1-F018`'s Entity/VO framing by making the distinction gradual. **Recorded as a source claim; not
adopted.** Classification: **CLAIM** · relevance **RESEARCH ONLY**.

### `20260823-235825` · Robert Audi, epistemology — `P3`
**Contribution** ⟦C⟧ *"a fairly detailed architecture of how knowledge is produced, grounded, transmitted,
preserved, challenged, and socially acquired."*
**Distinctions** ⟦C⟧ *"**Evidence supporting a claim does not automatically mean that the claim has been
adopted as knowledge.**"* · ⟦C⟧ *"Every knowledge claim should **retain the source type** from which its
epistemic status derives."* · three kinds of grounding (causal / justificational / epistemic).
⟦INFERENCE⟧ *Evidence ≠ adoption* is an independent arrival at the corpus's admission distinction
(`S1-F017`'s pipeline) from an external source. **Source-type retention** is a genuinely new candidate:
provenance of *epistemic kind*, not merely of origin — adjacent to `W:C-10` but not the same question.
Classification: **DISTINCTION** ×2 · relevance **POSSIBLE IMPLEMENTATION CANDIDATE** (source-type
retention is a representation question).

### `20260824-000400` · Joseph Shieber, *Theories of Knowledge* — `P3`
**Dispositions** ⟦C⟧ *"The kernel should model the **epistemic object** that is being known."* ·
⟦C⟧ *"The kernel should **not commit philosophically** to either foundationalism or coherentism."*
⟦INFERENCE⟧ The second is a **neutrality requirement** — the Kernel must not encode a theory of
justification structure. That is a constraint on Kernel *content*, and it converges with `S1-F022`'s
refusal to import perspectival truth. Classification: **CLAIM** + **DISTINCTION** · **RESEARCH ONLY**.

### `20260824-001005` · critical-thinking source (2,152 lines) — `P3`
**Refusals** ⟦C⟧ *"**Do not put 'critical thinking' into the KnowledgeOS kernel as a methodology.**"* ·
⟦C⟧ *"The kernel should **not** make `Fallacy` a property of a `Claim`."*
**Contribution** the `Question` / `Inquiry` primitive (recorded separately in the corpus's inquiry thread).
⟦INFERENCE⟧ The `Fallacy` refusal is precise and reusable: a defect detected *about* a claim must not
become an attribute *of* the claim — the same shape as `S1-F014`'s derived-vs-stored problem.
Classification: **REJECT** ×2 · **RESEARCH ONLY**.

### `20260824-001108` · philosophical-lenses integration (Chinese + Tarka + Audi) — `P4`
**Constraints** ⟦C⟧ *"The Kernel **must not silently convert representation into semantic truth**."* ·
⟦C⟧ *"The Kernel must handle **justified false beliefs** differently from knowledge."*
⟦INFERENCE⟧ The first restates ⟦L⟧ v1.1 *Expression ≠ Meaning* / *Representation ≠ Identity* —
**CONSISTENT**. The second is new to Session 1: a *justified false belief* is admissible-but-false, which
neither the corpus's state sets nor its admission tests clearly handle. Bears on `W:C-17` (internally
impossible claim) without being the same question. Classification: **DISTINCTION** (new) ·
**POSSIBLE IMPLEMENTATION CANDIDATE**.

### `20260824-001634` · decision-power source (1,673 lines) — `P3`
**Model** ⟦C⟧ *"**Aims → Information → Evaluation → Decision**"*; decisional space = ⟦C⟧ *"the set of
choices that an actor is actually **permitted and able** to make in a given context"*; core question
⟦C⟧ *"**Who is allowed to decide?**"*
⟦INFERENCE⟧ *Permitted **and** able* separates authorisation from capability — which is exactly the
distinction the governance thread needed and which `S1-F001`/`S1-F002` reached from the measured side
(*records, does not create*). **Fifth arrival at the authority family.** Classification: **MODEL** ·
**RESEARCH ONLY** (decision power is explicitly not placed in the Kernel).

### `20260824-002008` · Nyāya-sūtra lenses — `P3`
**Claims** ⟦C⟧ *"The kernel must preserve **their relationship**, not just the proposition"* (knower /
known / means / knowledge) · ⟦C⟧ *"the Kernel must maintain the relationship between **true and false
cognitions**."*
⟦INFERENCE⟧ Independent external support for the *relationship* genus of Knowledge (KCON-001, Phase 1
original). Classification: **CLAIM** · **RESEARCH ONLY**.

### `20260824-002503` · Quine, *Word and Object* — `P3`
**Claim** ⟦C⟧ *"the kernel must **protect the boundaries between those dimensions**."*
⟦INFERENCE⟧ Quine's contribution in this corpus is *dimension non-collapse*, not ontology — consistent
with the *boring Kernel* principle (`S1-F021`). Classification: **CLAIM** · **RESEARCH ONLY**.

### `20260824-002740` · Wittgenstein, *Tractatus* — `P3` (328 lines)
⟦INFERENCE⟧ Contains **no Kernel disposition sentence** (verified by pattern search). Its distinctions
(*fact ≠ thing*, *sense ≠ truth*, *saying ≠ showing*, *name ≠ proposition*) are already carried in the
corpus's review of the same material and in the non-collapse registers. **No material new finding.**

---

## The recurring pattern ⟦INFERENCE⟧

Across nine sources the disposition is uniform: **take the distinction, refuse the ontology.** Nothing in
this register proposes a Kernel member; several explicitly forbid one (`Fallacy` as claim property,
critical thinking as methodology, philosophical commitment to foundationalism/coherentism).

⟦INFERENCE⟧ That uniformity is itself evidence about Phase 2: the book block is **not** where the corpus's
Kernel models come from. Every competing Kernel formulation recorded so far (`S1-F009`…`F019`) came from
`P4` reasoning rounds or `P1`/`P7` project documents — **not** from book extraction.

---

## New candidates contributed by this register

| Candidate | Source | Class |
|---|---|---|
| **Source-type retention** (epistemic kind of the source, not just origin) | Audi `235825` | POSSIBLE IMPLEMENTATION CANDIDATE |
| **Justified false belief** as a case needing separate handling | `001108` | POSSIBLE IMPLEMENTATION CANDIDATE |
| *Permitted **and** able* (authorisation ≠ capability) | decision power `001634` | RESEARCH ONLY |
| *Knowledge is a first-class category, not computed* | Williamson `235306` | CLAIM — opposes derived treatments |
| *All knowledge is evidence* | Williamson `235306` | CLAIM — cuts against `S1-F018`'s framing |

---

## Relationship to previous Session-1 findings

- **`S1-F014`/`F016`** — Williamson's *first-class, not computed* opposes both derived states and
  administrative identity.
- **`S1-F018`** — *all knowledge is evidence* makes the Entity/VO boundary gradual rather than sharp.
- **`S1-F017`** — Audi's *evidence ≠ adoption* is an independent external arrival at the same pipeline.
- **`S1-F021`** — Quine's boundary-protection claim matches the *boring Kernel* principle.
- **`S1-F001`/`F002`** — the decision-power model is a fifth arrival at the authority family.

### Added by the second-pass depth audit (2026-08-26)

Five documents were batch-marked processed in the first pass but their content never reached an artifact.
Re-read at full depth; recorded here rather than in new artifacts, per the no-duplicates rule.

**`20260825-101801` · Shannon & Weaver — `P3`** ⟦C⟧ *"even more directly useful for KnowledgeOS than I
initially expected"*; ⟦C⟧ Shannon + Wittgenstein *"together provide a surprisingly strong foundation for an
epistemic KnowledgeOS"*; and the disposition ⟦C⟧ *"**not to turn KnowledgeOS into an information-theory
system**."*
⟦INFERENCE⟧ Same *take-the-distinction-refuse-the-ontology* pattern as the rest of the register, and an
**eighth formalism** placed as regime (`S1-F032`). Its `INFORMATION ≠ MEANING ≠ KNOWLEDGE` chain was already
recorded via `S1-F026`'s collapse list; **no new finding**, arrival count only.

**`20260824-154254` · inverse approach — `P4`** ⟦C⟧ *"**After accounting for everything we can explain as
non-knowledge, what statistically persistent structure remains?**"* → ⟦C⟧ *"**Zero = the smallest stable
explanatory structure that remains after statistically accounting for non-knowledge phenomena.**"*
⟦INFERENCE⟧ **This is a genuine gap-filler and the first-pass miss that matters.** It is a *second, and
incompatible, definition of the Zero lens*: `20260824-152415` (`S1-F034`) defines Zero as *"remove prior
structure; ask what can be recovered from the source alone"* — a **subtractive, structural** operation;
this defines it as *"what statistically persists after explaining away non-knowledge"* — a **statistical
residual**. ⚠ Two definitions of the corpus's most-used instrument, 90 minutes apart, neither citing the
other. Registered as an **eighth VOCABULARY COLLISION** (*Zero lens*) and a **contradiction**, unresolved.
⟦INFERENCE⟧ It also inverts the corpus's method: define **not-knowledge** first, infer knowledge as
residual — the only document to propose that direction.

**`20260824-153124` · PRML (third book) — `P3`** ⟦C⟧ *"This third book materially changes the picture"*;
positions PRML for the ⟦C⟧ *"Zero Lens / latent-structure / Dhātu investigation"*.
⟦INFERENCE⟧ Contributes *latent structure inferred from observation* — already recorded twice
(`S1-F014` derived states, `S1-F032` HSMM). **Third arrival, no new finding.**

**`20260824-152955` · statistical learning as companion — `P4`** ⟦C⟧ pairs a ⟦C⟧ *"qualitative vocabulary of
problem-solving operations"* with ⟦C⟧ *"formal machinery for discovering, testing, validating, comparing,
and generalizing patterns."*
⟦INFERENCE⟧ A **qualitative/formal pairing** rather than a claim; consistent with *store the substrate,
compute the measure* (`S1-F038`). **No new finding.**

**`20260824-152254` · Swar Gyan / dhātu — `P3`** ⟦C⟧ *"orthographic decomposition / compositional
extraction"*, ⟦C⟧ *"generative decomposition grammar"*, ⟦C⟧ *"Akṣara/Morphological Candidate Extraction"*.
⟦INFERENCE⟧ Mechanism-altitude linguistic pipeline; the document itself notes this is **not** dhātu
extraction in the classical sense but orthographic decomposition. **Mechanism only; no Kernel disposition.**

⟦INFERENCE⟧ **Audit outcome:** of five under-treated documents, **one contained a material missed finding**
(`154254`, the second Zero-lens definition), three were additional arrivals at existing findings, and one is
mechanism-only. The first pass's batch processing lost **one contradiction**, now recorded.

---

**Status:** OPEN · this artifact is **updated as further book extractions are processed**, not duplicated.
