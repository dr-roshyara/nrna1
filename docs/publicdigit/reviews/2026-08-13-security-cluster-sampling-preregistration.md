# Security-cluster shape measurement — **PRE-REGISTERED sampling design**

**Commission:** Product Owner · Session 1 · *"define the sampling method before reading additional files"*
**Date:** 2026-08-13 · **Checkpoint:** `5e116c02` · **Status:** **design only — NOT ONE SAMPLED ROW HAS BEEN READ**

> **This document is registered BEFORE reading, so the result cannot be steered by which files I happen to open.** **That is its entire purpose.** Any later deviation from the rule below must be recorded as a deviation, not silently applied.

---

## 1 · ⛔ Frame correction — my published label was wrong

**I reported: *"`Tests\Unit\Domain\Election\Security\*` holds 539 rows."*** **Wrong.** My pattern `grep -F 'Election\Security'` matched **three** namespaces; my `find` then searched only the Domain path, which is why 38 of 76 files appeared to be "missing".

**The corrected frame, reconciled in both dimensions:**

| Namespace | Rows | Files |
|---|---:|---:|
| `Tests\Unit\Domain\Election\Security\*` | **312** | 37 |
| `Tests\Unit\Application\Election\Security\*` | **218** | 38 |
| `Tests\Feature\Election\Security\SecuritySchemaTest` | **9** | 1 |
| **TOTAL** | **539** | **76** |

**Both dimensions reconcile exactly (312+218+9 = 539 · 37+38+1 = 76).** **The 539 total was always right; only the namespace attribution was wrong.**

**Eighth incident, and the fifth of attribution rather than location.** **Note the shape of it: the cluster spans BOTH the Domain and Application layers, which the wrong label concealed — and that distinction matters to the finding, since Domain and Application tests answer different questions.** **Also recorded: three basenames (`PolicyPurityTest` · `DeviceBindingPolicyTest` · `NetworkBindingPolicyTest`) exist TWICE in different sub-namespaces, so the frame is keyed on the FULL class name, never a basename.**

## 2 · Why my first sample was not a measurement

**4 files · 36 rows.** **File-selected, and selected because their names looked business-relevant** — the error I then had to correct. **It also over-weighted small files: 36 rows from 4 files (mean 9) against a cluster mean of 7.1, while the largest class alone holds 33.** **It served as a probe. It is not evidence about 539 rows and is excluded from the measurement below.**

## 3 · The registered rule

| | |
|---|---|
| **Unit of classification** | **the ROW** — the matrix's unit. *(My probe sampled files, which is why it could not estimate a row proportion.)* |
| **Frame** | all **539** rows, ordered canonically: `namespace` → `class` → `test name`, ascending, byte-wise |
| **Selection** | **systematic:** take rows at positions **5, 15, 25, …** → every **10th** row, fixed start **5** ⇒ **n = 54 rows (10.0%)** |
| **Why systematic, not random** | **reproducible and auditable** — anyone can re-derive the identical 54 rows from the frame. A seeded RNG would add opacity for no gain, and unseeded randomness would make the sample unrepeatable |
| **Substitution** | 🔴 **FORBIDDEN.** If a selected row is hard to read, that fact is recorded — the row is **not** swapped for a more convenient one |
| **Reading rule** | read the **assertions of the selected test method**, plus only as much class context as those assertions require |

## 4 · Classification scheme — by assertion purpose, decided in advance

| Code | Meaning | Discriminator |
|---|---|---|
| **S1** | **Type/enum contract** | asserts a declared case, constant, or a method returning a fixed value of its own type |
| **S2** | **VO structure / immutability** | asserts `readonly`, construction round-trip, or that a property holds what the constructor received |
| **S3** | **Architecture fitness** | reflects over the codebase — forbidden names, topology, existence |
| **B1** | **Election business invariant** | asserts an election-domain outcome that could be wrong in production |
| **B2** | **Security/trust invariant with business consequence** | asserts a trust/authorisation outcome that changes what a participant may do |
| **X** | **Indeterminate** | cannot be classified from its assertions |

**The decisive test for S vs B, fixed now so it cannot drift:**

> **Would this assertion still pass if every Election business rule were implemented wrongly?** **YES → S. NO → B.** *(The same discriminator already applied to the facade's 17 delegation rows.)*

## 5 · Precision — and the limitation that dominates it

**Reported as a proportion with an interval, and the interval must be honest:**

* **Nominal:** 54 of 539 under simple random sampling ⇒ roughly **±13 pp** at 95% for a mid-range proportion.
* 🔴 **THE DOMINANT LIMITATION — CLUSTERING.** **Rows are NOT independent: a 22-row enum test contributes 22 near-identical rows.** **The effective sample size is closer to the number of DISTINCT CLASSES hit than to 54.** **So the reported interval will be stated in terms of classes-hit, and the nominal figure explicitly labelled as an understatement of the true uncertainty.**
* **Systematic sampling over a name-sorted frame carries a periodicity risk** — adjacent rows in one class are contiguous, so a fixed stride of 10 systematically under-samples classes with fewer than 10 rows. **Consequence stated in advance: small classes are under-represented by construction, which biases the estimate TOWARD the shape of LARGE classes.** **Since the largest classes carry the reflection indicator, this bias runs TOWARD finding "structural" — so a structural result must be discounted for it, and a business-heavy result would be the more surprising finding.**

## 6 · What the result may and may not support

**May:** an estimate of the row-proportion by category, with stated bias direction and clustering caveat · a decision-quality input to `SD-4`.
**May NOT:** characterise any un-sampled class · classify any row by name · reframe `SD-4` (the Product Owner's) · assert business coverage of the 1,376 · claim a defect. **A structural finding is a SCOPE fact about the programme's universe, never a criticism of the tests.**

## 7 · Stopping rule and disclosure obligations

**All 54 rows are classified — no early stop on a trend.** **If `X` exceeds 10% of the sample, the `X`-rate is reported rather than rows being forced into a category.** **Every deviation, unreadable row, and mid-course judgement call is recorded.** **The count `L3` is NOT advanced by sampling** — sampling measures shape; only a full per-row read classifies a matrix row.

## 8 · Scope discipline

**This is secondary to Election-Only delivery and blocks nothing.** **`EM-VOT-002` verification remains Session 3's to complete and Session 1's to check afterwards.** **No production, schema, migration, Constitution, test or fixture change; Session 3's implementation is not consumed as authority.**

---

**PRE-REGISTRATION COMPLETE — EXECUTION NOT STARTED**
**FRAME: 539 rows · 76 classes · 3 namespaces · n = 54 (every 10th, start 5)**

**Traceability:** master matrix `2026-08-08-election-master-matrix.tsv` (frame source, column 2) · probe sample and its withdrawal (`5e116c02`) · SD-4A class A/B/C/D triage · capability boundary report §5–§6 · facade delegation finding (`f2e0c3e9`)
