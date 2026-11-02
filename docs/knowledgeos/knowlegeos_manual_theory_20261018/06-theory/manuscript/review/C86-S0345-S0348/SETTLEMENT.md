# Window C86 (S0345–S0348): settlement

*2026-11-02. Section: §8.79 (v2). Review: `REVIEW-REPORT.md` (sha256 in `SHA256-REVIEW.txt`), W-01–W-17. 0 critical, 4 high. Every finding accepted after checking it against the sources and the book (S0344 L537–571; §8.73 C2 L56; S0342 L127; S0343 L102; S0345 L4).*

## Theory first

- **The producer records its own STOP** (C1, VALIDATED): S0346 assigns the STOP's recording to Governance as a human act (L24, L27, L65, L85); the STOP at seq 4 is `recordedBy implementation` (S0348 L25). Whether a human decision stood behind it is undecidable from the sources' silence (W-07: not K3). The end of work was recorded by an AST-015 STOP transition; R3 on §8.72 C4 and §8.78 C4.
- **A sticky-STOPPED work item; the lane's state not reported** (C2, W-04–W-06): the CONTINUATION is the one omitted step that needs a human decision; the producer's lane is stated in two branches (stale `true` now, or after the CONTINUATION). R3 on §8.77 C3.
- **The verifier's prompt, authorship not shown** (C3, W-02, W-11): only five words of the PO/ARB are verbatim; the producer may be the prompt's author; "Governance-recording" practises §8.73 C2's reading (i). The bearing on the book's own briefs is recorded beside §8.76 C4.
- **A placeholder appointment between the commissioned model's two branches** (C4, W-01, W-03): §18 (no appointment) and §19 (compare with the expected actor) both fail to cover it; the producer reports the model implemented. "Architecture" is the recorder's addition (W-12). The `false` is a prose judgement under the field's name (W-13).
- **"Already assigned": safe, not right** (C5, W-08).

## Dispositions

| W | Sev | Disposition | Where |
|---|---|---|---|
| W-01 | high | accepted (checked: S0344 L537–571) | title; PL4; C4; .5; index |
| W-02 | high | accepted (checked: §8.73 C2 L56) | C3; homes |
| W-03 | high | accepted | PL4; C4 |
| W-04 | normal | accepted: lane count marked Int | C2 |
| W-05 | high | accepted: two branches | title; PL2; C2 |
| W-06 | normal | accepted | C2 |
| W-07 | normal | accepted; R3 on §8.72 C4, §8.78 C4 | C1 |
| W-08 | normal | accepted | PL5; C5 |
| W-09 | normal | accepted (checked: S0344 L435, L449, L515, L539) | .3 |
| W-10 | normal | accepted: W's own inventory errors withdrawn (checked: S0343 L102) | .3; .5 |
| W-11 | normal | accepted | PL3; C3 |
| W-12 | normal | accepted (checked: S0345 L4) | C4 |
| W-13 | normal | accepted | C4; .4 |
| W-14 | normal | accepted | C4 |
| W-15 | normal | accepted; R3 on §8.77 C5, §8.78.3 | .3 |
| W-16 | normal | accepted | lineage; C5 |
| W-17 | normal | accepted | head |

## R3

- §8.72 C4 (the end recorded by a STOP transition, by the doer); §8.77 C3 (STOP followed by a sticky-STOPPED item; the lane not reported); §8.77 C5 and §8.78.3 (the separating reading taken); §8.78 C4 (a STOP transition used in practice).

## Checks at settlement

- Quote check on v2 against S0344–S0348: 91 fragments; every miss is a draft quotation (§8.79.5), the disclosure marker, or a span checked by hand (S0342 L127 "mirroring `seq 1 → 2 → 3`"; S0343 L44 "renders business language onto").
- R2 (`undefined_terms.py --scope 08.79`) on v2: tier A–C 0 in scope; tier D judged as ordinary English or established: appender (§8.73 C1), appoint, appointment, asked, because, doer, eight, escalate, generalize, once, plus, processe(s), quoted, touch.

## Procedure

- W's step-1 inventory was used before review; two items taken from it were W's errors, now withdrawn (W-10); disclosed in §8.79.5. W's first compound copy command was refused by permissions and rerun in simpler form; no git commands; R2 without the corpus.

## Measures

- 17 findings · 0 critical · 4 high · material omissions 4 (W-01, W-05, W-14, W-16).
