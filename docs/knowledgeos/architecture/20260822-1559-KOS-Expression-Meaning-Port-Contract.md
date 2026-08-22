# KnowledgeOS — Expression↔Meaning Port Contract

> **Logical Architecture — first deliverable.** The port's **published language**: what may cross the Expression↔Meaning boundary (Article 1.2 ACL), the obligations on every mechanism, the candidate payload vocabulary, the uncertainty and UNKNOWN representation, the evidence that accompanies an interpretation, the trust boundary, the prohibited inferences, and the collision-reporting protocol.
> **Conforms to:** Reference Architecture v1.1 — **CONSOLIDATED (r3) · ⟨r4 annotations⟩** — rendering existing invariants. **No new law · no register row · no new invariant.**
> **Authority:** HPA architectural-direction steering 2026-08-22 (`docs/knowledgeos/reviews/20260822-1559-…-r4-and-Port-Contract-HPA-steering.md`) — *"the first deliverable should be the Expression↔Meaning Port Contract"*, answering the eight questions (Step 4).
> **Altitude note.** This is a **Logical-Architecture contract**: it names the port's published language and the obligations any mechanism honors. Implementation form — message formats, schemas, APIs, classes, storage (DEF-5) — is out of scope.
> **Naming reconciliation.** The HPA's *"Semantic Interpretation Port"* and the architecture's *"Expression↔Meaning Port"* (v1.1 §10 · §5.2 · DEF-2) are the **same boundary** — the port described from the mechanism's side. This contract uses the canonical architectural name and records the HPA's synonym.
> **Status:** ⭐ **DELIVERED — first Logical-Architecture deliverable · PROPOSED · NON-AUTHORITATIVE.** Register **25+4 unchanged** · Constitution **FROZEN** · SNF research **paused** · KOS-SNF-ME v0.4 **gated, not authorized** · implementation **not opened**.

---

## 1 · Purpose and position

The consolidated Reference Architecture states what a mechanism may **not do** at the Expression↔Meaning boundary. It does not state what a mechanism must **declare** when it crosses it — and silence at a port is indistinguishable from success unless the contract requires the declaration. The false-acceptance evidence (a mechanism asserting understanding where grounds were insufficient in ~30% of a small case set) is precisely a mechanism that had no way to declare its own insufficiency.

**This contract closes that gap.** It is the port's published language — the vocabulary and obligations that make the boundary enforceable. It answers the HPA's eight questions and renders obligations that are **each traceable to an existing invariant** (never new law).

**A mechanism declares which statement it is making** — *"determined absent in the candidate"* (`declared determination`) or *"could not determine"* (`declared insufficiency`) — so the core distinguishes the two honestly, without conflating a determinate absence with a provider's inability (AH-1 · AH-3, vocabulary §4).

```
      EXPRESSION  (surface form · any language · any order — carries no epistemic weight)
           │  enters the port
           ▼
   ═════ Expression↔Meaning Port · ACL (Article 1.2) — THIS CONTRACT ═════
           │  mechanism submits a MEANING CANDIDATE only
           ▼
   KnowledgeCore — Verification Port (Article 6) — the only admission path
           │  constitutional admissibility determined at the aggregate boundary
           ▼
   KNOWLEDGE STATE  (VALIDATED · QUESTIONABLE · REJECTED · CONFLICTED · UNKNOWN · ABSENT · FALSE)
```

**The port, not SNF, is architectural** (HPA Step 2). Any mechanism — Pāṇinian-inspired compiler · dependency parser · symbolic parser · LLM semantic parser · human interpretation — feeds the same contract. The mechanisms are replaceable; the boundary is not (§16).

---

## 2 · The six obligations (rendered from v1.1 §10 ⟨A-2⟩)

| # | Obligation on any mechanism crossing the port | Invariant rendered |
|---|---|---|
| **1** | submits a **meaning candidate** only — never an epistemic state, never a verdict | INV-KOS-VERIFICATION-001 · Article 6.3 |
| **2** | carries its **justification path** — premises · rules · assumptions · inference rule; no black box | INV-KOS-VERIFICATION-001 (6.4) |
| **3** | **declares its own insufficiency** — abstention is a first-class output; *"I did not determine this"* is a valid, expected answer | INV-KOS-UNKNOWN-001 |
| **4** | **never proposes or derives a KnowledgeId** — canonical-form equality is not an identity claim | INV-KOS-IDENTITY-001 |
| **5** | **never emits a scalar in place of epistemic structure** — no accuracy-as-confidence, no probability-as-confidence | INV-KOS-DIMENSION-001 · Article 2.3 |
| **6** | mechanism **failure or silence maps to UNKNOWN** — never ABSENT, never FALSE, never a low-confidence accept | INV-KOS-UNKNOWN-001 |

Obligation 3 is the load-bearing one: a mechanism that cannot abstain will assert. The architectural answer is not a better parser — it is a port that will not accept a candidate lacking a declared insufficiency.

The declaration is now **structured (§4)**: one of **two sibling declarations** — `declared insufficiency` (*"I did not determine this"* → UNKNOWN, ⟨C-5⟩) or `declared determination` (*"I determined this about the candidate"* → the core evaluates). A mechanism states, precisely, which it is making.

---

## 3 · The eight questions, answered

### Q1 · What enters the port?

An **expression** — a surface form, in any language, any word order, any medium the port accepts — together with any **context delimiters** the submitter provides (what the expression is about, its setting), so the candidate can be bounded (Article 1.4). 

**What enters is expression, never epistemic content.** No verdict, no epistemic state, no identity may enter through this port. Expression varies freely and carries no epistemic weight (v1.1 §10). The port is one-way: it accepts expressions and returns candidates; nothing written outside returns as knowledge (D-5).

### Q2 · What does the semantic mechanism return?

A **meaning candidate** — a *proposal* about what the expression means — composed of:

| Candidate component | What it is | Status |
|---|---|---|
| **Candidate meaning** | a representation of the intensional content (e.g. a canonical form such as SNF — the encoding is **not fixed here**, DEF-4) | required |
| **Declared insufficiency** | the mechanism's own statement of what it could not determine — abstention is first-class (obligation 3); structured — `insufficiency` (what was not determined × why) vs `determination` (what was determined) | required |
| **Declared determination** | the mechanism's own statement of what it determined about the candidate — e.g. `NO_FILLER`, *"the role/slot is not expressed"* — a determinate negative; present when a determinate claim is made (obligation 2) | required |
| **Justification path** | premises · rules · assumptions · inference rule — the reasoning that produced the proposal (obligation 2) | required |
| **Interpretation metadata** | uncertainty · provenance · transformation evidence (§4) — candidate-side, **never aggregate members** | required |

The mechanism returns **never** an epistemic state, **never** a verdict, **never** a KnowledgeId (obligations 1, 4). The separation stays visible:

```
Semantic mechanism:   "This expression probably means X."        ← a proposal
KnowledgeCore:        "X is UNKNOWN / QUESTIONABLE / VALIDATED / CONFLICTED / …"  ← the domain decides
```

### Q3 · How is uncertainty represented?

As **candidate-side interpretation metadata** — the mechanism's uncertainty about its **own interpretation**, represented as structured metadata. A **Bayesian interpretation probability is permitted** as an expression of interpretation uncertainty (HPA strong-evidence item 7).

It is **never** aggregate **Confidence**. ⟨R-1⟩ Confidence is structured epistemic metadata **assigned inside the boundary**; a mechanism-supplied score — parser accuracy, model likelihood, match strength, interpretation probability — **must never cross the port as Confidence**. No scalar substitutes for epistemic structure (Article 2.3 · obligation 5).

The distinction the contract preserves: **interpretation uncertainty is about the mechanism** (candidate-side); **epistemic uncertainty is the domain's own** (carried by the seven states, in particular UNKNOWN and QUESTIONABLE). A mechanism's *"74% confident"* is a statement about the mechanism, never about the knowledge.

### Q4 · How is UNKNOWN represented?

By **abstention** — the mechanism declares *"I did not determine this meaning"* as a **first-class output** (obligation 3). That declaration maps to **UNKNOWN** (⟨C-5⟩), never to ABSENT, never to FALSE, never to a low-confidence accept.

**Abstention is the `FILLER_UNKNOWN` family** (the `declared insufficiency` declaration — *"a filler may exist, but the provider could not determine which one"*) → **UNKNOWN**. **`NO_FILLER`** (the `declared determination` declaration — *"the representation establishes that the role/slot is not expressed"*) is **not** an abstention: it is a **determinate negative the core evaluates** (Q6 · obligation 2) — never auto-mapped to ABSENT, never emitted by the mechanism (obligations 1 · 6).

The three negative states remain distinct — **unknown** (no grounds) ≠ **absent** (grounds that it does not exist) ≠ **false** (grounds that it is not so) (v1.1 §9). Abstention is not a failure; it is the mechanism honoring Zero's operational reading — *insufficient grounds → do not invent meaning → UNKNOWN*. A mechanism that cannot say *"I did not determine this"* will assert instead; this contract refuses to reward that.

### Q5 · What evidence accompanies an interpretation?

Two things, both required, both **candidate-side**:

1. **Justification path** — premises · rules · assumptions · inference rule · conclusion (Article 6.4 · obligation 2): the reasoning that produced the proposal, never a black box.
2. **Provenance + transformation evidence** — the source expression, the mechanism identity, and the **derivation steps** from expression to candidate — including any normalization / canonicalization steps and their **non-collapse record** (which distinctions were preserved, which collapsed). A normalizer that cannot report its own collapses is hiding the failure class that makes canonicalization a lossy hash.

This evidence accompanies the candidate to the **Verification Port** (Article 6 — the only admission path). The core owns the *record*; mechanisms own the *process* (v1.1 §4.2 Reasoning row).

### Q6 · What may the kernel trust?

The kernel (the aggregate boundary) may trust:

- the candidate's **declared structure** — that it honors this contract: the required fields are present, the insufficiency is declared where it exists, the justification path is preserved;
- the **preservation** of the justification path — that the candidate arrived with its reasoning intact.

The kernel may **never** trust the candidate's **content as truth** — it is not a truth oracle (D-1); it determines **constitutional admissibility**, never truth. It may rely on the declared insufficiency to route a candidate to **UNKNOWN** or **REJECTED** rather than accept it, and it may refuse any candidate that omits the required declarations.

In short: the kernel trusts the **form** of the submission, determines the **admissibility** of the transition, and treats the content as a **proposal** — *"what epistemic state, if any, that representation can participate in"* (HPA Step 1 boundary).

### Q7 · What may the kernel never infer from SNF?

The **six negative-direction prohibitions** (r4) — never, from any SNF / canonical-form output or any similarity, probability, authority or entropy signal derived from it:

| # | Prohibition | Invariant |
|---|---|---|
| 1 | **Representation → Identity** — a canonical form never becomes a KnowledgeId | INV-KOS-IDENTITY-001 |
| 2 | **Similarity → Equality** — structural similarity is never semantic equivalence | INV-KOS-IDENTITY-001 |
| 3 | **Probability → Truth** — an interpretation probability is never a truth verdict | INV-KOS-VERIFICATION-001 · DIMENSION-001 |
| 4 | **Canonicalization → Authority** — normalization confers no authority | INV-KOS-AUTHORITY-001 |
| 5 | **Low entropy → Certainty** — a highly-normalized form is not thereby more certain | INV-KOS-DIMENSION-001 · UNKNOWN-001 |
| 6 | **SNF equality → Knowledge identity** — equality of canonical forms is a similarity claim, never an identity determination | INV-KOS-IDENTITY-001 |

SNF equality therefore means: **a candidate for the same admitted meaning**, offered to an authorized identity-assignment act, which may accept or refuse it (⟨C-1⟩). The SNF family may support identity **reasoning** (as evidence, §8); it never performs identity **assignment**.

### Q8 · How are semantic collisions reported?

When two distinct expressions normalize to the same candidate, the port reports a **candidate-equality observation** — in measurement vocabulary, a **collision**. The **collision rate is a critical failure metric** (HPA strong-evidence item 9), not a quality nicety.

- Collisions are **evidence, never admission** — they may feed an **EvidenceLink** (a candidate-side observation offered to an identity-assignment act — the contract's **position on OQ-2**, which remains subject to HPA rule), and they are **never** read as identity, truth, equivalence, or certainty.
- **Non-collapse is measured in both directions.** A normalizer that collapses *approved/acknowledged* or *sees/believes-he-sees* has not preserved meaning — it has destroyed a distinction. **Semantic invariance without semantic non-collapse is a lossy canonicalization** (the two-sided property).
- Collision reports accompany the candidate and are preserved; they **never by themselves change any knowledge state** — reporting a collision is not a domain event (⟨A-3⟩; nothing in the aggregate changes).

---

## 4 · The candidate payload vocabulary (the port's published language)

All of the following is **port-contract vocabulary** — candidate-side, carried by the submission, **never an aggregate member** (r4-4). None becomes a member, an event, or a state.

| Term | Meaning | Rendered obligation |
|---|---|---|
| **meaning candidate** | the proposal about what the expression means | 1 |
| **declared insufficiency** | the mechanism's statement of what it could not determine — the **`FILLER_UNKNOWN`** declaration, *"a filler may exist, but the provider could not determine which one"* — a **determinate insufficiency** (abstention) → **UNKNOWN** (⟨C-5⟩ · obligation 3) | 3 |
| **declared insufficiency · reason** | why the insufficiency arose — an **orthogonal dimension, insufficiency only** (AH-3): `PARSE_UNAVAILABLE` — cannot parse the relevant token/form · `READING_UNDERDETERMINED` — can parse, but cannot decide the interpretation | 3 |
| **declared determination** | the mechanism's statement of what it determined about the candidate — the **`NO_FILLER`** declaration, *"the representation establishes that the role/slot is not expressed"* — a **determinate negative**, not an abstention; the core evaluates (obligation 2), never auto-mapped to ABSENT, never emitted by the mechanism (obligations 1 · 6) | 2 |
| **justification path** | premises · rules · assumptions · inference rule · conclusion | 2 |
| **interpretation uncertainty** | candidate-side structured metadata; Bayesian interpretation probability permitted, **never Confidence** | 3, 5 |
| **provenance** | source expression · mechanism identity · derivation steps | 2 |
| **transformation evidence** | normalization / canonicalization steps + non-collapse record | 2, Q5 |
| **collision / candidate-equality observation** | two expressions → same candidate; rate = critical failure metric | Q8 |
| **abstention** | *"I did not determine this"* — first-class output → UNKNOWN; exactly the **`FILLER_UNKNOWN`** family (the `declared insufficiency` declaration) — `NO_FILLER` is **not** an abstention | 3, 6 |

---

## 5 · The trust boundary — who owns what

| Level | Owned by | Owner role |
|---|---|---|
| **Expression** | the Expression context (mechanisms, external) | varies freely; no epistemic weight |
| **Meaning candidate** | the mechanism that produced it — **outside the boundary** | proposes; declares insufficiency |
| **Admitted meaning** | KnowledgeCore (member: Meaning) | the intensional content the core holds |
| **Knowledge identity** | KnowledgeCore (member: KnowledgeId) | **assigned here, never derived** |
| **Epistemic state** | KnowledgeCore, determined at the aggregate boundary | VALIDATED … FALSE — **no mechanism may produce this** |
| **Confidence** | KnowledgeCore, **assigned inside the boundary** | structured; no mechanism score crosses the port (⟨R-1⟩) |
| **History** | KnowledgeCore (member: History) | forward-only; nothing overwritten |

The port separates **mechanism-owned** (expression · candidate · metadata) from **domain-owned** (admitted meaning · identity · epistemic state · confidence · history). Nothing crosses from the first column to the second except through the Verification Port, as a candidate with a preserved justification path.

---

## 6 · What this contract does NOT decide

| # | Not decided here | Owner |
|---|---|---|
| 1 | **SNF as the port encoding** — this contract is encoding-agnostic; SNF *may* become the form candidates take (DEF-4) | Logical Architecture, later decision |
| 2 | **Message formats · schemas · APIs · classes · storage** (DEF-5) | Implementation Architecture / Systems |
| 3 | **How the aggregate boundary is realized** — commands, methods, domain services, policy evaluation (DEF-1) | Logical / Implementation Architecture |
| 4 | **The KOS-SNF-ME v0.4 experiment** — not authorized (HPA Step 5); this contract is the boundary *under which* a later v0.4 could be designed (compare SNF-A/B/C · an LLM semantic parser · a human reference; SNF becomes replaceable research) | **HPA act required** |
| 5 | **The KOS-SCB v0.2 experiment** (OQ-4) — unchanged, **unauthorized** | **HPA act required** |

**Open questions — positions at contract altitude (recorded, not closed):**

- **OQ-1 — RESOLVED** (recorded at implementation per the AH-1 ruling · HPA option-C approval, 2026-08-23) — *does declared insufficiency need its own vocabulary?* **Yes.** The declaration is structured into the **sibling pair** — `declared insufficiency` (what was not determined × why) · `declared determination` (what was determined) — with the value-cases in §4. (v1.1 §20 deferred OQ-1 to the Logical Architecture; the Port Contract is that deliverable.)
- **OQ-2** — *may SNF-equivalence be recorded as an EvidenceLink?* The contract's position: **yes, as a candidate-side evidence observation, never as the assignment itself** (Q8). **HPA rule still required.**
- **OQ-3** — *is cross-language sameness about meaning or translation?* The contract treats cross-language same-candidate as **a candidate for the same admitted meaning**, never identity. Recorded; experiment design may decide the translation-vs-meaning question.
- **OQ-5** — *should Confidence remain an aggregate member?* Unchanged by this contract; ⟨R-1⟩ keeps it safe. A later Logical-Architecture decision may revisit it.

---

## 7 · Quality gates

| Gate | Question | Result |
|---|---|---|
| **Identity** | can anything in this contract assign identity? | ✅ **No** — the contract is silent on assignment; KnowledgeId is assigned only at the aggregate root (obligation 4) |
| **Replacement** | can the mechanism be swapped without changing the contract? | ✅ **Yes** — Pāṇinian · dependency · symbolic · LLM · human all feed the same port (§1, v1.1 §11.1) |
| **Non-collapse** | does the contract protect the two-sided property? | ✅ **Yes** — collision rate is a critical failure metric and non-collapse is measured in both directions (Q8) |
| **No new law** | does every obligation render an existing invariant? | ✅ **Yes** — §2 table traces all six; the six prohibitions (Q7) are r4 interpretations of existing invariants |
| **Structure** | does the contract add any member, event, state, or register row? | ✅ **No** — candidate payload vocabulary is port-level, never an aggregate member (r4-4); reporting a collision is not a domain event (⟨A-3⟩); the vocabulary value-cases (`NO_FILLER` · `FILLER_UNKNOWN` · `reason`) are port vocabulary, never members, events, or states (r4-4 · ⟨A-3⟩ · §9) |
| **No-domain-capture (G-4)** | do any of the value-cases become a domain state, member, event, or register row? | ✅ **No** — `FILLER_UNKNOWN` · `NO_FILLER` · `PARSE_UNAVAILABLE` · `READING_UNDERDETERMINED` are port vocabulary only; §9 remains exactly seven states; register **25+4** |
| **Anti-laundering (G-6)** | can a mechanism convert underdetermination into determination? | ✅ **No** — `NO_FILLER` requires its justification path (obligation 2 · Q6); the core retains UNKNOWN/QUESTIONABLE routing for unjustified or contradicted absence claims (obligation 6); every insufficiency carries a `reason` — *"I could not parse it"* cannot masquerade as *"I established its absence"* |

---

## Traceability

- **Commission:** HPA architectural-direction steering 2026-08-22 (`docs/knowledgeos/reviews/20260822-1559-…-r4-and-Port-Contract-HPA-steering.md`) — Step 4: *"the first deliverable should be the Expression↔Meaning Port Contract"*, answering the eight questions; the Semantic Interpretation Port pipeline (Step 2); the six negative-direction prohibitions (Step 3); SNF v0.4 gated behind the boundary (Step 5).
- **Conforms to:** Reference Architecture v1.1 — **CONSOLIDATED (r3) · ⟨r4 annotations⟩** (`docs/knowledgeos/architecture/20260822-1402-KOS-EP01-Reference-Architecture-v1.1-DDD-Bounded-Context-and-Core-Domain-Model.md`) — the six obligations of §10 ⟨A-2⟩ · the Expression↔Meaning Port (ACL, Article 1.2) · the Verification Port (Article 6) · the seven epistemic states · the Confidence boundary rule ⟨R-1⟩ · the UNKNOWN mapping ⟨C-5⟩ · the non-collapse note ⟨C-1⟩ · the event-admission guard ⟨A-3⟩ · the rejected set (§17, r4 rows).
- **Evidence carried in (not created here):** the false-acceptance finding · the semantic non-collapse dimension · Bayesian interpretation uncertainty · collision rate as a critical failure metric · no composite SNF score — all recorded in the HPA steering instrument's §2 (strong evidence) and the second architectural review (`docs/knowledgeos/reviews/20260822-1459-…-Second-Architectural-Review-Semantic-Invariance.md`).
- **Discipline honored:** no new law, article, invariant, aggregate, member, event, or register row · no implementation form (schemas, APIs, classes, storage — DEF-5) · SNF encoding undecided (DEF-4) · no experiment authorized (OQ-4, v0.4) · Constitution satisfied, never extended · the strongest statement never exceeds the evidence.
- **Status:** ⭐ **EXPRESSION↔MEANING PORT CONTRACT — DELIVERED · first Logical-Architecture deliverable · PROPOSED · NON-AUTHORITATIVE.** Register **25+4 unchanged** · Constitution **FROZEN** · SNF research **paused** · KOS-SNF-ME v0.4 **gated** · OQ-2 **position recorded, HPA rule still required** · OQ-4 **unauthorized**.
