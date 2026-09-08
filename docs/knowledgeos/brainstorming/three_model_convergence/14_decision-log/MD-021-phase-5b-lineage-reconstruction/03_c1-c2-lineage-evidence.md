# Phase 5B — C1 and C2 Lineage Evidence

## Part 1 — The C1 lineage scaffold, every arrow classified

The candidate scaffold `EKS → PKS → engineering KnowledgeOS → portability → evidence → provenance →
governance → Kernel → Knowledge-state questions → later formalizations` is tested arrow by arrow, per
the authorization's own instruction that this is "only a candidate scaffold" and "never turn the
scaffold into a historical narrative unless the documents support it."

| Arrow | Classification | Evidence |
|---|---|---|
| EKS → Kernel (direct) | **A — explicitly evidenced transition** | Seq 0075 ("How to Change EKS into KnowledgeOS Kernel") and seq 0078 ("How to Integrate Current EKS into KnowledgeOS Kernel," revised after 0077) — both titles are themselves explicit derivation statements (Level 1). |
| EKS → PKS | **C — chronological association only** | No document found in this phase's own bounded search states "EKS became PKS" or "EKS was extended into PKS." Seq 0078's own open question treats EKS and PKS as *co-existing* inputs to a shared kernel ("smallest stable technology-neutral kernel hosting both EKS and PKS"), not as a derivation of one from the other. |
| portability → evidence → provenance → governance (the DDD architecture evolution) | **A — explicitly evidenced transition** | Seq 0057→0058→0061→0066-0069's own `refines` fields (Level 2, already used in Phase 4's own Cluster 1 narrative) explicitly chain these concerns through successive architecture revisions. |
| Kernel (the `kernel/`-directory Boundary-Discovery cycle) → Knowledge-state questions (the `phase_measure_theory/` arc) | **C, tending toward D — chronological/lexical association only, explicitly NOT demonstrated as a derivation** | This is the single most consequential negative finding of this phase (see `02_kernel-lineage.md`'s own closing summary): **not one `REFINES`/`DERIVES_FROM`/`SUPERSEDES` relationship was found crossing between the `kernel/` subdirectory family and the `phase_measure_theory/` subdirectory family** among the 12 objects this phase graphed. The two research regions run on substantially separate tracks, sharing vocabulary (`Kernel`, `K_t`) without a demonstrated derivation between them. |
| Knowledge-state questions → later formalizations (within `phase_measure_theory/` itself) | **A — explicitly evidenced transition** | Already established, and re-cited (not re-derived) from Phase 4's own concept register §F — the six-formulation `K_t` refinement chain (seq 0446→0461→0464→0469→0479→0480) carries explicit `refines` linkage at every step. |

**Overall finding**: the scaffold holds as an evidenced derivation chain for two of its five arrows
(the DDD-architecture-evolution segment and the within-`phase_measure_theory` refinement segment),
holds only as chronological association for one arrow (EKS→PKS), and **explicitly does not hold** —
contrary to what a narrative reading might assume — for the arrow connecting the engineering-Kernel-
discovery cycle to the Knowledge-state-formalization arc. **This scaffold must not be read as a single
continuous historical narrative** — it is, at best, three independently-evidenced sub-chains with two
gaps between them.

## Part 2 — C2 lineage, reconstructed independently of the sole classified C2 file

**Method**: mechanical, complete-population search (Level 2) for protocol.md's own C2-defining
vocabulary (`Knowledge Space`, `Buddhi`, `purification`, `Moksha`, `K_t`, `admissibility`) across all
1,185 `PRIMARY`-tier rows, then a **defined sample of exactly the five earliest-occurring files**
(one per term where distinct) — read for content, not the full up-to-396-file populations.

### ⚠️ Correction applied during this phase's own verification, before this artifact was finalized

The initial content check of the five earliest-occurring files found that **four of the five —
`K_t` (seq 0069), `Buddhi`/`purification` (seq 0080), and `Moksha` (seq 0086) — are false positives**
of the census methodology. Direct inspection of the per-file records' own surrounding text shows:

- **Seq 0080**: the terms "Buddhi"/"purification" appear only inside the classifier's own forward-
  looking note — *"a possible cross-model bridge to C2's Buddhi/purification epistemic-state language
  once C2 material with a comparable pipeline is found — **not yet supported by any C2 corpus
  evidence**."* The file's own actual content has nothing to do with these terms.
- **Seq 0086**: the per-file record **explicitly states the opposite of a positive occurrence** —
  *"no guṇas, Buddhi, purification, or Moksha appear, so the deeper C2 vocabulary this research's
  absence tally has tracked since Ledger Entry 10 remains entirely unseen."* This is a documented
  **absence**, not a presence — the census's simple keyword match cannot distinguish a term's
  occurrence from a negated statement about its non-occurrence.
- **Seq 0069**: the `K_t` hit is a hedged lexical-resemblance note — *"the corpus's closest LEXICAL
  approach yet to a K_t-shaped construct. **NOT flagged as a bridge on lexical grounds alone**
  (MD-012). Watch item: if C2 material later defines K_t... check for structural inheritance, not
  shared wording."* — an explicit non-claim, not a positive occurrence.
- A second-tier check (the next-earliest hits, seq 0081/0082) shows the **identical pattern repeats**
  — both are further "possible bridge... no C2 evidence exists yet" notes, not content occurrences.

**This is reported as a genuine, first-class methodological finding of Phase 5B, not swept aside**:
a simple keyword census over per-file records cannot, by itself, distinguish genuine content
occurrence from (a) a classifier's own hedged forward-reference to vocabulary that does not yet
exist in the file being classified, or (b) an explicit statement of the term's *absence*. **Every
claim in this artifact that rests on a keyword-census "occurrence" has been re-verified against its
actual surrounding text before being retained below** — this is exactly the discipline the
authorization's own methodological principle demands ("a shared term is evidence of lexical overlap...
not identity" — extended here to "a keyword match is not evidence of occurrence").

### Corrected findings, verified genuine (not census artifacts)

| Term | Corpus-wide `PRIMARY`-tier keyword-hit count (census, uncorrected) | Earliest **verified genuine** occurrence | Classification |
|---|---:|---:|---|
| `admissibility` | 57 | seq 0143 — genuine, substantive: *"the Governance Interpreter evaluates candidate interpretations... and determines admissibility for the requested act"* | `engineering_knowledgeos` |
| `Knowledge Space` | 125 | seq 0266 — genuine, substantive: *"Knowledge Space = explicit + implicit + tacit + inexpressible/currently-unrepresented"* | `engineering_knowledgeos` |
| `K_t` | 388 | **undetermined by this phase** — the earliest hit (0069) is a false positive; the true earliest genuine occurrence was not located within this phase's own bounded re-check | — |
| `Buddhi` | 94 | **undetermined by this phase** — the two earliest hits checked (0080, 0082) are both false positives (forward-reference notes); not further pursued | — |
| `purification` | 60 | **undetermined by this phase** — the two earliest hits checked (0080, 0081) are both false positives; not further pursued | — |
| `Moksha` | 46 | **undetermined by this phase** — the earliest hit (0086) is an explicit absence statement; not further pursued | — |

**The corrected central finding**: **only 2 of the original 5 claimed "earliest C2-vocabulary
occurrences" survive verification — `admissibility` (seq 0143) and `Knowledge Space` (seq 0266), both
classified `engineering_knowledgeos`.** These two remain genuine, Level-1-verified evidence that at
least some of protocol.md's own C2-defining vocabulary appears substantively, under a non-C2
classification, before MD-006's formal split. **The claim is narrower than this artifact's own first
draft asserted** — it does not extend to `Buddhi`, `purification`, `Moksha`, or `K_t`'s *true* earliest
occurrence, which this phase's census methodology cannot reliably locate without a deeper, more
expensive per-hit content check than this phase's own bounded scope affords. This limitation is
disclosed, not hidden.

**What this does and does not establish**: seq 0143 and seq 0266 are Level-1 evidence that
`admissibility`- and `Knowledge-Space`-type content appears substantively under `engineering_
knowledgeos` before the C1/C2 split. This is **not** evidence that a "C2 lineage" in the sense of a
coherent research programme existed before MD-006 named it, and **not** evidence that these two
occurrences are the same referent as the sole classified C2 file's own later usage (seq 2330, dated
2026-09-02). The relationship between seq 0143/0266 and seq 2330 is recorded as `UNRESOLVED` — a
shared vocabulary across a roughly month-long span is not, by itself, evidence of one continuous
lineage, per this phase's own governing principle.

**Independent epistemic subthreads — a narrower claim than originally drafted, still supported**: the
two verified occurrences are both classified `engineering_knowledgeos`, and, per Part 1's own finding,
this label's own research regions are not shown to derive from the sole C2 file's own region. **The
evidence remains most consistent with "several independent epistemic subthreads" or "a late,
comparatively isolated formalization"** (both possibilities the authorization named at §11) rather
than a demonstrated single continuous lineage — **not decided here**, and now resting on two
genuinely verified data points rather than five partly-spurious ones.
