# V-1 — Brief for the independent author of the synthetic mini-corpus (execution material)

| Field | Value |
|---|---|
| Plan | M-03 §5 V-1 |
| State | **READY, NOT RUN.** Needs Principal decisions D-1, D-2 (author), D-3 (sealing), D-4 (scorer) |
| Written by | Professor. It contains **requirements only**: no items, no wording, no key |

## 1. Who may write it

- someone who has **not** read the KnowledgeOS corpus, and who is not Professor (Session B);
- the author must not see Professor's notes (`01-basis/`, `03-methodology/`) beyond this brief.

## 2. What to write

- **10–12 short documents**, each with a date, written as if by researchers developing a theory over several weeks.
- **Domain:** a made-up theory similar in *shape* to a knowledge or governance theory (definitions, rules, revisions, some formal statements). **Not** KnowledgeOS, and no text taken from this repository.
- **At least 24 planted items** in the key (12 categories × 2), typically 24–30. Every category below at least **twice**, in different documents. Each key item belongs to exactly one category, so the count is unambiguous. *(Corrected 2026-10-18: the earlier "about 20" could not satisfy "every category twice".)*

| Category | What to plant |
|---|---|
| explicit definition | a term defined plainly |
| indirect definition | a term whose meaning is given only by examples or by contrast |
| unfamiliar vocabulary | a central idea expressed in words unlike common theory vocabulary |
| revision | a later document narrows, changes or retracts an earlier statement |
| genuine contradiction | two documents that cannot both hold |
| pseudo-contradiction | apparent conflicts that are really a scope difference or a terminology difference |
| implicit assumption | an argument that only works if something unstated is true |
| mathematical error | a formula or claim that is wrong, presented as correct |
| diagram-only relation | a relation shown in a text diagram (ASCII) and stated nowhere in prose |
| evasive contrary statement | a statement contradicting a claim, worded so a keyword search would miss it |
| misleading later summary | a late "overview" document that misstates an earlier concept |
| genuine ambiguity | a passage that honestly admits two readings; mark it as such in the key |

Mark which items are **foundational** (other items depend on them).

## 3. The key

One row per item: id · category · foundational (yes/no) · document and line range · what a correct extraction must say · for ambiguities, the defensible readings.

## 4. Sealing (commit–reveal, per D-3)

1. Put the documents (not the key) at the location the Principal names.
2. Keep the key **outside the repository**. Give its sha256 to the Principal for recording in the governance log.
3. Do not reveal the key until Professor's extraction is frozen and its sha256 recorded.
4. Then give the key to the scorer (D-4), who fills `V1-SCORING-SHEET.tsv` with outcomes CAPTURED · CAUGHT-BY-CONTROL · MISSED · MISREAD · AMBIGUOUS-IN-MATERIAL · NOT-SCORABLE.
