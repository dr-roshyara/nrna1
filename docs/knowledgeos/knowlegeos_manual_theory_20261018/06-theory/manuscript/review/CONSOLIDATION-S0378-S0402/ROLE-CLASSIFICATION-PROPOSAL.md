# Role classification for the book's ideas: proposal

*2026-11-03. The Professor, at the consolidation S0378–S0402 (the Principal, 2026-11-03: "propose a role classification for the book's ideas — core theory, supporting device (lens, viewpoint, notation, example), application, record of the estate — with a [criterion] for each"; one word replaced in brackets, as this folder's wording rule requires). **A proposal for the Principal, not a decision and not reviewer material.** Nothing is tagged, no window changes its format, and the concept register is not changed until the Principal approves (index W8).*

## 1. Is the role a new dimension? (the model-integrity check)

The book already has two classifications. The role must be either a new dimension or one of these, not both.

| Existing | What it classifies | Relation to the role |
|---|---|---|
| **Labels** Src · Obs · Int · Ded · Hyp · Ext | how a claim is supported | **Orthogonal.** A Ded claim may be core theory (§9.13 R1) or a supporting device (a worked example). A Src claim may be a record of the estate, or the premise of a core result. |
| **Kernel/application marks** K? · A/O · D (BOOK-OUTLINE, §8.12–§8.21 tables) | where a concept would sit relative to the kernel boundary, as proposals | **Overlapping.** A/O ("estate method or observation of one estate") straddles two roles (application, and record of the estate). D ("a definition or vocabulary") is the notation half of supporting device. K? is a placement question inside core theory. |

**Conclusion:** the role is a new dimension relative to the labels. Relative to the marks, it is partly an overloaded existing dimension.
- *It is orthogonal:* it asks what an idea does in the book, not how it is supported or where it would sit.
- *It is necessary:* the S0378–S0402 windows show many claims that restate a lens or an estate record at full length.
- *It is sufficient:* four classes plus their criteria cover every Ch. 8 claim type the last 25 windows produced.
- *Proposal:* if the roles are approved, the marks are kept only for their placement content (K?), and A/O and D are mapped onto roles (§4). The marks are not kept as a parallel vocabulary.

## 2. The four roles and their criteria

Apply the criteria in this order. The first that answers yes gives the role. If a claim has parts with different roles, split it.

| # | Role | Criterion (answer yes or no) | Examples (settled sections) |
|---|---|---|---|
| 1 | **Core theory** | **Removal.** If this claim were deleted, would a settled result lose a premise, or change? A settled result here means a theorem, a decided ruling, Req, a clause of D or D_S/D_W, a published-language item, or an answer to a RESEARCH-PURPOSE deliverable. | §9.13 R1 (CE* total on D); §9.19.6 T7; §9.20 Th1; C1 = constitute; §8.95 C4's three senses of admission (used by §10.2, §10.3) |
| 2 | **Application** | **Object.** Does the claim's truth depend on facts of one named product, estate component or design candidate (PublicDigit, EKS, a proposed kernel or ADR), so that it would be false or meaningless for another? | §8.103 C2 (the proposed kernel-scope ADR is met by refuse-all); §8.98 C5; §10.2's candidates |
| 3 | **Supporting device** (lens, viewpoint, notation, example) | **Replacement.** Could the claim be dropped or swapped for another device with no core claim changing? It serves to find, state or illustrate. Sub-criteria: a **lens** generates questions; a **viewpoint** is a reading of a source that produced no new result; a **notation** is a name, symbol or homonym split; an **example** is an instance of a result stated elsewhere. | §8.105 C1 (the logic manual's apparatus: it adds names only); §8.104 C2's mappings of falsification questions; homonym splits such as §8.93 C2 (notation); worked cases (example) |
| 4 | **Record of the estate** | **Fidelity.** Is the claim true just when it reports a source correctly (what the estate said, did, decided or measured), and does it assert nothing about D or the theory? | lineage paragraphs; §8.105 C3 (one PO/ARB selection for one slice); §8.101 C4 (a disposition quoted as an act) |

**Tie rule.** A claim that answers yes to criterion 1 is core, whatever else it is. Of the rest, an application is a record of the estate only if it makes no judgement about the object. A claim that records the estate *and* maps it to D is split: the report is a record, and the mapping is core, supporting or application by criteria 1–3.

## 3. What changes if approved (as the Principal stated)
1. **New windows tag their items by role.** Each Ch. 8 claim heading carries one role mark, next to its label: `(Src; Int; role: supporting/lens)`.
2. **Supporting items get one line with their home.** A supporting device, or a record of the estate that maps to an existing settled result, is written as one line: what the source proposes, the home it maps to, and agree / disagree / no counterpart. It no longer gets a full claim. *Expected effect, from C106–C111:* most of the restatement findings (R 63 of 109 across C105–C111) attach to such items, so the review effort shifts to core and application items. This is an estimate, not a measurement.
3. **The themes list and the chapter summaries mark roles.** THEMES.md rows and SUMMARIES statements carry the role of the sections they cite.
4. **The concept register** is changed only after approval. Proposal: a `role` column is added, filled only when a concept is next reviewed (no retro-tagging sweep), with the K?/A-O/D marks mapped as in §4.

## 4. Mapping of the existing marks (proposal)
- **K?** stays, as a placement question within core theory: "kernel candidate; boundary question owed".
- **A/O** becomes **application** (an estate method applied to one object) or **record of the estate** (an observation of one estate), decided by criterion 2's tie rule when the concept is next reviewed.
- **D** becomes **supporting device / notation**, except where a definition is a premise of a settled result, which makes it core theory by criterion 1.

## 5. Open points for the Principal
- (a) The order of the criteria (core first) and the tie rule.
- (b) Whether existing Ch. 8 sections are re-tagged at their chapter's rotation, or only new windows from S0403 on. *Recommended: new windows only, plus the next rotation of each chapter.*
- (c) Whether the role sits in the claim heading or in a separate column of the section's home table. *Recommended: the heading, beside the label.*
