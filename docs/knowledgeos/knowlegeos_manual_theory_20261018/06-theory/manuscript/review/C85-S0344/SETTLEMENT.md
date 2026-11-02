# Window C85 (S0344): settlement

*2026-11-02. Section: §8.78 (v2). Review: `REVIEW-REPORT.md` (sha256 in `SHA256-REVIEW.txt`), W-01–W-16. 0 critical, 4 high. Every finding accepted after checking it against S0344, S0343 and S0339.*

## Theory first

- **Never required, not prompted** (C1, W-01): the source bans requiring the human to inspect the record to route the work (L890) and offers optional diagnostics (L626); "never sees" and "removes the check" withdrawn. By default the one party who could attest the human act is outside the record's checking path.
- **Who performs START, two readings** (C1–C2, W-03): the capability executes START (L301–302, L1265), or the human act at the START boundary is starting the assigned session (L323–333, L524, L908). C2's deduction now states its premise H; R3 on §8.77 C6.
- **Mandatory human decisions named** (C3, W-02): appointment (L557), adoption (L746), §32's business model of every role transition (L928), the non-deterministic path (L253–259). The self-gating is limited to the residual triggers (L132–134, L708). The §9.19 T7 analogy and "widest consequence" withdrawn. A tension in the source recorded: §7's conditional (L265) against §32's unconditional model.
- **Predecessor handoff after authorization** (C4, W-06, W-07): not the beneficiary's self-registration; no STOP *transition*, but AST-018's `stop` command and the HANDOFF can record the end of work.
- **Actor freshness against review independence** (C5, W-04): the lineage closes the question for a fresh actor (S0339 L8) and leaves it open for review (L664–676). R3 on §8.76 C4; bears on the Principal's question.
- **AST-018 owns the prompts the slice specifies** (§8.78.3, W-16): §8.77 C5's forced dependency strengthened; R3 on §8.77 C5.

## Dispositions

| W | Sev | Disposition | Where |
|---|---|---|---|
| W-01 | high | accepted (checked: L890, L626, L323) | title; PL1; C1; index |
| W-02 | high | accepted: C3 restated; analogy and ranking withdrawn | title; PL3; C3; .4; index |
| W-03 | high | accepted: two readings; premise H stated; R3 on §8.77 C6 | PL2; C1; C2 |
| W-04 | high | accepted; R3 on §8.76 C4 | PL5; C5 |
| W-05 | normal | accepted | title; C2; index |
| W-06 | normal | accepted (checked: L289, L606) | PL4; C4 |
| W-07 | normal | accepted (checked: S0343 L41, L44; L381) | PL4; C4; .5 source defect |
| W-08 | normal | accepted | C5 |
| W-09 | normal | accepted (checked: L383–386, L833–871, L1044–1049) | C5; index |
| W-10 | normal | accepted (checked: L960–962) | .3 |
| W-11 | normal | accepted | .3 |
| W-12 | normal | accepted | .3 |
| W-13 | normal | accepted | head |
| W-14 | normal | accepted: stale pointers dropped | homes table |
| W-15 | normal | accepted: quoted verbatim (L326) | C1 |
| W-16 | normal | accepted; R3 on §8.77 C5 | .3 |

## R3

- §8.76 C4 (actor freshness against review independence); §8.77 C5 (AST-018 owns the prompts); §8.77 C6 (START read two ways). No inbound citations to §8.78 C1–C5 (W's run).

## Checks at settlement

- Quote check on v2 against S0344, S0343 and S0339: 178 fragments; every miss is a book-internal or draft quotation (§8.78.5), none a source quotation.
- R2 (`undefined_terms.py --scope 08.78`) on v2: tier A–C 0 in scope; tier D in scope judged as ordinary English or the source's sense: doer, escalator (glossed), specifie(d), recovery, activate, strengthen, escalate, predecessor, appoint, hand, addition, a word naming the MATCH check, quoted, once, agree, keep.

## Procedure

- W's step 2: one combined copy command was refused by the permission system and not run; W used a separate copy and ran every tool with an explicit root on the scratch copy; no git commands. R2 ran without the corpus (W's limit). W disclosed that its verdicts on items taken from its own inventory are not independent.

## Measures

- 16 findings · 0 critical · 4 high · material omissions 3 (W-03, W-04, W-16).
