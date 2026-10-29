# The G1 theory turn (evidence in the record): review commission (reviewers A and B)

*2026-10-29. Rulebook v1.4 §12b (theory turn). The Principal's instruction for the G1 turn (research question: what must the record hold so that each displayed class — refuted / not attested / supported by recorded evidence — can be read from the record alone? Starting point: evidence as entry kinds of the one designated record, accepted under the evidence area's own rule; a separate record only if shown necessary). Instruction: `03-methodology/REVIEWER-INSTRUCTIONS.md` (theory turn review). W = `docs/knowledgeos/knowlegeos_manual_theory_20261018/`.*

## The object
`06-theory/manuscript/chapter_09/09.18-evidence-in-the-record.md` (draft), with the model `tmp/tools/ce_model.py` **v21** (new: `W_FIELDS`, `in_generic`, `s_part`, `in_D_S`, `in_D_W`, `append_W`, `wclass`, `random_evidence`; checks 33–35) and its run `tmp/tools/ce_model-run-20261029-v21.txt`. Read with §9.10, §9.13, §9.15, §9.16 and Ch. 10 (§10.2.3, §10.3.6, §10.4, §10.5).

## Files you may open (only these)
- this brief; `03-methodology/REVIEWER-INSTRUCTIONS.md`; `03-methodology/IMPLEMENTATION-STRATEGY-PROPOSED.md` §3;
- the working book: `06-theory/manuscript/chapter_*/` (any section), `06-theory/CONCEPT-REGISTER.tsv`, `06-theory/THEORY-MAP.md`, `06-theory/RESEARCH-PURPOSE.md`, `06-theory/EXTERNAL-SOURCES.md`, `06-theory/manuscript/BOOK-OUTLINE.md`, `06-theory/manuscript/TARGET-TOC.md`;
- W's tools (`tmp/tools/`, read-only; you may run them, including `ce_model.py`, **on a scratch copy**, and you may probe it with your own records) and `02-FILES.jsonl`;
- corpus sources **only from** `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`; check each path there first, and list every one you open.
- **Never:** any other file under `03-methodology/` (the decision record included) or `00-access/`; any review folder except this one (restrict every search to `chapter_*` directories and the files above; do not search `06-theory/manuscript/` recursively); `review-architecture/`; S0155, S0156, S0157, S0161, S0170, S0174; `docs/knowledge_tranfer/*`; sealed, hold-out or quarantine files; `docs/knowledgeos/chronological-read/*`; `.env*`; any EKS or application code. If a file is refused or not allowed, record it and continue; do not inspect it another way.

## Questions
1. **The checks of REVIEWER-INSTRUCTIONS** for a theory turn: claims against cited book and source lines; labels; homes; levels; R1 (`register_home_check.py`); R2 (`undefined_terms.py --scope 09.18`, judge every in-scope candidate); the quote check; R3 for any correction; a reference to a missing claim checked as possible lost content.
2. **The research question:** does §9.18 show what the record must hold for each class to be read from the record alone? Is anything missing, or anything included that is not needed?
3. **The five premises, G8, and the class rule g0:** for each, is the result right, and are "necessary?", "kernel or application?" and "what would disprove it?" answered soundly? Is "correction is standing's annulment" right under T10-10 (1)? Is "no completeness rule needed" right, and is the reading of "not attested" honest to promise (b)?
4. **Containment (§9.18.6):** is the argument sound (W's atoms as sinks; generic clauses not containable)? Does check 33 test what it claims? Probe it: try to build a record in which a standing value changes when W entries are added or removed, or a W-clause breach that is not contained. Is the proposed two-level restatement of §10.3.6 (d3) well formed, and does it change any kernel law?
5. **The model v21:** do checks 1–32 reproduce v20 (compare with `ce_model-run-20261028-v20.txt`)? Do checks 33–35 test what they claim? Any defect in `wclass`, `in_D_S`, `in_D_W` or `append_W`?
6. **Separate record (§9.18.8):** is "not shown necessary" justified?
7. **Normative choices:** is anything presented as decided that is a choice for the Principal (beyond T9-12)? Is T9-12 well posed, with a sound recommendation?
8. **Prior work:** §9.10 O2/Q4, CON-0150/0212/0214/0217/0221, S0237, S0294, S0887: fair?

## Rules (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.
- Edit nothing in the repository (scratch copies only, outside it). No corpus or book text to any outside service. Do not reproduce any private person's name.

## Report
- Tables: findings (ID · finding · severity: critical / high / normal · evidence · correction · R3 lines · resolved when); a verdict per part (§9.18.4, each of (i)–(v), G8, g0, §9.18.6, §9.18.7, §9.18.8, the model).
- A summary of at most 10 lines, and one line on process independence.
- End with every file you opened and every command and search you ran.
- Deliver it once, beginning "G1 REVIEW REPORT <A or B>".
