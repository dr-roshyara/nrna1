# R7 · E3 architecture proposal — E3b complete (F-LOG-0297); draft r1.3 [superseded by Act 2, F-LOG-0297; r1.2: "PROPOSED for E3b review; draft r1.2"]

**Status (r1.3):** E3b complete (F-LOG-0297; it also rests on F-LOG-0284, A2 = (b), under which the non-CB elements were accepted, which E3 r1.1–r1.2 did not record. The §1 legend, the §7.2 reading-(a) column, §9.2 V1, the §10.1 A2 row ("carried open as (c)") and the §11 drafts are r1.1-era text, kept as history), draft r1.3, 2026-10-13. r1.3 is the Act 2 recording (F-LOG-0297, route R-1 per F-LOG-0295, tagged in place): `R7/E3b-REASSESSMENT-MODULE-CUT-PROPOSAL.md` r1.2 §II.6 items N1–N12 applied, each changed site tagged "[… by Act 2, F-LOG-0297 …]" with the r1.2 text kept, and §13 appended quoting the act. Act 2 accepts every ⚠E3b output (all five boxes ticked; (R-none) (a), a human ruling, not a finding; (α); neutral names kept) as a **bounded architecture proposal, one corpus**, with results under S, S′ and S″, none selected, and with the limitations of `R7/E3b-REASSESSMENT-MODULE-CUT-PROPOSAL.md` r1.2 §II.4. D12 is not resolved; no reading or spanning rule is selected. **The r1.3 recording is not yet reviewed** (a fresh review follows, F-LOG-0297 "Next"). E4 (R8) and E5 are **not** authorized. The r1.2 status line below is kept as history.

**Status (r1.2):** PROPOSED, draft r1.2, 2026-10-13. r1.2 is the Act 1 relabel recording (F-LOG-0295, route R-1, tagged in place): `R7/E3b-REASSESSMENT-MODULE-CUT-PROPOSAL.md` r1.2 §I.2a rows 1–19 applied, each changed site tagged "[relabelled by Act 1, F-LOG-0295; r1.1: "…"]", and the §I.1a definition and non-claims recorded under §2.1. Label and vocabulary swaps only: no assertion added, no status word changed; every ⚠E3b output stays **not accepted** pending a separate re-assessment act (Act 2). **The r1.2 recording is not yet reviewed** (a fresh review follows, F-LOG-0295 "Next"). E4 (R8) and E5 are **not** authorized. The r1.1 status line below is kept as history.

**Status:** PROPOSED, draft r1.1, 2026-10-12. Review history: r0, round 1 (fresh independent review): PASS_WITH_LIMITATIONS, no blocker; M1–M7 material, m1–m10 minor; r1 applies them. r1, round 2 (fresh independent review): **round 2 PWL, nothing blocking; r1.1 wording fixes not re-reviewed** (W1–W9, §12). Per F-LOG-0282 (4), presentable for a separate act on E3b. It is produced under F-LOG-0282 (E3 AUTHORIZED). It is a **proposal for human review**. It decides nothing, accepts nothing and authorizes nothing. F-LOG-0282: "The output is an architecture **proposal** for human review, with results under S, S′ and S″, none privileged." E3b, E4 (R8) and E5 are **not** authorized.

**Authority (F-LOG-0282, the human's selections, verbatim):**
- "(1) **A2, mandatory; select one:** [line break in the source] (c) A2 is carried open. E3 reports traces under readings (a) and (b), and flags every output derived from CB1 / CB23 as at risk for E3b."
- "(2) Scope: also Q4–Q6 of the proposal. Ports (Q7): not included."
- "(3) Output shape: one reading-neutral proposal, still showing per-reading results and disclosing any intersection- or union-based construction."
- "(4) Review regime: fresh independent `kos-theory-reviewer` subagents, one new reviewer per round. Stop rule: a FAIL at round 3 → human; otherwise PASS / PASS_WITH_LIMITATIONS with nothing blocking → present for a separate act on E3b."
- Governing text: "under the scope, exclusions and acceptance criteria of `R7/ARB-DECISION-PACKAGE.md` r4.4 §E3, and under the binding conditions B1–B14 of `R7/O3-PREPARATION-AUTHORIZATION-PACKAGE.md` r1.1 §3. **The package r1.1 §3 text governs.**"

**Inputs** (T = `docs/knowledgeos/chronological_knowelgeos_ablation_theory/analysis/theory/`): `R7/O3-PROPOSAL.md` r1.2; `R7/O3-PREPARATION-AUTHORIZATION-PACKAGE.md` r1.1 (§3 B1–B14; §4); `R7/E2b-ADOPTION-TEXT.md` r1.2 (§A1, the 27 elements); `R7/T3-CANDIDATE-MODEL.md` r1.8, with the pinned trace rows r1.5 §2 (commit 7bccae4d8) and r1.7 §2 (commit 6e0407db7); `R7/T2-RULE-CANDIDATES.md` r1.4; `R7/T1-CONCEPT-CANDIDATES.md` r1.6; `FORMAL-THEORY-R4.md` r3.1 (by id only, through the trace cells); `R7/ARB-DECISION-PACKAGE.md` r4.4 (§B, §E3, §E3b); `R7/VP-DECISION-REGISTER.md` r1.19; `R7-AUTHORIZATION-BRIEF.md` r3 (Λ1–Λ7); `F-GOVERNANCE-LOG.md` F-LOG-0248, F-LOG-0253 to F-LOG-0282.

**Standing qualifier (applies to every statement here, verbatim):** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

**Hard limits held in this document:** no VP decided; no spanning rule selected; the asymmetry is not accepted; F-LOG-0265's premise is not withdrawn; no reasoning from any VP1a–d outcome (N1); no invariant, transaction or enforceability claim; no canonical term (every name is "(provisional name, not canonical)"); no port; no record amended. Only this file is created.

---

## 1. Classification legend

Every claim carries exactly one tag. In tables, the tag is in the "class" column or at the start of the cell. In lists, it leads the bullet.

| tag | meaning | what must accompany it |
|---|---|---|
| **[A]** | adopted fact: stated in an adopted or governing record (E2b r1.2; a human act in the log; ARB r4.4; package r1.1 §3) | the source, quoted where load-bearing |
| **[D]** | derived conclusion: follows mechanically from [A] items or from pinned trace cells | the derivation and its sources |
| **[H]** | design hypothesis: an architecture reading this proposal puts forward for review | nothing more; it is not a finding |
| **[O]** | open question: the sources do not support an answer | what is missing |
| **[E]** | analyst judgment: an interpretive choice by the deriver | the reason |

**Reading of the tags against the source labels.** Every adopted element is itself "[E] bounded model element, one corpus" (E2b §A1.2). An [A] tag here therefore means "adopted as a bounded [E] model element or recorded by a human act", never "true of the domain". T3's per-reading host lists are "a report of T3 [E], not ratified" (F-LOG-0275 Act I (1)). A [D] built on them inherits that status, and is said to do so.

**[r1, M5] T3 and T2 text that is not adopted.** E2b adopts the elements with their T3 §2 traces (the 25: full r1.5 rows; CB1 / CB23: restricted r1.7 rows). The T3 §1 descriptions (e.g. §1b, §1c, §1d, §1e), the T3 r1.8 text written after the pins, and the T2 r1.4 statement and "checkable" columns are **not** adopted as text. Where this document relies on them, the tag reads **"[A] that T3 (or T2) states X (T3 [E] / T2 candidate, not adopted)"**: the fact adopted is only that the text exists and says X. Text from T3 r1.8 that postdates the pins is further marked **"post-pin T3 [E] context"** (O3 r1.2 Q-R (iii), unruled). Where this document filters such text (e.g. "inside" = carried and crossing no grouping), the tag is [D].

**At-risk flag.** **⚠E3b** marks every output derived from CB1 or CB23 (F-LOG-0282 clause (1)). It means: at risk at E3b under A2, because the CB1 / CB23 trace is restricted (§7).

---

## 2. Candidate module cut [relabelled by Act 1, F-LOG-0295; r1.1: "bounded contexts"] from CB1 and CB23 [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"]

### 2.1 What is proposed

| id | provisional name | class | content | source |
|---|---|---|---|---|
| **E3-M1** [relabelled by Act 1, F-LOG-0295; r1.1: "**E3-BC1**"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] | records module [relabelled by Act 1, F-LOG-0295; r1.1: "records context"] (provisional name, not canonical) | [H] | a candidate module (one side of the CB1 / CB23 cut) [relabelled by Act 1, F-LOG-0295; r1.1: "bounded context"] derived from the adopted element CB1 | E2b §A1.2 (CB1 adopted "by concept membership and trace"); T3 §1a heading "bounded-context-like partitions" |
| **E3-M23** [relabelled by Act 1, F-LOG-0295; r1.1: "**E3-BC23**"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] | norm-and-work module [relabelled by Act 1, F-LOG-0295; r1.1: "norm-and-work context"] (provisional name, not canonical) | [H] | a candidate module (the other side of that cut) [relabelled by Act 1, F-LOG-0295; r1.1: "bounded context"] derived from the adopted element CB23 | as above |
| (no id) | — | [A] | **CB4 is not a module [relabelled by Act 1, F-LOG-0295; r1.1: "not a context"].** "CB4 stays an explicit open slot (F-LOG-0263); it is not a side, and it is not an element" | F-LOG-0275 Act I (1); VP10 (iii) |
| (no id) | — | [D] | No third module [relabelled by Act 1, F-LOG-0295; r1.1: "No third context"] is proposed. Only two adopted elements are module sides [relabelled by Act 1, F-LOG-0295; r1.1: "are boundaries"] (CB1, CB23); P-a, P-b and P-d "are not adopted" | F-LOG-0275 Act I (1) |

**[Act 1, F-LOG-0295] What "candidate module cut" means in this record.** Recorded by Act 1 (F-LOG-0295, route R-1) from `R7/E3b-REASSESSMENT-MODULE-CUT-PROPOSAL.md` r1.2 §I.1a, adapted: the pointers to II.3 / II.6 are dropped, and "audit" and section references are expanded. It adds nothing beyond this membership definition and these non-claims.

*Act 1 definition (membership only).* A **module** is one side of the CB1 / CB23 cut, [H], [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"]. The cut is a partition of the 23 placed concepts other than C1, of the one adopted model. The audit puts it this way: "Both sides are fragments of one formal model, a single history tuple h (Def 1.7)" (RESEARCH-AUDIT-1 r1.1 Q2 (i)).
- it is defined by the adopted CB1 / CB23 T1 ids (F-LOG-0275 Act I (1));
- C1 spans both sides (VP12 (i));
- C22, C25, C26 and C28 are on no side (E2b §A1.2).

Act 1 asserts nothing else. In particular it asserts nothing about crossings or co-reading; those are structural statements, left to a separate re-assessment act (Act 2).

*Non-claims (part of the Act 1 definition):*
- **no separate language or model** (audit Q2 (i): "**Not supported.**");
- **no ownership or responsibility** (audit Q2 (ii): "**Not supported.**"); nothing is enforceable from records (R23);
- **no context-map pattern** and no typed relationship (audit Q2 (iii): "**Not supported.**"; §2.3 [O]);
- **no bounded-context status** for either module;
- no internal-cohesion claim for either module;
- no optimality of P-c;
- no domain coupling, runtime dependency, deployment, team or transaction meaning;
- no selection among S, S′ and S″.

Every relabelled output keeps its ⚠E3b flag; none is accepted by Act 1. Standing qualifier (verbatim): "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

**[added by Act 2, F-LOG-0297] Structural description [E]** (`R7/E3b-REASSESSMENT-MODULE-CUT-PROPOSAL.md` r1.2 §II.3, as ticked in Act 2): a low-crossing cut on T-A + T-B, including 18 inactive T-B slots, and on raw crossings only; E3-M23 cohesive far above chance; E3-M1's cohesion not distinguishable from chance; structural proxies only. A candidate module cut over one model, as defined above; not bounded contexts. **Names (N-a):** keep 'records module' / 'norm-and-work module' (provisional names, not canonical).

### 2.2 What each module would hold [relabelled by Act 1, F-LOG-0295; r1.1: "What each context would own"]

**Concepts** ([A], F-LOG-0275 Act I (1)): E3-M1 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC1"] holds CB1's "C13–C18, C23, C24, C27" (9). E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] holds CB23's "C2–C12, C19–C21" (14). C1 spans both (§2.4). C22, C25, C26 and C28 are on no side (stated exceptions, E2b §A1.2).

**Adopted elements, placed by concept membership** ([D] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"]; derivation: an element lies in a module [relabelled by Act 1, F-LOG-0295; r1.1: "lies in a context"] iff all of its T1 ids (T3 r1.5 §2 "T1 ids" column) are members of that module's [relabelled by Act 1, F-LOG-0295; r1.1: "that context's"] concept set; it straddles if its ids fall on both sides; it is on no side if its ids are all stated exceptions on no side; placement is by membership only and therefore **the same under S, S′ and S″**). **[r1, M3] Supplementary rule [E]:** a model-wide element (one whose T1 ids are "all C-rows") is placed in no module [relabelled by Act 1, F-LOG-0295; r1.1: "in no context"]. It is an analyst rule, not derived from any source; without it MW1 would count as straddling (counts 6 / 12 / 4 / 3). It is listed in §10.2. **[r1, m5] Disclosure:** "straddling" here is a membership statement. For the participant and CG1, it coincides with the **S** count (C1 counted on both sides); under S′ the count differs; under S″ it depends on the row choice (§3.3, §10.2 #1; §8.2). Stating it is not a selection of S:

| module [relabelled by Act 1, F-LOG-0295; r1.1: "context"] | elements wholly inside | class |
|---|---|---|
| E3-M1 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC1"] | CG4 (C13–C18), CG6 (C24), CV4 (C18, C27), CV7 (C23), CV-text (C15), CE6 (C13, C14) | [D] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] | CG2 (C3, C5–C7), CG3 (C8–C12), CG5 (C19, C20), CV1 (C4), CV2 (C5), CV5 (C10), CV6 (C21), CE1 (C8, C11), CE2 (C9, C11), CE3 (C12), CE4 (C7), CE5 (C19, C20) | [D] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| straddling | the participant (C1); CG1 (C1, C2); CV3 (C22 with C11, C12, C14, C20) | [A] for the participant ("spans CB1 and CB23", E2b §A1.2a) and CV3 ("CV3, under which it appears, straddles the cut", E2b §A1.2). **[r1, m5] CG1: [D]** [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"], from C1 spanning ([A] E2b §A1.2a) and C2 ∈ CB23 ([A] Act I (1)). E2b itself says only "CG1 may straddle the P-c cut" (§A1.2); this proposal does not upgrade that wording to an adopted fact |
| no module [relabelled by Act 1, F-LOG-0295; r1.1: "no context"] | CV8 (C25), CV9 (C28), CS1 (C26); MW1 (all C-rows) | [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"]. [A] for CV8, CV9, CS1: they "stay adopted and ungrouped" and "Under P-c they are on no side (T3 r1.7 exceptions)" (E2b §A1.2). **[r1, M3] [E] for MW1**, by the supplementary rule above |

Check [D]: 6 + 12 + 3 + 4 = 25 baseline elements placed; CB1 and CB23 are the modules themselves [relabelled by Act 1, F-LOG-0295; r1.1: "the contexts themselves"]. 25 + 2 = 27 (E2b §A1.2).

**Constraint rows (T-A).** E2b §A1.3 reports the hosts per reading "as a report of T3 [E], not ratified". The proposal lists them per reading and selects none. ([A] that the report exists and says this. **[r1, m9]** [D] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"], **conditional on the [H] E3-M1 / E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC1 / E3-BC23"]**: if the sides are read as modules, a module [relabelled by Act 1, F-LOG-0295; r1.1: "read as contexts, a context"] "would carry" the rows the host lists (ratified per reading, R-a, F-LOG-0290) place [superseded by Act 2, F-LOG-0297; r1.2: "the report hosts"] internally to its side. The bridge from host column to module [relabelled by Act 1, F-LOG-0295; r1.1: "host column to context"] is only as strong as that [H].)

| row set | S | S′ / S″ | class |
|---|---|---|---|
| internal to E3-M1 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC1"] | R7 | R7, **R8** | [D] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"], from E2b §A1.3 |
| internal to E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] | R1, R4, R11, R17, R18, R20 (6) | R1, R4, **R5, R6, R10**, R11, R17, R18, **R19**, R20 (10) | [D] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"], from E2b §A1.3 |
| shared by both (crossing the cut) | R2, R3, **R5, R6**, R8, R9, **R10**, R14, R15, R16, **R19**, R21, R22 (13) | R2, R3, R9, R14, R15, R16, R21, R22 (8) | [D] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"], from E2b §A1.3 [added by Act 2, F-LOG-0297: "14 / 9 under the other D12 reading (R24 moves from "hosted at CV4", line 73 (r1.1; §2.2 crossing row), to shared); D12 not resolved"] |
| crossing, hosted at MW1 (no module [relabelled by Act 1, F-LOG-0295; r1.1: "no context"]) / CV4 (inside E3-M1 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC1"]; R24 crosses) (placement: this §2.2) | R12, R13, R23 / R24 | R12, R13, R23 / R24 | [D] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"], from E2b §A1.3 [added by Act 2, F-LOG-0297: under the other D12 reading, R24 is no longer "hosted at CV4" but shared, so this CV4 cell is empty and the crossing count at MW1, CV4 is 3 / 3 (from 4 / 4); D12 not resolved] |

**Status of these rows** ([A], F-LOG-0275 Act II): "No grouping is enforceable from records (R23). R1–R24 remain candidate model constraints, not domain invariants." A module [relabelled by Act 1, F-LOG-0295; r1.1: "A context"] "carrying" a row means only that the row's statement reads items placed in that module [relabelled by Act 1, F-LOG-0295; r1.1: "that context"]. It does **not** mean the module checks [relabelled by Act 1, F-LOG-0295; r1.1: "the context checks"] or enforces it ([A] R23 per F-LOG-0275 Act II; and [A] that T2 r1.4 §0 states (T2 candidate, not adopted): "A model invariant over any of these unobserved coordinates cannot be enforced or checked from records").

**T-B rows** stay inactive and are not held as structure [relabelled by Act 1, F-LOG-0295; r1.1: "not owned as structure"] (§6).

### 2.3 Cross-module rows between E3-M1 and E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "The relationship between E3-BC1 and E3-BC23"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"]

**[r1, M1] Rebuilt from the E2b §A1.3 crossing report (recorded, not ratified) only.** The T3 relations table is not used.

- [A] E2b does not adopt the relations table. E2b §A1.2, verbatim: "Also not adopted as constraints: the P-c "Relations between boundaries" table and the 83-row K-c crossing table; they are carried as T3 [E] analysis." Under B1 ("derive … from the adopted bounded model only") it is therefore not a source of E3-REL content.
- [H] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] **E3-REL, cross-module rows** [relabelled by Act 1, F-LOG-0295; r1.1: "**E3-REL**"] (provisional name, not canonical) is **the set of T-A rows that E2b §A1.3 states, ratified per reading (R-a), as [superseded by Act 2, F-LOG-0297; r1.2: "E2b §A1.3 reports as"] "hosted on both CB1 and CB23"**, per reading, and nothing else:
  - S: R2, R3, R5, R6, R8, R9, R10, R14, R15, R16, R19, R21, R22 (13);
  - S′ / S″: R2, R3, R9, R14, R15, R16, R21, R22 (8). [added by Act 2, F-LOG-0297: "14 / 9 under the other D12 reading (R24 moves from "hosted at CV4", line 73 (r1.1; §2.2 crossing row), to shared); D12 not resolved"]
  The rows reported as crossing but hosted at MW1 (R12, R13, R23) or CV4 (R24) are not part of E3-REL. [A] The report is "recorded as a report of T3 [E], not ratified" (E2b §A1.3), so E3-REL rests on an unratified report. [Act 2, F-LOG-0297, tag: superseded by R-a (F-LOG-0290); E3-REL now rests on ratified per-reading lists of an unratified method (E2b §A2.2)]
- [O] **Whether E3-REL is a "relation" at all.** A crossing records which items a row's statement reads. That T3 r1.7 §1a (g) says a crossing is "not a boundary relation" is [A] that T3 states it (T3 [E], not adopted). E3-REL is therefore offered only as "the rows the two modules share [relabelled by Act 1, F-LOG-0295; r1.1: "two contexts share"]", not as a typed relationship. This applies equally to the S-only rows (R5, R6, R8, R10, R19) and to the rows shared under every reading. [added by Act 2, F-LOG-0297: "Relation status: [O], unchanged (Act 2)."]
- [D] Checkability, from what T2 r1.4 states in its "coordinates / checkable" column (T2 candidate, not adopted): no row of E3-REL is fully checkable from records; R22 is checkable only in its record-id part. The constraint rows R2, R3, R5, R6, R8, R9, R10, R19 are over unobserved coordinates. R14, R15 and R16 are guard rows, the absence of a constraint ("Nothing to check"; "The absence of an order is not a check"). R21 is over an observed coordinate (c), but "The constraint is on interpretation". R22's record identifiers are observed, while its "actor, act and work-event identifiers are **unobserved**".
- **[O] DDD context-map pattern: not supported by the sources.** No source names an upstream / downstream direction, a shared kernel, a customer–supplier relation, a conformist, an anti-corruption layer, a published language or a partnership between the sides. R9 types a reference from records into acts and work events ("Ref ⊆ R × (E ∪ W)"), but R3 ties an act's decision time to a record's recording time under constitution ("If con(a) = ρ then d(a) = r(ρ)"). Both directions therefore appear in constraint text (as T2 r1.4 states it; T2 candidate, not adopted), and neither is called a dependency. No pattern is proposed. [added by Act 2, F-LOG-0297: "No context-map pattern is claimed for a module cut (Act 1, I.1a); [O] unchanged." (I.1a: recorded in §2.1)]

### 2.4 The participant (VP12 (i)) spanning both modules [relabelled by Act 1, F-LOG-0295; r1.1: "spanning both"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"]

| point | class | content |
|---|---|---|
| E3-PP placement | [A] | "It is R4's single actor set 𝒜 (Def 1.2) and **spans CB1 and CB23**. "No additional role-separation assumption is introduced by this act"" (E2b §A1.2a, quoting F-LOG-0268) |
| proposal | [H] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] | **E3-PP:** the participant is a concept shared by E3-M1 and E3-M23, held by neither [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC1 and E3-BC23, owned by neither"] alone. Within E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "Within E3-BC23"] it is read as norm-maker α and executor α_W; within E3-M1 [relabelled by Act 1, F-LOG-0295; r1.1: "within E3-BC1"] as keeper k (T3 r1.7 §1a participant row: "spanning CB23 (norm-maker α, executor α_W) and CB1 (keeper k)"). These are readings of one actor set, **not roles**: no role concept is introduced (VP4 open; ARB §E3: VP4 "stated as unsupported unless decided under E2"). **[r1, m6]** The α / α_W / k readings carry the B14 caveat: they introduce no role, and they do not decide VP4 by implication ("VP12 (ii) risks deciding VP4 by implication", E3-PREREQ §6 item 12). **No shared-kernel pattern (or any other context-map pattern) is proposed for E3-PP** (§2.3 [O]) |
| how it counts | [A] | "The Human ARB selects no spanning-participant counting rule at this stage" (F-LOG-0271). Results under S, S′ and S″ are in §8 |
| S″ | [A] / [E] | S″ is a counting reading only (B14 [E]); it introduces no keeper, norm-maker or executor role and decides no VP4 |
| identity | [D] | the participant's identity is unobserved (T3 r1.5 §2 participant row: "[C] identity unobserved"; R22: actor identifiers "**unobserved**") |
| ownership | [O] | which module, if any [relabelled by Act 1, F-LOG-0295; r1.1: "which context, if any"], would be responsible for actor identity is not supported by the sources. VP12 (i) says "spanning", and no source assigns ownership of a spanning concept [added by Act 2, F-LOG-0297: "a module cut carries no ownership (I.1a)"] |

### 2.5 Per-reading host differences, and the H-D asymmetry under S [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"]

| point | S | S′ | S″ (counting only) | class |
|---|---|---|---|---|
| T-A rows that move | R5, R6, R10, R19 shared; R8 shared | R5, R6, R10, R19 in E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"]; R8 in E3-M1 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC1"] | as S′ | [D] from E2b §A1.3 |
| inactive T-B slots that move (not structure; §6) | R25, R38 cross | R25, R38 internal to CB23 | as S′ | [D] from E2b §A1.4 |
| H-D asymmetry at 𝐑₀ | "**H-D-asymmetric**: change clauses asymmetric (D-chg crosses, B-chg does not); record clauses symmetric" | "**symmetric**" | as S′ | [A] that T3 r1.7 §1a (d) records this [E]; carried by E2b §A1.9a |
| under R-i / R-i′ | asymmetric in change and record clauses | asymmetric in the record clauses only (as F-LOG-0265) | as S′ | [A] as above |
| F-LOG-0265 premise "neutral under 𝐑₀" | does not hold (T3 r1.7 [E]) | holds (T3 r1.7 [E]) | holds (T3 r1.7 [E]) | [A] E2b Act I (4): "T3 r1.7 records [E] that it holds under S′ and S″ and not under S" |
| cost of the reading | the 𝐑₀ change-clause asymmetry; four T-A frame-typing rows cross | "S′ is in tension with "spanning CB23 and CB1"" (E2b §A1.9a) | S″ "may conflict with" F-LOG-0268's "No additional role-separation assumption is introduced by this act" (E2b §A1.9a) | [A] |

**Status of the asymmetry** ([A], F-LOG-0271, verbatim): "The H-D asymmetry associated with P-c remains documented, unresolved, and unaccepted." **This proposal documents it and does not accept it.** It is not used to choose, rank or shape any module [relabelled by Act 1, F-LOG-0295; r1.1: "any context"]. F-LOG-0265's premise is not withdrawn (B3).

[A] Tier scope (E2b §A1.9a): among the constraint tiers, the asymmetry lies only in the inactive T-B slots R25 (𝐑₀) and R38 (R-j), "these are the VP1a options whose neutrality the cut is meant to preserve". Neither slot is used in any structure here (§6).

---

## 3. Candidate aggregate boundaries from CG1–CG6

### 3.1 Basis and limits

- [A] VP11 = (ii): "CG1–CG6 are kept separate, with the T-A rows that cross groupings as cross-grouping constraints, as a human design decision introducing a consistency notion not supplied by R4 (§3b)" (F-LOG-0269).
- [A] Consistency only: "consistency is admitted as a conceptual human design decision, without transaction semantics, following the package r4.1 §4.6 / §4.7 analyst gloss; the transaction part stays excluded" (F-LOG-0275 Act II (3a)).
- [A] "No grouping is enforceable from records (R23). R1–R24 remain candidate model constraints, not domain invariants" (F-LOG-0275 Act II).
- [A] that T3 §1b states (T3 [E], not adopted): "the groupings are conceptual clusters only, not units within which anything could be enforced".
- [A] F-LOG-0256 (human-preserved): "the theory does not establish aggregate or consistency boundaries … as empirical facts".
- [A] The ten labelled cross-grouping rows are interim: "Whether guard rows that cross groupings are cross-grouping constraints is returned for analysis; until then, only the ten enumerated rows are so labelled" (F-LOG-0275 Act II (3b)). [A] R11 is an open item (E2b §A1.11; F-LOG-0278); its crossing description is post-pin T3 [E] context (T3 r1.8 §6 item 1).
- **[H] Proposal E3-AG1 … E3-AG6:** each of CG1–CG6 is a **candidate aggregate boundary in the conceptual consistency sense only**: a cluster of concepts within which the candidate model constraints listed as "inside" are read together. **No transaction semantics. No invariant. No enforcement.** No aggregate root, identity, lifecycle or command is proposed ([O]: the sources supply none; a state/transition view is excluded, X15).

### 3.2 Per-grouping table

"Inside" = T-A rows the T3 §1b "T-A carried" column lists for the grouping and that cross no grouping ([D]: a filter applied to T3 §1b text, T3 [E], not adopted; the T2 ids themselves are in the adopted T3 r1.5 §2 rows). "Labelled crossing" = among the ten rows of F-LOG-0269 ([A]), with the groupings that T3 §1b names ([A] that T3 states them; T3 [E], not adopted). "Unlabelled guard crossing" = guard rows that cross groupings, question returned (C-γ). Module placement [relabelled by Act 1, F-LOG-0295; r1.1: "Context placement"] is by membership (§2.2) and is the same under every reading, except where noted. **[r1, m2]** R11 is listed only in the "unlabelled / open" column, never as "inside".

| id | provisional name | members (T1) | inside (T-A) | labelled crossing (interim) | unlabelled guard crossing (C-γ returned) | module (membership [relabelled by Act 1, F-LOG-0295; r1.1: "context (membership"]; same under S, S′, S″ unless noted) | class |
|---|---|---|---|---|---|---|---|
| **E3-AG1** | participant-with-capability cluster (provisional name, not canonical) | C1, C2 | R10 | R5 (CG3, CG1, CG2), R6 (CG5, CG2, CG1), R8 (CG4, CG1), R19 (CG1, CG3), R22 (CG4, CG1) | none named | **straddles** E3-M1 / E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC1 / E3-BC23"] through C1 [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] ([D], §2.2). Counting: §3.3 | members: [A] (T3 r1.5 §2 T1 ids, adopted trace). Inside: [D]. Labelled rows: [A] F-LOG-0269; groupings they cross: [A] that T3 §1b states them (T3 [E], not adopted). Module: [relabelled by Act 1, F-LOG-0295; r1.1: "Context:"] [D] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"]. Aggregate reading: [H] |
| **E3-AG2** | governed-target-with-norm-state cluster (provisional name, not canonical) | C3, C5, C6, C7 | R20 | R1 (CG2, CG3), R5, R6, R18 (CG2, CG5) | R17 (CG2, CG3, CG5) [D]; R21 (CG2, CG3, CG4): post-pin T3 [E] context; **R11 (CG2, CG3): open**, post-pin T3 [E] context | E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] | as above |
| **E3-AG3** | issue-act-with-adoptions cluster (provisional name, not canonical) | C8, C9, C10, C11, C12 | R4 | R1, R2 (CG3, CG4), R3 (CG3, CG4), R5, R9 (CG4 into CG3, CG5), R19 | R14 (CG3, CG4) [D]; R15 (CG3, CG4, CG5, CG6) [D]; R16 (CG3, CG4, CG5) [D]; R17 [D]; R21: post-pin T3 [E] context; R11 open | E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] | as above |
| **E3-AG4** | record cluster (provisional name, not canonical) | C13–C18 | R7 | R2, R3, R8, R9, R22 | R14, R15, R16 [D]; R21: post-pin T3 [E] context | E3-M1 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC1"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] | as above |
| **E3-AG5** | work-event cluster (provisional name, not canonical) | C19, C20 | none | R6, R9, R18 | R15, R16, R17 [D] | E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] | as above |
| **E3-AG6** | execution-trace cluster (provisional name, not canonical) | C24 | none | none | R15 [D] | E3-M1 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC1"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] | as above |

**Note on the guard-row groupings.** [A] that T3 r1.8 states the groupings for R21 (§1b: "c in CG4 / CV-text against σ in CG2 and f in CG3") and for R11 (§6 item 1: "σ in CG2 against acts E in CG3"). Both are **post-pin T3 [E] context** (the R21 entry was added to §1b under F-LOG-0273, after the r1.5 / r1.7 pins), not trace. [D] For R14–R17 T3 gives no grouping list. The cells are derived by placing each coordinate the T2 r1.4 statement names at its T1 concept's grouping (T3 r1.5 §2 T1 ids): f → C12, d → C11 (CG3); r → C14, Ref → C17, records → C13 (CG4); t(w) → C20, work events → C19 (CG5); norm value → C6 (CG2); acts → C8, C9 (CG3). **[r1, m3] Method exception for R15 → CG6:** R15's statement names no CG6 coordinate. The CG6 entry comes instead from the T3 r1.5 §2 CG6 row (and T3 §1b CG6 row), which lists R15 as CG6's only T-A row ("R15 (T-A, guard)"; §1b: "R15 (recording order is not decision order or start time; guard)"), and from T2 R15's "rests on" column, which includes C24. It is therefore not derived by the coordinate method above.

**Notes per grouping:**
- [A] that T3 §1b states, for E3-AG3 (T3 [E], not adopted; the same note is recorded in the human act F-LOG-0269): "grouping an issue act with its adoptions carries meaning mainly under D-st / D-st⁺ (VP1b, H-D clauses), where adoption gates force; without them only obj typing (R4) and the derived status (R19) link the two" (T3 §1b). The counterpart is recorded: "a grouping justified only by H-B-side clauses would lean the other way" (F-LOG-0269). [H] E3-AG3 is proposed on VP11 (ii) as decided, **not** on any VP1b outcome (N1, B13).
- [A] that T3 §1b states, for E3-AG5 / E3-AG6 (T3 [E], not adopted): "no link to CG5 is derivable" (CG6 observability). [A] VP16 (trace-to-work link) is an evidence gap (ARB §E3 exclusions). [D] No relation between E3-AG5 and E3-AG6 is proposed.
- [A] that T3 r1.8 §6 item 1 states (post-pin T3 [E] context): implication and meta rows (R12, R13, R23 at MW1; R24 at CV4) "are outside the §1b lists … (whether they belong there is not decided)". [O] Whether they bear on any aggregate boundary.
- [A] that T3 §1b states the observability per grouping (T3 [E], not adopted): E3-AG1, AG2, AG3, AG5 "unobs"; E3-AG4 "mixed" (id, r, c observed, partially); E3-AG6 "obs (recording order only)". R23 applies to every aggregate candidate: no constraint over its unobserved coordinates can be checked from records.

### 3.3 The CG1 straddle via C1, under S / S′ / S″ [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"]

[A] that T3 r1.8 §4 states (post-pin T3 [E] context, not adopted): "How the straddle **counts** for the crossing criterion is not settled: it follows the spanning readings S, S′ and S″ (§1a rule 2a), none of which is selected (F-LOG-0271), so results are reported under all three." [A] that O3 r1.2 Q-S4 states (O3 proposal [E], not adopted): "**not derived in any source.** … Any per-reading cell here would be a new [E] derivation".

The cells below extend rule 2a, which is defined for T2 statements, to a grouping. They are not derived in any source. The S and S′ cells follow directly from the readings' wording and are **[H]**. The S″ cell needs a choice of which rows to read the grouping through, so it is **[E]** (§10.2 #1).

| reading | how CG1 would count | class |
|---|---|---|
| S | C1 counts on both sides, C2 on CB23: CG1 has items on both sides and is counted as straddling | [H] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| S′ | C1 counts on neither side: CG1 is counted through C2 only, on CB23 | [H] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| S″ | S″ locates the actor per statement; a grouping is not a statement, so S″ assigns C1 no side at the grouping level ([O] whether S″ applies to elements at all). **Two results, depending on the rows chosen:** (i) read through CG1's own carried rows (R10, R22): R10 locates the actor as Cap holder (CB23); R22 names bare actor identity (unlocated) and crosses through record ids anyway; CG1 is counted on **CB23**. (ii) **[r1, M7]** read through every row that touches C1 in CG1 (R5, R6, R8, R10, R19, R22): R8 locates the actor as keeper (CB1), while R5, R6, R10 and R19 locate it on CB23; CG1 is then counted as **straddling**. Neither row choice is selected | [E] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"]; B14: counting only, no role concept |

[D] Membership is unaffected: under every reading CG1 contains C1, and C1 spans both sides (E2b §A1.2a). Only the count differs.

---

## 4. Q4–Q6: value-object, event, CS1 and MW1 questions

**Scope** ([A] F-LOG-0282 (2)): "also Q4–Q6 of the proposal. Ports (Q7): not included." No port is proposed anywhere in this document.

**What the sources support in general.** [A] that T3 §1c calls CV1–CV9 and CV-text "value-like concepts" and T3 §1d calls CE1–CE6 "domain-event-like" (T3 [E], not adopted). **[r1, M5]** In the tables below, the quoted element labels and the [A] cells quoting T3 §1c / §1d or T2 r1.4 text read "[A] that T3 / T2 states it" (T3 [E] / T2 candidate, not adopted). Only the T2 row ids themselves come from the adopted T3 r1.5 §2 rows. [A] E2b §3 (B11): these labels "stay structuring hypotheses, not aggregates, value objects, domain events or bounded contexts". [O] **No source states any value-object property** (equality by value, immutability, absence of identity, side-effect-free behaviour) **or any domain-event property** (publication, subscription, ordering, causation, handler) for any of these elements. Every mapping below is therefore **[H]**, and every property beyond the T3 description is **[O]**.

### 4.1 Q4: CV1–CV9 and CV-text

| id | adopted element (T3 §1c label, quoted) | [H] proposed reading | what the sources support ([A] / [D]) | open ([O]) | module (membership) [relabelled by Act 1, F-LOG-0295; r1.1: "context (membership)"] |
|---|---|---|---|---|---|
| E3-VO1 | CV1 "deontic value with a permitting subset" | value-object candidate | [A] typing only (R5, R18); "**P ≠ V is a parameter** (A6), VP13" | VP13 unset; equality semantics | E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| E3-VO2 | CV2 "norm as target ↦ value" | value-object candidate | [A] R1, R5; OQ3 (richer content is an extension; VP7 unsupported) | content beyond target ↦ value (VP7) | E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| E3-VO3 | CV3 "three governance times kept distinct (r, d, f), work start outside them" | [H] **weak**: a grouping of distinct time attributes, not one value | [A] carried rows R3, R14, R15. R14 (no equation) and R15 (no order) are guard / non-constraint rows; R3 is a constraint that holds only under constitution, whose antecedent con is unobserved; [A] it straddles the cut (E2b §A1.2) | whether a set of two guard rows and one conditional constraint can be a value object | straddles [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| E3-VO4 | CV4 "report-vs-constitution mode of a record–act pair, **unknown-capable** (reports / is / undetermined)" | value-object candidate with a mandatory "undetermined" value | [A] R2, R9, R24; "mode is not fixable from O" (R24); R39 is an inactive slot (VP2) | VP2 undecided; whether "undetermined" is a value or an absence | E3-M1 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC1"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| E3-VO5 | CV5 "prepared / adopted status (derived)" | derived value candidate | [A] R19 "Adoption status is derived (definitional)"; R27, R28 inactive (VP1b) | what status implies for force (VP1b, inactive) | E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| E3-VO6 | CV6 "conformance / violation (derived)" | derived value candidate | [A] R18 "Violation (definitional)"; R32–R34 inactive (VP1c) | VP1c; VP13 | E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| E3-VO7 | CV7 "not-evidenced-in-capture marker (absence ≠ non-occurrence)" | marker candidate | [A] R16 guard (no completeness); X18 excluded; R83 inactive with its condition | whether a marker of absence is a value | E3-M1 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC1"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| E3-VO8 | CV8 "record independence for a target (predicate)" | [H] predicate, not a stored value | [A] no T-A row; "[C] NOT_DETERMINABLE" (T3 r1.5 §2); R62 excluded | any value-object reading | none [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] ([A] E2b §A1.2: ungrouped; on no side under P-c) |
| E3-VO9 | CV9 "limitation kind: structural non-identifiability vs instrument unreliability" | **no mapping proposed** | [A] "meta-level, not a record attribute" (T3 §1c) | **CV9 / §3b tension, unresolved** (§10): "CV9's adoption may already engage the T3 §3b row "epistemic-status / evidential-limitation information in the model", which E2b §2d lists as excluded" (E3-PREREQ r4.1 §6 item 1, as quoted in package r1.1 §4) | none [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] ([A] E2b §A1.2: ungrouped; on no side under P-c) |
| E3-VO-text | CV-text "record text as content (incl. self-asserted force / status)" | value-object candidate (text as content) | [A] R21 "Texts are texts"; observed (c, partially); "content only, never σ, f or con" | — beyond the T3 description | E3-M1 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC1"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |

[A] Inactive slots on CV4 (R39), CV5 (R27, R28) and CV6 (R32–R34) stay inactive (B4); no value reading uses them.

### 4.2 Q5: CE1–CE6

[A] that T3 §1d states (T3 [E], not adopted): "**No order among them is held** beyond d = r under constitution (R3), and that antecedent is unobserved (R15; Rem 1.9a). **No occurrence is said to produce or cause another** (VP1a; X1; Rem 0.1)." [A] that T3 §3b states (T3 [E]; E2b §2d carries the §3b rows as excluded): "an order or sequence among occurrences" is an extension needing "new evidence or human design decision (N3 applies)". [A] F-LOG-0256: the theory does not establish "event order or causal relationships as empirical facts".

**[H] Proposal:** each CE is a candidate domain event **only in the sense of a dated happening** of the adopted model. **[O] No ordering, causation, production, publication, subscription or handling is proposed or supported.** No event flows between modules [relabelled by Act 1, F-LOG-0295; r1.1: "between contexts"] are proposed.

| id | adopted element (T3 §1d label) | carried T-A row(s) and their cut status (S / S′, S″) | observability ([A] that T3 §1d states it; T3 [E]) | open ([O]) | module (membership) [relabelled by Act 1, F-LOG-0295; r1.1: "context (membership)"] |
|---|---|---|---|---|---|
| E3-EV1 | CE1 "issue occurrence (at decision time d)" | R5: crosses / internal CB23 [D] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] | unobs | VP3 act identifiability (evidence gap); VP8 act kinds (unsupported) | E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| E3-EV2 | CE2 "adoption occurrence (at d; object is an issue act)" | R4: internal CB23; R19: crosses / internal CB23 [D] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] | unobs | VP3 | E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| E3-EV3 | CE3 "entry into force (time f, or never)" | R1 internal CB23; R14 crosses (all readings) [D] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] | unobs | — | E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| E3-EV4 | CE4 "norm-state change point" | R20 internal CB23 [D] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"]. [A] "**Chg-constraint slot empty**" (VP1a, inactive) | unobs | VP1a (inactive; never decided here) | E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| E3-EV5 | CE5 "work start" | R6: crosses / internal CB23 [D] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] | unobs | VP16 link to CG6 (evidence gap) | E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| E3-EV6 | CE6 "recording" | R7 internal CB1 (all readings) [D] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] | "**obs** (r, partially; A07)" | — | E3-M1 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC1"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |

[D] From what T3 §1d states (T3 [E]): only E3-EV6 is over an observed coordinate. Every other event candidate is over unobserved coordinates, so no source can show that it occurred (R23).

### 4.3 Q6: CS1 and MW1

| id | what the sources support | proposal | class |
|---|---|---|---|
| E3-CS1 | [A] that T3 §1e states (T3 [E], not adopted): CS1 is a "norm-state attestation channel (**unrealized slot**; no instrument shown; E-A5 / Protocol C not authorized)", with constraints "T-D (meta) only: R66 … R82". [A] ungrouped, on no side under P-c (E2b §A1.2) | **No DDD mapping.** E3-CS1 records only that CS1 is in no module [relabelled by Act 1, F-LOG-0295; r1.1: "no context"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] and no aggregate | [A] as stated; "no module [relabelled by Act 1, F-LOG-0295; r1.1: "no context"]" [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] (§2.2); [O] any mapping: not supported, and E-A5 is not authorized |
| E3-MW1 | [A] T1 ids "all C-rows" (adopted T3 r1.5 §2 row). [A] that T3 states (T3 [E], not adopted): MW1 is "model-wide: checkability boundary and clause implications" (§1e); "R23 is the observability criterion" (§1e); "MW1 implications R12, R13 attach to no single element" (§6 item 7; T3 [E], not adopted (pre-pin §6 text)) | **No DDD mapping as a module or aggregate [relabelled by Act 1, F-LOG-0295; r1.1: "as a context or aggregate"].** [H] E3-MW1 is carried as a model-wide constraint set that every module and aggregate [relabelled by Act 1, F-LOG-0295; r1.1: "every context and aggregate"] candidate is subject to: R23 bounds what any of them could check. R12 and R13 are implications whose antecedents "are **not asserted**" (T3 §1e, T3 [E]) | **[r1, M3] [E] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"]** that MW1 is in no module [relabelled by Act 1, F-LOG-0295; r1.1: "no context"] (supplementary rule, §2.2; §10.2); [H] the "applies to all" reading; [O] any further mapping |

---

## 5. Cross-grouping constraints and their limits

[A] The ten labelled rows: "R1, R2, R3, R5, R6, R8, R9, R18, R19 and R22 (T3 §1b)" (F-LOG-0269). [A] Interim, pending C-γ (F-LOG-0275 Act II (3b)). [A] "R1–R24 remain candidate model constraints, not domain invariants"; "No grouping is enforceable from records (R23)".

**[H] Proposal E3-XG:** the ten rows are carried as **conceptual cross-grouping constraints** between the aggregate candidates. They are not enforced by any aggregate, not checked from records, and carry no transaction semantics.

| row | T2 r1.4 working label (quoted, short) | groupings crossed ([A] that T3 §1b states; T3 [E]) | crosses the module cut [relabelled by Act 1, F-LOG-0295; r1.1: "context cut"]? S | S′ | S″ | checkable from records ([A] that T2 r1.4 states; T2 candidate) |
|---|---|---|---|---|---|---|
| R1 | "F1 (entry into force)" | CG2 ↔ CG3 | no, internal CB23 | no | no | "Not checkable or enforceable from records" |
| R2 | "F2, reference part" | CG3 ↔ CG4 | **yes** | **yes** | **yes** | "Not checkable" |
| R3 | "F2, time part: d = r under constitution" | CG3 ↔ CG4 | **yes** | **yes** | **yes** | "Not checkable" |
| R5 | "Act partition and act attribute typing" | CG3, CG1, CG2 | **yes** | no, internal CB23 | no, internal CB23 | "Not checkable" |
| R6 | "Work-event attribute typing" | CG5, CG2, CG1 | **yes** | no, internal CB23 | no, internal CB23 | "Not checkable" |
| R8 | "Record typing, keeper" | CG4, CG1 | **yes** | no, **internal CB1** | no, **internal CB1** | "Not checkable" |
| R9 | "Reference and constitution typing" | CG4 → CG3, CG5 | **yes** | **yes** | **yes** | "Not checkable" |
| R18 | "Violation (definitional)" | CG2 ↔ CG5 | no, internal CB23 | no | no | over unobserved coordinates |
| R19 | "Adoption status is derived (definitional)" | CG1 ↔ CG3 | **yes** | no, internal CB23 | no, internal CB23 | "Not checkable" |
| R22 | "Record identity is identifier identity" | CG4, CG1 | **yes** | **yes** | **yes** | record ids observed; actor, act and work-event ids unobserved |

Class: row ids [A] (F-LOG-0269); groupings crossed: [A] that T3 §1b states them (T3 [E], not adopted); cut status [D] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] from E2b §A1.3; checkability: [A] that T2 r1.4 states it (T2 candidate, not adopted).

**Counts [D] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"]:** of the ten rows, 8 also cross the module cut [relabelled by Act 1, F-LOG-0295; r1.1: "context cut"] under S (all but R1, R18); 4 under S′ and S″ (R2, R3, R9, R22). R8 is internal to E3-M1 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC1"] under S′ / S″ although it crosses CG4 ↔ CG1.

**Not labelled, carried open:**
- [A] Guard rows that cross groupings: R14, R15, R16, R17, R21. "Whether guard rows that cross groupings are cross-grouping constraints is returned for analysis" (C-γ). They are **not** members of E3-XG.
- [A] R11 is open (E2b §A1.11; F-LOG-0278). That it "appears to cross groupings … adding it needs its own act" is post-pin T3 [E] context (T3 r1.8 §6 item 1). Not a member of E3-XG.
- [A] A later C-γ answer re-derives the §3 and §5 outputs (package r1.1 §6, as cited in O3 r1.2 §6).

---

## 6. Inactive T-B policy slots (non-active extension points only)

[A] "All T-B rows (R25–R39, R79, R80, R83) remain **inactive** policy slots; R83 keeps its condition" (F-LOG-0261). R83's condition: "the non-occurrence inference (X18, R49) is never applied to the observed capture" (F-LOG-0254).

**They are listed here only as non-active extension points. No module, [relabelled by Act 1, F-LOG-0295; r1.1: "No context,"] aggregate, value-object or event candidate above uses, contains or is shaped by any of them.** The "slot attaches to" column records where T3 lists the slot. It is not a structural placement.

| rows | slot attaches to (T3 r1.5 §2 / §1) | VP (undecided; inactive) | cut status S | S′ / S″ | class |
|---|---|---|---|---|---|
| R25 (D-chg), R26, R38 | CG2, CE1, CE4 | VP1a | R25, R38 cross; R26 internal | all internal CB23 | [A] slot list; [D] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] cut status (E2b §A1.4) |
| R30, R31, R37 | CG2, CE4 | VP1a | internal | internal | as above |
| R27, R28 | CG3, CV5, CE2, CE3 | VP1b | internal | internal | as above |
| R32, R33, R34 | CV6, CE5 | VP1c | internal | internal | as above |
| R29, R35, R36, R83 | VP1d (record clauses) | VP1d | cross | cross | as above |
| R79 | VP1d | VP1d | internal | internal | as above |
| R80 | VP1d (no clause) | VP1d | none | none | as above |
| R39 | CG4, CV4 | VP2 | cross | cross | as above |

[A] ARB §E3 exclusions: "undecided VP1a–d, and undecided VP2, VP13, as inactive slots". [D] Count: 3 + 3 + 2 + 3 + 4 + 1 + 1 + 1 = 18, matching E2b §A1.4.

---

## 7. Traceability table

**Rules [A]:** every architecture element traces to the adopted elements, the 27 only (E2b §A1.2). The CB4 slot is not a trace target ([A] "not an element"; O3 r1.2 V1). The 25 baseline elements trace through **T3 r1.5 §2** (full row; package B1: "For these only, the full T3 r1.5 §2 row applies"). CB1 and CB23 trace through **T3 r1.7 §2, restricted** (F-LOG-0275 Act I (1)).

**Verification [D]:** the T3 r1.8 §2 rows of the 25 baseline elements are byte-identical to commit 7bccae4d8 (T3 r1.5), and the CB1 and CB23 rows to commit 6e0407db7 (T3 r1.7). Checked by the deriver with `git show` and a per-row hash comparison on 2026-10-12.

**Chain:** E3 element → E2b r1.2 element → T3 §2 row → T2 r1.4 ids (tier) → T1 r1.6 ids → R4 r3.1 / FROZEN R3 ids. The evidence class, inherited assumptions, reading sensitivity and open questions cells of the 25 apply in full by reference to T3 r1.5 §2.

### 7.1 Elements built on the 25 baseline elements

| E3 element | E2b element(s) | T3 r1.5 §2: T2 ids (tier) | T1 ids | R4 / FROZEN R3 ids | at risk? |
|---|---|---|---|---|---|
| E3-AG1 (membership, inside / crossing rows) | CG1 | R10, R22 (T-A) | C1, C2 | Def 1.2, 1.4–1.6, 1.7a; Obs O5, O8, O9; Lem 1.12; G07, H19 | no. **Its module column [relabelled by Act 1, F-LOG-0295; r1.1: "context column"] (straddle) [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"]** |
| E3-AG2 | CG2 | R11, R20, R1 (T-A); R25, R26, R30, R31, R37, R38 (T-B slot) | C3, C5, C6, C7 | Def 1.1, 1.3, 1.10; Obs O4; Rem 0.1; Rem 9.8; A01, A10, B07, D04, F06, H14, D16 | no; module column [relabelled by Act 1, F-LOG-0295; r1.1: "context column"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| E3-AG3 | CG3 | R1, R2, R3, R4, R5, R14, R15, R19 (T-A); R27, R28 (T-B slot) | C8–C12 | Def 1.4, 1.8, 1.9, 1.10; Obs O2, O3; Lem 3.7; A05–A08, B11, F07, F08, F17, G05, H04, H12 | no; module column [relabelled by Act 1, F-LOG-0295; r1.1: "context column"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| E3-AG4 | CG4 | R2, R3, R7, R8, R9, R21, R22 (T-A); R39 (T-B slot) | C13–C18 | Def 1.6, 1.8, 1.11; Rem 1.11a; Obs O1, O6, O7; A02, A03, A05–A07, F02, F13, F14, F17, G01, B11 | no; module column [relabelled by Act 1, F-LOG-0295; r1.1: "context column"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| E3-AG5 | CG5 | R6, R15, R18 (T-A) | C19, C20 | Def 1.5, 1.9; Obs O8; B06, A03, G05 | no; module column [relabelled by Act 1, F-LOG-0295; r1.1: "context column"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| E3-AG6 | CG6 | R15 (T-A, guard) | C24 | Obs O8; B06, A11, A15, G03 | no; module column [relabelled by Act 1, F-LOG-0295; r1.1: "context column"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| E3-XG | CG1–CG5 (the groupings the ten rows cross) | R1, R2, R3, R5, R6, R8, R9, R18, R19, R22 (T-A), as in the CG rows above | as the CG rows | as the CG rows | no. **Its cut-status column [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"]** |
| E3-VO1 … E3-VO9, E3-VO-text | CV1 … CV9, CV-text | per T3 r1.5 §2 (O3 r1.2 §2a, verbatim) | C4; C5; C22 (C11, C12, C14, C20); C18, C27; C10; C21; C23; C25; C28; C15 | per T3 r1.5 §2 | no; module column [relabelled by Act 1, F-LOG-0295; r1.1: "context column"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] (including the "no module [relabelled by Act 1, F-LOG-0295; r1.1: "no context"]" claims for E3-VO8 and E3-VO9) |
| E3-EV1 … E3-EV6 | CE1 … CE6 | R5; R4, R19; R1, R14; R20; R6; R7 (T-A), plus the T-B slots of T3 r1.5 §2 | C8, C11; C9, C11; C12; C7; C19, C20; C13, C14 | per T3 r1.5 §2 | no; module and cut columns [relabelled by Act 1, F-LOG-0295; r1.1: "context and cut columns"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] |
| E3-CS1 | CS1 | R66–R69, R82 (T-D meta) | C26 | Def 4.4; Props 4.5, 4.6; Rem 4.5b, 4.6a; Lem 4.7; Cor 4.3; F01–F03 | no; **its "no module [relabelled by Act 1, F-LOG-0295; r1.1: "no context"]" claim [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"]** |
| E3-MW1 | MW1 | R23, R12, R13 (T-A meta / implications) | all C-rows | Lem 1.12; Lem 3.3, 3.5, 3.6; Prop 3.4; Def 3.2; G06, H07 | no; **its "no module [relabelled by Act 1, F-LOG-0295; r1.1: "no context"]" claim [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"]** ([E] rule, §2.2) |
| E3-PP (identity, typing rows) | participant | R5, R6, R8 (typing of α, α_W, k), R22 (actor ids) (T-A) | C1 | Def 1.2, 1.4–1.6, 1.7a; Obs O8, O9; Lem 1.12; A05, A06 (role names in texts only) | **placement "spans both modules (E3-M1 and E3-M23) [relabelled by Act 1, F-LOG-0295; r1.1: "spans E3-BC1 and E3-BC23"]" [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"]** |

### 7.2 Elements derived from CB1 / CB23 (all [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"]): readings (a) and (b) of A2

| E3 element | E2b element | **reading (a): restricted trace** (T3 r1.7 §2; T1 ids and R4 / R3 ids not keyed to hosting; F-LOG-0275 Act I (1)) | **reading (b): what is missing** (cells not adopted; would need (d′) **and** an E2b amendment, two separate acts, not authorized) |
|---|---|---|---|
| **E3-M1** [relabelled by Act 1, F-LOG-0295; r1.1: "**E3-BC1**"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] | CB1 | T1: C13–C18, C23, C24, C27. R4 / R3: "Def 1.6, 1.8, 1.11; Rem 1.11a; Obs O1, O6, O7, O9; Lem 1.12; Lem 5.2; Cor 5.3; A02, A03, A05–A07, A11, A15, B06, B09 (caveat), B11, F02, F11, F13, F14, F17, G01, G03, G04, H11 (as r1.5)". **T2 link: none adopted.** Hosts only from the E2b §A1.3 / §A1.4 per-reading report ("a report of T3 [E], not ratified"). **Exception under (a):** the S-reading results for R5, R6, R10, R19, R25 and R38 stay exposed: "the CB1 cell carries no R4 ids for R5, R6, R10, R19, R25 and R38, although these rows cross under S" (E2b §A1.2 G6) | the §2 "T2 ids" cell; the evidence-class, inherited-assumptions, reading-sensitivity and open-questions cells; the hosting-keyed clause "for the shared guards R14, R15: Def 1.9; G05, H04, H12 (as CB23)"; the R4 ids for R5, R6, R10, R19, R25 and R38 under S. The existing r1.7 cells are stale (they "encode the superseded r1.6 placement (C1 on CB23)", E2b §A1.2). The amendment would have to adopt the reading-dependent cells per reading, selecting none (O3 r1.2 §6 A2 (b)) [superseded by Act 2, F-LOG-0297: trace per `R7/E3b-REASSESSMENT-MODULE-CUT-PROPOSAL.md` r1.2 §II.2; outputs accepted / pending as ticked: all accepted] |
| **E3-M23** [relabelled by Act 1, F-LOG-0295; r1.1: "**E3-BC23**"] [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] | CB23 | T1: C2–C12, C19, C20, C21. R4 / R3: "Def 1.1–1.5, 1.8–1.10; Rem 0.1; Rem 1.9a; Rem 3.1a; Rem 4.10; Obs O2–O5, O8; Lem 3.7; Rem 9.7, 9.8; A01, A03, A05–A08, A10, B06, B07, D04, E01 (recorded bearing), F06–F08, F17, G05, G07, H04, H10, H12, H14, H19 (union of r1.5 CB2 and CB3)". T2 link: as E3-M1 [relabelled by Act 1, F-LOG-0295; r1.1: "as E3-BC1"]. Exception under (a): as E3-M1 [relabelled by Act 1, F-LOG-0295; r1.1: "as E3-BC1"] for the S-reading rows | as E3-M1 [relabelled by Act 1, F-LOG-0295; r1.1: "as E3-BC1"], with the hosting-keyed clause "for the shared rows R8, R9, R16, R21, R22, as CB1" (reading-dependent: R8 is internal to CB1 under S′ / S″) [superseded by Act 2, F-LOG-0297: trace per `R7/E3b-REASSESSMENT-MODULE-CUT-PROPOSAL.md` r1.2 §II.2; outputs accepted / pending as ticked: all accepted] |
| **E3-REL** [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] (§2.3, rebuilt in r1, M1) | CB1, CB23 | T1 and R4 / R3 as the two rows above. **Its content (the shared T-A rows) has no T2 link in the restricted trace;** it comes only from the E2b §A1.3 per-reading report, adopted into E2b's text as "a report of T3 [E], not ratified". **[O] Whether that report counts as part of "their §2 rows" under (a) is not supported by any source. E3-REL is therefore not claimed as meeting the criterion under (a).** (The T3 relations table is no longer used: E2b §A1.2 does not adopt it.) | **not met:** the T2 ids and reading-sensitivity cells, per reading, plus the cells listed for E3-M1 / E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC1 / E3-BC23"] [superseded by Act 2, F-LOG-0297: trace per `R7/E3b-REASSESSMENT-MODULE-CUT-PROPOSAL.md` r1.2 §II.2; outputs accepted / pending as ticked: all accepted] |
| **E3-PP placement** [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] (§2.4) | participant + CB1 + CB23 | participant: full T3 r1.5 §2 row (§7.1). "Spans": E2b §A1.2a ([A]). CB side: as above | **not met:** as E3-M1 / E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC1 / E3-BC23"] [superseded by Act 2, F-LOG-0297: trace per `R7/E3b-REASSESSMENT-MODULE-CUT-PROPOSAL.md` r1.2 §II.2; outputs accepted / pending as ticked: all accepted] |
| **module columns** [relabelled by Act 1, F-LOG-0295; r1.1: "**context columns**"] of every §7.1 element [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] | CB1, CB23 + the element | membership: T1 ids of the element (T3 r1.5 §2) against CB1 / CB23 T1 ids. Under (a) the restricted trace counts as "their §2 rows", so the module columns [relabelled by Act 1, F-LOG-0295; r1.1: "the context columns"] **meet** the criterion under (a) | **[r1, M2] not met.** The CB1 / CB23 T1 ids are adopted by Act I (1) whatever A2 says, but adoption is not the criterion. Under (b) the criterion needs the whole CB1 / CB23 §2 rows, whose other cells are not adopted. Every module column [relabelled by Act 1, F-LOG-0295; r1.1: "Every context column"] therefore stays unmet under (b) until (d′) and the E2b amendment are made [superseded by Act 2, F-LOG-0297: trace per `R7/E3b-REASSESSMENT-MODULE-CUT-PROPOSAL.md` r1.2 §II.2; outputs accepted / pending as ticked: all accepted] |
| **cut-status columns** (§2.2, §4, §5, §6) [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"] | CB1, CB23 + the rows | E2b §A1.3 / §A1.4 per-reading report only (not ratified). **[O]** as for E3-REL: not claimed as meeting the criterion under (a) | **not met:** the T2 ids and reading-sensitivity cells, per reading [superseded by Act 2, F-LOG-0297: trace per `R7/E3b-REASSESSMENT-MODULE-CUT-PROPOSAL.md` r1.2 §II.2; outputs accepted / pending as ticked: all accepted] |

**A2′ (carried; [A] that O3 r1.2 §6 states (O3 proposal [E], not adopted)):** "The F-LOG-0248 chain (concept → R4 / R3 → inherited assumptions → reading sensitivity) **cannot be completed** for outputs derived from CB1 / CB23." Under reading (a) the chain for every ⚠E3b row stops after the R4 / R3 link. **[D] Under reading (a), the ARB §E3 trace criterion is met for E3-M1, E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC1, E3-BC23"], the E3-PP placement and the module columns [relabelled by Act 1, F-LOG-0295; r1.1: "context columns"], with the S-reading exception. It is not claimed for E3-REL or the cut-status columns, whose status under (a) is [O] (they rest on the unratified E2b §A1.3 / §A1.4 report). Under reading (b), it is not met for any ⚠E3b output.** [superseded by Act 2, F-LOG-0297: trace per `R7/E3b-REASSESSMENT-MODULE-CUT-PROPOSAL.md` r1.2 §II.2; outputs accepted / pending as ticked: all accepted]

---

## 8. Per-reading results

### 8.1 Construction of the single neutral proposal (disclosure)

[E] The proposal uses **per-reading annotations around a reading-invariant core.** It does not build any element by intersection or by union.
- **The core** holds what is identical under S, S′ and S″: element placement by concept membership (§2.2), the six aggregate candidates and their members (§3.2), the ten labelled rows and the groupings they cross (§5), the value-object and event candidates (§4), and the inactive slots (§6). **[r1, m5]** One core statement coincides with a reading: calling the participant and CG1 "straddling" (a membership statement) matches the **S** count. Under S′ the count differs; under S″ it depends on the row choice (§3.3, §10.2 #1). Both are shown beside it (§8.2), and the membership statement selects no reading.
- **The annotations** hold every result that differs. All three readings are shown in every such cell (§8.2).
- **Disclosure of what the core equals [D].** The rows that cross the cut under every reading are the intersection of the crossing sets. It equals S′ / S″ (36 of 83; T-A 12). The rows internal to a module [relabelled by Act 1, F-LOG-0295; r1.1: "internal to a context"] under every reading are the intersection of the internal sets. It equals S (CB1 {R7}; CB23 six T-A rows). **Read alone, the core therefore reproduces S′ / S″ on crossings and S on internal rows.** For that reason no crossing or placement result [relabelled by Act 1, F-LOG-0295; r1.1: "crossing or ownership result"] is stated from the core alone; the five reading-dependent T-A rows (R5, R6, R8, R10, R19) are always shown with all three readings. The union of the crossing sets equals S (46; T-A 17) (O3 r1.2 §3b note: "intersection … coincides with S′ / S″ … union … coincides with S"). Neither is used.
- **Order.** S, S′, S″ appear in source order, which is not a ranking ([A] that T3 r1.7 rule 2a states (T3 [E], not adopted; E2b §A1.10): S is "reported first … **not because T1, T2 or R4 support it over the alternatives.**"). No reading is privileged.

### 8.2 Elements whose placement or crossing differs (all [flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"])

| element / result | S | S′ | S″ (counting only) | class |
|---|---|---|---|---|
| E3-PP (participant) count | both modules [relabelled by Act 1, F-LOG-0295; r1.1: "both contexts"] | neither module [relabelled by Act 1, F-LOG-0295; r1.1: "neither context"] | per statement: E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] as actor of an act, actor of a work event or Cap holder; E3-M1 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC1"] as keeper; unlocated → no side | [A] E2b §A1.9a; and [A] that T3 r1.7 rule 2a states it (T3 [E], not adopted; E2b §A1.10) |
| E3-AG1 (CG1) count | straddles [H] | E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] (via C2) [H] | [E]: E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] (via C2, R10) if read through CG1's own rows; **straddles** (via R8, keeper on CB1) if read through every C1-touching row; no row choice selected | §3.3 |
| R5 (on E3-AG1, AG2, AG3; E3-VO1, VO2; E3-EV1) | shared | internal E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] | internal E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] | [D] E2b §A1.3 |
| R6 (on E3-AG1, AG2, AG5; E3-EV5) | shared | internal E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] | internal E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] | [D] |
| R8 (on E3-AG1, AG4) | shared | internal E3-M1 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC1"] | internal E3-M1 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC1"] | [D] |
| R10 (on E3-AG1) | shared | internal E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] | internal E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] | [D] |
| R19 (on E3-AG1, AG3; E3-VO5; E3-EV2) | shared | internal E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] | internal E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "E3-BC23"] | [D] |
| T-A placement: E3-M1 internal / E3-M23 internal [relabelled by Act 1, F-LOG-0295; r1.1: "T-A ownership: E3-BC1 internal / E3-BC23 internal"] / shared / at MW1, CV4 | 1 / 6 / 13 / 4 | 2 / 10 / 8 / 4 | as S′ | [D] [added by Act 2, F-LOG-0297: under the other D12 reading, shared 14 / 9 (R24 moves from "hosted at CV4" to shared) and the "at MW1, CV4" count 3 / 3 (from 4 / 4); D12 not resolved] |
| E3-XG rows also crossing the cut | 8 | 4 | 4 | [D] (§5) |
| E3-REL content (shared T-A rows only; whether this is a "relation" is [O], §2.3) | R2, R3, R5, R6, R8, R9, R10, R14, R15, R16, R19, R21, R22 (13) | R2, R3, R9, R14, R15, R16, R21, R22 (8) | as S′ | [H] built on [D] from E2b §A1.3 (§2.3) [added by Act 2, F-LOG-0297: "14 / 9 under the other D12 reading (R24 moves from "hosted at CV4", line 73 (r1.1; §2.2 crossing row), to shared); D12 not resolved"] |
| inactive T-B R25, R38 (§6; not structure) | cross | internal CB23 | internal CB23 | [D] E2b §A1.4 |
| excluded / open rows (not carried): T-C R63; T-D R71, R76 | cross | internal CB23 | internal CB23 | [A] that the T3 r1.7 §1a crossing table states it (T3 [E], not adopted as a constraint) |
| crossing totals, 83 T2 rows | 46 / 29 / 1 / 3 / 4 (range 44–52) | 36 / 38 / 2 / 3 / 4 | 36 / 38 / 2 / 3 / 4 | [A] E2b §A1.9a |
| H-D asymmetry at 𝐑₀ | change clauses asymmetric | symmetric | symmetric | [A] (documented, NOT accepted) |
| R-i / R-i′ | asymmetric, change and record clauses | asymmetric, record clauses | as S′ | [A] |
| F-LOG-0265 premise | does not hold | holds | holds | [A] (premise not withdrawn) |
| cost of the reading | asymmetry; frame-typing rows cross | tension with "spanning" | possible conflict with F-LOG-0268; VP4-by-implication risk | [A] E2b §A1.9a; E3-PREREQ §6 item 12 |

[D] Every other result in §2–§6 (memberships, aggregate members, groupings crossed, observability, the value-object and event candidates, the slots) is the same under all three readings.

---

## 9. Self-assessment against ARB §E3 acceptance criteria and V1–V11

**This is the deriver's self-assessment. It is evidence for the reviewer, not a verdict** (EP-02: engineering never accepts its own work).

### 9.1 ARB r4.4 §E3

| criterion (verbatim) | status | evidence |
|---|---|---|
| "every architecture element traces to T3 elements and their §2 rows" | **met for the 25-based elements. Under A2 (a): met, with the S exception, for E3-BC1, E3-BC23, the E3-PP placement and the context columns; [O] for E3-REL and the cut-status columns (they rest on the unratified E2b §A1.3 / §A1.4 report). Under A2 (b): NOT met for any ⚠E3b output** [superseded by Act 2, F-LOG-0297: trace per `R7/E3b-REASSESSMENT-MODULE-CUT-PROPOSAL.md` r1.2 §II.2; outputs accepted / pending as ticked: all accepted. The trace source becomes T3 r1.9, whole rows (F-LOG-0290). Point (i), the explicit "none" for R25 and R38 under S: ruled sufficient by (R-none) (a), a human ruling, not a finding (F-LOG-0297)] | §7.1 (full rows, git-verified pins); §7.2 (both readings). A2 is carried open (F-LOG-0282), so the criterion's final status is for E3b |
| "standing qualifier carried" | met | header; §10 (verbatim) |
| "independent review" | **met under the F-LOG-0282 stop rule, with a limitation** | round 1 on r0: PASS_WITH_LIMITATIONS (M1–M7, m1–m10, applied in r1); round 2 on r1: **round 2 PWL, nothing blocking; r1.1 wording fixes not re-reviewed** (W1–W9, §12). Reviewers Claude-family (Λ7) |
| AH2 (F-LOG-0271, recorded in §E3): "Any O3/E3 proposal must present the relevant results under all three interpretations: S, S′ and S″. It must not silently privilege one interpretation or present the asymmetry as an accepted property of the cut." | met (self-assessed) | §2.5, §3.3, §5, §8; construction disclosed (§8.1); asymmetry documented, not accepted |
| exclusions: undecided VP1a–d, VP2, VP13 as inactive slots | met | §6; VP13 noted at E3-VO1 |
| exclusions: "never invariants (N1, N2, N5)" | met | §3.1, §5; no row is called an invariant |
| exclusions: "VP3, VP6, VP16, VP9 as evidence gaps" | met | VP3 at E3-EV1/EV2; VP16 at E3-AG5/AG6, E3-EV5; **[r1, M6]** VP6 and VP9 in §10.1 ("evidence gaps" row) |
| exclusions: "VP4, VP7, VP8 stated as unsupported" | met | VP4 at §2.4; VP7 at E3-VO2; VP8 at E3-EV1 |
| exclusions: "no canonical terms (R8); no implementation; no generality claim" | met | every name "(provisional name, not canonical)"; no implementation content; one-corpus qualifier |
| unresolved risks carried: "one-corpus primitives (VP5); low reversibility of VP15; R23 (nothing enforceable from records)" | met | §10 |

### 9.2 V1–V11 (O3 r1.2 §8)

| # | status | evidence |
|---|---|---|
| V1 trace table to the 27, readings (a) and (b), partial chain disclosed | met as a table: both readings reported, with the (a) exception, the (b) gap, and E3-REL and the cut-status columns carved out of "met under (a)" as [O] | §7 |
| V2 S / S′ / S″ side by side; none privileged; S″ counting only | met | §8; §2.4 S″ row |
| V3 asymmetry disclosed, not accepted | met | §2.5 |
| V4 no T-B / T-C / X item as an invariant; T-B inactive; R83 condition | met | §6 |
| V5 R1–R24 as candidate constraints; R23 for every grouping-derived element | met | §3.1, §3.2 note, §5 |
| V6 only the ten labelled rows; guard rows and R11 open | met | §5 |
| V7 exclusions list | met | §9.1 |
| V8 neutrality statement, no VP1a–d reference (N1) | met | header; E3-AG3 note |
| V9 qualifier, ARB §B, Λ1–Λ7, F-LOG-0256, D4–D6 | met | §10 |
| V10 open items carried, unresolved | met | §10 |
| V11 the review record | met | rounds 1 and 2 recorded in the header and §12: round 1 PWL, no blocker; **round 2 PWL, nothing blocking; r1.1 wording fixes not re-reviewed** |

### 9.3 B1–B14 (package r1.1 §3 governs)

| # | status | note |
|---|---|---|
| B1 adopted model only, with provenance | met | §7; CB1 / CB23 hosting only from E2b §A1.3 / §A1.4. **[r1, M1]** The unadopted T3 relations table is no longer used for E3-REL; unadopted T3 / T2 text is tagged "[A] that T3 / T2 states" (§1) |
| B2 S, S′, S″ | met | §8 |
| B3 F-LOG-0265 premise | met | §2.5, §8.2 |
| B4 T-B inactive; R83 | met | §6 |
| B5 R1–R24 candidates only | met | §3.1, §5 |
| B6 consistency only | met | §3.1 |
| B7 ten rows interim | met | §5 |
| B8 qualifier | met | header, §10 |
| B9 limitations | met | §10 |
| B10 exclusions | met | §9.1 |
| B11 structuring hypotheses | met as E3 permits: every mapping is [H] and every property beyond T3 is [O] | §2–§4 |
| B12 open items not resolved | met | §10 |
| B13 N1 | met | no VP1a–d outcome used |
| B14 S″ counting only | met | §2.4, §3.3 |

**Cited post-pin text [E].** This proposal cites T3 r1.8 text that postdates the pins (§1b R21 entry; §4 VP11 side note; §6 item 1) **as context, never as trace**, each marked "post-pin T3 [E] context". T3 §6 item 7 is cited as T3 [E], not adopted (pre-pin §6 text) (r1.1, W8). (r1: the §1a R8 relations cell is no longer cited, M1.) Whether that is allowed is O3 Q-R (iii); F-LOG-0282 does not rule on it. Every such citation is marked.

---

## 10. Open questions and limitations

**Standing qualifier (verbatim):** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

### 10.1 Carried open, not resolved here

| item | content (source) | bearing on this proposal |
|---|---|---|
| A1 | VP4 / VP8 edges; ARB §C row 7 vs register §3 "may stay open" (O3 r1.2 §6) | E3-PP, E3-AG1, E3-AG3, E3-EV1: VP4, VP8 stated as unsupported |
| **A2** | trace criterion against the restricted CB1 / CB23 trace; carried open as (c) (F-LOG-0282) | every ⚠E3b output (§7.2) |
| A2′ | the F-LOG-0248 chain cannot be completed for CB-derived outputs | §7.2 |
| A3 | stale T3 r1.8 notes ("not made as of r1.8") | none on content |
| A4 | review regime | fixed by F-LOG-0282 (4) for this output |
| A5 | E2b §A1.9b may be read as requiring (d′) and R11 before any O3/E3 proposal | if upheld, this derivation ran with a prerequisite unmet [E] |
| (d′) | "T3 sync of the CB1 / CB23 cells (M5) and the hosting-keyed R4 clauses (G6), including the missing R4 ids under S" (E2b §A1.11) | the (b) gap of §7.2 |
| C-γ | "whether guard rows that cross groupings (R14–R17, R21; R11 open) are cross-grouping constraints in the sense of the VP11 act" | §3.2, §5: an answer re-derives them |
| R11 | open (E2b §A1.11); appears to cross groupings, not added (T3 r1.8 §6 item 1, post-pin T3 [E] context) | E3-AG2 / AG3 |
| **[r1, M6] evidence gaps** (ARB §E3: "VP3, VP6, VP16, VP9 as evidence gaps") | **VP6:** population of the unobserved coordinates (σ, f, d, Cap, k, t(w), con, Ref, actor identities): unpopulated; new evidence not authorized. **VP9:** multi-target behaviour: R4's finite 𝒯 kept, only one-target behaviour tested (V2 not authorized). VP3 and VP16: as noted at E3-EV1 / EV2 and E3-AG5 / AG6, E3-EV5. Source: E2b §2f defaults (unchanged, E2b §A1.7); T3 §4 VP6 / VP9 rows (T3 [E]) | VP6: every aggregate, value-object and event candidate over unobserved coordinates (R23). VP9: E3-AG2, E3-VO2 |
| **[r1, m8] cosmetic punctuation** | "cosmetic, deferred" (F-LOG-0278): register L3, L73, L174, ARB L3 | none on content |
| CV9 / §3b | "CV9's adoption may already engage the T3 §3b row "epistemic-status / evidential-limitation information in the model", which E2b §2d lists as excluded" | E3-VO9: no mapping |
| H13 | stale register VP11 note | none on content |
| spanning rule / asymmetry | unselected; "documented, unresolved, and unaccepted" (F-LOG-0271) | §8 |
| Q-R (iii) | citation of post-pin T3 text, unruled | §9.3 note |

### 10.2 New open questions raised by this derivation ([O])

1. Whether rule 2a (defined for T2 statements) may be applied to groupings at all (§3.3). The CG1 S and S′ cells are [H]. **[r1, M7]** For S″ there is a further **row-choice question**: through which rows is a grouping read? Reading CG1 through its own carried rows (R10, R22) gives "CB23"; reading it through every row that touches C1 (R5, R6, R8, R10, R19, R22) gives "straddles", via R8 (keeper, CB1). The S″ cell is therefore [E], and neither choice is selected.
2. Any DDD context-map pattern between E3-M1 and E3-M23 [relabelled by Act 1, F-LOG-0295; r1.1: "between E3-BC1 and E3-BC23"] (§2.3). [added by Act 2, F-LOG-0297: "not claimed under the module framing"]
3. Which module, if any, would be responsible for actor identity [relabelled by Act 1, F-LOG-0295; r1.1: "Which context, if any, would own actor identity"] (§2.4). [added by Act 2, F-LOG-0297: "not claimed under the module framing"]
4. Any value-object property for CV1–CV9 and CV-text, and whether CV3, CV7 and CV8 are values at all (§4.1).
5. Any event semantics beyond "dated happening" for CE1–CE6 (§4.2).
6. Whether the implication / meta rows (R12, R13, R23, R24) bear on any aggregate boundary (§3.2).
7. Aggregate identity, root, lifecycle or command: none supplied (§3.1).
8. **[r1, M3]** The supplementary placement rule for model-wide elements ("placed in no module [relabelled by Act 1, F-LOG-0295; r1.1: "no context"]", §2.2) is an analyst rule [E]. The alternative (MW1 straddling; counts 6 / 12 / 4 / 3) is equally consistent with the membership rule.
9. **[r1, M1]** Whether the E2b §A1.3 / §A1.4 per-reading report counts as part of "their §2 rows" under A2 (a) (§7.2: E3-REL and the cut-status columns).

### 10.3 Limitations that travel with this proposal

- **ARB r4.4 §B items 1–8**, with the item 4 note ("**[r4.2] superseded for the policy layer by F-LOG-0254**"; the invariant exclusion stands) and item 8 stale: "7 items are now decided or recorded (VP1d-H, VP15, VP5, VP10, VP14, VP12, VP11), and 13 VPs are open" (E2b §A1.8).
- **Λ1–Λ7** (brief r3 §1): 1 "Cross-family validation was waived, not satisfied." 2 "H-D and H-B are neither falsified nor validated." 3 "Non-identifiability is bounded to the authorized corpus and depends on H5, whose condition was waived. The waiver stays visible." 4 "D1's strong form is reading-relative." 5 "The weak form holds only in the O-form, with the R-j (ii)-O exception. R-a is untested." 6 "The evidence covers only the finite universes U and U_P2, and one corpus." 7 "Every reviewer and every successful independent run is Claude-family."
- **F-LOG-0256 (human-preserved):** "one corpus and finite test universes; within-family validation, cross-family waived; the shared Claude interpretation untested; H-D and H-B non-identifiable; the theory does not establish aggregate or consistency boundaries, event order or causal relationships as empirical facts". [D] Every module, [relabelled by Act 1, F-LOG-0295; r1.1: "Every context,"] aggregate and event candidate here is a design hypothesis, not an empirical finding.
- **D4, D5, D6** carried (E2b §A1.9).
- **Unratified analyst items** (E2b §6, §A1.10), including the K-c criterion and the per-reading host lists on which every cut-status column here rests.
- **ARB §E3 unresolved risks:** "one-corpus primitives (VP5); low reversibility of VP15; R23 (nothing enforceable from records)".
- **Reopen conditions** (ARB §F, as cited in O3 r1.2 §7): the V6 row reopens "E3's derivation and proposal"; the adequacy-failure row reopens "any E3 / E4 / E5 output tracing to those elements".
- **Authorship:** Claude-family deriver and reviewers (Λ7); round 2 PWL, nothing blocking; r1.1 wording fixes not re-reviewed.

---

## 11. Draft Human ARB act texts on E3b (select one; no default)

Each text is for a **separate** act. None authorizes E4 (R8) or E5.

**Accept** (based on ARB r4.4 §E3b draft; the bracketed A2 clause is **mandatory** because A2 was carried open by F-LOG-0282):
> "I, Human ARB, accept the architecture proposal `R7/E3-ARCHITECTURE-PROPOSAL.md` r[n] produced under §E3 (F-LOG-0282), with its review findings and the §B limitations, as a **bounded architecture proposal, one corpus**, with results under S, S′ and S″, none selected.
>
> A2, select one: [(a) the restricted CB1 / CB23 traces count as "their §2 rows" for E3b, with the S-reading exception for R5, R6, R10, R19, R25 and R38. E3-BC1, E3-BC23, the E3-PP placement and the context columns are accepted on that basis. **E3-REL and the cut-status columns are not accepted under (a)**: they rest on the unratified E2b §A1.3 / §A1.4 report, and whether that report counts as "their §2 rows" stays an open question [O] | (b) the ⚠E3b outputs (E3-BC1, E3-BC23, E3-REL, the E3-PP placement, and every context and cut-status column) are **not** accepted, although the CB1 / CB23 T1 ids are adopted (F-LOG-0275 Act I (1)), until (d′) is made and the whole synced CB1 / CB23 §2 rows are adopted by an E2b amendment, each by its own act; the other elements are accepted].
>
> It selects no spanning rule, accepts no asymmetry, and does not withdraw F-LOG-0265's premise. It decides no variation point and resolves no open item ((d′), C-γ, R11, the CV9 / §3b tension, H13, A1, A5). It makes no candidate constraint an invariant, adds no transaction semantics, and adopts no canonical terms. It amends no adopted or governed record, authorizes no evidence collection and authorizes no implementation. It is decided without reference to any VP1a–d outcome (N1). **It does not authorize E4 (R8) or E5.**"

**Return:**
> "I, Human ARB, return `R7/E3-ARCHITECTURE-PROPOSAL.md` r[n] for [items]. The E3b acceptance is not made. All records stand unchanged. **It does not authorize E4 (R8) or E5.**"

**Reject:**
> "I, Human ARB, do not accept the architecture proposal `R7/E3-ARCHITECTURE-PROPOSAL.md` r[n]. The adopted model (E2b r1.2) and all records stand unchanged. **It does not authorize E4 (R8) or E5.**"

[A] ARB r4.4 §E3b prerequisites: "E3's proposal produced; an independent review of it completed". The second is met under the F-LOG-0282 stop rule: round 2 PWL, nothing blocking; r1.1 wording fixes not re-reviewed (§9.1).

---

## 12. Change rows

| rev | change |
|---|---|
| r0 | initial derivation under F-LOG-0282 (A2 = (c); scope incl. Q4–Q6, no ports; one reading-neutral proposal). Round 1 fresh review: PASS_WITH_LIMITATIONS, no blocker; M1–M7 material, m1–m10 minor |
| r1 | applies M1–M7 and m1–m10 (first table below). Round 2 fresh review: PASS_WITH_LIMITATIONS, nothing blocking, nothing material; minor W1–W9 |
| r1.1 | applies W1–W9 (second table below). Not re-reviewed |
| r1.2 | Act 1 relabel recording (F-LOG-0295; route R-1, tagged in place): `R7/E3b-REASSESSMENT-MODULE-CUT-PROPOSAL.md` r1.2 §I.2a rows 1–19 applied at 124 tagged sites, each tagged "[relabelled by Act 1, F-LOG-0295; r1.1: …]" with the r1.1 text; §I.2a exclusions held (post-pin T3 [E] context; context-map; quotations of sources; §9; §11–§12); §I.1a definition and non-claims recorded as a note under §2.1; title and a new status line (r1.1 status line kept as history). No assertion added, no status word changed; ⚠E3b outputs stay not accepted. No Part II / II.6 (N1–N12) item applied. Not re-reviewed |
| r1.3 | Act 2 recording (F-LOG-0297; route R-1, tagged in place): `R7/E3b-REASSESSMENT-MODULE-CUT-PROPOSAL.md` r1.2 §II.6 N1–N12 applied. N1, N2 replaced (tagged "[superseded by Act 2, F-LOG-0297; r1.2: …]"); N3 tagged; N4–N6 appended; N7 (six §7.2 reading-(b) cells), N8 (§7.2 closing [D]) and N9 (§9.1 row 1) tagged as superseded by Act 2; N10: 78 ⚠E3b flags removed, each tagged "[flag removed by Act 2, F-LOG-0297; r1.2: "⚠E3b"]" (all outputs accepted; references to the flag that are not flags on an output are kept: §1 legend, §2.1 Act 1 note, §7.2 A2′ text, §9.1 row 1, §10.1 A2 row, §11 draft acts, §12 rows); N11: the 14 / 9 and 3 / 3 annotations under the other D12 reading (§2.2 shared and crossing rows, §2.3, §8.2 placement and E3-REL rows); N12: the §II.3 structural description [E] and the kept neutral names under §2.1. (R-none) (a) recorded in §13 and in the §9.1 row 1 tag. Title and a new status line (r1.2 status line kept as history); §13 appended. Not re-reviewed |

**r0 → r1** (round 1: PASS_WITH_LIMITATIONS; the fixes are not yet re-reviewed)

| finding | fix in r1 | where |
|---|---|---|
| M1 | E3-REL rebuilt from the E2b §A1.3 crossing report (recorded, not ratified) only: the set of rows "hosted on both CB1 and CB23" per reading (S 13; S′ / S″ 8). The T3 relations table (not adopted, B1) is no longer used, and neither is the r1.8 R8 relations cell. Whether E3-REL is a "relation" is [O]. E3-REL and the cut-status columns are carved out of "met under (a)" as [O] in the §7.2 row and closing [D], §9.1 row 1 and §9.2 V1. Accept (a) excludes them explicitly | §2.3; §7.2; §8.2; §9.1; §9.2; §9.3 B1 and post-pin note; §10.2 #9; §11 Accept (a) |
| M2 | One position on reading (b): the CB1 / CB23 T1 ids are adopted by Act I (1) whatever A2 says, but adoption is not the criterion; under (b) every context column stays unmet until (d′) and the E2b amendment. "Adopted under (a)" removed. Applied in the §7.2 row, the closing [D] and Accept (b) | §7.2; §11 Accept (b) |
| M3 | Explicit [E] supplementary rule: model-wide elements are placed in no context; MW1 re-tagged [E]; the alternative (straddling; 6 / 12 / 4 / 3) disclosed | §2.2; §4.3; §7.1; §10.2 #8 |
| M4 | §2.2 T-A table cell: "MW1 (no context) / CV4 (inside E3-BC1; R24 crosses)"; pointer fixed to §2.2 | §2.2 |
| M5 | Unadopted T3 / T2 text re-tagged "[A] that T3 / T2 states X (T3 [E] / T2 candidate, not adopted)"; filters tagged [D]; post-pin T3 r1.8 text (R21 groupings, R11, §4 straddle, §6 items) marked "post-pin T3 [E] context". New legend paragraph | §1; §2.2; §2.3; §3.1; §3.2 (intro, class column, R21 / R11 cells, notes); §3.3; §4 intro; §4.2; §4.3; §5; §8.2 |
| M6 | VP6 and VP9 carried as evidence gaps in a new §10.1 row; §9.1 pointer corrected | §10.1; §9.1 |
| M7 | CG1 S″ cell re-tagged [E]; "mechanical" dropped; the alternative S″ result ("straddles", via R8 keeper on CB1, when read through every C1-touching row) shown; the row-choice question added to §10.2 #1 | §3.3; §8.2; §10.2 #1 |
| m1 | E2b quoted exactly: "Also not adopted as constraints: …" | §2.3 |
| m2 | R11 listed only in the open column for CG2, not as "inside" | §3.2 |
| m3 | R15 → CG6: cites the T3 r1.5 §2 / §1b CG6 rows and T2 R15's "rests on" column; method exception stated | §3.2 note |
| m4 | ⚠E3b added to the no-context claims for E3-VO8, E3-VO9, E3-CS1 and E3-MW1, including §7.1 | §2.2; §4.1; §4.3; §7.1 |
| m5 | Disclosed that "straddling" for the participant and CG1 coincides with the S count; CG1's straddle re-tagged [D] (C1 spans [A]; C2 ∈ CB23 [A]); E2b's "may straddle" not upgraded | §2.2; §3.2; §8.1 |
| m6 | No shared-kernel (or other) pattern proposed for E3-PP; B14 / VP4-by-implication caveat attached to the α / α_W / k readings | §2.4 |
| m7 | E3-REL aligned: §8.2 now lists only the shared rows, with the "relation" question [O] applying to all rows; C24 / recording order removed from E3-REL (consistent with no AG5–AG6 relation); the checkability [D] restated row by row (R21 observed c, interpretation-only; R22 record ids observed) | §2.3; §8.2 |
| m8 | Cosmetic punctuation item added | §10.1 |
| m9 | §2.2 host-column-as-membership bridge re-tagged [D], conditional on the [H] E3-BC1 / E3-BC23 | §2.2 |
| m10 | Line break in the F-LOG-0282 clause (1) quote marked "[line break in the source]" | header |
| (consequential) | CV3 cell corrected: R3 is a constraint under constitution, not a guard row. Status line, §9.1 review row, V11 row and authorship line updated for round 1 | §4.1; header; §9; §10.3 |

**r1 → r1.1** (round 2: PASS_WITH_LIMITATIONS, nothing blocking, nothing material; the W-fixes are not re-reviewed)

| finding | fix in r1.1 | where |
|---|---|---|
| W1 | §10.2 items put in numeric order (1–9), so the pointers #1, #8 and #9 resolve | §10.2 |
| W2 | "under S′ the count differs; under S″ it depends on the row choice (§3.3, §10.2 #1)" | §2.2; §8.1 |
| W3 | O3 citations re-tagged "[A] that O3 r1.2 … states (O3 proposal [E], not adopted)" (§3.3 Q-S4; §7.2 A2′). Rule 2a citations re-tagged "[A] that T3 r1.7 rule 2a states (T3 [E], not adopted; E2b §A1.10)" (§8.1 Order; §8.2 participant class cell) | §3.3; §7.2; §8.1; §8.2 |
| W4 | CV3 [O] cell: "whether a set of two guard rows and one conditional constraint can be a value object" | §4.1 |
| W5 | §2.3 checkability [D]: "no row of E3-REL is fully checkable from records; R22 is checkable only in its record-id part" | §2.3 |
| W6 | Accept text adds "and authorizes no implementation" | §11 |
| W7 | CV9 / §3b quote sourced: E3-PREREQ r4.1 §6 item 1, as quoted in package r1.1 §4 | §4.1 |
| W8 | T3 §6 item 7 re-labelled "T3 [E], not adopted (pre-pin §6 text)"; the post-pin note lists §6 item 1 only | §4.3; §9.3 |
| W9 | "the adopted E2b §A1.3 crossing report" → "the E2b §A1.3 crossing report (recorded, not ratified)" | §2.3; §12 M1 row |
| (consequential) | Title and status line → r1.1; header, §9.1 review row, V11, §10.3 authorship line and the §11 E3b-prerequisite note record "round 2 PWL, nothing blocking; r1.1 wording fixes not re-reviewed" | header; §9; §10.3; §11 |

**Standing qualifier (verbatim):** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

---

## 13. E3b re-assessment (Act 2, F-LOG-0297)

**[added by Act 2, F-LOG-0297]** Appended by the Act 2 recording (`R7/E3b-REASSESSMENT-MODULE-CUT-PROPOSAL.md` r1.2 §II.6; route R-1). The changed sites in §1–§12 are tagged in place; no text is deleted.

**The act** (verbatim, `F-GOVERNANCE-LOG.md` F-LOG-0297; Human ARB, 2026-10-13):
> "I, Human ARB, having approved the relabel (Act 1, F-LOG-0295), rule and accept as follows, on the basis of `R7/E3b-REASSESSMENT-MODULE-CUT-PROPOSAL.md` r1.2 Part II.
>
> **(R-none):** (a) selected.
> for E3b, the explicit R4 'none' for R25 and R38 under S (F-LOG-0287) is ruled sufficient, **as a human ruling, not a finding**;
>
> **Accepted ⚠E3b outputs** of `R7/E3-ARCHITECTURE-PROPOSAL.md` r1.2, as a **bounded architecture proposal, one corpus**, with results under S, S′ and S″, none selected, and with the limitations of Part II §II.4.
>
> **Dependencies:** E3-M1 and E3-M23 form one box. Each later box requires it. The cut-status box may stay unticked on its own.
> - [x] **E3-M1 and E3-M23 (together).** A candidate module cut over one model, as defined in Part I §I.1a; not bounded contexts.
>   - The structural description of §II.3 is recorded as [E]: a low-crossing cut on T-A + T-B, including 18 inactive T-B slots, and on raw crossings only; E3-M23 cohesive far above chance; E3-M1's cohesion not distinguishable from chance.
> - **Names (N-a):** keep 'records module' / 'norm-and-work module'.
> - [x] **E3-REL, cross-module rows**, together with the identical shared-row cut-status cells (E3 line 72; line 330, the "shared" cells (13 / 8) only; line 332): 13 / 8 per the ratified §A1.3 list; 14 / 9 under the other D12 reading; D12 not resolved; relation status [O].
> - [x] the E3-PP placement, 'spans both modules'.
> - [x] the module columns.
> - [x] the cut-status columns (all other cut-status cells).
>
> All five boxes are ticked; no output stays pending.
>
> **Recording:** the Act 2 recording in E3 is made as set out in §II.6, followed by a fresh review of the recording.
>
> This completes E3b for the selected outputs. It does not:
> - select a reading or spanning rule, or resolve D12;
> - accept the asymmetry, or withdraw F-LOG-0265's premise;
> - decide any variation point;
> - resolve any open item ((g), (h), C-γ, R11, the CV9 / §3b tension, H13, A1, A5) other than by the (R-none) ruling for E3b;
> - ratify any method (K-c and rules 1–5 and 2a stay unratified [E]);
> - make any candidate constraint an invariant, or add transaction semantics;
> - adopt any canonical term;
> - amend any adopted record other than E3 as recorded;
> - authorize any evidence collection or implementation.
>
> It is decided without reference to any VP1a–d outcome (N1). The standing qualifier travels with it, verbatim: 'one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable'. **It does not authorize E4 (R8) or E5.**"

**Rulings and selections recorded here:**
- **(R-none) (a):** for E3b, the explicit R4 "none" for R25 and R38 under S (F-LOG-0287) is ruled sufficient. **This is a human ruling, not a finding.**
- **(α):** E3-REL and the cut-status columns are accepted with limitations, relying on the reopen condition of E2b §A2.7 (tied to K-c, rule 2a and the encoding).
- **Names (N-a):** the neutral names 'records module' and 'norm-and-work module' are kept (provisional names, not canonical).
- **Accepted:** every ⚠E3b output: E3-M1 and E3-M23 (together), E3-REL (cross-module rows) with the shared-row cut-status cells, the E3-PP placement, the module columns and the cut-status columns. No output stays pending. **E3b is complete.**

**Disclosed with the act (F-LOG-0297):** audit C9 recommended running Q6 tests 1 and 2 before any further amendment that cites host lists, and those tests are still open; D12 (R24) is not resolved; the R4 adequacy and act-identifiability claims stay the weakest load-bearing claims.

**Editorial note (recorder, not part of the act):** the line numbers in the quoted act (line 72; line 330; line 332) use r1.1 numbering. In r1.3 they are lines 99, 357 and 359. The per-cell clause for r1.1 line 330 had no flagged cells to act on; the table-level flag was on the §8.2 heading.

**Not authorized:** E4 (R8) and E5.

**Standing qualifier (verbatim):** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"
