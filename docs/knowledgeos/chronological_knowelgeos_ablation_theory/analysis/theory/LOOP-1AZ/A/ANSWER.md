# Which H-D / H-B differences can this record test?

Labels: **SOURCE FACT** (verbatim in SOURCES/RECORDS) · **INFERENCE** (derived; premise named) · **HYPOTHESIS** (claim of H-D or H-B) · **UNKNOWN** (not determinable from the corpus).

Inputs: only HYPOTHESES.md, RECORDS.json, CODING.json, CODING-REVIEW.json and SOURCES.md. I applied the reviewer's corrected values (R04 about_actor → null; R06/R07 actor/stated_by → "the Authority"; stated_by statuses of R10, R12, R13, R14 → INFERENCE; R18 target narrowed). I used the reviewer's norm_ids set (31 records). Quotes drop markdown `**`/`*`.

**Ordering rule.** I ordered by the register date column first. Same-day ties are broken by register number. That numbers follow filing order is an INFERENCE, supported by "The next ruling is R-91" and the R-89 collision note. Only the git commit (R11) has a time of day.

---

## Part 1: norm-change sequences (targets with ≥ 2 NORM records)

### S1: Slice 7B execution (R14 → R31 → R32; all 2026-08-01, register order)
- UNAUTHORIZED. R14 / R-47: "7B and 7C are NOT authorized and follow their own gates" (SOURCE FACT).
- → [R-56, ARB, *approve plan*. This is a planning act and does not change execution polarity. It is not a coded record.]
- UNAUTHORIZED. R31 / R-56 recording note: "Execution of 7B is a SEPARATE act and has NOT been issued". The consequence column agrees: "execution still NOT authorized" (SOURCE FACT).
- → [R32 / R-58, **ARB**, *authorize*: "SLICE 7B EXECUTION AUTHORIZED — Execution Governance · Authorization · ARB"]
- AUTHORIZED. R32: "Engineering is authorized to begin Slice 7B RED on keystones K1–K10, with K9 inside 7B."
- **Polarity change PC1**: UNAUTHORIZED → AUTHORIZED. The act is attested (ARB, R-58). No execution record exists for 7B. That is a corpus limitation.

### S2: Slice 7C execution (R14 → R33 → R34; 2026-08-01, register order)
- UNAUTHORIZED (R14). It is restated as UNAUTHORIZED in R33 / R-58: "Slice 7C remains unauthorized".
- → [R34 / R-65, **ARB**, *authorize*: "SLICE 7C AUTHORIZED — Execution Governance · Authorization · ARB."]
- AUTHORIZED. "Slice 7C AUTHORIZED; engineering may begin RED".
- **PC2**: UNAUTHORIZED → AUTHORIZED. The act is attested. There is no execution record.

### S3: WP-7B-R1 (R08 → R09 in R-60, 2026-08-01; → R04 in R-70, 2026-08-02)
- Status: non-ruling. R08: "the issuing text placed this motion under a heading reading "Recording Notes -- Not Part of Ruling"".
- → [R09, register's recorder (INFERENCE; the text is passive), *file as ruling*: "A recording note cannot open a work package — … — so it is filed as a ruling in line with the operative text."]
- Status: ruling. "WP-7B-R1 OPEN (not started)".
- → [R04 / R-70, **ARB**, *authorize*: "WP-7B-R1 AUTHORIZED TO IMPLEMENT — Execution Governance · Authorization · ARB."]
- AUTHORIZED. The implementer is not named, per the reviewer.
- R08 → R09 is a **status flip** (note → ruling), not a deontic polarity change. The step "open, not started" → AUTHORIZED is only an **implicit** polarity change (PC4, INFERENCE), because no record codes WP-7B-R1 as UNAUTHORIZED. There is no execution record.

### S4: WP-4B implementation (R10, 2026-08-02 → R36, 2026-08-04)
Merging the two targets is an INFERENCE: R36's batch-7 items are a sub-scope of WP-4B.
- PROHIBITED (contingent). R10 / R-72: "THAT PROVISO IS NOT MET TODAY, SO IMPLEMENTATION DOES NOT BEGIN"; "CONSEQUENCE: no RED begins."
- UNKNOWN: R-73..R-76, R-78, R-80, R-82, R-84 and R-85 are referenced but their text is absent.
- **Execution record R11** (2026-08-03 16:10:37 +0200): "Subject: test(adjudication): WP-4B RED -- the conclude-to-issue seam and its redrive". The commit body says: "Four keystones written; 4 failed (0 assertions). STOP at the RED boundary."
- Source only, not a coded record: R-77 (2026-08-03, time unknown) says "AND WP-4B REMAINS BLOCKED".
- Source only: R-81 (PREPARED, 2026-08-04) says "Batch 7's freeze is lifted FOR THIS REPAIR ONLY." This implies a freeze state whose creating act is not in the corpus.
- → [R-86, **Decision Authority**, *adopt + authorize*: "Decision Authority confirms and adopts R-81 through R-85 without amendment. These become governing rulings effective immediately."]
- AUTHORIZED. R36: "engineering is AUTHORIZED to execute R-81's per-process isolation, R-84's §12 reconcile (…)". Also: "Engineering is authorized to resume implementation…"
- **PC3**: PROHIBITED → AUTHORIZED. The act that produces the end state is attested (R-86). **The path is not**: "resume" and "freeze" imply intermediate states whose acts are missing. **R11 is the only execution record in the corpus, and it falls between the two norm states.**

### S5: the register's admissible content (R06, R07)
One span coded twice ("the register holds constitutional decisions"). The two records cannot be ordered and show no change. The sequence is degenerate.

Every other NORM record is the only NORM record on its target. The single-record targets are listed in the JSON.

---

## Part 2: status mediation

| # | Decision | Status change | Force change | Quote |
|---|---|---|---|---|
| **E1** | R-81..R-85 adopted by R-86 (2026-08-04) | PREPARED → ADOPTED | not governing → governing | "THESE FIVE ARE THEREFORE PREPARED RULINGS AWAITING ADOPTION, AND “BATCH 7 IS RELEASED” DOES NOT HOLD." → "These become governing rulings effective immediately." / "THIS RULING IS GOVERNING." |
| E2 (broad reading only) | R-60 motion (2026-08-01) | recording note → ruling | cannot open a work package → opens WP-7B-R1 | "A recording note cannot open a work package … so it is filed as a ruling" |

- **independent_n = 1** on the strict reading (a prepared act is adopted). It is **2** on the broad reading, which counts any status of the making act. The R-81 and R-83 annotations repeat E1 and are not separate episodes (SOURCE FACT: identical text, one adoption act).
- Status changed but force did not: R-90 PREPARED → WITHDRAWN ("Nothing depended on this ruling: it was never adopted"), and R-91 PREPARED → HELD ("NOT ADOPTED, AND NOT ELIGIBLE FOR ADOPTION AS ISSUED"). Both were never in force.
- **Anomalies (UNKNOWN):**
  - **R-89** is "PREPARED, NOT ADOPTED", yet the Authority is quoted saying "R-89 already governs authorization". There is no adoption act for it in the corpus.
  - **R-94** is an ARB-Chief disposition with no PREPARED tag, recorded as "Executed at `IMPLEMENTATION_PROGRESS.md:58`". R-86 says any future Chief ruling "remains PREPARED, NOT ADOPTED, until the Decision Authority acts on it".
  - **R-87 and R-88** are referenced as adoption acts, but their text is not provided. They may be further episodes that are NOT OBSERVED.

---

## Part 3: identifiability

| ID | H-D predicts | H-B predicts | Can the corpus tell them apart? | Status |
|---|---|---|---|---|
| P1: polarity change needs an act | Yes, always (act by a capable actor) | Not always: norms may also change in response to work | Every observed change (PC1–PC3) has an act. But the register only records decisions ("The register holds constitutional decisions, not operational acceptance."), so a change without an act could not appear in it. The conditional norms where it could happen (the R-72 proviso, the R-79 gate) are never seen with their condition met. | **NON-IDENTIFIABLE** (selection confound) |
| P2: execution conforms to the norm in force | No prediction | Execution begins only when permitted | One execution record, R11 (08-03 16:10). Before it: R-72 says "no RED begins", and R-77 says "WP-4B REMAINS BLOCKED" (time unknown). But the commit treats R-73..R-75 as already decided, and they may have met R-72's proviso. Their text is absent, so the norm in force at 16:10 is UNKNOWN. | **CONFOUNDED** (n=1). Not a falsification of H-B. |
| P3: work outcomes feed back into norm changes | No prediction required; acts may cite evidence | Acceptance after evidence, remediation after failure | Source text shows act-mediated feedback: R-81 "one throwing process aborts the entire pass" → "REDRIVE FAILURE ISOLATION AUTHORIZED"; R-83's grep; R-58 "merge gate green (R-55)". Both hypotheses allow this. No coded execution record is linked to a later norm change. | **PARTIALLY IDENTIFIED** (H-B's pattern is present, but it does not discriminate) |
| P4: force is status-mediated | Yes | Silent | E1 confirms H-D (n=1). R-89 and R-94 are unresolved anomalies. | **PARTIALLY IDENTIFIED**. Does not discriminate, since H-B is silent. The anomalies are INSUFFICIENT EVIDENCE against H-D. |
| P5: only capable actors change norms | Yes | Silent | Stated and applied: "ADOPTION AUTHORITY IS NOT DELEGATED."; "no self-issued act may adopt". R-89 and R-94 may be exceptions. | **PARTIALLY IDENTIFIED** |
| P6: the record carries or references work | Need not | Expected to | The register excludes operational acceptance, which is kept "in the commission's own artefacts". Yet its rows reference tests and gates, and the corpus includes git and session logs. The answer depends on where the record's boundary is drawn. | **CONFOUNDED** |
| P7: successor authorized only after predecessor accepted | The gate works only through an act | Authorization follows actual acceptance | R-72: "WP-4C is considered only after WP-4B is accepted". R-89 authorizes WP-4C-1 on 08-04, the same day WP-4B batch 7 is released. No WP-4B acceptance is in the corpus. | **INSUFFICIENT EVIDENCE** |
| P8: failure produces remediation | Only via an authorizing act | Remediation expected | R-81: defect → repair authorized. R-91: deviation found → repair "NOT granted here", then the ruling was HELD. Both are act-mediated, and the corpus ends on 08-04. | **NON-IDENTIFIABLE** (post-08-04 NOT OBSERVED) |

No prediction is IDENTIFIED or FALSIFIED.

**Corpus limitation vs domain property.** These are all things the corpus does not contain. None of them says what happens in the domain:
- "No change without an act is recorded" does **not** show such changes do not happen.
- "No execution record for 7A/7B/7C/WP-7B-R1" does **not** show that work did not occur. R-A1: an authorization is not evidence of execution, and the absence of a record is not evidence of non-execution.
- "No WP-4B acceptance" and "no R-91 repair" are both limitations of the corpus.

The domain's own meta-norms show only what the actors endorse. They do not show that the endorsed rules hold. Examples: R-77 "supersession requires an EXPLICIT ACT, NEVER INFERENCE", and R-79's four-way split "PERMISSION · AUTHORIZATION · COMMISSIONING · EXECUTION".

---

## Part 4: minimum discriminating evidence (summary; full fields in JSON)

- **P1.** Needed: a polarity change on one target with a *complete* act log for the interval, and the after-state attested outside the register. **1** act-less change falsifies H-D's "only". H-B's work-trigger claim needs at least 3 independent conditional norms. H-B's distinctive claim is falsified if at least 3 met conditions never change the norm until a separate act.
- **P2.** Needed: the text of R-73..R-76 and the time of day of R-77. That resolves R11 with **1** episode. A pattern needs at least 3 executions on different work packages. H-B is falsified by an execution under a fully determined PROHIBITED norm that is never sanctioned or remediated. P2 cannot falsify H-D, which makes no prediction here.
- **P3.** Needed: at least **2** independent explicit links from an outcome id to a norm-change act that cites it (one acceptance, one remediation), plus control cases without an outcome.
- **P4.** Needed: at least **3** status histories, each from a different adoption act, with force indicators. The first to obtain is R-89's adoption record. H-D is falsified by a ruling that stays PREPARED yet is cited as governing, with an adoption log showing no adoption. H-B has no falsifier here.
- **P5.** Needed: a capability register by norm type. **1** effective act by a non-capable actor (possibly R-94) falsifies H-D.
- **P6.** Needed: a census of a governance record whose boundary is fixed in advance (the full register R-1..R-94).
- **P7.** Needed: the WP-4B acceptance timestamp and the R-87/R-88 text, for at least 2 predecessor–successor pairs.
- **P8.** Needed: records after 08-04 on the R-91 deviation, followed over a fixed window, for at least 2 independent failures.

Independence rule throughout: episodes must come from different targets and different deciding acts. Rulings covered by one adoption, such as R-81..R-85 under R-86, count once.

---

## Ambiguities

1. **R06/R07** are the same span split by operation, so the norm is counted twice. S5 is degenerate.
2. **R14** covers two targets (7B and 7C) and sits in both S1 and S2.
3. **R31**'s UNAUTHORIZED comes from a recording note marked "not part of this ruling", the same category R-60 says cannot open a work package. The consequence column of R-56 supports it.
4. Merging **R10 and R36** into one target is an INFERENCE (whole package vs batch-7 sub-scope).
5. Whether **RED test commits count as "implementation"** under R-72 is an INFERENCE. R-72 itself says "no RED begins", which supports counting them.
6. **Same-day ordering** (R-47..R-65 on 08-01; R-81..R-94 on 08-04; R11 vs R-77 on 08-03) rests on register numbers or is UNKNOWN.
7. **R-89's force** (PREPARED yet "already governs") and **R-94's bindingness** without adoption are both UNKNOWN.
8. **PC4** exists only by inference, since no record codes WP-7B-R1 as UNAUTHORIZED.
9. **E2** counts as status mediation only if "status" includes note vs ruling.
10. **R24, R28 and R35** have no source span (UNKNOWN). The ADR-MP / register-numbering section has no source text.
11. The hypotheses do not say whether H-B's "in response to work" means changes *without* an act or changes *by acts triggered by* work. P1 discriminates only under the first reading.
12. The **R15–R21** norms bind only through R-36, which is outside those spans. They are single-record targets and do not enter any sequence.
