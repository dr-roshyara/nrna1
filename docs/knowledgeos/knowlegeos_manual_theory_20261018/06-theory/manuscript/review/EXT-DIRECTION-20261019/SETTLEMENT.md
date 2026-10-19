# Settlement of the extraction review (EXT-001 … 029)

*2026-10-19. Report: `REVIEW-REPORT.md` (non-independent check, by the Principal's decision). The Professor re-checked findings 1, 2, 3, 12 and 16 against the sources; they hold. All 18 findings are accepted. NOTES.md is kept as written; its corrections are listed below.*

## Verdicts applied

| Item | Verdict | Effect |
|---|---|---|
| Deliverable 5 | PROVISIONAL | must include required capabilities; existence is relative to them (finding 1) |
| Candidate boundary test | criterion PROVISIONAL · removal procedure INVALID as a boundary test | rewritten as delegability by relocation (finding 3) |
| Direction section | PROVISIONAL | corrected (below) |
| §5.4 C7 | PROVISIONAL | revised |
| §3.10 C8 | PROVISIONAL | the Hyp re-anchored |

## Changes made

| Finding | Where | Change |
|---|---|---|
| 1, 2 | RESEARCH-PURPOSE deliverable 5 and "How"; THEORY-MAP | the tuple is degenerate with preservation alone (∅ is minimal); required capabilities added; preservation as m(s, x) ∈ 𝒮_I ∪ {⊥} |
| 3 | purpose test; THEORY-MAP | delegability, tested by relocation; removal tests necessity |
| 4 | purpose intro; C7; THEORY-MAP | "AI replies in the Principal's brainstorms, partly restating unidentified attachments"; C7's Int made conditional; provenance question for the Principal |
| 5, 18 | C7 | three improvement orders: inclusion compatible with C2 (loses status), expected size conflicts, weighted open; Sat binary, "similar" not "same"; G1–G10 classify requirements, C3 status (orthogonal); EXT-016 cited |
| 6 | purpose; THEORY-MAP | Rigveda withdrawn to metaphor; Hegel kept as axiom A6 |
| 7 | purpose table; THEORY-MAP | collapses: a mechanism fork across four files, same outcome |
| 8 | purpose | EXT-029's "core" scoped as unverified |
| 9 | purpose table; THEORY-MAP | EXT-029's epistemic-gap kinds as the benchmark; Neither does not split Unknown |
| 10 | purpose | convergence a hypothesis to test; stability ≠ correctness; identifiability open |
| 11 | THEORY-MAP | capture–recapture only under independence |
| 13, 14 | purpose; THEORY-MAP | forks added: order of work; where the DDD contexts sit |
| 15 | purpose table | "differs" for the central question, minimality, collapses; performative row gets EXT-025/026 |
| 16 | C8 | Hyp anchored on V_possible vs V_relevant (EXT-029 L357–375) |
| 17 | THEORY-MAP | R_K list completed |
| Check D 5–10, 14, 15 | THEORY-MAP | candidate laws and invariants; requirements discovered; time-varying M; designed ambiguity |

## Corrections to NOTES.md (not rewritten)

- EXT-010: preservation is not "well-formed"; A1 + A2 is degenerate, and A1 is inconsistent with ⊥ as written.
- EXT-018 T9 (Φ∘Φ = Φ ⇒ Fix(Φ) exists) is **true**; EXT-014 T7 is trivially true. Neither is an error.
- EXT-029: the table gives uncertainty ≈ 1 − 0.4·coverage (constant ratio, likely generated), not 1 − coverage; it has no accuracy column, so it cannot support L929.
- EXT-029 L422 note: capture–recapture does not apply to this book's reviews without independence.
- EXT-017: Brandom is not the only concrete R_K proposal.
- Missed source errors are those of report §4 (EXT-004, 006, 008, 019/020, 022, 026, 027, 028, 029). None is used by the book.

## Open, for the Principal
- Which documents are the attachments ("Gap theory", "two files", "Foundation document", "Linux document")?

## Addendum (Principal's settle instruction, 2026-10-19)

- **Kernel scaffold, tested** (`tmp/tools/kernel_model.py`, run `kernel_model-run-20261019.txt`). The toy model has 64 states, 16 of them valid, and 5 mechanisms in the pool.
  - As written, the only minimal set is ∅.
  - With required capabilities R1, R2 and preservation as m(s, x) ∈ 𝒮_I ∪ {⊥}, ∅ fails Req. {record, grant} is a minimal kernel, and so is {record, grant2}, so the minimal kernel is not unique.
  - A mechanism that breaks an invariant is in no kernel. All four assertions pass.
  - The test covers the form only. Deliverable 5 stays open.
- **Boundary test** restated as three questions (necessary? must the kernel provide it? delegable by relocation?) in RESEARCH-PURPOSE.
- **RESEARCH-PURPOSE:**
  - a 5-line summary added;
  - Q5 given verbatim (EXT-015 L247);
  - the EXT-001 quote made verbatim ("Do not prevent collapses.");
  - the central question unchanged.
  - New tool `tmp/tools/ext_quote_check.py`: 12/12 quotes found after fixing five; §5.4, §3.10 and the map are also clean.
- **Check D's 15 omissions:** all are in the map's direction block, marked as candidates, not defects.

### Attachment candidates (for the Principal to confirm; evidence only)

Branch-search scans of the allowed list were logged under "ATTACH-ID".

| Attachment | Candidate | Evidence | Strength |
|---|---|---|---|
| "Gap theory" (EXT-013 L5) | **S2377** `brainstorming/mathematical_ideas_that_can_be_implemented/20260902-082333_knowledgeos-theory-v1-0-formal-theory-of-epistemic-gaps.md` | uses EC_t "the epistemic contract" (L109, L261); has the headings "Gap Type G7 — Observability Gap" (L704) and "G10 — Representation Gap" (L812), the same G-numbering as EXT-013 L106–115; "Gap theory" occurs in it. Related: S2513 "gap-theory-v1-critical-assessment" (17 hits) | strong |
| "Linux document" (EXT-007) | **S0167** `brainstorming/_misc/20260819-224159-linux-analogy-kernel-os-model.md` | the only allowed file matching "Linus" or "technical merit" (71 mentions of Linux). But EXT-007's O1–O3 ("AI is a tool", "technical merit over ideology", "use need not be mandatory") were **not** found in it, so the attachment may be an outside document on Linux and AI | weak |
| "Foundation document" (EXT-015 R2: "Knowledge Claim") | **S0210** `brainstorming/20260822-0135-knowledgeos-conceptual-foundation-relationship-invariant-preservation.md` | the only allowed file matching "foundation document" (L13: "conceptual foundation document"). Its use of "Knowledge Claim" is not checked | weak |
| "two files" (EXT-010 L3) | **none found** | the tuple (𝒮, M, I, A) occurs in no allowed file (0 hits). Within the 29, EXT-010 is the first file with it, so the two files are neither corpus files nor earlier brainstorms in this folder | — |
