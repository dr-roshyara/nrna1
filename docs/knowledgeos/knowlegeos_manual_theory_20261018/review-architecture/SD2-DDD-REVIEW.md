# SD2 — DDD review of strategic-design turn 2 (§10.2 Bounded contexts)

*Lead DDD Architect, part 1 (review only), under the mandate revision 2 (approved 2026-10-29). Every element reviewed is at S0. Nothing here raises a level, writes theory or decides.*

## 1. Baseline

| Item | Content |
|---|---|
| Date | 2026-10-29 |
| Run | new review |
| Theory and design baseline | `06-theory/manuscript/chapter_10/10.02-bounded-contexts.md` at `65e332c21` (settled). Later changes read as diffs only: `eefa12a1a` and `87fbfa2ed` add an "Admission decided since" banner and correct the CON-0143 gloss. Nothing else in the file changed. Context: §10.1 at `25cf2b93b`; §10.3 at `eefa12a1a` / `87fbfa2ed`; §10.4 at `87fbfa2ed` |
| Decisions applied | decision record at `758f803c4` (T10-01 (b); T10-05 (B) with K2; G1 turn) and `fe2cbe971` (T10-10 (1)) |
| Book lines verified | Ch. 9 at `0ae02e66b`: §9.13 L49 (`observe` withdrawn in v18); §9.15 L75 (K2), L280 (completeness of evidence); §9.10 L150, L179; §9.17 L33, L72–90 |
| Implementation baseline | none |
| Standards | `DDD_Tactical_Governance_Principles.md` at `668cc7b22` (§1 Methodological Fitness, applied by analogy; the module is tactical by scope); Evans (2003), Vernon (2013) **from the Architect's knowledge, not opened** |
| Scope | each candidate's model, language, responsibility and rules; whether anything is a folder, module or layer; whether (B) is applied faithfully (its application lives in §10.3.6, reviewed in SD4). Excluded: corpus sources and Ch. 0–8 lines (not allowed; taken as the book reports them); S0168's ten criteria (unscored by the book itself) |

## 2. Summary

- The turn's central move holds: necessary-only admission tests admit nothing, so all eight are **candidates**. No candidate is drawn from code, folders or the kernel line.
- **Language** is the weakest column. "Y" is asserted for six candidates without showing terms that mean different things across candidates. The one place this is shown ("scope", three senses) is exactly the evidence DDD expects, and it should be the norm (SD2-01, high). This matters more now: (B) admits on "its own vocabulary".
- **Candidate 1 bundles two models**: the semantics of standing, and the mechanics of the designated record. Under T10-10 (1), the designated record becomes the medium for every context's state (SD2-02, high; continued in SD4-01).
- Several grid columns are "Y" for nearly every candidate and so do not discriminate (SD2-03).
- Candidate 8 "writes into candidate 2". It should do so under candidate 2's rule, as turn 4 later types it (SD2-05).

## 3. Findings

| ID | Element | Type | Status | Finding | Evidence | What would resolve it | Severity |
|---|---|---|---|---|---|---|---|
| SD2-01 | Score grid, "Language" column | DDD assessment | verified | Six candidates score Language "Y", and two "?". The grid gives no per-candidate evidence of a **linguistic boundary**: a term that means something different in another candidate, or that only this candidate defines. This is the hallmark of a bounded context in Evans and Vernon. The text shows exactly one such case: "scope" for a grant (1), for a rule (4) and for canon (CON-0004). Under (B), admission requires "a rule … stated in its own vocabulary", so this column now carries admission weight | §10.2 L62–69; L135; decision record T10-05 | for each candidate, a short glossary: the terms it defines and the homonyms it shares with another candidate (the "scope" pattern). Book work, for turn 5 or the G1 turn, at the Principal's routing | high |
| SD2-02 | Candidate 1, Standing | design ambiguity | verified | Candidate 1's responsibility joins (i) a semantic model, made of authority acts and CE\* answers (T/F/U, refusal reasons), and (ii) the **designated record** with its designation rule and carrier-change gate, which are record mechanics. §10.1 put carriers in generic row 14. Turn 3 then gives 1 the record-integrity laws (identity, merge, integrity G6). §10.3.6 (settled) states that under one record every area's state lives in standing's record and schema. So "the record" is at once candidate 1's private model and the shared substrate of every future context. In DDD terms the two have different change drivers and different consumers | §10.2 L78–88; §10.1 row 14 L67; §10.3.3 row A L90, L98–100; §10.3.6 (87fbfa2ed) L16–18 | the Principal, on recommendation (see SD4-01): either name the record and D's kind schema as a **shared kernel**, owned and changed by a stated act, or keep them in candidate 1 and state that every context conforms to standing's schema. The book already states the second as a fact. What is missing is a named choice | high |
| SD2-03 | The grid as an instrument | DDD assessment | verified | "Reasons to change" is Y for all eight candidates, and "CON-0085 ≥ 1" is Y for all eight. Lifecycle is Y for seven. These columns never reject a candidate. Under Methodological Fitness §1 ("a criterion that never rejects … is presumed ceremonial"), they add no discrimination, so the grid's real signal is Authority (α/β), Invariants and Owned state. The book reached the same conclusion by another route ("necessary only", A-01) | §10.2 L60–69; DDD_Tactical_Governance_Principles §1 | none required; recommend that later admission reasoning cite only the discriminating columns | normal |
| SD2-04 | Candidate 3, Delivery | DDD assessment | verified | One candidate spans a core part (presentation, §10.1 row 3) and supporting parts (assembly, projections). The book flags it (L128). Turn 3 resolves it as "owns no invariant" and §10.3.6 as "a role". The DDD reading agrees: a role applied by every consumer is not a context. The cost is that the core display obligation is left without a context (SD1-02, SD3-03) | §10.2 L117–128 | as SD3-03 | normal |
| SD2-05 | Candidate 8, "write evidence entries into candidate 2" | design ambiguity | verified | The phrase reads as one area writing into another's model. Sound DDD practice: an upstream submits through the receiving context's own operation and rule. Turn 4 (R7) later types it exactly so ("A writes under W's rule"). The turn-2 text should be read in that sense | §10.2 L168, L182; §10.4 R7 | none beyond turn 4's typing; turn 5 should state the operation (O2, accept) as W's | normal |
| SD2-06 | Grid column "Owned state" | theory consistency (across turns) | verified | At turn 2, "Owned state" is scored loosely (candidates 4 and 5 "Y"). §10.3.6 (settled) and T10-10 (1) define it strictly: owned entry kinds of the one record. Under the strict definition, 4 and 5 have none (G11). The banner added since records the new statuses, but the grid still carries the old meaning. A reader can draw opposite conclusions from one column | §10.2 L65–66; banner (`87fbfa2ed`); §10.3.6 L10–13, L45–46; T10-10 (1) | annotate the grid column with the decided definition when T10-10 (1) is applied (already in progress by the Professor) | normal |
| SD2-07 | Admission of candidates (§10.1 row 6); Q6 | open question | verified | Two areas remain without a home: admission of candidates (a kernel step or a context?) and read visibility (Q6). For a knowledge system, admission is a lifecycle area of plausible domain weight. It is recorded as open, which is correct | §10.2 L241, L248; §10.3.3 L189 | a later turn (published language, or the G1 turn if evidence admission is involved) | normal |

## 4. What holds

- **No admission on necessary conditions** (L6, L227). This is the correct epistemic and DDD position: the six questions (S0150) are rejection tests.
- **Candidates are drawn from responsibilities and concepts**, not from code, folders, modules or present structure. The draft's argument from "the estate runs one" was withdrawn (L164, L255). **Nothing in the text is a folder, module or layer presented as a context.**
- **The kernel line is a separate field**, taken from the boundary test and "not chosen anew" (L33, L73). This keeps the subdomain or context view apart from the layering view.
- **Standing and well-foundedness are separated by meaning**: "standing asserts nothing about correctness, and a class asserts nothing about standing" (CON-0231, L99). This is a genuine model boundary.
- **Dependencies are stated with their sense**: candidate 2 depends on 1 only for *who may record* (L184). This is a well-formed customer/supplier relation, not a layering.
- **Generic areas are excluded** because they lack a KnowledgeOS model, "not merely because of their class" (L176). This is correct DDD: generic subdomains can be contexts.
- **Faithfulness to (B)**: as far as turn 2 is concerned, it is applied through the banner and §10.3.6 (SD4). Turn 2 itself predates the decision and does not claim to apply it.

## 5. Files opened and commands run

As listed in SD1 §5 (one session; the same commands served all four reviews). Specific to this report: `git show 65e332c21:…/10.02-bounded-contexts.md | cat -n`; `git diff 65e332c21 eefa12a1a -- …/10.02…`; `git diff 65e332c21 87fbfa2ed -- …/10.02…` (changed lines only). **Not opened:** corpus sources, `00-access/`, `review/` folders, `CONCEPT-REGISTER.tsv`, `THEORY-MAP.md`, `f8f6bbb2f`.
