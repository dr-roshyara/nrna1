*Whole-book review S0100, part P4, reviewer A (opus). Step-2 report, saved by the Professor from the hand-back, unedited, 2026-10-19T20:04:42Z.*

# Review of P4 (Ch. 6 and Ch. 9), whole-book review S0100, step 2

*Process independence: I am an AI session independent of the author as a process, not as a person. I may share the author's model.*

## 1. Assessment

1. **Ch. 6 is reliable as a case collection within its stated scopes.** Its sources are lineage-tagged and its overclaims are mostly withdrawn. **Ch. 9's derivations are honest about their premises.** Its kernel *type*, however, is too weak to state the laws the chapter then tests with it.
2. **The largest risk** is the kernel type of §9.1 (CON-0100). It allows only state invariants. So a mechanism that silently deletes everything, or makes up an act with no input, passes the preservation condition P and can belong to a minimal kernel. §9.6–§9.7 test transition laws (ARCH-INV-1, I-1, H1′, the roles law RL) with this form.
3. **Second risk:** derivations still cite superseded BC-7 claims. "Never stored" (T-1) and "`authorized` never consults the session" were both later found false, and Ch. 6 does not follow that correction.

## 2. Findings, ranked

| # | Sev | Type | Location | Source line | What is wrong or missing | Consequence | Correction | Check |
|---|---|---|---|---|---|---|---|---|
| 1 | **critical** | theory defect | §9.1 C2–C4, CON-0100; used as the test form in §9.6.2, §9.7 | — | The invariants I are a set of valid states 𝒮_I, and P only asks that m(s,x) ∈ 𝒮_I ∪ {⊥}. **Counterexample in §9.1's own toy model:** *purge* maps any valid state to (∅,∅,∅), which satisfies I1 (standing ⊆ acts) and I2 (acts ⊆ claims). So {record, grant, purge} is a kernel. *Forge* gives an act and standing with no input. P cannot express the act's outside origin (A1, ARCH-INV-1), append-only (H1′), "never silently disappears" (I-1), or T-1/T-2. These are relations over (s, x, s′) or over traces, not state sets. | The form "Kernel(M) = P ∧ Req" of §9.6 cannot carry §9.6.5's invariant table. §9.2, §9.3 and §9.6–§9.7 inherit the gap. | Add transition invariants over (s, x, s′) and a condition on where inputs come from (an act-carrying x). Re-check C3. Its proof still holds for per-step relations; trace properties need more. | internal; the toy tool is not in the copy, so I reasoned from §9.1's own description of the model |
| 2 | high | theory defect | §6.5 C2, §9.6.5 (the RL row), §9.6.7 | S0078 L201–204, L216 (D.2); S0073 L83; §1.14.3 roles table | RL says "Governance **records** … handoffs, STARTs, transitions" (§1.14.3, and §9.6.2's own table). The book clears the two `architecture` HANDOFFs ("not a breach") and keeps the STARTs as a breach. But S0078's argument concerns **I-5**, not RL. Its D.2 ("the required actor should record") merges performer with recorder, against recorder ≠ decider (§1.14.3). The observed practice is HANDOFF 36 governance-recorded + 2 architecture-recorded. | RL is applied selectively, and §9.6.5's conclusion that "BC-7 fails RL" is understated. | Either count the HANDOFFs as RL breaches too, or state a scoped RL as a hypothesis that needs an act. | source-verified (S0073, S0078) + internal |
| 3 | high | theory defect | §6.5 C4, CON-0134; §9.7.5 row "P-8 and CON-0134"; §9.8.4 | S0078 L142, L152; S0073 L77, L122–124 | The heading "never stored" and the quoted T-1 ("never … accepted as input") were found **false as an absolute**: there is no key whitelist, so a derived key would be stored. What holds is non-consultation. The 0/117 count is a census of a writer obligation. §8.8's CON-0141 records this but calls it "a lemma of CON-0134"; it is a correction of it. | §9.7.5 and §9.8 rest on a superseded form. | Restate CON-0134 as: a stored derived value has no standing for the fold; "0 stored" is a dated census. Cross-reference CON-0141. | source-verified |
| 4 | high | theory defect | §6.5 C5 | S0071 L132–137; S0072 L32; S0078 L86–100 | It quotes "`authorized` never consults the session" as the source's premise and does not flag F-1. It also omits S0078's point that the read dependency **strengthens** one root and weakens RA-4. §9.6.2 knows F-1. | C5's assessment of the one-vs-two-aggregate argument rests on a falsified premise. | Mark the premise as superseded and add the read-dependency argument. | source-verified |
| 5 | high | theory defect | §9.7.5 condition 4 | — | "it must answer T wherever the defined CE answers T" is one-directional. A **constant-T evaluator** meets conditions 1–4 and is unsound. The constant-U test was run; constant-T was not. | The delegation contract admits an evaluator that grants standing everywhere. | Require agreement on T **and** F (and U), or state soundness and completeness separately. | internal |
| 6 | high | theory defect | §9.6.4 Analysis 2 step 1; §9.6.6a item 3; CON-0137 contract item 4 | — | "Given global act identity" is itself a cross-application uniqueness. It is K4's own example ("no duplicate registration of one act"), unless identity is derived from content (a hash of the committed act). | "CR-a is delegable unless K4" is partly circular. | Make the identity mechanism a premise. Content-derived ids need no serialization point. Assigned ids mean K4 = yes for identity. | internal |
| 7 | high | theory defect (changed theory) | §9.9.7 M-T2, CON-0161 restated | — | M-T2 counts as an independence model only if A1 holds "as a form". §9.8 (M1, result 2) separates A1-as-fact. Under A1-as-fact, an unfaithful record recognizes standing where no act occurred, so A1 fails. | "Occurrence is an axiom beside A1" is overstated. It is the bridge premise of A1-as-fact (the residual assumption of §1.12). | Align with §9.8's two readings: independent of A1-as-form, not of A1-as-fact. | internal |
| 8 | high | theory defect | THEORY-MAP F22 row; map §9 position | — | The F22 row still says "CE is delegable only as a kernel-defined function over a shared record". §9.6.6a item 2 and §9.7.9 withdrew this. The position line reads "last in order S0065; Ch. 6 §6.1–6.5, Ch. 9 §9.1–9.5". | The map states a refuted result as current. | Update the row and the position line. | internal |
| 9 | normal | theory defect | §9.8.4, "When current state suffices" | S1609 L405, L415 | "In every deployment the fold is many-to-one, so … the kernel always needs H1′": the universal is unsupported, and the step does not follow. A bitemporal state factors through; the paragraph above says necessity rests on CON-0134; and the claim needs past queries to be in Req. | A further unsupported "always". | Make it conditional on past queries being in Req and on CON-0134. | internal |
| 10 | normal | source defect | §9.5 C3; §9.6.6 (plane-ward) | S0234 L272, L282; S0073 L122 | "Every invariant BC-7 owns is mechanically enforced." More counterexamples than the book lists: T-1c is a writer obligation, and I-1 lost 23/30 concurrent writes. The book lists only I-4/I-10. | Plane-ward evidence looks stronger than it is. | Add both. | source-verified |
| 11 | normal | theory defect | §6.3 C5, CON-0088 | — | "(B) ⇒ (A)" fails when the adopting project in (B) shares the domain (another election product). That is §6.1 C1's own point about bindings that coincide. | The implication is scoped too widely. | Restrict (B) to projects of another domain. | internal |
| 12 | normal | theory defect | §6.1 C1, remedy 1 | — | A declared interface fixes the *intended* split by stipulation. It does not test the extraction hypothesis (M ≠ M_PD). "It works from one product" is stated as identification. | Overclaim. | Say "declares", not "identifies". | internal |
| 13 | normal | theory defect | §6.6 C1, restricted form; CON-0139 | S0066 L496–509 | "A copy is harmful **only if** used as authoritative" overlooks two cases: a stale non-authoritative copy still misleads the actor (§9.5's failure is about currency, not standing), and copied scripts are second implementations of a rule. | The restricted form is too permissive. | Separate the standing harm (a second home) from the currency harm (stale use). | source-verified |
| 14 | normal | theory defect | §6.6 C4 | S0066 L338–358, L417–433 | "They are not ordered" is drawn from "Code exists ≠ Implementation authorized". Non-identity is not absence of order, and S0066 prescribes a normative order. | Descriptive possibility is merged with normative order. | State both. | source-verified |
| 15 | normal | source defect (lineage) | §9.3 C4, CON-0111 | S2834 L2116–2122, L2134 | S2834 derives "Capabilities ≠ Kernel Operators" from "your kernel-minimality programme". The book then reads it as the purpose's minimality test, so the support echoes its own premise. | It is not independent support. | Keep it VALIDATED as a definitional non-identity only, and note the echo. | source-verified |
| 16 | normal | theory defect | §9.9.3 ("none exists in the series") | S1500 L285 (inside the 025y section, L273–286) | L285 is the gap note of step 025y. S0973, which S1500 does not cover, discusses key compromise (L542–598). | Overgeneralized. | Scope it to 025y. | source-verified |
| 17 | normal | source defect | §6.5 C2, "2 of 5 transition types" | S0067 L65; S0071 L251–260 | The denominator leaves out STOP, CANCEL and FAIL; eight types are declared. | A count without its class. | State the class counted. | source-verified |
| 18 | normal | source defect | §6.5 C2, §9.6.5 (recordedBy on START) | S0073 L155 | "37 of 37" is generalized from 6 cases. §8.8 C2 reports that "6 of 37" itself is not reproducible. | The claim rests on a weak count. | Cite the basis. | source-verified |

## 3. Changed theory (TT4 settlement)

| Item | Holds? | Reason |
|---|---|---|
| §9.9 rewritten (T1, L1–L5, T2) | **mostly** | T1 is honestly definitional. L1, L2 and L4's models are valid. T2 point 2 is valid given §8.6 C1, which is PROVISIONAL. **M-T2 fails** against A1-as-fact (finding 7). One scope error (finding 16). I verified the quotes of S0913 L197, L752, L781, L1399, L1421, S0973 L297, L441, L1424, L1727, L1747–1773, and S1500 L223, L225. |
| §9.9.11 | yes | The withdrawals are correct. |
| §9.8 M5 | yes | Consistent with H4. |
| §9.8 result 6 | yes (internal only) | Not checked against S0129. |
| CON-0160 PROVISIONAL | yes | — |
| CON-0161 INVALID as stated, restated form ~ | yes, with finding 7 | — |
| THEORY-MAP F23 | yes | Its anchors L133, L1398 and L1426 are the lines that open the formulas; the content is at L134, L1399 and L1427–1428. |
| F34 · TT4 block · BOOK-OUTLINE Trust row | yes | The outline lies at `manuscript/BOOK-OUTLINE.md`, not where the brief says. |

## 4. Refutation adequacy, and the extra task

| Attempt in the book | Adequate? | Reason, and the strongest counterexample I found |
|---|---|---|
| §6.3 C1, CON-0086 | inadequate (the book says so) | It cites the source's own rule. |
| §6.3 C2, CON-0087 (derived patterns) | adequate | — |
| CON-0089 ("no counterexample found") | weak | One attempt only. |
| §9.1 C4 (∅, refuse, noop) | adequate for preservation alone | It misses purge and forge (finding 1). |
| §9.6 "what a reviewer would need" | falsifiers stated, not attempts | It misses the identity premise (finding 6). |
| §9.7.5 (constant U) | partly adequate | Constant T defeats it (finding 5). |
| §9.8 M1–M5 | adequate | Except the step in finding 9. |
| §9.9 M-T1 | trivial (the book says so) | — |
| §9.9 M-T2 | inadequate | Finding 7. |
| §9.9.4 trivial case | adequate | — |
| **No attempt recorded** | — | §6.4 C4 (capability identity); §6.6 C3, C5, C8 (direction law); §9.2 C2; §9.5 C2. |

**Foundational derivations:**

| Derivation | (a) Counterexample in scope | (b) Boundary | (c) Rival | (d) Falsifier | Book adequate? |
|---|---|---|---|---|---|
| §9.1 C2–C3, the kernel form and existence | purge and forge (finding 1) | the guard reading; trace invariants | a kernel defined by interface constraints, i.e. allowed transitions (EXT-008 "defined by its invariants") | a law of the book that no state invariant can express. Found: append-only | The proof is correct; the type is not checked against the book's own laws. **No.** |
| §9.6 Analysis 1, the necessity of CR-a and CE | a bearer-credential model: standing is checked from signed acts the requester presents, with no registry | N2 | capability-style credentials with expiry and revocation lists | R_s answered without any record. This is impossible under N2, so the claim that a *registry* is needed is the untested part | partly. Necessity is close to definitional from N2. |
| §9.6 Analysis 2 / §9.7 step 2, delegability | an assigned global id (finding 6) | act times present and trusted | a CRDT-style grow-only set with content ids, and revocations kept as markers | a cross-application uniqueness that a union satisfies | partly. F26 is well posed; the identity premise is hidden. |
| §6.5 C1–C2, record ≠ act; owns/preserves | START gates activation, so the record does more than represent | "reason lives in" is not operational | Reading 1: the subject-blind check is a defect | two raters classifying one invariant differently | yes. F4 and C3 record both readings. |
| §9.8 Lemma D | an unfaithful record: Hist is determined and false | F26, F31 | a bitemporal snapshot store | a model meeting the premises in which Hist is undetermined | yes. It is definitional and says so. |

## 5. Concept verdicts and architecture view

| Concept | Verdict | Reason and scope |
|---|---|---|
| CON-0099 | VALIDATED | Proof checked; closed reading. |
| CON-0100 | **existence proof VALIDATED** (closed reading) · **INVALID as the kernel type** for the book's laws | The purge counterexample is a demonstrated defect. |
| CON-0105, CON-0087, CON-0075, CON-0102, CON-0108 | VALIDATED | Distinctions hold within their stated scope. |
| CON-0111 | VALIDATED as a definitional non-identity | Support is circular (finding 15). |
| CON-0072 | VALIDATED, for declared contracts | Finding 12. |
| CON-0134 | **INVALID as stated** ("never stored"); restated form = CON-0141, PROVISIONAL | Disproved as a structural claim. |
| CON-0132, CON-0138, CON-0143, CON-0149, CON-0160, CON-0117 | PROVISIONAL | — |
| CON-0135, CON-0136, CON-0137, CON-0142, CON-0148, CON-0161, CON-0139, CON-0088, CON-0085 | INVALID as stated (I agree) | Restated forms PROVISIONAL, with findings 6, 7, 9, 11 and 13 to apply. |
| CON-0146 (a)–(c) | VALIDATED as mathematics | I checked the singleton example. |

**Not tested:** CON-0073, 0074, 0076, 0086, 0089, 0103, 0104, 0110, 0114, 0115, 0116, 0118, 0133, 0140, 0147.

**Architecture view:**
- Ch. 6 derives nothing from primitives. It adds boundary cases, and marks kernel versus application only through notes on the "kernel" homonym. §6.1–§6.4 and §6.7 are isolated from the kernel primitives.
- Ch. 9 has two chains:
  - P ∧ Req → existence;
  - R_s → CR-a, CE → delegability.
  Both rest on an undefined Req (T9-01) and an undefined CE. The first also rests on a type that is too weak (finding 1).
- §9.8 and §9.9 shrink the candidate list. History becomes record properties plus CE plus authority-as-verifiability. Trust becomes derived, plus an occurrence premise. What remains: object/identity, authority (A1 + record), evidence, relationships, policy, "the record" (Hyp), and the occurrence premise.
- **Independence:**
  - history is not independent of authority-as-verifiability, as the book says;
  - occurrence is not independent of A1-as-fact (finding 7);
  - "the record" is undecided.
- **Circular parts:** the §9.6.5 ARCH-INV-1 row (the book flags it); CON-0111's lineage; the §6.5 C6 Hyp (on hold).
- **Measured against RESEARCH-PURPOSE:** Ch. 9 applies the minimal and stable tests; Ch. 6 does not.

## 6. Coverage

*The blind comparison covers only my five sampled sources: S0071, S0066, S2834, S0073 and S0078. The rest of the part was reviewed non-blind.*

**Ranked gaps:**
1. **DISTORTED:** S0071's T-1 and F-1 as used in §6.5 C4–C5 (findings 3–4).
2. **DISTORTED:** RL applied to HANDOFFs (finding 2).
3. **MISSING:** S0071 L249, events are recorded facts with no subscribers. This bears on §9.5: no push channel exists to bring a rule to the actor.
4. **MISSING:** S2834's four status ladders that do not agree with each other (L1249–1261, L1391–1405, L1447–1465, L2190–2202). This matters before any status vocabulary is adopted.
5. **MISSING:** S0066's agent-neutral shared scripts (L473–509). This bears on §6.6 C1.
6. **MISSING:** S0078 D.3 ("predicted, so not a counterexample") holds only if the prediction came before the observation. T-4 was proposed after the first `architecture` recorder was seen (S0071 L45, L237).
7. **MISSING:** S0071 L324, the inference "different store rules, so the boundary is real".
8. **MISSING (minor):** S0066's cold-boot qualification.
9. **OPEN:** Unknown ≠ Uncertain, E_t/A_t/K_t, and q = g∘T. The map lists them as not yet in the book.
10. **JUSTIFIABLY EXCLUDED:** S2834's "closed feedback loop" (Ch. 7 is planned); Workflow as a value object.

**COVERED:** fold; recorded vs authorized; T-4; importance ≠ ownership; the aggregate test; the instance-free concept; linked, not copied; the hierarchy and its homonym; use ≠ change; stop conditions; independent verification; capabilities ≠ kernel operators; the recordedBy referent; census drift.

**New fork:** must kernel invariants include transition and trace properties (finding 1)?

**Source errors, listed separately:** S0067 L65's denominator; S0073 L155's 37/37; S0234 L272's "every"; S2834's foundations list (already noted in the book).

## 7. Next steps (dependency order)

| Step | Completion condition |
|---|---|
| 1. Retype the kernel: transition invariants plus input origin | §9.1 restated; purge and forge excluded in the toy model; C3 re-proved |
| 2. Restate CON-0134 against CON-0141; correct §6.5 C4–C5 and §9.7.5's row | no derivation cites "never stored" as a structural claim |
| 3. Scope RL for HANDOFFs | §6.5, §9.6.5 and §9.6.7 consistent with §1.14.3 |
| 4. Make the act-identity mechanism a premise in §9.6 and in CON-0137 | K4 restated with identity |
| 5. Make §9.7.5 condition 4 two-sided | constant T and constant U both fail |
| 6. Align M-T2 with §9.8's two readings of A1 | CON-0161's restated form updated |
| 7. Update map F22 and the position line | the map matches §9.6.6a and §9.7 |
| 8. T9-01 (Req) | a sourced list, each item through the three boundary questions |

No Principal question is needed to proceed. Step 3 can record the inconsistency without a ruling.

## 8. Limits

- `tmp/tools/kernel_model.py` is not in the copy and was not run.
- Chapters 0–5 and 8 were read only at grepped lines (§1.6, §1.12, §1.14 L143–161 and L296–322, §1.16.7, §8.8 L20–50).
- I did not open CHAPTER-PLAN, EXTERNAL-SOURCES or PRINCIPAL-TESTIMONY.
- Of the non-sampled sources, I checked only cited lines of S0067, S0072, S0234, S0128, S0913, S0973, S1500, S0039 and S0045. The others were not checked.
- The verdicts are mine. I decided nothing reserved to the Principal.

## 9. Files opened and searches run

**Step 1 (inventory):**
- `R/P4/BRIEF.md` (L43–123 only);
- the full texts of S0071, S0073, S0066, S2834;
- S0078 (L1–160, L193–323);
- `CORPUS-ALLOWED.tsv` (grep);
- `git log` on the five sources.

**Step 2:**
- `R/P4/STEP2-COMMISSION.md`; `R/P4/BRIEF.md` (full).
- Copy, read in full: `06-theory/RESEARCH-PURPOSE.md`; `chapter_06/06.00`–`06.07` and `TODO.md`; `chapter_09/09.00`–`09.09` and `TODO.md`.
- Copy, extracts only:
  - `CONCEPT-REGISTER.tsv` (header and this part's rows, grep);
  - `THEORY-MAP.md` (headings; rows F22–F34 by grep; `sed` L72–88, 138–139, 196–240, 258–270, 333–349);
  - `manuscript/BOOK-OUTLINE.md` (grep);
  - `chapter_01/01.06` (L1–30);
  - `chapter_01/01.12`, `01.14`, `01.16` (grep);
  - `chapter_08/08.08` (L20–50).
- Allowed sources, at cited lines (`sed`): S0067 L57, 65, 68, 144; S0072 L32; S0234 L20, 272, 282–283; S0128 L48–49; S0913 L133–1428 (19 lines); S0973 (13 lines, L1740–1775); S1500 L124, 223, 225, 285 and its headings; S0039 L234, 275, 326; S0045 L69. Allowed-list greps for S0913, S0973, S1500, S0067, S0072, S0234, S0128, S0039, S0045.
- Searches:
  - `ls`/`find` on the copy;
  - `book_search.py --help` (copy; no result);
  - `grep -rn -i "never consults|F-1|non-consultation|no key whitelist|whitelist|writer obligation"` over the copy's `06-theory`;
  - `grep "S0073"` in the register and the manuscript;
  - greps in the copy's `chapter_01` for "residual trust", "A1", "total order|gap-free", "1.16.7", and the roles table.

**I reached no file of the note's item 3.** The chapter `review/` folders were listed by `wc` as directories but not opened. I ran no `git` command in step 2 and wrote nothing.
