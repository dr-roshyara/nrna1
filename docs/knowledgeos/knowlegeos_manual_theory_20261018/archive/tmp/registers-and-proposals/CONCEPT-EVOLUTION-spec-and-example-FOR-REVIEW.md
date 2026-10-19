# Concept Evolution Record: final amended specification and CE-01 (review package)

**Status:**
- **working practice:** APPROVED by the Principal (2026-10-18), subject to amendments A–C, which are incorporated below;
- **this specification and CE-01:** AWAITING PRINCIPAL REVIEW;
- **the record `CONCEPT-EVOLUTION.md`:** NOT CREATED; creation and broad application are not authorized.

This approval concerns the practice only. It approves no concept, source claim or governance decision.

---

## 1. Final amended specification

**S1. Scope.**
- Authorized background sources and the book's own revisions only. Never the restricted corpus.
- An entry is made only for a concept that was actually **rejected, replaced or materially revised**.
- Existing chapters, baselines, identifiers, citations and historical records are never restructured or rewritten to implement the practice.

**S2. Canonical mechanisms reused.**
- The **idea register** (`06-theory/IDEA-REGISTER.tsv`) stays the canonical per-source record. An entry **links register IDs** and does not repeat their contents.
- Provenance uses the book's existing marks (S4).
- Document order uses existing timeline evidence.
- The record adds only what no existing mechanism holds: a **cross-document history per concept**.

**S3. Three status dimensions (amendment A), always separate.** "Within a stated scope" means the scope is written next to the value.

| Dimension | Permitted values |
|---|---|
| **research status** | proposed · under investigation · supported within a stated scope · refuted · unresolved |
| **governance status** | no decision identified · proposed for approval · approved within a stated scope · rejected · authority unclear |
| **position in the theory** | used · used conditionally · retained as an alternative · historical only · not adopted |

**S4. Provenance of every material claim (amendment B).**
- **[Src]**: stated by a source, with file and exact lines;
- **[Ded]**: deduced from source evidence; the premises are named;
- **[Asm]**: an assumption, stated as such;
- **[Ind]**: independently verified **by me**, saying how (re-reading, code, data, file metadata).

A reported result (e.g. from a reviewer) is marked as reported, never as [Ind], unless I have repeated the check.

**S5. Relationships (amendment C).**
- **Values:** rejects · replaces · narrows or modifies · alternative without decision · different situation · stale reference · term drift · misreading by the book · **unresolved**.
- It is recorded **per claim**, not per document.
- It is established only if the later source **states** it [Src] or a **stated deduction** supports it [Ded].
- Otherwise it is **unresolved**. A later date alone never establishes a replacement.

**S6. Entry fields:**
1. ID and original wording;
2. original source and its status;
3. the change: source, reason, relationship (S5);
4. the replacement and its rationale;
5. evidence, counterexamples and assumptions, each tagged (S4);
6. scope;
7. status in the three dimensions (S3);
8. dependencies in the book;
9. verification limitations;
10. open questions and the next responsible action.

Plus the **register IDs**.

**S7. Proportion.** One compact entry per changed concept, normally 10–25 table lines. No entries for unchanged concepts.

**S8. Limits.** The record **reports** governance status and never makes a decision. A source's own approval is reported as "approved within a stated scope", naming the source's programme. It is never presented as approval for the KnowledgeOS theory.

---

## 2. CE-01 worked example: "five names for one system"

| Field | Entry |
|---|---|
| **Register IDs** | B3-F1-25 (names denote tiers) · B3-F1-28 (OQ register; OQ-3 withdrawn) · B4-F2-04 (DR's root questions; OQ-1 withdrawn after DR) · B4-R-F25 (PC/DR timeline note) |
| **1 Concept** | **CE-01.** At the system level, the names PKS / KnowledgeOS / EKS / Engineering Knowledge System / Engineering Knowledge Operating Platform are **five names for one system**, a naming failure to be cured by a terminology freeze |
| **2 Original source and status** | V3 v3.0 §3 (file `20260816_0841_target_architecture_v3.md`). The original v3.0 wording is **not in the file**. Only its withdrawal notice survives: "v3.0 recorded *five names for one system* and proposed a terminology freeze" [Src: V3 L105]. [Ind: "five names" occurs once in V3, at L105.] Original status: a v3.0 derivation, before the ARB verdict [Src: V3 L653] |
| **3 Change and relationship** | V3 **REV 1, 2026-08-16 11:36**, "revised on ARB verdict; rulings are decisions, not derivations" [Src: V3 L653]: "ARB REV 1: v3.0 §3 is WITHDRAWN … **That was wrong.**" [Src: V3 L103–105]. Register entry: OQ-3 "WITHDRAWN: three tiers, not three names (§3)", settled by "ARB, REV 1" [Src: V3 L461]. **Relationship: rejects and replaces**, stated by the source itself [Src] |
| **4 Replacement and rationale** | **Replacement:** the names denote different tiers: KnowledgeOS T1 platform; PKS T3, "never reusable, an instance"; EKP inside T1/T2; EKS "no referent", retired [Src: V3 L107–113]. **Source rationale:** "collapsing three tiers into 'a naming problem' is a level error, not a labelling one. The freeze would have frozen one name onto three concepts" [Src: V3 L115]. **Book's analysis:** the same type error as standing vs lifecycle [Ded: Ch. 0 §0.2 L69; Ch. 1 §1.2]. Premise: the tier model of V3 §0.1, which the book reads but has not independently tested |
| **5a Later documents: the withdrawn claim** | PC: "v3 §3 recorded exactly this failure at the system level — PKS / KnowledgeOS / EKS / Engineering Knowledge System / Engineering Knowledge Operating Platform" [Src: PC L25]. DR: "v3 §3 recorded the identical failure at the system level (five names for the system)" [Src: DR L147], and OQ-3 "Canonical system name" listed as open [Src: DR L69]. **Relationship: stale reference** [Ded]. Premises: both were written before REV 1, and neither was revised after it. Evidence: (i) filename times 10:34 and 10:40 vs REV 1 at 11:36 [Src: V3 L653]; (ii) last-modified times PC 10:36:06, DR 10:41:18, V3 11:38:41 (+0200) [Ind: file metadata]; (iii) neither PC nor DR mentions REV 1, the withdrawal or tiers [Ind: text search]. **[Asm]** a filename time is the authoring time; **[Asm]** the modification times were not altered by copying or tooling |
| **5b Later documents: a separate observation (not CE-01)** | PC: "Three names, one concept, three documents, one day … the same drift has now reproduced one level down" (`RuleConflictPolicy` vs …Service / …Detector; OQ-27) [Src: PC L23–27]. DR: "Three concepts, seven names, one day" [Src: DR L147]. **This observation does not depend on CE-01** [Ded: it concerns names of one lower-level concept, not system-level names] and **is not withdrawn by REV 1**, which addresses only V3 §3 [Src: V3 L103]. So PC and DR are **stale on CE-01 and independent on naming drift**. That is why relationships are recorded per claim |
| **6 Scope** | system-level naming in the 08-16 programme. Lower-level naming drift is outside CE-01; it is a candidate separate entry **only if** it was itself later revised, which has not been established |
| **7 Status** | **Research status:** the tier reading is *supported within a stated scope*: the 08-16 sources, consistent with the book's level distinction [Ded]; no counterexample known; the tier model itself not independently tested. **Governance status:** *approved within a stated scope*, namely the ARB's decision in the 08-16 programme ("rulings are decisions", V3 L653), **as reported by the source**. For the KnowledgeOS theory: *authority unclear*. Whether 08-16 ARB rulings carry standing here is the **Principal's question** and is not decided. **Position in the theory:** the tier reading is *used conditionally*, as **supporting evidence** for the layered reading in Ch. 0 §0.2, not as a binding ruling. CE-01's original claim is *historical only* |
| **8 Dependencies** | Ch. 0 §0.2 (layered reading; R0-1 framing); Ch. 1 §1.17.5 (naming drift); Ch. 1 §1.14.4–5 (the parallel withdrawal of OQ-1, register B4-F2-04); BATCH-04-checkpoint (F25) |
| **9 Verification limitations** | (a) the original v3.0 §3 text is unavailable: CE-01's wording comes from the **withdrawal notice**, not the original; (b) there is no version history for `docs/knowledge_tranfer/` (no git log for these files), so the timing rests on filenames and modification times plus two [Asm]; (c) "decided by the ARB" is the **source's own report**: I have not seen an ARB record, only V3's statement; (d) the tier model (V3 §0.1) was not independently tested; (e) only the cited lines of PC and DR were read for this entry, plus a text search of each file, not a full re-read |
| **10 Open questions / next action** | (a) **Principal:** do the 08-16 ARB rulings have standing for the KnowledgeOS theory? (b) Should lower-level naming drift become its own entry? Only if a revision of it is found; none is known. (c) None blocks anything |

---

## 3. Unresolved questions and assumptions

**Assumptions in CE-01:**
1. Filename times are authoring times.
2. File modification times reflect the last real edit (not copying).
3. V3's own report of an ARB verdict is accurate (no ARB record seen).

**Unresolved questions:**
4. **Standing of 08-16 ARB rulings for this theory.** Principal; not decided here.
5. **Placement of the record.** `php scripts/doc-placement.php --scope=cross-product --maturity=research` returns **PENDING, exit 2** ("placement unruled", rule cross-product-research, ref ADR:OQ-2: "Record PENDING and escalate. Do not guess a location").
   - The authorized write scope of this lane is `docs/knowledgeos/knowlegeos_manual_theory_20261018/`, where the idea register already lives.
   - Whether the record may sit beside the register under the lane's existing authorization, or needs a governance ruling first, is **escalated, not guessed**.
   - The product-specific rule does not apply: the theory is not PublicDigit-specific.
6. Whether the three status vocabularies need further values (e.g. "superseded" under governance). Not added, since the Principal's lists are used as given.

---

## 4. Confirmation of preservation

**No existing file was changed** for this work:
- chapters, glossary and the idea register;
- baselines and their hashes;
- identifiers and citations;
- review, decision and revision records;
- git state.

The package exists only in the session scratchpad. Reads only, all authorized: V3 L101–115, L455–469, L653; PC L22–27 and a text search; DR L67–70, L145–149 and a text search; Ch. 0 L66–71; the idea register (search); file metadata; the placement script (read-only query).

## 5. Proposed next action for authorization

> **Authorize creation of `CONCEPT-EVOLUTION.md` containing the specification (§1) and CE-01 (§2), at a location you approve.**
>
> **Recommended location:** `docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/CONCEPT-EVOLUTION.md`, beside the idea register, inside the lane's existing write scope. This is recorded as an **escalated placement** (the placement script returns PENDING for cross-product research), not as a ruled one.
>
> **After that:** add entries only when a concept is actually rejected, replaced or materially revised. Each new entry is reported to you. No broad back-filling of past batches without separate authorization.
