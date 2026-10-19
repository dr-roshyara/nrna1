# Rule RK-1: one home per fact, a slower pace, the checks kept

| Field | Value |
|---|---|
| Status | **APPROVED** by the Principal, 2026-10-19 (in-session, ≈08:00Z) |
| Decision (verbatim) | "yes approve, write the addendum and apply from S0026" |
| Applies | **from S0026 onward**. Work on S0001–S0025, and the handling of review C4 (S0021–S0025), stays under the earlier rules. No retroactive change (rules apply forward: the second entry numbered 24 in `METHOD-VERSIONS.md`) |
| Register | `METHOD-VERSIONS.md`, entry 25 |
| Changes | rule L-4 (where hashes are recorded) · CV-1 §4a (where INVALID concepts are tracked) · ML-1 practice (chapter change logs) · the working practice (pace, record length) |
| Keeps unchanged | M-04 v1.0 steps 1–7 · the quote check · CV-1 statuses and concept IDs · the five-file review with a blind first phase (AR-9, AR-10, AR-11) · CV-1 §8a second attempt · SQ-1 sequence check |

## Why

1. **Speed was hurting quality.** Review C3 found six foundational errors. All were book sentences stronger than their sources. Four sources were read in about 16 minutes (S0022–S0025).
2. **The same facts were written in up to six places for each source.** These were the record's integration table, the revision log, the chapter change log, the chapter plan, the goal file and the read log. Invalid concepts were also written twice: once in the register and once in a TODO file.

## 1. One home per fact

| Fact about a source | Its only home | Others may only point to it |
|---|---|---|
| that it was read (time, path, sha256, lines, depth) | `tmp/corpus-reading/READ-LOG-CHRONO-20261018.tsv` | — |
| what the source says (quotes with anchors, claims, contradictions) | the record `tmp/corpus-reading/records/E-Snnnn.md` | — |
| what happened to each idea (label, action, where it went) | the record's integration table (M-04 steps 3–7) | the revision log cites the idea ID (`W-nnn`) |
| what book text changed | `06-theory/manuscript/BASELINE-REVISIONS.md`: **one row per revision** (RC ID, where, what, idea IDs) | — |
| before and after hashes | `BASELINE-REVISIONS.md`, **once per five-file batch**: before-hashes at the batch's first book edit, after-hashes at the batch checkpoint, for every manuscript file the batch touched | the checkpoint cites them |
| concept status | `06-theory/CONCEPT-REGISTER.tsv` | the book's `[CON-nnnn · status]` tag |
| current position | `tmp/GOAL-AND-CURRENT-POSITION.md`, updated **at batch checkpoints** (and when the Principal asks) | — |
| chapter structure | `06-theory/manuscript/CHAPTER-PLAN.md`, updated **only** when a chapter or section is added, removed or reordered | chapter index files |

**Chapter change logs** stop growing. Each chapter's change log gets one final line: "From S0026: changes are recorded only in `BASELINE-REVISIONS.md`." Existing lines stay.

## 2. Where INVALID concepts are tracked (CV-1 §4a, amended)

- An INVALID concept is tracked in the **concept register**. Its `follow_up` field holds what would settle it and the next action. The book tag `[CON-nnnn · invalid]` points there.
- A chapter `TODO.md` gets a row **only** for a problem that is not a single registered concept: a gap between concepts, an SQ-1 sequence gap (`SEQ-nnnn`, as SQ-1 §4 requires), an undefined source term, a missing record, or an open decision.
- Existing TODO rows stay. Rows for concepts already there continue to be updated when a later source touches them.

## 3. Pace and depth

- **At most two sources per turn.** After two, stop and report to the Principal, unless the Principal says otherwise.
- **Strength check before writing.** Before writing book text, compare every sentence with its source. The sentence must be no stronger than the source: no "settles" where the source "is consistent with", no "dissolved" where it "proposes". Put a source's claim in the source's mouth ("S00nn claims …") unless the book has checked it.
- **A real counterexample attempt before VALIDATED.** Name a concrete case that would break the concept, and say why it does or does not. If no real attempt can be made, the concept stays INVALID with the reason "no counterexample attempt possible yet".

## 4. Shorter records

A record keeps:
- the header (fingerprint, dates, layers of a multi-temporal file);
- the quotes with anchors (checked);
- claims with the source's own strength words;
- contradictions and uncertainties;
- interpretations, kept apart;
- the integration table;
- the CV-1 concept table.

It drops the "in business language" overview unless the book text does not cover the source. It also drops sections 3 (assumptions), 6 (examples), 7 (mathematical statements), 8 (terms) and 9 (references) where they would be empty or repeat other sections. A one-line "none" is enough.

## 5. What is not changed

The quote check · reading every source in full · the allowed-list check before opening · the five-file review with a frozen blind first phase · the CV-1 second attempt · the SQ-1 sequence check · the access record entries for every review · the session's restrictions (sealed files, no commits, forward-only rules).

## 6. Recorded with this rule

Rule SQ-1 (approved 2026-10-18, entry 24) was **not** included in the instructions for reviews C3 and C4 as first issued. It was added to review C4's Phase 2 on 2026-10-19 (≈08:01Z), while that phase was running. Review C3 had no SQ-1 check. Under the forward-only rule, C3 is not reopened; the omission is recorded here and in the access record.
