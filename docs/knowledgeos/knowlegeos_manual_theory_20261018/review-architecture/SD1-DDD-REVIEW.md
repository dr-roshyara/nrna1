# SD1 — DDD review of strategic-design turn 1 (§10.1 Subdomains)

*Lead DDD Architect, part 1 (review only), under `03-methodology/MANDATE-LEAD-DDD-ARCHITECT-PROPOSED.md` revision 2 (approved 2026-10-29). Every element reviewed is at S0. Nothing here raises a level, writes theory or decides. Findings go to the Principal and the Governance Engineer.*

## 1. Baseline

| Item | Content |
|---|---|
| Date | 2026-10-29 |
| Run | new review |
| Theory and design baseline | `06-theory/manuscript/chapter_10/10.01-subdomains.md` at `25cf2b93b` (settled in `20140bce9`; the Principal's decision (b) applied). The file has no later commit. Context read: `10.02-bounded-contexts.md` at `65e332c21`, `10.03-ownership-of-invariants.md` at `eefa12a1a` and `87fbfa2ed`, `10.04-context-map.md` at `87fbfa2ed` (for cross-turn consistency only; reviewed in SD2–SD4) |
| Decisions applied | `03-methodology/PRINCIPAL-DECISION-RECORD-RULES-20261018.md` at `758f803c4` (addenda 2026-10-29: T10-01 (b); T10-05 (B) with K2; G1 turn), and at `fe2cbe971` (addendum 2026-10-29T10:00:44Z, T10-10 (1)) |
| Implementation baseline | none |
| Standards | `engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md` at `668cc7b22` (§1 Methodological Fitness only; the module excludes strategic DDD by its own scope line); `docs/architecture/governance/DDD_PRINCIPLES.md` at `668cc7b22` (binding, headings only); `engineering/governance/STANDARDS_INDEX.md` at `7add008f6` (headings only). Evans (2003) and Vernon (2013) are applied **from the Architect's knowledge; neither text was opened**. They judge the design; they are not evidence for the theory |
| Scope | the core / supporting / generic split, judged against promise (b) (mandate §5). Excluded: corpus sources cited by §10.1 (S-numbers) were **not opened**, so every corpus line is taken as the book reports it; Ch. 0, 1, 6, 8 lines cited by §10.1 are outside §3's allowed list and were **not verified** |

## 2. Summary

- The split follows from the promise, not from size, complexity or code: the test (§10.1.2 L36–41) classifies by "part of what KnowledgeOS promises", and kernel membership is refused as a reason (stipulation 2). This is sound and is the main thing that holds.
- Two DDD problems sit in the **core** class. First, row 3 (presentation) is core, but by turn 4 no context owns or enforces it as an operation: it ends as "a role" (SD1-02, high). Second, row 2's line between "showing" (core) and "assessment" (supporting) leaves the **derivation rule** of the class unclassified (SD1-01, high). The derivation rule is where the honesty of the display lives.
- Row 1 (standing) bundles the semantic core (acts, CE\*) with record mechanics (designation, carrier) that resemble the generic row 14 (SD1-03, normal).
- The counterfactual frame (KnowledgeOS as the reference product, against canon's "supporting subdomain of PublicDigit") is stated honestly. It leaves the investment question for part 2 open (SD1-05).

## 3. Findings

| ID | Element | Type | Status | Finding | Evidence | What would resolve it | Severity |
|---|---|---|---|---|---|---|---|
| SD1-01 | Row 2, well-foundedness: "core: showing; supporting: the assessment methods" | design ambiguity | verified | The split names two parts, but the **derivation rule** (from recorded evidence to refuted / not attested / supported) is neither "showing" nor an "assessment method". Turn 2 assigns the derivation to candidate 2 and the showing to candidate 3 (§10.2.3 L188), and G2 says the rule is undecided. In DDD terms, the rule that makes the display "honest" is the core of promise (b). If it is filed under supporting "methods", the core is reduced to rendering a value whose meaning lies elsewhere | §10.1 L55, L81–82, L88; §10.2 L98, L188, G2 L211 | state the class of the derivation rule explicitly (DDD recommendation: core, with row 2's display; the assessment procedures stay supporting). A Principal decision is required only if the book cannot classify it under (b) | high |
| SD1-02 | Row 3, presentation at the point of use (core) | theory consistency (across turns) | verified | §10.1 makes presentation core. Later, §10.3.3 C finds candidate 3 owns no invariant, and §10.3.6 / §10.4.0 make delivery "a role that any consumer takes". The core display obligation then has **joint owners (1 and 2) and no enforcing context**: R3 and R4 are "display rules on consumers". In DDD, a core subdomain with no context responsible for it, and an obligation enforced by "every consumer", is a strategic-design smell: the place that matters most has no model and no steward | §10.1 L56, L81; §10.3 row C L124; §10.3.6 (87fbfa2ed) L48; §10.4 L18, R3–R4 | see SD3-03 and SD4-04: name who enforces "both values, unaltered" by which operation (turn 5 is the natural place) | high |
| SD1-03 | Row 1, current standing | DDD assessment | inferred | Row 1 lists "the record of authority acts, the evaluator of current authority … the designated record". The evaluator's semantics (T/F/U with refusal reasons) is plausibly specific. The record mechanics (designation, carriers, the carrier-change gate) are close to row 14 (generic carriers) and to generic append-only logs. The row itself records non-substitutability as "a hypothesis: no alternative was examined". DDD practice (Evans: distil the core, separate generic subdomains) would distinguish the two | §10.1 L54, L72; row 14 L67 | when the G1 / turn-5 work examines the record, test generic alternatives separately for (i) the record mechanism and (ii) CE\*'s semantics. Either confirms row 1 as one core or splits it | normal |
| SD1-04 | The test: "core if specific and part of the promise" | DDD assessment | verified | DDD's usual criterion for core is differentiation / where investment yields distinction. The book substitutes "part of the promise". Under mandate §5 this is the required yardstick, and it is coherent: mutually exclusive by construction (L41), necessity removed as a criterion. **Holds**; noted because "specific" is defined relative to "KnowledgeOS's model" (L41), so the specific/generic line is only as firm as that model | §10.1 L36–41 | nothing now; re-check once turn 5 fixes the published language | normal |
| SD1-05 | Stipulation 1, counterfactual reference product | open question | verified | The classes are valid only if KnowledgeOS is the product. Canon classes it a supporting subdomain of PublicDigit (L12, L75). Part 2's investment choices (what to model richly, what to buy or simplify) depend on which frame applies | §10.1 L12, L75 | a Principal decision before part 2: which reference product governs design investment for the reference model | normal |
| SD1-06 | Row 8, the engineering method (supporting under (b)) | DDD assessment | verified | Consistent with (b). A rival corpus line names the method as core, and is correctly recorded as a rival, not adopted. **Holds** | §10.1 L61, L82 | — | normal |

## 4. What holds

- The classification is justified by **the promise (b)**. The text states that no class comes from code, size or structure (L9), and that kernel or application membership is never a reason (stipulation 2; boundary-test column marked "not a reason").
- **Necessity is removed as a criterion of core** (L41). This is a sound correction: under DDD, everything necessary would otherwise be core.
- **Generic** rows (14 carriers, 15 runtime adapters) are generic **with a boundary proviso** (the promise's invariants hold at the boundary; the carrier-change gate). This is the correct DDD form for generic subdomains.
- **Rivals are recorded**, not silently rejected. This is consistent with Architectural Silence in spirit, though that principle is tactical by scope.
- **Every row stays at S0**, and the decision "fixes the classes; it raises no level" (L3).

## 5. Files opened and commands run

Commands, in order (repository root `nrna1`):
1. `cat` + `git log` of `03-methodology/MANDATE-LEAD-DDD-ARCHITECT-PROPOSED.md` (working tree; last commit `33ade4f97`).
2. `git log -1` for `25cf2b93b 20140bce9 65e332c21 fe67ad7cf eefa12a1a 87fbfa2ed`; `git show <c>:<file> | wc -l` for the three turn files; `git log --oneline` and per-file `git log --format=%h` on the three turn files.
3. `git show 25cf2b93b:…/10.01-subdomains.md | cat -n`.
4. `git show 65e332c21:…/10.02-bounded-contexts.md | cat -n`; `git show eefa12a1a:…/10.03-ownership-of-invariants.md | cat -n`.
5. `git diff 65e332c21 eefa12a1a -- …/10.02…`; `git diff --stat fe67ad7cf eefa12a1a -- …/10.03…`.
6. `grep -n` and `sed -n 454,519p` on the decision record (working tree = `758f803c4`); `git show fe2cbe971:<decision record> | sed -n '521,$p'`.
7. Ch. 9 (commit `0ae02e66b`): `wc -l 09.1[0-7]*.md`; `sed -n 75p, 280p` of 09.15; `sed -n 179p, 150p` of 09.10; `grep` on 09.13 (`endg`, `observe`), 09.17 (`(c′)`, `R2`), 09.16 (`R4a`, `R3a|detect`); `sed -n 28,45p` and `72,90p` of 09.17.
8. Standards: `git log -1`, `wc -l` and `grep '^#'` of the three standards files; `sed -n 1,40p` of `DDD_Tactical_Governance_Principles.md`.
9. `mkdir -p review-architecture/`.
10. `git log` and `grep '^## Addendum'` of the decision record; `git log --oneline -3`; `git ls-tree --name-only 87fbfa2ed chapter_10/`; `git show 87fbfa2ed --stat` (**this printed the file names of `review/SD4-CONTEXT-MAP/` reports; no review file was opened**).
11. `git show 87fbfa2ed:` of 10.03 and 10.04 into the session scratchpad; `git diff eefa12a1a 87fbfa2ed -- 10.03` (headers only); `git diff 65e332c21 87fbfa2ed -- 10.02`; `diff` of 10.03 lines 1–245 at the two commits (identical); `sed -n 246,323p` (10.03 §10.3.6 at `87fbfa2ed`); full read of 10.04 at `87fbfa2ed`.

**Not opened:** any corpus source, `00-access/`, any `review/` folder file, `review-implementation/`, `CONCEPT-REGISTER.tsv`, `THEORY-MAP.md`, the commit `f8f6bbb2f` (the T10-10 application, not settled), and any Ch. 0–8 file.
