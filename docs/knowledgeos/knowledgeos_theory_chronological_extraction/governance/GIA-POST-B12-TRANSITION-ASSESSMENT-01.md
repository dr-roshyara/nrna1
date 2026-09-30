# GIA-POST-B12-TRANSITION-ASSESSMENT-01

| | |
|---|---|
| **Kind** | transition assessment, moving from forensic investigation to controlled repair. ⛔ **Not an implementation, not an authorization** |
| **Prepared by** | governance / control-plane session |
| **Date · HEAD** | 2026-09-23 · `95916b397` |
| **Inputs** | `governance/GOVERNANCE-INTEGRITY-AUDIT-01.md` (committed draft at `15a05f297`; later edits in the working tree, §1) · `governance/GIA-DECISION-PACKAGE-01.md` · `governance/audits/2026-09-23-B12-G4-REFERENTIAL-IDENTITY-AUDIT.md` |
| **Change check** | control-plane and evidence hashes at `95916b397` are identical to the package §3 values: `gates.yaml 7a01bac1…` · `governance-state.yaml e20b4d67…` · `gate-schema.yaml 4ebde2c9…` · `gate-runner.py fcafa9cd…` · door `4b53c0b3…` · `PROPOSED-GATES-RCI.yaml 9a914c9d…` · `admit.py 23ccde6c…` · `build-manifest.py 5e2c5249…` · `CORPUS-MANIFEST.jsonl 54977c6e…`. **No new evidence contradicts the three artifacts** |

---

## 1. Current governance state

- 25 gates; 5 activated (KOS-G-001/002/003/010/022); nothing changed since the audit.
- The control plane has demonstrated false-CLEAR paths, so current CLEAR results are **not** conformance evidence (pending GIA-1).
- The research-produced evidence-binding implementation is present and **non-authoritative** (GIA-9).
- ⚠️ `GOVERNANCE-INTEGRITY-AUDIT-01.md` is committed only as the draft the **research session** committed in `15a05f297`. The complete version, with §2A, GIA-9/10 and the corrected case count, is **uncommitted in the working tree**. The authoritative text should be committed before L0 relies on it.

## 2. Governance Integrity Audit status

- **Verdict: FAIL · transition outcome B, READY WITH CORRECTIONS.**
- Based on 54 valid cases from 55 runs.
- **Unchanged by B-12.**

## 3. B-12 result

**PASS_WITH_OPEN_QUESTIONS.**

| Finding | Status |
|---|---|
| G-4 across the corpus | a **document-local** label: 123 occurrences, 53 files, at least 35 referents |
| F0014's G-4 | **UNIQUE** (its own §3, L93) |
| "Wrong G-4" hypothesis | **not supported** |
| H-3 closure | **UNRESOLVED** in the canonical record |
| New G-4 governance rule | **not justified** |

## 4. Established facts (from the three artifacts, not re-derived)

1. The false-CLEAR paths exist: D08–D10, D16, D17, B04, B09, A0, D04, D06, D07, G01 and B17 (audit).
2. There is a false-FAIL path: citing a correct canonical ID that is not in the registry (G02, F2837) (audit).
3. F0031–F0040: 9 of 10 IDs are bound to the wrong files. Canonical F0032, F0035, F0036 and F0040 are unread (independent coverage audit; `ID-ROOTCAUSE-01`).
4. 18 canonical paths are unresolvable (B-14). This session's B-12 parse produced the **same 18 independently**; same model family, stated.
5. H-3 facts:
   - **F0027 owns H-3** (L159) and rates it "containment vs adjacency **unevidenced**", with RO-2 routed to the **ARB** (L167).
   - **F0014 declares H-3 resolved** (L158) with **authority "—"**, and no ARB act is cited.
   - **F0027 was revised after F0014:** its I-4 row carries F0014's withdrawal (L108), yet H-3 was **kept unevidenced**.
   - **F0003** (H-7, L75), **F0035** (L29, L86) and **F0039** (L25) record nesting as open or unevidenced.
6. F0014's local derivation is **permissibility** (F0027's CONTAIN table permits space-in-space) **plus a hypothetical ERP deployment case**. It is not observed nesting (B-12 §7).

## 5. Open questions

- **H-3:** do Knowledge Spaces nest, and specifically, does the EKP contain the PKS or sit beside it? This is **research reconstruction**, and ultimately **ARB** (RO-2).
- **Canonical F0045:** what does it say about nesting, and when? **Unread by B-12.** The research's "F0040" *is* F0045, but its extraction must not stand in for reading F0045.
- **Canonical F0035 and F0036:** not read by the research. F0035 independently records H-3 open. F0036 contains an unrecorded internal withdrawal of its verdict.
- **The research citation form** (e.g. `F0014:G-4`, `F0027:H-3`): a research-protocol question (B-12 §11), **not** NR-1.
- **B-15:** the separation question.
- **GIA-5:** the known set for KOS-G-003.

## 6. Governance issues

| # | Issue | Evidence status | Owner | Governance repair? | Research correction? | Theory question? | L0 decision? | Phase |
|---|---|---|---|---|---|---|---|---|
| 1 | false-CLEAR governance paths | **established** (audit §6) | governance | **yes** (IC-1, IC-2, IC-4) | no | no | GIA-1, GIA-2 | extended 1a |
| 2 | false-FAIL G02 (canonical ID of an unread file) | **established** (audit §7) | governance | **yes, after GIA-5** | no | no | **GIA-5** | 1a (decision) → 1a/1b (fix) |
| 3 | self-test weakness (30/31; not linked to evaluation; missing fixtures) | **established** (A0, D06; audit §5) | governance | **yes** (IC-4, IC-7) | no | no | GIA-2 | 1a |
| 4 | gate-definition mutation under an activated ID | **established** (D08–D10) | governance | **yes** (IC-2, IC-3) | no | no | **GIA-4** | 1a |
| 5 | governance namespace collisions (NR-1; KOS-G-070…073; governance-minted G-4, H-1, D-n) | **established** (audit §11, §2A) | governance | yes (rule, then lint) | no | no | **GIA-6** | parallel, non-blocking |
| 6 | research-produced evidence-binding implementation | **present, unaudited** | governance (review) | review → adopt/adapt/reject | no | no | **GIA-9** | 1b |
| 7 | B-14: 18 unresolved canonical paths | **established**, independently reproduced | L0 (corpus list) | represent as `MISSING_CANONICAL_SOURCE` | no | no | **GIA-10** | before any admission control |
| 8 | B-15 separation question | **facts recorded; unclassified** | **L0** | no | no | no | **B-15** | standing |
| 13 | B-12's "no new G-4 governance rule" | **established** (B-12 §11) | governance | **no, and none to be created** | no | no | no (accept the finding) | closed |

## 7. Research provenance issues

| # | Issue | Evidence status | Owner | Governance repair? | Research correction? | Theory question? | L0 decision? | Phase |
|---|---|---|---|---|---|---|---|---|
| 9 | F0031–F0040 wrong-ID provenance (105 refs, 10 artifacts) | **established** | research, under an L0-authorized correction unit | no | **yes**: overlay / freeze-forward, never renumber in place (Increment 0 §5) | no | **RC-H-04** | after 1b |
| 10 | canonical F0032, F0035, F0036, F0040: original research omissions (never read) | **established** | research | no | **yes**: re-admission and re-read of canonical sources | possibly, after reading | RC-H-04 | after the provenance repair |
| 12 | canonical F0045 vs research "F0040" | **established identity** (research "F0040" = canonical F0045); **content not examined** | research | no | **yes**: re-bind; read canonical F0045 **from the corpus** | possibly | RC-H-04 | provenance repair |
| 14 | research citation form such as `F0014:G-4` | **open question** (B-12 §11) | research protocol, via L0 | **no** (not NR-1) | possibly (a protocol clarification) | no | **yes** (a research-protocol change under ARCH §9) | later |

**Recorded identities. Not repaired, and not inferred from research artifacts:**
- `F0035 = canonical source requiring re-admission/re-read after provenance repair` (`KnowledgeOS_Ontology_Discovery.md`, sha `15a60b7828b92e9c`). It independently records H-3 **open**. This is a fact from the canonical file (B-12 §8), separate from B-12's G-4 conclusion.
- `canonical F0045 = unresolved later evidence regarding nesting` (`KnowledgeOS_Semantic_Architecture_Reconciliation.md`). Its content is **not** to be taken from the research's "F0040" extraction. It is to be read from the canonical corpus during provenance repair.

## 8. Theory-reconstruction issues

**Issue 11: H-3 unresolved closure.**

| Item | Value |
|---|---|
| Evidence status | **established:** partially supported locally, unresolved in the canonical record |
| Owner | **research reconstruction**; substantive ruling **ARB** (RO-2) |
| Governance repair? | no |
| Research correction? | yes (see below) |
| Theory question? | **yes** |
| L0 decision? | yes (ARB for RO-2) |
| Phase | theory reconstruction, after the provenance repair |

**Current state: `PARTIALLY_SUPPORTED / UNRESOLVED`.**
- ⛔ F0014's closure is **not** to be converted into established theory.
- ⛔ F0014 is **not** refuted.

**Required consequences for specific research objects** (none edited here):

| Object | What B-12 found | Required change |
|---|---|---|
| **T-0022** "A deployment IS a nested Knowledge Space" (`RECONSTRUCTED_THEORY_OBJECT`, sources F0014) | cites the correct F0014 and its unique G-4; the name reads as an assertion; the research already marks it CONTESTED | **epistemic-status review** (the status and wording must reflect PARTIALLY_SUPPORTED/UNRESOLVED) · **missing-source addition** (F0027 §6 H-3 "unevidenced" and RO-2; F0027's post-F0014 revision; F0003 H-7; F0035; F0039) · **no provenance correction** |
| **DI-0014** (DISSOLUTION_BY_EXISTING_STRUCTURE) | premises are faithful to F0014; the second premise ("ontology already permits…") is **true of F0027's CONTAIN table** | **epistemic-status review:** record that the derivation establishes *permissibility*, not *evidence of nesting* (B-12 §7) · **missing-source addition** (F0027 §6 as a counter-premise) · no provenance correction |
| **SI-0005** (L2, *"closes H-3 by adding nothing"*) | the row wording is stronger than the canonical record; the seed's own reconciliation table marks it CONTESTED | **epistemic-status review** (the row text "closes H-3" versus CONTESTED) · **later re-reading** (F0035, F0045) before any change of level |
| **STR-0006** "Recursive containment" (carried from T-0022; F1; NOT LAB_READY) | depends on T-0022 | **no change now**; **theory reconstruction** follows the T-0022 outcome |
| **C-0010** (three-way: F0014 · F0026 · F0027) | faithful, but omits F0003, F0035 and F0039 | classified by cause, below |
| **T-0044 / SI-0043** "nesting contested within one day" (sources F0039 and research "F0040") | "F0040" = canonical **F0045**: part of the F0031–F0040 provenance problem, **not G-4** | **provenance correction** (re-bind to F0045 within the correction unit) · **later re-reading** of canonical F0045 **and** of canonical F0040 (README) · **epistemic-status review** afterwards (the claim of "one day" depends on F0045's actual date and content) |

**C-0010's omissions are not all of one kind:**

| Omitted source | Was it read by the research? | Nature of the omission |
|---|---|---|
| **F0003** | yes, correct ID, `READ` | **coverage/provenance defect:** a read source's H-7 ("Space NESTING … unevidenced … H-3 (Relationship Ontology)") was not recorded in the contradiction. ⚠️ Re-reading hazard: F0003's *own* `H-3` means something else ("PURPOSE more fundamental than CONTAINER"). The nesting item is F0003:**H-7** |
| **F0039** | yes, correct ID, `READ_COMPLETE` | **coverage/provenance defect:** a read source's "nesting unevidenced (H-3)" (L25) is in T-0044 but not in C-0010 |
| **F0035** | **no** (never read; ID defect) | **consequence of the F0031–F0040 problem**, not a recording defect. It resolves only after re-admission |

Neither kind **by itself** requires theory reconstruction. Adding the sources is a missing-source correction. Whether the added evidence changes the H-3 position is the research reconstruction's call, not governance's.

## 9. GIA-1 … GIA-10 impact assessment

| Decision | Changed by B-12? | Note |
|---|---|---|
| GIA-1 | no | — |
| **GIA-2** (extended 1a) | **no**; still valid | B-12 found no governance control gap |
| GIA-3 | no | — |
| GIA-4 | no | — |
| **GIA-5** | no | G02 is unrelated to G-4. It remains a canonical-ID false-FAIL |
| **GIA-6** (NR-1) | **strengthened in scope, not in content** | B-12 separates the two problems cleanly: **governance-minted identifiers → NR-1**; **corpus/research short-label citation → a research-protocol question** (issue 14). NR-1 must **not** be extended to corpus labels |
| GIA-7 | no | — |
| GIA-8 | no | — |
| **GIA-9** | **adds review items**, no change of decision | B-12 showed that parsing the canonical list must not split paths on whitespace, and that it yields exactly 18 unresolvable rows. The 1b review must verify that `build-manifest.py` and `admit.py` do the same (see below) |
| **GIA-10** | **corroborated** | an independent parse reproduces the 18 |

**Principles confirmed:**
- B-12 does **not** invalidate extended 1a;
- it creates **no** governance gate;
- it makes H-3 a **research reconstruction** matter;
- it authorizes **no** research repair and **no** F0031–F0040 correction.

No evidence found here changes any of these, so the decision package stands **unmodified**.

**GIA-9 review scope** (for 1b; nothing activated or modified now):
- `evidence/build-manifest.py`, `CORPUS-MANIFEST.jsonl`, `MANIFEST-HASH.txt`, `admit.py`, `README.md`, and `governance/PROPOSED-GATES-RCI.yaml`;
- the 18 unresolved paths;
- **paths containing whitespace**;
- **exactly-one-match** semantics, where an ambiguous prefix must STOP;
- receipt semantics;
- **zero-receipt semantics**: legacy rows INCONCLUSIVE, never back-filled;
- the interaction with **GIA-5**, since the manifest is the candidate known set;
- the **KOS-G-070…073** namespace collision (GIA-6).

## 10. B-15 status

- Unchanged: **facts recorded, unclassified, for L0**.
- B-12 adds nothing to B-15.

## 11. B-14 status

- **Established and independently reproduced** (18 paths).
- Proposed handling: `MISSING_CANONICAL_SOURCE`, and never reconstruct by guessing.
- The decision is **GIA-10**.

## 12. 1a readiness

- **Evidence: sufficient.** The specification is complete (package §7).
- The regression set is defined (B04, B09, D04, D06–D10, D16, D17, G01, G02, C1) plus the full 54-case re-run.
- **Blocking only on L0:** GIA-1, GIA-2, GIA-3, GIA-4 and GIA-7.
- GIA-5 is needed before G02 can be fixed. It does not block the other IC items.

## 13. 1b readiness

**Not ready.** It depends on:
1. 1a being accepted;
2. GIA-5;
3. GIA-10;
4. the independent review for GIA-9 (scope in §9).

1b starts as a **review**, not a build.

## 14. Required human decisions

| When | Decisions |
|---|---|
| Now, for 1a | **GIA-1, GIA-2, GIA-3, GIA-4, GIA-7** |
| Before 1b | **GIA-5, GIA-10**, then GIA-9 after the review |
| Standing | **GIA-6** (NR-1, governance identifiers only) · **GIA-8** · **B-15** |
| Research program (L0 / ARB) | **RC-H-04** (the correction unit for F0031–F0040, including the re-admission of F0032/F0035/F0036/F0040 and the reading of canonical F0045) · the **research citation form** (issue 14) · **RO-2 / H-3** (ARB, after reconstruction) |
| Administrative | commit the authoritative `GOVERNANCE-INTEGRITY-AUDIT-01.md` (§1) |

## 15. Explicit next-step sequence

1. **L0:** decide GIA-1, GIA-2, GIA-3, GIA-4, GIA-7 (and GIA-5 if possible).
2. **Governance:** extended 1a, in the order 1a.1 baseline → 1a.2 regression fixtures (RED) → 1a.3 IC-1…IC-8 → 1a.4 self-test at 100% → 1a.5 all 54 cases → 1a.6 independent review → 1a.7 **L0 acceptance**.
3. **L0:** GIA-5 and GIA-10.
4. **Governance:** the 1b independent review of the research-produced evidence binding → **L0 GIA-9** (adopt/adapt/reject) → implement or adapt → review → **L0 acceptance** and activation decision.
5. **L0:** authorize **RC-H-04**.
6. **Research:** the correction unit.
   - Provenance overlay / freeze-forward for F0031–F0040.
   - **Re-admit and read** canonical F0032, F0035, F0036 and F0040, and **read canonical F0045**, through the admitted evidence path.
   - Add the missing sources to C-0010.
   - Review the epistemic status of T-0022, DI-0014, SI-0005, T-0044 and SI-0043.
7. **Research:** H-3 reconstruction across F0014, F0027 (both versions), F0003 (H-7), F0035, F0039 and F0045. It then proposes a consequence. **ARB** rules on RO-2.
8. **Only then:** F0041+.

## 16. Responsibility boundary

| | May | May not |
|---|---|---|
| **Governance** | audit; implement **authorized** governance controls; test governance; maintain governance artifacts; prepare L0 decisions | repair research theory; decide H-3 substantively; rewrite F0031–F0040; promote F0014's closure to established theory; resolve F0045 from the research's "F0040"; activate unapproved gates; create a G-4 governance rule |
| **Research** | the later source re-reading; reconstruct provenance; compare F0014/F0027/F0003/F0035/F0039/F0045; propose theory consequences | modify active governance; activate gates; commit governance artifacts; accept its own governance controls |
| **Human authority (L0) / ARB** | the GIA decisions, B-15, RC-H-04, activation, acceptance, RO-2 | — |

---

*Traceability:* the three input artifacts (§§ cited inline) · hashes verified at `95916b397` · research read status of F0003/F0014/F0027/F0039 from `FILE-REGISTRY.jsonl` at HEAD (read-only). Nothing was modified or activated, and no research artifact was edited.

---

**READY FOR L0 DECISION**
