# EPIC-004D Determination Aggregate Responsibilities

**Kind:** Tactical DDD artifact №3 (one artifact → ARB review → refine → freeze → next). **Role:** implementation architect operating from a frozen constitutional baseline — not a discoverer. This artifact defines **what the Determination aggregate is responsible for protecting**; it designs nothing (no methods, events, commands, VOs, repositories, services, APIs, schemas).
**Constitutional baseline (frozen — not reopened here):** Determination is the only accepted aggregate in this scope, existing implementation confirmed · Governance owns constitutional authority, delegation, lifecycle, and validity; Adjudication never models authority and depends only on Governance's published contract · the AdjudicationProceeding concern is a Process Manager / orchestration, not designed here · the four Constitutional Policies · K1/P2 · ADR-T1/T11/T14/T19.
**Governance rule applied as mandatory acceptance criterion (adopted this cycle, recorded in MEMORY):** *Responsibilities are discovered, not invented. Every accepted responsibility must trace to explicit business evidence or a previously frozen ARB decision; an untraceable responsibility is rejected or deferred.*
**Acceptance criteria (all five must hold):** (1) it is a business responsibility · (2) it owns a business decision · (3) the business expects consistency · (4) no other component can own it more naturally · (5) it follows from the frozen ARB decisions.

---

## Responsibilities (A candidate · B evidence · C why this aggregate · D consistency obligation · E recommendation)

### R-1 — Lawful lifecycle of the ruling record

- **A:** guard the record's lifecycle: Draft → Issued → Final; refuse every illegal transition without mutation and without emission; emit nothing after Final.
- **B:** implemented and pinned by tests (`DeterminationTest`: cannot-issue-twice, cannot-finalize-a-draft, no-event-after-final); ADR-T19; Round 50-07 ("Final emits nothing").
- **C:** lifecycle legality of a record is the textbook obligation of the record's own guardian — no service or process manager can enforce it without the aggregate's cooperation, and splitting it out would leave the aggregate unable to protect its own state.
- **D:** **Yes** — an illegal transition must fail atomically with zero partial effect (proven behavior).
- **E: ACCEPT.**

### R-2 — Exactly-once issuance *for this determination* (instance-level)

- **A:** this determination issues at most once; issuance is possible only from Draft.
- **B:** implemented (`issue()` guard; `DeterminationAlreadyIssued` at the boundary); ADR-T14.
- **C:** only the instance can know its own issued/not-issued state at the moment of transition.
- **D:** **Yes** — issue-exactly-once is the aggregate's core atomic fact.
- **E: ACCEPT** — *with the honest split recorded in R-3.*

### R-3 — One determination per challenge (set-level) — **the honest finding of this artifact**

- **A:** across all determinations, at most one exists per challenge.
- **B:** implemented — but **not in the aggregate**: the uniqueness check lives in the issuance service (`findByChallengeRef` → refuse) plus the DB unique index (`uniq_determination_per_challenge`); ADR-T14.
- **C:** **it cannot be owned by the aggregate, structurally.** A set-level invariant spans aggregate instances; a single instance cannot guard the set (a second instance cannot see the first inside its own boundary). The business rule is real and constitutional in weight, but its natural owner is the **issuance boundary** (application service + storage constraint), which the existing implementation already reflects.
- **D:** Yes — but the consistency is set-scoped, discharged transactionally at the boundary, not inside one instance.
- **E: REJECT as an aggregate responsibility — ACCEPT as an issuance-boundary responsibility** (recorded so artifact-family successors place it deliberately; the current implementation is already correct). *Criterion 4 is the decisive one: another component owns it more naturally — and already does.*

### R-4 — Fix the ruling's content and evidence basis once, at issuance (act-time record-fixing)

- **A:** at the instant of issue, fix immutably: the outcome, legitimacy, and reason (into the ruling-bearing event, not retained state — ADR-T19 Model B) together with the references the ruling rests on (challenge, evidence envelope, contested outcome, authority, jurisdiction).
- **B:** implemented (issue-time event carries all ruling content; aggregate retains lifecycle + refs only); Constitutional Policy alignment: this is Contemporaneous Record-Fixing's discipline (P3) applied to the ruling itself.
- **C:** fixation must be inseparable from the transition that makes the record "issued" — only the aggregate can guarantee the two are one fact.
- **D:** **Yes** — content-fixation and state transition commit as one (with the outbox row, per ADR-T1 — the transactional obligation the aggregate's transaction discharges).
- **E: ACCEPT — in its current, implemented form.** **DEFER the expanded form** ("fix the evidence-set-as-considered," C1's surviving obligation from the Candidate-2 ruling): whether the ruling's evidence basis becomes a *set* of admissions supplied by the Process Manager, and whether its fixation seat is this aggregate's issuance or the orchestration's conclusion, **depends on the Process Manager design — explicitly out of scope here.** Traceable, real, and deliberately not decided by this artifact.

### R-5 — Refuse issuance absent the required assertions (presence, not validity)

- **A:** no issuance without a non-empty authority assertion, evidence reference, challenge reference, and the other required refs.
- **B:** implemented (every VO rejects empty/whitespace); Q-1 Resolution answer 5 ("refuse issuance absent an authority assertion — the non-empty check is the seed of that obligation").
- **C:** the refusal must be inseparable from issuance itself; a downstream check would allow an unattributed ruling to exist momentarily — which the record-fixing discipline forbids.
- **D:** Yes — refusal is atomic non-creation.
- **E: ACCEPT — bounded hard by the Q-1 constraint:** this is **presence/attribution only. Validity of the authority's delegation is Governance's, full stop** (binding constraint: Adjudication never implements or duplicates constitutional authority rules). Where the published-contract consultation happens is later tactical design — *not* an aggregate responsibility under criterion 4.

### R-6 — Attribution of the ruling (who issued, under what jurisdiction, on what evidence)

- **A:** every issued ruling permanently carries which authority issued it, the jurisdiction, and the evidence reference — the accountability triple.
- **B:** implemented (VOs + event payload); Q-1 answer 5 ("record which authority issued"); the strategic accountability requirement (every trust-bearing crossing attributable — who owed what to whom).
- **C:** attribution recorded anywhere but in the ruling's own fixed record would be separable from it — exactly what attribution must never be.
- **D:** Yes — part of R-4's single fixation fact.
- **E: ACCEPT** (distinct from R-4 because the *business decision owned* differs: R-4 owns "the content is fixed"; R-6 owns "the ruling is attributable" — the ARB may merge them on review if the distinction carries no weight).

### R-7 — Anonymity guardianship over everything it records and emits

- **A:** nothing the aggregate records or emits may enable voter↔vote linkage.
- **B:** ADR-T11 (constitutional invariant); every VO's recorded no-voter-identifier discipline; AT-Q7 fitness scanning.
- **C:** the aggregate is where record content is fixed (R-4) — the last point where forbidden content could enter the permanent record; guardianship at fixation is guardianship that cannot be bypassed.
- **D:** Yes — inseparable from R-4's fixation.
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

## Self-review

No implementation leakage — no methods, events, commands, VOs, repositories, services designed (existing implementation is cited as *evidence*, never extended) ✅ · every accepted responsibility traces to implementation evidence, a frozen ARB decision, or a constitutional policy — the traceability rule held; nothing untraceable was proposed ✅ · every exclusion carries grounds ✅ · the aggregate boundary respected — one set-level rule honestly rejected *out* of the aggregate rather than absorbed for convenience ✅ · frozen decisions untouched (no new aggregates; authority untouched; Proceeding undesigned) ✅ · the five acceptance criteria applied to every candidate, with criterion 4 doing real work twice (R-3, R-5's validity half) ✅.

**Stop condition: STOP.** Responsibilities only. Invariants, events, commands, repositories, and services belong to later artifacts, each on explicit ARB opening after this artifact's review → refine → freeze.

---
*Frozen baseline: `EPIC-004C` (complete) · `EPIC-004_Q1_Authority_Resolution.md` · the four Constitutional Policies (EPIC-003 §THE FOUR DECISIONS) · ADR-T1/T11/T14/T19 · Implementation evidence: EPIC-003 inventories + the qualified `app/Contexts/Adjudication` test suite.*
