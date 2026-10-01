# CHANGELOG r3c-1: repair of the coder-facing schema

**Inputs:** TASK.md, SCHEMA-r3.md (authoritative, unchanged), the draft SCHEMA-r3-CODER.md, the old EQUIVALENCE.md, REVIEW.md and REVIEW.json (verdict FAIL).
**Outputs:** SCHEMA-r3-CODER.md (rewritten), EQUIVALENCE.md (rebuilt), and this file.

No source records were used or reconstructed. No commands were run.

## Approach

The draft compressed SCHEMA-r3 into its own wording. That is how the B1 qualifiers were lost. r3c-1 does not paraphrase. Each rule line of the coder file is a SCHEMA-r3 rule sentence, in SCHEMA-r3's order and section numbering. The only changes are:
- markup removed;
- examples replaced;
- cross-references adjusted, where needed.

Every line starts with its id (TASK item 4). EQUIVALENCE.md maps each id to a SCHEMA-r3 line number (TASK item 5).

## 1. B1: equivalence restored (TASK item 1)

| Review item | Restored at |
|---|---|
| D4 "on the target" | D4.1: "performing X on the target was put forward for decision" |
| Rank-1 cell "for X" | R-A5.3.6: "ACT_REFUSED if a PROPOSAL or attempt for X is identified" |
| R-A2 "a refusal expression that disposes of that proposal" | R-A2.3 |
| Row 46 "with no refusal expression" | R-A2.5 |
| Row 127 "the intended failure is not a non-effect marker; rank 3 if it occurred" | D6.10, D6.11 |
| "guard" | D8.15 |
| "no RED begins" | D8.10 |
| D4 question wording | D4.2: "a question put to the decider of whether to do X" |

New worked example E-A10 shows the target qualifier at work. The review's failure case is a refusal verb with a proposal for X on a different target. Under the restored rule it codes UNKNOWN, not ACT_REFUSED.

Further draft wordings were reverted to SCHEMA-r3, because SCHEMA-r3 wins:
- "plain meaning" → "r2 meaning", plus NOT IN SCHEMA;
- "(subject to A3)" removed from R-A3.1;
- "counted, apart from decided values" → "counted, and excluded from support counts";
- "elsewhere 'span or section' allows it" → R-X1.4 verbatim, plus NOT IN SCHEMA;
- "This definition does not refer to R-A3" (D3.3) and "so it stays countable" (D6.15) are back in;
- the full R-N2 status-word list is back in;
- the D10 forms "held it — not adopted" and "confirms and adopts" are back, verbatim.

## 2. B2: examples (TASK item 2)

**All worked examples were replaced.** The draft's §5 table is gone. The new §6 has 26 ACT examples and 17 NORM examples, all from one fictional domain: the Elmbrook allotment-garden association (committee minutes, a rule book and members' plot logs).
- Each example names one path and cites the coder lines it applies.
- None uses the wording ruling, register, ADR, RED, WP-, matrix, Chief, ARB or Decision Authority.

**Templates the review flagged were broken:**
- "Two operators" is now lower-case prose with "but" (E-N07).
- The PREPARED example has no all-caps headline and no "— <actor> (PREPARED)" tail (E-A15).
- The bare-authorize example uses a different surface (E-N10).
- The gated-negative example now has an identified proposal and gives `state_if_extended` ACT_REFUSED, not the no-proposal UNKNOWN pattern (E-A08).
- The negated-*may* example uses "Nothing may …" (E-N04).
- The class-label example uses "routine maintenance, not a members' decision" (E-N15).
- The ground example uses a "Reason:" label, not "because" (E-A20).
- "RULING AS ISSUED, VERBATIM" and the "(… instruction)" pattern are gone.

**Examples that leaned on open ambiguities were replaced:**
- The draft's "Gated" example (register row, present passive, "classified as <NP>") is replaced by an active, unambiguous "hereby approves" (E-A06).
- "(by lineage)" is removed. E-A21 and E-A22 assign acts to records only by the rule as SCHEMA-r3 states it:
  - U-7: a span recording DISTINCT acts "yields one record per (operation, target)";
  - R-S1.2: "each record codes only the act for its own (operation, target)";
  - R-A5.1.5–R-A5.1.7: for a ground-marked act on a shared span, "it is the act of its own record among them".

**Examples inside rule sentences.** SCHEMA-r3 also illustrates rules inside the rule text (R-36, ADR-T14, WP-4C-1, Slice 7C, R-86, R-90, PB-004, the Promotion Review, PROMOTE / Remains Candidate, and others). Each of these is replaced by an allotment-garden example. EQUIVALENCE lists every replacement, old → new, with code E.

**Word lists kept verbatim.** Word lists that a rule tests for are not examples and stay as SCHEMA-r3 gives them. This keeps the rules equivalent. These lists include:
- the refusal verbs and the D7/D9 operators;
- the status labels;
- the five R-A3 lemmas and the twelve unlisted synonyms;
- the D15 markers;
- the REPORTED-sign forms;
- the intended-failure markers, including "RED";
- the non-commencement forms, including "no RED begins", which TASK item 1 requires.

So SCHEMA-r3 terms such as REGISTER ROW and "register ruling identifier" still occur in rule lines. They do not occur in any example.

## 3. Self-containment (TASK item 3)

Everything SCHEMA-r3 defines internally is now in the coder file, because the file carries every rule sentence. Where SCHEMA-r3 refers to something it does not contain, the coder file says NOT IN SCHEMA and supplies nothing:

| Line | Missing item |
|---|---|
| F-act.operation.2 | the v1 operation list |
| U-2a | how lineage (`v1_event_ids`) is established |
| F-norm.deontic.6, D7.8 | r2 meaning of PERMITTED, AUTHORIZED, UNAUTHORIZED, DELEGATED |
| R-A5.0.2 | the gaps G3 and G4 (the competitions themselves are stated in R-A5.0.1) |
| R-A5.0.5, R-A5.3.10 | r2 meaning of reported ACT_OCCURRED and of ACT_REVERSED |
| R-X1.5 | r2 rules on other uses of the section |

Purpose clauses and pointers to THEORY-CHOICES are not carried, because they decide no value. EQUIVALENCE §7 lists them.

## 4. Machine-check format (TASK item 4)

Each rule sentence is on its own line, starting with an id:
- U- for §1;
- F- for §2 fields;
- ST- for §3;
- R-A1.n … R-X1.n for §4;
- Dn.m for §5.

The R-A5 rank table is written out as lines R-A5.3.3–R-A5.3.13 (code H in EQUIVALENCE), so no table cell holds several rules.

## 5. EQUIVALENCE rebuilt (TASK item 5)

The old row numbers 1–164 are replaced by coder ids mapped to SCHEMA-r3 line numbers, with codes V / E / R / H / NIS. The old rows 43, 44, 46, 117 and 127 were the ones that falsely said "Same". The new rows record those qualifiers as present verbatim.

## 6. Known limits and items not addressed

- **Length.** The coder file is much longer than the draft, because it no longer compresses. I estimate it at well above the earlier 3,500-word target. I did not count it by machine. The current TASK sets no word limit, and I put equivalence first.
- **Review items outside this TASK, left open:**
  - KNOWN-AMBIGUITIES B5 (R11's section) and the no-span records R24/R28/R35;
  - shipping SOURCES.md as the section source;
  - the unverifiable TC-1 erratum.

  KNOWN-AMBIGUITIES.md and SOURCES.md are not in this directory. There is no rule in SCHEMA-r3 for a record with no available span, so the coder file states none.
- **"Taken" is still undefined** in R-A5.1.8 (shared span with more acts than records). This is verbatim SCHEMA-r3, not repaired here.
- **How the check was done.** All checks were done by reading. Nothing was diffed or word-counted by tool.
