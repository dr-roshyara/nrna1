# Round 49-05 — EBSD Evaluation (reasoning → decisions)

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** EBSD — evaluation · **Built against:** Landscape v1.0 · **Evidence:** `Round49-04` Dossier (EV-IDs)
**Status:** ⚖️ EVALUATION — turns Dossier evidence into reasoned verdicts via one **fixed template per candidate**. Decisions recorded compactly in `Round49-06` (BDR).
**Date:** 2026-06-26

> **Separation:** `49-04` = Evidence · **`49-05` = Reasoning (this)** · `49-06` = Decisions. Falsification: each candidate's **null hypothesis = "NOT an independent BC."** Verdict taxonomy from `Round48A` v1.1; tests weighted (Primary T1 decisions / T2 truth / T5 transactional-autonomy decisive). **Evidence Sufficiency (Yes/No)** = was today's evidence enough to decide, or could runtime evidence overturn it?

*Template: Candidate · Question · Null · Supporting · Contradicting · Alternatives · Analysis · Confidence · Decision · Reason · Outstanding uncertainty · Future falsification · Evidence sufficiency.*

---

### Evidence
- **Question:** is Evidence an independent truth-owning BC? **Null:** it is not.
- **Supporting:** EV-010/011 — immutable `ReplayEvidenceEnvelope` (deterministic hash, hashed voter id), `EvidenceClassification/Snapshot`. **Contradicting:** none observed.
- **Alternatives:** an aggregate inside a broader context — but no broader owner consumes/writes it; it is written-once, read-many.
- **Analysis:** Primary T1 (owns the evidentiary decision of record) ✓, T2 (owns authoritative immutable truth) ✓, T5 (append-only, own txn) ✓ — all Primary pass; no contradiction.
- **Confidence: High. Decision: Confirmed BC.** **Reason:** owns immutable system-of-record (EV-010/011). **Outstanding:** none material. **Future falsification:** if no context ever reads Evidence independently. **Evidence sufficiency: YES.**

### Voting
- **Question:** independent truth-owning BC? **Null:** not.
- **Supporting:** EV-070/071 — owns anonymous vote SoR (no `user_id`, anonymized device metadata); `results()` hasMany → Results derived. **Contradicting:** `Vote` is an Active-Record model (domain+persistence mixed — *tactical*, not boundary).
- **Alternatives:** part of Election aggregate — but Vote owns its own table/identity and spawns Results.
- **Analysis:** T1 ✓ (vote validity), T2 ✓ (vote SoR), T5 ✓ (per-vote txn); Anonymity invariant lives here.
- **Confidence: Medium-High. Decision: Confirmed BC.** **Reason:** anonymous vote SoR; Results=projection (EV-070/071). **Outstanding:** Active-Record → aggregate-design concern (Round 50). **Future falsification:** if vote ownership dissolves into Election. **Evidence sufficiency: YES** (boundary); aggregate shape deferred.

### Appointment (Authority / Delegation)
- **Question:** independent truth-owning BC? **Null:** not (merge into Authorization — the prior).
- **Supporting:** EV-050/051 — `DelegationStatus` (ACTIVE/REVOKED) + `DelegationLifecyclePolicy`; owns Mandate lifecycle/truth. **Contradicting:** evidence base is small (one policy + VO).
- **Alternatives:** a sub-part of an Oversight context.
- **Analysis:** T1 ✓ (delegation transitions), T2 ✓ (Mandate truth), T5 ✓ (per-delegation). **Prior FALSIFIED:** Appointment owns truth; Authorization does not → the *reverse* of the merge prior.
- **Confidence: Medium. Decision: Confirmed BC** (Authority/Mandate). **Reason:** owns Mandate truth (EV-050/051); EP-5. **Outstanding:** small evidence base; relation to Committee context. **Future falsification:** if Mandate proves to be only a value object inside another aggregate. **Evidence sufficiency: Medium** (Direct but thin).

### Contestation
- **Question:** independent BC? **Null:** not (belongs in Adjudication).
- **Supporting:** EV-090 — certified-promoted (S-5 standing); closes the correction loop. **Contradicting:** zero implementation (expected absence).
- **Alternatives:** a module within Adjudication.
- **Analysis:** T1 ✓ (owns the *challenge/standing* decision), distinct capability (T7 ✓) from Adjudication's determination; absence ≠ disproof (symmetry).
- **Confidence: High (that the capability is a genuine, expected gap). Decision: Confirmed BC — GREENFIELD.** **Reason:** distinct standing/challenge capability; not implemented (EV-090). **Outstanding:** whether it's standalone vs a module of Adjudication. **Future falsification:** if, when built, it cannot stand without Adjudication. **Evidence sufficiency: YES** (for "build it"); structure TBD.

### Adjudication
- **Question:** is certified Adjudication realized as a BC? **Null:** not realized.
- **Supporting:** EV-001/002/003 — a `ConstitutionalArbitrationKernel`+Decision exists. **Contradicting (decisive):** it adjudicates **committee/jurisdiction** (winner = `JurisdictionNode`), **NOT** certified election-determination/finality.
- **Alternatives:** (a) certified Adjudication = the jurisdiction arbitration (scope re-interpretation) — but certified Adjudication is about *election determinations/contestations*, a different subject; (b) jurisdiction arbitration is a *separate* concern/subdomain.
- **Analysis:** the certified election-determination Adjudication is **behaviorally absent**; what exists is a different (jurisdiction) responsibility (cohesion test: changes for committee/jurisdiction rules, not determinations).
- **Confidence: Medium. Decision: Confirmed BC (certified) — but UNREALIZED in code; existing jurisdiction-arbitration is a SEPARATE concern.** **Reason:** EV-002 (winner=JurisdictionNode ≠ election determination). **Outstanding (high):** is jurisdiction-arbitration its own BC/subdomain? Is certified Adjudication greenfield like Contestation? **Future falsification:** L3 of the election-result/certification path. **Evidence sufficiency: NO** (scope question open — defer the realization detail).

### Replay
- **Question:** independent BC? **Null:** not (capability over Evidence).
- **Supporting:** EV-020 — determinism contract over the evidence-evaluation pipeline; operates on `EvidenceEnvelope`. **Contradicting:** EV-021 — owns `ReplaySession`/`ReplayCertification` aggregates (some lifecycle of its own).
- **Alternatives:** application service · domain capability · thin BC.
- **Analysis:** T2 (owns authoritative truth) ✗ — verifies truth, doesn't own it; but it *does* own session/certification records (weak T1). Verifies, not decides.
- **Confidence: Medium. Decision: Application Capability** (verification over Evidence). **Reason:** EV-020 (verifies, owns no domain truth); EV-021 sessions are operational records, not domain SoR. **Outstanding:** the `ReplaySession`/`Certification` aggregates. **Future falsification:** if Replay must own a domain decision independent of Evidence. **Evidence sufficiency: NO** (runtime evidence could overturn → revisit after implementation).

### Authorization
- **Question:** independent truth-owning BC? **Null:** not (domain service).
- **Supporting:** EV-030/031 — pure, side-effect-free, deterministic resolver; owns no persistence. **Contradicting:** none (no truth ownership found).
- **Alternatives:** a domain service used across contexts; a capability within Appointment/Adjudication.
- **Analysis:** Primary T2 ✗ (owns no truth), T5 ✗ (no txn — pure function); T1 partial (computes a capability decision). A computation, not an owner.
- **Confidence: Medium. Decision: Supporting Subdomain / Domain Service** (capability resolution), NOT a truth-owning BC. **Reason:** EV-030/031 (pure resolver, owns nothing). **Outstanding:** which context hosts it (consumes Mandate from Appointment). **Future falsification:** if it acquires authoritative state. **Evidence sufficiency: Medium.**

### Election Lifecycle
- **Question:** independent truth-owning BC? **Null:** not (derivation over Election).
- **Supporting:** EV-060/061 — `compute(Election) → Snapshot`; "SSOT computed from signals … the Election aggregate root." **Contradicting:** none for "derivation."
- **Alternatives:** workflow service · read-model · part of Voting/Election.
- **Analysis:** T2 ✗ (owns no *separate* SoR — derives from Election), T5 ✗ (pure compute). A derivation, not an owner.
- **Confidence: Medium. Decision: Supporting Subdomain** (derivation/read-model + transition service over the Election aggregate); candidate **merge with Voting/Election**. **Reason:** EV-060/061. **Outstanding:** whether transition *enforcement* (vs computation) owns anything. **Future falsification:** if a lifecycle invariant needs its own txn. **Evidence sufficiency: Medium.**

### Audit
- **Question:** independent domain BC? **Null:** not (infrastructure).
- **Supporting:** EV-040/041 — fire-and-forget JSONL logger; deps `File`/`Carbon`; rotation/retention. **Contradicting:** none (no domain decision/truth).
- **Alternatives:** observability/platform capability.
- **Analysis:** T1 ✗, T2 ✗; dependencies are infrastructure (filesystem/clock).
- **Confidence: Medium-High. Decision: Infrastructure / Platform Capability**, NOT a domain BC. **Reason:** EV-040/041 (filesystem logger, owns nothing). **Outstanding:** full-IP storage → Anonymity review (operational, not vote-linked). **Future falsification:** if audit ever drives a business decision. **Evidence sufficiency: Medium-High.**

---

## Non-candidate confirmations (recorded, not BCs)
- **Results** → **Derived Read Model** (confirmed: `Vote::results()` hasMany; reconstructable). High.
- **Legitimacy** → **Derived Read Model** (single resolver `LegitimacyOutcome`, not persisted). High.
- **Anonymity** → **Architectural Invariant** (supreme; schema-level: no `user_id`). High.
- **Trust-Anchor / Consent** → **External boundary** (not in software; device-PKI "Trust" is a distinct concern — GI-2). High.

## Evaluation summary (→ BDR 49-06)
**Confirmed BCs (4):** Evidence · Voting · Appointment(Authority) · Contestation(greenfield). **Confirmed-but-unrealized / scope-deferred (1):** Adjudication. **Application Capability (1):** Replay. **Supporting Subdomain/Service (2):** Authorization · Lifecycle. **Infrastructure (1):** Audit. **Read Models (2):** Results · Legitimacy. **Invariant:** Anonymity. **External:** Trust-Anchor/Consent.

**Result: of 8 owning candidates, ~4 confirmed BCs; the rest merge/downgrade to capability/service/infrastructure — the certified governance concepts are UNCHANGED throughout (semantic ≠ software ownership).** The falsification process did real work (it rejected/downgraded 4 of 8).

---

*Round 49-05 — EBSD Evaluation — ISSUED (reasoning; decisions → 49-06 BDR).*
*Fixed template per candidate + Evidence Sufficiency. Confirmed BC: Evidence/Voting/Appointment/Contestation; Adjudication = Confirmed-but-unrealized (scope open, sufficiency NO); Replay = Application Capability (sufficiency NO, revisit post-impl); Authorization = domain service; Lifecycle = supporting/derivation; Audit = infrastructure. Prior FALSIFIED (Appointment owns truth, not Authorization). Certified concepts untouched. Next: 49-06 BDR (compact).*
