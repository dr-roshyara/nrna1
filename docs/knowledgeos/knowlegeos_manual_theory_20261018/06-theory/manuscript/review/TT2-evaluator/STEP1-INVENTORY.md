*Saved verbatim 2026-10-19 from the reviewer's step-1 hand-back (blind). Harness: commissioned 17:21:32Z, received at this save.*

# STEP 1 INVENTORY — THEORY TURN 2

**Topic:** the evaluator of standing, `stand(k, x, t | record of acts)`.
**Mode:** topic review, step 1 (blind).
**What I opened:** only the reviewer instructions and the four sources. I did not open anything under `06-theory/`, and I did not open RESEARCH-PURPOSE.md. I wrote no files.
**Independence:** this is process independence, not personal independence. I did not see the Professor's work before writing this.

**Lines read:**
- S0128: all of it, L1–193.
- S0916: all of it, L1–1830.
- S1414: all of it, L1–1652.
- S2775: all of it, L1–2391.

The long files were read in two parts each, because of their size.

**Lineage:**
- **S0916 and S2775 share an origin and count as one line of support for the temporal claims.** S2775 restates S0916's content in later notation (epistemic contract EC, Γ, Zero, Det). The shared content: valid vs transaction time, late-arriving information, correction without rewriting history, snapshots, intervals, and "precision must not exceed evidence".
- **S1414 is a separate thread on authority, but it is not fully independent.** It comes from the same series as S0916 (the phase_measure_theory steps, with numbered invariants I_n and "PASS" verdicts). It refers back to a "Step 193" for temporal versioning.
- **S0128 is the only source that measures something.** It is an ADR about a real fold over workflow records (`workflow-state.php`). Its subject is governance state for work items, not standing of knowledge items. Its author discloses that it has an interest in the outcome.

---

## S0128 — KOS-AIP-GOV-STATE-DURABILITY-ADR (PROPOSED)

1. **The record stores no derived state.** Owner, session states and work-item state are computed by a fold over the stored `transitions[]` and `grants[]` and are never persisted. This is the only observed instance of a standing evaluator in the four sources, and it is a fold over acts [S0128 L45; L64–68].
2. **The evaluator's inputs are named by the record's own top-level keys:** `schema · workItem · workflow · roles · transitions · grants`. So the fold needs a rule set (workflow), a role vocabulary, an authority record (grants) and an append-only log of acts (transitions) [L45; L60–64].
   - [I] Scope here = one work item, i.e. one record.
3. **Ordering is by `seq` alone; there is no time axis.** Only 2 of 210 transitions carry a `date`, both to the day. The observed evaluator runs on logical time, not wall-clock time [L49].
4. **`seq` is dense and monotonic in all 16 records**, so a dropped act shows up as a gap. The evaluator can detect an incomplete input but cannot repair it [L48; L96; L161].
5. **The record outranks prose (rule I-10).** Standing comes from the record of acts, not from documents that cite it [L50; L80–82].
6. **A snapshot must be a projection, never a source.** If a snapshot is ever read as authority there are "two truths", with no divergence detector [L104; L134].
   - [I] For the topic: a cached result of the evaluator must never become one of its inputs.
7. **The snapshot must be mechanical, not an act.** A party preserving its own authority record would be acting in its own interest [L108].
8. **The log of acts cannot be re-derived from anything else; the fold's output can.** The log is "REPRODUCIBLE only while it exists" [L135].
9. **Measured reconstructability from tracked documents:** sessions 58/58, grants 83/99. The 16 grants that are lost are mostly amendments, so "who acted" survives and "what they were authorised to do after amendment" does not [L51; L112–121].
   - [I] Standing can therefore be partly undecidable when the authority input is partial.
10. **If the record is absent, standing defaults to prose:** "authority drift by attrition". When the evaluator's inputs are missing, a default evaluator takes over silently [L123].
11. **Importing the log into git would make commit times import times, not act times** [L171; L173]. This confirms that recording time ≠ act time inside a real system.
12. **Source caveats [I]:**
    - O-9's "two independent methods" is reported, not shown.
    - The estate's "authority" is grants over work items. Carrying it over to standing of knowledge items is a step the source does not take.

## S0916 — Step 25W, temporal consistency and bitemporal knowledge (brainstorm)

1. **Three time axes are needed:**
   - ValidTime: when the claim held in the domain;
   - Transaction/RecordedTime: when the system recorded it;
   - KnowledgeTime / EpistemicStateTime: when the system became justified in accepting it.

   [S0916 L49–89; L201–227]
2. **The third axis exists because "an assertion may exist in the system but not yet be accepted as knowledge"** (observation 10:07, assessed 10:08, accepted 10:10). Recorded ≠ accepted. [I] This is the closest the four sources come to "standing" as a separate predicate [L216–226].
3. **AcceptanceTime is an act time, distinct from valid and recorded time** (analyst confirms at 10:10) [L120–132].
4. **TruthAt(t) ≠ KnownAt(t), and WorldState(t) ≠ KnowledgeState(t)** are two separate queries [L230–292].
5. **Historical decisions must use historical knowledge,** with a knowledge cutoff that prevents "hindsight leakage" [L296–319; L1293–1311; L1634–1642].
6. **Correction supersedes, it does not rewrite.** A₁ becomes "Superseded/Invalidated", A₂ is added, and the original acceptance stays recorded [L323–362].
7. **State is computed as `State_t = Fold(Events_≤t)` over immutable acts** (AssertionAccepted, RuleActivated, RuleRetired, DecisionAuthorized, and so on) [L586–613].
   - [I] This assumes a total order ≤ on t; compare S2775 #9.
8. **EventHistory ≠ WorldHistory, and ArrivalOrder ≠ EventOrder** [L617–677].
9. **The snapshot is a parameter tuple, not a store:** `(KnowledgeCutoff, ValidTime, Contexts, Semantic/Rule/Model/PolicyVersions)`, which makes `Evaluate(SnapshotID, Query)` reproducible [L402–441; L1217–1248].
10. **Version alignment and temporal consistency:** a derivation is valid only if its evidence, rules, semantics, models, identity and context are compatible. CurrentRule ≠ HistoricalRule [L487–582; L1450–1486].
11. **The reproducibility contract also includes AlgorithmVersion and a seed** (for stochastic computation) [L1362–1446].
    - [I] This means the evaluator itself is versioned and is one of its own parameters.
12. **The "falsification experiments" A–H are asserted PASS with no test.** They are expectations, not results [L1490–1642] [I].
13. **"Valid" has several meanings in this source** (ValidTime, Valid(D) for a derivation, "epistemically valid knowledge") [L77; L538; L414] [I].
14. **Open question:** distributed sources share no clock and no transaction boundary, so what is KnowledgeState(t)? [L1753–1830]

## S1414 — Step 196, the authority–responsibility model (brainstorm)

1. **A stored status is evidence, not governance truth.** `status=APPROVED` must be validated against the transition that produced it [S1414 L239–263].
   - [I] This is the strongest argument in the four sources for computing standing from acts rather than reading a field.
2. **Permission does not imply authority**; technical access ≠ legitimacy [L196–206; L1579].
3. **Authorization is a four-place predicate `Authorized(a, τ, C, t)`**, and authority is scoped as `Authority(a, C, O, t)`: actor, context, operation, time [L305–321; L406–459].
4. **Authority is necessary but not sufficient.** `ValidTransition = Auth ∧ Pre ∧ Evidence ∧ Invariant`, later with Rules (R) and Policy added [L325–377; L1382–1431].
5. **Authority expires.** `AuthorityGrant = (Actor, Scope, Capability, ValidFrom, ValidUntil, GrantingAuthority)` is "a governed fact" [L463–508].
6. **Authority needs provenance and a delegation chain** [L512–578].
   - Invariant I52: delegated authority must not exceed its source in scope, duration or nature [L582–618].
7. **Grant(A, A) is invalid unless the domain explicitly permits self-constituting authority** [L1265–1281].
   - [I] This is the regress root: standing of an item depends on the authority of actors, which depends on standing of grants. The source leaves the base case to "the domain".
8. **Invariant I53: revocation is not erasure.** Authority changes affect future authorization only, unless an explicit domain rule says otherwise [L1124–1166].
9. **AI output enters as `P_candidate`** and goes through Review → Verification → Determination. Execution capability does not imply authority [L622–718; L1066–1086].
10. **`Determination = Assessment + AuthorizedActor + ValidProcedure`.** Authority is a condition on the transition that confers standing [L722–754].
11. **"No semantic elevation without an explicit transition."** Every elevation is explicit, governed and lineage-preserving [L1491–1543; L1585–1589].
12. **Invariant I54 (separation of duties) and the evidence-supplier ≠ assessor rule are conditional on domain policy** ("not universally required") [L1205–1261].
13. **The determination tuple** `(Claim, Evidence, Assessment, Actor, Role, Authority, Rule, Scope, Time, Lineage)` is the source's candidate canonical structure [L1302–1322].
14. **"Deterministic assurance":** the system verifies the procedure deterministically rather than predicting the outcome [L1037–1062].
15. **Caveats [I]:**
    - t is a single unqualified time. The source never says whether it is valid time or recorded time.
    - "Authority" is a property of actors over decisions, not of knowledge items. Item standing is derived from it only by #10.

## S2775 — Part XII, time, temporal semantics and versioning (brainstorm; same origin as S0916)

1. **Five or six time dimensions, never to be collapsed:**
   - occurrence, t_o;
   - observation, t_obs;
   - knowledge, t_k;
   - recording, t_r;
   - publication, t_p;
   - decision, t_d.

   [S2775 L27–43; L261–288]
2. **Missing temporal dimensions stay missing.** A known one is never substituted for an unknown one [L290–298].
3. **Bitemporal `B(x) = ⟨V(x), R(x)⟩` answers "what did we believe was valid at t, as known by k"**, which is two time parameters. Bitemporality is "a useful minimum pattern, not a universal temporal ontology" [L402–441].
4. **There are three different "as of" queries:**
   - Q1 ValidAt(t);
   - Q2 KnownStateAsOf(t);
   - Q3 CurrentKnowledgeAbout(t).

   "'As of' is not a complete temporal specification." [L1115–1157; rule T8 L1814–1816]
   - [I] So "standing at t" is ambiguous until the axis is fixed.
5. **Contract time:** `Det(K, p, EC₁, Γ)` does not imply `Det(K, p, EC₂, Γ)`. Determination is relative to the contract version in force [L771–803].
6. **Temporal invalidity ≠ historical falsity.** "Employee is authorized" valid on [t₁, t₂) expires; it was not false [L807–851].
7. **A snapshot is a semantic projection that must name its axis** (valid or transaction), not "rows at t" [L1161–1189].
8. **Reconstruction:** `Replay(H_t, EC_t) = K_t`, given preserved event order, transaction time, versions, rule and contract versions, provenance, and a deterministic transition function δ [L1193–1225; Theorem 12.1, L1596–1646].
   - [I] The "proof" is a sketch. It assumes its conclusion (identical inputs and a deterministic δ) and leaves out an initial state K₀.
9. **Temporal order may be partial. "Unknown order must remain unknown"**; the system must not invent a total order [L597–633; Theorem 12.2, L1650–1688].
   - [I] This conflicts with the total-order fold of S0916 #7, and it is not addressed.
10. **Logical time ≠ physical time.** Sequence numbers are a valid temporal representation of order only [L61–77; L675–699].
    - [I] This fits S0128's seq-only record.
11. **Revision preserves history:** `H(K_t) ⊆ H(K_{t+1})`. Correction changes current knowledge without rewriting epistemic history [L1049–1085; L1412–1446].
12. **A temporal conflict stays a local conflict set** unless "an authorized temporal resolution rule exists" [L1450–1490].
    - [I] So the evaluator needs a "conflict / undetermined" outcome, not only yes/no.
13. **Temporal governance must declare:** the authoritative clock, calendar, timezone, precision, source authority, validity semantics, correction rules and reconstruction requirements. A missing temporal rule is a governance gap [L2149–2174].
14. **Scope is relative to the bounded context:** event fields "depend on the bounded context". Temporal invariants are declared, not universal (`EffectiveTime ≤ TransactionTime` is domain-specific) [L1860–1876; L1922–1944].
15. **Several obligations are conditional** ("when contractually relevant": XII-C4) [L2256–2258].
    - [I] The quantifier is weaker than the "MUST" heading suggests.

---

## Cross-source table: what the evaluator needs, and where the sources disagree

| Aspect | S0128 (measured) | S0916 | S1414 | S2775 | Disagreement [I] |
|---|---|---|---|---|---|
| Act record | `transitions[]` with dense seq, append-only | immutable events, `Fold(Events_≤t)` | transitions τ carrying lineage | history H_t, deterministic δ | all agree it is a fold or replay over acts |
| Authority input | `grants[]`, stored separately | "authority" listed as a dimension; DecisionAuthorized event | AuthorityGrant (ValidFrom/Until, grantor), delegation chain | "source authority" in temporal governance; authorized resolution rule | only S1414 makes grants time-bounded and recursive (regress root left to the domain) |
| Rule/schema parameter | `workflow`, `roles`, `schema` | Rule/Semantic/Model/Policy/AlgorithmVersion | Rule, Preconditions, Invariants, Policy | contract EC_t, Γ, rule version | S0916 alone adds algorithm version and seed |
| Scope x | one work item (per record) | Context in the snapshot | context C / bounded context, operation O | Γ, bounded context | none defines scope nesting or cross-scope standing |
| Time axes | seq only (logical); wall-clock almost absent | valid, recorded, epistemic/acceptance (3) | one unqualified t | up to 6, plus version time and contract time | **main disagreement:** from 0 physical axes to 6; which axis "standing at t" uses is never fixed |
| Order | total within a record (seq) | ≤ on t, implied total | not addressed | partial; unknown stays unknown | total-order fold vs partial order: unresolved |
| Retroactivity | correction by mapping document, not by edit | supersede, keep the old acceptance | I53: no retroactive invalidation unless a domain rule says so | correction changes current knowledge, history kept | compatible only under bitemporal reading; S1414 does not separate "asked then" from "asked now" |
| Stored vs derived standing | derived, never stored; snapshot is a projection only | snapshot is a parameter set | stored status is only evidence | snapshot is a projection | agree: a cache must not become an input |
| Outcomes | owner/state, plus seq-gap detection | accepted / superseded / conflict | valid / invalid transition | determined / conflict set / gap | three-valued at least (yes/no/undetermined); S0128 also has "input incomplete" |
| Kernel vs application | the fold sits in the engine (`workflow-state.php`) | "architecture" | "architecture should encode" | fields and invariants per bounded context | **no source tests kernel vs application explicitly** |
