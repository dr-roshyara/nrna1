C80 REVIEW REPORT

Window reviewer W (Opus). Transition review, step 2, of §8.73 (draft), its row in the §8.00 index (L89), and `STEP8-AND-CHECKS.md`. I re-read my saved inventory first; its sha256 `f3608c30…bf03cc` matches `SHA256-STEP1.txt`. Independence here means process independence, not personal independence.

## Summary (10 lines)
1. §8.73 is reliable within its scope on the facts: the order of the appends, the PASS verdicts, the relay that narrows, and the open provenance and durability points. I found no critical defect.
2. Largest risk: C1 misses the book's own closest home. §8.29 C1 records processes refusing to append START as "human" ("a chat instruction is not a human START"; "nor its own starter").
3. C3 states that the verifier "does not decide" V-1's wider reading. The source shows the verifier repeating the author's justification and calling the field "now truthful", so it did decide in effect.
4. C5 calls the verifier's `false` "the scoped sense of §8.71.3". §8.71.3's scope is a grant scope. L165's scope is the commission's purpose in prose, which is the §8.72 C4 kind of value, not the scoped one.
5. C5's "the HANDOFF ends the author's lane" is a fact about the record. Reading it as *the record of the author's end* is a normative identification that §8.72 C4 left to the Principal or the PO/ARB.
6. C2 says reading (ii) "fits better", while §8.73.4 says the question is undecidable. The better evidence for (ii) is the appointee's own S0327 L112 and L119–120.
7. C2 misses three things: the ruling defers to "checks already passed by the candidate" (S0328 L10); the precedent's scope, "for the Architecture correction" (S0323 L14); and "fresh" again not addressed.
8. Quote and R2 problems are minor: one rearranged quote and one paraphrase that changes "false" to "not shown". R2 tier D was not reported in STEP8.
9. The proposed R3 on §8.72 C4 is acceptable once restated (W-05).
10. Coverage of my inventory: 24 items COVERED, 6 MISSING, 3 DISTORTED, 3 JUSTIFIABLY EXCLUDED.

## Findings

| W-nn | Sev. | Type | Claim | Evidence | Correction | R3 lines |
|---|---|---|---|---|---|---|
| W-01 | high | omission | C1, against the estate's earlier refusals | §8.29 C1 (L39–43): processes refused "appending START as 'human'"; "a chat instruction is not a human START" (S0192 L64, in meaning); "The reviewer must not be its own registrar, nor its own starter" (S0192 L78). S0190 L115 quotes the same rule. S0328 L51 bases the START on "the human (PO/ARB, in-session) issued the decision". | Add §8.29 C1 to C1's homes and to its "earlier refusals". State that S0328 does what §8.29 C1 records as refused, on an in-session decision that the earlier rule says is not a human START. The humanAct cited is recorded only in the appointee's own registration. "No law forbids it" stays true, because §8.29 C1 is Src. | 08.73 L16, L42, L105–106; 08.00 L89 |
| W-02 | high | defect | C3, "Agreement: the verifier does not decide it" (L66) | S0329 L66 gives "reports `false`, because the documented contract defines the field `=ACTIVE⇒true`". That is S0325 L48's justification, which §8.72 C2 Ded showed false: the contract is one-way and the `false` follows from the code. S0329 L96 says the field is "now *truthful*". S0329 L45 shows the verifier read the correction evidence. | Restate. The verifier records the semantics for the adoption decision (L127), yet classifies the case as "not a finding", gives V-1 PASS on the narrow reading only, and repeats the author's justification. That shares an information-lineage factor (§8.06 C1) on exactly the disputed point. V-1 PASS is PASS under the narrow reading; §8.72 C2's two readings stay open. | 08.73 L26, L66; 08.00 L89 ("V-1's wider reading left to the adoption decision") |
| W-03 | high | omission | C2 (and C3's independence) | S0328 L10: "subject to the ordinary independence checks already passed by the candidate". These are the candidate's own conditions 7–8, "on the evidence available" (S0327 L67–68). S0320 L76 asks for a "fresh independent verifier". §8.71 C1 reads "fresh" as no prior participation. S0328 L22 discloses the refusal as prior participation. | Add the following. The ruling's independence ground is the appointee's self-check (§8.06 C3: "a process cannot attest its own separation", S0091 L257). "Fresh" is again not addressed (as at §8.71 C1, W-03 there). After seq 4–6 the verifier also has governance-recording participation on its own lane, before it verifies. | 08.73 L17, L52–54 |
| W-04 | high | defect (ubiquitous language) | C5, "That is the scoped sense of 'authorized' (§8.71.3) … closer to the mechanism than S0326's unscoped `false`" | §8.71.3 L127: the scoped sense is "with `--scope`, within a grant's scope". S0329's snapshot (L28–35) shows no scope and `authorized_to_act = true`. L165's "lane scope = verification of V-1/V-3/V-5" is the commission's purpose in prose, not a grant scope. §8.72.4 (C4's rival answer): the field name used "for a value the mechanism does not compute". | Restate. L165 is a prose judgement under the mechanism's field name, like S0326 L77. It differs only in stating its qualifier and in keeping capability true (L164). It is not §8.71.3's scoped sense. Withdraw "closer to the mechanism". | 08.73 L20, L89 |
| W-05 | high | defect (normative point presented as decided) | C5 and the proposed R3 on §8.72 C4 ("the end of the author's work was later recorded by the HANDOFF") | §8.72 C4 L95: which transition should record the end, and whether it should, "is a normative point for the Principal or the PO/ARB". The HANDOFF (S0328 L63) transfers mutation ownership to the verifier. It was appended by the receiving process acting as Governance, after S0326, and it has its own purpose. | Restate the R3 as follows: "A later HANDOFF (seq 5), appended for the verifier's lane by the verifier acting as Governance (§8.73 C1), moved the author's lane to HANDED_OFF (S0329 L20). This removes §8.72 C4's stale ACTIVE / `authorized_to_act = true` risk for `b51dba91` as a by-product. Whether the HANDOFF records the author's STOP stays the Principal's or the PO/ARB's question. §8.72 C4's deduction stands for its time." Change plain-language item 5 and the index row to match. | 08.72 C4 inbound: 08.73 L20, L88, L89; map L247 (section-level); 08.00 L89 |
| W-06 | normal | defect | C2, "fits (ii) better" | §8.73.4 L108 says the question is "not decidable from the window". A sentence that contrasts a role name with a session label does not by itself separate processes. The strongest evidence for (ii) is the same process one source earlier: "no process may REGISTER itself" (S0327 L112) and "direct Governance to REGISTER" (L119–120). | Say (ii) is supported by S0327 L112 and L119–120, (i) by the reported in-session direction (S0328 L3), and that the window does not decide between them. | 08.73 L52, L108 |
| W-07 | normal | omission | C2, "the precedent cited does not reach this" | S0323 L14: "not disqualifying **for the Architecture correction**". S0328 L48 presents a quotation that joins registrar prose (S0323 L12) with a paraphrase of L14 and drops that scope. | Add: the precedent was ruled for the authoring role and is extended to the verification role, which carries the stricter "fresh independent" requirement. The quotation in S0328 is not verbatim. | 08.73 L53 |
| W-08 | normal | defect | C1, "S0328 does both" | S0190 L111 refuses a `recordedBy: governance` append by a non-Governance process. The window does not report `recordedBy` for seq 4–5 (S0329 L19 gives only seq 6), and under reading (i) the appender is Governance. | Make it conditional: it does the first if seq 4–5 carry `recordedBy: governance` (Governance is named to append them, S0328 L62–63) and if reading (ii) holds. The second (registering its own lane, S0190 L112) holds outright. | 08.73 L42 |
| W-09 | normal | omission | C4 and §8.73.3, "Agreement with §8.67 C3" | "The legibility defect is fixed without weakening the safety property" (S0330 L41) is the recorder's own wording; S0329 says "explains truthfully" (L111). The recorder is the author, and S0320 L89 says "do not self-review". | Attribute L41 to the author-recorder as its own evaluation of its own fix, against S0330 L16 ("asserts none of these results as its own finding"). The agreement is with S0329 L111. | 08.73 L95 |
| W-10 | normal | omission | C3, fixtures "rebuilt independently" | S0329 L52: the author's regressions "were read, then independently reconstructed". The verifier ran on `deepseek-chat` (S0327 L21), a model factor (§8.06 C1). | Add: the test design was made after reading the author's tests, so it is independent in data instances, not in design. The model factor is recorded (Src) and may differ from the author's (not shown). | 08.73 L65, L68 |
| W-11 | normal | omission | C3 and preservation 8 | S0329 L96 says "Human START remains a human authority boundary", with V-1 as the evidence. The fixtures test only CANCELLED and ACTIVE (L58–61). §8.72 C2 (W-04 there): ACTIVE can be re-entered by a CONTINUATION without a new human act. | Note that preservation 8 was not tested against §8.72 C2's open CONTINUATION case or the COMPLETED case. | 08.73 L96 |
| W-12 | normal | omission | C4: recording outside any lane | S0330 is recorded by `b51dba91` after its lane is HANDED_OFF (S0329 L20). S0323, S0328 and S0330 are unlaned recording acts. S0327 L9 refuses an "unlaned review" as a governed act. | Record the open question: the governance-recording capacity sits outside the lane model and G-3, so prose registrations, the only records of PO/ARB acts, have no lane, START or `authorized` state. Not a fork; a question. | 08.73 L78 |
| W-13 | normal | omission | C1, "only witness" | S0330 L23 relays seq 4–6 "as recorded", but its facts are drawn from S0329 and the PO/ARB (L16). | Add: S0330 relays the sequence and is not a second witness. Strength otherwise right. | 08.73 L40 |
| W-14 | normal | defect (quotation) | Lineage L6; plain language L24 | L6 renders "(self-declared …) — the same process", but the source has the dash inside the parenthesis (S0328 L3). L24 changes §8.27 C2's "false" into "not shown". | Quote S0328 L3 with the parenthesis in place. Use "false" in L24, or mark the change. | 08.73 L6, L24 |
| W-15 | normal | source/record | C5, "after its STOP … ACTIVE (L20)" | L20 is the Phase 0 state, before the verification work. The state after STOP is given jointly by S0329 L113 ("no transition") and L134. | Cite L113 and L134. | 08.73 L89 |
| W-16 | normal | record | STEP8 R2 | STEP8 reports no tier A, B or C. Tier D lists 28 tokens in scope (my run used `--no-corpus`; see Limits). | Judged in the appendix. One homonym: "witness". | STEP8 L21 |

## Verdicts
- **C1:** PROVISIONAL. The facts and the strength of "no second witness" are right. The §8.29 C1 home is missing (W-01), and "does both" is conditional (W-08).
- **C2:** PROVISIONAL. The two readings are sound, but "fits (ii)" is overstated (W-06), the precedent's scope is dropped (W-07), and the ruling's reliance on self-checks and on "fresh" is missing (W-03). The bar by label is VALIDATED.
- **C3:** PROVISIONAL. "The right form" (refutation, not proof) is VALIDATED. "The verifier does not decide it" is INVALID as stated (W-02). The limits need W-10 and W-11.
- **C4:** VALIDATED within scope: the author records the verdict, which can be checked against S0329, and the relay narrows. Add W-09 and W-12.
- **C5:** PROVISIONAL. The HANDOFF moving `b51dba91` to HANDED_OFF is VALIDATED (Src). "Scoped sense" is INVALID as stated (W-04). "The author's end recorded" is a normative identification (W-05).

## Coverage against my inventory
- **COVERED (24):** S0328 items 1, 2, 3, 5, 6, 7, 8, 11, 12 · S0329 items 1, 2, 3, 5–11, 15, 16 · S0330 items 1, 3, 5, 7, 10, 12.
- **MISSING (6):**
  - the ruling's reliance on the candidate's own checks, and "fresh" (S0328 items 4 and 13; W-03);
  - the census, reported and scoped to the store (S0328 item 10; §8.71 C2 again; normal);
  - the search-miss limit of the stale-name sweep (S0329 item 17);
  - the who-commits rule (S0330 item 8);
  - the restated sequence as a relay (S0330 item 4; W-13).
- **DISTORTED (3):**
  - S0329 items 12–13, the `authorized_to_act` homonym, homed in the wrong sense (W-04);
  - S0329 item 14, "does not decide" (W-02);
  - S0330 item 6, the author's synthesis taken as agreement (W-09).
- **JUSTIFIABLY EXCLUDED (3):** S0328 item 9 (non-scope lists), S0330 items 2 and 9 (process statements already carried by C4).
- **Question for the Principal (W-05, W-12):** must the end of a lane's work have its own transition, and do registrations of PO/ARB acts need a lane? No new fork for the map.

## Appendix: R2 (tier D in scope, judged one by one)
- **witness:** a homonym. It is used for a witness to an act (C1, L40) and for a second record that lets content be checked (C4, L78). Qualify each use.
- **fixture:** glossed in plain language as "test cases". OK.
- **self-appended, would-be, beneficiary, interest, appointee, appointment, disclosure:** compositional, or in use since §8.71. OK.
- **snapshot:** quoted source term. OK.
- **The rest** (ninth, rebuilt, hand, continue, repeat, though, eight, listed, right, said, quoted, plain, commission, commissioned, transcript, created, rest): ordinary words. OK.

## Limits
- R2 was run with `--no-corpus`, because the corpus scan would read allowed-list files the brief does not let me open. Tiers A, B and C cannot be separated from D in my run, so I cannot confirm STEP8's "no tier A, B or C" independently.
- I did not open S0192, S0091 or S0324. They are cited only through §8.29, §8.06 and §8.71–§8.72. I checked the quotes by hand against S0327–S0330, S0320, S0323 and S0190 L95–124.
- The proof-step check does not apply; §8.73 has no derivation.

## Files opened
- In the review folder `…/06-theory/manuscript/review/C80-S0328-S0330/`: `BRIEF-W-STEP2.md`, `STEP1-INVENTORY.md`, `STEP8-AND-CHECKS.md`.
- Under `…/06-theory/manuscript/chapter_08/`:
  - 08.73 (whole);
  - 08.72 (whole);
  - 08.71 (whole);
  - 08.27 L40–79;
  - 08.06 L28–72;
  - 08.29 L30–50;
  - lines printed with sed: 08.28 L64–72, 08.69 L45–52, 08.00 L89.
- `chapter_01/01.06` L15–19 (sed).
- Sources:
  - S0327 (whole);
  - S0323 (whole);
  - S0320 (whole);
  - S0190 L95–124.
- I did not reopen S0328–S0330; I used their text as read in step 1, held in context.
- Tool scripts: `undefined_terms.py` (the header and the corpus-branch lines, in the scratch copy).
- R2 and R3 outputs:
  - `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-c80-wren/c80-w-r2.txt`
  - `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-c80-wren/c80-w-r3.txt`

## Commands and searches
1. `ls` of my own C80 review folder; `wc -l`; `sha256sum STEP1-INVENTORY.md`; `cat SHA256-STEP1.txt`; `wc -l` of chapter_08 files 08.73, 08.00, 08.71, 08.72, 08.27.
2. `ls chapter_08 | grep` (section numbers); `ls chapter_03 chapter_01 | grep`; `ls -d chapter_*`.
3. `grep -E 'S0(320|322|323|327|190)\b' CORPUS-ALLOWED.tsv` and `head -2` of that file: all five listed.
4. `ls tmp/tools/`; `grep -n '8\.73\|8\.72 '` on 08.00-index; `sed -n 89p` on the index.
5. `grep -n '^\*\*C…'` on 08.27, 08.28, 08.06, 08.69, 08.67; `sed` reads of 08.28, 08.69, 01.06; `grep` on 03.09 and 01.14 for headings and `14.3`.
6. A first combined copy-and-run command was refused by the permission system. I then created the scratch directories under `…/rev-c80-wren/c80-w-root` and copied the allowed book files (chapter_00–06 and 08–10, `BOOK-OUTLINE.md`, the register, the map, `RESEARCH-PURPOSE.md`) and `tmp/tools/*.py`, `*.tsv` and `QUOTE-BASELINE.txt` into it.
7. `python3 -I undefined_terms.py --root <scratch> --help`: it failed at the corpus path. `inbound_citations.py --root <scratch> --help`.
8. `grep` on the scratch `undefined_terms.py` for its arguments; `sed` of its L1–40, L125–140 and L170–186.
9. `python3 -I undefined_terms.py --root <scratch> --no-corpus --scope 08.73` → c80-w-r2.txt.
10. `python3 -I inbound_citations.py --root <scratch> "§8.72 C4" "§8.73 C1" … "§8.73 C5" "§8.71.3" "§8.71 C1"` → c80-w-r3.txt.
11. `grep -rn -i 'chat instruction|not a human START|in-session'` over chapter_0*, chapter_1*, `THEORY-MAP.md` and `CONCEPT-REGISTER.tsv` (not the review folders).

No git commands. Nothing in the repository was edited.
