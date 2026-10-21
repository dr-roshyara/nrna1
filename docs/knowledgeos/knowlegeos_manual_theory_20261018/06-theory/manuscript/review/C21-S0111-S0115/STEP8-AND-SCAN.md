# Window C21: per-source measures, step 8, falsification attempts, book search, mechanical checks

*2026-10-21. Sources: S0111–S0115, in order, each read whole (depth full), each started and closed in turn. New: Ch. 8 §8.14 (C1–C7); register CON-0208–CON-0212; the Ch. 8 index row. Window size 5. Kernel work kept proportionate (the Principal, 2026-10-21): the changed theory under review is the application of the Principal's decisions after C20 (§9.16 R4a v7, §9.13 v12, model v13), done before reading; §8.14's kernel links are stated as Int and proposed, not applied.*

## Per source (time, depth, findings)

Time = stop minus start (READ-LOG): one whole-file read plus the close, not reading effort.

| Source | Lines | Start → stop (UTC) | Time | Depth | Findings | Used in §8.14 | Repeats |
|---|---|---|---|---|---|---|---|
| S0111 | 75 | 05:33:29 → 05:33:39 | 10 s | full | 3 | C1 | "no verdict the act does not contain" (CON-0207) |
| S0112 | 77 | 05:33:39 → 05:33:50 | 11 s | full | 4 | C1, C2, C6 | — |
| S0113 | 97 | 05:33:50 → 05:34:00 | 10 s | full | 5 | C2, C6, C7 | identity (S0112) |
| S0114 | 137 | 05:34:01 → 05:34:14 | 13 s | full | 7 | C3, C4, C5 | NOT ATTESTED never PASS (§8.12 C1) |
| S0115 | 99 | 05:34:14 → 05:34:24 | 10 s | full | 4 | C3, C4, C5, C7 | the category flag (S0113), the dispute asymmetry (S0114) |

**Skim, honestly applied.** No source was skimmed. S0115 is the nearest: it is a mapping table over S0114's definitions, and its own additions are the four-prerequisite conjunction, no enforcement on an inference from absence, the claim-scoped suspension and the proportionate floor. Still no skimmed source exists for the §3 safeguard; the reviewer judges depth blind.

## Book search (lexical and by meaning; top hits read)

| Query | Hits read | Relation |
|---|---|---|
| meaning: existence from presupposition vs performance | CON-0199 (§8.12 C7); CON-0153 | evidence for the performance limb (C1) |
| meaning: prevention / enforcement location | §9.16 R3a, R4 | parallel (C2) |
| lexical: annul, void, invalidat, supersed | §9.13 (annulment void from the start); §0.5 I-7 | compares (C3) |
| lexical: dispute, flag, mark | §9.13 marks; F24 | compares (C4) |
| meaning: absence, completeness, C1 = constitute | §9.13 C1; §9.15 L4; §8.4 C3 (an absence claim states its scope) | sharpens (C5) |
| lexical: attest, identity, binding | §9.15 L2, L10; §8.6; §8.12 C2 | bears on L10 (C6) |

## Step 8: dependencies

| Relies on | Status | Check |
|---|---|---|
| §8.12 C7 (CON-0199 PROVISIONAL) | **unverified** | C1 is consistent evidence, not proof |
| §9.13 (annulment, marks, C1) | changed theory (v12), **not yet reviewed** | C3–C5 compare; nothing in §9.13 changed by this window |
| §9.15 L4, L10 | L10 settled at C20; **unverified** | C5, C6 bear on them; nothing changed |
| §9.16 R3a, R4a v7 | changed theory, **not yet reviewed** | C2, C7 parallels |

## Falsification attempts

| Concept | (a) strongest counterexample | (b) boundary | (c) rival | (d) falsifier |
|---|---|---|---|---|
| CON-0208 | the authority may have declined YES for reasons other than non-performance (scope uncertainty, caution) | one decision | the decision shows nothing about grounds of existence | a later act of the authority declaring a never-performed capability existent |
| CON-0209 | an enforcing rule inside an already control-plane capability decides nothing new | capabilities whose category is open | category is decided by ownership, not by response type | an act mapping BLOCK that the estate holds category-neutral |
| CON-0210 | a receipt forged or recorded in error: its claim of occurrence **was** false from the start | claims of occurrence, recorded correctly | invalidation as falsification (the usual reading of "invalid") | a record whose correct historical claim is later shown false by a later act alone |
| CON-0211 | a dispute with no effect at all is just a comment, so "flag" adds nothing | receipts | dispute as a lifecycle state (D3.5's chain) | a receipt that cannot be both superseded and disputed |
| CON-0212 | an append-only, realized store with a failed read still has the absence answered by a cached copy | records read from the authoritative store | absence as weak evidence even in an unrealized store | an estate act enforcing on absence in an unrealized store, uncorrected |

## Mechanical checks

- **Quote check:** §8.14 14 found, 0 not found. Whole book: 14 NOT FOUND, equal to the baseline.
- **R1:** 188 rows with a home; 1 inherited flag (CON-0133). CON-0208–0212 in §8.14 with their tags.
- **R2:** no candidates in §8.14.
- **close_source.py:** S0111–S0115 full, in order.
- **Model:** `ce_model.py` v13, run `ce_model-run-20261021-v13.txt`, all assertions passed.
