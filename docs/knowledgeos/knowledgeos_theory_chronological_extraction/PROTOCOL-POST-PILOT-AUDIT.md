# Protocol Post-Pilot Audit

Audit of the seven post-pilot optimizations plus the cross-section consistency sweep, against Batch 001 evidence only.

---

## 1. Audit of the seven applied optimizations

| ID | Section | Pilot evidence | Implemented | Internal conflict | Safeguard preserved | Action |
|---|---|---|---|---|---|---|
| **OPT-1** | §41 artifact tiers | 10 of ~22 artifacts would be empty; 2 most valuable not listed | ✅ CORE/CONDITIONAL/DERIVED present | ⛔ **YES — two found** (see AUDIT-02, AUDIT-03) | ⚠️ **weakened, now repaired** | **MODIFIED** |
| **OPT-2** | §9 checklist framing | 30 steps ≠ 30 acts; steps 4–10 are one reading pass | ✅ present | none | ✅ all substantive checks retained | **KEEP** |
| **OPT-3** | §0E.4 6+6 dimensions | 6 conditional dims `NOT_APPLICABLE` on all 10 files | ✅ present | none | ⚠️ needed an anti-escape rule | **MODIFIED** |
| **OPT-4** | one event / one primary record | one refutation written to 3 registers | ✅ present, with routing table | none | ✅ distinct semantics preserved | **KEEP** |
| **OPT-5** | §1 P3A consult + verify | P3A had the data; P3A was wrong 3/10 | ✅ present, both halves | none | ✅ §1's "P3A ≠ truth" intact | **KEEP** |
| **OPT-6** | §5A `UNRECOVERABLE` + `revision_type` | IFR-0008 original unrecoverable; 5 revision kinds observed | ✅ present | none | ✅ strengthens §28 | **KEEP** |
| **OPT-7** | §14/§49 `OUT_OF_WINDOW_DEPENDENCY` | 12 cited instruments outside the window | ✅ present | none | ✅ protects `UNDERIVED_BY_CORPUS` | **KEEP** |

## 2. Audit findings requiring change

### AUDIT-01 · `BLOCKING` · §3 — the pilot's own date fix repeated P3A's error shape

**Finding.** The first correction replaced P3A's scalar `best_historical_date` with a scalar `authored_date_from_content`. Same shape, one level up.

**Evidence (from the pilot's own output, not argument).** `FILE-REGISTRY.jsonl` contains `date_type: "COMMISSION_DATE + REVISION_DATE (REV 2)"` for F0002/F0003/F0006 and `"COMMISSION_DATE + IN-FILE CORRECTION DATE (same day)"` for F0005 — two typed events concatenated into one string, because one scalar cannot hold what the file says.

**Risk.** A scalar forces an arbitrary pick whenever a file has more than one date about itself. That is the precise mechanism that produced the 3/10 P3A failures.

**Action — DONE.** §3 now defines typed `date_events[]` (`date_value`/`date_type`/`date_source`/`date_basis`/`date_confidence`/`applies_to`/`evidence`) with a closed 10-value `date_type` list, and derives `historical_sequence_date` under four ordered rules. Rule 1 — *only `applies_to: THIS_FILE` events may set position* — is the single rule all three P3A errors violated. `UNCERTAIN` is a permitted outcome and an arbitrary pick is explicitly forbidden. Propagated into §9's record schema; stale §49 references updated.

### AUDIT-02 · `REQUIRES_CHANGE` · §41 — artifact tiering weakened negative evidence

**Finding.** OPT-1 said an absent Tier-2 artifact is valid "provided the batch report states *no X found*". That is prose, not a checkable claim, and it silently merged four different states.

**Risk.** Exactly the failure §37 check 12 exists to catch, one level up: *not searched* quietly becoming *not present*. Artifact minimization must never buy itself negative-evidence laxity.

**Action — DONE.** §41 now requires an explicit absence reason per un-created Tier-2 artifact: `ABSENT_BY_CONTENT` / `NOT_SEARCHED` / `OUT_OF_WINDOW` / `UNCERTAIN`, recorded as `tier2_absences{}` in `RECONSTRUCTION-STATE.json`, with `ABSENT_BY_CONTENT` stated to be a **positive claim that must be earned by an actual search**, and `NOT_SEARCHED` named as the honest default under doubt.

### AUDIT-03 · `REQUIRES_CHANGE` · §41 — the output tree contradicted the tiers it introduced

**Finding.** The pre-existing tree listed `CONTINUITY.jsonl`, `BRANCHES.jsonl`, `MERGES.jsonl` etc. as flat mandatory entries while the new tier block reclassified them; and `SOURCE-LOCAL-IDENTIFIERS`, `INTRA-FILE-REVISIONS`, `P3A-COMPARISON` existed in the tiers but were absent from the tree.

**Risk.** Two sections of one document giving different answers to "must I create this file?" — §5's no-duplicate-authority failure, introduced by the optimization itself.

**Action — DONE.** The tree is now declared the *catalogue of names*, every entry carries a `[1]`/`[2]`/`[3]` tier marker, the three new artifacts are listed, and `RECONSTRUCTION-STATE.json` was added (it had been missing from the tree entirely).

### AUDIT-04 · `REQUIRES_CHANGE` · §0E.4 — conditionality could become an escape hatch

**Finding.** OPT-3 made six dimensions conditional without defining what licenses `NOT_APPLICABLE`.

**Risk.** "Not applicable" degrading into "not examined" — the same class of error as AUDIT-02.

**Action — REQUIRED, applied below.**

## 3. Cross-section consistency sweep

| Pair | Result |
|---|---|
| §0A ↔ §0E | consistent — behavior vs recording split holds |
| §1 ↔ §42 | consistent — §1 now mandates consult-and-verify; §42 keeps recovery-rate framing and still forbids "recall" |
| §2 ↔ §3 | consistent — complete-file reading is what makes typed date events possible |
| §4 ↔ §4A | consistent — padding convention defined once, applied in both |
| §5 ↔ §5A | consistent — three revision cases distinct |
| §7 ↔ §8 ↔ §31 | consistent — verified-edge/theory-edge separation intact; §7's optional `theory_objects[]` unchanged |
| §13 ↔ §14 ↔ §15 ↔ §16 | consistent — `OUT_OF_WINDOW_DEPENDENCY` sits below `UNDERIVED_BY_CORPUS` and cannot be promoted without §15's full search |
| §19A ↔ §19B ↔ §19C | consistent — candidate/reconstructed/validated/canonical ladder unbroken |
| §30 ↔ §30A ↔ §43 | consistent — implementation firewall untouched by this pass |
| §36 ↔ §36A ↔ §37 | **one gap, now closed** — `tier2_absences{}` added to state so §37 can check it |
| §41 ↔ §46 | consistent after AUDIT-03 |
| §45 ↔ §49 | consistent — Gate 0 precedes Gates 1–3; §49 carries all pilot blockers |
| §9 step refs (15/16/24) | valid — step numbering unchanged by this pass |
| ID namespaces (F/E/AR/T/TH/RO/DI/CT/D/P/A/S/R/G/C/B/M/I) | all defined in §4A |
| Fence balance | 276, even |

**No contradictory definitions, no references to removed fields, no undefined identifiers found.**

## 4. Scalability check — did rigor survive the optimization?

| Safeguard that demonstrably prevented an error | Still enforced? |
|---|---|
| §2 complete-file atomicity | ✅ untouched — and now load-bearing for §3's date typing |
| §19A six-criteria identity test | ✅ untouched |
| §1 P3A ≠ truth | ✅ strengthened |
| §28 no silent repair | ✅ strengthened via `UNRECOVERABLE` |
| §33 historical-completeness bar | ✅ untouched |
| §37 batch validation | ✅ extended to cover `tier2_absences` |
| §25 negative-evidence discipline | ✅ **restored** at artifact level by AUDIT-02 |

Recording surface reduced; **no epistemic safeguard was removed or narrowed.**

## 5. Verdict

**Pilot result: PASS WITH CORRECTIONS.** All four audit findings are closed in-document.
