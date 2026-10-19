PROPOSED / NOT RATIFIED; materials only; not executed

# KC-1 external replication: adjudication procedure

Fixed before any encoding and frozen with the materials. Adjudication **labels** disagreements; it never changes a sheet, a verdict, the key or the decision label of analysis-plan.md §2.

## 1. Who

- **Two adjudicators**, A1 and A2, named by the human before the freeze (PROTOCOL.md §8.1) and recorded in the freeze commit message.
- **Eligibility:** neither authored, built or reviewed these packets or the KC-1 analyses (KC-CONVENTIONS-PROPOSAL, KC-1-READINESS-AUDIT, MKC, T3); neither served as an encoder. **At least one adjudicator is human.** If a model is used as the second adjudicator it must be non-Claude-family, and its full transcript is archived like an encoder's.
- **Tie-breaker:** the human who ratifies the protocol (or a delegate named at the freeze) decides cases where A1 and A2 still differ after reconciliation (§4 step 4). The tie-breaker sees both written classifications.

## 2. When

1. After collection closes and the raw archive is hashed (PROTOCOL.md §8.3).
2. After `score.py` has produced the primary tables, which are committed **before** adjudication starts.
3. Before any interpretation, summary or decision document cites the result.

## 3. What is adjudicated, and the categories

**Cases.** Every (encoder, guard row) whose derived verdict differs from the arm's **modal** verdict, and every (encoder, guard row) whose verdict differs from the arm's **key** verdict. Optional, if time allows: guard-row item-set differences that change no verdict (labelled "item-level", reported separately).

**Inputs per case.** The packet sent (sendable body), the encoder's verbatim reply (sections B1–B4), the arm's key and key_PRIVATE.md §4 (pre-specified risk points). **Not shown to adjudicators:** the encoder's identity class and cluster (the coordinator removes section A and replaces the encoder id with a random case id). The arm cannot be hidden, since the reasons cite its rules.

| category | test (apply in this order; the first that fits) |
|---|---|
| **N non-compliance** | the reply shows the encoder did not follow the task: it used outside knowledge or sources, ignored rule 6 altogether, encoded a different row, or answered a different question |
| **E encoder error** | the encoder's stated reasoning, applied correctly, would give the key or modal verdict, but a slip intervened (glossary mapping contradicting the glossary, a symbol misread, a clerical omission, a JSON typo) |
| **G instruction gap** | the packet is **silent or under-specified** for the part that moved the verdict, and the encoder filled the hole. Operational test: the adjudicators can write **one sentence** that (i) names no row, row phrase or verdict, (ii) contradicts no sentence of the packet, and (iii) would have excluded the encoder's reading. Typical signs: the reason says the rule is silent; the part fits no letter (a)–(g) or fits two (answer sheet B3); a cited text was needed but not supplied |
| **I inherent** | the packet **as written** licenses both readings: the encoder cites a rule (or table letter) and applies it in a way the wording supports, and another encoder cites the same or another applicable rule with the opposite result; no sentence meeting the G test can be written without choosing between two readings the source itself supports. (blind2's R21 split between B′ rule 3's two sentences is the reference example: audit §12.4) |
| **U unclassified** | the reasons do not allow a classification |

**G vs I in one line:** a gap can be closed by adding a neutral sentence; an inherent ambiguity can be closed only by choosing a reading. When both seem to apply, record **I**, and name the gap sentence that would have resolved it as "resolving choice" (as audit §12.4 did).

**Source attribution (for G and I).** Each G or I case also records whether the gap or ambiguity lies in (s) the **convention's source text** (T3, KC), or (r) the **analyst's operationalisation** in the packet (key_PRIVATE.md §5, items E1–E11; in particular E7, the general-form parse). Attribution (r) means the result says nothing about the source convention on that row.

## 4. Steps

1. A1 and A2 classify every case **independently**, in writing, citing the reply's words and the packet sentence(s) concerned, and record the source attribution.
2. Their raw classifications are committed **before** reconciliation. Agreement between A1 and A2 is reported as a count (k of N cases); no κ.
3. A1 and A2 meet and reconcile; changes are recorded with reasons.
4. Remaining differences go to the tie-breaker; both positions are reported.
5. The report lists, per arm and row: the cases, final categories, the G sentences proposed, and the source attribution.

## 5. What adjudication cannot do

- It cannot change a verdict, re-score a sheet, exclude an encoder from the primary analysis, or change a decision label. (Exclusions exist only as the pre-specified sensitivity analyses, analysis-plan.md §4.4.)
- It cannot revise a packet and re-run within this study. A G finding becomes a **proposed amendment** for a separately approved follow-up study, with new materials, a new freeze and new encoders.
- It cannot call a convention correct or incorrect. It classifies how a text was read.
