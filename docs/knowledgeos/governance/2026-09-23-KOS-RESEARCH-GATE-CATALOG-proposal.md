# KnowledgeOS Research Gate Catalog — Proposal

| | |
|---|---|
| **Kind** | Candidate gate catalog (content for a future Gate Registry) |
| **Status** | ⚠️ **PROPOSED — authority: generated. Not authoritative without human review.** Nothing implemented, adopted or wired |
| **Date** | 2026-09-23 |
| **Builds on** | `2026-09-23-RESEARCH-GOVERNANCE-GATE-ARCHITECTURE-assessment.md` (the mechanism; this document supplies the rules) |
| **Question answered** | *Given only the KnowledgeOS research methodology, which gates are needed, why does each exist, and at which phase/step does it operate?* |

---

## 0. Scope limitation (mandate)

> **The governance gates are created solely for the KnowledgeOS Theory Reconstruction and Theory Construction research program in `docs/knowledgeos/knowledgeos_theory_chronological_extraction/`. No gate is assumed to apply to any other research program. Generalizing beyond KnowledgeOS needs separate evidence and an explicit future decision.**

*Provenance of this scope:* the user stated it in the session of 2026-09-23. This text is an **AI transcription of that statement, not an attested human act** (assessment §B.2, G-4). It becomes binding once it is recorded through the attested channel. H-1 (who holds research authority) is still open.

**Consequences of the scope:**
- **Mechanism vs. rules.** The runner, verdict, fixture and ledger mechanism is generic plumbing. **The gate rules belong to KnowledgeOS.** No gate is written as a general research principle.
- **Gates are instruments.** A gate that proves poorly defined is a finding about the methodology. It is classed `INSTRUMENT` or `PROTOCOL` (P2P §10.3c) and never `THEORY`.
- **No new rules.** Every gate below is derived from a rule that already exists in `ARCH` v1.1, `P1P` or `P2P`. Where one of the program's own integrity questions has no source rule, it is listed as **NOT-YET-DEFINED** (§11) rather than invented.

Abbreviations: `D/` = the research directory · `ARCH` = `D/prompts/KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md` · `P1P` = `D/prompts/knowledge_os_protocoll.md` · `P2P` = `D/prompts/knowledge_os_step2_theory_construction_protocol.md`.

---

## 1. What the gates protect — the research properties

Each gate protects exactly one primary property. The five properties marked ★ are the program's own freeze conditions (P2P §3A.6: *"unexpected discoveries can be represented · replacement/split/merge work · provenance survives · Step-1 remains immutable · L5 cannot be produced"*).

| Code | Property | Research-integrity question it answers |
|---|---|---|
| `HIST` ★ | Historical integrity | Did we alter the corpus or the Step-1 record? |
| `PROV` ★ | Provenance | Can every derived item be traced back to its evidence? |
| `AUTH` ★ | Authority boundary | Did Phase 2 produce something it may not (L5)? Did a machine proposal become accepted theory? |
| `EVOL` ★ | Evolution integrity | Did replacement, split and merge actually work without loss? |
| `REPR` ★ | Representability | Can an unexpected discovery be represented without changing the core? |
| `READ` | Evidence completeness | Did READ admit and account for all the required evidence? |
| `EPIS` | Epistemic honesty | Did CONSTRUCT keep candidate construction apart from corpus fact and from truth? |
| `NEUT` | Laboratory neutrality | Did IMPLEMENT stay theory-neutral, without adding unjustified assumptions? |
| `FALS` | Falsifiability | Did each experiment have a falsifier registered before it ran? |
| `COMP` | Output completeness | Did anything silently drop out of the primary theory document? |
| `CONT` | Continuation | Is the next step determinable from state rather than from a prompt? |
| `REPRO` | Reproducibility | Can the step be reproduced? |

---

## 2. The rule that separates gates from theory

| | **Gate verdict** | **Research outcome** |
|---|---|---|
| Is about | how the research step was **conducted** | what the **theory** is |
| Values | `PASS · PASS_WITH_OPEN_QUESTIONS · FAIL · INCONCLUSIVE` (P2P §3A.6) | `SURVIVED · REFUTED · INCONCLUSIVE` (P2P §1) |
| Fault class on failure | `PROTOCOL · INSTRUMENT · EVIDENCE/RECORDING · GOVERNANCE/PROCESS` | `THEORY` |

> ⛔ **A correctly conducted experiment whose outcome is `REFUTED` is a gate `PASS`.** A gate `FAIL` means *"the research process violated a required condition"*. It never means *"the KnowledgeOS theory is wrong"*. Every card below therefore records **Epistemic meaning** separately; for almost all gates it is **NONE**.

A gate that checked zero items returns `INCONCLUSIVE`. So does a gate whose preconditions are unmet. `INCONCLUSIVE` blocks progression the way `FAIL` does, and is never counted as a pass.

---

## 3. Card format and classes

**Gate count:** there are **45 catalogued gates**. The IDs run from KOS-G-001 to KOS-G-081 and are sparse by design, because each stage has its own number block:

| IDs | Stage |
|---|---|
| 00x | cross-cutting |
| 01x | Phase 1 |
| 02x | Phase-2 entry |
| 03x | READ |
| 04x | CONSTRUCT |
| 05x | IMPLEMENT |
| 06x | EXPERIMENT/TEST |
| 07x | CONSOLIDATE |
| 08x | freeze/promotion |

So the count is 45, not 81.

**Class:**
- `AUT`: deterministic; becomes a script with a planted-violation fixture.
- `REV`: semantic review by a session that did not execute the step.
- `HUM`: human adjudication.
- `AUT+REV`: the script checks **form**, and review checks **meaning**. The two verdicts are recorded separately, so a form PASS is never read as a meaning PASS.

**Need:**
- `EVIDENCED`: a recorded failure in this program shows the gate is needed.
- `CORE`: protects a ★ freeze property.
- `PRECAUTIONARY`: source rule exists, but no failure has been recorded.

**Tier** (build order, §12):
- `A`: AUT or AUT-form, and EVIDENCED or CORE.
- `B`: REV, or AUT with an unmet precondition.
- `C`: advisory or low value.

**When:**
- `PRE`: the gate must pass before the step starts.
- `POST`: the gate must pass before the next step starts.

---

## 4. S0 — Cross-cutting gates (every execution unit, Phase 1 and Phase 2)

**KOS-G-001 · Corpus immutability**
`POST every unit` · `AUT` · CORE · Tier B
- **Protects:** `HIST` ★
- **Source:** RA-1
- **Reason:** theory reconstruction depends on the historical evidence staying exactly as it was.
- **Detects:** any change to a corpus file listed in `list_of_files_to_read.log`.
- **Fixture:** change one byte in a copied corpus file; the gate must FAIL.
- **PASS:** current hashes equal the approved manifest.
- **FAIL:** unexpected mutation.
- **Epistemic meaning:** NONE.
- **Governance meaning:** research-process violation.
- **Human:** approve the manifest (H-4).
- **Needs:** corpus hash manifest (P1P §45 Gate 3, never executed). Until it exists: INCONCLUSIVE.

**KOS-G-002 · Step-1 record immutability**
`POST every unit` · `AUT` · EVIDENCED + CORE · **Tier A**
- **Protects:** `HIST` ★
- **Source:** RA-2, RA-5, Q9, Q17, I-1, P1P §5A
- **Reason:** Step 1 must stay independently re-runnable (Q17). Evidence that the gate is needed: the RO-0014 migration rewrote 25 FILE-REGISTRY rows in place during Phase-2 prerequisite work, while Q17 was self-reported as passed.
- **Detects:**
  - in a Phase-1 unit, any change to Step-1 files other than appended lines;
  - in a Phase-2 unit, **any** change to Step-1 files at all (I-1: `git diff ../` empty).
- **Fixture:** (a) edit an existing JSONL line; (b) insert a line mid-file; (c) Phase-2 unit appends to `THEORY-OBJECTS.jsonl`. Each must FAIL.
- **PASS:** prefix-identical to the baseline (Phase 1), or zero diff (Phase 2).
- **FAIL:** the offending path and line.
- **Epistemic meaning:** NONE.
- **Governance meaning:** GOVERNANCE/PROCESS violation. The correction path is a Phase-1 correction request, never a Phase-2 repair.
- **Human:** baseline and legacy disposition of RO-0014 (H-4); waivers.
- **Needs:** approved baseline commit.

**KOS-G-003 · Write-set confinement**
`POST every Phase-2 unit` · `AUT` (Q26 copy detection: `AUT+REV`) · EVIDENCED · **Tier A**
- **Protects:** `HIST`, `EPIS`
- **Source:** Q18, P2P §15.0, Q26
- **Reason:** keep Step-2 residue out of the Step-1 record. Evidence: §15.0b recorded L1 evidence fused with L2 hypothesis across same-named files.
- **Detects:**
  - a changed path outside `phase2_extraction/`;
  - an `[E]` record without `"origin":"STEP2"`;
  - (REV) an `[E]` file that restates a Step-1 record instead of referencing it by id.
- **Fixture:** a Phase-2 unit that writes `D/GAPS.jsonl`; an `[E]` record without origin.
- **PASS:** every write is in the write-set and every record is tagged.
- **Epistemic meaning:** NONE.
- **Governance meaning:** process violation.

**KOS-G-004 · Append-only history of the research itself**
`POST every unit` · `AUT` · EVIDENCED · **Tier A**
- **Protects:** `EVOL` ★, `PROV`
- **Source:** Q24, Q32, I-11, I-12
- **Reason:** losing which pass produced what, or an earlier formulation, makes theory evolution unrecoverable. Evidence: IFR-0010's original was unrecoverable.
- **Detects:**
  - a changed or removed prior entry in `THEORY-EVOLUTION` or `FAILED-TESTS`, or in any append-only Phase-2 artifact;
  - an artifact without `iteration_id`;
  - a later iteration overwriting an earlier iteration's file.
- **Fixture:** edit the old text of an evolution entry; delete a failed test.
- **PASS:** earlier content is byte-identical and every artifact carries `iteration_id`.
- **Epistemic meaning:** NONE.
- **Governance meaning:** process violation.

**KOS-G-005 · L5 unreachability**
`POST every Phase-2 unit` · `AUT` · CORE · **Tier A**
- **Protects:** `AUTH` ★
- **Source:** Q1, RA-4, I-4, I-5b, provenance class `GOVERNANCE_ACT` ("not available to Phase 2")
- **Reason:** Phase 2 must not adopt anything; canonicalization is a governance act.
- **Detects:**
  - any record at level L5 or maturity M5 in Phase-2 output;
  - any `asserted_by: GOVERNANCE_ACT` in Phase-2 output.
- **Fixture:** one L5 record; one `GOVERNANCE_ACT` record. Both must FAIL.
- **PASS:** census L5 = 0 and `GOVERNANCE_ACT` = 0.
- **Epistemic meaning:** NONE. A FAIL means the process claimed authority it does not have, not that the claim is false.
- **Governance meaning:** authority violation.

**KOS-G-006 · Provenance chain**
`POST CONSTRUCT, IMPLEMENT, CONSOLIDATE` · `AUT` · CORE · **Tier A**
- **Protects:** `PROV` ★
- **Source:** Q15, I-3, I-10, P2P §14
- **Reason:** untraceable theory cannot be audited.
- **Detects:** a seed item or derived item with an empty early chain, or a chain link that does not resolve.
- **Fixture:** a seed item whose chain points to a missing id.
- **PASS:** every chain is non-empty and resolves to Step-1 or corpus ids.
- **Epistemic meaning:** NONE.
- **Governance meaning:** EVIDENCE/RECORDING defect.

**KOS-G-007 · Referential integrity**
`POST every unit` · `AUT` · EVIDENCED · **Tier A**
- **Protects:** `PROV`
- **Source:** Q5, P-1, P1P §37 checks 1 and 5
- **Reason:** "a broken graph makes every traversal wrong **without error**". Evidence: it caught real dangling references in 2 of 2 batches.
- **Detects:** duplicate ids; unresolved references (checked per item); verified edges pointing to missing files.
- **Fixture:** a duplicate `file_id`; a relation targeting `T-9999`.
- **Epistemic meaning:** NONE.
- **Governance meaning:** EVIDENCE/RECORDING defect.

**KOS-G-008 · Research State updated**
`POST every unit` · `AUT+REV` · PRECAUTIONARY · Tier B
- **Protects:** `CONT`
- **Source:** Q61, RA-11, RA-12, P2P §16B.2, Q40
- **Reason:** continuation must not depend on a human remembering where the work is.
- **Detects:**
  - (AUT) a unit that changes registries without changing the authoritative state, or a missing §16B.2 field;
  - (REV) a next target not justified by the §3C.5 criteria (Q40).
- **Fixture:** a commit that appends registry rows with no state change.
- **Epistemic meaning:** NONE.
- **Governance meaning:** the unit is INCOMPLETE (§16B.4).
- **Human:** which state file is authoritative (H-5).
- **Needs:** one machine-readable state. There are currently three state files.
- **Note:** the F0026–F0030 unit was observed mid-flight today with registries appended and state not yet updated. That is not yet a violation; it is what this gate would check at commit time.

---

## 5. S1 — Phase 1: Historical Reconstruction (per file, per batch)

**KOS-G-010 · Theory Discovery Index**
`POST per file` · `AUT+REV` · EVIDENCED · **Tier A** (form)
- **Protects:** `READ`
- **Source:** P1-Q1, RA-10, ACL-1
- **Reason:** two files were excluded by *kind*, yet held the corpus's own answer to "what is KnowledgeOS for". The error was caught three passes later, and only because a human asked.
- **Detects:**
  - (AUT) a `READ_COMPLETE` file with no index entry;
  - (AUT) `document_kind`, folder or filename used as a field;
  - (AUT) `false` without `why`;
  - (AUT, warn → REV) a `why` whose only content is a kind term such as "governance/process/administrative";
  - (REV) whether `true`/`false` is actually correct.
- **Fixture:** (a) a missing entry; (b) an entry with `document_kind`; (c) `false` with an empty `why`; (d) `false` with `why:"administrative document"`.
- **Epistemic meaning:** NONE for form. A REV FAIL means evidence may have been wrongly withheld from theory, which is an EVIDENCE defect.
- **Open:** the `false` branch has never been exercised (NDF-08).

**KOS-G-011 · Per-file gate sequence**
`POST per file` · `AUT+REV` · PRECAUTIONARY · Tier B
- **Protects:** `READ`
- **Source:** P1P §9A (12 ordered gates; `UNDERSTANDING_UNCERTAIN` is a legal terminal state)
- **Reason:** "a file cannot be marked complete simply because it was read."
- **Detects:**
  - (AUT) `dossier_status: COMPLETE` without all 12 gate records;
  - (AUT) `RECONSTRUCTION_RECORD_VALIDATED` with a required field missing;
  - (REV) a gate recorded but not substantively done.
- **Epistemic meaning:** NONE. `RECONSTRUCTION_RECORD_VALIDATED` explicitly does **not** mean the mathematics has been validated.

**KOS-G-012 · Batch integrity**
`POST per batch, PRE next batch` · `AUT` (checks 1–6, 8–10, 14) + `REV` (checks 7, 11–13) · EVIDENCED · **Tier A** (AUT part)
- **Protects:** `HIST`, `PROV`
- **Source:** P1P §37
- **Reason:** P3A dated 7 of 25 files wrongly by one uniform mechanism, which check 14 now targets. CHECK 14 itself was specified wrongly (AUDIT-05). That is evidence that **this gate's instrument must be fixture-tested**.
- **Detects:** the 14 listed conditions, for example a candidate edge silently turned into a verified edge (9), Track-B entering Track-A (10), or a date taken from `A_CITED_ARTIFACT` (14).
- **Fixture:** one planted violation per AUT check, plus the historical AUDIT-05 state.
- **Epistemic meaning:** NONE.
- **Governance meaning:** EVIDENCE/RECORDING, or PROTOCOL if the check itself is wrong.

**KOS-G-013 · Absence discipline**
`POST per file/batch` · `AUT+REV` · PRECAUTIONARY · Tier B
- **Protects:** `EPIS`
- **Source:** I-16, P1P §37 checks 12–13
- **Reason:** "not found" must not become "absent", and "not connected" must not become "independent".
- **Detects:**
  - (AUT) `ABSENT_BY_CONTENT` without a search reference;
  - (REV) whether the search was adequate.
- **Epistemic meaning:** NONE.

**KOS-G-014 · Freeze-before-scale**
`PRE each new batch` · `HUM` (inputs `AUT`) · EVIDENCED · Tier B
- **Protects:** `REPRO`, `PROV`
- **Source:** P1P §45 Gates 0–3 (pilot → Reference Architecture → schemas → state/hash/registry import)
- **Reason:** scaling on unfrozen schemas makes P-2 uncheckable. Evidence: Gates 1–3 were never executed (`RECONSTRUCTION-STATE.json` l.93), and THEORY-OBJECTS has 28 keysets across 30 rows.
- **Detects:** batch *n+1* starting while `gate_0..3` are not satisfied.
- **Today:** this gate would **FAIL**. Whether to halt, or to accept the gap as a recorded legacy finding, is **H-4**, not something the gate decides.
- **Epistemic meaning:** NONE.

---

## 6. S2 — Phase-2 entry

**KOS-G-020 · Class-A prerequisites**
`PRE Phase 2 / each iteration` · `AUT` · EVIDENCED · **Tier A**
- **Protects:** `PROV`
- **Source:** P2P §2.2 (P-1 no dangling refs · P-2 one registry schema · P-3 collision map present); "a gate may never require the output of the stage it guards"
- **Reason:** "an unguarded join **fuses distinct objects**". Evidence: four meanings of `D-`, and RO-0014.
- **Detects:** P-1 (as in G-007); P-2 (every row validates against *one* schema); P-3 (collision map file present and covering all prefixes).
- **Meta-check:** no Class-B item (a Step-2 discovery) appears as an entry gate.
- **Today:** P-2 is **INCONCLUSIVE**. No frozen schema exists, and RO-0014 is still `OPEN` in RESEARCH-OBLIGATIONS.
- **Epistemic meaning:** NONE.

---

## 7. S3 — READ (P2P phases 0, A, B, C)

**KOS-G-030 · Admission completeness**
`POST READ` · `AUT+REV` · EVIDENCED · **Tier A** (form)
- **Protects:** `READ`
- **Source:** Q19, Q45, Q46
- **Reason:** the v2 dry run parked 11 objects and dropped 10 architecture objects. "Ungrouped ≠ excluded."
- **Detects:**
  - (AUT) the set of Step-1 node ids (T-, HA-, other types) ≠ the set of admitted ids;
  - (AUT) a file in the audited range with no contribution disposition;
  - (REV) a contribution marked represented, excluded-with-reason or unresolved incorrectly.
- **Fixture:** drop one HA- node from admission.
- **Epistemic meaning:** NONE for form. A REV FAIL is an EVIDENCE defect: the theory may be ignoring evidence.

**KOS-G-031 · No exclusion by document kind**
`POST READ, CONSTRUCT` · `REV` (AUT keyword flag, warn only) · EVIDENCED · Tier B
- **Protects:** `READ`
- **Source:** Q54, ACL-1, RA-6
- **Reason:** the violation that produced ACL-1 and P1-Q1 (F0022 and F0023).
- **Detects:** a contribution excluded from theory on the grounds of kind, type or folder.
- **Epistemic meaning:** NONE (an EVIDENCE defect).

**KOS-G-032 · History uncontaminated**
`POST READ; POST every later phase` · `AUT` (Q25) + `REV` (Q16, Q27) · PRECAUTIONARY · **Tier A** (Q25 part)
- **Protects:** `HIST`, `EPIS`
- **Source:** Q16, Q25, Q27, P2P §4.4
- **Reason:** history must not be rewritten to match the theory.
- **Detects:**
  - (AUT) `HISTORICAL-STORY.md` changed after the READ unit's commit;
  - (AUT) a story claim missing its file reference or epistemic status;
  - (REV) an L2 interpretation inside the history.
- **Fixture:** edit the story in a CONSTRUCT commit.
- **Epistemic meaning:** NONE.

**KOS-G-033 · Window carried**
`POST READ, CONSTRUCT` · `AUT+REV` · EVIDENCED · Tier A (form)
- **Protects:** `EPIS`
- **Source:** Q55, ACL-4
- **Reason:** "propagated to zero files" was true of 25 files and false of the corpus.
- **Detects:**
  - (AUT) a consumed Phase-1 fact with no window field;
  - (REV) a claim that generalizes beyond its window.
- **Epistemic meaning:** NONE (overclaim is a calibration defect, not a falsification).

**KOS-G-034 · Expectation declared before re-READ**
`PRE each re-READ` · `AUT` · PRECAUTIONARY (positive precedent: P3A predictions 4/4) · Tier B
- **Protects:** `FALS`
- **Source:** Q34, P2P §4.3b
- **Reason:** confirmation bias from iteration 2 onward.
- **Detects:** a re-READ result without an `expected_finding` that was **committed in an earlier commit**. Commit order is used rather than self-written timestamps, because the agent can write a timestamp but cannot back-date a commit that CI has seen.
- **Epistemic meaning:** NONE.

**KOS-G-035 · Out-of-core evidence recorded**
`POST READ, CONSTRUCT` · `REV` · PRECAUTIONARY · Tier C
- **Protects:** `PROV`, `EPIS`
- **Source:** Q37, Q39, P2P §3C.2–3C.4
- **Detects:** a consultation outside the core that is not in `EVIDENCE-EXPANSION`; repeated out-of-boundary answers not raised as a scope finding.
- **Limit:** an *unrecorded* consultation is not deterministically detectable, so this gate is review only.

---

## 8. S4 — CONSTRUCT (P2P phases D, B2, E, F, G)

**KOS-G-040 · Origin and level labelled**
`POST CONSTRUCT, CONSOLIDATE` · `AUT+REV` · CORE-adjacent · **Tier A** (form)
- **Protects:** `EPIS` (*constructed ≠ corpus fact ≠ true*)
- **Source:** Q51, Q43, `ARCH` 2B labels `[C]/[S]/[E]`
- **Reason:** "an expert construction reading as a corpus fact" / "collapsing corpus fact into validated result".
- **Detects:**
  - (AUT) a statement without an origin label, or without a level A–E, or at level `F`;
  - (REV) a label that is wrong.
- **Fixture:** an unlabelled statement; a level-`F` statement.
- **Today:** two relation records (T-0006, T-0009) carry origin `—`. Admissibility is undefined (NDF-07), so they are INCONCLUSIVE, not PASS.
- **Epistemic meaning:** NONE.

**KOS-G-041 · Expert construction justified**
`POST CONSTRUCT` · `AUT+REV` · EVIDENCED · Tier A (form)
- **Protects:** `EPIS`
- **Source:** Q52, Q53, Q10, Q38, ACL-3
- **Reason:** proposing before searching. Evidence: Q10 was violated once, Q53 "paid twice", and the `bar` error.
- **Detects:**
  - (AUT) an `[E]` addition missing necessity, basis, reasoning, alternatives or falsifier;
  - (AUT) no search-record reference before the construction's commit;
  - (REV) a search that was inadequate.
- **Epistemic meaning:** NONE.

**KOS-G-042 · Term collision**
`POST CONSTRUCT` · `REV` (AUT: exact-string clash with a corpus term, flagged for review) · EVIDENCED · Tier B
- **Protects:** `EPIS`
- **Source:** Q56, RA-9, Q8, `ARCH` §4.2c
- **Reason:** a claim that is true in one reading and false in the other. Evidence: violated once, corrected by renaming.
- **Detects:** a new term that reuses a corpus term with a different meaning, without renaming, qualification or a recorded relationship; an identifier join not checked against the collision map.
- **Epistemic meaning:** NONE.

**KOS-G-043 · Structure search and staging cap**
`POST CONSTRUCT phase F` · `AUT` (Q14) + `REV` (Q20) · EVIDENCED · Tier A (Q14)
- **Protects:** `EPIS`
- **Source:** Q14, Q20
- **Reason:** "elegance mistaken for rigor"; theory bias toward the pre-listed S-1..S-5. Evidence: v2 failed Q20.
- **Detects:**
  - (AUT) a structure staged above F1 while a linked defining condition is open;
  - (REV) the search space was only the pre-listed structures.
- **Epistemic meaning:** NONE.

**KOS-G-044 · Competition and identity discipline**
`POST CONSTRUCT phase E` · `AUT+REV` · EVIDENCED · Tier B
- **Protects:** `EVOL` ★, `EPIS`
- **Source:** Q4, Q7, Q23, I-6, P1P §19A (six identity criteria)
- **Reason:** resolution by preference (C-0007); false identity (TH-0003 was kept UNCERTAIN).
- **Detects:**
  - (AUT) a closed competition without a discriminator;
  - (AUT) a merge without all six §19A criteria evidenced;
  - (AUT) an abandoned formulation without `REJECTED_INTERPRETATION`;
  - (REV) whether the discriminator and criteria are sound.
- **Epistemic meaning:** NONE.

**KOS-G-045 · Falsifiability typed, not manufactured**
`POST CONSTRUCT phase G` · `AUT+REV` · EVIDENCED · Tier A (form)
- **Protects:** `FALS`
- **Source:** Q2
- **Reason:** v2 forced fabricated falsifiers.
- **Detects:**
  - (AUT) a seed item without a typed `falsifiable_as`; `FALSIFIABILITY_NOT_YET_SPECIFIED` is **counted, not failed**;
  - (REV) a falsifier that is manufactured or weak.
- **Epistemic meaning:** NONE.

**KOS-G-046 · Gaps dispositioned, not papered over**
`POST every checkpoint` · `AUT+REV` · EVIDENCED · Tier A (form)
- **Protects:** `EPIS`, `COMP`
- **Source:** Q57, Q44
- **Reason:** gaps were accumulating silently, and the protocol records Q57 as violated.
- **Detects:**
  - (AUT) a gap whose disposition is not one of `FILLED [E] · REFUSED · POINTER · WAITING · BLOCKED`, including bare "unresolved";
  - (REV) a gap filled for completeness.
- **Epistemic meaning:** NONE.

**KOS-G-047 · Claim strength never exceeds evidence**
`POST CONSTRUCT, CONSOLIDATE` · `REV` (+AUT for Q6 presence) · EVIDENCED · Tier B
- **Protects:** `EPIS`
- **Source:** Q3, Q6, Q22, Q50
- **Reason:** C-0007 recorded a false claim in 7 files outranking its single true refutation.
- **Detects:**
  - (AUT) a provisional item without `provisional_reason`;
  - (REV) overclaiming, maturity raised by repetition or volume, or an experiment result promoted to validated.
- **Epistemic meaning:** a FAIL lowers the **stated strength** of a claim. It does not falsify the claim.

---

## 9. S5 — IMPLEMENT / Theory Laboratory (P2P phase L)

**KOS-G-050 · Laboratory neutrality**
`POST IMPLEMENT` · `AUT` (Q31) + `REV` (Q28, Q33) · PRECAUTIONARY · Tier A (Q31)
- **Protects:** `NEUT`
- **Source:** Q28, Q31, Q33, P2P §3B.9 ("the software supports the research, it must never silently decide it")
- **Reason:** the first program must not silently become the theory.
- **Detects:**
  - (AUT) an adapter type imported by the domain core, or a theory-object id hard-coded in lab source (heuristic, sent for review);
  - (REV) a representation taken from a pre-specified kernel instead of derived from CONSTRUCT.
- **Fixture:** a domain file that imports a persistence or LLM adapter.
- **Epistemic meaning:** NONE. A neutrality breach invalidates the lab's **outputs** as evidence (INSTRUMENT); it says nothing about the theory.

**KOS-G-051 · No silent completion**
`POST IMPLEMENT` · `REV` · EVIDENCED · Tier B
- **Protects:** `NEUT`
- **Source:** LAB-READINESS governing rule, LAB-READINESS condition 3, Q30
- **Reason:** the invented `bar` threshold would have implemented a quantitative rule that ES-003.2 forbids.
- **Detects:**
  - an implementation value, threshold or term with no corpus basis and no `OPEN_TERM` record;
  - a representational deficiency worked around in the schema instead of raised against the protocol.
- **Epistemic meaning:** NONE. Lab results that depend on the invention are INSTRUMENT-invalid.

**KOS-G-052 · A machine proposal gains no authority**
`POST every Phase-2 unit` · `AUT` · CORE · **Tier A**
- **Protects:** `AUTH` ★
- **Source:** I-14, P2P §3B.6 provenance classes (`asserted_by` mandatory and non-defaultable), I-5b
- **Reason:** answers the question *"did we accidentally turn a machine proposal into accepted theory?"*
- **Detects:**
  - a record with no `asserted_by`;
  - a `MACHINE_PROPOSAL` at L3 or above without a referencing `HUMAN_ASSERTION` or `VALIDATED_FINDING`;
  - a promotion without an `AuthorityAct`.
- **Fixture:** a MACHINE_PROPOSAL at L3 with no reference.
- **Epistemic meaning:** NONE.
- **Governance meaning:** authority violation.
- **Human:** the referenced `HUMAN_ASSERTION` must itself be attested (assessment G-4). Otherwise this gate can be satisfied by an AI-written "human" record, and its PASS is **scoped as "form only, attestation unverified"** until then.

**KOS-G-053 · Lab readiness**
`PRE each experiment` · `REV` (condition 9: `HUM`/`REV`) · EVIDENCED · Tier B
- **Protects:** `NEUT`, `REPRO`
- **Source:** LAB-READINESS conditions 1–9
- **Reason:** 3 of 6 structures were LAB_READY, yet none of those three tests the promotion mechanism, which is the core of the theory.
- **Detects:** an experiment started on a candidate that is not LAB_READY, or a missing condition that is not named.
- **Epistemic meaning:** NONE.

---

## 10. S6 — EXPERIMENT / TEST (P2P phases H0, H; laboratory experiments)

**KOS-G-060 · Falsifier pre-registered**
`PRE execution / POST result` · `AUT` · EVIDENCED · **Tier A**
- **Protects:** `FALS`
- **Source:** Q35, Q47, Q13
- **Reason:** a test that cannot fail. Evidence: the `bar` failure, a jump from observation to theory.
- **Detects:**
  - an experiment whose `falsification_condition` was not committed **before** the result commit;
  - missing separated stages;
  - a finding with an empty or untyped `test_required`.
- **Fixture:** falsifier and result added in the same commit.
- **Epistemic meaning:** NONE. A FAIL means the experiment's outcome cannot be used as evidence either way. It does not mean the hypothesis failed.

**KOS-G-061 · Adversarial framing first; verification never edits**
`PRE phase H / POST phase H` · `AUT` · PRECAUTIONARY (Q12 positive: IFR-0012) · Tier A
- **Protects:** `EPIS`, `EVOL`
- **Source:** Q21, Q12
- **Reason:** verification degenerating into defence of the seed, or silent repair during verification.
- **Detects:** an H0 record not committed before H starts; a seed item whose content changes during H (only demotion or cap fields, with a finding id, are allowed).
- **Epistemic meaning:** NONE.

**KOS-G-062 · Laboratory tests executed**
`POST IMPLEMENT/TEST; input to G-080` · `AUT` (existence and outcome) + engine run · CORE · **Tier A**
- **Protects:** `EVOL` ★, `REPR` ★, `AUTH` ★
- **Source:** P2P §3B.8: `EMERGENT_DISCOVERY_TEST`, `THEORY_REPLACEMENT_TEST`, `THEORY_SPLIT_TEST`, `THEORY_MERGE_TEST`, `THEORY_NON_DETERMINATION_TEST`
- **Reason:** answers the question *"did replacement, split and merge actually get tested?"* These are freeze properties.
- **Detects:** a missing test record, a test not re-run at the current commit, or an outcome that is not reproducible by re-running.
- **Epistemic meaning:** the **test outcome** is a laboratory finding about the laboratory's representational capacity (INSTRUMENT). It is not evidence about the KnowledgeOS theory (§10.3c).

**KOS-G-063 · Every divergence classified**
`POST IMPLEMENT (2A↔2B comparison)` · `AUT+REV` · PRECAUTIONARY · Tier A (form)
- **Protects:** `EPIS`
- **Source:** Q36, Q29, P2P §10.3c
- **Reason:** "an instrument failure read as evidence about the theory"; "a scoring function replacing judgement".
- **Detects:**
  - (AUT) a divergence without a class from `THEORY · PROTOCOL · INSTRUMENT · RESEARCH_DISCOVERY`, or an averaged or scored divergence;
  - (REV) whether the class is correct.
- **Epistemic meaning:** only a REV-confirmed `THEORY` classification carries theory meaning. The gate verdict itself does not.

**KOS-G-064 · Reviewer independence**
`PRE any maturity ≥ M2 claim` · `HUM` + `AUT` record · PRECAUTIONARY · Tier B
- **Protects:** `EPIS`
- **Source:** P2P §11.0 (M2 needs a genuinely independent reader; a same-session review is only a SECONDARY_REVIEW)
- **Detects:** (AUT) an M2 claim whose reviewer session id equals the executor session id, or with no reviewer record.
- **Human:** whether a separate same-model session counts as independent (H-8).
- **Epistemic meaning:** NONE.

**KOS-G-065 · Counts qualified**
`POST any statistical statement` · `AUT+REV` · PRECAUTIONARY · Tier C
- **Protects:** `EPIS`
- **Source:** Q49, P2P §13B
- **Detects:** a count missing population, frame, unit, dependence, generalization or limitation.
- **Epistemic meaning:** NONE.

---

## 11. S7 — CONSOLIDATE (P2P phases M, K) and S8 — Freeze / promotion

**KOS-G-070 · Candidate Theory current and complete**
`POST CONSOLIDATE (checkpoint)` · `AUT` · EVIDENCED ×2 · **Tier A**
- **Protects:** `COMP`
- **Source:** Q41, Q60, P2P §5A.7
- **Reason:** iteration 1 failed Q41. Q60 was violated: 3 of 11 seed items were absent "and no gate saw it".
- **Detects:**
  - a Candidate Theory not updated in the checkpoint commit;
  - a seed id not linked in the theory document and not `DELIBERATELY_OMITTED` with a reason.
- **Fixture:** remove SI-0003 from the theory document. This is the historical state and must FAIL.
- **Epistemic meaning:** NONE.

**KOS-G-071 · Relations accounted**
`POST CONSOLIDATE` · `AUT+REV` · EVIDENCED · **Tier A** (form)
- **Protects:** `COMP`, `PROV`
- **Source:** Q59
- **Reason:** "a theory of disconnected fragments". Evidence: the first B-2 pass produced 2 NONE_FOUND entries without a reason.
- **Detects:**
  - (AUT) a theory object with neither relations nor `NONE_FOUND`;
  - (AUT) a `NONE_FOUND` without a reason, checked **per value** (the per-value vs. per-object rule is NDF-06);
  - (REV) whether the relations are correct.
- **Scope statement required:** a PASS means **"relationships accounted for"**, never "all relationships in the corpus discovered". The denominator is unknown (C3).
- **Epistemic meaning:** NONE.

**KOS-G-072 · Coverage stated**
`POST CONSOLIDATE` · `AUT` · EVIDENCED · Tier A
- **Protects:** `EPIS`
- **Source:** Q58
- **Reason:** a 356-line theory built on 0.8 % of the corpus read as if it were the whole theory.
- **Detects:** a theory document with no files-read / corpus-size ratio, or a ratio that disagrees with the registry count.
- **Epistemic meaning:** NONE.

**KOS-G-073 · Surprise report**
`POST CONSOLIDATE` · `AUT` (presence) + `REV` · PRECAUTIONARY · Tier B
- **Protects:** `REPR`
- **Source:** P2P §16A
- **Detects:** the report is missing or lacks one of the five categories.
- **Special verdict:** **zero surprises → `INCONCLUSIVE` (GOVERNANCE/PROCESS suspicion)**, not FAIL and not PASS. The protocol says that outcome warrants "suspicion of the process, not of the corpus."
- **Epistemic meaning:** NONE.

**KOS-G-074 · Theory readable without JSON**
`POST CONSOLIDATE` · `REV` · PRECAUTIONARY · Tier C
- **Protects:** —
- **Source:** Q42
- **Note:** advisory. It is a legitimate quality check but does not protect research integrity, so it **should not block**.

**KOS-G-080 · Freeze condition**
`at a proposed freeze` · **`HUM`**, with AUT inputs · CORE · Tier B
- **Protects:** all ★ properties
- **Source:** P2P §3A.6 ("do not freeze because the software runs"; "one agreeing run is not validation")
- **Inputs:** G-002, G-005, G-006, G-052, G-062 verdicts, at least two runs, and every open question recorded.
- **Gate verdict:** `PASS_WITH_OPEN_QUESTIONS` is the normal best case.
- **Decision:** always human (H-7). **The gate never freezes anything; it only certifies whether the inputs are complete.**

**KOS-G-081 · Canonicalization**
`at any L5 / M5 proposal` · **`HUM`** · CORE · Tier B
- **Protects:** `AUTH` ★
- **Source:** RA-4, I-5b, Q1, `ARCH` layer 5
- **Detects:** (AUT) an L5 or M5 record anywhere without a reference to an **attested** human act.
- **Decision:** human only. Phase 2 can never pass this gate for itself.

---

## 12. Answers to the program's own integrity questions

| Question | Gate(s) | Status |
|---|---|---|
| Did we alter the historical corpus? | G-001, G-002, G-032 | defined; G-001 needs the manifest |
| Did we preserve provenance? | G-006, G-007, G-004 | defined |
| Did READ actually read the required evidence? | G-010, G-011, G-030, G-031 | form: AUT · adequacy: REV |
| Did CONSTRUCT distinguish candidate construction from truth? | G-040, G-041, G-047, G-043 | defined |
| Did IMPLEMENT introduce unjustified assumptions? | G-051, G-053 | REV only; no deterministic test exists |
| Did the laboratory remain theory-neutral? | G-050 | partly AUT |
| Did an experiment have a pre-registered falsifier? | G-060, G-034 | AUT by commit order |
| Did replacement, split and merge actually get tested? | G-062 | AUT + engine |
| Did we turn a machine proposal into accepted theory? | G-052, G-081 | AUT, but **limited by unattested human acts** |
| Did Phase 2 produce L5? | G-005 | AUT |
| Can we reproduce the research step? | G-062 (lab tests only) | ⚠️ **NOT-YET-DEFINED** for research steps (NDF-R1) |
| Did the artifact pass the required conformance checks? | the Tier-A set | AUT |

### NOT-YET-DEFINED (no source rule exists; nothing invented)

| ID | Missing | Why it cannot be a gate yet |
|---|---|---|
| **NDF-R1** | Reproducibility of a *research* step. §3A.6 says "a second run reproduces the methodology" and LAB-READINESS condition 9 says "an independent implementer could reproduce", but no procedure defines what a second run is, who runs it, or what counts as agreement | needs a protocol rule (a §9 proposal) |
| NDF-R2 | Schema set (P1P §45 Gate 2) | G-020 P-2 and G-011 field checks depend on it |
| NDF-R3 | Corpus and baseline manifest (Gate 3) | G-001 and G-002 depend on it |
| NDF-R4 | Correction-request lifecycle | G-002's correction path has no record format |
| NDF-R5 | NONE_FOUND reason: per value or per object | G-071 |
| NDF-R6 | Whether origin `—` is admissible | G-040 |
| NDF-R7 | Attested human-act channel | G-052, G-064, G-080 and G-081 can only certify form until it exists |
| NDF-R8 | The step-type → gate map above needs human confirmation | this catalog is a proposal |

### Rules deliberately **not** made gates

- **Class-B items (Step-2 discoveries).** P2P §2.2 says they are *"never gates — these are what Step 2 exists to find"*.
- **§3C.5 next-target ranking.** It is qualitative by design, and ES-003.2 forbids numeric scores. It stays inside G-008 as a REV check on whether a justification is present, never as a computed score.
- **Q11** (all eight lenses × at least two modes). It is a verification-coverage rule and is folded into G-061/G-064 review scope, not made a separate gate.

---

## 13. What is actually needed first — minimal Tier-A registry

These gates are deterministic, protect a ★ property or answer a recorded failure, and need **no unmet precondition** except where noted. They are the candidates for Gate Registry v1 (assessment P1):

| Gate | Protects | Why first |
|---|---|---|
| **G-002** Step-1 immutability | HIST ★ | recorded violation (RO-0014); needs only a baseline commit (H-4) |
| **G-003** Write-set | HIST | cheapest possible check |
| **G-004** Append-only history | EVOL ★ | recorded loss (IFR-0010) |
| **G-005** L5 census | AUTH ★ | freeze property |
| **G-007** Referential integrity | PROV | caught real defects in 2/2 batches |
| **G-010** P1-Q1 (form) | READ | origin of ACL-1 |
| **G-030** Admission (form) | READ | v2 dropped 21 objects |
| **G-052** Machine proposal ≠ authority (form) | AUTH ★ | answers the most dangerous question |
| **G-060** Falsifier pre-registered | FALS | the `bar` failure |
| **G-070** Candidate Theory complete | COMP | 3 of 11 items dropped with every gate passing |
| **G-071** Q59 (form, scoped) | COMP | recorded self-graded failure |

That is **11 of the 45 catalogued gates**. Everything else waits until the Tier-A set has run for at least one execution unit and has been evaluated. Consistent with the scope limitation, the gate system is **built from evidence of need, not from completeness of the rulebook**.

---

## 14. Placement question (for human decision)

Gates belong to this program, but must **not** be writable by the executing step (assessment §D.4). Two options:

- **(a)** `D/governance/` holds the registry, fixtures and ledger. The scope boundary is physical, and CODEOWNERS applies to that path.
- **(b)** `docs/knowledgeos/governance/research-gates/`, which keeps judge and judged in separate trees.

**Recommendation: (a).** The scope limitation says these gates exist only for this program, and co-location makes that visible. Q18 is unaffected, because Q18 constrains Phase-2 *writes*, and the registry is written only through human-approved changes. The runner code itself lives in `scripts/lib/EngineeringKnowledge/` per the existing convention.

---

*Traceability:*
- Every gate cites its source rule in `ARCH` v1.1, `P1P` or `P2P` (§2.2, §3A.6, §3B.6, §3B.8, §3B.9, §9A, §16, §16A, §16B, §19A, §37, §45) or `phase2_extraction/LAB-READINESS-GATE.md`.
- Evidence-of-need citations are taken from the protocols' own "Observed?" column and from the assessment.
- No research artifact was modified.
- The scope statement is transcribed and unattested (§0).
