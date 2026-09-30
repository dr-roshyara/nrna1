# F-SERIES v1.3 — DD-1…DD-4 ARCHITECTURE REVIEW (design review only)

**Status: REVIEW — no implementation, no decision taken on the human's behalf (F-LOG-0013).** v1.2 stays frozen and not
approved. No code changed. F3082 stays at READ-COMPLETE. DD-1…DD-4 are analysed here, **not resolved**.

**Commissions:**
- the human's instruction of 2026-09-25 (senior architecture and epistemic review of DD-1…DD-4);
- `prompts/prompt_2.md` (F-Series v1.3 architecture conformance audit);
- `prompts/prompt_v1.0.md`. *Relationship:* `prompt_v1.0.md` (22:12) asked for a **new** research architecture.
  `prompt_2.md` (22:15) and the current instruction supersede it: *"DO NOT CREATE A NEW RESEARCH ARCHITECTURE … it
  already exists and is frozen."* So `prompt_v1.0.md`'s deliverable is **not** produced. Its content (feedback record
  fields, terminology namespaces, the F-Series role) is used here only where the frozen architecture already contains
  it. `prompt_2.md`'s audit structure A–N is answered in **Annex A**, so that one document serves both commissions, as
  instructed.

**Governing sources read** (read-only; the third lane `docs/knowledgeos/knowledgeos_theory_chronological_extraction/`):

| Source | Status | Cited as |
|---|---|---|
| `prompts/KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md` | ⛔ FROZEN v1.2 | **ARCH §n**, `RA-n`, `ACL-n` |
| `prompts/knowledge_os_protocoll.md` (Master Protocol, Phase 1) | governing Phase-1 protocol | **MP §n** |
| `prompts/knowledge_os_step2_theory_construction_protocol.md` | DRAFT v3.3, Phase 2 | **S2 §n** |
| `KNOWLEDGEOS-RESEARCH-STATE.md` | authoritative execution position (`RA-11`) | **STATE** |
| `governance/` (`gates.yaml`, `gate-runner.py`, `governance-state.yaml`) | the control plane (`RA-16`) | **GOV** |

---

## 1. Executive assessment

**The v1.3 design is internally coherent, but architecturally NON-CONFORMANT in its current scope.** The
non-conformance is not in DD-1…DD-4 themselves. It is upstream of them, and it changes what DD-1…DD-4 are about.

1. **F-Series has become a second Phase-1 protocol.**
   - The frozen architecture has **one** Phase-1 protocol, the Master Protocol (ARCH header "Binding"; STATE
     "Phase-1 protocol").
   - The Master Protocol already defines the per-file reconstruction (MP §9, 30 steps), the File Dossier (§6), the
     Evidence Object (§6A), append-only record versioning with `previous_record_hash` (§5A), per-file quality gates
     (§9A), the Theory Discovery Index artifact and gate `P1-Q1`, ID namespaces (§4A) and the canonical F-ID registry
     (§4).
   - F-Series v1.0–v1.3 re-derives all of these from the S-Series methodology (v3.5 / XC / P3B), with its own record
     types, gates, state file and governance log. **No F-Series protocol version references the architecture or the
     Master Protocol.**
   - ARCH §9: *"A protocol that contradicts it is defective; the protocol changes, not the architecture."*
2. **F-Series performs Phase-2 work inside Phase 1.** Since v1.1, F-Series has done the following, all of which the
   architecture assigns to Phase 2 (ARCH §7; MP header table; MP "Phase-1 scope binding"):
   - generated Level-3 **hypotheses** with pre-registration;
   - planned **checkpoint tests** and statistical/ML confirmatory analysis;
   - produced Level-2 **domain interpretation** and external-theory comparison;
   - asked *"what could this imply for KnowledgeOS"* (v1.1 §F-1A).

   This came from the human direction of 2026-09-25 ("research-discovery programme"). That direction predates this
   review's reading of the frozen architecture, and **reconciling the two is a human decision** (§12, H-1).
3. **Level names collide (`RA-9`).** The F-Series "L1/L2/L3" (source / analysis / hypothesis) collides with the
   architecture's epistemic ladder L0…L5 (S2 §1: L0 historical evidence, L1 reconstruction, **L2 hypothesis**, L3
   derivation, L4 validation, L5 canonical). F-Series "L3 hypothesis" is the architecture's **L2**, which is a Phase-2
   level.
4. **The control plane is duplicated (`RA-16`).** F-Series built its own approval parser, human-reference scheme and
   decision records beside `governance/gates.yaml` / `gate-runner.py` / `governance-state.yaml`. `RA-16` states that
   building a second preflight layer beside the existing one *"would be an ACL-3 violation by this architecture's own
   rule."* Likewise, F-SERIES-STATE is a second execution-position record beside STATE (`RA-11`).
5. **The F-ID identity is outside the canonical registry.** MP §4: the canonical registry is
   `docs/knowledgeos/list_of_files_to_read.log`, and *"do not generate a second, competing File ID scheme."* F3082–F3108
   (27 IDs, minted in this session at the human's request) are not in it, and F-MANIFEST acts as a second registry.

**What is genuinely valuable and conformant in spirit.** These are *mechanisms* the Master Protocol states as rules
without enforcing them:
- the controlled reader with page coverage;
- the unit frame and content-inventory floor;
- evidence sealing by hash;
- GIT-CAS commits;
- the independent audit with a computed comparison;
- hash-bound human decisions;
- quarantine and isolation attestation.

MP §5A already demands append-only, hash-linked versioning. MP §9A already demands that a file is not complete merely
because it was read. The F-Series integrity layer could make those rules mechanical.

**Consequence for DD-1…DD-4.**
- DD-2 and DD-3 exist only because F-Series holds Phase-2 artifacts (L2/L3). Under a conformant scope, DD-2 reduces to
  a status question already answered by `RA-15`, and DD-3 largely disappears from Phase 1.
- DD-1 and DD-4 survive, but must be expressed in the architecture's terms: correction requests, MP §5A versions, and
  the `RA-16` control plane.

**IMPLEMENTATION BLOCKED** (§14).

---

## 2. Architecture conformance matrix

| Requirement | Frozen architecture / Step-2 source | v1.3 mechanism | Conforms? | Gap | Required action |
|---|---|---|---|---|---|
| Phase-1 evidence preservation | ARCH §3 layer 2, §7; `RA-1`, `RA-2` | reader + page coverage; unit frame; content inventory; EvidenceObject seal; GIT-CAS | **PARTIAL** | the preserved record is F-Series-specific (FCI items, XC contributions), not the MP File Reconstruction Record / Dossier / Evidence Object | map the F-Series artifacts onto MP §6/§6A/§9 records, or propose a protocol change (MP) with evidence |
| Historical provenance | ARCH §4.3 (shared kernel = identifiers + provenance); `RA-14` | page → unit → item → contribution chain, hash-bound | **YES (mechanism)** / **PARTIAL (vocabulary)** | its IDs (FCI/FAN/FRS) are not MP namespaces (MP §4A) | adopt the MP namespaces (`E`, `D`, `P`, `A`, `T`, `TH`, `G`, `C`…) |
| Chronology | MP §3 (typed date evidence; `historical_sequence_date` rule) | none; HDR-2 open; processing order ≠ historical order | **NO** | F-Series has no historical-sequence derivation; the MP already has one | consume the MP §3 rule; HDR-2 becomes the question of *applying* MP §3, not of inventing semantics |
| Epistemic separation | ARCH §2, §7; MP scope binding; S2 §1 ladder | F-Series L1/L2/L3 | **NO** | the L2/L3 content is Phase-2 work; the level names collide (`RA-9`) | remove F-Series L3 (hypotheses) and the confirmatory L2 from Phase 1; keep only MP-admitted Phase-1 outputs (records + labelled observation / `[E]` records with status `HYPOTHESIS`/`NOT_YET_ASSESSED`); rename the levels |
| ACL-1 (a classification is never a relevance filter) | ARCH §4.2; gate `Q54` (Phase 2) | no document-kind filter found; the provenance class PRIMARY/SECONDARY is recorded, not filtered on | **YES (no violation found)** | not *enforced*: nothing prevents a later filter | keep; add a test that no F-Series gate reads provenance / kind / folder as a condition |
| ACL-2 (category exists in corpus first) | ARCH §4.2; `Q38` + `Q53` | the 26 inventory categories are a closed list applied to the corpus; the C03 example seeds corpus-theory terms ("kernel", "invariant", "fixed point") | **PARTIAL / RISK** | the categories are generic (acceptable); the worked example primes theory vocabulary | replace the example with a theory-neutral one |
| ACL-3 (query before constructing) | ARCH §4.2; `Q53`; `RA-16` text | F-Series constructed its own protocol, state, governance, registry and levels without querying the existing Phase-1 artifacts | **NO** | the duplication itself is the ACL-3 finding | H-1 decides the reconciliation |
| ACL-4 (a fact keeps its scope) | ARCH §4.2; gate `Q55` | no `scope` / regime field on inventory items or contributions | **NO** | Phase-2 consumers cannot know a record's scope | add scope/regime (MP 1A list includes scope/regime) |
| Research Reconstruction Package | ARCH §4.1 | partial outputs (Annex A.I) | **PARTIAL** | no TDI, theory objects, threads, branches, merges, gaps, scope, historical ordering or reconstruction confidence | produce the RRP elements per the MP, or consume the MP's |
| Phase-1 → Phase-2 handoff | ARCH §4 (ACL, Published Language) | none — F-Series has no handoff artifact; its L2/L3 sit *inside* Phase 1 | **NO** | — | define the F-Series output as RRP contributions |
| Phase-2 → Phase-1 feedback | ARCH §5, §6; `RA-5`, `RA-7` | v1.3 re-extraction = a new EvidenceObject version (AUTHORIZE-REEXTRACTION) | **PARTIAL** | no correction-request record (triggering question, scope, method, files examined, found/not found); every correction requires a signed human decision, which makes normal feedback exceptional (conflicts with `RA-7`) | model the correction as an MP §5A revision triggered by a recorded request; human authority only where GOV says so |
| Theory-neutral reconstruction | ARCH §1; MP one-line test | the reading/extraction gates are theory-neutral; the L3 question "what could this imply for KnowledgeOS" and C10 "structure *for KnowledgeOS*" are theory-directed; C03 primes theory terms | **PARTIAL** | Phase-1 wording directs reading toward the theory | remove / move (Annex A.J) |
| [C]/[S]/[E]/[T] origin separation | S2 §5C.2, gates `Q51`/`Q52`; MP scope binding item 8 | not present; F-Series L2 DOMAIN-INTERPRETATION / EXTERNAL-THEORY-COMPARISON are expert contributions without an `[E]` label and without the nine MP fields | **NO** | an `[E]`-type statement can sit in a Phase-1 record unlabeled | Phase-1 observations → the MP `[E]` record form; Phase-1 facts implicitly `[C]` with a verbatim locator |
| L1/L2/L3 separation (F-Series internal) | — (F-Series own) | files, levels, gates, prescription guard (lexical) | internally YES, architecturally **misplaced** | — | superseded by the row "Epistemic separation" |
| Four epistemic statuses never collapsed | `RA-15` (SOURCE-SUPPORTED · RECONSTRUCTION-VALID · THEORY-CONSISTENT · INDEPENDENTLY CORROBORATED) | none explicit; the proposed DD-2 `L1-ACCEPTED` merges several notions into one state | **NO / RISK** | DD-2 as proposed collapses statuses | §7 |
| Audit independence | not in ARCH; MP §9A gates are self-checks | independent L1 audit (frame coverage, computed comparison) | **EXCEEDS** (not required; compatible) | independence is procedural, not statistical | offer as a Phase-1 protocol-change proposal (MP) with evidence, rather than a parallel rule |
| Re-entry | MP §9 stateful processing; §5A versioning | T16 re-entry into analysis on the same evidence | **PARTIAL** | "analysis" is Phase 2 | in a conformant scope, re-entry = re-running MP steps on the same record version |
| Version lineage | MP §5A (`record_version`, `previous_record_hash`, `changed_by_file`, `change_reason`, `change_evidence`); `O-1` resolution (append-only overlay) | EvidenceObject v_k → v_{k+1}, `supersedes` | **YES in intent** | different field vocabulary | adopt the MP §5A fields |
| Hindsight protection | MP §0E.1 (chronology = traversal order, not consultation boundary), §0E.2–0E.3 (targeted backward/forward investigation is *allowed*) | strict no-look-ahead (the reader refuses later F-IDs) | **CONFLICT** | the MP explicitly permits targeted forward investigation and research obligations; F-Series forbids all look-ahead | H-4: keep F-Series strictness as a local run rule, or align with MP §0E |
| Human governance | ARCH §3 layer 5, `RA-4`, `RA-16`; GOV | F-GOVERNANCE-LOG, approval lines, HumanDecision records (HDR-6) | **DUPLICATE** | a second control plane (`RA-16` names this an ACL-3 violation) | express F-Series authority needs as GOV gates / governance-state entries |
| Canonicalization boundary | `RA-4` (L5 unreachable) | F-Series never canonicalizes (C14) | **YES** | — | keep |
| Execution position | `RA-11`, `RA-12` (STATE authoritative; next work recomputed from state) | F-SERIES-STATE + `f_status` | **DUPLICATE** | two execution-position authorities | F-Series position reported into STATE, or STATE references it |
| File identity | MP §4 (one canonical registry, no second scheme) | F-MANIFEST; F3082–F3108 outside the registry | **NO** | a second registry; 27 unregistered IDs | H-3 |
| Theory Discovery Index (1B) | ARCH §2A; `RA-10`; MP `P1-Q1` (no READ_COMPLETE without an index entry; `why` mandatory when false) | none; the content inventory is richer but is not the TDI artifact, and READ-COMPLETE does not require it | **NO** | `P1-Q1` would refuse every F-Series READ-COMPLETE | add a TDI entry per file (it can be derived from the inventory); gate on it |

---

## 3. DD-1 — AUDITED → REOPENED

1. **Problem.** An audited file may later prove mis-reconstructed: Phase-2 discovery (ARCH §6), backward discovery
   (MP §5A), or a later finding.
2. **Proposed solution (r2).** A new state REOPENED under a REOPEN decision; the old audit stays valid for the old
   version; the file becomes the next F-ID again.
3. **Architectural justification.** A correction is a new reconstruction version (ARCH §5; `RA-2`; MP §5A), and
   feedback is *normal* (`RA-7`).
4. **Epistemic consequences.** Two versions coexist, and the audit of v_k certifies only v_k. Anything citing v_k
   stays a statement about v_k.
5. **Phase-1/Phase-2 separation.** Conformant only if the trigger is a **correction request** (Phase 2 asks, Phase 1
   writes, `RA-5`) or a Phase-1 backward discovery (MP §5A `changed_by_file`). Non-conformant if Phase-2 artifacts are
   re-bound by Phase 1.
6. **Provenance.** It must record *why* the corpus was revisited: the triggering question, the originating Phase-2
   item, the scope and method, the files examined, what was found and what was not found (`prompt_v1.0.md` §8; ARCH §6).
   r2 records only a reason and a hash.
7. **Reproducibility.** The identities of v_k and v_{k+1} are preserved; the generation of v_{k+1} is not
   reproducible (execution-environment identity only).
8. **Failure modes.**
   - (a) **Hindsight contamination.** The re-extractor has seen the Phase-2 hypothesis that triggered the reopening,
     and extracts toward it. This is the most serious failure mode.
   - (b) **Normal feedback made exceptional.** Every correction requires a signed human decision, so corrections
     become rare (conflicts with `RA-7`).
   - (c) **Head-of-line blocking.** Reopening an early file makes it the "next F-ID" and blocks every later file.
     This mirrors the thread-versus-phase problem `RA-13` forbids at the phase level.
9. **Alternatives.**
   - **A1** — no REOPENED state: a correction is an MP §5A append-only revision of the affected records (and a new
     EvidenceObject version for the L1 artifacts), triggered by a recorded correction request. The AUDITED state of
     v_k stays as history.
   - **A2** — REOPENED as in r2.
   - **A3** — forbid corrections of audited files.
10. **Recommendation (not implemented).** **A1.** Model the correction as a *revision event on the file's record*,
    not as a regression of the file's lifecycle state. It needs:
    - a correction-request record (the provenance fields in 6);
    - an extractor for v_{k+1} who receives the request's *scope* but not the triggering Phase-2 hypothesis, recorded
      in its isolation attestation;
    - human authority only where GOV requires it (H-2);
    - no head-of-line blocking, because the revision runs as its own unit, not as "the next F-ID".

**§E answers (explicit model):**
- **What becomes invalid?** Nothing retroactively. v_k's *currency* ends: it is no longer the latest version.
- **What remains historically valid?** v_k, its audit, and every consumption of v_k.
- **Does the old audit remain valid for the old version?** Yes, and for it only.
- **Can downstream artifacts keep using v_k?** Yes, as statements about v_k. Phase 2 decides whether to incorporate
  v_{k+1} (ARCH §5: "Phase 2 incorporates it").
- **How is the new version distinguished?** By its evidence hash, `record_version` and `previous_record_hash`
  (MP §5A).
- **Can reopening create hindsight contamination?** Yes (failure mode 8a). Mitigation: a blind re-extractor plus the
  recorded trigger; residual DETECT.
- **What authorization is required?** Per H-2: a governance gate. Recommendation: a correction request is enough for
  a Phase-1 revision, and an audited file's new version is re-audited under the same cadence rule.

## 4. DD-2 — L1-ACCEPTED + `acceptance_basis`

1. **Problem.** r1 defined "accepted" as needing an independent audit, but the audit is due only for the first file
   and every fifth, so most files could never be consumed.
2. **Proposed solution (r2).** One state L1-ACCEPTED carrying a basis, `SEALED-ONLY` or `AUDITED+HUMAN`.
3. **Architectural justification claimed.** Consumers need a single eligibility test.
4. **Epistemic consequences.** A single state for two different evidential positions invites exactly the collapse
   `RA-15` forbids: *"A single status field cannot carry these four. Collapsing them is how repetition becomes
   corroboration."* A consumer that checks the state and not the basis treats `SEALED-ONLY` as `AUDITED+HUMAN`.
5. **Phase-1/Phase-2 separation.** "Eligible for downstream reconstruction" (Phase 1 consuming Phase 1, MP §0E) is not
   "eligible for Phase-2 relevance" (ACL-1: Phase 1 never gates Phase-2 relevance). L1-ACCEPTED would conflate the two.
6. **Provenance.** Neutral: the basis is recorded.
7. **Reproducibility.** Neutral.
8. **Failure modes.** A consumer drops the basis; an acceptance is read as correctness; the gate blocks consumption of
   unaudited but valid records (contrary to MP §0E's normal backward/forward investigation).
9. **Alternatives.** See §7.
10. **Recommendation.** **Do not introduce `L1-ACCEPTED` as a state** (§7: it is an epistemic category error as a
    *state*). Replace it with independent status fields aligned with `RA-15`, and an explicit Published-Language
    membership rule.

## 5. DD-3 — does REVALIDATION need a new human decision?

1. **Problem.** After v_{k+1}, may artifacts derived from v_k be carried over?
2. **Proposed solution (r2).** Revalidation re-runs the gates against v_{k+1} and needs no human decision, because the
   re-extraction was authorized.
3. **Testing the assumption.** *Authorization to create a new evidence version* and *authorization to reuse prior
   research artifacts against it* are **different acts**:
   - The first says: the corpus should be read again for this file.
   - The second says: conclusions formed on v_k still stand on v_{k+1}.

   The second is an **epistemic claim**, not an authorization. It can be wrong even when every gate passes: gates test
   form, not whether a conclusion survives changed evidence. It is also hindsight-prone: the artifact existed before
   v_{k+1} and may have shaped it. **The r2 assumption does not hold.**
4. **Architectural placement.** In the architecture, the artifacts at stake (analysis, hypotheses) are **Phase-2
   artifacts**, and "Phase 2 incorporates" a new reconstruction version (ARCH §5). Re-binding them is Phase-2 work,
   governed by S2 (theory change log, `[T]` / origin rules), not an F-Series transition.
5. **Phase-1/Phase-2 separation.** An F-Series revalidation of L2/L3 would be Phase 1 writing Phase-2 state (`RA-2`,
   `RA-5` inverted).
6. **Provenance.** If ever performed, it must be a new artifact with `revalidated_from`, a restarted status (never
   inherited), and a record of the old/new evidence diff.
7. **Reproducibility.** Unaffected.
8. **Failure modes.** A silent inheritance of maturity (the `RA-15` "repetition raises maturity" pattern in a new
   form).
9. **Alternatives.**
   - **B1** — no revalidation in Phase 1; Phase 2 decides incorporation.
   - **B2** — Phase-1 revalidation of Phase-1 records only (e.g. a contribution re-anchored to v_{k+1}): an MP §5A
     revision, no human decision, status restarted.
   - **B3** — r2 as proposed.
10. **Recommendation.** **B1 for L2/L3-type artifacts** (they leave Phase 1, H-1). **B2 for Phase-1 records.** A human
    decision is needed only if GOV declares reuse a governed act. That is H-2, not assumed.

## 6. DD-4 — scope of GO-D1

1. **Problem.** Who authorizes the start of extraction, and for which scope?
2. **Proposed solution (r2).** GO-D1 for F3082 only; per-batch GOs later.
3. **Architectural justification.**
   - `RA-16`: authority to execute is read from the governance control plane before every execution unit.
   - `RA-11`/`RA-12`: execution *position* is recomputed from STATE, and **no protocol depends on a human to supply
     it**.
   - Authority and position are distinct; `RA-16` exists precisely because `RA-11` did not cover authority.
4. **Epistemic consequences.** None directly. This is an execution-control question.
5. **Phase-1/Phase-2 separation.** Neutral.
6. **Provenance.** A GO must be a GOV record: scope, date, by whom, under which protocol version.
7. **Reproducibility.** Neutral.
8. **Failure modes.**
   - A per-file GO makes a human the source of execution position (conflicts with `RA-11`).
   - An F-Series-only GO bypasses GOV (`RA-16`).
   - A standing GO with no scope limit removes human control.
9. **Alternatives.**
   - **C1** — GO recorded in GOV (`governance-state.yaml` / a `gates.yaml` gate) for a named scope: F3082
     (demonstration), then windows or batches.
   - **C2** — an F-Series decision record (r2).
   - **C3** — no GO beyond protocol approval.
10. **Recommendation.** **C1.** The scope sequence (F3082 first, then windows) is the human's choice; the *location* of
    the record is GOV.

## 7. `L1-ACCEPTED` — semantic analysis

These notions are distinct. None implies the next, and none may be written as another.

| Notion | Meaning | Established by | Phase |
|---|---|---|---|
| **mechanically sealed** | the artifacts hash to a recorded evidence hash | code (EVIDENCE-INTEGRITY) | 1 |
| **structurally valid** | required fields present, references resolvable (MP §9A `RECONSTRUCTION_RECORD_VALIDATED`, *"not that the mathematics is correct"*) | gates | 1 |
| **integrity verified** | the sealed state equals the committed state (GIT-INTEGRITY), unmodified since | code + git | 1 |
| **source-supported** (`RA-15` A) | each item's verbatim locator verifies against the source | code (quote check) + reading | 1 |
| **reconstruction-valid** (`RA-15` B) | Phase 1 faithfully established what the source meant in context | MP §9/§9A; strengthened by the independent audit | 1 |
| **independently audited** | a separate auditor's inventory, above the floor, compared by computation | the AuditRecord | 1 (F-Series addition) |
| **human accepted** | a human act accepting the record as the basis for further work | a decision record | GOV |
| **eligible for downstream reconstruction** | later Phase-1 files may consult it (MP §0E) | the record exists and integrity is verified — **no acceptance needed** | 1 |
| **eligible for Phase-2 consumption** | it is a member of the Published Language (RRP) with its statuses and scope carried (ACL-4); ⛔ relevance is Phase 2's call (ACL-1) | the handoff | 1 → 2 |
| **theory-consistent** (`RA-15` C) | it fits the emerging theory | Phase 2 | 2 |
| **independently corroborated** (`RA-15` D) | a genuinely independent source supports it | Phase 2, rarely | 2 |
| **scientifically validated** (S2 L4) | tested and surviving | Phase 2 (2C) | 2 |

**Verdict.** As a *state* that gates consumption and merges `SEALED-ONLY` with `AUDITED+HUMAN`, `L1-ACCEPTED` is an
**epistemic category error**: it collapses rows 1–7 into one bit, against `RA-15`. It is **not required** by the
architecture: within Phase 1, consumption needs only integrity (row 8), and the handoff needs statuses and scope, not
acceptance. It was an **implementation convenience** that r1's own inconsistency made look necessary.

**If a human nevertheless wants an acceptance marker,** it must be defined as:
- **means:** "a human, by the recorded decision D, accepted evidence hash H as the basis for further research, with
  the audit status S at that time";
- **does not mean:** true · correct · reconstruction-valid · audited (unless S says so) · validated · corroborated ·
  relevant to Phase 2;
- **carried as:** a *field* (`human_acceptance: {decision, hash}`) beside the others, **never** as the lifecycle state
  that gates consumption.

## 8. Phase-1 / Phase-2 boundary analysis

What v1.x currently makes F-Series responsible for, and where each belongs:

| F-Series v1.1–v1.3 element | Architecture placement | Verdict |
|---|---|---|
| read · unit frame · content inventory · XC contributions | Phase 1A (MP §9) | **belongs** (map to MP records) |
| TDI | Phase 1B (`P1-Q1`) | **missing** — must be added |
| relationships / cross-file candidates | Phase 1A (MP §7, "relationships") | **belongs**, but F-Series placed it at "L2 analysis"; it is 1A |
| lens checklist: mathematical / logical / DDD critical analysis | Phase 1, *as scrutiny and recording* (MP scope binding items 3–4, 7, 9) | **belongs as observations**; not as findings or validations |
| correctness findings | Phase 1 *records* a MATH-QUESTION or observation; **validation** is Phase 2 | the name is fine, the claim strength must not exceed "observation" |
| DOMAIN-INTERPRETATION / EXTERNAL-THEORY-COMPARISON | `[E]` record with the nine MP fields, status `HYPOTHESIS`/`NOT_YET_ASSESSED` | **re-form** as MP `[E]` records |
| L3 hypotheses, pre-registration, checkpoints, confirmatory tests, multiplicity, statistics/ML confirmation | Phase 2 (S2 L2–L4; 2C) | ⛔ **does not belong in Phase 1** |
| checkpoint populations, near-duplicate signal | Phase 1 *discovery instrument* (MP item 9: detect duplicates, locate candidates) | **belongs as a signal only**; populations for testing are Phase 2 |
| canonicalization | nobody (`RA-4`) | F-Series already excludes it ✔ |

F-Series therefore **must not** be responsible for theory validation, theory acceptance, scientific correctness,
hypothesis approval or canonicalization. It currently is not responsible for acceptance, validation or
canonicalization. It **is** responsible for hypothesis generation and planned confirmatory testing, which is the
violation.

## 9. Research-oriented corpus analysis

Does the architecture let F-Series preserve each material kind without collapsing it?

| Material kind | Preserved by F-Series v1.2/v1.3? | Collapse risk |
|---|---|---|
| historical statements | yes (verbatim items) | low |
| hypotheses in the source | yes (category HYPOTHESIS) — ⚠️ **the same word** as the F-Series L3 kind | **`RA-9` collision**: a source hypothesis vs an F-Series-generated hypothesis |
| definitions | yes (DEFINITION with the verbatim source definition) | low |
| mathematical arguments | yes (THEOREM, FORMULA, source status) | medium (detector recall; release records) |
| architectural ideas | yes (ARCHITECTURE-COMPONENT) | low |
| experiments / failed experiments | partially (EXAMPLE, CONCLUSION; **no EXPERIMENT or NEGATIVE-RESULT category**) | **medium**: a failed experiment can be recorded as a "CONCLUSION" and read as established |
| contradictions | yes, if source-framed (C07); analyst tensions separate | low |
| abandoned ideas | **no explicit category or status** (MP has lifecycle / intra-file revision, §5A case 3) | **high**: abandoned ≈ still-current |
| partial theories · speculative ideas | no explicit status (the source's own maturity, MP `source_self_declared_status`) | **high**: speculative ≈ asserted |
| research questions | yes (RESEARCH-QUESTION, UNRESOLVED-QUESTION) | low |
| derived structures · later interpretations | F-Series L2/L3 (misplaced; §8) | **high**: analyst structure ≈ source structure without `[E]` |

**Gaps:**
- the source's self-declared status (MP §9 `source_self_declared_status`);
- intra-file revisions (MP §5A case 3);
- experiment and negative-result categories;
- abandonment / lifecycle.

These exist in the MP and not in F-Series. That is another reason to map onto the MP instead of maintaining a
parallel schema.

## 10. Threat-model implications

| Guarantee | Class |
|---|---|
| reading coverage; unit frame; inventory floor; quote verification; evidence sealing; GIT-CAS; LANE-OWNERSHIP | **mechanical** (against E1/E2; not against E3) |
| hash-bound decisions under HDR-6 A | **cryptographic** (only if a key the agent cannot use exists) |
| decisions under HDR-6 B; human GO; human acceptance | **human-governance dependent / procedural** |
| isolation of the extractor/auditor context; no model priors | **procedural + detect**; ⛔ **not guaranteed** |
| audit independence | **procedural** (a methodological independence claim), ⛔ not statistical |
| semantic completeness of extraction | ⛔ **not guaranteed** (floor + audit + human only) |
| math/definition detection recall | **empirical** (to be measured), never complete |
| conformance to the architecture | ⛔ **currently not achieved** (§2) |
| the Phase-1 control plane `gates.yaml` / `gate-runner.py` | **UNDETERMINED**: not reviewed here; its guarantees would have to be established before F-Series authority is moved onto it |

Passing tests is conformance to F-Series' own specification. It is **not** conformance to the architecture, and it is
not methodological validity. §2 shows the gap between the two.

## 11. Alternative designs for F-Series' role (the decision under DD-1…DD-4)

| | Option | What happens | Pros | Cons |
|---|---|---|---|---|
| **R-A** | **F-Series becomes the execution integrity layer for the existing Phase-1 protocol** | the reader, unit frame, inventory floor, sealing, GIT-CAS, independent audit, quarantine and isolation wrap the MP's per-file reconstruction; records use MP types and namespaces; TDI added; state reported to STATE; authority from GOV; L2/L3 (hypotheses, tests) removed — Phase-2 work goes to Step 2 | conformant; keeps the valuable integrity work; fills MP gaps (enforcement) | requires re-mapping v1.2 artifacts; MP protocol-change proposals for the additions (independent audit, sealing) |
| **R-B** | F-Series stays a separate Phase-1 protocol for its population | the architecture must be amended (ARCH §9 proposal with evidence) to admit two Phase-1 protocols | keeps the current design | an architecture change against a frozen artifact; two Phase-1 record schemas; duplicate control planes |
| **R-C** | F-Series retires; its population joins the MP lane's queue | STATE schedules those files under the MP | simplest; fully conformant | loses the integrity layer, unless it is proposed to the MP separately |

**Recommendation:** **R-A.** It is conformant, and it preserves exactly what v1.2's audits showed to be worth keeping.

## 12. Open decisions requiring human authority

| Id | Decision | Minimum information |
|---|---|---|
| **H-1** | **F-Series' role:** R-A / R-B / R-C (§11). This includes reconciling the 2026-09-25 "research-discovery programme" direction with the frozen architecture | F-Series v1.1+ does Phase-2 work (§8); ARCH §9 makes a contradicting protocol defective; R-A keeps the integrity layer and moves hypothesis/test work to Step 2 |
| **H-2** | **Authority model:** which F-Series acts need human authority (GO, L1 acceptance, correction, exception), and whether those authorities live in GOV (`RA-16`) | a second control plane is named an ACL-3 violation (`RA-16`); feedback must stay normal (`RA-7`) |
| **H-3** | **Identity of F3082–F3108:** extend the canonical registry `list_of_files_to_read.log` (a registry change under MP §4, bootstrap-once), or treat them otherwise | MP §4 forbids a second ID scheme; these 27 IDs are not in the registry; F-MANIFEST currently acts as a registry |
| **H-4** | **Look-ahead:** keep the F-Series strict no-look-ahead as a local run rule, or align with MP §0E (targeted forward investigation allowed, chronology = traversal, not consultation) | the two rules conflict; this also determines what HDR-2 means |
| **H-5** | **DD-1:** A1 (a correction = an MP §5A revision, triggered by a request, blind re-extractor) / A2 (REOPENED state) / A3 (no corrections) | §3 |
| **H-6** | **DD-2:** drop the L1-ACCEPTED state in favour of `RA-15`-aligned status fields + RRP membership (recommended), or define it as a field only (§7) | §4, §7 |
| **H-7** | **DD-3:** B1 + B2 (recommended) or B3 | §5 |
| **H-8** | **DD-4:** C1 — GO in GOV for a named scope (recommended) | §6 |
| carried | HDR-1 (isolation residual) · HDR-6 (signed vs procedural) | unchanged. HDR-2/HDR-3 (temporal, multiplicity) **leave F-Series** under R-A (they are Phase-2 questions), except HDR-2's Phase-1 part, which becomes H-4 + MP §3 |

## 13. Recommended decision sequence

1. **H-1**: the role. Everything else depends on it.
2. **H-3** (identity) and **H-2** (authority model).
3. **H-4** (look-ahead / chronology).
4. **H-5…H-8** (DD-1…DD-4 as reframed).
5. Then: a **v1.3-R design** re-expressing F-Series as the chosen role (for R-A: the MP record mapping, TDI, scope,
   `RA-15` statuses, STATE/GOV integration, and the integrity mechanisms as MP protocol-change proposals).
6. A conformance re-audit of v1.3-R against the architecture (not only an adversarial audit).
7. Then HDR-1 / HDR-6 / approval / GO, then F3082.

## 14. Conclusion

> ## ⛔ **IMPLEMENTATION BLOCKED.**
>
> v1.3 must not be implemented as designed. Its DD-1…DD-4 are answerable only after **H-1**: F-Series as currently
> scoped is a second Phase-1 protocol performing Phase-2 work, outside the frozen architecture's single Phase-1
> protocol, registry, control plane and epistemic ladder. The integrity mechanisms are worth keeping. Their placement
> is the open question, and it is the human's.

---

## Annex A — `prompt_2.md` conformance audit (A–N), cross-referenced

- **A. Executive verdict.** **NON-CONFORMANT** as scoped; **conditionally conformant** under R-A (§1, §11).
- **B. Architecture mapping.** §2 (requirement → mechanism → evidence → gap → action).
- **C. Phase-1A.**
  - SUPPORTED: source, provenance, claims (contributions), definitions, assumptions.
  - PARTIALLY SUPPORTED: derivations (THEOREM/FORMULA only, no derivation instances), relationships (cross-file
    candidates, misplaced), contradictions (source-framed only).
  - MISSING: chronology (historical ordering), gaps (no G-records), scope/regime, theory objects, theory threads.
  - CONFLICTING: record schema vs MP §6/§9 (§2).
- **D. Phase-1B / TDI.**
  - **MISSING** as an artifact and a gate (`P1-Q1`).
  - The inventory is content-keyed (good: no document-kind filter), so a TDI entry is derivable. Nothing in F-Series
    classifies by folder or kind.
  - The residual risk is judgement-based `NO-SUBSTANTIVE-CONTENT` dispositions on "procedural-looking" units. The
    audit reviews them, but `P1-Q1`'s rule (*"it is a governance/process/administrative document" is not an
    admissible reason*) is not encoded.
- **E. ACL-1…ACL-4** (represented · mechanically enforced · procedural · bypassable · test):
  - **ACL-1:** not violated · not enforced · — · a future filter could be added · a test is needed that no gate
    reads kind/provenance.
  - **ACL-2:** generic categories · — · the C03 example primes theory terms · yes · replace the example.
  - **ACL-3:** violated by the duplication itself (§1).
  - **ACL-4:** not represented (no scope field) · — · — · — · add scope.
- **F. Epistemic boundary.**
  - F-Series produces L2-type (hypothesis) content in Phase 1 (§8).
  - It does not produce L4/L5, which conforms.
  - It must not decide that a reconstructed structure is the theory, and it does not, but its L3 question is
    theory-directed.
- **G. Provenance.**
  - The chain is strong (page → unit → item → contribution).
  - The `[C]/[S]/[E]/[T]` origins are absent; Phase-1 facts would carry `[C]` implicitly.
  - Analyst content lacks `[E]` labels (§2).
  - No new origin category is needed.
- **H. Feedback loop.**
  - PARTIAL: v1.3 versioning is conformant in intent.
  - Missing: the correction-request record and a blind re-extractor.
  - Conflict: signed decisions for every correction (`RA-7`) (§3).
- **I. Reconstruction Package.**
  - Already produced: evidence objects (sealed; different schema), definitions, assumptions, claims (as
    contributions), provenance.
  - Partially produced: corpus registry (F-MANIFEST: not canonical), file reconstruction records (XC `files.jsonl`),
    derivations, relationships, contradictions.
  - Not produced: TDI, theory objects, theory threads, branches, merges, gaps, scope/regime, historical ordering,
    reconstruction confidence/status.
  - Produced in the wrong context: hypotheses, analysis findings, test plans (Phase 2).
- **J. Remaining contamination risks.**
  - the theory-directed L3 question (v1.1 §F-1A);
  - C10 "structure *for KnowledgeOS*";
  - the C03 example terms ("kernel", "invariant", "fixed point");
  - the orchestrator's own context (it has read theory documents, including this review's sources);
  - the repository CLAUDE.md injected into agents;
  - model priors.
- **K. Remaining mechanical-enforcement gaps.**
  - no TDI gate;
  - no scope field;
  - no origin labels;
  - ACL-1 not tested;
  - the control plane is not GOV;
  - the plus the v1.3-design items not yet implemented (all of v1.3).
- **L. Required F-Series v1.3 changes** (only after H-1; stated for R-A):

  | Class | Changes |
  |---|---|
  | **ARCHITECTURAL REQUIREMENT** | none: the architecture is not changed |
  | **PROTOCOL REQUIREMENT** | F-Series references ARCH and MP; records use MP types, namespaces and §5A versioning; TDI per file; scope/regime; `RA-15` status fields; `[E]` record form for analyst observations; L2/L3 hypothesis and test content removed to Step 2; level names renamed (`RA-9`); theory-neutral examples |
  | **MECHANICAL ENFORCEMENT** | a `P1-Q1`-equivalent gate; a scope field required; an origin label required on non-`[C]` content; an ACL-1 test; state reported into STATE; authority read from GOV |
  | **PROCEDURAL RULE** | a blind re-extractor for corrections; a correction-request record |
  | **HUMAN DECISION** | H-1…H-8 (§12) |

- **M. Questions requiring human decision.** §12.
- **N. Recommendation.** Do **not** implement v1.3 now. Decide H-1 first; then a v1.3-R design and a conformance
  re-audit.
