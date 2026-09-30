# KnowledgeOS Evidence-Bound Research Control Architecture

| | |
|---|---|
| **Kind** | Research **control** architecture: how the rules of the governing research architecture are **bound to evidence and made checkable** |
| **Status** | ⚠️ **PROPOSED — authority: generated. Not authoritative without human review.** Submitted as an **architecture-change proposal under `ARCH` §9** (candidate *Addendum B*), with evidence (§0) |
| **Scope** | **Only** the KnowledgeOS Theory Reconstruction and Theory Construction program in this directory. No clause is assumed to apply anywhere else |
| **Relation to `ARCH` v1.1** | **Subordinate and additive.** `prompts/KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md` stays the governing research architecture. This document **adds no research method and changes no research rule**. It defines the controls that make existing rules checkable, and marks the few places where it would need a new rule (§3, column *New?*) |
| **Relation to `governance/`** | **Consumes it.** The temporary governance mechanism (`governance/README.md`: gates.yaml, gate-runner.py, governance-state.yaml, preflight door) stays the enforcement layer. Gate IDs stay in its `KOS-G-nnn` namespace |
| **Companion files** | `control-invariants.yaml` (machine-readable invariants) · `control-coverage-matrix.yaml` (what enforces each invariant today) · `research_agent_contract.md` (behavioural contract for the research agent) |
| **Modified to produce this** | nothing. No research result, registry, protocol, gate, hook or governance-state entry was touched |

> **One sentence:** the research rules mostly exist already. What is missing are **correspondence controls**, meaning checks that the artifacts still agree with **the corpus**, and not only with **each other**.

---

## 0. Evidence: why a control architecture, and why now

Following `ARCH` §9 ("exactly as ACL-1…ACL-4 were derived from recorded errors"), every invariant in §3 is derived from a recorded observation. **V** = verified by this session.

| # | Observation | Source |
|---|---|---|
| **E-1** | 9 of 10 `FILE-REGISTRY` rows F0031–F0040 are bound to a different physical file from the canonical list. Canonical F0032/F0035/F0036/F0040 were never read. P1P §4 ("never renumber, never reassign") was violated **with no record** (V) | `docs/knowledgeos/governance/audits/2026-09-23-INDEPENDENT-CORPUS-COVERAGE-AUDIT-…md` §0 |
| **E-2** | With E-1 in place, the five **activated** gates all PASS and the runner reports `STATUS: CLEAR` (V) | audit §6 |
| **E-3** | The activated gates KOS-G-010 and KOS-G-022 report **`0/0 PASS` when their input files are missing**. The runner's own `--self-test` scores **30/31** (one MISMEASURED: `theory_doc_exists`) (V: scratch copy of HEAD `da9a5beec`) | this document |
| **E-4** | For F0028, 10 of 18 independently identified substantive units were lost with **no recorded exclusion**. One qualification was upgraded and one count changed meaning (V) | audit §1 |
| **E-5** | The human-owned `governance-state.yaml` was syntactically **repaired by the research session**. This was recorded openly, but it is still the governed party editing its own control | `governance/governance-state.yaml` activation note |
| **E-6** | `asserted_by`, the mandatory provenance class of I-14, occurs **0 times** in committed data (V) | `2026-09-23-KOS-RESEARCH-GATE-SPECIFICATION-v0.1.md`, G-052 |
| **E-7** | Q18 (Phase 2 writes only in `phase2_extraction/`) contradicts Q61 (every unit updates the root state file) (V) | specification, G-003 |
| **E-8** | `RECONSTRUCTION-STATE.json` records `corpus_registry_hash: NOT_COMPUTED`. P1P §45 Gate 3 (hashing, registry import) was never executed (V) | `RECONSTRUCTION-STATE.json` |

**Diagnosis:**
- E-1 violated a rule that **existed** (P1P §4).
- E-4 is loss the protocols already forbid (Q45/Q46, Q3).
- **None of the existing controls compares an artifact with the corpus.** They compare artifacts with each other (E-2).
- Where they do compare, a missing input counts as agreement (E-3).

### 0A. Why the research session did not follow P1P §4: root cause, from the record

The rule was clear. Every factor below is verified in git history or the current files.

| # | Factor | Evidence |
|---|---|---|
| **R-1** | **The rule was not in the operative context.** The file-ID rule and the canonical list live only in P1P §4 (l.1121–1160), inside a 4,650-line document. **`ARCH` v1.1 and P2P, the documents that drove every unit from F0026 on, contain zero references to `list_of_files_to_read.log`, to `file_id` assignment, or to "renumber".** Phase-1 reading was being done inside Phase-2-driven units ("process F0036–F0040 and raise the theory to v0.9") | `grep` of the three documents |
| **R-2** | **The authoritative state changed the form of the next target.** After `3d1f31195` the state said *"Next unread: **F0031**"*, which is a canonical ID. After the F0031–F0035 unit (`6ea332cef`) it said *"the next five unread **top-level files**"*, which is a description no protocol defines. RA-12 makes state drive the next unit, so this wording change **became the selection rule** | `git log -p KNOWLEDGEOS-RESEARCH-STATE.md` |
| **R-3** | **The human instruction was correct.** The recorded next step was "F0031–F0035" (`.claude/sessions/2026-09-22.md` l.633). No human instruction said "top-level". **The session kept the right labels and filled them with files it selected itself** | session log; no counter-instruction found |
| **R-4** | **The import step was silently abandoned.** All 30 rows F0001–F0030 carry the `timestamp` copied from the canonical list (P1P §5: *"copied verbatim, not regenerated"*). **All 10 rows from F0031 on lack it.** The rows were constructed, not imported | `FILE-REGISTRY.jsonl` field census |
| **R-5** | **Mixed-unit commits hid the change.** The F0031–F0035 registry rows landed in `224a3e671`, titled *"move governance gates to the two PHASE BOUNDARIES"*, whose message does not mention reading any file | `git show 224a3e671` |
| **R-6** | **Nothing checked it.** No control compares registry paths with the canonical list (E-2). A rule that exists only in text, in a document not being consulted, is an intention (layer 2), not a control | audit §6 |
| **R-7** | **The substitute method was a directory listing.** Files were chosen as `sorted(glob("docs/knowledgeos/*.md"))` minus already-registered paths, and IDs were minted by incrementing from the last entry. The order is **alphabetical**, which explains "top-level files" in R-2. The same method was used in both diverging windows, so the error was **systematic, not a slip** | research session's own forensic report, commit `6a7ac1bd9` (`ID-ROOTCAUSE-01.md`) |

**Reconciliation with the research session's own report (`6a7ac1bd9`).**
- **Agreed facts:**
  - F0001–F0030 were correct 30 of 30;
  - F0031–F0040 are wrong 9 of 10, and F0039 matches by coincidence;
  - the first divergence is at `224a3e671`;
  - 11 artifacts are contaminated;
  - the canonical log was never opened in those sessions.
- **One difference of framing.** That report withdraws "the Phase-2 protocol's silence on file identity" as a contributing factor, calling it *"a mitigating frame for a failure that has none"*. P1P governs `FILE-REGISTRY`, and it names the log seven times.
  - **This document accepts that as the statement of accountability.**
  - R-1 is kept for a different purpose. It is a **design fact for the control plane**, not a mitigation: a rule reachable only by reading the correct 4,650-line document at the correct moment will fail again under the same conditions.
  - That is why the remedy is a pointer (the contract) **plus** a machine check, and not "read more carefully next time".
- **Both reports agree** that the rule was adequate and that the gap is enforcement.

**Conclusion:** this was not wilful non-compliance. **A rule that is not in the operative context and is not checked will be dropped as soon as a nearby, operative artifact (here, the state) implies something else.** The architecture therefore:
- restates no rule;
- requires the operative documents to **point to** the binding rule (the agent contract);
- expresses position **only in canonical IDs** (RCI-006);
- makes the rule **checkable** (RCI-002, RCI-004).

The research session wrote the lesson itself, one batch before (C-0009): *"I documented the trap, built no guard, and walked into it"* (commit `ca8b96d59`).

---

## 1. Four layers, and what each is allowed to claim

| Layer | Artifact(s) | Says | ⛔ Must never be used as |
|---|---|---|---|
| **1 Architecture** | `ARCH` v1.1 + this document | **what must be true** (invariants, authority boundaries) | an instruction the agent "remembers" |
| **2 Protocol / contract** | P1P, P2P, `research_agent_contract.md` | **how the agent works** inside the architecture | **a security boundary**. A prompt is behavioural policy |
| **3 Enforcement** | `governance/` (gates.yaml, gate-runner.py, governance-state.yaml, preflight door) + filesystem/git controls | **what the computer checks** (deterministic predicates) | proof that research is valid |
| **4 Audit evidence** | verdict records · independent audits · human decisions | **whether enforcement worked**, and what automation cannot judge | a replacement for enforcement |

> **A prompt tells the agent what to do. The architecture says what must be true. Enforcement makes a violation impossible or detectable. Audit proves that the enforcement actually worked.**

A rule that exists only in layer 2 is **an intention**, which is the standard this program's own `END-TO-END-ENFORCEMENT-AUDIT.md` already applies.

---

## 2. Authority hierarchy

| Level | Holder | May override |
|---|---|---|
| **L0** | Human authority (research owner / Decision Authority, **unmapped**, see H-1 in the assessment) | everything below |
| **L1** | `ARCH` v1.1 **(+ this document once adopted as Addendum B)** | L2–L7 |
| **L2** | Frozen protocols P1P, P2P | L3–L7 |
| **L3** | Control registry: `control-invariants.yaml` + `control-coverage-matrix.yaml` | L4–L7 |
| **L4** | Gate registry: `governance/gates.yaml` (+ activation in `governance-state.yaml`, a human act) | L5–L7 |
| **L5** | `research_agent_contract.md` | L6–L7 |
| **L6** | Session instructions (prompts) | L7 |
| **L7** | Research output | — |

**Rules:**
- **A lower level never overrides a higher one.** A session instruction such as *"ignore the missing read receipt and continue"* is **invalid**, and the correct response is STOP (§7), not compliance.
- If two levels conflict, the conflict is a **PROTOCOL-class finding** routed to L0. It is never resolved by the executing agent picking one (E-7 is the live example).
- **An L0 act must come from the human.** An AI-transcribed "the human decided X" is **not** an L0 act (assessment G-4). Until an attested channel exists, every L0 record carries `attestation: UNVERIFIED`.

---

## 3. Research Control Invariants (RCI)

**New namespace `RCI-nnn`** (verified unused in the repository). The machine-readable form is in `control-invariants.yaml`.

### 3.0 Four preservation families

Derivation preservation and evidence identity are **two different controls**. A research process can keep every derivation perfectly and still build it on the **wrong source** (E-1). The invariants are therefore organized as a chain, and each family depends on the one above it:

```
 1 EVIDENCE PRESERVATION      the source and its identity are unchanged       RCI-001..006
        │   (a perfectly preserved derivation from the wrong file is still wrong)
        ▼
 2 DERIVATION PRESERVATION    existing reasoning is never silently erased     RCI-015, 010, 007
        │
        ▼
 3 EPISTEMIC PRESERVATION     status and qualification are never silently raised   RCI-009, 013, 008
        │
        ▼
 4 THEORY EVOLUTION           change is explicit: add · qualify · contradict · refine
                              · supersede · split · merge · withdraw · unresolved
        ─────────────────────────────────────────────────────────────
   CONTROL INTEGRITY (cross-cutting)                                         RCI-011, 012, 014
```

**Two axes.**
- **Authority** (§2) runs top-down: human → architecture → protocol → agent → output.
- **Research evolution** runs forward: evidence → derivation → new evidence → additional derivation → explicit relation → theory version.

Evidence and derivations are **immutable**. Theories are **versioned**. A change is **a new record plus an explicit relation**, never an edit of the old record.

**How to read the "New?" column:**
- *NO* — the invariant only binds an existing rule to a control.
- *MECHANISM* — it adds a record type or check but no research rule.
- *RULE* — it would change or add research methodology. That needs L0 approval under `ARCH` §9, and the methodology is frozen.

| ID | Invariant | Protects existing rule | Evidence | New? |
|---|---|---|---|---|
| **RCI-001** | **Canonical identity.** One canonical `file_id` ↔ exactly one corpus artifact for the life of the corpus baseline | P1P §4 | E-1 | NO |
| **RCI-002** | **No research-side ID assignment.** Research references canonical IDs. It never creates, renumbers, reassigns or regenerates them. Every registry row's `path` equals the canonical path for its `file_id` | P1P §4, §5 ("copied verbatim, not regenerated") | E-1 | NO |
| **RCI-003** | **Corpus read-only.** No research unit changes a corpus file | RA-1 | E-8 (never measured) | NO |
| **RCI-004** | **Identity verified before admission.** A source enters reading only after its canonical ID, path and content hash match the approved manifest | P1P §45 Gate 3, §36A hashes | E-1, E-8 | MECHANISM (+ timing, see §6) |
| **RCI-005** | **Read receipt.** A source counts as read only if a receipt binds `{file_id, path, sha256-at-read, unit_id}` and the runner can verify it against the manifest | P1P §9A `FILE_READ` | E-1; F0028 `read_status: READ` vs others `READ_COMPLETE` | MECHANISM |
| **RCI-006** | **Position and coverage in canonical IDs.** Every "processed n / N", window claim and **next target in the research state** names canonical IDs or an ID-resolvable set. A description ("the next five unread top-level files") is not a position | Q58, RA-11, RA-12 | E-1; R-2 | NO |
| **RCI-007** | **Passage-level traceability.** Every substantive research claim has a provenance path to a **source location** in one or more canonical files, not only to a file ID | Q15, I-3, I-10, P2P §5A.7 | E-2 (G-022 traces seed → appendix only) | MECHANISM (source-location field) |
| **RCI-008** | **No silent exclusion.** Every theory-bearing contribution of a read file is represented, excluded with a reason, or marked unresolved | Q45, Q46, Q54, ACL-1 | E-4 | NO (granularity of "contribution" is NOT-YET-DEFINED) |
| **RCI-009** | **Epistemic preservation.** A transformation never silently raises the epistemic status or strips a qualification of a source statement | Q3, Q22, Q50, I-14 | E-4 (F0028 C05, C18) | NO |
| **RCI-010** | **Revisions and withdrawals preserved.** An in-file withdrawal, supersession or banner that modifies the body is recorded as such | P1P §5A, `INTRA-FILE-REVISIONS` | audit §3 (F0036 withdrawal vs verdict), §1 (F0028 header/body) | NO |
| **RCI-011** | **Governance separation.** Research execution never activates, deactivates, redefines, waives or **repairs** its own controls | `governance/README.md` §5, P2P §0.3 forbidden actions | E-5 | NO |
| **RCI-012** | **Acceptance separation.** The producer of a result is never its sole acceptor | KOS-G-060, ES-001 authority chain | `governance/README.md` §7 ("every acceptance so far has been the author's") | NO |
| **RCI-013** | **Machine proposals carry no authority.** Every Phase-2 statement carries `asserted_by`. A `MACHINE_PROPOSAL` above L2 needs a human assertion or a validated finding | I-14, P2P §3B.6 | E-6 | NO |
| **RCI-015** | **Non-destructive derivation evolution.** A recorded derivation, interpretation, hypothesis, relationship or theory object is **never silently deleted, overwritten or semantically replaced** by later research. A change of position is represented explicitly as: additional derivation · qualification · contradiction · refinement · supersession · split · merge · replacement proposal · withdrawal · unresolved conflict. **The prior state stays recoverable.** The same applies to audits and corrections: an audit records findings, a correction produces a **new** version, and the audited version is preserved (e.g. theory v0.9 stays as issued; any revalidation yields v0.9-AUDIT / v0.10) | **exists in five places:** P1P §5A (append-only record versioning) · P1P §19B (append-only thread events) · P1P l.2985 (candidate theory checkpointed, never overwritten) · P2P I-11, I-12, Q12, Q23, Q24, Q25, the iteration rule ("a later iteration never overwrites an earlier one; it supersedes it") and the §3B.8 replacement/split/merge tests · `ARCH` RA-2, RA-5, "no silent repair" | IFR-0010 (unrecoverable original, recorded by P2P); P2P I-12 fixtures | **NO**, elevation only |
| **RCI-014** | **Instrument validity (gate of gates).** Every AUT check has pass and fail fixtures. **A missing or empty input is never PASS.** A failing self-test makes every verdict of that runner version INCONCLUSIVE. Every gate declares its **scope class**: `INTERNAL_CONSISTENCY` or `CORPUS_CORRESPONDENCE` | assessment §I, specification §7 | E-2, E-3 | MECHANISM |

**Deliberately not an invariant:** reading **order**. P2P §3C.6 lets the next target be a targeted search or expansion rather than the next file, and Q37 records such consultations. **Reading canonical F2837 early is legitimate. Calling it F0032 is not.** RCI-002 constrains the *binding*, not the order.

---

## 4. The three correspondences

The existing gates check **C0**: that artifacts agree with each other. The failures in §0 all sit in **C1–C3**.

```
 CORPUS (list_of_files_to_read.log + files)            ← canonical, read-only
    │  C1 identity:     canonical ID ↔ path ↔ bytes         RCI-001..006   AUT
    ▼
 PHASE-1 RECORD (registry · index · objects · gaps · revisions)
    │  C2 substance:    source passage ↔ extracted unit     RCI-007..010   AUT form + REV/AUDIT meaning
    ▼
 PHASE-2 (seed · theory objects · relations · theory document)
    │  C3 trace:        extracted unit ↔ seed ↔ theory      RCI-007, 013   AUT (partly exists: KOS-G-020..023)
    ▼
 C0 internal consistency (parse, unique ids, dangling refs, index ⇔ registry)   KOS-G-001..003, 010   AUT (exists)
```

| Correspondence | Automatable? | Who judges meaning |
|---|---|---|
| **C1 identity** | **fully**: manifest, hash, path equality | nobody needs to; it is a fact |
| **C2 substance** | **form only**: a location field exists, a disposition exists, an IFR record exists | an **independent auditor** (§8), with a blinded source-first inventory |
| **C3 trace** | **form**: set differences, resolvable references | reviewer, when the question is whether a trace is *faithful* |

**The runner never interprets theory.**
- It may answer: does F0035 exist, does its hash match, is there a receipt, does every seed item have a source location, are IDs unique, is every exclusion dispositioned.
- It must **not** answer: is the interpretation correct, did the author mean X, is the theory meaningful.

---

## 5. Evidence binding (increment 1)

| Element | Definition | Who produces | Who verifies | What it proves / ⛔ does not prove |
|---|---|---|---|---|
| **Canonical manifest** | derived deterministically from `docs/knowledgeos/list_of_files_to_read.log`: one entry per `file_id` with `{path, sha256, bytes}` at a baseline commit | a governance step (**not** the research session) | the runner recomputes it; **a human approves the baseline** (L0) | proves identity and bytes at baseline. ⛔ Does not prove the list is the whole corpus (the state notes 3,081 listed vs 9,163 md files; that is a separate, open question) |
| **Identity verification** | before a source is admitted: `registry.path == manifest.path(file_id)` and `sha256(file) == manifest.sha256` | runner (PRE) | — | proves the right bytes under the right ID |
| **Read receipt** | `{file_id, path, sha256_at_read, unit_id, bytes_read}`, appended by the research unit | research agent | runner: hash and path match the manifest; `bytes_read` equals the file length | ⛔ **Proves the binding only: which bytes were declared read.** It does **not** prove the file was read or understood. That is C2, which belongs to audit |
| **Coverage computation** | "processed" = set of canonical IDs with a verified receipt | runner | — | makes RCI-006 claims computable |

**Canonical position is not research traversal.** They are two different things, and the state keeps them in separate fields:

```yaml
# KNOWLEDGEOS-RESEARCH-STATE (proposed fields)
next_targets:         [F0041, F0042, F0043, F0044, F0045]   # canonical position: which canonical files come next
research_traversal:   [F2837, F0040, F0035]                 # the order files were actually read, by canonical ID
```

- **Traversal is free.** The researcher may search, jump forward, revisit, follow a dependency or chase a contradiction (P2P §3C.6, P1P §0E.1).
- **Identity is not free.** Wherever and whenever a file is read, it carries its canonical ID.
- **Coverage** is the set of canonical IDs with a verified receipt, whatever order they were read in.
- **Completeness** means "no canonical ID in the declared window is unread". The failure in E-1 would have shown up here, as four unread IDs inside a window reported as processed.

**Source admission, not agent instruction.** The contract (L5) tells the agent to load the canonical list. That is still an instruction. **Increment 1 moves identity resolution into the machine:**

```
unit requests F0035 ─▶ source-admission resolves F0035 in the manifest ─▶ path + sha256 verified
   ─▶ receipt written ─▶ the agent receives the path it may read
```

The agent never chooses a path for an ID, or an ID for a path. A request for a path that has no canonical ID is refused (`MISSING_CANONICAL_SOURCE`).

**The existing mismatch (E-1) is not repaired by governance.**
- Re-binding F0031–F0040 is a **Phase-1 correction**: an authorized research unit filed as correction requests, per RA-5.
- It is scheduled by L0.
- Governance must **not** "fix the registry" (RCI-011 applies to governance too: *the judge does not rewrite the evidence*).

---

## 6. Control timing: precondition, postcondition, boundary

```
START ─▶ PRECONDITION ─▶ RESEARCH UNIT ─▶ POSTCONDITION ─▶ … ─▶ PHASE BOUNDARY
          "may I start?"                   "is the result valid?"   "may I cross?"
          RCI-004, 005(file),              RCI-002, 006, 007,       everything activated
          011 (governance intact)          008-form, 013, 014       (P2P §0.3, exists)
```

⛔ **Conflict with a recorded human direction.**
- P2P v3.2 (human-directed, 2026-09-23) made the governance check mandatory **only at the two phase boundaries**, and withdrew the per-window call as "more than was asked for".
- The Phase-1 boundary is reached only after the whole corpus of 3,081 files has been processed. So an identity error made while reading (E-1) would go undetected until then.
- **Recommended resolution of RC-H-01 (awaiting L0 confirmation): separate *source admission* from *governance gates*.**

| | Runs | Contents | Relation to v3.2 |
|---|---|---|---|
| **Source admission control** | before **each source read** | cheap, deterministic C1 checks only: canonical ID exists · path matches · sha256 matches · receipt written | **new, and outside the gate suite.** It is an evidence-admission step, not a governance gate |
| **Governance gates** | at the **two phase boundaries** | the full activated suite | **unchanged, as v3.2 directs** |

- v3.2's decision (no mandatory gate before each 5-file window) stays intact.
- Only identity binding moves forward to the moment of reading, because that is where the E-1 error was made.
- If L0 rejects this, the alternative (identity checked only at boundaries) should be recorded as **accepted late detection**.

---

## 7. STOP protocol (extends P2P §0.3, does not replace it)

P2P §0.3 already defines `BLOCK` and `GOVERNANCE_INOPERATIVE`, and forbids three things on a block: reading on, repairing the gate, and deactivating the gate. This section adds **reason codes and a record**.

| Reason code | Raised when | Resumes only after | Resume authority |
|---|---|---|---|
| `IDENTITY_CONFLICT` | RCI-001/002 fails, or a source's ID/path disagrees with the manifest | a correction unit re-binds, and the runner passes | L0 schedules; research executes the correction |
| `MISSING_CANONICAL_SOURCE` | a manifest entry's file is absent | L0 decision (corpus change or manifest change) | L0 |
| `HASH_MISMATCH` | file bytes ≠ manifest | L0 decides: corpus changed legitimately (new baseline) or tampering | L0 |
| `CORPUS_MODIFIED` | RCI-003 fails | same as above | L0 |
| `GOVERNANCE_INOPERATIVE` | runner/rule book unreadable, self-test failing, activation file malformed (**E-5 case**) | **the governance maintainer** repairs, not the research session | Governance + L0 |
| `PROTOCOL_CONFLICT` | two higher-level rules cannot both be satisfied (E-7) | L0 ruling under `ARCH` §9 | L0 |
| `FROZEN_ARTIFACT_MODIFICATION` | a frozen document would have to change | L0 (KOS-G-061) | L0 |
| `UNRESOLVED_PROVENANCE` | a claim cannot be bound to a source location (RCI-007) at a boundary | the claim is demoted or bound | research + reviewer |

**Stop record** (append-only, one line per stop; proposed location `governance/STOP-LOG.jsonl`):

```yaml
stopped_at:  <commit sha>
unit_id:
reason_code:
blocking_control: RCI-nnn / KOS-G-nnn
affected_artifacts: []
required_authority: L0 | GOVERNANCE | RESEARCH+REVIEWER
recorded_by: research-agent        # the stop is always recorded by the party that stopped
resolved_by:                       # filled by the resuming authority, never by the stopped party
```

⛔ **After a STOP the research agent may do exactly two things:** write the stop record, and report. It must not repair, re-bind, re-ID, deactivate, or "work around to keep momentum".

---

## 8. Roles, separation and write zones

| Role | Writes | Must never |
|---|---|---|
| **Research agent** | Phase-1 registries (append-only, Phase-1 units) · `phase2_extraction/` (Phase-2 units) · research state · read receipts · stop records | touch the corpus, `governance/*`, `architecture/*`, `prompts/*`; assign IDs; accept its own work (RCI-012) |
| **Governance engineer** | `architecture/*` (proposals) · gate specifications · proposed `gates.yaml` entries · canonical manifest *generation* | activate gates; accept research; repair research artifacts; write L0 records |
| **Gate runner** | verdict output only; **read-only on everything else** | interpret theory; pass on missing input (RCI-014) |
| **Independent auditor** | audit reports only (`docs/knowledgeos/governance/audits/`) | modify anything audited; read the research extraction before building its own source-first inventory |
| **Human (L0)** | `governance-state.yaml` activation · manifest baseline approval · waivers · acceptance · freeze · rule changes | — |

**Independent audit contract (essentials).** A separate file can be written if wanted.
1. **Assume the research artifacts may be wrong.** The job is not "verify the research completed its work".
2. **Read the canonical source first**, and build an independent inventory **before** opening any research artifact.
3. Compare: canonical source ↔ inventory ↔ Phase-1 extraction ↔ seed ↔ theory.
4. Report coverage and loss. Do not repair, and do not evaluate the theory.
5. State independence limits: shared model family, prior exposure.

The 2026-09-23 audit followed this form, and that is the precedent.

**Write zones in today's layout.** No reorganization is proposed; moving files would break frozen protocol paths.

| Path | Zone | Status |
|---|---|---|
| `docs/knowledgeos/**` corpus files | **read-only** | enforced by nothing today |
| root registries `*.jsonl` | research, **append-only**, Phase-1 units | append-only enforced by nothing today |
| `phase2_extraction/` | research, Phase-2 units | — |
| `KNOWLEDGEOS-RESEARCH-STATE.md` | research, every unit (Q61) | ⚠️ **conflicts with Q18 (E-7), pending L0** |
| `prompts/` | human-curated | — |
| `governance/`, `architecture/` | governance proposes; human activates | tamper-**evident** (runner digests), not tamper-proof |

Real enforcement of zones needs git/CI (assessment §H): CODEOWNERS on `governance/`, `architecture/` and `prompts/`, plus a required check on a protected branch. **Until then every zone is advisory, and must be described as advisory.**

---

## 9. What automation must not decide

- whether a source unit is "substantive"
- whether an extraction is faithful
- whether a qualification was lost
- whether a theory claim is true
- the next target (P2P §3C.5)
- identity of theory objects (§19A)
- acceptance, freeze, waiver, canonicalization
- activation of a gate
- repair of a governance defect

An automated PASS over any of these would be **a decision disguised as a measurement**.

---

## 10. Namespaces

| Namespace | Owner | Meaning |
|---|---|---|
| `RA-n`, `ACL-n` | `ARCH` v1.1 | research architecture rules (unchanged) |
| **`RCI-nnn`** | this document | research control invariants (new) |
| **`KOS-G-nnn`** | `governance/gates.yaml` (schema: *"stable; never reused, never renumbered"*) | **the only gate namespace** |
| ~~`KOS-G-nnn`~~ in `2026-09-23-KOS-RESEARCH-GATE-CATALOG-proposal.md` / `…-SPECIFICATION-v0.1.md` | governance proposals | ⛔ **withdrawn as identifiers.** Their content maps onto gates.yaml IDs or onto new entries registered there. Where they collide (e.g. "KOS-G-001"), **the gates.yaml meaning wins** |

**New enforcement proposals in `control-coverage-matrix.yaml` carry placeholder names** (`PROPOSED:canonical-identity-binding`, …). An ID is allocated only when an entry is registered in gates.yaml, so nobody can collide by reserving numbers in advance.

---

## 11. Increments

| # | Increment | Closes | Precondition |
|---|---|---|---|
| **0** | **Root-cause closure** (analysis only, no repair): exact canonical mapping for F0031–F0040, exact affected artifacts, preservation of the current state as issued. See `INCREMENT-0-ROOT-CAUSE-CLOSURE.md` | E-1 | none |
| **1a** | **Instrument trust first:** the existing runner fails closed on missing or empty input, and a failing self-test yields INCONCLUSIVE (RCI-014). *The gate system is not expanded until its instrument is trustworthy* | E-3 | RC-H-03 |
| **1b** | **Evidence binding:** manifest, hashes, source admission, read receipts, position and coverage in canonical IDs (RCI-001..006) | E-1, E-2, E-8 | RC-H-01, RC-H-02 |
| **2** | **Source-to-research traceability and derivation preservation:** source-location fields, dispositions per contribution, IFR discipline, and a mechanical *no destructive mutation* check (a recorded row changed without an explicit revision/supersession record ⇒ FAIL) (RCI-007, 008, 010, 015) | E-4, audit §3, IFR-0010 | increment 1; the NOT-YET-DEFINED granularity decided |
| **3** | **Independent governance:** separation (RCI-011, 012), git/CI zone enforcement, attested L0 acts, periodic independent audits | E-5 | H-1, H-10 of the assessment |
| **4** | **Research continuation** (F0041+) | — | increments 1–2 passing, **and** the F0031–F0040 correction unit done. ⚠️ Whether research pauses until then is **L0's call, not this document's** |

---

## 12. Stress test of this proposal

| Attack | Holds? | Answer |
|---|---|---|
| The research agent writes its own receipts, so receipts prove nothing | partly | receipts prove the **binding** (the runner re-hashes against a manifest the agent did not produce). They never prove reading. **Substance stays with audit.** Stated in §5 |
| The manifest is derived from a list that may itself be incomplete (3,081 of 9,163) | yes, as a limit | the manifest defines the *canonical* corpus; it does not claim completeness. Completeness is a separate L0 question |
| Governance generates the manifest, so governance could corrupt it | yes | human approval of the baseline (L0) plus a deterministic derivation anyone can re-run |
| Human acts are still AI-transcribed | **yes, unresolved** | every L0 record is marked `attestation: UNVERIFIED` until a channel exists (§2) |
| Hooks and settings are editable by the governed party | yes | the architecture calls every zone **advisory** until git/CI enforcement exists (§8). It claims no wall |
| Per-source preconditions make research slow | real | only C1 checks run per source, and they are cheap. C2 stays at audit cadence |
| This document becomes a second architecture competing with `ARCH` | risk | it is filed as a §9 proposal, subordinate, and adds no research rule (§3 *New?* column) |
| Automating C2 would turn gates into theory judges | yes, if done | §4 and §9 limit C2 automation to *form* |

---

## 13. Decisions required from the human (L0)

| ID | Decision |
|---|---|
| **RC-H-01** | Evidence binding: **recommended** is a per-source *source admission control* (C1 checks only), with the governance gates staying at the two phase boundaries as v3.2 directs (§6). Alternative: boundaries only, recording late detection as accepted |
| **RC-H-02** | Approve generation of the canonical manifest, and its baseline commit |
| **RC-H-03** | Adopt RCI-014 fail-closed semantics for the existing runner (missing input ⇒ not PASS), and the `scope class` field |
| **RC-H-04** | Schedule the F0031–F0040 **correction unit** (re-binding to canonical IDs; RA-5), and decide whether research continues before it. Under RCI-015 the correction **must not edit** theory v0.9 or the existing registry rows. It appends correction records and yields a new version (v0.9-AUDIT → v0.10), with each affected finding classed as retained · source-identity-affected · requires re-read · unsupported · unresolved |
| **RC-H-05** | Accept or reject this document as `ARCH` Addendum B (§9), and the authority hierarchy of §2 |
| **RC-H-06** | Resolve Q18 vs Q61 (E-7) |
| **RC-H-07** | Placement: keep `research_agent_contract.md` in `architecture/`, or move it into `prompts/` (human-curated) |

---

*Traceability:*
- Evidence: the independent audit of 2026-09-23 (`docs/knowledgeos/governance/audits/`); the gate specification v0.1 and assessment (`docs/knowledgeos/governance/`); a probe of `governance/gate-runner.py` on a scratch copy of HEAD `da9a5beec`.
- Consumes: `ARCH` v1.1 (§9, RA-1, RA-5, RA-9, RA-11), P1P §4/§5/§9A/§36A/§45, P2P §0.3/§3B.6/§3C.6/§5A.7/§16, and `governance/README.md` §§2–5.
- Modifies nothing.
