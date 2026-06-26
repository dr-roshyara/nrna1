# Round 49-04 — Behavioral Evidence Dossier (v1.1, empirical research notebook)

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** EBSD — evidence collection · **Built against:** Landscape v1.0
**Status:** 📋 EVIDENCE DOSSIER — **observed facts + interpretation kept SEPARATE. No decisions.** EV-IDs referenced by `49-06` BDR. Decisions made in `49-05` (EBSD Evaluation).
**Date:** 2026-06-26 · *(v1.0→v1.1: observed-fact vs interpretation split; contradictory evidence; provenance; business-vs-technical behavior; evidence-quality rating; "independent reason to change"; architecture-fitness evidence; Emerging Patterns.)*

> **Discipline.** *Evidence remains evidence until explicitly evaluated; it does not become a decision merely by being observed.* Falsification stance: per candidate ask *"can we falsify that this is an independent BC?"*
> **Evidence quality:** **Direct** (read the class) · **Strong** (observed via tests) · **Moderate** (via implementation) · **Weak** (inferred) · None. Orthogonal to **Level** L1/L2/L3 (L3 = runtime+tests+transactions+responsibilities agree).
> **Per-candidate questions:** owns decisions? · owns truth? · transactions? · events? · tests? · **independent reason to change?** (Evans cohesion).

---

## Adjudication
- **Observed (provenance):** `ConstitutionalArbitrationKernel.php::decide(ctx,capability,at)`; `ConstitutionalDecision.php` (`readonly` VO {winner: `JurisdictionNode`, legitimacy, reason, evaluatedAt, trace}); tests `ConstitutionalPolicyTest`, `ConstitutionalAssertionsTest`. **[EV-001/002/003]**
- **Business behaviour:** resolves which **committee/jurisdiction** wins a constitutional conflict (Geo).
- **Technical behaviour:** stateless kernel + policy → immutable decision record with trace.
- **Dependencies:** `GovernanceDecisionKernel`, `JurisdictionNode` (Geo/Graph), clock.
- *owns decisions?* yes (jurisdiction) · *owns truth?* no persistent SoR observed · *txns?* single · *events?* `LegitimacyEvaluated/Granted` · *independent reason to change?* **changes when committee/jurisdiction rules change — NOT when election-determination rules change.**
- **Supporting:** EV-001/002/003. **Contradictory:** none that it's *jurisdiction* arbitration; **contradicts** that it is *election-determination* Adjudication (winner is a JurisdictionNode, not a ballot/contest outcome).
- **Architecture-fitness:** existing — `ConstitutionalAssertionsTest`; missing — election-determination boundary test.
- **Quality: Direct · Level L2–L3 · Confidence Medium.**
- **Interpretation (hypothesis, NOT decision):** the *certified election-determination Adjudication* may be **unrealized**; the code's "arbitration" is a **different (jurisdiction) responsibility**.

## Authorization
- **Observed (provenance):** `ElectionCapabilityResolver.php::evaluate(context)` → `CapabilityDecision`+`CapabilityTrace`; documented INVARIANT: side-effect-free · deterministic · non-I/O · non-temporal. **[EV-030/031]**
- **Business behaviour:** decides "may this actor perform this action here?".
- **Technical behaviour:** pure function over sorted policies; no persistence, no clock, no I/O.
- *owns decisions?* yes (computes) · *owns truth?* **no** · *txns?* none (pure) · *events?* none · *independent reason to change?* **changes when capability *policies* change** (cohesive around policy resolution).
- **Supporting:** EV-030/031. **Contradictory (to BC-hood):** owns no truth; no aggregate; no persistence → looks like a service, not a context.
- **Architecture-fitness:** existing — capability/policy tests; missing — boundary isolation test.
- **Quality: Direct · Level L2 · Confidence Medium.**
- **Interpretation:** behaves as a **domain service / decision resolver**; "truth-owning BC" looks falsifiable.

## Appointment  *(L3 read DONE)*
- **Observed (provenance):** `Authority/ValueObjects/DelegationStatus` (ACTIVE/REVOKED); `Authority/Policies/DelegationLifecyclePolicy::canTransition(from,to)` (ACTIVE→REVOKED; REVOKED→none); `RemoveCommitteeMemberCommand`/`Handler`; `Contexts/Committee/*` read models; event `CommitteeLifecycleChanged`. **[EV-050/051]**
- **Business behaviour:** grant/revoke **delegated authority (Mandate)** and manage its lifecycle. **Technical:** status state-machine (ACTIVE/REVOKED) + policy + CQRS commands.
- *owns decisions?* yes (delegation transitions) · *owns truth?* **likely YES** (`DelegationStatus` = who holds delegated authority + its state) · *txns?* per-delegation · *events?* `CommitteeLifecycleChanged` · *independent reason to change?* when delegation/mandate rules change.
- **Supporting:** EV-050/051. **Contradictory (to the merge-into-Authorization prior):** **Appointment OWNS Mandate truth; Authorization owns NONE** → the merge direction **reverses**.
- **Architecture-fitness:** existing — membership tests; missing — delegation-boundary test.
- **Quality: Direct · Level L2 · Confidence Medium.**
- **Interpretation:** the **Authority/Delegation** subdomain owns **Mandate truth** → a *stronger* BC candidate than the prior assumed; **"merge Appointment → Authorization" appears FALSIFIED** (Authorization, owning no truth, is the more likely service/merge).

## Election Lifecycle  *(L3 read DONE)*
- **Observed (provenance):** `ElectionLifecycleEngine` interface — `compute(Election $election) → ElectionLifecycleSnapshot`, `getState(Election) → ElectionLifecycleState`; doc: *"Single Source of Truth for election lifecycle … computed from signals … the **Election aggregate root**"*; `TransitionMatrix`; tests `ConstitutionalTransitionGuardTest`. **[EV-060/061]**
- **Business behaviour:** determine the canonical election lifecycle state + permissions/locks/allowed-actions. **Technical:** **pure computation over the Election aggregate → immutable read-model snapshot.**
- *owns decisions?* derives state/permissions · *owns truth?* **NO separate SoR — computes FROM the Election aggregate** · *txns?* none (compute) · *events?* lifecycle events emitted around it · *independent reason to change?* when lifecycle-computation rules change.
- **Supporting:** EV-060/061. **Contradictory (to separate-BC):** it computes from the Election aggregate and returns a read model → a **derivation**, not an independent truth-owner.
- **Architecture-fitness:** existing — transition-guard tests.
- **Quality: Direct · Level L2 · Confidence Medium.**
- **Interpretation:** behaves as a **derivation/service over the Election aggregate** (read-model snapshot); "separate BC" looks **falsifiable**; closely bound to Election/Voting.

## Voting  *(L3 read DONE)*
- **Observed (provenance):** `Vote extends BaseVote` (table `votes`); casts `no_vote_option`, `no_vote_posts`, **`device_metadata_anonymized`**, `cast_at`; `candidate_01..60` JSON columns; **`results()` hasMany Result**; **no `user_id`**; `Domain/Voting/{VotingEngine,VoteAggregator,QuorumRule}`; tests `VoteControllerConstitutionalTest`. **[EV-070/071]**
- **Business behaviour:** record an anonymous verifiable vote; spawn Result records. **Technical:** Eloquent Active-Record model; anonymity at schema (no `user_id`, anonymized device metadata).
- *owns decisions?* vote validity · *owns truth?* **YES (anonymous vote records = SoR; Results derived via `hasMany`)** · *txns?* per-vote · *events?* `VoteAccepted` (inferred) · *independent reason to change?* when ballot/vote structure changes.
- **Supporting:** EV-070/071. **Contradictory (tactical, not BC-existence):** `Vote` is an **Active-Record model** (domain+persistence mixed) — an aggregate-design concern for Round 50, not a boundary contradiction.
- **Architecture-fitness:** existing — `VoteControllerConstitutionalTest`; missing — anonymity-reconstruction (Q7) test, pure-aggregate test.
- **Quality: Direct · Level L2 (L3-partial via `results()`) · Confidence Medium-High.**
- **Interpretation:** **truth-owning context** (anonymous vote SoR); **Results = projection confirmed** (`hasMany Result`); engine ≠ BC.

## Evidence
- **Observed (provenance):** `ReplayEvidenceEnvelope.php::computeHash()` (`readonly`, frozen evidence, deterministic hash, schema-versioned, **hashed** `voterIdentifier`); `EvidenceClassification/Snapshot`, `EvaluationAuditTrail`; test `ConstitutionalEvidenceSnapshotTest`. **[EV-010/011]**
- **Business behaviour:** preserve the reviewable, immutable record an evaluation used. **Technical:** immutable VO + deterministic hashing.
- *owns truth?* **YES (immutable SoR)** · *txns?* append-only immutable · *independent reason to change?* changes when the evidence record schema changes (cohesive).
- **Supporting:** EV-010/011. **Contradictory:** none observed.
- **Architecture-fitness:** existing — `ReplayDeterminismContractTest`, `ConstitutionalEvidenceSnapshotTest`; missing — none critical.
- **Quality: Direct + Strong (tests) · Level L2–L3 · Confidence High.**
- **Interpretation:** strong truth-owning context candidate.

## Replay
- **Observed (provenance):** `ReplayDeterminismContractTest.php` ("same evidence input → same evaluation output, every time"); `ReplaySession`, `ReplayCertification`, `GovernanceReplayService`, `ScopeAwareReplayValidator`; events `ReplaySessionOpened/CertificationIssued/DivergenceDetected`. **[EV-020/021]**
- **Business behaviour:** *verify* that an election evaluation reproduces (integrity). **Technical:** deterministic-replay contract over the evidence pipeline.
- *owns decisions?* no · *owns truth?* **no (operates over Evidence)** · *independent reason to change?* changes when verification/determinism rules change — **tied to Evidence schema.**
- **Supporting (capability hypothesis):** EV-020 (determinism contract), depends on `EvidenceEnvelope`. **Contradictory (it owns something):** owns `ReplaySession`/`ReplayCertification` aggregates → *some* lifecycle of its own.
- **Architecture-fitness:** existing — determinism contract test; missing — boundary-isolation test (Replay vs Evidence).
- **Quality: Direct + Strong · Level L2–L3 · Confidence Medium.**
- **Interpretation:** likely a **verification capability / application service over Evidence** — but the `ReplaySession`/`Certification` aggregates are *contradictory* evidence to be weighed in 49-05.

## Audit
- **Observed (provenance):** `ElectionAuditService.php::log(...)` → JSONL files, rotation (100 MB), 30-day retention, categories; deps `Illuminate\Support\Facades\File`, `Carbon`; stores **IP in full** (operational events). **[EV-040/041]**
- **Business behaviour:** record operational events for accountability. **Technical:** fire-and-forget filesystem logger.
- *owns decisions?* no · *owns truth?* no (observability logs) · *dependencies?* **infrastructure (filesystem/clock)** · *independent reason to change?* changes when log format/retention/storage changes (infra cohesion).
- **Supporting (infra hypothesis):** EV-040/041 (File/Carbon deps). **Contradictory:** none — no domain decision observed.
- **Architecture-fitness:** existing — `ReplayDeterminismAuditTest`; missing — n/a (infra).
- **Quality: Direct · Level L2 · Confidence Medium.**
- **Interpretation:** behaves as **Infrastructure/Platform**, not a domain BC. *(Anonymity flag: full-IP storage in operational logs — not vote-linked, but record for the L3 Anonymity check.)*

## Contestation
- **Observed:** zero files (challenge/appeal/standing/dispute). **[EV-090]**
- **Quality: Direct (absence) · confirmed Expected absence.**
- **Interpretation:** genuine, *expected* gap (never implemented). Absence ≠ wrong BC (symmetry principle).

---

## Emerging Patterns (observation only — NOT decisions)
- **EP-1 — Truth ownership is rarer than expected.** Only **Evidence** (EV-010/011) and **Voting** (EV-070) clearly own authoritative truth; Authorization (EV-030), Replay (EV-020), Audit (EV-040), and Adjudication-as-arbitration (EV-002) own *decisions/behaviour* but no persistent SoR. *Status: observation.*
- **EP-2 — Several certified concepts exist as deterministic services/capabilities, not truth-owning contexts.** Authorization (pure resolver), Replay (determinism contract), Adjudication (stateless kernel). *Status: observation.*
- **EP-3 — Infrastructure concerns surface in Audit** (filesystem/clock/rotation) — an infra, not domain, signal. *Status: observation.*
- **EP-4 — "Constitutional" vocabulary spans two subsystems** (Membership committee/jurisdiction vs Election trust/evidence). Naming-collision pattern. *Status: observation.*

## Dossier status
| Candidate | Quality | Level | Conf | Deeper L3 owed? |
|-----------|---------|-------|------|-----------------|
| Adjudication | Direct | L2–L3 | Med | scope question |
| Authorization | Direct | L2 | Med | — |
| Appointment | Direct | L2 | Med | — (done — owns Mandate truth) |
| Lifecycle | Direct | L2 | Med | — (done — derivation over Election) |
| Voting | Direct | L2 | Med-High | — (done — owns vote SoR) |
| Evidence | Direct+Strong | L2–L3 | High | — |
| Replay | Direct+Strong | L2–L3 | Med | — |
| Audit | Direct | L2 | Med | — |
| Contestation | Direct (absence) | — | High | n/a |

**No decisions recorded.** All nine candidates now at Direct evidence (L2+). **Ready for `49-05` EBSD Evaluation.**

### Updated Emerging Pattern
- **EP-5 — A prior was falsified by L3.** The "Appointment → merge into Authorization" prior reversed: **Appointment (Authority/Delegation) owns Mandate truth; Authorization owns none** → if anything merges, it is **Authorization** (a service), not Appointment. *(Evidence EV-030, EV-050/051. Observation only.)* **Next:** finish those → `49-05` EBSD Evaluation (Hypothesis · Null · Evidence-supporting · Evidence-contradicting · Analysis/alternatives · Confidence · Decision · Reason · Outstanding-uncertainty) → `49-06` BDR (Decision · Reason · Evidence-ref only).

---

*Round 49-04 v1.1 — Behavioral Evidence Dossier — ISSUED (empirical notebook; evidence only).*
*Observed-fact vs Interpretation SPLIT; contradictory evidence recorded; provenance (class/method/test); business-vs-technical behaviour; evidence-quality (Direct/Strong/Moderate/Weak) + Level L1-L3; "independent reason to change" (Evans); architecture-fitness evidence; Emerging Patterns EP-1..4 (observation only). NO decisions. Next: finish L3 (Appointment/Lifecycle/Voting) → 49-05 Evaluation → 49-06 BDR.*
