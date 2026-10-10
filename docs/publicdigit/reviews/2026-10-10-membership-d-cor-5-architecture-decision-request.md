# Decision Request D-COR-5 (A): Annul / Void Create No Consequences (Membership kernel)

**To:** Principal Reviewer and Architecture Review Board (ARB)
**From:** `claude-code-session:42e0287e…` (self-declared, not attestable), on behalf of the PO's Membership correction work
**Date prepared:** 2026-10-10
**Context:**
- the correction slice C-1 (kernel Annul / Void) in the existing Membership bounded context;
- contract `docs/publicdigit/reviews/2026-10-09-membership-correction-slice-semantic-contract-draft.md` (§14.6, §15.5);
- gate register `docs/publicdigit/reviews/2026-10-10-membership-c1-gate-decision-record.md` (gate 2).

**Status: DECIDED (2026-10-10).** **D-COR-5 (A): CONFIRMED by the Human ARB (Dr. Nab Raj Roshyara), 2026-10-10** (decision supplied in session `42e0287e…`): "accept the no-implicit-consequences rule for Annul/Void". Confirmed semantics: (1) Annul / Void changes the liveness of the targeted fact; (2) it does not implicitly generate Membership-period consequences; (3) existing consequences may revive when their causes become live again; (4) the applicable invariants reject invalid resulting states; (5) Correct-generated consequences remain distinct; (6) the business / legal question of appeals after re-entry remains separate under CQ-9. The request text below is kept as the decision basis.

## Question

Does the ratified rule that **Annul and Void create no consequences** remain unchanged in the Membership kernel?

## Recommended option (not a decision)

**Confirm the existing rule:**
- Annul and Void change only the **liveness** of their target, and may thereby **revive superseded consequences**.
- They **do not** implicitly execute period trimming or create any other correction consequence.
- G\* evaluates the resulting state and **rejects** it if any invariant (INV-9, INV-EP, INV-COV) would be violated.

The PO's recorded position (2026-10-10) is the same. It is recorded as the PO's position, **not** as this decision.

## Ratified sources (verified in the repository on 2026-10-10)

| Source | Wording |
|---|---|
| `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratification-act.md` §3 | "Annulment and Void do not create new membership consequences. They alter the liveness of existing facts and therefore may revive consequences that already existed." |
| `docs/publicdigit/reviews/2026-10-08-membership-final-proof.md` §16.2 (fact algebra, authoritative) | Annul / Void, column "Creates consequences": "**no**; revives superseded consequences only" |
| `docs/publicdigit/reviews/2026-10-08-membership-final-decision-register.md` (freeze-audit amendment, line ~215) | "Annul / Void create consequences? \| no; revive only" |
| act §14 (ratified policy parameters) | BD-REV ratified choice: "**Trim / controlled correction consequence handling**". The *ratified* parameter ties trimming to **correction** consequences. The "after re-entry, an ending is reversed … trim" text is the register's original *question* wording (row BD-REV) |
| act §4 (causal closure) | where a reversal's result is invalid, "the operation is either accepted when the resulting state remains valid or refused until the dependent decisions are explicitly addressed" |

## Conflicting older wording

| Source | Wording | Conflict |
|---|---|---|
| decision register, row BD-REV (line ~41) | "After re-entry, an ending is reversed: refuse, or trim the later period?" → recommended **trim** | assumes an ending reversal trims a later period |
| decision register, row BD-TERM4 (line ~42) | a successfully appealed exclusion is treated as never having happened: "yes (Annul)" | assumes Annul realises the appeal even after re-entry |
| final proof §7, scenario H (line ~172) | "successful appeal: Annul(End{exclusion}) → enlarges P → g2 / ER" | treats Annul as running g2 (generating consequences) |
| final proof §16.2 harness note | the verification harness let End enlarge and Annul target any fact (a superset of the algebra) | explains why the earlier verification did not surface the conflict |

## Source comparison and conflict classification (evidence closure, 2026-10-10)

| # | Source (section / line) | Normative status | Stated Annul / Void semantics | Implicit period consequences permitted? | Consistency | Classification / required resolution |
|---|---|---|---|---|---|---|
| S1 | act §3 | **ratified decision** | "do not create new membership consequences … alter the liveness … may revive" | **no** | consistent with S2, S3, S4, S5 | anchor |
| S2 | act §4 (causal closure) | ratified decision | where a replay is impossible: "accepted when the resulting state remains valid or refused until the dependent decisions are explicitly addressed" | **no** (refuse, not trim) | consistent with S1 | anchor |
| S3 | act §14 | ratified decision | BD-REV = "Trim / controlled **correction** consequence handling" | trim belongs to **Correct** | consistent with S1 | anchor |
| S4 | FP §16.2 (authoritative algebra) | ratified (artefact named by the act) | Annul / Void: "no; revives superseded consequences only" | **no** | consistent with S1 | anchor |
| S5 | register :215 (freeze-audit amendment) | ratified (artefact named by the act) | "Annul / Void create consequences? no; revive only (MA §2 corrected)" | **no** | consistent with S1. It records the correction of an earlier model (MA §2) | anchor |
| C1 | register :41, row BD-REV | **question wording** of a business parameter, answered by S3 | "After re-entry, an ending is reversed: refuse, or trim the later period?" | implies a trim after reversal | conflicts with S1 / S4 **if** read as Annul-driven | **imprecise description, superseded by S3**: the ratified answer is correction-consequence handling |
| C2 | register :42, row BD-TERM4 | business parameter (recommendation "yes (Annul)"), **not** an architecture rule | an appealed exclusion "never happened" via Annul | silent on re-entry | conflicts only after re-entry (refused by G\* under S1) | **not a semantic contradiction**: it is a business intent whose execution after re-entry is a CQ-9 / LQ-2 question |
| C3 | FP §7, scenario H (:172) | **early scenario table** (mental execution), superseded in FP by §16.2 | "Annul(End{exclusion}) → enlarges P → g2 / ER" | **yes** (g2 on Annul) | contradicts S1 / S4 / S5 | **superseded statement**: S5 "MA §2 corrected" and FP §16.2 replace it. The §16.2 harness note explains why verification did not flag it |
| C4 | FP §16.2 harness note | ratified artefact, verification remark | the harness let End enlarge and Annul target any fact (a superset) | — | explains why C3 survived verification | none (informative) |
| E1 | `g6_correction_oracle.py` (H = 5) | **bounded model result** | implements S1 – S5 | no | Annul / Void(End p0) after re-entry → refused (INV-9) | evidence only; not proof, not approval |

**Summary:**
- There is **one genuine contradiction (C3)**, and the ratified corpus itself supersedes it (S4, S5).
- **C1 is imprecise question wording** answered by S3.
- **C2 is a business intent**, not an architecture rule.
- No ratified source supports implicit consequences for Annul / Void.

## Option evaluation

| Criterion | **Option A: preserve no-consequence rule** | **Option B: implicit consequences for Annul / Void** |
|---|---|---|
| ratified decisions | consistent with S1 – S5 | **amends** act §3, FP §16.2, register :215; reinterprets act §14 |
| append-only / bitemporal | unchanged; KnownAt preserved | still append-only, but Annul facts would need frozen consequences (g2 at commit) and `TrimRevived` semantics for Annul-of-Annul |
| non-overlap (INV-9) | enforced by G\*; an appeal after re-entry is refused until the re-entry is no longer live | satisfied by trimming. **But** a reversal reopening p0 to [s, ∞) contains p1 entirely, so p1 is **absorbed** (the BD-REV-ER case, recommended refuse). Option B therefore needs BD-REV-ER = allow, or partial semantics |
| re-entry / restoration | appeal restoration (R-3) holds with D-COR-2. After re-entry it requires an explicit prior act on the re-entry | an automatic outcome after re-entry, which **decides the CQ-9 business question technically** |
| determinism / idempotency / replay | unchanged (Res is a pure fold; IDEM-1 … 4) | deterministic only if consequences are frozen at commit; recomputation on conflict extends to Annul / Void (R-11) |
| auditability | every period change traces to an explicit act by a competent organ | period changes appear as side effects of an appeal; the re-entry's fate is implicit |
| implementation / migration | C-1 as specified (no g2 in C-1) | C-1 re-scoped to include g2, LiveCut and the BD-REV-ER guard; the contract's §14 / §15 rewritten; the oracle changed |
| proof / test obligations | existing (P-REST / P-APPEAL, INV-9, differential tests) | new: CFC+ for Annul-generated consequences; INV-EP / INV-COV in C-1; re-verify the FP §16.5 claims |

**Principal Architect recommendation (as supplied):** Option A.

**Strongest counterargument:** BD-TERM4's intent ("an appealed exclusion never happened") is not achievable in one act after re-entry under Option A. A competent organ must also annul or void the re-entry, which may feel procedurally heavy or legally wrong (the re-entry was a real, valid admission).

**Response:** that is exactly the business / legal question (CQ-9, LQ-2) Option A leaves open instead of deciding it technically. Option B would hard-code one answer.

**Residual risk:** if PO + Legal later require "continuation" semantics after re-entry, a dedicated, explicitly authored operation (not an implicit Annul side effect) would be needed. That is a future, separately decided slice.

## Observed effect of the existing rule (derived; bounded model evidence, not proof)

**Oracle:** `docs/publicdigit/membership/architecture/models/g6_correction_oracle.py` (in-memory; bounds H = 5).

| History | Result |
|---|---|
| Admit p0@0, End p0@2 (exclusion), Admit p1@3 (re-entry), **Annul(End p0)** | **refused (INV-9)**: the revived open period p0 would overlap p1 |
| the same with **Void(End p0)** | refused (INV-9) |
| Annul(Admit p1) first, then Annul(End p0) | accepted: p0 = [0, ∞) |
| Annul(End p0) **before** any re-entry | accepted (the ratified appeal semantics) |

## Consequences

**If confirmed:**
- C-1 implements Annul / Void without consequence generation.
- An appeal against an exclusion after a re-entry is admissible only once the re-entry fact is no longer live (it is annulled or voided by the competent organ).
- The older "trim" wording (BD-REV for reversals; FP §7 H) is recorded as **superseded by act §3 / FP §16.2** for Annul / Void. "Trim" remains the ratified consequence policy for **Correct**-generated consequences only.
- INV-9 is preserved by G\*. No new kernel behaviour is introduced.

**If the architecture is to change** (Annul / Void may generate consequences):
- this requires a **formally authorised amendment** of the ratification act §3 and FP §16.2;
- **C-1 must be re-scoped** before implementation (the kernel would need g2 for Annul / Void);
- a "trim" executed by reversing an exclusion End would extend p0 to [s, ∞), which contains the later period p1. **p1 would be absorbed entirely**, which is the BD-REV-ER case (recommended: refuse). So the amendment would also need a BD-REV-ER resolution;
- the contract's §14.6, K-G1, T8 – T10 and the C-1 Definition of Done must be revised.

## Not part of this request

The **business / legal question**: what a successful appeal after re-entry means, whether the re-entry is annulled or voided, ex tunc vs ex nunc (LQ-2), and BD-TERM4. **Owner: PO + Legal (CQ-9).** It remains OPEN and is not inferred from this architecture decision.

## Evidence required to close this gate

A recorded decision giving:
- the option chosen;
- the name and role of the decider(s) (Principal Reviewer; ARB);
- the date;
- any conditions.

Then:
1. the gate register row 2 is updated verbatim;
2. the contract §14.6 / §16 is updated;
3. the C-1 start condition 2 is satisfied.

## Part of the same handoff: D-COR-2 concurrence

- **Recorded:** the PO APPROVED D-COR-2 on 2026-10-10 (refuse Annul / Void of a non-live target with `TargetNotLive`; Annul(Annul) and Void(Annul) of a live annulment remain possible). The PO's record leaves "the Principal Reviewer's concurrence **if required by the governance process**" outstanding.
- **What the governance sources say (verified 2026-10-10):**
  - **D-COR-2 itself is not classified in the ratified corpus.**
  - **Analogues** are classified as **business policy**: FP §16.11 lists BD-REV-ER and "BD-REV correction target (§16.3)" as business policy that does not change the architecture.
  - The 2026-10-08 decision register gives business questions a "**PO decides**" column (`2026-10-08-membership-decision-register.md:52`).
  - **Against:** D-COR-2 also protects a *ratified architectural* statement. Under "accept", R-3's unconditional appeal restoration fails (counterexample `Admit a, Annul(a), Void(a), Annul(Annul(a))`). That gives it an architectural dimension.
  - The only sources naming "PR / PO" jointly are the **unratified** contract draft and the PO's own "if required".
- **Determination: INSUFFICIENT EVIDENCE.** No authoritative rule states whether PR concurrence is mandatory. **Requested:** the Principal Reviewer either (a) **concurs** with D-COR-2, or (b) records that D-COR-2 is a **PO business-policy decision needing no concurrence**. Either answer closes C-1 start condition 1.

## Status statements

- The correction contract is **CONDITIONALLY APPROVED by the PO**. It is **not formally approved** until the D-COR-5 (A) decision and the formal approval are recorded.
- **C-1 is BLOCKED. No implementation is authorised to start.** The PO's conditional authorisation becomes effective only when start conditions 1 – 3 are recorded.
- This request has **not** been sent to anyone by the assistant. The PO hands it over.

## Approval field

| Field | Value |
|---|---|
| Decision | **CONFIRMED** (Option A), by the Human ARB, 2026-10-10 |
| Option chosen | **Option A**: preserve the ratified no-consequence rule |
| Principal Reviewer (name, date) | **CONCURS WITH OPTION A**: Principal Architect and Reviewer (ChatGPT), position supplied by the human ARB, Dr. Nab Raj Roshyara, in session `42e0287e…` on **2026-10-10**. Wording: "Annul/Void changes target liveness without implicitly generating Membership-period consequences. Previously established consequences may revive when their causes become live again. The resulting state must satisfy the applicable invariants. Correct-generated consequences remain separate." **This is the Principal Reviewer's position, not the human ARB's decision** |
| ARB (name / reference, date) | **Human ARB, Dr. Nab Raj Roshyara: CONFIRMED, 2026-10-10** (supplied in session). No other ARB member's signature is recorded or claimed |
| Conditions | — |
| **D-COR-2: Principal Reviewer** | **CONCURS**: Principal Architect and Reviewer (ChatGPT), supplied by Dr. Nab Raj Roshyara in session on 2026-10-10. "This concurrence does not waive any additional approval required by the project's governance rules." |

---

## ARB review record: prepared for signature (2026-10-10; requested by the ARB Chief)

**Status: PREPARED, NOT DECIDED.** The ARB Chief's message (2026-10-10) requests an explicit decision; it does not contain one. The assistant cannot decide for the ARB or the Principal Reviewer and has not contacted anyone.

### D-COR-5 (A): proposed decision text

> **"Annul and Void change only the liveness of their target. They do not generate implicit Membership-period consequences (no trimming). Existing consequences may revive when their causes become live again. G\* evaluates the resulting state and rejects it if any invariant (INV-9, INV-EP, INV-COV) is violated. Older wording to the contrary (decision register row BD-REV question text; row BD-TERM4 'yes (Annul)' where applied after re-entry; final proof §7 scenario H 'Annul(End) → g2') is superseded for Annul / Void by act §3, act §14 and FP §16.2."**

| Element | Content |
|---|---|
| **Authoritative sources** | act §3 (no new consequences; liveness only; may revive); act §4 (refused "until the dependent decisions are explicitly addressed"); act §14 (BD-REV = "Trim / controlled **correction** consequence handling"); FP §16.2 (Annul / Void "no; revives superseded consequences only"); register freeze-audit amendment line 215 ("no; revive only") |
| **Conflicting sources** | register row BD-REV (question wording, :41); row BD-TERM4 (:42); FP §7 scenario H (:172); FP §16.2 harness note (the verification superset that hid the conflict) |
| **Rationale** | (1) the ratifying instrument (the act) and the authoritative algebra (FP §16.2) agree. (2) Act §14 frames trim as *correction* handling. (3) Implicit trimming by a reversal would absorb the later period entirely (the BD-REV-ER case). (4) No ratified rule is changed |
| **Invariant implications** | INV-9 is enforced by G\* (an appeal after re-entry is refused until the re-entry is no longer live). INV-EP / INV-COV are unaffected in C-1 (no consequences without Correct). R-3 appeal restoration holds (with D-COR-2 = refuse) |
| **Bounded evidence** | `g6_correction_oracle.py` (H = 5): Annul / Void(End p0) after re-entry → refused INV-9; after Annul(Admit p1) → accepted; before re-entry → accepted. **Bounded model evidence, not proof** |
| **Dissent / alternative reading** | the older corpus reading: an appeal reversal trims the later period (BD-REV question wording, FP §7 H). **Dissent by a named person: none recorded** |
| **Scope** | the Membership kernel (C-1 and later slices); Annul / Void semantics only |
| **Not decided here** | the business / legal meaning of an appeal after re-entry (PO + Legal; CQ-9, LQ-2, BD-TERM4); BD-REV-ER; D-COR-1 / 3 / 4; CQ-3 |
| **Decision** | ☑ **CONFIRMED** (Human ARB, 2026-10-10)  ☐ REJECTED  ☐ DEFERRED |
| **ARB Chief / ARB (name, date)** | **Dr. Nab Raj Roshyara (Human ARB), 2026-10-10: CONFIRMED** |
| **Principal Reviewer (name, date)** | **CONCURS WITH OPTION A** (ChatGPT, Principal Architect and Reviewer; supplied 2026-10-10; see the approval field above) |

### D-COR-2: governance determination

| Element | Content |
|---|---|
| **Question** | is Principal Reviewer concurrence required for the PO's D-COR-2 decision? |
| **Authoritative rule found** | **none.** The ratified corpus does not classify D-COR-2 |
| **Evidence for "PO business policy"** | FP §16.11 classifies the analogues (BD-REV-ER; BD-REV correction target §16.3) as business policy, no architecture change; the decision register's "PO decides" column (`2026-10-08-membership-decision-register.md:52`) |
| **Evidence for "architectural"** | D-COR-2 protects ratified R-3 (appeal restoration); the "accept" option breaks it (counterexample) |
| **Determination** | **INSUFFICIENT EVIDENCE.** To close condition 1, either the Principal Reviewer **concurs**, or the ARB Chief / Principal Reviewer **records that D-COR-2 is a PO business-policy decision needing no concurrence** |
| **Record** | ☑ **PR concurs** (ChatGPT, Principal Architect and Reviewer; supplied 2026-10-10) |
