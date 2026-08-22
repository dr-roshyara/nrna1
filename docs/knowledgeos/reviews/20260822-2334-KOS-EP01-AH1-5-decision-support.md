# KnowledgeOS — AH-1…AH-5 Decision Support — research evidence → architectural hypotheses → HPA decision (2026-08-22)

> **Role:** a **decision-support document** for the five architectural hypotheses (AH-1…AH-5) recorded in the P5 Semantic Competition report. It lays out, for each hypothesis, the observation, the P5 evidence, the proposed architectural interpretation, what the existing architecture already covers, what would and would not change if the hypothesis were accepted, the constitutional and DDD impact — and leaves the **HPA decision (ACCEPT / REJECT / DEFER) OPEN**.
> **It is not** an engineering prompt · not an architecture change · not a recommendation · not an authorization. It exists so the HPA can make the five architectural decisions *cleanly, without accidentally authorizing implementation*.
> **Source / commission:** Human Principal Architect (HPA), 2026-08-22 — *"The right next artifact is a **decision-support document for AH-1…AH-5**, based only on the P5 evidence … for each hypothesis: 1. Observation 2. Evidence 3. Proposed architectural interpretation 4. What existing v1.1 already covers 5. What would actually change if accepted 6. What would *not* change 7. Constitutional impact 8. DDD impact 9. **HPA decision: ACCEPT / REJECT / DEFER** (left OPEN)."* The HPA: *"I would **not give Claude another engineering prompt yet**."*
> **Position:** after P5 closed (HPA acceptance `c9a7650f`) · before the HPA's rulings on AH-1…AH-5. This document moves nothing; it prepares the decision surface.
> **Evidence basis (restricted):** the P5 report only (`docs/knowledgeos/brainstorming/KOS-SNF-RESEARCH-P5-COMPETITION-001.md`), plus the existing authoritative documents — Reference Architecture v1.1 · Constitution v1.0 · Expression↔Meaning Port Contract · Post-Research Architectural Review. No new research, no new engineering, no new measurement.
> **Status:** 📋 **AH-1…AH-5 DECISION SUPPORT — DELIVERED · PROPOSED · NON-AUTHORITATIVE.** All five decision fields (§1–§5, field 9) **⬜ OPEN** · no authorization conveyed · v1.1 **unchanged** · Constitution **FROZEN** · research **CLOSED** · OQ-4 **unauthorized**.

---

## 0 · How to read this document

**The decision sequence (unchanged, HPA-confirmed at P5 closure):**

```text
Research evidence
       ↓
AH-1 … AH-5          ← this document prepares the decision
       ↓
HPA decision          ← ACCEPT / REJECT / DEFER (field 9, left OPEN)
       ↓
only if accepted
       ↓
architecture refinement   ← a SEPARATE authorized act, never implied by this document
```

**What each decision value would mean here:**

| Decision | Meaning at this altitude |
|---|---|
| **ACCEPT** | the HPA judges the hypothesis's consequence is warranted and **names the next act** (e.g. a Port Contract vocabulary amendment, a research-governance requirement, a modelling deferral). It does **not** perform the act; the act requires its own authorization. |
| **REJECT** | the HPA judges the hypothesis is not warranted — the existing architecture already covers it, or the evidence does not support it. Recorded as a ruling; nothing changes. |
| **DEFER** | the HPA judges the question is real but not yet decidable — remains open (typically with a named owner and condition). |

**Strength-of-evidence discipline (carried through every section).** All P5 evidence is **toy-world**: one verb lexicon, a closed entity set, an author-designed corpus, hand-written gold, one seed (`20260822`). It measures producer behavior against a *declaration*, not against language. **No claim about natural language** — translation · legal · narrative · abstract · institutional · procedural · technical-ontology · metaphor/pragmatics were **excluded by decision** (Q-2). The strongest statement in any section never exceeds this. Accepting an AH sharpens a *question* or a *discipline*; it is not a domain or language fact.

**Field sources.** Fields 1–3 quote the P5 report's AH table verbatim-in-substance. Fields 4–8 cite the authoritative documents only (v1.1 · Constitution v1.0 · Port Contract · Post-Research review). Field 9 is the HPA's, left open.

---

## 1 · AH-1 — UNKNOWN vs NOT_EXPRESSED as separate port declarations

### 1 · Observation
> No producer expresses the UNKNOWN vs NOT_EXPRESSED distinction. (P5 report, AH-1 row; Established item 9: "No producer represents the epistemic/semantic absence distinction at all." — all producers fail **110/110** on the `unknown_vs_not_expressed` family.)

### 2 · Evidence
- **110/110 failures, all producers**, all arbitration policies — the distinction exists in the IR vocabulary and in the repaired metric (four-way gate, 14/14), and is absent from **every** mechanism (P5 Established item 9).
- The metric itself had to be repaired to stop collapsing the two: v0.1 put NOT_EXPRESSED vs UNKNOWN at 0.247 under τ = 0.40; v0.2 separates all four states (0.45–1.00) and the four-way gate is test-pinned (Established items 2–3 · D-3).
- Suggested item 2: a *structural inability to say "I don't know which filler"* may be the common cause of both the `unknown_vs_not_expressed` failure and the high false-acceptance rates.
- *Toy-world scope:* one verb lexicon, hand-written gold, one seed. The 110/110 is evidence the distinction is *hard for these producers*, not that it is impossible in general.

### 3 · Proposed architectural interpretation
> The Port Contract's obligation 3 (*declared insufficiency*) may need to distinguish **"no filler"** from **"filler unknown"** — two different declarations, not one. (P5 report, AH-1 row; decision needed from **HPA · Port Contract stage**.)

### 4 · What existing v1.1 already covers
- **v1.1 §9** — the seven epistemic states are distinct and *none is a degree of another*: *unknown* (no grounds) ≠ *absent* (grounds that it does not exist) ≠ *false* (grounds that it is not so). This is the **domain-side** distinction, fully present.
- **Port Contract obligation 3** — "declares its own insufficiency — abstention is a first-class output; *'I did not determine this'* is a valid, expected answer" (renders INV-KOS-UNKNOWN-001). Today the vocabulary is a **single** "declared insufficiency".
- **Port Contract Q4** — abstention maps to **UNKNOWN** (⟨C-5⟩), never ABSENT, never FALSE, never a low-confidence accept; the three negative states remain distinct.
- **OQ-1** (v1.1 §20 · Port Contract §6) — *does declared insufficiency need its own vocabulary?* The contract currently **adopts "declared insufficiency"** as a single port term and records the question for the HPA. AH-1 is that question, now evidenced.
- **Constitution Article 9** — UNKNOWN SHALL NOT equal ABSENT; structured uncertainty SHALL be preserved, never flattened to a lacuna.

### 5 · What would actually change if accepted
The **Port Contract's candidate payload vocabulary** (§4) would gain a **structured insufficiency declaration** — a mechanism would declare *what it could not determine*, distinguishing *"the slot is absent (I determined there is no filler)"* from *"the filler is unknown (I could not determine it)"*. Concretely: obligation 3's wording, the §4 vocabulary table, and a resolution of OQ-1. It would also set a **future experiment-design requirement**: a mechanism that cannot make the distinction is measurably failing (the metric's four-way gate already scores it). This is a **contract-level wording change**; nothing else.

### 6 · What would *not* change
Reference Architecture v1.1 (§9 already distinguishes the seven states; no wording change needed) · the Constitution (Article 9 already forbids the collapse) · the KnowledgeAggregate · the eleven invariants (no new invariant) · register **25+4** · the domain-side rule (declared insufficiency → UNKNOWN, whatever its content) · no mechanism promoted · no SNF winner.

### 7 · Constitutional impact
**None to any article.** Article 9 (unknown ≠ absent · structured uncertainty preserved) is the *source* of the distinction; the change would render it at the mechanism boundary, where it is currently implicit. **V.3 amendment discipline is not engaged** — this is a Logical-Architecture vocabulary decision (OQ-1), not the addition of a constitutional concept.

### 8 · DDD impact
**Port-vocabulary only.** The insufficiency structure is **candidate-side metadata** — port vocabulary, **never an aggregate member** (r4-4). Owner of the declaration stays the mechanism; owner of the state stays the KnowledgeCore. No ownership change · no context-map change · no aggregate change · no new domain event (⟨A-3⟩ — reporting an insufficiency detail changes nothing in the aggregate).

### 9 · HPA decision: ACCEPT / REJECT / DEFER — **⬜ OPEN** (the HPA fills this)

---

## 2 · AH-2 — agreement is not evidence of sameness (corroboration at scale)

### 1 · Observation
> 0.587 of agreements are agreements on a wrong reading (pairwise false consensus). (P5 report, AH-2 row.)

### 2 · Evidence
- **Established item 7** — "Agreement is not truth, at scale. **0.587** of agreeing mechanism-pairs agree on a wrong reading; the 'with NP → INSTRUMENT' rule makes A/B/C wrong together on 90/90 traps."
- The false-consensus traps are adversarial families in the author-designed corpus; toy world, one seed. The number is a **corroboration**, not a measurement about language.

### 3 · Proposed architectural interpretation
> Corroborates, at scale, that mechanism agreement must **never reach the core as evidence of sameness** — already forbidden by INV-KOS-IDENTITY-001; the number is **corroboration, not a new rule**. (P5 report, AH-2 row; decision needed from **HPA — informational**.)

### 4 · What existing v1.1 already covers
- **INV-KOS-IDENTITY-001** — identity is assigned, never derived; similarity ≠ identity; canonical-form equality is a similarity claim (⟨C-1⟩).
- **Port Contract** — obligation 4 (never proposes or derives a KnowledgeId) · **Q8** (collision = **evidence, never admission**) · Q7 prohibitions 2 & 6 (Similarity → Equality · SNF equality → identity).
- **Post-Research review** — Move 1 / Q7: v1.1 makes **no mechanism-agreement claim at all**; OBS-PR-3: disagreement (`D_mech`) is a **research signal**, not a domain rule, and v1.1 is correctly silent on mechanism agreement.
- **Constitution Article 1** — Similarity SHALL NOT become identity.

### 5 · What would actually change if accepted
**Nothing in the architecture** — the hypothesis is explicitly informational. Acceptance = the HPA records that the 0.587 figure corroborates an existing prohibition and requires no new rule. It may additionally stand as a standing **experiment-design condition** for any future gated mechanism competition (agreement must never be read as sameness — already a Port Q8 principle). If the HPA wishes, the number can be annotated as supporting evidence beside the existing invariant — an **annotation, not a change**.

### 6 · What would *not* change
Everything. v1.1 · Constitution · aggregate · Port Contract · invariants · register **25+4** — all unchanged. The finding is already recorded in the research artifact (Established item 7); the architecture is correct with or without a second recording.

### 7 · Constitutional impact
**None.** Article 1 already states the rule the number corroborates. No article added, weakened, or reinterpreted.

### 8 · DDD impact
**None.** Agreement remains a **measurement-altitude research signal** (`D_mech`), never a domain rule. Whether the port ever needs a vocabulary for *multiple mechanisms' differing candidates* is **OBS-PR-3** — a future Logical Architecture decision, independent of this number.

### 9 · HPA decision: ACCEPT / REJECT / DEFER — **⬜ OPEN** (the HPA fills this)

---

## 3 · AH-3 — parse-level vs reading-level abstention as separate declarations

### 1 · Observation
> Registry ignorance and semantic underdetermination are both scored as "warranted abstention" (SNF-D's definitional artifact). (P5 report, AH-3 row.)

### 2 · Evidence
- **AH-3 row** — the observation concerns SNF-D specifically: its identifier-registry (NSID) abstains when a token is unknown to it, and the measurement scored that and reading-level underdetermination as the **same** "warranted abstention" bucket.
- **Threats-to-validity item 6** — *"abstention_warranted is partly definitional for SNF-D (AH-3)."* The taxonomy separates outcomes; it does not yet separate **why** the mechanism abstained.
- *Toy-world scope*: one producer's definitional behavior on one corpus; the same discipline note applies as in AH-1.

### 3 · Proposed architectural interpretation
> *Cannot parse the word* and *cannot decide the reading* may be **distinct declarations at the port**. (P5 report, AH-3 row; decision needed from **HPA · Port Contract stage**.)

### 4 · What existing v1.1 already covers
- **Port Contract obligation 3** — declared insufficiency, single vocabulary today (same as AH-1).
- **Port Contract Q3** — interpretation uncertainty is the mechanism's uncertainty about **its own interpretation**, candidate-side, never Confidence. The reason-for-abstention would be a structured form of this metadata.
- **Post-Research review §3 / §8** — the **2×2 abstention taxonomy** (K · R · warranted/unwarranted) is a **measurement** instrument at research altitude; the **domain-side** rule (abstention → UNKNOWN, whatever the reason) is ⟨C-5⟩ · Article 9 and is untouched by any finer port vocabulary.
- **OQ-1** (v1.1 §20 · Port Contract §6) — the same open question AH-1 touches: does declared insufficiency need internal structure?

### 5 · What would actually change if accepted
The Port Contract's declared-insufficiency vocabulary would gain a **reason dimension** — the mechanism declares *why* it abstained: parse-level inability (*cannot parse the word*) vs reading-level underdetermination (*cannot decide the reading*). Contract-level wording change only. **AH-1 and AH-3 are adjacent but separable**: AH-1 distinguishes *what was not determined* (filler absent vs filler unknown); AH-3 distinguishes *why the mechanism abstained* (parse failure vs reading underdetermination). Each is independently decidable; if both are accepted they compose, but nothing requires them to move together.

### 6 · What would *not* change
v1.1 · Constitution · aggregate · invariants. The domain-side rule — declared insufficiency maps to **UNKNOWN whatever its reason** (⟨C-5⟩) — is unchanged. No abstention taxonomy becomes a domain object · no mechanism promoted.

### 7 · Constitutional impact
**None.** Article 9 (structured uncertainty SHALL be preserved, never flattened to a lacuna) is the source the richer declaration would render; no amendment. V.3 not engaged (port-vocabulary, not a concept addition).

### 8 · DDD impact
**Candidate-side vocabulary only** (r4-4). The abstention reason is mechanism-owned metadata, never an aggregate member; the state stays domain-owned. No ownership or boundary change · no new event (⟨A-3⟩).

### 9 · HPA decision: ACCEPT / REJECT / DEFER — **⬜ OPEN** (the HPA fills this)

---

## 4 · AH-4 — metric version as part of any measured-competence claim

### 1 · Observation
> A repaired evaluation metric changed measured performance materially. (P5 report, AH-4 row.)

### 2 · Evidence
- **Established items 2–3** — v0.1 collapsed EXPRESSED vs NOT_EXPRESSED (0.330) and NOT_EXPRESSED vs UNKNOWN (0.247); v0.2 separates all four (four-way gate **14/14**). Under v0.1, producers were credited CORRECT on **45/90** false-consensus traps; under v0.2, **0/90**.
- **Established item 4** — v0.1's claimed `d(x,x)=0` was false for UNKNOWN-bearing IRs (measured 0.124); repaired in v0.2.
- A/B baseline sweep `74398c7b7874` (v0.1) vs repaired sweep `9ce9d670cb47` (v0.2) — same corpus, different scores.
- *Interpretation*: the measured competence of a mechanism was **not separable from the metric that scored it**. The metric is a *declared research instrument* (floors chosen, not derived; no triangle inequality claimed — Not-established item 3).

### 3 · Proposed architectural interpretation
> Any future promotion argument must **name its metric version**; a mechanism's measured competence is not separable from the metric that scored it. (P5 report, AH-4 row; decision needed from **HPA — research governance**.)

### 4 · What existing v1.1 already covers
- **Post-Research review §8** — the measurement vector (C, NC, FC, T, K, R, A, H, Cal) lives at the **research/measurement altitude**, where v1.1 is correctly silent; *no new KnowledgeOS dimension is created because a useful research metric exists*.
- **v1.1 §16** — MECHANISM and REPRESENTATION altitudes are **freely changeable**; measurement instruments are mechanism/representation-altitude artifacts, never kernel members.
- **⟨A-3⟩** — a benchmark run is not a domain event; benchmark names are not components.
- **§20 DEF-5 / OQ-4** — the future experiment is **gated / unauthorized**; its design discipline is not yet fixed, so the metric-version naming requirement has no current binding home.

### 5 · What would actually change if accepted
A **research-governance standing requirement**, not an architecture change: any future promotion argument (a mechanism, an SNF encoding, a representation) must **name its metric version, corpus/family scope, and the failure modes that version addresses**; a measured-competence claim carries its scoring instrument as part of the claim. This would be recorded as **governance**, and would take effect when (if ever) a promotion argument is made — i.e. it conditions the future experiment's design (v0.4 / OQ-4, both gated) and any promotion submission.

### 6 · What would *not* change
v1.1 · Constitution · aggregate · Port Contract. No invariant · no altitude change · no element added. The measurement/architecture separation already exists; acceptance only makes the **naming** requirement explicit for future claims.

### 7 · Constitutional impact
**None.** No article engaged. (Indirectly the discipline *protects* Articles 1 and 3 — a metric version cannot smuggle a similarity score or a performance result into an identity or authority claim — but no wording changes.)

### 8 · DDD impact
**None.** Measurement is research altitude; no domain concept · no ownership change. ⟨A-3⟩ stands: a benchmark run is not a domain event.

### 9 · HPA decision: ACCEPT / REJECT / DEFER — **⬜ OPEN** (the HPA fills this)

---

## 5 · AH-5 — asserted vs possible: category boundary or degree?

### 1 · Observation
> Modality is declared DISTINCT in gold but graded by the metric — τ_conv agreement on modality-only pairs. (P5 report, AH-5 row.)

### 2 · Evidence
- **Not-established item** (P5) — "modality/tense/quantifier differences are declared DISTINCT in gold but the metric treats modality as **graded (0.10)**, so candidate pairs differing only in modality still register as agreement at τ_conv — a **known and deliberate tension, unresolved**."
- **OBS-P5-3** — modality graded vs declared DISTINCT: recorded, **not** silently "fixed" (*"changing it would make every hedge a different meaning"*).
- The four-way gate (Established item 2) covers the epistemic/semantic absence states — it does **not** touch modality. No P5 family directly measures asserted-vs-possible equivalence in canonical form; the observation is about the **metric's grading choice**, not about a producer's collapse.
- *Toy-world scope*: the same scope discipline applies.

### 3 · Proposed architectural interpretation
> Whether *asserted* vs *possible* is a **category boundary or a degree** is a **modelling** question the research cannot settle. (P5 report, AH-5 row; decision needed from **HPA — OQ-3 territory**.)

### 4 · What existing v1.1 already covers
- **v1.1 §9** — the seven epistemic states are distinct — but modality (asserted/possible) is **inside meaning**, not one of the seven states; v1.1 is **silent** on how a canonical form must represent modality.
- **DEF-4** (v1.1 §20 · Port Contract §6) — SNF as the port encoding is **undecided**; the canonical form's treatment of modality is therefore **unmodelled**.
- **Constitution Article 9** — structured uncertainty SHALL be preserved — a claim about epistemic states, arguably extending to meaning-structure, but it does not settle asserted-vs-possible.
- **Port Contract Q3 / Q4** — interpretation uncertainty is candidate-side; abstention maps to UNKNOWN — neither settles the modelling question.
- **OQ-3** (v1.1 §20) — *is cross-language sameness a claim about meaning or about translation?* AH-5 is the **within-language** sibling: is *asserted-here* vs *possible-here* the same meaning or a different meaning?

### 5 · What would actually change if accepted
Two distinct readings of "accepted" — the document presents both so the HPA can choose or defer:

- **Reading A — modelling deferral** (the P5 hypothesis's own framing). The HPA rules that asserted-vs-possible is a **modelling decision for the future Logical Architecture / experiment-design step**, not settled now. No architecture change; the tension stays recorded (OBS-P5-3); the future canonical form (DEF-4) will need to **name its modality treatment**. This is the lower-consequence reading.
- **Reading B — domain ruling**. The HPA rules that asserted and possible are **distinct meanings**. Then a canonical form that equates them **collapses a distinction** — a ⟨C-1⟩ two-sided-property violation — and the future experiment's metric must treat modality as a **category boundary, not a grade**. This is the stronger consequence and would cascade into DEF-4 and the future metric design.
- *Evidence asymmetry:* **P5 does not by itself require Reading B** — the report explicitly says the research cannot settle the question. The observation evidences a *metric choice*, not a *meaning collapse*.

### 6 · What would *not* change
In **both** readings: the seven epistemic states · the aggregate · the invariants · register **25+4**. In **Reading A**: nothing changes at all. In **Reading B**: v1.1 itself still does not change — the ruling would govern the future canonical form's design and the metric, both at MECHANISM / REPRESENTATION / research altitude — though it would shape DEF-4.

### 7 · Constitutional impact
**None** in Reading A. In Reading B: still no article change — Article 9 (structured uncertainty preserved) would *support* treating asserted/possible as distinct, but nothing in the Constitution forces the modelling choice. **No amendment is engaged either way** (V.3 not implicated).

### 8 · DDD impact
**None** in Reading A. In Reading B: the modelling choice lives in the **candidate meaning representation** (DEF-4) — a REPRESENTATION-altitude projection, regenerable and non-authoritative; no ownership change · no aggregate member · no new event (⟨A-3⟩).

### 9 · HPA decision: ACCEPT / REJECT / DEFER — **⬜ OPEN** (the HPA fills this)

---

## 6 · The decision surface

| AH | Decision needed | Altitude / owner of the change it would unlock | Adjacent open questions it touches |
|---|---|---|---|
| **AH-1** | HPA · Port Contract stage | Logical Architecture — Port Contract vocabulary (obligation 3 · §4 · OQ-1) | **OQ-1** |
| **AH-2** | HPA — informational | none — corroboration only | OBS-PR-3 (future disagreement representation) |
| **AH-3** | HPA · Port Contract stage | Logical Architecture — Port Contract vocabulary (OQ-1) | **OQ-1** · 2×2 abstention taxonomy (measurement) |
| **AH-4** | HPA — research governance | research governance — future experiment design | OQ-4 (gated) · v0.4 (gated) · F-1…F-5 |
| **AH-5** | HPA · OQ-3 territory | modelling — DEF-4 (port encoding) · future metric design | **OQ-3** · DEF-4 · OBS-P5-3 |

**Already waiting, not produced by P5** (recorded for the same decision act, not decided here): **OQ-2** (may SNF-equivalence be recorded as an EvidenceLink — Port Contract takes a position, HPA rule required) · **OQ-5** (should Confidence remain an aggregate member) · **F-1…F-5** (Port Contract wording candidates from LA Review 01, gated on HPA approval). The HPA may rule on these together or separately; this document neither bundles nor pre-empts them.

---

## 7 · What this document does NOT do

- **Does not decide anything.** All five field-9s are **⬜ OPEN**.
- **Does not authorize any implementation.** Accepting an AH would *name a next act* (e.g. a Port Contract vocabulary amendment for AH-1/AH-3, a governance requirement for AH-4, a modelling deferral for AH-5) — and that act requires its **own** authorization. No v1.1 change · no Constitution change · no aggregate change · no experiment · no Kernel work follows from this document.
- **Does not manufacture a v1.2.** If the HPA concludes the stronger statements are already represented in v1.1, the correct outcome remains the Post-Research review's **NO CHANGE**.
- **Does not reopen the research phase.** Research **CLOSED** · **OQ-4 stays unauthorized**.
- **Does not promote any mechanism, select an SNF winner, or create a composite authority score.**

---

## Traceability

- **Commission:** HPA, 2026-08-22 — decision-support document for AH-1…AH-5, based only on the P5 evidence (+ v1.1 · Constitution v1.0 · Port Contract · Post-Research review), nine-field structure per hypothesis, field 9 (ACCEPT/REJECT/DEFER) left **OPEN**, no engineering prompt.
- **Evidence source:** `docs/knowledgeos/brainstorming/KOS-SNF-RESEARCH-P5-COMPETITION-001.md` — Part I, AH table (lines 236–248) · Established items 2–4, 7, 9 · Not-established items (modality tension) · Threats-to-validity item 6 · OBS-P5-3 · reproducibility block (digests `e9fb867903fb` · `9ce9d670cb47` · `74398c7b7874`).
- **Authoritative grounding:** Reference Architecture v1.1 — `docs/knowledgeos/architecture/20260822-1402-KOS-EP01-Reference-Architecture-v1.1-DDD-Bounded-Context-and-Core-Domain-Model.md` (§7 invariants · §9 seven states · §10 ⟨A-2⟩ obligations · §14 ⟨C-5⟩ · §15 ⟨C-1⟩/⟨R-1⟩ · §16 altitudes · §19 gates · §20 DEF-4/OQ-1…OQ-5) · Constitution v1.0 — `docs/knowledgeos/architecture/20260822-0951-KOS-EP01-Constitution-v1.0.md` (Articles 1 · 3 · 6 · 9; V.3 amendment discipline) · Expression↔Meaning Port Contract — `docs/knowledgeos/architecture/20260822-1559-KOS-Expression-Meaning-Port-Contract.md` (obligations 3–6 · Q3/Q4/Q7/Q8 · §4 vocabulary · §6 OQ-1…OQ-5) · Post-Research Architectural Review — `docs/knowledgeos/reviews/20260822-1658-KOS-EP01-Reference-Architecture-v1.1-Post-Research-Architectural-Review.md` (NO CHANGE verdict · Move 1–3 · §8 measurement classification · OBS-PR-1…3).
- **Predecessor HPA acts on this chain:** P5 HPA acceptance + closure (`docs/knowledgeos/reviews/20260822-2327-KOS-EP01-P5-semantic-competition-HPA-acceptance.md`, commit `c9a7650f`) · P5 plan + EP-02 completion review (`docs/plans/20260822-1856-kos-snf-p5-semantic-competition-refinement-plan.md` §16, commit `32554cbd`) · P5 execution (commit `92d7e5eb`) · Post-Research review HPA acceptance (`20260822-1711`, NO CHANGE).
- **Discipline honored:** advisory only — no recommendation, no engineered decision · the strongest statement never exceeds the evidence (all P5 evidence is toy-world) · research/production separation maintained (nothing outside `docs/knowledgeos/` was touched) · no new law, invariant, aggregate, member, event, or register row proposed · Constitution satisfied, never extended · every acceptance consequence is stated as *an act that would itself require authorization* · **next step = the HPA's ruling** on AH-1…AH-5 (and, at its discretion, the already-waiting OQ-2 · OQ-5 · F-1…F-5).
- **Status:** 📋 **AH-1…AH-5 DECISION SUPPORT — DELIVERED · PROPOSED · NON-AUTHORITATIVE.** Decision fields **⬜ OPEN** · Reference Architecture v1.1 **unchanged** · Constitution **FROZEN** · register **25+4 unchanged** · research **CLOSED** · OQ-4 **unauthorized**.
