# EPIC-003 Tactical DDD Entry Assessment

**Kind:** engineering bridge assessment — maps the existing implementation onto the accepted EPIC-002 strategic baseline **before** Tactical DDD begins. Not Strategic DDD, not governance, not implementation.
**Stance (per ARB refinement):** archaeologist, not reviewer — the purpose is to recover what architectural intent already exists, not to grade the code. Not every mismatch is a violation: where the strategic model evolved while the implementation faithfully reflected the previous understanding, that is recorded as *evolution lag*, not erosion.
**Classification rule (binding, per ARB):** **behavior precedes structure.** Where namespace layout and runtime behavior disagree, contexts are classified by observed business behavior; layout is evidence, behavior is stronger evidence.
**Constitutional constraints (immutable here):** the EPIC-002 strategic baseline — Canonical Context Map (5 ratified BCs), Relationship Pattern Selection (as adversarially reviewed), ARB Resolution. Implementation adapts to strategy; strategy does not adapt to implementation.
**Evidence base:** four parallel codebase inventories (Adjudication+Contestation · Trust/Governance/Shared/messaging · evidence/record/custody sweep · architectural-intent + behavioral mapping), 2026-07-25. Every claim below traces to file-level evidence in those reports; nothing is inferred from documents alone.
**Explicitly not done here:** no aggregates, entities, value objects, repositories, factories, domain/application services, APIs, events, commands, CQRS, or persistence designs.

---

## 0. The one finding that frames everything

**The constitutional correction loop (Challenge → Determination → Correction → Resolution) is an open arc, not a closed loop.** Its tail (steps 3–4: `DeterminationIssuedReactionHandler` in Election; both Contestation reaction handlers) is fully built, provider-wired, scheduled (outbox relay + inbox redrive run every minute in production), integration-tested, and provenance-correct. Its head (raising a Challenge; issuing a Determination) has **zero production triggers** — no route, console command, job, listener, or UI anywhere invokes `Challenge::raise` or `AdjudicationService` (verified by exhaustive grep across `routes/`, `app/Console`, `app/Jobs`, `app/Listeners`, `app/Http`, `app/Services`, `resources/`). The source documents this as **staged construction, not decay**: the challenges migration says raise-time columns are "owned by a later backlog item"; `CoordinatesAdjudication` says "raise path not yet implemented." Two further consequences:

- A correction applied by the greenfield loop writes **only** an `election_applied_determinations` row plus an outbox event — **nothing the legacy voting/results world can observe changes.** The two worlds meet at exactly one seam: a read-only existence check (`LegacyElectionExistenceAdapter`), which is forbidden from touching votes/results.
- The legacy results path **cannot honor a correction even in principle today**: `results_published` is a terminal state with no outgoing transitions; the publish-time integrity sweep *auto-corrects* checksum mismatches rather than blocking; unpublish bypasses the state machine entirely.

Everything below should be read against this: the strategic model's most important consequence chain (COL-5: adjudication → upstream correction) currently has no operational landing point.

## 1. Per-BC assessment (the five ratified Bounded Contexts)

### 1.1 Collection & Aggregation

**Strategic definition:** assemble heterogeneous, multi-kind evidence into a usable body; owns no decision; constrained by K4 (plurality-in-principle vs. operationalization-in-practice).

**Existing implementation:** substantial but living entirely outside `app/Contexts/`. A full heterogeneous evidence vocabulary in `app/Domain/Election/Security/` (+ its `Simplified/` second generation, ~60+28 files): `TrustEvidenceAggregate` consolidating `NetworkEvidence`/`DeviceEvidence`/`VerificationEvidence`/`SessionContinuity`; `EvidenceSnapshot` with provenance; the overlay observers (`IpVelocityOverlay`, `DeviceAnomalyOverlay`, …) gathering evidence at evaluation time; `DivergenceLogger` (self-described "Constitutional Evidence Collection… pure constitutional archaeology") with the divergence tables; the append-only `election_security_events` table with JSON evidence columns. Ballot-accounting aggregation exists in `ResultController::statisticalVerification()`/`verifyResults()`. A fully modeled `app/Domain/Voting/` (VoteAggregator, quorum rules) exists with **zero callers anywhere** — a dormant second notion of voting (governance motions), not the ballot pipeline.

**Architectural intent (recovered):** evidence-based voter trust as a first-class model, built deliberately and twice — the `Simplified/` generation is a second attempt kept in parallel, with the divergence-observation machinery existing specifically to prove the two agree before retiring the old one. The intent is mature; the domain it serves is *voter-trust legitimacy at vote time*.

**Alignment:** the capability *shape* matches the BC exactly — heterogeneous, multi-kind evidence aggregated for downstream evaluation, with provenance. **The current implementation primarily supports vote-time legitimacy decisions** (`ConstitutionalLegitimacyDecision`, whose single production caller is `VoteController::store()`). *This is a current-implementation observation, not a stable architectural conclusion* (ARB refinement, 2026-07-25): whether this evidence vocabulary becomes the canonical evidence source for adjudication — i.e., whether tomorrow's picture is Collection → vote legitimacy **and** → Adjudication — **is a Tactical DDD design decision, deliberately left open here.** Additionally: `EvidenceEnvelopeRef` in the Adjudication context points at "the Evidence context" — **which does not exist**. This BC is that missing referent.

**Boundary observations (not violations — evolution lag):** two parallel generations of the same model; everything outside contexts; the evidence vocabulary is trust/security-scoped, narrower than the BC's general responsibility.

**Behavioral mapping:** *in:* evaluation requests from `VoteController::store()` and middleware (`LogDivergenceObservations`); *decisions inside:* none owned (evaluation produces evidence and findings; the legitimacy verdict belongs to the chokepoint class, deliberately); *out:* `VotingTrustResult` + policy sequence, `ElectionSecurityEvent` appends, divergence observations; *collaborators:* the trust evaluator stack, `SecurityEventRecorder`.

**Maturity matrix:** Strategic Intent ✅ mature · Ubiquitous Language ⚠ established but trust-scoped · Domain Behaviour ✅ live in production · Domain Model ⚠ substantial but bifurcated (two generations) · Infrastructure ⚠ legacy placement · **Tactical Readiness: PARTIALLY READY.**

**Required (architectural only):** decide the fate of the two-generation split *before* tactical modeling (the divergence apparatus exists to enable exactly that decision); establish whether the trust-evidence vocabulary generalizes to adjudication-facing evidence or the BC needs a broader model with this as one evidence family.

### 1.2 Contemporaneous Record-Fixing

**Strategic definition:** fix the record (or its justification) at act-time, before and independent of review; owns D2; act-time volatility, changes for recording/legal reasons.

**Existing implementation:** the strongest behavioral presence of any BC, scattered across helpers/traits/services rather than a context. Act-time vote writes (`VoteStorageTrait::storeVoteWithResults` — transaction, `cast_at` stamped, `vote_hash` computed *before* first save); write-once step records (`VoterStepTrackingService::completeStep` returns the existing row unchanged if present); append-only per-voter audit files (`ElectionAudit::voter_log` with `FILE_APPEND|LOCK_EX`; `ElectionAuditService` JSONL scheme); the PHP-enforced append-only `ElectionSecurityEvent` (save/delete throw); and — greenfield — ADR-T19 Model B, where a Determination's ruling content is fixed exactly once in the immutable outbox event, same transaction.

**Architectural intent:** unambiguous and repeated — "NO user_id (votes are anonymous!)," append-only comments, write-once semantics. The intent to fix records at act-time is everywhere.

**Alignment: high in behavior, absent in boundary.** No context owns this responsibility; it is realized as a discipline observed in many places.

**Boundary observations:** (1) **Retention contradiction — the top finding for this BC:** `audit:cleanup` deletes the per-voter audit files after **30 days** while `election_security_events` retains 730 days. A dispute raised after 30 days has no per-voter record to adjudicate against — this directly undermines COL-2 (Record-Fixing → Adjudication) for the strategic model's central consumer. (2) `vote_hash`/`receipt_hash` in an inconsistent dual state (one migration declares vote_hash "completely removed"; it remains live, unique, NOT NULL, and populated). (3) `ElectionAuditLog` (DB) has no immutability guard, unlike its filesystem and security-event siblings.

**Behavioral mapping:** *in:* the 5-step voting flow's acts (code entry, agreement, ballot, verify, submit); *decisions inside:* what constitutes the fixed record per act (hash composition, anonymized metadata); *out:* votes/results rows, step rows, audit appends, security events; *collaborators:* the voting controllers/traits (producers), audit services.

**Maturity matrix:** Strategic Intent ✅ · Ubiquitous Language ⚠ implicit (no named concept) · Domain Behaviour ✅ pervasive and live · Domain Model ❌ none (discipline, not model) · Infrastructure ⚠ split across filesystem/DB with inconsistent guarantees · **Tactical Readiness: PARTIALLY READY.**

**Required:** resolve the 30-vs-730-day retention contradiction as an explicit business decision before tactical work (it is a business-policy question, not a refactoring); reconcile the vote_hash/receipt_hash dual state.

### 1.3 Custodial Integrity

**Strategic definition:** attributable, tamper-evident possession/verification trail for assigned streams; owns D3 (custodial streams); constrained by K3.

**Existing implementation:** piecemeal custody mechanisms with real substance: SHA256 `data_checksum` over vote content (+ `verifyChecksum` — using `===`, not `hash_equals`, unlike the codebase's own better practice in `CodeVerificationTrait`); the code-based custody link (`codes.voting_code` copied to `votes.voting_code` — the "audit trail code linking code to vote"); `VerificationAttestationRecord` (registrar attribution, evidence hashes, mismatch detection — genuine custody-break detection); `TrustEvidencePrivacyPolicy` (hashing with hardcoded raw-IP refusal); and, in a different context entirely, Membership's Constitutional Snapshot machinery (`SnapshotIntegrityHash` with `hash_equals`, `GovernanceProvenance`, lineage graphs) — the most mature custody-shaped code in the repo. The Replay suite (`ReplayEvidenceEnvelope` sealed evidence + integrity verification) is **designed, tested, and never invoked in production** — a dormant custody-verification capability (with a known determinism weakness: `spl_object_id` serialization).

**Boundary observations:** (1) **The anonymity design is no-direct-FK, not unlinkability**: `codes` carries `user_id`, and the plaintext `voting_code` on both sides makes re-identification a single join. Recorded factually — whether this is acceptable custody design is a business/tactical question, but it must be *known* going into a BC whose whole subject is attributable possession trails. (2) The entry code is stored **plaintext** while vote codes are bcrypt-hashed — split custody hygiene. (3) Publish-time custody-violation handling is inverted: checksum mismatches are auto-corrected and logged, never blocking. (4) Custody-shaped excellence exists in Membership's constitutional snapshots — same pattern family, different context; a candidate ubiquitous-language donor, not a boundary problem.

**Behavioral mapping:** *in:* records from fixing (COL-4a's model-blind handoff exists today as: vote rows arrive, hashes computed over them); *decisions inside:* integrity valid/violated (but the violation consequence is currently auto-repair, not escalation); *out:* checksums, attestation records, verification results; *collaborators:* publish flow, verification controllers.

**Maturity matrix:** Strategic Intent ⚠ present but fragmented · Ubiquitous Language ⚠ partial ("custody" never named; "attestation," "checksum," "audit trail" are) · Domain Behaviour ⚠ real but with inverted violation-handling · Domain Model ❌ no unified model · Infrastructure ⚠ mixed hygiene · **Tactical Readiness: PARTIALLY READY.**

**Required:** an explicit decision on the violation-consequence inversion (auto-correct vs. block/escalate) — this is D3 territory and must not be silently inherited; classification of the re-identification join as accepted-by-design or custody defect.

### 1.4 Self-Verifying Integrity

**Strategic definition:** discharge the integrity obligation via public/cryptographic verification for assigned streams; publishes the verification protocol (Published Language, per the reviewed pattern selection).

**Existing implementation: NONE — confirmed absence, reported explicitly.** No bulletin board (zero PHP hits; "bulletin" appears only in docs prose). No ZK proofs, homomorphic tallying, mixnets, threshold decryption, or signatures; no crypto dependency in `composer.json`; all hashing is unkeyed SHA256 + bcrypt. `encrypted_vote` is a dead column (written by nothing, read by nothing). The receipt "re-verification" flow is the nearest neighbor and is **not** self-verification: authenticated-only (org members, results phase), plaintext exact-match lookup, shuffled-list "find your code and click confirm" — detects omission of a receipt, proves nothing about recorded selections. The public "security" pages describe verifiability without providing any.

**Architectural intent:** the *aspiration* exists (dead `encrypted_vote` column, receipt scheme, marketing copy) — evidence the team identified the capability, not that it was built. Same distinction as Trust below.

**Maturity matrix:** Strategic Intent ⚠ aspirational only · Ubiquitous Language ❌ absent · Domain Behaviour ❌ absent · Domain Model ❌ absent · Infrastructure ❌ absent · **Tactical Readiness: NOT READY — greenfield.**

**Consequences for the strategic edges:** COL-3b (Published Language) and COL-4b (fixing conforms to the published protocol) have **no implementation counterpart at all**; when built, this BC is a true greenfield construction with no strangling required — and nothing existing should be retrofitted into pretending otherwise (the receipt flow is not a starting point; it is a different, weaker thing).

### 1.5 Adjudication — including Mandatory Special Review №1

**Strategic definition:** resolve contested/incomplete evidence into an authoritative determination; owns D1; never automatic (K1); carries the CB-3-Alt annotation.

**Existing implementation:** `app/Contexts/Adjudication` — 37 files, immaculate greenfield hexagonal shape, thoroughly tested. And deliberately narrow: the `Determination` aggregate records a binding ruling (Draft→Issued→Final), enforces one-determination-per-challenge, fixes ruling content in the immutable event (ADR-T19). Its own docblocks state it "performs NO business reasoning (legitimacy/admissibility are decided upstream)." Evidence enters as one opaque `EvidenceEnvelopeRef` string whose only rule is non-empty; there is **no evidence weighing, sufficiency, scoring, or corroboration logic anywhere in the context** (`Domain/Policies/` is empty). `Contestation` implements only the reaction slice; the raise path is explicitly deferred.

**Special Review №1 — is `app/Contexts/Adjudication` identical / extension / subset / different, relative to the strategic Adjudication BC?**

**Determination: SUBSET.** Grounds:
- **Same business intent:** "the binding ruling on a contested outcome" — exactly the strategic BC's determination-issuing responsibility. The names collide because the concepts genuinely coincide; this is not an accidental homonym.
- **Strict subset in scope:** the strategic BC owns D1 (automatic vs. discretionary adjudication) and the evidence-weighing judgment that produces a ruling. The implemented context owns *recording* the ruling and its uniqueness/lifecycle — the judgment itself is declared upstream, **and no upstream exists** (the referenced "Evidence context" is unimplemented; the only legitimacy resolver in the repo, `ConstitutionalLegitimacyDecision`, answers a different question — voter-trust-at-vote-time, not contested-outcome adjudication).
- **Not "different":** nothing in the implemented context contradicts the strategic model; every VO and invariant (opaque cross-context refs, no voter linkage, one ruling per challenge, event-as-record) is *consistent with and useful to* the strategic BC.

**Recommendation (no renaming performed, per instruction):** treat the existing context as the **nucleus** of the strategic Adjudication BC — keep the name, extend the scope in Tactical DDD (the evidence-weighing/sufficiency half becomes new tactical work inside the same boundary, fed by Collection via COL-1). The reconciliation flagged at map-acceptance resolves as *extension of the existing context*, not rename, not parallel context. The alternative (a second, differently-named adjudication context) would manufacture the exact custody-of-the-name problem the reconciliation was meant to avoid.

**Behavioral mapping:** *in (today):* `IssueDeterminationCommand` — from tests only; *decisions inside (today):* uniqueness + lifecycle only; *(strategically also:* D1, sufficiency*)*; *out:* `DeterminationIssued` (sole producer, restricted per catalog); *collaborators:* Election reaction and Contestation reactions consume; Contestation requests but never creates (TP-2).

**Maturity matrix:** Strategic Intent ✅ · Ubiquitous Language ✅ established and aligned · Domain Behaviour ⚠ tail-only (no production trigger; open arc) · Domain Model ⚠ nucleus present, judgment half absent · Infrastructure ✅ complete and qualified · **Tactical Readiness: PARTIALLY READY — the strongest structural nucleus of the five, with its behavioral head missing.**

## 2. Mandatory Special Review №2 — Hybrid Integrity classification

Classifying existing code between the two ratified integrity contexts (classification only, no redesign):

| Existing artifact | Classification | Ground |
|---|---|---|
| `codes`/`votes` voting_code custody link; code hashing (bcrypt + plaintext split) | **Custodial** | Possession/attribution trail semantics |
| `Vote::data_checksum` + verify/sync machinery | **Custodial** | Tamper-evidence over held records, verified by the holder |
| Per-voter audit files, `ElectionSecurityEvent`, step records | **Custodial** (they double as Record-Fixing outputs) | Institution-held trails, not publicly verifiable |
| `VerificationAttestationRecord`, `VoterVerification` | **Custodial** | Named-custodian attribution + custody-break detection |
| Membership Constitutional Snapshot integrity/provenance/lineage | **Custodial** (different context, same family) | Holder-verified integrity with attribution |
| Replay suite (dormant) | **Custodial** | Verifiable only with DB access + `app.key` — not public |
| Receipt re-verification flow | **Custodial** (weak) — explicitly NOT self-verifying | Authenticated, server-held plaintext, omission-detection only |
| *Anything self-verifying* | **— none exists —** | §1.4 |

**Net: every evidentiary stream in the system today is de facto custodial.** The hybrid ruling's stream-assignment rule therefore starts from an all-custodial baseline; Self-Verifying Integrity acquires streams only as it is built. This is a clean, honest starting position — no existing code needs reclassification into the self-verifying column.

## 3. Existing-implementation landscape (contexts that are not the five, but that Tactical DDD must reconcile with)

- **Trust (`app/Contexts/Trust`)** — *per the ARB's provisional assessment, recorded in substance:* not an implementation of a Trust bounded context but **a legacy workflow that captures trust-attestation events**: two event classes, emitted from `VoterVerificationController` on the plain Laravel bus, **with no registered listener** — fire-and-forget into the void. Boundary ownership inversion (the controller owns the use case; the context publishes). The ADR-001 intent (trust levels, evidence capture) is strategically coherent and *upstream* of the EPIC-002 five (identity/eligibility = external upstream per the dependency map); its missing evidence model (Identity → **Evidence** → Attestation → Revocation) is the primary tactical gap. Strategic Intent ✅ / Language ✅ / Behaviour ⚠ / Model ❌ / Infrastructure ⚠ legacy / **PARTIALLY READY**. Its `VerificationAttestationRecord`-family content is the part bearing on Custodial Integrity's future.
- **Governance** — fully built, load-bearing, decision-legitimacy domain. One boundary observation: `GovernanceDecision` directly imports Membership's `CommitteeId` type — a cross-context Domain import that the greenfield contexts explicitly avoid (ADR-T16 strings-only). Predates that rule; evolution lag, not erosion.
- **Committee / Finance** — not contexts: a read-model seam (typed with Membership VOs, consumed by Membership) and a single frozen projection-event contract respectively. Both are *reserved names with deliberate seam artifacts* — future boundary markers.
- **Election vs. Elections** — two vintages: Election (singular) is the greenfield correction-reaction context (strangler seam via read-only ACL); Elections (plural) is a thin voter-assignment slice that returns legacy models and dispatches legacy events (not context-pure), whose `ResultsPublished/Unpublished` events also have **no registered listener**.
- **Membership** — the mature, load-bearing domain (495 files, real triggers across HTTP/jobs/schedule); the proof that the tactical patterns work in production here. Internal accretion signals (DTO/DTOs, doubled subfolders) noted for its own housekeeping, not this program.
- **The legacy voting monolith** — `VoteController` (4254 lines) et al.: the operational record-producing machinery. It touches the new architecture at exactly two seams: `ElectionLifecycle::` (widely — the SSOT is genuinely enforced) and the trust/legitimacy gate (once, in `store()`, as the *primary* enforcement with legacy checks demoted to defense-in-depth). It never touches `app/Contexts/*`. Three parallel copies (real/demo/deligate) of the same 5-step flow exist; the `VotingService` factory family is the one deliberate abstraction over that duplication.
- **Dormant designed capabilities:** the Replay suite (tested, zero production constructors) and `app/Domain/Voting` (fully modeled, zero references of any kind). Both are intent-evidence awaiting a consumer; the Replay suite is directly relevant to Custodial Integrity's verification story.

## 4. Cross-cutting reconciliation table

| Strategic BC | Existing implementation | Current ownership | Required movement (architectural, pre-tactical) | Readiness |
|---|---|---|---|---|
| Collection & Aggregation | Trust-evidence pipeline ×2 generations (`app/Domain+Application/Election/Security[/Simplified]`), divergence apparatus, security-events table; dormant `Domain/Voting` aggregation | Legacy trees; no context | Resolve two-generation split (the divergence apparatus is the built-in instrument); decide vocabulary generalization | **PARTIALLY READY** |
| Contemporaneous Record-Fixing | Act-time vote writes, write-once steps, append-only audit files/events; greenfield event-as-record (ADR-T19) | Traits/helpers/services; discipline without a boundary | Resolve 30-vs-730-day retention contradiction (business decision); reconcile vote_hash/receipt_hash duality | **PARTIALLY READY** |
| Custodial Integrity | Checksums, custody links, attestation records, privacy hashing; Membership snapshot machinery (donor pattern); dormant Replay | Fragmented across legacy + one other context | Decide violation-consequence inversion (auto-correct vs. block); classify the re-identification join explicitly | **PARTIALLY READY** |
| Self-Verifying Integrity | **None** (dead column, non-crypto receipt flow, prose pages) | — | None to move — pure greenfield; do not retrofit the receipt flow | **NOT READY (greenfield)** |
| Adjudication | `app/Contexts/Adjudication` nucleus (ruling-recording) + Contestation reaction slice + Election correction reaction; **no production trigger** (open arc); legacy has zero dispute machinery and a results path that cannot re-open | Greenfield contexts (tail); nothing owns the head | Extend the existing context (subset→full scope) per Special Review №1; the raise/issue head and the legacy-results landing point for corrections are the two structural gaps | **PARTIALLY READY** |

**Every implementation artifact examined belongs to exactly one BC above, is explicitly shared (messaging platform, ClockInterface — the one primitive both worlds share), or is explicitly recorded as adjacent landscape (§3).**

## 5. Architectural Risk Register (ARB refinement, 2026-07-25 — architectural risk, not implementation risk; this table IS the ARB agenda)

| # | Risk | Severity | Reason | Where evidenced |
|---|---|---|---|---|
| R-1 | Correction loop has no production entry | **Critical** | The core domain (constitutional correction) is incomplete — the loop cannot fire outside tests | §0; intent+behavior report |
| R-2 | Corrections cannot affect published results | **Critical** | The trust loop cannot complete: terminal `results_published` state, no re-open path, auto-correcting publish sweep | §0; §1.5 |
| R-3 | Record retention mismatch (30 vs. 730 days) | **High** | Adjudication evidence may disappear before a dispute can use it — undermines COL-2 for the model's central consumer | §1.2 |
| R-4 | No self-verifying implementation | **Medium** | An entire strategic BC is greenfield; COL-3b/COL-4b have no counterpart | §1.4 |
| R-5 | Evidence vocabulary split into two generations | **Medium** | Tactical modelling complexity; the divergence apparatus exists to resolve it but the decision is unmade | §1.1 |
| R-6 | Custody violation-consequence inverted (auto-correct, never block) | **High** | D3-adjacent behavior inherited silently would encode the wrong default | §1.3 |
| R-7 | Anonymity is no-direct-FK, not unlinkability (re-identification join) | **High** | Bears directly on the custody model's subject matter; must be a decision, not an inheritance | §1.3 |

## 6. Quality gate (self-verified)

- Every accepted BC assessed — ✅ (five entries, §1).
- Every artifact assigned to exactly one BC or explicitly shared/adjacent — ✅ (§4 note, §3).
- Strategic violations identified — ✅, with the archaeologist discipline applied: most mismatches are staged construction or evolution lag, explicitly labeled; the genuinely open items are the retention contradiction, the custody violation-consequence inversion, the re-identification join, and the corrections-cannot-land-in-legacy-results gap.
- Every recommendation justified — ✅ (each carries its grounds inline).
- No strategic decision reopened — ✅ (the baseline is treated as constitutional throughout; the Adjudication determination is *subset/extension*, which the map-acceptance explicitly anticipated).
- No tactical model designed — ✅ (no aggregate/entity/repository/API/event designs anywhere in this document).

---

**Stop condition:** the Tactical DDD Entry Assessment is complete. **STOP.** Tactical DDD does not begin; no architecture is redesigned; no aggregates or entities are written. Await explicit ARB authorization — the assessment's open business decisions (§4 Required-movement column) are the natural agenda for that authorization discussion.

---

## ACCEPTED (ARB Resolution, 2026-07-25 — recorded verbatim in substance)

> **The Tactical DDD Entry Assessment is accepted.** It successfully bridges the accepted Strategic DDD baseline to the existing implementation through evidence-based implementation archaeology. The assessment identifies the current implementation landscape, architectural intent, maturity, and open business decisions without redesigning the system or reopening strategic architecture. **Tactical DDD shall commence only after the ARB resolves the four architectural business decisions identified in the assessment:** (1) correction landing point — how adjudication corrections affect the legacy results world; (2) audit retention policy — the 30-vs-730-day contradiction; (3) custody policy — whether the code-to-vote linkage is an accepted design trade-off or an architectural defect; (4) integrity policy — whether custody violations block publication or continue with auto-correction and logging.

These are business and governance decisions, not tactical modeling decisions. Resolving them first prevents Tactical DDD from encoding assumptions that later have to be revisited.

## THE FOUR DECISIONS — RULED (ARB, 2026-07-25, explicit per-item)

1. **Correction landing point: SUPERSEDING CONSTITUTIONAL PUBLICATION** *(ARB naming — stronger than "append-only corrective publication": history is preserved; authority moves forward, exactly as constitutional amendments work).* **ARB ruling (recorded in substance):** adjudication shall not mutate or reopen historical election publications. Every binding determination that changes the authoritative outcome shall produce a new constitutional publication that explicitly supersedes the earlier publication while preserving the complete historical record. This maintains the forward-only, append-only, immutable-record principles, preserves full audit reconstruction (original publication → challenge → evidence → determination → correction → current outcome), and **keeps the legacy publication state machine isolated from adjudication concerns** (rejecting the re-open option's coupling of the legacy engine to adjudication). **Constitutional Publication Invariant (binding):** a published election result SHALL NEVER be modified or deleted; a determination SHALL produce a new constitutional publication that explicitly references the publication it **supersedes** — supersede, not replace. The correction becomes a first-class business object of the core domain (trustworthiness through correction). *(Disposes risk R-2 at the decision level; realization is Tactical DDD work.)*
2. **Audit retention: THE EVIDENCE PRESERVATION WINDOW** *(ARB naming — a single domain concept, not a configuration value: retention is a business policy, not a technical setting).* **Definition:** Evidence Preservation Window = Contestation Window + Maximum Adjudication Duration + Legal Safety Margin, **attached to the election instance** (different election types may carry different windows — board election vs. constitutional referendum vs. emergency vote — with no architectural change, only policy change). **ARB ruling (recorded in substance):** evidence retention shall be governed by the business lifecycle of contestation and adjudication rather than by fixed technical durations; for each election, retention shall be at least the contestation window plus the maximum adjudication horizon plus any legally required safety margin. **Retention Invariant (binding):** *evidence required for a legally permissible challenge must never expire before that challenge can no longer be initiated or resolved.* Flat-730 rejected (turns a business rule into infrastructure policy; over-retains voter-adjacent data); keep-30 rejected ("you may challenge, but we deleted the evidence" undermines adjudication's credibility). **Consequence accepted with the ruling: the Contestation Window itself must be explicitly defined** (needed by the correction loop regardless). *(Disposes R-3 at the decision level.)*
3. **Custody linkage: ACCEPTED PHASE-1 CUSTODIAL CONSTRAINT** *(ARB naming — intentional, temporary, justified, subject to future evolution; deliberately NOT "defect" and NOT merely "trade-off").* **ARB ruling (recorded in substance):** the current code-to-vote linkage is an intentional design trade-off that supports custodial investigation and dispute resolution while the system operates entirely within the Custodial Integrity model; it is not an architectural defect at this stage. Grounds: removing every linkage today would destroy the institution's ability to investigate duplicate voting, code theft, administrative mistakes, and legal disputes *before Self-Verifying Integrity exists* — do not sacrifice operational integrity for a security property that cannot yet be fully realized. **Three binding invariants:**
   - **CL-1:** the linkage SHALL exist solely for custodial investigation; it SHALL never be used during normal election processing.
   - **CL-2:** access SHALL require privileged administrative authority and be fully auditable — **every lookup must itself become evidence.**
   - **CL-3:** the linkage SHALL be reviewed when Self-Verifying Integrity becomes operational; the architecture may then migrate toward stronger unlinkability.
   **Vocabulary correction (binding):** the architecture SHALL NOT state "the system is anonymous." The precise statement: *the system preserves ballot secrecy operationally, but not full cryptographic unlinkability; administrative re-identification is possible through controlled custodial mechanisms.* *(Disposes R-7 as a decision — the limitation is architecture now, not undocumented assumption.)*
4. **Integrity violations: QUARANTINE PENDING DETERMINATION** *(ARB naming — the system does not merely stop; it enters a controlled state).* **ARB ruling (recorded in substance):** automated integrity verification may detect and report checksum mismatches, but it shall not perform corrective actions that alter constitutional records. Any integrity anomaly places publication into a controlled pending state (Integrity Quarantine) until an authorized authority issues a determination; publication then proceeds unchanged, proceeds with an authorized correction, or is rejected. **Governing principle: detection may be automatic; correction may not.** **Integrity Invariant (binding):** automated integrity verification MAY detect anomalies; it SHALL NOT determine their significance; it SHALL NOT perform corrective actions that change constitutional records — only an authorized determination may authorize a correction. Grounded directly in P2 (no trustworthy discipline examined treats correction of contested integrity matters as automatic) — the previous auto-correct sweep contradicted the program's own strongest finding. *(Reverses the R-6 inversion.)* **Architectural connection (observation, not a design):** the quarantine path — Automatic Detection → Challenge → Determination → Correction → Resolution — is the first identified natural **production entry point for the correction loop's missing head** (risk R-1): integrity anomalies are a business source of Challenges.

**The four rulings form one coherent constitutional policy** (ARB observation, recorded): (1) corrections are append-only and supersede, never rewrite, history · (2) evidence is retained per the business lifecycle of contestation and adjudication · (3) the current linkage is an accepted Phase-1 custodial constraint under privileged, audited access, reviewed at Self-Verifying Integrity · (4) detection may be automated; correction requires explicit authority. All four derive technical behavior from explicit domain policy, not implementation convenience.

**Gate status: the Tactical DDD entry condition set by the ARB Resolution above is SATISFIED.** Tactical DDD is formally unblocked; it commences on explicit ARB opening, carrying these four rulings as binding inputs alongside the two entry items already on record (Adjudication scope extension per Special Review №1; hybrid stream assignments from the all-custodial baseline per Special Review №2).

---
*Strategic baseline: `EPIC-002_Canonical_Context_Map.md` (ACCEPTED) · `EPIC-002_Relationship_Pattern_Selection.md` (ACCEPTED, ARB Resolution §) · Evidence: four codebase inventory reports, 2026-07-25 (Adjudication/Contestation · Trust/Governance/Shared/messaging · evidence/record/custody sweep · architectural intent + behavioral mapping).*
