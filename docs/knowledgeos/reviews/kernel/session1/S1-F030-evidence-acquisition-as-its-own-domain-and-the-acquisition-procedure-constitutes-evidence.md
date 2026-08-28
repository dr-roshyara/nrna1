# S1-F030 · **The acquisition procedure determines whether an observation can function as evidence** — evidence acquisition proposed as its own domain, plus the association/intervention distinction

**Finding ID:** S1-F030
**Finding class:** CLAIM (constitutive acquisition) + DESIGN PROPOSAL (acquisition as a domain) + DISTINCTION (association vs intervention) + a modelling discipline rule
**Status:** OPEN
**Implementation relevance:** **POSSIBLE IMPLEMENTATION CANDIDATE** (acquisition metadata on evidence); **RESEARCH ONLY** for the causal apparatus
**Lenses:** Evidence · Zero · Chinese lenses (applied in-document) · Justification · Temporal

---

## Source and provenance

| | |
|---|---|
| **Source documents** | `20260824-033419-book-of-evidence-evidence-acquisition-is-its-own-domain.md` (1,078 l) — primary; `20260824-033614-zero-and-chinese-lenses-on-minimum-structure-before-evidence.md` (964 l); `20260824-032635-causal-inference-what-if-association-versus-intervention.md` (1,052 l); `20260824-032927-causal-inference-through-zero-ddd-and-wisdom-lenses.md` (1,196 l) |
| **Source works** | *The Book of Evidence*; Hernán & Robins, *Causal Inference: What If* |
| **Provenance** | **`P3` BOOK_EXTRACTION** with `P4` lens application. ⚠ ⟦VERIFIED⟧ 0 URLs |
| **Date / phase** | 2026-08-24 **03:34 – 03:36** and **03:26 – 03:29** · Phase 2 |

---

## Finding 1 · Acquisition is constitutive of evidential status ⟦CLAIM — and it is consequential⟧

⟦C⟧ *"**The acquisition procedure itself determines whether an observation can function as evidence.**"*
⟦C⟧ The framing question: *"**How does KnowledgeOS deliberately create, acquire, select, preserve, and
qualify evidence in the first place?**"*
⟦C⟧ And from the Zero/Chinese reading: *"**'What happened, and how could we know that it happened?'**"* ·
⟦C⟧ *"What kind of relationship must exist between **the thing observed, the situation in which it appears,
the observer, and the action that follows**?"*

⟦INFERENCE⟧ **This is a stronger claim than anything else in the corpus about evidence.** Elsewhere evidence
is treated as material that *arrives* and is then admitted (`S1-F008` *evidence admission*; `S1-F018`
*"enters the domain at the gate"*). Here evidential status is **constituted by how it was obtained** — so
two identical observations can differ in evidential standing.

⟦INFERENCE⟧ Consequences the corpus has not recorded:
- It makes **acquisition provenance a precondition of admissibility**, not merely a desirable attribute —
  which is a different requirement from Audi's *source-type retention* (`S1-F024`) and finer than
  ⟦L⟧ v1.1's justification-path obligation.
- It intersects `S1-F029`'s construction/validation role constraint: if acquisition constitutes
  evidential status, then **role and acquisition together** determine what an evidence reference must
  carry (`W:C-10`).
- It sharpens `S1-F018`'s Entity/Value-Object dilemma once more: an evidence **Entity** shared across
  claims carries **one** acquisition history, which may qualify it for one use and not another.

⟦L⟧ Comparison target only: v1.1 admits evidence through the Verification Port with a preserved
justification path, but says nothing about *acquisition procedure* as a condition of evidential function.
Classified **NOT ADDRESSED**; recorded as a research claim, **not** a gap assertion.

---

## Finding 2 · Evidence acquisition proposed as its own domain ⟦DESIGN PROPOSAL⟧

⟦INFERENCE⟧ The document's own title states the proposal — *evidence acquisition is its own domain* — and
the framing question above is a **domain-scoping** question (create / acquire / select / preserve /
qualify are five distinct verbs).

⟦INFERENCE⟧ Against the corpus: `S1-F011` excluded **Evidence Acquisition** as a mechanism (⟦C⟧ *"the domain
admits evidence, but does not acquire it"*), and `S1-F006` placed measurement at mechanism altitude. This
document proposes the opposite direction — acquisition as a *domain* in its own right. ⚠ **Direct tension,
recorded, unresolved.** ⟦INFERENCE⟧ Note the tension is partly dissolvable by `S1-F028`'s extent/contents
distinction: acquisition could be its own **bounded context** (a domain elsewhere) while remaining outside
the **Kernel's** extent. Neither document says so.

---

## Finding 3 · Association ≠ intervention ⟦DISTINCTION⟧

⟦C⟧ *"**A mechanism for distinguishing 'the data show a relationship' from 'the evidence supports that
changing X would change Y.'**"*
⟦C⟧ Framing: *"What epistemic problem exists if KnowledgeOS observes thousands of statements, events,
decisions and outcomes?"*

⟦INFERENCE⟧ A **new non-collapse** for the register: *observed association* ≠ *intervention warrant*. It is
the causal analogue of `S1-F027`'s inference-licensing family — another prohibited inference, from
correlation to actionability. ⟦INFERENCE⟧ Its practical bite is on **decision usefulness**: a claim may be
well-evidenced as association and still not license action, which the corpus's confidence discussions
(`W:C-11`) never separate.

---

## Finding 4 · A modelling-discipline rule ⟦CLAIM — reusable⟧

⟦C⟧ *"**Do not automatically turn every noun into a domain object.**"*
⟦C⟧ Paired with the scoping question *"What is the domain of causal reasoning?"*

⟦INFERENCE⟧ This is the corpus's most compact statement of the failure mode it repeatedly commits: the
eight Kernel formulations and four member lists were largely built by promoting nouns (identity, evidence,
confidence, history) to members. ⟦INFERENCE⟧ It is the same discipline as KCON-012 (atomicity, not
relatedness) and `S1-F023`'s *ativyāpti* test, stated in one sentence and applicable at modelling time
rather than review time.

---

## Finding 5 · From the misfiled EKS baseline: two governance rules ⟦CLAIM⟧

`20260824-032219-eks-current-architecture-baseline-reconstruction.md` (2,098 l) — ⚠ census C12 flagged this
as **misfiled**: an architecture-baseline reconstruction by type, not brainstorming. Two extractable rules:
⟦C⟧ *"**No new architecture without an activation event.**"*
⟦C⟧ *"**Deterministic does not mean demanded.**"*

⟦INFERENCE⟧ The second is directly relevant to the unruled **`W:C-2`** (determinism: property, test, or
DEF-1?) and to `C-K3` in the ledger — the Phase 1/external contradiction over whether the Kernel is a
*deterministic runtime*. *Deterministic does not mean demanded* is a third position: determinism may be
**available without being required**. Recorded as a candidate resolution shape for `C-K3`; ⚠ **not applied,
and not adjudicated** — the document is a baseline, not a ruling on the Kernel.

---

## DDD interpretation

- Acquisition procedure → **CANDIDATE constitutive precondition** of evidential status; **CANDIDATE
  attribute set** on an evidence reference.
- Evidence acquisition → **CANDIDATE BOUNDED CONTEXT** (proposal), in tension with its exclusion as a
  mechanism.
- *association* vs *intervention warrant* → **CANDIDATE NON-COLLAPSE** (new).
- *Do not turn every noun into a domain object* → **CANDIDATE modelling policy**.
- *Deterministic ≠ demanded* → **CANDIDATE reading for `W:C-2`**.

---

## Relationship to previous Session-1 findings

- **`S1-F011`** — direct tension: acquisition excluded as mechanism there, proposed as a domain here.
- **`S1-F018`** — third additional cost on the Entity horn (single acquisition history, multiple uses).
- **`S1-F029`** — acquisition and role together define what an evidence reference must carry (`W:C-10`).
- **`S1-F027`** — association/intervention is a further member of the inference-licensing family.
- **`S1-F016`/KCON-012** — *don't promote nouns* is the modelling-time form of the atomicity criterion.
- **Ledger `C-K3`** — *deterministic ≠ demanded* offers a third position on the determinism contradiction.
- **`S1-F028`** — extent/contents could dissolve the F011↔F030 tension; neither document invokes it.

---

## Classification, confidence, open questions

**Type:** CLAIM (constitutive acquisition) · DESIGN PROPOSAL (acquisition domain) · DISTINCTION
(association/intervention) · CLAIM (modelling discipline) · CLAIM ×2 (governance rules from a misfiled
baseline).
**Confidence:** high on quotations; **medium** on the acquisition-as-domain proposal (single source, and
contradicted elsewhere in the corpus).
**Open questions:** is acquisition a domain, a mechanism, or a bounded context outside the Kernel's extent? ·
must an evidence reference carry acquisition procedure **and** role? · does *deterministic ≠ demanded*
resolve `C-K3`, or merely restate it?

**Status:** OPEN. Nothing adopted; the F011↔F030 tension left standing.
