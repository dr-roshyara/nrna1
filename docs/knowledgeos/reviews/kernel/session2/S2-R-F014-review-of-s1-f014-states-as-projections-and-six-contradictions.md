# Session-2 Review — S1-F014

## 1 · Source artifact
`session1/S1-F014-epistemic-states-as-derived-projections-of-relationships-and-assessments.md`

## 2 · What Session 1 claims
(1) **States are derived, not managed** — *"Epistemic states are not independent properties of a claim. They are projections of relationships and assessments"* — a direct answer to `S1-F013`'s blocker. (2) Six **named internal contradictions**, the corpus's first self-contradiction register. (3) The KnowledgeAggregate as smallest consistency boundary (§13), **refused** by the project — and internally inconsistent with §7.

## 3 · Evidence classification
Quotations **FACT**. Finding 1 **HYPOTHESIS**. The six contradictions **FACT as recorded** (the document names them). §13 **HYPOTHESIS + REFUSAL**.

## 4 · Question type
Finding 1 is **TYPE/classification** — the same altitude as `S1-F013` Finding 2, which is why they can genuinely engage. Finding 2 is a **register**, not a claim. Finding 3 **BOUNDARY**.

## 5 · DDD / architectural altitude
`SUPERSEDED`/`RECONCILED`/`CONTESTED` → candidate **relationships**; `INSUFFICIENT_EVIDENCE` → candidate **assessment**; epistemic state → candidate **derived attribute**, not a stored member; `EvidenceReference` vs evidence content → candidate **VO vs external artefact** (new, bears on `W:C-10`).

## 6 · Provenance assessment — **CONFIRMED, and it contains a genuine methodological advance**
Session 1: *"Two models answering one identical prompt two minutes apart. Where they agree that is **shared-prompt convergence, not independent corroboration**; where they **disagree** — and they do, sharply — **that is more informative**."*

That second clause is new to the record and correct. I extend it: under a shared prompt, agreement is **confounded**, but disagreement is positive evidence that **the prompt did not determine the answer** — i.e. evidence of genuine underdetermination in the material. This is the first asymmetric use of shared-prompt lineage in the corpus, and it is sound.

## 7 · Zero-lens assessment
✅ **Session 1's vocabulary work here is its best.** It flags a **fifth sense of *projection*** — *derived attribute* — distinct from CQRS read-model, epistemic projection of a Knowledge Space, participant projection and measure-theoretic projection, with the instruction *"Do not merge with any earlier sense."* That is `S1-F002`'s discipline applied correctly and preemptively.

The v1.1 tension is marked **POTENTIAL TENSION**, *"not a defect claim, not adjudicated"*, with the note that the document never references v1.1. Correct class.

## 8 · Contradiction test — and Finding 2 supplies **independent support for `S2-F018`**
Session 1 reads the *Admission vs Lifecycle* contradiction as *"precisely the `S1-F009` ↔ `S1-F010` disagreement … the corpus itself frames F009/F010 as a **dilemma**, not a mistake."*

Read the corpus's actual wording: *"Admission is the first transition. **If** the Kernel owns only admission, who owns the rest of the lifecycle? **If** it owns the lifecycle, is it a God Aggregate?"*

**That presupposes both propositions.** It takes *admission is the first transition* (F010) as given and *the Kernel owns admission* (F009) as a live option, then asks what follows. A dilemma about consequences is only formulable if the two premises are **jointly holdable** — which is exactly `S2-F018`'s dissolution.

⚠ **This is the register's first genuine independent convergence.** The source document is 2026-08-23; `S2-F018` was written 2026-08-25 from `S1-F009`/`S1-F010` alone, before `S1-F014` existed in the directory I was reading. Different routes — I used F010's *"independently of the Kernel"* framing sentence; the document simply reasons from both premises at once. **POSSIBLE INDEPENDENT ARRIVAL**, and I label it no more strongly than that.

So Session 1's reading is right in substance and understated in force: the corpus does not merely decline to call F009/F010 a mistake — it **treats them as compatible** and moves to the consequence.

## 9 · What survives
Finding 1 as a hypothesis. Finding 2 **entirely** — a self-contradiction register is a research instrument the corpus otherwise lacks. The `EvidenceReference` / evidence-content split. Session 1's §6 asymmetry and §7 vocabulary flag. And the §7-vs-§13 internal inconsistency, which Session 1 caught: *"§13 says the Kernel **is** the KnowledgeAggregate holding Epistemic State, while §7 says the Kernel should **not** manage states. The document contains both."* ✅

## 10 · What is challenged
Little. This is the best-handled artifact reviewed so far. One note: *"answers the blocker … `both, with the relationship primary`"* — the document **proposes** an answer; `S1-F013` two minutes earlier says UNRESOLVED. Session 1 records this as *"a genuine disagreement, not reconciled"* — correct, and per §6's own principle this disagreement is the **informative** kind.

## 11 · Implementation relevance

### Candidate A · States derived rather than stored
**Kernel test:** passes in the sense that *something* must let you explain a state — but the choice between stored and derived is a **modelling** decision, not a guarantee.
**Decision → DO NOT IMPLEMENT.** Three grounds: it contradicts v1.1 §9, which makes the seven states **first-class, enumerated, and transitioned forward-only** with *"no mechanism may ever produce this member"* (l. 213); the document is internally inconsistent on it (§7 vs §13); and changing whether states are first-class **is a law change**, hence adjudication. **NOT ADJUDICATED.**

### Candidate B · Relationship preservation as a Kernel responsibility
*"The Kernel should preserve relationships … and assessments."*
**Kernel test:** without preserved relationships you cannot explain *why* a claim is superseded or contested — only *that* it is. Real.
**Existing protection:** partial — `TemporalValidity` carries `superseded-by` (l. 214), and conflicts have their own `ConflictRecord` aggregate. Whether *contestation* and *reconciliation* relationships are preserved as relationships is not established by what I have verified.
**Decision → NEEDS FURTHER EVIDENCE.** Trigger: the first requirement to answer *"superseded by what, and contested by whom?"* rather than *"is it superseded?"*

### Candidate C · The six-contradiction register
**Decision → PRESERVE AS KNOWLEDGE ONLY**, strong form. It is a **research instrument**: each of the six is a well-posed dilemma with both horns stated, and three map onto live workbook items. Its value is in structuring later adjudication, not in code.

### Candidate D · §13's aggregate-as-smallest-boundary
**Decision → DO NOT IMPLEMENT.** The project refused it, and `S1-F016` falsifies it nine minutes later on its own test.

## 12 · Standing hypothesis (§17) — recorded per `X-004` as CONSISTENT/INCONSISTENT only
**CONSISTENT.** *Preserve relationships and assessments; derive states* is a preservation architecture. No support claimed — see `X-004`.

## 13 · Open questions
Are states stored or derived — and is that a law question rather than a research one? · Where is the evidence reference/content boundary (`W:C-10`)? · If evidence is outside, how is confidence assigned (`W:C-11`)?

## 14 · Confidence
**High** on §8 and §6. **High** on Candidates A, C, D. **Medium** on B. Level 1 **UNVERIFIED**.

## 15 · Final Session-2 assessment
The strongest Session-1 artifact reviewed to date: correct provenance asymmetry, a preemptive vocabulary-collision flag, a caught internal inconsistency, and a contradiction register that is a genuine instrument. Its *Admission vs Lifecycle* entry independently reaches `S2-F018`'s dissolution from the other direction. **Zero IMPLEMENT; two refused, one preserved, one evidence-gated.**
