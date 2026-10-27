# Access note: allowed sources that describe files not on the allowed list

*2026-10-27. The Professor. Found by the C33 reviewer (W-06) and checked by the Professor against two files only: the index (`02-FILES.jsonl`) and the allowed list (`CORPUS-ALLOWED.tsv`). No sealed, hold-out or quarantine list was opened or probed. None of the files named below was opened, by the Professor or, as their transcripts show, by the reviewers.*

## Facts

Seven files of the "Architecture Review Set" are on the allowed list: S0175–S0181, and S0182, the set's index, which is next in order and not yet read. Each summarizes corpus files, and some of the files they summarize are **not** on the allowed list:

| Not on the allowed list | Index ID | Named or described in (allowed) | Lines |
|---|---|---|---|
| `brainstorming/20260820-123902-kos-design-patterns.md` | **S0155** (recorded as exposed, sealed hold-out S3; `review/C29-S0151-S0158/ACCESS-INCIDENT.md`) | S0176; S0181; S0179; S0182 | S0176 L19, L126; S0181 L16, L29, L69, L82, L97, L99, L101, L106; S0179 L49, L55, L107, L150, L158, L234, L241; S0182 L40, L43 |
| `brainstorming/20260819-204018-kos-product-architecture-v2.md` | S0161 | S0178; S0179; S0182 | S0178 L16, L32, L35, L51, L69, L80, L86, L142, L144, L157, L159; S0179 L33, L35, L50, L129, L145; S0182 L39 |
| `brainstorming/20260820-115444-poa-vs-ddd-decision-hierarchy.md` | S0156 | S0175; S0179; S0182 | S0175 L19, L59, L114, L120; S0179 L81, L137; S0182 L44 |
| `brainstorming/20260819-104830-election-only-mode-readiness.md` | S0157 | S0175; S0182 | S0175 L24; S0182 L44 |
| `brainstorming/20260819-104804-voting-election-outcome-refinement.md` | S0170 | S0175; S0182 | S0175 L25; S0182 L44 |
| `brainstorming/20260819-104802-delegation-map-domain-owners.md` | S0174 | S0177; S0182 | S0177 L20, L167; S0182 L41 |

*Line numbers are from a `grep` of each allowed file for the timestamp in the file name. Some summarized content may not carry the timestamp on its line.*

## What this means (facts, not a ruling)

- **The content of these files reached the Professor and the C31–C33 reviewers through allowed sources.** This happened in C32 and C33, while reading S0175–S0181 in index order, and again in the reviews. The extent varies:
  - S0155: a few claims (for example that Hexagonal Architecture is "already adopted", and pattern positions);
  - S0161: product-first framing, aggregates and a lifecycle;
  - S0156: the POA-versus-DDD question;
  - S0157, S0170 and S0174: one line each, "off-topic, no KnowledgeOS claims".
- **For S0155, the Principal's rule applies:** "nothing from it is ever used". Two book passages used S0155 content as quoted by the summaries: §8.25 C7 ("Hexagonal Architecture is stated as already adopted", S0176) and §8.26 C1 (S0179 P1, S0181 L27–29). **Both are removed** in the C33 settlement. They remain only in git history (drafts `019ed5b47`, `f0fb39c29`, the C32 settlement `167e5a7b8`) and in the reviewers' saved reports, which are records and are not rewritten.
- **For S0156, S0157, S0161, S0170 and S0174:** why they are not on the allowed list is not known to the Professor, and the sealed list is not probed. As a precaution, the book uses **nothing** these summaries say about them. One instance resting on S0161 (S0179 D1, in §8.26 C1) was removed.
- **The reading ahead:** S0182, the next source in order, names all six files.

## For the Principal

Whether this exposure affects the hold-out test, or the reading of S0182 and similar summaries, is a question for the Principal. It is reported at the consolidation gate.
