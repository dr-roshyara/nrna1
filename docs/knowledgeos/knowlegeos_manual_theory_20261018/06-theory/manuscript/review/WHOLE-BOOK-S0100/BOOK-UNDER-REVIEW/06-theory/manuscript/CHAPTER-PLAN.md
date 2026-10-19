# Chapter plan: the KnowledgeOS theory book

| Field | Value |
|---|---|
| Basis | Decision D-R5 (Principal, 2026-10-18): "professor should use its ai intelligence to decide how how many chapters"; the recommendation in that record to keep the plan in one file |
| Version | v1, 2026-10-19 (after reading S0001–S0018) |
| Rule | the plan is updated when the evidence changes it; each change is noted at the end with its reason. A chapter is written when its evidence has been examined, not because it is listed here |

## 1. The structure and why

The first eighteen corpus sources fall into **seven theory questions**:
1. what knowledge is;
2. who may change its standing;
3. how rules bind;
4. how knowledge is modelled;
5. how it changes over time;
6. how decisions are made around it;
7. what KnowledgeOS does with all of this (the loop).

Chapters 0–2 already answer the first three. They stay single files (ML-1 exception) and keep what they hold. New topics get **new chapters in section files** rather than growing chapters 0–2 further. That keeps each chapter reviewable as a unit (AR-9, ML-2).

| Ch. | Title (working) | Question it answers | Main corpus sources so far | State |
|---|---|---|---|---|
| 0 | Foundations before the corpus | What problem; what "KnowledgeOS" names; the claim model; invariants | background + S0002, S0004, S0007, S0014 | written (single file) |
| 1 | Knowledge, authority and execution | What authority is (provenance, standing); acts vs records; roles; execution plane; who may know | background + S0001…S0018 | written (single file) |
| 2 | Rules, applicability and conflict | What a rule is; scope; conflict; binding strength vs enforcement | background + S0003, S0005, S0008, S0015 | written (single file) |
| **3** | **The knowledge meta-model** | What an element of KnowledgeOS is: dimensions, kinds (archetypes), the six concepts, the category test, derived attributes (intent = status × owner) | S0009, S0016 (+ S0008, S0012, S0013, S0017) | **started 2026-10-19**: §3.1–3.7 written (section files; concepts CON-0001…0011, CON-0029…0047) |
| **4** | **How knowledge changes** | The four kinds of change (governance-act, evidential, work-execution, composite) and provenance; granted vs earned; lifecycles; derivation vs attestation; retirement | S0018, S0012, S0004, **S0027** (+ S0011) | **started 2026-10-19**: §4.1 (from observation to pattern) |
| **5** | **Decisions and decision-readiness** | Engineer decisions (catalogue) vs governance decisions (log); the stopping rule; freeze and retire as ungoverned decision types; the platform prepares, humans decide | S0006, S0017, **S0023** (+ S0013, S0014) | **started 2026-10-19**: §5.1 (the decision docket), §5.2 (gap kinds), §5.3 (deferral) |
| **6** | **Boundaries, ownership and spaces** | Tests of a real boundary; ownership as evidence; spaces and nesting; one meaning per term; identifier namespaces | S0001, S0008, S0011, S0012, **S0028** (+ S0013) | **started 2026-10-19**: §6.1 (method, binding, evidence) |
| **7** | **The KnowledgeOS loop and its evidence** | Generation (n = 0); evidence back-flow (n≈3 informal, ≈2 formal); survival ≠ sufficiency; the register as an incomplete record of authorization; what is validated vs hypothesis | S0002, S0005, S0010, S0014, S0015 | planned |
| **9** | **The kernel: type and existence** | RESEARCH-PURPOSE deliverable 5: the kernel scaffold, required capabilities, minimality, existence | EXT-008, EXT-010, EXT-011 (external) + corpus kernel sources when read | **started 2026-10-19**: §9.1 (the scaffold, tested) |
| **8** | **How the theory is validated** | Falsification against artifacts; blind review; process vs personal independence; external validation cannot close an internal link; repetition is not proof | S0007, S0011, S0015, **S0025** (+ S0005, S0022, S0024) | **started 2026-10-19**: §8.1 (the bootstrap experiment) |

## 2. Rules for moving material

- **Nothing is moved out of chapters 0–2** by this plan. Where a new chapter treats a topic that chapters 0–2 already touch (e.g. the meta-model note RC-16 in Ch. 0 §0.3), the new chapter **develops** it and the old passage gets a one-line pointer. That is an L-4 revision with a record.
- A new chapter is created as `chapter_NN/` with `review/` and `TODO.md` (ML-1, ML-2), one file per section named `NN.MM-<short-name>.md`, each ending with a **References** list (source path, S-ID, lines, E-record).
- Concepts in new chapters carry their CV-1 IDs (`[CON-NNNN · validated|invalid]`).

## 3. Open points

- Whether Ch. 3–8 is the right division will be tested by the next sources: the corpus has about 2,700 files, and later material (e.g. the "Zero/Gap" and kernel research) may need further chapters.
- The order of chapters 3–8 is by dependency: Ch. 3 (what things are) before Ch. 4 (how they change) before Ch. 5 (decisions about them).

## Change log

| Date | Change | Reason |
|---|---|---|
| 2026-10-19 | v1 created | D-R5; eighteen sources read |
| 2026-10-19 | Ch. 3 started (§3.1–3.4) | S0016 read; the lead of Ch. 1 §1.8 resolved at its source |
| 2026-10-18 | Ch. 3 §3.5 added (ontology layers) | S0020 read |
| 2026-10-18 | Ch. 3 §3.6 added (relationships; invariants vs choices) | S0021 read |
| 2026-10-19 | Ch. 3 §3.7 added (cross-product projection). **Note for Ch. 8:** falsification by projection (CON-0047) is the first candidate method for validating the theory across products | S0022 read |
| 2026-10-19 | Ch. 5 started (§5.1 the decision docket) | S0023 read; the corpus's own decision artifact |
| 2026-10-19 | Ch. 5 §5.2 added (gap kinds). **Note for Ch. 8:** a lexical search cannot show the absence of a semantic coupling (S0024 L191). **Note for Ch. 6:** kernel / PKS / product placement (S0024 §6–9) is in Ch. 3 §3.6 for now | S0024 read |
| 2026-10-19 | **Ch. 8 started** (§8.1 the bootstrap experiment); the Ch. 8 notes of S0022 and S0024 are now referenced from `chapter_08/08.00-index.md` | S0025 read |
| 2026-10-19 | Ch. 5 §5.3 added (deferral); Ch. 6 opened (§6.1). Working picture of the theory now in `../THEORY-MAP.md` | S0028–S0030 read |
| 2026-10-19 | Ch. 5 §5.4 added (gap operator), from research branch BRANCH-01 | trial of a topic branch (register 32) |
| 2026-10-19 | **Ch. 9 opened** (§9.1 the kernel scaffold, tested) | Principal: the kernel result as a section where deliverable 5 belongs |
| 2026-10-19 | **Ch. 0–2 split into section files** by `tmp/tools/split_chapter.py` along their `##` headings; each rejoins byte for byte (sha256 4efa1c12…, 0772bb16…, 653ec213…); originals removed; each chapter now has an `NN.00-index.md` | METHOD-VERSIONS entry 44 |
