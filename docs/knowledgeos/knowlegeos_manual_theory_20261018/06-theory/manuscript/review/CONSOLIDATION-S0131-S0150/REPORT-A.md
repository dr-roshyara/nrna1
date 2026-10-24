*Consolidation reviewer A (Opus), verbatim (saved by the Professor at 2026-10-24T00:39:37Z). sha256 of the hand-back text at receipt: `e97c331c22de51f0e631fb84675434cbe082b6306dcba58189fb38c9bc110bed` (UTF-8, the text below this line exactly).*

CONSOLIDATION S0131-S0150 REPORT A

**Disclosure.** My session came with a system prompt for a different review task (an EKS/PKS research review). I followed this commission instead. I also had the two CLAUDE.md files, the repository's git status (which lists modified working files; I opened none of them) and the recent commit subjects. Those subjects name the frozen copy, the S0150 evaluation facts and the C28 settlement, but contain no theory text. I did not open REPORT-B or any other report in the consolidation folder. An `ls` of that folder showed the names PLAN.md, frozen-table.md, R3-OUTPUT.txt and README-PROFESSOR.md; I did not open them. I opened no file listed in REVIEWER-NOTE item 4, no Ch. 0–2 basis file and no sealed, hold-out, .env or chronological-read file. Independence here is process independence, not personal independence. The analysis needs more than the two-page target: the brief asks for 72 verdicts and four coverage tables.

## 1. Assessment
- Within its stated scope, the new Ch. 8 theory (§8.18–§8.21) is mostly reliable. The cores of CON-0239, 0240, 0242, 0245 and 0253 survive attack. Ch. 2 and Ch. 3 are sound in their derivations, but carry stale text and status contradictions that later sections created.
- **Largest risk:** §8.21 and its index row promise a claim ("an internal ledger proves nothing outward") that the body does not contain, and settlement rows W-10 and W-15 point to text that is not there. In addition, CON-0256 as stated is met by its own falsifier inside the estate's design.

## 2. Findings (ranked; none critical)

| ID | Finding | Sev. | Type | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|---|
| A-01 | §8.21's title and Ch. 8 index row say "an internal ledger proves nothing outward", but no claim in §8.21 states it. Settlement W-10 ("C14: 'not assured'; 'Evidence ≠ Proof'") is absent: C14 is the bounded-context test. W-15 points to "§8.21 C15", which does not exist. S0150 L248–273 is in References with no claim citing it. | high | defect (settlement not applied) | 08.21 L1, L152–158; 08.00 L35; SETTLEMENT §1; R3 "internal ledger": 0 hits | Restore the claim (S0150 L109–118, L254–260: git and logs as authorship evidence, against CON-0196 and S0148 L55; the Evidence Boundary Model ↔ CON-0244), or retitle the section and fix the settlement pointers | the claim is present with its anchors, or the title and index no longer state it |
| A-02 | **CON-0256 as stated fails its own falsifier.** A reference stored in the survivor but derived from the source (a recorded source enumeration or hash manifest) is "a function of the survivor alone" and still detects divergence. That is the estate's own design: §8.20 C2/C8 put the SOURCE FINAL-STATE ENUMERATION in the new store. The W-01 split between "internal inconsistency" and "divergence" depends on where the reference's information came from, not where it is stored. | high | defect | 08.21 L51–57; 08.20 L54, L106–107; register row 256 | INVALID as stated. Restate (ii) as: the reference's information originates independently of the checked content, and the act cannot rewrite it | restated and reviewed |
| A-03 | §2.11 defines "Effective = adopted (a lifecycle status, … not a standing) ∧ t ∈ validity". §2.8.3 L56, Ch. 1 §1.14.1-12 L269 and the CON-0063 basis quote it as "standing *adopted*". It also contradicts §2.2 (a stored lifecycle state does not establish authority, STATE ≠ AUTHORITY) and is narrower than §9.13's current-authority (covers, grant conditions, endings, three values). CON-0063 survives under both readings. | high | defect (stale/inconsistent) | 02.11 L27; 02.08 L56; 02.02 L47; 09.13 L127; R3 CON-0063: 6 dependants incl. THEORY-MAP L56, BOOK-OUTLINE L36 | Define "effective" as §9.13's current-authority restricted to rules without grant conditions; rebase CON-0063 | one wording in all six places |
| A-04 | CON-0010's INVALID reason ("§3.5 adopts S0020's view that L-A covers knowledge kinds") is stale: §3.5 L34 says the declining argument does not follow from S0020 L49. T3-10 repeats the stale reason. | high | defect (status basis) | 03.04 L7; 03.05 L34; TODO T3-10 | Re-state the reason. What remains is T0-05, the pilot-gated deletion test and the distinctness ≠ survival point. Re-verdict the core | reason consistent with §3.5 |
| A-05 | CON-0251 uses "before" in two senses: occurrence and evidenced/causal order. Physical time always exists, so "no shared order source, physical or logical" is never literally met. The law is Lamport's happened-before (1978) and is not cited as prior work. C8 and CON-0254 rest on it. | high | defect + omission | 08.20 L50–60; register row 252 | Restate as "is *shown* to be before only if …"; add the Ext benchmark | restated |
| A-06 | CON-0038's criterion ("invariant iff changing it breaks the model") is circular, because the model includes its invariant list. The empty invariant set satisfies it trivially. | high | defect | 03.06 L76, L82 | INVALID as stated. Restate relative to a stated set of guarantees or decisions (compare CON-0067's decision test) | restated |
| A-07 | Ch. 2 reads the RM-1…4, SC-1 and SC-2 rulings only through AI-generated preparation documents (RS, SC, SD) and labels them "ruled". The C23 rule is not applied. SC-1 lacks the "record not located" caveat that SC-2 carries. | normal | defect (stale vs C23) | 02.00a L3; 02.08 L53, L124, L130 | Label each ruling "as registered by …, act not seen" | done |
| A-08 | INVALID concepts are still cited as homes: CON-0252 in §8.20.3 L237; CON-0085 in §8.21's homes table (L19) and in §8.21.3 L165. W-04 was applied only in C14. | normal | defect (pattern) | R3 CON-0252, CON-0085 | Replace with CON-0254, and with "two necessary-condition forms" | no INVALID home remains |
| A-09 | §8.20 C14 says §8.19 cites S0140 L31–37 for the marker lifecycle. It does not, and the lifecycle (S0140 item 4) is missing from §8.19. | normal | defect + omission | 08.20 L157; grep of §8.19: none | Add the claim to §8.19, or fix C14 | consistent |
| A-10 | Stale F11 text: §3.8 L15 and C5, T3-20 and THEORY-MAP L25 say "three senses". §3.9 maps two activities × two modes plus three further uses. | normal | defect | grep "three senses" | Update all four places | done |
| A-11 | §3.6 L110 reads CON-0057 as valid "as a necessary condition only". The register says it was refuted as necessary (§6.1 C4). | normal | defect | register row 58 | Fix the text | done |
| A-12 | §3.3's definition row still contains "spaces NEST", the clause §3.10/CON-0098 removed because it answers F8. Register CON-0011 has "spaces may nest". | normal | defect | 03.03 L51; 03.10 L23, L29 | Drop the clause or mark it as S0008's claim | done |
| A-13 | CON-0162: clause (ii) implies (i) when both range over the same cases; if (ii)'s range is wider, the two clauses mismatch. The test is not minimal as written. | normal | defect | register row 162; my derivation: d = f(D) ⇒ every d-distinction is a D-distinction | State the case set of (ii); drop (i) or justify it | restated |
| A-14 | §2.8.4a(1) "the two are linked by containment" contradicts Ch. 1 §1.6 item 3 (containment of grant and canon scope is undefined until a mapping exists). §2.8.2 labels an imported SC premise "Ded", and §2.8.6 confirms "stands as Ded". | normal | defect | 02.08 L31, L114, L189; 01.06 L32 | Mark the containment as a premise (Src inferred), "undefined until mapped" | done |
| A-15 | CON-0243 bundles at least four claims (cut, re-take, destructive step, operand; the last explicitly "not from K1"). "A hash … shows that one is missing" should read "shows that the set differs". | normal | defect (UL) | register row 244 | Split the operand condition into its own ID; fix the hash wording | split |
| A-16 | Hidden premises. CON-0244 assumes one durability policy per store; per-path policies inside one store refute "cannot give either its own durability". CON-0254(ii) assumes the follower must also hold the evidence entries. CON-0249's "non-trivial" (excludes something) is too weak: it must exclude what a violator could write, and the operator's identity log is operator testimony that must itself be anchored. | normal | defect | rows 245, 255, 250 | State each premise | stated |
| A-17 | §8.18 C10's "only a recorded re-run by another party" is contradicted by §8.19 C10's second route (re-derivation). The register has already dropped "only". | normal | defect | 08.18 L111; 08.19 L106 | Add a note in §8.18 | done |
| A-18 | A measurement carried forward unexamined: §2.9 RC-13 "41 enforced controls" includes 22 `ask` triggers that do not block (§3.9 C4). | normal | defect | 02.09 L76; 03.09 L110 | Qualify, citing §3.9 C4 | done |
| A-19 | Homonyms: the RO's I-2 and the book's I-2 appear unqualified in one section (§2.9 L55 vs L82; also I-10 in §2.5). A1 is used without its reading in §2.4, §2.5, §3.5 and §3.9 C5a, although §1.6 requires every use to name its reading. §2.11 L18 places A1 at "§1.2" (it is §1.6). | normal | defect (rule not applied) | 01.06 L15 | Write I-n(RO) and name A1's reading | done |
| A-20 | SEQ: AC-7 is undefined (§3.8 L5; R2, one use in the book). MM-1 is used in the 03.00 index before any definition. §2.0's short-name list omits AMS and GKC. A §2.9 L73 inline TODO T2-06 sits beside the paragraph that resolves it. The 03.00 source list stops at S0022 while §3.8–3.10 use S0026, S0029, S0043, S0166 and others. | normal | defect | R2 output; 02.09 L73–76 | Define or point | done |
| A-21 | §2.5 ↔ §2.10 cite each other for I-4's "explicit rank". §2.10 does not contain that phrase. Mutual citation, no source text. | normal | defect | 02.05 L7; 02.10 L23 | Cite I-4/PK-P7's own text | done |
| A-22 | §2.0 says "RS is outside this book's allowed list", yet cites RS by line throughout, and leaves unnamed which three of the eight are rules. §2.4's reconciled analyser vocabulary drops OVERLAPS, which §2.4 L16 and its own table keep. §3.1 says S ties representation, status and scope, but n = Skill ⇒ r = Tacit ties nature too. §3.2 says "applies only orthogonality" over a row that is the category test. | normal | defect | 02.00a L30; 02.04 L28, L40; 03.01 L38; 03.02 L13–19 | Local fixes | done |
| A-23 | Quote check: three failures in Ch. 3. Two are typography only (03.01 L22; 03.04 L11). One is not verbatim: 03.06 L110 "remains HYPOTHESIS", where S0025 L152 has "remain HYPOTHESES" (six components, inside "DOES NOT ARISE"). These are inherited and should be worked through at rotation (entry 60). | normal | defect | quote check run | Correct them | 0 failures in Ch. 3 |
| A-24 | Map and outline are stale: THEORY-MAP §9 lists "8 (§8.1–8.17)"; "read in order 149" against 150 positions is unexplained; the outline's marks table has no row for CON-0238–0256; the outline line "Ch. 8 (§8.1–8.11)". The CON-0056 basis tests the converse (enforcement without maturity); its real support is S0024 L84–88, which I checked. CON-0033's restated core has no ID. | normal | defect / omission | map L351–355; outline L66, L183, L205 | Update; add marks (§5 below) | done |
| A-25 | Coverage omissions (§4 tables). The material ones: S0150's Evidence Boundary Model and four principles; S0144's "realized while not adopted"; S0139's "registration ≠ production"; S0138's readers-first vs writer-first; S0142's six forced placements and one chosen; S0132's "compatible ⇒ merge" gloss; S0134's undefined "interpreter"; S0131's union read as interpretation; §8.21's S0149/S0150 References rows missing lines used in C13 and C14. | normal | omission | §4 | Add, or exclude with a reason | each marked |

**Rotated-chapter defect count:** **Ch. 2: 13**. A-03, A-07, A-14 (2), A-18, A-19 (2), A-20 (2 in Ch. 2), A-21, A-22 (3 in Ch. 2), and the CON-0056 basis. **Ch. 3: 13**. A-04, A-06, A-10, A-11, A-12, A-13, A-20 (AC-7, MM-1, index list), A-22 (2 in Ch. 3), A-23, and CON-0033's restated core with no ID. Counting rule: one defect per distinct error and place; repeats in the map and TODO files are not counted.

## 3. Concept verdicts

**C25–C28 (19)**

| CON | Verdict | Reason · scope |
|---|---|---|
| 0238 | PROVISIONAL | (i) and (ii) follow from D's rules. "No reference of any sense" quantifies over senses not shown to be exhaustive (three listed). One decision. |
| 0239 | VALIDATED | Writer exclusivity does not imply record exclusivity: S0131's two defaults and `--dir` witness it. Scope: one estate; the D reading is hypothetical. |
| 0240 | VALIDATED | Given one fixed decoder and a byte-preserving medium, byte equality ⇒ equal identities, and the converse fails (fields outside R2's identity). |
| 0241 | PROVISIONAL | The D reading holds only by analogy (fork vs several applications); the estate's state machine blocks direct transfer. |
| 0242 | VALIDATED | As a distinction; "checked" is stipulated. The moving condition is sufficient, not the only route (A-17). |
| 0243 | PROVISIONAL | Core corollary holds; the bundle and hash wording need repair (A-15). |
| 0244 | PROVISIONAL | Hidden premise (A-16); a producer's criterion. |
| 0245 | VALIDATED | Acknowledgement does not imply survival (an unserialised read-modify-write writer; possibility shown from the code). Rate not established. The L9/K6 part is by analogy. |
| 0246, 0247, 0252, 0255 | INVALID (kept) | Restated cores checked below. |
| 0248 | PROVISIONAL | "Only unreachability binds" is not shown exhaustive over remedies. Core sound. |
| 0249 | PROVISIONAL | The non-triviality condition is too weak; the log needs an anchor (A-16). |
| 0250 | PROVISIONAL | Normative must-do from one estate; D has no hold state (kernel question open). OPEN-M7 shows the trivial "hold forever" case. |
| 0251 | PROVISIONAL | Homonym "before"; prior art (A-05). |
| 0253 | VALIDATED | Reach ≠ detection, a standard train/test and mutation-testing distinction (Ext benchmark not cited). The three axes are not shown exhaustive. |
| 0254 | PROVISIONAL | (i) "cannot" is computational infeasibility (a fixed point), not impossibility. (ii) has a hidden premise. |
| 0256 | **INVALID as stated** | A-02. |

**Ch. 2 (9):** 0056 **VALIDATED** (second verdict; S0024 L84–88 "Not one responsibility is enforcing… at any maturity level"; the maturity scale is undefined; the basis needs fixing). 0063 **VALIDATED** (holds under §2.11 and under §9.13: no adoption gives F, postponed validity gives F; rebase per A-03). 0082, 0083 **PROVISIONAL** (whether each is a kind of nothing or E2 with a reason; dimension set). 0034, 0040, 0055, 0061, 0062 **INVALID** (cores checked; no change).

**Ch. 3 (44):**
- **VALIDATED (second verdicts):** 0001 (as distinct from representation; value list open); 0047 (as a method definition; its evidence is weak); 0064 (scoped: Szpilrajn and acyclicity hold on S0026's listed edges; I checked that the typed union has no pair carrying two relations); 0092; 0093.
- **PROVISIONAL:** 0002, 0004, 0005 (no unknown or conflicted value; F13's 3 vs 4 values), 0090, 0094 ((c) and (d) answer the same question), 0095, 0097, 0098, 0162 (A-13), 0184, 0185.
- **0038: INVALID as stated** (A-06).
- **INVALID (27, cores checked):** 0003, 0006, 0007, 0008, 0009, 0010 (reason stale, A-04), 0011 (definition stale, A-12), 0029–0032, 0033 (restated core unnumbered), 0035–0037, 0039 (SEQ-0012 stands), 0041–0043, 0045, 0046, 0057 (text stale, A-11), 0065–0067, 0091, 0096.

**Restated cores of the four INVALID-as-stated concepts:**
- 0248 (from 0246): sound.
- 0249 (from 0247): sound, with A-16.
- 0254 (from 0252): sound, with A-16.
- 0256 (from 0255): fails (A-02).

## 4. Coverage against the window inventories (C = covered, M = missing, D = distorted, JE = justifiably excluded)

**C25 (S0131–S0135)**

| Items | Mark | Where |
|---|---|---|
| S0131 1–5, 7–12, 14, 15; defects a–d, f, h, i | C | §8.18 C1–C5, C24, C9, lineage |
| S0131 2 (S0133's narrowing to "writer of governance evidence") | D | C1 keeps "exactly one" |
| S0131 6 ("no engine redesign"), 13 (EKS-03 mapping), defects e (union is an interpretation), g | M | — |
| S0132 2, 4, 5, 7–9; defects a, b, d | C | C6–C9 |
| S0132 1, 3, 10 | JE | status and advocacy |
| S0132 6 ("compatible ⇒ merge"); defects c, e | M | — |
| S0133 1, 2, 4–9; defects a, d | C | C10–C14, C23 |
| S0133 3 (INV-R1…R4, INV-ORDER); defects b, c, e (no owner), f | M | — |
| S0134 1–9; defects a, c, d, f | C | C15–C19 |
| S0134 defects b (two owners settled by declaration), e (interpreter undefined) | M | — |
| S0135 1–7; defects a, b | C | C20–C23, §8.18.3 |
| S0135 defect c | M | trivial |
| Cross-source points | C | except the migration subject (C, open) |

**C26 (S0136–S0140)**

| Items | Mark | Where |
|---|---|---|
| S0136 1–13; defects a–e | C | §8.19 C1–C9, C11, C17 |
| S0136 14 (partial manifest = FAIL; tmp quarantined) | M | — |
| S0137 1–3, 5–11; defect c | C | C10–C16 |
| S0137 4 | C | C2 |
| S0137 12, 13; defects a, b | M | — |
| S0138 1–6, 8, 9; defect a | C | C17–C22, §8.19.3 |
| S0138 7 (marker vs record), 10 (readers-first vs writer-first); defects b, c | M | — |
| S0139 1, 2; defects a, b | C | C23 |
| S0139 3 (registration ≠ production) | M | — |
| S0140 1–3, 5; defects a–f | C | C25–C27, C18, §8.20 C3 |
| S0140 4 (marker lifecycle) | M | A-09 |
| Cross-source | C | measurement stability M (trivial) |

**C27 (S0141–S0145)**

| Items | Mark | Where |
|---|---|---|
| S0141 1–7, 9, 10; defects a, c | C | §8.20 C1–C5, lineage; CON-0249 (iv) |
| S0141 r2 (happy path only), 8 (closure test); defect b | M | — |
| S0142 1, 3–10; defects a, c, d | C | C6–C11 |
| S0142 11 | C | via §8.21 C5 |
| S0142 2 (forced vs chosen); defects b (duplicate grantId), e, f | M | — |
| S0143 1–6; defects a–d, f | C | C12–C14 |
| S0143 defect e | M | — |
| S0144 1, 2, 4–6; defects a–c | C | C15–C18 |
| S0144 3 (realized, configuration not adopted) | M | — |
| S0145 1, 3–6, 8; defects a, d, h | C | C19–C23, §8.21 C10 |
| S0145 2, 7 (partly); defects b, c, e, f, g | M | — |
| Cross-source | C | except Phase-1 confound, production order and allowed-list date (M) |

**C28 (S0146–S0150)**

| Items | Mark | Where |
|---|---|---|
| S0146 1–9, 11, 12; defects 1, 2 | C | §8.21 C1–C6; §8.20 C2, C9 |
| S0146 2 | C | as open |
| S0146 10 (author ≠ independent reviewer, not every prior reviewer); defects 3, 5 | M | — |
| S0146 defect 4 | JE | — |
| S0147 1–7; defects 1, 2 | C | C7, W-12 |
| S0147 defect 3 | M | — |
| S0148 1–6, 8; defects 1–6, 8 | C | C8–C12 |
| S0148 7 | JE | — |
| S0148 defect 7 | M | trivial |
| S0149 1, 3–6; defects 1–5 | C | C13 |
| S0149 2 | C | partly |
| S0149 7, 9 (events → invalidation); defects 6–8 | M | — |
| S0149 8, 10 | JE | — |
| S0150 1–3, 7, 8 | C | C14 |
| S0150 4 | JE | — |
| S0150 5 (four principles), 6 (Evidence Boundary Model); defect 1 (git/logs as authorship) | **M** | A-01 |
| S0150 defects 2–4 | JE (2, 3) / M (4) | — |
| Cross-source | C | except the authorship contradiction (M) |

## 5. Architecture view
- **What C25–C28 added.**
  - To the laws: K1's anchor is relative to its cut (CON-0243); L9 needs K6 and K1 durability (CON-0245); cross-record order is K2 plus a premise (CON-0251); and a K5 scope question (CON-0240).
  - One candidate primitive: a positive **hold state** ended only by disposition (CON-0250; D has no such state).
  - One assumption: a fixed **record identity**, which D cannot supply (CON-0239).
  - Everything else is method (CON-0242, 0253) or observation of one estate and largely one producer lineage (`bc1b47ef`): CON-0241, 0244, 0248, 0249, 0254 (ii), 0256.
- **Kernel or application, minimal, stable.**
  - K?: 0238, 0239, 0243 (core), 0245, 0250, 0251.
  - A: 0240 (migration), 0241, 0242, 0244, 0248, 0249, 0253, 0256.
  - D (known fact): 0254 (i).
  - Minimal: no (0243 bundled; 0162 redundant clause). Stable: those VALIDATED in §3.
- **Circular parts:** CON-0038 (A-06); SEQ-0012 (CON-0039); §2.5 ↔ §2.10 (A-21).
- **Unsupported parts:** §8.21's ledger claim (A-01).
- **Isolated parts:** CON-0256's scope is one lineage.
- **Trivial cases:** CON-0249 with a vacuous exclusion; CON-0250 "hold forever"; CON-0038 with the empty invariant set.

**Settlement C28, spot-checked against the book:** W-01–03, 06–09, 11–14 and 16 are present. W-04/05 are partial (A-08). W-10 is absent. W-15 is in C14, not C15 (A-01). The register counts (256: 40/118/98), R1 (232 rows, 1 inherited flag, CON-0133, out of scope) and the §8.21 quote check (5/0) all reproduce.

**Pattern checks:**
- Rule applied beyond its text: A-19.
- Missing premise: A-13, A-14, A-16.
- INVALID concept as a home: A-08.
- A source's self-issued status adopted: A-07 (SC-1).
- Measurement carried forward unexamined: A-18.
- Authority read through a registrar's gloss: A-07.

## 6. Next steps, in dependency order
1. A-01: restore or retitle §8.21; fix the settlement pointers. Done when R3 on the claim shows a home.
2. A-02 and A-05: restate CON-0256 and CON-0251. Then re-check §8.20 C8 and CON-0254, which rest on CON-0251.
3. A-03: one definition of "effective"; rebase CON-0063 in all six places.
4. A-04, A-06, A-11, A-12, A-10: Ch. 3 status and stale text.
5. A-07 and A-19: apply the C23 rule and A1's named readings to Ch. 2.
6. Add marks for CON-0238–0256 to BOOK-OUTLINE §3a, and handle the A-25 omissions.

## 7. Limits
- I did not read the Ch. 0–2 basis files (RS, RM, SC, SD, EV and others), S0300, or most corpus sources in full.
- Corpus spot-checks: S0016 L45–46, L82; S0024 L68–90; S0025 L150–155; S0131 L114–121; S0142 L230–234; S0146 L218–222. All paraphrases I checked held, apart from the three quote-check failures in A-23.
- I did not re-run containment_check.py. I did re-run the laminar count (1,940 families; 946 non-laminar forests; 0 laminar non-forests), which matches §3.10 C4.
- A search miss is reported as a miss.

## Summary (≤15 lines)
1. No critical findings; 25 findings, 6 high. Ch. 2: 13 defects; Ch. 3: 13.
2. §8.21 promises a claim (internal ledger) that is not in the body; the settlement's W-10/W-15 pointers are wrong.
3. CON-0256 fails as stated: a source-derived reference stored in the survivor meets its falsifier, and that is the estate's own design.
4. CON-0251 merges occurrence order with evidenced order; it is Lamport's happened-before, uncited.
5. CON-0038's criterion is circular; it becomes INVALID as stated.
6. Ch. 2's "effective" definition contradicts itself (lifecycle status vs standing) and §2.2; CON-0063 survives, re-based.
7. CON-0010's INVALID reason is stale against §3.5.
8. Stale text: F11's "three senses", CON-0057 "necessary", §3.3 "spaces NEST", THEORY-MAP §9.
9. INVALID concepts are still cited as homes (CON-0252, CON-0085).
10. VALIDATED by this review: CON-0239, 0240, 0242, 0245, 0253; second verdicts confirmed for 0001, 0047, 0056, 0063, 0064, 0092, 0093.
11. Coverage is good for C25–C27. C28 misses S0150's theory: the Evidence Boundary Model, the four principles, authorship evidence.
12. C25–C28 add one candidate primitive (a positive hold state), one assumption (record identity) and corollaries of K1/K2. The rest is method or one estate.
13. The BOOK-OUTLINE marks table has no row for CON-0238–0256.

## Files opened and searches run
- **Instructions:**
  - W/06-theory/manuscript/review/CONSOLIDATION-S0131-S0150/REVIEWER-NOTE.md, SCOPE.md, BRIEF.md
  - W/03-methodology/REVIEWER-INSTRUCTIONS.md
  - `ls -la` of the consolidation folder (names only)
- **Copy (BOOK-UNDER-REVIEW/06-theory/…):**
  - chapter_02, all 15 files
  - chapter_03, all 14 files
  - chapter_08: 08.00-index, 08.18, 08.19, 08.20, 08.21 in full; 08.13 L31–48; 08.17 (grep lines)
  - chapter_09/09.00-index in full; grep lines of 09.04, 09.09, 09.10, 09.12, 09.13 (incl. L127)
  - 01.06 L1–17 and L31–32; 01.14 L269–272
  - grep lines of 01.00-index, chapter_00/01 TODO, 05.04, 06.02, 06.03
  - THEORY-MAP.md: L13–58, L111–116, L128–132, L154, L294, L343–400, headings
  - CONCEPT-REGISTER.tsv, via my scripts
  - BOOK-OUTLINE.md: L5, L60–80, L180–260
  - `find` listing of the copy
- **Inventories and settlement:**
  - review/C25-S0131-S0135/, C26-S0136-S0140/, C27-S0141-S0145/, C28-S0146-S0150/ STEP1-INVENTORY.md
  - review/C28-S0146-S0150/SETTLEMENT.md
- **Corpus:**
  - CORPUS-ALLOWED.tsv (head and grep)
  - S0016 L45–46, L82; S0025 L150–155; S0024 L68–90; S0131 L114–121; S0146 L218–222; S0142 L230–234
- **Tools, all run with `python3 -I` on the copy:**
  - book_search.py `--help` (it returned an empty search)
  - register_home_check.py (R1)
  - undefined_terms.py (R2)
  - corpus_quote_check.py on Ch. 2, Ch. 3 and §8.18–§8.21
  - inbound_citations.py (R3) for: CON-0063, "effective = adopted", CON-0256, CON-0251, CON-0010 (twice), "internal ledger", CON-0085, CON-0252, CON-0038, CON-0056, CON-0011, F11, "act × scope × time", CON-0249, CON-0033
- **Greps on the copy:** AMS, AC-7, TODO-TRIAGE, "Effective =", "three senses", "ledger | Evidence ≠ Proof | not assured | outward", "S0140 L3x", "never a source", "manufactures authority", "nine criteria", §1.6 scope/period, Ch. 9 references to Ch. 2 and the forks, TODO IDs, statuses of cited CONs
- **Scratch scripts and outputs (all in /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/):**
  - cons_a_reg.py (register row extractor)
  - cons_a_laminar.py (laminar and forest count)
  - outputs: cons_a_ch3rows.txt, cons_a_newrows.txt, cons_a_r2.txt, cons_a_r3.txt, cons_a_820a.txt
- **Not reached:** no file of REVIEWER-NOTE item 4, no basis file, REPORT-B, PLAN.md or R3-OUTPUT.txt.
