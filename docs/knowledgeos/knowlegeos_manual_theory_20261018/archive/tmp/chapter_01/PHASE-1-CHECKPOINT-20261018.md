# Phase 1 checkpoint: evidence for the authority-lifecycle model (2026-10-18)

**Corpus evidence** is from S0001–S0104. **[Repo]** marks supplementary repository evidence, which lies outside the corpus. The [Repo] items were found under the repository-wide definition-search authorization.

## 1. Questions investigated and evidence found

| # | Question | Result |
|---|---|---|
| Q1 | **T1-19:** is independence a general condition of validity? | **No.** It is a family of scoped rules (§3) |
| Q2 | Do the 22 "currently valid/authoritative" hits contain definitions? | **Yes, 5 material ones** (§2, plus the table below). The remaining 17 hits are incidental uses (product timing, "currently validated model", prompts, session notes) |
| Q3 | Section A-2 of `.claude/IMPLEMENTATION_PROTOCOL.md` | **Read.** It sets out the hierarchy (sense c): Business Authority → Architecture Decisions (ADRs · ARB rulings) → Governance (ES-00x) → Implementation Protocol → Implementation → Operational Evidence. Precedence table: a protocol cannot override an ADR; "business decisions **can invalidate** implementation". Status: protocol **OPERATIONAL, not a governed standard**; A-2 is a DA amendment of 2026-07-30 |
| Q4 | How do conditional acts become operative? | **Through an explicit later act, in both cases found.** (a) S0052: "Q-B1 APPROVED AFTER the F-1 repair", later "record Q-B1 and Q-B6 approved after repair". (b) [Repo] R-72 (2026-08-02): "AUTHORIZED … SUBJECT TO A PROVISO THAT IS NOT YET SATISFIED … IMPLEMENTATION DOES NOT BEGIN"; later R-86 (2026-08-04): "engineering is AUTHORIZED to execute". **No case found of a condition taking effect automatically from facts** |
| Q5 | Validity of an act vs operational effect after recording | Corpus: S0071 I-2 (activation requires a recorded HANDOFF ∧ a recorded human START); S0092 L35 (an act that "did occur" while unrecorded); S0095 L141 (transcription records a "given" act; inventing one is forbidden). [Repo] R-80: "*authorized* read as *executable*" is named as a conflation |
| Q6 | Can authority end, expire, be withdrawn or be replaced? | **Governance side:** no grant revocation has ever been observed (47/47 grants AUTHORIZED, S0078). Grants are **amended** (AMD1–AMD3), lanes are **CANCEL**led (1 case), and owners are "**ruled ineligible**" (S0079 L57). **[Repo] product side:** ADR-001 "Temporal Legitimacy Window" (ACCEPTED) is **implemented** in `app/Contexts/Membership/Domain/Committee/Constitutional/` as `TemporalAuthorityWindow` (validFrom / validUntil; PENDING / ACTIVE / EXPIRED), with "Legitimacy is always point-in-time"; Stress Test 8: "revocation stops future Authority … prior … remains as historical fact" |
| Q7 | Is the "Freeze v2 ARB directive" backed by a governance record? | The earliest record is commit `1cfeb240f` (2026-08-04 18:54), which writes "DISCOVERY FREEZE v2 ADOPTED … (ARB review 2026-08-04)" into CONTEXT.md and the session log. **There is no verbatim ARB text and no rulings-register row.** It is recorded as an adoption, but the act itself is not evidenced independently |
| Q8 | Freeze of an artifact vs freeze of activity | Two distinct senses. Artifact: [Repo] Phase-02 §7 and L239 ("ArtifactFrozen / FreezeAmended — ARB via ADR"). Activity: S0063, Freeze v1/v2, R-27/R-37/R-38. [Repo] session 2026-08-01 L1182: "**Frozen ≠ adopted.** The freeze stops refinement; it does not supply authority" |
| Q9 | External obligations | No governance artifact treats law as a source or limit of authority. The product side has literature-level research only (EPIC-002) |
| Q10 | Permission / Authorization / Commissioning / Execution | [Repo] R-80, 2026-08-03 (definitions as given there): PERMISSION "may something be done?"; AUTHORIZATION "is execution sanctioned?"; COMMISSIONING "has someone been instructed?"; EXECUTION "is the work being performed?". The ruling names three observed conflations: opened → authorized (R-60/R-65); permission → ownership; authorized → executable (R-72) |

**The 5 material definitions among the 22 hits:**

| Source | Status | Wording | Applicability |
|---|---|---|---|
| (a) `docs/knowledge_tranfer/20260816_0841_target_architecture_v3.md` §10.1–10.2 | see §2 | see §2 | see §2 |
| (b) `docs/ARCHITECTURE_DECISION_RECORDS.md` ADR-001 | ACCEPTED; implemented with unit tests. Its dates (2024-Q1 …) **predate the file's creation (2026-05-11)**: claimed, not verified | "**Legitimacy is always point-in-time. Never 'current legitimacy.'** … Historical decisions maintain their legitimacy at decision time"; it separates "never valid" from "expired after the decision" | product domain (membership committees) |
| (c) `docs/knowledgeos/theory-extraction/47-P29-…` §10 | research (Lane T, 2026-09-08), "no canonical theory" | "`Accepted(P)` = CURRENTLY accepted under policy ρ"; historical acceptance is retained; current validity is evaluated under the **current ρ**; replay needs ρ supplied externally | a research analogue |
| (d) `docs/knowledgeos/architecture/20260821-2344-…Epistemic…` L401 | PROPOSED, non-authoritative | "Historical and current validity **conflated by absence**: EKS/AIP authority un-timed (2/218)" | KnowledgeOS. **Independent confirmation of the untimed authority store** |
| (e) `docs/publicdigit/membership/.../20261010_1217…` L575 | brainstorming; reports a "Principal decision" | "Distinguish the appointment relationship from its **authority period**. A role title alone must not establish that authority is currently valid" | product domain |

## 2. New finding: "Approved ≠ Currently authoritative"

**Source:** `docs/knowledge_tranfer/20260816_0841_target_architecture_v3.md` §10.1 (lines 309–319), with OQ-11 (L469). The document is "Architecture Reconciliation v3.1, REV 1, 2026-08-16".

**Exact context:**

> "## 10.1 ⭐ STATE ≠ AUTHORITY — ARB REV 1 … Active ≠ Authorized · **Approved ≠ Currently authoritative** · Exists ≠ Permitted … the ARB adds `Approved ≠ Currently authoritative` … a rule may be `Active` in its lifecycle and **not currently authoritative — because its authority lapsed, was revoked, or was superseded. A single status field cannot say that.**"
>
> "§10.2 The governing invariant: No type's lifecycle may contain a direct edge from creation to an authoritative state. Every authoritative state is entered by a **recorded human act**, never by the passage of a mechanism."

**Status:**
- The **document** is PROPOSED ("APPROVABLE AS A PROPOSAL, NOT YET AS TARGET ARCHITECTURE", ARB 2026-08-16); its authority field is "Generated".
- §10.1 is marked "**ARB REV 1**". The §0.2 ADOPT list contains "**Per-type lifecycle refinement — §10**" and "**ARCH-INV-1 — the mechanism records authority; it never grants it**" (adopted by ARB ruling).
- The "richer Rule model", where the distinction becomes "load-bearing", is **STAGED, not adopted** (§0.3).
- **OQ-11 is open:** "Does Approved ≠ Currently authoritative require a second field, or a governed authority lifecycle?"

**Scope:** "This document architects **T3**", the product knowledge space. It is **not** the KnowledgeOS platform governance model.

**Applicability to TD-001:**
- **Strong conceptual support** for keeping *approval* (an historical act) separate from *current authority* (a time-indexed predicate). This is r1.1's structure.
- It names three ways authority ends: **lapse, revocation, supersession**.
- **Caveats:** the scope is T3, and the model is staged. §10.2's "**recorded** human act" wording **leans to C1-a** for *entering* an authoritative state. It is counter-evidence to a pure C1-b reading and is recorded as such.

## 3. T1-19: independence is a family of scoped rules

| Rule | Scope | Source | Effect |
|---|---|---|---|
| IND-1 | acceptance: "engineering never accepts its own work"; "automation … does not approve itself" | S0067 I-8; S0043 L28 (EEP); [Repo] EP-02 citations | **This is authority, not eligibility.** Engineering holds no acceptance authority at all |
| IND-2 | implementation → verification of **the same work**: "a process that implemented a work item may NOT independently verify that same implementation" | [Repo] Amendment A-1.4 / D-5 (PO/ARB 2026-08-14), in `docs/architecture/governance/KnowledgeOS_Controlled_Session_Orchestration_Proposal.md` L277, L338, L348 | eligibility for the purpose "independent verification" |
| IND-3 | engineering → governance review: "**PERMIT WITH DISCLOSURE** … **Do not introduce a blanket prohibition**" | [Repo] A-4.3 / DEC-2; P-2 / A-5.2 (PO/ARB 2026-08-15) | **a counterexample to a general independence bar:** the overlap is allowed, and only disclosure (D-a…D-d) is mandatory |
| IND-4 | closure: "a lane may not complete its own assignment (G-1)" | S0058 L43 | — |
| IND-5 | grant-specific bars: AMD3's four bars (S0078 §0); the seven criteria (S0104), including lineage and inherited context; "any future exception requires a separate explicit PO/ARB act" | S0078, S0104 | set **per grant** by the PO/ARB. Broader than IND-2 |
| IND-6 | general gate rule: "a gate's verdict may not be issued by the party the gate constrains" | S0102 §8.3 | **PROPOSED, not adopted** |
| IND-7 | enforcement is evidential only: INV-ATTR-1/2; P-5 "never … an authorization or enforcement gate"; independence status is `asserted` vs `attested` | [Repo] decision registration 2026-08-15 | — |

**Counterexamples and exceptions:**
- S0074 L17: a grant that "**bars no one**" lets the refinement author correct its own artifact ("the safeguard is the bounded scope plus the independent review that follows, not exclusion").
- S0065 L20: an acceptance that rests on "the PO/ARB's own authority" **without** independent verification.
- S0058 L49–51: a verification with no lane and no grant. "Does not make its content wrong"; it is to be weighed at acceptance.

**Conclusion:**
- **Validity** of an act (authority) ≠ **eligibility** of the act to support a particular operation (e.g. to count as *independent* verification) ≠ the **evidential status** of eligibility (asserted or attested).
- IND-1 reduces to authority. IND-2/4/5 concern eligibility for a purpose. IND-3 and IND-7 show there is **no general bar**.
- **Reading 1** of T1-19 (the bar is part of the grant's scope) holds for IND-5 only.
- **Reading 2** (a separate eligibility condition) holds for IND-2 and IND-4, which bind regardless of grant text.
- **Both readings are partly right.** The model needs eligibility as a **purpose-relative** predicate kept apart from validity.

**Limitation:** independence rules for kinds of act other than verification, closure and acceptance were not searched exhaustively.

**Side finding:** the identifier **R-34 is overloaded**. In the register it is "ruling-classification discipline". A-1.4 calls it "the actual independence rule". Reports cite it for "engineering does not accept its own work". The register row's own text contains **only** the classification rule.

## 4. Questions that block modelling vs assumptions and policy parameters

**Blocking: none.** Every open question can be represented as an explicit **parameter or axiom**, and its consequences can be computed. The model can therefore be built now.

| Open question | Treatment in the model |
|---|---|
| C1 (do records constitute or evidence authority?) | **Layered**: validity, recognition, operational effect. The models from Phase 3 are compared inside one framework. **Not decided** |
| carry-over of an ending (κ) | parameter `persist` \| `cascade`. Evidence favours `persist` (ADR-001; Stress Test 8; Conflict 4). The choice is policy |
| automatic vs explicit fulfilment of conditions | the model uses an explicit CONFIRM act, as evidenced (n=2). Fact-triggered conditions are an **unevidenced extension**, kept out of the model |
| interval boundary convention | parameter. The repository precedent is the closed interval `[validFrom, validUntil]` (`TemporalAuthorityWindow::stateAt`) |
| closure horizon λ (when may absence of a record be read as "not authorized"?) | parameter. **No source defines it.** It is needed for any "not authorized" verdict drawn from records |
| uniqueness of the current claim per subject | an optional invariant + "later prevails" policy (T2-01). Sources: R-41; S0053 |
| record truthfulness | an explicit axiom. Evidence that it can fail: S0104 I-1; S0060-class errata |
| genesis (root authority) | stipulated root holders (T1-17) |
| naming of "authority" | does not affect the mathematics. Reserved (T1-01) |

## 5. Completing the search

**Remaining checks:**
1. **The original verbatim ARB text for Discovery Freeze v2**, if one exists, e.g. in `docs/knowledge_tranfer/` from 08-04. It matters for T1-06 only and does not block the model.
2. **Independence rules for other kinds of act**, a residual of T1-19. They would only add rows to the eligibility predicate.

**Objective condition for closing Phase 1:**
- every TD-001 component (r1.1 clauses 1–3, recognition, the C1 alternative) has (i) at least one sourced supporting item **or** an explicit "unevidenced" mark, and (ii) its strongest counter-evidence recorded;
- every parameter in §4 has its evidence status stated.

**Both conditions are met as of this checkpoint.** Phase 1 is closed for modelling purposes. Items 1–2 stay as non-blocking residuals, and the search is not being broadened further.
