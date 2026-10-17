# Cross-Domain Organisational Architecture v1.1: Review Corrections, Executable Evidence and ARB Decision Package

**Date:** 2026-10-10 23:27 · **Author:** Claude (developer and researcher role)
**Supersedes:** `20261010_2314_final_cross_domain_architecture.md` (v1.0-candidate). v1.0 stays as the record; **this document amends it section by section** and does not restate unchanged parts.
**Responds to:** "Principal review of the consolidated cross-domain architecture" (2026-10-10). Verdict there: *accept as a strong candidate for the decision stage; do not ratify or implement unchanged.*
**Status:** **CANDIDATE. Authority: generated. Not ratified.** Nothing here approves a decision or authorises implementation. Election remains out of scope.

**Evidence labels (Finding 6), used throughout:**
- **Proposed:** written down, no approval.
- **Approved:** a recorded PO / ARB act exists.
- **Implemented:** the code exists in the repository (commit evidence).
- **Verified:** an executable check was **re-run in this session** and passed.
- **Accepted:** a formal acceptance act exists.

"Reported" means a claim taken from a repository record that I did not re-run.

---

## 0. Summary of what changed

| Review finding | v1.1 resolution | Evidence produced in this session |
|---|---|---|
| F1: policy ownership contradictory | **One owner per rule:** context-owned *policy modules*, plus one Authorization-owned *semantics and combination algorithm*. Grants come only from the module that owns the resource type (§2) | design |
| F2: cache exactness unproven | Record the **read set** (every fact and policy version the PDP read), and trust a cached entry only if the consumer **watermark** has reached the log head, or after a **synchronous version read** (§3) | **new model g8 P2:** the "obvious facts" vector trusted stale entries in 1,956 cases (978 unsafe permits); the full dependency closure *without* the lag rule still did in 960 (480 unsafe); watermark or synchronous read: **0** |
| F3: temporal contract incomplete | Two operations, `decideNow` and `reconstructDecision`, with explicit `v`, `k`, `t`, and a **DecisionRecord** that makes "as decided" replay exact (§4) | design |
| F4: Committee aggregate owns too much | Committee aggregate for committee-local invariants **plus a per-(person, organisation) `PositionHoldings` coordinating aggregate** for cross-committee incompatibility. JurisdictionAssignment becomes a separate candidate lifecycle (§5) | **new model g7:** per-committee aggregate alone broke cross-committee incompatibility in **18 / 20** interleavings; with the reservation aggregate: **0 / 20**. Local capacity: 0 / 20 in all strategies |
| F5: reuse the temporal pattern only after compatibility validation | XD-1 is **narrowed**: the pattern applies to *authoritative lifecycle relationships*, not to policy versions or forum content. A per-context compatibility test plan is added (§6) | design + plan |
| F6: progress labels too loose | Five evidence levels; one completion rule per gate; the gates were **re-scored against evidence re-run today** (§8) | Membership kernel **144 tests / 112,684 assertions OK**; 51 DB-free Geography and Governance unit-test files OK; oracles g3, g5, g7 and g8 re-run (exit 0) |
| **Self-correction (not raised by the review)** | **v1.0 §6.2 was unsound.** A prohibition must bind when its guard is *possibly* true (T or U), not only when it is T | **new model g8 P1** (5,625 cases): v1.0 rule (K1) gave **96 unsound permits**; absence-as-false gave **238**; corrected rule (K2) gave **0 unsound permits and 0 lost certain permits** |

**New research models** (research only, no production code; same conventions as `g3`–`g6`):
- `docs/publicdigit/membership/architecture/models/g7_governance_aggregate_boundary.py`
- `docs/publicdigit/membership/architecture/models/g8_pdp_guards_and_cache.py`

Run either with `python3 -I <path>`. The results are **verified within bounds**, never "proved".

---

## 1. Correction of v1.0 §6.2 and §6.4: guards over unknown facts (security-critical)

**What v1.0 claimed:** a prohibition whose guard is U does not fire, and this is safe because grants need T.

**What the model shows:** that is false. Counterexample (g8 P1, rule K1): grant `f1`, prohibition `f2`, facts `f1 = T`, `f2 = U`. K1 permits, yet the completion `f2 = T` is prohibited. An unknown fact can *hide* a prohibition, even though it can never *create* a grant.

**Corrected semantics, adopted as the v1.1 candidate.** Soundness criterion: *Permit only if every completion of the unknown facts permits.*

| Rule kind | Binds when its guard is | Reason |
|---|---|---|
| Grant (positive basis) | **T** (certainly true) | an unknown must not create authority |
| Mandatory condition | must be **T** | an unknown must not satisfy a requirement |
| Mandatory prohibition | **T or U** (possibly true) | an unknown must not hide a prohibition |
| Ordinary deny | **T or U** | same as prohibition |

**Result:** 0 unsound permits in 5,625 cases. It also loses **no** permit that every completion would grant, so within the bounds it is not over-conservative.

**Corrected safety invariant (replaces v1.0 A-1 / A-2):**

```text
Permit(F) ⇒ ∀ completions c of U-facts in F: Permit₂(c)        (soundness under uncertainty)
Permit(F) ⇒ ∃ grant g: guard(g,F)=T ∧ ∀ conditions γ: guard(γ,F)=T ∧ ∀ prohibitions/denies π: guard(π,F)=F
```

**Operational consequence:** a missing fact needed by a prohibition (for example "is under disciplinary suspension", with the source unavailable) produces `Deny(PROHIBITION_POSSIBLE)`, classified as *indeterminate*, so it can be routed to human review. Closed-world fact types (v1.0 §6.4) shrink the U-space and therefore reduce these indeterminate denials.

---

## 2. F1: Authorization policy ownership (resolved candidate)

### 2.1 Three responsibilities, three owners

| Responsibility | Owner | Artefact |
|---|---|---|
| Rule language, guard semantics (§1), combination algorithm, decision function, DecisionRecord | **Authorization** | `PolicySemantics` version (platform-level; changes only by ADR) |
| Rules about *who may do what to this context's resources*: read, post and moderate a forum; admit a member; appoint to a position | **The context that owns the resource type** (Forum, Membership, Governance, Organisation) | an immutable **PolicyModule** version: `(moduleId, version, resourceTypes, rules[], effectiveFrom)` |
| Platform-wide and organisation-wide prohibitions and conditions (tenant isolation, "suspended members may not post anywhere") | **Authorization** (platform module) + **Organisation** (organisation module) | PolicyModule versions as above, with **prohibitions and conditions only** |
| Facts used by the rules | their owning contexts | query ports (PIPs) |

### 2.2 Combination contract (single source of truth per rule)

```text
EffectivePolicy(resource, t) = PlatformModule@t ⊕ OrgModule(org(resource))@t ⊕ ResourceModule(type(resource))@t
grants      ← ResourceModule only          # no context can grant on another context's resources
prohibitions/conditions ← all three modules   # any level may restrict
```

- Every rule lives in exactly one module version. No rule is copied into a central PolicySet.
- **Activation:** the owning context activates a module version only after Authorization's `policy-lint` passes. The lint checks mandatory conflicts, dead grants and shadowed prohibitions (SMT check, v1.0 §8). Lint is a service that Authorization offers, not an ownership claim.
- **This is option A of the review** ("Forum owns its immutable policy versions and Authorization evaluates them"), generalised to every resource-owning context. **v1.0's "PolicySet aggregate per organisation, owned by Authorization" is withdrawn.**

### 2.3 Why this resolves the contradiction

v1.0 had two potential owners for forum rules: the PolicySet and ForumAccessPolicy. v1.1 has one owner (Forum) for forum grants and one owner (Authorization) for what a grant *means*. The "grants only from the resource's module" rule also blocks a whole defect class: for example, Governance cannot accidentally grant `forum.moderate`.

---

## 3. F2: Cache invalidation contract (verified within bounds)

### 3.1 Evidence (g8 P2)

18,960 cases: 16 initial states × update logs of length ≤ 3 over {membership, tenure, jurisdiction, policy} × every cache-build point × every consumer lag. Each cell counts cases where a cached entry was **trusted although stale** (in brackets: of which an unsafe Permit).

| Strategy | Trusted although stale | Refused (fell back to a fresh decision) |
|---|---|---|
| C-DIRECT: version vector over the "obvious" facts (membership, tenure), lagged consumer view | **1,956** (978 unsafe) | 8,896 |
| C-CLOSURE: full dependency closure, lagged consumer view | **960** (480 unsafe) | 13,952 |
| **C-WATER**: closure + trust only when the consumer watermark = log head | **0** | 17,600 |
| **C-AUTHV**: closure checked against a synchronous authoritative version read | **0** | 13,952 |

**Reading the result:**
- The review's two concerns are both real and **separate**. An incomplete dependency vector (C-DIRECT) and consumer lag (C-CLOSURE) each cause unsafe stale permits on their own.
- C-AUTHV refuses less than C-WATER (13,952 vs 17,600) because it does not depend on the consumer having caught up. It costs one cheap version read per check (`seq` numbers only, no fact payload).

### 3.2 Contract (candidate)

1. **Read-set capture, not hand-listed dependencies.** The PIP layer records `(factType, subjectId, seq)` for **every** fact it returns to the PDP, and `(moduleId, version)` for every policy module consulted. The dependency set is therefore "everything the decision read". That includes derived dependencies (jurisdiction assignment, geography snapshot version, committee lifecycle) by construction. This answers the review's "derived dependency" point: nothing relies on a developer remembering to list a dependency.
2. **Validity check:** C-AUTHV is the default. C-WATER is allowed where the consumer exposes a monotone watermark and the log head can be read.
3. **Lag:** when the check cannot be completed (version service unavailable, or watermark behind), the entry is treated as a **miss** and the request takes the authoritative path, or is denied under the action class's outage rule.
4. **Scope:** only READ and CONTRIBUTE decisions are cached. MODERATE, ADMINISTER and GOVERN are always decided fresh (unchanged from v1.0 §6.5).
5. **Open obligation:** the model abstracts a log with a total order. The production contract must still show that every owning context exposes a **per-subject monotone `seq`**. Membership does (INV-14, verified). Governance, Organisation, Geography snapshots and policy modules must be designed to.

**Claim strength:** exactness is **verified within bounds** for the modelled dependency shapes. It is not claimed beyond them.

---

## 4. F3: Temporal contract of the decision function

### 4.1 Clocks

| Symbol | Meaning |
|---|---|
| `t` | evaluation time: when the action is or was attempted |
| `v` | valid time at which facts are evaluated; **default `v = t`** (facts as they were true when the action happened) |
| `k` | knowledge cutoff: which recorded facts and policy versions may be used |

### 4.2 Operations

```text
decideNow(actor, action, resource, t = now)
    = decide(actor, action, resource, facts@(v=t, k=head), modules@(t, k=head), t)
    → DecisionRecord{ id, actor, action, resource, t, v=t, k=head(watermark or log positions read),
                      moduleVersions, readSet{(factType, subjectId, seq)}, result, classification, witnessPath }

reconstructDecision(record, mode)
    mode = AS_DECIDED   : k = record.k        → MUST reproduce record.result exactly        (property R-1)
    mode = IN_HINDSIGHT : k = now             → "what should have been decided at t, knowing what we know now"
    mode = AT(k')       : explicit cutoff     → audit and dispute questions
    always:              t = record.t, v = record.v (overridable only explicitly)
```

**Properties to test:**
- **R-1 (exact replay):** `reconstructDecision(rec, AS_DECIDED).result = rec.result`, provided the retained history is intact. This is checkable because `readSet` pins every version.
- **R-2 (correction awareness):** `IN_HINDSIGHT` may differ from `AS_DECIDED`. The difference is reported with the facts whose versions changed.
- **R-3 (no future knowledge):** no fact or module with `recordedAt > k` is visible in either mode. This is the three-clock property K-8 of v1.0.

**Port consequence:** every query port takes `(subject, asOf v, knownAt k)` and returns `(value ∈ {T, F, U}, seq)`. This is the extension of `MembershipQueryPort` and of `AuthorityCheckPort` (G-AUTH-1) that v1.0 §6.7 named.

---

## 5. F4: Governance aggregate boundaries (evidence-backed candidate)

### 5.1 Evidence (g7)

20 interleavings of two concurrent Appoint commands (read → check → commit with expected versions):

| Invariant | Committee aggregate only (G-A) | G-A + `PositionHoldings` | One aggregate per organisation |
|---|---|---|---|
| INV-P6, cross-committee incompatibility (same person, positions in C1 and C2) | **18 / 20 violated** | **0** | 0 |
| INV-P3, local capacity (one seat, two persons, same committee) | 0 | 0 | 0 |

### 5.2 Revised candidate design

| Aggregate | Protects | Consistency |
|---|---|---|
| **Committee** (G-A, narrowed) | lifecycle, Term, Positions, κ capacity (INV-P3), participation episodes (INV-P2), tenure ⊆ participation (INV-P4) | strong, one transaction |
| **PositionHoldings** per (Person, Organisation), *new* | incompatible-position table (INV-P6), "max positions per person" if policy has one (BQ-P1) | every Appoint commits Committee **and** PositionHoldings with expected versions. Two small aggregates, one transaction, inside the modular monolith (one database). No saga is needed |
| **JurisdictionAssignment**, *separate candidate lifecycle* | assignment of GeoUnits to a committee over time | committee-local unless a rule like "at most one committee of kind K per GeoUnit" is adopted. **That rule would be cross-committee**, and would then need a `JurisdictionRegistry` per (organisation, committee kind) by the same reasoning as PositionHoldings. **[OPEN] BQ-J1** |
| Committee ↔ Unit relation (R-06) | the relation kind and its period | inside Committee (no cross-committee invariant known) |

**Rejected:** one Governance aggregate per organisation. It is correct, but it serialises every appointment of the organisation. The model shows the narrower reservation aggregate achieves the same safety.

**Design rule (generalisable):** *an invariant whose quantifier ranges over several committees must live in an aggregate keyed by what it quantifies over* (here: the person). The same reasoning produced Membership's D1 (keyed by (Person, Organisation)).

---

## 6. F5: Temporal pattern, scoped and with a compatibility test plan

### 6.1 XD-1 narrowed

The ratified Membership fact algebra is the **leading standard for authoritative lifecycle relationships**: Membership periods, UnitAffiliation, Participation, Tenure, JurisdictionAssignment and Committee lifecycle.

**It does not apply to:**

| Artefact | Why not | Pattern instead |
|---|---|---|
| Policy modules and Mandate versions | they are rules, not facts about the world. Retroactively *correcting* a rule would silently change past decisions | **forward-only versioning:** immutable versions with `effectiveFrom`; Void allowed only before activation; a mistake is fixed by a new version with an explicit, authorised back-dating act |
| Forum content (posts, edits) | content is not a truth claim with valid time | plain revision history + moderation state |
| Geography reference data | it is synced external reference data | snapshot version id (XD-5) |

### 6.2 Compatibility tests before XD-1 is declared for each context

Each case is a bounded model and an oracle extension (`g9_*`). It must pass for the context before that context adopts the pattern.

| Case | Context | Question the test must answer |
|---|---|---|
| T-1 future-effective appointment (recorded now, effective next month) | Governance | does `EffectiveTenure` stay F until `effectiveAt` for every `k`? |
| T-2 retroactive mandate change | Governance + Authorization | do past decisions keep their `AS_DECIDED` result (R-1) while `IN_HINDSIGHT` changes? |
| T-3 committee dissolved with open tenures | Governance | are tenures non-effective after dissolution *without* being rewritten (the analogue of X-2)? |
| T-4 jurisdiction boundary change | Governance + Geography | which snapshot does a decision at `t` use, and does replay pin it? |
| T-5 policy version superseded | Authorization + owning context | forward-only: no past decision changes under `AS_DECIDED` |
| T-6 annulled appointment (appeal) | Governance | does the Annul revive the predecessor's tenure exactly as Membership's counterfactual theorem `Res(F·Annul(c)) = Res(F∖D(c))` predicts? |

---

## 7. Updated relationship and invariant deltas (v1.0 §4 and §10)

| ID | Change |
|---|---|
| A-1 / A-2 | **replaced** by the §1 invariant (soundness over completions) |
| A-5 | **restated:** a cached decision is trusted only if its read-set versions equal the authoritative versions (C-AUTHV) or the consumer watermark is at head (C-WATER) |
| INV-P6 | **moved** from the Committee aggregate to PositionHoldings |
| R-10 | JurisdictionAssignment is a candidate separate lifecycle (BQ-J1) |
| R-12 | ForumAccessPolicy = Forum's PolicyModule (§2) |
| new INV-AUTH-OWN | a grant for resource type ρ can only come from the module owned by ρ's context |
| new R-1 / R-2 / R-3 | decision replay properties (§4.2) |

---

## 8. F6: Gates re-scored against evidence

**Completion rule:** a gate passes only when its **target level** is reached and the evidence is cited. "Verified" means it was re-run in this session.

| # | Gate | Target | Current level | Evidence | Pass |
|---|---|---|---|---|---|
| 1 | Cross-domain concepts identified | Proposed | Proposed | v1.0 §1–§2 | ✅ |
| 2 | Relationship catalogue with owners | Approved | Proposed | v1.0 §4 + §7 here | ❌ |
| 3 | Membership architecture | Approved | **Approved** | ratification act 2026-10-08 | ✅ |
| 4 | Membership kernel (Slices 0, 1a, 1b) | Implemented + Verified | **Implemented + Verified** | `PBDIGIT-258/259/260` (42 commits); **144 tests / 112,684 assertions OK today** | ✅ |
| 5 | Membership C-1 accepted | Accepted | Implemented (reported) | `PBDIGIT-268`; acceptance pending | ❌ |
| 6 | Membership cutover | Accepted | blocked | ARB-MEM-01 | ❌ |
| 7 | Participation and committee research models | Verified | **Verified** | g3, g5 re-run today, exit 0 | ✅ |
| 8 | Temporal pattern verified for Membership | Verified | **Verified** | kernel suite includes the exhaustive differential, golden-oracle and property tests (part of the 144) | ✅ |
| 9 | Authorization semantics sound | Verified | **Verified (bounded)** | g8 P1: K2 has 0 unsound permits (after correcting v1.0) | ✅ |
| 10 | Geography reference model | Implemented + Verified | **Implemented + Verified (unit)** | 51 DB-free Geography/Governance unit-test files OK today; DB-backed tests not run (ARB-SEC-01) | ✅ |
| 11 | Ubiquitous language | Approved | Proposed | v1.0 §2 | ❌ |
| 12 | XD decisions | Approved | Proposed | §9 | ❌ |
| 13 | RD-1…RD-5 | Approved | Proposed | research §25 | ❌ |
| 14 | Governance participation contract | Approved | not written | — | ❌ |
| 15 | Rule / evidence matrix G1 / G2 | Proposed (populated) | empty | — | ❌ |
| 16 | Context map | Approved | Proposed | v1.0 §3 | ❌ |
| 17 | `PBDIGIT-266` closed | Verified | open | needs test DB | ❌ |
| 18 | MembershipQueryPort | Implemented + Verified | designed (reported) | — | ❌ |
| 19 | Committee aggregate (narrowed) | Implemented + Verified | modelled | g7 | ❌ |
| 20 | Legacy committee consolidation | Implemented + Verified | — | — | ❌ |
| 21 | Authorization PDP | Implemented + Verified | modelled | g8 | ❌ |
| 22 | G-AUTH-1 port | Implemented + Verified | specified | §4.2 | ❌ |
| 23 | Organisation structure + UnitAffiliation | Implemented + Verified | — | — | ❌ |
| 24 | Jurisdiction assignment + geo-scoped grants | Implemented + Verified | — | — | ❌ |
| 25 | Geography versioning (XD-5) | Approved | Proposed | — | ❌ |
| 26 | Forum MVP | Implemented + Verified | — | — | ❌ |
| 27 | Moderation | Implemented + Verified | — | — | ❌ |
| 28 | Cache contract | Verified (bounded) + Implemented | **Verified (bounded)** | g8 P2; not yet implemented | ❌ |
| 29 | Audit consolidation + replay R-1 | Implemented + Verified | — | — | ❌ |
| 30 | End-to-end cross-domain acceptance | Accepted | — | — | ❌ |

**Result: 7 / 30 = 23 %**, with every passed gate tied to cited evidence. v1.0 counted 9. The two gates removed (2 and 11) were reached only as *Proposed* and are not counted, as the review required. Gate 9 now passes on new evidence; gate 4 was upgraded from reported to verified.

---

## 9. ARB decision package (in the review's decision order)

Format per item: **question → options → recommendation → consequence → evidence.** All items are Proposed; none is decided.

| Order | ID | Question | Options | Recommendation | Consequence of the recommendation | Evidence |
|---|---|---|---|---|---|---|
| 1 | **XD-1** | One temporal pattern? | (a) ratified Membership algebra for lifecycle relationships; (b) per-context choice | **(a), scoped as in §6.1**; adopt per context only after the §6.2 tests pass | one meaning of correction; policies stay forward-only | ratified act; kernel tests (gate 4, 8) |
| 2a | **XD-4** | Where does Authorization live? | (a) own capability context owning semantics + platform module; (b) inside Shared; (c) per context | **(a)**, with **policy modules owned by resource contexts (§2)** | removes the v1.0 ownership contradiction | §2 |
| 2b | **XD-7** | Unknown-fact semantics | (a) absence = false; (b) Kleene, bind on T (v1.0); (c) Kleene, grants / conditions on T, prohibitions / denies on T ∨ U | **(c)** | indeterminate denials become visible and reviewable | g8 P1: (a) 238, (b) 96, (c) **0** unsound |
| 3 | **RD-1…RD-5** | participation subject; role representation; membership dependency; aggregate; legacy | Person; M2; derived intersection; **G-A narrowed + PositionHoldings**; re-model in Governance | as listed (RD-4 amended by §5) | unblocks the Governance contract | research 2026-10-09; g7 |
| 3b | **BQ-J1** *(new)* | May two committees of the same kind cover the same GeoUnit? | yes / no | business decision | "no" requires a JurisdictionRegistry aggregate | §5.2 |
| 4 | **XD-2** | Name of Person ↔ Unit belonging | UnitAffiliation (Organisation-owned) | adopt | no collision with EM-VOC-002 | v1.0 F-3 |
| 5 | **XD-3** | Identity scope | global PersonId with consent-gated linking / organisation-scoped refs | global, consent-gated, no cross-tenant reads | G1-S1 representable | v1.0 |
| 6 | **XD-5** | Geography versioning | snapshot version id / full bitemporal | snapshot id first | replay R-1 can pin geography | §3.2 item 5 |
| 7 | **XD-6, XD-8** | freshness limits; post attribution and retention | ops / privacy evidence | defer to the ops and privacy owners | — | — |
| — | **XD-9** *(new)* | Cache validity strategy | C-AUTHV / C-WATER / no cache | C-AUTHV default; C-WATER where a watermark exists | no stale permits within bounds | g8 P2 |

---

## 10. How far are we from the implementation goal?

- **Research and conceptual architecture: converged.** v1.1 changed no context boundary. It corrected one security semantic (guards), one consistency boundary (cross-committee) and one contract (cache), each with executable evidence. More broad brainstorming is not needed.
- **Decision readiness: package ready, nothing decided.** Gates 12, 13, 14, 16 and 25 are open. The package is §9.
- **Formal assurance: improved.** The three most dangerous claims (unknown facts, cache freshness, aggregate safety) now have bounded models. The temporal pattern's transfer to Governance (§6.2) is still unverified.
- **Implementation readiness: partial.**
  - Membership kernel: implemented and verified today.
  - Geography: implemented; unit tests verified today.
  - Authorization PDP, Governance consolidation, Organisation structure and Forum: not built.
- **End-to-end readiness: not demonstrated.**
- **Gate measure:** **7 / 30 (23 %)** under the strict evidence rule, where v1.0 counted 9 / 30 under a looser rule. The lower number is the more reliable baseline.

---

## 11. Remaining TODOs

### P0: Security and decision authority
- Provision the isolated test database and run the `PBDIGIT-266` security test plan; protect the affected routes with interim role checks, without waiting for the PDP.
- Present the §9 decision package to PO / ARB in order: XD-1 → XD-4 + XD-7 → RD-1…RD-5 (with the amended RD-4) and BQ-J1 → XD-2 → XD-3 → XD-5 → XD-6 / XD-8 → XD-9.
- Answer BQ-P1…BQ-P3 (several positions per person; membership required per position kind; tenure lapse on membership end).
- Complete Membership C-1 formal acceptance and continue the approved Membership track.

### P1: Contracts and verification
- Write the Governance Participation Semantic Contract after RD-1…RD-5. Use the narrowed Committee aggregate, PositionHoldings and the JurisdictionAssignment lifecycle.
- Specify the query-port signature `(subject, asOf v, knownAt k) → (T | F | U, seq)` for Membership, Governance, Organisation and Geography, with closed-world declarations.
- Specify the G-AUTH-1 `AuthorityCheckPort` extension and the DecisionRecord (read set, module versions, `t`, `v`, `k`, witness path).
- Specify the PolicyModule contract and `policy-lint` (mandatory conflicts, dead grants, shadowed prohibitions).
- Build compatibility models T-1…T-6 (`g9_*`) before adopting the temporal pattern in Governance.
- Extend g8 to multi-module policies (platform ⊕ organisation ⊕ resource) and test INV-AUTH-OWN.
- Extend g7 with BQ-J1 (jurisdiction exclusivity) once the business answer is known.
- Populate the rule and evidence matrix for Groups 1 and 2 from `ELECTION_MANIFESTO.md` (EM-GOV / EM-VOC) and the legacy committee limits, with evidence status and decision status kept separate and an `upperBoundCX ≈ 3/n` column.

### P2: Incremental implementation (each slice only after approval)
- Committee aggregate (narrowed) + PositionHoldings + MembershipQueryPort + EffectiveTenure (G-1).
- Minimal Authorization PDP: K2 guards, policy modules, default deny, action classes, DecisionRecord, replay property R-1 (A-1).
- Migrate the legacy committee models A / B / C into Governance with parity tests (G-2).
- OrganisationStructure + UnitAffiliation (O-1); JurisdictionAssignment + geography snapshot ids (J-1).
- Forum MVP through the PDP, with the read-set cache (C-AUTHV) and revocation tests (F-1); moderation, jurisdiction forums, subscriptions and invitations (F-2).
- Audit consolidation and decision-replay tests (AU-1); end-to-end acceptance scenarios before ratification.

### P3: Continuous assurance
- Keep re-running the oracle suite (g3–g8, then g9) and the DB-free unit suites as a regression gate. Score gates only from re-run evidence.
- Use NLP / LLM rule extraction with passage citations and embedding clustering of terms only as human-verified research aids. Use anomaly detection on authority usage as a signal only.

---

## 12. Traceability

| Section | Sources |
|---|---|
| §0, §1 | `models/g8_pdp_guards_and_cache.py` P1 (run 2026-10-10) |
| §2 | principal review Finding 1; v1.0 §5.5, §5.6, §6 |
| §3 | principal review Finding 2; g8 P2 |
| §4 | principal review Finding 3; v1.0 §6.3, §7.3 |
| §5 | principal review Finding 4; `models/g7_governance_aggregate_boundary.py`; participation research §16 |
| §6 | principal review Finding 5; ratified act §9 (`Res(F·Annul(c)) = Res(F∖D(c))`) |
| §8 | principal review Finding 6; `vendor/bin/phpunit tests/Unit/Contexts/Membership/{Domain,Application}/Organisational` → OK (144 tests, 112,684 assertions); 51 DB-free Geography / Governance unit-test files OK; `git log --grep PBDIGIT-258/259/260` |
