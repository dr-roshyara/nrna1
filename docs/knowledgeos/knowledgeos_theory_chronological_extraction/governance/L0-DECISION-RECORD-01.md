# L0-DECISION-RECORD-01 — L0 decisions for the F0031–F0040 incident

| | |
|---|---|
| **Kind** | record of human (L0) decisions, and one decision request that is still open |
| **Recorded by** | governance / control-plane session, which transcribes the human's act. ⚠️ **Attestation: UNVERIFIED.** This is an AI transcription of statements the human made in this session (no attested channel yet; see the assessment G-4 / NR-1). The human may confirm it by committing or signing it |
| **Date · HEAD** | 2026-09-23 · `f3922660e` |
| **Identifier** | `L0-DEC-nn` (checked unused on all 7 local branches before first use) |

---

## L0-DEC-01 — Governance identifier rename accepted · **DECIDED**

**The human's act** (governance session, 2026-09-23): *"Yes, I directed it — keep"*, confirmed in writing as *"the later L0 instruction explicitly directing `H-C1…H-C7 → RC-H-01…RC-H-07` was my L0 instruction and supersedes my earlier instruction not to rename."*

| Item | Decision |
|---|---|
| Rename | **kept; not reverted** |
| Authoritative identifier for the correction control | **`RC-H-04`**, defined once in `architecture/research-control-architecture.md` §13 (L389). Verified unique on all 7 local branches |
| Old → new mapping (provenance) | `H-C1→RC-H-01` · `H-C2→RC-H-02` · `H-C3→RC-H-03` · **`H-C4→RC-H-04`** · `H-C5→RC-H-05` · `H-C6→RC-H-06` · `H-C7→RC-H-07`. The meanings are unchanged (`IDENTIFIER-RENAME-01.md`, mapping table). Executed in `e8ffaf6f5` |
| Records keeping the old token | `COMMIT-BOUNDARY-FINDING-01.md` and `.claude/sessions/2026-09-23.md`, as historical witnesses of the collision (the research session's judgment, stated so it can be overruled). **Not overruled here**; see Q-R below |
| False statements the rename introduced into `GIA-INCIDENT-CLOSURE-01.md` | corrected by **append-only Erratum A** in that file |
| Unrelated `H-C4` on branch `kos-v11-ddd-refinement` | **untouched** |
| **B-17** | the identifier ambiguity is **resolved by L0-DEC-01**. ⛔ The research session's backlog entry *"B-17 CLOSED"* is **not** the L0 closure decision; **this record is** |
| **C-2** (GIA-INCIDENT-CLOSURE-01 §4) | **SATISFIED by L0-DEC-01** |
| ⛔ **What L0-DEC-01 does NOT do** | it does not authorize C-4, C-5 or any correction; it does not accept C-1 or C-3; it rewrites no history; it changes no status of ARCH, P1P, P2P or the RCA |
| B-16 (third occurrence: `e8ffaf6f5` swept this session's two uncommitted files) | stays an **open incident finding** |

**Kept distinct:**
- the governance namespace decision (L0-DEC-01);
- the provenance incident (B-16; C-1);
- the correction authorization (C-4);
- correction execution (C-5);
- independent review (C-6);
- final closure (C-7).

## Still open for L0

| Item | Decision required |
|---|---|
| **C-1** | Accept the attribution table (`GIA-INCIDENT-CLOSURE-01.md` §3 and Erratum A, **8 files**) as the authoritative authorship record, with no history rewrite |
| **C-3** | Acknowledge the self-correction: the governance audit's "H-Cn no collision" was scoped to this branch only |
| **C-4** | Authorize `RC-H-04`, with a scope and remedy (request below) |
| Q-R (rename scope) | from `IDENTIFIER-RENAME-01.md`: should the two historical records keep the old `H-C` tokens (the research session's reading) or also be renamed? *No default is inferred here.* The governance session notes that keeping them is consistent with RCI-015 |

---

## RC-H-04 AUTHORIZATION REQUEST (C-4) · **PENDING — NOT AUTHORIZED**

⛔ **Nothing below is authorized until the human records a decision in the "L0 decision" column.** C-5 must not start before then.

**Definition being authorized:** `RC-H-04`, RCA §13: *"Schedule the F0031–F0040 correction unit (re-binding to canonical IDs; RA-5), and decide whether research continues before it. Under RCI-015 the correction must not edit theory v0.9 or the existing registry rows. It appends correction records and yields a new version (v0.9-AUDIT → v0.10), with each affected finding classed as retained · source-identity-affected · requires re-read · unsupported · unresolved."*

### Decisions embedded in the request

| # | Question | Options | Evidence | Governance note, not a choice | L0 decision |
|---|---|---|---|---|---|
| **C4-a** | **Remedy** | **B**: correction overlay only (append ID-correction records; nothing edited) · **C**: freeze and forward (v0.9 preserved; v0.9-AUDIT classifies findings; v0.10 cites canonical IDs; contains B). ⛔ A (renumber in place) is excluded by RCI-015/RA-2 | Increment 0 §5 | only C schedules reading the unread canonical files, which the scope below requires either way | ☐ B · ☐ C |
| **C4-b** | **Ordering relative to the governance fixes**: run the correction **after** extended 1a and 1b are accepted (re-admission through a reviewed admission path), or **before** | after / before (with the risk recorded: reading through an unreviewed, research-produced admission path, GIA-9) | transition assessment §15; closure record §4 | "before" means `evidence/admit.py` would be used unreviewed, or reading would happen without admission control | ☐ after 1a+1b · ☐ before (risk accepted) |
| **C4-c** | **Research continuation before the correction** (part of the RC-H-04 definition itself) | research stays stopped (no B-2, no F0041+) until C-7 · or named exceptions | RCA §13; Increment 0 §4 (every new correct citation in F0031–F0040 worsens the collision) | none | ☐ stopped until C-7 · ☐ exceptions: ____ |
| ⚠️ **C4-?** | **"The two unresolved scope questions identified by the research session"** | ⛔ **not located in any committed artifact.** The candidates found are C4-b, C4-c and Q-R | — | **not resolved by inference**; the human is asked to name them | ____ |

### Scope the authorization would cover (C-5 execution by the research session)

| In scope | Out of scope (forbidden) |
|---|---|
| append-only ID-correction records for research IDs F0031–F0040 → actual path → canonical ID (Increment 0 §1) | editing theory v0.9 in place; editing existing registry rows; renumbering (option A) |
| (if C) v0.9-AUDIT: classify every affected finding (105 refs, 10 artifacts; Increment 0 §3) as retained / source-identity-affected / requires re-read / unsupported / unresolved | changing `list_of_files_to_read.log` or any corpus file |
| re-admission and complete reading of canonical **F0032, F0035, F0036, F0040** and canonical **F0045** from the corpus | inferring F0045's content from the research's "F0040" extraction |
| missing-source additions to C-0010 (F0003 H-7, F0035, F0039); epistemic-status review of T-0022, DI-0014, SI-0005, T-0044, SI-0043 (transition assessment §8) | substantive H-3 ruling (research may propose; the ARB rules on RO-2) |
| the correction evidence package: what was read, the receipts/hashes, every correction record, and every disposition | activating or editing any gate; committing governance artifacts (GIA-8); B-2; F0041+ |
| staging by explicit **file** path, and checking tracked state before each commit (B-16) | `git add -A`; staging directories; any history rewrite |

**After C-5:** C-6, an independent review by a session other than research, then **C-7, L0 acceptance and closure**. The research session does **not** declare closure.

---

*Traceability:*
- The human's statements in the governance session (2026-09-23).
- `IDENTIFIER-RENAME-01.md` and `e8ffaf6f5`.
- `GIA-INCIDENT-CLOSURE-01.md` §§2–4 and Erratum A.
- RCA §13 (L389); Increment 0 §§1, 3, 4, 5; transition assessment §§8, 15.
- Nothing authorized beyond L0-DEC-01.

---

## L0-DEC-02 — C-1 accepted · **DECIDED** (2026-09-23, appended)

**The human's act** (governance session): *"c1 -> yes"*. ⚠️ Attestation: UNVERIFIED (AI transcription, as for L0-DEC-01).

| Item | Decision |
|---|---|
| **C-1** | **ACCEPTED.** The attribution table in `GIA-INCIDENT-CLOSURE-01.md` §3, **as extended by Erratum A (8 files)**, is the authoritative authorship record for those files. For these paths, **authorship is taken from the table, not from `git log`** |
| Git history | **not rewritten** (confirmed by this decision) |
| B-16 | the distortion is now **corrected by record**. The incident finding (a boundary crossed three times) stays **OPEN**; prevention stays with GIA-8 |
| ⛔ Not decided here | C-3 · C-4 (C4-a/b/c) · Q-R · the research session's two scope questions |

**Status after L0-DEC-02:** **C-1 SATISFIED** · **C-2 SATISFIED** · C-3 OPEN · C-4 OPEN, not authorized · C-5/6/7 NOT STARTED · incident **NOT CLOSED**.

---

## L0-DEC-03 — C-2 explicitly confirmed · **DECIDED** (2026-09-23, appended)

**The human's act** (governance session): *"c2->yes"*. ⚠️ Attestation: UNVERIFIED.

| Item | Decision |
|---|---|
| **C-2** | **CONFIRMED by explicit L0 act.** Previously marked satisfied only by derivation from L0-DEC-01. The disambiguation is accepted: **`RC-H-04`**, unique on all 7 local branches, is the identifier used in the C-4 authorization. The superseded qualified form `RCA:H-C4` is not needed |
| ⛔ Not decided here | C-3 · C-4 (C4-a/b/c) · Q-R · the research session's two scope questions |

**Status after L0-DEC-03:** **C-1 SATISFIED** · **C-2 SATISFIED (confirmed)** · C-3 OPEN · C-4 OPEN, not authorized · C-5/6/7 NOT STARTED · incident **NOT CLOSED**.

---

## L0-DEC-04 — C-3 acknowledged · **DECIDED** (2026-09-23, appended)

**The human's act** (governance session): *"c3->yes"*. ⚠️ Attestation: UNVERIFIED.

| Item | Decision |
|---|---|
| **C-3** | **ACKNOWLEDGED.** The self-correction in `GIA-INCIDENT-CLOSURE-01.md` §2 is accepted: the result "`H-Cn` no collision" in `GOVERNANCE-INTEGRITY-AUDIT-01.md` §11 was scoped to this branch's trees (worktrees and other branches were not searched), and the `H-C` series was minted without a cross-branch collision check. The audit text stays **as issued**; the closure record is the correction (RCI-015) |
| Consequence for practice | any future governance identifier series is checked for collisions **across all local branches and worktrees** before first use (as was done for `L0-DEC-nn`). This is recorded as practice, not as a new rule; a rule remains GIA-6 / NR-1 |
| ⛔ Not decided here | C-4 (C4-a/b/c) · Q-R · the research session's two scope questions |

**Status after L0-DEC-04:** **C-1 · C-2 · C-3 SATISFIED** · **C-4 OPEN, not authorized** · C-5/6/7 NOT STARTED · incident **NOT CLOSED**.

---

## L0-DEC-05 — C-4: RC-H-04 authorized, CONDITIONAL · **DECIDED, NOT YET EXECUTABLE** (2026-09-23, appended)

**The human's acts** (governance session): *"c4->yes"*, then the answers C4-a *"C: freeze and forward"* · C4-b *"After 1a + 1b accepted"* · C4-c *"Named exceptions"* (no exceptions named) · two scope questions *"I'll paste them"*. ⚠️ Attestation: UNVERIFIED.

| Item | Decision |
|---|---|
| **C-4** | **RC-H-04 AUTHORIZED**, subject to the conditions below |
| **C4-a remedy** | **C, FREEZE AND FORWARD.** v0.9 is preserved as issued (as-issued commit `ca8b96d59`). A **v0.9-AUDIT** classifies every affected finding (105 references, 10 artifacts) as retained · source-identity-affected · requires re-read · unsupported · unresolved. **v0.10** cites canonical IDs only. The B overlay (append-only ID-correction records) is included. Renumbering in place is excluded |
| **C4-b ordering** | **AFTER extended 1a AND 1b are accepted by L0.** Re-admission and re-reading must go through the reviewed admission path |
| **C4-c research continuation** | **"Named exceptions", but none are named.** ⛔ **No exception is inferred.** Until the human names exceptions, research stays **stopped**: no B-2, no F0041+, no new theory, no Candidate Theory changes based on F0031–F0040 |
| **The research session's two scope questions** | **to be supplied by the human.** ⛔ **C-5 is held until they are supplied and answered** |

### Preconditions for starting C-5 (all must hold; none holds today)

| # | Precondition | Source | Status |
|---|---|---|---|
| P-1 | extended 1a implemented, independently reviewed and **accepted by L0** | C4-b; GIA-2/3/4/7 | ⛔ not started (GIA decisions pending) |
| P-2 | 1b review done: GIA-5, GIA-9 and GIA-10 decided, and the admission path **accepted by L0** | C4-b | ⛔ not started |
| P-3 | the two research-session scope questions supplied and answered by L0 | L0-DEC-05 | ⛔ open |
| P-4 | C4-c exceptions named, or explicitly "none" | L0-DEC-05 | ⛔ open |

**The authorized scope, once P-1…P-4 hold**, is the "in scope / out of scope" table of the RC-H-04 request above, with remedy **C**. The research session executes (C-5). A separate session reviews (C-6). L0 closes (C-7).

⛔ **This decision does not authorize starting C-5 now**, and it does not authorize implementing 1a or 1b (those need GIA-1, GIA-2, GIA-3, GIA-4 and GIA-7, which are not decided).

**Status after L0-DEC-05:** C-1 · C-2 · C-3 **SATISFIED** · **C-4 AUTHORIZED, CONDITIONAL** (P-1…P-4 open) · C-5 **NOT STARTED, blocked** · C-6/C-7 NOT STARTED · incident **NOT CLOSED**.

---

## L0-DEC-06 — C-4 held: two research scope questions (verbatim) · **RECORDED; ANSWERS OPEN** (2026-09-23, appended)

**The human's act** (governance session): *"Record C-4 as authorized in principle, but hold C-5 execution until these two scope questions are explicitly resolved. Do not infer the answers. Do not treat C4-b or C4-c as substitutes."* ⚠️ Attestation: UNVERIFIED.

| # | Question (verbatim) | Relevant fact (evidence only, **not an answer**) | L0 answer |
|---|---|---|---|
| **SQ-1** | *"Whether canonical F0032, F0035, F0036 and F0040 are within RC-H-04 or should be handled by a successor correction unit."* | these four are the canonical IDs in F0031–F0040 that were **never read** (Increment 0 §2) | ⛔ **OPEN** |
| **SQ-2** | *"Whether the canonical identity mapping means that some apparent F0032/F0037 targets fall outside the nominal F0031–F0040 correction range."* | research "F0032" is canonical **F2837**, and research "F0037" is canonical **F2838**, both outside the nominal range. Research "F0036" and "F0040" are canonical F0042 and F0045 (Increment 0 §1) | ⛔ **OPEN** |

**Effect:** L0-DEC-05 stands as **C-4 AUTHORIZED IN PRINCIPLE**. C-5 has one more precondition, **P-5: SQ-1 and SQ-2 answered by L0**. This replaces P-3, which asked for the questions to be supplied.

---

## L0-DEC-07 — C4-c: SAFE-RESEARCH-EXCEPTION-01 · **DECIDED** (2026-09-23, appended)

**The human's act** (governance session), recorded **verbatim**. ⚠️ Attestation: UNVERIFIED.

> **SAFE-RESEARCH-EXCEPTION-01.** Research may perform **read-only research on previously unread corpus files that are demonstrably outside the F0031–F0040 provenance incident and have no dependency on the disputed canonical identities**.
>
> **Allowed:** identify and read safe, previously unread files · reconstruct their content and provenance · extract theory-bearing definitions, claims, derivations, relationships and gaps · update research discovery/reconstruction artifacts where this does not alter the affected F0031–F0040 state.
>
> **Explicitly forbidden until C-7:** B-2 · F0041+ progression · re-admission or reading of canonical F0032, F0035, F0036 or F0040 under the unresolved correction · modifying the F0031–F0040 research state · creating new research conclusions that depend on the disputed identities · executing RC-H-04/C-5 before its remaining governance prerequisites are satisfied · changing theory v0.9 or existing registry rows · any governance decision or gate activation.
>
> **If a file's status is uncertain, classify it as GOVERNANCE-HELD and stop rather than infer that it is safe.**
>
> This exception does not modify, weaken, or close the incident and does not authorize C-5.

**This replaces P-4** (exceptions named). **C4-c = SAFE-RESEARCH-EXCEPTION-01.**

### Governance observations (facts for L0; not interpretations applied)

| # | Observation | Evidence | Consequence under the exception's own rule |
|---|---|---|---|
| **SRE-Q1** | ⚠️ **Every previously unread canonical file outside the incident has an ID ≥ F0041.** F0001–F0030 are all read. F0031–F0040 is the incident. The lowest unread are **F0041, F0043, F0044, F0046, F0047…** | registry paths resolved against the canonical list at HEAD: 40 canonical IDs read | "allowed: read safe, previously unread files" and "forbidden: F0041+ progression" can **both** apply to the same files. **The two readings:** (i) *progression* = advancing the research position / next-target sequence, and out-of-order read-only reading is allowed; or (ii) any F0041+ read is forbidden, which would leave the exception with **no eligible file**. ⛔ **Not resolved here.** Until L0 clarifies, every F0041+ candidate is **GOVERNANCE-HELD** |
| SRE-2 | canonical **F0042, F0045, F2837, F2838** were already read under disputed IDs | Increment 0 §1 | **not** "previously unread" and **dependent on disputed identities**, so outside the exception (held) |
| SRE-3 | reading a new file means **registering** it (an append to `FILE-REGISTRY.jsonl` under its canonical ID). The research ID-assignment step is the mechanism that failed, and **no admission control exists yet** (1b pending, C4-b) | P1P §4; B-13; GIA-9 | not forbidden by the exception (an append does not alter the F0031–F0040 state), but the **F0031 failure class is unguarded**. For each file, its canonical ID must be taken verbatim from `list_of_files_to_read.log`. Recorded as a risk; any mitigation is an L0 call |

**Status after L0-DEC-06/07:**
- C-1 · C-2 · C-3 **SATISFIED**.
- **C-4 AUTHORIZED IN PRINCIPLE.** C-5 is blocked on **P-1** (1a accepted), **P-2** (1b accepted) and **P-5** (SQ-1 and SQ-2 answered).
- C4-c = SAFE-RESEARCH-EXCEPTION-01, with **SRE-Q1 open** (until it is answered, F0041+ candidates are GOVERNANCE-HELD).
- C-6/C-7 NOT STARTED · incident **NOT CLOSED**.

---

## L0-DEC-08 … 10 — transcription of the research-side L0 record · **TRANSCRIBED** (2026-09-23, appended)

**Source:** `L0-DECISIONS-RESEARCH-RECORD-01.md` (research session, commit `1e6dc3824`), which records acts the human made **in the research session** (D-1, D-2, D-3). **This governance entry transcribes them**, as that record requests and as GVR-F0032 X-1 / GVR-F0026 X-5 require. The human's direction to the governance session to transcribe: the review forwarded in this session (*"the next actor should be the governance/control-plane session … transcribe into authoritative L0-DECISION-RECORD-01"*). ⚠️ **Attestation: UNVERIFIED, and second-hand.** The acts were given in the research session and transcribed first by research, then by governance.

| ID | L0 act (as recorded research-side) | Governance entry |
|---|---|---|
| **L0-DEC-08** | **D-1: O-1 = interpretation A.** "Other Theory Objects" means objects other than those the **current file establishes or directly evidences**. Phase 1 may create/update its own file's objects, and may not touch unrelated objects | **RECORDED.** Scope: a reading of the Phase-1 rule; not an architecture change (the architecture annotates it under §8B). The constraint that survives: SAFE-RESEARCH-EXCEPTION-01 still forbids changing existing registry rows. ⚠️ The research record calls the permitted form *"append-only overlays (C4-a remedy **B**)"*, but **L0-DEC-05 chose C4-a = C** (freeze and forward, which **contains** B). This is recorded as a label discrepancy, not a conflict |
| **L0-DEC-09** | **D-2: the reads are authorized:** F0027 · F0001 · F0010 (the retrofit pilot) and the earlier **F0026** and **F0032** (controlled test) | **RECORDED.** This answers GVR-F0026 U-1/X-5 and GVR-F0032 A-1. ⛔ It authorizes **those five reads only**. It does **not** authorize C-5, satisfy P-1/P-2/P-5, answer SQ-1/SQ-2, or close the incident. Whether the F0032 read counts as part of RC-H-04 (GVR-F0032 U-4) remains **OPEN** |
| **L0-DEC-09a** | **SRE-Q1 = (a)** (research commit `73b6bb5b5`; confirmed by D-2 as "recorded only research-side → confirmed"): *"F0041+ progression" = chronological advancement of the main research sequence; out-of-sequence read-only reads of safe F0041+ files are not prohibited by it* | **RECORDED.** This **supersedes** L0-DEC-07's governance observation "SRE-Q1 OPEN / F0041+ GOVERNANCE-HELD". SAFE-RESEARCH-EXCEPTION-01 otherwise stands as written |
| **L0-DEC-10** | **D-3: `prompts/readme.md`: "the human commits it"** | **RECORDED as transcribed, with an ambiguity flag.** The human's words were reported as *"You commit it"*. The research session read this as *the human* commits. If it meant *Claude* commits, the reading is wrong. ⛔ **Clarification required from L0.** The file is still **uncommitted** (`?? prompts/readme.md`) and unchanged |

### New governance items surfaced during transcription (for L0; not decided)

| ID | Item | Evidence |
|---|---|---|
| **GI-1** | **No L0 act approves Research Architecture v1.2 (Addendum B, RA-13…RA-16)**, authored and committed by the research session in `9444cfbb5`. ARCH §9 requires a proposal with evidence; the commit supplies evidence, but no approver is recorded, as was also the case for v1.0/v1.1 | the research-side record has no D-item for v1.2; the ARCH header records only O-1 as L0-resolved |
| **GI-2** | **"Addendum B" is now doubly used.** ARCH v1.2 §8B is "Addendum B". `architecture/research-control-architecture.md` is "submitted … as candidate *Addendum B*" (RC-H-05). RC-H-05's question as worded no longer names a free slot | ARCH header; RCA header |
| **GI-3** | **The T-0026 representation question** (from the research-side record): overlay row, new `THEORY-OBJECT-REVISIONS` registry, or hold until C-5. **Evidence for L0, not a choice:** P1P **§5A "RECORD VERSIONING (APPEND-ONLY)"** already specifies the mechanism for revising Theory Objects: append a new version with `record_version · previous_record_hash · new_record_hash · changed_by_file · change_reason · change_evidence · changed_at`; *"never in-place"*. It has **never been implemented** (0 rows carry these fields), and its freeze was part of the unexecuted §45 Gate 3. A §5A version row would **consume** an existing rule. A new registry would be a new artifact shape (ES-005.4) | P1P l.1247 ff.; spec G-004 |

**Status after L0-DEC-08…10:**
- C-1 · C-2 · C-3 **SATISFIED**.
- C-4 **authorized in principle**. C-5 is blocked on P-1, P-2 and P-5 (SQ-1, SQ-2).
- SRE-Q1 **RESOLVED (a)**.
- Five reads **AUTHORIZED** (L0-DEC-09).
- Open for L0: **GI-1, GI-2, GI-3, L0-DEC-10's clarification**, and GVR-F0032 U-4.
- Incident **NOT CLOSED**.

### GI-3 evidence: can P1P §5A represent the T-0026 correction exactly as written? (verification, appended)

**§5A separates three revision cases, which "must not be conflated"** (P1P §5A, "The third case"):
1. **reconstruction revision:** a `record_version` chain;
2. **cross-file correction:** gap resolution (§16) or contradiction (§22), a file→file edge;
3. **intra-file revision:** `intra_file_revisions[]` on the File Reconstruction Record.

| T-0026 omission | Evidence location | §5A case | Verdict |
|---|---|---|---|
| I-4 underspecified / falsification withdrawn | canonical F0027 L108 (already in F0027's record `corrections`: VERDICT_REVERSED) | 1, on T-0026, with F0027 evidence | **representable** (own-file, L0-DEC-08) |
| I-11 restated | F0027 L115 (already in the F0027 record: OVERSTATEMENT_WEAKENED) | 1 | **representable** |
| "10/11 invariants held; I-4 FALSIFIED" | F0027 **header** L7, citing the cross-product file (canonical F0036, **held**) | 1, using **F0027's own wording only** | **representable** as F0027 states it. ⛔ F0036's content may not be used |
| I-8 not an invariant | a later file: T-0033, `historical_sources` research "F0034" = canonical **F0031** (incident range) | **2**, a cross-file edge, **not** a `record_version` | ⛔ **not representable by the F0027 unit**: other-file evidence (outside O-1 = A) and inside RC-H-04 |
| (also) `key_invariants` lists 9 of 11 (omits I-4, I-7) | F0027 §4 | 1 | representable |

**Mechanics §5A does not define** (its freeze belonged to the unexecuted §45 Gate 3; 0 rows carry the fields):
- (m1) how `previous_record_hash` / `new_record_hash` are computed (canonical serialization and algorithm);
- (m2) how an existing **unversioned** row becomes version 1. Treating it as v1 as-is, with its hash computed over the current line, is one option.

⛔ Governance does not choose these. They are mechanical and non-epistemic, but they fix how a Phase-1 record is revised. They are therefore **for L0 to confirm** (or for L0 to delegate to governance).

**Evidence summary for GI-3:**
- §5A case 1 is the existing mechanism for the own-file parts. A new registry is not needed for them.
- The I-8 part is a case-2 cross-file item and stays held with RC-H-04.
- Using §5A requires (m1) and (m2) to be fixed first.

---

## L0-DEC-11 — L0-DEC-10 clarified: README committed by Claude · **DECIDED** (2026-09-23, appended)

**The human's act** (governance session): *"commit it"*, given right after the governance session reported `prompts/readme.md` as the only uncommitted item pending L0-DEC-10. ⚠️ Attestation: UNVERIFIED.

| Item | Decision |
|---|---|
| **L0-DEC-10** | **RESOLVED:** *Claude commits it.* The research-side reading of D-3 (*"the human commits it"*) is superseded |
| Content | committed **as found**; not edited by the governance session. It already contains the "Gate Verification and Phase Completion" section. The research-side note that the gate steps were not added is therefore out of date |
| ⚠️ Observation (not acted on) | the README names `architecture_phase_1_phase_2.md` as governing context, which ARCH v1.2 (`9444cfbb5`) marks **SUPERSEDED**. The README should be reconciled by its owner; that is not decided here |

---

## L0-DEC-12 … 16 — extended Increment 1a authorized · **DECIDED** (2026-09-23, appended)

**The human's acts** (governance session, a structured decision following `GATE-INTEGRITY-AUDIT-02`). ⚠️ Attestation: UNVERIFIED.

| ID | Decision | Human's answer |
|---|---|---|
| **L0-DEC-12 (GIA-1)** | current and recorded **CLEAR results are NOT evidence of governance conformance** | "Approve all three" |
| **L0-DEC-13 (GIA-2)** | **extended Increment 1a APPROVED** = IC-1…IC-8 (`GIA-DECISION-PACKAGE-01.md` §7), in the sequence 1a.1 baseline → 1a.2 regression fixtures first → 1a.3 implement → 1a.4 self-test 100% → 1a.5 full battery → 1a.6 independent review → 1a.7 L0 acceptance | "Approve all three" |
| **L0-DEC-14 (GIA-7)** | **no REVIEW or HUMAN gate is activated** until a completion-record mechanism exists (IC-8) | "Approve all three" |
| **L0-DEC-15 (GIA-3)** | **only the governance session modifies** `governance/gate-runner.py`, `gates.yaml` and the door. A separate session reviews; L0 accepts. **The research session is excluded** | "Governance session only" |
| **L0-DEC-16 (GIA-4)** | **an activation pins the exact gate-definition hash.** Any change to a pinned gate's definition makes governance `GOVERNANCE_INOPERATIVE` until the human re-activates | "Yes, pin the hash" |
| **L0-DEC-17 (GIA-5)** | **KOS-G-003's authoritative known set of file IDs = the canonical corpus list** (option A) | "A: canonical manifest" |

**Implementation notes, recorded before implementation (governance, not L0):**
- **For L0-DEC-17:** the governance session will use `docs/knowledgeos/list_of_files_to_read.log` (the canonical list, committed `7698c99b4`) **directly** as the known file-ID set. It will **not** use the research-produced `evidence/CORPUS-MANIFEST.jsonl`, which stays non-authoritative under GIA-9. This keeps L0-DEC-17 independent of the pending 1b review. An unreadable canonical list makes the check INCONCLUSIVE (IC-1).
- **For L0-DEC-16:** pins are **written by the human** in `governance-state.yaml`, because activation is a human act. The runner will **print** the pin lines for the human to paste; governance does **not** write activation. **Consequence:** after 1a lands, the current bare activations are **unpinned**, and the runner reports `GOVERNANCE_INOPERATIVE` until the human re-activates with pins. That is intended.

⛔ These decisions do not authorize 1b, C-5, RC-H-04, or any research action. Research stays **BLOCKED** (audit-02 G8) until 1a is accepted (1a.7) and a re-run shows release.

---

## L0-DEC-18 · L0-DEC-19 — admit.py repair authority; batch-2 release condition · **DECIDED** (2026-09-23, appended)

**The human's acts** (governance session, following `audits/2026-09-23-ADMIT-AUDIT-CRASH-FINDING.md`). ⚠️ Attestation: UNVERIFIED (AI transcription).

| ID | Decision |
|---|---|
| **L0-DEC-18 (D-a, option i, narrow)** | **L0-DEC-15 is extended**: the governance/control-plane session may repair **the specific `admit.py --audit` defect** tests-first, followed by independent review. ⛔ **Scope is limited to correctly handling the declared VOID-record shape, plus the necessary regression coverage.** No general `admit.py` redesign. ⛔ **No modification of research evidence or registry state to obtain a pass** |
| **L0-DEC-19 (D-b)** | **C-5 is NOT an unconditional prerequisite** for testing or releasing the governance instrument. The release condition separates **instrument operability** from **the known RC-H-04 evidence divergence**. **Before batch 2, all of:** (1) independent 1a.6 review complete; (2) L0 1a.7 acceptance; (3) human activation pins installed; (4) `gate-runner.py` produces an actual verdict, not `GOVERNANCE_INOPERATIVE`; (5) `admit.py --audit` completes and produces a binding verdict; (6) the known nine-ID RC-H-04 divergence is **explicitly reported and not silently treated as PASS**; (7) no new unexplained governance/control-plane defect |

**Standing instructions carried with these decisions:**
- **Do not start batch 2.**
- After the `admit.py` repair and its independent review, governance **presents the resulting machine verdict and the exact proposed treatment of the nine-ID divergence for an L0 decision** before batch 2 is released.
- **Batch 1 remains research-completed evidence, not governance-certified.**

⛔ These decisions do not authorize C-5, RC-H-04, 1b, pin installation by any session, or any research action.

---

## L0-DEC-20 · L0-DEC-21 — batches 2/3 status; correction slice after the 1a.6 review · **DECIDED** (2026-09-23, appended)

**The human's acts** (governance session, a structured decision following `audits/2026-09-23-GIA-1a6-INDEPENDENT-REVIEW.md`, commit `f21b3e6e3`). ⚠️ Attestation: UNVERIFIED (AI transcription).

| ID | Decision |
|---|---|
| **L0-DEC-20** | **Batch 2 (`b1844ab70`, F0007/F0009/F0011–F0013) and batch 3 (`c360b89cb`, F0014–F0018) are recorded as `EXECUTED BEFORE GOVERNANCE RELEASE / NOT RETROACTIVELY CERTIFIED`.** Their commits and artifacts are **retained unchanged** as historical research work. They may be inspected and reused **subject to provenance and evidence-state checks**. They are **not** represented as governed by the accepted instrument. **Future batches require the post-acceptance release conditions (L0-DEC-19)** |
| **L0-DEC-21** | **A narrow correction slice is authorized**, governance session only, tests-first, **followed by a fresh independent verification**. Scope is exactly: **IR-G1** (activating a tier-B gate ⇒ `GOVERNANCE_INOPERATIVE`), **IR-G2** (stage/timing values outside the schema enums are refused; the door's no-control message is made accurate), **IR-G4** (an activated gate whose check is not implemented ⇒ `GOVERNANCE_INOPERATIVE`), **IR-A1** (`admit.py --audit`: an unparseable receipt line or a non-string `file_id` becomes an unrecognised-row binding failure, not a traceback; **an explicit new authorization, not an expansion of L0-DEC-18**), and **IR-A2** (the admit regression derives its expected receipt count instead of hard-coding it). IR-G1 is held **blocking** for 1a acceptance |

**Carried with these decisions:**
- The independent review (`f21b3e6e3`) is **not rewritten**. These decisions respond to it.
- Findings **not** in the slice stay recorded as residuals: IR-G3, IR-G5, IR-G6, IR-A3–IR-A6, R-1, R-2, IR-B1–IR-B4.
- **1a.7 acceptance (D-1, D-2) is deferred** until the slice lands and is independently verified.
- ⛔ Do not start batch 4. No pins, no C-5, no RC-H-04 action.

---

## L0-DEC-22 — protocol role amendments, Phase 1 and Phase 2 · **DECIDED** (2026-09-23, appended)

**The human's acts** (governance session): the role texts were supplied by the human with *"can you update the prompt for phase 1"* and *"follow the prompt and update … step2 … also"*. The Phase-1 integration mode was chosen as *"Phase-1-scoped (Recommended)"*. Commit instruction: *"commit the protocol edits and record them"*. ⚠️ Attestation: UNVERIFIED (AI transcription).

| Protocol | Change | Boundary preserved |
|---|---|---|
| `prompts/knowledge_os_protocoll.md` (Phase 1; baseline `708674287`) | **+71 lines**: *Senior Researcher Role — Phase 1* section, after the governing-architecture block, plus a **Phase-1 scope binding** table | **Phase-1-scoped.** Construction, validation and canonicalization stay out of Phase 1. `[E]` is a **record** only (`HYPOTHESIS` / `NOT_YET_ASSESSED`). Header table, §0B, §0E.6, §28, §29 and the frozen architecture are unchanged and **win on any apparent difference** |
| `prompts/knowledge_os_step2_theory_construction_protocol.md` (Phase 2; baseline `224a3e671`) | **+166 / −4** (the 4 are replacements): *Senior Researcher Role and Research Objective* section, a binding table, and a **new ORIGIN value `[T] TEST-DERIVED`**, carried into §5C.2, §5C.3, `Q51`, `Q52`, the 2C job row, and a v3.3 header row | no new job, level or gate. **`[T]` is an ORIGIN value, not a STRENGTH value**: it enters at L2. L5 stays human-only. "Replace or reject" goes through new records, demotion and competition, never by rewriting history |

**Recorded consequences:**
- ⚠️ **Known inconsistency, not fixed here:** `governance/gates.yaml` **KOS-G-044** still describes origin labels as `[C]/[S]/[E]`. It is a REVIEW gate, not activated and not activatable (L0-DEC-14). A one-line text fix under L0-DEC-15 is pending, and changing it changes its pin.
- The research session recorded the uncommitted state beforehand in `PROTOCOL-CHANGE-RECORD-01.md` (research-owned; not committed by governance). Its line counts match this entry.
- ⛔ This decision changes research methodology text only. It does not release batch 4 (L0-DEC-19 still binds) and does not certify batches 1–3.

---

## L0-DEC-23 — classification of the research artifacts in `18133be5b` · **DECIDED** (2026-09-23, appended)

**The human's act** (governance session, structured decision): *"Classify as proposed (Recommended)"*. ⚠️ Attestation: UNVERIFIED (AI transcription).

| Artifact | Classification |
|---|---|
| `SENIOR-RESEARCHER-BASELINE-01.md` | **`EXECUTED WHILE GOVERNANCE BLOCKED / NOT GOVERNANCE-CERTIFIED`.** Retained **unchanged**. Its `[E]-01`…`[E]-04` are **`HYPOTHESIS` records, not Theory Objects**. It is reusable subject to provenance and evidence-state checks, and to the post-release Critical Attack Pass. *Facts established by governance, not a content review:* no new corpus reading is claimed, and the commit touched only its two markdown files (no registry, Theory Object or Candidate-Theory change) |
| `PROTOCOL-CHANGE-RECORD-01.md` | **a factual state record** of the uncommitted protocol edits. No research content, so no certification is needed. Its counts agree with L0-DEC-22 |

⛔ **Not decided here:** whether the baseline's content is correct, and whether the Critical Attack Pass is authorized. That comes after release under L0-DEC-19, with the research session's own release revalidation. Research stays **BLOCKED**.

---

## L0-DEC-24 · L0-DEC-25 · L0-DEC-26 — after VERIFICATION-02 · **DECIDED** (2026-09-23, appended)

**The human's acts** (governance session, structured decision following `audits/2026-09-23-L0-DEC-21-SLICE-VERIFICATION-02.md`). ⚠️ Attestation: UNVERIFIED (AI transcription).

| ID | Decision |
|---|---|
| **L0-DEC-24 (Q-2)** | **IR-A1's "unparseable receipt line" includes invalid UTF-8.** V-A1a is therefore within IR-A1, which is **not closed for that input**. The fix is authorized tests-first: **each receipt line is decoded inside the existing guard**, so a `UnicodeDecodeError` becomes the same visible unrecognised-row failure. ⛔ Nothing else in `admit.py` changes. V-A1b, V-A1c and the other VERIFICATION-02 findings stay **recorded residuals** |
| **L0-DEC-25 (Q-1)** | VERIFICATION-02 is committed **unchanged** and classified as **independent of the implementation, but NOT the "fresh" verification L0-DEC-21 requires** (its verifier wrote the 1a.6 review). **A genuinely fresh session**, with no context from the 1a.6 review, VERIFICATION-02 or the implementation, verifies the L0-DEC-21 slice **plus** the L0-DEC-24 fix |
| **L0-DEC-26** | The research session's protocol edit **`628d02169`** (Step-2 §5A.3a, *the three representations*, +20/−1) is recorded as made **on the human's instruction** (*"if necessary add these into the prompt also"*). No content review by governance |

⛔ Still deferred: 1a.7, pins, the live release run, batch 4.

---

## L0-DEC-27 — Minimum Viable Governance: risk classes, one release check · **DECIDED** (2026-09-23, appended)

**The human's acts** (governance session, structured decision following an advisor proposal): *"Adopt it (Recommended)"*, and on the fresh check, *"Yes, one fresh check (Recommended)"*. ⚠️ Attestation: UNVERIFIED (AI transcription).

**Principle.** Govern the decisions that can damage the research, not every action performed during it. The standard is **"sufficient to protect research validity"**, not "perfect".

**Risk classes (every governance defect gets one):**

| Class | Meaning | Effect |
|---|---|---|
| **R1** research-invalidating | can produce false evidence, false provenance or false assurance: wrong corpus file, silent provenance change, dependent evidence counted as independent, a PASS/CLEAR over a failure, a result untraceable to its source | **RED: stop** |
| **R2** research-relevant, contained | a known limitation that cannot invalidate research evidence under stated conditions | **YELLOW: continue**, with the limitation recorded |
| **R3** operational | wording, logging, test thoroughness, documentation | **GREEN: fix opportunistically** |

**One Research Release Check** replaces L0-DEC-19's seven-item condition (L0-DEC-19 is **superseded**; its items fold in as shown):

| # | Question | Covers L0-DEC-19 item |
|---|---|---|
| 1 | Is the approved methodology unchanged since the last release? | — |
| 2 | Is the research corpus and state identifiable (HEAD, manifest hash, receipts)? | 5, 6 |
| 3 | Are the known governance defects classified, with no open R1? | 7 |
| 4 | Can the controls detect what they claim? This means one fresh independent verification of material control changes, plus the regression batteries | 1, 4 |
| 5 | Has L0 accepted the current state (acceptance and pins)? | 2, 3 |

Result: **GREEN** (no R1, no R2 limitation affecting the scope) · **YELLOW** (no R1; R2 limitations recorded, with why they cannot invalidate the authorized scope) · **RED** (any R1). **L0 decides the release** on that result.

**L0 decides only:** release, scope changes, exceptions, phase boundaries, and R1 defects. Technical choices inside an approved scope are not L0 decisions.

**Every control already implemented stays.** They answer R1 defects that actually occurred: the F0031–F0040 wrong-file incident, D09's silent CLEAR, the G02 false FAIL, and IR-G1's latent CLEAR.

**Open residuals, classified (none R1, so none blocks):**

| Class | Items |
|---|---|
| **R2** | the nine RC-H-04 IDs F0031–F0038 and F0040 (**evidence divergence**, reported by the audit; contained only if the released scope does not touch or cite them) · IR-A3 (an absent registry gives BOUND silently; contained while the release check confirms the registry is present and the nine IDs are reported) · IR-A6 / A-5 (a receipt proves admission, not reading) · R-2 (runner code unpinned) · IR-B1 (fixtures unpinned) · IR-B2 (schema unpinned) · IR-A5 (receipt stream not append-protected) |
| **R3** | IR-G3 · IR-G5 · IR-G6 · IR-A4 · IR-B3 · IR-B4 · R-1 · V-A1b · V-A1c · V-A2a · V-A2b · V-G1a · V-G2a · V-G2b · V-G4a · KOS-G-044 origin-label wording |

**Remaining path to release:** VERIFICATION-03 (one fresh check, already commissioned) → L0 acceptance and pins → Research Release Check → L0 release (GREEN or YELLOW). After that, **governance work resumes only on a new R1 defect, a scope change, or a phase boundary.**

> **Resolution note (appended 2026-09-23; L0-DEC-22's recorded inconsistency):** KOS-G-044's wording was aligned to `[C]/[S]/[E]/[T]` in `1c5c5bea3`, on the human's instruction *"fix the KOS-G-044 origin-label wording"*. Text only. The five activated pins are unchanged. The VERIFICATION-03 commission now includes that commit in its scope.

---

## L0-DEC-28 — VERIFICATION-03 recorded · **DECIDED** (2026-09-23, appended)

**The human's acts** (governance session): *"use a subagent"* (the verifier route), then *"commit VERIFICATION-03 and record it"*. ⚠️ Attestation: UNVERIFIED (AI transcription).

| | |
|---|---|
| **Record** | `audits/2026-09-23-L0-DEC-21-SLICE-VERIFICATION-03.md`, committed **unchanged** (written by the verifier, read in full by governance before commit) |
| **Verifier standing** | a **subagent launched by the implementing governance session** at the human's direction, with no context beyond the commission line and a disclosure instruction. **Fresh in context; not organisationally independent.** It followed the commission's reading order: VERIFICATION-02 was opened only after its measurements were recorded. ⭐ **This is the fresh verification required by L0-DEC-25, via the route the human chose, with its limitation disclosed** |
| **Verdicts** | IR-G1 CLOSED · IR-G2, IR-G4, IR-A1 (incl. L0-DEC-24), IR-A2 CLOSED_WITH_FINDINGS · **slice + fix: ACCEPT_WITH_FINDINGS**. No NOT_CLOSED item; no rejection criterion met. All ten reported measurements reproduced exactly; the five pins were recomputed at `cf8d2f4d0` and HEAD and agree |
| **New findings, classified (L0-DEC-27)** | **N-1** (byte-level read is stricter: bare-CR files and NBSP/`\x1c`-only lines now fail visibly; the live data is unaffected) · **N-2** (the schema parse error is printed twice) · **N-3** (a list/mapping `check` crashes the runner, which still ends INOPERATIVE with exit 3; pre-existing) · **N-4** (the runner token `NO_ACTIVE_GOVERNANCE_CONTROLS` when a filter excludes the activated gates; the door text is accurate). All **R3**, following the verifier's proposal; none is false assurance. L0 may reclassify at the release check |
| **Stated limits of the record** | its pin script shares algorithm and library with the runner (cf. IR-G5); VERIFICATION-02's "12 tier-A gates pinned ⇒ BLOCK" is marked NOT VERIFIED |

⛔ **Not decided here:** 1a.7 acceptance (D-1/D-2), pin installation, the Research Release Check, release. Research stays **BLOCKED**.

---

## L0-DEC-29 — 1a.7: Extended Increment 1a ACCEPTED · **DECIDED** (2026-09-23, appended)

**The human's act** (governance session): *"accept 1a.7 and record it"*. ⚠️ Attestation: UNVERIFIED (AI transcription).

**Accepted, as one control-plane state at HEAD `cb1c6f847`:**

| Part | Authorized by | Evidence the acceptance rests on |
|---|---|---|
| Extended Increment 1a (IC-1…IC-8, pins, canonical list) | L0-DEC-13…17 | 1a.6 review `f21b3e6e3` (ACCEPT_WITH_FINDINGS) |
| `admit.py` VOID-row repair | L0-DEC-18 | 1a.6 review (ACCEPT_WITH_FINDINGS) |
| Correction slice IR-G1/G2/G4/A1/A2 | L0-DEC-21 | VERIFICATION-02 (implementation-independent) · **VERIFICATION-03 (fresh; L0-DEC-28)**: ACCEPT_WITH_FINDINGS, no item NOT_CLOSED |
| Invalid-UTF-8 fix | L0-DEC-24 | VERIFICATION-03 |
| KOS-G-044 wording | L0-DEC-22 resolution note | VERIFICATION-03 (only KOS-G-044 changed; pins unchanged) |

**Accepted with the findings recorded, not as a claim of perfection** (L0-DEC-27): no open **R1**. The **R2** limitations are listed in L0-DEC-27. The **R3** items are those in L0-DEC-27, plus N-1…N-4 (L0-DEC-28).

**Consequences:**
- The **pins are the next act, and a human one** (L0-DEC-16). The five lines printed by `gate-runner.py --pin-lines` (`485a327da2ca8f9e` · `7084bc523256ccb3` · `9327509ad713a267` · `45e060f20216d64d` · `9c5d3802a2ff6942`) are pasted by the human under `activated:` in `governance/governance-state.yaml`. ⛔ **No session writes them.**
- After the pins: the governance session runs the **Research Release Check** (L0-DEC-27) and reports GREEN, YELLOW or RED. **L0 decides release.**
- ⛔ This acceptance **does not release research**. It certifies no batch (L0-DEC-20/23 stand), and it authorizes neither 1b nor C-5 / RC-H-04.

---

## L0-DEC-30 — RESEARCH RELEASED under YELLOW, bounded scope · **DECIDED** (2026-09-23, appended)

**The human's act** (governance session): *"release under YELLOW with the suggested scope and record it"*, following `audits/2026-09-23-RESEARCH-RELEASE-CHECK-01.md` (`e06870174`, YELLOW). ⚠️ Attestation: UNVERIFIED (AI transcription).

**Release status: 🟡 YELLOW.** Research is **released for exactly this scope**:

| | |
|---|---|
| ✅ **Authorized** | **Critical Attack Pass 01** on the four `HYPOTHESIS` records `[E]-01`…`[E]-04` of `SENIOR-RESEARCHER-BASELINE-01.md`. Each is attacked (mathematical · statistical · logical · DDD · computational) and ends as **survives / weakened / reformulated / falsified**, recorded as new Phase-2 records with origin labels (`[E]`/`[T]`) and levels (§1 ladder). The pass then gives a **Phase-2 readiness assessment**, and **STOPS** |
| ⛔ **No corpus reading** | no file is opened, admitted or receipted. The pass works on already-recorded material only |
| ⛔ **No work on F0031–F0040** | nothing may read, cite or build on the nine RC-H-04 IDs. Any need to do so ⇒ **RED for that work**; stop and escalate |
| ⛔ **Also not authorized** | Batch 4 · Phase-2 entry (the readiness assessment is an assessment, not an entry) · C-5 / RC-H-04 · 1b · edits to existing Theory Object / registry rows (append-only, `SAFE-RESEARCH-EXCEPTION-01`) · changes to Candidate Theory v0.9 · protocol edits · any governance-file change |
| ⚠️ **Limitation to carry (release check O-1)** | `[E]-02` relies on **T-0056**, one of 12 objects (T-0047…T-0058) without a relation record. **Governance default, recorded because L0 did not state otherwise:** `[E]-02` may use T-0056, and its attack record **must state that limitation**. L0 may change this |
| **Entry obligation** | the research session first runs its own **release revalidation**: it confirms this entry, HEAD, and a live `CLEAR` from the door. ⛔ **If the door is not CLEAR, it does not start** |
| **Exit** | STOP after the readiness assessment. Report to L0. Any further scope needs a new release (L0-DEC-27) |

**Governance after release (L0-DEC-27):** governance work resumes only on a **new R1 defect**, a **scope change**, or a **phase boundary**. The R2 containment for the nine IDs holds **only while this scope is respected**.

## L0-DEC-31 — T-A RELEASED under YELLOW, bounded scope · **DECIDED** (2026-09-26, appended)

**The human's act** (explicit L0 decision, 2026-09-26): *"I am now acting explicitly as the L0 decision-maker. Please treat the following as my explicit L0 authorization …"*, confirmed in the human's own words as *"Yes, record and run"*. It follows `audits/2026-09-26-RESEARCH-RELEASE-CHECK-02.md` (YELLOW). **This is a human L0 decision, not AI-authored authority.** ⚠️ Attestation: UNVERIFIED (AI transcription). Preparation: F-lane F-LOG-0035…0039.

| Decision | L0 | Content |
|---|---|---|
| **A. Methodology acceptance** | **ACCEPT** | frozen T-A r3, `chronological_knowelgeos_ablation_theory/prompts/KNOWLEDGEOS-H-F2-1-R-T-A-PREREGISTRATION.md`, sha256 `be16deb7af88d1c87072566c0e1c1feb43258cf2e84e6d707107bedc6a617133`, and `analysis/t_a/aggregate.py` sha256 `13532a5bd4123514ab1cf92d2a057a64c8bab20bdca5b2cf59e43febc2d8c7b9`, **byte-for-byte unchanged during T-A**. The four advisory files and FP-GOV-01…04 are **not** part of the methodology and **not adopted** |
| **B. Known limitations (YELLOW)** | **ACCEPT, each explicitly** | (1) **F2800:** stale global manifest; outside the T-A scope; no T-A dependency; no manifest rebuild; no T-A reader may read, cite or rely on F2800. (2) **T-0056: NOT AN INPUT — PROVENANCE CAVEAT APPLIES**; its text is not read as a T-A input; batch 3 not retroactively certified; the limitation is neither evidence for nor against H-F2-1-R. (3) **Four advisory files:** external advisory material, not in-force methodology; FP-GOV-01…04 outside T-A and not adopted. (4) **RC-H-04:** known; outside the T-A scope; not claimed resolved. (5) **KOS-G-020:** FAIL recorded; inactive; not relied upon; not relabelled GREEN |
| **C. Authorization to execute T-A** | **AUTHORIZE** | the pre-registered T-A experiment under the exact frozen r3 protocol and the released scope below |

| | |
|---|---|
| ✅ **Released objects (T-A only)** | **F0018**: `d61bf5e84:docs/knowledgeos/KnowledgeOS_Engineering_Progression_Model.md`, sha256 `b685f5991338f24df135a50bd7d004999d62401bdac10f9c0ef8241ecbbed7bc`. **ES-006 = IN, seven historical objects**: `d63202b8c:engineering/governance/ES-006-Knowledge.md` `7205c52d8b13090abbb51b788d08516bd4b1e2c514c2b5ab8cdde79f87327530` · and at `engineering/governance/ES-006-Engineering-Knowledge-Governance.md`: `aee484e9c` `d23d8b53865c9c5bdde412274c3f09b3e34d1bf8ecf69beb7e2396f8b795af9c` · `da565a213` `25fcd0048e45cc43b396e895f7646d403041172d9d9f62315e19bd2e5f5fffb0` · `c71f7d689` `facb576d3637df66c2e464a5e54affe7666e39e15e38e99132e1e0a6d0b97bda` · `8d1df4b1d` `170f344d6eb0efe52faa729fc28fcc06a19cd73ced0a63f3a8cbe6007c4065b2` · `43682264d` `12287296507530a600724018d1d8177053f967fbfe61d596ed8130fb240b6e6c` · `668cc7b22` `349b7d5d5e2df4ffea052c06c35e2c2d5503c22ffd79654946ad671d2f0c42a0`. The r3 §2.1 erratum is preserved (the full-history rule governs; r3 is not edited). M-2/M-3 not released (Q-GS, Q-D4 NOT_RUN) |
| ⛔ **Not released** | every other corpus object, including F2800, F0031–F0040 (RC-H-04), F0041, and T-0056's text as input |
| ⛔ **Execution restrictions** | frozen r3 only · no ML · no embeddings · no LLM classification · no inferential statistics · no corpus-wide search · no global manifest rebuild · no modification of r3 or `aggregate.py` · no H-F2-1-R revision · no A6 replacement · no simultaneous theory revision and experiment · **stop at the pre-registered aggregation point** |
| **Independent reader** | a fresh-context reader (receives only the blind packet `analysis/t_a/reader_packet/`, PACKET.sha256 `2966140f2f1a05f430187e3eb8c4a4737c66a94565c478a48c8c3a12bfad5e71`, and the released objects by hash; no access to the authoring session's predictions or results; identity, model family and fresh-context declaration recorded; ledger sealed independently) is required **before the result is interpreted as independently verified**. **Not the current Claude session.** Status at decision: **not commissioned** |
| **Entry obligation** (as for L0-DEC-30) | the research session confirms this entry, HEAD, every frozen hash and object pin, and a live `CLEAR` from the door. ⛔ **Any mismatch, or a door that is not CLEAR, means STOP and report to L0; no silent repair** |
| **Validity** | single use; void on any hash mismatch at execution time |

**This authorization does NOT mean:**
- that H-F2-1-R is validated or canonical;
- that A6 is resolved;
- that the global corpus manifest is clean;
- that T-0056 provenance is repaired;
- that the four advisory proposals are adopted;
- that the result is generalizable beyond the released scope.

It is **governance authorization to perform the experiment, not scientific validation.** It does not decide batch-3 certification, C-5 / RC-H-04, T-B, or any phase boundary.

## L0-DEC-32 — R-39 targeted evidence RELEASED under an L0 exception (no new RRC), two pinned objects · **DECIDED** (2026-09-26, appended)

**The human's acts** (explicit L0, 2026-09-26): the instruction *"The next and only evidence target is R-39 … Treat this as a new L0-authorized targeted evidence release"*, then structured answers to the object/RRC questions: **"Rulings log + module"** and **"L0 exception (Recommended)"**. **Human L0 decision, not AI-authored authority.** ⚠️ Attestation: UNVERIFIED (AI transcription). Preparation: F-lane F-LOG-0050 (localization and pre-registered outcomes).

| | |
|---|---|
| **Reason** | after T-A closed INCONCLUSIVE (F-LOG-0049), both blind T-A readers independently identified the ES-006 pointer *"ADOPTED via explicit DA early-promotion exception R-39"* as a live possible A6 counterexample. Only the pointer had been released |
| **OQ-6 scope** | **IN**, for this targeted read only (both objects are outside the canonical corpus) |
| ✅ **Released objects** (complete reads, by immutable object) | (1) `668cc7b22:engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md`, blob `70042f210f3b5498f7f89a4d93258a1dd05fd910`, sha256 `5bb384af65cc5d8b19760ef168e7d5d3a9db9a67e75a148e16ad4445f080b005` · (2) `668cc7b22:engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md`, blob `69c6679d3e3a02edcd96e25a3f0e4ec6db53ca15`, sha256 `45780fd5dfe920a98f7260e94ad5b06e22029b45b0ed3758002c8462c41948b9` |
| **RRC exception** | a new Research Release Check is **waived by explicit L0 exception**: the control state is unchanged since RRC-02 (control-file hashes identical, re-verified at read time) and the scope is two pinned objects. Entry obligation: door `CLEAR` and both object hashes verified before reading. Any mismatch means STOP |
| ⛔ **Not released** | any other version of these files · CAP-001 · A2e and A5e material · every other file, including any source the objects point to (**a pointer means STOP and a new request**) |
| **Limits** | no change to H-F2-1-R, the axioms, D1/D3; no T-B; no ML; no canonicalization. The outcome is classified only into the pre-registered categories (F-LOG-0050) |
| **Validity** | single use; void on any hash mismatch |
