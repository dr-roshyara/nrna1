# EPIC-004A Adjudication Tactical Discovery

**Kind:** evidence document — NOT a design document. Answers *"what must the Tactical Model explain?"* without designing how software represents it.
**Authorization chain:** Strategic Baseline accepted (EPIC-002) · Entry Assessment accepted (EPIC-003) · Adjudication authorized (ARB, 2026-07-25) · **Tactical Work Package accepted and FROZEN (EPIC-004; Q-1/Q-2 remain pending ARB items per its §5b and carry into §9 below).** Tactical DDD itself remains unauthorized.
**Governing principle applied throughout:** *"Am I discovering something that exists in the business, or inventing how software should represent it?"* Everything below is the former. Uncertainty is preserved, not resolved.
**Evidence base:** the four EPIC-003 codebase inventories (2026-07-25), the accepted strategic artifacts, the four Constitutional Policies, and the ADR record. Every claim traces to one of these.

---

## 1. Business Vocabulary Inventory

| Term | Definition (as evidenced) | Where it appears | Who owns it | Confidence |
|---|---|---|---|---|
| **Determination** | The binding ruling on a contested outcome | `DeterminationIssued` docblock; Canonical Event Catalog (Decision/Core, restricted); `determinations` table | Adjudication (sole producer) | HIGH |
| **Challenge** | A contest raised against an outcome; *requests* a determination, never creates one (TP-2) | `Challenge` aggregate; `challenges` table | Contestation | HIGH |
| **Contested Outcome** | Election + target type + target id; target types are a documented CLOSED SET (ElectionResult, Determination) | `ContestedOutcomeRef` in both Adjudication and Contestation (local VOs, string crossing per ADR-T16); ADR-UL-01 | Shared language, locally reconstructed | HIGH |
| **Evidence (envelope)** | An opaque hash reference to an evidence envelope "owned by the Evidence context" — **whose owner was never built**; the strategic Collection & Aggregation BC is the referent | `EvidenceEnvelopeRef` (non-empty is its only rule) | **Unresolved — the central vocabulary gap** | LOW / OPEN |
| **Sufficiency** | The evidence-adequacy judgment a determination presupposes; strategically grounded in ES declare-failure and P2 | Strategic record only; **no code counterpart anywhere** | Adjudication (strategically) | MEDIUM (strategy) / ABSENT (code) |
| **Legitimacy** | **THREE distinct senses coexist — a semantic collision, discovered, not resolved:** (a) Adjudication's enum — is the *contested outcome* Legitimate/Illegitimate (command input; rules deferred, ADR-T17); (b) legacy `LegitimacyOutcome` — may this *voter's act of voting* proceed (Allowed/Denied/Deferred/Investigate, from `ConstitutionalLegitimacyDecision`); (c) Governance's enum — is a *governance decision* LEGITIMATE/REVOKED/DISPUTED/UNAUTHORIZED | Three different namespaces, three different subjects | Three different owners | **The word is overloaded — flagged (§9)** |
| **Authority (issuing)** | "A constitutional oversight body" — an opaque string; no model, no role check, no appointment rule (Q-1). Separately, Governance owns a real authority-delegation model (`AuthorityChain`, `AuthorityResolver`, delegation events) — a potential donor, not yet connected | `IssuedByAuthority`; Governance context | **OPEN (Q-1)** | LOW |
| **Declare Failure** | The obligation never to certify on insufficient evidence; the customer's refusal power on COL-1 | Strategic record (ES family; P2); COL-1 pattern rationale | Adjudication (strategic) | HIGH (strategy) / ABSENT (code) |
| **Integrity Quarantine** | The controlled pending state a publication enters on a detected integrity anomaly, until an authority determines significance | Constitutional Policy 4 (policy-born term; **not yet in any code** — current behavior is the ruled-against auto-correct) | Publication/integrity side produces it; Adjudication consumes it as a business trigger | HIGH (policy) / ABSENT (code) |
| **Correction** | The Election-side consequence of an Upheld determination — contained-only, forward-only; Dismissed yields deliberate silence (D-02) | `ElectionCorrectionPolicy`; `ElectionCorrectionApplied`; ADR-T8 | Election | HIGH |
| **Resolution** | The Contestation-side terminal acknowledgment that the loop completed (Upheld via correction; Dismissed via short-circuit) | `Challenge::resolve`; `Resolution` (application-level) | Contestation | HIGH |
| **Publication / Superseding Constitutional Publication** | Legacy: a terminal act (two parallel paths, chief-gated, no re-open). Policy 1: a chain of publications where a later one *supersedes* — never replaces — an earlier one | Legacy publish flow; Constitutional Policy 1 (**superseding form unbuilt**) | Legacy: election management. Superseding form: **a hotspot (§8)** | HIGH (policy) / ABSENT (code) |
| **Jurisdiction** | Opaque VO on a determination; semantics never elaborated anywhere found | `Jurisdiction` VO | Adjudication | LOW — meaning undiscovered |
| **Contestation Window / Evidence Preservation Window / Adjudication Horizon** | Policy-2-born: the period a challenge may be initiated; the retention envelope (window + max adjudication duration + legal margin); the adjudication-duration component this context's model will imply | Constitutional Policy 2; Q-2 | **OPEN (Q-2)** | HIGH (policy) / UNDEFINED (terms) |

## 2. Decision Inventory

| Decision | Triggering condition | Outcome | Evidence source | Implementation status |
|---|---|---|---|---|
| Is the evidence sufficient for a determination? | A routed challenge presented for judgment | Determination possible — or a declare-failure demand back to Collection (COL-5a) | The evidence body (COL-1) + custody attestation (COL-3a) + the fixed record (COL-2) | **ABSENT** — the judgment half of the context |
| Is the contested outcome legitimate, and is the challenge upheld or dismissed? | Judgment concludes | `Legitimacy` × `DeterminationOutcome` (the ruling content) | Same as above; rules deferred by ADR-T17 | **Recording implemented; deciding ABSENT** (supplied as command input) |
| Is the issuing authority valid? | A determination is to be issued | Valid/invalid issuer | **None — no model (Q-1)**; only a non-empty-string check | ABSENT |
| Is this challenge ready for adjudication? | Determination presented for a challenge | Proceed only when Routed | Challenge state | **Implemented** (`canProceedToAdjudication`; conflict/replay exceptions) |
| Is a correction required, and of what shape? | A determination arrives at Election | Upheld → ContainedOnly; Dismissed → silence | The determination's outcome | **Implemented** (`ElectionCorrectionPolicy`, D-02) |
| Is quarantine required? | An integrity anomaly is detected (e.g., checksum mismatch at publish) | Publication enters the controlled pending state | Automated detection output | **ABSENT** — ruled by Policy 4; current code does the opposite (auto-correct) |
| May publication proceed after quarantine? | Authority reviews a quarantined anomaly | Proceed unchanged / proceed with authorized correction / reject | The authority's determination | ABSENT |
| One determination per challenge? | A second issuance attempt for the same challenge | Refused | Uniqueness check | **Implemented** (service guard + DB unique index) |
| Does a determination lack election scope? | A v1 payload reaches the Election reaction | Business incompatibility → dead-letter, never a silent drop | Payload schema | **Implemented** |

## 3. Invariant Candidates (collected, not validated)

One determination per challenge (implemented) · determination content fixed once at issue, `Final` emits nothing (ADR-T19) · **detection may be automatic; correction may not** (Policy 4) · a published result is never modified or deleted; corrections supersede (Policy 1) · forward-only — no reversal, no rescind; anonymity forbids un-casting (ADR-T8/T11) · no adjudication artifact may carry voter↔vote linkage (ADR-T11) · evidence for a permissible challenge never expires while the challenge can be initiated or resolved (Policy 2) · every custodial-linkage lookup itself becomes evidence (CL-2) · a Challenge requests a Determination and never creates one (TP-2) · one CorrelationId mint per constitutional conversation; Adjudication is today's chain start and becomes `fromConsumed` when the raise path lands (ADR-MP-06, in-source note) · one transaction writes one aggregate root plus its outbox rows (ADR-T1/T14).

## 4. Lifecycle Inventory (lifecycles named; no state machines produced)

- **Determination:** prepared → issued → finalized; terminal and silent at the end. *(Implemented.)*
- **Challenge:** raised → admitted → (investigating) → routed → adjudicated → resolved; alternates dismissed, lapsed. *(States exist; the raising end has no production trigger.)*
- **Correction:** applied once per determination, or deliberate silence. *(Implemented; append-only ledger.)*
- **Integrity Quarantine (policy-born, unbuilt):** detected → quarantined → authority determination → released, corrected, or rejected.
- **Publication:** legacy single terminal act today; Policy 1 implies a supersession chain (publication v1 → determination → superseding publication v2 → …) whose lifecycle terms are undiscovered.
- **Evidence Preservation Window:** opens with the act/election; closes when a challenge can no longer be initiated or resolved — terms undefined (Q-2).

## 5. Responsibility Inventory (who knows / decides / owns / observes — no software objects assigned)

- **Knows the evidence body:** strategically Collection & Aggregation; today, factually, the legacy trust-evidence pipeline and the custodial artifacts.
- **Decides sufficiency and the ruling:** the adjudicative authority — *strategically*; **nobody today** (the recording nucleus explicitly receives the ruling pre-decided).
- **Owns the ruling record:** Adjudication — sole producer, exactly-once, content-fixed-at-issue. *(Implemented and proven.)*
- **Decides correction shape:** Election, by its own policy. *(Implemented.)*
- **Observes and acknowledges completion:** Contestation (advances the challenge on determination and correction); auditors observe the whole conversation via the provenance chain.
- **Owns authority appointment:** **OPEN (Q-1)** — the Governance context's delegation model is the evident candidate donor; connecting them is a business/governance decision, not a discovery conclusion.
- **Owns the publication act:** legacy: the chief role, via the management controller. The superseding publication's owner is undiscovered — hotspot.

## 6. Information Ownership

| Information | Producer | Consumer(s) | Nature of crossing |
|---|---|---|---|
| `DeterminationIssued` | Adjudication (sole; restricted) | Election, Contestation — explicitly NOT Voting | Catalog-governed published event; schema-versioned (v1/v2) |
| `ElectionCorrectionApplied` | Election | Contestation | Carries Election-owned identity only — no ChallengeId; correlation via determinationId |
| `ChallengeAdjudicated` / `ChallengeResolved` | Contestation | Nobody (deliberate chain terminus) | Resolution enrichment lives on the integration event only, never the domain event |
| Challenge ref, evidence ref, election/target ids | Various | Various | Opaque strings; every context reconstructs local VOs (ADR-T16) — a trust boundary at every crossing |
| Custody attestation | Custodial Integrity (strategically); today the fragmented custody artifacts | Adjudication as customer (COL-3a) | Supplier obligation shaped by admissibility demands |
| Public verification result | Self-Verifying Integrity (future) | Any observer; Adjudication never privileged | Published Language (COL-3b) — nothing exists yet |
| Correlation/causation | `EventProvenance` | The audit chain | Integration events only — never domain events (hard invariant) |

## 7. Existing Code Evidence (per discovered responsibility)

| Responsibility | Exists | Missing | Contradicts architecture |
|---|---|---|---|
| Ruling recording, uniqueness, lifecycle | `app/Contexts/Adjudication` — complete, tested, qualified | — | — |
| Judgment (sufficiency → ruling) | — | **Entirely** — no policy, no rules, empty `Domain/Policies/`; declared "upstream" with no upstream | — |
| Issuing trigger (production path) | — | **Entirely** — zero production callers; staged construction, documented in-source | — |
| Reaction tail (correction, resolution) | Election + Contestation reactions — complete, wired, scheduled | — | — |
| Challenge raising | Aggregate methods + states exist | Production trigger, raise-path handlers, raise-time columns (deferred by design) | — |
| Authority validity | — | Everything beyond a non-empty string | Governance's authority model exists but is unconnected — evolution lag, not violation |
| Integrity anomaly handling | Detection exists (checksums, verification methods) | Quarantine state, authority determination path | **Yes — the auto-correcting publish sweep does the opposite of Policy 4** (now ruled; remediation is tactical work) |
| Publication supersession | Legacy terminal publication exists | The superseding chain (Policy 1) | **Yes — terminal `results_published` with no outgoing transitions cannot express supersession** (ruled; landing point defined at policy level) |
| Evidence vocabulary | Rich trust-evidence model (two generations) + custody artifacts | An adjudication-facing evidence body; the envelope's owner | The three-way **Legitimacy collision** (§1) — vocabulary, not structure |

## 8. Candidate Modeling Hotspots (areas Tactical DDD must resolve — no solutions offered)

1. **The judgment process** — how evidence sufficiency and the ruling are decided (the entire missing half; carries K1/P2: never automatic).
2. **Declare-failure** — what insufficiency demands of Collection, concretely (COL-5a's contract).
3. **The authority model** — pending Q-1's answer; including whether Governance's delegation machinery is the donor.
4. **The quarantine → challenge trigger** — the loop's head (see finding F-3 below on its cross-context nature).
5. **The superseding publication contract** — Adjudication's output side of Policy 1 (consumer-facing, business-level).
6. **The Legitimacy vocabulary collision** — which sense the ubiquitous language reserves the bare word for, and what the other two senses get called.
7. **Evidence-source alignment** — whether the trust-evidence vocabulary becomes adjudication's canonical evidence source (T-6's deliberately-open decision).
8. **The Evidence Preservation Window's adjudication-horizon component** — what the tactical model implies for Q-2's terms.

## 9. Tactical Discovery Findings

**Now understood:** the recording half of Adjudication is complete, proven, and consistent with the strategic model; the reaction tail of the loop is production-wired; the four policies translate into concrete, discoverable business decisions and invariant candidates (§§2–3); the vocabulary is mostly firm, with three well-bounded open areas (evidence envelope, authority, window terms).

**Remains uncertain (preserved, not resolved):** the business content of sufficiency standards (the strategic record's differentiated-scrutiny echo suggests the *kind* of answer, not the answer); `Jurisdiction`'s actual meaning; the superseding publication's lifecycle terms; the evidence envelope's internal semantics.

**Requires ARB clarification (three items — the first two carried from the frozen package, the third NEW from this discovery):**
- **Q-1 (carried):** who may issue a determination — the authority model, and whether Governance's delegation machinery is its donor.
- **Q-2 (carried):** the Contestation Window's terms (and with them the preservation window's arithmetic).
- **Q-3 (NEW):** **the loop's head crosses the package boundary.** T-2 (recover the missing production path, including the quarantine entry) ends in *raising a Challenge* — which is Contestation's responsibility, and Contestation is explicitly not-in-scope per the frozen package's §3. The quarantine path runs: detection → quarantine → authority determination → **Challenge raised (Contestation)** → routed → **Determination issued (Adjudication)**. Discovery cannot resolve whether the Adjudication iteration's T-2 covers the Contestation-side trigger, or whether a separate (possibly minimal) Contestation work package is required first. **Reported, not resolved.**

---

**Self-review (per instruction):** every conclusion above cites its evidence (inventory reports, policies, ADRs, strategic artifacts) ✅ · no tactical model exists — no aggregate boundaries invented, no responsibilities assigned to software objects (all responsibility statements are business-actor statements) ✅ · lifecycles are named, no state machines drawn ✅ · uncertainty is preserved and labeled, including three explicit ARB questions ✅ · the discovering-vs-inventing test was applied at every section ✅.

**Completion claim submitted to the ARB:** *the Tactical Model for Adjudication must explain everything in §§1–8 — and nothing here has designed any of it.*

**Stop condition: STOP.** No aggregate discovery, no Tactical DDD, no tactical artifact of any kind. Await explicit ARB review of this discovery — including disposition of Q-1, Q-2, and the new Q-3 — before the next phase is authorized.

---

## ACCEPTED WITH RULINGS (ARB, 2026-07-25 — 7.8/10; the LAST preparatory document)

- **Accepted as sufficient evidence.** With a recorded criticism: §§5 (Responsibility Inventory), 6 (Information Ownership), and 8 (Modeling Hotspots) drifted from *"what must the Tactical Model explain?"* into *"how should Tactical DDD think about the model?"* — raw materials Tactical DDD derives itself. The stronger scope would have ended at vocabulary, decisions, lifecycles, code-evidence reconciliation, and unresolved business questions.
- **Q-1 and Q-2 remain open business questions** (unchanged).
- **Q-3 is REJECTED as an ARB question and reclassified: Tactical Collaboration concern.** Processes crossing bounded contexts are normal — that is what process managers, domain events, and choreography exist for; a cross-context flow implies neither a defective work package nor a second authorization. Handled during collaboration modeling, off the ARB agenda.
- **The Tactical Discovery phase is CLOSED. No further discovery or governance artifacts.** Architecture reduces uncertainty; it does not eliminate it before allowing design.
- **Execution rule from here (binding):** one Tactical DDD artifact → ARB review → refine → freeze → next artifact. Sequence: Aggregate Discovery → Aggregate Boundaries → Aggregate Responsibilities → Invariants → Value Objects → Domain Events → Commands → Repositories → Domain Services.
- **Next authorized activity: Aggregate Discovery for the Adjudication bounded context** — the first Tactical DDD artifact.

---
*Frozen charter: `EPIC-004_Adjudication_Tactical_Work_Package.md` · Constitutional inputs: EPIC-002 baseline, EPIC-003 Entry Assessment (§THE FOUR DECISIONS, Special Reviews, Risk Register) · Evidence: the four 2026-07-25 codebase inventories, ADR-T-LOG, ADR-MP series, Canonical Event Catalog.*
