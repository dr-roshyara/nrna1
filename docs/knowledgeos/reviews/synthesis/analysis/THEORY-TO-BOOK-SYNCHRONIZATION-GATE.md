# THEORY-TO-BOOK SYNCHRONIZATION GATE (proposed 2026-08-30; requires HPA adoption)

**Purpose:** define the one lawful path by which a result from the theory / independent
verification lane may become book content. **Nothing crosses this boundary by arriving.** The gate
is a protocol, not code — no repository mechanism requires an implementation, and none is built.

**Standing rule it enforces:** *Research discovers · Verification tests · Governance decides ·
The book records.* The book lane never performs the first three functions, and a report's
existence is never evidence for its content.

## Entry conditions (all ten must hold before ANY book change is drafted)

| # | Condition | How it is checked | If it fails |
|---|---|---|---|
| 1 | **The verification report exists as a file** in the repository — not a conversation, not a summary relayed into a session | file path recorded; II.1's own rule applies ("the filesystem artifact is the record; chat is only an execution report") | STOP — nothing to synchronize |
| 2 | **Stable artifact identity**: md5 + line/word counts recorded, and the identity is quoted in any later citation | BA-ED2-13 | STOP — an unidentified artifact cannot be cited |
| 3 | **Evidence classes explicit** per claim (TESTED / READ / COMPOSITION / SPECIFIED / HYPOTHESIS / REQUIRED-BY-COHERENCE / NOT ESTABLISHED / OPEN) | read the report; unclassed claims are listed, never inferred | classed items may proceed; **unclassed items are blocked**, not guessed |
| 4 | **Findings separated from interpretations** (⟦E⟧ vs ⟦INT⟧, or an equivalent the report declares) | 3C precedent | STOP on any claim where the two are fused |
| 5 | **Unresolved items listed** by the report itself | explicit register or section | STOP — a report with no residue is a claim of completeness |
| 6 | **Contradictions preserved**, not harmonized — including contradictions with the current book | diff against the OQ table in `BOOK-READINESS-AUDIT.md` §B | contradictions are recorded as findings; **never** silently reconciled |
| 7 | **Historical vs newly discovered material distinguished** — what the record always said vs what this lane discovered | the five-layer provenance model (audit §D) | STOP — undated material cannot enter a historical reconstruction |
| 8 | **Proposed book changes identified** by location (part/chapter/section) and by change class (1 editorial · 2 evidence/provenance · 3 structure · 4 theory · 5 governance) | GN-71 classification gate | classes **4 and 5 STOP** at the book boundary and go to their own lane |
| 9 | **No claim promoted merely by appearing in the report.** A status change (e.g. NOT ESTABLISHED → TESTED, or an OQ closing) requires an explicit HPA/architecture-lane ruling that the book then *records* | ledger entry cited by ID | STOP — the book may report *that a lane asserts X*, never *that X holds* |
| 10 | **Book changes undergo their own editorial/evidential review** — the standing controls (BA-ED2-01…14) plus, for any status change, an independent check | per-chapter verification + independent reviewer | a change that cannot pass its own review is not made |

## Procedure (six steps, in order)

**S1 · Receive and record.** Register the report's identity (path, md5, counts, date, authoring
lane). Record it as *received*, verbatim if it is to be quoted. **No assessment yet.**

**S2 · Classify without acting.** For each claim: change class (1–5), affected book locations
(use audit §B as the citation index), evidence class as the report states it, and whether it is
historical, newly discovered, or corrective. Produce a synchronization findings list. **Still no
edits.**

**S3 · Route.** Class 4 (theory) and class 5 (governance) leave the book lane immediately and go
to the architecture/governance lane for ruling. Classes 1–3 remain, but any class-1/2/3 change
whose *justification* depends on an unrules class-4 item is blocked until that ruling exists.

**S4 · Await ruling.** The HPA (or the architecture lane, where delegated) rules on the routed
items. **Until a ruling exists, the book's existing wording stands** — including wording the
report contests. Contested-but-unruled wording may be annotated in the unresolved register; it is
not changed.

**S5 · Draft under authorization.** Only ruled changes are drafted, bounded to the authorized set,
with the provenance model applied: the earlier record is quoted as it stands, the later discovery
is presented as later, corrections are additive, both layers preserved.

**S6 · Verify and close.** Run the standing controls plus a targeted check that (a) no evidence
class was upgraded beyond the ruling, (b) no historical artifact was rewritten to agree with the
new finding, (c) the six binding GREEN protections survive, (d) artifact identities are
resynchronized. Produce a closure record. **Then stop.**

## Prohibitions (permanent, not per-handoff)

- The book never rules; it records rulings.
- The book never resolves an OQ, closes a gap, or promotes a status on its own authority.
- The book never treats an AI-produced report (any model, any session) as engineering evidence.
- The book never edits a historical report, a frozen artifact, or Edition 1 to agree with a later
  finding.
- The book never uses its own acceptance as evidence of theoretical correctness.
- Where a report and the record conflict and no ruling exists, **the record stands and the conflict
  is recorded.**

## Freeze rule (mid-production arrival)

If a report arrives while a Part is in production, the affected chapters are **frozen at their
current artifact identity** until S4 completes. Production may continue only on chapters the
synchronization findings list does not touch. This exists because the highest-risk chapter —
IV.4, the open-questions chapter — is precisely the one a verification result would move.
