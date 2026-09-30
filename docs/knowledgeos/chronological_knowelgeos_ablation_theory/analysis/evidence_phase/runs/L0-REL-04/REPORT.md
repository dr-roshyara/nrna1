# L0-REL-04 evidence pass — R-39 pointer sections (reader SELF) — REPORT

| | |
|---|---|
| Release | L0-REL-04 (issued by the human adopting the review's wording: "follow the prompts"). S-3 = approval record §5; S-4 = separation finding §6; reader SELF; EPIC-004 closed |
| S-3 | `engineering/verification/reports/2026-08-01-classification-model-approval-record.md` · sha256 `729f7bbeb63d126652f972570a77ecc15a3ddad80728fd91b65c40fb6ffd38c0` ✔ · **§5 `## 5. Deliverables authorized on approval — prepared, not started`, L101–113 (end of file)** |
| S-4 | `engineering/verification/reports/2026-08-01-classification-placement-separation-finding.md` · sha256 `cd19b98c62011c6aa0d5b101a3163db53299256a16d600b38eef374a512f45f5` ✔ · **§6 `## 6. One observation about this whole exchange`, L103–111 (end of file)** |
| Files | `evidence.json` `b73041ea…` · `constraints.json` `5a55883d…` · cumulative (L0-REL-03 + 04): `../CUMULATIVE/evidence.json` `4e6737ef…` · `../CUMULATIVE/constraints.json` `79b7ed31…` · `../CUMULATIVE/IG-MATRIX.json` |
| Formal basis | SECONDARY-REPRODUCED (`results_ref.json` `1771c79a…`) |
| Reader disclosure | the SELF reader already knows R-39's content from the earlier R-39 SELF read (F-LOG-0053). **Not used as evidence**; only the released lines were coded |

## 1. Source fidelity (decided from the text, not inherited from the locate heuristic)
- **Both are SECONDARY REFERENCES**, not primary records of R-39.
- Both matching lines are **Traceability footers** of AI-authored verification reports (S-4 L107: "my first move should be a search"). They cite R-39 and do not record it.

## 2. Evidence
3 cells for EP-13c, all **AMBIGUOUS**, `interpretation_confidence` HIGH (the reading is clear; it simply does not decide the proposition). **0 events** (neither section states an R-39 event).

| Cell | Wording (byte-exact) | Source claim |
|---|---|---|
| S3-c1 (L113) | `**R-39 / proposed R-69** (precedent before structure)` | R-39, with the proposed R-69, cited as precedent for "precedent before structure" |
| S4-c1 (L111) | same | same citation in the companion finding |
| S4-c2 (L105) | `the DA's product clarification, R-39,` | R-39 listed among mechanisms "already in the repository" |

**This resolves the CAP-001 pointer's referent, not R-39's semantics.**
- The "old sense" of R-69 quoted in CAP-001 §9 L191 is **"proposed R-69 (precedent before structure)"**, paired with R-39, in a document-classification / placement context.
- **What R-39 decided is still not stated** in any released line.

## 3. R-39 semantic signature (O, E, B, G, T, P, V, X), from the released lines only

| Dim | Value |
|---|---|
| O operation | not stated. The lines record a **citation** of R-39 as precedent, not an operation of R-39 |
| E evidence | not stated |
| B bar / eligibility | **insufficient information**. Neither bypass nor modification is stated |
| G authorization | not stated |
| T order | not stated |
| P persistence | not stated for R-39's effect. The lines only show that R-39 is **cited as precedent** in later records (a citation practice, not a persistence semantics) |
| V validation | not stated |
| X exception semantics | **unclear**. "Precedent" and "mechanism" are stated; exception, threshold change and rule change are not |

## 4. Engine (frozen, unchanged)
- **This pass:** EP-13c AMBIGUOUS (SEMANTIC-AMBIGUITY, open); 18 SILENT; **no constraint**; every model NOT-ELIMINATED; no family flag.
- **Cumulative** (L0-REL-03 + 04, 7 cells, 4 events): **15 SILENT · 3 OUT-OF-SCOPE (EP-01, EP-02a, EP-02b) · 1 AMBIGUOUS (EP-13c, 4 claims)**; no constraint; every model NOT-ELIMINATED; no family flag.
- There was no schema mismatch this time.

## 5. Information-gain matrix (mechanical; frozen propositions × frozen results; uniform prior over the 4 primary models, H(M) = 2 bits; no corpus)

| EP (EQ) | Splits the primary models | IG |
|---|---|---|
| EP-03a (EQ-3) persistence · EP-04a / EP-04b (EQ-4) revocation / auto-invalidation | {MT1-P1, MT2-P1} vs {MT1-P0, MT2-P0} | **1.0 bit** |
| EP-02a / EP-02c (EQ-2) floor at promotion · EP-05b (EQ-5) re-promotion needs new evidence | {MT1-P1} vs the other three | **0.811** |
| **EP-13c (EQ-13, the R-39 pointer)**, EP-13a/b, EP-02b | no split (all four identical) | **0**: family-level test only |
| EP-01, EP-05a, EP-06…EP-12 | not expressible | 0 (family-level only) |

**Two mechanical findings** (facts about the frozen models and propositions; no model change):
1. **The R-39 pointer cannot discriminate the models.** Resolving EP-13c can only confirm or challenge the family as a whole: supported gives all four INCONSISTENT, so a family flag; refuted gives all four consistent.
2. **MT1-P0 and MT2-P0 are evidence-equivalent** over all 19 propositions (identical value vectors). The maximal resolution attainable with the current proposition set is **3 classes: {MT1-P0 ≡ MT2-P0}, {MT1-P1}, {MT2-P1}**. Two releases can reach it: one resolving EQ-3/4 (the P0/P1 axis), then one resolving EQ-2 or EQ-5 within the P1 pair.

## 6. Unresolved
- What R-39 decided: operation, bar bypass vs bar modification, authority, order, persistence, validation.
- EQ-2 / EQ-3 / EQ-4 / EQ-5: no evidence yet.
- **OBS-SF-1: unchanged.** These sections do not address the n ≥ 2 attribution.

## 7. Closed
- EPIC-004 not opened. No other section or file read. No link followed. No new search. No ML. No model selected, ranked, refined or modified.
