# INVENTORY-REVIEW-F: adversarial review of canary-diag/FIELD-INVENTORY.md (read-only)

Paths: CR = chronological-read. VR7/U/W/E/C/V as in the inventory legend. rev3/R7 = the two contract files. No identifiers printed.

## VERDICT: RELIABLE-WITH-CORRECTIONS
The code citations, call graph and consequences I re-traced were accurate. I found 2 status misclassifications, 1 omitted
artifact block, 1 premise (row 41) refuted by counts, 1 new asymmetry, and a few weak or overstated rows.

Inventory counts (re-derived from the table): SPECIFIED 48, NAME-ONLY 18, ABSENT 10, CONTRADICTORY 9 (row 3 is dual-labelled CONTRADICTORY/NAME-ONLY and sits in
the 9). The 37 non-SPECIFIED figure is right.
Corrected counts: SPECIFIED 48 (53 out, 72 in), NAME-ONLY 19, ABSENT 10, CONTRADICTORY 8. Further arguable moves are listed under MINOR and are not applied.

## Call graph (task 2 precondition): confirmed
V.verify rev7 branch V:418-436 -> r7v.verify_r7 (VR7:69-215) only when run ends -R7; all historical gates (V:462-1061) then run on the same objects; failures are
mapped to U/E/R by tag prefix at V:1064-1085 (G-04, G-05, G-08, G-09, G-12, D-23, HUB and F-tags land in R; SCHEMA/G-01/G-02/G-07 in U; READ-COVERAGE/AUDIT in E).
VR7 reaches U (typing/meta/claims/plan), W (witness/W8), E (records/claims/quotes/census), C (S1/S3/summary/lifecycle), as listed in the inventory.

## Findings
1. MATERIAL. Row 53 is misclassified SPECIFIED; the whole file-reading-records layout is under-inventoried. rev3 has 0 hits for "fact"; R7:319 calls the kinds
   "values of the existing fact-kind dimension", which has no antecedent. The key names `facts`, `kind` (E:146-152), `reading_state` (only R7:643), record-level
   `source_id` and `ack_tokens` are never defined as keys. Rows 48/49/52/54/56-61 cover pieces; the container itself has no row. Reclassify 53 NAME-ONLY and add a
   row "FRR record layout".
2. MATERIAL. Row 41 premise is refuted by counts. Using the verifier's own pattern (c.holdout_pattern, c.holdout_sets), 0 of 2,734 non-hold-out corpus files contain
   a hold-out label name and 0 contain a hold-out S-id token (helper scripts ho_count*.py in the scratchpad). So "a verbatim quote of a page trips SEAL" has no
   empirical support, and "REDESIGN: mask at the reader" is unwarranted. The "magnitude unknown" caveat is now answerable: nil for file bytes.
3. MATERIAL (new, not in the inventory). W8 SEAL is evaluated with label=None (V:117 `R7_SEAL_CHECK`, W:265 `_hit(holdout, tin)` on the whole tool input), but S3 passes the
   label (C:169-172) and so honours the seal's allowed-mention lists. 15 of the S5 manifest labels (all non-hub; 2 hold-out files) have allowed hold-out S-id mentions
   in their discovery bundles. For those labels any Write whose content contains that token is accepted by S3 and is a W8 SEAL stop (batch stop, P3B-ESC). Trigger
   needs the agent to copy the token, but nothing in I(run) says so.
4. MINOR. Row 72 should be SPECIFIED: rev3 §D:241-243 and the binding §E schema (rev3:250 "binding") fix `scale` = OBJECT and `origin` = P3B; the conflict is only with
   descriptive prose (rev3:886, :889, :817). Row 5 applies exactly this "binding section governs" rule; row 72 does not.
5. MINOR. Row 21 first half: `status_basis.<field>.sources` as a list is stated by the rev3:281 schema (`sources:[S####]`). Only the scalar-string rule for free-text
   fields is unstated. Verified code: U:104-110, 289-303 untyped -> R7-U as described.
6. MINOR. Row 42 marked SPECIFIED but the element type of later_support/later_refinement/rejected_by (bare S-id strings, U:validation_violations) is not stated.
   rev3:265 and 1175 give `[]`; R7:384 says "its `source_id`", which reads like an object. R7:395 types only contradicted_by. Should be NAME-ONLY for three lists.
7. MINOR. Row 68 cites R7:122 ("never an exception"); that sentence is §2.5(5), the revision/contract-sha binding only, not a general fail-closed rule. The behaviour
   itself is right: `_jl`/`_js` (VR7:55-66) use bare json, a list-valued CE/LINT raises AttributeError, and main (V:1188) catches only Refused/S5Error/OSError.
8. MINOR. Dispatch prompt mitigations are cited for rows 2, 66, 81 only. render_prompt (orch:230-262) also lists the exact Write paths (incl. `p1-gap.jsonl`) and says
   "NO other tool calls". That neutralises rows 77 and 83 in practice and makes 29 a trap only if the agent chooses STAGE-2A-2B (still in the rev3 method enum).
9. MINOR. Weak SPECIFIED/NAME-ONLY calls, left unchanged: row 31 ("complete census" is undefined in R7; code E:259-269 means every record file of the label's reading
   runs, not just hit files), row 52 (R7:246 uniqueness key arguably specifies fact_id), row 57 (R7:322 gives `finding` domain; only "DEFINES required" is implicit).
10. MINOR. Row 38: 31 of 2,734 non-hold-out files contain a POINTER7 phrase (counts via C.POINTER7 after _pointer_norm). A verbatim quote spanning one is an unavoidable S3
    layer-A hit because row 38's scan includes quotes. Small, but a second "verbatim quote vs lint" trap.
11. MINOR. Row 84: code uses the last Write of ANY permitted file (W:409-413) whereas R7:671 says "last permitted object Write"; the code is more lenient than the text in that
    direction and stricter than R7 §7 for SINGLE reads. Row text is accurate about the strict side only.

## Task 2: the 18 "new" items (consequences re-traced)
- 3 CONFIRMED. All 396 manifest entries carry the rev3 contract sha (counts), one shared input_manifest_sha256; R7:122 asks the addendum sha; slices carry both (pr:417-418). V:511-513 -> G-07 -> U.
- 18 CONFIRMED. U:340-343 `SID.findall(str(v))` per births value; whole unless BIRTH-UNRESOLVED-; r5:452-455 S2 row-source rule runs via C.edge_and_s2_violations. Consequence R7-E claim failure and R7-R S2. The registry text arguably implies it (INV-LEX); NAME-ONLY is defensible.
- 21, 22 CONFIRMED (22 verified for `states.step`, `date_basis`, `supplied_by.anchor`, census `anchor`/`stage1_terms`: no registry row, not a source key -> "untyped", R7-U).
- 23 CONFIRMED by counts: 17 of 143,789 ledger hits carry an S-id token in the anchor; 15 labels in the search file, 9 in the S5 manifest, 7 of those hub (stage 2 empty), 2 non-hub.
  `hit_key` has no registry row (path regex ^stage2_dispositions\[\*\]$ only) and V:698-700 forces the exact key: unavoidable R7-U for those 2 labels.
- 28 CONFIRMED. V:715-723 (rev4+ adds NOT-CONSUMED-ESCALATED), V:852-857 (unread hit file fails G-04), V:880-882 (method WHOLE-FILE enters need_cov even if all ESCALATED -> READ-COVERAGE). Consequence tag R/E, not an exception.
- 29 CONFIRMED. scan_occ is built only from log entries with `tool` (V:677-681); W8 lists no scanner call (R7:489-501); R7 has 0 hits for Stage-2A. G-04 + F3 failures.
- 40 CONFIRMED (C:155-164, cm:166-207). "from S#### to S####" matches SID_BETWEEN -> S3 range failure regardless of hold-out coverage; SEAL only if the span covers a hold-out id.
- 41 corrected, see findings 2-3. 44, 45 CONFIRMED: `source_meta` fields = META_FIELDS (pr:55-56) lack order_evidence; 02-FILES: 167 of 1,167 EXPLICIT files have order_evidence exactly SOURCE_ID.
- 66 CONFIRMED (E:166-168 `run not in reading_runs`; orch:126 tells agents to use their own run id). 68 CONFIRMED (finding 7). 72 see finding 4.
- 74 CONFIRMED (V:970-971; no hashing tool allowed by W8; fails as G-09 -> R). 81 CONFIRMED (W:82-92: no numbered lines -> content_match False -> R7-W). 83 CONFIRMED statically.
- 84 CONFIRMED with the nuance of finding 11.

## Task 3: sample of 8 SPECIFIED rows
Rows 2, 10, 16, 24, 27, 35, 78 hold (name and value domain found: R7:126-130; R7 §3.3 note on escalations.field; rev3:178-198; rev3:71 literal Tier Z note string; R7:639-651;
R7:631; R7:482-485). Row 19 holds on the name but `anti_projection` has no value domain beyond "truthy". Row 42 fails (finding 6). Row 31 is weak (finding 9). 6 of 8 sound, 2 weak/failed.

## Task 4: completeness
extract_keys.py/keys.tsv: 1,015 occurrences, 341 distinct literals; 120 are not textually in the inventory. Triage: all are witness/plan/manifest/slice/transcript keys, enum values covered
by a general row (NOT-CONFIRMED, LATER-THAN-FIRST-APPEARANCE, GENUINELY-UNDEFINED-AFTER-CENSUS, gap_status, escalations reason), alert-only (`execution_impact`, V:968) or
bookkeeping. The one agent-artifact gap is the FRR record layout (finding 1). No other agent-artifact key on the R7 path lacks a row. Dynamic keys remain covered by table reading only.

## Strategic section (no decision)
Counts: 37 non-SPECIFIED rows before and after my two moves (19+10+8). By first-listed remedy in the inventory: EXPOSE only about 20; DERIVE/REMOVE of an agent-emitted value about
10 (3, 14, 44, 45, 51, 64, 65, 66, 67, 74); REDESIGN of verifier or contract behaviour 5 (18, 23, 41, 68, 81-variant). v2 covers 20 rows fully and 3 partly (inventory last column), 15 not at all.
For "annex the rules into R7":
- About 20 rows are plain missing text (value domains, equalities, key names). Text fixes them without touching verifier logic.
- 48 of 85 rows (56%) are already specified; the contract is mostly sound, and rows 56-61 are one table.
- Row 41 magnitude is nil (0 of 2,734), removing the strongest redesign argument; rows 77, 83, 29 are already neutralised by the dispatch prompt.
- A redesign re-opens witness/evidence/typing code that already has 130+ numbered acceptance tests (R7 §11, T01..T135+).
Against annexing / for a minimal-observation redesign:
- 10 rows are values a tool computes better: sha256s (74, 67), claim ids and CE (64-66), ack tokens (51), edge_class (14), position and precedence (44, 45), contract/manifest shas (3).
  Annexing explains how an LLM must compute hashes it cannot compute under W8 (74) or copy values that R7 itself mislabels (3).
- At least three failures are not curable by text: 23 (S-id inside a must-copy key under default-deny), 74 (no hashing tool), 28/29 (rev3 method enum vs R7 W8). They need a code or schema change either way.
- R7 closed-world typing (22) plus per-field rules creates latent traps (S-id in any untyped free text); each annex rule adds another agent-side constraint, and the agent already reads rev3 (1,358 lines) plus R7 (954).
- Cost of annexing is not small: ADDENDUM_SHA256 is pinned (U:25) and I(run) categories are a closed enum (R7 §5.8), so an annex means a new frozen addendum sha or a new category, both a contract change.
- Findings 1 and 3 show the inventory method (agent-visible text vs code) keeps finding new cross-artifact rules (FRR layout, label-less SEAL); a derive-everything design would remove that class.
Open data for the decision: the share of the 10 derive/remove rows that the v2 package already removes (R1(a)-(c)); whether the 2 labels in row 23 and the 15 labels in finding 3 can be excluded or handled by a tool-side fix.
