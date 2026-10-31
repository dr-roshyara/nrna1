# Open theoretical questions that block the most results (consolidation S0278–S0302)

*2026-10-31. The Professor. The Principal's instruction 3 (2026-10-31): "use the existing TODO files and dependency list to identify the open theoretical questions that block the greatest number of other results … Do not start new research lines." Working book at `94183183a` (the freeze point). Not part of the reviewers' scope.*

## Method, and its limits
- **Inputs:** the ten chapter `TODO.md` files (186 rows; 168 not marked Resolved), the map's forks (`THEORY-MAP.md` §7), §10.2's gap list G2–G11, and `tmp/tools/dependency_graph.py`.
- **Measure:** the number of book sections (of 156) that cite the question by any of its identifiers. Related identifiers are grouped into one question (for example G10, T9-14, T9-18 and "§8.39 C3 (b)" are one question about item identity).
- **Limits:**
  - **The dependency graph does not discriminate here:** `--down` on any of the candidate home sections reaches the same large cluster (299 items from §8.39), as Rulebook §12d warns. The ranking therefore uses direct citations, which count explicit reliance only.
  - Identifier matches can include unrelated uses (two "G" series exist: §9.15's guarantees and §10.2's gaps).
  - A citation shows that a result names the question, not that it fails if the question is answered one way. For each question below, the dependent results were checked by sample, not one by one.

## The five questions, by sections citing them

| # | Question (identifiers) | Sections citing | What remains unresolved | What would resolve it | Research or decision |
|---|---|---|---|---|---|
| 1 | **The kernel's attribute set and class vocabulary** (F13, T9-02, T9-01, §10.2 G7) | 36 | Two attribute systems stand side by side (Ch. 1's labels; Ch. 3's six dimensions), with three values in §3.1 and four in §1.3; the kernel's required capabilities are not yet derived from the corpus (T9-01 "Under Research"); the source kind of evidence is not carried (G7). | **Derivation:** one table of attributes, value sets and kinds (T9-02's criterion), each attribute traced to a capability that passes the three boundary questions (T9-01's criterion). The corpus input exists (S0167's five verbs; the kernel inventories at §8.23–§8.26). | **Research first** (a derivation from material already read), **then a Principal decision** to adopt the table. |
| 2 | **What KnowledgeOS is, and its mission** (T0-03, T0-02, F1, T0-03b) | 31 | Four unreconciled framings (a state-and-analysis system; a governance architecture; a platform; a supporting subdomain); two mission statements; PKS and EKS as one system or two. Partly decided: the promise when serving knowledge (T10-01, (b)); D1–D3 (I-013) are governance inputs, not adopted theory. | Not derivable from the corpus: the sources disagree, and the window sections only route framings here (B-10). The theory can proceed on an **explicit assumption marked as such** (T0-02's criterion). | **Principal decision** (or a stated theory-level assumption the Principal accepts). |
| 3 | **Outside the kernel: does the record constitute authority or represent it?** (§1.13 L134 open; F2, T1-15, T1-53, §1.17 E4) | 29 | Decision C1 (2026-10-20) settles "constitute" **within the kernel** only. Outside it, the estate itself states the other concept ("the human act IS the authority; the grant would be its record", S0300 L32; S0296 L8: §8.57 C4, §8.60 C4). External authority (law, regulators) is outside the act model (T1-15). | A ruling on which concept applies outside the kernel, and where external authority attaches, with a statement of how it relates to A1. The windows supply evidence that the estate works under C1-b; they cannot decide it. | **Principal decision.** |
| 4 | **Item identity over versions** (G10, T9-14, T9-18, §8.39 C3 (b)) | 28 | Entry identity is settled (§8.39 C3 (a)); whether a revised or re-expressed statement is the same item is open. It recurs in every recent window: the normal form that would decide identity (§8.56 C1), the semantic language (§8.57 C3), the summary repaired in place (§8.59 C1), the plan's layers (§8.60 C2). R2's order independence under the extended identity is not checked (T9-18). | **Derivation and a decision:** a stated rule for when a governed act assigns an existing identifier to new content (§9.10 Q1 makes the identifier governed); the bearer decision (G3, F37); a model check of R2 with the extended identity (T9-18's criterion: a run of the existing `ce_model.py`, which is not a new research line). | **Research** for T9-18 (a model check) and for the candidate rule; **Principal decision** to adopt the rule and the bearer. |
| 5 | **Which root act ends the authority regress; genesis** (N4-15, T1-17, F3) | 26 | The book ends authority's regress at a root act by stipulation (§1.14.1); which act is the root is open (N4-15); the corpus's genesis clause ("the first decision is recorded Provisional/Proposed", S0024) is unadopted (CON-0054); the regress of reflection levels has no home (§8.54 C2). | A comparison of the book's root-act stipulation with the corpus's genesis clause (T1-17's next action), then a choice of the root act. | **Research** (the comparison, from sources already read), **then a Principal decision**. |

## Questions just below the five
- **Order of acts** (F26; 10 sections): where act times are absent or untrusted (only 2 of 216 transitions carry a time, §9.17 L137).
- **The bearer of the dimensions** (F37, G3; 9 sections): a decision the gap list already names ("a decision on the bearer"); it feeds question 4.
- **The designation protocol** (F22's protocol questions T9-07, T9-11; 6 sections).
- **A check before designation** (§8.46 C2's open topic; 4 sections), which §8.59 C4 placed C66's question under.

## What this ranking does not claim
- It does not claim that a lower-ranked question is less important, only that fewer sections name it.
- It does not decide any question; the "what would resolve it" column states the criterion already recorded in the TODO row where there is one.
- Nothing here was taken from the reviewers' folder or the copy.
