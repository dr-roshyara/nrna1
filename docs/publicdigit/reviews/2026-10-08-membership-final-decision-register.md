# Membership Final Decision Register

**Date:** 2026-10-08 · **Supersedes:** `…-membership-decision-register-final.md` · **records no new decision**
**Reference:** `…-membership-architecture-ratified.md` (**MA**) · `…-membership-final-proof.md` (**FP**)

## ADOPTED

EM-VOC-001/002/003 · EM-ENT-001…007 · EM-EO-001…004 · EM-FM-001…006 (deferred) · EM-SEQ-001/002 · EM-GOV-001…003 · SM2 · ADR_20261004_1549 · ADR-T11 · Manifesto ≠ Constitution · delivery baseline freeze. **No conflict with MA** (FP §8).

## RATIFIED ARCHITECTURE (proposed; becomes ratified on the Principal Reviewer's act)

| ID | Item | Condition |
|---|---|---|
| RA-1 | Organisation Membership = the enduring (p,o) relationship entity; **B′** identity (MembershipId per (p,o), PeriodId per Admit); compatible with EM-VOC-001 | — |
| RA-2 | **D1** aggregate = all periods in the I5 scope | **minimal iff BD-I5 + BD-SYNC**. With no I5 → D2 |
| RA-3 | INV-9 with g1, g2, ER, **CFC** | scope = BD-I5 |
| RA-4 | Trim = a recorded consequence; live iff its cause is live; never directly targeted | only if BD-REV = trim |
| RA-5 | fact kinds: Admit, Renew, ChangeType (opt.), Suspend, Restore, End(kind), Annul, Void, Correct, Trim | Recognise / TermExtension only if BD-REC0 / a term pause require them |
| RA-6 | append-only facts; effectiveAt / decidedAt / recordedAt; a persisted seq (not a business timestamp); idempotent recording | — |
| RA-7 | provenance {decision, rule, attested}; authority evidence (refs + result) | — |
| RA-8 | two-valued predicates per the MA §3 DAG; no stored status | — |
| RA-9 | lifetime ≠ term ≠ standing ≠ eligibility | — |
| RA-10 | Membership owns `Suspended` only; other restrictions belong elsewhere | — |
| RA-11 | MembershipNumber = a credential; uniqueness outside the aggregate; no registry | — |
| RA-12 | published boundary: (MembershipId, asOf) references; events FactAnnulled / Voided / Corrected, PeriodTrimmed, TrimRevived, End, Suspend | — |
| RA-13 | no Event Sourcing requirement | — |
| RA-14 | no deletion or rewriting of ids or facts; an explicit merge act for person merges | — |
| RA-15 | imprecise legacy dates are normalised at import by a recorded decision; precision is kept as metadata | — |

## BUSINESS DECISION

| ID | Exact question | Why business | Recommended | Architecture impact | Implementation can proceed without it? |
|---|---|---|---|---|---|
| **BD-I5** | May a person hold overlapping memberships in the same organisation (e.g. honorary + ordinary as separate classes)? | the meaning of membership classes | **no (scope (p,o))** | **fixes INV-9's scope and D1's justification** | **no: closure blocker** |
| **BD-SYNC** | Must a double membership be impossible at every moment, or may it be reconciled afterwards? | an integrity policy | **impossible at every moment** | **D1 vs D2** | **no: closure blocker** |
| BD-1 | Is re-entry the same membership (with a new period)? | identity meaning | yes (B′) | naming only (an A-answer needs an EM-VOC-001 act) | yes (the structure is the same) |
| BD-2 | Does a returning member keep the number? | credential policy | yes | the number slot | until the number command |
| BD-3 | Which acts end a membership? | statutes | resignation, exclusion, organisation termination, death | the End-kind list | yes |
| BD-SUSP | Is a suspended member still a member (counted, paying, term running)? | statutes | yes | none (a TermExtension only if the term pauses) | yes |
| BD-REC0 | Is "member but not yet confirmed" possible? | process | no | an optional Recognise fact | until admission |
| BD-REV | After re-entry, an ending is reversed: refuse, or trim the later period? | appeal semantics | trim | whether Trim exists | until reversals |
| BD-TERM4 | Is a successfully appealed exclusion treated as never having happened? | appeal semantics | yes (Annul) | none | yes |
| BD-CONSENT | Can someone become a member without their own application or acceptance (except by statute)? | product + legal | no | the consent guard | until admission |
| BD-TEN | What does "member since" mean? | reporting / statutes | named predicates; display = current admission | none | yes |
| BD-NU | May two organisations issue the same number? | credential scope | yes | the constraint scope | until the number |
| BD-TYPE | Can the type change within a period? | product | yes | type segments | until ChangeType |
| BD-GVR | Should "grants voting rights" be dropped, or kept as a classification only? | product | classification | none | yes |
| BD-NORM (new) | Who may normalise imprecise legacy dates at import? | data authority | a registrar role | none (authority) | until migration |

## LEGAL QUESTION

LQ-1 consent; statutory / ex-officio / automatic membership · LQ-2 the effect of a successful appeal · LQ-3 termination grounds and procedures · LQ-4 retroactive statute changes.

## DOWNSTREAM DECISION

DS-1 Finance: fee policy for trimmed or revived periods · DS-2 Election: Q-FM-MAP, FM-5, FM-6, the reaction of registrations to annulled or voided facts · DS-3 Identity: person equivalence → the merge act · DS-4 Governance: the authority model and port · DS-5 E0b-S: standing.

## IMPLEMENTATION CHOICE

The fact table shape (Annul, Void and Correct may share it) · the seq assignment · the idempotency key · materialised projections · the (organisation, number) unique index and the allocator · optimistic version vs row lock · query port vs events · the tenant time zone · migration mechanics · the optional use of Event Sourcing.

## OPEN RISK

- concurrent legal membership classes in some statutes (BD-I5);
- person merges discovering overlaps (DS-3);
- legal validity of consent and appeal rules (LQ);
- fee handling for trims (DS-1);
- the limits of bounded / random computation (the key invariants have proofs).

---

## Amendment (final challenge round)

**RATIFIED ARCHITECTURE changes:**
- **RA-3** becomes INV-9 + **INV-SHAPE**, enforced by the single post-condition guard **G\***; g1 / ER are subsumed.
- **RA-4** becomes Trim = a **consequence recorded on its cause** (no own identity; not a fact kind); live iff its cause is live and not superseded by a Correct (CFC, derived from the meaning of Annul / Void).
- **RA-2:** D1 is valid in every policy branch; minimal iff I5 ∧ transactional ∧ trim.

**Architectural parameters** (the value changes behaviour, not structure): BD-I5 (scope) · BD-SYNC · BD-REV · BD-1 (naming) · BD-2 / BD-NU · BD-REC0 / BD-TYPE / BD-SUSP (optional facts) · BD-CONSENT (guard). **Business-only:** BD-3, BD-TERM4, BD-TEN, BD-GVR, BD-NORM. **Legal:** LQ-2 now explicitly includes "ex tunc vs ex nunc effect of annulment" (it determines CFC revival).

**BD-I5 and BD-SYNC are no longer blockers.** D1 is valid under all their values. They are business-policy parameters.

---

## Amendment (final closure round)

- **RA-4 becomes CFC+:** a consequence is live iff its cause is live, its cause is not superseded, and the cut lies inside the cause-target's base claim (non-circular).
- **INV-COV** is added.
- **INV-SHAPE** is renamed **INV-EP** (definitional: an episode is contiguous).
- **G\*** is reclassified as an **enforcement mechanism** (domain service); the invariants are the domain truth.
- **Correct requires a live target.**
- **The previous "RATIFIED" is withdrawn and re-established:** CX found (420 / 120,000) → fixed → 0.

### Requirements traceability (final)

| Requirement | Business meaning | Domain concept | Fact / predicate | Invariant | Aggregate responsibility | External | Test |
|---|---|---|---|---|---|---|---|
| identity | one relationship per person and organisation | Membership | MembershipId | INV-M | root identity | — | re-entry keeps MembershipId |
| admission | becoming a member | Admit | Admit + provenance | INV-1, INV-9 | G\* | consent (BD-CONSENT), authority (Gov) | backdated re-admit into a lifetime is refused |
| recognition | confirmed membership | Admit (REC-0 = no) | — | — | — | — | no pending state |
| membership period | a legal episode | Period | PeriodId, lifetime | INV-EP | period entity | — | a fragmenting fact is refused |
| re-entry | rejoining | Admit (a new period) | CurrentPeriod | INV-9 | G\* | — | a new PeriodId |
| renewal | an extended term | Renew | TermCovered | INV-12 | segments | — | late renewal keeps the gap |
| lapse | the term ran out | (derived) | Lapsed | INV-3 | — | — | lapse ≠ End |
| suspension / restoration | rights restricted | Suspend / Restore | Suspended | — | restrictions | Governance bans separate | IsMember unchanged |
| termination | the episode ends | End(kind) | Ended | INV-2 | — | Election FM-5 (deferred) | not in force from effectiveAt |
| correction | a fix to a wrong record | Correct | values | INV-COV | CFC+ | — | KnownAt is preserved |
| annulment | a decision set aside | Annul | liveness | INV-COV | CFC+ | legal LQ-2 | revival = recorded counterfactual |
| void | the record was never real | Void | liveness | INV-COV | CFC+ | — | excluded from statistics |
| membership number | the human credential | root attribute | — | uniqueness (EXTERNAL) | root | an org-level constraint | duplicate refused |
| membership type | the class of membership | type segments | CurrentType | INV-11 | segments | Election reads it | no entitlement |
| fees | dues | Fee (outside the root) | — | — | — | Finance; E0b-S | Fee(P) survives a trim |
| authority | a competent decision | provenance | authority evidence | — | stored evidence | Governance port | explainable without Governance's current state |
| consent | the person's will | provenance.declaration | — | — | consent guard | legal LQ-1 | a direct add without a declaration is refused |
| history / effective dates / retrospective correction | reconstructable truth | facts + clocks | TrueAt / KnownAt / DecidedBy | INV-6, INV-14 | fact log, seq | — | a pre-correction report is reproducible |
| migration | legacy records | attested facts | — | never invent | import command | BD-NORM | no fabricated authority or dates |
| standing | dues compliance | (E0b-S) | Standing | — | — | Finance evidence | — |
| Election integration | the voter population source | published facts | IsMember… | INV-11 | — | Election | Election reads no tables |
| Finance integration | accounting | events | — | — | — | Finance | trim / revival events |
| Governance integration | competence | evidence | — | — | — | Governance | — |
| Identity integration | person equivalence | — | — | — | reconciliation facts | Identity | — |

**Every requirement has a home.** Those without Membership ownership (eligibility, settlement, competence, equivalence) are named with their owner.

### Open questions (each classified; no architecture blocker)

- **Business:** BD-I5, BD-SYNC, BD-REV, BD-1, BD-2, BD-NU, BD-3, BD-SUSP, BD-REC0, BD-TYPE, BD-TERM4, BD-TEN, BD-GVR, BD-NORM.
- **Legal:** LQ-1…LQ-4.
- **Product:** BD-CONSENT (product rule + LQ-1).
- **Governance:** DS-4.
- **Downstream:** DS-1, DS-2, DS-3, DS-5.
- **Implementation:** storage shape, the seq mechanism, idempotency, the number allocator / index, projections, the time zone.
- **Deferred enhancement:** ML-assisted duplicate detection; section enrolments.

---

## Amendment (principal review round)

**RATIFIED ARCHITECTURE (proposed) changes**, all within resolution / enforcement; FP §14:
- **CFC+ becomes proportional:** LiveCut(c) = Cut(c) ∩ BaseClaim(Target(c)) while its cause is live, rooted in a live fact and not superseded. This replaces "the whole cut ⊆ base".
- **g2:** consequences only over Δ = NewBase − PrevBase − the target's own live cuts. A Correct inherits its superseded fact's consequences, clipped to the new base.
- **I** (the acceptance predicate) = INV-EP ∧ INV-9 ∧ **INV-COV** ∧ INV-1. INV-COV is now enforced by construction.
- **Concurrency:** commits are serialised per Membership (optimistic version, then re-run G\* on conflict).

**New business item (not a contradiction):**
- **BD-REV-ER:** may a correction absorb a whole other period (live Admit, empty lifetime)? (a) allow, or (b) refuse until that period's Admit is annulled or voided.
- **Recommendation: refuse.** Both options satisfy every invariant in all runs.

### Final classification of every remaining item

| Item | Class |
|---|---|
| Membership / Period / Fact ontology, D1, CFC+ (proportional), INV-M, INV-1, INV-EP, INV-COV, INV-2, INV-3, INV-12, INV-6 / INV-14, G\* layering, context contracts | **Adopted architecture** (proposed; adopted on the Principal Reviewer's act) |
| INV-11 (no entitlement) | **Adopted architecture** (already ADOPTED) |
| BD-I5, BD-SYNC, BD-REV, BD-REV-ER, BD-1, BD-2, BD-NU, BD-3, BD-SUSP, BD-REC0, BD-TYPE, BD-TERM4, BD-TEN, BD-GVR, BD-NORM | **Business policy** |
| LQ-1 (consent / statutory basis), LQ-2 (ex tunc / ex nunc), LQ-3 (termination), LQ-4 (retroactive statutes) | **Legal decision** |
| BD-CONSENT | **Product decision** |
| DS-4 (authority model + port) | **Governance decision** |
| DS-1 Finance, DS-2 Election, DS-3 Identity, DS-5 standing | **Downstream context decision** |
| storage shape, seq / version, idempotency, outbox, number allocator, `UNIQUE(org, person)`, projections, tenant time zone | **Implementation decision** |
| ML-assisted duplicate / migration-anomaly detection; section enrolments | **Future enhancement** |

There is no "architecture maybe" and no "needs more research" item.

### Requirements traceability (test column added)

| Requirement | Test (RED seed) |
|---|---|
| admission | re-entry: the same MembershipId, a new PeriodId; a backdated admission into a live lifetime is refused |
| recognition / consent / authority | an Admit without a declaration is refused (BD-CONSENT); provenance carries authority evidence |
| suspension | a suspended member: IsMember = true; BaseClaim unchanged |
| termination | Ended ⇒ ¬InForce |
| renewal / lapse | history M: a late renewal preserves the gap; lapse ≠ End |
| re-entry | history L: P2 survives a historical change of P1 |
| correction / annulment / void | histories A–K, N–R; recorded-counterfactual equality |
| membership number | a duplicate number is refused (a set-based constraint) |
| membership type | CurrentType from type segments; no entitlement |
| standing | E0b-S (Finance evidence) |
| election integration | architecture test: Election reads no Membership table |
| finance integration | PeriodTrimmed / TrimRevived events emitted from consequences |
| historical truth | KnownAt reproduces a pre-correction report |
| migration | dry-run report; no fabricated date / authority / consent |
| concurrency | two conflicting commands: exactly one commits, the other re-runs G\* |

**Every requirement has an owner.** Those not owned by Membership:
- eligibility → Election;
- settlement → Finance;
- competence → Governance;
- person equivalence → Identity.

---

## Amendment (closure re-run)

**RATIFIED ARCHITECTURE (proposed) change** (FP §15):
- **activeness is chain-wide:** a fact's consequences are live only if the fact, and every fact in its correction chain, is live;
- **a Correct may target only an active fact.**

**Why:** without this, a correction of an annulled correction keeps orphaned cuts (CX-15). Effects:
- the CFC principle holds again;
- the recorded-counterfactual theorem is PROVEN.

**No new business, legal or product item.** The classification table of the principal-review amendment stands unchanged.

**Traceability (correction / annulment / void):** add the test "Annul(c) resolves to the counterfactual without c and its dependants; G\* refuses it when that counterfactual violates I" (CX-15, history H).

---

## Amendment (final freeze audit)

**No architecture change.** Clarifications (FP §16):

| Item | Decision | Class |
|---|---|---|
| Annul / Void create consequences? | no; revive only (MA §2 corrected) | ARCHITECTURE (consistency fix) |
| Annul targets | facts with decision provenance, incl. Annul (appeal) | ARCHITECTURE (domain rule) |
| Annul(Void), Correct(Annul), Correct(Void) | refused | ARCHITECTURE (domain rule) |
| Annul(Annul), Void(Annul) | restore the original; G\* checks the revival | ARCHITECTURE (domain rule; LQ-2 for ex nunc) |
| Correct of an annulled / voided fact | **BD-REV** (folded in): recommended allow-inert for annulled, refuse for voided | BUSINESS (architecture-neutral; exhaustively verified both ways) |
| concurrency | optimistic version + recompute f⁺ + re-run G\* | ARCHITECTURE requirement / IMPLEMENTATION mechanism |
| recordedAt non-decreasing in seq per Membership | required for KnownAt | ARCHITECTURE (derived) |

**Architecture-shaping parameters** (changing them changes components, not the kernel; every branch is pre-analysed, MA §10):
- **BD-I5:** subtractive.
- **BD-REV:** subtractive.
- **BD-SYNC:** asynchronous is additive in the enforcement layer; the ratified branch is transactional.

**All other parameters are policy only.**
