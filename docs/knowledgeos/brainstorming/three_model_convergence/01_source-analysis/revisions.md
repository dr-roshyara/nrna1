# 01_source-analysis — Revision Log (append-only)

**Purpose.** A later file may revise the correct reading of an earlier one. Per MD-004 the original
reading is **never erased**; the revision is recorded here and cross-referenced from both records.

**This log is append-only.** Entries are never edited or removed. A revision that is itself later
revised gets a *new* entry citing the one it supersedes.

**Scope.** This log records revisions of *interpretation* made during the sequential pass. The
wholesale re-assignment of primary/secondary classification happens once, after the pass, in the
**global reclassification** stage, and is recorded in `00_control/classification-register.tsv`
plus `05_cross-model/`-stage artifacts — not here.

---

## Entry format

```
### REV-nnn — file NNNN revised by file MMMM
- **Date recorded:**
- **Original reading (file NNNN, pass 1):**
- **What file MMMM shows:**
- **Revised reading:**
- **Kind:** refines | narrows | generalizes | renames | contradicts | replaces | reinterprets
- **Original classification retained:** yes (always) — provisional class unchanged / flagged for global reclass
- **Hindsight-bias check (§39):** does the revision read a *later* concept back into the earlier
  document? If yes, the revised reading is marked **LATER INTERPRETATION**, and the historical
  meaning is stated separately.
```

---

## Entries

*(none yet — the sequential pass is at file 0005)*

### REV-001 — file 0005 possibly revised/preceded by file 0033
- **Date recorded:** 2026-09-01
- **Original reading (file 0005, pass 1):** the BOUNDARY/TRIGGER/ADVISORY correction (19/22/16
  counts; "enforcement lives in the runtime adapter; capabilities live in the platform") was recorded
  as an apparently self-contained correction internal to 0005's own Tier 2 analysis.
- **What file 0033 shows:** 0033, also dated 2026-08-02, presents the identical taxonomy and
  identical numeric findings as its own original correction, triggered by adopting an externally-
  sourced (unread, second-hand) vocabulary — "Boundaries ≠ Triggers."
- **Revised reading:** both documents cannot be independent origins of an identical, numerically
  matched finding. Most economical explanation: 0005's correction block was appended on review after
  0033 existed, and the correction propagated backward without cross-reference — consistent with this
  corpus's general practice of dated after-the-fact annotations on earlier documents.
- **Kind:** contradicts (provenance dispute) — not resolved, recorded as open.
- **Original classification retained:** yes, always. 0005's provisional classification and Tier-2
  content are unchanged; this entry is a cross-reference, not a correction to 0005's record.
- **Hindsight-bias check (§39):** this does NOT read a later concept back into 0005 — the content in
  question (BOUNDARY/TRIGGER/ADVISORY) was already present in 0005's own text at first reading, dated
  the same day as 0033. The finding here is about *authorial* order, not about importing new content
  into 0005's analysis.
- **Consequence for the ledger:** "first occurrence in reading order" (§43's governing sequence) and
  "first occurrence in authorship" may not coincide in this corpus. The reading order remains the
  analysis's governing evidence base (§1 forbids reordering); this caveat is recorded for anyone later
  auditing priority/origin claims.
