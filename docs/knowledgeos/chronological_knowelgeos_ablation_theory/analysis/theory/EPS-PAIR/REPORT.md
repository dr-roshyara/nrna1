# 1y: the search for an evidence-only minimal pair (supersession)

| | |
|---|---|
| Status | research record. Not canonical. Authority: none |
| Specs | locate spec `SPEC-LOCATE.json`, frozen at `d0ee57bab` · pair spec `SPEC-PAIR.json`, frozen at `eac99ae8e`. Both were frozen before the corresponding step ran |
| Read | register `7795c14b…`, row L80 (R-94) only |
| Rerun | `tmin_check.py` (`7da8cf57…`): instrument logic unchanged; R-94 added as two coded events. Output `TMIN-RERUN.json` (`dd102a6a…`) |
| Log | F-LOG-0130 |

## Locate (mechanical)
- Across all register rows except R-77 and R-83, **only two rows contain a positive supersession term**:
  - R-94 (2026-08-04, "Delivery Governance · Disposition · ARB CHIEF");
  - R-33 (2026-07-08, no category triple).
- **Explicit supersession is almost absent from the register.** This fits the record invariant: change happens by annotation or a new act, and "supersession requires an EXPLICIT ACT, NEVER INFERENCE".

## R-94 (SOURCE-FACT)
- R-94 is a remedy choice among "retain · annotate · supersede". **ANNOTATE is adopted.**
- Grounds as issued: "the historical acceptance remains correct; **superseding would blur chronology**; leaving it unchanged risks future readers assuming today's implementation was the accepted scope. **Annotation preserves both truths with minimal governance change.**"
- "The row keeps its ✅ and its 100% — PB-006 is neither reopened nor re-verified."
- Evidence: a scope-boundary finding.

## Results
- **ε-pair (frozen rule): NO-PAIR.**
  - The supersede option was declined, not performed, so the outcome matches R-83 (both refusals).
  - The object kind also differs: an acceptance record here, a design rule in R-83.
  - **Evidence remains UNDETERMINED.**
  - The register records **no performed supersession** among the rows examined; R-33 is unread.
- **Supersession is declined whatever the evidence:**
  - with no evidence (R-83);
  - with insufficient evidence (R-77);
  - **with evidence present, on a record principle** (R-94: chronology).

  So evidence is **not sufficient** for supersession (MODEL-DERIVED on three refusals).
- **The rerun gives a new minimal pair on operation:** SUPERSEDE declined vs ANNOTATE performed, with the same authority, object, evidence and state (R-94).
- **Caveat (outcome ≠ legality):** this is a **choice among options**, grounded in a principle. It is not a refusal on grounds of legality.
  - So the operation is **NECESSARY for the outcome**, but **UNDETERMINED for legality**.
  - The other NECESSARY pairs are all rule-grounded refusals, so they are legality pairs:
    - authority: "declines … in the Chief's own favour";
    - kind: the register holds constitutional decisions only;
    - prior state: "implementation does not begin" / "not permitted".
- **Theory refinement (MODEL-ASSUMPTION, source-motivated):** a decision has **two layers**.
  - **Legality:** guards determine which operations are permitted.
  - **Choice:** among the legal operations, one is selected by stated principles. Examples: "minimal governance change", "preserve chronology", "reopening requires materially new evidence".
  - T-min's single `outcome` field conflated the two. Future coding must mark each refusal as **RULE-GROUNDED** or **CHOICE-GROUNDED**.

## Next (highest information per exposure)
- **(a) A different source family with a structured supersession field.** The ADR files' own **Status** lines (e.g. "Superseded by …") are structured and mechanically locatable, and they record performed supersessions with authority. A locate-only pass over the ADR status lines could supply the performed supersession the register lacks. That completes an ε-pair, or shows evidence is not what gates supersession.
- **(b) Optional:** a bounded read of R-33, the last register candidate.
- **Route (G-R vs G-O):** still no route-only pair. It stays open until a source varies route alone.

## 1y″(a): ADR-MP bounded read (spec `SPEC-ADRMP-PAIR.json`, frozen at `a98eaa9ad`; lines L1–L9 and L80–L81 only)
- **SOURCE-FACT:**
  - "Status: Accepted · 2026-07-07 · supersedes the bundled Decision-Log entry D-12 (now a pointer)".
  - "Rule honored: one architectural question per ADR (D-12 was too large; split here)".
  - "D-12 is retained as a pointer … the log records that the decision was made and split; the ADRs hold the authoritative, single-question records".
- **Coding:**
  - o: SUPERSEDE, performed as a **split**;
  - a: NR;
  - k: Decision-Log entry;
  - ε: NR (the listed sources are traceability pointers, not a stated basis);
  - grounding: **RULE**, a record-structure rule.
- **Pair: NO-PAIR.** The authority is unknown and the kind differs. **ε stays UNDETERMINED.**
- **Record prediction SUPPORTED in a second source family.** The superseded text is kept as a pointer, and authority moves to the new records. A candidate coordinate follows: **authoritative location** (which record currently holds authority).
- **Supersession grounds across all four observed cases:**

  | Case | Outcome | Ground | Kind of ground |
  |---|---|---|---|
  | R-77 | refused | evidence insufficient | RULE |
  | R-83 | refused | no evidence | RULE |
  | R-94 | declined | chronology | CHOICE |
  | ADR-MP | **performed** | "one question per ADR" | RULE, record structure |

  **Evidence never appears as the stated ground of a performed supersession.**

## 1y″(b): legality vs choice (`tmin2_check.py` `ca5f2687…`, committed before its run at `c4799ba0f`; output `TMIN/RESULT-LEGALITY-CHOICE.json` `f5493cd2…`)
- **Events:** 24 in total. Refusals: 9 RULE-grounded, 2 CHOICE-grounded.
- **LEGALITY** (performed + RULE refusals):
  - **authority, kind and prior state are NECESSARY**;
  - operation, route, evidence and exception are UNDETERMINED;
  - **one sufficiency counterexample remains** (R-86 vs R-91). The missing variable is procedural conformance / role separation.
- **CHOICE** (performed + CHOICE refusals): **operation is NECESSARY** (R-94); nothing else is.
- **The RULE refusals cite seven kinds of ground:**
  - the evidence bar (L493-B, R-77, R-83), always on standing-changing operations (RAISE, SUPERSEDE);
  - self-authority (the Chief);
  - role separation (R-91);
  - an unmet proviso (R-72);
  - permission only (WP-8);
  - a freeze (L205);
  - admissibility (R-90).
