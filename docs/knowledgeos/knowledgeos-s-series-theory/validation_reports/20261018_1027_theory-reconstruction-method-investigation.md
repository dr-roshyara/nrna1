# How to reconstruct the KnowledgeOS theory: method investigation

| Field | Value |
|---|---|
| Author / clock | Session B, 2026-10-18 10:27 UTC (clock read) |
| Mode | Read-only investigation. **No corpus content opened.** No manuscript, theory, governance, inventory or source file changed. This is the only new file. |
| Evidence labels | **[V]** verified in this session from files or metadata · **[R]** reported in earlier records (not re-verified; often second- or third-hand) · **[I]** inference · **[M]** proposed method (not done) |
| Not claimed | a corpus audit, a recovered theory, or a current theory version |

---

## 1. Executive conclusion

**What went wrong.**
- **Two large preparation efforts never met in the middle.**
  - Session A built a corpus map: a file registry, contribution rows and concept families.
  - Session B built an empirical study design (v0.4) and a mathematical core (v1).
  - **Nobody tested the map against the primary sources, and nobody read the primary sources at scale.** The access gate blocked it, and the work continued around the block instead of through it.
  - The result: v1's mathematics grew from fewer than 1% of the sources plus third-hand summaries. It is a formalization of *a* theory, not a recovery of *the* theory. [V: READ-LOG, 13 rows; origin report 0746]
- **Formalization ran ahead of recovery.** Elegant mathematics filled the space where corpus evidence should have been. [I]

**What must change.**
1. **Measure the map before using it.** Session A's registry and families are the biggest asset, but they are unreliable until measured. The pilot must measure how faithful they are. [M]
2. **Read through dependencies, not through folders.** Account for every file at a shallow level; read deeply only the documents that **define, revise, verdict or contradict** a foundational concept. Add a random audit to catch what the selection misses. [M]
3. **Prove fidelity with tests that a plausible-but-wrong reconstruction would fail:**
   - quotes verified mechanically;
   - a blind re-reconstruction;
   - **pre-registered predictions** about unread sources. [M]
4. **The target may itself be an unfinished programme.** By the corpus's own reported verdicts, its theory is incomplete: "kernel NOT SELECTED", "v1.2 FROZEN", "CONDITIONALLY VERIFIED" [R: families T-1, T-7]. So "recover the complete theory" must mean recovering the complete **state of the programme**, open problems included, and not forcing a finished theory. [I]

---

## 2. Audit of existing work

| Work | Status | Evidence | Reliability for reconstruction |
|---|---|---|---|
| Corpus registry `02-FILES.jsonl` (2,779 records) | **completed (Session A)** | [V] fields include `provenance`, `best_historical_date`, `best_historical_date_basis`, `order_evidence`, `explicit_dates`, `commit`, `objects_touched`, `summary` | **untested.** The dating and provenance fields are exactly what the history reconstruction needs, but their accuracy has never been sampled |
| Contributions `03-CONTRIBUTIONS.jsonl` | completed (Session A) | [V] 27,906 rows | untested; the fidelity of the extraction is unknown |
| Families `20-FAMILIES/` | completed (Session A) | [V] 2,507 files; [R] 2,457 allowed, with lifecycles DORMANT / ACTIVE / CONTESTED | **"a handle, not an identity claim"** [R]; only 5 read, partially [V] |
| Overlap register | thin | [V] 44 rows | insufficient for near-duplicate handling |
| Provenance class | completed | [R] 2,026 PRIMARY · 739 SECONDARY-SYNTHESIS · 14 unresolved | untested, but usable as a first split between primary and synthesis |
| Allowed-corpus list | completed, **untrusted for reads** (G-LOG-0146 §6) | [V] 2,734 rows, all under `docs/knowledgeos/` | fine for metadata counts only |
| Registry date column (allowed list) | **unreliable as chronology** | [V] values include 1921, 1953, "2026-12", "August …"; 2,092 rows fall in 2026-08 and 603 in 2026-09 | the corpus was written within about 2 months, so chronology needs **minute-level** evidence (filename timestamps, in-text dates, commits), not this column |
| Corpus boundary | **gap found** | [V] a reference-architecture **v1.1 DDD** document exists **only in a git worktree** (`.claude/worktrees/kos-v11-ddd/…`), outside the registered corpus | the corpus may miss material versions; the boundary must be settled |
| S-Series v0.4 manuscript | completed draft, pre-data | [V] §1.3: RQ-3 is a **process theory** (how ideas developed); RQ-4 is a **content theory** (what KnowledgeOS is) | a reliable record of the empirical programme; it recovers no theory content |
| v0.4 §15 and v1 | partial | [V] §15.2 (S-1…S-10) from 4 documents; §15.7 (T-1…T-8) **via families** | §15.2 is first-hand but partial; §15.7 is third-hand |
| Manuscript v1 | completed draft (mathematics) | [V] origin report: Part III newly derived | a valid **derived** layer; **not** evidence of the original theory |
| Late-addition re-review | done today | [V] six errata proposed, not applied | — |
| Primary-source reading | **not done** | [V] 11 corpus documents opened, 5 of them partially | — |
| Fidelity testing of any summary layer | **not done** | — | — |
| Chronology reconstruction | **not done** | — | — |
| Gate | **blocked** | [R] D1 reviewer and tester not appointed; G-LOG-0147 not entered | blocks all of §8 |

**Suggested pilot source.** `engineering/architecture/reference/Engineering_Platform_Reference_Architecture.md` is **not in the KnowledgeOS corpus registry** [V]. It belongs to the project's engineering method, not to the theory. The corpus does contain `KOS-EP01-Reference-Architecture-v1.0` (S0243, 2026-08-22) [V]. That is an **architecture** document, downstream of the theory, so it is a poor first test of theory recovery (§8). [I]

---

## 3. Nature of the theory (from the evidence now available; mostly [R], second- or third-hand)

The material is a **research programme with internal versioning, not one text**. It contains these kinds of material:

| Kind | Examples (as reported) | Source standing |
|---|---|---|
| A dependency architecture | K → 𝓘 (invariants) → 𝓞 (operations) → δ (transitions) → kernel; "no canonical K ⇒ no canonical 𝓘 …" | first-hand (step 285) |
| Separation axioms ("non-collapse") | AX-1: O≠K, Evidence≠Knowledge, Claim≠Truth, Probability≠Knowledge, Proposal≠Decision, Representation≠Identity; the v2 chain Reality≠Observation≠…≠Action; the Foundation's seven collapses | via families, plus one first-hand document |
| Formal definitions and theorems with self-verdicts | theory v1.0 (33 DEF, 7 AX, ~11 THM, "CONDITIONALLY VERIFIED"); v1.1 repairs; v1.2 "FROZEN"; v2 (Parts I–XVII) | **third-hand** |
| An evaluation layer | Gap(K, EC), Zero ⇔ Δ = ∅, contract-relative order, FalseZero | third-hand |
| Operations and vocabulary | 𝒪_core = {ASSERT, LINK, REVISE, RETRACT, ISOLATE}; eight primitives (Entity … Action) as "vocabulary, not fields" | third-hand / first-hand (C-30) |
| An epistemic pipeline with open transforms | X → Y → E → 𝓕 → Π → K; Qualify and Φ undefined | first-hand (C-38) |
| Executed experiments and self-corrections | the adequacy ≠ realization witness; "6/6 tests ⇏ Adequacy = 1.0" | third-hand |
| A representation candidate | ABK-1 (Belnap–Dunn values, justification DAG …) | third-hand |
| Philosophical lenses | Gita-derived (Buddhi as operator, Kṣetrajña outside K, Guṇa as mode; 142 family names); Tripuṭī; Vedanta/Pramāṇa (a root-level corpus file) | first-hand (C-30, C-0901), plus names |
| Probability and measure | `phase_measure_theory` (820 files); Y ∼ P(Y∣K) | 4 files opened |
| Conceptual foundation | relationships, collapses, a dimension model, H-ZERO-001 (UNKNOWN preserved) | first-hand (one document) |
| Governance and constitution | KOS-EP01 Constitution, invariant map and matrix, the kernel decision (2026-08-22) | names only [V] |

**Consequences for the method [I]:**
- The reconstruction target has **at least four layers**: the philosophical lenses, the conceptual layer (separations, relationships), the formal layer (definitions, theorems, versions) and the engineering layer (constitution, architecture).
- The relationships between the layers are themselves part of the theory.
- The corpus records its own verdicts and open problems. These must be **recovered as content**, not overwritten.

---

## 4. Recommended reconstruction method [M]

**The unit of reconstruction is the concept dossier**, not the file and not the chapter. A dossier holds one foundational concept with all its definitions, their history, dependencies and uses.

**Sequence (and why):**

1. **Account for every file in the boundary at a shallow level** (§5).
   - Every file gets a status: *accounted* · *structurally extracted* · *deep-read* · *excluded (reason)* · *inaccessible*.
   - *Why:* completeness of **accounting** is cheap and auditable. Completeness of **deep reading** is neither.
2. **Discover concepts by mechanical extraction**, not from summaries.
   - **Extract** defined terms (patterns: "Definition", "DEF-", ":=", "≡", "is defined as", bold term + colon), symbol introductions, axiom and theorem labels, verdict words ("FROZEN", "SUPERSEDES", "VERIFIED", "REJECTED"), version tags and cross-references.
   - **Every extracted item carries its file and line.**
   - *Why:* this finds concepts that are not in file names or family labels, and it is reproducible.
3. **Estimate what is still unseen.**
   - Compare three independent "captures" of the concept set:
     - Session A's families;
     - the mechanical extraction;
     - a random deep-read sample.
   - Use **capture–recapture** estimators (Lincoln–Petersen, Chao1) on the foundational-concept set to estimate how many concepts none of the three found.
   - *Why:* this gives a measured answer to "how many important ideas might we still be missing?", with stated assumptions (unequal catchability biases it low; reported as such).
4. **Select documents for deep reading by role, not by folder.** A document is deep-read if it does any of the following:
   - **defines** or **redefines** a foundational concept;
   - **issues a verdict or a version** ("FROZEN", "supersedes");
   - **is cited by many others** (citation in-degree);
   - **contradicts** another definition;
   - **is drawn** in the random residual audit.
5. **Build one dossier per foundational concept.** Each dossier records:
   - every definition, quoted verbatim with its location;
   - dated evidence for each;
   - the terms its definition depends on;
   - the propositions that use it;
   - competing formulations and their relation (§6);
   - the self-verdicts it carries.
6. **Assemble the dossiers into the dependency graph** (§6). Strands and chapters emerge from it (§7).
7. **Challenge every dossier for fidelity** (§8 tests) before it may be used in writing.

**Why not the alternatives (comparison):**

| Approach | Strength | Fatal weakness on its own |
|---|---|---|
| Chronological reading | history is natural | about 2,700 files written in about 2 months; reliable fine-grained chronology does not yet exist; reading everything in time order at full depth is the most expensive route |
| Families, then their primaries | cheap navigation | inherits the families' omissions and errors, which are unmeasured; the families are labelled "a handle, not an identity claim" |
| Concept dependencies first | follows the theory's own logic (K → 𝓘 → 𝓞 → δ) | needs a concept list first, which is circular without discovery (step 2) |
| Syntheses first (the earlier recommendation) | the corpus's own maps | 739 records are classed SECONDARY-SYNTHESIS [R]; syntheses select and smooth, so they systematically hide minority ideas and contradictions |
| **Hybrid (recommended):** account for every file + mechanical discovery + syntheses **as an index only** + dependency-driven deep reading + contradiction-driven investigation + a random residual audit | each part covers the others' blind spots; coverage is **measured** | needs discipline about labels; mitigated by the fidelity tests |

**Correction to my earlier recommendation.** In the previous session I recommended reading the synthesis and kernel reviews first. That is acceptable **only as an index**. As a first-stage *substitute* for discovery it is not sufficient. [I]

---

## 5. Corpus strategy [M]

**Boundary (first).**
- Settle what is in scope:
  - the registry (2,779 records);
  - files outside the registry that hold theory versions (e.g. the worktree-only v1.1 DDD document [V]);
  - documents cited by the corpus but absent from it.
- Every boundary decision is recorded.
- Sealed and hold-out files stay excluded and are **counted only, never characterized**.

**Depth tiers.**

| Tier | What is done | Applies to |
|---|---|---|
| T0 accounted | metadata, hash, size, dating evidence | every file in the boundary |
| T1 structurally extracted | the mechanical extraction of §4.2; headings; cross-references | every readable file |
| T2 deep-read | the full text is read and dossier entries are written | files selected under §4.4 |

**Duplicates and drafts.**
- **Exact duplicates:** none among the allowed rows by hash [V].
- **Near-duplicates:** to be detected by shingling (MinHash). The 44-row overlap register is insufficient [V].
- **Within a near-duplicate cluster:** deep-read one representative and diff the rest. Any difference touching a definition makes that file a T2 candidate.
- **Superseded drafts are kept as history, never discarded.**

**Large files.**
- Apply T1 to the whole file. Apply T2 to the sections that the T1 extraction marks as definitional or verdict-bearing.
- The coverage ledger records the section ranges actually read.

**Summaries.**
- They are used only to *locate* primaries.
- Any dossier statement that rests on a summary carries **[via summary]** and cannot pass fidelity acceptance.

**Inaccessible sources.**
- They are listed with the reason.
- Any concept whose only definitions are inaccessible is marked **UNRECOVERABLE-NOW**, not guessed.

**Conflicting source records** (e.g. the date column versus filename timestamps).
- Use an explicit evidence hierarchy: in-text dates and filename timestamps > commit dates > mtime. Source-ID order is ingestion order and is **not chronology** [R: v3.5 R1].
- Conflicts are kept, not resolved silently.

**Sampling limits** (answers "a stopping rule does not prove completeness").
- The "no new claim in N reads" stopping rule only shows that the *rate* of discovery is low.
- It is supplemented by:
  - the capture–recapture estimate (§4.3);
  - a **random residual audit**: n files drawn uniformly from the not-deep-read remainder. If none contains a new foundational concept, the remainder's prevalence is below about 3/n at 95% (rule of three). For n = 100, below 3%.
  - **targeted absence searches**: for each foundational concept, a lexical search of the whole boundary for its terms and symbols. Every hit is classified, so nothing is missed merely because it was not sampled.

---

## 6. Theory integration method [M]

**Three graphs, built from dossiers:**
- **Definitional graph.** An edge A → B means B's definition uses A.
  - Cycles mean **circular definitions**, to be reported.
  - Sources mean candidate foundations.
- **Inferential graph.** Premises → proposition, each edge labelled with its assumptions.
  - Unsupported transitions show up as propositions with no premises.
- **Version graph.** A definition at time t1 → its revision at t2, labelled *extends · restricts · replaces · rejects · re-states*.
  - The order is a **partial** order built from dated evidence and citation constraints ("A cites B" ⇒ B is earlier). It is never forced to be total.

**Strands** are communities of the definitional graph. They are confirmed by reading, never accepted from clustering alone.

**Classifying each pair of formulations** with the same or a similar name:

| Relation | How it is decided |
|---|---|
| equivalent | formal translation in both directions (e.g. via v1's framework), or explicit textual identification |
| specialization | one entails the other, not conversely |
| historical redefinition | the version graph shows a replacement, plus a textual act ("supersedes", "repairs") |
| **genuine contradiction** | both are asserted in the same period or strand, they cannot both hold, and no textual resolution exists |
| homonym (different concepts, same name) | they are not inter-translatable and have different dependency neighbourhoods |
| undetermined | the evidence is insufficient; this is recorded |

**Fidelity categories** (each dossier statement carries exactly one):
1. explicit in one primary;
2. explicit in several **independent** primaries (independence is judged by the version and citation graphs: copies and descendants count once);
3. historical interpretation backed by documents;
4. synthesis across sources;
5. new derivation;
6. unverified hypothesis;
7. contradiction or open question;
8. not reconstructable now.

**v1's role.** v1 enters only as category 5. A v1 construct is linked to an original concept only after a **mapping test**: a formal translation of the original definition, checked against the dossier. Example: does v1's K (the Nerode quotient) coincide with any of the seven K definitions? [M]

---

## 7. Writing method [M]

- **The chapter order is the topological order of the definitional graph**, coarsened by strand. Concepts with no dependencies come first. The outline is **derived after Gate 4**, never before.
- **Each section follows a fixed form:**
  1. the original problem;
  2. the historical motivation;
  3. the original definitions (quoted, anchored);
  4. a plain-language explanation;
  5. the formal model;
  6. derivation and evidence;
  7. examples and counterexamples;
  8. relations to other sections;
  9. alternative readings and open contradictions;
  10. a box: **"Later contributions"** (category 5 only).
- **Every substantive sentence carries a dossier anchor.** A build check fails any section with an unanchored claim, or with a category-5 statement outside a "Later contributions" box.
- **Open problems and self-verdicts of the original are written as such.** The book may legitimately end in "the corpus left the kernel unselected" if that is what the evidence shows.

---

## 8. Pilot design [M]

**Recommended component: "Zero, Gap and the epistemic contract".**

**Why this component:**
- **It is foundational.** The action loop K → Zero → Determination → Decision runs through it [R: C-38], and contract-relative order depends on it.
- **It has a known conflict to test the method on:** two meanings of Zero, neutral start (H-ZERO-001) versus Δ = ∅ [V: coverage audit].
- **It has a version history to recover:** gap theory v1.0 (T-2) → v2 contract-relative Zero and FalseZero (T-4) → the Conceptual Foundation (H-ZERO-001) [R].
- **It has a third-hand map to measure.** Families T-2 and T-4 and v1 Ch. 5 make **checkable claims** about the primaries, so the pilot measures the map's fidelity as a by-product.
- **It is bounded.** 62 family names contain "zero" and 74 contain "gap" [V, counts only]. That is manageable.
- **Second pilot (if the first passes):** K, with its seven definitions. It is larger and harder.

**Source requirements** (a named list, fixed before reading):
- the gap-theory v1.0 primary;
- the v2 rewrite sources S2763–S2780;
- the Conceptual Foundation (already read under an exception);
- EKS S-1 and S-11 (cited by the Foundation);
- every primary cited by the Zero/Gap families;
- a lexical search for Zero/Gap/FalseZero/UNKNOWN terms across the authorized boundary, which **is itself a read** and needs authorization;
- **5 held-aside primaries** chosen before the deep reading, for the prediction test.

**Work steps:**
1. Write the source list and the held-aside list, and freeze them with a hash.
2. Run T1 extraction on the pilot sources.
3. Deep-read and build the dossier.
4. Build the version graph.
5. Classify the formulations (§6).
6. Record family-fidelity measurements as they arise.
7. Write the predictions for the held-aside documents, and freeze them.
8. Read the held-aside documents and score the predictions.
9. Have a fresh reviewer re-reconstruct blind from the sources alone, then compare.
10. Write a provisional section in the §7 form.
11. Evaluate against the criteria.

**Acceptance criteria (all must pass; any failure fails the pilot):**

| # | Criterion | Threshold |
|---|---|---|
| A1 | Quote integrity: every quoted definition is found verbatim at its cited location (mechanical check) | 100% |
| A2 | No definitional statement rests only on a summary | 0 such statements |
| A3 | Family-row fidelity measured: a random sample of ≥ 30 family rows about Zero/Gap is checked against the primaries | report the rate with a 95% CI. **Not pass/fail; it decides whether families may be used as a map in Phase 3** |
| A4 | Recall: every lexical hit in the pilot boundary is classified (relevant · irrelevant · new formulation) | 0 unclassified |
| A5 | Blind reconstruction: the fresh reviewer's definition list and dependency edges are compared with ours | every disagreement adjudicated from the sources; **no unresolved disagreement on a definition** |
| A6 | Prediction: frozen predictions about the 5 held-aside primaries (which Zero meaning, which contract model, version markers) | ≥ 4 of 5 correct. **This is the test a plausible-but-wrong reconstruction fails** |
| A7 | Attribution audit: 20 random statements, with their fidelity categories checked by the reviewer | ≥ 19 of 20 correct |
| A8 | Comprehensibility: the Principal can restate the component's meaning from the plain-language section | Principal's judgment |

**If the pilot fails:**
- The failing criterion locates the defect:
  - **A3 low:** drop the families as a map and rely on extraction.
  - **A5 or A6 failing:** the reading depth or the selection rule is insufficient; deepen it.
  - **A1 or A2 failing:** fix the anchoring discipline.
- Revise the method and rerun on the same component. **No scale-up until the pilot passes.**

---

## 9. Access and decision dependencies (exact)

1. **Read authority for the pilot sources** (the whole pilot is blocked without it). Either:
   - the gate passes: appoint the D1 reviewer and a separate human tester, enter G-LOG-0147, and record your gate act; **or**
   - you record a **bounded exception** naming the frozen pilot source list, the held-aside list and the lexical search, with the seal custodian confirming that none of them is hold-out material.
2. **Governance ruling on the prediction test (A6).** G-LOG-0144 bars sessions that accessed this workspace from "Kind-P and prediction roles" [R].
   - A6 is a **fidelity** prediction about non-sealed documents, not a Kind-P hold-out prediction. Governance must confirm that the distinction holds.
   - Otherwise the predictions must be written by an eligible session.
3. **Corpus boundary decision.** Are out-of-registry files (worktree-only versions; documents the corpus cites but does not contain) in scope?
4. **F-Series.** It remains separate unless you authorize its use. Its relevant results would enter only as category-4 or category-5 evidence.

---

## 10. Implementation roadmap [M]

| Phase | Deliverable | Done when |
|---|---|---|
| **P0 Boundary and accounting** (partly permitted now: metadata only) | boundary decision record; T0 ledger for every file; a near-duplicate cluster list (it needs content shingling, so it is gated) | every file has a status; out-of-registry versions are listed |
| **P1 Pilot: Zero/Gap** (gated) | dossier, version graph, family-fidelity rate, provisional section, review record | A1–A8 pass |
| **P2 Second pilot: K** (gated) | the same, for the seven K definitions, including the K-first versus contract-first ordering | A1–A8 pass. The method is now proven on a hard case |
| **P3 Corpus reconstruction** (gated) | T1 over the boundary; capture–recapture estimate; dossiers for all foundational concepts; the three graphs; the residual audit | estimated unseen foundational concepts ≤ the agreed bound; the residual audit (n ≥ 100) finds 0 new foundational concepts; every batch is audited |
| **P4 Structure** | strand map, derived outline, coherence report (cycles, unsupported transitions, contradictions) | the outline follows from the graph; an independent review of the coherence report is complete |
| **P5 Writing** | chapters in the §7 form | every claim is anchored; the build check passes; each chapter is reviewed independently, then the whole is integrated |

---

## 11. Risks and failure conditions (early detection)

| Risk | Early signal | Response |
|---|---|---|
| A polished but incomplete reconstruction | capture–recapture estimates many unseen concepts; the residual audit finds new foundations | do not start P4 |
| Summary leakage | dossier statements marked [via summary] | A2 fails; reread the primaries |
| Plausible but wrong | A6 predictions fail; the blind reviewer disagrees | revise the method before scaling up |
| Derived mathematics presented as original | category-5 statements outside "Later contributions" boxes; the A7 audit | the build check fails the section |
| False chronology | the version graph relies on IDs, the date column or mtime | only the evidence hierarchy is admissible; incomparabilities are reported |
| Forced unification | strands that are not inter-translatable are merged in the outline | report several theories, or an incomplete framework |
| Administration replacing research | phases that produce records without dossier growth | measure progress **only** by dossiers passing A1–A7 |
| Same-model review mistaken for independent review | review records lacking a "same model family" label | label every review; human external review for the final work (D6) |

---

## 12. Recommendation to the Principal

**Decide one thing: authorize the Zero/Gap pilot** with a frozen, named source list, through one of these routes:
- **recommended:** the gate route (appoint the D1 reviewer and the human tester); or
- a bounded, recorded exception for that list only.

Ask governance at the same time for the A6 ruling (§9.2).

- **What it produces:** a dossier on one foundational concept, traceable to exact source lines; the first **measured** reliability figure for Session A's map; and a pass/fail verdict on whether this method recovers the original rather than inventing a plausible version.
- **How to judge it:** criteria A1–A8.
- **If it fails:** the failing criterion names the repair; rerun on the same component. **No corpus-wide pass and no writing until the pilot passes.**

---

## Answers to the completion questions

| Question | Answer (section) | Still needs evidence? |
|---|---|---|
| How will we find all the important ideas without relying on summaries? | mechanical extraction over every file, three-way capture–recapture, residual audit, absence searches (§4.2–4.3, §5) | the estimator's behaviour on this corpus: measured in P1 and P3 |
| How will we find the original source and reasoning behind each idea? | dossier anchors; citation in-degree; deep reading of the defining and revising documents (§4.4–4.5) | — |
| How will we reconstruct relationships across thousands of files? | definitional, inferential and version graphs from dossiers; strands confirmed by reading (§6) | — |
| How will we tell historical versions, contradictions and superseded claims apart? | version graph with dated evidence; the six-way relation classification (§6) | the reliability of the dating fields: sampled in P1 |
| How will we prove a section reflects the original? | A1, A2, A5, A6, A7 (§8); A6 is the decisive test | the A6 governance ruling |
| When is coverage sufficient to begin writing? | P3 completion conditions (§10) | the agreed bound on unseen concepts: a Principal choice at P3 |
| When is the theory itself incomplete or irrecoverable? | self-verdicts and open problems recorded as content; concepts whose only definitions are inaccessible marked UNRECOVERABLE-NOW; definitions missing after targeted absence searches (§5, §7) | — |
| How do we get from pilot to manuscript without losing traceability? | dossier anchors carried into every sentence; the build check; the outline derived from the graph (§7, §10) | — |
