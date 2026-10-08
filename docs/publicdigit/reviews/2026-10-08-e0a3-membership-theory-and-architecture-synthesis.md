# E0a-3: Membership Theory and Architecture Synthesis

**Type:** Research synthesis for **Principal Reviewer** review · theory + formal model + architecture candidates · **decides no business rule; implements nothing** · **Date:** 2026-10-08
**Commission:** *"E0a-3 Membership Theory & Architecture Synthesis"* (2026-10-08)
**Predecessor:** `2026-10-08-e0a2-final-decision-gate.md` (**G**). Its six MUST-NOW decisions (D-ID.1, D-ID.2, D-TERM-1, D-SUSP, REC-0, I5) **stay OPEN**. This document does not change their status.
**Corpus:** DR, FR (+ correction), DP, G, R, Q, D; brainstorming V3/V4 + blueprint; Election Manifesto; ADR_20261004_1549; code and tests.
**Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)

> **Evidence classes:** ADOPTED · STRONG · MODERATE · WEAK/ANALOGICAL · IMPL (implementation observation) · HYP (hypothesis) · OPEN-BIZ · ARCH-DERIV (architectural derivation) · IMPL-CHOICE.
>
> **Computational results** come from an exhaustive enumeration of **137,256** act ledgers (all sequences of ≤ 6 acts over 7 act kinds) per (person, organisation). The script is described in Appendix A. A result labelled **"verified"** holds for all enumerated ledgers. **It is a bounded check, not a proof** for unbounded histories. It is used to **expose contradictions**, which it can do conclusively, and to **support** invariants, which it can do only within the bound.

---

## 1. Executive conclusion

1. **The strongest theory: membership truth is a function of one dated act history per person and organisation.** An Organisation Membership is the organisation's **recognition of a person as belonging to it**. That recognition is established by an **admission act** and changed only by further **dated, attributable acts**: renewal, suspension, restoration, ending, annulment, correction. Every condition ("in force", "lapsed", "suspended", "a member at t") is **derived** from that history, never stored as an independent truth.
2. **The identity models A, B and C are not competing ontologies. They are partitions of the same act history.**
   - A cuts the history at each admission (one membership per admission);
   - B keeps one block per person+organisation (one membership with several periods);
   - C (and the new **D**) keeps a person+organisation root with identified periods.
   - **Verified:** `IsMember(p,o,t)` is **identical under every partition** for all 137,256 ledgers, under both suspension models.
   - **So D-ID.1 does not change *what is true*. It changes only *what is called "one membership"*:** the reference granularity, where I5 is enforced, and naming.
3. **Most of the architecture is decision-robust.** These are invariant across all plausible answers to the six open decisions:
   - the act-history model;
   - the derived predicates;
   - a person+organisation-rooted consistency boundary with identified periods (**Architecture D**);
   - the act vocabulary;
   - the authority position;
   - the published-fact separation;
   - the context map.

   The genuine **variation points** are few and local (§24): the partition (naming), number placement, the definition of the counted "member" under suspension, an optional "pending" condition, and **record time**.
4. **Record time (bitemporality) is needed iff a successful appeal annuls an effective ending (D-TERM-4 option A1)** or a retroactive correction is allowed. **Verified:** across all ledgers, the **only** act that rewrites past valid-time truth is **annulment of an effective ending** (1,657 ledgers; 0 for every other act kind). Withdrawing a *pending* resignation does **not** rewrite the past.
5. **Falsified or rejected:**
   - the legacy single `status` (it conflates four dimensions and reaches contradictions);
   - lapse as a lifecycle state;
   - "membership grants voting";
   - "Membership(p,o,t) is primitive" (it is derived);
   - a separate Membership Number Registry concept (unnecessary: the number belongs to the root or to the period);
   - Model C as **two aggregates** (it adds a cross-aggregate consistency problem without semantic gain over D);
   - ML for this question (no added value, §16.4).
6. **One terminology tension remains:**
   - **EM-VOC-001** says "Organisation Membership = the `Member` aggregate". Under **D-ID.1 = B**, Architecture D's root *is* that membership (fully consistent).
   - Under **D-ID.1 = A**, the "membership" is the **period**, and the aggregate root is a person-in-organisation record. **EM-VOC-001 would then need a terminology amendment**, *or* Architecture A must be used (with a cross-aggregate I5 guard).
   - This is the **one architecture–terminology coupling** to D-ID.1 (§18, §24).

---

## 2. Research corpus classification

| Statement | Class |
|---|---|
| Organisation Membership = the `Member` aggregate (EM-VOC-001); affiliation ≠ membership (EM-VOC-002); Election-Only needs no membership; Election owns the entitlement; the ADR chain; ADR-T11; EM-FM-003…006 (deferred) | **ADOPTED** |
| An application is a separate, real process (aggregate, statuses, events, tests) | **STRONG** (IMPL + tests + guides) |
| Lapse is resumable by renewal within a window | **STRONG** (IMPL + guide) |
| Resignation and non-payment are *ending* reasons distinct from lapse | **MODERATE** (guide) |
| An ending "cannot be undone; a new application is needed" | **MODERATE** (guide) |
| The membership number is organisation-issued (importable custom numbers) | **STRONG** (tutorial + import columns) |
| Number continuity on re-entry; appeal; withdrawal; expulsion; suspension effects | **OPEN-BIZ** (no evidence) |
| Committee lineage (terminated → a new relationship); SM2 (removed is final) | **WEAK/ANALOGICAL** |
| UNIQUE(`organisation_user_id`); global number uniqueness; `status='active'` filters; `expired` stored; six re-entry behaviours | **IMPL** (not domain truth) |
| V4 FC-1…12, the blueprint, MembershipFacts v0, INV-SEL, bound admission | **HYP** (brainstorm / proposals) |
| `[from, until)`; validity matrix; event names | **IMPL-CHOICE** (conventions) |
| §§4–24 of this document | **ARCH-DERIV** unless labelled otherwise |

## 3. Membership ontology

**What an Organisation Membership IS (ARCH-DERIV from ADOPTED + STRONG):**
- the organisation's **recognised relationship** with **one person**, under which that person belongs to the organisation as a member;
- **created** by an admission act (on some basis: approval, import, invitation, direct entry, migration);
- **bounded in time** by its term (effectiveness);
- **governed** by further acts.

**What it IS NOT:**
- not the person (Identity);
- not the person's access or role in the organisation (Affiliation; EM-VOC-002);
- not an application (a request that may or may not lead to admission);
- not a committee position (Governance);
- not an election entitlement (Election; EM-ENT-001/007);
- not a payment record (Fee / Finance);
- **not a status flag.**

**What it OWNS:** its holder and organisation references; its admission basis; its term(s); its type assignment(s); its number (or its root's number); its own **act history** (who did what, when, on which basis, effective when); and the **derivation** of its conditions.

**What it does NOT own:**
- whether an actor **had authority** (Governance answers that; Membership records the claim and the outcome);
- dues **payments** (the Fee aggregate and Finance);
- participation bans issued by other organs for specific processes (Governance);
- anything about votes.

## 4. Formal mathematical model

```text
Universe:   P persons, O organisations, T time (total order), K act kinds
Act:        α = (kind ∈ K, holder p, organisation o, validAt τ_v, recordedAt τ_r, actor, authorityRef?, basis?, payload)
K (minimum, §23) = {Admit, Renew, ChangeType, Suspend, Restore, End, Annul, Correct}
Ledger:     H(p,o) = { α : holder(α)=p ∧ organisation(α)=o }        -- PRIMITIVE facts
Partition:  π : H(p,o) → Blocks, each block begins with an Admit       -- the IDENTITY choice (D-ID.1)
            A: every Admit opens a new block;  B: one block (Admit after End re-opens);  D: root(p,o) + one period per Admit
Effective act set:  E(H, τ_r*) = acts recorded by τ_r*, minus acts annulled by τ_r*, with corrections applied
```

**Derived predicates (on a block or period m, valid time t, knowledge time τ):**

```text
Admitted(m,t)   ⇔ ∃ Admit ∈ m, validAt ≤ t
Ended(m,t)      ⇔ ∃ End ∈ E(m), effectiveAt(End) ≤ t             -- effective, not decided
Effective(m,t)  ⇔ Admitted(m,t) ∧ t < termEnd(m,t)                -- termEnd from Admit/Renew (lifetime ⇒ ∞)
Lapsed(m,t)     ⇔ Admitted(m,t) ∧ ¬Ended(m,t) ∧ ¬Effective(m,t)
Suspended(m,t)  ⇔ last Suspend/Restore in E(m) with validAt ≤ t is a Suspend
Recognised(m,t) ⇔ Admitted(m,t)   [if REC-0 = no]   |   ∃ Recognise ∈ E(m), validAt ≤ t   [if REC-0 = yes]
InForce(m,t)    ⇔ Recognised(m,t) ∧ Effective(m,t) ∧ ¬Ended(m,t)
IsMember(p,o,t) ⇔ ∃ m ∈ π(H(p,o)) : InForce(m,t) ∧ (S1 ⇒ ¬Suspended(m,t))
```

**Primitive vs derived:**
- `Membership(p,o,t)` is **derived** (= `IsMember`). It is **not** a primitive fact.
- The primitives are the **acts**. Three kinds of fact are distinguished:
  - **Temporal facts:** validAt, effectiveAt, recordedAt, term end;
  - **Lifecycle facts:** the acts themselves;
  - **Derived business predicates:** everything in the block above.
- `InForce` is **not** "Effective ∧ LifecycleAllows" with a stored lifecycle. It is the conjunction of **independently derived** conditions.
- The exact role of suspension is the **D-SUSP variation point** (S1 puts `¬Suspended` into IsMember; S2 does not).

## 5. Identity theory

| Concept | Persists when a person leaves and returns? | Identity criterion |
|---|---|---|
| Person | **yes** | Identity context (`users.id`; global) — ADOPTED/IMPL |
| Organisation | **yes** | organisation id |
| OrganisationAffiliation | **not necessarily** (it can be soft-deleted and re-created) | an (affiliation) record; EM-VOC-002 |
| **Person-in-organisation (p,o)** | **yes, always** (a value pair) | equality of p and o |
| Membership (block) | **D-ID.1**: A no; B yes | partition π |
| Membership period | **no** (each admission opens one) | the Admit act that opens it |
| Membership Number | **D-ID.2** | an organisation-issued value |
| Admission decision | no (one per admission) | the act |
| Lifecycle episode (suspension span) | no | Suspend…Restore pair |

**Three identity relations, kept apart:**
- **Referential identity** `m1 == m2`: the same block under π. This is model-dependent.
- **Relationship continuity** `sameRelationship(m1,m2) ⇔ holder(m1)=holder(m2) ∧ org(m1)=org(m2)`: **model-independent**, always computable.
- **Historical continuity:** whether two periods are adjacent or have a gap. **Model-independent** (it follows from the ledger).

**Result (ARCH-DERIV):**
- **Continuity questions never need D-ID.1.** Only *referential identity* does.
- The domain needs referential identity for:
  - (i) naming "the membership" in documents and decisions;
  - (ii) an election registration bound to a specific membership (Q-FM-MAP option A, deferred);
  - (iii) I5 locality.
- **Architecture D provides a period identity in every case**, so (i)–(iii) are satisfiable whatever D-ID.1 says. The decision then only names which level is "the Organisation Membership".

## 6. Temporal theory

| Question | Answer | Basis |
|---|---|---|
| Does `effectiveUntil(m) < t` (lapse) imply termination? | **No.** It implies `¬InForce` only. **Verified (P2):** in no ledger without an End act does an ending arise | STRONG + verified |
| Does suspension change `Effective`? | **Only if** the business says the term pauses (D-SUSP row 1). Otherwise suspension changes **rights** (S2) or the counted membership (S1), never the term | OPEN-BIZ (row 1); ARCH-DERIV otherwise |
| `TerminationEffective(m,t) ⇒ ¬InForce(m,t)` | **Holds by definition** (InForce contains ¬Ended). **Verified (P1/P4):** never "ended and in force" | ARCH-DERIV + verified |
| Must `effectiveUntil(m) = t_end`? | **No.** It is equivalent to deriving ¬InForce from Ended. Truncating the term is a representation choice | IMPL-CHOICE (FR I2 downgraded in DP) |
| Re-entry: re-open m1 or a new m2? | **Both preserve every tested invariant identically** (P1, P2, P4, P6). They differ only in referential identity, I5 locality (§12) and naming | verified |
| When is record time needed? | **Iff** a later act can change past valid-time truth. **Verified:** only **annulment of an effective ending** does (1,657 / 137,256 ledgers; every rewriting ledger ends with Annul). Corrections would too (not enumerated) | verified + ARCH-DERIV |
| Withdrawal of a *pending* resignation | It annuls an End whose effectiveAt is still in the future, so **no past truth changes** | ARCH-DERIV |
| Interval convention | `[from, until)` | IMPL-CHOICE |

## 7. Lifecycle theory: one status or several dimensions?

**Legacy `status` conflates four independent dimensions (IMPL analysis):**
- `active` ≈ Recognised ∧ ¬Ended ∧ (Effective, until the job runs) ∧ ¬Suspended;
- `expired` mixes **effectiveness** into the lifecycle (hence the FR contradiction "expired yet effective after renewal");
- `suspended` and `ended` are acts' outcomes;
- `archived` is record management.

**Candidate dimensions:**

| Dimension | Values | Source of truth | Status |
|---|---|---|---|
| Recognition | recognised / pending | Admit (+ Recognise if REC-0 = yes) | variation point (REC-0) |
| Ending | open / ended (+ kind) | End / Annul acts | core |
| Effectiveness | effective / lapsed | the term from Admit/Renew | core, **derived** |
| Restriction | none / suspended (+ other bans, from Governance) | Suspend / Restore | core; its role in IsMember is D-SUSP |
| Standing | compliant / not | the fee ledger (E0b) | outside the lifecycle |

**Test (verified):** with dimensions kept separate, the contradiction set is **empty**: no "ended ∧ in force", no "lapse ⇒ ended". The single-enum form needs **composite values** (for example suspended-and-lapsed: 6 reachable (lifecycle, effective) pairs, FR §11) or loses information.

**Result:** the *stored* lifecycle shrinks to **{open, ended}**, with **pending** only if REC-0 = yes and **suspended** only if S1. Everything else is derived. **This is smaller than the legacy enum and contradiction-free**, which makes it the minimum, not an elaboration.

## 8. Recognition theory

- **Application, import, invitation, approval and direct entry are not separate kinds of membership.** They are **different bases of one act**: `Admit(basis ∈ {approval(applicationRef), import(sourceRegister), invitation(invitationRef), direct, migration})`. ARCH-DERIV from the IMPL paths + EM-VOC-001.
- **MembershipApplication** is a separate **process aggregate** (STRONG). It **precedes** membership and may end in rejection.
- **AdmissionDecision** is **not** a separate aggregate. It is the decision content of the `Admit` act (actor, authorityRef, basis, decidedAt).
- **"Member"** = the Organisation Membership (EM-VOC-001), **not** a third concept.

**Per-path state transitions (minimum):**

```text
approval:   Application{submitted} --approve--> Application{approved} + Admit(basis=approval)
            Application{submitted} --reject-->  Application{rejected}            (no membership)
import:     Admit(basis=import, sourceRegister, historical validAt?)              (REC-IMP: recognised by this act?)
invitation: Invitation{sent} --accept--> Admit(basis=invitation)                  (REC-INV)
direct:     Admit(basis=direct)                                                   (authority: REC-AUTH)
```

**REC-0** decides only whether `Recognised` can lag behind `Admitted`, i.e. whether a separate `Recognise` act exists. **Nothing else in the model changes.**

## 9. Authority positioning

| Aspect | Owner | Representation |
|---|---|---|
| **Who is competent** to perform an act type (organ, delegation, statutes) | **Governance / Organisation** (EQ-5, EM-OPEN-014; deferred) | an authority model, outside Membership |
| **That the act occurred** (who, when, claimed authority, basis) | **Membership** | fields of the act: `actor`, `authorityRef` |
| **Whether the act is effective** | **Membership** | effectiveAt; Annul |
| **Checking competence at command time** | the Membership application layer, via a **Governance port** (Customer/Supplier) | `AuthorityCheck(actor, actKind, organisation)` |

**Result:** authority is a **dependency of commands**, not of the domain model. The act carries an `authorityRef`, which keeps history explainable even if the authority model changes later (evidence over recomputation). ARCH-DERIV.

## 10. Suspension theory

- **Computation:** S1 and S2 give **different `IsMember` series in 20,704 of 137,256 ledgers**. D-SUSP is therefore a **genuine semantic variation point**, not a cosmetic one.
- **A decision-robust architecture (ARCH-DERIV) needs only two things:**
  - (a) publish **`InForce`** and **`Suspended`** as **separate** facts;
  - (b) treat "is a member for counting purposes" as a **named derived predicate** whose definition is the only thing D-SUSP changes.
- **Term and dues effects** (D-SUSP rows 1–2) are **separate rules on separate facts.** The term pause changes `termEnd` (an extension act). The dues pause belongs to the fee ledger (E0b). They do not require a lifecycle state.
- **The smallest explaining model:** suspension = a **Suspend/Restore act pair** creating a **restriction interval** on the membership. **S1 vs S2 is a definition choice of `IsMember`,** plus optional term and dues rules. **No separate SUSPENDED storage state is needed in either case.** That is *stronger* than G's framing, because S1 becomes expressible without a stored state.
- **Participation bans from other organs** (PI-5) are Governance restrictions, not membership suspensions.

## 11. Termination theory

**Minimum vocabulary (ARCH-DERIV):**

| Concept | Representation | Why it is distinct |
|---|---|---|
| **lapse** | **not an act**: derived (`¬Effective ∧ ¬Ended`) | verified P2 |
| **End** | **one act kind** with `kind ∈ {resignation, termination, expulsion, …}` (D-TERM-1 fixes the list), `decidedAt`, `effectiveAt`, reason | all ending kinds share one effect (Ended from effectiveAt). The kind matters for consequences (re-admission bar, FM-5 mapping), not for structure |
| **withdrawal (of resignation)** | `Annul(End)` **before** effectiveAt | no past truth changes (verified mechanism) |
| **appeal (A1)** | `Annul(End)` **after** effectiveAt | rewrites past truth, so it **needs record time** (verified) |
| **restoration after an ended period (A2)** | `Admit` (re-open) | only under B. The same act as re-entry |
| **re-entry** | `Admit` | partition decides the identity (D-ID.1) |
| **correction** | `Correct(actRef)` or `Void(Admit)` (never valid) | changes recorded facts, not domain events. Retroactive, so **needs record time** |

**Result:** separate classes such as MembershipWithdrawal or MembershipAppeal are **unnecessary**. Two act kinds (`End`, `Annul`) plus `Correct` cover every ending phenomenon in the corpus. D-TERM-1 is a **list of End kinds**, not new structure.

## 12. Multiplicity theory

**Candidate invariant I5:** `∀p,o,t: |{m ∈ π(H(p,o)) : Admitted(m,t) ∧ ¬Ended(m,t)}| ≤ 1`.

| Falsification attempt | Result |
|---|---|
| multiple membership types / associate + full / honorary | types are **attributes** of one membership (single-valued); honorary = a fee-exempt type. Not separate memberships (MODERATE) |
| youth / women / men sections, wings | **committee types** (Governance), not membership (STRONG, `CommitteeType`) |
| concurrent affiliations or roles | affiliation / roles (EM-VOC-002), not membership |
| historical memberships | ended, so excluded by `¬Ended` |
| imported memberships | the same act (`Admit` with basis import). Duplicates violate I5, which is the intended guard |
| multiple applications | applications are not memberships (a guard exists) |
| committee roles | Governance |

**No counterexample found → CANDIDATE** (business confirmation pending).

**Computation:**
- Under **B** (and D's root), I5 holds **structurally**: 0 violable ledgers even without a guard.
- Under **A**, I5 is **violable in 23,791 ledgers without a guard**, so it needs an explicit **cross-aggregate** check.

This is the most concrete architectural difference between A and B/D (§21).

## 13. Membership Number theory

- **The 2×2 (G §6) maps onto Architecture D without a new concept:**
  - **retained number** → a **root** attribute;
  - **reissued number** → a **period** attribute;
  - **tenant-defined** → the root and the period both carry a number slot, and one is authoritative per tenant policy. That is justified **only** if organisations differ (G §5).
- **A "Membership Number Registry" is not needed.** In A + retained (Model C), the number needs a pair-level home, and that home **is** D's root. So Model C **is** Architecture D under A-naming.
- **Uniqueness scope** (U1 per organisation: HYP-strong) is a constraint on the number's namespace. It is independent of where the number lives.

## 14. Historical truth model

The questions asked of every scenario: **who · which organisation · which membership · when · under which type · under which decision · under which authority · in force? · restricted?**

| Scenario | Answerable from the ledger (D)? | Missing in legacy |
|---|---|---|
| admitted | ✓ (Admit: holder, org, validAt, basis, actor, authorityRef, type) | decision and authority (`reviewed_by` unused); approval start date |
| active | ✓ derived | — |
| lapsed | ✓ derived from the term | stored `expired` contradicts renewal |
| renewed | ✓ (Renew; the renewal ledger exists) | status not restored |
| suspended / restored | ✓ (act pair) | unreachable |
| terminated / resigned | ✓ (End kind, decided / effective, reason) | unreachable; `ended_at` only via dormant code |
| rejoined | ✓ (a new Admit; the partition gives the id) | impossible (UNIQUE) or overwritten |
| corrected | ✓ **only with record time** | no correction concept |
| type changed | ✓ (ChangeType act, validAt) | no type history (a single column) |
| imported | ✓ (Admit basis=import, source, historical dates) | CSV dates and number ignored |
| migrated (tenant) | ✓ (a new (p,o′) ledger + provenance link) | none |

**Missing domain facts identified:**
- **type history** (a `ChangeType` act);
- **admission basis and authorityRef on every act**;
- **effectiveAt ≠ decidedAt** on End;
- **record time**, if A1 or retroactive correction is adopted.

## 15. Candidate invariants

| # | Invariant | Class |
|---|---|---|
| INV-1 | the holder and organisation of a block or period never change (except via Void + new Admit) | ARCH-DERIV (STRONG) |
| INV-2 | `Ended(m,t) ⇒ ¬InForce(m,t)` | ARCH-DERIV (definition) · verified |
| INV-3 | lapse never implies Ended | ARCH-DERIV · verified |
| INV-4 | every condition is derived from acts; no stored condition contradicts the acts | ARCH-DERIV (eliminates the legacy contradictions) |
| INV-5 | every act carries actor, validAt, recordedAt (and authorityRef, basis where applicable) | ARCH-DERIV from the historical-truth requirement |
| INV-6 | acts are append-only; annulment and correction are new acts | ARCH-DERIV |
| INV-7 | the membership history survives any affiliation change | STRONG (DP REQ-INV-04) |
| INV-8 | `IsMember` is partition-invariant | **verified** (P6) |
| INV-9 | ≤ 1 non-terminal block per (p,o) | **CANDIDATE** (OPEN-BIZ confirmation: I5) |
| INV-10 | the number is unique within the organisation | HYP-strong (N-U) |
| INV-11 | Membership grants no election entitlement; no voting fact in the Membership model | ADOPTED |
| INV-12 | an annulment of an effective End is visible as such (past belief recoverable) | conditional on D-TERM-4 = A1 |

## 16. Formal / property-based tests

| Property | Result over 137,256 ledgers (≤ 6 acts) |
|---|---|
| **P1/P4** never "ended and in force" (A, B, D; S1, S2) | **0 violations** |
| **P2** lapse alone never produces an ending | **0 violations** |
| **P3** a later act never rewrites past valid-time truth | **violated in 1,657 ledgers, all by `ANNUL_END`** (0 for every other act kind) |
| **P5** election entitlement is not inferable from membership | **structural:** no entitlement fact exists in the model (ADOPTED INV-11) |
| **P6** `IsMember` is identical across partitions A, B, D | **0 differences** (S1 and S2) |
| I5 with an admission guard | 0 violations (A, B, D) |
| I5 without a guard | **A: 23,791 violable · B: 0** |
| D-SUSP sensitivity | `IsMember` differs S1 vs S2 in **20,704** ledgers |
| re-entry histories | 4,306 (A: >1 identity; B: 1) |

**16.4 Machine learning: not used, deliberately.**
- ML would add value only for pattern discovery in **legacy data**.
- Using the development database for analysis is outside this read-only research's mandate.
- The behavioural patterns that matter (the six re-entry behaviours, three status vocabularies) were found **exactly** by code reading.
- Clustering cannot improve on an exact enumeration, and it must not decide semantics.

## 17. Legacy implementation comparison

| Theory expectation | Current implementation | Classification |
|---|---|---|
| conditions derived from acts | `status` stores active / expired / suspended / ended | **architectural mismatch** |
| lapse derived | `ExpireMemberships` writes `expired`; renewal does not undo it | **implementation defect** (and mismatch) |
| an Admit act with basis + actor + authority | the approval path writes neither `reviewed_by` / `reviewed_at` nor the start date | **missing capability** |
| one re-entry semantics | six different behaviours | **unresolved business rule** (D-ID.1) + **defects** |
| I5 as a business rule | UNIQUE(`organisation_user_id`) forever | **migration concern** (correct only under B/D) |
| number org-scoped, assigned at admission | global unique; not assigned on approval (test fails) | **defect** + **unresolved rule** (N-U, D-ID.2) |
| End act with kind / decided / effective | `endMembership` dormant; `archived` unreachable | **missing capability**; dormant code **obsolete** |
| Suspend / Restore acts | `suspend()` unreachable (`manageMembers` undefined) | **missing capability** + authority (deferred) |
| type history | a single `membership_type_id` | **missing capability** |
| history survives affiliation deletion | ON DELETE CASCADE | **defect** |
| two Member models, three vocabularies (MB-1/2) | live | **obsolete behaviour** (consolidate) |
| an application as a process | exists, tested | **conforming** |
| renewal extends the term from max(old, now) | exists | **conforming** (plus the status defect) |
| Finance downstream via a fee event | exists | **conforming** |

## 18. Candidate aggregate boundary

**The consistency boundary from the invariants (ARCH-DERIV):**
- INV-1…6 and I5 must hold **atomically** whenever an act is appended for (p,o).
- **I5 spans all blocks of (p,o)**, so the smallest boundary that enforces I5 **without a cross-aggregate mechanism** is **(p,o)**.

**Inside the aggregate root, one per (person, organisation):**
- the identity of the root;
- holder and organisation references;
- **periods**, each with: an identity, its Admit (basis, actor, authorityRef, type at admission), term end(s), its acts (Renew, ChangeType, Suspend, Restore, End, Annul, Correct), and the number (if reissued);
- the root number (if retained);
- the derived conditions.

**Outside the aggregate:**

| Concept | Where it lives |
|---|---|
| MembershipApplication | its own aggregate |
| OrganisationAffiliation | Organisation context |
| Authority / organs / statutes | Governance |
| Fee ledger | its own aggregate (references the period); Finance projection |
| ElectionMembership / VotingEntitlement | Election |
| Participation bans | Governance |
| Audit projection | downstream |

**EM-VOC-001 check:**
- **D-ID.1 = B:** the root **is** the Organisation Membership (the `Member` aggregate). **Consistent.**
- **D-ID.1 = A:** the Organisation Membership is the **period**, and the root is a person-in-organisation record. **EM-VOC-001's "= the aggregate" wording would need amending** (a terminology decision requirement). The alternative is to adopt **Architecture A** (membership = aggregate), accepting a cross-aggregate I5 guard and, if the number is retained, a separate pair-level holder.

## 19. Bounded-context model

| Upstream → Downstream | Relationship | Published language / translation |
|---|---|---|
| Identity → Membership | **Conformist** (Membership uses PersonId as-is; internal, never cross-tenant exposed) | PersonId |
| Organisation/Governance → Membership | **Customer/Supplier** | affiliation existence; **authority check port** (deferred model); organisation reference |
| Membership → Election | **Open Host Service + Published Language**; Election uses an **ACL** (criterion evaluators) | membership facts at t (§20). **No table access** |
| Membership → Finance | **Published event**; Finance is **Conformist** (as implemented: `FeePaidProjection`, *"data, never a directive"*) | fee paid (amount, currency, method, period ref) |
| Membership → Audit | **Published events / act stream**; Audit is a downstream projection | the acts (they *are* the membership audit) |
| Governance → Election | (existing; unchanged) | participation bans (PI-5), officers |
| **No Shared Kernel and no shared database** | — | — |

## 20. Election integration (Election unchanged)

**The facts Election needs** (Full Membership only; Election-Only needs **none**, EM-EO-001):

| Fact | Partition-invariant? | Purpose |
|---|---|---|
| `IsMember(p,o,t)` (InForce) | **yes** (verified P6) | eligibility at the reference date |
| `Suspended(p,o,t)` | yes | a criterion (separate fact, D-SUSP-robust) |
| `typeAt(p,o,t)` | yes | a criterion (MembershipType ≠ entitlement) |
| `standing(p,o,t)` | yes (E0b) | a criterion |
| `periodRef` at registration | **no** (it names a block or period) | **only** for Q-FM-MAP option A (deferred) |
| End / suspension events | yes (as facts) | the EM-FM-003 trigger (deferred; FM-5 maps End kinds) |

- Membership never computes eligibility or entitlement (INV-11).
- The ADR chain (Registration → ElectionMembership → derived Admission → VotingEntitlement / `canVote`) is untouched.
- Anonymity is untouched: no membership fact reaches the cast transaction.

**Boundary test passed:** every needed fact is a Membership-owned derived predicate. None requires Election to understand membership internals.

## 21. Architecture alternatives

| | **A: admission-bound** | **B: enduring + periods** | **C: relationship + separate episode aggregates** | **D: (p,o)-rooted aggregate with identified periods** |
|---|---|---|---|---|
| Semantics | membership = admission | membership = (p,o) relation | relation and episodes are separate aggregates | one root per (p,o); periods identified; naming per D-ID.1 |
| Aggregate | Membership per admission | Membership per (p,o) | Relationship + Episode (2 aggregates) | one root per (p,o) |
| Invariants | I5 **cross-aggregate** (guard + concurrency) | I5 internal | I5 across Relationship + Episodes (cross-aggregate) | I5 **internal** |
| Temporal | one period per aggregate | several periods | one per Episode | several identified periods |
| History | across aggregates of (p,o) | inside | split | inside |
| Identity | per admission | per (p,o); periods by date | both | both (root + period ids) |
| Number | per admission (retained → needs a pair holder) | on the membership | on the Relationship | root **or** period |
| Commands | Admit, Renew, ChangeType, Suspend, Restore, End, Annul, Correct | same + re-open | split across 2 aggregates | same (on the root, targeting a period) |
| Events | MembershipAdmitted, … | same | duplicated across 2 | same |
| Persistence implication | uniqueness of a non-terminal membership per (p,o) needs a DB/application mechanism | one row per (p,o): the **legacy UNIQUE fits** | 2 tables + a cross-check | one root row per (p,o) (**legacy UNIQUE fits**) + periods |
| Migration | the legacy row splits into memberships; UNIQUE replaced | legacy row = membership; add periods | 2-step | **legacy row = root; derive the first period** from `joined_at` / expiry / status |
| Election | `periodRef` = membership id | `periodRef` needs a date | episode id | period id |
| Complexity | low per aggregate; **cross-aggregate I5** | medium | **highest** | medium |
| Strengths | simple aggregates; matches A-naming and EM-VOC-001 under A | matches the legacy shape and EM-VOC-001 under B | — | **decision-robust** (covers all four 2×2 cells); I5 internal; references unambiguous; best migration fit |
| Weaknesses | I5 + retained number need extra structure | undated references are ambiguous; ending final only per period | cross-aggregate consistency without semantic gain | under A-naming it needs an EM-VOC-001 terminology amendment |
| Contradictions | none | none | **dominated by D** (the same semantics with a harder consistency problem) | none found |

## 22. Architecture comparison

Criteria from the commission. **Qualitative ratings; no weights are assigned.** Weighting is a Principal Reviewer judgement, and weights are subjective.

| Criterion | A | B | C | D |
|---|---|---|---|---|
| Domain correctness (IsMember identical: verified) | ✓ | ✓ | ✓ | ✓ |
| Historical truth (dated) | ✓ | ✓ | ✓ | ✓ |
| Historical references (undated) | ✓ | ✗ | ✓ | ✓ |
| Invariant consistency (I5 locality) | guard needed | internal | guard needed | internal |
| Business expressiveness across the D-ID answers | only A-answers | only B-answers | A + retained | **all four cells** |
| Auditability (act history) | ✓ | ✓ | ✓ | ✓ |
| Context integrity | ✓ | ✓ | ✓ | ✓ |
| Evolution (if a D-ID answer changes later) | rework | rework | rework | **naming only** |
| Migration feasibility | medium | good | poor | **good** |
| Implementation simplicity | good (+ guard) | good | poor | good |
| EM-VOC-001 fit | under A | under B | partial | under B; under A needs a terminology amendment |

**Falsified or dominated:** C (dominated by D). **Surviving:** A, B, D.
- **D is the strongest decision-robust candidate.**
- B is D restricted to B-naming.
- A remains a valid choice **if** the business chooses A **and** prefers keeping EM-VOC-001's wording over I5 locality.

## 23. Minimum Sufficient Membership Theory

| Element | Content | Count |
|---|---|---|
| **Concepts** | Person (ref), Organisation (ref), **Membership root (p,o)**, **Membership period**, **Act** | 5 |
| **Act kinds** | Admit · Renew · ChangeType · Suspend · Restore · End(kind) · Annul · Correct (+ Recognise iff REC-0 = yes) | 8 (9) |
| **Stored lifecycle** | per period: open / ended (+ pending iff REC-0 = yes) | 2 (3) |
| **Derived predicates** | Admitted, Ended, Effective, Lapsed, Suspended, Recognised, InForce, IsMember | 8 |
| **Invariants** | INV-1…INV-11 (+ INV-12 conditional) | 11 (12) |
| **Aggregates** | 1 (the root) + existing neighbours (Application, Fee) | 1 new |

- **Under-modelling avoided:** a status plus joined/left dates cannot answer re-entry, annulment, type history or authority (§14).
- **Over-modelling avoided:**
  - no episode aggregates;
  - no registry;
  - no per-ending-kind classes;
  - no generic policy framework;
  - no event sourcing (acts are append-only **records**; current conditions may be materialised);
  - no bitemporality unless D-TERM-4 = A1 or retroactive correction.

## 24. Decision-robust architecture

| Open decision | Architecture affected? | Variation point (if any) | Invariant across answers |
|---|---|---|---|
| **D-ID.1** | **structure: no** (D). **Naming: yes** | which level is called "Organisation Membership" (root vs period). Under A: an EM-VOC-001 terminology amendment, or choose A-architecture | ledger, partition-invariant IsMember, I5 locality (D), periods |
| **D-ID.2** | **minimal** | the number slot: root vs period | everything else |
| **D-TERM-1** | **no** | the list of End kinds (an enumeration value set) | End act structure, INV-2 |
| **D-TERM-3** (later) | no | whether Annul is allowed before effectiveAt | — |
| **D-TERM-4** (later) | **yes, temporal** | **record time** is needed iff A1 | the act model |
| **D-TERM-5** (later) | **yes, temporal** | record time iff retroactive correction | — |
| **D-SUSP** | **predicate only** | the definition of the counted IsMember; optional term/dues rules | Suspend/Restore acts; separate published facts |
| **REC-0** | **small** | the existence of a Recognise act / pending condition | Admit structure |
| **I5** | **no** (under D it is internal) | M1 vs M2 decides whether the guard exists | — |
| **N-U** | **persistence only** | the namespace of the number | — |

**Frozen-able now** (for the Principal Reviewer to accept as the architecture core):
- the act-history model with derived predicates;
- the (p,o)-rooted consistency boundary with identified periods (D);
- the act vocabulary (minus the kind lists);
- authority as a port;
- the separate published facts (`InForce`, `Suspended`, `typeAt`, standing later);
- the context map;
- INV-1…8, INV-11.

## 25. Remaining business decisions

**MUST:** D-ID.1, D-ID.2, D-TERM-1, D-SUSP, REC-0, I5 (unchanged from G).

**This synthesis narrows what each answer touches.** Most now affect only naming, an enumeration, a slot, or a predicate definition (§24).

**LATER:** D-TERM-3, D-TERM-4 (**the only one that can force record time**), D-TERM-5, REC-IMP, REC-INV, N-U.

## 26. Remaining architectural decisions (Principal Reviewer)

1. Accept or reject **Architecture D** as the candidate core (vs A, B).
2. **The EM-VOC-001 terminology path** if D-ID.1 = A: amend the wording, or fall back to Architecture A.
3. Accept **acts as append-only records with materialised current conditions** (not event sourcing).
4. Accept the **separate published facts** (InForce ≠ Suspended ≠ standing).
5. The **authority port** shape (deferred design; position only).
6. Accept the **ChangeType act** (type history) as core vs deferred.

## 27. Remaining implementation decisions

- the materialisation strategy for current conditions;
- the persistence of acts (table design);
- the uniqueness mechanism for I5 (only if A);
- the number generator and namespace (after N-U);
- the time types (`LocalDate` vs instant per fact; tenant time zone);
- the published-fact mechanism (query port vs events).

## 28. Migration implications (no migration performed)

- **Under D (or B):**
  - the legacy `members` row maps to the **root** (UNIQUE(`organisation_user_id`) keeps its meaning);
  - the **first period** is derived from `joined_at`, `membership_expires_at`, `status` and `ended_at`;
  - `expired` → an open period past its term (lapse, derived);
  - `ended` → an ended period (End kind unknown → `legacy`);
  - `suspended` → a period with an open Suspend.
- **Under A:** each legacy row becomes one membership. UNIQUE is replaced by an I5 guard.
- **In every case:**
  - replace the ON DELETE CASCADE (INV-7);
  - scope number uniqueness per organisation (if U1);
  - consolidate the two Member models and three vocabularies;
  - the six re-entry paths become one `Admit` (or re-open) command after D-ID.1;
  - legacy rows lack a decision or authority, so they get `basis = legacy`, `authorityRef = unknown` (do not fabricate).

## 29. Risks and falsification opportunities

| Risk / test | How it could falsify this theory |
|---|---|
| a real organisation has **concurrent memberships** of different types | breaks I5 (M2/M3). D still represents it (overlapping periods), but the guard changes |
| suspension **changes the term** | requires a term-extension act (supported); falsifies "suspension never touches Effective" for that tenant |
| statutes make a **resignation effective only after a board acceptance** | adds a decision step inside End (decided ≠ accepted ≠ effective). The act model absorbs it; the End structure grows |
| **retroactive admission** (a backdated join date) is common in imports | requires valid ≠ recorded time for Admit too, which widens the bitemporal need beyond A1 |
| very long histories per (p,o) | aggregate size. Unlikely (associations); falsifiable from data later |
| a **person merge** (duplicate accounts) | two roots for one person, which needs a merge act. Identity resolution is deferred (DR proportionality) |
| the enumeration bound (≤ 6 acts) | longer interactions could hide a counterexample. The properties are compositional, but this is **not proven for unbounded histories** |

## 30. Recommended next step

1. **Principal Reviewer review of §24's frozen-able core and §26's architecture decisions**, especially Architecture D and the EM-VOC-001 path.
2. **In parallel, the PO answers G §16 Q1–Q6.** With this synthesis, most answers only parameterise D (naming, slot, enumeration, predicate).
3. **Then E0b/E1: the canonical Membership domain model** on Architecture D: act catalogue, invariants as requirement cards, the published facts.
4. **Still deferred:** authority, Q-FM-MAP, standing (E0b), persistence.

---

### Appendix A: computational method

- Script `theory.py` (session scratchpad, `python3 -I theory.py 6`, 2026-10-08).
- **Acts:** {ADMIT, EXPIRE (the term passes), RENEW, SUSPEND, RESTORE, END, ANNUL_END}; all sequences of length 1–6 (137,256 ledgers).
- **Partitions:** A (a new block per admission), B (one block; admission after an end re-opens it), D (a root + a new period per admission).
- **Suspension models:** S1 (suspended ⇒ not counted as a member), S2 (counted).
- **Valid-time evaluation:** `ANNUL_END` voids the latest prior END (option A1).
- **P3** compares truth at earlier ticks before and after appending the last act.
- **Limits:** one (p,o); abstract ticks; no correction act; no recognition act; no type acts; bounded length.

**STOP.** Returned to the Principal Reviewer. No code, schema, migration, defect or commit.

---

**Correction (E0a-4, 2026-10-08; additive, text above unchanged):** see `2026-10-08-e0a4-principal-falsification-and-architecture-stress-test.md` §27. Falsified:
- (1) §1.4 / §6 "record time is needed iff A1": backdated Admit/End/Renew and imports also rewrite the past;
- (2) §1.2 / §24 "D-ID.1 changes only naming": admission date, tenure, number and reporting differ;
- (3) single-interval term: a late renewal erases lapse gaps, so **term segments** are needed;
- (4) Admit(basis): it must record the person's declaration too.

Amended:
- the ontology is facts with provenance (acts + attestations);
- I5 needs guard g2 (ending reversal after re-entry);
- authority is stored as an evidence snapshot;
- number uniqueness lies outside the aggregate;
- the migration fit is secondary evidence only.
