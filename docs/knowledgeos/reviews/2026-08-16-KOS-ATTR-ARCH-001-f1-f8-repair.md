# KOS-ATTR-ARCH-001 — Governance Assurance
# Stage 1, F-1…F-8 repair pass — the accepted findings, applied

**Session 4 — `S4-architecture-attr-target` · ACTIVE, mutation owner · grants `G-KOS-ATTRARCH-DESIGN` + `AMD1` · 2026-08-16**
**Repairs:** the accepted F-1…F-8 findings of the independent architecture review (`a8d607a0` + erratum `3ff6b67a`), per the Human/ARB decisions recorded in the decision summary and the F-1 uniqueness sentence given in the Human's words. **Supersedes the specific sections cited below of rev 3 (`6c345e4d`) and the Stage-1 Domain and Responsibility Model (`8dab1be1`).** Every other section of both documents stands.

> ## STATUS — unchanged, except where a section below supersedes it
> | Level | Status |
> |---|---|
> | **Aggregate recommendation** | **PROPOSED** — restated per F-1 (R-1) |
> | **Bounded-context placement** (Stage 2) | **NOT YET CONFIRMED** — gated on `KOS-ARCH-BASELINE-001` acceptance |
> | **Target architecture** | **NOT APPROVED** |
>
> No C4. No technology. No schemas. No implementation. `INV-ATTR-4/5/6` remain **CANDIDATE / NOT ADOPTED**.
> **Self-review disclosure (P-2 Class-A — ownership review only).** Produced and reviewed by the same session; the substance of the repairs is the accepted independent review's own findings — this pass is not a fresh independent review and must not be represented as one.

---

## R-1 · F-1 (MAJOR) — the aggregate boundary, restated

**Supersedes:** rev 3 §1.1's boundary conclusion, §1.2's supersession-row reasoning, and §2's derivation note.

### R-1.1 The boundary

> **The minimal aggregate is `{ Assurance Claim · its Assessments }`, with establishment serialized per claim. "Current outcome" is a guaranteed *read over* the boundary — a derivation, never a member of it. Evidence References stay outside, referenced.**

### R-1.2 The justification — what the boundary is actually for

Rev 3 justified the boundary by "INV-ATTR-2 alone." That justification is **unsound as written**: INV-ATTR-2-as-read-rule (a derivation with an ELSE branch) is total by construction — no read can observe an undefined outcome, transaction or no transaction. The boundary's job is not storing an outcome; it is **serializing establishment per claim**, so that *"the currently-established Assessment"* in the derivation denotes **at most one** assessment. Without that, two establishments could succeed concurrently and the claim would hold two "current" outcomes — i.e. no classified outcome at all. The invariant doing the work is the uniqueness rule:

> **At most one established assessment per Assurance Claim at a time.**

This is the **mechanism-level restatement** of the outcome-definiteness the approved model **presupposes throughout in the singular** — INV-ATTR-2 *"it is treated as declared"* · INV-ATTR-3 *"**the** assurance outcome must remain visible"* · §2 *"**the** classified result"* · §5 *"**the** current outcome"*. It is **not a new business rule and not a new candidate invariant** — the presupposition is already in the approved model; this sentence names its mechanism.

**Recorded by ARB act — the Human's interpretive sentence (in the Human's words, from the decision summary):**

> *"I interpret the approved Assurance Model as requiring at most one established assessment per Assurance Claim at a time."*

### R-1.3 The supersession-row wording, corrected

Rev 3 §1.2 credited atomicity with preventing an "intermediate undefined state." Corrected: **no undefined state is reachable even without atomicity** — the derivation is total. What atomicity actually prevents is (1) the **dual-established ambiguity** (fatal — the case above) and (2) a **transient dip to weakest** between supersede and establish (harmless, and conservative in the right direction). The scenario table's conclusion stands; its stated reason is corrected.

### R-1.4 The derivation, completed

```
current outcome (claim) :=  outcome of the claim's currently-established Assessment   ← at most one (F-1 uniqueness rule; R-1.2)
                            ELSE the weakest outcome of the claim's dimension           ← INV-ATTR-2
```

The uniqueness rule is what makes the first branch definite; the ELSE branch is what makes the read total. Both, together, are what INV-ATTR-2/3 require.

---

## R-2 · F-2 (MAJOR) — the event vocabulary, dispositioned completely

**Supersedes:** rev 3 §3's "four domain facts" sentence, and completes the disposition the domain model §9's list omits. Rev 1 proposed **thirteen** events; rev 2 §7 dispositioned ten and miscounted. **All thirteen, dispositioned:**

| Event | Disposition | Why |
|---|---|---|
| `AssuranceClaimAsserted` | **DOMAIN FACT** | consumers need to know a claim now exists and is scoped |
| `AssuranceClaimSuperseded` | **DOMAIN FACT** (the fifth) | the approved model §4 gives the Claim a lifecycle ending in `superseded`; anyone relying on the prior claim must learn it no longer governs — the same reason `AssessmentSuperseded` is a domain fact. Also the mechanism F-3 needs |
| `AssessmentEstablished` | **DOMAIN FACT** | a governance judgment; the current outcome changes — consumers depend on it |
| `AssessmentSuperseded` | **DOMAIN FACT** | anyone relying on the prior outcome must learn it no longer stands |
| `EscalationTriggerRaised` | **DOMAIN FACT** | Governance must act; crosses the boundary by design |
| `EvidenceObserved` | **internal** | an artifact existing is not an assurance fact |
| `EvidenceAssociated` | **internal** | matters only within the claim's consistency boundary |
| `EvidenceEvaluated` | **internal** | an input to assessment, not a published conclusion |
| `EvidenceSuperseded` | **internal** | retirement matters inside the claim's boundary and cannot silently change an outcome; consumers learn through a superseding assessment |
| `AssessmentProposed` | **internal** | a proposal is not yet a conclusion |
| `AssessmentRejected` | **internal** | how establishment *fails*; a rejected proposal never changed any current outcome |
| `OutcomeClassified` | **removed — redundant** | it is what `AssessmentEstablished` *means*; two events for one fact |
| `OutcomeDisclosed` | **read-side projection** | publication is a consumer concern |

**Result: five domain facts · six internal · one removed as redundant · one read-side — thirteen events, none dropped.** (The reviewer's own projection initially said "seven internal"; erratum E-R1 corrected the count — six internal is right.)

---

## R-3 · F-3 (MODERATE) — the rule precised; disputes resolved inside the model

**Supersedes:** rev 3 §1.2's "one claim per (Governed Act, dimension)" line and Q-B2's wording.

**The rule, precised:**

> **One *current* claim per (Governed Act, dimension).** Superseded claims stand in history.

**Status-disputes are Assessments.** *"The performer is not established"* is an assertion about the claim's *epistemic status* — by rev 2 §2's own definitions, **an Assessment** (or the current-outcome derivation read aloud), never a competing claim. The two statements live at different levels and never contend for one claim slot.

**Content-disputes are Evidence + claim-supersession.** *"Lane Y performed T-123"* against *"Lane X performed T-123"*: the contradiction enters as **Evidence** (the same treatment Actor Statements already receive); Assessment weighs the contending evidence; the outcome stays weak while the contradiction stands. Where the governing content itself must change, the standing claim is **superseded by a corrected claim** — `AssuranceClaimSuperseded`, restored by R-2.

**Rejected alternatives, stated:** claims per `(act, dimension, claimant)` — **REJECTED** (yields multiple simultaneous current outcomes, destroying the definiteness INV-ATTR-2/3 presuppose and rev 3's structural guarantee of INV-ATTR-3) · Claimant on the Assessment — **REJECTED** (conflates who asserts with who judges — the P-2 distinction the model keeps apart). **Claimant stays on the Claim**, identifying who asserted the currently-governing content.

---

## R-4 · F-4 (MODERATE) — assessor standing, in one paragraph

**Adds to** the domain model §11 (P-2 classes). No new concept (`ES-005.4`).

> **Assessor standing needs no first-class treatment — it is already in the model.** *"This assessor has Class-B standing"* is itself an **Assurance Claim**: kind **Independence**, category **C4** — the approved model §5 explicitly includes *"Class-B review claims"* as C4 Independence claims. It is assessed like any other claim: absent an assessment it defaults to **Declared** under INV-ATTR-2, with C4's mandatory disclosure ladder applying at every lower outcome. The regress terminates the only way assurance regresses ever do: at a **disclosed Declared** or at a **human acceptance**. Candidate INV-ATTR-6, if adopted, protects the regress from shortcuts (standing assurance does not transfer to conclusion assurance). Later-found deficient standing is handled by the existing pattern: it **motivates a superseding assessment** — a deliberate act, never a retroactive rewrite.

---

## R-5 · F-5 (MINOR) — one architectural constraint, recorded

**Adds to** the domain model §5 (the enforcement of `INV-ATTR-1`) and rev 2 §9's dependency-direction row.

> **Architectural constraint — the input set of every workflow/authority gate is closed: `{transitions, grants}`. Extending it requires an explicit ARB act.**

This converts INV-ATTR-1's protection from a property of today's code into a property of the architecture, and closes both latent channels the review found:
- **the role co-location channel** — a Governance *practice* of refusing registrations on weak outcomes would be assurance-as-gate implemented in role behavior, invisible to the dependency structure; now named as a prohibited extension.
- **the storage co-location channel** — assurance state landing in the same document the engine folds would put a future precondition one refactor away; now named as a prohibited extension.

Stated plainly with the model's purpose: **the only gate assurance may influence is the human** (P-5's automatic/human line).

---

## R-6 · F-6 (MINOR) — who may establish an assessment (one rule)

**Adds to** rev 3 §3, which stated no human act is required but never named the owner.

> **An assessment is established by the Assessor that performed it — acting under its P-2 Class-A/B standing, inside the claim's consistency boundary — by recording `AssessmentEstablished`. No Human/ARB act is required, and establishment confers no authority, constitutes no approval, and changes no permission (INV-ATTR-1 · P-5).**

Establishment is the single most consequential write in the domain (the only act that changes a current outcome). Naming its performer and standing closes the review's finding.

---

## R-7 · F-7 (MINOR) — one ownership row: act → category classification

**Adds one row to** the domain model §4 ownership table.

| Thing | Owner | Explicitly not owned by |
|---|---|---|
| **Governed Act → Business Use Category (C1–C5) classification** | **Governance** — performed when the act enters assurance scope (its first claim is asserted), because the classification selects the Governance-owned requirement the Gap compares against | any mechanism · the assessed party (self-classification would rig the Gap derivation) |

The Gap derivation (rev 3 §4.1) consumes the act's category as its input; allocating *who* classifies and *when* closes the finding. Governance owns the category *definitions* already (P-5/P-6); this row allocates the per-act *application* of them.

---

## R-8 · F-8 (MINOR) — the rename, routed with Q-A1

**`AssessmentAccepted` → `AssessmentEstablished`** — already applied in rev 3 §3, and **now routed with Q-A1's approved vocabulary amendment** (Q-A1 **APPROVED** carrying F-8's rename, decision summary). **Supersedes the domain model §9's occurrence of `AssessmentAccepted`** to `AssessmentEstablished`. The disambiguating sentence (errata E-1) stands: *"Established" means established as the current Assurance-domain conclusion; it does not mean business acceptance, authorization, or approval.*

---

## Completion — the repair pass, delivered

**F-1…F-8: all repaired** (R-1…R-8 above), each on the accepted review's own disposition and the Human/ARB's recorded decisions.

**Q-B1/Q-B6 readiness — the conditions the recorded approvals were gated on are now met:**

| Gate | Recorded decision | Condition | State |
|---|---|---|---|
| **Q-B1** | APPROVED **after** the F-1 repair | boundary restated on the recorded interpretation, not the superseded text | **met** (R-1) |
| **Q-B6** | APPROVED **after** F-2's full disposition | expected result five domain facts, not four | **met** (R-2) |

The recorded approvals were conditional on these repairs; the repairs are done. **Landing the approvals — recording them as satisfied — is a Human/ARB act, not the Architecture lane's to perform.**

**Not opened by this repair:** Stage 2 — `S4-architecture-attr-stage2` remains **CREATED**; its START waits on `KOS-ARCH-BASELINE-001` Phase A acceptance (a separate work item's gate) **and** a Human START act · no C4 · no technology · no schemas · no implementation · `INV-ATTR-4/5/6` unadopted · nothing written back to the baseline.

---

**Traceability:** independent review `a8d607a0` + erratum `3ff6b67a` (findings §6, recommendations §7) · decision summary `2026-08-16-KOS-ATTR-ARCH-001-review-decision-summary.md` (Q-A1…Q-C1 decisions · F-1 sentence in the Human's words · hand-back) · rev 3 `6c345e4d` (§1, §2, §3 superseded per R-1/R-2/R-6/R-8) · rev 2 `7feec4ff` (its §7 table superseded by R-2; §9 enforcement completed by R-5) · rev 1 `8dab1be1` (domain model; §4 row added per R-7 · §9 event disposition completed per R-2/R-8 · §11 paragraph added per R-4) · Business Assurance Model rev 3 APPROVED (`5ab3b4e6`) §2–§5 · P-2 Class-A/B · P-5 · P-6 · `ES-005.4` · `R-34` · grants `G-KOS-ATTRARCH-DESIGN` + `AMD1` (F-1…F-6) + `AMD2` (placement) · placement per `docs/knowledgeos/reviews/README.md` (KnowledgeOS work products → `docs/knowledgeos/reviews/`).

---

> # REPAIR PASS — DELIVERED · F-1…F-8 repaired · Q-B1/Q-B6 conditions met · Stage 2 not opened
