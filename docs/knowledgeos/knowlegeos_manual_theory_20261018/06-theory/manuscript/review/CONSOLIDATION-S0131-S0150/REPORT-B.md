*Consolidation reviewer B (Sonnet), verbatim (saved by the Professor at 2026-10-24T00:40:42Z). sha256 of the hand-back text at receipt: `150473b7d7693e22a84431dba8ac623385a148c580375d3f3c00f058c02ce405` (UTF-8, the text below this line exactly).*

CONSOLIDATION S0131-S0150 REPORT B

**Disclosure.** My session context carried the user's and the project's CLAUDE.md files, a git status snapshot and five recent commit subjects. They name this consolidation and a frozen copy. They also list a modified `START-TIMES.tsv` and an untracked file I did not open. I used none of them. The task prompt reproduced REVIEWER-NOTE.md, so I read the file as well. Process independence only, not personal independence. I read the book only in `BOOK-UNDER-REVIEW/`. I did not open any item-4 file (the working chapters, the working map, register and outline, BASELINE-REVISIONS, old review texts, git history of the manuscript). I did not compare files. Three deviations from the permitted list, stated up front.
- I opened `frozen-table.md`, 30 lines of hashes.
- I read `W/02-FILES.jsonl` for corpus paths, which SCOPE points to.
- I listed the consolidation folder (names only). I saw no REPORT-A, and opened no PLAN, README-PROFESSOR or R3-OUTPUT.

Limits.
- I did not open S0131–S0150. The coverage marks rest on the four inventories and the book. The corpus quote check covered only quotes written with `[Snnnn Lx]`. The many "in meaning" citations are unchecked by me.
- Ch. 2 rests on basis files I may not read. RS, RM, SC, SD, EV and the repository scripts it cites are therefore unchecked. I could only cross-check the 31/8/9/12 schema counts in §2.8.6 against S0020 L22–30, and they agree.
- I did not read Ch. 0–1, 4–7 or the body of Ch. 9, except for grep excerpts and the passages named below.

## 1. Concept verdicts

Verdict scale is VALIDATED / INVALID / PROVISIONAL. K = kernel candidate, A = application or estate method, O = observation of one estate.

### 1a. Window concepts CON-0238–0256

| CON | Reg. | Mine | Reason and scope | K/A · minimal · stable |
|---|---|---|---|---|
| 0238 | PROV | PROV | Senses (i) and (ii) follow from D. Sense (iii) and the universal "no reference of any sense" rest on three listed senses and one decision (S0109/S0110, n=1). | K for (i)(ii), O for (iii) · bundles three claims · low |
| 0239 | PROV | PROV | Sound and modest. Writer exclusivity does not give record identity. A fixed identity is sufficient for K2, not presupposed by it. D cannot supply the identity. Rival: plain configuration drift. | K (an assumption, boundary test owed) · yes · medium |
| 0240 | PROV | PROV | Core valid but nearly trivial. Under one fixed decoder, bytes equal implies identity equal, and the converse fails. The medium premise is real. The "standing kept" wording overstates (B-07). | A, with a K5 question · yes · medium |
| 0241 | PROV | PROV | Holds for acts under no merge-violable law. The R2 reading is by analogy, as the book says. | A · yes · medium |
| 0242 | PROV | PROV | Nearly definitional: reproducible is not reproduced. The independence-of-stake clause is correct. | A, linked to L1a · yes · high |
| 0243 | PROV | PROV, **split** | Bundles at least five clauses. Anchor cut: a K1 corollary that adds nothing, as the register says. Count is not a commitment. Destructive-step guard. Operand condition, which the register itself says is "not from K1". Bounded claim can satisfy itself. | K corollary plus A rules · **not minimal** · medium |
| 0244 | PROV | PROV | The producer's criterion plus a retrofit onto G3 and CE*. Rival: ordinary separation of persistent and derived state. No falsifier. | A · yes · low |
| 0245 | PROV | PROV | Lost update and durability, an established result. K6 applied to the estate is by analogy, as the book labels it. | A, with a K6 link · yes · medium |
| 0246 | INV | INVALID holds | Its term contradicts its own byte-verified case. | restated as 0248 |
| 0247 | INV | INVALID holds | Neither "must" is necessary, and the trivial predicate detects nothing. | restated as 0249 |
| 0248 | PROV | PROV | The first half is a tautology. The real content is that byte-verified evidence leaves only unreachability. List the three premises: hash per file, a reader who opens only the record, a marker held outside the file. | A · yes · medium |
| 0249 | PROV | PROV | Sound with a non-trivial declaration. The operator's identity log is the operator's own report, so the claim is detection of foreign writers. Entry granularity is correct. | A · yes · medium |
| 0250 | PROV | PROV | Needs an explicit hold state independent of location. Remnants of "on the container" remain in the text (B-01). D lacks a positive hold, which is an open K question. | A now, K question · yes · medium |
| 0251 | PROV | PROV | This is happened-before (Lamport) restated with its premise. "is before" should read "can be shown before". No novelty. | K (a K2 corollary) · yes · high |
| 0252 | INV | INVALID holds | A self-including closing entry is possible. | restated as 0254 |
| 0253 | PROV | PROV | The reach versus detection distinction is in-sample versus out-of-sample. The three axes are fine. | A (method) · yes · high |
| 0254 | PROV | PROV | Clause (i) is a modal overclaim (B-06). Clause (ii) is sound under its premise. | A · yes · medium |
| 0255 | INV | INVALID holds | "Cannot fail" lacked its premise, and the falsifier was immune. | restated as 0256 |
| 0256 | PROV | PROV | The universal is too strong (B-05). It is a near-tautology and the known test-oracle problem. Scope: replacement gates, one lineage. | A · yes · medium |

### 1b. Ch. 2 concepts

| CON | Reg. | Mine | Reason |
|---|---|---|---|
| 0056 | VAL | VAL, narrow | Holds only as non-implication: maturity does not imply enforcement. The book supports it with its own measured cases (§2.10 governance rules, `design-check.sh` advisory). It does not rest on S0024's counts or on the undefined L0–L4. The register basis cites the converse (enforcement without maturity), which does not support the claim. |
| 0063 | VAL | **PROV** | The derivation shows "not currently effective". The claim says "cannot be cited as authority". An adopted rule with postponed effect has standing. The map already says "cannot bind". The formula it uses is stale (B-15, B-16). |
| 0082 | PROV | PROV | E9 is probably not a kind of nothing, because evidence is present. It is an evidence-quality axis. "Readable but malformed" needs a boundary against E6(c). |
| 0083 | PROV | PROV | E10 is the open-world limit. It is acceptable as a meta-level note, but not a kind on par with E1–E8. |
| 0034, 0040, 0055, 0061, 0062 | INV | INVALID holds | Reasons hold: binding is not operating; extent is the book's reading; counts by mechanism and kinds by effect; not verifiable; standing and scope open. |

### 1c. Ch. 3 concepts

VALIDATED (second, independent verdict):

| CON | Mine | Scope and caveat |
|---|---|---|
| 0001 | VAL | The dimension's existence, which is separate from representation. The value list is not closed. n=Skill ⇒ r=Tacit couples it to representation, so it is independent except for S. |
| 0047 | VAL | The method only. The hypothetical domains give existence proofs of failure, not corroboration of the cases that "held". |
| 0064 | VAL | Typed union, closure and total extension on S0026's listed edges. I confirmed by inspection that the listed edges are acyclic. The content is trivial (B-33). |
| 0092 | VAL | Non-sufficiency only, in the sense "runtime block". It is not valid in the "binding force" sense of §3.1. |
| 0093 | VAL | Analytic: a specification is not evidence of a run. |

PROVISIONAL (verdict requested):

| CON | Mine | Note |
|---|---|---|
| 0002 | PROV | The bearer is open (F37). |
| 0004 | PROV | The value list is inconsistent ("product" is missing). |
| 0005 | PROV | "inferred" merges deduction and induction. |
| 0038 | PROV | The criterion "invariant iff changing it breaks the model" is circular (B-30). |
| 0090 | PROV | A map of uses, not a definition. It is not exhaustive. |
| 0094 | PROV | The four criteria answer three questions. Only (b) is a correctness condition. |
| 0095 | PROV | A stipulation. The skill counterexample is real. It depends on CON-0010 (B-04). |
| 0097 | PROV | The independence of the criteria is shown by constructed models. |
| 0098 | PROV | The senses are a map, not shown to be exhaustive. |
| 0162 | PROV | Sufficiency is moved to the set, which is correct. |
| 0184 | PROV | Reading A of the bearer. |
| 0185 | PROV | Reading B of the bearer. It depends on CON-0010 (B-04). |

INVALID cores, checked as restated:
- **Reason holds:** 0003, 0006, 0008, 0011, 0029, 0030, 0031, 0035, 0036, 0037, 0039 (circular, SEQ-0012), 0041, 0042, 0043, 0057, 0065, 0066, 0067, 0091 (restated as 0095), 0096 (restated as 0098).
- **Holds, with a caveat:** 0009 is restated as 0162. 0032 is a labelling defect (Int presented as Src). 0045 is invalid, but its argument uses the informal restatement of 0033, which has no register ID (B-36).
- **Status doubtful (B-04, B-31, B-32, B-37):**
  - 0010's reason is stale.
  - 0007's reason is an unresolved choice, not a defect.
  - 0046 misses the stronger counter that `generates` is itself transformational.
  - 0033's restated core has no register ID.
  - Several INVALIDs (0011, 0029, 0030, 0031, 0035, 0036, 0041, 0043, 0065) are "unsupported" or "not yet built". The instruction scale would call those PROVISIONAL.

## 2. Findings

No critical finding. "critical by F4 only" applies to none.

| ID | Finding | Sev | Type | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|---|
| B-01 | Remnant "on the container". §8.20.1 item 1, the C9 heading and the C9 Int bullet say the hold sits "on the container". C9's own correction (W-04), CON-0250 restated and the index row say an explicit hold state independent of location. The settlement's W-16 fixed the register only. | high | defect (settled work) | 08.20 L27, L111, L113 against L48, L115, L250; index row 8.20 | Rewrite the heading and item 1. | a book search for "on the container" hits only the correction notes |
| B-02 | The §8.21 title and the Ch. 8 index row promise "an internal ledger proves nothing outward". The body has no such claim. The settlement's W-10 remedy ("C14 not assured; Evidence ≠ Proof") is absent from the copy. S0150's authorship-evidence defect is also absent. Inventory S0150 defect 1 sets git history as authorship evidence against non-attestable identity (S0146 L147, S0148 L55). | high | defect plus omission | 08.21 title, 08.00 index row 8.21; searches "Evidence . Proof", "not assured", "internal ledger" | Add the claim with its sources, or drop the phrase and amend the settlement row. | title, body and settlement agree |
| B-03 | Estate "agreement" read as support. §8.19–§8.21 carry about 15 "Agreement" tags. By my regex only 2 (8.19 C25, 8.20 C8) carry a same-lineage caveat. 8.18 C15 and 8.19 C7 say "reached independently". The estate chain and the book's K/L laws share the Principal's corpus. §8.19's own header says "two lines of support at most". | high | defect (overclaim) | 08.18 C15; 08.19 C7, C25; 08.20 C8 | Tag every agreement as same-lineage unless a lineage argument is given. Withdraw "independently". | no unqualified "independently" in 8.18–8.21 |
| B-04 | CON-0010 (INVALID) is a live premise without a flag in §3.1 reading (B) and F37, §3.3's category test of "artifact", §3.8 C6 and §3.9 C2 ("Knowledge is not an artifact … needs only that they are distinct (§3.4)"). Its dependents are CON-0095, CON-0185 and the §3.3 failures of "artifact". Its INVALID reason (§3.5 adopts S0020 L49) is overtaken by §3.5's own grading paragraph ("does not follow"). R3 shows 7 explicit lines only. The dependency is mostly implicit. | high | defect | 03.01, 03.03, 03.05, 03.08 C6, 03.09 C2 | Re-review CON-0010. My view is PROVISIONAL: distinctness holds, survival does not. Flag the dependents. | CON-0010 re-verdicted, dependents marked |
| B-05 | CON-0256's universal "cannot detect its divergence" (title, index, register). The text itself says such a reference "can catch internal inconsistency". A survivor-only reference does detect a divergence that shows as inconsistency, for example a truncated copy failing its own hash. The falsifier is therefore satisfiable as worded. | normal | defect (quantifier) | 08.21 C1, register 0256 | "cannot detect divergence that leaves the survivor internally consistent" | wording changed in all four places |
| B-06 | CON-0254 (i), "a hash cannot commit to itself". A fixed point h=H(prefix‖h) exists in principle and is infeasible to find. Non-cryptographic checksums can embed their own fix. | normal | defect (modality) | 08.20 C6, register 0254 | "cannot feasibly" | wording fixed |
| B-07 | CON-0240 and §8.18.1 say content identity certifies "standing" or "that the answers are kept". Identity excludes recorder and status (§9.16 R2). R2 uses the status for marks (the weakest status) and the recorder set. A replacing migration can change marks with identity unchanged. The C2 text knows this, but the headline does not. | normal | defect (scope) | 08.18 C2 and 8.18.1; 09.16 R2 L93–98 | "certifies the fields CE* reads, not marks" | headline matches C2 |
| B-08 | 8.19 C18 ends "α is unreachable in practice", unconditional. 8.20 C2 later says it is reachable when the switch record cites runtime's head. There is no pointer in 8.19. | normal | stale text | 08.19 C18; 08.20 C2 | add a forward note | the two sections agree |
| B-09 | The 8.19 C19 heading says "the final re-hash closes the detection window, so no serialisation is needed". The body says "none removes it" and "irreducible without atomicity". | normal | defect | 08.19 C19 | fix the heading | heading matches body |
| B-10 | Cited INVALID concepts. §8.19.3 lists "the expected-write predicate (CON-0247)" and §8.20.3 lists "the closing-record rule (CON-0252)" as theory-turn questions. R3 on the four INVALIDs: 0246 gives 9 lines, 0247 gives 11, 0252 gives 8, 0255 gives 5. The direct dependents are the restatements, home sections and index rows, plus these two open-question lines. | normal | defect | 08.19 L231; 08.20 L237 | name 0249 and 0254, and restate the questions | the two lines revised |
| B-11 | Map and outline lag. BOOK-OUTLINE's Ch. 8 marks table has no mark for CON-0239–0256. THEORY-MAP maps none of them, except CON-0244 in one place and the R_K cite. THEORY-MAP §9 still says Ch. 8 is §8.1–§8.17. | normal | omission | BOOK-OUTLINE L143–157, L205–215; THEORY-MAP L294, L353 | add marks, map rows and the corrected chapter line | rows present |
| B-12 | SEQ. §8.18–§8.21 use D, CE*, K1–K7, L1a–L10, R2/R3 and Q1 from Ch. 9, which follows Ch. 8. They are cited by address only. The Ch. 8 index does not state the dependency. | normal | defect (order) | 08.18.1, 08.18 "Existing homes" | add a dependency note or glossary | note present |
| B-13 | Omissions found by the coverage tables (§4). The inventories' main ones: S0150 authorship evidence (B-02); S0142 114 grants against 113 unique ids (reported "verifies exactly", never investigated); S0145 defects b, c, e, f, g; S0133 INFO-1 narrowing to "one writer of governance evidence"; S0132 "compatible ⇒ merge" gloss and the R-CONFLICT drift; S0136 "partial manifest is FAIL"; S0139 "registration is not production"; S0143 "no governed artifact modified" against CAP-003; S0144 "realized while configuration not adopted"; S0146 "DV failures are derivations from a specification"; S0149 domain events and DecisionChanged. | normal | omission | §4 tables | add or mark as excluded | each item marked |
| B-14 | §2.9 re-derives the HARD gate for sets and omits rules whose applicability is CANNOT DETERMINE. §2.8.4 closes it ("unknown overlap is UNKNOWN") but the gate text does not say so. | low | defect (premise) | 02.09, 02.08.4 | one clause | present |
| B-15 | §2.11 states "Effective = adopted ∧ t ∈ validity (Ded)" as a definition. §9.13's current-authority adds lapse of the authorising grant, scope cover and annulment. CON-0063 and the map use the short form. | normal | stale text | 02.11; 09.13 L85, L127 | mark it a necessary condition and point to §9.13 | pointer present |
| B-16 | CON-0063: the conclusion "cannot be cited as authority" exceeds the derivation "not currently effective". An adopted rule with postponed effect has standing. | normal | defect | 02.08.3; map L56 says "cannot bind" | restate as "cannot bind now" | register and text agree |
| B-17 | §2.10 reports "41 enforced controls" (19 deny plus 22 ask). §3.9 C4 shows `ask` interrupts and does not block ("merges blocking with interrupting"). §2.10 does not reconcile. | normal | defect (inconsistency) | 02.10; 03.09 C4; S0043 L40–50 | add the §3.9 distinction to §2.10 | reconciled |
| B-18 | Homonym I-n. §2.9 uses I-2 for the foundations' invariant ("recognition requiring a record") and for the Relationship Ontology's I-2 in one section. The I-n(RO) label appears only in §3.6. | normal | defect (language) | 02.09; 03.06 L79 | label in place | labels present |
| B-19 | "realisability" is used in §2.3(d) and never defined (R2 candidate, judged real). | normal | defect (undefined) | 02.03 L68 | define or drop | defined |
| B-20 | §2.9 keeps "TODO T2-06: DP-n is undefined until its source is read" and resolves it in the next paragraph (RC-13). | normal | stale text | 02.09 | mark superseded | marked |
| B-21 | §2.0 says "only three of the eight are rules", and says it cannot say which. RS is outside this book's allowed list. A count is asserted without derivation. | normal | unsupported | 02.00a | state as RS's claim, or drop | marked |
| B-22 | Ch. 2 cites about 25 audit labels (L-06 … L-24, H-02 … H-10, R-14 … R-21, E-05, C-02 … C-06). They resolve only to `AUDIT-ch00-02.md`, which is not in the book. | normal | traceability | 02.99, prose | add an index or restate | resolvable |
| B-23 | §2.8.6 says "Two consequences" and lists four items. | low | defect | 02.08.6 | renumber | fixed |
| B-24 | "ruled" labels (RM-1…4, SC-1, SC-2) rest on the sources' own statements. For SC-2 the book says the record is "not located". Ruling records are the sources' self-reports. | normal | defect (self-issued status) | 02.00a, 02.08.3, 02.08.6 | add "as recorded in RS/SC" | marked |
| B-25 | The Ch. 3 index source list (S0008–S0022) omits S0018, S0023, S0024, S0026, S0029, S0043, S0166, S0889, S1052, S1524 and the T2 sources used in §3.5–§3.10. | normal | stale text | 03.00 | update | list matches sections |
| B-26 | Index row 3.9 says "four senses of 'execute'; runs(runtime, artifact)". The body has two activities in two modes. §3.8 C5 has three senses. `runs(...)` is the form of the INVALID CON-0091. | low | stale text | 03.00; 03.09.2 | align | aligned |
| B-27 | §3.3 uses I-7(RO) before §3.6's label note. §3.7 uses I-4, I-6, I-7 and §3.10 C6 uses I-7 without a label. | normal | defect (language) | 03.03 L53; 03.07; 03.10 C6 | label | present |
| B-28 | "AC-7" is used in §3.8.1 and never defined (R2 candidate, judged real). | normal | defect (undefined) | 03.08 L5 | define or cite | defined |
| B-29 | §3.6 quotes the Kernel as "remains HYPOTHESIS". S0025 L153 reads "Kernel · Capability SDK · … remain HYPOTHESES". Quote check: 1 real failure in my subset. The other two flagged (nested quote marks in 3.01; an emoji omitted in 3.04) are faithful. | low | defect (quote) | 03.06 L110 | quote exactly | quote check clean |
| B-30 | §3.4 plain-language says "Deleting the document does not delete the rule". §3.8 C6 says this fails when the document is the only carrier. | normal | defect (inconsistency) | 03.04; 03.08 C6 | qualify §3.4 | consistent |
| B-31 | §3.6 CON-0038's criterion, "invariant iff changing it breaks the model", is circular and not operational. "The model" is not defined independently of its invariants. | normal | defect (logic) | 03.06 | give an operational test | test stated |
| B-32 | CON-0007 is marked INVALID, but §3.2 offers two options ("owner is an attribute outside the dimensions, or the six are insufficient") and neither is a defect. The register's own basis says "not closed alone is not a contradiction". | normal | status | 03.02; register 0007 | re-verdict PROVISIONAL or restate the defect | settled |
| B-33 | CON-0064 and §3.8 C3 reason "the labelled union loses nothing; no listed pair stands in two relations". The first claim holds for any typed union, so the premise is irrelevant. The VALIDATED content is trivial. | normal | defect (non sequitur) | 03.08 C3 | state it as trivial | restated |
| B-34 | §3.7 CON-0046 omits the stronger counter. `generates` (production) is in the source's "composition" set, so the source's composition and transformation split fails on the existing side too. | normal | omission | 03.07 | add | added |
| B-35 | §3.10 C4 reports "Computed check … 1,940 families … 946" with no script or run record. Reproduced by me: 1,940, 0 laminar non-forests, 946 forests that are not laminar (464 non-forests). `tmp/tools` has none. | normal | traceability | 03.10 C4 | save the script and run | file cited |
| B-36 | CON-0033's restated core (definition by regeneration, norm separate) has no register ID. CON-0045's invalidity argument depends on it. | normal | defect | 03.05; 03.07 | give an ID | registered |
| B-37 | Status scale blur. INVALID is used for unsupported or not-yet-built cases (see §1c). | normal | defect (status) | §1c | add a qualifier or re-verdict | settled |
| B-38 | The Ch. 3 TODO lacks the IDs of six INVALID concepts (CON-0009, 0065, 0066, 0067, 0091, 0096). | low | defect | 03/TODO | add rows | present |
| B-39 | §3.9 C6 states the "indexing slips" of S0889 twice. | low | defect | 03.09 C6 | merge | merged |
| B-40 | §8.18 C24 sits between C5 and C6. | low | defect (order) | 08.18 | renumber or note | noted |

## 3. Rotated-chapter defect count

| Chapter | Defects (my findings) | critical | high | normal | low | IDs |
|---|---|---|---|---|---|---|
| Ch. 2 | **11** | 0 | 0 | 9 | 2 | B-14…B-24 |
| Ch. 3 | **16** | 0 | 1 | 11 | 4 | B-04, B-25…B-39 (B-39 is mine in §3.9 C6) |

B-17 straddles §2.10 and §3.9 C4 and is counted in Ch. 2 only. The Ch. 3 total counts B-04, B-25–B-39 and B-37. Three quote flags from the check are tool artifacts except B-29.

## 4. Coverage against the four inventories

Items are the inventory's numbered points. "D" = defect (a), (b), … as lettered.

### 4a. C25 (S0131–S0135) → §8.18

| Source | COVERED | MISSING | DISTORTED / EXCLUDED |
|---|---|---|---|
| S0131 | 1 (C5), 2–6 (C1), 7 discriminator (C24), 8 (C2), 9–10 (C4b), 11–12 (C3, C4a, C4c), 13 hidden second truth (C1), 14 (C5), 15 (C1); D a–d, f, h, i | #7 ownership boundary; #13 historical-reference mapping; D e ("union" is the source's reading); D g ("no engine redesign" overtaken) | — |
| S0132 | 2–5 (C6, C7, C8), 7 (C8), 8–9 (C9); D a, b, d | #6 "compatible ⇒ merge" gloss; D c; D e | #10 business value excluded (coverage list) |
| S0133 | 1 (C10), 2 (C12), 4, 5 (coverage list), 7 (C11), 8 (C10), 9 (C12–C14); D a, c, d | #3 INV-ORDER (INV-R1 is in 8.19 C7); #6 INFO-1 narrowing; D b, e, f | — |
| S0134 | 1–9 (C15–C19); D a, c, d, f | D b (two-owner settled by declaration); D e (interpreter undefined) | — |
| S0135 | 1–7 (C20–C23, coverage list); D a | D b (grep miss); D c | — |
| Cross-source | order, axes, counts, subject widening, "can prove when", misattribution, gates, pending, acceptance | R-CONFLICT drift; writer count narrowed | — |

### 4b. C26 (S0136–S0140) → §8.19

| Source | COVERED | MISSING | DISTORTED / EXCLUDED |
|---|---|---|---|
| S0136 | 1–13 (C1–C9), 14 tmp remnant is MISSING; D a–e | #14 partial manifest = FAIL; tmp remnant quarantined | — |
| S0137 | 1–11 (C10–C15); D b, c | #12 writer-last and dormant resolver; #13 exhaustive search for reintroduced claims; D a | — |
| S0138 | 1–6, 8, 9 (C17–C22, 8.19.3); D a | #7 markers versus records; #10 readers before writer; D b, c | — |
| S0139 | 1, 2 (C23, C11); D a, b | #3 registration is not production | — |
| S0140 | 1–5 (C25–C27, C18); D a, c, d | D e (retraction widens the window); D f (self-verification) | D b: α unreachable, covered but unconditional (B-08) |
| Cross-source | agreement, contradiction with S0131, status changes, recurring classes, lineage, pending | count-versus-content class only partly | — |

### 4c. C27 (S0141–S0145) → §8.20

| Source | COVERED | MISSING | EXCLUDED |
|---|---|---|---|
| S0141 | 1 (C17 iv), 2 (C3), 3–5 (C1, C2, C4), 6 (C5), 9, 10 partly (C2, C4, C5); D a, c | r2 happy-path residual; #7 glyph collision; #8 verdict scale partly; D b | — |
| S0142 | 1, 3–10 (C6–C11); D a, c, d, f partly | #2 six of seven placements forced; #11 113 unique ids; D b; D e | — |
| S0143 | 1, 3–6 (C12–C14); D a–d, f | D e ("no governed artifact modified" against CAP-003) | — |
| S0144 | 1, 2, 4–6 (C15–C18); D a–c | #3 realized while configuration not adopted | — |
| S0145 | 1, 3–6, 8 (C19–C23); D a, d, h | #2 cost in amendment cycles; D b, c, e, f, g | #7 Phases 0–4 detail (plan, not theory) |
| Cross-source | missing-act boundary, narrowing, false negative, self-reference, status by non-authority, claim stronger than evidence | Phase-1 metric confounded; production order versus index order | — |

### 4d. C28 (S0146–S0150) → §8.21

| Source | COVERED | MISSING | EXCLUDED |
|---|---|---|---|
| S0146 | 1–12 (C1–C6, C7 lineage); D 1, 2 (C6 W-13) | D 3 (counts with no list); D 4 (115/114 not checkable by me); D 5 (derivations, not observations) | — |
| S0147 | 1–7 (C7); D 1, 2 | D 3 (no knowledge consumer, no verification-to-acceptance edge) | — |
| S0148 | 2–6, 8 (C8–C12); D 1–6, 8 | #1 report vocabulary and exit 0; D 7 (tests reported, not shown) | #7 domain-layer content rules |
| S0149 | 1, 3–6 (C13) | #2 seven contexts partly (C14); #7 Method/Binding/Evidence; #9 domain events, DecisionChanged; D 6–8 | #8 service split; #10 pillars |
| S0150 | 1–3, 7 (C14); 8 (coverage list) | #5 four principles; #6 Evidence Boundary Model; D 1 authorship evidence (B-02); D 2, 3 | #4 three things distinct; legal text |
| Cross-source | tool is not judgment, AMD6 "quiet", DV-7, self-closure against "VERIFIED", who accepts, context list change, concept to context too early, authority homonym, recurring class, reasons load-bearing | git-as-authorship against non-attestable identity (B-02) | — |

No item is DISTORTED in a way that changes meaning. The C18 "α unreachable" item is stale, not distorted (B-08).

## 5. Sequence check (SEQ)

| Item | Where | Note |
|---|---|---|
| Forward use of Ch. 9 constructs (D, CE*, K, L, R2) | 8.18–8.21 | B-12 |
| "realisability" never defined | 2.03 L68 | B-19 |
| "AC-7" never defined | 3.08 L5 | B-28 |
| I-n homonym | 2.09; 3.03, 3.07, 3.10 | B-18, B-27 |
| SC-1 "unruled" in §2.8.3, ruled in §2.8.6 | 2.08 | The §2.8.3 sentence is source-attributed; label as superseded |
| F6, F13, DB-5 and audit labels used without chapter definition | 3.01; Ch. 2 prose | B-22 |
| Stale cross-sections | 2.10 against 3.09 C4; 8.19 C18 against 8.20 C2; 2.11 against 9.13 | B-17, B-08, B-15 |
| "commission" (a grant of work, and the commissioner's list) and "closed" (a verdict, an enumeration, a store) | 8.19–8.20 | low homonym risk; qualify |

## 6. Architecture view

- **What C25–C28 added.** The book itself says "no kernel change" about 15 times. The windows add refinement candidates only.
  - K1: anchor cut. K2: citation order between records. K6/L9: serialised acceptance plus durability. K7: recorded interpreter. K5: two levels of identity.
  - D lacks a positive hold state (open).
  - Estate method: checkable versus checked, reach versus detection, reference conditions.
  - No new primitive or law. Everything else is observation of one estate. The sources are one chain (the AIP durability chain plus Track 2).
- **Circularity risk.** Estate rule is mapped to D, called "agreement", and then cited as support for D (B-03). The kernel results hold only as Ded from the book's own K/L laws, with instances as illustrations. I did not find the pattern in Ch. 2 or Ch. 3, which flag lineage carefully (§2.1, §2.4, §2.8.4 corroboration).
- **Isolated or thin parts.**
  - CON-0244, CON-0253 and CON-0238 are unmapped in THEORY-MAP.
  - §3.10 (F8) is isolated from the kernel.
  - §3.9 F11/F12 connect to Ch. 1 A1.
- **Circular.** CON-0039/0066 (flagged), CON-0038 (B-31).
- **Minimality.** CON-0243 is a bundle. CON-0251 is happened-before. CON-0256 is the known test-oracle problem (B-05).
- **Stability.** Low for 0238, 0244. Medium for most. High for 0242, 0251, 0253.

## 7. The previous settlement's rows, spot-checked against the book

| W | Result |
|---|---|
| 01–03 | Present: CON-0256 in §8.21 C1 and the register |
| 04, 05 | Present: CON-0045 and CON-0085 marked INVALID in C13 and C14. Register statuses INVALID, verified |
| 06 | Present |
| 07 | Present: THEORY-MAP L294 cites S0149 |
| 08 | Present |
| 09 | Present |
| **10** | **Not found** (B-02) |
| 11 | Present |
| 12 | Present |
| 13, 14, 15 | Present |
| **16** | **Register only.** Text remnants remain (B-01) |

Settlement §4 checks: the quote check on §8.21 gives 5 found, 0 not found. The whole-book baseline of 3 matches my subset (3.01, 3.04, 3.06). R1 gives 232 rows with a home and 1 flag, as stated. The register has 256 concepts: 40 VALIDATED, 118 PROVISIONAL, 98 INVALID, as stated. The S0145 recount (21–29%, 23–31%, 42%) checks against S0145 L45–47 (4/14, 6/13, 7/12, 12 distinct).

## 8. Pattern checks

| Pattern | Instances found |
|---|---|
| Rule quoted but applied beyond its text | CON-0063 (B-16). §8.21 C8 quoted the information order and used the truth order (self-corrected, W-09). |
| Derivation missing a premise | §2.9 gate (B-14); CON-0240 wording (B-07); CON-0256 universal (B-05); §3.8 C3 (B-33); CON-0038 circular (B-31). |
| INVALID concept cited as a home | §8.19.3 CON-0247 and §8.20.3 CON-0252 (B-10). CON-0010 as a live premise (B-04). CON-0033's restatement has no ID (B-36). |
| Source's self-issued status adopted as shown | "ruled" in Ch. 2 (B-24). Ch. 8 labels glosses carefully, with "accepted" tracked through seven producers. No unlabelled instance in C25–C28. |
| Measurement carried forward unexamined | Counts (41/19/22/16) appear in §2.10 and §3.9 C4 (checked, flagged as one line of support). "2 of 216 dated" reappears in 8.19 and 8.20. |
| Authority's decision read through a gloss | Ch. 8 labels every gloss ("the registrar's"). Ch. 2 reads ARB rulings through AI-generated RS. |

## Summary

1. No critical finding. Four high findings (B-01–B-04).
2. §8.20 still says the hold is "on the container". The settlement fixed the register only.
3. The §8.21 title and index promise a claim with no body. The settlement's W-10 remedy and S0150's authorship-evidence point are absent.
4. About 15 "Agreement" tags in §8.19–§8.21 read estate resemblance as support. Only 2 carry a lineage caveat, and two say "independently".
5. CON-0010 (INVALID) is a live premise in §3.1, §3.3, §3.8 and §3.9 without a flag.
6. Verdicts: CON-0063 goes to PROVISIONAL. CON-0243 should be split. CON-0256 and CON-0254 need wording fixes.
7. Ch. 2: 11 defects (0 high, 9 normal, 2 low). Ch. 3: 16 defects (1 high, 11 normal, 4 low).
8. My checks held. The three-valued containment rules (k=2, 3) and the laminar-family count both reproduce. The §2.3(d) conflict-minimality example checks by hand.
9. Settlement counts, R1 and the quote-check baseline match. 13 of 16 settlement rows are present.
10. Coverage: C25–C28 are mostly COVERED. The MISSING items are listed in §4 and B-13.
11. The windows add refinement candidates to K1/K2/K5/K6/K7 and no new law.

## Files opened

Instructions: REVIEWER-NOTE.md, REVIEWER-INSTRUCTIONS.md, SCOPE.md, BRIEF.md. Also `frozen-table.md` (first 30 lines) and the consolidation folder listing (names only). I also read `W/02-FILES.jsonl` (path lookups, counts).

Copy, read whole:
- chapter_02, all 14 files.
- chapter_03, all 14 files.
- chapter_08/08.18, 08.19, 08.20, 08.21.

Copy, read partly:
- 08.00-index.md, L1–20 and L30–45.
- 08.13, L34–48.
- 09.16, L88–104.
- 09.13, 09.15 and 00.05 by grep.
- BOOK-OUTLINE.md, headings, L143–157 and L185–215.
- THEORY-MAP.md, headings, L43–58, L111–116, L351–380 and F-rows.
- CONCEPT-REGISTER.tsv, parsed in Python (selected rows).
- tmp/tools/book_search.py, L1–60.

Allowed extras:
- The four STEP1-INVENTORY.md files and SETTLEMENT.md.
- CORPUS-ALLOWED.tsv (grep).
- S0016 L40–50 and L82. S0025 L153. S0024 L70–90. S0043 L40–64. S0020 L22–30 and L46. S0022 L52 and L69. S0021 L103–115. S0145 L44–50 and L119–121. S0148 L129–133. S0142 L229–232. S0140 L54–55.
- W/tmp/tools: `ls`, corpus_quote_check.py L1–40, containment_check-run-20261020.txt (tail), conflict_minimality_check-run-20261020.txt (line count), grep -l for laminar/forest.

Scratch: `cons_b_contain.py` and `cons_b_lam.py` in my scratchpad.

## Searches and commands run

- **R1:** `register_home_check.py --root <copy>`.
- **R2:** `undefined_terms.py --root <copy>`, judged for the sections in scope.
- **Quote check:** `corpus_quote_check.py` on all Ch. 2 and Ch. 3 sections and on 08.18–08.21 (3 flagged, 1 real).
- **R3:** `inbound_citations.py --root <copy>` for CON-0246, 0247, 0252, 0255, and for CON-0010, 0063, 0256, 0254, 0038, 0240, 0007, 0033, 0250, 0056.
- **book_search.py (copy):** "I-7\b"; "Evidence . Proof" "not assured" "authorship" "internal ledger"; "adopted . t . validity|effective = ".
- **Greps in the copy:** "on the container" in 08.20; "ledger|Evidence ≠|assured" in 08.21; "Agreement" counts in 8.18–8.21; "compatible", "file_put_contents|one writer of governance", "INV-ORDER", "historical reference", "tokenRefs", "mirror" in 08.18; 15 coverage terms in 08.19–08.21; "forward|Ch. 9|defined in" in 08.00; "CON-0238" in 08.13; "I-7" in 00.05, 03.06, 03.03; "S0149" in THEORY-MAP; "effective" in 09.13.
- **Python checks:** register status counts and Ch. 3 TODO ID coverage; text-versus-register tag consistency in Ch. 2 and Ch. 3 (no mismatch); 02-FILES count for S-ids ≤150 (149, all CONTENT); the containment and overlap check (k=2 gives 625 pairs, 0 unsound, steps 1–2 alone contradict in 70; k=3 gives 15,625 pairs and 2,940 for steps 1–2 alone); the laminar-family count (1,940 families).
