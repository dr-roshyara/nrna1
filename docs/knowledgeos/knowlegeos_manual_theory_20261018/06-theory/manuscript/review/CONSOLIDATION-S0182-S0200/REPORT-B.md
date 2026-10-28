CONSOLIDATION S0182–S0200: REPORT B

Reviewer B (Sonnet 5.5). Frozen copy only (BOOK-UNDER-REVIEW). Notes are in /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/consolB/. Nothing in the repository was edited.

## 0. Summary (15 lines)
1. The four new sections hold up well against their sources. About 200 cited line ranges across S0182–S0200 and S0300 were re-read; no fabricated quotation was found, and the quote-check tool found 15 of 15 quotations it could test (8.27 has none; it cites "in meaning"). Commit times and subjects in §8.29 C3 and §8.30 C4 reproduce from git.
2. Model v20 reproduces: my run of `ce_model.py` is identical to `ce_model-run-20261028-v20.txt`, and v19 → v20 differs only by the five check-32 lines. Reproduction shows the plumbing, not the protocol. v20's "owner" is, by the book's own induction, the constant ROOT_OWNER, so check 32 tests a one-constant predicate.
3. Highest-severity findings: B-01 (§6.7 C1 states the opposite of its source and of §8.9 C9), B-02 (plain-language lines in §8.28 state absences broader than their own search scope), B-03 (§9.13.2 and §9.17 describe the v20 guard differently), and B-04 (CON-0121 VALIDATED on a premise the book itself lists as unverified, T8-02). All four are high.
4. No critical finding. Nothing found invalidates a chapter conclusion.
5. Evidence strength: the windows C34–C37 add instances within one estate and one lineage. All 25 claims in §8.27–§8.30 are labelled "(No new concept)", and the book itself counts them as one line of support. They cannot raise evidence strength; they function as a coverage ledger.
6. Ch. 8's purpose (validation or assurance governance, DB-4) is still undecided. §8.27–§8.30 are about the estate's reviewers, not about validating this theory.
7. SEQ: reading order is not forward-only. 27 of the 32 Ch. 8 files cite Ch. 9 constructs; Ch. 6 cites Ch. 8 and Ch. 9; the Ch. 6 index runs 6.7→6.1, the reverse of every other chapter index; Ch. 9 numbers two "theory turn" chains in one index.
8. Concept verdicts: 22 Ch. 6 and 96 Ch. 8 concepts. Second verdicts disagree on CON-0121 (→ PROVISIONAL). Four PROVISIONALs are re-derived as VALIDATED within scope (0147, 0176, 0059, 0128); 0127 is VALIDATED for its logical half only. Eight VALIDATED are confirmed with a stated scope narrowing.
9. Ch. 8 §8.02–§8.26 bodies were NOT read. Verdicts for their concepts rest on register rows, index rows and cross-references (marked "R" below). Ch. 8 §8.01, §8.27–§8.30, Ch. 6 and §9.17 were read whole.
10. Coverage: the inventories' most important misses are S0196's epistemic classes and bounded contexts (CBC-1..4), S0197's A–G reality classes, S0191's three-layer/sequencing changes, S0187's evidence-chain tension, and S0200's own concession that it would "move beyond complex numbers".

Process independence: I am independent in process only, not in person. I had no access to Report A. I did not open the working chapters, the working map or register, BASELINE-REVISIONS, old review texts (other than the four named STEP1-INVENTORY files), or manuscript git history. I did not compare files.

## 1. Findings table
Severity: critical / high / normal. "R3 lines" are from `inbound_citations.py --root …/BOOK-UNDER-REVIEW`. Section numbers are in the copy.

| ID | Section · chapter | Finding | Sev | Evidence (verified by me) | Correction | R3 lines | Resolved when |
|---|---|---|---|---|---|---|---|
| B-01 | §6.7 C1 · Ch.6 | The "Later evidence" bullet says: "So at the time the set was a governed artifact; S0089 classes such a diagram as evidence of registration." The sources say the reverse. S0090 L57: the four .puml and both READMEs are untracked. S0089 L81: "never committed, therefore not a governed artifact … evidence of NON-REGISTRATION, not … NON-EXISTENCE". §8.09 L77 states it correctly. Two sections contradict each other, and the book's own Ch. 8 rule on this distinction is broken in Ch. 6. | high | S0089 L81; S0090 L57; 06.07 L28; 08.09 L77 | "…the set was not a governed artifact; S0089 classes an untracked diagram as evidence of non-registration, not of non-existence." | claim-level: THEORY-MAP L242 ("§6.7 C1: … untracked", consistent with the correction); §8.09 L77 (consistent). Others section-only. | 06.07 L28 matches S0089 L81 and §8.09 L77. |
| B-02 | §8.28.1 items 1–2; §8.28.3 · Ch.8 | The plain-language text and the open-question line state absences wider than the body's search scope. Item 2: "the estate has no such mark today". §8.28.3: "the kernel attestation has no instance in the estate". Item 1: "nothing in the estate answers that from evidence yet". The body C2 says the opposite strength: for acceptance "none is reported in the sources read; that is a search result, not an established absence" (C35 W-10). Body C1: "No source in the window tests them". This breaks CON-0120, which the book VALIDATED. §9.17 L166 scopes it correctly ("no attestation of process identity"). | high | 08.28 L30–31, L62, L111; S0190 L25 | Scope each line: "in the sources read" / "in this window". | claim-level inbound to §8.28 C2: 08.29 L21, L51; 09.17 L166. No inbound line quotes the plain-language wording. | The three lines carry the body's scope. |
| B-03 | §9.13.2 designation bullet vs §9.17 R3 (c) item 1 · Ch.9 | Two descriptions of the guard disagree. §9.13.2's guard clause lists old store in force, new carrier never used, citing every attested maximal entry, and T. It says "across merged applications a fork is detected, not prevented". It does not mention the v20 refusal of a move not made by the old store's owner at append. §9.17 item 1 says "In v20 the guard therefore also refuses…", and (c′) says "prevented, within any kernel that holds the record". §9.13.2 mentions the owner only in D's checks. The passage was changed by the same settlement. | high | 09.13 L48; 09.17 L166, L204 | Add the owner refusal to §9.13.2's guard clause, with the declared-application condition. | R3 has no pattern for "§9.13.2" (0 hits, home "—"). Grep for T9-10: TODO L20, THEORY-MAP L195–196, register CON-0264 row, 09.17 (five lines), 09.99 L73–75. | Both passages state the same guard. |
| B-04 | §9.17 R3 (c) D clauses · Ch.9 | L173: "With this last clause a single foreign move in a merged record is detected". Unqualified. Limit (ii) (L166, L204) and the model (check 32 prints it) say a foreign kernel that declares the owner's application passes. The probe shows only that a foreign move declaring its own application is caught. | normal | 09.17 L166, L173, L204; run of v20 | Add "if the mover declares its own application". | Same as B-03. | L173 carries the condition. |
| B-05 | T9-10 · Ch.9 TODO | Status "Resolved" and "completion condition met". The Principal's requirement is "prevented, not only detected", and prevention holds only under a declared-application premise. By the book's induction the owner predicate is constant, so check 32 shows the refusal plumbing but not an ownership protocol. T9-11 carries the gap, but the T9-10 status does not. | normal | 09.17 L166 (i), L204; ce_model.py L129, L262–266; run output | State "Resolved as a design sketch; prevention conditional on a declared application (T9-11)". | TODO L20. | Status wording changed, or the premise moved into the completion criterion. |
| B-06 | §8.30 C5 · Ch.8 | The attack on S0200 reads "semantic state" as content (home §1.4) and says S0200 "merges what it claims to keep apart". S0200 itself defines S as "semantic validity" (L330) and uses S as a high/low quality grade ("authoritative but incomplete/ambiguous", L197–202). So the merge claim holds for A (approved/proposed/rejected positions, L91–103; but A is also "how much authority", L86) and not for S. The book omits S0200's own concession that it would "move beyond complex numbers" to a 5-vector, with the complex form "a special two-dimensional projection" (L313–345). The "nothing complex-specific" point is conceded by the source. The attack is therefore partly on a form the source gives up. | normal | S0200 L36–46, L86, L91–103, L197–202, L313–345 | Restate S as validity (ordinal). Limit "merge" to A. Record the concession. | claim-level: 08.99 L2; THEORY-MAP L201, L370. | The three points are restated. |
| B-07 | §8.30 C4 (V line) · Ch.8 | The V line contrasts the commit subject "P4 gate OPEN conditional" with the text "remains CLOSED". The full texts agree: the subject is "P4 gate OPEN conditional on P3-F1 bounded evidence completion" (git `c673de5de`); the text says "P4 gate remains CLOSED until this lands" (S0198 L52); the header says "may open once the P3-F1 bounded completion is recorded" (S0198 L3–5). The book quotes truncated fragments and shows no inconsistency. | normal | git log -1 c673de5de; S0198 L3–5, L52 | Quote in full or delete the contrast. | R3 "§8.30 C4": 0 inbound. | The V line quotes whole phrases. |
| B-08 | §8.27 lineage · Ch.8 | "[S0182] names six files that are not on the allowed list (S0182 L39–44)". L39–44 are six rows (01–06) whose linked files are S0178, S0176, S0177, S0180, S0181, S0175, which the book read at the previous consolidation (07 = S0179 is at L45). What is not allowed are the brainstorming items in column 4. One of these maps to S0161 in 02-FILES.jsonl (path only; see §7). | normal | S0182 L39–45; 02-FILES.jsonl | "…whose fourth column names corpus files not on the allowed list". | Lineage paragraph; no claim cites it. | Sentence restated. |
| B-09 | §8.27.1 item 2; C2 · Ch.8 | The plain-language text says an act "must be recorded if it did". The sources give a rule about how absence is judged ("a live but unregistered act still counts as missing", S0184 L96; S0185 L108), not an obligation to record. The "iff" in C2 is a fair conjunction of the two conditionals; the "must" is not in the sources. | normal | S0184 L96; S0185 L108 | "…and counts as missing if it is not". | claim-level inbound: 08.28 L69; 08.29 L21, L37, L49. | Wording changed. |
| B-10 | §8.28 C1, first limit (W-03) · Ch.8 | "Criteria 1 and 2 presuppose that EKS and PKS are two independent domains." Criterion 2 ("would a future knowledge domain need it?") does not. Only criterion 1, and S0191's test (L465–467), do. The limit overreaches. | normal | S0187 L734–757; S0191 L465–467 | Limit the presupposition to criterion 1 and S0191's test. | claim-level: T0-03b (Ch.0 TODO L27); 08.30 L19, L50, L56; THEORY-MAP L199. | Limit restated. |
| B-11 | §8.28 C2 · Ch.8 | "S0190 is a direct estate instance of that premise failing." S0190's INV-ATTR-1 is about an AI session's identity. The premise at issue is a declared moving application in the model. The book does not show application = process. This is an analogy, not a direct instance. | normal | S0190 L25, L103; 08.28 L62 | "an analogous instance (process identity, not application)". | 08.29 L21, L51; 09.17 L166. | "direct" removed or application = process shown. |
| B-12 | §8.00 index row for §8.29 · Ch.8 | The row cites "a view is never authority (C7)". §8.29 has C1–C6 only. The phrase occurs nowhere else in the copy. A draft claim was dropped and the index was not updated. | normal | 08.00 L43; section C-list | Delete the clause. | "§8.29 C7": 0 claim lines. | Row matches C1–C6. |
| B-13 | Position text · map and outline | THEORY-MAP L374 says "8 (§8.1–§8.26)"; BOOK-OUTLINE L183 says "§8.1–8.21". Ch. 8 now runs to §8.30. The register counts (265/47/122/96) are correct. | normal | THEORY-MAP L374; BOOK-OUTLINE L183 | Update both. | map lines. | Ranges match. |
| B-14 | Register basis column · both | 18 of 119 rows in scope still say "not yet reviewed" in `basis`, while `reviewed` and `history` record reviews: CON-0102, 0103, 0104, 0106, 0108, 0112, 0119, 0120, 0129, 0130, 0132, 0133, 0139, 0140, 0141, 0164, 0166, 0172. CON-0176's meaning says a property is readable "exactly when it is constant on f"; §6.7 C7 (c) says "constant on every fibre". CON-0119 and CON-0122 are INVALID with a valid core named in text ("valid as two axes"; core "PROVISIONAL") but no registered ID. Likewise 0073, 0085, 0086, 0088. | normal | register read with `csv`; 06.07 C7 | Synchronise the basis column and CON-0176. Register the partly valid cores or state that they are unregistered. | not applicable | Basis matches history. |
| B-15 | §6.6 lineage, Ch.6 index · Ch.6 | The lineage line says "S0066 (a developer guide, undated)", and the next paragraph says "It is not undated". Stale heading. The Ch. 6 index lists §6.7 first and §6.1 last; Ch. 0, 5, 8 and 9 ascend. | normal | 06.06 L7–13; 06.00 | Fix the heading; reorder the index. | claim-level: none found for the index. | Both fixed. |
| B-16 | §8.29.1 item 3 · Ch.8 | "A protected file could be written through a shell command that the check does not see." S0196 L494 says a Bash heredoc bypasses the `ask` gate, deferred; protection is by filename pattern. The summary does not name the `ask` gate. | normal | S0196 L494 | Name the gate. | §8.29 C5: 1 claim line (08.99 L2). | Wording scoped. |
| B-17 | Ch. 8 and the book's purpose · architecture | §8.27–§8.30 (446 lines) yield no concept and 25 claims labelled "No new concept"; every agreement is "within the estate's lineage, not independent support". Reading more of this chain adds instances, not strength. Ch. 8's own header concedes it is undecided whether the chapter is a validation or an assurance-governance chapter (DB-4). | normal | 08.00 closing note; 08.27–08.30 | Decide DB-4. Meanwhile record windows C34–C37 as a coverage ledger. | not applicable | DB-4 decided or the ledger status recorded. |

The book's source labels were mostly right where I checked. All quotations and line references I re-read held, except B-07 (truncated), B-08 (attribution) and the strength points B-02, B-09 and B-11. The "V (Professor, git)" statements in §8.29 C3 and §8.30 C4 hold. The times are 22:37:51→22:46:03 (8 min) and 23:09:57→23:24:22 (14 min), with the erratum at 23:30:56 (6 min after S0199) and the later ruling `61219780e` at 2026-08-22 03:21.

## 2. Concept verdicts
Method codes: S = section body read and checked; R = register row, index row and cross-references only (section body not read). A "confirmed" PROVISIONAL means: no demonstrated defect found; it is not an independent justification.

### 2.1 Ch. 6 homes (22)
| ID | Verdict | Reason |
|---|---|---|
| 0072 | VALIDATED (second) | A definition: product-specific input contract β. Scope: methods with a declared contract. The extraction hypothesis (the product enters M only through β) is untested and not part of the concept. S. |
| 0075 | VALIDATED (second) | {0,1}² componentwise is a lattice; so is [0,1]²; tiers 2 and 3 are incomparable (all re-derived); distance is ordinal. S. |
| 0087 | VALIDATED (second) | A distinction (protocol versus records). Limit kept: canon assigns only the records. S. |
| 0089 | VALIDATED (second) | Core/supporting is relative to a product. Support is canon's own counterfactual, a conditional, and this is standard DDD usage. Scope stated. S. |
| 0102 | VALIDATED (second) | Stated ≠ held ≠ enforced; "held" homonym noted in the text. S. |
| 0104 | VALIDATED, scope narrowed | Describes what S0039 defines. The authority-flow half rests on AIP-14, whose standing is T0-02 and T0-17 (disputed). Not a general law. S. |
| 0108 | VALIDATED (second) | True once "without error" means no false pass and no false block; then it is analytic. A restatement, not a discovery. Scope: blocking enforcement. S. |
| 0103 | PROVISIONAL | The criteria overlap; "exercised as one programme" is history, not structure; S0036 counts "one capability" as five. S. |
| 0132 | PROVISIONAL | "The reason lives in" is not operational. The operational reading (holder of the amendment act) fails on S0091's joint owners BC-7/BC-5. §8.29.3 and §8.30 C3 rest on it. S. |
| 0140 | PROVISIONAL | A candidate list from one source; completeness is Hyp. S. |
| 0147 | VALIDATED within scope | Testing a direction law on a view needs a partition and typed dependencies. Necessary and true, and one clear counter-arrow refutes while confirmation needs all arrows typed. Scope: diagram testing. S. |
| 0172 | PROVISIONAL | The section itself says it is §1.10 plus §1.16.6. Minimality unresolved. S. |
| 0176 | VALIDATED (math (a)–(c)) | Checked: a value-only check confirms exactly when the fibre is a singleton; comparing intermediate levels is sufficient, not necessary; a property of the inputs is readable iff constant on every fibre. Needs the register wording fixed (B-14). The kernel-law reading stays Hyp. S. |
| 0073, 0074, 0085, 0086, 0088 | INVALID confirmed | 0074 → 0075 (registered). 0073, 0085, 0086, 0088: restated in §6.1 C1, §6.2 C1, §6.3 C7 and §6.3 C5 respectively; no register ID for the cores (B-14). S. |
| 0133, 0134, 0139, 0146 | INVALID confirmed | 0133 merged into ARCH-INV-1; 0134 → 0141; 0139 → 0172; 0146 → 0176. S. |

### 2.2 Ch. 8 VALIDATED (16): second verdicts
| ID | Verdict | Reason |
|---|---|---|
| 0120 | VALIDATED (second) | "Needs its search scope" is right. "Exhaustive" must be read relative to the declared scope. §8.30 C1 extends it to readings, as Int. R. |
| 0121 | **PROVISIONAL (disagree)** | A prescriptive method rule. Register: "generality Hyp (one case)". Its benefit depends on factor → blind-set overlap, which T8-02 lists as unverified. Not justified within a stated scope. R. |
| 0126 | VALIDATED (second) | In a per-defect deterministic model the disagreement set is B(a) Δ B(b); no defect missed by both can be reported. Re-derived. "⊇" absorbs idealised joint outputs. R. |
| 0129 | VALIDATED (second) | A sufficient condition. Trivial but correct. Application rule, not kernel. R. |
| 0153 | VALIDATED (second) | A distinction; the mapping to the boundary test is correctly demoted to CON-0178. R. |
| 0208 | VALIDATED, scope narrowed | Holds as the authority's decision (S0120 L15), not as a general law of existence. It leans on CON-0199 (Hyp) and 0213 (PROVISIONAL). R. |
| 0209 | VALIDATED, clause 1 only | Blocking response decides category by consequence. R. |
| 0219 | VALIDATED (second) | A label that crosses between acts cannot identify a decision. R. |
| 0220 | VALIDATED (second) | The gap arises from requiring the reference. R. |
| 0226 | VALIDATED (second) | Definitional core only. R. |
| 0232 | VALIDATED (second) | Scope: which text governs a decision; authority's words. R. |
| 0239 | VALIDATED, scope narrowed | Register records reviewer B as PROVISIONAL. §9.17 L85: M2 is "a model, not an observed case". Valid for the model. R+S (9.17). |
| 0240 | VALIDATED (second) | Fixed decoder: bytes ⇒ identity; the converse fails. Trivial. R. |
| 0242 | VALIDATED (second) | Checkable ≠ checked. R. |
| 0245 | VALIDATED (second) | The lost update is a standard concurrency possibility; the 23-of-30 figure is one measurement relayed twice (§8.30 C2), not shown. R. |
| 0253 | VALIDATED (second) | Rediscovering known defects shows reach, not detection. R. |

### 2.3 Ch. 8 PROVISIONAL (59), by verdict
- **VALIDATED within scope (3):**
  - 0059: non-sufficiency of "no domain vocabulary" for reuse (logic plus one counterexample, S).
  - 0127: for the logical half only (a recorded shared factor refutes; an empty record cannot establish, given a truthful record). "Never PASS" is policy.
  - 0128: likelihood ratios multiply under conditional independence, and not otherwise. Standard probability, re-derived.
- **PROVISIONAL, confirmed (56). R unless noted. Defect-free so far; reason in brief:**
  - 0058, 0060 (S): n=1, self-review; contamination needs the brief to supply the object.
  - 0125: nine candidate factors; the bridge to overlap is T8-02.
  - 0130: the branching mathematics (extinction a.s. iff m ≤ 1, non-degenerate) is right, but the fit to correction chains is untested.
  - 0141: needs a fixed fold; the interpreter is swappable (§6.5 C4).
  - 0150, 0151, 0152, 0154, 0156, 0158: scoped, one line or Hyp-as-law.
  - 0164, 0166, 0178: restated forms; 0164 overlaps RC-9 (§1.10).
  - 0179, 0180, 0181: restated forms, one case.
  - 0195, 0196, 0199, 0200, 0201: an estate act or Hyp.
  - 0203, 0204, 0206, 0207: Src plus Int; 0207 is one case.
  - 0211, 0212, 0213, 0214, 0215, 0216, 0217: 0217 has one producer testing its own model.
  - 0221, 0222, 0223, 0224, 0225, 0228, 0229, 0230, 0231.
  - 0233: figures reported, not reproduced.
  - 0236, 0237, 0238, 0241, 0243, 0244, 0248, 0249, 0250.
  - 0251: needs the no-shared-order premise.
  - 0254: (i) a hash cannot feasibly cover itself is standard; "a fixed point exists in principle" holds only with probability about 1−1/e for an ideal hash, so say so. (ii) is an estate design choice.
  - 0257, 0259.

### 2.4 Ch. 8 INVALID (21): cores checked as restated (register, not re-litigated)
- 0106 → 0164; 0112 → 0166; 0155 → 0179; 0157 → 0180; 0159 → 0181; 0197 → 0200; 0198 → 0201; 0202 → 0238; 0205 → 0207; 0210 → 0214; 0218 → 0224; 0227 → 0230; 0234 → 0237; 0235 → 0236; 0246 → 0248; 0247 → 0249; 0252 → 0254; 0255 → 0256 → 0257. All confirmed (R).
- 0119 and 0122: INVALID confirmed; the partly valid core is unregistered (B-14).
- None of the Ch. 8 homes lie in §8.27–§8.30; the scope's "none" is correct.

## 3. Coverage (against the four inventories)
| Item | Status | Home / note |
|---|---|---|
| S0184/S0185 independence-gate refusal; eligibility from record, not prompt | COVERED | §8.27 C1 |
| S0183 final durable-copy check; termination argument; record existence ≠ authority | COVERED | §8.27 C4, C5; §8.20 |
| S0183 criteria 12/15, DV-6 launder, quarantine, OPEN-M7/DISPOSE | COVERED | §8.20 |
| S0185 graded bars (Flag AC) | COVERED, partly | §8.27 C1 only on one name |
| S0182 two-axis tags (claim level × status class); S0182 vs S0186 status vocabularies unmapped | MISSING (low) | Related: §8.25 C7, §8.26 C1 |
| S0186 system of record; projections; generations G0–G3; self-declared identity | COVERED | §8.27 C6; §8.28 C2 |
| S0186 four boundary candidates; "scripts are mechanisms, not services" | MISSING | For T0-03b |
| S0187 kernel criteria; mechanism-not-meaning; candidates | COVERED | §8.28 C1, W-04 exclusion justified |
| S0187 evidence chain "core of the kernel" against "Decision above kernel" (L291–305 vs L637) | MISSING | T0-03b |
| S0188 grants figures, stale relay; two delegation statuses; durable vs version-controlled | COVERED | §8.28 C4, C8 |
| S0188 Rule ↔ Grant not connected (RM-3) | COVERED, elsewhere | §1.15 and Ch. 1 (R) |
| S0189 evidence hierarchy; two loop orders; deferral register | COVERED | §8.28 C5–C7 |
| S0189 "not all persistence is domain state" (JSONL stream, git commit as evidence) | MISSING (normal) | Bears on §8.27 C6 |
| S0191 revision of S0187; convergence matrix problems | COVERED | §8.28 C1, C8 |
| S0191 three-layer structure (AIP possibly a consumer); ".claude is not the kernel"; stop EP-01 | MISSING | T0-03 / T0-03b |
| S0192/S0193 not-supplied act; absence as honest record; Route A/B; threefold pattern | COVERED | §8.29 C1 (routes not named) |
| S0194 corpus boundary; contamination vector; hierarchy tiers | COVERED | §8.29 C2, §8.30 C1 |
| S0194 A–G reality classes with no promotion; seven EKS/PKS options; teleological closing line | MISSING (normal) | Same A–G in S0197 L65; relevant to the book's label discipline |
| S0195 re-measurement; erratum; Q5 | COVERED | §8.29 C3, §8.29.3 |
| S0196 specified-not-realized; M4 axes; X-02; internal defects | COVERED | §8.29 C4, C6, §8.29.3 |
| S0196 epistemic classes (Observed/Measured/Derived/Synthesized/…; "a derivation may not classify as Observed"); CBC-1..4 bounded contexts; concept economy | MISSING (medium) | Candidate for T0-03b and for the Src/Ded/Int labels |
| S0197 phase firewall; quarantine of target architecture; corpus-boundary rule | COVERED, partly | §8.30 C1, C4 |
| S0198/S0199 P3-F1..F4; density ≠ completeness; one-writer | COVERED | §8.30 C1–C3 |
| S0199 count series; "0 of 110 grants carry seq" | COVERED, partly | §8.30.3 |
| S0200 K = S + iA; elegance warning; falsifier | COVERED, DISTORTED in emphasis | §8.30 C5; see B-06 |
| S0200 five incompatible facet lists; contradictory final figure; 5-vector concession | MISSING (low) | |
Ranking of the misses: (1) S0196 epistemic classes and CBC-1..4; (2) S0191 three-layer/sequencing; (3) S0187 evidence-chain tension; (4) S0194/S0197 A–G classes; (5) S0189 persistence forms; (6) S0182 dual tags.

## 4. SEQ order
| Chapter | Problem |
|---|---|
| Ch. 0 | About 64 pointers to Ch. 1–6 (counted by the `§N.M` scan). Acceptable for a foundations chapter if labelled as pointers. Not checked one by one. The I-n numbering collision (T0-01) means a bare "I-9" is ambiguous. |
| Ch. 5 | Internal order fine (2 forward refs). §5.4–§5.7 cite Ch. 9 (§9.1, 9.9, 9.10, 9.13) and Ch. 8 (§8.5–§8.10): the gap operator and the record sections use constructs introduced later. |
| Ch. 6 | The index runs 6.7→6.1 (B-15). §6.5 uses CON-0141 (§8.8), F22, §9.4, §9.6.7; §6.6 uses §9.5, §9.6; §6.7 uses §8.9, §9.6. 39 forward refs to Ch. 8/9. §6.2→§6.3 and §6.4→§6.5 are forward within the chapter. |
| Ch. 8 | Order is by reading window, not dependency. 27 of 32 files cite Ch. 9 constructs (K1–K7, D, CE*); 401 `§9.x` citations; heaviest: §8.18 (53), §8.19 (48), §8.12 (38). The index note covers §8.18–§8.21 only; §8.22–§8.30 cite §9.13, §9.15–§9.17 as well. Intra-chapter forward refs (e.g. §8.5→§8.6→§8.10) are 26. |
| Ch. 9 | The index carries two "theory turn" chains: §9.6–§9.9 as turns 1–4 and §9.10–§9.17 as turns 1–8 "of the critical-path chain". §9.6–§9.9 use Req, I_T, I_R and the evaluator before §9.10–§9.13 build them (T9-05 admits they are informal). 109 forward refs inside the chapter. |
A forward-only reading order would put Ch. 9 §9.10–§9.17 (kernel type, evaluator, trust boundary, designated record) before Ch. 8 §8.18–§8.30, or move those sections after Ch. 9.

## 5. What I verified, and what stays unproven
Verified (re-read myself):
- Citations in §8.27 (S0182–S0186), §8.28 (S0187–S0191), §8.29 (S0192–S0196), §8.30 (S0197–S0200, plus S0300 L379, 454, 1203–1204, 1229, 1286, 1315).
- v20 run; the Ch. 6 §6.7 C7 mathematics; register totals (265/47/122/96) and scope lists (they match the register exactly); the §8.00 index against section claim lists.
Reported, not shown:
- Lines I did not re-read: S0187 L81–103, L172–221; S0188 L91–109 (header only), L288–302; S0189 L82–137, L445, L818–834, L1010–1048, L1822–1838; S0191 L162–298, L436–444, L562–578; S0196 L85, L111–113, L125–127, L743, L164, L409, L490 (forbidden-file or unneeded). I treat these as unverified.
- The §8.28 C4 recount (142 grants, 0/144) and "reported 126 grants" were checked against §1.13 text only, not the live record.
Unproven by the book's own evidence:
- Any claim of a mechanism against the estate that rests on one chain relayed by sources of one lineage.
- That the v20 guard prevents conflicting moves (it detects those declaring their own application).

## 6. Limits (what I did not cover)
- Ch. 8 §8.02–§8.26 bodies (about 3,100 lines) were not read; findings there are limited to structure, index and cross-reference checks. Chapters 0, 5, 9 were read only for the passages in scope and the SEQ scan.
- Concept verdicts for Ch. 8 PROVISIONAL (56 of 59) and INVALID rest on register rows; "R" says so.
- R2 (`undefined_terms.py`) printed 687 candidate lines; I did not judge each. A sample (pinning, adviser, serialised, unattested) are used as plain words or defined nearby; not individually checked. R1 (`register_home_check.py`): 241 rows with a section home, 1 flag (CON-0133, H4: tag carries no status, register INVALID, text "merged into ARCH-INV-1"), 5 untagged homes.
- The architecture view (primitives → laws → results) was not assessed beyond §9.17 and the scoped passages.

## 7. Files opened, searches and commands
**Access flag.** I did not reach any file in REVIEWER-NOTE item 4 by design. One disclosure: to map "S0182 L39 col. 4" names to source IDs, I grepped `02-FILES.jsonl` and it returned the path of one file I know to be S0161 (`docs/knowledgeos/brainstorming/20260819-204018-kos-product-architecture-v2.md`) and a list of S0083–S0086, S0175–S0182 paths. Path and ID only; I did not open S0161. `02-FILES.jsonl` is not in REVIEWER-NOTE item 4. I did not open `CORPUS-ALLOWED.tsv` (not under W); I relied on the book's own reading history for allowed status of S0028, S0031, S0067, S0071, S0078, S0089, S0090, S0300.

**Files opened (W = …/docs/knowledgeos/knowlegeos_manual_theory_20261018):**
- Instructions: W/06-theory/manuscript/review/CONSOLIDATION-S0182-S0200/{REVIEWER-NOTE.md, BRIEF.md, SCOPE.md}; W/03-methodology/REVIEWER-INSTRUCTIONS.md. (FREEZE.md and README-PROFESSOR.md appeared in `wc -c` output only; not opened.)
- Copy (…/CONSOLIDATION-S0182-S0200/BOOK-UNDER-REVIEW/06-theory/): manuscript/chapter_06/06.00–06.07 (all, in full), chapter_06/TODO.md; chapter_08/08.00, 08.01, 08.27, 08.28, 08.29, 08.30 (full), 08.99, TODO.md; chapter_09/09.17 (full), 09.13 (L48), 09.00-index, TODO.md; chapter_00/TODO.md, 00.00-index (index rows), 00.09 (L1–30); chapter_05/05.00 (index rows); chapter_01/01.13 (greps); THEORY-MAP.md (greps and L360–384); manuscript/BOOK-OUTLINE.md (greps and L195–215); CONCEPT-REGISTER.tsv (python, csv).
- Whole-copy reads by script only: all `chapter_*/NN.NN-*.md` files (cross-reference check, forward-reference scan, index-versus-section check, per-section Ch. 9 counts); grep over chapters 00–09, register and map for the coverage strings.
- Tools and logs (run from W, read-only): tmp/tools/{register_home_check.py, undefined_terms.py, corpus_quote_check.py, inbound_citations.py}; copy's tmp/tools/book_search.py; tmp/tools/ce_model.py (copied to scratch and run); tmp/tools/ce_model-run-20261028-v19.txt, -v20.txt (diff).
- 02-FILES.jsonl (python read for S-ID → path).
- Corpus sources (selected line ranges via sed; S0200 whole): S0182, S0183, S0184, S0185, S0186, S0187, S0188, S0189, S0190, S0191, S0192, S0193, S0194, S0195, S0196, S0197, S0198, S0199, S0200; also S0300 (KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md), S0028 (…architecture-baseline.md L104, 131–133, 257–259), S0031 (…architecture-consolidation.md L13, 130–152, 240–241), S0067 (L9, 57, 65), S0071 (L11, 42–50, 262), S0078 (L201–208), S0089 (L81), S0090 (L57). All under /home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/.
- Allowed by name: …/06-theory/manuscript/review/C34-S0182-S0186/STEP1-INVENTORY.md (whole), C35-S0187-S0191/STEP1-INVENTORY.md (first 140 lines), C36-S0192-S0196/STEP1-INVENTORY.md (first 120), C37-S0197-S0200/STEP1-INVENTORY.md (first 110).

**Commands and searches (all read-only):**
- `mkdir -p` the scratch directory; `wc -c`/`wc -l`/`find`/`ls` on the copy and tools.
- `python3 -I tmp/tools/register_home_check.py --root <copy>`; `python3 -I tmp/tools/undefined_terms.py --root <copy>`; `python3 -I tmp/tools/corpus_quote_check.py` on 08.27, 08.28, 08.29, 08.30 of the copy.
- `python3 -I tmp/tools/inbound_citations.py --root <copy>` for: "6.7 C1 untracked governed artifact", §6.7 C1, §8.29 C7, §8.29 C5, §8.29 C3, §8.29 C2, §8.29 C1, §8.30 C5, §8.30 C4, §8.28 C1, §8.28 C2, §8.28 C4, §8.27 C1, §8.27 C2, §9.13.2, §9.17 R3, T9-10, CON-0121, CON-0104, CON-0176, CON-0132, §8.00.
- `python3 -I <copy>/tmp/tools/book_search.py` for: "a view is never authority", "claim level status class tag", "DISPOSE transition", "jointly unsatisfiable criteria 12 15", "launder quarantine move", "Flag AC barred only where conflict remains", "migration mechanical write governance append", "GovernedWork", "Knowledge Port adapter", "Rule Grant not connected RM-3", "keep-out list", "three loops runtime knowledge governance", "ChangeSet", "Deferred Architecture Register", "eligible set narrowing exhaustion", "four boundary candidates Governance Evidence Knowledge Decision".
- `grep -rE` over chapters 00–09, register and map for: DISPOSE, OPEN-M7, launder, criteria 12, Flag AC, mechanical write, keep-out, RM-3, Knowledge Port, Claim level, claim level, boundary candidates, narrowing, residual window, placement is forced, Observation → Evidence, evidence chain, above kernel, idle since, criterion 12/15, unsatisfiable, evidence-not-authority, ESTABLISHED, Rule Port, convergence, S0191, no grant references, feeds authority, JSONL, not an aggregate store, Domain Kernel, Assurance Kernel, vector, Route A/B, epistemic class, Derived.*Observed, EP-01, three-layer, Synthesized, CBC-, closed verdict, AP-1, reality class, seven options, forward-only supersession, ZERO-INDEPENDENT, R-CONFLICT, "governed artifact", "untracked", "§8.1–§8.26", T9-10, "§9.13.2".
- python scripts (inline): register totals and scope-list comparison by chapter; register `basis` "not yet reviewed" count; Ch. 8 index claim-list versus section C-list; `§N.M C#` target existence check; forward-reference scan (`§N.M` with N.M later than the citing section); Ch. 8→Ch. 9 per-section count; Ch. 9 §9.6–§9.9 later-section citations; S-ID path lookup in 02-FILES.jsonl.
- `git log -1 --format='%h %ad %s' --date=iso` on db9154664, 999ede26b, e5f98a6ad, c673de5de, 6034db5c0, 61219780e, 0d6fb1fca.
- `diff` of ce_model-run-20261028-v19.txt and -v20.txt; `cp` of ce_model.py into scratch; `python3 -I ce_model.py` (scratch copy; its output diffed against the stored v20 run: identical).
- `sed -n`/`cat` with line numbers on the corpus sources listed above.
- Files written, outside the repository, in scratch: r1.txt, r2.txt, quote.txt, ch6.txt, ch8.txt, xref.txt, r3_*.txt, model/run.txt.
