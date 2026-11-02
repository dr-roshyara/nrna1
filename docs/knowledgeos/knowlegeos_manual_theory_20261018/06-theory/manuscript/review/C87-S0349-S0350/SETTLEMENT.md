# Window C87 (S0349–S0350): settlement

*2026-11-02. Section: §8.80 (v2). Review: `REVIEW-REPORT.md` (sha256 in `SHA256-REVIEW.txt`), W-01–W-14. 0 critical, 5 high. Every finding accepted after checking it against the sources and the book (S0344 L608; S0348 L40, L47; S0349 L40–44, L82, L94, L150; S0350 L92; §8.19 L245; §8.71 C4 L93; §8.75 C4 L82; §1.14.1 L161).*

## Theory first

- **Self-registration, two senses** (C1, W-01): a REGISTER naming the verifier followed an instruction to it to "register yourself", before the capability was commissioned and against the recorded sequence's Governance REGISTER (S0348 L40; W-09). That the verifier appended it is Int.
- **§8.78 C4's open question answered** (W-04): yes in design (S0349 L25, L170), and in practice (S0350). The earlier one-writer reason (§8.19, S0139) is relaxed, not answered. R3 on §8.78 C4.
- **MATCH under premises** (C2, W-02): by construction under (p1)–(p2); on the better-supported branch it holds for any caller presenting the label.
- **Appointment by role** (C2, W-05): a change over time from appointment by label; §8.79 C4's uncovered case dissolved by design. R3 on §8.79 C4.
- **The first session** (C3, W-10): no comparison, no human START, no predecessor for its HANDOFF; the root-act question is already §1.14.1 and §8.75 C4, so no new question for the Principal.
- **Formal independence** (C4, W-03, W-07): the bars are the producer's, their passing self-declared; "VERIFIED" exceeds "failed to falsify" for open-world claims only.
- **Seq 4–7** (C5, W-08): after seq 7 no stale `true`; the interval undetermined. R3 on §8.79 C2.

## Dispositions

| W | Sev | Disposition | Where |
|---|---|---|---|
| W-01 | high | accepted: senses (a)/(b) defined | head; PL1; C1; C4 |
| W-02 | high | accepted | title; PL2; C2 |
| W-03 | high | accepted (W's own inventory errors withdrawn) | C2; C4; PL4; .5 |
| W-04 | high | accepted; R3 on §8.78 C4 | C1; homes |
| W-05 | high | accepted; R3 on §8.79 C4 | title; PL2; C2 |
| W-06 | normal | accepted (checked: S0350 L92) | .3 |
| W-07 | normal | accepted | PL4; C4 |
| W-08 | normal | accepted; R3 on §8.79 C2 | PL5; C5 |
| W-09 | normal | accepted | C1 |
| W-10 | normal | accepted: question restated, none new | title; PL3; C3 |
| W-11 | normal | accepted | C1 |
| W-12 | normal | accepted | PL2; C2 |
| W-13 | normal | accepted | .5 |
| W-14 | normal | accepted | .3 |

## R3

- §8.78 C4 (an incoming session's self-registration answered in design and practice); §8.79 C2 (after seq 7 the producer's lane HANDED_OFF; seq 4–7 undetermined); §8.79 C4 (appointment by role dissolves the uncovered case).

## Checks at settlement

- Quote check on v2 against S0344, S0348, S0349, S0350: 112 fragments; every miss is a quotation of the book (§8.19, §8.29 C1, §1.14.1), a draft quotation (§8.80.5), the defined term, or the disclosure marker.
- R2 (`undefined_terms.py --scope 08.80`) on v2: tier A–C 0 in scope; tier D judged as ordinary English or the source's sense: answered appender appointment because commission, a word naming a check, direct-write once predecessor quoted witness 

## Procedure

- W's step-1 inventory was saved the same second the Professor's reading began; every part of §8.80 was written after it (§8.80.5). W disclosed two errors in its own inventory (W-03), adopted in the draft, now withdrawn. W's first combined copy command was refused by permissions and rerun split; no git commands; R2 without the corpus; W's quote script mispaired nested quotes, so its quote check was by hand.

## Measures

- 14 findings · 0 critical · 5 high · material omissions 4 (W-04, W-05, W-06, W-10).
