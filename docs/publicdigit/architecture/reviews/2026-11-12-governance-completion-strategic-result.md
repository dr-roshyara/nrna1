# Governance completion: strategic result (membership ending and decision records)

| | |
|---|---|
| **Status** | **Strategic result, for the Principal's approval before tactical design.** It records no decision of its own; the decisions are D-194, D-201, D-202. Written by Claude, not independent |
| **Commission** | Principal, 2026-11-12: "Governance strategic discovery and tactical completion" |
| **Sources** | decision record (D-3 of the Membership register; D-71; D-160; D-167; D-181; D-193; D-194; D-200; **D-201, D-202**); ratified Membership architecture (fact table, `End(kind)`, Annul / Void / Correct); kernel `EndKind`; Governance thin slice (accepted, D-157, with additions through D-196); governance tactical model §20.1, §33, §40–§41 (proposed); I-TDD-02 rev 12 (accepted, D-200) |

**Labels:** [AD] accepted decision · [RAT] ratified · [AC] architectural conclusion · [TD] tactical · [LQ] legal · [OQ] open for the Principal.

## 1. Business responsibilities and ownership

| Fact or answer | Owner | Source |
|---|---|---|
| that a membership ended, its kind (`resignation` · `exclusion` · `death`; `legacy` only for attested migration) and its effective date | **Membership** | [RAT]; Membership register D-3; kernel `EndKind` |
| whether the actor may bring about or record that ending | **Governance** (AuthorityCheck) | D-194, D-167 |
| who the actor is (`PersonRef` in this organisation) | **Identity** (R-1) | D-200 |
| what the ending means for belonging, forums and newsletters | **Organisation** via C1 / C2, and the consuming contexts | D-161, D-162, D-168 |
| decision records: what was decided, by whom, under which rules, with what evidence, known when | **Governance** | **D-202** |

## 2. Governance–Membership boundary (per kind of ending)

| Kind | Who acts (rules silent) | Governance answers | Timing |
|---|---|---|---|
| **resignation** | **the member** (self-service), or an **empowered recording office** entering the member's declaration (it records; it does not decide) | **Authorised iff the actor is the member concerned, or the actor holds a recording office** the rules or a designation empower. **The member's own right needs no designation** (D-194) | **D-201:** effective at once, or at a date the member states (≥ declaration); **withdrawable before that date**; rules on notice take precedence. **[LQ]** |
| **exclusion** | **only the organ the rules name** (decision part of a Resolution, pushed with the decision), or an office the rules empower (pulled at the act) | competence per part on the decision date (D-202 item 2). **Silent → `Undetermined`; nothing ends** (D-167, D-181) | the part's effective date. **A pending challenge changes nothing** (D-71) |
| **death** | an **empowered recording office**, from evidence | recording authority, with the basis "recorded fact" and evidence. **Silent → waits** | the date of death (from evidence) |
| `legacy` | — | — | **not in release 1** (D-160: new ledger organisations only) |

**[AC]:** D-194's "other kinds" has **no release-1 content**. The ratified closed list has no other business kind.

## 3. Governance–Identity relationship

- **Identity supplies the actor's `PersonRef`; Governance never resolves identity.**
- **The self-resignation check compares `PersonRef`s** (actor = subject). **It needs no membership knowledge in Governance:** Membership supplies the subject.

## 4. Contracts and the direction of authority

| # | Contract | Direction | Meaning | Failure |
|---|---|---|---|---|
| 1 | AuthorityCheck `end-membership(kind)` (one act kind per ending kind, or one act kind with a kind parameter: **[TD]**) | Governance → Membership | the answer (six fields + basis, D-202), pulled at the act, or pushed with a Resolution part (exclusion by an organ) | refused; **`Undetermined` ≠ No** |
| 2 | `End(kind)`, effective date | Membership → Organisation (C1 / C2) | coverage ends at the effective date; a future effective date ends it then | as C1 / C2 (refused, never partial) |
| 3 | withdrawal of a future-dated resignation | Membership (own fact) | **[TD] for M-TDD-01:** most likely **Annul** of that End (ratified: "annulling an End" may enlarge a lifetime). **To verify:** whether a member's declaration has "decision provenance" as Annul requires | — |
| 4 | Governance decision records (D-202) | Governance → all consumers | the basis every authority answer cites | — |

## 5. Settled and open

| Settled | Open |
|---|---|
| D-194 (who acts, per kind) | **[LQ]** legal effective time of a resignation; any statutory right to leave immediately |
| D-201 (resignation timing, withdrawal) | **[TD]** act-kind shape (one vs per kind); the representation of withdrawal (contract 3) |
| D-202 (decision-record requirement, X-3) | **[TD]** lifting governance model §20.1, §33, §40–§41 into an accepted Governance addition: the minimum D-202 needs, not the whole model |
| the closed list of ending kinds [RAT] | — |

**Principal decisions still needed for these subjects: none.**

## 6. Conflicts with the accepted baseline

**None found.**
- D-194's "other kinds" has no release-1 content (§2).
- D-201's future-dated ending relies on `effectiveAt`, which the ratified model carries on every fact, with no rule against a future date. **M-TDD-01 must confirm** that C1 / C2 handle a recorded future ending correctly. That is a design check, not a known conflict.

## 7. Implications for the next tactical designs

**A. Governance completion (smallest coherent addition):**
- the `end-membership` act kind(s) per §2;
- **an accepted decision-record and decision-log contract** carrying D-202's eight guarantees, **drawn from the existing proposed model, not redesigned**;
- the per-part evidence that the Identity and Platform conditions (X-3) need;
- invariants, failure behaviour, temporal rules, proof obligations.

**B. M-TDD-01 (Membership admission and ending):**
- admission with an explicit term (D-163);
- ending per §2;
- **future-dated resignations and their withdrawal (D-201)**;
- corrections under the ratified Annul / Void / Correct rules **without changing any ratified invariant**;
- consumption of the Governance answer, the actor from Identity, and the effect on C1 / C2.

## 8. Clarification (2026-11-12, Principal's review): decisions versus recorded facts

**Approved in principle by the Principal, subject to this clarification.**

| Category | What it is | Governed by |
|---|---|---|
| **A. Governance decisions** | an organ's Resolution and its parts (e.g. **an exclusion**) | **D-202**: all eight guarantees |
| **B. Non-decision facts** | **a member's own resignation** (the member's act); **a death recorded from evidence** (a recorded fact) | actor (Identity), recording authority where applicable, evidence, dates and provenance. **Not a Governance decision record** |

- **No Governance decision is manufactured for B.**
- This matches the existing model: governance model §41.4 gives such changes the basis kind **`RecordedFact`** ("death, resignation submitted"), distinct from **`Part`**.
- **Nothing in D-194, D-201, D-202 or the ratified Membership invariants changes.**
