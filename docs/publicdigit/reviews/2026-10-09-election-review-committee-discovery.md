# Election case-review body ("Election Review Committee") — discovery report

**Date:** 2026-10-09 · **Commission:** `docs/publicdigit/election_management/brainstorming/20261009_2121_election_management.md` · **Nature:** DISCOVERY ONLY.
- No production code, schema, ADR, story or Membership artefact changed; no test run.
- **Labels:** FACT (`path:line`) · [I] inference · RECOMMENDATION · DECISION REQUIRED (PO / ARB / Domain Owner).
- **Sources:**
  - three read-only investigations (dispute resolution; candidate representatives; terminology and authority);
  - the lifecycle / audit investigation `2026-10-09-election-lifecycle-and-audit-from-state-investigation.md`.

## 1. Canonical terminology (evidence)

| Term | Status | Meaning | Evidence |
|---|---|---|---|
| **Election Review Committee** | **NOT CANONICAL**: appears only in the commission brief, which itself flags the name as unconfirmed | — | brief `:3,23-29,50-56` |
| **Issuing authority** (`IssuedByAuthority`) | **RATIFIED concept**, implemented as an opaque string | The constitutional body without which no Determination exists | `app/Contexts/Adjudication/Domain/Determination/IssuedByAuthority.php:10-12`; ADR-UL-01 (ARB 2026-07-08) |
| **Constitutional delegation for adjudication** | **DECIDED and FROZEN** (EPIC-004 Q-1) | "Only an actor holding a valid constitutional delegation for adjudication" may issue; **Governance owns** the authority model; Adjudication must never duplicate it | `docs/implementation/EPIC-004_Q1_Authority_Resolution.md:4,11-15,25` |
| **ChallengeAdjudicationBody** | Research ADR-5 **APPROVED**; **form DEFERRED** (internal committee "Option B" vs external organisation "Option C") | An independent body adjudicating challenges | `docs/architecture/design/Round37-05_ADR-5_Challenge_Architecture.md:312-330`; `Round37-07_ADR-7…:240-252` |
| **Election Committee** (Manifesto) | Rules ADOPTED (act v4); Manifesto **unratified** (EM-OPEN-017) | An **acceptance** body: at least 3 members, ⌈2n/3⌉, independent of the Chief. **Adjudicative power deliberately left open (EM-OPEN-071)** | `docs/publicdigit/business_rules/ELECTION_MANIFESTO.md:136-145,521`; code `app/Contexts/Election/Domain/OperatingCore` |
| Election officers (Chief / Deputy / Commissioner; "Wahlausschuss" in the UI) | Implemented | **Operate** the election ("operational authority, not sovereignty", EM-GOV-008) | `app/Policies/ElectionPolicy.php`; ADR_20261004_1549 `:111-123` lists three different "Election Committee"s |
| Appeals Committee, Election Commission, tribunal, Wahlprüfung | Not canonical; "Election Commission" explicitly **does not exist** | — | `2026-08-16-governance-report-acceptance-participants.md:19` |

**RECOMMENDATION:**
- Do **not** introduce "Election Review Committee" as a new term.
- The capability is the **issuing authority for election Challenges**. "Which concrete body holds that authority" is a Governance fact (a delegation), not a new bounded context.
- A ubiquitous-language ruling (EM-GOV-030 naming discipline) decides the display name. **DECISION REQUIRED.**

## 2. Mandate and responsibility boundaries

| Concern | Owner (FACT) | Not owned by the review body |
|---|---|---|
| Filing and lifecycle of a case | **Contestation**: `Challenge` (Raised → Admitted → [Investigating] → Routed → Adjudicated → Resolved; Dismissed; Lapsed) | — |
| Running the case and receiving the ruling | **Adjudication**: `AdjudicationProcessManager`, `Determination` (Draft → Issued → Final; Upheld / Dismissed) | — |
| Who may decide (authority) | **Governance** (delegation; Q-1) | Adjudication / Election must not re-implement it |
| Consequence for the election | **Election**: `DeterminationIssuedReactionHandler` → `ElectionCorrectionApplied(ContainedOnly)` (forward-only, ADR-T8) | Annulment / recount: an ADR is required |
| Election eligibility / voter list | **Election** (Chief governs `ElectionMembership`, EM-ENT-007; two-person suspension) | Review body today: **no jurisdiction** (`TargetType` is a closed set {ElectionResult, Determination}) |
| General Membership | Membership context | **Explicitly excluded** |

## 3. Actors, use cases and decision-authority matrix

| Use case | Actor | Today | Decided by |
|---|---|---|---|
| UC-1 File a challenge | Standing-holder (`RaiserStandingRef`, opaque) | Service only, **no product entry point** | **DECISION REQUIRED:** standing, target, window, channel (PBDIGIT-196; ADR-5 S-1…S-3) |
| UC-2 Admit / dismiss / investigate | ? | Admit implemented; dismiss / investigate domain-only | **DECISION REQUIRED** (PBDIGIT-205) |
| UC-3 Route to adjudication | System | Implemented (outbox → process manager) | — |
| UC-4 Decide (rule) | **Issuing authority** | **No intake**: `receiveRulingDecision()` has no production caller (PBDIGIT-177 not authorised, after PBDIGIT-178) | Governance delegation (Q-1); body form open (ADR-7) |
| UC-5 Collective decision (if the authority is a committee) | Committee seats | **Engine exists for acceptance gates only** (seats, accept / object, ⌈2n/3⌉, dissent, vacancies) | **DECISION REQUIRED:** reuse for rulings? quorum, majority, recusal |
| UC-6 Apply consequence | Election | Implemented, record-only | ADR-T8 |
| UC-7 Appeal | Standing-holder | Only by challenging a prior Determination (`TargetType::Determination`) | ADR-5 E.5 says final within the cycle; **DECISION REQUIRED** |
| UC-8 Object to voter eligibility / suspension | ? | **Not modelled** | **DECISION REQUIRED:** a new `TargetType` needs an ADR-UL evolution plus the termination rule (EM-OPEN-004 / PBDIGIT-68 BR-1) |

## 4. Lifecycle model (reuse, not invent)
**RECOMMENDATION:** no new aggregate. A review case **is** a `Challenge`; a review decision **is** a `Determination` issued by the delegated authority. The collective decision, if adopted, is the **procedure behind the authority's ruling** (an adapter on the WP-4D intake port), not a second case lifecycle.

```
Challenge:     Raised ─admit→ Admitted ─route→ Routed ─(Determination issued)→ Adjudicated ─→ Resolved
                  └─dismiss→ Dismissed      └─investigate→ Investigating         (Lapsed on horizon)
Determination: Draft ─issue(authority)→ Issued ─finalize→ Final      outcome ∈ {Upheld, Dismissed}
[proposed] Ruling procedure (committee):  Open ─positions(seat)…→ Decided(⌈2n/3⌉ upheld | objections) | Unachievable(vacancies)
```

## 5. Invariants (testable predicates)
1. **Authority:** `issue(d) ⇒ holdsDelegation(d.authority, adjudication, d.jurisdiction, t)`. Governance answers; Adjudication only asks (Q-1).
2. **Independence:** `d.authority ∉ {operators of the contested election}`. The adjudicator is independent of the authorities it adjudicates (ADR-7 D.5); the Chief / Deputy who made a contested decision cannot rule on it. **Recusal rule DECISION REQUIRED.**
3. **One ruling per challenge:** `|{d : d.challenge = c ∧ d.state ∈ {Issued, Final}}| ≤ 1` (the process manager is idempotent today).
4. **Collective threshold (if adopted):** `upheld ⇔ accepts ≥ ⌈2n/3⌉` with `n ≥ 3`, one position per seat, positions irrevocable. These are the acceptance-gate rules (EM-GOV-033 / 036); reusing them for rulings needs a ruling.
5. **Closed jurisdiction:** `target(c) ∈ TargetType` (closed; extension needs an ADR-UL).
6. **Anonymity:** no case, ruling or audit record contains a voter ↔ ballot link (ADR-T11); evidence references, not ballot data.
7. **Tenant:** `org(c) = org(d) = org(election)`.
8. **Truthful audit:** each recorded transition is a constitutional transition. This **currently fails for the election lifecycle** (`from_state` stale), see the companion report. That defect matters if rulings are evaluated against lifecycle history [I].

## 6. Aggregate and bounded-context implications
- **No new bounded context.** Contestation, Adjudication, Election and Governance already own the parts (EPIC-002 canonical map ratifies five contexts; no Appeals context).
- **New code would be an adapter, not a model:** a Governance-backed `AuthorityDecision` intake (WP-4D) and, optionally, a committee-ruling procedure that reuses OperatingCore voting semantics.
  - Open contract name: AuthorityDecision / Assertion / Grant (Q-1 open item).
- **Persistence:** OperatingCore has **no repositories or adapters** (`ElectionCommitteeRepository`, `ProtocolAppend` unimplemented). Any collective ruling needs these first.

## 7. Security, audit and tenant isolation
| Concern | State |
|---|---|
| Separation of duties | **Gap G3 (ADR_20261004_1549):** one-actor `approve` / `suspend` routes still bypass the two-person rule. The two-person check compares proposer and confirmer **by name**, not by id (`ElectionMembership.php:243-246`) [weak] |
| Authority validation | `IssuedByAuthority` checks only non-emptiness; no Governance check yet |
| Audit | Outbox / inbox with provenance (good). Gaps: failure event not published (PBDIGIT-175); provenance chain broken at issuance (174); non-atomic issuance + outbox (178); timestamp drift (158); election lifecycle `from_state` (companion report) |
| Tenant | Contexts are organisation-scoped; no cross-context tenant test of the full loop (WP-8 / PBDIGIT-182 deferred) |
| Confidentiality | Ruling reasons / evidence free text must be screened for voter data (cf. EM-OPEN-091 for representatives) |

## 8. Existing stories and ADRs to reuse
**Reuse:**
- **PBDIGIT-196** (product entry point; standing, target, window, channel);
- **PBDIGIT-205** (admissibility / dismissal);
- **PBDIGIT-177 / 178** (authority intake / atomic issuance);
- **PBDIGIT-176** (Domain Owner Q1–Q4);
- **PBDIGIT-197 / 198 / 199 / 206** (finality, horizon, lapse);
- **PBDIGIT-184** (`Q-UC5` committee constitution);
- **PBDIGIT-191** (Appointment / Governance epic, not opened; needs PBDIGIT-189).

**ADRs:**
- ADR-UL-01;
- EPIC-004 Q-1;
- ADR-5 / ADR-7 (research);
- ADR-T8;
- ADR_20261004_1549;
- ADR_20260807_1500.

**No duplicate story should be created for "Election Review Committee"**; the capability is distributed over these.

## 9. Proposed first vertical slice and dependencies
The committee's **authority and procedure are not decided**, so this commission's stop condition applies: **no implementation slice can be authorised for the committee yet.**

**Smallest coherent slices, by the decisions they need:**

| Option | Slice | Needs |
|---|---|---|
| **S0 (no new decision)** | Close **G3**: route one-actor `approve` / `suspend` through the existing two-person rule, and compare actors by id. It hardens the separation of duties every review process depends on. | PO authorisation only (the ADR already decided the two-person rule) |
| **S1** | WP-4D **authority-decision intake** with an interim administrative adapter (one delegated actor), so any ruling can enter production | PBDIGIT-178 → 177 authorisation; Q-1 contract name |
| **S2** | **Committee ruling procedure** (reuse the OperatingCore voting semantics behind S1) | EM-OPEN-071 / ADR-7 body form; quorum / recusal rules; OperatingCore persistence |
| **S3** | **Filing entry point** (UI / HTTP) for ElectionResult challenges | PBDIGIT-196 PO contract |
| **S4** | Eligibility / suspension objections as a new jurisdiction | ADR-UL evolution; termination rule (PBDIGIT-68 BR-1) |

**RECOMMENDATION:**
- S0 now, if the PO authorises it (it needs no unresolved decision).
- Then S1 (the precondition for every ruling, collective or not).
- S2 / S3 only after the decisions in §10.

Each slice: RED → GREEN → regression → developer guide, under its existing story.

## 10. Decisions required (only PO / ARB / Domain Owner)
1. **Identity of the deciding body:**
   - (a) the Manifesto Election Committee with adjudicative powers (EM-OPEN-071);
   - (b) a separate ChallengeAdjudicationBody, internal or external (ADR-7 D.5);
   - (c) any holder of a Governance delegation (Q-1).
   - Also: adopt a display name?
2. **Appointing authority:** EM-OPEN-066 / 049 / 094. External; no default may be invented.
3. **Procedure:** quorum, majority (reuse ⌈2n/3⌉?), recusal and conflict of interest (EM-OPEN-069 remainder), abstention (D-9).
4. **Jurisdiction:** election results only, or also eligibility / voter-list / suspension objections?
5. **Standing, window, channel** (PBDIGIT-196; EPIC-004 Q-2); who decides admissibility (PBDIGIT-205).
6. **Finality and appeal:** final within the cycle (ADR-5 E.5)? PBDIGIT-176 Q1–Q4.
7. **Authorisations:** S0; PBDIGIT-178 → 177; PBDIGIT-184 `Q-UC5`; open epic 191 (PBDIGIT-189).

## 11. Risks
| Risk | Why it matters |
|---|---|
| Inventing the authority | Q-1 forbids duplicating it; EM-OPEN-049 / 066 forbid a default |
| A parallel case model | The Challenge / Determination model already exists and is ratified |
| Audit truthfulness | The lifecycle `from_state` defect is relevant evidence in disputes |
| Shared test database contention | Concurrent sessions: implementation evidence is unreliable until test runs are serialised |

## 12. Stop conditions (met)
- Authority and requirements for the review body are ambiguous (§10).
- **Stopped after discovery, as instructed.**
- No implementation performed.
