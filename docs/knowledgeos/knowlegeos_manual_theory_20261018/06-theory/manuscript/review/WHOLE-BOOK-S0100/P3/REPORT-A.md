*Whole-book review S0100, part P3, reviewer A (opus). Step-2 report, saved by the Professor from the hand-back, unedited, 2026-10-19T20:04:58Z.*

# Review of P3 (Ch. 4 and Ch. 5), whole-book review S0100, step 2

**Process independence:** I am an AI session independent of the author as a process, not as a person (I may share the author's model family).

## 1. Assessment

- **Is it reliable within its scope?** Ch. 4 and Ch. 5 are careful and mostly source-faithful. Most quotes I checked hold. Many earlier overclaims have already been withdrawn (§5.6.4, §5.7.4). As case law on acts, records and counts, it is reliable within its scope.
- **Largest risk:** the part's load-bearing results are worded more strongly than its own premises allow:
  - §5.4 C2 says "the decision" where only eligibility is shown;
  - the word "Zero" names both the gap set and the condition that it is empty;
  - §5.7 C3 contradicts its own plain-language summary, its register row and its source on supersession.
- **Against the purpose:** no concept in either chapter is tested as kernel or application, minimal, or stable. Ch. 4 never uses the word "kernel".

The blind comparison covers only my five sampled sources (S2377, S0044, S0061, S0027 and S0896). I reviewed the rest of the part non-blind.

## 2. Findings (ranked)

No **critical** finding: nothing I found defeats a foundational concept as stated. Each high finding is fixable in place.

| # | Sev. | Type | Location | Source line | What is wrong | Consequence | Correction | Check |
|---|---|---|---|---|---|---|---|---|
| 1 | high | theory defect | §5.7 C3 vs §5.7.1 L20, CON-0145, S0080 | S0080 L47: "the ARB explicitly forbids inferring it" | C3 says a supersession "is recognized when it is recorded, or when a later act makes it evident; **such an inference counts as evidence**". §5.7.1 and the register say "inferring one is not evidence". | Three statements of one rule contradict each other; C3 contradicts its source. | Pick one rule and align text, plain language and register. The source supports only "recorded, never inferred". | source-verified |
| 2 | high | theory defect | §5.5 C1 ("the field named the authorizer, so A1 can be shown") | S0044 L42: "today the honest attribution lives only in the rationale comment" | The `actor` field is a git-user stamp, not a record of an act. By §5.5 C2's own premise, a record written by a non-human recorder attests no outside act. | C1 contradicts C2 in the same section. The restated core of CON-0109 overclaims. | The record shows neither the executor nor an authorization; attribution survives only as free text. Drop "A1 can be shown". | source-verified |
| 3 | high | theory defect | §5.4 C2 (map 5a "Res") | S0896 L781–784 ("execution is permitted only if…"), L815 ("subject to authorization") | C2 says "the decision depends on the multiset of (status, criticality) pairs". The source and the book (§5.4 "detects, never decides"; §5.5 C4) make this a necessary condition for eligibility. The decision is an act. | "Decision" is used as a homonym, against A1. The headline result is mis-stated. | Restate as "execution eligibility depends on…"; the decision also needs an act (and, per S0896, authorization). | source-verified |
| 4 | high | theory omission | §5.4 (CON-0080), trivial case | S0896 L1021–1050 (no invented gaps; requirements only via an "authorized rule") | An empty contract, or one with no blocking requirement, satisfies the gap operator and gives *proceed* by default. Nothing requires the contract to contain anything. Criticality and R have no owner. | The operator satisfies every prohibition and no must-do requirement: the trivial model the commission asks about. | Name ContractDerivation as the must-do half. Give an owner (an act) for R and for criticality. State the empty-contract case. | source-verified + internal |
| 5 | high | theory defect (language) | §5.4.2 Z3, §5.4 C8 ("What S2377 keeps…: Zero is the empty gap"), RESEARCH-PURPOSE L70 | S0896 L1237–1276 (Z_t = Zero(K,EC) = {g₁…gₘ}), L804 (Zero_blocking = ∅); S1296 L136 ("typed discrepancy"); S2377 L356–362 | In S0896 and S1296, Zero is the gap set, and its "Zero condition" is about **blocking** requirements only. In S2377 and RESEARCH-PURPOSE, Zero is the **predicate** Δ = ∅ over **all** requirements. C8 calls this kept; it is changed. | "Zero" has a fifth sense inside Z3. The S2377 Zero (all requirements met) is not S0896 eligibility (blocking requirements met). | Split Z3 into the operator (a set) and two predicates (all met; blocking met). Correct "keeps". | source-verified |
| 6 | high | theory defect | §4.1.2 independence ("Ded, defined here"); §4.1 C5; T4-03 | — | (a) A definition is labelled a deduction. (b) Under "no shared actor, instructions, reviewer or work item", no two instances in a one-Principal, one-AI estate under one instruction set can ever be independent. Recurrence is unsatisfiable in-estate, which the text never states. (c) All-or-nothing sharing is not statistical independence: a shared reviewer need not create a common cause for the property counted. | The conclusion "the corpus's counts are not independent" holds trivially. The promotion rule has no must-do half. | Relabel as a definition. State the in-estate consequence. Consider graded lineage (the book's own "one line of support") or independence relative to the property counted. | internal |
| 7 | high | theory omission | whole part vs RESEARCH-PURPOSE L45–54 | — | No concept is marked kernel or application, minimal, stable, or as answering the central question. Ch. 4 has 0 mentions of "kernel"; Ch. 5 mentions it only in open questions. | The part cannot yet feed deliverables 1–3. | Add the four-test line to each register row of the part. | internal (search) |
| 8 | high | theory defect | CON-0079 verdict (§5.3.2) | S0029 L16: "deterministic? · does its object/use case exist? · reusable across repositories?"; "A row activates only when the first two are yes" | "Nested: should we build it now? presupposes can we?" confuses the order of asking with dependence between the answers. The three criteria (determinism, demand, reuse) are logically independent. The source's own case is deterministic but not demanded (L43). | An INVALID verdict rests on a faulty argument. | Orthogonality of the criteria holds; activation is a conjunction of two of them. Re-review CON-0079. | source-verified |
| 9 | normal | theory defect | CON-0078 verdict | S0029 L14, L23, L27 | Gates that do not fit the six families refute the **closed list and the cap**, not "a gate is justified by the failure mode it prevents" (the register meaning). The volume gate (L27) arguably fits "sufficient dataset". | The verdict is aimed at the wrong part. | Split: the closed family list is INVALID; the concept is PROVISIONAL. | source-verified |
| 10 | normal | theory defect | §5.4 C8 table, row "satisfaction" | S0896 L760–778: binary C_i(K) ∈ {0,1} "but retaining the richer status" | S0896 also has a yes/no predicate beside a status, which is structurally S2377's Sat plus TypeGap. The contrast is overstated. (This corrects my own step-1 inventory too.) | The claimed difference shrinks to the content of the type list. | Rewrite the row. | source-verified |
| 11 | normal | theory defect | §5.4 C8.3; CON-0101 history "S2377 self-contradiction (F15)" | S2377 L2181 ("not represented"), L2135 (THM-G5 "for fixed EC"), L2198 ("may have improved") | The text's own case is case 1 (R changed): no contradiction, as C8.3 itself shows. Case 2 is the book's construction. | F15 stands as a fork; "contradicts its own order" and the register's "self-contradiction" do not hold for the text. | "S2377 leaves open…" | source-verified |
| 12 | normal | theory defect | §5.1 CON-0050 qualified rule | S0023 L32–34 | The presupposed question was the metric (D-9a), upstream of D-9. It was not "the answer to that decision". | The rule as stated does not cover its only case. | "…depends on the answer to any open question the decision rests on". | source-verified |
| 13 | normal | source fidelity | §4.2 C3 | S0040 L40 | "Sub-week windows withhold rates" is a minimum **time window**. The book reads it as a minimum sample size. | Distorted. | Say so, or drop "minimum size". | source-verified |
| 14 | normal | theory defect | §4.1 C3 vs CON-0070 register | — | The section says "meaningful only if"; the register validates it only "for counts used as confirmatory evidence". Descriptive census counts (the book's own 15/19, §5.7 C1) are meaningful without pre-fixing. | Section and register scopes differ. | Restrict the section. | internal |
| 15 | normal | theory defect | §5.3 C3/C4 ("Track A is reasoning, which L10 forbids") | S0029 L10 ("or an authority re-rules"), L43 ("Correction accepted"), L59 | Who accepted the correction is unstated. If an authority accepted it, it is a permitted re-ruling. | "Breaks its own rules" is unsupported, not shown. | Mark as undetermined. | source-verified |
| 16 | normal | source fidelity | §4.1 C5 (volume watch "conflicts with" L140) | S0029 L27; S0027 L199 | The ~10–15 watch arms the **lifecycle machinery**. It does not extract a pattern. | Distorted conflict. | Restate as a workload gate. | source-verified |
| 17 | normal | evidence | §4.1 C5 ("one agent, one instruction set") | S0027 (no actor named) | Stated as fact; it is Int. | — | Label Int. | source-verified (absence in S0027) |
| 18 | normal | consistency | map §4a/§5 vs register | — | Map shows CON-0076 ✓ and "Res (scope of the n ≥ 2 rule)"; the register has CON-0076 PROVISIONAL. Map 5a says "three" senses of Zero; L349 lists "5 (§5.1–5.6)". | The map overstates and is stale. | Sync the map. | internal |

Lower-priority points are in Appendix A.

## 3. Changed theory

| Item | Holds? | Reason |
|---|---|---|
| §5.7 C4 cross-reference to §8.10 C10 ("a later pack gives bounded context / stewardship before ownership") | **holds** (internal only) | §8.10 C10 reports two differently ordered chains on one day (S0093 vs S0095 L99, in meaning). It notes the sources do not say the order changed, and that "§5.7 C4 rests on the first order". C4's "not stable" is fair. C4's Int (capability before its provider) survives either order. I could not quote-check S0093 or S0095: they are not on P3's allowed list. §8.10's reference table omits the cited S0093 L43 (outside this part). |

## 4. Refutation adequacy and the foundational derivations

### Recorded falsification attempts

| Attempt | Adequate? | Reason / stronger counterexample |
|---|---|---|
| §4.1 C1 "a reader takes a count for a fact" | inadequate | A misreading risk, not a case where the claim fails. |
| §5.2 genesis needs two closures | adequate | It found a real case and the rescue was withdrawn. |
| §5.5 C2 "a person records their own decision" | adequate | It led to the premise being stated. |
| §5.5 C3 must-do half (a registrar that never registers) | adequate | — |
| §5.5 C6 trivial case (one subject) | adequate but weak | Stronger: scopes bound to subjects at grant issue (the stated hidden premise). |
| §5.7 C1 never-close; §5.7 C5 never-reopen | adequate | — |
| CON-0048 (fields vary), CON-0049 (packages 3–10 unordered) | inadequate | They test how the concept is applied, not the concept. |
| CON-0080 "does it decide? no"; CON-0082/0083 | inadequate | Each restates the definition. Stronger: the empty contract (finding 4) and the Zero homonym (finding 5). |
| CON-0068/0069/0070/0071/0077/0107 | **none recorded** | For CON-0070, my counterexample is a descriptive census count (finding 14). |

### The foundational derivations

**CON-0070 + the §4.1 independence definition** (they underlie §4.1 C3/C5, §5.3 C1/C6, §5.5 C5, §5.4 C2, T4-03)
- (a) Strongest counterexample: a census count (15 of 19, S0079 L74) is meaningful though its class was named in the same reconciliation.
- (b) Boundary: confirmatory or inferential counts only.
- (c) Rival explanation: R-6's weakness is the missing baseline (C4), not the classification done afterwards.
- (d) Falsifier: a pre-registered count that is no more reliable than a post-hoc one on the same data.
- Book adequacy: partly. The register restricts the scope; the section does not. The independence rule has no must-do half (finding 6).

**§5.4 C2: (status, criticality) decides**
- (a) Strongest counterexample: identical pairs with a missing authorization act. Eligible, but there is no decision (S0896 L815).
- (b) Boundary: eligibility under a fixed, non-empty contract with criticality given.
- (c) Rival explanation: eligibility is the set of blocking requirements that are not satisfied. The multiset is more than needed. Choosing the remedy needs per-requirement status and dependencies (S0896 L1500–1526).
- (d) Falsifier: two states with equal pairs and different correct eligibility.
- Book adequacy: the proof is valid for eligibility; it is mis-worded as "decision" (finding 3).

**CON-0131: a subject-blind check cannot enforce a rule about subjects**
- (a) Strongest counterexample: grant scopes that are unique per session. Covered by the stated premise.
- (b) Boundary: the check is the only enforcement point and the caller supplies the scope.
- (c) Rival explanation: S0067's "boundary-conforming" reading. The book records it.
- (d) Falsifier: a subject-blind f that refuses a producer.
- Book adequacy: adequate. VALIDATED stands. S0058 L82–88 verified.

**§5.5 C2: a record cannot attest its recorder's own act**
- (a) Strongest counterexample: a recorder that transcribes a human's utterance. Its "own output" is the act's record.
- (b) Boundary: the act referred to is the recorder's own.
- (c) Rival explanation: the risk is unverifiable externality, not self-reference.
- (d) Falsifier: a mechanism that detects self-reference.
- Book adequacy: adequate, but C1 contradicts it (finding 2).

## 5. Concept verdicts and the architecture view

### Verdicts

| Concept | My verdict | One-line reason, scope |
|---|---|---|
| CON-0068 | VALIDATED | A record design; four kinds plus a disclaimer hold against S0027 L113–121. |
| CON-0069 | VALIDATED | Necessary, not sufficient. The proposing-instance rule can be derived from the independence definition. |
| CON-0070 | VALIDATED | For confirmatory counts only; the section text is too broad. |
| CON-0071 | VALIDATED | Definitional and tautological (scope ∩ coverage); no empirical content. |
| CON-0107 | VALIDATED | As a description of S0040 L14–41. |
| CON-0077 | VALIDATED | As a row design. |
| CON-0078 | PROVISIONAL (concept) / INVALID (closed list) | Finding 9. |
| CON-0079 | orthogonality of the criteria VALIDATED; register INVALID unsupported | Finding 8. |
| CON-0080 | PROVISIONAL | Plus the homonym (finding 5) and the empty contract (finding 4). |
| CON-0081 | INVALID | Agree. |
| CON-0101 | PROVISIONAL | "Self-contradiction" not shown (finding 11). |
| CON-0109 | INVALID as stated | The restated core is valid only without "A1 can be shown". |
| CON-0113 | INVALID as stated, valid restated | Agree; S0044 L26–31 verified. |
| CON-0131 | VALIDATED | Proof checked. |
| CON-0144 (restated) | PROVISIONAL | One day. "Deliverable" includes a deliberate null, so its membership test is not fixed, by the book's own CON-0070. |
| CON-0145 | INVALID (current §5.7 C3 text) | Contradicts S0080 L47 and its register row. |
| CON-0050 | INVALID | The qualified form is mis-aimed (finding 12). |
| CON-0053, CON-0124, CON-0123 | agree with the register | Checked against S0024 L16–24, L155, L162, L199 and S0052 L16, L66, L85, L87. |
| §4.1 C4 (evidenced ≠ measured), §5.3 C6 (precision ≠ identification) | VALIDATED | Sound; S0027 L34 and S0029 L28–29 verified. |
| §5.3 C2 (scope of n ≥ 2) | PROVISIONAL | Rests on CON-0076, which is PROVISIONAL. |

Not tested: CON-0012, 0013, 0014, 0019–0023, 0048, 0049, 0051, 0052, 0054, 0082, 0083.

### Architecture view

- **Primitives:** the part introduces only requirement/contract, status and criticality (§5.4), plus method norms (counting class, independence, pre-specified rule).
- **Laws:** almost every claim is an instance of a Ch. 1 law: A1, §1.12, §1.13.1, §1.14.3, CON-0102.
- **Results:** §5.4 C2, CON-0131, §5.5 C2 and §5.3 C6 are derived; the rest are cases.
- **The chain:** primitives → laws → results holds only in §5.4, and that chain is cut at R and criticality, which have no owner.
- **Independence and minimality:**

| Item | Independent / derivable / undecided | Model where it alone fails |
|---|---|---|
| criticality vs status | independent | same statuses, different criticality (C2) |
| epistemic vs institutional vs normative status kinds | independent | Prohibited ∧ Unknown on one requirement, which supports the tuple form |
| CON-0077 | derivable | CON-0070 + a threshold + an action |
| §4.1 C2 rule | derivable | the C5 definition |
| CON-0071 | derivable | set intersection |
| CON-0145 | undecided | I-7 + §1.12 |

- **Isolated parts:** §5.2 "gap" (platform vs goal) and §5.4 "gap" (unsatisfied requirements) are not connected. §5.4.4 only lists the link as open.

## 6. Coverage

### Blind comparison against my five sampled sources

| Point (from my inventory) | Mark |
|---|---|
| S2377: gap as a set, numbers derived, contract compatibility, provenance of I_t, overlap, the 7/10 slip, the sign error, proof grades | COVERED |
| S2377 §54 vs THM-G5 | DISTORTED (finding 11) |
| S2377 adequacy is yes/no (L2577) yet graded (L2247) | MISSING (normal) |
| S2377: G = Σ w_r d_r brings back the cross-class aggregation rejected at L5–9 | MISSING (normal) |
| S2377: no governance or approval gap class | MISSING (normal) |
| S2377: ⊥ used with two meanings | COVERED (the book found it; my inventory missed it) |
| S0044: RD-1…RD-7, 3/5, DDD refusal, activity ≠ effect | COVERED |
| S0044: RD-1's "domain meaning unproven" | MISSING in §4.3 C2 |
| S0044: "attribution only in the rationale comment" | MISSING (finding 2) |
| S0044: stale L22 ("anomalies: none yet"); no-ML constraint | MISSING (minor) |
| S0061: pinned blob, annotation, two messages, V#2 caveat, gate to Stage 2 | COVERED (§5.6 C6/C7) |
| S0061: negative scope list | partly (from S0053) |
| S0061: V-E, an acceptance over unauditable figures | COVERED in substance in §8.5 C6 (S0055), not as a feature of the act (§5.6) |
| S0027: template, two streams, R-6 count, back-edge by user audit, persona review | COVERED |
| S0027: append-only rule beside REV blocks; OE-KOS-4 "refused to invent a business decision" as a concept | MISSING (minor) |
| S0896: statuses, Missing, Failed, Lord boundary, not a metric, requirement graph | COVERED |
| S0896: binary C_i | DISTORTED (finding 10) |
| S0896: blocking-only Zero condition | MISSING (finding 5) |
| S0896: replay / reproducibility (L981–1019) | MISSING |
| S0896: the "PASS" thought-tests are not falsification | MISSING |

### Ranked gaps

1. Contract derivation as the must-do half, with owners for R and criticality.
2. The kernel/application test on every concept.
3. An independence notion that can be satisfied.
4. The Zero/Gap/decision/closure homonyms (Appendix B).
5. Replay of the gap operator.

### Forks

- New candidate fork: "Can in-estate recurrence ever count as independent?" (finding 6).
- F15 stands as a fork; reword its basis (finding 11).

### Source errors (separate)

- S0024: "4" vs "3" evidence gaps.
- S0077 L26: "Four independent verification passes" against three recorded.
- S2377: 7/10 classes; age sign.
- S0029: STAGED rows after ACTIVE.
- S0044 L22: stale.

## 7. Next steps (dependency order)

1. Resolve the supersession rule: §5.7 C3, §5.7.1 and CON-0145 agree with S0080 L47. **Done when** all three say the same.
2. Restate §5.4 C2 as eligibility; add the empty-contract case; name owners for R and criticality. **Done when** C2 and map 5a no longer say "decision".
3. Split Z3 and align RESEARCH-PURPOSE L70 and the map. **Done when** each sense has its own term.
4. Repair §5.5 C1. **Done when** it is consistent with C2 and S0044 L42.
5. Relabel the §4.1 independence definition and state its in-estate consequence. **Done when** T4-03 has a resolution criterion that can be met.
6. Re-review CON-0078 and CON-0079. **Done when** both verdicts rest on the register meanings.
7. Apply the RESEARCH-PURPOSE test to all register rows of the part.

**Question for the Principal** (needed for step 5): may instances produced under the same Principal, AI and instruction set ever count as independent recurrence? If not, promotion inside the estate is impossible by definition.

## 8. Limits

- Read in full: all part files, the part's register rows, the map rows for §4/§5/§5a and the architecture section, RESEARCH-PURPOSE.
- Read only where cited: §8.10 C10, §8.5 C6, the Ch. 2 §2.8.8 E-table (by grep).
- Not read: other chapters; §1.6/§1.12–1.17 (relied on as cited); S0017, S0019, S0025, S0030, S0036, S0051, S0053, S0055, S0064, S0065, S0067, S0076, S0078, S0129 (lines only), S0208, S0721, S2834.
- Not re-run: book_search beyond the listed queries; the kernel model tool.
- Not decided: F15, F21, F28.

## 9. Files opened and searches run

**Files opened, step 2** (paths under R = `docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/WHOLE-BOOK-S0100/`):
- R/P3/STEP2-COMMISSION.md (full)
- R/P3/BRIEF.md (full)
- In R/BOOK-UNDER-REVIEW/06-theory/:
  - manuscript/chapter_04/04.00, 04.01, 04.02, 04.03, TODO.md (full)
  - manuscript/chapter_05/05.00 … 05.07, TODO.md (full)
  - RESEARCH-PURPOSE.md (full)
  - CONCEPT-REGISTER.tsv (header + grep of 36 IDs)
  - THEORY-MAP.md (grep, sed L59–112 and L255–280)
  - manuscript/chapter_08/08.10-… (grep "C10", sed L78–89, References)
  - manuscript/chapter_08/08.05-… (grep "C6. A snapshot" -A6)
  - manuscript/chapter_02/*.md (grep for "2.8.8" and E1–E8; this touched 02.08, 02.09, 02.99-change-log, TODO)
- Directory listings: `ls` of manuscript/, chapter_08/, chapter_02/; `wc -l` of the part files. The `review/` subfolders inside chapters appeared in the listings but were not opened.
- Corpus sources, all from the P3 allowed table:
  - S0080: L1–12, L26–60
  - S0079: L1–6, L30–34, L55–76
  - S0029: L1–45, L55–76
  - S0040: L1–60
  - S0028: L57, L182
  - S0026: L103
  - S0896: L100–110, 671–712, 753–790, 791–822, 847–884, 1233–1280, plus a grep for varnothing/empty/Zero=
  - S0913: L1160–1176
  - S1296: L136–149, 179–182, 212–220, 230–234
  - S0077: L1–50
  - S0058: L49–51, L82–88
  - S0129: L14–15, 19, 34, 147–149, 160
  - S0023: L5–7, 12, 22, 32–43, 168
  - S0024: L16–24, 155, 162, 199–202
  - S0052: L16, 66, 74, 81, 85, 87

**Book searches** (copy's `book_search.py` only): `--help`, "gitignored", "V-E", "retroactively audited", "invent gaps", "replay", "rationale comment", "refused to invent", "empty contract", "criticality".

**Other greps** (scoped to part files): `kernel|application` (count); `falsif|counterexample attempt|trivial`.

**Step-1 files:** listed in my inventory.

**Forbidden files:** I reached no file in REVIEWER-NOTE item 3: no working chapters, working map, register or outline, BASELINE-REVISIONS, OLD files, or manuscript git history. I opened nothing else under R/ and no sealed or hold-out file. No git command was run in step 2. I wrote, moved or changed nothing.

---

## Appendix A: lower-priority points (normal)

- §4.3 C6 calls S0044's discriminator "fixed in advance". §4.2 C5 faults the same seven criteria for having no threshold. Apply the same critique in both places.
- §4.3 C5b and §5.5 C1 label "the authorizer was a person" as Src. It is Int from "at the user's direction".
- §5.4 C1 applies Ch. 2's E-list (kinds of empty applicability sets) to evidence sets without the transfer check that §5.4 C4 demands for E10.
- §5.4 C7 reads "preservation of previously valid requirements" as a condition. Under a fixed R it already follows from ⊆, so the reading makes it redundant.
- §5.6 C6 "tension VALIDATED": the derivation itself shows compatibility (the pin names the earlier version). What remains is a checkability burden, not a contradiction.
- §5.2 calls "CONFIRMED [L24] while counting one architectural gap" a contradiction. "The gap" there is the overall verdict, so it is weak as a contradiction.
- The 05.00 index lists §5.3 after §5.7.
- S2377's derived measures g_obs (L1400) and the freshness deficit: §5.4 C8's claim holds for g_obs. The freshness deficit does satisfy d = 0 ⟺ Sat for a requirement whose only standard is freshness.

## Appendix B: homonyms

| Term | Meanings |
|---|---|
| Zero | Z1–Z4, plus inside Z3 the set vs the predicate, and all requirements vs blocking only |
| gap | §5.2 platform shortfall · §5.4 unsatisfied requirements · §4.2 "not a gap" · S2377 "evidence/warrant gap" vs §5.2 "evidence gap" |
| decision | §4.2 developer's stage · §5.1 authority act · §5.4 C2 eligibility · §5.5 "decide" as choosing vs as conferring |
| closure | IGNORED as closure (§4.2) · closing a lane (§5.7) · closing a gap (§5.2) |
| independent | generating process (§4.1) · "independent verification" (S0077; §5.7 C2) · statistical |
| criterion | activation criterion · watch criteria · "check criterion" (§5.7 C6) |
| requirement | §5.4 vs §9.1 (the book already records this) |

## Appendix C: invariant owners

| Invariant | Owner / guarantee |
|---|---|
| presence of humanActRef | the engine |
| externality of the act | discipline only |
| one decision per recommendation | none (open) |
| closure rule | Governance restraint |
| subject-based authorization | none (subject-blind check) |
| pinned object | the registrar, by hash |
| R and criticality | **none** |
| writer identity | none (§5.7 C9) |
