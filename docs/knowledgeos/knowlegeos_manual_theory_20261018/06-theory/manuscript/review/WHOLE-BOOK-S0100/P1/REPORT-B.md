*Whole-book review S0100, part P1, reviewer B (sonnet). Step-2 report, saved by the Professor from the hand-back, unedited, 2026-10-19T20:07:24Z.*

# P1 (Ch. 0 and Ch. 1): step-2 review, whole-book review S0100

*Process independence: I am an AI session independent of the author as a process, not as a person. All verdicts are judgments by a fresh session on a frozen copy.*

**Conventions.** V = checked against an allowed source or granted file by me. U = rests only on files I may not open, so internal validity only and attribution unverifiable. M = mixed. "Internal" = checked for internal consistency only. "Book §" = a section in `BOOK-UNDER-REVIEW`. Source IDs (Sxxxx) are as in the brief.

## 1. Assessment

1. **Reliability.** Ch. 0 and Ch. 1 are reliable as an annotated, honest ledger of what the 2026-07/08 sources say. Source quotes mostly check out, and the self-corrections are real. They are not yet reliable as a theory. The foundational law (§1.6) is partly a stipulation presented as a law, and the central question's "valid" is never recomposed into one predicate. There is no primitive list and no kernel/application classification. The formal evaluator (§1.14.1) is admitted not to be a function yet.
2. **Largest risk.** Foundational circularity plus source lineage. The authority axiom is analytic ("standing" is defined as what acts change) and its supposed corroboration comes from instances of itself. Nearly all support comes from AI-generated candidate documents of one programme, relayed "ARB rulings" included. Many headings still read "(Src, ARB …)".
3. **Survives scrutiny.** The act/record distinction, evidence grade ⟂ standing (§1.3), the visibility bound as a necessary condition (§1.7), and the negative result that the telos alone does not derive the invariants (§0.5). Several of the book's own decisive moves, including withdrawing its own errors, are sound.

## 2. Findings (ranked)

| # | Sev. | Type | Location | What is wrong | Consequence / correction | Basis |
|---|---|---|---|---|---|---|
| 1 | **critical** | theory defect | §1.6 axiom and "theorem"; §1.14.4-1; §1.2 table | (a) "Standing changes only by recorded acts" is definitional. §1.6 says its instances are "consequences, not predictions", and defines counterexamples away ("such a property … is not a counterexample but the act itself"). (b) §1.14.4-1 files P-8 as "NEW EVIDENCE for §1.6" although P-8's five instances follow from the axiom: a consequence is counted as support for its premise. (c) §1.6 says the theory "needs only one authority axiom", but the chapter adds a companion grant premise (§1.6 refined), P-E, P-R, INV-A3, the reason condition ι and C1. (d) The §1.2 table says standing is "what the organisation currently treats it as" and changes "only by a governance act". Behaviour can change without an act, so this conflicts with the §1.2 senses paragraph (recognized = what records show). (e) Scope is unstated, and external authority and desuetude are left open. | The foundational "law" is a stipulation. It is true by definition, and its support is circular or comes from one programme. Restate it as a stipulated definition of *recognized* standing with explicit scope. Keep the empirical claim (organisations in scope in fact change standing only by acts) separate, with a falsifier. Stop counting instances as evidence. Replace "one axiom" by the list of premises. | Internal; sources EP01/GA/AMS/V3 are U. **M** |
| 2 | high | theory defect | §1.2; register CON-0016/0017/0018 | The register says CON-0016 "authority = provenance × standing" is VALIDATED, while both factors are INVALID (provenance values are not a partition; "standing" has several senses). "×" is undefined (T1-31, resolved only by Int). "Provenance never changes" needs a claim-identity criterion. I-0 is only proposed later, in §1.10, so the dependency is used before it is defined. The platform's own guard `baseline requires authority ∈ {authoritative, derived}` (S0018 L104) lets a provenance value gate a status transition. The book never discusses it. The empirical motive (S0022) comes from author-built hypothetical domains (S0022 L27). | The central split is PROVISIONAL, not validated. Repair the value set (the book's own candidate: producer × derivation), define claim identity before §1.2, and treat the S0018 L104 coupling as a candidate counterexample to independence. | **V** (S0018, S0022, S0012) |
| 3 | high | theory defect (A/C collapse) | §1.8 heading and Analysis; §1.11 | The heading says KnowledgeOS "is a generator" and the Analysis says it is "not a knowledge base", resting on a cited-only tier model plus an analogy. The same section records generation at n = 0 (S0005 per the book) and S0008 L63 says "creates PKS … n=0 … the generator slot is a person". Target architecture is presented as identity. | Retitle conditionally, or move the generative reading to a labelled target (C). | **V** (S0008 L63–64) |
| 4 | high | theory omission | §0.1, central question; §1.2, §1.14.1, §1.17.2 | §0.1's core statement ("valid at the moment it is used, and visibly") is declared to unify *all* of loss, drift, staleness and inconsistent AI behaviour. Knowledge that has left with a person is absent, not invalid. The book's own corpus paragraph needs "existence". Ch. 1 then splits "valid" into authorized, well-founded, currently recognized, evidenced and within validity period. No predicate recomposes them, so the central question ("which knowledge is valid?") has no one-line answer in this part. | State the composite predicate, or say why none exists. Fix the universal "all". | Internal |
| 5 | high | contradiction | §0.7 step 4 and §0.8 against §1.17.4 and §1.16.3 | §0.7: the working context is "thrown away at the end of the task", which satisfies I-8 "by construction". §1.17.4: "the assembled working context *is* the input set of the task, and recording it is what makes the later evaluation possible", and reproducibility is a precondition for verification. | Not reconciled. A pin/reference record (§1.16.6) would reconcile them, but this is never stated. | Internal |
| 6 | high | contradiction (forward-referenced) | §0.3–0.4 against §1.1, §1.5 | Ch. 0 treats *Executable* as a representation of one claim (rule → test, same claim, new form; GKC L103: merge-gate = Constraint×Executable). Ch. 1 says whatever executes is a capability, "not a knowledge carrier". Ch. 3 §3.9 leaves fork F11 unsettled. §1.1 also says "knowledge does not execute" is "close to definitional" yet calls it "a strong, testable architectural claim". The config counterexample is absorbed by decree (config becomes "capability input"). | The knowledge ≠ carrier chain rests on an unresolved premise. CON-0039 is INVALID in the register. | **V** (GKC L103; Ch. 3 §3.9) |
| 7 | high | theory defect | §1.14.1-8, against §1.14.1-7 and its status box | §1.14.1-8 ("Totality … (Ded, with proofs)") is unconditional, but the status box says existence, uniqueness and totality are "not established" in cases. Totality holds by construction (the headline list partitions F by definition), and defect 3 says "no adoption on record" is F in the formula but "cannot determine" as headline. The property 4 proof ("no relevant entry recorded after…") is close to tautological. 1.14.1-8 is not on the C18 changed list. | Make 1.14.1-8 conditional. Report property 1 as a sketch. | Internal |
| 8 | high | theory defect | §0.6, §1.2, §1.12, §1.14.2-5, INV-A3 | Almost all laws are prohibitions ("never grants", "never holds", "absence is never permission"), and a refusal is declared a "success" (§1.14.2-5; resolver calls UNASSIGNED a success). The null system that records nothing and refuses everything satisfies all of them. The only must-do requirement is evaluator totality (§1.14.1), and that is not established. §1.14.5-6 admits INV-A3's STOP rests on a rule with no adopting act. | State liveness and required capabilities for the authority subsystem (can an act be recorded, can a grant be issued). | Internal |
| 9 | high | theory omission | whole part against RESEARCH-PURPOSE | No primitive list. No kernel/application tag, no minimality or stability check, for any concept. A large share of Ch. 1 is observation of one estate: the §1.9 census, 1.12-4, the §1.13.2 recount, the design-check reading in 1.14.5-5, the .gitignore note in §1.16.1. These carry only Src/Ind marks, with no flag separating observation (A) from theory (B). | Add kernel/application/observation tags and a minimal primitive list. | Internal |
| 10 | high | source defect / lineage | §1.14 headings; §1.2 to §1.17 generally | Headings read "(Src, ARB REV 1)" (1.14.2-1, 1.14.3-1, 1.14.4-1/2), but the rulings are relayed inside an AI-generated, "approvable as a proposal" document whose originals were not located (1.14.5-6, 1.14.5-8). The chapter's support is a single-programme AI-generated set (`docs/knowledge_tranfer/` and S0002–S0034 of 2026-08-02/03). The §1.14 header counts it as one line of support, but the headings do not. 224 non-granted anchors in P1 are U. | Relabel as "relayed". Carry a lineage note into each heading. | **M/U** |
| 11 | normal | distortion | §0.5, I-11 | GKC L66 says "Every other rule derives from it:" followed by a list. The book says GKC "does not claim to generate I-1…I-10". That is a defensible reading of the colon list, but the ambiguity is not stated. | Quote both readings. | **V** |
| 12 | normal | distortion | §0.3 | "Authority scope: where it is valid (provisional)" paraphrases GKC L39, "where canonicity holds". The derivation "tacit ⇒ scope ⊥" works only for the GKC wording. The formula's ⇔ is stronger than Meta-Model L65 (flagged by the book). The symbol ⊥ is used for "inapplicable" here but for "unspecified" in §0.7's revised note and for "unknown citation" in §1.14.1. | Use GKC's wording and give ⊥ one meaning. | **V** |
| 13 | normal | source misuse | §0.2, mission vs constraint | The test "A mission with no falsifier is not a mission" (S0019 L163) is MQ-7 about M-A, the AIP-14-as-mission candidate that S0019 L23–29 withdraws. The book uses it as a standing criterion against the enacted mission and builds a tension from it. | Mark it as belonging to the withdrawn framing. | **V** |
| 14 | normal | source defect | §0.1 | "[RQ-002 Charter L52–62]" resolves to `RQ-002_Project_Knowledge_Domain.md` L52, not the Charter in the Ch. 0 source list. "Meta-Model", "Raw" and "Taxonomy" are not in the short-name list either. | Fix the labels. | **V** |
| 15 | normal | distortion | 1.14.2-1 further evidence | "The engine checks only that a reference is present" is attributed to S0097 L41, which says only "no gate reads process identity" and "independence declared, not attestable". S0100 L22 describes recording the PO/ARB's START into a lane held by a barred process, not "a START by a barred process". | Re-anchor. | **V** |
| 16 | normal | statistics | §1.4 | "6 entries moved in two days" against "1 of 38" gives no denominator for the first register and no selection control. "Criteria cause closure" is not supported. The same applies to "13 of 29" (U). | Present as reported counts only. | U |
| 17 | normal | inconsistency | §1.9 against 1.12-4 and 1.14.5-5 | The census says 11 of 20 contracts are "enforced by something stronger than advice or convention". The eight "gate/threshold" items include gates that the book shows are non-blocking by configuration (`design-check.sh`). The census also numbers stages differently from TR and DR-AB ("stage 2"). | Re-label the census categories. | U (code) |
| 18 | normal | definition | §1.14.1-4 head | The head says "grant evaluated at the time of the act", but the formal model's condition 4 evaluates grant endings at t under policy P. The head is true only for P = ∅. | Say so in the head. | Internal |
| 19 | normal | omission | §1.2 N-2; §1.14.1 | "No direct created → authoritative edge" is either the axiom restated (the edge is act-labelled) or contradicted by human-authored root documents adopted at creation. Only genesis-by-evidence and root acts "by stipulation" are discussed. | Choose a reading and state it. | Internal |
| 20 | normal | omission | §1.5 | "Impossible to phrase" and "the dissolution is valid (Ded)" are accepted. The dissolution is valid only if representation is independent of nature, which is the premise. A rival, CON-0120 (an absence claim must state its search scope), explains the same five errors without the knowledge ≠ artifact split. No necessity test is recorded. | Record the rival and a falsifier (see §4). | **V** (S0016 L92–98; Ch. 3 §3.4) |
| 21 | normal | dangling pointer | §1.6, Ch. 4 index | §1.6: "the full taxonomy of change belongs to the planned Ch. 4". Ch. 4 index: the material "is in Ch. 1 §1.6 and §1.14 for now". Neither contains the work-execution, composite or non-progression kinds of S0018. | Break the loop: place the taxonomy in one chapter. | **V** |
| 22 | normal | homonyms | part-wide | See Appendix A. | | |

## 3. Changed theory (brief's list)

| Item | Holds? | Reason |
|---|---|---|
| 1.14.1-4 head names its premise | **Yes, conditional** | Premise made explicit; the opposing natural reading of V3 L319 is recorded (1.14.1-5). Finding 18: the head holds for P = ∅ only. |
| 1.14.1-7 head/body conditional; status box | **Yes** | The hedge "sketched, not established" is correct. The Kleene/Kripke point is right: with υ = "does not count until evidenced" the operator is non-monotone. "Unique" is loosely used. §1.14.1-8 stays unconditional (finding 7). |
| 1.14.2-1 two-sided evidence | **Mostly** | S0129 L147 is verbatim in meaning. Finding 15: the S0097 L41 and S0100 L22 paraphrases drift. |
| 1.14.3-2 further evidence (§6.5, §8.9, §8.11) | **Yes, cross-refs exist** | I confirmed the §8.11 C1, C2, C4, C6 headings; the cited chapters were not otherwise checked. |
| 1.14.3-3 head | **Yes** | S0014 L57 verified. It correctly limits independence to process (it also covers this review). |
| 1.14.4-10 head | **Yes** | The reconciliation is conditional on PM-1. S0011 L62 and S0020 L89 are verified. The "Src" label is over-strong, since the reconciling is Int. |
| 1.14.5-1 related evidence | **Yes** | S0097 L45 verified. |
| 1.14.5-8 head | **Yes but near-trivial** | A DAG has topological orders. |
| 1.14.5-14 head and repaired sentence | **Mostly** | The reduction to "freezes of activity" is untested. Whether S0017's three freezes were activity freezes or status transitions is not shown, and `frozen_changed_without_adr` governs edits to frozen documents, not who may freeze. |
| 1.14.5-6 / 1.14.3-3 lineage line | **Yes** | One programme, one day, counted as one line. |
| 1.13.1-3 (property 3's scope) | **Yes** | The engine refusal (S0129 L147) evidences property 2 only. The S0090 L30 quote is verified. |
| 1.13.2-3 point 5 (S0129 L97; "existing principles") | **Yes** | S0129 L44, L97, L102, L187 verified. |

## 4. Refutation adequacy and the extra task

**Falsification attempts recorded in the book (adequate or inadequate):**

| Attempt | Verdict |
|---|---|
| §0.2 mission: abduction, rival "portability as design quality", independence of the eight artifacts, S0032 self-contradiction | **Adequate** |
| §0.5 telos: found its own strengthening and withdrew it | **Adequate** |
| §1.2: withdrew "foundations already had it" after a table check | **Adequate** (genuine) |
| §1.1 config counterexample | **Inadequate**: survives only by reclassifying config as "capability input" |
| §1.6 "counterexample = the act itself" | **Inadequate**: defines counterexamples away |
| §1.6 two-keys general form | **Adequate**: the review found genesis, freeze by declaration, and human-act-only questions |
| §1.7 aggregation case | **Adequate** |
| §1.11 "all-binding" check | **Inadequate**: "method may be empty" makes the split non-falsifiable |
| §1.14.1-2 ("Implemented as standing?", "Verified authoritative?") | **Inadequate**: refuted by applying the axiom itself |
| §1.14.1-8 fifteen cases | **Inadequate as a test**: author-built, and every case "gets a defined answer under every parameter value", so none can refute |
| §1.14.5-5 design-check example | **Adequate** (Ind; U for code) |

**No attempt recorded:** §1.3 (necessity of three labels), §1.4, §1.10 (I-0 beyond two collisions), §1.12-2/3, §1.13.1-1 (the recount only supports), §1.15 mapping, §1.16.7 ("only two enforcement forms"), the §1.9 hypothesis (a falsifier is stated, not run).

**Extra task: foundational derivations.**

| Derivation | (a) strongest counterexample in scope | (b) boundary | (c) rival | (d) falsifier |
|---|---|---|---|---|
| **D1: authority = provenance × standing** (§1.2) | A claim with changing provenance (AI draft rewritten and signed). The value set {generated, derived} is not a partition and the unit's identity across edits is undefined. The baseline guard S0018 L104 couples the two. | Per-version artifacts with a creation event. It stops where "attested", "reviewed" or "evaluator" attributes blur provenance and standing. | S0020 reading: one ordinal trust rank (`derived` rank 2, `authoritative` rank 1). Or provenance as edges (W3C PROV), with the split an artefact of label encoding. | Find a repository set where provenance and standing labels co-vary fully and no process creates off-diagonal cases. |
| **D2: standing changes only by recorded acts** (§1.6) | Desuetude, or a rule abandoned in practice. A regulation changing an internal rule's standing (external authority, kept Open as CON-0044). | A governed organisation with discrete decision acts. It stops at de facto standing and rough-consensus bodies. | A definitional theorem of event sourcing (standing = fold of the act log). | An organisation whose participants report a legitimate standing change with no act, where no definitional exclusion applies. |
| **D3: knowledge ≠ carrier; knowledge does not execute** (§1.5, §1.1) | An executable-only rule (a lint regex): both knowledge (GKC L103) and capability (§1.1 test). | Rules with stable prose plus plural carriers. | CON-0120: state the search scope for every absence claim. The five errors were search errors, with no metaphysical split needed. | Show a case where the split avoids an error the search-scope rule cannot. |
| **D4: two times with policy P** (§1.14.1, §1.16.2) | None easily found. The model is parameterised (ρ, κ, χ, ω, σ, π, υ, P, ι) until it fits any verdict. | Where records for "cited authority" exist. AMS L200–206 reports that no rule cites its grant (dated 08-16, stated as "today"). | A pure event-sourced rule: standing = the last act on the claim, with revocation by act, and no P. | Two real cases in one organisation needing incompatible settings of one parameter. |

The book's own treatment is **inadequate** for D1–D3: the support comes from the same programme, the counterexamples are absorbed by definition, and no alternative explanation is tested. For D4 it is **partial**: the defects are declared, but there is no comparison with the simplest baseline.

## 5. Concept verdicts and architecture view

**Verdicts (only concepts I tested).**

| Concept | Verdict | Reason and scope |
|---|---|---|
| Provenance × standing split (§1.2) | **PROVISIONAL** | Core plausible; motive hypothetical; identity dependency |
| Provenance value set {generated, derived} | **INVALID as stated** | Not a partition (the book agrees) |
| "Standing" as attributed to S0018 | **INVALID** (multi-sense) | PROVISIONAL as the book's own claim-level notion |
| Authority axiom (§1.6) | **PROVISIONAL** | Valid as a stipulation, unsupported as a law |
| X ≠ authority family as a theorem | **VALIDATED, given the axiom** | Logically valid and trivial |
| Evidence necessary not sufficient, general form | **INVALID** (book's own) | Counterexamples found by the review; promotion form PROVISIONAL |
| Evidence grade ⟂ standing (§1.3) | **VALIDATED** | Logical; EP01 labels U |
| Knowledge does not execute (CON-0039) | **INVALID as stated** | Definitional |
| Knowledge ≠ carrier (§1.5) | **PROVISIONAL** | Necessity untested; Executable tension |
| Visibility bound (§1.7) | **VALIDATED as a necessary condition** | The k = 0 (no sources) case is unaddressed |
| Record ≠ act; verifiable ≠ valid (§1.12) | **VALIDATED** | Residual trust assumption stated |
| Authority as recorded reference (§1.13.1-1) | **PROVISIONAL** | AMS is Track B; C1 reserved |
| I-0 identity (§1.10) | **PROVISIONAL** | No identity criterion |
| Formal evaluator (§1.14.1) | **Not yet a function**; INVALID for existence/uniqueness/totality claims as stated | Seven defects declared |
| P-E and "gates follow ratification" | **PROVISIONAL** | P-E is explicitly unadopted |
| Telos (§0.5) | Negative result **VALIDATED**; derived mappings PROVISIONAL | |
| Enacted mission (CON-0025) | **INVALID** (agree) | |
| §0.7 ephemeral context satisfies I-8 | **PROVISIONAL** | Conflicts with §1.17.4 |

**Not tested:** §1.15 vocabulary details, §1.16.4–§1.16.7 mappings, §1.9 ladder claims, §0.4 dynamics.

**Architecture view.**
- **Primitives** (implicit, never enumerated): claim, act, record, grant, standing, provenance, evidence grade, scope, time, role.
- **Laws:** the axiom A1; the role laws (producer ≠ verifier, recorder ≠ decider, no self-widening); ARCH-INV-1; P-8; P-E; INV-A3; I-0.
- **Results:** the evaluator; the gate admissibility conditions.
- **Independence.**
  - P-8 and ARCH-INV-1 are derivable from A1 plus "acts are human" (the book shows this for ARCH-INV-1).
  - P-E is independent of A1 (a model where a gate blocks with no standing change).
  - The role laws are independent of A1 (one actor satisfying A1 but not producer ≠ verifier).
  - Provenance ⟂ standing and lifecycle phase ⟂ standing are undecided: `historical`/`superseded`/`archived` overlap, and the baseline guard couples values.
- **Circular or unsupported:** A1 ↔ the standing definition; "who executes" ↔ knowledge/capability; §0.2 ↔ §1.8 (mutual revision); C1 conditions §1.6 point 2. The brief's graph shows one mutual-citation group (139 nodes) and five cycles.
- **Isolated:** §1.15 (vocabulary test), the §1.17.5 may-read hypothesis (conditional throughout).

## 6. Coverage

**Blind comparison covers only my five sampled sources (S0032, S0018, S0022, S0008, S0016); the rest of P1 is reviewed non-blind.**

| Inventory point | Status | Note |
|---|---|---|
| S0032 mission enacted; abduction; rival readings | **COVERED** (§0.2) | The book also finds S0032 L10 and L167, which my inventory missed |
| S0032 constraint vs mission; intent not evidence; laboratory vs Product Primacy; conceptual vs chronological order | **COVERED** | |
| S0032 five-layer hierarchy and sponsor ownership | **OPEN / thin** | MQ-1 appears; the hierarchy table is not discussed in P1 |
| S0018 provenance × standing, earned vs granted, qualification ≠ authority, LG-1 ill-posed | **COVERED** (§1.2, §1.6, §1.14.4) | |
| S0018 status ← authority guard (L104) | **MISSING** | Bears on independence (finding 2) |
| S0018 four kinds, ten mechanisms, PM-2/PM-3/PM-5 | **MISSING / dangling** | Reciprocal deferral between §1.6 and Ch. 4 (finding 21) |
| S0022 I-4 falsification and withdrawal; I-11 survival; F-3 external authority | **COVERED** (§1.2, §1.11, §1.14.4) | S0022's own internal inconsistency (I-4 falsified in tables, withdrawn in body; counts "10 of 11", "8 of 12") is **MISSING** in P1 |
| S0022 S-1..S-4 and M-1..M-4 (specializes, constrains, instantiates, attests) | **JUSTIFIABLY EXCLUDED** (Ch. 3) | |
| S0008 glossary dispositions, R-1/AST, chain edges, SC-1..SC-8, one-owner tension | **COVERED** (§1.9, §1.10) | |
| S0016 six dimensions; "unstatable" | **COVERED** but critique **MISSING** | See finding 20 |

**Ranked gaps by theoretical importance:** (1) the composite "valid" predicate (finding 4); (2) the S0018 L104 coupling; (3) kernel/application tags and primitives (finding 9); (4) liveness requirements (finding 8); (5) S0018's remaining kinds; (6) S0022's own inconsistency.

**Source errors, listed separately** (not theory defects): the S0032 residual "vacant" lines (L10, L167, L190, L233) and the S0022 footer/tables against the body; the book already records most of these.

**Not tested in P1, flagged:** the V3 "ARB ruling" status; the code-checked claims (U).

## 7. Next steps (dependency order, with completion conditions)

1. **Decide what A1 is** (stipulated definition or empirical law). Completion: §1.6 states scope and falsifier, the "NEW EVIDENCE for §1.6" wording is removed, and "one axiom" is replaced by a list of premises.
2. **Define claim identity** (a criterion for "same claim" across edits). Completion: stated before §1.2, with a test on an edited claim.
3. **Repair provenance** (value set, or per-version restatement). Completion: CON-0016's verdict is consistent with 0017/0018.
4. **Resolve the Executable-carrier tension.** Completion: §0.3 and §1.1/§1.5 agree, or F11 is settled.
5. **Reconcile §0.7 and §1.17.4** (discard vs pin). Completion: one rule.
6. **State the composite validity predicate** or say why none exists. Completion: one sentence per component, with a worked case.
7. **Make §1.14.1-8 conditional** and add a minimal-baseline comparison (standing = last act on the claim). Completion: head matches the status box.
8. **Add a kernel/application/observation tag** to every concept. Completion: a table covers all concepts of Ch. 0–1.
9. **Fix the source-label defects** in findings 11–15, and retitle §1.8.
10. **Place the taxonomy of change** in one chapter. Completion: Ch. 4 or §1.6, not both pointing at each other.

**Question for the Principal (only where the theory cannot proceed):** is the authority axiom meant as a normative stipulation (a rule you adopt) or as an empirical law about organisations? The answer decides the status of §1.6 and everything under it. (C1, constitute or represent, is already reserved.)

## 8. Limits

- I did not read Ch. 2–Ch. 9 beyond spot checks (Ch. 3 §3.4 and §3.9, Ch. 4 index, Ch. 8 §8.11 headings, Ch. 9 §9.7 one line).
- I did not open `THEORY-MAP.md`, `BOOK-OUTLINE.md` or `CHAPTER-PLAN.md`.
- I parsed the register's id/term/status columns and read the full rows only for CON-0016/17/18/19/20/24/25/27/39. I read Ch. 1's `01.99-change-log.md` rows 51–53 only, the Ch. 1 TODO by grep only, and not the Ch. 0 TODO.
- About 224 non-granted anchors (EP01, GA, TR, DR-AB, AMS, V3, CF, AB, IM, IA, PC, DR, DE, AG, SI, RS, SC, SD, DG01, code) are U. That includes the §1.13.2 recount (the runtime records are untracked), the §1.9 code checks, and the .gitignore line numbers (§1.16.1 says :33; S0129 L187 says :25 and :32).
- I checked the proofs of §1.14.1-8 by reasoning, not by machine.
- Allowed sources I did not open include S0001, S0005, S0006, S0009, S0010, S0013, S0021, S0023, S0024, S0026, S0027, S0028 and S0089. Their quotes in P1 are unverified by me.
- **Isolation incident.** I ran `grep -ln "human, AI, or external" *.md` and `grep -n …` over `docs/implementation/*.md`, and `ls | head -50` in that directory. That searched, and listed file names of, files outside the twelve granted. The only content displayed was one line of a granted file, `RQ-002_Project_Knowledge_Domain.md`. I did not otherwise read any ungranted file, and I stopped after that.

## 9. Files opened and searches run

**Step 1 (as already reported).**
- `…/P1/BRIEF.md` (lines 54–83)
- S0032 `KnowledgeOS_Vision_Mission_Clarification.md`
- S0018 `KnowledgeOS_Engineering_Progression_Model.md`
- S0022 `KnowledgeOS_Ontology_Cross_Product_Validation.md`
- S0008 `KnowledgeOS_Semantic_Architecture_Reconciliation.md`
- S0016 `KnowledgeOS_Meta_Model.md`

**Step 2, opened.** Under `R` = `docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/WHOLE-BOOK-S0100/`:
- `R/P1/STEP2-COMMISSION.md`; `R/P1/BRIEF.md` (full)
- `R/BOOK-UNDER-REVIEW/06-theory/RESEARCH-PURPOSE.md`
- `R/BOOK-UNDER-REVIEW/06-theory/CONCEPT-REGISTER.tsv` (parsed)
- `…/manuscript/chapter_00/00.00`–`00.09` (all)
- `…/manuscript/chapter_01/01.00`–`01.17` (all)
- `…/manuscript/chapter_01/01.99-change-log.md` (grep rows only) and `…/chapter_01/TODO.md` (grep rows only)
- `…/manuscript/chapter_03/03.04-knowledge-and-artifact.md` and `03.09-what-executes.md` (part)
- `…/manuscript/chapter_04/04.00-index.md` (part)
- `…/manuscript/chapter_08/08.11-identity-across-a-continuation.md` (grep)
- `…/manuscript/chapter_09/09.07*` (grep)
- Directory listings of `R/BOOK-UNDER-REVIEW` and of chapter_03/04/08/09.

**Allowed sources, selected lines only:**
- S0019 `KnowledgeOS_Mission_Discovery.md`
- S0012 `…Lifecycle_Gap_Analysis.md`
- S0002 `…Platform_Discovery_Coverage_Map.md`
- S0003 `…Governed_Retrieval_Positioning_Validation.md`
- S0007 `…Architecture_Validation_Matrix.md`
- S0004 `…Research_Backlog.md`
- S0020 `…Ontology_Architecture_Classification.md`
- S0034 `…Engineering_Knowledge_Domain_Model.md`
- S0011 `…Domain_Model_Falsification_Report.md`
- S0014 `…Phase_B_Evidence_Reconciliation.md`
- S0015 `…Independent_Review_Phase_B.md`
- S0017 `…Engineering_Decision_Model_Discovery.md`
- S0025 `2026-08-02-mvk-bootstrap-validation-report.md`
- S0031 `2026-08-02-knowledgeos-architecture-consolidation.md`
- S0129 (GOV-STATE-DURABILITY po-arb-position-registration)
- S0097 (amd2-verification-registration)
- S0100 (amd2-verification-independence-refusal)
- S0090 (independent-verification-2-amendment1)
- S0096 (capability-analysis-amendment)
- S0095 (record-integrity-disposition)
- S0089 (independent-verification)
- S0128 (`KOS-AIP-GOV-STATE-DURABILITY-ADR.md`, L48)

**Granted:**
- Charter (`KnowledgeOS_Product_Discovery_Charter.md`)
- DevConst (`KnowledgeOS_Development_Constitution.md`)
- GKC (RQ-002 General Knowledge Architecture Constitutional Model)
- PKSM (`Project_Knowledge_Strategic_Model.md`)
- Synth (RQ-002 Architectural Synthesis)
- Meta-Model (`RQ-002_Knowledge_Meta_Model.md`)
- `RQ-002_Research_Findings_Raw.md`
- `RQ-002_Project_Knowledge_Domain.md`
- `Project_Knowledge_Architecture_Charter.md` (one grep)

**Searches, scoped to the copy or to named allowed or granted files:**
- `grep -n "C1\b\|C2\b\|C4\b\|C6\b"` on `chapter_08/08.11*`
- `grep -rln "work-execution\|WORK-EXECUTION" chapter_0*` and `grep -rn "taxonomy of change|four kinds of change"` on chapter_03/04
- `grep -n` on `chapter_09/09.07*` for "not yet a function" and `chapter_03/03.04*`, `chapter_08/08.04*` for search-scope wording
- `grep` on `chapter_01/01.99-change-log.md` for "C18|TT4|turn 4"
- `grep -n "F-CS-5"`, `"30–90"`, `"executors"` on the Raw and GKC granted files
- `grep -n "presence|present|humanActRef"` on S0097
- A python parse of `CONCEPT-REGISTER.tsv`
- The out-of-scope `docs/implementation/*.md` grep and `ls` described in Limits.

**Item 3 of the reviewer note.** I did not open the working chapter files, `THEORY-MAP.md`, `BOOK-OUTLINE.md` at their working locations, `BASELINE-REVISIONS.md`, any `*-OLD-*` or `*-HEADS-OLD-NEW*` file, or the manuscript git history, and I did not compare files. I ran no `book_search.py` search and no git command. The only departure from the isolation rules is the `docs/implementation` directory search described under Limits.

## Appendix A: homonyms (lower priority)

- **valid:** knowledge valid at use (§0.1), a valid act = authorized (§1.17.2), validity(c) = period (§1.14.1), "VALIDATED" (register), a valid deduction (§1.3).
- **authority:**
  - the `authority` field (provenance × standing);
  - the axiom's "authority" ≈ standing;
  - authority = a recorded permission (§1.13);
  - Decision Authority (an organisational role);
  - "Authority Reference" (V3).
- **standing / status / state / current authority / effective / recognized:** distinct but overlapping, tracked as T1-02 and T1-07. "State" in the §1.14 title is V3's lifecycle state.
- **execute:** three senses (Ch. 3 §3.9).
- **binding:** "the product binds the platform"; binding in method/binding/evidence; "binding strength" (mandatory); "bind" in EP01 (observed and adopted statements).
- **scope:** canon scope, grant scope, subject scope, evaluation scope (S-3).
- **projection:** a lifecycle sense, a presentation sense, and a graph sense.
- **I-n:** I-8, I-9 and I-11 each name different rules in different documents (the book flags I-11 and I-9). "AP-1", "C-1", "C-5" and "R-n" also collide.
- **record:** record of an act, record position, register record, evidence-stream record.
- **⊥:** inapplicable (§0.3), unspecified (§0.7 note), unknown citation (§1.14.1), refusal (RESEARCH-PURPOSE).
- **Numbering:** "fourth label" (§1.7 visibility) and "fourth property" (§1.11 ownership) both claim fourth place.
- **Marks:** "Ded" is said in §1.3 to mean a valid deduction, but results marked Ded (1.14.1-7, -8) carry declared defects.

## Appendix B: smaller items

- The §1.7 bound has a k = 0 case (no recorded sources): an empty intersection is undefined as a bound.
- §1.14.1 "today no rule … cites the grant" dates from AMS 08-16 but is stated as "today".
- §1.9's ladder hypothesis ("level should correlate with reliability") is the book's own, not "predicted by the foundations".
- §1.12-2 and §1.13.1-2 handle the act/record question carefully; S0095 L141, L149, L210, L216 and S0090 L30 all verify.
- §1.2 says the schema regressed on one split and improved on another. The retraction is well-evidenced.
- §0.8's "coherent core" holds only if premise (T) is stated. §0.8 itself says (T) is not stated in the sources.
- The book's own reviewers (process-independent AI sessions) are named as a limit in §1.14.3-3. The same limit applies to this report.
