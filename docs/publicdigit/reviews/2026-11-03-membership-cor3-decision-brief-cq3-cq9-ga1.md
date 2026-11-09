# Membership COR-3: decision brief for CQ-3, CQ-9 and G-A1

**Status:** **RECOMMENDATION, for the PO's decision.** Written on the PO's instruction "write your recommendation for my decision" (2026-11-03). It decides, approves and authorises nothing. CQ-3 and CQ-9 belong to the PO + Legal + the Governance owner; the PO has acted as the single owner for Governance before (GOV-END). G-A1 belongs to the ARB.
**Authority:** generated (Claude, DDD architect). **Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0).

## 1. Why these three, now

- **COR-3** is the last correction slice. It adds the application commands `AnnulFact`, `VoidFact` and `CorrectFact` through `DecideAndAppend`, the `MembershipAct` correction acts, authority evidence and event emission (correction contract §10, §14.5).
- COR-2 and COR-4 are accepted. When COR-3 is accepted, **ARB-MEM-01** (correction accepted before any cutover, migration or live write) is met.
- The roadmap makes COR-3 wait on **CQ-3 / G-AUTH-1** (authority) and **G-A1** (event publication); see the W4 row. **CQ-9** affects only the appeal path (`Annul(Annul)`).

## 2. What the records already settle (not reopened)

| Settled | Source |
|---|---|
| **The kernel records provenance; it does not judge competence.** Authority evidence on the fact is mandatory; a technical role is **not** proof of competence | contract §9 CQ-3 row; EP-01 review `:276` |
| BR-7 / D-4: Membership stores authority evidence; **Governance owns the rule**; an interim adapter introduces no policy of its own | GOV-END record §2 |
| **The precedent:** GOV-END B1 (interim), decided by the PO 2026-11-02. Recording acts are allowed for owner / admin with mandatory evidence; the **disciplinary act fails closed** until Governance can check the competent organ; `competenceVerified = false`; the rule names itself in `ruleRef` | `2026-11-02-membership-gov-end-decision-record.md` |
| Annul must carry decision provenance and `decidedAt`, and sets aside only a decision (R-2, R-7, P-2, V-2) | kernel, C-1 / COR-4 |
| D-COR-3: Correct changes **dates only** | D-COR-3 record |
| **The semantics of a successful appeal:** `Annul(Annul)` restores (R-3); D-COR-2 | contract §9 |
| **"Who decides collectively or by delegation" cannot be answered from the repository.** It is in the committee statutes request (§6.1 items 5, 11, 19), **not yet sent** | `docs/publicdigit/architecture/reviews/2026-11-03-publicdigit-po-arb-decision-action-package.md` §3.5.5 |

## 3. CQ-3: who may Annul, Void and Correct?

**The three acts differ in kind:**
- **Void** says the record never reflected reality: a data error.
- **Correct** (dates only) fixes a wrongly recorded date.
- **Annul** sets aside a *decision* (ex tunc), for example after an appeal.

Void and Correct are acts of the **record keeper**. Annul is an act of the **competent organ**.

| Option | Meaning | Assessment |
|---|---|---|
| **A: interim, in the GOV-END pattern (recommended)** | see below | unblocks the record-keeping acts now; keeps the decision act closed until the competent organ can be checked. Consistent with your own GOV-END decision |
| B: wait for the statutes | no COR-3 until Governance's AuthorityCheck exists | safest, but blocks ARB-MEM-01 indefinitely, while nobody can even fix a typo in a date |
| C: owner / admin may do all three | a platform default | **not recommended.** It lets a technical role set aside a decision of an organ (contrary to BR-7 and the EP-01 review) |

**Recommended wording (A):**

> "CQ-3, interim (until Governance's AuthorityCheck answers the statutes question, DS-4 / O-10):
> (1) **Void** and **Correct** may be recorded by an organisation **owner or admin**. Each carries an **evidence reference**: for Void, a reference to what shows the record was wrong; for Correct, a reference to the source document that states the true date. A Correct fixes a **recording** error only. A changed *decision* is a new decision (Annul + a new fact), not a Correct.
> (2) **Annul** is refused for every actor until Governance provides the competence check for the deciding organ.
> (3) Every check records AuthorityEvidence with `ruleRef` naming this decision and `competenceVerified = false`.
> This interim rule is replaced by the Governance-owned implementation. It is not a Membership policy."

**Consequences for COR-3:**
- `MembershipAct` gains `Annul`, `Void` and `Correct`, each with the evidence reference at the authority check, as Slice 2b does for End.
- The interim authority adapter allows Void and Correct for owner / admin, and denies Annul.
- The kernel needs no change.
- **The race tests' placeholder act is replaced** by the real acts (COR-4 residual risk).

## 4. CQ-9: who hears appeals; ex tunc or ex nunc?

**What is settled:** the semantics of a successful appeal, `Annul(Annul)` restores (R-3). **What is open:**
- the appeal **body**;
- ex tunc vs ex nunc (**LQ-2**, Legal);
- whether a successfully appealed exclusion "never happened" (BD-TERM4, recommended yes);
- the business half of D-COR-5 (an appeal after re-entry).

**Recommendation: keep CQ-9 open, and let the appeal path fail closed in COR-3.**
- Under CQ-3 option A, Annul is refused for every actor, so `Annul(Annul)` is refused too. COR-3 then needs no answer to CQ-9.
- Add the appeal body and LQ-2 to the **statutes request** and to Legal's queue, next to CQ-3.
- Change nothing in the kernel: the appeal semantics already work.

**What it costs:** until Governance names the competent organs, no decision can be set aside in the system. That is the same fail-closed stance as exclusion under GOV-END. Data errors remain fixable through Void and Correct.

## 5. G-A1: how are events published?

**Facts:**
- An accepted decision already **carries the event data**: `FactAnnulled` / `FactVoided`, `FactCorrected`, `PeriodTrimmed`, `TrimRevived` (C-1, COR-2).
- Nothing is emitted yet, and no Membership event classes exist (roadmap G-A1).
- **The shared outbox** (`app/Contexts/Shared/Infrastructure/Outbox`, table `outbox_events`) exists, but its repository is unfinished (`OutboxRepository.php:11, :17`: TODOs).
- A publisher of the shared outbox would run as a **scheduled / background job**. That is exactly what **PBDIGIT-211** breaks: scheduled jobs see no tenant data, and an ARB decision on the authoritative tenant context is still required.

| Option | Meaning | Assessment |
|---|---|---|
| **A: Membership's own transactional outbox (recommended, as the roadmap does)** | a Membership-owned table, written **in the same transaction as the fact** (the existing append, READ COMMITTED), with an explicit `organisation_id` column and **no** tenant global scope. **COR-3 records the events; delivery (a relay to consumers) is W6** | atomic (no fact without its event, no event without its fact); independent of PBDIGIT-211; Membership owns its contract. Cost: one more additive migration (it joins the release gate) and a relay later |
| B: the shared outbox, after PBDIGIT-211 is fixed | wait for the tenant-context ARB decision, then finish the shared outbox | one outbox for the platform, but it couples COR-3 to an open cross-context defect and an unscheduled ARB decision |
| C: no outbox; derive events from the fact log | a relay tails `org_membership_facts` | no second table, but facts are ordered per Member only; a global cursor needs a new global sequence. More novel, less proven |

**Recommended wording (A):**

> "G-A1: Membership publishes through its own transactional outbox. Event records are written in the same transaction as the fact they describe, with an explicit organisation id and no tenant global scope. COR-3 records events. Delivery to consumers is W6. Re-evaluate joining the shared outbox once PBDIGIT-211 is resolved."

**Anonymity:** membership events carry no vote data, so ADR-T11 is unaffected.

## 6. Summary of the recommendation

| Decision | Recommendation | Owner |
|---|---|---|
| **CQ-3** | **A, interim:** Void and Correct by owner / admin with an evidence reference; **Annul refused** until Governance can check the organ; `competenceVerified = false` | PO (as Governance owner) + Legal |
| **CQ-9** | **keep open; the appeal path fails closed** (it follows from CQ-3 A); add the appeal body and LQ-2 to the statutes request and Legal's queue | PO + Legal |
| **G-A1** | **A:** Membership's own transactional outbox; COR-3 records events; delivery is W6 | ARB |

**With these three decisions, COR-3 can be specified:** a story and a TDD plan for your approval, as with COR-2 and COR-4. **ARB-MEM-01 would then depend only on COR-3's delivery and acceptance.**

**What this does not unblock:**
- annulling decisions (setting decisions aside) and appeals: they wait for the statutes and Governance;
- event delivery to consumers (W6);
- any migration on a persistent database (the release gate).

## 7. Traceability

- correction contract §9 (CQ-3, CQ-9, D-COR-5), §10, §14.5;
- GOV-END decision record;
- roadmap §3.2 G-A1, §4.2, §5 (W1, W4, W6);
- PBDIGIT-211;
- decision action package 2026-11-03 §3.5.5;
- COR-2 / COR-4 acceptance records (event data; placeholder act).
