PROPOSED / NOT RATIFIED; materials only; not executed

# KC-1 external blind replication: protocol (human or non-Claude encoders)

**Status.** Prepared 2026-10-17 as materials only. **Nothing has been sent to any encoder, model or service.** Nothing here is adopted, ratified, selected or authorised; KC-1 is not made. Running it needs a human act that ratifies this protocol, names the adjudicators and the coordinator, and makes the freeze commit (§8.1). Base folder: `R7/`; this folder: `R7/KC-1-VALIDATION/external-protocol/`. No existing file was edited.

| file | role |
|---|---|
| `PROTOCOL.md` | this document |
| `packet_T3.md`, `packet_SH.md`, `packet_PARSE.md` | mandatory arms (sendable text between the BEGIN / END markers) |
| `packet_PARSE_B.md`, `packet_GEN.md` | optional arms |
| `answer_sheet_template.md` | one sheet per encoder: coordinator record + verbatim reply |
| `analysis-plan.md` | units, decision rule, statistics, missing data, stopping rule |
| `score.py` | scorer (runs on answer sheets; `--oc` prints the operating characteristics) |
| `adjudication.md` | classification of disagreements |
| `key_PRIVATE.md` | **never shown to encoders**: packet codes, key vectors, risk points, source trace |
| `tools/build_packets.py`, `tools/source_data.json` | builds all packets from one template |
| `tools/leak_check.py`, `tools/leak_check.out` | leak and balance check (ALL PASS) |
| `tools/freeze.py` | prints hashes; `--write` is the human's freeze step |
| `tools/oc_table.out` | operating characteristics of the decision rule |
| `tools/dryrun_blind2_manifest.csv`, `tools/dryrun_blind2.out` | **dry run** of `score.py` on blind2 replies (not a result) |

## 1. Why this study, and which earlier flaws it addresses

The KC-1 decision package records that evidence able to reopen or sharpen the deferral includes "a non-Claude or human blind replication, which would address Λ7, including a test of (c)+named parse and of T3's enumerated (a)" (`KC-1-READINESS-AUDIT.md` §14.F). The two earlier blind tests (`KC-1-READINESS-scripts/blind/`, `blind2/`) had the limits below; each has a design answer here.

| earlier limit (source) | answer in this protocol |
|---|---|
| n = 2–3 per arm; one run decides (audit §9, §12.4, §14.C) | minimum 5, recommended 10 valid sheets per mandatory arm; reduced-n labels fixed in advance (analysis-plan §2, §3.1) |
| all encoders Claude-family (Λ7) | Claude-family encoders are ineligible; humans and ≥ 2 non-Claude model families; ≥ 3 clusters per arm, ≤ 40 % per cluster (§6.1; analysis-plan §3.4) |
| packets of unequal specificity (audit §9) | one template; only rule 6 varies; rule 6 has the same lead + seven-part table structure in every arm; word counts within ±25 % (checked: −9 % to +15 %) (§4.3) |
| glossary vs unfolding tension in B′ (audit §12.4 item 5) | no arm has a read-through rule; the glossary states it is at the level named; glossary and rules agree in every arm (§4.4) |
| A′ lacked T3's enumerated list (audit §12.4 item 5; §14.C) | the T3 arm carries T3's list; the general form is the optional GEN arm (§4.1) |
| (b) and (c)+parse never tested as stipulated rules (§14.C) | SH arm (closed rule) and PARSE arm ((c) + K1c parse in general form), optional PARSE_B (alternative parse) (§4.1) |
| transcripts not archived; reasons abridged (audit §12.4) | full raw transcripts archived with UTC timestamps; answer sheets are verbatim; archive hashed and committed before scoring (§8.2–8.3) |
| pre-registration committed only after outcomes (audit §12.4) | the human commits the frozen materials **before** any encoding; every sheet records the commit id (§8.1) |
| verdicts computed through FZ substitution and several scripts | `score.py` derives verdicts directly from a published placement table; encoders never see it (analysis-plan §1.2) |

## 2. Research question

**Primary.** Given each convention's complete, equally specific rule text, do independent human or non-Claude encoders reproduce **that convention's** guard-row verdicts at R11, R14, R15, R16, R17 and R21?

This is a question about the **determinacy of a text**: whether the words fix the verdicts for readers who share none of the authors' context. It is **not** a question about which convention is correct, which purpose the counts should serve (hosting relevance or constraint coupling), or which convention KC-1 should select. Those are normative questions that only the human can settle (audit §14.C "Normative"). An arm that is REPRODUCED is not thereby better; an arm that is INDETERMINATE is not thereby wrong, only under-determined by its text.

**Secondary.** Item-set agreement; behaviour of the five control rows across arms; classification of disagreements (instruction gap vs inherent).

## 3. Hypotheses (decision rule and labels: analysis-plan §2)

- **H-T3:** T3's rule text, with its enumerated list, determines T3's six verdicts (T3 REPRODUCED). Expected to hold.
- **H-SH:** encoders comply with the closed rule "listed rows read nothing" (SH REPRODUCED). Expected by construction; an **instrument check**.
- **H-PARSE:** (c) "listed rows read only the object-level parts of their statement" plus the K1c parse, stated as a general rule, determines K1c's six verdicts (PARSE REPRODUCED). **No directional prediction.**
- Optional **H-PARSE_B** (alternative parse) and **H-GEN** (general-form (a), no list).

No hypothesis compares arms, and none is tested by a significance test.

## 4. Design

### 4.1 Arms (between-encoder; each encoder sees exactly one packet)

| arm | what rule 6 says for the listed rows R11, R14–R17, R21 | source | key vector (verdict level) |
|---|---|---|---|
| **T3** (a) | judged by the items whose tie or order they deny; other parts read as for any row | T3:66 rule 1, with its list | FZ |
| **SH** (b) | read nothing | KC:88 K1 (b) | none × 6 |
| **PARSE** (c) + K1c | only object-level parts (the parts that assert a formula about histories): typings read; denials, reports of absence, contrasts, permissions, exceptions and restatements not read | KC:88 K1 (c); KC §2.4 item 4; KC:158 | C (K1c) |
| *PARSE_B* (c) + alt. parse (optional) | as PARSE, but permissions ("may") and exceptions ("except …") read | KC §2.4 item 4, alternative column | C-R15ref-R14 |
| *GEN* (a) general form (optional) | as T3, but for "every row that denies a tie or an order"; no list | blind2 A′ P3 | FZ |

The vectors themselves are only in `key_PRIVATE.md`.

**One-factor design.** Rules 1–5 (T3's rules for every other matter: level of naming, actors, class rule, row references, nothing read) are **byte-identical in every arm**; only rule 6 varies. This is the KC "moves" method (switch one convention, K1, away from the T3 baseline; KC:80–84). Consequence: PARSE tests "(c) + a named parse" **as a K1 value**, not principle C with every K-value changed (which blind2 B′ approximated). Guard-row verdicts depend only on what is read in the guard rows, so the K2–K13 values matter only through how they shape that reading; holding them fixed removes them as a confound.

**Why one arm per parse and not a factorial.** KC §2.4 item 4 names one parse (K1c) and alternatives at two rows (R14 permission, R15 exception). A 2 × 2 factorial would need four parse arms (≥ 20 encoders) to estimate effects of parse choices, but the question is whether each **named** parse text is determinate, not what each factor does; the factor cells other than K1c and the "both read" cell name no convention in the KC record. Hence PARSE (K1c, mandatory) and PARSE_B (both alternatives, = C-R15ref-R14, optional). C-Σ has the same guard verdicts as C-R15ref and is not separately tested.

**Why no `packet_SPLIT.md`.** Branch S (audit §13, §14.E) assigns each row two relations (naming for hosting, asserting for coupling). Its determinacy is the conjunction of the determinacy of its two parts, which T3 / GEN and PARSE / PARSE_B already test. A SPLIT packet would need a different output (two sets per row), which breaks the shared answer format and invites carry-over between the two lists within one encoder. It is not justified as an arm; it can be assessed from the T3 and PARSE results.

**The enumerated list is in every non-GEN arm.** T3's list is part of T3's text; giving it to T3 alone would make the T3 arm differ from the others in two ways. With the list in every arm, the arms differ only in what rule 6 says the listed rows read. The list identifies which rows are listed, not any verdict.

### 4.2 Shared materials

Identical in every packet (checked by `tools/leak_check.py`): task text (section 1), rules 1–5, the rule-6 skeleton and part descriptions (a)–(g), symbol glossary (section 3), concept list C1–C28 (section 4), rows (section 5). Rows: the six guard rows and five controls (R1, R6, R9, R79, R80), with blind1 / blind2's texts, shuffled with `random.seed(20261017)`: **R6, R16, R80, R21, R11, R79, R15, R9, R17, R1, R14**. Neutral packet codes (single letters, seed 20261019) replace any arm name.

### 4.3 Matched length and detail

Each arm's rule 6 has the same form: the shared list sentence; a lead sentence quoting its convention's source sentence (lightly adapted; key_PRIVATE.md §5 E3–E9); the same instruction to treat the statement part by part; the same seven-part table, with the arm's treatment in each cell. So each arm answers the same seven sub-questions, at the same level of detail. Rule-6 words: T3 338, SH 270, PARSE 283, PARSE_B 306, GEN 342 (mandatory-arm mean 297; all within ±25 %). No arm contains a worked example: T3's own example names R14, its sides and its verdict, so it is omitted from every arm.

**The parse in general form.** KC §2.4 item 4 gives K1c as a per-row table. A per-row table is determinate by construction (audit §12.4 item 4), so testing it would test copying. The PARSE arm therefore states the parse as part types with placeholder examples (x, y, A, B, S) that quote no row. This general form is the analyst's reconstruction (key_PRIVATE.md §5 E7); a PARSE departure from K1c may show a gap in the reconstruction rather than in K1c, and adjudication records which (adjudication.md §3, "source attribution").

### 4.4 Glossary vs unfolding (resolved)

In blind2 B′, the glossary mapped Adopted → C10 and Chg → C7 while B′ rule 4 said to read them through. Here no arm has a read-through rule (rules 1–5 are T3's: a derived predicate named as a concept counts as its own item), and the glossary says so in one added sentence (key_PRIVATE.md §5 E10). Glossary and rules agree in every arm. The cost is stated in §11: no arm tests principle C's unfolding.

### 4.5 Leak check

`tools/leak_check.py` (output `tools/leak_check.out`: **ALL PASS**) checks: status header; shared sections byte-identical; rules 1–5 identical (GEN differs only in rule 1's last sentence); identical part descriptions; identical row order and the list = the six guard rows; no banned vocabulary outside the fixed corpus text (CB1 / CB23, crossing, host, E3-REL, guard, placement, T-A/T-B, K1, SH, T3, KC, MKC, FZ, verdict, interpretation, expected, correct, preferences, coupling, spanning, analyst, Claude, KnowledgeOS, F-LOG, VP-ids, principle, parse, split, the verdict tokens X / none / side); no C-id list other than the format example; matched rule-6 length; neutral codes. Inside the fixed corpus text, "side" occurs ("B-side" in R79 / R80), identical in every packet. The human re-runs the check after any change, before the freeze.

## 5. Encoders

### 5.1 Eligibility

- **Models:** any non-Claude-family model (e.g. models from other providers), each recorded by provider, family, exact model id and snapshot date. **Claude-family models of any version are ineligible**; `score.py` flags them.
- **Humans:** reading English, comfortable with basic set notation (∈, ⊆, functions A → B). No training in the project's material. Informed consent; pseudonymous ids only; no personal data beyond the background field.
- **Familiarity screen (recorded, not excluding):** prior exposure to KnowledgeOS, PublicDigit, the T1/T2/T3 or KC texts, or the earlier blind tests. A "yes" is reported and analysed as a sensitivity; it does not exclude, because exclusion criteria applied after the reply would be outcome-adjacent. Anyone who has worked on KC-1 is ineligible beforehand.

### 5.2 Clusters

A cluster is a correlation group: one model family (all versions), or one human cohort (same institution or same briefing). Each mandatory arm needs ≥ 3 clusters and ≤ 40 % of its valid sheets from one cluster (analysis-plan §3.4).

## 6. Procedure

### 6.1 Roles

- **Coordinator:** recruits, allocates, sends packets, receives replies, fills section A of the answer sheets, checks validity only. Does not score during collection and never tells an encoder anything about other replies.
- **Scorer:** runs `score.py` after the archive is frozen (§8.3). May be the coordinator.
- **Adjudicators:** per adjudication.md §1.
- **Human principal:** ratifies, names the roles, makes the freeze commit and the archive commit, sets the deadline.

### 6.2 Before encoding

Ratification; roles named; target n per arm and deadline fixed; allocation list fixed (§6.3); leak check re-run (ALL PASS); freeze (§8.1).

### 6.3 Allocation

Build the list of encoder slots per cluster before recruitment closes. Within each cluster, order its slots by id, draw a random permutation of the arm list with `random.seed(20261020)` (one draw per cluster, clusters in alphabetical order), and assign slot k to arm k mod (number of arms). This balances clusters across arms. Record the list in the freeze commit (or, if slots are not known by then, in a second commit **before** the first packet is sent). Encoders do not choose arms and are not told there are arms.

### 6.4 Administration

- **Send exactly the sendable body** (text between the BEGIN and END markers) of the allocated packet. Its sha256 must equal the `#sendable-body` line of `FREEZE-MANIFEST.sha256`; record it on the sheet.
- **Models:** a fresh conversation or API call; no system prompt; the sendable body as the single user message; tools, browsing, retrieval, code execution and memory off; provider-default sampling unless fixed before the freeze; record all settings. No follow-up message, except one identical "Please give your answer in the required output format." if the reply has no JSON at all (recorded as a deviation).
- **Humans:** the sendable body as a file or print-out; work alone; no time limit, time recorded; no questions answered about content (a fixed reply: "Please apply the text as written and note your doubt in the reason line").
- Paste the reply verbatim into section B of `answer_sheet_template.md`, saved as `responses/<encoder_id>.md`; save the full transcript as `raw/<encoder_id>.txt`.

### 6.5 Invalid replies and replacement

Validity per analysis-plan §4.1. An invalid sheet is archived and replaced once by a fresh encoder of the same cluster where possible; the replacement is recorded on both sheets. No sheet is replaced because of its content.

### 6.6 Isolation

Encoders never see other encoders' replies, scores, the key, the scorer, this protocol, the analysis plan, or any KC-1 document. No group sessions. Model runs share no context. Results are not published until collection has closed.

## 7. Scoring and analysis

As fixed in `analysis-plan.md`. Primary unit: the guard-row verdict, derived by the scorer from each encoder's item set through the published placement table. Secondary unit: exact item-set match. Decision rule: ≥ 80 % of valid sheets (⌈0.8 n⌉) on the key verdict at **each** of the six guard rows, with n ≥ 5 (n = 4 provisional, n ≤ 3 inconclusive). No significance tests.

## 8. Integrity

### 8.1 Freeze (before any encoding; human act)

1. Re-run `python3 -B tools/build_packets.py tools/source_data.json .` only if a change was ratified; then `python3 -B tools/leak_check.py . > tools/leak_check.out` must end "ALL PASS".
2. `python3 -B tools/freeze.py --write` writes `FREEZE-MANIFEST.sha256` (every material, plus the sha256 of each packet's sendable body).
3. **The human commits the folder** (including the manifest, the allocation list and the adjudicators' names). If any encoder could read the repository, keep `key_PRIVATE.md` outside it and commit only its hash (`freeze.py` records it).
4. The commit id goes into every coordinator record. **No packet is sent before this commit exists.** Any later change to any frozen file voids the freeze; a new freeze needs a new commit and is reported.

This AI-prepared draft makes no commit and no freeze.

### 8.2 Raw archive

For every encoder: `raw/<encoder_id>.txt` (the full transcript or submitted text, including the packet as sent), `responses/<encoder_id>.md` (answer sheet), UTC timestamps (sent, received), identity class, model details or human background, settings, deviations. Nothing is edited after receipt.

### 8.3 Archive freeze (before scoring)

When collection closes: hash every file in `raw/` and `responses/` into `ARCHIVE.sha256`; the human commits it. Only then is `score.py` run, and its output is committed before adjudication starts.

### 8.4 Identity class

Every sheet records human or model, and for models the provider, family, exact id and snapshot date. The report gives per-arm counts by identity class and cluster.

### 8.5 Deviations

Every departure from this protocol is recorded on the sheet and listed in the report, with its effect on n.

## 9. Adjudication

Per `adjudication.md`: two independent adjudicators (≥ 1 human, none involved in KC-1), blind to identity class, after scoring; categories non-compliance / encoder error / instruction gap / inherent / unclassified, with source attribution (convention text vs packet operationalisation); a tie-breaker named at the freeze. Adjudication labels; it never re-scores.

## 10. What the result can and cannot change in KC-1

**Can:**
- address **Λ7 at the encoder level** for the determinacy evidence (Rq1 in the KC-1 matrix);
- show whether **T3's enumerated (a)**, the closed **(b)**, and **(c) + a named parse in general form** are determinate for external readers: the three option forms the decision package names (audit §12.4 item 3, §14.D);
- show whether the PARSE text yields K1c's verdicts or other ones (DETERMINATE-OTHER), which would bear on whether "(c) + parse" must be stated as a per-row table to be determinate;
- locate gaps (instruction gap) that a revised text could close, as input to a separate follow-up.

**Cannot:**
- decide which convention is correct, or which purpose (hosting relevance, constraint coupling, or both via branch S) the counts should serve; that is the human's normative question (audit §14.C);
- select (a), (b) or (c), or ratify T3, KC or any K-value; it does not make KC-1;
- remove **Λ7 at the source**: every text the encoders read was written or selected by Claude-family agents (§11);
- settle R80's role, the spanning rule (K14), K18, or any question outside the six guard rows;
- authorise E4 / R8, KC-2 to KC-5, or any amendment; the deferral blocks listed in audit §14.F stay as they are.

## 11. Limitations

1. **One corpus.** Eleven rows (six guard rows), one concept list, one glossary. Determinacy on these rows does not generalise to other rows, other corpora or other cuts.
2. **Λ7 at the source.** Rows, concepts, glossary, rules 1–5, the rule-6 operationalisation, the general-form parse and this protocol are Claude-authored or Claude-selected. External encoders remove the shared **reader** prior, not the shared **author** prior: a text can be determinate because its author wrote it in a style that any careful reader resolves the same way, and a shared authorial misconception would pass unnoticed.
3. **Operationalisation.** The T3 lead's added sentence (broad reading) and the seven-part table are analyst choices (key_PRIVATE.md §5). For T3 the narrow and broad readings give the same verdicts (key §3), so this matters at item level only. For PARSE, the general form is a reconstruction of a per-row table; its fidelity is part of what is tested, and a failure may be the reconstruction's.
4. **Not principle C.** The one-factor design tests (c) as a K1 value with the other conventions at T3's values. It does not test principle C's full rule set (unfolding, sort rule), which blind2 B′ approximated.
5. **The list is given.** Every non-GEN arm tells the encoder which rows are listed. The determinacy measured is conditional on the list; GEN (optional) is the only test without it.
6. **Encoder familiarity.** Humans may know related literature; models may have seen similar texts. The familiarity screen records exposure but cannot detect it.
7. **Competence vs determinacy.** A human who misreads notation and a text that is ambiguous both produce disagreement; adjudication separates them only as far as the reasons allow.
8. **Small n.** Even n = 10 gives a 95 % lower bound of 0.69 at 10 / 10; the decision rule is descriptive, and its operating characteristics (`tools/oc_table.out`) assume independence, which clusters violate.
9. **Controls.** The control key (FZ) is known to depart at R80 under the general rules; controls enter no decision.
10. **Language.** English only.

## 12. Reproduction of the prepared materials

From this folder, with no `__pycache__` written:

```
python3 -B tools/build_packets.py tools/source_data.json .
python3 -B tools/leak_check.py .
python3 -B score.py --oc
python3 -B score.py --manifest tools/dryrun_blind2_manifest.csv --key key_PRIVATE.md --base .
python3 -B tools/freeze.py            # prints hashes only; --write is the human's freeze step
```

The dry run scores the blind2 verbatim replies (Claude-family, ineligible, different packets) to test the scorer. Its output (GEN 3 / 3 at all six rows; B′ R21 split 2 : 1; both INCONCLUSIVE at n = 3) reproduces `blind2_eval.out` and is **not** a result of this protocol.
