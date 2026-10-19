# Document modularity and section design (working practice)

**Status: PROPOSED.** Not approved; not policy. It applies prospectively only, and only once the Principal approves it. Prepared 2026-10-18 at the Principal's instruction "Future Document Modularity and Section Design". It is kept in the session scratchpad because the current instruction forbids changing repository state. Once approved, its natural home is beside `03-methodology/M-04-working-practice-20261018.md` and the one-page method, as a short addition and not a new method.

## 1. Rule (proposed)

For **new** theory documents and sections created after approval:
1. **Size.** Aim for about 200–500 lines per conceptual file. 500 is a soft target, not a limit.
2. **Cohesion first.** Split only where there are genuinely distinct concepts, arguments, evidence groups or responsibilities that can be read and reviewed separately. Never split to meet a number.
3. **Keep together** what must be read together:
   - a definition with the claims that use it in the same derivation;
   - a theorem with its premises and proof;
   - a counterexample with the claim it refutes;
   - a parameter with every formula it enters.
4. **Navigation.** Every multi-file document has one short overview file (purpose, file list, dependency order, reading order).

## 2. Preservation (mandatory)

Existing work stays exactly as it is:
- chapters;
- baselines and their hashes;
- section identifiers and cross-references;
- registers, logs, review and decision records;
- the §1.14.1 and bounded-guarantee work.

A file over the target is **not** a reason to change it. A structural change to existing material needs its own explicit authorization, and is then done **separately** from any change of content (§5).

## 3. Who decides

| Matter | Decided by |
|---|---|
| number of sections and files, boundaries, file names, size estimates, justified exceptions, for an authorized new document | **Professor** (technical recommendation; no per-case approval needed) |
| the general rule; changes to governance requirements, approved baselines or completed theory; structure that changes meaning, traceability or commitments; exceptions needing a reserved decision | **Principal** |
| whether a structure keeps arguments complete, assumptions and dependencies explicit, citations traceable, identifiers stable and assembly reproducible | **independent reviewer**, where review is in scope anyway |

**Ordinary organization vs authorization needed.** Organizing **new** material inside an authorized task is ordinary. Anything that touches existing material, an approved baseline, an identifier others cite, or the meaning of a claim needs authorization.

## 4. Structure plan (before drafting a substantial new document)

The plan is proportionate: a few lines for a short document, one table for a chapter. It records:
1. purpose and scope;
2. sections and files;
3. the responsibility of each;
4. dependencies, as a reading order;
5. estimated sizes;
6. exceptions with reasons;
7. how identifiers, citations, equations and cross-references are kept stable;
8. how the whole is read or assembled.

**Identifiers.** Section identifiers are **stable and file-independent**: §1.14.1 stays §1.14.1 wherever its text lives. Cross-references use the identifier, never a file path or line number. A file's name begins with the identifier range it holds.

**Assembly.** The overview file lists the files in reading order. If a single reading copy is needed, it is produced by concatenating them in that order, which is a mechanical step.

## 5. If a structural change is ever authorized

1. Record the authorized scope.
2. Keep the prior state's identity (hash).
3. Change structure only: no wording or content in the same step.
4. Check that the concatenation of the new files equals the old content, apart from the declared navigation lines.
5. Verify links, citations and identifiers.
6. Record what moved and what did not.
7. Stop and report if a baseline or an unapproved decision would be affected.

## 6. Working procedure

**Understand → plan → explain exceptions → draft (new material only) → verify across boundaries → report** (structure created, exceptions, open decisions).

## 7. Critical assessment

- **Benefits:** smaller review units, mathematical arguments reviewable in isolation, cheaper diffs and hash checks, and a lower risk of the problems seen today. Several of today's defects were contradictions between distant passages of one long file (e.g. "E3–E6" against the licensing paragraph a few lines below it; §1.17.5 against §1.7).
- **Risks:**
  1. **Splitting can hide cross-file contradictions** that a single file shows when read in full. The cross-boundary check in step 5 of the working procedure is therefore an obligation, not an option.
  2. **Line counts measure text, not reviewability.** The §1.14.1 formal model is 202 lines, but its correctness depends on §1.13, the glossary and Chapter 2 §2.11. Keeping the dependency list explicit matters more than size.
  3. **More files means more navigation to maintain.** It stays light only if overview files stay short.
- **Overlap check.** This extends the existing working practice (review in proportion; baseline continuity; documentation placement via `php scripts/doc-placement.php` for any **new** location). It adds no new method, and it must not become a second placement rule.

## 8. Observations for future planning (no action)

- Current sizes: Ch. 0 292 lines · Ch. 2 735 · **Ch. 1 1,280** · glossary 58. Chapter 1 and Chapter 2 exceed the proposed target. **No change is proposed;** they stay as they are under §2.
- The §1.14.1 repair proposal (scratchpad, 444 lines, plus an 86-line candidate model kept separate for independent attack) already roughly follows this practice. If a revised §1.14.1 is later authorized, a structure plan would be prepared first. Whether the formal model should then live in its own file is a question for that authorization, not decided here.

## 9. Decision requested from the Principal

Approve, amend or decline this practice. Until then, it is **PROPOSED** and not applied.
