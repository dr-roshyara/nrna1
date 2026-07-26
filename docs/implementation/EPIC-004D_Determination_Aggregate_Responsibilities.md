# EPIC-004D Determination Aggregate Responsibilities

**Kind:** Tactical DDD artifact №3 (one artifact → ARB review → refine → freeze → next). **Role:** implementation architect operating from a frozen constitutional baseline — not a discoverer. This artifact defines **what the Determination aggregate is responsible for protecting**; it designs nothing (no methods, events, commands, VOs, repositories, services, APIs, schemas).
**Constitutional baseline (frozen — not reopened here):** Determination is the only accepted aggregate in this scope, existing implementation confirmed · Governance owns constitutional authority, delegation, lifecycle, and validity; Adjudication never models authority and depends only on Governance's published contract · the AdjudicationProceeding concern is a Process Manager / orchestration, not designed here · the four Constitutional Policies · K1/P2 · ADR-T1/T11/T14/T19.
**Governance rule applied as mandatory acceptance criterion (adopted this cycle, recorded in MEMORY):** *Responsibilities are discovered, not invented. Every accepted responsibility must trace to explicit business evidence or a previously frozen ARB decision; an untraceable responsibility is rejected or deferred.*
**Acceptance chain (canonical form per ARB, 2026-07-26 — every step must hold):** **Business evidence** *(renamed from "traceable" — traceability is the mechanism; evidence is the reason)* → business obligation → business decision → consistency required? → alternative owners eliminated (§F)? → removal breaks protected truth? → **protected domain truth identified** → **responsibility accepted** *(the artifact accepts a concrete responsibility because it has been shown to protect a specific domain truth — not an abstract proposition)*. *Aggregates protect domain truths — **properties (finality, uniqueness, attribution, authenticity, integrity), not objects**; the protection target is the positive form of the removal-test sentence — the removal test is its falsification probe. The finer distinction — Business Truth, implemented by Business Invariant(s) — is reserved for artifact №4, where it is load-bearing.*
**Methodological Fitness Rule (ARB, generalized — permanent governance):** *every acceptance criterion must demonstrate discriminative power; a criterion that never rejects or modifies a candidate over the lifetime of the methodology is presumed ceremonial until evidence shows otherwise.* (Status here: the ownership step rejected R-3; the validity half of R-5 was cut by the Q-1 constraint; the classification scheme pre-excluded the coordination concerns.)
**Classification scheme (per responsibility):** Decision (makes a business decision) · Consistency (maintains an invariant) · Lifecycle (owns state transitions) · Recording (records business facts) · **Coordination (near-automatic reject — coordination belongs outside the aggregate by prior ruling).**

---

## Responsibilities (A candidate · B evidence · C why this aggregate · D consistency obligation · E recommendation)

### R-1 — Lawful lifecycle of the ruling record

- **A:** guard the record's lifecycle: Draft → Issued → Final; refuse every illegal transition without mutation and without emission; emit nothing after Final.
- **B:** implemented and pinned by tests (`DeterminationTest`: cannot-issue-twice, cannot-finalize-a-draft, no-event-after-final); ADR-T19; Round 50-07 ("Final emits nothing").
- **C:** lifecycle legality of a record is the textbook obligation of the record's own guardian — no service or process manager can enforce it without the aggregate's cooperation, and splitting it out would leave the aggregate unable to protect its own state.
- **D:** **Yes** — an illegal transition must fail atomically with zero partial effect (proven behavior).
- **F (why nobody else):** *Governance* — owns authority, not record lifecycles. *Process Manager* — can request transitions but cannot prevent illegal ones without the aggregate's own guard; a PM-enforced lifecycle is advisory. *Application Service* — a service-level guard can be bypassed by any second caller; the guard must live with the state. *Publication* — downstream consumer. *Policy* — policies inform transitions; they cannot enforce atomic refusal.
- **Classification:** Lifecycle.
- **Removal test:** *"A binding ruling, once final, can never change"* becomes false — the constitutional finality of determinations would be unenforced.
- **Protects:** the lawfulness of the ruling record's history and the absoluteness of its finality.
- **E: ACCEPT.**

### R-2 — Exactly-once issuance *for this determination* (instance-level)

- **A:** this determination issues at most once; issuance is possible only from Draft.
- **B:** implemented (`issue()` guard; `DeterminationAlreadyIssued` at the boundary); ADR-T14.
- **C:** only the instance can know its own issued/not-issued state at the moment of transition.
- **D:** **Yes** — issue-exactly-once is the aggregate's core atomic fact.
- **F (why nobody else):** *Governance / Publication / Policy* — no visibility into this instance's transition instant. *Process Manager* — sees its own requests, not the aggregate's state under concurrency. *Application Service* — owns the SET-level rule (R-3), but the instance-level fact ("this one has already issued") is knowable only inside the boundary at commit time.
- **Classification:** Consistency.
- **Removal test:** *"A determination is issued exactly once"* becomes false — a re-issued determination would emit two ruling events for one record.
- **Protects:** each determination represents exactly one act of ruling.
- **E: ACCEPT** — *with the honest split recorded in R-3.*

### R-3 — One determination per challenge (set-level) — **the honest finding of this artifact**

- **A:** across all determinations, at most one exists per challenge.
- **B:** implemented — but **not in the aggregate**: the uniqueness check lives in the issuance service (`findByChallengeRef` → refuse) plus the DB unique index (`uniq_determination_per_challenge`); ADR-T14.
- **C:** **it cannot be owned by the aggregate, structurally.** A set-level invariant spans aggregate instances; a single instance cannot guard the set (a second instance cannot see the first inside its own boundary). The business rule is real and constitutional in weight, but its natural owner is the **issuance boundary** (application service + storage constraint), which the existing implementation already reflects.
- **D:** Yes — but the consistency is set-scoped, discharged transactionally at the boundary, not inside one instance.
- **Classification:** Consistency — but SET-scoped, which is precisely why it fails the ownership step.
- **Removal test (applied to the BUSINESS rule, not the aggregate):** *"At most one determination exists per challenge"* becomes false — the rule is real and constitutional; only its OWNER is not the aggregate.
- **E: REJECT as an aggregate responsibility — ACCEPT as an issuance-boundary responsibility** (recorded so artifact-family successors place it deliberately; the current implementation is already correct). *The ownership step is the decisive one: another component owns it more naturally — and already does.*

### R-4 — Fix the ruling's content and evidence basis once, at issuance (act-time record-fixing)

- **A:** at the instant of issue, fix immutably: the outcome, legitimacy, and reason (into the ruling-bearing event, not retained state — ADR-T19 Model B) together with the references the ruling rests on (challenge, evidence envelope, contested outcome, authority, jurisdiction).
- **B:** implemented (issue-time event carries all ruling content; aggregate retains lifecycle + refs only); Constitutional Policy alignment: this is Contemporaneous Record-Fixing's discipline (P3) applied to the ruling itself.
- **C:** fixation must be inseparable from the transition that makes the record "issued" — only the aggregate can guarantee the two are one fact.
- **D:** **Yes** — content-fixation and state transition commit as one (with the outbox row, per ADR-T1 — the transactional obligation the aggregate's transaction discharges).
- **F (why nobody else):** *Governance* — decides authority, not ruling records. *Process Manager* — assembles inputs; if fixation lived there, "issued" and "fixed" would be two separable moments — exactly what P3 forbids. *Application Service* — could write both, but could also write them apart; only the aggregate makes separation structurally impossible. *Publication* — consumes the fixed record; fixation upstream of it. *Policy* — informs content; cannot fix it.
- **Classification:** Recording + Consistency (one fixation fact).
- **Removal test:** *"The ruling as published is the ruling as decided at the moment of issue"* becomes false — post-hoc rationalization of rulings would be possible.
- **Protects:** the ruling the world sees is the ruling that was decided — never a reconstruction.
- **E: ACCEPT — in its current, implemented form.** **DEFER the expanded form** ("fix the evidence-set-as-considered," C1's surviving obligation from the Candidate-2 ruling): whether the ruling's evidence basis becomes a *set* of admissions supplied by the Process Manager, and whether its fixation seat is this aggregate's issuance or the orchestration's conclusion, **depends on the Process Manager design — explicitly out of scope here.** Traceable, real, and deliberately not decided by this artifact.

### R-5 — Refuse issuance absent the required assertions (presence, not validity)

- **A:** no issuance without a non-empty authority assertion, evidence reference, challenge reference, and the other required refs.
- **B:** implemented (every VO rejects empty/whitespace); Q-1 Resolution answer 5 ("refuse issuance absent an authority assertion — the non-empty check is the seed of that obligation").
- **C:** the refusal must be inseparable from issuance itself; a downstream check would allow an unattributed ruling to exist momentarily — which the record-fixing discipline forbids.
- **D:** Yes — refusal is atomic non-creation.
- **F (why nobody else):** *Governance* — owns whether the authority is VALID; it cannot own whether this record carries an assertion at all. *Process Manager* — can check before requesting, but a second caller bypasses it. *Application Service* — same bypass exposure; presence must be a condition of existence, not of one path. *Publication/Policy* — too late / advisory.
- **Classification:** Consistency (existence-condition).
- **Removal test:** *"Every ruling is attributable from the moment it exists"* becomes false — an unattributed ruling could exist, however briefly.
- **Protects:** every determination has an accountable issuer.
- **E: ACCEPT — bounded hard by the Q-1 constraint:** this is **presence/attribution only. Validity of the authority's delegation is Governance's, full stop** (binding constraint: Adjudication never implements or duplicates constitutional authority rules). Where the published-contract consultation happens is later tactical design — *not* an aggregate responsibility under criterion 4.

### R-6 — Attribution of the ruling (who issued, under what jurisdiction, on what evidence)

- **A:** every issued ruling permanently carries which authority issued it, the jurisdiction, and the evidence reference — the accountability triple.
- **B:** implemented (VOs + event payload); Q-1 answer 5 ("record which authority issued"); the strategic accountability requirement (every trust-bearing crossing attributable — who owed what to whom).
- **C:** attribution recorded anywhere but in the ruling's own fixed record would be separable from it — exactly what attribution must never be.
- **D:** Yes — part of R-4's single fixation fact.
- **F (why nobody else):** *Governance* — knows who HOLDS authority; only the ruling's own record can bind who EXERCISED it here, to this ruling, inseparably. *Process Manager* — a PM-side attribution log is a second record that can diverge from the ruling. *Application Service / Publication / Policy* — attribution anywhere but inside the fixed record is separable from it, which attribution must never be.
- **Classification:** Recording.
- **Removal test:** *"For any determination, who issued it, over what jurisdiction, on what evidence — is always answerable from the record itself"* becomes false — the accountability triple would depend on reconstruction.
- **Protects:** every determination is answerable — who, under what jurisdiction, on what evidence — from the record alone.
- **E: ACCEPT** (distinct from R-4 because the *business decision owned* differs: R-4 owns "the content is fixed"; R-6 owns "the ruling is attributable" — the ARB may merge them on review if the distinction carries no weight).

### R-7 — Anonymity guardianship over everything it records and emits

- **A:** nothing the aggregate records or emits may enable voter↔vote linkage.
- **B:** ADR-T11 (constitutional invariant); every VO's recorded no-voter-identifier discipline; AT-Q7 fitness scanning.
- **C:** the aggregate is where record content is fixed (R-4) — the last point where forbidden content could enter the permanent record; guardianship at fixation is guardianship that cannot be bypassed.
- **D:** Yes — inseparable from R-4's fixation.
- **F (why nobody else):** *Fitness tests* — detect after the fact; cannot refuse at fixation. *Process Manager / Application Service* — filtering upstream helps but is bypassable; the record's own guardian is the unbypassable seat. *Governance / Publication / Policy* — wrong subject matter or too late.
- **Classification:** Decision (admissibility of content into a constitutional record).
- **Removal test:** *"No constitutional record enables voter↔vote linkage"* becomes false at its most durable point — the permanent record itself.
- **Protects:** ballot secrecy at the one place it would be permanent if lost — the constitutional record.
- **E: ACCEPT** — noting honestly that this is a *constraint-shaped* responsibility (it owns the decision "this content is admissible in a constitutional record"), shared in enforcement with fitness tests; the aggregate's part is refusing forbidden content at fixation.

## Accepted responsibility list

**R-1** lawful lifecycle · **R-2** exactly-once issuance (instance) · **R-4** act-time fixation of ruling content + basis (current form) · **R-5** refusal absent required assertions (presence only) · **R-6** attribution · **R-7** anonymity guardianship at fixation.

## Deferred

- **R-4-expanded** — fixation of the evidence-set-as-considered (C1's surviving obligation): awaits the Process Manager design; its seat (aggregate issuance vs. orchestration conclusion) is that design's question. *(Traceable to the Candidate-2 ruling; deferred, not dropped.)*

## Explicit exclusions (rejected with grounds)

- **R-3 as aggregate responsibility** — set-level; owned by the issuance boundary (see above; already correctly implemented there).
- **Deciding the ruling's content** (outcome/legitimacy/sufficiency) — the authority decides (Q-1; K1/P2); the aggregate records. Constitutional, not stylistic.
- **Validating authority delegation** — Governance's sole ownership (Q-1 binding constraint).
- **Coordinating deliberation** — the Process Manager's concern (Candidate-2 ruling); not designed here.
- **Publication/supersession of corrections** — Policy 1 lands on the publication side; Determination's output is consumed there.
- **Tenancy enforcement** — infrastructure responsibility (mapper/adapter + `TenantContext`), per the implemented pattern; the aggregate stays tenant-free (ADR-T16 discipline).
- **Retention/preservation windows** — Q-2 territory; a records-management concern, not the ruling record's own.

## Self-review (post-strengthening)

No implementation leakage — no methods, events, commands, VOs, repositories, services designed (existing implementation is cited as *evidence*, never extended) ✅ · every accepted responsibility traces to implementation evidence, a frozen ARB decision, or a constitutional policy ✅ · **§F applied to every acceptance — each alternative owner (Governance / Process Manager / Application Service / Publication / Policy) eliminated explicitly, not waved at** ✅ · **every responsibility classified; none of the accepted six is Coordination** (the coordination-shaped concerns were excluded to the PM by prior ruling) ✅ · **the removal test applied to every acceptance — each names the business sentence that becomes false; R-3's removal test honestly shows the rule is real while its owner is not the aggregate** ✅ · every exclusion carries grounds ✅ · frozen decisions untouched ✅ · the strengthened acceptance chain (traceable → obligation → decision → consistency → ownership-with-alternatives-eliminated → removal test) held for all six acceptances ✅.

**Stop condition: STOP.** Responsibilities only. Invariants, events, commands, repositories, and services belong to later artifacts, each on explicit ARB opening after this artifact's review → refine → freeze.

---

## FROZEN (ARB, 2026-07-26)

**Artifact №3 is accepted and FROZEN** in canonical-chain form (epistemic loop closed: responsibility → protected domain truth → removal test → falsification). The six accepted responsibilities and their protected truths are the binding input to artifact №4 (Invariants), which the ARB has opened. The R-4/R-6 merge offer remains open for №4 to inform (their invariants may prove to be one atomic fact protecting two truths — №4's finding to make).

---
*Frozen baseline: `EPIC-004C` (complete) · `EPIC-004_Q1_Authority_Resolution.md` · the four Constitutional Policies (EPIC-003 §THE FOUR DECISIONS) · ADR-T1/T11/T14/T19 · Implementation evidence: EPIC-003 inventories + the qualified `app/Contexts/Adjudication` test suite.*
